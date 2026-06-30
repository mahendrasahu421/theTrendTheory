@extends('froentend.layouts.app')

@section('content')
<main class="container" style="padding:40px 16px">
    <h1>Reviews</h1>

    @forelse($reviews as $review)
        <article style="border-bottom:1px solid #eee;padding:18px 0">
            <h2 style="font-size:18px;margin:0 0 6px">{{ $review->title ?? 'Customer Review' }}</h2>
            <p style="margin:0 0 8px;color:#555">{{ $review->comment }}</p>
            <a href="{{ route('reviews.show', $review) }}">Read review</a>
        </article>
    @empty
        <p>No reviews yet.</p>
    @endforelse

    {{ $reviews->links() }}
</main>
@endsection
