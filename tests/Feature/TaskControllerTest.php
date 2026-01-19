<?php

use App\Models\User;
use App\Models\Employee;
use App\Models\Task;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;

uses(WithFaker::class);

beforeEach(function () {
    // Ensure migrations run and we have an authenticated user when needed
});

it('lists tasks on index', function () {
    // Arrange
    $employee = Employee::factory()->create();
    $tasks = Task::factory()->count(3)->create([
        'assigned_to' => $employee->id,
    ]);

    // Act
    $response = $this->get('/tasks');

    // Assert
    $response->assertStatus(200);
    foreach ($tasks as $task) {
        $response->assertSee($task->title);
    }
});

it('shows create form with employees', function () {
    // Arrange
    $employees = Employee::factory()->count(2)->create();

    // Act
    $response = $this->get('/tasks/create');

    // Assert
    $response->assertStatus(200);
    foreach ($employees as $e) {
        $response->assertSee((string)$e->fullname);
    }
});

it('validates required fields on store', function () {
    $response = $this->post('/tasks', []);

    $response->assertSessionHasErrors(['title', 'assigned_to', 'due_date', 'status']);
});

it('creates a task with valid data and redirects', function () {
    // Arrange
    $employee = Employee::factory()->create();

    $payload = [
        'title' => 'Prepare report',
        'description' => 'Quarterly financial report',
        'assigned_to' => $employee->id,
        'due_date' => now()->addDay()->toDateString(),
        'status' => 'pending',
    ];

    // Act
    $response = $this->post('/tasks', $payload);

    // Assert
    $response->assertRedirect(route('tasks.index'));
    $this->assertDatabaseHas('tasks', [
        'title' => 'Prepare report',
        'assigned_to' => $employee->id,
        'status' => 'pending',
    ]);
});

it('rejects store when assigned_to does not exist', function () {
    // Arrange
    $payload = [
        'title' => 'Invalid assignment',
        'description' => null,
        'assigned_to' => 999999,
        'due_date' => now()->addDay()->toDateString(),
        'status' => 'pending',
    ];

    // Act
    $response = $this->post('/tasks', $payload);

    // Assert
    $response->assertSessionHasErrors(['assigned_to']);
});

it('allows nullable description on store', function () {
    $employee = Employee::factory()->create();

    $payload = [
        'title' => 'No description',
        'description' => null,
        'assigned_to' => $employee->id,
        'due_date' => now()->addDay()->toDateString(),
        'status' => 'pending',
    ];

    $response = $this->post('/tasks', $payload);

    $response->assertRedirect(route('tasks.index'));
    $this->assertDatabaseHas('tasks', [
        'title' => 'No description',
        'description' => null,
    ]);
});
