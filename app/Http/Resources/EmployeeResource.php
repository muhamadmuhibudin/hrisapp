<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fullname' => $this->fullname,
            'email' => $this->email,
            'hire_date' => $this->hire_date,
            'status' => $this->status,
            'salary' => (float) $this->salary,
            'department' => $this->whenLoaded('department', fn() => [
                'id' => $this->department->id,
                'name' => $this->department->name,
            ]),
            'role' => $this->whenLoaded('role', fn() => [
                'id' => $this->role->id,
                'title' => $this->role->title,
            ]),
        ];
    }
}