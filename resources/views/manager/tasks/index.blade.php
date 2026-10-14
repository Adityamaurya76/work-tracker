@extends('layouts.app')

@section('title', 'Manage Tasks | WorkTrack')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Task Management</h2>
        <p class="text-secondary mb-0">
            Manage and assign employee tasks.
        </p>
    </div>

    <a href="{{ route('manager.tasks.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Create Task
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('manager.tasks.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="search" class="form-label small">Search</label>
                <input type="text" name="search" id="search" class="form-control"
                       value="{{ request('search') }}" placeholder="Search tasks...">
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label small">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="todo" @selected(request('status') === 'todo')>Todo</option>
                    <option value="in_progress" @selected(request('status') === 'in_progress')>In Progress</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="priority" class="form-label small">Priority</label>
                <select name="priority" id="priority" class="form-select">
                    <option value="">All Priorities</option>
                    <option value="low" @selected(request('priority') === 'low')>Low</option>
                    <option value="medium" @selected(request('priority') === 'medium')>Medium</option>
                    <option value="high" @selected(request('priority') === 'high')>High</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="employee" class="form-label small">Employee</label>
                <select name="employee" id="employee" class="form-select">
                    <option value="">All Employees</option>
                    @foreach ($employees as $emp)
                        <option value="{{ $emp->id }}" @selected(request('employee') == $emp->id)>
                            {{ $emp->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('manager.tasks.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Task</th>
                        <th>Employee</th>
                        <th>Priority</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $task->title }}</div>
                                <small class="text-secondary">
                                    {{ \Illuminate\Support\Str::limit($task->description, 60) }}
                                </small>
                            </td>
                            <td>{{ $task->employee?->name ?? '—' }}</td>
                            <td>
                                @php
                                    $priorityClass = match ($task->priority) {
                                        'high' => 'danger',
                                        'medium' => 'warning',
                                        default => 'success',
                                    };
                                @endphp
                                <span class="badge text-bg-{{ $priorityClass }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </td>
                            <td>
                                @if($task->due_date)
                                    <span class="{{ $task->status !== 'completed' && $task->due_date->isPast() ? 'text-danger fw-semibold' : '' }}">
                                        {{ $task->due_date->format('d M Y') }}
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = match ($task->status) {
                                        'completed' => 'success',
                                        'in_progress' => 'primary',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge text-bg-{{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('manager.tasks.show', $task) }}"
                                       class="btn btn-outline-primary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('manager.tasks.edit', $task) }}"
                                       class="btn btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('manager.tasks.destroy', $task) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this task?')"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-secondary"></i>
                                <h6 class="mt-2">No tasks found</h6>
                                <p class="text-secondary">Create your first task to get started.</p>
                                <a href="{{ route('manager.tasks.create') }}"
                                   class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-lg me-1"></i> Create Task
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $tasks->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
