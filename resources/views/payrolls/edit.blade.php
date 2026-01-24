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
                    <h3>Payrolls</h3>
                    <p class="text-subtitle text-muted">Handle Payroll data</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Payroll</li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        Edit
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

                    <form action="{{ route('payrolls.update', $payroll->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee</label>
                            <select name="employee_id" id="status" class="form-control">
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" 
                                {{ (old('employee_id', $payroll->employee_id) == $employee->id) ? 'selected' : '' }}>
                                    {{ $employee->fullname }}
                                </option>
                            @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="salary" class="form-label">Salary</label>
                            <input type="number" class="form-control @e`rror('salary') is-invalid @enderror" name="salary"
                                value="{{ old('salary', $payroll->salary) }}" required>
                            @error('salary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deductions" class="form-label">Deductions</label>
                            <input type="number" class="form-control @error('deductions') is-invalid @enderror" name="deductions"
                                value="{{ old('deductions', $payroll->deductions) }}" required>
                            @error('deductions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bonuses" class="form-label">Bonuses</label>
                            <input type="number" class="form-control @error('bonuses') is-invalid @enderror" name="bonuses"
                                value="{{ old('bonuses', $payroll->bonuses) }}" required>
                            @error('bonuses')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="net_salary" class="form-label">Net Salary</label>
                            <input type="number" class="form-control @error('net_salary') is-invalid @enderror" id="net_salary"
                                name="net_salary" value="{{ old('net_salary', $payroll->net_salary) }}" readonly>
                            @error('net_salary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="pay_date" class="form-label">Pay date</label>
                            <input type="date" class="form-control date @error('pay_date') is-invalid @enderror" name="pay_date" value="{{ old('pay_date', $payroll->pay_date) }}"
                                required>
                            @error('pay_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Update</button>
                        <a href="{{ route('payrolls.index') }}" class="btn btn-secondary mt-3">Back to List</a>
                    </form>    
                </div>
            </div>

        </section>
    </div>
@endsection