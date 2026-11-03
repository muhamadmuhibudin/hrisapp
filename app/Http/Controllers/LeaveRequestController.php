<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function __construct()
    {
        // Bind controller to LeaveRequestPolicy
        $this->authorizeResource(LeaveRequest::class, 'leave_request');
    }

    public function index()
    {
        $user = Auth::user();
        $query = LeaveRequest::with(['employee', 'approvedBy', 'rejectedBy'])->latest();

        // Employees only see their own requests; Admins/HR see all requests
        if ((int) $user->role_id === 3) {
            $query->where('employee_id', $user->employee_id);
        }

        $leaveRequests = $query->get();

        return view('leave-requests.index', compact('leaveRequests'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('leave-requests.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', LeaveRequest::class);
        $user = Auth::user();

        // Employees submit for themselves; Admins/HR can submit for others
        $employeeId = ((int) $user->role_id === 3) ? $user->employee_id : $request->employee_id;

        $request->validate([
            'employee_id' => ((int) $user->role_id !== 3) ? 'required|exists:employees,id' : 'nullable',
            'reason' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Prevent overlapping leave request dates for the same employee
        $overlappingLeave = LeaveRequest::where('employee_id', $employeeId)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })->exists();

        if ($overlappingLeave) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Leave request dates overlap with an existing scheduled leave.');
        }

        LeaveRequest::create([
            'employee_id' => $employeeId,
            'reason' => $request->reason,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'pending',
        ]);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    public function edit(LeaveRequest $leaveRequest)
    {
        $employees = Employee::all();
        return view('leave-requests.edit', compact('leaveRequest', 'employees'));
    }

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $user = Auth::user();

        // Prevent employees from modifying a request that has already been processed by HR
        if ((int) $user->role_id === 3 && $leaveRequest->status !== 'pending') {
            return redirect()->route('leave-requests.index')
                ->with('error', 'You cannot edit a leave request that has already been processed.');
        }

        $request->validate([
            'employee_id' => ((int) $user->role_id !== 3) ? 'required|exists:employees,id' : 'nullable',
            'reason' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $dataToUpdate = [
            'reason' => $request->reason,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];

        if ((int) $user->role_id !== 3) {
            $dataToUpdate['employee_id'] = $request->employee_id;
            if ($request->has('status')) {
                $dataToUpdate['status'] = strtolower($request->status);
            }
        }

        $leaveRequest->update($dataToUpdate);

        ActivityLog::record(
            'leave.updated',
            "Updated leave request #{$leaveRequest->id} for {$leaveRequest->employee->fullname}",
            $leaveRequest
        );

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request updated successfully.');
    }

    public function confirm(int $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $this->authorize('confirm', $leaveRequest);

        if ($leaveRequest->status !== 'pending') {
            return redirect()->route('leave-requests.index')
                ->with('error', 'This leave request has already been processed.');
        }

        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        ActivityLog::record(
            'leave.approved',
            "Approved leave request #{$leaveRequest->id} for {$leaveRequest->employee->fullname}",
            $leaveRequest
        );

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request approved successfully.');
    }

    public function reject(int $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $this->authorize('reject', $leaveRequest);

        if ($leaveRequest->status !== 'pending') {
            return redirect()->route('leave-requests.index')
                ->with('error', 'This leave request has already been processed.');
        }

        $leaveRequest->update([
            'status' => 'rejected',
            'rejected_by' => Auth::id(),
            'rejected_at' => now(),
        ]);

        ActivityLog::record(
            'leave.rejected',
            "Rejected leave request #{$leaveRequest->id} for {$leaveRequest->employee->fullname}",
            $leaveRequest
        );

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request rejected successfully.');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        ActivityLog::record(
            'leave.deleted',
            "Deleted leave request #{$leaveRequest->id} for {$leaveRequest->employee->fullname}",
            $leaveRequest
        );
        $leaveRequest->delete();

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request deleted successfully.');
    }
}