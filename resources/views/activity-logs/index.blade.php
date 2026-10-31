@extends('layouts.dashboard')

@section('content')
    <div class="page-heading">
        <h3>Activity Logs</h3>
        <p class="text-subtitle text-muted">Audit trail of sensitive HR and Payroll actions</p>
    </div>

    <section class="section">
        <div class="row mb-3">
            <div class="col-6 col-lg-3">
                <div class="card mb-2">
                    <div class="card-body px-4 py-3">
                        <h6 class="text-muted font-semibold mb-1">Total Logs</h6>
                        <h4 class="font-extrabold mb-0">{{ number_format($stats['total']) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card mb-2">
                    <div class="card-body px-4 py-3">
                        <h6 class="text-muted font-semibold mb-1">Today</h6>
                        <h4 class="font-extrabold mb-0 text-primary">{{ number_format($stats['today']) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card mb-2">
                    <div class="card-body px-4 py-3">
                        <h6 class="text-muted font-semibold mb-1">Payroll Actions</h6>
                        <h4 class="font-extrabold mb-0 text-info">{{ number_format($stats['payroll']) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card mb-2">
                    <div class="card-body px-4 py-3">
                        <h6 class="text-muted font-semibold mb-1">Leave Actions</h6>
                        <h4 class="font-extrabold mb-0 text-warning">{{ number_format($stats['leave']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('activity-logs.index') }}" class="row g-2 mb-4">
                    <div class="col-md-3">
                        <select name="module" class="form-select">
                            <option value="">All Modules</option>
                            <option value="payroll" @selected(request('module') === 'payroll')>Payroll</option>
                            <option value="leave" @selected(request('module') === 'leave')>Leave Requests</option>
                            <option value="employee" @selected(request('module') === 'employee')>Employees</option>
                            <option value="role" @selected(request('module') === 'role')>Roles</option>
                            <option value="auth" @selected(request('module') === 'auth')>Authentication</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search description or action..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="user_id" class="form-select">
                            <option value="">All Users</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected((string) request('user_id') === (string) $user->id)>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                        <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $log->user?->name ?? 'System' }}</td>
                                    <td><span class="badge {{ $log->badge_class }}">{{ $log->action }}</span></td>
                                    <td>{{ $log->description }}</td>
                                    <td><code>{{ $log->ip_address }}</code></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No activity recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection