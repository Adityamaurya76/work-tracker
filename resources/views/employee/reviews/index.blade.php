@extends('layouts.app')

@section('title', 'My Performance Reviews | WorkTrack')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">My Performance Reviews</h2>
        <p class="text-secondary mb-0">View feedback, performance ratings, and notes submitted by management</p>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Review Date</th>
                        <th>Reviewer</th>
                        <th>Rating</th>
                        <th>Feedback Summary</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td class="ps-4 text-nowrap fw-semibold">
                                {{ \Carbon\Carbon::parse($review->review_date)->format('M d, Y') }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.85rem; font-weight: 600;">
                                        {{ strtoupper(substr($review->reviewer->name ?? 'M', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $review->reviewer->name ?? 'Manager' }}</div>
                                        <small class="text-secondary">{{ $review->reviewer->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="bi bi-star-fill"></i>
                                        @else
                                            <i class="bi bi-star text-muted"></i>
                                        @endif
                                    @endfor
                                    <span class="ms-2 fw-bold text-dark small">({{ $review->rating }}/5)</span>
                                </div>
                            </td>
                            <td style="max-width: 350px;">
                                <div class="text-truncate text-secondary" title="{{ $review->comments }}">
                                    {{ $review->comments }}
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('employee.reviews.show', $review) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i> View Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-secondary">
                                <i class="bi bi-star-half fs-1 d-block mb-2 text-warning"></i>
                                <p class="mb-0">No performance reviews available yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($reviews->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection
