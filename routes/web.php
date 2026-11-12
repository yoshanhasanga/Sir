<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TimetableController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Requires High-Security Authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/', [TimetableController::class, 'index'])->name('timetable.index');

    Route::prefix('api')->group(function () {
        // State
        Route::get('/state', [TimetableController::class, 'apiState']);

        // Data Mutations (Requires Edit Permission)
        Route::post('/settings', [TimetableController::class, 'updateSettings']);
        Route::post('/centres/{id}', [TimetableController::class, 'updateCentre']);
        Route::post('/lecturers', [TimetableController::class, 'addLecturers']);
        Route::put('/lecturers/{id}', [TimetableController::class, 'updateLecturer']);
        Route::delete('/lecturers/{id}', [TimetableController::class, 'deleteLecturer']);
        Route::post('/assignment', [TimetableController::class, 'toggleAssignment']);
        Route::post('/assignments/copy', [TimetableController::class, 'copyAssignments']);
        Route::post('/timetable/slot', [TimetableController::class, 'updateSlot']);
        Route::post('/timetable/slots-bulk', [TimetableController::class, 'saveSlotsBulk']);
        Route::post('/timetable/clear', [TimetableController::class, 'clearSlots']);
        Route::post('/timetable/day-off', [TimetableController::class, 'toggleDayOff']);
        Route::post('/progress', [TimetableController::class, 'updateProgress']);
        Route::post('/reset', [TimetableController::class, 'resetAll']);
        Route::post('/sync-import', [TimetableController::class, 'syncImport']);

        // Ministry of Education Approval Workflow Routes
        Route::get('/approvals', [ApprovalController::class, 'index']);
        Route::post('/approvals', [ApprovalController::class, 'store']);
        Route::post('/approvals/{id}/decide', [ApprovalController::class, 'decide']);
    });
});
