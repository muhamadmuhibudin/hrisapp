<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Employee;

class CheckRole
{
    /**
     * Handle an incoming request and verify user roles.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Ensure the user is fully authenticated before querying
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 2. Retrieve employee with eager loading to prevent N+1 query issues
        $employee = Employee::with('role')->find(auth()->user()->employee_id);

        // 3. Gracefully handle edge cases where employee or role data is missing
        if (!$employee || !$employee->role) {
            abort(403, 'Unauthorized Action: Role configuration is missing.');
        }

        // Standardize role title to lowercase for comparison
        $currentRole = strtolower($employee->role->title);

        // 4. Store session data (useful for global Blade checks)
        $request->session()->put('role', $currentRole);
        $request->session()->put('employee_id', $employee->id);

        // 5. Perform a case-insensitive check against the allowed roles
        $allowedRoles = array_map('strtolower', $roles);

        if (!in_array($currentRole, $allowedRoles)) {
            abort(403, 'Unauthorized Action: Insufficient permissions.');
        }

        return $next($request);
    }
}