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
                    <h3>Employees</h3>
                    <p class="text-subtitle text-muted">Handle employees data or profile</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Employees</li>
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
                        Data
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <a href="{{ route('employees.create') }}" class="btn btn-primary mb-3 ms-auto">New Employee</a>
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
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
                                <th>Fullname</th>
                                <th>Email</th>
                                <th>Department Id</th>
                                <th>Role Id</th>
                                <th>Status</th>
                                <th>Salary</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                                <tr>
                                    <td>{{ $employee->fullname }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>{{ $employee->department->name }}</td>
                                    <td>{{ $employee->role->title }}</td>
                                    <td>
                                        @if($employee->status == 'active')
                                            <span class="badge bg-warning">{{ ucfirst($employee->status) }}</span>
                                        @else
                                            <span class="badge bg-info">{{ ucfirst($employee->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($employee->salary) }}</td>

                                    <td class="d-flex justify-content-between gap-1">
                                        @can('view', $employee)
                                            <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-info btn-sm btn-primary"
                                                rel="noopener noreferrer">View</a>
                                        @endcan

                                        @can('update', $employee)
                                            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning btn-sm btn-primary">Edit</a>
                                        @endcan

                                        @can('delete', $employee)
                                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger btn-delete">Delete</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-gray-500 py-10">
                                        No employee records found.
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