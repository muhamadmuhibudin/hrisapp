<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Presence;
use App\Models\Employee;
use Carbon\Carbon;

class PresenceController extends Controller
{
    public function index()
    {
        if(session('role') == 'Employee'){
            $presences = Presence::where('employee_id', session('employee_id'))->get();
        } else {
            $presences = Presence::all();
        }
       return view('presences.index', compact('presences'));
    }

    public function create()
    {   $employees = Employee::all();
        return view('presences.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $role = Auth::user()->employee?->role?->title;

        if (in_array($role, ['Super Admin', 'HR Manager'])) {
            $request->validate([
                'check_in' => 'required',
                'check_out' => 'required',
                'date' => 'required|date',
                'status' => 'required|string',
                'employee_id' => 'required|exists:employees,id',
            ]);

            Presence::create($request->all());
        } else {
            Presence::create([
                'employee_id' => Auth::user()->employee_id,
                'check_in' => Carbon::now()->format('Y-m-d H:i:s'),
                'check_out' => Carbon::now()->addHours(9)->format('Y-m-d H:i:s'),
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'date' => Carbon::now()->format('Y-m-d'),
                'status' => 'present',
            ]);
        }

        return redirect()->route('presences.index')
            ->with('success', 'Presence recorded successfully, Check out will be auto recorded after 9 hours.');
    }


    public function edit(Presence $presence) 
    {
        $employees = Employee::all();
        return view('presences.edit', compact('presence', 'employees'));
    } 

    public function update(Request $request, Presence $presence)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'check_in' => 'required',
            'check_out' => 'required',
            'date' => 'required|date',
            'status' => 'required|string',
        ]);

        $presence->update($request->all());

        return redirect()->route('presences.index')
                         ->with('success', 'Presence updated successfully.');
    }

    public function destroy (Presence $presence) 
    {
        $presence->delete();
        return redirect()->route('presences.index')
                         ->with('success', 'Presence deleted successfully.');
    }

}