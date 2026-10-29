<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Presence;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function __construct()
    {
        // Bind the controller to the PayrollPolicy for automated authorization checks
        $this->authorizeResource(Payroll::class, 'payroll');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Admins and HR Managers have full visibility; regular employees only see their own records
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

        // Enforce strict numeric validation to prevent malicious input manipulation
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary'      => 'required|numeric|min:0',
            'bonuses'     => 'required|numeric|min:0',
            'deductions'  => 'required|numeric|min:0',
            'pay_date'    => 'required|date',
        ]);

        // Automatically calculate absence deductions based on the current month's presence records
        $payDate      = Carbon::parse($request->pay_date);
        $startOfMonth = $payDate->copy()->startOfMonth()->format('Y-m-d');
        $endOfMonth   = $payDate->copy()->endOfMonth()->format('Y-m-d');

        // Assuming a standard 22-day working month for absence penalty calculation
        $workingDays = 22; 
        
        $presentDays = Presence::where('employee_id', $request->employee_id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('status', 'present')
            ->count();

        $absentDays = $workingDays - $presentDays;
        
        // Apply a base penalty for each day absent (e.g., IDR 100,000 / day)
        $absencePenalty = ($absentDays > 0) ? ($absentDays * 100000) : 0;
        
        // Combine manual deductions with system-calculated absence penalties
        $totalDeductions = $request->deductions + $absencePenalty;

        // Prevent net salary from falling below zero due to excessive deductions
        $netSalary = $request->salary - $totalDeductions + $request->bonuses;
        if ($netSalary < 0) {
            $netSalary = 0;
        }

        Payroll::create([
            'employee_id' => $request->employee_id,
            'salary'      => $request->salary,
            'bonuses'     => $request->bonuses,
            'deductions'  => $totalDeductions,
            'net_salary'  => $netSalary,
            'pay_date'    => $request->pay_date,
        ]);

        return redirect()->route('payrolls.index')
            ->with('success', "Payroll created. Auto-deduction for {$absentDays} absent days applied.");
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
            'salary'      => 'required|numeric|min:0',
            'bonuses'     => 'required|numeric|min:0',
            'deductions'  => 'required|numeric|min:0',
            'pay_date'    => 'required|date',
        ]);

        // Prevent negative values when HR manually overrides the payroll record
        $netSalary = $request->salary - $request->deductions + $request->bonuses;
        $netSalary = max(0, $netSalary);

        $payroll->update([
            'employee_id' => $request->employee_id,
            'salary'      => $request->salary,
            'bonuses'     => $request->bonuses,
            'deductions'  => $request->deductions,
            'net_salary'  => $netSalary,
            'pay_date'    => $request->pay_date,
        ]);

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll updated successfully.');
    }

    public function export()
    {
        // Only Admins (1) and HR Managers (2) are authorized to export payroll data
        if (Auth::user()->role_id === 3) {
            abort(403, 'Unauthorized action. Only HR and Admin can export data.');
        }

        $payrolls = Payroll::with('employee')->get();
        $fileName = 'payroll_report_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Define CSV header columns
        $columns = ['Payroll ID', 'Employee Name', 'Basic Salary', 'Bonuses', 'Deductions', 'Net Salary', 'Pay Date'];

        // Use stream callback to efficiently process large datasets without memory leaks
        $callback = function () use ($payrolls, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns, ';');

            foreach ($payrolls as $payroll) {
                $employee = $payroll->employee;
                $employeeName = $employee ? ($employee->fullname ?? $employee->full_name ?? 'No Name') : 'Unknown Employee';
                
                fputcsv($file, [
                    $payroll->id,
                    $employeeName,
                    $payroll->salary,
                    $payroll->bonuses,
                    $payroll->deductions,
                    $payroll->net_salary,
                    $payroll->pay_date,
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy(Payroll $payroll)
    {
        $this->authorize('delete', $payroll);
        $payroll->delete();

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll deleted successfully.');
    }
}