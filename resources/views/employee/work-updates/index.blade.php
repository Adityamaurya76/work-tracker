@extends('layouts.app')

@section('title', 'My Daily Work Updates | WorkTrack')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">My Daily Work Updates</h2>
        <p class="text-secondary mb-0">Record your daily work progress, completion percentage, and roadblocks</p>
    </div>
    <div>
        <a href="{{ route('employee.work-updates.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Submit New Update
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('employee.work-updates.index') }}" class="row g-3 align-items-end">
            <div class="col-md-9">
                <label for="task_id" class="form-label small fw-semibold">Filter by Task</label>
                <select name="task_id" id="task_id" class="form-select">
                    <option value="">All Tasks</option>
                    @foreach($tasks as $t)
                        <option value="{{ $t->id }}" {{ request('task_id') == $t->id ? 'selected' : '' }}>
                            {{ $t->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @if(request()->filled('task_id'))
                    <a href="{{ route('employee.work-updates.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Updates Table --}}
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Work Date</th>
                        <th>Task</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Work Summary</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($updates as $update)
                        <tr>
                            <td class="ps-4 text-nowrap fw-semibold">
                                {{ \Carbon\Carbon::parse($update->work_date)->format('M d, Y') }}
                            </td>
                            <td>
                                @if($update->task)
                                    <a href="{{ route('employee.tasks.show', $update->task) }}" class="text-decoration-none fw-semibold">
                                        {{ Str::limit($update->task->title, 35) }}
                                    </a>
                                @else
                                    <span class="text-secondary">N/A</span>
                                @endif
                            </td>
                            <td style="min-width: 140px;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 6px;">
                                        <div class="progress-bar {{ $update->progress_percentage == 100 ? 'bg-success' : 'bg-primary' }}"
                                             role="progressbar"
                                             style="width: {{ $update->progress_percentage }}%"></div>
                                    </div>
                                    <span class="small fw-semibold text-secondary">{{ $update->progress_percentage }}%</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'in_progress' => 'bg-primary-subtle text-primary border border-primary-subtle',
                                        'completed'   => 'bg-success-subtle text-success border border-success-subtle',
                                        'blocked'     => 'bg-danger-subtle text-danger border border-danger-subtle',
                                    ];
                                @endphp
                                <span class="badge {{ $statusClasses[$update->status] ?? 'bg-secondary' }} px-2 py-1">
                                    {{ ucwords(str_replace('_', ' ', $update->status)) }}
                                </span>
                            </td>
                            <td style="max-width: 300px;">
                                <div class="text-truncate fw-medium" title="{{ $update->work_done }}">
                                    {{ $update->work_done }}
                                </div>
                                @if($update->pending_reason)
                                    <div class="small text-danger text-truncate mt-1" title="{{ $update->pending_reason }}">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $update->pending_reason }}
                                    </div>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('employee.work-updates.show', $update) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i> Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-secondary">
                                <i class="bi bi-journal-plus fs-1 d-block mb-2"></i>
                                <p class="mb-3">No work updates submitted yet.</p>
                                <a href="{{ route('employee.work-updates.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i> Submit Your First Update
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($updates->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $updates->links() }}
        </div>
    @endif
</div>
@endsection
