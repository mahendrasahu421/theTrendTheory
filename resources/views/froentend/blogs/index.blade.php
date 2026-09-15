@extends('froentend.layouts.app')

@push('seo')
    <title>{{ $pageTitle }} — Vayu</title>
    <meta name="description" content="Explore fashion trends, style inspiration, designer editorials, and brand announcements from Vayu India.">
    <link rel="canonical" href="{{ route('blogs.index') }}">
@endpush

@section('main')
    <div class="journal-page-wrapper py-5" style="background: #faf8f5; min-height: 100vh;">
        <div class="container">

            {{-- Journal Hero Title --}}
            <div class="text-center max-w-700 mx-auto mb-5">
                <span class="badge px-3 py-2 rounded-pill text-uppercase fw-semibold mb-2" 
                      style="background: rgba(0, 40, 90, 0.08); color: #00285a; letter-spacing: 0.14em; font-size: 0.75rem;">
                    <i class="bi bi-journal-text me-1"></i> THE TREND JOURNAL
                </span>
                <h1 class="display-5 fw-bold text-dark mb-3 text-uppercase" style="font-family: 'Cinzel', serif; letter-spacing: 0.04em;">
                    Stories, Trends &amp; News
                </h1>
                <p class="text-secondary fs-6 mb-0 mx-auto" style="max-width: 580px;">
                    Curated fashion insights, styling masterclasses, and official updates from the creative studio at Vayu.
                </p>

                {{-- Type Tabs: All / Blogs / News --}}
                <div class="d-inline-flex align-items-center gap-1 p-1 bg-white rounded-pill shadow-xs border mt-4">
                    <a href="{{ route('blogs.index') }}" 
                       class="px-4 py-2 rounded-pill text-decoration-none fw-semibold fs-13 transition-all {{ $type === 'all' ? 'bg-dark text-white shadow-sm' : 'text-secondary hover-dark' }}">
                        All Stories
                    </a>
                    <a href="{{ route('blogs.index', ['type' => 'blog']) }}" 
                       class="px-4 py-2 rounded-pill text-decoration-none fw-semibold fs-13 transition-all {{ $type === 'blog' ? 'bg-dark text-white shadow-sm' : 'text-secondary hover-dark' }}">
                        Style &amp; Trends
                    </a>
                    <a href="{{ route('blogs.index', ['type' => 'news']) }}" 
                       class="px-4 py-2 rounded-pill text-decoration-none fw-semibold fs-13 transition-all {{ $type === 'news' ? 'bg-dark text-white shadow-sm' : 'text-secondary hover-dark' }}">
                        Brand &amp; News
                    </a>
                </div>
            </div>

            {{-- Search & Category Filter Bar --}}
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-5 p-3 rounded-4 bg-white border shadow-xs">
                {{-- Category Pills Slider --}}
                <div class="d-flex align-items-center gap-2 overflow-auto py-1 scrollbar-none flex-grow-1" style="white-space: nowrap;">
                    <a href="{{ route('blogs.index', array_merge(request()->except('category', 'page'), ['category' => ''])) }}" 
                       class="btn btn-sm rounded-pill px-3 fw-medium {{ empty($category) ? 'btn-dark' : 'btn-outline-secondary' }}" style="font-size: 0.8rem;">
                        All Categories
                    </a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('blogs.index', array_merge(request()->except('category', 'page'), ['category' => $cat])) }}" 
                           class="btn btn-sm rounded-pill px-3 fw-medium {{ $category === $cat ? 'btn-dark' : 'btn-outline-secondary' }}" style="font-size: 0.8rem;">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>

                {{-- Search Box --}}
                <form method="GET" action="{{ route('blogs.index') }}" class="d-flex align-items-center gap-2" style="min-width: 260px;">
                    @if (request('type'))
                        <input type="hidden" name="type" value="{{ request('type') }}">
                    @endif
                    @if (request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="input-group input-group-sm">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search articles..." 
                               class="form-control rounded-start-pill border-end-0 px-3">
                        <button class="btn btn-dark rounded-end-pill px-3" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Featured Hero Article (if present and on default view) --}}
            @if ($featuredPost && empty($search) && empty($category) && $type === 'all')
                <div class="featured-hero-card mb-5 rounded-4 overflow-hidden bg-white border shadow-sm position-relative">
                    <div class="row g-0 align-items-center">
                        <div class="col-lg-7">
                            <div class="featured-img-box position-relative" style="height: 420px;">
                                <img src="{{ $featuredPost->image_url ?? asset('images/og-default.jpg') }}" 
                                     alt="{{ $featuredPost->title }}" 
                                     class="w-100 h-100 object-fit-cover">
                                <span class="badge bg-danger position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill fs-12 text-uppercase tracking-wider">
                                    <i class="bi bi-star-fill me-1"></i> Featured Story
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="p-4 p-xl-5">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="badge bg-dark-subtle text-dark fs-12 px-3 py-1 rounded-pill">{{ $featuredPost->category }}</span>
                                    <span class="text-muted fs-12">&bull;</span>
                                    <span class="text-muted fs-12"><i class="bi bi-clock me-1"></i>{{ $featuredPost->read_time }}</span>
                                </div>
                                <h2 class="fw-bold text-dark fs-3 mb-3 lh-sm">
                                    <a href="{{ route($featuredPost->type === 'news' ? 'news.show' : 'blogs.show', $featuredPost->slug) }}" class="text-dark text-decoration-none hover-primary">
                                        {{ $featuredPost->title }}
                                    </a>
                                </h2>
                                <p class="text-secondary fs-14 mb-4 lh-base">
                                    {{ $featuredPost->summary }}
                                </p>
                                <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-sm rounded-circle bg-dark text-white d-flex align-items-center justify-content-center fw-bold" style="width: 34px; height: 34px; font-size: 11px;">
                                            TT
                                        </div>
                                        <div>
                                            <span class="d-block text-dark fw-bold fs-13">{{ $featuredPost->author_name }}</span>
                                            <small class="text-muted fs-11">{{ $featuredPost->published_at ? $featuredPost->published_at->format('M d, Y') : 'Recent' }}</small>
                                        </div>
                                    </div>
                                    <a href="{{ route($featuredPost->type === 'news' ? 'news.show' : 'blogs.show', $featuredPost->slug) }}" class="btn btn-dark rounded-pill px-4 py-2 fs-13 fw-semibold">
                                        Read Article <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Main Articles Grid --}}
            <div class="row g-4 mb-5">
                @forelse ($posts as $post)
                    <div class="col-lg-4 col-md-6 d-flex">
                        <article class="journal-card w-100 rounded-4 bg-white border overflow-hidden d-flex flex-column transition-all shadow-hover">
                            {{-- Cover Thumbnail --}}
                            <div class="journal-thumb-wrap position-relative overflow-hidden" style="height: 220px;">
                                <a href="{{ route($post->type === 'news' ? 'news.show' : 'blogs.show', $post->slug) }}">
                                    <img src="{{ $post->image_url ?? asset('images/og-default.jpg') }}" 
                                         alt="{{ $post->title }}" 
                                         class="w-100 h-100 object-fit-cover transition-transform" 
                                         loading="lazy">
                                </a>
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-white text-dark shadow-xs fs-11 px-2 py-1 rounded-pill">
                                        {{ $post->category }}
                                    </span>
                                </div>
                                @if ($post->type === 'news')
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge bg-primary text-white shadow-xs fs-11 px-2 py-1 rounded-pill">
                                            News
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Card Body --}}
                            <div class="p-4 d-flex flex-column flex-fill">
                                <div class="d-flex align-items-center gap-2 mb-2 text-muted fs-12">
                                    <span><i class="bi bi-calendar3 me-1"></i>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Recent' }}</span>
                                    <span>&bull;</span>
                                    <span><i class="bi bi-clock me-1"></i>{{ $post->read_time }}</span>
                                </div>

                                <h3 class="fs-5 fw-bold text-dark mb-2 lh-base">
                                    <a href="{{ route($post->type === 'news' ? 'news.show' : 'blogs.show', $post->slug) }}" class="text-dark text-decoration-none hover-primary">
                                        {{ $post->title }}
                                    </a>
                                </h3>

                                <p class="text-secondary fs-14 mb-4 lh-base flex-fill">
                                    {{ $post->summary ?: Str::limit(strip_tags($post->content), 110) }}
                                </p>

                                {{-- Footer Byline --}}
                                <div class="pt-3 border-top d-flex align-items-center justify-content-between mt-auto">
                                    <span class="text-dark fw-semibold fs-12">By {{ $post->author_name }}</span>
                                    <a href="{{ route($post->type === 'news' ? 'news.show' : 'blogs.show', $post->slug) }}" class="text-dark fw-bold fs-12 text-decoration-none hover-primary">
                                        Read More &rarr;
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="p-5 rounded-4 bg-white border max-w-500 mx-auto">
                            <i class="bi bi-journal-x fs-1 text-muted d-block mb-3"></i>
                            <h4 class="fw-bold text-dark">No Articles Found</h4>
                            <p class="text-muted fs-14 mb-3">We couldn't find any articles matching your search or category filter.</p>
                            <a href="{{ route('blogs.index') }}" class="btn btn-dark rounded-pill px-4 py-2">
                                View All Articles
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($posts->hasPages())
                <div class="d-flex justify-content-center mb-5">
                    {{ $posts->links() }}
                </div>
            @endif

        </div>
    </div>

    <style>
        .shadow-hover {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .journal-thumb-wrap img {
            transition: transform 0.4s ease;
        }
        .scrollbar-none::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-none {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endsection
