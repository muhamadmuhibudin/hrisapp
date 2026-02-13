<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{

    private function isSuperAdmin(User $user): bool
    {
        return $user->role_id === 1;
    }

    private function isHR(User $user): bool
    {
        return $user->role_id === 2;
    }

    public function viewAny(User $user): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user) || $user->role_id === 3;
    }

    public function view(User $user, Task $task): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user) || (int) $task->assigned_to === (int) $user->employee_id;
    }

    public function create(User $user): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user);
    }

    public function update(User $user, Task $task): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user);
    }

    public function delete(User $user, Task $task): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user);
    }

    public function restore(User $user, Task $task): bool
    {
        return $this->isSuperAdmin($user);
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return $this->isSuperAdmin($user);
    }
}
