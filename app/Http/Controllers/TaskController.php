<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Employee;

class TaskController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Task::class);

        $user = Auth::user();
        if ($user->role_id === 1 || $user->role_id === 2) {
            $tasks = Task::all();
        } else {
            $tasks = Task::where('assigned_to', $user->employee_id)->get();
        }

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $this->authorize('create', Task::class);
        $employees = Employee::all();
        return view('tasks.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Task::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:tasks,title',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:employees,id',
            'due_date' => 'required|date',
            'status' => 'required|string',
        ]);

        Task::create($validated);
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $employees = Employee::all();
        return view('tasks.edit', compact('task', 'employees'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:tasks,title,' . $task->id,
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:employees,id',
            'due_date' => 'required|date',
            'status' => 'required|string',
        ]);

        $task->update($validated);
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function done(int $id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('update', $task);
        $task->update(['status' => 'done']);
        return redirect()->route('tasks.index')->with('success', 'Task marked as done.');
    }

    public function pending(int $id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('update', $task);
        $task->update(['status' => 'pending']);
        return redirect()->route('tasks.index')->with('success', 'Task marked as pending.');
    }
}