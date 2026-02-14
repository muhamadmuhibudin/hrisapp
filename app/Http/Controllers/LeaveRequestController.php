<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LeaveRequest;
use App\Models\Employee;

class LeaveRequestController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(LeaveRequest::class, 'leave_request');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role_id === 3) { // Employee
            $leaveRequests = LeaveRequest::where('employee_id', $user->employee_id)->get();
        } else {
            $leaveRequests = LeaveRequest::all();
        }

        return view("leave-requests.index", compact("leaveRequests"));
    }

    public function create()
    {
        $employees = Employee::all();
        return view("leave-requests.create", compact("employees"));
    }

    public function store(Request $request)
    {
        $this->authorize('create', LeaveRequest::class);

        if (Auth::user()->role_id === 3) { // Employee
            $request->validate([
                'reason' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            LeaveRequest::create([
                'employee_id' => Auth::user()->employee_id,
                'reason' => $request->reason,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => 'pending',
            ]);
        } else { // HR / Super Admin
            $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'reason' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            LeaveRequest::create([
                'employee_id' => $request->employee_id,
                'reason' => $request->reason,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request created successfully.');
    }

    public function edit(LeaveRequest $leaveRequest)
    {
        $employees = Employee::all();
        return view("leave-requests.edit", compact("leaveRequest", "employees"));
    }

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        if (Auth::user()->role_id === 3) { // Employee
            $request->validate([
                'reason' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $leaveRequest->update([
                'reason' => $request->reason,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);
        } else { // HR / Super Admin
            $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'reason' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $leaveRequest->update($request->all());
        }

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request updated successfully.');
    }

    public function confirm(int $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $this->authorize('confirm', $leaveRequest);

        $leaveRequest->update(['status' => 'Confirmed']);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request confirmed successfully.');
    }

    public function reject(int $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $this->authorize('reject', $leaveRequest);

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