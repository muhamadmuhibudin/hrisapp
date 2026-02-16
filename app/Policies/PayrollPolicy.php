<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Payroll;

class PayrollPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user) || $this->isEmployee($user);
    }

    public function view(User $user, Payroll $payroll): bool
    {
        return $this->isSuperAdmin($user)
            || $this->isHR($user)
            || (int) $user->employee_id === (int) $payroll->employee_id;
    }

    public function create(User $user): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user);
    }

    public function update(User $user, Payroll $payroll): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user);
    }

    public function delete(User $user, Payroll $payroll): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user);
    }

    private function isSuperAdmin(User $user): bool
    {
        return $user->role_id === 1;
    }

    private function isHR(User $user): bool
    {
        return $user->role_id === 2;
    }

    private function isEmployee(User $user): bool
    {
        return $user->role_id === 3;
    }
}