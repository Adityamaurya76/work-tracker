@extends('layouts.app')

@section('title', 'Task Details | WorkTrack')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">{{ $task->title }}</h2>
        <p class="text-secondary mb-0">Assigned by {{ $task->assigner->name ?? 'Manager' }} on {{ $task->created_at->format('M d, Y') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('employee.work-updates.create', ['task_id' => $task->id]) }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Submit Work Update
        </a>
        <a href="{{ route('employee.tasks.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Tasks
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- Left column: Task details and history --}}
    <div class="col-lg-8">
        {{-- Description Card --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Task Description</h5>
            </div>
            <div class="card-body">
                @if($task->description)
                    <p class="mb-0 text-secondary" style="white-space: pre-line;">{{ $task->description }}</p>
                @else
                    <p class="text-muted mb-0 fst-italic">No description provided for this task.</p>
                @endif
            </div>
        </div>

        {{-- Work Updates for this task --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Daily Work Updates</h5>
                <a href="{{ route('employee.work-updates.create', ['task_id' => $task->id]) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus-circle me-1"></i> Log Update
                </a>
            </div>
            <div class="card-body p-0">
                @if($task->workUpdates && $task->workUpdates->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Date</th>
                                    <th>Status</th>
                                    <th>Progress</th>
                                    <th>Work Summary</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($task->workUpdates as $update)
                                    <tr>
                                        <td class="ps-3 text-nowrap fw-semibold">
                                            {{ \Carbon\Carbon::parse($update->work_date)->format('M d, Y') }}
                                        </td>
                                        <td>
                                            @php
                                                $updateStatusClasses = [
                                                    'in_progress' => 'bg-primary-subtle text-primary',
                                                    'completed'   => 'bg-success-subtle text-success',
                                                    'blocked'     => 'bg-danger-subtle text-danger',
                                                ];
                                            @endphp
                                            <span class="badge {{ $updateStatusClasses[$update->status] ?? 'bg-secondary' }}">
                                                {{ ucwords(str_replace('_', ' ', $update->status)) }}
                                            </span>
                                        </td>
                                        <td>{{ $update->progress_percentage }}%</td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 250px;" title="{{ $update->work_done }}">
                                                {{ $update->work_done }}
                                            </div>
                                            @if($update->pending_reason)
                                                <small class="text-danger d-block">Blocker: {{ $update->pending_reason }}</small>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-center text-secondary">
                        <i class="bi bi-journal-x fs-2 d-block mb-1"></i>
                        No daily work updates logged yet for this task.
                    </div>
                @endif
            </div>
        </div>

        {{-- Status Change History --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Status History</h5>
            </div>
            <div class="card-body">
                @if($task->statusHistories && $task->statusHistories->count() > 0)
                    <div class="timeline">
                        @foreach($task->statusHistories->sortByDesc('created_at') as $history)
                            <div class="d-flex mb-3 pb-3 border-bottom last-border-0">
                                <div class="me-3">
                                    <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                        <i class="bi bi-arrow-left-right text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-semibold">
                                        Status changed from
                                        <span class="badge bg-secondary-subtle text-secondary">{{ ucwords(str_replace('_', ' ', $history->old_status ?? 'Created')) }}</span>
                                        to
                                        <span class="badge bg-primary-subtle text-primary">{{ ucwords(str_replace('_', ' ', $history->new_status)) }}</span>
                                    </div>
                                    <small class="text-secondary">
                                        By {{ $history->changedBy->name ?? 'User' }} &bull; {{ $history->created_at->format('M d, Y h:i A') }}
                                    </small>
                                    @if($history->comment)
                                        <p class="small text-muted mb-0 mt-1 fst-italic">{{ $history->comment }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-secondary mb-0">No status changes recorded yet.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Right column: Status update form and task metadata --}}
    <div class="col-lg-4">
        {{-- Status Update Card --}}
        <div class="card shadow-sm mb-4 border-primary">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-pencil-square me-1"></i> Update Status</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('employee.tasks.update-status', $task) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="status" class="form-label fw-semibold">Current Status</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="todo" {{ old('status', $task->status) === 'todo' ? 'selected' : '' }}>To Do</option>
                            <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check2-circle me-1"></i> Save Status
                    </button>
                </form>
            </div>
        </div>

        {{-- Meta Info Card --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Task Information</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                    <li class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary">Priority</span>
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
                    </li>

                    <li class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary">Assigned By</span>
                        <span class="fw-semibold">{{ $task->assigner->name ?? 'Manager' }}</span>
                    </li>

                    <li class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary">Due Date</span>
                        @php
                            $isOverdue = $task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status !== 'completed';
                        @endphp
                        <span class="{{ $isOverdue ? 'text-danger fw-bold' : 'fw-semibold' }}">
                            {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'No due date' }}
                            @if($isOverdue)
                                <i class="bi bi-exclamation-circle text-danger ms-1" title="Overdue"></i>
                            @endif
                        </span>
                    </li>

                    @if($task->completed_at)
                        <li class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary">Completed At</span>
                            <span class="text-success fw-semibold">
                                {{ \Carbon\Carbon::parse($task->completed_at)->format('M d, Y h:i A') }}
                            </span>
                        </li>
                    @endif

                    <li class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary">Created Date</span>
                        <span>{{ $task->created_at->format('M d, Y') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
