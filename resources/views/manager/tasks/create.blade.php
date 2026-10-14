
@extends('layouts.app')

@section('title', 'Create Task | WorkTrack')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Create Task</h2>
                <p class="text-secondary mb-0">
                    Assign work to an employee.
                </p>
            </div>

            <a href="{{ route('manager.tasks.index') }}"
               class="btn btn-outline-secondary">
                Back to Tasks
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('manager.tasks.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Task Title</label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}"
                            placeholder="Enter task title"
                            required
                        >
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea
                            name="description"
                            id="description"
                            rows="4"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Describe the task"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">
                            Assign Employee
                        </label>
                        <select
                            name="assigned_to"
                            id="assigned_to"
                            class="form-select @error('assigned_to') is-invalid @enderror"
                            required
                        >
                            <option value="">Select employee</option>
                            @foreach ($employees as $employee)
                                <option
                                    value="{{ $employee->id }}"
                                    @selected(old('assigned_to') == $employee->id)
                                >
                                    {{ $employee->name }} ({{ $employee->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="priority" class="form-label">Priority</label>
                            <select
                                name="priority"
                                id="priority"
                                class="form-select @error('priority') is-invalid @enderror"
                                required
                            >
                                <option value="low" @selected(old('priority') === 'low')>Low</option>
                                <option value="medium" @selected(old('priority', 'medium') === 'medium')>Medium</option>
                                <option value="high" @selected(old('priority') === 'high')>High</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input
                                type="date"
                                name="due_date"
                                id="due_date"
                                min="{{ now()->toDateString() }}"
                                value="{{ old('due_date') }}"
                                class="form-control @error('due_date') is-invalid @enderror"
                                required
                            >
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            Assign Task
                        </button>

                        <a href="{{ route('manager.tasks.index') }}"
                           class="btn btn-light border">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection