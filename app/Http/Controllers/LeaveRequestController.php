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

    public function edit(LeaveRequest $leaveRequest)
    {
        $employees = Employee::all();
        return view("leave-requests.edit", 
        compact("leaveRequest", "employees"));
    }

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'reason' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $leaveRequest->update($request->all());
        return redirect()->route('leave-requests.index')
                         ->with('success', 'Leave request updated successfully.');
    }

    public function confirm (int $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->update(['status' => 'Confirmed']);

        return redirect()->route('leave-requests.index')
                         ->with('success', 'Leave request confirmed successfully.');
    }

    public function reject(int $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->update(['status' => 'Rejected']);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request rejected successfully.');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();
        return redirect()->route('leave-requests.index')
                         ->with('success', 'Leave request deleted successfully.');
    }
}
