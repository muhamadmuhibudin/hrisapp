<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LeaveRequest;
use App\Models\Employee;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->employee?->role?->title === 'Employee') {
            $leaveRequests = LeaveRequest::where('employee_id', $user->employee->id)->get();
        } else {
            $leaveRequests = LeaveRequest::all();
        }

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
        $user = Auth::user();
        $role = $user->employee?->role?->title;

        if ($role === 'Employee') {

            $request->validate([
                'reason' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            LeaveRequest::create([
                'employee_id' => $user->employee->id,
                'reason' => $request->reason,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => 'pending',
            ]);

        } else {

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
        $user = Auth::user();
        $role = $user->employee?->role?->title;

        if ($role === 'Employee' && $leaveRequest->employee_id !== $user->employee->id) {
            abort(403);
        }

        $employees = Employee::all();
        return view("leave-requests.edit", compact("leaveRequest", "employees"));
    }


    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $user = Auth::user();
        $role = $user->employee?->role?->title;

        if ($role === 'Employee' && $leaveRequest->employee_id !== $user->employee->id) {
            abort(403);
        }

        if ($role === 'Employee') {
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

        } else {
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


    private function authorizeHR()
    {
        if (
            !in_array(
                Auth::user()->employee?->role?->title,
                ['Super Admin', 'HR Manager']
            )
        ) {
            abort(403);
        }
    }

    public function confirm (int $id)
    {
        $this->authorizeHR();

        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->update(['status' => 'Confirmed']);

        return redirect()->route('leave-requests.index')
                         ->with('success', 'Leave request confirmed successfully.');
    }

    public function reject(int $id)
    {
        $this->authorizeHR();
        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->update(['status' => 'Rejected']);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request rejected successfully.');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        $user = Auth::user();
        $role = $user->employee?->role?->title;

        if ($role === 'Employee' && $leaveRequest->employee_id !== $user->employee->id) {
            abort(403);
        }

        $leaveRequest->delete();

        return redirect()
            ->route('leave-requests.index')
            ->with('success', 'Leave request deleted successfully.');
    }

}
