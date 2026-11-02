<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EmployeeController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        abort_unless(in_array($request->user()->role_id, [1, 2]), 403, 'Unauthorized.');

        $employees = Employee::with(['department', 'role'])
            ->latest()
            ->paginate(15);

        return EmployeeResource::collection($employees);
    }

    public function show(Request $request, Employee $employee): EmployeeResource
    {
        abort_unless(in_array($request->user()->role_id, [1, 2]), 403, 'Unauthorized.');

        return new EmployeeResource($employee->load(['department', 'role']));
    }
}