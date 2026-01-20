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
                    <h3>Tasks</h3>
                    <p class="text-subtitle text-muted">Handle employee task</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Tasks</li>
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
                        <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3 ms-auto">New Task</a>
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Assigned to</th>
                                <th>Due date</th>
                                <th>Status</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                                <tr>
                                    <td>{{ $task->title }}</td>
                                    <td>{{ $task->employee->fullname }}</td>
                                    <td>{{ $task->due_date }}</td>
                                    <td>
                                        @if($task->status == 'pending')
                                            <span class="badge bg-warning">{{ $task->status }}</span>
                                        @elseif($task->status == 'done')
                                            <span class="badge bg-success">{{ $task->status }}</span>
                                        @else
                                            <span class="badge bg-info">{{ $task->status }}</span>
                                        @endif
                                    </td>
                                    <td class="d-flex justify-content-between gap-1">
                                        <div>
                                            <a href="" class="btn btn-sm btn-info">View</a>

                                            @if($task->status == 'pending')
                                                <a href="" class="btn btn-sm btn-success">Mark as done</a>
                                            @else
                                                <a href="" class="btn btn-sm btn-warning">Mark as Pending</a>
                                            @endif
                                        </div>

                                        <div>
                                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-warning">Edit</a>

                                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger btn-delete">Delete</button>
                                            </form>
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