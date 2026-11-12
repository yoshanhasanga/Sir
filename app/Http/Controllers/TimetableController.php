<?php

namespace App\Http\Controllers;

use App\Models\ApprovalRequest;
use App\Models\DayOff;
use App\Models\Lecturer;
use App\Models\ProgrammeSetting;
use App\Models\SecurityLog;
use App\Models\TimetableSlot;
use App\Models\Topic;
use App\Models\TopicAssignment;
use App\Models\TopicProgress;
use App\Models\TrainingCentre;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class TimetableController extends Controller
{
    /**
     * Get the consolidated application state.
     */
    public function getState(): array
    {
        $setting = ProgrammeSetting::first() ?? ProgrammeSetting::create([
            'start_date' => '2026-11-02',
            'weeks' => 20,
            'include_saturday' => false,
        ]);

        $centres = TrainingCentre::orderBy('sort_order')->get()->map(function ($c) {
            return [
                'id' => $c->id,
                'code' => $c->code,
                'name' => $c->name,
                'manager_name' => $c->manager_name ?? '',
                'contact' => $c->contact ?? '',
                'sort_order' => $c->sort_order,
            ];
        });

        $topics = Topic::orderBy('topic_number')->get()->map(function ($t) {
            return [
                'id' => $t->id,
                'topic_number' => $t->topic_number,
                'title' => $t->title,
                'required_hours' => (float) $t->required_hours,
            ];
        });

        $lecturers = Lecturer::orderBy('id')->get()->map(function ($l) {
            return [
                'id' => $l->id,
                'name' => $l->name,
            ];
        });

        $assignments = TopicAssignment::all()->map(function ($a) {
            return [
                'centre_id' => $a->centre_id,
                'topic_id' => $a->topic_id,
                'lecturer_id' => $a->lecturer_id,
            ];
        });

        $slots = TimetableSlot::all()->map(function ($s) {
            return [
                'centre_id' => $s->centre_id,
                'week' => $s->week,
                'day' => $s->day,
                'slot_index' => $s->slot_index,
                'topic_id' => $s->topic_id,
                'is_other_topic' => (bool) $s->is_other_topic,
                'lecturer_id' => $s->lecturer_id,
                'unit_note' => $s->unit_note ?? '',
                'is_held' => (bool) $s->is_held,
            ];
        });

        $daysOff = DayOff::where('is_off', true)->get()->map(function ($d) {
            return [
                'week' => $d->week,
                'day' => $d->day,
            ];
        });

        $progress = TopicProgress::all()->map(function ($p) {
            return [
                'centre_id' => $p->centre_id,
                'topic_id' => $p->topic_id,
                'manual_hours' => (float) $p->manual_hours,
                'remarks' => $p->remarks ?? '',
            ];
        });

        $user = Auth::user();
        $currentUser = $user ? [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'designation' => $user->designation,
            'can_view' => (bool) $user->can_view,
            'can_edit' => (bool) $user->can_edit,
            'can_delete' => (bool) $user->can_delete,
            'can_approve' => (bool) $user->can_approve,
            'is_read_only' => $user->isReadOnly(),
        ] : null;

        $pendingApprovalsCount = ApprovalRequest::where('status', 'pending')->count();

        return [
            'settings' => [
                'start_date' => $setting->start_date ? $setting->start_date->format('Y-m-d') : '2026-11-02',
                'weeks' => $setting->weeks,
                'include_saturday' => (bool) $setting->include_saturday,
            ],
            'centres' => $centres,
            'topics' => $topics,
            'lecturers' => $lecturers,
            'assignments' => $assignments,
            'slots' => $slots,
            'days_off' => $daysOff,
            'progress' => $progress,
            'user' => $currentUser,
            'pending_approvals_count' => $pendingApprovalsCount,
        ];
    }

    /**
     * Show main timetable dashboard.
     */
    public function index(): View|RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $initialState = $this->getState();
        $currentUser = Auth::user();

        return view('timetable', compact('initialState', 'currentUser'));
    }

    /**
     * Helper to enforce edit permission.
     */
    protected function checkEditPermission(): ?JsonResponse
    {
        $user = Auth::user();
        if (!$user || (!$user->can_edit && !$user->can_approve)) {
            return response()->json([
                'success' => false,
                'message' => 'Access Denied: Your account role has View-Only permission.',
            ], 403);
        }
        return null;
    }

    /**
     * Helper to enforce delete permission with MoE Approval.
     */
    protected function checkDeletePermission(): ?JsonResponse
    {
        $user = Auth::user();
        if (!$user || !$user->can_delete) {
            return response()->json([
                'success' => false,
                'message' => 'Access Denied: You do not have permission to delete data.',
            ], 403);
        }

        if (!$user->can_approve) {
            return response()->json([
                'success' => false,
                'requires_approval' => true,
                'message' => 'Ministry of Education authorization is required for deletions.',
            ], 403);
        }

        return null;
    }

    /**
     * API: Get state as JSON.
     */
    public function apiState(): JsonResponse
    {
        return response()->json($this->getState());
    }

    /**
     * API: Update Programme Settings.
     */
    public function updateSettings(Request $request): JsonResponse
    {
        if ($err = $this->checkEditPermission()) return $err;

        $validated = $request->validate([
            'start_date' => 'required|date',
            'weeks' => 'required|integer|min:1|max:52',
            'include_saturday' => 'required|boolean',
        ]);

        try {
            $setting = ProgrammeSetting::first() ?? new ProgrammeSetting();
            $setting->start_date = $validated['start_date'];
            $setting->weeks = $validated['weeks'];
            $setting->include_saturday = $validated['include_saturday'];
            $setting->save();

            SecurityLog::logEvent(Auth::id(), 'settings_updated', $request->ip(), $request->userAgent(), $validated);

            return response()->json(['success' => true, 'settings' => $setting]);
        } catch (QueryException $e) {
            Log::error('Settings update DB error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'දත්ත සුරැකීමේදී දෝෂයක් මතු විය. ඔබගේ දත්ත ආරක්ෂිතයි.'], 500);
        }
    }

    /**
     * API: Update Training Centre details.
     */
    public function updateCentre(Request $request, $id): JsonResponse
    {
        if ($err = $this->checkEditPermission()) return $err;

        try {
            $centre = TrainingCentre::findOrFail($id);
            $centre->update($request->only(['name', 'manager_name', 'contact']));

            return response()->json(['success' => true, 'centre' => $centre]);
        } catch (QueryException $e) {
            Log::error('Centre update DB error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Centre තොරතුරු සුරැකීමේ දෝෂයකි.'], 500);
        }
    }

    /**
     * API: Add Lecturer(s).
     */
    public function addLecturers(Request $request): JsonResponse
    {
        if ($err = $this->checkEditPermission()) return $err;

        $request->validate([
            'names' => 'required|array',
            'names.*' => 'string|min:1',
        ]);

        try {
            $created = [];
            foreach ($request->names as $name) {
                $name = trim($name);
                if ($name !== '') {
                    $created[] = Lecturer::create(['name' => $name]);
                }
            }

            SecurityLog::logEvent(Auth::id(), 'lecturers_added', $request->ip(), $request->userAgent(), ['count' => count($created)]);

            return response()->json(['success' => true, 'lecturers' => $created]);
        } catch (QueryException $e) {
            Log::error('Add lecturers DB error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'දේශකවරුන් ඇතුළත් කිරීමේදී දෝෂයක් මතු විය.'], 500);
        }
    }

    /**
     * API: Update Lecturer.
     */
    public function updateLecturer(Request $request, $id): JsonResponse
    {
        if ($err = $this->checkEditPermission()) return $err;

        try {
            $lecturer = Lecturer::findOrFail($id);
            $lecturer->update($request->only('name'));

            return response()->json(['success' => true, 'lecturer' => $lecturer]);
        } catch (QueryException $e) {
            Log::error('Update lecturer DB error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'දේශක නම යාවත්කාලීන කිරීමේ දෝෂයකි.'], 500);
        }
    }

    /**
     * API: Delete Lecturer (Requires MoE Approval if not authorizer).
     */
    public function deleteLecturer(Request $request, $id): JsonResponse
    {
        if ($err = $this->checkDeletePermission()) return $err;

        try {
            $lecturer = Lecturer::findOrFail($id);
            $name = $lecturer->name;

            DB::transaction(function () use ($id, $lecturer) {
                TimetableSlot::where('lecturer_id', $id)->update(['lecturer_id' => null]);
                TopicAssignment::where('lecturer_id', $id)->delete();
                $lecturer->delete();
            });

            SecurityLog::logEvent(Auth::id(), 'lecturer_deleted', $request->ip(), $request->userAgent(), ['id' => $id, 'name' => $name]);

            return response()->json(['success' => true]);
        } catch (QueryException $e) {
            Log::error('Delete lecturer DB error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'දේශකයා ඉවත් කිරීමේදී දෝෂයක් මතු විය.'], 500);
        }
    }

    /**
     * API: Assign or Unassign Lecturer.
     */
    public function toggleAssignment(Request $request): JsonResponse
    {
        if ($err = $this->checkEditPermission()) return $err;

        $validated = $request->validate([
            'centre_id' => 'required|exists:training_centres,id',
            'topic_id' => 'required|exists:topics,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'action' => 'required|in:add,remove',
        ]);

        try {
            if ($validated['action'] === 'add') {
                TopicAssignment::firstOrCreate([
                    'centre_id' => $validated['centre_id'],
                    'topic_id' => $validated['topic_id'],
                    'lecturer_id' => $validated['lecturer_id'],
                ]);
            } else {
                TopicAssignment::where([
                    'centre_id' => $validated['centre_id'],
                    'topic_id' => $validated['topic_id'],
                    'lecturer_id' => $validated['lecturer_id'],
                ])->delete();
            }

            return response()->json(['success' => true]);
        } catch (QueryException $e) {
            Log::error('Toggle assignment DB error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'විෂය සඳහා දේශකයා අනුයුක්ත කිරීමේ දෝෂයකි.'], 500);
        }
    }

    /**
     * API: Copy assignments of a source centre to all other centres.
     */
    public function copyAssignments(Request $request): JsonResponse
    {
        if ($err = $this->checkEditPermission()) return $err;

        $sourceCentreId = $request->validate(['source_centre_id' => 'required|exists:training_centres,id'])['source_centre_id'];
        
        try {
            $sourceAssignments = TopicAssignment::where('centre_id', $sourceCentreId)->get();

            DB::transaction(function () use ($sourceCentreId, $sourceAssignments) {
                $otherCentres = TrainingCentre::where('id', '!=', $sourceCentreId)->get();
                foreach ($otherCentres as $centre) {
                    TopicAssignment::where('centre_id', $centre->id)->delete();
                    foreach ($sourceAssignments as $sa) {
                        TopicAssignment::create([
                            'centre_id' => $centre->id,
                            'topic_id' => $sa->topic_id,
                            'lecturer_id' => $sa->lecturer_id,
                        ]);
                    }
                }
            });

            return response()->json(['success' => true]);
        } catch (QueryException $e) {
            Log::error('Copy assignments DB error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'අනුයුක්තයන් පිටපත් කිරීමේ දෝෂයකි.'], 500);
        }
    }

    /**
     * API: Update a single timetable slot.
     */
    public function updateSlot(Request $request): JsonResponse
    {
        if ($err = $this->checkEditPermission()) return $err;

        $validated = $request->validate([
            'centre_id' => 'required|exists:training_centres,id',
            'week' => 'required|integer|min:0',
            'day' => 'required|integer|min:0|max:6',
            'slot_index' => 'required|integer|min:0|max:3',
            'topic_id' => 'nullable',
            'is_other_topic' => 'boolean',
            'lecturer_id' => 'nullable',
            'unit_note' => 'nullable|string',
            'is_held' => 'boolean',
        ]);

        try {
            $slot = TimetableSlot::updateOrCreate(
                [
                    'centre_id' => $validated['centre_id'],
                    'week' => $validated['week'],
                    'day' => $validated['day'],
                    'slot_index' => $validated['slot_index'],
                ],
                [
                    'topic_id' => $validated['topic_id'] ? $validated['topic_id'] : null,
                    'is_other_topic' => $validated['is_other_topic'] ?? false,
                    'lecturer_id' => $validated['lecturer_id'] ? $validated['lecturer_id'] : null,
                    'unit_note' => $validated['unit_note'] ?? '',
                    'is_held' => $validated['is_held'] ?? false,
                ]
            );

            return response()->json(['success' => true, 'slot' => $slot]);
        } catch (QueryException $e) {
            Log::error('Update slot DB error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'කාලසටහන් කොටුව සුරැකීමේ දෝෂයකි.'], 500);
        }
    }

    /**
     * API: Bulk save slots (used by auto-fill).
     */
    public function saveSlotsBulk(Request $request): JsonResponse
    {
        if ($err = $this->checkEditPermission()) return $err;

        $slots = $request->input('slots', []);

        try {
            DB::transaction(function () use ($slots, $request) {
                if ($request->input('replace_all', false)) {
                    TimetableSlot::truncate();
                }

                foreach ($slots as $item) {
                    TimetableSlot::updateOrCreate(
                        [
                            'centre_id' => $item['centre_id'],
                            'week' => $item['week'],
                            'day' => $item['day'],
                            'slot_index' => $item['slot_index'],
                        ],
                        [
                            'topic_id' => !empty($item['topic_id']) ? $item['topic_id'] : null,
                            'is_other_topic' => !empty($item['is_other_topic']),
                            'lecturer_id' => !empty($item['lecturer_id']) ? $item['lecturer_id'] : null,
                            'unit_note' => $item['unit_note'] ?? '',
                            'is_held' => !empty($item['is_held']),
                        ]
                    );
                }
            });

            SecurityLog::logEvent(Auth::id(), 'slots_bulk_saved', $request->ip(), $request->userAgent(), ['count' => count($slots)]);

            return response()->json(['success' => true]);
        } catch (QueryException $e) {
            Log::error('Bulk slots DB error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'ස්වයංක්‍රීය කාලසටහන සුරැකීමේදී දෝෂයක් මතු විය.'], 500);
        }
    }

    /**
     * API: Clear week or centre slots (Requires MoE Approval if not authorizer).
     */
    public function clearSlots(Request $request): JsonResponse
    {
        if ($err = $this->checkDeletePermission()) return $err;

        $validated = $request->validate([
            'centre_id' => 'required|exists:training_centres,id',
            'week' => 'nullable|integer',
            'type' => 'required|in:week,centre',
        ]);

        try {
            $query = TimetableSlot::where('centre_id', $validated['centre_id']);
            if ($validated['type'] === 'week') {
                $query->where('week', $validated['week']);
            }
            $query->delete();

            SecurityLog::logEvent(Auth::id(), 'slots_cleared', $request->ip(), $request->userAgent(), $validated);

            return response()->json(['success' => true]);
        } catch (QueryException $e) {
            Log::error('Clear slots DB error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'කාලසටහන ඉවත් කිරීමේදී දෝෂයක් මතු විය.'], 500);
        }
    }

    /**
     * API: Toggle Day Off.
     */
    public function toggleDayOff(Request $request): JsonResponse
    {
        if ($err = $this->checkEditPermission()) return $err;

        $validated = $request->validate([
            'week' => 'required|integer',
            'day' => 'required|integer',
            'is_off' => 'required|boolean',
        ]);

        try {
            DayOff::updateOrCreate(
                ['week' => $validated['week'], 'day' => $validated['day']],
                ['is_off' => $validated['is_off']]
            );

            return response()->json(['success' => true]);
        } catch (QueryException $e) {
            Log::error('Day off DB error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'නිවාඩු දිනය සටහන් කිරීමේ දෝෂයකි.'], 500);
        }
    }

    /**
     * API: Update Progress (manual extra hours & remarks).
     */
    public function updateProgress(Request $request): JsonResponse
    {
        if ($err = $this->checkEditPermission()) return $err;

        $validated = $request->validate([
            'centre_id' => 'required|exists:training_centres,id',
            'topic_id' => 'required|exists:topics,id',
            'manual_hours' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        try {
            $progress = TopicProgress::updateOrCreate(
                [
                    'centre_id' => $validated['centre_id'],
                    'topic_id' => $validated['topic_id'],
                ],
                [
                    'manual_hours' => $validated['manual_hours'] ?? 0,
                    'remarks' => $validated['remarks'] ?? '',
                ]
            );

            return response()->json(['success' => true, 'progress' => $progress]);
        } catch (QueryException $e) {
            Log::error('Progress update DB error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'ප්‍රගති වාර්තාව සුරැකීමේ දෝෂයකි.'], 500);
        }
    }

    /**
     * API: Reset all timetable and assignments data (Requires MoE Approval).
     */
    public function resetAll(Request $request): JsonResponse
    {
        if ($err = $this->checkDeletePermission()) return $err;

        try {
            DB::transaction(function () {
                TimetableSlot::truncate();
                TopicAssignment::truncate();
                DayOff::truncate();
                TopicProgress::query()->update(['manual_hours' => 0, 'remarks' => '']);
                Lecturer::truncate();

                $setting = ProgrammeSetting::first();
                if ($setting) {
                    $setting->update([
                        'start_date' => '2026-11-02',
                        'weeks' => 20,
                        'include_saturday' => false,
                    ]);
                }
            });

            SecurityLog::logEvent(Auth::id(), 'database_reset', $request->ip(), $request->userAgent(), []);

            return response()->json(['success' => true, 'state' => $this->getState()]);
        } catch (QueryException $e) {
            Log::error('Database reset DB error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'දත්ත සමුදාය Reset කිරීමේදී දෝෂයක් මතු විය.'], 500);
        }
    }

    /**
     * API: Complete import sync from client Excel parser.
     */
    public function syncImport(Request $request): JsonResponse
    {
        if ($err = $this->checkEditPermission()) return $err;

        $data = $request->all();

        try {
            DB::transaction(function () use ($data) {
                if (!empty($data['settings'])) {
                    $setting = ProgrammeSetting::first() ?? new ProgrammeSetting();
                    $setting->start_date = $data['settings']['start_date'] ?? '2026-11-02';
                    $setting->weeks = $data['settings']['weeks'] ?? 20;
                    $setting->include_saturday = !empty($data['settings']['include_saturday']);
                    $setting->save();
                }

                if (!empty($data['centres']) && is_array($data['centres'])) {
                    foreach ($data['centres'] as $cData) {
                        TrainingCentre::where('id', $cData['id'])->update([
                            'name' => $cData['name'] ?? '',
                            'manager_name' => $cData['manager_name'] ?? '',
                            'contact' => $cData['contact'] ?? '',
                        ]);
                    }
                }

                if (isset($data['lecturers']) && is_array($data['lecturers'])) {
                    Lecturer::truncate();
                    foreach ($data['lecturers'] as $lData) {
                        if (!empty($lData['name'])) {
                            Lecturer::create([
                                'id' => $lData['id'] ?? null,
                                'name' => $lData['name'],
                            ]);
                        }
                    }
                }

                if (isset($data['assignments']) && is_array($data['assignments'])) {
                    TopicAssignment::truncate();
                    foreach ($data['assignments'] as $aData) {
                        TopicAssignment::create([
                            'centre_id' => $aData['centre_id'],
                            'topic_id' => $aData['topic_id'],
                            'lecturer_id' => $aData['lecturer_id'],
                        ]);
                    }
                }

                if (isset($data['slots']) && is_array($data['slots'])) {
                    TimetableSlot::truncate();
                    foreach ($data['slots'] as $sData) {
                        TimetableSlot::create([
                            'centre_id' => $sData['centre_id'],
                            'week' => $sData['week'],
                            'day' => $sData['day'],
                            'slot_index' => $sData['slot_index'],
                            'topic_id' => $sData['topic_id'] ?? null,
                            'is_other_topic' => !empty($sData['is_other_topic']),
                            'lecturer_id' => $sData['lecturer_id'] ?? null,
                            'unit_note' => $sData['unit_note'] ?? '',
                            'is_held' => !empty($sData['is_held']),
                        ]);
                    }
                }

                if (isset($data['days_off']) && is_array($data['days_off'])) {
                    DayOff::truncate();
                    foreach ($data['days_off'] as $doData) {
                        DayOff::create([
                            'week' => $doData['week'],
                            'day' => $doData['day'],
                            'is_off' => true,
                        ]);
                    }
                }

                if (isset($data['progress']) && is_array($data['progress'])) {
                    foreach ($data['progress'] as $pData) {
                        TopicProgress::updateOrCreate(
                            [
                                'centre_id' => $pData['centre_id'],
                                'topic_id' => $pData['topic_id'],
                            ],
                            [
                                'manual_hours' => $pData['manual_hours'] ?? 0,
                                'remarks' => $pData['remarks'] ?? '',
                            ]
                        );
                    }
                }
            });

            SecurityLog::logEvent(Auth::id(), 'excel_imported_and_synced', $request->ip(), $request->userAgent(), []);

            return response()->json(['success' => true, 'state' => $this->getState()]);
        } catch (QueryException $e) {
            Log::error('Excel import DB error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Excel දත්ත සමුදාය සමඟ sync කිරීමේදී දෝෂයක් මතු විය.'], 500);
        }
    }
}
