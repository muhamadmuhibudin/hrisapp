<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PayrollResource;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PayrollController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();
        $query = Payroll::with('employee')->latest('pay_date');

        // Scope to own payrolls for regular employees
        if ($user->role_id === 3) {
            $query->where('employee_id', $user->employee_id);
        }

        return PayrollResource::collection($query->paginate(15));
    }

    public function show(Request $request, Payroll $payroll): PayrollResource
    {
        $user = $request->user();

        if ($user->role_id === 3 && $payroll->employee_id !== $user->employee_id) {
            abort(403, 'Unauthorized.');
        }

        return new PayrollResource($payroll->load('employee'));
    }
}