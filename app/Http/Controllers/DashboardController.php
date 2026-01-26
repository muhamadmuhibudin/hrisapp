<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Payroll;
use App\Models\Presence;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
    {
        $employee = Employee::count();
        $department = Department::count();
        $payroll = Payroll::count();
        $presence = Presence::count();

        $tasks = Task::with('employee')->latest()->take(5)->get();

        $presenceData = Presence::selectRaw('date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $presenceDates = $presenceData->pluck('date');
        $presenceCounts = $presenceData->pluck('total');

        return view('dashboard.index', compact(
            'employee',
            'department',
            'payroll',
            'presence',
            'tasks',
            'presenceDates',
            'presenceCounts'
        ));
    }

    public function presence()
    {
        $end = Carbon::now();
        $start = $end->copy()->subMonths(11)->startOfMonth();

        $data = Presence::where('status', 'present')
            ->whereBetween('date', [$start, $end])
            ->selectRaw('DATE_FORMAT(date, "%Y-%m") as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $result = [];
        $labels = [];

        for ($i = 0; $i < 12; $i++) {
            $m = $start->copy()->addMonths($i)->format('Y-m');
            $labels[] = $start->copy()->addMonths($i)->format('M Y');
            $result[] = $data[$m] ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $result
        ]);
    }

}