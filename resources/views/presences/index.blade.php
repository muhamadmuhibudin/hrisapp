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
                    <h3>Roles</h3>
                    <p class="text-subtitle text-muted">Handle Presence data</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Presence</li>
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
                        Data Presence
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <a href="{{ route('presences.create') }}" class="btn btn-primary mb-3 ms-auto">New Presence</a>
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
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Date</th>
                                <th>Status</th>
                                @if(in_array(Auth::user()->employee?->role?->title, ['Super Admin', 'HR Manager']))
                                <th>Option</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($presences as $presence)
                                <tr>
                                    <td>{{ $presence->employee->fullname }}</td>
                                    <td>{{ $presence->check_in }}</td>
                                    <td>{{ $presence->check_out }}</td>
                                    <td>{{ $presence->date }}</td>
                                    <td>
                                        @if($presence->status == 'present')
                                            <span class="badge bg-success">{{ ucfirst($presence->status) }}</span>
                                        @elseif($presence->status == 'absent')
                                            <span class="badge bg-danger">{{ ucfirst($presence->status) }}</span>
                                        @endif
                                    </td>

                                    <td class="d-flex justify-content-between gap-1">
                                        <div>
                                            @if(in_array(Auth::user()->employee?->role?->title, ['Super Admin', 'HR Manager']))
                                            <a href="{{ route('presences.edit', $presence->id) }}" class="btn btn-sm btn-warning">Edit</a>

                                            <form action="{{ route('presences.destroy', $presence->id) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger btn-delete">Delete</button>
                                            </form>
                                            @endif
                                        </div>
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