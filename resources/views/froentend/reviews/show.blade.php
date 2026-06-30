@extends('froentend.layouts.app')

@section('content')
<main class="container" style="padding:40px 16px">
    <h1>{{ $review->title ?? 'Customer Review' }}</h1>
    <p>{{ $review->comment }}</p>
    <p>{{ $review->rating }}/5 by {{ $review->reviewer_name ?? 'Customer' }}</p>
</main>
@endsection
