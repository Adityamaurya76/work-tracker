@extends('layouts.app')

@section('title', 'Submit Daily Work Update | WorkTrack')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Submit Daily Work Update</h2>
                <p class="text-secondary mb-0">Record what you worked on, your progress, and any issues encountered</p>
            </div>
            <a href="{{ route('employee.work-updates.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Updates
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('employee.work-updates.store') }}" method="POST">
                    @csrf

                    {{-- Task Selection --}}
                    <div class="mb-3">
                        <label for="task_id" class="form-label fw-semibold">Task <span class="text-danger">*</span></label>
                        <select name="task_id" id="task_id" class="form-select @error('task_id') is-invalid @enderror" required>
                            <option value="">Select an active task</option>
                            @foreach($tasks as $task)
                                <option value="{{ $task->id }}" {{ (string)old('task_id', request('task_id')) === (string)$task->id ? 'selected' : '' }}>
                                    {{ $task->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('task_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($tasks->isEmpty())
                            <div class="form-text text-warning">
                                <i class="bi bi-info-circle me-1"></i> You currently have no active tasks assigned.
                            </div>
                        @endif
                    </div>

                    {{-- Work Date and Progress Row --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="work_date" class="form-label fw-semibold">Work Date <span class="text-danger">*</span></label>
                            <input type="date"
                                   name="work_date"
                                   id="work_date"
                                   class="form-control @error('work_date') is-invalid @enderror"
                                   value="{{ old('work_date', date('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            @error('work_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="progress_percentage" class="form-label fw-semibold">Task Progress (%) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number"
                                       name="progress_percentage"
                                       id="progress_percentage"
                                       class="form-control @error('progress_percentage') is-invalid @enderror"
                                       min="0"
                                       max="100"
                                       placeholder="e.g. 50"
                                       value="{{ old('progress_percentage', 0) }}"
                                       required>
                                <span class="input-group-text">%</span>
                                @error('progress_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Update Status --}}
                    <div class="mb-3">
                        <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="in_progress" {{ old('status', 'in_progress') === 'in_progress' ? 'selected' : '' }}>In Progress (Work is ongoing)</option>
                            <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed (Task is finished)</option>
                            <option value="blocked" {{ old('status') === 'blocked' ? 'selected' : '' }}>Blocked (Waiting on dependencies or help)</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Work Done --}}
                    <div class="mb-3">
                        <label for="work_done" class="form-label fw-semibold">Work Accomplished <span class="text-danger">*</span></label>
                        <textarea name="work_done"
                                  id="work_done"
                                  rows="4"
                                  class="form-control @error('work_done') is-invalid @enderror"
                                  placeholder="Describe the specific work you completed today..."
                                  required>{{ old('work_done') }}</textarea>
                        @error('work_done')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Pending Reason / Blockers --}}
                    <div class="mb-4">
                        <label for="pending_reason" class="form-label fw-semibold">Roadblocks / Pending Reason <span class="text-muted small fw-normal">(Optional)</span></label>
                        <textarea name="pending_reason"
                                  id="pending_reason"
                                  rows="3"
                                  class="form-control @error('pending_reason') is-invalid @enderror"
                                  placeholder="Mention any blockers, questions, or external dependencies preventing progress...">{{ old('pending_reason') }}</textarea>
                        @error('pending_reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit button --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('employee.work-updates.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary" {{ $tasks->isEmpty() ? 'disabled' : '' }}>
                            <i class="bi bi-send me-1"></i> Submit Work Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
