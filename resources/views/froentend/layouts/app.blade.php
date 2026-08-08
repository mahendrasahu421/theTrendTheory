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
    @stack('styles')
    <style>
        .ttt-auth-pop{position:fixed;inset:0;z-index:12000;display:none;align-items:center;justify-content:center;padding:18px}
        .ttt-auth-pop.is-open{display:flex}
        .ttt-auth-shade{position:absolute;inset:0;background:rgba(15,23,42,.52);animation:tttAuthFade .22s ease both}
        .ttt-auth-card{position:relative;width:min(420px,100%);background:#fff;border-radius:14px;box-shadow:0 24px 70px rgba(15,23,42,.28);padding:24px;animation:tttAuthPop .32s cubic-bezier(.2,.9,.2,1.12) both;overflow:hidden}
        .ttt-auth-card:before{content:"";position:absolute;left:-30%;right:-30%;top:0;height:4px;background:linear-gradient(90deg,#00285a,#ff3f6c,#14b8a6,#00285a);background-size:220% 100%;animation:tttAuthLine 2.8s linear infinite}
        .ttt-auth-close{position:absolute;right:14px;top:12px;border:0;background:#f1f5f9;color:#0f172a;width:32px;height:32px;border-radius:50%;font-size:18px;line-height:1;cursor:pointer;transition:transform .18s ease,background .18s ease}
        .ttt-auth-close:hover{background:#e2e8f0;transform:rotate(90deg)}
        .ttt-auth-title{font-family:'Cinzel',serif;font-weight:800;color:#00285a;font-size:21px;margin:0 34px 8px 0;letter-spacing:.5px}
        .ttt-auth-sub{color:#64748b;font-size:13px;line-height:1.45;margin-bottom:18px}
        .ttt-auth-field{margin-bottom:12px}
        .ttt-auth-field label{display:block;font-size:12px;text-transform:uppercase;font-weight:800;color:#64748b;margin-bottom:6px}
        .ttt-auth-field input{width:100%;height:46px;border:1.5px solid #dbe3ec;border-radius:10px;padding:0 13px;font-size:14px;outline:0;transition:border-color .18s ease,box-shadow .18s ease,transform .18s ease}
        .ttt-auth-field input:focus{border-color:#00285a;box-shadow:0 8px 24px rgba(0,40,90,.12);transform:translateY(-1px)}
        .ttt-auth-action{position:relative;overflow:hidden;width:100%;height:48px;border:0;border-radius:999px;background:#00285a;color:#fff;font-size:14px;font-weight:800;letter-spacing:.4px;cursor:pointer;transition:transform .18s ease,box-shadow .18s ease,background .18s ease}
        .ttt-auth-action:before{content:"";position:absolute;inset:0;background:linear-gradient(110deg,transparent 0%,rgba(255,255,255,.22) 45%,transparent 70%);transform:translateX(-110%);transition:transform .5s ease}
        .ttt-auth-action:hover{background:#ff3f6c;box-shadow:0 12px 28px rgba(255,63,108,.28);transform:translateY(-1px)}
        .ttt-auth-action:hover:before{transform:translateX(110%)}
        .ttt-auth-action.is-loading{pointer-events:none;color:rgba(255,255,255,.72)}
        .ttt-auth-action.is-loading:after{content:"";position:absolute;right:18px;top:50%;width:16px;height:16px;margin-top:-8px;border:2px solid rgba(255,255,255,.4);border-top-color:#fff;border-radius:50%;animation:tttAuthSpin .75s linear infinite}
        .ttt-auth-msg{min-height:18px;margin-top:12px;font-size:13px;color:#64748b;text-align:center;transition:color .18s ease,transform .18s ease}
        .ttt-auth-msg.error{color:#dc2626}
        .ttt-auth-msg.success{color:#047857}
        .ttt-auth-otp{display:none}
        .ttt-auth-pop.otp-sent .ttt-auth-otp{display:block;animation:tttAuthStep .24s ease both}
        .ttt-auth-pop.otp-sent .ttt-auth-send{display:none}
        .ttt-auth-card.shake{animation:tttAuthShake .26s ease both}
        @keyframes tttAuthFade{from{opacity:0}to{opacity:1}}
        @keyframes tttAuthPop{from{opacity:0;transform:translateY(18px) scale(.96)}to{opacity:1;transform:translateY(0) scale(1)}}
        @keyframes tttAuthStep{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
        @keyframes tttAuthLine{from{background-position:0 0}to{background-position:220% 0}}
        @keyframes tttAuthSpin{to{transform:rotate(360deg)}}
        @keyframes tttAuthShake{0%,100%{transform:translateX(0)}25%{transform:translateX(-6px)}75%{transform:translateX(6px)}}
        @media (prefers-reduced-motion:reduce){.ttt-auth-shade,.ttt-auth-card,.ttt-auth-card:before,.ttt-auth-pop.otp-sent .ttt-auth-otp,.ttt-auth-action.is-loading:after{animation:none}.ttt-auth-action,.ttt-auth-close,.ttt-auth-field input{transition:none}}
    </style>
</head>

<body>
    @php
        $navCats = \App\Models\Category::navCategories();
        $footerShopCategories = $navCats->isNotEmpty()
            ? $navCats
            : \App\Models\Category::active()->whereNull('parent_id')->ordered()->limit(6)->get();
        $footerPages = \Illuminate\Support\Facades\Schema::hasTable('pages')
            ? \App\Models\Page::where('is_active', true)->orderBy('title')->get()
            : collect();
        $popularSearchCategories = \App\Models\Category::active()
            ->whereNotNull('parent_id')
            ->withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->having('products_count', '>', 0)
            ->orderByDesc('products_count')
            ->orderBy('sort_order')
            ->limit(6)
            ->get();
        $hasNewArrivals = \App\Models\Product::active()->exists();
        $hasSaleProducts = \App\Models\Product::active()
            ->where(function ($q) {
                $q->where('is_on_sale', true)->orWhereRaw('original_price > price');
            })
            ->exists();
    @endphp

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
                    @foreach ($navCats as $navCat)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('shop.category', $navCat->slug) }}" target="_blank">
                                {{ $navCat->name }}
                            </a>
                        </li>
                    @endforeach
                    @if($hasNewArrivals)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('shop.new-arrivals') }}" target="_blank">New Arrivals</a>
                        </li>
                    @endif
                    @if($hasSaleProducts)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('collections.show', 'sale') }}"
                                style="color:#ff3f6c!important;font-weight:700" target="_blank">Sale</a>
                        </li>
                    @endif
                </ul>
            </div>

            {{-- CENTER: Brand --}}
            <div class="brand-center">
                <a href="{{ route('home') }}" class="brand-text">THE TREND THEORY</a>
            </div>

            {{-- RIGHT: Icons --}}
            <div class="icon-group ms-auto d-flex align-items-center gap-3">
                <a href="{{ route('search') }}" class="nav-icon-link" aria-label="Search">
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
                        @foreach($footerShopCategories as $category)
                            <li><a href="{{ route('shop.category', $category->slug) }}">{{ $category->name }}</a></li>
                        @endforeach
                        @if($hasNewArrivals)
                            <li><a href="{{ route('shop.new-arrivals') }}">New Arrivals</a></li>
                        @endif
                        @if($hasSaleProducts)
                            <li><a href="{{ route('collections.show', 'sale') }}">Sale</a></li>
                        @endif
                    </ul>
                </div>
                @if($footerPages->isNotEmpty())
                    <div class="footer-col">
                        <h4>PAGES</h4>
                        <ul>
                            @foreach($footerPages->take(8) as $page)
                                <li><a href="{{ route('pages.show', $page->slug) }}">{{ $page->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
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
                @if($popularSearchCategories->isNotEmpty())
                    <h4>POPULAR SEARCHES</h4>
                    <div class="searches-links">
                        @foreach($popularSearchCategories as $category)
                            <a href="{{ route('shop.category', $category->slug) }}">{{ $category->name }}</a>{{ !$loop->last ? ' |' : '' }}
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="footer-bottom">
                <p>© {{ date('Y') }} THE TREND THEORY. All rights reserved.</p>
                <div class="footer-bottom-links">
                    @foreach($footerPages->whereIn('slug', ['privacy-policy', 'terms-of-use'])->take(2) as $page)
                        <a href="{{ route('pages.show', $page->slug) }}">{{ $page->title }}</a>
                    @endforeach
                    <a href="{{ url('/sitemap.xml') }}">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    <div class="ttt-auth-pop" id="tttAuthPop" aria-hidden="true">
        <div class="ttt-auth-shade" onclick="closeTttAuthModal()"></div>
        <div class="ttt-auth-card" role="dialog" aria-modal="true" aria-label="Login to continue">
            <button type="button" class="ttt-auth-close" onclick="closeTttAuthModal()" aria-label="Close">×</button>
            <h3 class="ttt-auth-title">Login to continue</h3>
            <p class="ttt-auth-sub">Add to cart ke liye email ya mobile number se quick login karein. Abhi test OTP <b>123456</b> hai.</p>
            <div class="ttt-auth-field">
                <label>Email or mobile number</label>
                <input type="text" id="tttAuthIdentifier" autocomplete="email" placeholder="Enter email or mobile">
            </div>
            <button type="button" class="ttt-auth-action ttt-auth-send" onclick="sendTttOtp()">Send OTP</button>
            <div class="ttt-auth-otp">
                <div class="ttt-auth-field">
                    <label>OTP</label>
                    <input type="text" id="tttAuthOtp" inputmode="numeric" maxlength="6" placeholder="123456">
                </div>
                <button type="button" class="ttt-auth-action" onclick="verifyTttOtp()">Verify & Continue</button>
            </div>
            <div class="ttt-auth-msg" id="tttAuthMsg"></div>
        </div>
    </div>

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
        (function() {
            var pendingAuthRetry = null;

            function authPop() {
                return document.getElementById('tttAuthPop');
            }

            function authMsg(message, type) {
                var msg = document.getElementById('tttAuthMsg');
                if (!msg) return;
                msg.textContent = message || '';
                msg.className = 'ttt-auth-msg' + (type ? ' ' + type : '');
                if (type === 'error') shakeAuthCard();
            }

            function shakeAuthCard() {
                var card = document.querySelector('.ttt-auth-card');
                if (!card) return;
                card.classList.remove('shake');
                void card.offsetWidth;
                card.classList.add('shake');
            }

            function setAuthLoading(selector, isLoading) {
                var btn = document.querySelector(selector);
                if (!btn) return;
                btn.classList.toggle('is-loading', !!isLoading);
                btn.disabled = !!isLoading;
            }

            function csrfToken() {
                var meta = document.querySelector('meta[name="csrf-token"]');
                return meta ? meta.content : '{{ csrf_token() }}';
            }

            window.openTttAuthModal = function(retryCallback) {
                pendingAuthRetry = typeof retryCallback === 'function' ? retryCallback : null;
                var pop = authPop();
                if (!pop) return;
                pop.classList.add('is-open');
                pop.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                authMsg('Enter email/mobile and use OTP 123456.');
                setTimeout(function() {
                    var input = document.getElementById('tttAuthIdentifier');
                    if (input) input.focus();
                }, 80);
            };

            window.closeTttAuthModal = function() {
                var pop = authPop();
                if (!pop) return;
                pop.classList.remove('is-open', 'otp-sent');
                pop.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                authMsg('');
            };

            window.sendTttOtp = function() {
                var pop = authPop();
                var identifier = (document.getElementById('tttAuthIdentifier') || {}).value || '';
                identifier = identifier.trim();
                if (!identifier) {
                    authMsg('Please enter email or mobile number.', 'error');
                    return;
                }
                authMsg('Sending OTP...');
                setAuthLoading('.ttt-auth-send', true);
                fetch('{{ route('otp.send') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken(),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ identifier: identifier })
                }).then(function(r) {
                    return r.json();
                }).then(function(data) {
                    if (!data.success) throw new Error(data.message || 'Unable to send OTP');
                    if (pop) pop.classList.add('otp-sent');
                    var otp = document.getElementById('tttAuthOtp');
                    if (otp) {
                        otp.value = '123456';
                        otp.focus();
                    }
                    authMsg('OTP sent. Use 123456 for now.', 'success');
                }).catch(function(err) {
                    authMsg(err.message || 'Unable to send OTP.', 'error');
                }).finally(function() {
                    setAuthLoading('.ttt-auth-send', false);
                });
            };

            window.verifyTttOtp = function() {
                var identifier = (document.getElementById('tttAuthIdentifier') || {}).value || '';
                var otp = (document.getElementById('tttAuthOtp') || {}).value || '';
                identifier = identifier.trim();
                otp = otp.trim();
                if (!identifier || !otp) {
                    authMsg('Please enter OTP.', 'error');
                    return;
                }
                authMsg('Verifying OTP...');
                setAuthLoading('.ttt-auth-otp .ttt-auth-action', true);
                fetch('{{ route('otp.verify') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken(),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ identifier: identifier, otp: otp })
                }).then(function(r) {
                    return r.json().then(function(data) {
                        if (!r.ok) throw new Error(data.message || 'Invalid OTP');
                        return data;
                    });
                }).then(function(data) {
                    var meta = document.querySelector('meta[name="csrf-token"]');
                    if (meta && data.csrf) meta.content = data.csrf;
                    authMsg('Logged in. Continuing...', 'success');
                    var retry = pendingAuthRetry;
                    pendingAuthRetry = null;
                    setTimeout(function() {
                        closeTttAuthModal();
                        if (retry) retry();
                    }, 250);
                }).catch(function(err) {
                    authMsg(err.message || 'Unable to verify OTP.', 'error');
                }).finally(function() {
                    setAuthLoading('.ttt-auth-otp .ttt-auth-action', false);
                });
            };

            window.tttNeedsAuth = function(data, response) {
                return (response && response.status === 401) ||
                    (data && (data.redirect || data.message === 'Unauthenticated.'));
            };

            window.TTT_AUTH_MODAL = {
                open: window.openTttAuthModal,
                close: window.closeTttAuthModal,
                needsAuth: window.tttNeedsAuth
            };
        })();
    </script>
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
            var retrySliderAddToCart = function() {
                document.getElementById('sliderAddToCart').click();
            };
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
                    } else if (window.TTT_AUTH_MODAL && window.TTT_AUTH_MODAL.needsAuth(data)) {
                        window.TTT_AUTH_MODAL.open(retrySliderAddToCart);
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
