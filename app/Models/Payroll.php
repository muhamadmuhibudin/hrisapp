<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Observers\PayrollObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;


class Payroll extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "payroll";
    protected $fillable = [
        'employee_id',
        'salary',
        'bonuses',
        'deductions',
        'net_salary',
        'pay_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
