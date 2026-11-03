<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'employee' => $this->whenLoaded('employee', fn() => $this->employee->fullname),
            'reason' => $this->reason,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'approved_by' => $this->whenLoaded('approvedBy', fn() => $this->approvedBy?->name),
            'approved_at' => $this->approved_at?->toDateTimeString(),
            'rejected_by' => $this->whenLoaded('rejectedBy', fn() => $this->rejectedBy?->name),
            'rejected_at' => $this->rejected_at?->toDateTimeString(),
        ];
    }
}