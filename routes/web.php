<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\LeaveRequestController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Wrap all routes that require authentication
Route::middleware(['auth'])->group(function () {

    // --- 1. General Routes (Basic Access) ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/presence', [DashboardController::class, 'presence']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- 2. Admin & HR Area (Special Authorization) ---
    // Note: Lowercase usage proves our case-insensitive middleware is working!
    Route::middleware(['checkRole:super admin,hr manager'])->group(function () {
        Route::resource('employees', EmployeeController::class);
        Route::resource('departments', DepartmentController::class);
        Route::resource('roles', RoleController::class);

        // Task & Leave Request State Management (Approve/Reject/Done)
        Route::get('tasks/done/{id}', [TaskController::class, 'done'])->name('tasks.done');
        Route::get('tasks/pending/{id}', [TaskController::class, 'pending'])->name('tasks.pending');
        
        Route::get('leave-requests/confirm/{id}', [LeaveRequestController::class, 'confirm'])->name('leave-requests.confirm');
        Route::get('leave-requests/reject/{id}', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
    });

    // --- 3. Shared Area (All Roles) ---
    // Portfolio Note: URL access is allowed for all, 
    // but the data privacy within is locked down by Laravel Policies.
    Route::middleware(['checkRole:super admin,hr manager,employee'])->group(function () {
        Route::resource('tasks', TaskController::class);
        Route::resource('presences', PresenceController::class);
        Route::resource('payrolls', PayrollController::class);
        Route::resource('leave-requests', LeaveRequestController::class);
    });

});

require __DIR__ . '/auth.php';