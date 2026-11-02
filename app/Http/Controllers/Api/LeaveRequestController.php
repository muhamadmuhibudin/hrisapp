<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeaveRequestResource;
use App\Models\ActivityLog;
use App\Models\LeaveRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LeaveRequestController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();
        $query = LeaveRequest::with('employee')->latest();

        // Scope to own requests for regular employees
        if ($user->role_id === 3) {
            $query->where('employee_id', $user->employee_id);
        }

        return LeaveRequestResource::collection($query->paginate(15));
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        abort_unless($user->employee_id, 422, 'User is not linked to an employee profile.');

        $validated = $request->validate([
            'leave_type' => ['required', 'string', 'max:100'],
            'reason' => ['required', 'string', 'max:500'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        // Prevent overlapping leave requests
        $hasOverlap = LeaveRequest::where('employee_id', $user->employee_id)
            ->where('status', '!=', 'rejected')
            ->where(function ($query) use ($validated) {
                $query->where('start_date', '<=', $validated['end_date'])
                    ->where('end_date', '>=', $validated['start_date']);
            })
            ->exists();

        if ($hasOverlap) {
            return response()->json([
                'message' => 'Leave dates overlap with an existing request.',
            ], 422);
        }

        $leaveRequest = LeaveRequest::create([
            'employee_id' => $user->employee_id,
            'leave_type' => $validated['leave_type'],
            'reason' => $validated['reason'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'pending',
        ]);

        return (new LeaveRequestResource($leaveRequest->load('employee')))
            ->additional(['message' => 'Leave request submitted successfully.'])
            ->response()
            ->setStatusCode(201);
    }

    public function updateStatus(Request $request, LeaveRequest $leaveRequest): LeaveRequestResource
    {
        abort_unless(in_array($request->user()->role_id, [1, 2]), 403, 'Unauthorized.');

        $validated = $request->validate([
            'status' => ['required', 'in:confirmed,rejected'],
        ]);

        $leaveRequest->update(['status' => $validated['status']]);

        ActivityLog::record(
            'leave.' . $validated['status'],
            "API: {$validated['status']} leave request #{$leaveRequest->id}",
            $leaveRequest
        );

        return (new LeaveRequestResource($leaveRequest->load('employee')))
            ->additional(['message' => 'Leave request status updated.']);
    }
}