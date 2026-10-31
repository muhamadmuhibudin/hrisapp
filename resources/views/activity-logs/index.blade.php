@extends('layouts.dashboard')

@section('content')
    <div class="page-heading">
        <h3>Activity Logs</h3>
        <p class="text-subtitle text-muted">Audit trail of sensitive HR and Payroll actions</p>
    </div>

    <section class="section">
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
                                    <td><span class="badge bg-info">{{ $log->action }}</span></td>
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