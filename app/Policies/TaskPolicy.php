<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
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

    public function view(User $user, Task $task): bool
    {
        // HR can view any task, Employees can only view tasks assigned to them
        return $this->isHR($user) || (int) $task->assigned_to === (int) $user->employee_id;
    }

    public function create(User $user): bool
    {
        return $this->isHR($user);
    }

    public function update(User $user, Task $task): bool
    {
        return $this->isHR($user);
    }

    public function delete(User $user, Task $task): bool
    {
        return $this->isHR($user);
    }

    public function restore(User $user, Task $task): bool
    {
        return false; // Handled by before() method. Only Super Admin can restore.
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return false; // Handled by before() method. Only Super Admin can force delete.
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