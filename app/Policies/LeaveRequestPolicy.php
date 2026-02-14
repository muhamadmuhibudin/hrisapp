<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Payroll;
use App\Models\LeaveRequest;

class LeaveRequestPolicy
{
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

    public function viewAny(User $user): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user) || $this->isEmployee($user);
    }

    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        return $this->isSuperAdmin($user)
            || $this->isHR($user)
            || (int) $user->employee_id === (int) $leaveRequest->employee_id;
    }

    public function create(User $user): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user) || $this->isEmployee($user);
    }

    public function update(User $user, LeaveRequest $leaveRequest): bool
    {
        return $this->isSuperAdmin($user)
            || $this->isHR($user)
            || (int) $user->employee_id === (int) $leaveRequest->employee_id;
    }

    public function delete(User $user, LeaveRequest $leaveRequest): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user)
            || (int) $user->employee_id === (int) $leaveRequest->employee_id;
    }

    public function restore(User $user, LeaveRequest $leaveRequest): bool
    {
        return $this->isSuperAdmin($user);
    }

    public function forceDelete(User $user, LeaveRequest $leaveRequest): bool
    {
        return $this->isSuperAdmin($user);
    }

    public function confirm(User $user, LeaveRequest $leaveRequest): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user);
    }

    public function reject(User $user, LeaveRequest $leaveRequest): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user);
    }
}