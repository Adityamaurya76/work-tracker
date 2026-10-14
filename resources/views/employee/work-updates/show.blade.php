@extends('layouts.app')

@section('title', 'Work Update Details | WorkTrack')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Work Update Details</h2>
                <p class="text-secondary mb-0">Logged for {{ \Carbon\Carbon::parse($workUpdate->work_date)->format('F d, Y') }}</p>
            </div>
            <a href="{{ route('employee.work-updates.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Updates
            </a>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Summary</h5>
                @php
                    $statusClasses = [
                        'in_progress' => 'bg-primary-subtle text-primary border border-primary-subtle',
                        'completed'   => 'bg-success-subtle text-success border border-success-subtle',
                        'blocked'     => 'bg-danger-subtle text-danger border border-danger-subtle',
                    ];
                @endphp
                <span class="badge {{ $statusClasses[$workUpdate->status] ?? 'bg-secondary' }} px-3 py-2 fs-6">
                    {{ ucwords(str_replace('_', ' ', $workUpdate->status)) }}
                </span>
            </div>
            <div class="card-body p-4">
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="text-secondary small fw-medium d-block">Associated Task</label>
                        @if($workUpdate->task)
                            <a href="{{ route('employee.tasks.show', $workUpdate->task) }}" class="fw-bold text-decoration-none fs-5">
                                {{ $workUpdate->task->title }}
                            </a>
                        @else
                            <span class="text-secondary fw-semibold">No associated task</span>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label class="text-secondary small fw-medium d-block">Progress Reported</label>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <div class="progress flex-grow-1" style="height: 10px;">
                                <div class="progress-bar {{ $workUpdate->progress_percentage == 100 ? 'bg-success' : 'bg-primary' }}"
                                     role="progressbar"
                                     style="width: {{ $workUpdate->progress_percentage }}%"></div>
                            </div>
                            <span class="fw-bold">{{ $workUpdate->progress_percentage }}%</span>
                        </div>
                    </div>
                </div>

                <hr class="text-muted">

                <div class="mb-4">
                    <label class="text-secondary small fw-medium d-block mb-2">Work Accomplished</label>
                    <div class="p-3 bg-light rounded-3">
                        <p class="mb-0 text-dark" style="white-space: pre-line;">{{ $workUpdate->work_done }}</p>
                    </div>
                </div>

                @if($workUpdate->pending_reason)
                    <div class="mb-3">
                        <label class="text-danger small fw-medium d-block mb-2">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Roadblocks / Issues Encountered
                        </label>
                        <div class="p-3 bg-danger-subtle rounded-3 text-danger border border-danger-subtle">
                            <p class="mb-0" style="white-space: pre-line;">{{ $workUpdate->pending_reason }}</p>
                        </div>
                    </div>
                @endif
            </div>
            <div class="card-footer bg-white text-secondary small py-3">
                Submitted on {{ $workUpdate->created_at->format('M d, Y \a\t h:i A') }}
            </div>
        </div>
    </div>
</div>
@endsection
