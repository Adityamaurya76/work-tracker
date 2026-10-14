@extends('layouts.app')

@section('title', 'Team Work Updates | WorkTrack')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Team Work Updates</h2>
        <p class="text-secondary mb-0">Review daily progress and blockers reported by employees</p>
    </div>
</div>

{{-- Filters --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('manager.work-updates.index') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label for="employee" class="form-label small fw-semibold">Filter by Employee</label>
                <select name="employee" id="employee" class="form-select">
                    <option value="">All Employees</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->name }} ({{ $emp->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="date" class="form-label small fw-semibold">Filter by Date</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @if(request()->hasAny(['employee', 'date']))
                    <a href="{{ route('manager.work-updates.index') }}" class="btn btn-outline-secondary">
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
                        <th class="ps-4">Date</th>
                        <th>Employee</th>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Work Done & Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($updates as $update)
                        <tr>
                            <td class="ps-4 text-nowrap">
                                <span class="fw-semibold">{{ \Carbon\Carbon::parse($update->work_date)->format('M d, Y') }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.85rem; font-weight: 600;">
                                        {{ strtoupper(substr($update->employee->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $update->employee->name ?? 'Unknown' }}</div>
                                        <small class="text-secondary">{{ $update->employee->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($update->task)
                                    <a href="{{ route('manager.tasks.show', $update->task) }}" class="text-decoration-none fw-semibold">
                                        {{ Str::limit($update->task->title, 35) }}
                                    </a>
                                @else
                                    <span class="text-secondary">General Update</span>
                                @endif
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
                            <td style="max-width: 300px;">
                                <div class="text-truncate fw-medium" title="{{ $update->work_done }}">
                                    {{ $update->work_done }}
                                </div>
                                @if($update->pending_reason)
                                    <div class="small text-danger text-truncate mt-1" title="{{ $update->pending_reason }}">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Blockers: {{ $update->pending_reason }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-secondary">
                                <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                                <p class="mb-0">No daily work updates found matching your criteria.</p>
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
