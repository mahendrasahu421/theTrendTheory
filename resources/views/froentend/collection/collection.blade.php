@extends(theme_view('layouts.app'))
@section('main')
    <!-- Navbar -->


    <!-- ===== WOMEN'S COLLECTION SLIDER ===== -->
    <section class="container collection-slider-section">
        <div class="container-fluid position-relative px-0">

            <div class="collection-header text-center">
                <h2>{{ $title }}</h2>
                <p>{{ $titleContent }}</p>
            </div>

            <div class="collection-container">
                <div class="row g-4">
                    @if ($category->childrencategory && $category->childrencategory->isNotEmpty())
                        @foreach ($category->childrencategory as $child)
                            <div class="col-lg-3 col-md-6">
                                <div class="collection-card">

                                    <div class="collection-img">
                                        @php
                                            $image = $child->images->first();
                                        @endphp

                                        <img src="{{ optional($image)->image_url ?? asset('default.jpg') }}"
                                            alt="{{ $child->slug_name }}">

                                        <div class="collection-hover-btn">
                                            <a
                                                href="{{ route('collection.child', [$category->slug_name, $child->slug_name]) }}">
                                                <button class="btn-hover-add">Explore</button>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="collection-info">
                                        <h3>{{ ucwords(str_replace('-', ' ', $child->slug_name)) }}</h3>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>No subcategories found.</p>
                    @endif
                </div>
            </div>

        </div>
    </section>

    <!-- ===== NEW IN - LIFESTYLE SLIDER ===== -->
    <section class="collection-slider-section">
        <div class="container-fluid position-relative px-0">
            <div class="collection-header text-center">
                <h2>NEW IN</h2>
                <p>fresh arrivals · styled on real people</p>
            </div>

            <div class="slider-container-collection">
                <button class="collection-arrow collection-arrow-left" id="newinArrowLeft"><i
                        class="bi bi-chevron-left"></i></button>
                <button class="collection-arrow collection-arrow-right" id="newinArrowRight"><i
                        class="bi bi-chevron-right"></i></button>

                <div class="collection-slider-wrapper" id="newinSliderWrapper">
                    <div class="collection-track" id="newinSliderTrack">
                        <!-- Lifestyle 1 - Man -->
                        <div class="collection-slide">
                            <div class="lifestyle-card">
                                <div class="lifestyle-img">
                                    <img src="https://images.unsplash.com/photo-1534030347209-467a5b0ad3e6"
                                        alt="Man in casual wear">
                                    <div class="lifestyle-hover-btn">
                                        <button class="btn-hover-add">SHOP LOOK</button>
                                    </div>
                                </div>
                                <div class="lifestyle-info">
                                    <h3>urban street</h3>
                                    <p>oversized hoodie · cargo pants</p>
                                </div>
                            </div>
                        </div>

                        <!-- Lifestyle 2 - Woman -->
                        <div class="collection-slide">
                            <div class="lifestyle-card">
                                <div class="lifestyle-img">
                                    <img src="https://images.unsplash.com/photo-1551232864-3f0890e580d9"
                                        alt="Woman in dress">
                                    <div class="lifestyle-hover-btn">
                                        <button class="btn-hover-add">SHOP LOOK</button>
                                    </div>
                                </div>
                                <div class="lifestyle-info">
                                    <h3>evening elegance</h3>
                                    <p>satin slip dress · heels</p>
                                </div>
                            </div>
                        </div>

                        <!-- Lifestyle 3 - Couple -->
                        <div class="collection-slide">
                            <div class="lifestyle-card">
                                <div class="lifestyle-img">
                                    <img src="https://images.unsplash.com/photo-1529333166437-7750a6dd5a70"
                                        alt="Couple in denim">
                                    <div class="lifestyle-hover-btn">
                                        <button class="btn-hover-add">SHOP LOOK</button>
                                    </div>
                                </div>
                                <div class="lifestyle-info">
                                    <h3>denim duo</h3>
                                    <p>jeans · jackets · for both</p>
                                </div>
                            </div>
                        </div>

                        <!-- Lifestyle 4 - Group -->
                        <div class="collection-slide">
                            <div class="lifestyle-card">
                                <div class="lifestyle-img">
                                    <img src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1"
                                        alt="Group fashion">
                                    <div class="lifestyle-hover-btn">
                                        <button class="btn-hover-add">SHOP LOOK</button>
                                    </div>
                                </div>
                                <div class="lifestyle-info">
                                    <h3>weekend edit</h3>
                                    <p>casual · chic · streetwear</p>
                                </div>
                            </div>
                        </div>

                        <!-- Duplicates -->
                        <div class="collection-slide">
                            <div class="lifestyle-card">
                                <div class="lifestyle-img">
                                    <img src="https://images.unsplash.com/photo-1534030347209-467a5b0ad3e6" alt="Man">
                                    <div class="lifestyle-hover-btn">
                                        <button class="btn-hover-add">SHOP LOOK</button>
                                    </div>
                                </div>
                                <div class="lifestyle-info">
                                    <h3>urban street</h3>
                                    <p>oversized hoodie · cargo pants</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="collection-footer">
                <a href="#" class="section-btn">EXPLORE NEW ARRIVALS <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </section>
@endsection
