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
                        @can('create', App\Models\Task::class)
                            <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3 ms-auto">New Task</a>
                        @endcan
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
                                <th>Title</th>
                                <th>Assigned to</th>
                                <th>Due date</th>
                                <th>Status</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
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
                                    <td>
                                        <div class="d-flex justify-content-between gap-2">
                                            <div class="d-flex gap-1">

                                                @can('view', $task)
                                                    <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-sm btn-info">View</a>
                                                @endcan

                                                @if($task->status == 'pending')
                                                    <a href="{{ route('tasks.done', $task->id) }}" class="btn btn-sm btn-success">Mark as Done</a>
                                                @else
                                                    <a href="{{ route('tasks.pending', $task->id) }}" class="btn btn-sm btn-warning">Mark as Pending</a>
                                                @endif
                                            </div>

                                            <div class="d-flex gap-1">
                                                @can('update', $task)
                                                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                @endcan

                                                @can('delete', $task)
                                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger btn-delete">Delete</button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-gray-500 py-10">
                                        No tasks available.
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