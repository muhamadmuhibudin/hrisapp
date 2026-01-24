<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presence;
use App\Models\Employee;

class PresenceController extends Controller
{
    public function index()
    {
       $presences = Presence::all();
       return view('presences.index', compact('presences'));
    }

    public function create()
    {   $employees = Employee::all();
        return view('presences.create', compact('employees'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'check_in' => 'required',
            'check_out' => 'required',
            'date' => 'required|date',
            'status' => 'required|string',
        ]);

        Presence::create($request->all());

        return redirect()->route('presences.index')
                         ->with('success', 'Presence recorded successfully.');
    }

}