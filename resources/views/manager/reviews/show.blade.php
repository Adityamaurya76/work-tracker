@extends('layouts.app')

@section('title', 'Review Details | WorkTrack')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Performance Review</h2>
                <p class="text-secondary mb-0">Review for {{ $review->employee?->name ?? 'Unknown' }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('manager.reviews.edit', $review) }}" class="btn btn-outline-primary">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                <a href="{{ route('manager.reviews.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-sm-6 mb-3">
                        <span class="text-secondary small d-block">Employee</span>
                        <span class="fw-semibold">{{ $review->employee?->name ?? '—' }}</span>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <span class="text-secondary small d-block">Reviewer</span>
                        <span>{{ $review->reviewer?->name ?? '—' }}</span>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <span class="text-secondary small d-block">Review Date</span>
                        <span>{{ $review->review_date->format('d M Y') }}</span>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <span class="text-secondary small d-block">Rating</span>
                        <div>
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : ' text-secondary' }} fs-5"></i>
                            @endfor
                            <span class="ms-2 fw-semibold">{{ $review->rating }}/5</span>
                        </div>
                    </div>
                </div>

                <div>
                    <span class="text-secondary small d-block mb-2">Comments</span>
                    <p class="mb-0">{{ $review->comments ?: 'No comments provided.' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
