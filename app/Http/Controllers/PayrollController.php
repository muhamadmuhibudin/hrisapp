<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payroll;
use App\Models\Employee;

class PayrollController extends Controller
{
    public function __construct()
    {
        // Binding ke PayrollPolicy
        $this->authorizeResource(Payroll::class, 'payroll');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role_id === 1 || $user->role_id === 2) { 
            $payrolls = Payroll::with('employee')->get();
        } else {
            $payrolls = Payroll::with('employee')
                ->where('employee_id', $user->employee_id)
                ->get();
        }

        return view('payrolls.index', compact('payrolls'));
    }

    public function create()
    {
        $this->authorize('create', Payroll::class);

        $employees = Employee::all();
        return view('payrolls.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Payroll::class);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary' => 'required|numeric',
            'bonuses' => 'required|numeric',
            'deductions' => 'required|numeric',
            'pay_date' => 'required|date',
        ]);

        $netSalary = $request->salary - $request->deductions + $request->bonuses;

        Payroll::create([
            'employee_id' => $request->employee_id,
            'salary' => $request->salary,
            'bonuses' => $request->bonuses,
            'deductions' => $request->deductions,
            'net_salary' => $netSalary,
            'pay_date' => $request->pay_date,
        ]);

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll created successfully.');
    }

    public function show(Payroll $payroll)
    {
        $this->authorize('view', $payroll);

        return view('payrolls.show', compact('payroll'));
    }

    public function edit(Payroll $payroll)
    {
        $this->authorize('update', $payroll);

        $employees = Employee::all();
        return view('payrolls.edit', compact('payroll', 'employees'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $this->authorize('update', $payroll);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary' => 'required|numeric',
            'bonuses' => 'required|numeric',
            'deductions' => 'required|numeric',
            'pay_date' => 'required|date',
        ]);

        $netSalary = $request->salary - $request->deductions + $request->bonuses;

        $payroll->update([
            'employee_id' => $request->employee_id,
            'salary' => $request->salary,
            'bonuses' => $request->bonuses,
            'deductions' => $request->deductions,
            'net_salary' => $netSalary,
            'pay_date' => $request->pay_date,
        ]);

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll updated successfully.');
    }

    public function destroy(Payroll $payroll)
    {
        $this->authorize('delete', $payroll);

        $payroll->delete();

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll deleted successfully.');
    }
}