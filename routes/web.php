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

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/presence', [DashboardController::class, 'presence']);

    // Tasks
    Route::resource('/tasks', TaskController::class)
        ->middleware('checkRole:Super Admin,HR Manager,Employee');
    Route::get('/tasks/done/{id}', [TaskController::class, 'done'])
        ->name('tasks.done')
        ->middleware('checkRole:Super Admin,HR Manager');
    Route::get('/tasks/pending/{id}', [TaskController::class, 'pending'])
        ->name('tasks.pending')
        ->middleware('checkRole:Super Admin,HR Manager');

    // Employees
    Route::resource('/employees', EmployeeController::class)
        ->middleware('checkRole:Super Admin,HR Manager');

    // Departments
    Route::resource('/departments', DepartmentController::class)
        ->middleware('checkRole:Super Admin,HR Manager');

    // Roles
    Route::resource('/roles', RoleController::class)
        ->middleware('checkRole:Super Admin,HR Manager');

    // Presences
    Route::resource('/presences', PresenceController::class)
        ->middleware('checkRole:Super Admin,HR Manager,Employee');

    // Payrolls
    Route::resource('/payrolls', PayrollController::class)
        ->middleware('checkRole:Super Admin,HR Manager,Employee');

    // Leave Requests
    Route::resource('/leave-requests', LeaveRequestController::class)
        ->middleware('checkRole:Super Admin,HR Manager,Employee');
    Route::get('leave-requests/confirm/{id}', [LeaveRequestController::class, 'confirm'])
        ->name('leave-requests.confirm')
        ->middleware('checkRole:HR Manager,Super Admin');
    Route::get('leave-requests/reject/{id}', [LeaveRequestController::class, 'reject'])
        ->name('leave-requests.reject')
        ->middleware('checkRole:HR Manager,Super Admin');
});

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';