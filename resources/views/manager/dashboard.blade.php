@extends('layouts.app')

@section('title', 'Manager Dashboard | WorkTrack')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold mb-1">Manager Dashboard</h2>
    <p class="text-secondary mb-0">Overview of tasks, employees, and activity.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-list-task"></i>
                </div>
                <div>
                    <p class="text-secondary mb-0 small">Total Tasks</p>
                    <h4 class="fw-bold mb-0">{{ $stats->total ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-person-check"></i>
                </div>
                <div>
                    <p class="text-secondary mb-0 small">Assigned</p>
                    <h4 class="fw-bold mb-0">{{ $stats->assigned ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-clock"></i>
                </div>
                <div>
                    <p class="text-secondary mb-0 small">Pending</p>
                    <h4 class="fw-bold mb-0">{{ $stats->pending ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div>
                    <p class="text-secondary mb-0 small">In Progress</p>
                    <h4 class="fw-bold mb-0">{{ $stats->in_progress ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <p class="text-secondary mb-0 small">Completed</p>
                    <h4 class="fw-bold mb-0">{{ $stats->completed ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div>
                    <p class="text-secondary mb-0 small">Overdue</p>
                    <h4 class="fw-bold mb-0">{{ $stats->overdue ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <p class="text-secondary mb-0 small">Employees</p>
                    <h4 class="fw-bold mb-0">{{ $totalEmployees }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Recent Tasks</h5>
            <a href="{{ route('manager.tasks.index') }}" class="btn btn-outline-primary btn-sm">
                View All Tasks
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Task</th>
                        <th>Employee</th>
                        <th>Priority</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentTasks as $task)
                        <tr>
                            <td>
                                <a href="{{ route('manager.tasks.show', $task) }}" class="text-decoration-none fw-semibold">
                                    {{ $task->title }}
                                </a>
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
                            <td>{{ $task->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-secondary"></i>
                                <p class="text-secondary mt-2 mb-0">No tasks yet. Create your first task to get started.</p>
                                <a href="{{ route('manager.tasks.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="bi bi-plus-lg me-1"></i> Create Task
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
