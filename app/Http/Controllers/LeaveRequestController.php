<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\Employee;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $leaveRequests = LeaveRequest::all();
        return view("leave-requests.index", 
        compact("leaveRequests"));
    }

    public function create()
    {
        $employees = Employee::all();
        return view("leave-requests.create", compact("employees"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'reason' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $request->merge(
            ['status' => 'Pending']
        );

        LeaveRequest::create($request->all());
        return redirect()->route('leave-requests.index')
                         ->with('success', 'Leave request created successfully.');
    }
}
