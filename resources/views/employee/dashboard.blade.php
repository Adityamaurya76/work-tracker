@extends('layouts.app')

@section('title', 'Employee Dashboard | WorkTrack')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Employee Dashboard</h2>
        <p class="text-secondary mb-0">Welcome back, {{ auth()->user()->name }}! Here's a summary of your assigned work.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('employee.work-updates.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Submit Daily Update
        </a>
        <a href="{{ route('employee.tasks.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-list-task me-1"></i> View All Tasks
        </a>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium">Total Tasks</span>
                    <h3 class="fw-bold mb-0 mt-1">{{ $stats->total ?? 0 }}</h3>
                </div>
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-journal-check"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium">Pending (To-Do)</span>
                    <h3 class="fw-bold mb-0 mt-1">{{ $stats->pending ?? 0 }}</h3>
                </div>
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium">In Progress</span>
                    <h3 class="fw-bold mb-0 mt-1">{{ $stats->in_progress ?? 0 }}</h3>
                </div>
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium">Completed</span>
                    <h3 class="fw-bold mb-0 mt-1 text-success">{{ $stats->completed ?? 0 }}</h3>
                </div>
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

@if(($stats->overdue ?? 0) > 0)
    <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
        <div>
            You have <strong>{{ $stats->overdue }}</strong> overdue task{{ $stats->overdue > 1 ? 's' : '' }}. Please review and update your progress.
        </div>
    </div>
@endif

{{-- Recent Assigned Tasks --}}
<div class="card shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Recent Assigned Tasks</h5>
        <a href="{{ route('employee.tasks.index') }}" class="btn btn-sm btn-link text-decoration-none">
            View All <i class="bi bi-chevron-right"></i>
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Task</th>
                        <th>Assigned By</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTasks as $task)
                        @php
                            $isOverdue = $task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status !== 'completed';
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <a href="{{ route('employee.tasks.show', $task) }}" class="fw-semibold text-decoration-none">
                                    {{ $task->title }}
                                </a>
                                @if($task->description)
                                    <div class="small text-secondary text-truncate" style="max-width: 320px;">
                                        {{ $task->description }}
                                    </div>
                                @endif
                            </td>
                            <td>{{ $task->assigner->name ?? 'Manager' }}</td>
                            <td>
                                @php
                                    $priorityClasses = [
                                        'low'    => 'bg-secondary-subtle text-secondary',
                                        'medium' => 'bg-info-subtle text-info',
                                        'high'   => 'bg-danger-subtle text-danger',
                                    ];
                                @endphp
                                <span class="badge {{ $priorityClasses[$task->priority] ?? 'bg-secondary' }} px-2 py-1">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'todo'        => 'bg-secondary-subtle text-secondary',
                                        'in_progress' => 'bg-primary-subtle text-primary',
                                        'completed'   => 'bg-success-subtle text-success',
                                    ];
                                @endphp
                                <span class="badge {{ $statusClasses[$task->status] ?? 'bg-secondary' }} px-2 py-1">
                                    {{ ucwords(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </td>
                            <td>
                                <span class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}">
                                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'No due date' }}
                                    @if($isOverdue)
                                        <i class="bi bi-exclamation-circle text-danger ms-1" title="Overdue"></i>
                                    @endif
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('employee.tasks.show', $task) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i> Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-secondary">
                                <i class="bi bi-check2-circle fs-1 d-block mb-2 text-success"></i>
                                <p class="mb-0">No tasks currently assigned to you. Great job!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
