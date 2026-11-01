<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LeaveRequest;

class LeaveRequestPolicy
{
    /**
     * Perform pre-authorization checks.
     * Super Admin bypasses all specific policy checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $this->isHR($user) || $this->isEmployee($user);
    }

    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        // HR can view all requests, Employees can only view their own
        return $this->isHR($user) || (int) $user->employee_id === (int) $leaveRequest->employee_id;
    }

    public function create(User $user): bool
    {
        return $this->isHR($user) || $this->isEmployee($user);
    }

    public function update(User $user, LeaveRequest $leaveRequest): bool
    {
        // HR can update any request, Employees can only update their own (e.g., before it's approved)
        if (in_array($user->role_id, [1, 2])) {
            return true;
        }

        return $user->employee_id === $leaveRequest->employee_id
            && $leaveRequest->status === 'pending';
    }

    public function delete(User $user, LeaveRequest $leaveRequest): bool
    {
        // HR can delete any request, Employees can only delete their own
        return $this->isHR($user) || (int) $user->employee_id === (int) $leaveRequest->employee_id;
    }

    public function restore(User $user, LeaveRequest $leaveRequest): bool
    {
        return false; // Only Super Admin can restore (handled by before method)
    }

    public function forceDelete(User $user, LeaveRequest $leaveRequest): bool
    {
        return false; // Only Super Admin can force delete (handled by before method)
    }

    // --- Custom Actions ---

    public function confirm(User $user, LeaveRequest $leaveRequest): bool
    {
        return $this->isHR($user);
    }

    public function reject(User $user, LeaveRequest $leaveRequest): bool
    {
        return $this->isHR($user);
    }

    // --- Role Helper Methods ---

    private function isSuperAdmin(User $user): bool
    {
        return (int) $user->role_id === 1;
    }

    private function isHR(User $user): bool
    {
        return (int) $user->role_id === 2;
    }

    private function isEmployee(User $user): bool
    {
        return (int) $user->role_id === 3;
    }
}