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
                    <h3>Employee</h3>
                    <p class="text-subtitle text-muted">Handle employee data</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Employee</li>
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
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif    
                    <form action="{{ route('employees.update', $employee->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="" class="form-label">Fullname</label>
                            <input type="text" class="form-control @error('fullname') is-invalid @enderror" name="fullname" value="{{ old('fullname', $employee->fullname) }}" required>
                            @error('fullname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Email</label>
                            <input type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email', $employee->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Phone Number</label>
                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number"
                                value="{{ old('phone_number', $employee->phone_number) }}" required>
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Address</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror">{{ old('address', $employee->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Birth Date</label>
                            <input type="text" class="form-control date @error('birth_date') is-invalid @enderror" name="birth_date"
                                value="{{ old('birth_date', $employee->birth_date) }}" required>
                            @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Hire Date</label>
                            <input type="text" class="form-control date @error('hire_date') is-invalid @enderror" name="hire_date"
                                value="{{ old('hire_date', $employee->hire_date) }}" required>
                            @error('hire_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Department</label>
                            <select name="department_id" class="form-control @error('department_id') is-invalid @enderror">
                                <option value="">Select a Department</option>
                            @foreach($departments as $department)    
                                <option value="{{ $department->id }}" 
                                    @if(old('department_id', $employee->department_id) == $department->id) selected @endif>{{ $department->name }}</option>
                            @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Role</label>
                            <select name="role_id" class="form-control @error('role_id') is-invalid @enderror">
                                <option value="">Select a Role</option>
                            @foreach($roles as $role)    
                                <option value="{{ $role->id }}" 
                                @if(old('role_id', $employee->role_id) == $role->id) selected @endif >{{ $role->title }}</option>
                            @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Status</label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="inactive" {{ $employee->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="active" {{ $employee->status == 'active' ? 'selected' : '' }}>Active</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Salary</label>
                            <input type="number" class="form-control @error('salary') is-invalid @enderror" name="salary"
                                value="{{ old('salary', $employee->salary) }}" required>
                            @error('salary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Submit Employee</button>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary mt-3">Back to List</a>
                    </form>    
                </div>
            </div>

        </section>
    </div>
@endsection