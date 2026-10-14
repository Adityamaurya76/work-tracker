@extends('layouts.app')

@section('title', 'Performance Reviews | WorkTrack')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Performance Reviews</h2>
        <p class="text-secondary mb-0">Manage employee performance reviews.</p>
    </div>
    <a href="{{ route('manager.reviews.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> New Review
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Employee</th>
                        <th>Review Date</th>
                        <th>Rating</th>
                        <th>Comments</th>
                        <th>Reviewer</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td class="fw-semibold">{{ $review->employee?->name ?? '—' }}</td>
                            <td>{{ $review->review_date->format('d M Y') }}</td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : ' text-secondary' }}"></i>
                                @endfor
                                <span class="ms-1 small text-secondary">({{ $review->rating }}/5)</span>
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($review->comments, 50) ?: '—' }}</td>
                            <td>{{ $review->reviewer?->name ?? '—' }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('manager.reviews.show', $review) }}" class="btn btn-outline-primary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('manager.reviews.edit', $review) }}" class="btn btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('manager.reviews.destroy', $review) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this review?')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-clipboard-x fs-1 text-secondary"></i>
                                <p class="text-secondary mt-2 mb-0">No performance reviews yet.</p>
                                <a href="{{ route('manager.reviews.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="bi bi-plus-lg me-1"></i> Create Review
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $reviews->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
