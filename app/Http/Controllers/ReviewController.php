<?php

namespace App\Http\Controllers;

use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('froentend.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        abort_unless($review->is_active, 404);

        return view('froentend.reviews.show', compact('review'));
    }
}
