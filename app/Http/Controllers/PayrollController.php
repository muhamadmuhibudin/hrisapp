<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payroll;
use App\Models\Employee;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $role = Auth::user()->employee?->role?->title;

        if (in_array($role, ['Super Admin', 'HR Manager'])) {
            $payrolls = Payroll::with('employee')->get();
        } else {
            $payrolls = Payroll::with('employee')
                ->where('employee_id', Auth::user()->employee_id)
                ->get();
        }

        return view ('payrolls.index', compact ('payrolls'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('payrolls.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary' => 'required|numeric',
            'bonuses' => 'required|numeric',
            'deductions' => 'required|numeric',
            'pay_date' => 'required|date',
        ]);

        $netSalary = $request->input('salary') - $request->input('deductions') + $request->input('bonuses');
        $request->merge(['net_salary' => $netSalary]);

        Payroll::create($request->all());

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll created successfully.');
    }

    public function edit(Payroll $payroll)
    {
        $employees = Employee::all();
        return view('payrolls.edit', compact('payroll', 'employees'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary' => 'required|numeric',
            'bonuses' => 'required|numeric',
            'deductions' => 'required|numeric',
            'pay_date' => 'required|date',
        ]);

        $netSalary = $request->input('salary') - $request->input('deductions') + $request->input('bonuses');
        $request->merge(['net_salary' => $netSalary]);

        $payroll->update($request->all());

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll updated successfully.');
    }

    public function show(Payroll $payroll)
    {
        return view('payrolls.show', compact('payroll'));
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll deleted successfully.');
    }

}