<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PresenceResource;
use App\Models\Presence;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PresenceController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();
        $query = Presence::with('employee')->latest('date');

        // Scope to own records for regular employees
        if ($user->role_id === 3) {
            $query->where('employee_id', $user->employee_id);
        }

        return PresenceResource::collection($query->paginate(15));
    }

    public function checkIn(Request $request): JsonResponse
    {
        $user = $request->user();
        abort_unless($user->employee_id, 422, 'User is not linked to an employee profile.');

        $today = now()->toDateString();

        $alreadyCheckedIn = Presence::where('employee_id', $user->employee_id)
            ->where('date', $today)
            ->exists();

        if ($alreadyCheckedIn) {
            return response()->json([
                'message' => 'You have already checked in today.',
            ], 422);
        }

        $presence = Presence::create([
            'employee_id' => $user->employee_id,
            'date' => $today,
            'check_in' => now()->format('H:i:s'),
            'status' => 'present',
        ]);

        return (new PresenceResource($presence->load('employee')))
            ->additional(['message' => 'Check-in recorded successfully.'])
            ->response()
            ->setStatusCode(201);
    }
}