@extends('froentend.layouts.app')

@push('seo')
    <title>{{ $post->title }} — Vayu</title>
    <meta name="description" content="{{ $post->summary }}">
    <link rel="canonical" href="{{ route($post->type === 'news' ? 'news.show' : 'blogs.show', $post->slug) }}">
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ $post->summary }}">
    <meta property="og:image" content="{{ $post->image_url ?? asset('images/og-default.jpg') }}">
    <meta property="og:type" content="article">
    <meta name="author" content="{{ $post->author_name }}">
    @isset($articleSchema)
        <script type="application/ld+json">{!! $articleSchema !!}</script>
    @endisset
@endpush

@section('main')
    <article class="journal-single-wrapper py-5" style="background: #ffffff; min-height: 100vh;">
        <div class="container" style="max-width: 900px;">

            {{-- Breadcrumbs --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb fs-13 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-muted text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}" class="text-muted text-decoration-none">Journal</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blogs.index', ['category' => $post->category]) }}" class="text-muted text-decoration-none">{{ $post->category }}</a></li>
                    <li class="breadcrumb-item active text-dark fw-medium text-truncate" style="max-width: 250px;" aria-current="page">{{ $post->title }}</li>
                </ol>
            </nav>

            {{-- Article Header --}}
            <header class="mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge bg-dark text-white px-3 py-1 rounded-pill fs-12 text-uppercase">
                        {{ $post->category }}
                    </span>
                    @if ($post->type === 'news')
                        <span class="badge bg-primary text-white px-3 py-1 rounded-pill fs-12 text-uppercase">
                            News / Press
                        </span>
                    @endif
                    <span class="text-muted fs-13">&bull;</span>
                    <span class="text-muted fs-13"><i class="bi bi-clock me-1"></i>{{ $post->read_time }}</span>
                </div>

                <h1 class="display-5 fw-bold text-dark mb-3 lh-sm" style="font-family: 'Cinzel', serif; letter-spacing: 0.02em;">
                    {{ $post->title }}
                </h1>

                @if (!empty($post->summary))
                    <p class="lead text-secondary fs-5 mb-4 lh-base" style="font-weight: 400;">
                        {{ $post->summary }}
                    </p>
                @endif

                {{-- Author & Publish Date Row --}}
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 py-3 border-top border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar rounded-circle bg-dark text-white d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px; font-size: 14px;">
                            {{ strtoupper(substr($post->author_name, 0, 2)) }}
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark fs-14">{{ $post->author_name }}</h6>
                            <span class="text-muted fs-12">
                                Published on {{ $post->published_at ? $post->published_at->format('F d, Y') : $post->created_at->format('F d, Y') }}
                            </span>
                        </div>
                    </div>

                    {{-- Quick Share Actions --}}
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted fs-12 fw-semibold me-1">Share:</span>
                        <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" class="btn btn-sm btn-light rounded-circle p-2" title="Share on WhatsApp" style="width: 34px; height: 34px;">
                            <i class="bi bi-whatsapp text-success"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-sm btn-light rounded-circle p-2" title="Share on X / Twitter" style="width: 34px; height: 34px;">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                        <button type="button" onclick="navigator.clipboard.writeText(window.location.href); alert('Link copied to clipboard!');" class="btn btn-sm btn-light rounded-circle p-2" title="Copy link" style="width: 34px; height: 34px;">
                            <i class="bi bi-link-45deg"></i>
                        </button>
                    </div>
                </div>
            </header>

            {{-- Cover Image --}}
            @if ($post->image_url)
                <div class="mb-5 rounded-4 overflow-hidden shadow-sm" style="max-height: 480px;">
                    <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-100 h-100 object-fit-cover">
                </div>
            @endif

            {{-- Main Content Body --}}
            <div class="journal-content-body mb-5 fs-6 lh-lg text-dark" style="color: #1e293b;">
                {!! nl2br($post->content) !!}
            </div>

            {{-- Tags & Keywords --}}
            @if (!empty($post->tags_list))
                <div class="d-flex align-items-center flex-wrap gap-2 pt-4 border-top mb-5">
                    <span class="fw-bold fs-13 text-dark me-2"><i class="bi bi-tags me-1"></i> Tags:</span>
                    @foreach ($post->tags_list as $tag)
                        <a href="{{ route('blogs.index', ['search' => $tag]) }}" class="badge bg-light text-dark border px-3 py-2 rounded-pill text-decoration-none fs-12 fw-medium hover-dark">
                            #{{ $tag }}
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Related Reads Section --}}
            @if ($relatedPosts->isNotEmpty())
                <div class="related-reads-section pt-5 border-top mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h4 class="fw-bold text-dark mb-0" style="font-family: 'Cinzel', serif;">Related Stories</h4>
                        <a href="{{ route('blogs.index') }}" class="text-dark fw-bold fs-13 text-decoration-none">View All &rarr;</a>
                    </div>

                    <div class="row g-4">
                        @foreach ($relatedPosts as $rel)
                            <div class="col-md-4">
                                <div class="card h-100 border rounded-4 overflow-hidden shadow-xs hover-card">
                                    @if ($rel->image_url)
                                        <div style="height: 140px; overflow: hidden;">
                                            <img src="{{ $rel->image_url }}" alt="{{ $rel->title }}" class="w-100 h-100 object-fit-cover">
                                        </div>
                                    @endif
                                    <div class="p-3 d-flex flex-column flex-fill">
                                        <span class="badge bg-light text-dark border align-self-start mb-2 fs-10">{{ $rel->category }}</span>
                                        <h6 class="fw-bold mb-2 lh-base">
                                            <a href="{{ route($rel->type === 'news' ? 'news.show' : 'blogs.show', $rel->slug) }}" class="text-dark text-decoration-none">
                                                {{ Str::limit($rel->title, 55) }}
                                            </a>
                                        </h6>
                                        <small class="text-muted fs-11 mt-auto">{{ $rel->read_time }} &bull; {{ $rel->published_at ? $rel->published_at->format('M d') : 'Recent' }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Back to Journal Button --}}
            <div class="text-center py-4">
                <a href="{{ route('blogs.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-2 fs-13 fw-bold">
                    <i class="bi bi-arrow-left me-1"></i> Back to All Articles
                </a>
            </div>

        </div>
    </article>

    <style>
        .journal-content-body h2, .journal-content-body h3, .journal-content-body h4 {
            font-family: 'Cinzel', serif;
            font-weight: 700;
            color: #00285a;
            margin-top: 1.8rem;
            margin-bottom: 0.9rem;
        }
        .journal-content-body p {
            margin-bottom: 1.4rem;
            line-height: 1.8;
            font-size: 1.05rem;
            color: #334155;
        }
        .journal-content-body blockquote {
            border-left: 3px solid #00285a;
            padding: 1rem 1.5rem;
            margin: 1.8rem 0;
            background: #f8fafc;
            border-radius: 0 12px 12px 0;
            font-style: italic;
            color: #00285a;
            font-size: 1.1rem;
        }
        .journal-content-body ul, .journal-content-body ol {
            margin-bottom: 1.4rem;
            padding-left: 1.5rem;
        }
        .journal-content-body li {
            margin-bottom: 0.5rem;
            line-height: 1.65;
        }
        .hover-card {
            transition: all 0.2s ease;
        }
    </style>
@endsection
