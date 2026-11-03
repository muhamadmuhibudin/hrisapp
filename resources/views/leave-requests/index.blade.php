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
                        @can('create', App\Models\LeaveRequest::class)
                            <a href="{{ route('leave-requests.create') }}" class="btn btn-primary mb-3 ms-auto">New Leave
                                Request</a>
                        @endcan
                    </div>

                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div id="loading" class="loading-state text-center py-10">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <table id="table1" class="table table-striped data-table" style="display:none;">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Reason</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Approval Info</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaveRequests as $leaveRequest)
                                @php
                                    $status = strtolower($leaveRequest->status);
                                    $displayStatus = ucfirst($status);
                                @endphp

                                <tr>
                                    <td>{{ $leaveRequest->employee->fullname }}</td>
                                    <td>{{ $leaveRequest->reason }}</td>
                                    <td>{{ $leaveRequest->start_date }}</td>
                                    <td>{{ $leaveRequest->end_date }}</td>

                                    <!-- STATUS -->
                                    <td>
                                        @if($status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $displayStatus }}</span>
                                        @endif
                                    </td>

                                    <!-- APPROVAL INFO -->
                                    <td>
                                        @if($status === 'approved' && $leaveRequest->approvedBy)
                                            <small class="d-block fw-semibold text-success">
                                                Approved by: {{ $leaveRequest->approvedBy->name }}
                                            </small>
                                            <small class="text-muted">
                                                {{ $leaveRequest->approved_at?->format('d M Y, H:i') }}
                                            </small>
                                        @elseif($status === 'rejected' && $leaveRequest->rejectedBy)
                                            <small class="d-block fw-semibold text-danger">
                                                Rejected by: {{ $leaveRequest->rejectedBy->name }}
                                            </small>
                                            <small class="text-muted">
                                                {{ $leaveRequest->rejected_at?->format('d M Y, H:i') }}
                                            </small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <!-- ACTIONS -->
                                    <td class="d-flex justify-content-between gap-1">
                                        <div class="d-flex gap-1">
                                            @can('confirm', $leaveRequest)
                                                @if($status === 'pending')
                                                    <a href="{{ route('leave-requests.confirm', $leaveRequest->id) }}"
                                                        class="btn btn-sm btn-success">Approve</a>
                                                    <a href="{{ route('leave-requests.reject', $leaveRequest->id) }}"
                                                        class="btn btn-sm btn-warning">Reject</a>
                                                @endif
                                            @endcan
                                        </div>

                                        <div class="d-flex gap-1">
                                            @can('update', $leaveRequest)
                                                <a href="{{ route('leave-requests.edit', $leaveRequest->id) }}"
                                                    class="btn btn-sm btn-primary">Edit</a>
                                            @endcan

                                            @can('delete', $leaveRequest)
                                                <form action="{{ route('leave-requests.destroy', $leaveRequest->id) }}"
                                                    method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger btn-delete">Delete</button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-gray-500 py-10">
                                        No leave requests found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection