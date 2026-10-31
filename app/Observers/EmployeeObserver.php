<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class EmployeeObserver
{
    public function created(Employee $employee): void
    {
        if (!Auth::check()) {
            return;
        }

        ActivityLog::record(
            'employee.created',
            "Created employee {$employee->fullname} (ID #{$employee->id})",
            $employee
        );
    }

    public function updated(Employee $employee): void
    {
        if (!Auth::check()) {
            return;
        }

        ActivityLog::record(
            'employee.updated',
            "Updated employee profile/role for {$employee->fullname} (ID #{$employee->id})",
            $employee
        );
    }

    public function deleted(Employee $employee): void
    {
        if (!Auth::check()) {
            return;
        }

        ActivityLog::record(
            'employee.deleted',
            "Deleted employee {$employee->fullname} (ID #{$employee->id})",
            $employee
        );
    }
}