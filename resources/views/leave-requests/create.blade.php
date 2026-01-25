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
                    <p class="text-subtitle text-muted">Handle Leave Request data</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Leave Request</li>
                            <li class="breadcrumb-item active" aria-current="page">New</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        Create
                    </h5>
                </div>
                <div class="card-body">

                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('leave-requests.store') }}" method="POST">
                        @csrf

                        @if(in_array(Auth::user()->employee?->role?->title, ['Super Admin', 'HR Manager']))

                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee</label>
                            <select name="employee_id" id="status" class="form-control">
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" 
                                {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->fullname }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        @endif

                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason</label>
                            <select name="reason" id="status" class="form-control">
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Vacation Leave">Vacation Leave</option>
                                <option value="Birth Leave">Birth Leave</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control date @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date') }}"
                                required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control date @error('end_date') is-invalid @enderror" name="end_date" value="{{ old('end_date') }}"
                                required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                        <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary mt-3">Back to List</a>
                    </form>    
                </div>
            </div>

        </section>
    </div>
@endsection