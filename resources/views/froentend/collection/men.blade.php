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

    <!-- 1 -->
    <div class="col-lg-3 col-md-6">
        <div class="collection-card">
            <div class="collection-img">
                <img src="https://images.unsplash.com/photo-1520975661595-6453be3f7070" alt="">
                <div class="collection-hover-btn">
                    <button class="btn-hover-add">Explore</button>
                </div>
            </div>
            <div class="collection-info">
                <h3>Classic Formal Suit</h3>
                <div class="price">₹12,999</div>
            </div>
        </div>
    </div>

    <!-- 2 -->
    <div class="col-lg-3 col-md-6">
        <div class="collection-card">
            <div class="collection-img">
                <img src="https://images.unsplash.com/photo-1516826957135-700dedea698c" alt="">
                <div class="collection-hover-btn">
                    <button class="btn-hover-add">Explore</button>
                </div>
            </div>
            <div class="collection-info">
                <h3>Slim Fit Shirt</h3>
                <div class="price">₹2,199</div>
            </div>
        </div>
    </div>

    <!-- 3 -->
    <div class="col-lg-3 col-md-6">
        <div class="collection-card">
            <div class="collection-img">
                <img src="https://images.unsplash.com/photo-1490114538077-0a7f8cb49891" alt="">
                <div class="collection-hover-btn">
                    <button class="btn-hover-add">Explore</button>
                </div>
            </div>
            <div class="collection-info">
                <h3>Casual Denim Jacket</h3>
                <div class="price">₹3,999</div>
            </div>
        </div>
    </div>

    <!-- 4 -->
    <div class="col-lg-3 col-md-6">
        <div class="collection-card">
            <div class="collection-img">
                <img src="https://images.unsplash.com/photo-1506629905607-d405b7a9c1d8" alt="">
                <div class="collection-hover-btn">
                    <button class="btn-hover-add">Explore</button>
                </div>
            </div>
            <div class="collection-info">
                <h3>Printed T-Shirt</h3>
                <div class="price">₹1,499</div>
            </div>
        </div>
    </div>

    <!-- 5 -->
    <div class="col-lg-3 col-md-6">
        <div class="collection-card">
            <div class="collection-img">
                <img src="https://images.unsplash.com/photo-1541099649105-f69ad21f3246" alt="">
                <div class="collection-hover-btn">
                    <button class="btn-hover-add">Explore</button>
                </div>
            </div>
            <div class="collection-info">
                <h3>Leather Jacket</h3>
                <div class="price">₹8,499</div>
            </div>
        </div>
    </div>

    <!-- 6 -->
    <div class="col-lg-3 col-md-6">
        <div class="collection-card">
            <div class="collection-img">
                <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b" alt="">
                <div class="collection-hover-btn">
                    <button class="btn-hover-add">Explore</button>
                </div>
            </div>
            <div class="collection-info">
                <h3>Formal Trousers</h3>
                <div class="price">₹2,799</div>
            </div>
        </div>
    </div>

    <!-- 7 -->
    <div class="col-lg-3 col-md-6">
        <div class="collection-card">
            <div class="collection-img">
                <img src="https://images.unsplash.com/photo-1487222477894-8943e31ef7b2" alt="">
                <div class="collection-hover-btn">
                    <button class="btn-hover-add">Explore</button>
                </div>
            </div>
            <div class="collection-info">
                <h3>Casual Hoodie</h3>
                <div class="price">₹2,299</div>
            </div>
        </div>
    </div>

    <!-- 8 -->
    <div class="col-lg-3 col-md-6">
        <div class="collection-card">
            <div class="collection-img">
                <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f" alt="">
                <div class="collection-hover-btn">
                    <button class="btn-hover-add">Explore</button>
                </div>
            </div>
            <div class="collection-info">
                <h3>Winter Overcoat</h3>
                <div class="price">₹9,499</div>
            </div>
        </div>
    </div>

    <!-- 9 -->
    <div class="col-lg-3 col-md-6">
        <div class="collection-card">
            <div class="collection-img">
                <img src="https://images.unsplash.com/photo-1520975916090-3105956dac38" alt="">
                <div class="collection-hover-btn">
                    <button class="btn-hover-add">Explore</button>
                </div>
            </div>
            <div class="collection-info">
                <h3>Sport Tracksuit</h3>
                <div class="price">₹3,699</div>
            </div>
        </div>
    </div>

    <!-- 10 -->
    <div class="col-lg-3 col-md-6">
        <div class="collection-card">
            <div class="collection-img">
                <img src="https://images.unsplash.com/photo-1530845641037-4c0d5d63b1e6" alt="">
                <div class="collection-hover-btn">
                    <button class="btn-hover-add">Explore</button>
                </div>
            </div>
            <div class="collection-info">
                <h3>Casual Sneakers</h3>
                <div class="price">₹4,299</div>
            </div>
        </div>
    </div>

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
                                    <img src="https://images.unsplash.com/photo-1534030347209-467a5b0ad3e6"
                                        alt="Man">
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
