@extends('layouts.app')

@section('title', 'Task Details | WorkTrack')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">{{ $task->title }}</h2>
        <p class="text-secondary mb-0">Task #{{ $task->id }} &middot; Created {{ $task->created_at->format('d M Y, h:i A') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('manager.tasks.edit', $task) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('manager.tasks.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Description</h5>
                <p class="mb-0">{{ $task->description ?: 'No description provided.' }}</p>
            </div>
        </div>

        @if($task->statusHistories->count())
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Status History</h5>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Changed By</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($task->statusHistories->sortByDesc('created_at') as $history)
                                <tr>
                                    <td>{{ $history->created_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                        @if($history->old_status)
                                            <span class="badge text-bg-secondary">{{ ucfirst(str_replace('_', ' ', $history->old_status)) }}</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td><span class="badge text-bg-primary">{{ ucfirst(str_replace('_', ' ', $history->new_status)) }}</span></td>
                                    <td>{{ $history->changedBy?->name ?? '—' }}</td>
                                    <td>{{ $history->comment ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if($task->workUpdates->count())
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Work Updates</h5>
                @foreach($task->workUpdates->sortByDesc('work_date') as $update)
                    <div class="border rounded p-3 mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>{{ $update->employee?->name ?? 'Unknown' }}</strong>
                            <span class="text-secondary small">{{ $update->work_date->format('d M Y') }}</span>
                        </div>
                        <p class="mb-1"><strong>Work Done:</strong> {{ $update->work_done }}</p>
                        @if($update->pending_reason)
                            <p class="mb-1 text-danger"><strong>Blockers:</strong> {{ $update->pending_reason }}</p>
                        @endif
                        <div class="d-flex gap-3 small text-secondary">
                            <span>Progress: {{ $update->progress_percentage }}%</span>
                            <span>Status: {{ ucfirst(str_replace('_', ' ', $update->status)) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Details</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <span class="text-secondary small d-block">Status</span>
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
                    </li>
                    <li class="mb-3">
                        <span class="text-secondary small d-block">Priority</span>
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
                    </li>
                    <li class="mb-3">
                        <span class="text-secondary small d-block">Assigned To</span>
                        <span class="fw-semibold">{{ $task->employee?->name ?? '—' }}</span>
                    </li>
                    <li class="mb-3">
                        <span class="text-secondary small d-block">Assigned By</span>
                        <span>{{ $task->assigner?->name ?? '—' }}</span>
                    </li>
                    <li class="mb-3">
                        <span class="text-secondary small d-block">Due Date</span>
                        @if($task->due_date)
                            <span class="{{ $task->status !== 'completed' && $task->due_date->isPast() ? 'text-danger fw-semibold' : '' }}">
                                {{ $task->due_date->format('d M Y') }}
                                @if($task->status !== 'completed' && $task->due_date->isPast())
                                    <span class="badge text-bg-danger ms-1">Overdue</span>
                                @endif
                            </span>
                        @else
                            —
                        @endif
                    </li>
                    @if($task->completed_at)
                    <li class="mb-3">
                        <span class="text-secondary small d-block">Completed At</span>
                        <span>{{ $task->completed_at->format('d M Y, h:i A') }}</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
