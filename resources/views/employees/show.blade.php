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
                    <h3>employees</h3>
                    <p class="text-subtitle text-muted">Handle employee data and profile</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Employee</li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white pt-2 pb-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i> Employee Detail
                    </h5>
                </div>
                <div class="card-body mt-4">
                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold text-muted">Full Name</div>
                        <div class="col-md-8">{{ $employee->fullname }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold text-muted">Email</div>
                        <div class="col-md-8">{{ $employee->email }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold text-muted">Department</div>
                        <div class="col-md-8">{{ $employee->department->name }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold text-muted">Role</div>
                        <div class="col-md-8">{{ $employee->role->title }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold text-muted">Birth Date</div>
                        <div class="col-md-8">
                            <span class="badge bg-info text-dark">
                                {{ \Carbon\Carbon::parse($employee->birth_date)->format('d F Y') }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold text-muted">Hire Date</div>
                        <div class="col-md-8">
                            <span class="badge bg-info text-dark">
                                {{ \Carbon\Carbon::parse($employee->hire_date)->format('d F Y') }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold text-muted">Status</div>
                        <div class="col-md-8">
                            @if($employee->status == 'active')
                            <span class="badge bg-success">{{ ucfirst($employee->status) }}</span>
                            @else($employee->status == 'inactive')
                            <span class="badge bg-danger">{{ ucfirst($employee->status) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold text-muted">Salary</div>
                        <div class="col-md-8">{{ number_format($employee->salary) }}</div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection