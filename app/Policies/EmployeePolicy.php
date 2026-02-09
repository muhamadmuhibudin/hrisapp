<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
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
        return $this->isSuperAdmin($user) || $this->isHR($user);
    }

    public function view(User $user, Employee $employee): bool
    {
        if ($this->isSuperAdmin($user) || $this->isHR($user)) {
            return true;
        }
        return $this->isEmployee($user) && $user->employee_id === $employee->id;
    }

    public function create(User $user): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user);
    }

    public function update(User $user, Employee $employee): bool
    {
        if ($this->isSuperAdmin($user) || $this->isHR($user)) {
            return true;
        }
        return $this->isEmployee($user) && $user->employee_id === $employee->id;
    }

    public function delete(User $user, Employee $employee): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user);
    }

    public function restore(User $user, Employee $employee): bool
    {
        return $this->isSuperAdmin($user);
    }

    public function forceDelete(User $user, Employee $employee): bool
    {
        return $this->isSuperAdmin($user);
    }
}