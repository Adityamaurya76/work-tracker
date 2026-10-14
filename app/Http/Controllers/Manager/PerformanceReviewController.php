<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\PerformanceReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceReviewController extends Controller
{
    /**
     * Display a listing of all performance reviews.
     */
    public function index()
    {
        $reviews = PerformanceReview::with(['employee', 'reviewer'])
            ->latest('review_date')
            ->paginate(10);

        return view('manager.reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new review.
     */
    public function create()
    {
        $employees = User::where('role', 'employee')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('manager.reviews.create', compact('employees'));
    }

    /**
     * Store a newly created review.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'integer', 'exists:users,id'],
            'review_date' => ['required', 'date'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comments' => ['nullable', 'string', 'max:5000'],
        ]);

        $validated['reviewer_id'] = Auth::id();

        PerformanceReview::create($validated);

        return redirect()
            ->route('manager.reviews.index')
            ->with('success', 'Performance review created successfully.');
    }

    /**
     * Display the specified review.
     */
    public function show(PerformanceReview $review)
    {
        $review->load(['employee', 'reviewer']);

        return view('manager.reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified review.
     */
    public function edit(PerformanceReview $review)
    {
        $employees = User::where('role', 'employee')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('manager.reviews.edit', compact('review', 'employees'));
    }

    /**
     * Update the specified review.
     */
    public function update(Request $request, PerformanceReview $review)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'integer', 'exists:users,id'],
            'review_date' => ['required', 'date'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comments' => ['nullable', 'string', 'max:5000'],
        ]);

        $review->update($validated);

        return redirect()
            ->route('manager.reviews.index')
            ->with('success', 'Performance review updated successfully.');
    }

    /**
     * Remove the specified review.
     */
    public function destroy(PerformanceReview $review)
    {
        $review->delete();

        return redirect()
            ->route('manager.reviews.index')
            ->with('success', 'Performance review deleted successfully.');
    }
}
