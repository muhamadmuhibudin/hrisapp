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
                    <table class="table table-striped" id="table1">
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
                            @foreach($employees as $employees)
                                <tr>
                                    <td>{{ $employees->fullname }}</td>
                                    <td>{{ $employees->email }}</td>
                                    <td>{{ $employees->department->name }}</td>
                                    <td>{{ $employees->role->title }}</td>
                                    <td>
                                        @if($employees->status == 'active')
                                            <span class="badge bg-warning">{{ $employees->status }}</span>
                                        @else
                                            <span class="badge bg-info">{{ $employees->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $employees->salary }}</td>

                                    <td class="d-flex justify-content-between gap-1">
                                        <a href="{{ route('employees.show', $employees->id) }}" target="_blank" class="btn btn-info btn-sm btn-primary" rel="noopener noreferrer">View</a>
                                        <a href="{{ route('employees.edit', $employees->id) }}" target="_blank" class="btn btn-warning btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('employees.destroy', $employees->id) }}" method="POST">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-sm btn-primary delete-form">Delete</button>
                                        </form>
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