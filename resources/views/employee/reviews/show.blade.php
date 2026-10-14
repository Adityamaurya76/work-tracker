@extends('layouts.app')

@section('title', 'Performance Review Details | WorkTrack')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Performance Review Details</h2>
                <p class="text-secondary mb-0">Review dated {{ \Carbon\Carbon::parse($review->review_date)->format('F d, Y') }}</p>
            </div>
            <a href="{{ route('employee.reviews.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Reviews
            </a>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Evaluation Summary</h5>
                <div class="d-flex align-items-center text-warning fs-5">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $review->rating)
                            <i class="bi bi-star-fill"></i>
                        @else
                            <i class="bi bi-star text-muted"></i>
                        @endif
                    @endfor
                    <span class="ms-2 fw-bold text-dark fs-6">({{ $review->rating }} / 5)</span>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="text-secondary small fw-medium d-block">Reviewed By</label>
                        <div class="d-flex align-items-center mt-1">
                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px; font-weight: 600;">
                                {{ strtoupper(substr($review->reviewer->name ?? 'M', 0, 1)) }}
                            </div>
                            <div>
                                <span class="fw-bold">{{ $review->reviewer->name ?? 'Manager' }}</span>
                                <small class="text-secondary d-block">{{ $review->reviewer->email ?? '' }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-secondary small fw-medium d-block">Review Date</label>
                        <span class="fw-bold fs-5 mt-1 d-block">{{ \Carbon\Carbon::parse($review->review_date)->format('M d, Y') }}</span>
                    </div>
                </div>

                <hr class="text-muted">

                <div class="mb-3">
                    <label class="text-secondary small fw-medium d-block mb-2">Manager Feedback & Comments</label>
                    <div class="p-4 bg-light rounded-3">
                        <p class="mb-0 text-dark lh-base" style="white-space: pre-line;">{{ $review->comments }}</p>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white text-secondary small py-3">
                Recorded on {{ $review->created_at->format('M d, Y \a\t h:i A') }}
            </div>
        </div>
    </div>
</div>
@endsection
