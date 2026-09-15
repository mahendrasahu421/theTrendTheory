@extends('froentend.layouts.app')

@section('title', $meta_title ?? $page->title . ' | Vayu')

@push('seo')
    <meta name="description" content="{{ $meta_description ?? '' }}">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">
@endpush

@section('main')
    <main class="container" style="max-width:920px;padding:42px 16px 64px">
        <h1 style="font-family:'Cinzel',serif;font-size:28px;color:#00285a;margin-bottom:22px">
            {{ $page->title }}
        </h1>
        <div style="color:#334155;font-size:15px;line-height:1.85">
            {!! $page->content !!}
        </div>
    </main>
@endsection
