<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class LeaveRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'reason',
        'start_date',
        'end_date',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
