<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Payroll;
use Illuminate\Support\Facades\Auth;

class PayrollObserver
{
    public function created(Payroll $payroll): void
    {
        if (!Auth::check()) {
            return;
        }

        ActivityLog::record(
            'payroll.created',
            "Created payroll #{$payroll->id} (Net: {$payroll->net_salary}) for employee ID {$payroll->employee_id}",
            $payroll
        );
    }

    public function updated(Payroll $payroll): void
    {
        if (!Auth::check()) {
            return;
        }

        ActivityLog::record(
            'payroll.updated',
            "Updated payroll #{$payroll->id} (Net: {$payroll->net_salary}) for employee ID {$payroll->employee_id}",
            $payroll
        );
    }

    public function deleted(Payroll $payroll): void
    {
        if (!Auth::check()) {
            return;
        }

        ActivityLog::record(
            'payroll.deleted',
            "Deleted payroll #{$payroll->id} (Net: {$payroll->net_salary}) for employee ID {$payroll->employee_id}",
            $payroll
        );
    }
}