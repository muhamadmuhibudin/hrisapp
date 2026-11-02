<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'employee' => $this->whenLoaded('employee', fn() => $this->employee->fullname),
            'salary' => (float) $this->salary,
            'bonuses' => (float) $this->bonuses,
            'deductions' => (float) $this->deductions,
            'net_salary' => (float) $this->net_salary,
            'pay_date' => $this->pay_date,
        ];
    }
}