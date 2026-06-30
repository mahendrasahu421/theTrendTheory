<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @stack('seo')
    <title>{{ $meta_title ?? 'THE TREND THEORY' }}</title>
    <meta name="description" content="{{ $meta_description ?? 'Shop latest fashion.' }}">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $meta_title ?? 'THE TREND THEORY' }}">
    <meta property="og:description" content="{{ $meta_description ?? '' }}">
    <meta property="og:image" content="{{ $og_image ?? asset('images/og-default.jpg') }}">
    <meta name="theme-color" content="#00285A" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @isset($schema)
        <script type="application/ld+json">{!! $schema !!}</script>
    @endisset
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet"
        media="print" onload="this.media='all'" />
    <link href="{{ asset('frontend/style.css') }}" rel="stylesheet" />
</head>

<body>

    {{-- OFFER BAR --}}
    <div class="offer-bar">
        <div class="offer-track">
            Extra 10% OFF — Use Code NEW10 | Min Cart ₹999 &nbsp;|&nbsp; Free Shipping above ₹999 🚚
        </div>
    </div>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container position-relative">

            {{-- Mobile toggle --}}
            <button class="navbar-toggler border-0 d-lg-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNav" aria-label="Menu">
                <i class="bi bi-list fs-4" style="color:#00285a"></i>
            </button>

            {{-- LEFT: Nav Links --}}
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav gap-lg-4">
                    {{-- Simple parent categories only --}}
                    @php
                        $navCats = \App\Models\Category::where('is_active', true)
                            ->whereNull('parent_id')
                            ->where('show_in_nav', true)
                            ->orderBy('sort_order')
                            ->get();
                    @endphp
                    @foreach ($navCats as $navCat)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('shop.category', $navCat->slug) }}" target="_blank">
                                {{ $navCat->name }}
                            </a>
                        </li>
                    @endforeach
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shop.new-arrivals') }}" target="_blank">New In</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shop.category', 'sale') }}"
                            style="color:#ff3f6c!important;font-weight:700" target="_blank">Sale</a>
                    </li>
                </ul>
            </div>

            {{-- CENTER: Brand --}}
            <div class="brand-center">
                <a href="{{ route('home') }}" class="brand-text">THE TREND THEORY</a>
            </div>

            {{-- RIGHT: Icons --}}
            <div class="icon-group ms-auto d-flex align-items-center gap-3">
                <a href="{{ url('/search') }}" class="nav-icon-link" aria-label="Search">
                    <i class="bi bi-search"></i>
                </a>
                @auth
                    <a href="{{ url('/wishlist') }}" class="nav-icon-link" ><i class="bi bi-heart"></i></a>
                @else
                    <a href="{{ url('/login') }}" class="nav-icon-link"><i class="bi bi-heart"></i></a>
                @endauth
                <a href="{{ url('/cart') }}" class="nav-icon-link position-relative">
                    <i class="bi bi-bag"></i>
                    <span id="cart-count"
                        style="position:absolute;top:-6px;right:-8px;background:#ff3f6c;color:#fff;
                             font-size:10px;font-weight:700;width:18px;height:18px;border-radius:50%;
                             display:flex;align-items:center;justify-content:center;line-height:1">
                        {{ session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0 }}
                    </span>
                </a>
                @auth
                    <a href="{{ url('/profile') }}" class="nav-icon-link"><i class="bi bi-person"></i></a>
                @else
                    <a href="{{ url('/login') }}" class="nav-icon-link"><i class="bi bi-person"></i></a>
                @endauth
            </div>

        </div>
    </nav>

    @yield('main')

    {{-- FOOTER --}}
    <footer class="footer-section">
        <div class="container">
            <div class="footer-top">
                <div class="footer-logo">THE TREND THEORY</div>
                <div class="footer-social">
                    <a href="https://instagram.com/thetrendtheory" target="_blank" rel="noopener"
                        aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="https://facebook.com/thetrendtheory" target="_blank" rel="noopener"
                        aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="https://twitter.com/thetrendtheory" target="_blank" rel="noopener"
                        aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                    <a href="https://youtube.com/@thetrendtheory" target="_blank" rel="noopener"
                        aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
            <div class="trust-badges-section">
                <div class="trust-item"><i class="bi bi-truck"></i><span>Free Shipping on ₹999+</span></div>
                <div class="trust-item"><i class="bi bi-arrow-return-left"></i><span>15-Day Returns</span></div>
                <div class="trust-item"><i class="bi bi-shield-check"></i><span>100% Secure Payments</span></div>
            </div>
            <div class="footer-grid">
                <div class="footer-col">
                    <h4>SHOP</h4>
                    <ul>
                        <li><a href="{{ route('shop.category', 'men') }}">Men's Collection</a></li>
                        <li><a href="{{ route('shop.category', 'women') }}">Women's Collection</a></li>
                        <li><a href="{{ route('shop.new-arrivals') }}">New Arrivals</a></li>
                        <li><a href="{{ route('shop.category', 'sale') }}">Sale</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>HELP</h4>
                    <ul>
                        <li><a href="/pages/faqs">FAQs</a></li>
                        <li><a href="/pages/shipping-returns">Shipping & Returns</a></li>
                        <li><a href="/pages/track-order">Track Order</a></li>
                        <li><a href="/pages/size-guide">Size Guide</a></li>
                        <li><a href="/pages/contact">Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>ABOUT</h4>
                    <ul>
                        <li><a href="/pages/our-story">Our Story</a></li>
                        <li><a href="/pages/sustainability">Sustainability</a></li>
                        <li><a href="/blog">Blog</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>NEWSLETTER</h4>
                    <p>Subscribe for exclusive updates & offers</p>
                    <form class="newsletter-box" action="{{ route('newsletter.subscribe') }}" method="POST">
                        @csrf
                        <input type="email" name="email" placeholder="Your email address" required>
                        <button type="submit" class="footer-submit">→</button>
                    </form>
                    <div class="payment-icons">
                        <i class="bi bi-credit-card"></i>
                        <i class="bi bi-paypal"></i>
                        <i class="bi bi-google"></i>
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                    <div class="trust-badge"><i class="bi bi-shield-lock"></i> Secure Checkout</div>
                </div>
            </div>
            <div class="popular-searches">
                <h4>POPULAR SEARCHES</h4>
                <div class="searches-links">
                    <a href="{{ route('shop.category', 'mens-jeans') }}">Men's Jeans</a> |
                    <a href="{{ route('shop.category', 'womens-dresses') }}">Women Dresses</a> |
                    <a href="{{ route('shop.category', 'mens-tshirts') }}">Oversized T-Shirts</a> |
                    <a href="{{ route('shop.category', 'mens-cargo') }}">Cargo Pants</a> |
                    <a href="{{ route('shop.category', 'mens-hoodies') }}">Hoodies</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© {{ date('Y') }} THE TREND THEORY. All rights reserved.</p>
                <div class="footer-bottom-links">
                    <a href="/pages/privacy-policy" >Privacy Policy</a>
                    <a href="/pages/terms-of-use">Terms of Use</a>
                    <a href="{{ url('/sitemap.xml') }}">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- PRODUCT QUICK VIEW SLIDER --}}
    <div class="slider-overlay"></div>
    <div class="universal-slider" id="universalSlider">
        <div class="slider-header">
            <span id="sliderTitle">SELECT OPTIONS</span>
            <button class="close-slider" onclick="closeSlider()">✕</button>
        </div>
        <div class="slider-body">
            <div class="product-view" id="productView">
                <div class="product-left">
                    <img id="mainProductImg" src="" class="main-product-img" alt="Product">
                    <div class="product-images-wrapper" id="productGallery"></div>
                </div>
                <div class="product-right">
                    <h2 class="product-title" id="sliderProductName">Loading...</h2>
                    <div class="price-row">
                        <span class="new-price" id="sliderPrice"></span>
                        <span class="old-price" id="sliderOldPrice" style="display:none"></span>
                        <span class="save-tag" id="sliderDiscount" style="display:none"></span>
                    </div>
                    <p class="shipping">Shipping calculated at checkout.</p>
                    <div class="size-section" id="sliderSizeSection">
                        <h4>SIZE <span class="selected-size" id="selectedSize">-</span></h4>
                        <div class="size-grid" id="sizeGrid"></div>
                    </div>
                    <div class="action-section">
                        <div class="qty-box">
                            <button id="sliderQtyMinus">−</button>
                            <span class="qty-value" id="sliderQty">1</span>
                            <button id="sliderQtyPlus">+</button>
                        </div>
                        <button class="add-cart-btn" id="sliderAddToCart">ADD TO CART</button>
                    </div>
                    <button class="buy-now-btn" id="sliderBuyNow">BUY IT NOW</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Slider
        var currentProductId = null;

        function openSlider(productId) {
            currentProductId = productId;
            document.getElementById('universalSlider').classList.add('active');
            document.querySelector('.slider-overlay').classList.add('active');
            document.body.style.overflow = 'hidden';
            if (productId) loadProductInSlider(productId);
        }

        function closeSlider() {
            document.getElementById('universalSlider').classList.remove('active');
            document.querySelector('.slider-overlay').classList.remove('active');
            document.body.style.overflow = '';
        }

        function loadProductInSlider(id) {
            document.getElementById('sliderProductName').textContent = 'Loading...';
            fetch('/api/v1/products/' + id, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(data) {
                    if (!data.success) return;
                    var p = data.data;
                    document.getElementById('sliderProductName').textContent = p.name;
                    document.getElementById('sliderPrice').textContent = '₹' + Number(p.price).toLocaleString('en-IN');
                    if (p.original_price && p.original_price > p.price) {
                        var d = Math.round(((p.original_price - p.price) / p.original_price) * 100);
                        document.getElementById('sliderOldPrice').textContent = '₹' + Number(p.original_price)
                            .toLocaleString('en-IN');
                        document.getElementById('sliderOldPrice').style.display = 'inline';
                        document.getElementById('sliderDiscount').textContent = 'SAVE ' + d + '%';
                        document.getElementById('sliderDiscount').style.display = 'inline';
                    }
                    document.getElementById('mainProductImg').src = p.image;
                    var gallery = document.getElementById('productGallery');
                    gallery.innerHTML = '';
                    var imgs = (p.gallery && p.gallery.length) ? p.gallery : [p.image];
                    imgs.forEach(function(img) {
                        var el = document.createElement('img');
                        el.src = img;
                        el.alt = p.name;
                        el.onclick = function() {
                            document.getElementById('mainProductImg').src = img;
                        };
                        gallery.appendChild(el);
                    });
                    var sizeGrid = document.getElementById('sizeGrid');
                    sizeGrid.innerHTML = '';
                    if (p.sizes && p.sizes.length) {
                        document.getElementById('sliderSizeSection').style.display = 'block';
                        p.sizes.forEach(function(size) {
                            var s = document.createElement('span');
                            s.className = 'size-option';
                            s.textContent = size;
                            s.onclick = function() {
                                document.querySelectorAll('#sizeGrid .size-option').forEach(function(x) {
                                    x.classList.remove('active');
                                });
                                s.classList.add('active');
                                document.getElementById('selectedSize').textContent = size;
                            };
                            sizeGrid.appendChild(s);
                        });
                        if (sizeGrid.firstChild) {
                            sizeGrid.firstChild.classList.add('active');
                            document.getElementById('selectedSize').textContent = p.sizes[0];
                        }
                    } else {
                        document.getElementById('sliderSizeSection').style.display = 'none';
                    }
                });
        }
        document.getElementById('sliderAddToCart').addEventListener('click', function() {
            if (!currentProductId) return;
            var size = document.getElementById('selectedSize').textContent;
            var qty = parseInt(document.getElementById('sliderQty').textContent);
            fetch('/cart/add/' + currentProductId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        quantity: qty,
                        size: size
                    })
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(data) {
                    if (data.success) {
                        var b = document.getElementById('cart-count');
                        if (b) b.textContent = data.cart_count;
                        closeSlider();
                    } else if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                });
        });
        document.getElementById('sliderQtyMinus').addEventListener('click', function() {
            var el = document.getElementById('sliderQty');
            if (parseInt(el.textContent) > 1) el.textContent = parseInt(el.textContent) - 1;
        });
        document.getElementById('sliderQtyPlus').addEventListener('click', function() {
            var el = document.getElementById('sliderQty');
            el.textContent = parseInt(el.textContent) + 1;
        });
        document.querySelector('.slider-overlay').addEventListener('click', closeSlider);
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.open-product-slider');
            if (btn) {
                e.preventDefault();
                openSlider(btn.dataset.productId);
            }
        });

        // Sliders
        (function() {
            function makeSlider(trackId, wrapperId, leftId, rightId, slideW) {
                var track = document.getElementById(trackId);
                var wrapper = document.getElementById(wrapperId);
                var btnL = document.getElementById(leftId);
                var btnR = document.getElementById(rightId);
                if (!track || !wrapper) return;
                var orig = Array.from(track.children);
                var count = orig.length;
                if (count === 0) return;
                for (var c = 0; c < count * 3; c++) track.appendChild(orig[c % count].cloneNode(true));
                var x = 0,
                    timer = null,
                    step = 1.2;
                var loopAt = slideW * count * 2;

                function tick() {
                    x -= step;
                    if (Math.abs(x) >= loopAt) x += loopAt;
                    track.style.transform = 'translateX(' + x + 'px)';
                }

                function start() {
                    if (!timer) timer = setInterval(tick, 20);
                }

                function stop() {
                    clearInterval(timer);
                    timer = null;
                }
                start();
                wrapper.addEventListener('mouseenter', stop);
                wrapper.addEventListener('mouseleave', start);
                if (btnL) btnL.addEventListener('click', function() {
                    stop();
                    x += slideW;
                    track.style.transform = 'translateX(' + x + 'px)';
                    setTimeout(start, 400);
                });
                if (btnR) btnR.addEventListener('click', function() {
                    stop();
                    x -= slideW;
                    track.style.transform = 'translateX(' + x + 'px)';
                    setTimeout(start, 400);
                });
            }
            document.addEventListener('DOMContentLoaded', function() {
                makeSlider('heroSliderTrack', 'heroSliderWrapper', 'heroArrowLeft', 'heroArrowRight', 532);
                makeSlider('mostSliderTrack', 'mostSliderWrapper', 'mostArrowLeft', 'mostArrowRight', 385);
                makeSlider('menSliderTrack', 'menSliderWrapper', 'menArrowLeft', 'menArrowRight', 405);
                makeSlider('womenSliderTrack', 'womenSliderWrapper', 'womenArrowLeft', 'womenArrowRight', 405);
                makeSlider('newinSliderTrack', 'newinSliderWrapper', 'newinArrowLeft', 'newinArrowRight', 405);
            });
        })();
    </script>
    <script>
        // ── GLOBAL WISHLIST TOGGLE ──────────────────────────────
        function toggleWishlist(productId, btn) {
            var isWished = btn.classList.contains('wished');
            var url = isWished ? '/wishlist/' + productId : '/wishlist/add/' + productId;
            var method = isWished ? 'DELETE' : 'POST';

            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]') ? document.querySelector(
                            'meta[name=csrf-token]').content : '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(data) {
                    if (data.success) {
                        btn.classList.toggle('wished');
                        btn.classList.add('pop');
                        setTimeout(function() {
                            btn.classList.remove('pop');
                        }, 300);
                        var icon = btn.querySelector('i');
                        if (btn.classList.contains('wished')) {
                            icon.className = 'bi bi-heart-fill';
                        } else {
                            icon.className = 'bi bi-heart';
                        }
                        showWishToast(isWished ? 'Removed from wishlist' : 'Added to wishlist!');
                    }
                })
                .catch(function() {
                    console.log('Wishlist error');
                });
        }

        function showWishToast(msg) {
            var old = document.querySelector('.wish-toast');
            if (old) old.remove();
            var t = document.createElement('div');
            t.className = 'wish-toast';
            t.textContent = msg;
            t.style.cssText =
                'position:fixed;bottom:80px;right:24px;z-index:9999;padding:10px 20px;border-radius:30px;font-weight:600;font-size:13px;color:#fff;background:#ff3f6c;box-shadow:0 8px 20px rgba(0,0,0,.15);animation:slideInToast .3s ease';
            document.body.appendChild(t);
            setTimeout(function() {
                t.remove();
            }, 1000);
        }
    </script>
    @stack('scripts')
</body>

</html>
