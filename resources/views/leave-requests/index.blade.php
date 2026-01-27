@extends('layouts.dashboard')
@section('content')
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Leave Request</h3>
                    <p class="text-subtitle text-muted">Manage Leave Request data</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Leave Request</li>
                            <li class="breadcrumb-item active" aria-current="page">Index</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        Leave Requests Data
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <a href="{{ route('leave-requests.create') }}" class="btn btn-primary mb-3 ms-auto">New Leave
                            Request</a>
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Reason</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                @if(in_array(Auth::user()->employee?->role?->title, ['Super Admin', 'HR Manager']))
                                    <th>Option</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaveRequests as $leaveRequest)
                                @php
                                    $status = strtolower($leaveRequest->status);
                                    $displayStatus = ucfirst($status);
                                    $role = Auth::user()->employee?->role?->title;
                                @endphp

                                <tr>
                                    <td>{{ $leaveRequest->employee->fullname }}</td>
                                    <td>{{ $leaveRequest->reason }}</td>
                                    <td>{{ $leaveRequest->start_date }}</td>
                                    <td>{{ $leaveRequest->end_date }}</td>

                                    <!-- STATUS -->
                                    <td>
                                        @if($status === 'pending')
                                            <span class="badge bg-warning">{{ $displayStatus }}</span>
                                        @elseif($status === 'rejected')
                                            <span class="badge bg-danger">{{ $displayStatus }}</span>
                                        @elseif($status === 'confirmed')
                                            <span class="badge bg-success">{{ $displayStatus }}</span>
                                        @endif
                                    </td>

                                    <!-- ACTIONS -->
                                    <td class="d-flex justify-content-between">

                                        @if(in_array($role, ['Super Admin', 'HR Manager']))
                                            <div>
                                                @if($status === 'pending')
                                                    <a href="{{ route('leave-requests.confirm', $leaveRequest->id) }}"
                                                        class="btn btn-sm btn-success">Confirm</a>
                                                    <a href="{{ route('leave-requests.reject', $leaveRequest->id) }}"
                                                        class="btn btn-sm btn-warning">Reject</a>

                                                @elseif($status === 'confirmed')
                                                    <a href="{{ route('leave-requests.reject', $leaveRequest->id) }}"
                                                        class="btn btn-sm btn-warning">Reject</a>

                                                @elseif($status === 'rejected')
                                                    <a href="{{ route('leave-requests.confirm', $leaveRequest->id) }}"
                                                        class="btn btn-sm btn-success">Confirm</a>
                                                @endif
                                            </div>

                                            <div>
                                                <a href="{{ route('leave-requests.edit', $leaveRequest->id) }}" class="btn btn-sm btn-primary">Edit</a>

                                                <form action="{{ route('leave-requests.destroy', $leaveRequest->id) }}" method="POST"
                                                    class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>

        </section>
    </div>
@endsection