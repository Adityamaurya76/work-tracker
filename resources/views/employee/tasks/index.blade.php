@extends('layouts.app')

@section('title', 'My Tasks | WorkTrack')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">My Assigned Tasks</h2>
        <p class="text-secondary mb-0">Track and update the status of your assigned tasks</p>
    </div>
</div>

{{-- Filters --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('employee.tasks.index') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label for="status" class="form-label small fw-semibold">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="todo" {{ request('status') === 'todo' ? 'selected' : '' }}>To Do</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="col-md-5">
                <label for="priority" class="form-label small fw-semibold">Priority</label>
                <select name="priority" id="priority" class="form-select">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @if(request()->hasAny(['status', 'priority']))
                    <a href="{{ route('employee.tasks.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Tasks Table --}}
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Title</th>
                        <th>Assigned By</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th>Quick Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                        @php
                            $isOverdue = $task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status !== 'completed';
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <a href="{{ route('employee.tasks.show', $task) }}" class="fw-semibold text-decoration-none">
                                    {{ $task->title }}
                                </a>
                                @if($task->description)
                                    <div class="small text-secondary text-truncate" style="max-width: 280px;">
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
                                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'None' }}
                                    @if($isOverdue)
                                        <i class="bi bi-exclamation-circle text-danger ms-1" title="Overdue"></i>
                                    @endif
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('employee.tasks.update-status', $task) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm" style="width: auto; min-width: 120px;" onchange="this.form.submit()">
                                        <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>To Do</option>
                                        <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </form>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('employee.tasks.show', $task) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i> Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-secondary">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                <p class="mb-0">No tasks found matching your filter criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($tasks->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $tasks->links() }}
        </div>
    @endif
</div>
@endsection
