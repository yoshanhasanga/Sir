<?php

namespace App\Http\Controllers;

use App\Models\ApprovalRequest;
use App\Models\DayOff;
use App\Models\Lecturer;
use App\Models\ProgrammeSetting;
use App\Models\SecurityLog;
use App\Models\TimetableSlot;
use App\Models\TopicAssignment;
use App\Models\TopicProgress;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApprovalController extends Controller
{
    /**
     * Get pending and recent approval requests.
     */
    public function index(): JsonResponse
    {
        try {
            $requests = ApprovalRequest::with(['requester:id,name,email,role,designation', 'approver:id,name,email,role,designation'])
                ->orderBy('id', 'desc')
                ->limit(50)
                ->get();

            $pendingCount = ApprovalRequest::where('status', 'pending')->count();

            return response()->json([
                'success' => true,
                'requests' => $requests,
                'pending_count' => $pendingCount,
            ]);
        } catch (\Throwable $e) {
            Log::error('Approvals fetch error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to load authorization requests at this time.',
                'requests' => [],
                'pending_count' => 0,
            ], 500);
        }
    }

    /**
     * Submit a request for deletion or sensitive operation requiring MoE approval.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'action_type' => 'required|string|in:delete_lecturer,clear_week,clear_centre,edit_setting,delete_slot',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'payload' => 'nullable|array',
        ]);

        $user = Auth::user();

        // If the user is already a Ministry Approver with full approval authority, they can auto-approve
        $autoApprove = $user && $user->can_approve;

        try {
            $approval = ApprovalRequest::create([
                'requester_id' => $user ? $user->id : 1,
                'action_type' => $validated['action_type'],
                'title' => $validated['title'],
                'description' => $validated['description'] ?? 'Requested by ' . ($user ? $user->name : 'User'),
                'payload' => $validated['payload'] ?? [],
                'status' => $autoApprove ? 'approved' : 'pending',
                'approved_by' => $autoApprove ? $user->id : null,
                'decided_at' => $autoApprove ? now() : null,
                'decision_notes' => $autoApprove ? 'Directly authorized by Ministry Approver' : null,
            ]);

            if ($autoApprove) {
                $this->executeAction($approval);
                SecurityLog::logEvent(
                    $user->id,
                    'action_auto_approved',
                    $request->ip(),
                    $request->userAgent(),
                    ['action_type' => $approval->action_type, 'title' => $approval->title]
                );

                return response()->json([
                    'success' => true,
                    'executed' => true,
                    'message' => 'Action authorized and executed immediately by Ministry Approver.',
                    'approval' => $approval,
                ]);
            }

            SecurityLog::logEvent(
                $user ? $user->id : null,
                'approval_requested',
                $request->ip(),
                $request->userAgent(),
                ['action_type' => $approval->action_type, 'title' => $approval->title]
            );

            return response()->json([
                'success' => true,
                'executed' => false,
                'message' => 'Authorization request submitted! Waiting for Ministry of Education approval.',
                'approval' => $approval,
            ]);
        } catch (QueryException $e) {
            Log::error('Approval store DB error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'A secure database error occurred while creating the approval request. No data was modified.',
            ], 500);
        } catch (\Throwable $e) {
            Log::error('Approval store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again.',
            ], 500);
        }
    }

    /**
     * Decide (Approve or Reject) an approval request (Only designated MoE Approvers).
     */
    public function decide(Request $request, $id): JsonResponse
    {
        $user = Auth::user();

        // Enforce strict Ministry of Education Authorizer permission
        if (!$user || !$user->can_approve) {
            return response()->json([
                'success' => false,
                'message' => 'Access Denied: Only designated Ministry of Education Approvers have permission to authorize deletions.',
            ], 403);
        }

        $validated = $request->validate([
            'decision' => 'required|in:approved,rejected',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $approval = ApprovalRequest::findOrFail($id);

            if ($approval->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This request has already been ' . $approval->status,
                ], 400);
            }

            $approval->status = $validated['decision'];
            $approval->approved_by = $user->id;
            $approval->decision_notes = $validated['notes'] ?? '';
            $approval->decided_at = now();
            $approval->save();

            if ($approval->status === 'approved') {
                $this->executeAction($approval);
            }

            SecurityLog::logEvent(
                $user->id,
                'approval_decision_' . $approval->status,
                $request->ip(),
                $request->userAgent(),
                [
                    'approval_id' => $approval->id,
                    'action_type' => $approval->action_type,
                    'decision' => $approval->status,
                    'requester_id' => $approval->requester_id,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => $approval->status === 'approved' ? 'Authorization granted and action executed successfully in database.' : 'Request was rejected.',
                'approval' => $approval,
            ]);
        } catch (QueryException $e) {
            Log::error('Approval decision DB error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'A secure database error occurred while executing the approval. Details have been logged.',
            ], 500);
        } catch (\Throwable $e) {
            Log::error('Approval decision error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the decision.',
            ], 500);
        }
    }

    /**
     * Execute the approved payload in MySQL database.
     */
    protected function executeAction(ApprovalRequest $approval): void
    {
        $payload = $approval->payload ?? [];

        DB::transaction(function () use ($approval, $payload) {
            switch ($approval->action_type) {
                case 'delete_lecturer':
                    if (!empty($payload['lecturer_id'])) {
                        $id = $payload['lecturer_id'];
                        TimetableSlot::where('lecturer_id', $id)->update(['lecturer_id' => null]);
                        TopicAssignment::where('lecturer_id', $id)->delete();
                        Lecturer::where('id', $id)->delete();
                    }
                    break;

                case 'clear_week':
                    if (isset($payload['centre_id']) && isset($payload['week'])) {
                        TimetableSlot::where('centre_id', $payload['centre_id'])
                            ->where('week', $payload['week'])
                            ->delete();
                    }
                    break;

                case 'clear_centre':
                    if (isset($payload['centre_id'])) {
                        TimetableSlot::where('centre_id', $payload['centre_id'])->delete();
                    }
                    break;

                case 'edit_setting':
                    if (!empty($payload['settings'])) {
                        $setting = ProgrammeSetting::first();
                        if ($setting) {
                            $setting->update($payload['settings']);
                        }
                    }
                    break;
            }
        });
    }
}
