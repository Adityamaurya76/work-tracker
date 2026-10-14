@extends('layouts.app')

@section('title', 'Edit Review | WorkTrack')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Edit Performance Review</h2>
                <p class="text-secondary mb-0">Update review for {{ $review->employee?->name ?? 'Unknown' }}</p>
            </div>
            <a href="{{ route('manager.reviews.index') }}" class="btn btn-outline-secondary">
                Back to Reviews
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('manager.reviews.update', $review) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employee</label>
                        <select name="employee_id" id="employee_id"
                                class="form-select @error('employee_id') is-invalid @enderror" required>
                            <option value="">Select employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" @selected(old('employee_id', $review->employee_id) == $employee->id)>
                                    {{ $employee->name }} ({{ $employee->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="review_date" class="form-label">Review Date</label>
                            <input type="date" name="review_date" id="review_date"
                                   value="{{ old('review_date', $review->review_date->format('Y-m-d')) }}"
                                   class="form-control @error('review_date') is-invalid @enderror" required>
                            @error('review_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="rating" class="form-label">Rating (1-5)</label>
                            <select name="rating" id="rating"
                                    class="form-select @error('rating') is-invalid @enderror" required>
                                <option value="">Select rating</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" @selected(old('rating', $review->rating) == $i)>
                                        {{ $i }} - {{ ['Poor', 'Below Average', 'Average', 'Good', 'Excellent'][$i - 1] }}
                                    </option>
                                @endfor
                            </select>
                            @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="comments" class="form-label">Comments</label>
                        <textarea name="comments" id="comments" rows="4"
                                  class="form-control @error('comments') is-invalid @enderror"
                                  placeholder="Provide feedback">{{ old('comments', $review->comments) }}</textarea>
                        @error('comments')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Update Review
                        </button>
                        <a href="{{ route('manager.reviews.index') }}" class="btn btn-light border">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
