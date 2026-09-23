<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @hasSection('custom_seo')
        @yield('custom_seo')
    @else
        @stack('seo')
        <title>{{ $meta_title ?? 'THE TREND THEORY' }}</title>
        <meta name="description" content="{{ $meta_description ?? 'Shop latest fashion and luxury oversized streetwear.' }}">
        <link rel="canonical" href="{{ $canonical ?? url()->current() }}">
        <meta property="og:site_name" content="THE TREND THEORY">
        <meta property="og:type" content="{{ $og_type ?? 'website' }}">
        <meta property="og:url" content="{{ $canonical ?? url()->current() }}">
        <meta property="og:title" content="{{ $meta_title ?? 'THE TREND THEORY' }}">
        <meta property="og:description" content="{{ $meta_description ?? 'Shop latest luxury streetwear drops.' }}">
        <meta property="og:image" content="{{ $og_image ?? asset('images/og-default.jpg') }}">
        <meta property="og:image:secure_url" content="{{ $og_image ?? asset('images/og-default.jpg') }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        
        <!-- Twitter / WhatsApp Cards -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $meta_title ?? 'THE TREND THEORY' }}">
        <meta name="twitter:description" content="{{ $meta_description ?? 'Shop latest luxury streetwear drops.' }}">
        <meta name="twitter:image" content="{{ $og_image ?? asset('images/og-default.jpg') }}">
    @endif
    <meta name="theme-color" content="#00285A" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @isset($schema)
        <script type="application/ld+json">{!! $schema !!}</script>
    @endisset
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="{{ asset('frontend/style.css') }}?v={{ filemtime(public_path('frontend/style.css')) }}" rel="stylesheet" />
    <link href="{{ asset('frontend/checkout-pop.css') }}?v={{ filemtime(public_path('frontend/checkout-pop.css')) }}" rel="stylesheet" />
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.13.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging-compat.js"></script>
    @stack('styles')
    <style>
        .ttt-auth-pop{position:fixed;inset:0;z-index:12000;display:none;align-items:center;justify-content:center;padding:20px}
        .ttt-auth-pop.is-open{display:flex}
        .ttt-auth-shade{position:absolute;inset:0;background:rgba(15,23,42,.68);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);animation:tttAuthFade .22s ease both}
        .ttt-auth-card{position:relative;display:grid;grid-template-columns:minmax(0,1.05fr) minmax(360px,.95fr);width:min(920px,100%);min-height:520px;background:#fff;border-radius:18px;box-shadow:0 28px 90px rgba(15,23,42,.34);animation:tttAuthPop .32s cubic-bezier(.2,.9,.2,1.12) both;overflow:hidden}
        .ttt-auth-card:before{content:"";position:absolute;left:-30%;right:-30%;top:0;z-index:3;height:4px;background:linear-gradient(90deg,#00285a,#ff3f6c,#14b8a6,#00285a);background-size:220% 100%;animation:tttAuthLine 2.8s linear infinite}
        .ttt-auth-media{position:relative;min-height:520px;background:#0f172a;overflow:hidden}
        .ttt-auth-media img{width:100%;height:100%;object-fit:cover;display:block;transform:scale(1.02)}
        .ttt-auth-media:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,40,90,.06),rgba(0,0,0,.66))}
        .ttt-auth-media-copy{position:absolute;left:28px;right:28px;bottom:28px;z-index:2;color:#fff}
        .ttt-auth-kicker{display:inline-flex;align-items:center;gap:7px;padding:7px 12px;border:1px solid rgba(255,255,255,.34);border-radius:999px;background:rgba(0,0,0,.28);font-size:10px;font-weight:800;letter-spacing:1.4px;text-transform:uppercase;margin-bottom:12px}
        .ttt-auth-media-title{font-family:'Cinzel',serif;font-size:32px;font-weight:800;letter-spacing:1px;line-height:1.12;margin:0 0 8px;text-transform:uppercase}
        .ttt-auth-media-text{font-size:13px;line-height:1.55;margin:0;color:rgba(255,255,255,.86);max-width:360px}
        .ttt-auth-form-panel{position:relative;display:flex;flex-direction:column;justify-content:center;padding:44px 38px;background:linear-gradient(180deg,#ffffff 0%,#f8fafc 100%)}
        .ttt-auth-close{position:absolute;right:16px;top:14px;z-index:4;border:0;background:#f1f5f9;color:#0f172a;width:36px;height:36px;border-radius:50%;font-size:0;line-height:1;cursor:pointer;transition:transform .18s ease,background .18s ease}
        .ttt-auth-close:after{content:"\F62A";font-family:"bootstrap-icons";font-size:16px}
        .ttt-auth-brand{font-family:'Cinzel',serif;font-size:14px;font-weight:800;color:#00285a;letter-spacing:1px;margin-bottom:12px;text-transform:uppercase}
        .ttt-auth-title{font-weight:800;color:#0f172a;font-size:28px;line-height:1.15;margin:0 34px 10px 0;letter-spacing:0}
        .ttt-auth-sub{color:#64748b;font-size:14px;line-height:1.55;margin-bottom:24px}
        .ttt-auth-field{margin-bottom:14px}
        .ttt-auth-field label{display:block;font-size:12px;text-transform:uppercase;font-weight:800;color:#64748b;margin-bottom:7px;letter-spacing:.4px}
        .ttt-auth-phone-wrap{display:flex;align-items:center;border:1.5px solid #dbe3ec;border-radius:12px;background:#fff;transition:border-color .18s ease,box-shadow .18s ease,transform .18s ease;overflow:hidden}
        .ttt-auth-phone-wrap:focus-within{border-color:#00285a;box-shadow:0 8px 24px rgba(0,40,90,.12);transform:translateY(-1px)}
        .ttt-auth-dial{height:50px;display:inline-flex;align-items:center;padding:0 12px;border-right:1px solid #e2e8f0;color:#0f172a;font-size:14px;font-weight:800;background:#f8fafc}
        .ttt-auth-field input{width:100%;height:50px;border:1.5px solid #dbe3ec;border-radius:12px;padding:0 14px;font-size:15px;outline:0;transition:border-color .18s ease,box-shadow .18s ease,transform .18s ease}
        .ttt-auth-phone-wrap input{border:0;border-radius:0;padding-left:12px;background:#fff}
        .ttt-auth-field input:focus{border-color:#00285a;box-shadow:0 8px 24px rgba(0,40,90,.12);transform:translateY(-1px)}
        .ttt-auth-phone-wrap input:focus{box-shadow:none;transform:none}
        .ttt-auth-action{position:relative;overflow:hidden;width:100%;height:50px;border:0;border-radius:999px;background:#00285a;color:#fff;font-size:14px;font-weight:800;letter-spacing:.4px;cursor:pointer;transition:transform .18s ease,box-shadow .18s ease,background .18s ease}
        .ttt-auth-action:before{content:"";position:absolute;inset:0;background:linear-gradient(110deg,transparent 0%,rgba(255,255,255,.22) 45%,transparent 70%);transform:translateX(-110%);transition:transform .5s ease}
        .ttt-auth-action.is-loading{pointer-events:none;color:rgba(255,255,255,.72)}
        .ttt-auth-action.is-loading:after{content:"";position:absolute;right:18px;top:50%;width:16px;height:16px;margin-top:-8px;border:2px solid rgba(255,255,255,.4);border-top-color:#fff;border-radius:50%;animation:tttAuthSpin .75s linear infinite}
        .ttt-auth-msg{min-height:18px;margin-top:13px;font-size:13px;color:#64748b;text-align:center;transition:color .18s ease,transform .18s ease}
        .ttt-auth-msg.error{color:#dc2626}
        .ttt-auth-msg.success{color:#047857}
        .ttt-auth-create-row{display:flex;align-items:center;justify-content:center;gap:8px;margin-top:16px;padding-top:16px;border-top:1px solid #e2e8f0;font-size:13px;color:#64748b}
        .ttt-auth-create-link{display:inline-flex;align-items:center;gap:6px;color:#ff3f6c;font-weight:800;text-decoration:none}
        .ttt-auth-terms{font-size:11.5px;line-height:1.45;color:#94a3b8;text-align:center;margin:14px 0 0}
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
        /* ── LUXURY NAVBAR ALWAYS SHAKING LOGIN BUTTON ── */
        @keyframes navBtnShake {
            0%, 65%, 100% {
                transform: rotate(0deg) scale(1);
            }
            68% {
                transform: rotate(-5deg) scale(1.04);
            }
            72% {
                transform: rotate(5deg) scale(1.04);
            }
            76% {
                transform: rotate(-4deg) scale(1.03);
            }
            80% {
                transform: rotate(4deg) scale(1.03);
            }
            84% {
                transform: rotate(-2deg) scale(1.01);
            }
            88% {
                transform: rotate(0deg) scale(1);
            }
        }

        .nav-btn-login-shaking {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #00285a;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 7px 16px;
            border-radius: 999px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 11.5px;
            font-weight: 800;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(0, 40, 90, 0.25);
            animation: navBtnShake 2.5s cubic-bezier(0.36, 0.07, 0.19, 0.97) infinite;
            transform-origin: center center;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .nav-btn-account {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f1f5f9;
            color: #00285a !important;
            padding: 6px 14px;
            border-radius: 999px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
        }

        .ttt-order-loader {
            position: fixed;
            inset: 0;
            z-index: 130000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(15, 23, 42, 0.62);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .ttt-order-loader.is-active {
            display: flex;
        }

        .ttt-order-loader__card {
            width: min(360px, 100%);
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            box-shadow: 0 24px 70px rgba(15, 23, 42, 0.28);
        }

        .ttt-order-loader__spinner {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 3px solid #e2e8f0;
            border-top-color: #00285a;
            animation: tttOrderSpin 0.8s linear infinite;
            margin: 0 auto 14px;
        }

        .ttt-order-loader.is-success .ttt-order-loader__spinner {
            position: relative;
            border-color: #00285a;
            background: #00285a;
            animation: none;
        }

        .ttt-order-loader.is-success .ttt-order-loader__spinner::after {
            content: "";
            position: absolute;
            left: 14px;
            top: 8px;
            width: 12px;
            height: 22px;
            border: solid #ffffff;
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
        }

        .ttt-order-loader__title {
            font-family: 'Cinzel', serif;
            font-size: 18px;
            font-weight: 900;
            color: #00285a;
            margin-bottom: 6px;
        }

        .ttt-order-loader__text {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            line-height: 1.5;
        }

        @keyframes tttOrderSpin {
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px){.ttt-auth-pop{padding:14px;align-items:flex-end}.ttt-auth-card{grid-template-columns:1fr;width:100%;max-height:calc(100vh - 28px);min-height:auto;overflow:auto;border-radius:18px 18px 0 0}.ttt-auth-media{min-height:220px}.ttt-auth-media-copy{left:20px;right:20px;bottom:20px}.ttt-auth-media-title{font-size:24px}.ttt-auth-form-panel{padding:28px 20px 24px}.ttt-auth-title{font-size:23px}.ttt-auth-close{background:#fff;box-shadow:0 8px 20px rgba(15,23,42,.18)}}
        @media (max-width: 480px){.ttt-auth-media{min-height:180px}.ttt-auth-media-text{display:none}.ttt-auth-kicker{font-size:9px}.ttt-auth-field input,.ttt-auth-dial,.ttt-auth-action{height:48px}}
        @media (prefers-reduced-motion:reduce){.ttt-auth-shade,.ttt-auth-card,.ttt-auth-card:before,.ttt-auth-pop.otp-sent .ttt-auth-otp,.ttt-auth-action.is-loading:after{animation:none}.ttt-auth-action,.ttt-auth-close,.ttt-auth-field input,.ttt-auth-phone-wrap{transition:none}}
    </style>
</head>

<body>
    @php
        $navCats = \App\Models\Category::navCategories();
        $footerShopCategories = $navCats->isNotEmpty()
            ? $navCats
            : \App\Models\Category::active()->whereNull('parent_id')->ordered()->limit(6)->get();
        $footerPages = \Illuminate\Support\Facades\Schema::hasTable('cms_pages')
            ? \App\Models\Page::where('is_active', true)->orderBy('title')->get()
            : collect();
        $footerSeoCategories = \App\Models\Category::active()
            ->whereHas('products', fn($q) => $q->where('is_active', true))
            ->with('parent')
            ->withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->orderByDesc('products_count')
            ->orderBy('sort_order')
            ->limit(18)
            ->get();
        $popularSearchCategories = $footerSeoCategories->take(12);
        $hasProducts = \App\Models\Product::active()->exists();
        $hasNewArrivals = \App\Models\Product::active()->where('is_new', true)->exists();
        $hasSaleProducts = \App\Models\Product::active()
            ->where(function ($q) {
                $q->where('is_on_sale', true)->orWhereRaw('original_price > price');
            })
            ->exists();
        $hasBestSellers = \App\Models\Product::active()
            ->where(function ($q) {
                $q->where('total_sold', '>', 0)->orWhere('is_featured', true);
            })
            ->exists();
        $footerSeoSpecialLinks = collect([
            $hasProducts ? ['label' => 'All Products', 'url' => route('shop.index'), 'title' => 'Shop all products online'] : null,
            $hasNewArrivals ? ['label' => 'New Arrivals', 'url' => route('shop.new-arrivals'), 'title' => 'Shop new arrivals online'] : null,
            $hasBestSellers ? ['label' => 'Best Sellers', 'url' => route('collections.show', 'best-sellers'), 'title' => 'Shop best sellers online'] : null,
            $hasSaleProducts ? ['label' => 'Sale', 'url' => route('collections.show', 'sale'), 'title' => 'Shop sale offers online'] : null,
        ])->filter()->values();
        $footerSeoGroups = $footerShopCategories->map(function ($parent) use ($footerSeoCategories) {
            $links = $footerSeoCategories
                ->filter(fn($category) => (int) $category->parent_id === (int) $parent->id)
                ->take(8)
                ->values();

            if ($links->isEmpty() && $footerSeoCategories->contains('id', $parent->id)) {
                $links = collect([$parent]);
            }

            return [
                'title' => $parent->name,
                'links' => $links,
            ];
        })->filter(fn($group) => $group['links']->isNotEmpty())->values();
        $footerSeoCategoryNames = $footerSeoCategories->take(5)->pluck('name')->join(', ');
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
                <a href="{{ route('home') }}" class="brand-link d-inline-flex align-items-center gap-2 text-decoration-none">
                    {{-- <img src="{{ asset('images/THE TREND THEORY-logo-dark.png') }}" alt="THE TREND THEORY" class="brand-logo-img" style="height: 36px; width: auto; object-fit: contain;"> --}}
                    <span class="brand-text">THE TREND THEORY</span>
                </a>
            </div>

            {{-- RIGHT: Icons --}}
            <div class="icon-group ms-auto d-flex align-items-center gap-3">
                <a href="{{ route('search') }}" class="nav-icon-link" aria-label="Search">
                    <i class="bi bi-search"></i>
                </a>

                {{-- Notification Bell --}}
                <div class="ttt-notif-nav-wrap" id="tttNotifWrap">
                    <button type="button" class="nav-icon-link ttt-notif-trigger-btn" id="tttNotifBell" onclick="toggleTttNotifications(event)" aria-label="Notifications">
                        <i class="bi bi-bell"></i>
                        <span id="notif-count" class="ttt-notif-badge-pill" style="display:none;">0</span>
                    </button>

                    {{-- Notification Dropdown Card --}}
                    <div class="ttt-notif-dropdown" id="tttNotifDropdown" style="display:none;" onclick="event.stopPropagation()">
                        <div class="ttt-notif-head">
                            <div class="ttt-notif-title-wrap">
                                <span class="ttt-notif-title"><i class="bi bi-bell-fill"></i> Notifications</span>
                                <span class="ttt-notif-subtitle">Orders, drops and offers</span>
                            </div>
                        </div>
                        {{-- Push Permission Quick Action Bar --}}
                        <div class="ttt-notif-perm-bar" id="tttNotifPermBar">
                            <span id="tttPermText"><i class="bi bi-shield-check"></i> Instant alerts <b id="tttPermState">Disabled</b></span>
                            <button type="button" class="btn-perm-switch" id="tttPermBtn" onclick="togglePushPermissionAction()">Enable</button>
                        </div>
                        <div class="ttt-notif-list" id="tttNotifList">
                            <div class="ttt-notif-loading"><i class="bi bi-arrow-repeat"></i> Loading notifications...</div>
                        </div>
                    </div>
                </div>

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
                    <a href="{{ url('/profile') }}" class="nav-btn-account" aria-label="Profile">
                        <i class="bi bi-person-circle"></i>
                        <span class="d-none d-sm-inline">{{ Str::limit(auth()->user()->name ?? 'Account', 10) }}</span>
                    </a>
                @else
                    <button type="button" class="nav-btn-login-shaking" onclick="window.openTttAuthModal ? window.openTttAuthModal() : window.location.href='{{ route('login') }}'" aria-label="Login">
                        <i class="bi bi-person-fill"></i>
                        <span>LOGIN</span>
                    </button>
                @endauth
            </div>

        </div>
    </nav>

    @yield('main')

    {{-- DYNAMIC SEO & AEO FOOTER --}}
    @include('froentend.layouts.footer')

    <div class="ttt-auth-pop" id="tttAuthPop" aria-hidden="true">
        <div class="ttt-auth-shade" onclick="closeTttAuthModal()"></div>
        <div class="ttt-auth-card" role="dialog" aria-modal="true" aria-label="Login to continue">
            <button type="button" class="ttt-auth-close" onclick="closeTttAuthModal()" aria-label="Close">×</button>
            <div class="ttt-auth-media">
                <img src="{{ asset('storage/gallery/images/main-desktop-wide-fc7f45c7-98d4-4647-9121-851811d07ec6-2800x1000-crop-center-1784095408-jydom4.webp') }}"
                     alt="THE TREND THEORY new fashion drop"
                     onerror="this.src='{{ asset('images/TheTrendTheory.jpg') }}'">
                <div class="ttt-auth-media-copy">
                    <div class="ttt-auth-kicker">
                        <i class="bi bi-stars"></i>
                        New Drop Access
                    </div>
                    <h3 class="ttt-auth-media-title">Own the latest trend</h3>
                    <p class="ttt-auth-media-text">Login with mobile to unlock wishlist, faster checkout, order tracking and member-only offers.</p>
                </div>
            </div>
            <div class="ttt-auth-form-panel">
                <div class="ttt-auth-brand">THE TREND THEORY</div>
                <h3 class="ttt-auth-title">Login with mobile</h3>
              
                <div class="ttt-auth-field">
                    <label for="tttAuthIdentifier">Mobile number</label>
                    <div class="ttt-auth-phone-wrap">
                        <span class="ttt-auth-dial">+91</span>
                        <input type="tel" id="tttAuthIdentifier" inputmode="numeric" autocomplete="tel"
                               maxlength="10" placeholder="98765 43210">
                    </div>
                </div>
                <button type="button" class="ttt-auth-action ttt-auth-send" onclick="sendTttOtp()">Send OTP</button>
                <div class="ttt-auth-otp">
                    <div class="ttt-auth-field">
                        <label for="tttAuthOtp">OTP</label>
                        <input type="text" id="tttAuthOtp" inputmode="numeric" maxlength="6" placeholder="123456">
                    </div>
                    <button type="button" class="ttt-auth-action" onclick="verifyTttOtp()">Verify & Continue</button>
                </div>
                <div class="ttt-auth-msg" id="tttAuthMsg"></div>
                <div class="ttt-auth-create-row">
                    <span>New customer?</span>
                    <a class="ttt-auth-create-link" href="{{ route('register') }}">
                        <i class="bi bi-person-plus"></i>
                        Create account
                    </a>
                </div>
                <p class="ttt-auth-terms">By continuing, you agree to receive login OTP and account updates from THE TREND THEORY.</p>
            </div>
        </div>
    </div>

    {{-- Web Push Notification Permission Prompt (Allow / Block Modal Banner) --}}
    <div class="ttt-push-prompt" id="tttPushPrompt" style="display:none;" role="dialog" aria-label="Notification Permission">
        <div class="push-prompt-card">
            <button type="button" class="push-close-btn" onclick="blockPushPrompt()" aria-label="Close">&times;</button>
            <div class="push-card-top-row">
                <div class="push-icon-circle">
                    <i class="bi bi-bell-fill"></i>
                </div>
                <div class="push-text-wrap">
                    <h4 class="push-title">Stay updated with THE TREND THEORY</h4>
                    <p class="push-desc">Get order updates, new drops and selected offers on your device.</p>
                </div>
            </div>
            <div class="push-actions-grid">
                <button type="button" class="btn-push-allow" onclick="requestBrowserNotificationPermission()">
                    <i class="bi bi-check-circle-fill"></i> Allow
                </button>
                <button type="button" class="btn-push-block" onclick="blockPushPrompt()">
                    <i class="bi bi-slash-circle"></i> Not now
                </button>
            </div>
        </div>
    </div>

    {{-- GLOBAL CENTER LUXURY CHECKOUT & ORDER SUMMARY MODAL (BONKERS STYLE) --}}
    <div class="checkout-pop center-modal" id="globalCheckoutPop" aria-hidden="true">
        <div class="checkout-pop__shade" onclick="closeGlobalCheckoutPop()"></div>
        <aside class="checkout-pop__panel" role="dialog" aria-modal="true" aria-label="Order Summary &amp; Checkout">
            <button type="button" class="checkout-pop__close-circle" onclick="closeGlobalCheckoutPop()" aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>

            {{-- 1. MAIN CHECKOUT VIEW --}}
            <div class="gco-view active" id="gcoViewMain">
                <div class="checkout-pop__top">
                    <div class="checkout-pop__brand">
                        <i class="bi bi-gem"></i> THE TREND THEORY
                    </div>
                    <div class="checkout-pop__secure">
                        <i class="bi bi-shield-fill-check"></i> 100% SECURE
                    </div>
                </div>

                <div class="checkout-pop__discount-banner">
                    <div class="discount-banner-content">
                        <i class="bi bi-lightning-charge-fill"></i>
                        <span><strong>PREPAID SPECIAL:</strong> Extra 5% Instant Discount auto-applied at checkout!</span>
                    </div>
                </div>

                <div class="checkout-pop__user-bar">
                    <div class="user-bar-left">
                        <i class="bi bi-person-check-fill text-success"></i>
                        <span>Logged in as: <strong id="gcoUserNumber">{{ optional(auth()->user())->phone ?? (optional(auth()->user())->email ?? 'Customer') }}</strong></span>
                    </div>
                    <span class="user-bar-badge"><i class="bi bi-patch-check-fill"></i> Verified Buyer</span>
                </div>

                <div class="checkout-pop__body">
                    {{-- 1. ORDER SUMMARY CARD --}}
                    <section class="checkout-block">
                        <div class="checkout-title">
                            <span><i class="bi bi-bag-check-fill"></i> ORDER SUMMARY</span>
                        </div>
                        <div class="checkout-card checkout-summary-card" id="gcoSummaryCard">
                            <div class="gco-summary-toggle" onclick="gcoToggleSummary(event)" role="button" tabindex="0" style="cursor: pointer; user-select: none;">
                                <div class="summary-left-info">
                                    <div class="checkout-cart-icon"><i class="bi bi-cart3"></i></div>
                                    <div class="summary-title-col">
                                        <div class="checkout-card-title">Order Summary</div>
                                        <div class="checkout-save" id="gcoSavingsTag"><b id="gcoSavingsAmount">₹0</b> saved so far</div>
                                    </div>
                                </div>
                                <div class="checkout-summary-price-col">
                                    <span id="gcoItemCount" class="gco-item-count-top">1 item</span>
                                    <div class="checkout-summary-bottom-price">
                                        <span id="gcoMrpTotalTop" class="mrp-strikethrough">₹0</span>
                                        <strong id="gcoTotalTop" class="gco-total-val">₹0</strong>
                                        <i class="bi bi-chevron-down" id="gcoSummaryChevron"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- Expanded items & breakdown (Collapsed by default) --}}
                            <div id="gcoSummaryDetails" class="gco-summary-expanded" onclick="event.stopPropagation();">
                                <div id="gcoOrderItemsList"></div>

                                <div class="gco-breakdown-card">
                                    <div class="gco-break-row"><span>Total MRP</span><b id="gcoMrpTotal">₹0</b></div>
                                    <div class="gco-break-row green"><span><i class="bi bi-percent"></i> Discount on MRP</span><b id="gcoMrpDiscount">-₹0</b></div>
                                    <div class="gco-break-row"><span>Subtotal</span><b id="gcoSubtotal">₹0</b></div>
                                    <div class="gco-break-row green" id="gcoPrepaidRow"><span><i class="bi bi-lightning-fill"></i> Prepaid 5% Extra Off</span><b id="gcoPrepaidDiscount">-₹0</b></div>
                                    <div class="gco-break-row"><span><i class="bi bi-truck"></i> Shipping Fee</span><b id="gcoShipping" class="free-shipping-tag">FREE</b></div>
                                    <div class="gco-break-final"><span>Total Payable</span><b id="gcoFinalPay">₹0</b></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- 2. DELIVERY ADDRESS CARD (EXACT MATCH media_1788342643636.png) --}}
                    <section class="checkout-block">
                        <div class="checkout-card checkout-address-card" id="gcoAddressCard">
                            <div class="address-top-row">
                                <div class="address-main-left">
                                    <div class="address-pin-icon"><i class="bi bi-geo-alt"></i></div>
                                    <div class="address-text-col">
                                        <div class="address-deliver-title">Deliver To <span id="gcoDeliverName">{{ auth()->user()->name ?? 'Customer' }}</span></div>
                                        <p id="gcoDeliverAddress" class="address-line-text">
                                            {{ optional(auth()->user())->address
                                                ? auth()->user()->address . ', ' . auth()->user()->city . ', ' . auth()->user()->state . ' - ' . auth()->user()->pincode
                                                : 'No address added yet. Please tap to add address.' }}
                                        </p>
                                        <p id="gcoDeliverContact" class="address-contact-line">{{ optional(auth()->user())->phone ? '+91 ' . auth()->user()->phone : '' }}{{ optional(auth()->user())->email ? ' | ' . auth()->user()->email : '' }}</p>
                                    </div>
                                </div>
                                <button type="button" class="btn-address-change" onclick="gcoShowAddressSelect()">
                                    Change
                                </button>
                            </div>
                            <div class="shipping-info-subbox">
                                <div class="shipping-subbox-title">Standard Shipping</div>
                                <div class="shipping-subbox-row">
                                    <span class="shipping-delivery-date" id="gcoDeliveryDateText">
                                        Delivery between {{ now()->addDays(3)->format('j') }} and {{ now()->addDays(5)->format('j F') }}
                                    </span>
                                    <span class="shipping-fee-pill" id="gcoShippingBadge">₹50</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- 3. OFFERS & REWARDS CARD (EXACT MATCH media_1788342775114.png) --}}
                    <div class="ttt-offers-rewards-wrap" style="margin-bottom: 16px;">
                        <div class="ttt-offers-head">OFFERS &amp; REWARDS</div>
                        <div class="ttt-offers-card">
                            <div class="ttt-coupon-input-box">
                                <input type="text" id="gcoCouponInput" placeholder="Enter coupon code" autocomplete="off" style="text-transform: uppercase;">
                                <button type="button" class="ttt-coupon-apply-btn" onclick="gcoApplyCoupon()">APPLY</button>
                            </div>
                            <div class="ttt-offers-mid-row">
                                <div class="ttt-offers-left" onclick="openCouponsModal()">
                                    <span class="ttt-badge-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#526071" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 2l2.4 2.4 3.4-.6 1.2 3.2 3.2 1.2-.6 3.4 2.4 2.4-2.4 2.4.6 3.4-3.2 1.2-1.2 3.2-3.4-.6-2.4 2.4-2.4-2.4-3.4.6-1.2-3.2-3.2-1.2.6-3.4-2.4-2.4 2.4-2.4-.6-3.4 3.2-1.2 1.2-3.2 3.4.6 2.4-2.4z"/>
                                            <line x1="9" y1="15" x2="15" y2="9"/>
                                            <circle cx="9.5" cy="9.5" r=".7" fill="#526071"/>
                                            <circle cx="14.5" cy="14.5" r=".7" fill="#526071"/>
                                        </svg>
                                    </span>
                                    <span class="ttt-available-text"><span id="gcoAvailableCount">11</span> coupons available</span>
                                </div>
                                <button type="button" class="ttt-view-all-btn" onclick="openCouponsModal()">View All</button>
                            </div>
                            <div class="ttt-loyalty-row">
                                <span class="ttt-loyalty-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#526071" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2l2.4 2.4 3.4-.6 1.2 3.2 3.2 1.2-.6 3.4 2.4 2.4-2.4 2.4.6 3.4-3.2 1.2-1.2 3.2-3.4-.6-2.4 2.4-2.4-2.4-3.4.6-1.2-3.2-3.2-1.2.6-3.4-2.4-2.4 2.4-2.4-.6-3.4 3.2-1.2 1.2-3.2 3.4.6 2.4-2.4z"/>
                                        <polygon points="12 8 13.2 10.8 16 11.2 14 13.1 14.5 16 12 14.6 9.5 16 10 13.1 8 11.2 10.8 10.8 12 8" fill="#526071" stroke="#526071" stroke-width="0.5"/>
                                    </svg>
                                </span>
                                <span class="ttt-loyalty-text">You're earning <b id="gcoLoyaltyPoints">26 loyalty points</b> on this order</span>
                            </div>
                        </div>
                    </div>

                    {{-- 4. PAYMENT OPTIONS CARD --}}
                    {{-- 4. PAYMENT OPTIONS CARD --}}
                    @php
                        $activePrimaryGateway = \App\Models\SiteSetting::get('primary_payment_gateway', 'phonepe');
                        $isPhonePeEnabled = \App\Models\SiteSetting::get('phonepe_enabled', '1') == '1';
                        $isRazorpayEnabled = \App\Models\SiteSetting::get('razorpay_enabled', '1') == '1';
                        $isCodEnabled = \App\Models\SiteSetting::get('cod_enabled', '1') == '1';
                    @endphp

                    <section class="checkout-block">
                        <div class="checkout-title">
                            <span><i class="bi bi-credit-card-2-front-fill"></i> PAYMENT METHOD</span>
                        </div>

                        {{-- Option 1: Online Payment (PhonePe / Razorpay / UPI / Cards) --}}
                        <div class="checkout-card checkout-payment-card mb-2" id="gcoPayCardOnline" onclick="gcoSelectPayMethod('online')" style="cursor:pointer; border: 2px solid #5f259f; background: #faf5ff; transition: all 0.2s ease;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <input type="radio" name="gco_payment_mode" id="gcoModeOnline" value="online" checked style="accent-color:#5f259f; width:17px; height:17px; cursor:pointer;">
                                    <strong style="font-size:13.5px; color:#0f172a;">⚡ Instant Online Payment</strong>
                                </div>
                                <span class="badge bg-success-subtle text-success border font-xs fw-bold px-2 py-0.5">EXTRA 5% OFF</span>
                            </div>

                            <div class="prepaid-discount-pill" style="margin-bottom:8px;">
                                <i class="bi bi-lightning-fill text-warning"></i>
                                <span>Extra 5% Instant Discount automatically applied on Prepaid checkout</span>
                            </div>

                            {{-- Single Row Payment Icons Strip (No Text) --}}
                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-top: 6px;">
                                {{-- PhonePe --}}
                                <div style="display: inline-flex; align-items: center; justify-content: center; height: 26px; width: 40px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 2px 6px;" title="PhonePe">
                                    <svg viewBox="0 0 24 24" width="18" height="18">
                                        <circle cx="12" cy="12" r="11" fill="#5f259f"/>
                                        <path fill="#ffffff" d="M14.5 7h-3.8c-.4 0-.8.3-.8.8v8.4c0 .4.3.8.8.8h1.2c.4 0 .8-.3.8-.8v-3.2h1.8c2.2 0 3.8-1.5 3.8-3s-1.6-3-3.8-3zm0 4.2h-1.8V8.8h1.8c1.1 0 1.9.6 1.9 1.2s-.8 1.2-1.9 1.2z"/>
                                    </svg>
                                </div>
                                {{-- Google Pay --}}
                                <div style="display: inline-flex; align-items: center; justify-content: center; height: 26px; width: 40px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 2px 6px;" title="Google Pay">
                                    <svg viewBox="0 0 24 24" width="18" height="18">
                                        <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/>
                                        <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24z"/>
                                        <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.14-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.98 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                                        <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                                    </svg>
                                </div>
                                {{-- Paytm --}}
                                <div style="display: inline-flex; align-items: center; justify-content: center; height: 26px; width: 44px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 2px 6px;" title="Paytm">
                                    <svg viewBox="0 0 36 14" width="28" height="11">
                                        <path fill="#002970" d="M0 2h3.8c2 0 3.3.9 3.3 2.7 0 1.9-1.3 2.8-3.3 2.8H1.8v5H0V2zm1.8 3.9h1.6c1 0 1.7-.4 1.7-1.2 0-.8-.7-1.2-1.7-1.2H1.8v2.4z"/>
                                        <path fill="#00baf2" d="M8 4.8h1.7v7.7H8V4.8zm0-2.8h1.7v1.8H8V2zm4.4 10.5c-2 0-3.3-1.4-3.3-3.9s1.3-3.9 3.3-3.9c1.9 0 3.1 1.2 3.1 3.1v.8h-4.7c.1 1.3.9 2.2 2 2.2.9 0 1.4-.4 1.8-1l1.2.8c-.6 1.1-1.8 1.9-3.4 1.9zm1.4-5.3c-.1-1-.8-1.7-1.7-1.7s-1.4.7-1.6 1.7h3.3zm4.6 5.3V7h-1.3V5.3h1.3V2.9h1.7v2.4h1.7V7h-1.7v4.6c0 .6.3.9.9.9h.8v1.6h-1.1c-1.4 0-2.3-.6-2.3-1.6zm6.3-7.2h1.7v1.3c.4-.9 1.3-1.4 2.3-1.4 1 0 1.9.6 2.3 1.4.6-.9 1.4-1.4 2.5-1.4 1.7 0 2.6 1.1 2.6 2.9v4.4h-1.7V7.4c0-1-.6-1.5-1.3-1.5-.9 0-1.6.7-1.6 1.5v5.1h-1.7V7.4c0-1-.6-1.5-1.3-1.5-.9 0-1.6.7-1.6 1.5v5.1h-1.7V5.3z"/>
                                    </svg>
                                </div>
                                {{-- UPI --}}
                                <div style="display: inline-flex; align-items: center; justify-content: center; height: 26px; width: 40px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 2px 6px;" title="UPI">
                                    <svg viewBox="0 0 38 24" width="22" height="14">
                                        <path fill="#097939" d="M12 2h7l-8 20h-7z"/>
                                        <path fill="#ed7524" d="M23 2h7l-8 20h-7z"/>
                                    </svg>
                                </div>
                                {{-- Visa & Master Cards --}}
                                <div style="display: inline-flex; align-items: center; justify-content: center; height: 26px; width: 40px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 2px 6px;" title="Credit & Debit Cards">
                                    <i class="bi bi-credit-card-2-front-fill" style="color: #2563eb; font-size: 15px;"></i>
                                </div>
                                {{-- NetBanking --}}
                                <div style="display: inline-flex; align-items: center; justify-content: center; height: 26px; width: 40px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 2px 6px;" title="Net Banking">
                                    <i class="bi bi-bank2" style="color: #0f172a; font-size: 14px;"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Option 2: Cash on Delivery (COD) --}}
                        @if($isCodEnabled)
                        <div class="checkout-card checkout-payment-card" id="gcoPayCardCod" onclick="gcoSelectPayMethod('cod')" style="cursor:pointer; border: 1.5px solid #e2e8f0; background: #ffffff; transition: all 0.2s ease;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <input type="radio" name="gco_payment_mode" id="gcoModeCod" value="cod" style="accent-color:#059669; width:17px; height:17px; cursor:pointer;">
                                    <div>
                                        <strong style="font-size:13.5px; color:#0f172a; display:block;">💵 Cash on Delivery (COD)</strong>
                                        <span style="font-size:11.5px; color:#64748b;">Pay cash at doorstep when parcel is delivered</span>
                                    </div>
                                </div>
                                <span class="badge bg-light text-muted border font-xs px-2 py-0.5">Pay on Delivery</span>
                            </div>
                        </div>
                        @endif
                    </section>
                </div>

                {{-- STICKY MODAL FOOTER --}}
                <div class="checkout-pop__footer">
                    <div class="checkout-cashback" id="gcoFooterCashback"><i class="bi bi-lightning-fill"></i> Extra 5% OFF on Online Payment</div>
                    <div class="checkout-footer-summary">
                        <div>
                            <div class="total-payable-label">Total Payable Amount</div>
                            <div class="total-payable-amount" id="gcoFooterTotal">₹0</div>
                        </div>
                        <div class="total-saved-wrap">
                            <span class="badge-total-saved" id="gcoFooterSaved"><i class="bi bi-check2-circle"></i> Saved ₹0</span>
                        </div>
                    </div>

                    <button type="button" class="gco-pay-btn" id="gcoDynamicPayBtn" 
                            style="{{ ($activePrimaryGateway === 'razorpay' || (!$isPhonePeEnabled && $isRazorpayEnabled)) ? 'background: linear-gradient(135deg, #0284c7 0%, #00285a 100%);' : 'background: linear-gradient(135deg, #5f259f 0%, #00285a 100%);' }}" 
                            onclick="gcoProceedToActiveGateway()">
                        <span id="gcoDynamicBtnText">
                            @if($activePrimaryGateway === 'razorpay' || (!$isPhonePeEnabled && $isRazorpayEnabled))
                                PROCEED TO PAY VIA RAZORPAY
                            @elseif($activePrimaryGateway === 'phonepe' || $isPhonePeEnabled)
                                PROCEED TO PAY VIA PHONEPE / UPI
                            @else
                                PROCEED TO PAYMENT
                            @endif
                        </span>
                        <i class="bi bi-shield-lock-fill" id="gcoDynamicBtnIcon"></i>
                    </button>
                    <div class="checkout-powered">
                        <i class="bi bi-shield-check text-success"></i> 256-Bit SSL Encrypted • 100% Secure &amp; RBI Compliant Checkout
                    </div>
                </div>
            </div>

            {{-- 2. SELECT DELIVERY ADDRESS SUBVIEW (SCREENSHOT 1) --}}
            <div class="gco-view" id="gcoViewAddressSelect">
                <div class="gco-subview-head">
                    <div class="gco-subview-title">
                        <button type="button" class="gco-subview-back-btn" onclick="gcoShowMain()"><i class="bi bi-chevron-left"></i></button>
                        <span>Select Delivery Address</span>
                    </div>
                    <button type="button" class="gco-btn-add-new" onclick="gcoShowAddressEdit(true)">+ Add New Address</button>
                </div>

                <div class="checkout-pop__body" style="padding: 14px 16px 14px;">
                    <div class="gco-address-list" id="gcoAddressList"></div>
                    <button type="button" class="gco-current-location-btn" onclick="gcoStartCurrentLocationAddress()">
                        <i class="bi bi-crosshair"></i>
                        <span>Use Current Location</span>
                    </button>
                </div>

                <div class="checkout-pop__footer" style="padding: 12px 16px 14px;">
                    <button type="button" class="gco-pay-btn" id="gcoConfirmAddressBtn" onclick="gcoConfirmSelectedAddress()">
                        <span>CONFIRM &amp; DELIVER HERE</span>
                        <i class="bi bi-check2-circle"></i>
                    </button>
                </div>
            </div>

            {{-- 3. EDIT / ADD ADDRESS SUBVIEW (SCREENSHOT 2) --}}
            <div class="gco-view" id="gcoViewAddressEdit">
                <div class="gco-subview-head">
                    <div class="gco-subview-title">
                        <button type="button" class="gco-subview-back-btn" onclick="gcoShowAddressSelect()"><i class="bi bi-chevron-left"></i></button>
                        <span id="gcoEditViewTitle">Edit Address</span>
                    </div>
                </div>

                <div class="checkout-pop__body" style="padding: 10px 18px 30px;">
                    <form id="gcoAddressForm" onsubmit="event.preventDefault(); gcoSaveAddressForm();">
                        <div class="gco-form-section-title">Shipping Address</div>
                        <button type="button" class="gco-use-location-btn" onclick="gcoUseCurrentLocation()">
                            <i class="bi bi-geo-alt-fill"></i>
                            Use my current location
                        </button>
                        <div class="gco-location-map" id="gcoLocationMap" hidden>
                            <iframe id="gcoLocationMapFrame" title="Current location map" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            <div class="gco-location-map-foot">
                                <span id="gcoLocationMapCoords">Location selected</span>
                                <a id="gcoLocationMapLink" href="#" target="_blank" rel="noopener">Open in Google Maps</a>
                            </div>
                        </div>

                        {{-- Pincode with instant auto lookup --}}
                        <div class="gco-floating-field" id="gcoPinWrap">
                            <input type="text" id="gcoInPin" maxlength="6" placeholder=" " value="{{ auth()->user()->pincode ?? '' }}" oninput="gcoLookupPincode(this.value)">
                            <label>Pincode *</label>
                        </div>

                        {{-- City & State (2 columns) --}}
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <div class="gco-floating-field">
                                <input type="text" id="gcoInCity" placeholder=" " value="{{ auth()->user()->city ?? '' }}">
                                <label>City *</label>
                            </div>
                            <div class="gco-floating-field">
                                <input type="text" id="gcoInState" placeholder=" " value="{{ auth()->user()->state ?? '' }}">
                                <label>State *</label>
                            </div>
                        </div>

                        {{-- Flat, House no. --}}
                        <div class="gco-floating-field">
                            <input type="text" id="gcoInFlat" placeholder=" " value="">
                            <label>Flat, House no. *</label>
                        </div>

                        {{-- Apartment, Area, Sector, Village --}}
                        <div class="gco-floating-field">
                            <input type="text" id="gcoInArea" placeholder=" " value="{{ auth()->user()->address ?? '' }}">
                            <label>Apartment, Area, Sector, Village *</label>
                        </div>

                        <div class="gco-form-section-title">Customer Information</div>

                        {{-- Full Name --}}
                        <div class="gco-floating-field">
                            <input type="text" id="gcoInName" placeholder=" " value="{{ auth()->user()->name ?? '' }}">
                            <label>Full Name *</label>
                        </div>

                        {{-- Email Address --}}
                        <div class="gco-floating-field">
                            <input type="email" id="gcoInEmail" placeholder=" " value="{{ optional(auth()->user())->email ?? '' }}">
                            <label>Email Address *</label>
                        </div>

                        {{-- Mobile Number --}}
                        <div class="gco-floating-field">
                            <input type="tel" id="gcoInPhone" maxlength="10" placeholder=" " value="{{ optional(auth()->user())->phone ?? '' }}">
                            <label>Mobile Number *</label>
                        </div>

                        <div class="gco-form-section-title">Save Address As</div>
                        <div class="gco-address-type-group">
                            <div class="gco-type-pill active" id="gcoTypeHome" onclick="gcoSetAddressType('Home')">
                                <span class="gco-type-dot"></span> Home
                            </div>
                            <div class="gco-type-pill" id="gcoTypeWork" onclick="gcoSetAddressType('Work')">
                                <span class="gco-type-dot"></span> Work
                            </div>
                        </div>

                        <div id="gcoFormMsg" style="font-size: 12.5px; font-weight: 700; margin-top: 10px; min-height: 18px;"></div>

                        <button type="submit" class="gco-save-address-btn" id="gcoSaveBtn">
                            Save Address
                        </button>
                    </form>
                </div>
            </div>
        </aside>
    </div>

    {{-- PRODUCT QUICK VIEW / ADD TO CART MODAL --}}
    <div class="slider-overlay" onclick="closeSlider()"></div>
    <div class="universal-slider" id="universalSlider" role="dialog" aria-modal="true" aria-label="Quick Shop" aria-hidden="true">
        <div class="slider-header">
            <div class="slider-header-info">
                <span class="slider-header-badge"><i class="bi bi-bag-plus"></i> QUICK ADD</span>
                <span class="slider-header-title" id="sliderTitle">SELECT SIZE &amp; OPTIONS</span>
            </div>
            <button class="close-slider" onclick="closeSlider()" aria-label="Close quick shop">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="slider-body">
            <div class="product-view" id="productView">
                <div class="product-left">
                    <div class="main-product-frame">
                        <img id="mainProductImg" src="{{ asset('images/placeholder-product.jpg') }}" class="main-product-img" alt="Product"
                            onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                        <span class="quick-discount-badge" id="sliderDiscountBadge" style="display:none;"></span>
                    </div>
                    <div id="productGallery" class="quick-gallery-strip"></div>
                </div>
                <div class="product-right">
                    <div class="slider-brand-tag">THE TREND THEORY • SIGNATURE</div>
                    <h2 class="product-title" id="sliderProductName">Loading Product...</h2>
                    
                    <div class="price-row">
                        <span class="new-price" id="sliderPrice">₹0</span>
                        <span class="old-price" id="sliderOldPrice" style="display:none">₹0</span>
                        <span class="save-tag" id="sliderDiscount" style="display:none">SAVE 0%</span>
                    </div>
                    
                    <div class="quick-trust-pills">
                        <span><i class="bi bi-shield-check"></i> 100% Authentic</span>
                        <span><i class="bi bi-truck"></i> Free Shipping > ₹999</span>
                        <span><i class="bi bi-arrow-repeat"></i> Easy Returns</span>
                    </div>

                    <div class="size-section" id="sliderSizeSection">
                        <div class="size-section-header">
                            <span class="size-label">SIZE: <strong class="selected-size" id="selectedSize">-</strong></span>
                            <span class="size-guide-link" onclick="window.location.href=(document.getElementById('sliderViewFull')?.href || '#') + '#sizeGuide'">Size Guide</span>
                        </div>
                        <div class="size-grid" id="sizeGrid"></div>
                    </div>

                    <div class="quick-print-side-section" id="sliderPrintSideSection">
                        <div class="quick-print-side-header">
                            <span>PRINT SIDE: <strong id="sliderSelectedPrintSide">Select</strong></span>
                        </div>
                        <div class="quick-print-side-grid" id="sliderPrintSideGrid"></div>
                        <div class="quick-print-side-error" id="sliderPrintSideError">Please select Front Side or Back Side print.</div>
                    </div>

                    <div class="action-section">
                        <div class="qty-box">
                            <button id="sliderQtyMinus" type="button" aria-label="Decrease quantity">−</button>
                            <span class="qty-value" id="sliderQty">1</span>
                            <button id="sliderQtyPlus" type="button" aria-label="Increase quantity">+</button>
                        </div>
                        <button class="add-cart-btn" id="sliderAddToCart" type="button">
                            <i class="bi bi-bag-plus-fill"></i> ADD TO CART
                        </button>
                    </div>

                    <button class="buy-now-btn" id="sliderBuyNow" type="button">
                        <i class="bi bi-lightning-charge-fill"></i> BUY IT NOW
                    </button>

                    <div class="quick-view-full-wrap">
                        <a id="sliderViewFull" href="#" target="_blank" class="quick-view-full-link">
                            <span>View Full Product Details &amp; Reviews</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            function toastIcon(type) {
                if (type === 'success') return 'bi-check-circle-fill';
                if (type === 'error') return 'bi-x-circle-fill';
                if (type === 'warning') return 'bi-exclamation-triangle-fill';
                return 'bi-info-circle-fill';
            }

            function ensureToastStack() {
                var stack = document.querySelector('.ttt-toast-stack');
                if (stack) return stack;
                stack = document.createElement('div');
                stack.className = 'ttt-toast-stack';
                stack.setAttribute('aria-live', 'polite');
                stack.setAttribute('aria-atomic', 'false');
                document.body.appendChild(stack);
                return stack;
            }

            window.tttNotify = window.showTttToast = function(message, type, options) {
                var text = (message == null ? '' : String(message)).trim();
                if (!text) return null;

                var toast = document.createElement('div');
                var kind = type || 'info';
                var duration = options && options.duration ? options.duration : 3200;
                toast.className = 'ttt-toast ttt-toast-' + kind;
                toast.innerHTML =
                    '<span class="ttt-toast-icon"><i class="bi ' + toastIcon(kind) + '"></i></span>' +
                    '<span class="ttt-toast-message"></span>' +
                    '<button type="button" class="ttt-toast-close" aria-label="Close notification"><i class="bi bi-x"></i></button>';
                toast.querySelector('.ttt-toast-message').textContent = text;

                var stack = ensureToastStack();
                stack.appendChild(toast);
                requestAnimationFrame(function() {
                    toast.classList.add('is-visible');
                });

                function removeToast() {
                    toast.classList.remove('is-visible');
                    setTimeout(function() {
                        if (toast.parentNode) toast.parentNode.removeChild(toast);
                    }, 220);
                }

                toast.querySelector('.ttt-toast-close').addEventListener('click', removeToast);
                setTimeout(removeToast, duration);
                return toast;
            };

            window.alert = function(message) {
                window.tttNotify(message, 'info');
            };
        })();
    </script>
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

            window.TTT_IS_AUTHENTICATED = {{ auth()->check() ? 'true' : 'false' }};
            function setAuthLoading(selector, isLoading) {
                var btn = document.querySelector(selector);
                if (!btn) return;
                btn.classList.toggle('is-loading', !!isLoading);
                btn.disabled = !!isLoading;
            }

            function csrfToken() {
                var meta = document.querySelector('meta[name="csrf-token"]');
                var token = meta ? meta.content : '{{ csrf_token() }}';
                if (window.TTT_PRODUCT_SHOW) window.TTT_PRODUCT_SHOW.csrf = token;
                return token;
            }
            window.tttCsrfToken = csrfToken;

            function mobileIdentifier() {
                var input = document.getElementById('tttAuthIdentifier');
                var value = input ? input.value : '';
                var digits = String(value).replace(/\D/g, '');
                if (digits.length > 10) digits = digits.slice(-10);
                if (input) input.value = digits;
                return digits;
            }

            window.openTttAuthModal = function(retryCallback) {
                pendingAuthRetry = typeof retryCallback === 'function' ? retryCallback : null;
                var pop = authPop();
                if (!pop) return;
                pop.classList.add('is-open');
                pop.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                authMsg('');
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
                var identifier = mobileIdentifier();
                if (identifier.length !== 10) {
                    authMsg('Please enter a valid 10 digit mobile number.', 'error');
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
                var identifier = mobileIdentifier();
                var otp = (document.getElementById('tttAuthOtp') || {}).value || '';
                otp = otp.trim();
                if (identifier.length !== 10 || !otp) {
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
                    if (data.csrf) {
                        if (meta) meta.content = data.csrf;
                        if (window.TTT_PRODUCT_SHOW) window.TTT_PRODUCT_SHOW.csrf = data.csrf;
                    }
                    if (data.user) {
                        window.TTT_USER = Object.assign({}, window.TTT_USER || {}, data.user);
                    }
                    window.TTT_IS_AUTHENTICATED = true;
                    authMsg('Logged in. Continuing...', 'success');
                    var retry = pendingAuthRetry;
                    pendingAuthRetry = null;
                    setTimeout(function() {
                        closeTttAuthModal();
                        if (retry) {
                            retry();
                        } else {
                            window.location.reload();
                        }
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

            function initTttAuthMobileInput() {
                var input = document.getElementById('tttAuthIdentifier');
                if (!input) return;
                input.addEventListener('input', function() {
                    mobileIdentifier();
                });
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        window.sendTttOtp();
                    }
                });
            }

            function initHomeAuthPopup() {
                @guest
                    @if (request()->routeIs('home'))
                        setTimeout(function() {
                            window.openTttAuthModal();
                        }, 650);
                    @endif
                @endguest
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    initTttAuthMobileInput();
                    initHomeAuthPopup();
                });
            } else {
                initTttAuthMobileInput();
                initHomeAuthPopup();
            }
        })();
    </script>
    @php
        $authUserPayload = null;
        if (auth()->check()) {
            $checkoutUser = auth()->user();
            $checkoutAddresses = \Illuminate\Support\Facades\Schema::hasTable('addresses')
                ? $checkoutUser->addresses()->orderByDesc('is_default')->latest()->get()
                : collect();
            $checkoutDefaultAddress = $checkoutAddresses->firstWhere('is_default', true) ?: $checkoutAddresses->first();
            $authUserPayload = [
                'name' => optional($checkoutDefaultAddress)->name ?: $checkoutUser->name,
                'email' => $checkoutUser->email,
                'phone' => optional($checkoutDefaultAddress)->phone ?: $checkoutUser->phone,
                'address' => optional($checkoutDefaultAddress)->address_line ?: $checkoutUser->address,
                'city' => optional($checkoutDefaultAddress)->city ?: $checkoutUser->city,
                'state' => optional($checkoutDefaultAddress)->state ?: $checkoutUser->state,
                'pincode' => optional($checkoutDefaultAddress)->pincode ?: $checkoutUser->pincode,
                'address_id' => optional($checkoutDefaultAddress)->id,
                'address_type' => optional($checkoutDefaultAddress)->type ?: 'Home',
                'addresses' => $checkoutAddresses->map(fn($address) => [
                    'id' => $address->id,
                    'type' => $address->type ?: 'Home',
                    'name' => $address->name,
                    'phone' => $address->phone,
                    'address' => $address->address_line,
                    'city' => $address->city,
                    'state' => $address->state,
                    'pincode' => $address->pincode,
                    'latitude' => $address->latitude,
                    'longitude' => $address->longitude,
                    'location_source' => $address->location_source,
                    'is_default' => (bool) $address->is_default,
                    'full_address' => $address->full_address,
                ])->values(),
            ];
        }
        $razorpayKeyId = (string) env('RAZORPAY_KEY_ID', '');
    @endphp
    <script>
        window.TTT_USER = @json($authUserPayload);
        window.TTT_RAZORPAY_KEY = @json($razorpayKeyId);

        var gcoCartData = null;
        var gcoAppliedCoupon = null;

        function isGlobalAddressComplete(user) {
            if (!user) return false;
            var phone = String(user.phone || '').trim();
            var addr = String(user.address || '').trim();
            var pin = String(user.pincode || '').trim();
            return phone.length >= 10 && addr.length >= 4 && pin.length >= 4;
        }

        function gcoFormatMoney(val) {
            return '₹' + Math.round(Number(val || 0)).toLocaleString('en-IN');
        }

        function gcoGetDeliveryDate() {
            var d = new Date();
            d.setDate(d.getDate() + 3);
            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            var day = d.getDate();
            var suffix = 'th';
            if (day === 1 || day === 21 || day === 31) suffix = 'st';
            else if (day === 2 || day === 22) suffix = 'nd';
            else if (day === 3 || day === 23) suffix = 'rd';
            return day + suffix + ' ' + months[d.getMonth()];
        }

        var gcoActiveAddressType = 'Home';
        var gcoCurrentLocationCoords = null;
        var gcoStoreAddressUrl = @json(route('profile.addresses.store'));
        var gcoDefaultAddressUrlTemplate = @json(route('profile.addresses.default', ['address' => '__ADDRESS_ID__']));

        function gcoEscape(value) {
            return String(value || '').replace(/[&<>"']/g, function(ch) {
                return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[ch];
            });
        }

        function gcoUserAddresses() {
            var user = window.TTT_USER || {};
            return Array.isArray(user.addresses) ? user.addresses : [];
        }

        function gcoFindAddress(addressId) {
            addressId = Number(addressId || 0);
            return gcoUserAddresses().find(function(address) {
                return Number(address.id || 0) === addressId;
            }) || null;
        }

        function gcoDefaultAddress() {
            var addresses = gcoUserAddresses();
            return addresses.find(function(address) { return !!address.is_default; }) || addresses[0] || null;
        }

        function gcoAddressFull(address) {
            if (!address) return '';
            var line = address.address || address.address_line || '';
            var full = [line, address.city, address.state].filter(Boolean).join(', ');
            if (address.pincode) full += (full ? ' - ' : '') + address.pincode;
            return full;
        }

        function gcoAddressToUser(address) {
            if (!address) return {};
            return {
                name: address.name || (window.TTT_USER || {}).name || 'Customer',
                email: (window.TTT_USER || {}).email || '',
                phone: address.phone || (window.TTT_USER || {}).phone || '',
                address: address.address || address.address_line || '',
                city: address.city || '',
                state: address.state || '',
                pincode: address.pincode || '',
                address_id: address.id || null,
                address_type: address.type || 'Home'
            };
        }

        function gcoApplyUserPayload(userPayload) {
            window.TTT_USER = Object.assign({}, window.TTT_USER || {}, userPayload || {});
            if (!Array.isArray(window.TTT_USER.addresses)) window.TTT_USER.addresses = [];
            gcoSyncMainAddress();
        }

        function gcoSyncMainAddress() {
            var user = window.TTT_USER || {};
            var selected = gcoFindAddress(user.address_id) || gcoDefaultAddress();
            if (selected) {
                Object.assign(user, gcoAddressToUser(selected));
                window.TTT_USER = user;
            }

            var nameEl = document.getElementById('gcoDeliverName');
            if (nameEl) nameEl.textContent = user.name || 'Customer';

            var addrEl = document.getElementById('gcoDeliverAddress');
            if (addrEl) {
                var fullAddr = gcoAddressFull(user);
                addrEl.textContent = fullAddr || 'No address added yet. Please tap to add address.';
            }

            var contactEl = document.getElementById('gcoDeliverContact');
            if (contactEl) {
                var phoneStr = user.phone ? (user.phone.startsWith('+91') ? user.phone : '+91 ' + user.phone) : '';
                contactEl.textContent = [phoneStr, user.email].filter(Boolean).join(' | ');
            }
        }

        window.gcoShowMain = function() {
            document.querySelectorAll('.gco-view').forEach(function(v) { v.classList.remove('active'); });
            var main = document.getElementById('gcoViewMain');
            if (main) main.classList.add('active');
        };

        var gcoTempSelectedAddressId = null;

        window.gcoShowAddressSelect = function() {
            var user = window.TTT_USER || {};
            var listEl = document.getElementById('gcoAddressList');
            var addresses = gcoUserAddresses();
            var selectedId = Number(user.address_id || 0);

            if (gcoTempSelectedAddressId === null) {
                var def = addresses.find(function(a) { return selectedId ? Number(a.id || 0) === selectedId : !!a.is_default; }) || addresses[0];
                gcoTempSelectedAddressId = def ? Number(def.id || 0) : 0;
            }

            if (listEl) {
                if (!addresses.length && user.address) {
                    addresses = [Object.assign({ id: 0, type: user.address_type || 'Home', is_default: true }, user)];
                }

                if (!addresses.length) {
                    listEl.innerHTML = '<div class="gco-address-empty" style="text-align:center; padding:30px 15px; color:#64748b;">' +
                        '<i class="bi bi-geo-alt" style="font-size:36px; color:#cbd5e1; display:block; margin-bottom:8px;"></i>' +
                        '<b style="font-size:14px; color:#0f172a; display:block;">No saved address yet</b>' +
                        '<span style="font-size:12px;">Add a new address or use current location below.</span>' +
                    '</div>';
                } else {
                    listEl.innerHTML = addresses.map(function(address) {
                        var addrId = Number(address.id || 0);
                        var isSelected = (gcoTempSelectedAddressId === addrId);
                        var tagClass = address.type === 'Work' ? 'gco-tag-work' : (address.type === 'Current Location' ? 'gco-tag-home' : 'gco-tag-home');
                        return '<div class="gco-address-select-card ' + (isSelected ? 'is-selected' : '') + '" onclick="gcoPickAddress(' + addrId + ')" role="button" tabindex="0">' +
                            '<div class="gco-address-radio-wrap">' +
                                '<input type="radio" name="gco_addr_choice" value="' + addrId + '" ' + (isSelected ? 'checked' : '') + ' onclick="event.stopPropagation(); gcoPickAddress(' + addrId + ')">' +
                            '</div>' +
                            '<div class="gco-address-card-content">' +
                                '<div class="gco-card-user-row">' +
                                    '<div class="gco-user-name-tag">' +
                                        '<span>' + gcoEscape(address.name || user.name || 'Customer') + '</span>' +
                                        '<span class="' + tagClass + '">' + gcoEscape(address.type || 'Home') + '</span>' +
                                        (address.is_default ? '<span class="gco-default-badge">Default</span>' : '') +
                                    '</div>' +
                                    '<button type="button" class="gco-edit-icon-btn" onclick="event.stopPropagation(); gcoEditSpecificAddress(' + addrId + ')" title="Edit Address">' +
                                        '<i class="bi bi-pencil-square"></i> Edit' +
                                    '</button>' +
                                '</div>' +
                                '<div class="gco-select-card-address">' + gcoEscape(gcoAddressFull(address)) + '</div>' +
                                '<div class="gco-select-card-contact"><i class="bi bi-telephone-fill"></i> ' + gcoEscape([address.phone || user.phone, user.email].filter(Boolean).join(' | ')) + '</div>' +
                            '</div>' +
                        '</div>';
                    }).join('');
                }
            }

            document.querySelectorAll('.gco-view').forEach(function(v) { v.classList.remove('active'); });
            var selView = document.getElementById('gcoViewAddressSelect');
            if (selView) selView.classList.add('active');
        };

        window.gcoPickAddress = function(addressId) {
            gcoTempSelectedAddressId = Number(addressId || 0);
            var cards = document.querySelectorAll('.gco-address-select-card');
            cards.forEach(function(card) {
                var radio = card.querySelector('input[type="radio"]');
                if (radio) {
                    var val = Number(radio.value || 0);
                    var checked = (val === gcoTempSelectedAddressId);
                    radio.checked = checked;
                    card.classList.toggle('is-selected', checked);
                }
            });
        };

        window.gcoConfirmSelectedAddress = function() {
            if (gcoTempSelectedAddressId !== null) {
                if (gcoTempSelectedAddressId > 0) {
                    gcoSelectSavedAddress(gcoTempSelectedAddressId);
                } else {
                    gcoSelectAndDeliver();
                }
            } else {
                gcoShowMain();
            }
        };

        window.gcoEditSpecificAddress = function(addressId) {
            var addr = gcoFindAddress(addressId);
            gcoShowAddressEdit(false, addr);
        };

        window.gcoShowAddressEdit = function(isNew, existingAddress) {
            var titleEl = document.getElementById('gcoEditViewTitle');
            if (titleEl) titleEl.textContent = isNew ? 'Add Address' : 'Edit Address';

            var user = window.TTT_USER || {};
            var selectedAddress = existingAddress || gcoFindAddress(user.address_id) || gcoDefaultAddress();
            var source = selectedAddress ? Object.assign({}, user, gcoAddressToUser(selectedAddress)) : user;
            gcoCurrentLocationCoords = null;
            gcoHideLocationMap();
            if (isNew) {
                if (document.getElementById('gcoInPin')) document.getElementById('gcoInPin').value = '';
                if (document.getElementById('gcoInCity')) document.getElementById('gcoInCity').value = '';
                if (document.getElementById('gcoInState')) document.getElementById('gcoInState').value = '';
                if (document.getElementById('gcoInFlat')) document.getElementById('gcoInFlat').value = '';
                if (document.getElementById('gcoInArea')) document.getElementById('gcoInArea').value = '';
                gcoSetAddressType('Home');
            } else {
                if (document.getElementById('gcoInPin')) document.getElementById('gcoInPin').value = source.pincode || '';
                if (document.getElementById('gcoInCity')) document.getElementById('gcoInCity').value = (source.city || '').toUpperCase();
                if (document.getElementById('gcoInState')) document.getElementById('gcoInState').value = (source.state || '').toUpperCase();
                if (document.getElementById('gcoInArea')) document.getElementById('gcoInArea').value = source.address || '';
                if (document.getElementById('gcoInFlat')) document.getElementById('gcoInFlat').value = '';
                gcoSetAddressType(source.address_type || 'Home');
            }

            if (document.getElementById('gcoInName')) document.getElementById('gcoInName').value = user.name || '';
            if (document.getElementById('gcoInEmail')) document.getElementById('gcoInEmail').value = user.email || '';
            if (document.getElementById('gcoInPhone')) document.getElementById('gcoInPhone').value = source.phone || user.phone || '';

            var msg = document.getElementById('gcoFormMsg');
            if (msg) msg.textContent = '';

            document.querySelectorAll('.gco-view').forEach(function(v) { v.classList.remove('active'); });
            var editView = document.getElementById('gcoViewAddressEdit');
            if (editView) editView.classList.add('active');
        };

        window.gcoSetAddressType = function(type) {
            gcoActiveAddressType = type;
            var homePill = document.getElementById('gcoTypeHome');
            var workPill = document.getElementById('gcoTypeWork');
            if (homePill) homePill.classList.toggle('active', type === 'Home');
            if (workPill) workPill.classList.toggle('active', type === 'Work');
        };

        function gcoHideLocationMap() {
            var wrap = document.getElementById('gcoLocationMap');
            var frame = document.getElementById('gcoLocationMapFrame');
            var link = document.getElementById('gcoLocationMapLink');
            var coords = document.getElementById('gcoLocationMapCoords');
            if (wrap) wrap.hidden = true;
            if (frame) frame.removeAttribute('src');
            if (link) link.href = '#';
            if (coords) coords.textContent = 'Location selected';
        }

        function gcoShowLocationMap(lat, lng) {
            var wrap = document.getElementById('gcoLocationMap');
            var frame = document.getElementById('gcoLocationMapFrame');
            var link = document.getElementById('gcoLocationMapLink');
            var coords = document.getElementById('gcoLocationMapCoords');
            var point = Number(lat).toFixed(6) + ',' + Number(lng).toFixed(6);
            var mapUrl = 'https://maps.google.com/maps?q=' + encodeURIComponent(point) + '&z=17&output=embed';
            var openUrl = 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(point);
            if (frame) frame.src = mapUrl;
            if (link) link.href = openUrl;
            if (coords) coords.textContent = point;
            if (wrap) wrap.hidden = false;
        }

        window.gcoStartCurrentLocationAddress = function() {
            gcoShowAddressEdit(true);
            setTimeout(function() {
                gcoUseCurrentLocation();
            }, 120);
        };

        window.gcoUseCurrentLocation = function() {
            var msg = document.getElementById('gcoFormMsg');
            if (!navigator.geolocation) {
                if (msg) { msg.textContent = 'Current location is not supported in this browser.'; msg.style.color = '#dc2626'; }
                return;
            }

            if (msg) { msg.textContent = 'Fetching current location...'; msg.style.color = '#0f4c81'; }

            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                gcoCurrentLocationCoords = { latitude: lat, longitude: lng };
                gcoSetAddressType('Current Location');
                gcoShowLocationMap(lat, lng);

                var fallback = 'Current Location (' + lat.toFixed(5) + ', ' + lng.toFixed(5) + ')';
                if (document.getElementById('gcoInArea')) document.getElementById('gcoInArea').value = fallback;
                if (document.getElementById('gcoInFlat')) document.getElementById('gcoInFlat').value = '';

                fetch('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + encodeURIComponent(lat) + '&lon=' + encodeURIComponent(lng))
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        var a = data.address || {};
                        var city = a.city || a.town || a.village || a.county || a.state_district || '';
                        var state = a.state || '';
                        var pin = a.postcode || '';
                        if (document.getElementById('gcoInArea')) document.getElementById('gcoInArea').value = data.display_name || fallback;
                        if (pin && document.getElementById('gcoInPin')) document.getElementById('gcoInPin').value = pin;
                        if (city && document.getElementById('gcoInCity')) document.getElementById('gcoInCity').value = city.toUpperCase();
                        if (state && document.getElementById('gcoInState')) document.getElementById('gcoInState').value = state.toUpperCase();
                        if (msg) { msg.textContent = 'Location filled. Please check and save address.'; msg.style.color = '#16a34a'; }
                    })
                    .catch(function() {
                        if (msg) { msg.textContent = 'Location filled with coordinates. Please add city, state and pincode.'; msg.style.color = '#f97316'; }
                    });
            }, function() {
                if (msg) { msg.textContent = 'Location permission denied. Please enter address manually.'; msg.style.color = '#dc2626'; }
            }, { enableHighAccuracy: true, timeout: 12000, maximumAge: 60000 });
        };

        window.gcoLookupPincode = function(pin) {
            pin = String(pin || '').trim().replace(/[^0-9]/g, '');
            if (pin.length !== 6) return;

            var pinWrap = document.getElementById('gcoPinWrap');
            if (pinWrap) pinWrap.classList.add('is-loading');

            fetch('https://api.postalpincode.in/pincode/' + pin)
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (pinWrap) pinWrap.classList.remove('is-loading');
                    if (Array.isArray(data) && data[0] && data[0].Status === 'Success' && data[0].PostOffice && data[0].PostOffice.length > 0) {
                        var po = data[0].PostOffice[0];
                        var city = (po.District || (po.Block || po.Circle || '')).toUpperCase();
                        var state = (po.State || '').toUpperCase();
                        if (document.getElementById('gcoInCity')) document.getElementById('gcoInCity').value = city;
                        if (document.getElementById('gcoInState')) document.getElementById('gcoInState').value = state;
                    }
                })
                .catch(function() {
                    if (pinWrap) pinWrap.classList.remove('is-loading');
                });
        };

        window.gcoSaveAddressForm = function() {
            var msg = document.getElementById('gcoFormMsg');
            var btn = document.getElementById('gcoSaveBtn');

            var pin = (document.getElementById('gcoInPin') || {}).value || '';
            var city = (document.getElementById('gcoInCity') || {}).value || '';
            var state = (document.getElementById('gcoInState') || {}).value || '';
            var flat = (document.getElementById('gcoInFlat') || {}).value || '';
            var area = (document.getElementById('gcoInArea') || {}).value || '';
            var name = (document.getElementById('gcoInName') || {}).value || '';
            var email = (document.getElementById('gcoInEmail') || {}).value || '';
            var phone = (document.getElementById('gcoInPhone') || {}).value || '';

            if (!pin || pin.trim().length < 6) {
                if (msg) { msg.textContent = 'Please enter valid 6-digit Pincode.'; msg.style.color = '#dc2626'; }
                document.getElementById('gcoInPin')?.focus();
                return;
            }
            if (!city.trim() || !state.trim()) {
                if (msg) { msg.textContent = 'City and State are required.'; msg.style.color = '#dc2626'; }
                return;
            }
            if (!flat.trim() && !area.trim()) {
                if (msg) { msg.textContent = 'Please enter Flat/House no. or Street Area.'; msg.style.color = '#dc2626'; }
                document.getElementById('gcoInFlat')?.focus();
                return;
            }
            if (!name.trim() || name.trim().length < 2) {
                if (msg) { msg.textContent = 'Please enter Full Name.'; msg.style.color = '#dc2626'; }
                document.getElementById('gcoInName')?.focus();
                return;
            }
            if (!phone.trim() || phone.trim().length < 10) {
                if (msg) { msg.textContent = 'Please enter valid 10-digit Mobile Number.'; msg.style.color = '#dc2626'; }
                document.getElementById('gcoInPhone')?.focus();
                return;
            }

            var streetAddress = [flat.trim(), area.trim()].filter(Boolean).join(' ');

            var payload = {
                type: gcoActiveAddressType || 'Home',
                name: name.trim(),
                email: email.trim(),
                phone: phone.trim(),
                address: streetAddress,
                city: city.trim(),
                state: state.trim(),
                pincode: pin.trim(),
                latitude: gcoCurrentLocationCoords ? gcoCurrentLocationCoords.latitude : null,
                longitude: gcoCurrentLocationCoords ? gcoCurrentLocationCoords.longitude : null,
                location_source: gcoCurrentLocationCoords ? 'browser' : null
            };

            if (btn) { btn.textContent = 'Saving Address...'; btn.disabled = true; }

            fetch(gcoStoreAddressUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.tttCsrfToken ? window.tttCsrfToken() : '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (!res.success) throw new Error(res.message || 'Failed to save address.');
                gcoApplyUserPayload(res.user || payload);
                if (btn) { btn.textContent = 'Save Address'; btn.disabled = false; }
                if (msg) { msg.textContent = 'Address saved successfully!'; msg.style.color = '#16a34a'; }

                setTimeout(function() {
                    gcoShowMain();
                }, 400);
            })
            .catch(function(err) {
                if (btn) { btn.textContent = 'Save Address'; btn.disabled = false; }
                if (msg) { msg.textContent = err.message || 'Failed to save address. Please try again.'; msg.style.color = '#dc2626'; }
            });
        };

        window.gcoSelectSavedAddress = function(addressId) {
            var selected = gcoFindAddress(addressId);
            if (selected) {
                var addresses = gcoUserAddresses().map(function(address) {
                    address.is_default = Number(address.id || 0) === Number(addressId || 0);
                    return address;
                });
                gcoApplyUserPayload(Object.assign({}, gcoAddressToUser(selected), { addresses: addresses }));
                gcoShowMain();
            }

            fetch(gcoDefaultAddressUrlTemplate.replace('__ADDRESS_ID__', encodeURIComponent(addressId)), {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.tttCsrfToken ? window.tttCsrfToken() : '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.success && res.user) {
                    gcoApplyUserPayload(res.user);
                }
            })
            .catch(function() {});
        };

        window.gcoSelectAndDeliver = function() {
            gcoSyncMainAddress();
            gcoShowMain();
        };

        window.gcoChangeItemQty = function(index, delta) {
            if (!gcoCartData || !gcoCartData.items || !gcoCartData.items[index]) return;
            var item = gcoCartData.items[index];
            var newQty = Number(item.quantity || 1) + delta;
            if (newQty <= 0) {
                window.gcoRemoveItem(index);
                return;
            }
            item.quantity = Math.min(10, newQty);
            gcoRecalculate();
        };

        window.gcoRemoveItem = function(index) {
            if (!gcoCartData || !gcoCartData.items || !gcoCartData.items[index]) return;
            var item = gcoCartData.items[index];
            var itemName = item.name || 'Item';
            var key = item.key || item.product_id;

            if (key) {
                fetch('/cart/remove/' + encodeURIComponent(key), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': 'DELETE'
                    },
                    body: JSON.stringify({ key: key })
                }).catch(function() {});
            }

            gcoCartData.items.splice(index, 1);
            gcoRecalculate();

            if (typeof window.tttNotify === 'function') {
                window.tttNotify('"' + itemName + '" removed from cart.', 'info');
            }
        };

        function gcoRecalculate() {
            if (!gcoCartData) return;
            var items = gcoCartData.items || [];
            var totalCount = 0;
            var subtotal = 0;
            var totalMrp = 0;

            items.forEach(function(i) {
                var q = Number(i.quantity || 1);
                var p = Number(i.price || 0);
                var mrp = Number(i.original_price || p);
                totalCount += q;
                subtotal += (p * q);
                totalMrp += (mrp * q);
            });

            var discountOnMrp = Math.max(0, totalMrp - subtotal);
            var isOnlineMode = (typeof gcoSelectedPaymentMode === 'undefined' || gcoSelectedPaymentMode === 'online');
            var prepaidDiscount = isOnlineMode ? (Math.round(subtotal * 0.05 * 100) / 100) : 0;
            var shipping = subtotal >= 999 ? 0 : 50;
            var couponDisc = gcoAppliedCoupon ? Number(gcoAppliedCoupon.discount || 0) : 0;
            var finalTotal = Math.max(0, subtotal + shipping - prepaidDiscount - couponDisc);
            var totalSavings = discountOnMrp + prepaidDiscount + couponDisc;

            // Render Items List
            var itemsListEl = document.getElementById('gcoOrderItemsList');
            if (itemsListEl) {
                var deliveryText = gcoGetDeliveryDate();
                itemsListEl.innerHTML = items.map(function(item, idx) {
                    var q = Number(item.quantity || 1);
                    var p = Number(item.price || 0);
                    var orig = Number(item.original_price || p);
                    var discPct = orig > p ? Math.round(((orig - p) / orig) * 100) : 0;
                    return '<div class="gco-item-card">' +
                        '<div class="gco-item-img-wrap">' +
                            '<img src="' + (item.image || '{{ asset('images/placeholder-product.jpg') }}') + '" class="gco-item-img" alt="' + gcoEscape(item.name || 'Product') + '" onerror="this.src=\'{{ asset('images/placeholder-product.jpg') }}\'">' +
                            (discPct > 0 ? '<span class="gco-item-img-badge">-' + discPct + '%</span>' : '') +
                        '</div>' +
                        '<div class="gco-item-info">' +
                            '<div class="gco-item-title-row">' +
                                '<div class="gco-item-title">' + gcoEscape(item.name || 'Product') + '</div>' +
                                '<button type="button" class="btn-gco-item-trash" onclick="event.stopPropagation(); gcoRemoveItem(' + idx + ');" title="Remove item"><i class="bi bi-trash3"></i></button>' +
                            '</div>' +
                            '<div class="gco-item-meta-row">' +
                                '<span class="gco-item-size-pill"><i class="bi bi-rulers"></i> Size: ' + gcoEscape(item.size || 'Free Size') + '</span>' +
                                (item.design_side || item.designSide ? '<span class="gco-item-size-pill"><i class="bi bi-aspect-ratio"></i> Print: ' + gcoEscape(String(item.design_side || item.designSide).toUpperCase()) + '</span>' : '') +
                            '</div>' +
                            '<div class="gco-item-stepper-row">' +
                                '<div class="gco-stepper">' +
                                    '<button type="button" aria-label="Decrease quantity" onclick="event.stopPropagation(); gcoChangeItemQty(' + idx + ', -1)">−</button>' +
                                    '<span>' + q + '</span>' +
                                    '<button type="button" aria-label="Increase quantity" onclick="event.stopPropagation(); gcoChangeItemQty(' + idx + ', 1)">+</button>' +
                                '</div>' +
                                '<span class="gco-stepper-label">Qty: ' + q + '</span>' +
                            '</div>' +
                            '<div class="gco-delivery-tag"><i class="bi bi-truck text-success"></i> Delivery by <b>' + deliveryText + '</b></div>' +
                        '</div>' +
                        '<div class="gco-item-price-side">' +
                            (orig > p ? '<span class="gco-item-strike-price">' + gcoFormatMoney(orig * q) + '</span>' : '') +
                            '<span class="gco-item-final-price">' + gcoFormatMoney(p * q) + '</span>' +
                            (discPct > 0 ? '<span class="gco-item-save-pill">' + discPct + '% OFF</span>' : '') +
                        '</div>' +
                    '</div>';
                }).join('');
            }

            // Update UI elements
            var itemCountEl = document.getElementById('gcoItemCount');
            if (itemCountEl) itemCountEl.textContent = totalCount + ' ' + (totalCount === 1 ? 'item' : 'items');

            var savingsTagEl = document.getElementById('gcoSavingsTag');
            if (savingsTagEl) savingsTagEl.innerHTML = '<b id="gcoSavingsAmount">' + gcoFormatMoney(totalSavings) + '</b> saved so far';

            var mrpTopEl = document.getElementById('gcoMrpTotalTop');
            if (mrpTopEl) mrpTopEl.textContent = gcoFormatMoney(totalMrp);

            var totalTopEl = document.getElementById('gcoTotalTop');
            if (totalTopEl) totalTopEl.textContent = gcoFormatMoney(finalTotal);

            var mrpTotalEl = document.getElementById('gcoMrpTotal');
            if (mrpTotalEl) mrpTotalEl.textContent = gcoFormatMoney(totalMrp);

            var mrpDiscEl = document.getElementById('gcoMrpDiscount');
            if (mrpDiscEl) mrpDiscEl.textContent = '-' + gcoFormatMoney(discountOnMrp);

            var subtotalEl = document.getElementById('gcoSubtotal');
            if (subtotalEl) subtotalEl.textContent = gcoFormatMoney(subtotal);

            var prepaidDiscEl = document.getElementById('gcoPrepaidDiscount');
            if (prepaidDiscEl) prepaidDiscEl.textContent = '-' + gcoFormatMoney(prepaidDiscount);

            var shipEl = document.getElementById('gcoShipping');
            if (shipEl) shipEl.textContent = shipping === 0 ? 'FREE' : gcoFormatMoney(shipping);

            var shipBadge = document.getElementById('gcoShippingBadge');
            if (shipBadge) {
                shipBadge.textContent = shipping === 0 ? 'FREE' : gcoFormatMoney(shipping);
                shipBadge.className = shipping === 0 ? 'shipping-fee-pill is-free' : 'shipping-fee-pill';
            }

            var finalPayEl = document.getElementById('gcoFinalPay');
            if (finalPayEl) finalPayEl.textContent = gcoFormatMoney(finalTotal);

            var footerTotalEl = document.getElementById('gcoFooterTotal');
            if (footerTotalEl) footerTotalEl.textContent = gcoFormatMoney(finalTotal);

            var footerSavedEl = document.getElementById('gcoFooterSaved');
            if (footerSavedEl) footerSavedEl.textContent = 'Saved ' + gcoFormatMoney(totalSavings);

            var pointsEl = document.getElementById('gcoLoyaltyPoints');
            if (pointsEl) pointsEl.textContent = Math.max(5, Math.round(finalTotal * 0.02)) + ' loyalty points';

            gcoCartData.subtotal = subtotal;
            gcoCartData.shipping = shipping;
            gcoCartData.total = finalTotal;
        }

        window.gcoSelectCoupon = function(code) {
            var input = document.getElementById('gcoCouponInput');
            if (input) {
                input.value = code;
                window.gcoApplyCoupon();
            }
        };

        window.gcoApplyCoupon = function(overrideCode) {
            var input = document.getElementById('gcoCouponInput');
            var code = (overrideCode || (input ? input.value : '')).trim().toUpperCase();
            if (!code) {
                if (typeof window.tttNotify === 'function') window.tttNotify('Please enter a coupon code.', 'error');
                return;
            }
            
            fetch('{{ route("coupon.apply") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ code: code, coupon_code: code })
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.success) {
                    var disc = Number(res.discount || 0);
                    gcoAppliedCoupon = { code: code, discount: disc };
                    if (input) input.value = code;
                    if (typeof window.showCouponPartyPopup === 'function') {
                        window.showCouponPartyPopup({
                            code: code,
                            discount: disc,
                            message: res.message || ('Coupon ' + code + ' applied successfully!')
                        });
                    } else if (typeof window.tttNotify === 'function') {
                        window.tttNotify(res.message || ('Coupon ' + code + ' applied!'), 'success');
                    }
                    gcoRecalculate();
                } else {
                    if (typeof window.tttNotify === 'function') {
                        window.tttNotify(res.message || 'Invalid or expired coupon code.', 'error');
                    }
                }
            })
            .catch(function() {
                if (code === 'SHARKTANK10' || code === 'TREND10') {
                    var disc = Math.round(gcoCartData.subtotal * 0.1);
                    gcoAppliedCoupon = { code: code, discount: disc };
                    if (typeof window.showCouponPartyPopup === 'function') {
                        window.showCouponPartyPopup({
                            code: code,
                            discount: disc,
                            message: 'Coupon ' + code + ' applied! Saved ' + gcoFormatMoney(disc)
                        });
                    } else if (typeof window.tttNotify === 'function') {
                        window.tttNotify('Coupon ' + code + ' applied! Saved ' + gcoFormatMoney(disc), 'success');
                    }
                    gcoRecalculate();
                } else {
                    if (typeof window.tttNotify === 'function') {
                        window.tttNotify('Could not apply coupon.', 'error');
                    }
                }
            });
        };

        window.openGlobalCheckoutModal = function(cart, productFallback) {
            gcoShowMain();
            var user = window.TTT_USER || {};
            gcoSyncMainAddress();
            user = window.TTT_USER || {};
            
            // Sync User Bar & Address Card
            var userNumber = user.phone || user.email || 'Customer';
            var numEl = document.getElementById('gcoUserNumber');
            if (numEl) numEl.textContent = userNumber;

            var nameEl = document.getElementById('gcoDeliverName');
            if (nameEl) nameEl.textContent = user.name || 'Customer';

            var addrEl = document.getElementById('gcoDeliverAddress');
            if (addrEl) {
                var fullAddr = [user.address, user.city, user.state].filter(Boolean).join(', ');
                if (user.pincode) fullAddr += (fullAddr ? ' - ' : '') + user.pincode;
                addrEl.textContent = fullAddr || 'No address added yet. Please tap to add address.';
            }

            var contactEl = document.getElementById('gcoDeliverContact');
            if (contactEl) {
                contactEl.textContent = [user.phone, user.email].filter(Boolean).join(' | ');
            }

            // Normalize Cart Data
            cart = cart || {};
            var items = cart.items || [];
            if (!items.length && productFallback) {
                items = [{
                    id: productFallback.id,
                    name: productFallback.name || 'Product',
                    image: productFallback.image || '',
                    price: Number(productFallback.price || 0),
                    original_price: Number(productFallback.original_price || productFallback.price || 0),
                    size: productFallback.size || 'Regular',
                    color: productFallback.color || '',
                    design_side: productFallback.design_side || productFallback.designSide || '',
                    quantity: Number(productFallback.quantity || 1)
                }];
            }

            gcoCartData = {
                items: items,
                subtotal: Number(cart.subtotal || 0),
                shipping: Number(cart.shipping || 0),
                discount: Number(cart.discount || 0),
                total: Number(cart.total || 0)
            };

            gcoRecalculate();

            // Ensure order summary is collapsed by default on open
            var summaryDetails = document.getElementById('gcoSummaryDetails');
            var summaryCard = document.getElementById('gcoSummaryCard');
            var summaryChevron = document.getElementById('gcoSummaryChevron');
            if (summaryDetails) {
                summaryDetails.classList.remove('is-open');
                summaryDetails.style.setProperty('display', 'none', 'important');
            }
            if (summaryCard) summaryCard.classList.remove('is-open');
            if (summaryChevron) summaryChevron.style.transform = 'rotate(0deg)';

            var pop = document.getElementById('globalCheckoutPop');
            if (pop) {
                pop.classList.add('is-open');
                pop.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }
        };

        window.closeGlobalCheckoutPop = function() {
            var pop = document.getElementById('globalCheckoutPop');
            if (pop) {
                pop.classList.remove('is-open');
                pop.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }
        };

        window.gcoToggleSummary = function(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            var details = document.getElementById('gcoSummaryDetails');
            var card = document.getElementById('gcoSummaryCard');
            var chevron = document.getElementById('gcoSummaryChevron');
            if (!details) return;

            var isOpen = details.classList.contains('is-open') || (details.style.display === 'block');
            if (isOpen) {
                details.classList.remove('is-open');
                details.style.setProperty('display', 'none', 'important');
                if (card) card.classList.remove('is-open');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            } else {
                details.classList.add('is-open');
                details.style.setProperty('display', 'block', 'important');
                if (card) card.classList.add('is-open');
                if (chevron) chevron.style.transform = 'rotate(180deg)';
            }
        };

        window.TTT_PAYMENT_CONFIG = {
            primary_gateway: "{{ $activePrimaryGateway ?? 'phonepe' }}",
            phonepe_enabled: {{ ($isPhonePeEnabled ?? true) ? 'true' : 'false' }},
            razorpay_enabled: {{ ($isRazorpayEnabled ?? true) ? 'true' : 'false' }},
            cod_enabled: {{ ($isCodEnabled ?? true) ? 'true' : 'false' }}
        };

        var gcoSelectedPaymentMode = 'online';
        var gcoOrderInProgress = false;

        function showTttOrderLoader(title, text) {
            var loader = document.getElementById('tttOrderLoader');
            if (!loader) {
                loader = document.createElement('div');
                loader.id = 'tttOrderLoader';
                loader.className = 'ttt-order-loader';
                loader.setAttribute('aria-hidden', 'true');
                loader.innerHTML = '<div class="ttt-order-loader__card" role="status" aria-live="polite"><div class="ttt-order-loader__spinner"></div><div class="ttt-order-loader__title" id="tttOrderLoaderTitle"></div><div class="ttt-order-loader__text" id="tttOrderLoaderText"></div></div>';
                document.body.appendChild(loader);
            }

            loader.classList.remove('is-success');
            var titleEl = document.getElementById('tttOrderLoaderTitle');
            var textEl = document.getElementById('tttOrderLoaderText');
            if (titleEl) titleEl.textContent = title || 'Placing your order';
            if (textEl) textEl.textContent = text || 'Please wait while we securely process your order.';
            loader.classList.add('is-active');
            loader.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function hideTttOrderLoader() {
            var loader = document.getElementById('tttOrderLoader');
            if (!loader) return;
            loader.classList.remove('is-active');
            loader.classList.remove('is-success');
            loader.setAttribute('aria-hidden', 'true');

            var pop = document.getElementById('globalCheckoutPop');
            if (!pop || !pop.classList.contains('is-open')) {
                document.body.style.overflow = '';
            }
        }

        function gcoShowOrderSuccessAndRedirect(redirectUrl, orderNumber) {
            var loader = document.getElementById('tttOrderLoader');
            if (!loader) {
                window.location.href = redirectUrl;
                return;
            }

            var titleEl = document.getElementById('tttOrderLoaderTitle');
            var textEl = document.getElementById('tttOrderLoaderText');
            loader.classList.add('is-active', 'is-success');
            loader.setAttribute('aria-hidden', 'false');
            if (titleEl) titleEl.textContent = 'Order placed successfully';
            if (textEl) {
                textEl.textContent = orderNumber
                    ? 'Order #' + orderNumber + ' confirmed. Redirecting to success page...'
                    : 'Your order is confirmed. Redirecting to success page...';
            }
            document.body.style.overflow = 'hidden';

            window.setTimeout(function() {
                window.location.href = redirectUrl;
            }, 1200);
        }

        function gcoSetOrderProcessing(isProcessing, title, text, buttonText) {
            gcoOrderInProgress = !!isProcessing;
            var btn = document.getElementById('gcoDynamicPayBtn');
            var btnText = document.getElementById('gcoDynamicBtnText');
            var btnIcon = document.getElementById('gcoDynamicBtnIcon');

            if (isProcessing) {
                showTttOrderLoader(title, text);
                if (btn) btn.disabled = true;
                if (btnText) btnText.textContent = buttonText || 'PROCESSING...';
                if (btnIcon) btnIcon.className = 'spinner-border spinner-border-sm';
                return;
            }

            hideTttOrderLoader();
            if (btn) btn.disabled = false;
            if (typeof window.gcoSelectPayMethod === 'function') {
                window.gcoSelectPayMethod(gcoSelectedPaymentMode);
            }
        }

        function gcoSelectedItemDesignSide(item) {
            return item
                ? (item.design_side || item.designSide || (typeof currentSliderPrintSide !== 'undefined' ? currentSliderPrintSide : '') || '')
                : (typeof currentSliderPrintSide !== 'undefined' ? currentSliderPrintSide : '');
        }

        window.gcoSelectPayMethod = function(mode) {
            gcoSelectedPaymentMode = mode;
            var radioOnline = document.getElementById('gcoModeOnline');
            var radioCod = document.getElementById('gcoModeCod');
            var cardOnline = document.getElementById('gcoPayCardOnline');
            var cardCod = document.getElementById('gcoPayCardCod');
            var btn = document.getElementById('gcoDynamicPayBtn');
            var btnText = document.getElementById('gcoDynamicBtnText');
            var btnIcon = document.getElementById('gcoDynamicBtnIcon');
            var cashbackPill = document.getElementById('gcoFooterCashback');

            if (mode === 'online') {
                if (radioOnline) radioOnline.checked = true;
                if (cardOnline) {
                    cardOnline.style.borderColor = '#5f259f';
                    cardOnline.style.background = '#faf5ff';
                }
                if (cardCod) {
                    cardCod.style.borderColor = '#e2e8f0';
                    cardCod.style.background = '#ffffff';
                }
                if (cashbackPill) cashbackPill.style.display = 'block';

                var config = window.TTT_PAYMENT_CONFIG || {};
                var primary = config.primary_gateway || 'phonepe';
                if (btn) {
                    if (primary === 'razorpay' || (!config.phonepe_enabled && config.razorpay_enabled)) {
                        btn.style.background = 'linear-gradient(135deg, #0284c7 0%, #00285a 100%)';
                        if (btnText) btnText.textContent = 'PROCEED TO PAY VIA RAZORPAY';
                    } else {
                        btn.style.background = 'linear-gradient(135deg, #5f259f 0%, #00285a 100%)';
                        if (btnText) btnText.textContent = 'PROCEED TO PAY VIA PHONEPE / UPI';
                    }
                    if (btnIcon) btnIcon.className = 'bi bi-shield-lock-fill';
                }
            } else {
                if (radioCod) radioCod.checked = true;
                if (cardCod) {
                    cardCod.style.borderColor = '#059669';
                    cardCod.style.background = '#f0fdf4';
                }
                if (cardOnline) {
                    cardOnline.style.borderColor = '#e2e8f0';
                    cardOnline.style.background = '#ffffff';
                }
                if (cashbackPill) cashbackPill.style.display = 'none';

                if (btn) {
                    btn.style.background = 'linear-gradient(135deg, #059669 0%, #0f172a 100%)';
                    if (btnText) btnText.textContent = 'PLACE ORDER WITH CASH ON DELIVERY (COD)';
                    if (btnIcon) btnIcon.className = 'bi bi-cash-stack';
                }
            }

            gcoRecalculate();
        };

        window.gcoProceedToActiveGateway = function() {
            if (gcoOrderInProgress) {
                return;
            }

            if (gcoSelectedPaymentMode === 'cod') {
                return window.gcoPlaceCodOrder();
            }

            var config = window.TTT_PAYMENT_CONFIG || {};
            var primary = config.primary_gateway || 'phonepe';

            if (primary === 'razorpay' || (!config.phonepe_enabled && config.razorpay_enabled)) {
                return window.gcoProceedToRazorpay();
            }
            return window.gcoProceedToPhonePe();
        };

        window.gcoPlaceCodOrder = function() {
            var user = window.TTT_USER || {};
            if (!isGlobalAddressComplete(user)) {
                gcoShowAddressEdit(true);
                return;
            }

            var selectedItem = (gcoCartData && gcoCartData.items[0]) ? gcoCartData.items[0] : null;
            var productId = selectedItem ? (selectedItem.id || selectedItem.product_id || null) : null;
            var isBuyNow = (gcoCartData && gcoCartData.items && gcoCartData.items.length === 1);
            var designSide = gcoSelectedItemDesignSide(selectedItem);

            var address = user.address_line || user.address || '';
            if (user.flat_no) address = user.flat_no + ', ' + address;

            var payload = {
                name: user.name || 'Valued Customer',
                phone: user.phone || '',
                address: address,
                city: user.city || '',
                state: user.state || '',
                pincode: user.pincode || '',
                payment: 'cod',
                buy_now: isBuyNow,
                product_id: productId,
                qty: selectedItem ? Number(selectedItem.quantity || 1) : 1,
                size: selectedItem ? (selectedItem.size || '') : '',
                color: selectedItem ? (selectedItem.color || '') : '',
                design_side: designSide
            };

            gcoSetOrderProcessing(true, 'Placing your order', 'Please wait. Do not refresh or press back.', 'PLACING ORDER...');
            window.tttNotify('Placing Cash on Delivery Order...', 'info');

            fetch('{{ route('checkout.place') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.tttCsrfToken ? window.tttCsrfToken() : '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.success && res.redirect) {
                    closeGlobalCheckoutPop();
                    gcoShowOrderSuccessAndRedirect(res.redirect, res.order_number);
                } else {
                    window.tttNotify(res.message || 'Failed to place COD order.', 'error');
                    gcoSetOrderProcessing(false);
                }
            })
            .catch(function(err) {
                window.tttNotify(err.message || 'Failed to place order. Please try again.', 'error');
                gcoSetOrderProcessing(false);
            });
        };

        window.gcoProceedToPhonePe = function() {
            var user = window.TTT_USER || {};
            if (!isGlobalAddressComplete(user)) {
                gcoShowAddressEdit(true);
                return;
            }

            var total = gcoCartData ? gcoCartData.total : 0;
            var subtotal = gcoCartData ? gcoCartData.subtotal : total;
            var shipping = gcoCartData ? gcoCartData.shipping : 0;
            var selectedItem = (gcoCartData && gcoCartData.items[0]) ? gcoCartData.items[0] : null;
            var productId = selectedItem ? (selectedItem.id || selectedItem.product_id || null) : null;
            var designSide = gcoSelectedItemDesignSide(selectedItem);

            closeGlobalCheckoutPop();

            window.launchPhonePeDirect({
                amount: total,
                total: total,
                subtotal: subtotal,
                shipping: shipping,
                product_id: productId,
                buy_now: (gcoCartData && gcoCartData.items && gcoCartData.items.length === 1),
                qty: selectedItem ? Number(selectedItem.quantity || 1) : 1,
                size: selectedItem ? (selectedItem.size || '') : '',
                color: selectedItem ? (selectedItem.color || '') : '',
                design_side: designSide
            });
        };

        window.launchPhonePeDirect = function(orderInfo) {
            var user = window.TTT_USER || {};
            var amount = Number(orderInfo.amount || orderInfo.total || 0);
            if (!amount || amount <= 0) return;

            gcoSetOrderProcessing(true, 'Opening PhonePe', 'Please wait while we connect to the secure payment gateway.', 'CONNECTING TO PHONEPE...');
            window.tttNotify('Connecting to PhonePe Secure Gateway...', 'info');

            fetch('{{ route('payment.phonepe.initiate') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.tttCsrfToken ? window.tttCsrfToken() : '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: user.name || 'Customer',
                    phone: user.phone || '',
                    address: user.address || '',
                    city: user.city || '',
                    state: user.state || '',
                    pincode: user.pincode || '',
                    product_id: orderInfo.product_id || null,
                    buy_now: !!orderInfo.buy_now,
                    qty: orderInfo.qty || 1,
                    size: orderInfo.size || '',
                    color: orderInfo.color || '',
                    design_side: orderInfo.design_side || ''
                })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success && data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    window.tttNotify(data.message || 'PhonePe payment could not be initiated.', 'error');
                    gcoSetOrderProcessing(false);
                }
            })
            .catch(function(err) {
                console.error('PhonePe initiate error:', err);
                window.tttNotify('Could not connect to PhonePe Gateway.', 'error');
                gcoSetOrderProcessing(false);
            });
        };

        window.gcoProceedToRazorpay = function() {
            var user = window.TTT_USER || {};
            if (!isGlobalAddressComplete(user)) {
                gcoShowAddressEdit(true);
                return;
            }

            var total = gcoCartData ? gcoCartData.total : 0;
            var subtotal = gcoCartData ? gcoCartData.subtotal : total;
            var shipping = gcoCartData ? gcoCartData.shipping : 0;
            var selectedItem = (gcoCartData && gcoCartData.items[0]) ? gcoCartData.items[0] : null;
            var productId = selectedItem ? (selectedItem.id || selectedItem.product_id || null) : null;
            var designSide = gcoSelectedItemDesignSide(selectedItem);

            closeGlobalCheckoutPop();

            window.launchRazorpayDirect({
                amount: total,
                total: total,
                subtotal: subtotal,
                shipping: shipping,
                product_id: productId,
                buy_now: true,
                qty: selectedItem ? Number(selectedItem.quantity || 1) : 1,
                size: selectedItem ? (selectedItem.size || '') : '',
                color: selectedItem ? (selectedItem.color || '') : '',
                design_side: designSide
            });
        };

        window.launchRazorpayDirect = function(orderInfo) {
            var user = window.TTT_USER || {};
            var amount = Number(orderInfo.amount || orderInfo.total || 0);
            if (!amount || amount <= 0) return;

            gcoSetOrderProcessing(true, 'Opening secure payment', 'Please wait while we connect to Razorpay.', 'OPENING RAZORPAY...');
            fetch('{{ route('payment.razorpay.create') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.tttCsrfToken ? window.tttCsrfToken() : '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ amount: amount, product_id: orderInfo.product_id || null })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data.success) {
                    window.tttNotify(data.message || 'Payment initiation failed.', 'error');
                    gcoSetOrderProcessing(false);
                    return;
                }

                var options = {
                    key: data.key || window.TTT_RAZORPAY_KEY,
                    amount: data.amount,
                    currency: data.currency || 'INR',
                    order_id: data.razorpay_order_id,
                    name: 'THE TREND THEORY',
                    description: 'Fashion Order Payment',
                    image: '/favicon.ico',
                    prefill: {
                        name: user.name || '',
                        email: user.email || '',
                        contact: user.phone || ''
                    },
                    theme: { color: '#00285a' },
                    handler: function(response) {
                        gcoSetOrderProcessing(true, 'Verifying payment', 'Payment received. We are placing your order now.', 'VERIFYING PAYMENT...');
                        fetch('{{ route('payment.razorpay.verify') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': window.tttCsrfToken ? window.tttCsrfToken() : '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_signature: response.razorpay_signature,
                                order_data: {
                                    product_id: orderInfo.product_id || null,
                                    buy_now: !!orderInfo.buy_now,
                                    qty: orderInfo.qty || 1,
                                    size: orderInfo.size || '',
                                    color: orderInfo.color || '',
                                    design_side: orderInfo.design_side || '',
                                    name: user.name || '',
                                    email: user.email || '',
                                    phone: user.phone || '',
                                    address: user.address || '',
                                    city: user.city || '',
                                    state: user.state || '',
                                    pincode: user.pincode || '',
                                    subtotal: orderInfo.subtotal || amount,
                                    shipping: orderInfo.shipping || 0,
                                    total: amount
                                }
                            })
                        })
                        .then(function(r) { return r.json(); })
                        .then(function(res) {
                            if (res.success && res.redirect) {
                                gcoShowOrderSuccessAndRedirect(res.redirect, res.order_number);
                            } else {
                                window.tttNotify(res.message || 'Payment verification failed.', 'error');
                                gcoSetOrderProcessing(false);
                            }
                        })
                        .catch(function() {
                            window.location.href = '/cart';
                        });
                    },
                    modal: {
                        ondismiss: function() {
                            console.log('Payment modal dismissed');
                            gcoSetOrderProcessing(false);
                        }
                    }
                };

                var rzp = new Razorpay(options);
                rzp.on('payment.failed', function(res) {
                    window.tttNotify('Payment failed: ' + (res.error.description || 'Transaction declined'), 'error');
                    gcoSetOrderProcessing(false);
                });
                hideTttOrderLoader();
                rzp.open();
            })
            .catch(function(err) {
                console.error('Razorpay create error:', err);
                window.tttNotify('Could not start payment. Please try again.', 'error');
                gcoSetOrderProcessing(false);
            });
        };
    </script>
    <script>
        // Universal Product Slider & Add to Cart Modal
        var currentProductId = null;
        var currentSliderPrintSide = null;
        var currentSliderPrintMode = 'both';
        var currentSliderFrontImage = '';
        var currentSliderBackImage = '';
        var currentSliderGallery = [];

        window.openSlider = function(productId, initialImage) {
            if (!productId) return;
            currentProductId = productId;
            var slider = document.getElementById('universalSlider');
            var overlay = document.querySelector('.slider-overlay');
            if (slider) {
                slider.classList.add('active');
                slider.setAttribute('aria-hidden', 'false');
            }
            if (overlay) overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            loadProductInSlider(productId, initialImage);
        };

        window.closeSlider = function() {
            var slider = document.getElementById('universalSlider');
            var overlay = document.querySelector('.slider-overlay');
            if (slider) {
                slider.classList.remove('active');
                slider.setAttribute('aria-hidden', 'true');
            }
            if (overlay) overlay.classList.remove('active');
            document.body.style.overflow = '';
        };

        function renderSliderPrintSides(product) {
            currentSliderPrintMode = product.available_print_sides || 'both';
            currentSliderFrontImage = product.front_image || '';
            currentSliderBackImage = product.back_image || '';
            currentSliderGallery = product.gallery || [];
            currentSliderPrintSide = currentSliderPrintMode === 'front_only' ? 'front' : (currentSliderPrintMode === 'back_only' ? 'back' : null);

            var section = document.getElementById('sliderPrintSideSection');
            var grid = document.getElementById('sliderPrintSideGrid');
            var label = document.getElementById('sliderSelectedPrintSide');
            var error = document.getElementById('sliderPrintSideError');

            if (!section || !grid) return;

            section.style.display = 'block';
            section.classList.remove('needs-choice');
            if (error) error.style.display = 'none';
            if (label) label.textContent = currentSliderPrintSide === 'front' ? 'Front Side' : (currentSliderPrintSide === 'back' ? 'Back Side' : 'Select');

            var sides = [];
            if (currentSliderPrintMode === 'front_only') {
                sides = ['front'];
            } else if (currentSliderPrintMode === 'back_only') {
                sides = ['back'];
            } else {
                sides = ['front', 'back'];
            }

            grid.innerHTML = '';
            sides.forEach(function(side) {
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'quick-print-side-btn' + (currentSliderPrintSide === side ? ' active' : '');
                btn.dataset.side = side;
                btn.innerHTML = '<i class="bi ' + (side === 'front' ? 'bi-aspect-ratio' : 'bi-arrow-repeat') + '"></i> ' + (side === 'front' ? 'Front Side' : 'Back Side');
                btn.onclick = function() {
                    selectSliderPrintSide(side, btn);
                };
                grid.appendChild(btn);
            });
        }

        function selectSliderPrintSide(side, btn) {
            currentSliderPrintSide = side;
            document.querySelectorAll('.quick-print-side-btn').forEach(function(el) {
                el.classList.remove('active');
            });
            if (btn) btn.classList.add('active');

            var section = document.getElementById('sliderPrintSideSection');
            var label = document.getElementById('sliderSelectedPrintSide');
            var error = document.getElementById('sliderPrintSideError');
            var mainImg = document.getElementById('mainProductImg');

            if (section) section.classList.remove('needs-choice');
            if (error) error.style.display = 'none';
            if (label) label.textContent = side === 'back' ? 'Back Side' : 'Front Side';

            if (side === 'back' && currentSliderBackImage) {
                if (mainImg) mainImg.src = currentSliderBackImage;
            } else if (side === 'front' && currentSliderFrontImage) {
                if (mainImg) mainImg.src = currentSliderFrontImage;
            } else if (currentSliderGallery.length) {
                var targetIndex = side === 'back' && currentSliderGallery.length > 1 ? 1 : 0;
                if (mainImg) mainImg.src = currentSliderGallery[targetIndex] || currentSliderGallery[0];
            }
        }

        function requireSliderPrintSide() {
            if (currentSliderPrintSide) return true;

            var section = document.getElementById('sliderPrintSideSection');
            var error = document.getElementById('sliderPrintSideError');
            if (section) {
                section.classList.add('needs-choice');
                section.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            if (error) error.style.display = 'block';
            if (typeof window.tttNotify === 'function') {
                window.tttNotify('Please select Front Side or Back Side print.', 'error');
            }
            return false;
        }

        function loadProductInSlider(id, initialImage) {
            var placeholder = '{{ asset('images/placeholder-product.jpg') }}';
            var nameEl = document.getElementById('sliderProductName');
            var priceEl = document.getElementById('sliderPrice');
            var oldPriceEl = document.getElementById('sliderOldPrice');
            var discEl = document.getElementById('sliderDiscount');
            var sizeLabelEl = document.getElementById('selectedSize');
            var qtyEl = document.getElementById('sliderQty');
            var sizeSecEl = document.getElementById('sliderSizeSection');
            var mainImg = document.getElementById('mainProductImg');
            var galleryEl = document.getElementById('productGallery');
            var printSideSecEl = document.getElementById('sliderPrintSideSection');
            var printSideGridEl = document.getElementById('sliderPrintSideGrid');
            var printSideLabelEl = document.getElementById('sliderSelectedPrintSide');
            var printSideErrorEl = document.getElementById('sliderPrintSideError');

            if (nameEl) nameEl.textContent = 'Loading Product Details...';
            if (priceEl) priceEl.textContent = '';
            if (oldPriceEl) oldPriceEl.style.display = 'none';
            if (discEl) discEl.style.display = 'none';
            if (sizeLabelEl) sizeLabelEl.textContent = '-';
            if (qtyEl) qtyEl.textContent = '1';
            if (sizeSecEl) sizeSecEl.style.display = 'none';
            if (mainImg) mainImg.src = initialImage || placeholder;
            if (galleryEl) galleryEl.innerHTML = '<div style="padding:15px;color:#64748b;font-size:13px;"><i class="bi bi-arrow-repeat spin"></i> Loading images...</div>';
            currentSliderPrintSide = null;
            currentSliderPrintMode = 'both';
            currentSliderFrontImage = '';
            currentSliderBackImage = '';
            currentSliderGallery = [];
            if (printSideSecEl) {
                printSideSecEl.style.display = 'none';
                printSideSecEl.classList.remove('needs-choice');
            }
            if (printSideGridEl) printSideGridEl.innerHTML = '';
            if (printSideLabelEl) printSideLabelEl.textContent = 'Select';
            if (printSideErrorEl) printSideErrorEl.style.display = 'none';

            fetch('/api/v1/products/' + id, {
                    headers: { 'Accept': 'application/json' }
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (!data.success) {
                        if (nameEl) nameEl.textContent = data.message || 'Product unavailable';
                        if (galleryEl) galleryEl.innerHTML = '';
                        return;
                    }

                    var p = data.data;
                    var mainImage = p.image || p.image_url || p.card_image_url || initialImage || placeholder;

                    if (nameEl) nameEl.textContent = p.name;
                    var titleEl = document.getElementById('sliderTitle');
                    if (titleEl) titleEl.textContent = 'SELECT SIZE & OPTIONS';
                    if (priceEl) priceEl.textContent = '₹' + Math.round(Number(p.price || 0)).toLocaleString('en-IN');

                    // View full details link
                    var viewLink = document.getElementById('sliderViewFull');
                    if (viewLink && p.url) viewLink.href = p.url;

                    var badge = document.getElementById('sliderDiscountBadge');
                    if (p.original_price && p.original_price > p.price) {
                        var d = Math.round(((p.original_price - p.price) / p.original_price) * 100);
                        if (oldPriceEl) {
                            oldPriceEl.textContent = '₹' + Math.round(Number(p.original_price || 0)).toLocaleString('en-IN');
                            oldPriceEl.style.display = 'inline';
                        }
                        if (discEl) {
                            discEl.textContent = 'SAVE ' + d + '%';
                            discEl.style.display = 'inline-flex';
                        }
                        if (badge) {
                            badge.textContent = '-' + d + '%';
                            badge.style.display = 'inline-flex';
                        }
                    } else {
                        if (oldPriceEl) oldPriceEl.style.display = 'none';
                        if (discEl) discEl.style.display = 'none';
                        if (badge) badge.style.display = 'none';
                    }

                    // Set main image
                    if (mainImg) mainImg.src = mainImage;

                    // Build gallery thumbnails (horizontal)
                    if (galleryEl) {
                        galleryEl.innerHTML = '';
                        var imgs = (p.gallery && p.gallery.length) ? p.gallery : [mainImage];

                        imgs.filter(Boolean).forEach(function(imgSrc, index) {
                            var el = document.createElement('img');
                            el.src = imgSrc;
                            el.alt = p.name + ' - image ' + (index + 1);
                            el.className = index === 0 ? 'active' : '';
                            el.loading = 'lazy';
                            el.onerror = function() { this.src = placeholder; };
                            el.onclick = function() {
                                if (mainImg) mainImg.src = imgSrc;
                                galleryEl.querySelectorAll('img').forEach(function(x) { x.classList.remove('active'); });
                                el.classList.add('active');
                            };
                            galleryEl.appendChild(el);
                        });

                        // Set first image as main
                        if (imgs.length > 0 && mainImg) mainImg.src = imgs[0];
                    }

                    // Sizes
                    var sizeGrid = document.getElementById('sizeGrid');
                    if (sizeGrid) {
                        sizeGrid.innerHTML = '';
                        if (p.sizes && p.sizes.length) {
                            if (sizeSecEl) sizeSecEl.style.display = 'block';
                            p.sizes.forEach(function(size, idx) {
                                var s = document.createElement('span');
                                s.className = 'size-option' + (idx === 0 ? ' active' : '');
                                s.textContent = size;
                                s.onclick = function() {
                                    sizeGrid.querySelectorAll('.size-option').forEach(function(x) { x.classList.remove('active'); });
                                    s.classList.add('active');
                                    if (sizeLabelEl) sizeLabelEl.textContent = size;
                                };
                                sizeGrid.appendChild(s);
                            });
                            if (sizeLabelEl) sizeLabelEl.textContent = p.sizes[0];
                        } else {
                            if (sizeSecEl) sizeSecEl.style.display = 'none';
                        }
                    }

                    renderSliderPrintSides(p);

                    // Stock status
                    var addBtn = document.getElementById('sliderAddToCart');
                    var buyBtn = document.getElementById('sliderBuyNow');
                    if (!p.is_in_stock) {
                        if (addBtn) {
                            addBtn.innerHTML = '<i class="bi bi-x-circle"></i> OUT OF STOCK';
                            addBtn.disabled = true;
                            addBtn.style.opacity = '0.5';
                        }
                        if (buyBtn) {
                            buyBtn.disabled = true;
                            buyBtn.style.opacity = '0.5';
                        }
                    } else {
                        if (addBtn) {
                            addBtn.innerHTML = '<i class="bi bi-bag-plus-fill"></i> ADD TO CART';
                            addBtn.disabled = false;
                            addBtn.style.opacity = '1';
                        }
                        if (buyBtn) {
                            buyBtn.disabled = false;
                            buyBtn.style.opacity = '1';
                        }
                    }
                })
                .catch(function(err) {
                    console.error('Quick view error:', err);
                    if (nameEl) nameEl.textContent = 'Unable to load product';
                    if (galleryEl) galleryEl.innerHTML = '';
                });
        }

        // ADD TO CART inside Slider
        var sliderAddBtn = document.getElementById('sliderAddToCart');
        if (sliderAddBtn) {
            sliderAddBtn.addEventListener('click', function() {
                if (!currentProductId) return;
                var btn = this;
                var origHtml = btn.innerHTML;
                var size = document.getElementById('selectedSize')?.textContent || '';
                var qty = sliderQtyValue(document.getElementById('sliderQty')?.textContent);
                var retrySliderAddToCart = function() {
                    document.getElementById('sliderAddToCart')?.click();
                };

                if (!requireSliderPrintSide()) return;

                if (window.TTT_IS_AUTHENTICATED === false) {
                    if (window.TTT_AUTH_MODAL && typeof window.TTT_AUTH_MODAL.open === 'function') {
                        window.TTT_AUTH_MODAL.open(retrySliderAddToCart);
                        return;
                    }
                }

                btn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> ADDING...';
                btn.disabled = true;

                fetch('/cart/add/' + currentProductId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.tttCsrfToken ? window.tttCsrfToken() : '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        quantity: qty,
                        size: size,
                        design_side: currentSliderPrintSide
                    })
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success) {
                        var b = document.getElementById('cart-count');
                        if (b) {
                            b.textContent = data.cart_count;
                            b.style.display = 'flex';
                        }
                        btn.innerHTML = '<i class="bi bi-check2-circle"></i> ADDED TO BAG!';
                        if (typeof window.tttNotify === 'function') {
                            window.tttNotify('Product added to bag successfully!', 'success');
                        }
                        setTimeout(function() {
                            closeSlider();
                            btn.innerHTML = origHtml;
                            btn.disabled = false;
                        }, 600);
                    } else if (window.TTT_AUTH_MODAL && window.TTT_AUTH_MODAL.needsAuth(data)) {
                        btn.innerHTML = origHtml;
                        btn.disabled = false;
                        window.TTT_AUTH_MODAL.open(retrySliderAddToCart);
                    } else {
                        btn.innerHTML = origHtml;
                        btn.disabled = false;
                        window.tttNotify(data.message || 'Could not ADD TO CART', 'error');
                    }
                })
                .catch(function(err) {
                    console.error('ADD TO CART error:', err);
                    btn.innerHTML = origHtml;
                    btn.disabled = false;
                });
            });
        }

        // Qty controls
        function sliderQtyValue(value) {
            return Math.max(1, Math.min(10, parseInt(value, 10) || 1));
        }

        var qtyMinus = document.getElementById('sliderQtyMinus');
        if (qtyMinus) {
            qtyMinus.addEventListener('click', function() {
                var el = document.getElementById('sliderQty');
                if (el) el.textContent = sliderQtyValue(sliderQtyValue(el.textContent) - 1);
            });
        }

        var qtyPlus = document.getElementById('sliderQtyPlus');
        if (qtyPlus) {
            qtyPlus.addEventListener('click', function() {
                var el = document.getElementById('sliderQty');
                if (el) {
                    var currentQty = sliderQtyValue(el.textContent);
                    var nextQty = sliderQtyValue(currentQty + 1);
                    el.textContent = nextQty;
                    if (currentQty >= 10 && typeof window.tttNotify === 'function') {
                        window.tttNotify('Maximum 10 quantity allowed.', 'error');
                    }
                }
            });
        }

        // Buy Now inside Slider
        var sliderBuyBtn = document.getElementById('sliderBuyNow');
        if (sliderBuyBtn) {
            sliderBuyBtn.addEventListener('click', function() {
                if (!currentProductId) return;
                var size = document.getElementById('selectedSize')?.textContent || '';
                var qty = sliderQtyValue(document.getElementById('sliderQty')?.textContent);
                var btn = this;
                var origHtml = btn.innerHTML;

                var retrySliderBuyNow = function() {
                    document.getElementById('sliderBuyNow')?.click();
                };

                if (!requireSliderPrintSide()) return;

                if (window.TTT_IS_AUTHENTICATED === false) {
                    if (window.TTT_AUTH_MODAL && typeof window.TTT_AUTH_MODAL.open === 'function') {
                        window.TTT_AUTH_MODAL.open(retrySliderBuyNow);
                        return;
                    }
                }

                btn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> PROCESSING...';
                btn.disabled = true;

                fetch('/cart/add/' + currentProductId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.tttCsrfToken ? window.tttCsrfToken() : '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ quantity: qty, size: size, design_side: currentSliderPrintSide, buy_now: true, set_quantity: true })
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    btn.innerHTML = origHtml;
                    btn.disabled = false;

                    if (data.success) {
                        closeSlider();
                        var cart = data.cart || {};
                        var name = document.getElementById('sliderProductName')?.textContent || 'Product';
                        var img = document.getElementById('mainProductImg')?.src || '';
                        var priceText = (document.getElementById('sliderPrice')?.textContent || '0').replace(/[^0-9.]/g, '');
                        var oldPriceText = (document.getElementById('sliderOldPrice')?.textContent || '').replace(/[^0-9.]/g, '');
                        
                        var fallbackProduct = {
                            id: currentProductId,
                            name: name,
                            image: img,
                            size: size,
                            design_side: currentSliderPrintSide,
                            quantity: qty,
                            price: parseFloat(priceText) || 0,
                            original_price: parseFloat(oldPriceText) || (parseFloat(priceText) || 0)
                        };

                        if (typeof window.openGlobalCheckoutModal === 'function') {
                            window.openGlobalCheckoutModal(cart, fallbackProduct);
                        } else {
                            window.location.href = '/checkout?buy_now=1&product_id=' + encodeURIComponent(currentProductId) + '&size=' + encodeURIComponent(size || '') + '&design_side=' + encodeURIComponent(currentSliderPrintSide || '') + '&qty=' + encodeURIComponent(qty || 1);
                        }
                    } else if (window.TTT_AUTH_MODAL && window.TTT_AUTH_MODAL.needsAuth(data)) {
                        window.TTT_AUTH_MODAL.open(retrySliderBuyNow);
                    } else {
                        window.tttNotify(data.message || 'Unable to proceed with Buy Now', 'error');
                    }
                })
                .catch(function(err) {
                    btn.innerHTML = origHtml;
                    btn.disabled = false;
                    console.error('Buy now error:', err);
                });
            });
        }

        // Global Event Delegation for ANY quick-view or add-to-cart click
        document.addEventListener('click', function(e) {
            // Close on overlay click
            if (e.target.matches('.slider-overlay') || e.target.closest('.close-slider')) {
                e.preventDefault();
                closeSlider();
                return;
            }

            // Open slider on explicit product card button click
            var btn = e.target.closest('.open-product-slider, .btn-hover-add, .open-quick-view, [data-open-slider], button[data-product-id], .shop-quick-add-btn, .rec-quick-add-btn');
            if (btn && !btn.disabled) {
                var pid = btn.getAttribute('data-product-id') || (btn.dataset ? btn.dataset.productId : '');
                if (!pid) return;
                e.preventDefault();
                e.stopPropagation();
                var cardImage = btn.getAttribute('data-product-image') || (btn.dataset ? btn.dataset.productImage : '') || '';
                if (!cardImage) {
                    var card = btn.closest('.collection-card, .lifestyle-card, .most-item, .collection-img, .lifestyle-img, .most-img, .product-card, .feat-card, .col-6, .col-md-4, .col-lg-3');
                    var img = card ? card.querySelector('img') : null;
                    cardImage = img ? img.src : '';
                }
                openSlider(pid, cardImage);
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

                var slides = Array.from(track.children);
                if (!slides.length) return;

                var activeIndex = 0;
                var x = 0;
                track.style.transition = 'transform 0.4s cubic-bezier(0.22, 0.68, 0.32, 1)';

                function getMaxOffset() {
                    return Math.max(0, track.scrollWidth - wrapper.clientWidth);
                }

                function getMaxIndex() {
                    var maxOffset = getMaxOffset();
                    var index = slides.findIndex(function(slide) {
                        return slide.offsetLeft >= maxOffset;
                    });
                    return index === -1 ? slides.length - 1 : index;
                }

                function applyPosition() {
                    var maxOffset = getMaxOffset();
                    var maxIndex = getMaxIndex();
                    if (activeIndex > maxIndex) activeIndex = maxIndex;
                    if (activeIndex < 0 || maxOffset === 0) activeIndex = 0;
                    x = -Math.min(slides[activeIndex].offsetLeft || 0, maxOffset);
                    track.style.transform = 'translateX(' + x + 'px)';

                    // Update arrow opacity/visibility
                    if (btnL) {
                        btnL.style.opacity = activeIndex <= 0 ? '0.45' : '1';
                        btnL.style.pointerEvents = activeIndex <= 0 ? 'auto' : 'auto';
                    }
                    if (btnR) {
                        btnR.style.opacity = activeIndex >= maxIndex ? '0.45' : '1';
                        btnR.style.pointerEvents = activeIndex >= maxIndex ? 'auto' : 'auto';
                    }
                }

                function nextSlide() {
                    var maxIndex = getMaxIndex();
                    if (maxIndex === 0) return;
                    activeIndex = activeIndex >= maxIndex ? 0 : activeIndex + 1;
                    applyPosition();
                }

                function prevSlide() {
                    var maxIndex = getMaxIndex();
                    if (maxIndex === 0) return;
                    activeIndex = activeIndex <= 0 ? maxIndex : activeIndex - 1;
                    applyPosition();
                }

                if (btnL) btnL.addEventListener('click', function(e) {
                    e.preventDefault();
                    prevSlide();
                });
                if (btnR) btnR.addEventListener('click', function(e) {
                    e.preventDefault();
                    nextSlide();
                });

                // Touch / Swipe support for mobile & tablet
                var touchStartX = 0;
                var touchEndX = 0;
                var isTouching = false;

                wrapper.addEventListener('touchstart', function(e) {
                    if (e.touches && e.touches.length === 1) {
                        touchStartX = e.touches[0].clientX;
                        isTouching = true;
                    }
                }, { passive: true });

                wrapper.addEventListener('touchend', function(e) {
                    if (!isTouching) return;
                    isTouching = false;
                    touchEndX = e.changedTouches[0].clientX;
                    var diffX = touchStartX - touchEndX;
                    if (Math.abs(diffX) > 40) {
                        if (diffX > 0) {
                            nextSlide();
                        } else {
                            prevSlide();
                        }
                    }
                }, { passive: true });

                window.addEventListener('resize', applyPosition);
                applyPosition();
            }

            window.makeSlider = makeSlider;
            window.makeProductSlider = makeSlider;

            // Global hover gallery auto-rotator for product cards
            function initCardHoverGalleries() {
                var galleries = Array.prototype.slice.call(document.querySelectorAll('.home-product-carousel [data-home-card-gallery]'));
                if (!galleries.length) return;

                galleries.forEach(function(gallery) {
                    if (gallery.dataset.homeGalleryInited === 'true') return;
                    var slides = Array.prototype.slice.call(gallery.querySelectorAll('.home-card-gallery-img'));
                    if (slides.length < 2) return;

                    gallery.dataset.homeGalleryInited = 'true';
                    var hoverTimer = null;
                    var activeIndex = slides.findIndex(function(slide) { return slide.classList.contains('active'); });
                    if (activeIndex < 0) activeIndex = 0;

                    function showImage(nextIndex) {
                        activeIndex = (nextIndex + slides.length) % slides.length;
                        slides.forEach(function(slide, idx) {
                            slide.classList.toggle('active', idx === activeIndex);
                        });
                    }

                    gallery.addEventListener('mouseenter', function() {
                        if (hoverTimer) return;
                        hoverTimer = setInterval(function() {
                            if (document.hidden) return;
                            showImage(activeIndex + 1);
                        }, 900);
                    });

                    gallery.addEventListener('mouseleave', function() {
                        if (!hoverTimer) return;
                        clearInterval(hoverTimer);
                        hoverTimer = null;
                        showImage(0);
                    });
                });
            }
            window.initCardHoverGalleries = initCardHoverGalleries;

            document.addEventListener('DOMContentLoaded', function() {
                makeSlider('heroSliderTrack', 'heroSliderWrapper', 'heroArrowLeft', 'heroArrowRight', 532);
                makeSlider('mostSliderTrack', 'mostSliderWrapper', 'mostArrowLeft', 'mostArrowRight', 385);
                makeSlider('menSliderTrack', 'menSliderWrapper', 'menArrowLeft', 'menArrowRight', 405);
                makeSlider('womenSliderTrack', 'womenSliderWrapper', 'womenArrowLeft', 'womenArrowRight', 405);
                makeSlider('newinSliderTrack', 'newinSliderWrapper', 'newinArrowLeft', 'newinArrowRight', 405);
                makeSlider('recentSliderTrack', 'recentSliderWrapper', 'recentArrowLeft', 'recentArrowRight', 300);
                makeSlider('relatedSliderTrack', 'relatedSliderWrapper', 'relatedArrowLeft', 'relatedArrowRight', 300);
                initCardHoverGalleries();
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

        /* ─── Frontend Notification Dropdown System ─── */
        function toggleTttNotifications(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            var dd = document.getElementById('tttNotifDropdown');
            if (!dd) return;
            if (dd.style.display === 'none' || dd.style.display === '') {
                dd.style.display = 'block';
                var countBadge = document.getElementById('notif-count');
                if (countBadge) countBadge.style.display = 'none';
                loadTttNotifications(true);
                autoMarkAllReadBackend();
            } else {
                dd.style.display = 'none';
            }
        }

        document.addEventListener('click', function(e) {
            var wrap = document.getElementById('tttNotifWrap');
            var dd = document.getElementById('tttNotifDropdown');
            if (wrap && dd && !wrap.contains(e.target)) {
                dd.style.display = 'none';
            }
        });

        function loadTttNotifications(autoMarkRead) {
            var dd = document.getElementById('tttNotifDropdown');
            var isDropdownOpen = dd && (dd.style.display === 'block');

            fetch('{{ route('notifications.feed') }}')
                .then(function(r) { return r.json(); })
                .then(function(res) {
                    var list = document.getElementById('tttNotifList');
                    var countBadge = document.getElementById('notif-count');
                    if (!list) return;

                    // If opening or dropdown is open, automatically clear unread badge and mark read
                    if (autoMarkRead || isDropdownOpen) {
                        if (countBadge) countBadge.style.display = 'none';
                        autoMarkAllReadBackend();
                    } else {
                        if (countBadge) {
                            if (res.unread_count > 0) {
                                countBadge.innerText = res.unread_count > 9 ? '9+' : res.unread_count;
                                countBadge.style.display = 'flex';
                            } else {
                                countBadge.style.display = 'none';
                            }
                        }
                    }

                    if (!res.notifications || res.notifications.length === 0) {
                        list.innerHTML = '<div class="ttt-notif-empty"><span class="ttt-notif-empty-icon"><i class="bi bi-bell-slash"></i></span><strong>No notifications yet</strong><span>New order updates and offers will appear here.</span></div>';
                        return;
                    }

                    var html = '';
                    if ('Notification' in window && Notification.permission === 'default') {
                        html += '<div class="ttt-notif-optin-strip" onclick="requestBrowserNotificationPermission()"><span><i class="bi bi-bell-fill"></i> Enable instant alerts</span><b>Allow</b></div>';
                    }

                    res.notifications.forEach(function(n) {
                        // When dropdown is viewed, mark read automatically
                        var isUnread = (!autoMarkRead && !isDropdownOpen && !n.is_read);
                        var unreadClass = isUnread ? 'unread' : '';
                        var title = escapeTttNotifHtml(n.title || 'Notification');
                        var message = escapeTttNotifHtml(n.message || '');
                        var timeAgo = escapeTttNotifHtml(n.time_ago || '');
                        var actionLabel = escapeTttNotifHtml(n.action_label || '');
                        var actionUrl = encodeURIComponent(n.action_url || '');
                        var imgTag = n.image_url ? '<img src="' + escapeTttNotifAttr(n.image_url) + '" class="ttt-notif-thumb" alt="">' : '<div class="ttt-notif-icon-box"><i class="bi ' + escapeTttNotifAttr(n.icon || 'bi-bell-fill') + '"></i></div>';
                        
                        html += '<div class="ttt-notif-item ' + unreadClass + '" onclick="handleNotifClick(' + Number(n.id) + ', decodeURIComponent(\'' + actionUrl + '\'))">';
                        if (isUnread) {
                            html += '<span class="ttt-notif-unread-dot"></span>';
                        }
                        html += imgTag;
                        html += '<div class="ttt-notif-content">';
                        html += '<div class="ttt-notif-item-head"><strong>' + title + '</strong><span class="ttt-notif-time">' + timeAgo + '</span></div>';
                        if (n.action_label) {
                            html += '<span class="ttt-notif-action-link">' + actionLabel + ' <i class="bi bi-arrow-right-short"></i></span>';
                        }
                        html += '</div></div>';
                    });
                    list.innerHTML = html;
                })
                .catch(function(e) {
                    console.log('Notif feed error', e);
                    var list = document.getElementById('tttNotifList');
                    if (list) {
                        list.innerHTML = '<div class="ttt-notif-empty"><span class="ttt-notif-empty-icon"><i class="bi bi-wifi-off"></i></span><strong>Could not load</strong><span>Please try again in a moment.</span></div>';
                    }
                });
        }

        function autoMarkAllReadBackend() {
            fetch('{{ route('notifications.markAllRead') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(function() {
                var badge = document.getElementById('notif-count');
                if (badge) badge.style.display = 'none';
            }).catch(function(e) {
                console.log('Auto mark read notice:', e);
            });
        }

        function escapeTttNotifHtml(value) {
            return String(value).replace(/[&<>"']/g, function(ch) {
                return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[ch];
            });
        }

        function escapeTttNotifAttr(value) {
            return escapeTttNotifHtml(value).replace(/`/g, '&#096;');
        }

        function handleNotifClick(notifId, actionUrl) {
            fetch('/notifications/' + notifId + '/read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).finally(function() {
                if (actionUrl) {
                    window.location.href = actionUrl;
                }
            });
        }

        // Auto load unread count on page start
        document.addEventListener('DOMContentLoaded', function() {
            loadTttNotifications();
            updatePushPermUI();
        });

        /* ─── Web Push Permission Handling ─── */
        function updatePushPermUI() {
            var stateEl = document.getElementById('tttPermState');
            var btnEl = document.getElementById('tttPermBtn');
            if (!stateEl || !btnEl) return;

            if (!('Notification' in window)) {
                stateEl.innerHTML = '<span style="color:#94a3b8;">Unsupported</span>';
                btnEl.style.display = 'none';
                return;
            }

            if (Notification.permission === 'granted') {
                stateEl.innerHTML = '<span style="color:#059669;">Active</span>';
                btnEl.innerText = '';
                btnEl.style.display = 'none';
                btnEl.className = 'btn-perm-switch btn-perm-active';
            } else if (Notification.permission === 'denied') {
                stateEl.innerHTML = '<span style="color:#dc2626;">Blocked</span>';
                btnEl.innerText = 'Allow';
                btnEl.style.display = 'inline-flex';
                btnEl.className = 'btn-perm-switch';
            } else {
                stateEl.innerHTML = '<span style="color:#b45309;">Off</span>';
                btnEl.innerText = 'Allow';
                btnEl.style.display = 'inline-flex';
                btnEl.className = 'btn-perm-switch';
            }
        }

        function initPushPermissionPrompt() {
            if (!('Notification' in window)) return;
            
            // If already granted, don't show prompt
            if (Notification.permission === 'granted') {
                updatePushPermUI();
                return;
            }

            // If user clicked Block in this session / recently (< 12 hours)
            var blockedAt = localStorage.getItem('ttt_push_blocked');
            if (blockedAt) {
                var hours = (Date.now() - parseInt(blockedAt)) / (1000 * 60 * 60);
                if (hours < 12) {
                    updatePushPermUI();
                    return;
                }
            }

            var promptEl = document.getElementById('tttPushPrompt');
            if (promptEl) {
                promptEl.style.display = 'flex';
            }
            updatePushPermUI();
        }

        function togglePushPermissionAction() {
            if (!('Notification' in window)) {
                alert('Your browser does not support Web Push Notifications.');
                return;
            }

            if (Notification.permission === 'granted') {
                updatePushPermUI();
                return;
            }

            requestBrowserNotificationPermission();
        }

        // ─── Firebase Cloud Messaging Initialization ───
        var tttFirebaseConfig = {
            apiKey: "{{ config('services.firebase.api_key', 'AIzaSyBxewN-r_TDJfHBwuzcdIq2Bme6dyRCWVo') }}",
            authDomain: "{{ config('services.firebase.project_id', 'the-trend-theory') }}.firebaseapp.com",
            projectId: "{{ config('services.firebase.project_id', 'the-trend-theory') }}",
            storageBucket: "{{ config('services.firebase.storage_bucket', 'the-trend-theory.firebasestorage.app') }}",
            messagingSenderId: "{{ config('services.firebase.sender_id', '664156075505') }}",
            appId: "{{ config('services.firebase.app_id', '1:664156075505:ios:6e6b662021c3ce7eef0050') }}"
        };

        var tttFcmMessaging = null;
        try {
            if (typeof firebase !== 'undefined') {
                if (!firebase.apps.length) {
                    firebase.initializeApp(tttFirebaseConfig);
                }
                if (firebase.messaging && firebase.messaging.isSupported()) {
                    tttFcmMessaging = firebase.messaging();
                    tttFcmMessaging.onMessage(function(payload) {
                        console.log('[Firebase FCM] Foreground notification:', payload);
                        var title = (payload.notification && payload.notification.title) || (payload.data && payload.data.title) || 'The Trend Theory';
                        var body = (payload.notification && payload.notification.body) || (payload.data && payload.data.body) || '';
                        if (typeof showWishToast === 'function') {
                            showWishToast(title + (body ? ': ' + body : ''));
                        }
                        if (typeof loadTttNotifications === 'function') {
                            loadTttNotifications();
                        }
                    });
                }
            }
        } catch(e) {
            console.log('[Firebase FCM] Init error:', e);
        }

        // Register Service Worker for Web Push Notifications
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/firebase-messaging-sw.js').then(function(reg) {
                    console.log('Service Worker Registered successfully:', reg.scope);
                    if (Notification.permission === 'granted') {
                        syncPushSubscription(reg);
                    }
                }).catch(function(err) {
                    console.log('Service Worker registration error:', err);
                });
            });
        }

        function syncPushSubscription(swReg) {
            try {
                var deviceId = localStorage.getItem('ttt_device_id') || (function() {
                    var did = 'dev_' + Math.random().toString(36).substring(2, 15) + Date.now().toString(36);
                    localStorage.setItem('ttt_device_id', did);
                    return did;
                })();

                if (tttFcmMessaging && swReg) {
                    tttFcmMessaging.getToken({
                        serviceWorkerRegistration: swReg
                    }).then(function(currentToken) {
                        if (currentToken) {
                            console.log('[Firebase FCM] Device token acquired:', currentToken);
                            localStorage.setItem('ttt_fcm_token', currentToken);
                            dispatchSubscriptionPayload({
                                endpoint: 'https://fcm.googleapis.com/fcm/send/' + currentToken,
                                fcm_token: currentToken,
                                device_type: 'web',
                                public_key: 'fcm_key_' + deviceId,
                                auth_token: 'fcm_auth_' + deviceId,
                                content_encoding: 'aesgcm'
                            });
                            return;
                        }
                        fallbackSubscriptionSync(deviceId);
                    }).catch(function(err) {
                        console.log('[Firebase FCM] getToken notice:', err);
                        fallbackSubscriptionSync(deviceId);
                    });
                } else {
                    fallbackSubscriptionSync(deviceId);
                }
            } catch(e) {
                console.log('Subscription sync notice:', e);
            }
        }

        function fallbackSubscriptionSync(deviceId) {
            var endpoint = 'https://webpush.thetrendtheory.com/sub/' + deviceId;
            dispatchSubscriptionPayload({
                endpoint: endpoint,
                device_type: 'web',
                public_key: 'p256dh_' + deviceId,
                auth_token: 'auth_' + deviceId,
                content_encoding: 'aesgcm'
            });
        }

        function dispatchSubscriptionPayload(payload) {
            fetch('/api/push/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : ''
                },
                body: JSON.stringify(payload)
            }).then(function(r) { return r.json(); })
              .then(function(res) {
                  console.log('[Push] Subscription registered:', res);
              }).catch(function(e) { console.log('[Push] Sync error:', e); });
        }

        function requestBrowserNotificationPermission() {
            if (!('Notification' in window)) {
                alert('Browser does not support notifications.');
                return;
            }

            Notification.requestPermission().then(function(permission) {
                var promptEl = document.getElementById('tttPushPrompt');
                if (promptEl) promptEl.style.display = 'none';

                if (permission === 'granted') {
                    localStorage.setItem('ttt_push_allowed', 'true');
                    localStorage.removeItem('ttt_push_blocked');
                    
                    if ('serviceWorker' in navigator) {
                        navigator.serviceWorker.ready.then(function(reg) {
                            syncPushSubscription(reg);
                        });
                    }

                    if (typeof showWishToast === 'function') {
                        showWishToast('Notifications enabled');
                    }
                } else if (permission === 'denied') {
                    localStorage.setItem('ttt_push_blocked', Date.now().toString());
                    if (typeof showWishToast === 'function') {
                        showWishToast('🚫 Notifications Blocked');
                    }
                }
                updatePushPermUI();
            });
        }

        function blockPushPrompt() {
            var promptEl = document.getElementById('tttPushPrompt');
            if (promptEl) {
                promptEl.classList.add('hide');
                setTimeout(function() {
                    promptEl.style.display = 'none';
                    promptEl.classList.remove('hide');
                }, 300);
            }
            localStorage.setItem('ttt_push_blocked', Date.now().toString());
            updatePushPermUI();
            if (typeof showWishToast === 'function') {
                showWishToast('Notification prompt dismissed.');
            }
        }
    </script>

    <style>
    /* ─── Push Notification Allow / Block Opt-In Prompt Styles ─── */
    .ttt-push-prompt {
        position: fixed;
        top: 24px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 999999;
        display: flex;
        animation: pushPromptDrop .35s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .ttt-push-prompt.hide {
        animation: pushPromptFadeOut .25s ease forwards;
    }

    @keyframes pushPromptDrop {
        from { opacity: 0; transform: translate(-50%, -30px); }
        to { opacity: 1; transform: translate(-50%, 0); }
    }

    @keyframes pushPromptFadeOut {
        from { opacity: 1; transform: translate(-50%, 0); }
        to { opacity: 0; transform: translate(-50%, -20px); }
    }

    .push-prompt-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 20px 50px -10px rgba(0, 40, 90, 0.28), 0 0 0 1.5px rgba(0, 40, 90, 0.12);
        padding: 20px 24px;
        width: min(440px, calc(100vw - 32px));
        display: flex;
        flex-direction: column;
        gap: 16px;
        position: relative;
    }

    .push-close-btn {
        position: absolute;
        top: 10px;
        right: 14px;
        background: none;
        border: none;
        font-size: 22px;
        color: #94a3b8;
        cursor: pointer;
        line-height: 1;
    }

    .push-card-top-row {
        display: flex;
        gap: 14px;
        align-items: center;
    }

    .push-icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, #00285a, #1e3f75);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 40, 90, 0.2);
    }

    .push-text-wrap {
        flex: 1;
    }

    .push-title {
        font-size: 14px;
        font-weight: 800;
        color: #00285a;
        margin: 0 0 4px;
        padding-right: 18px;
        line-height: 1.3;
    }

    .push-desc {
        font-size: 12px;
        color: #64748b;
        margin: 0;
        line-height: 1.45;
    }

    .push-actions-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .btn-push-allow {
        background: #00285a;
        color: #ffffff;
        border: none;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all .15s ease;
        box-shadow: 0 4px 14px rgba(0, 40, 90, 0.25);
    }

    .btn-push-block {
        background: #f8fafc;
        color: #64748b;
        border: 1.5px solid #e2e8f0;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all .15s ease;
    }

    @media (max-width: 576px) {
        .ttt-push-prompt {
            top: 12px;
            width: calc(100vw - 24px);
        }
        .push-prompt-card {
            width: 100%;
            padding: 16px;
        }
        .push-actions-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Push Permission Bar inside Dropdown */
    .ttt-notif-perm-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 16px;
        background: #f8fafc;
        border-bottom: 1px solid #eef2f6;
        font-size: 11px;
        font-weight: 700;
        color: #475569;
    }

    .btn-perm-switch {
        background: #00285a;
        color: #ffffff;
        border: none;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 10.5px;
        font-weight: 800;
        cursor: pointer;
        transition: background .15s ease;
    }

    .btn-perm-active {
        background: #10b981 !important;
    }

    /* ─── Luxury In-App Notification Dropdown Styles ─── */
    .ttt-notif-nav-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .ttt-notif-trigger-btn {
        background: none !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
        color: inherit !important;
        font-size: 18px !important;
        cursor: pointer;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        line-height: 1;
        outline: none !important;
        box-shadow: none !important;
        position: relative;
    }

    .ttt-notif-badge-pill {
        position: absolute;
        top: -6px;
        right: -8px;
        background: #00285a;
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }

    .ttt-notif-dropdown {
        position: absolute;
        top: calc(100% + 12px);
        right: 0;
        width: min(350px, calc(100vw - 32px));
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 20px 50px -10px rgba(0, 40, 90, 0.22), 0 0 0 1px rgba(0, 40, 90, 0.08);
        z-index: 999999;
        overflow: hidden;
        animation: notifDropAnim .2s cubic-bezier(0.16, 1, 0.3, 1);
        transform-origin: top right;
        box-sizing: border-box;
    }

    @keyframes notifDropAnim {
        from { opacity: 0; transform: scale(0.96) translateY(-10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    .ttt-notif-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        background: linear-gradient(to bottom, #ffffff, #f8fafc);
        border-bottom: 1px solid #eef2f6;
        font-size: 13px;
        font-weight: 800;
        color: #00285a;
        letter-spacing: -0.2px;
    }

    .ttt-mark-read-btn {
        background: rgba(0, 40, 90, 0.05);
        border: none;
        font-size: 11px;
        color: #00285a;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        cursor: pointer;
        transition: all .15s ease;
    }

    .ttt-notif-optin-strip {
        background: linear-gradient(135deg, #00285a, #1e3f75);
        color: #ffffff;
        padding: 10px 12px;
        border-radius: 10px;
        font-size: 11.5px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        cursor: pointer;
        transition: transform .15s ease;
        margin-bottom: 4px;
    }

    .ttt-notif-optin-strip span {
        background: #ffffff;
        color: #00285a;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }

    .ttt-notif-list {
        max-height: 400px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        padding: 8px;
        gap: 6px;
    }

    .ttt-notif-item {
        display: flex;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 12px;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all .2s ease;
        text-align: left;
        background: #ffffff;
    }

    .ttt-notif-item.unread {
        background: #f0f7ff;
        border-color: #bae6fd;
    }

    .ttt-notif-icon-box {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        color: #00285a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .ttt-notif-thumb {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        object-fit: cover;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }

    .ttt-notif-content {
        flex: 1;
    }

    .ttt-notif-item-head {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        gap: 6px;
    }

    .ttt-notif-item-head strong {
        font-size: 12.5px;
        font-weight: 800;
        color: #00285a;
        line-height: 1.3;
    }

    .ttt-notif-time {
        font-size: 10px;
        color: #94a3b8;
        white-space: nowrap;
        font-weight: 600;
    }

    .ttt-notif-item-msg {
        font-size: 11.5px;
        color: #475569;
        margin: 4px 0 6px;
        line-height: 1.45;
    }

    .ttt-notif-action-link {
        font-size: 11px;
        font-weight: 800;
        color: #00285a;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .ttt-notif-empty, .ttt-notif-loading {
        padding: 40px 20px;
        text-align: center;
        color: #94a3b8;
        font-size: 12.5px;
    }

    /* Notification UI refresh */
    .ttt-notif-dropdown {
        width: min(390px, calc(100vw - 24px));
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.16);
        background: #ffffff;
    }

    .ttt-notif-head {
        padding: 14px 14px 12px;
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        gap: 12px;
    }

    .ttt-notif-title-wrap {
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
    }

    .ttt-notif-title {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #00285a;
        font-size: 14px;
        font-weight: 900;
        line-height: 1.2;
    }

    .ttt-notif-title i {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #eff6ff;
        color: #00285a;
        font-size: 13px;
    }

    .ttt-notif-subtitle {
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
        line-height: 1.2;
        padding-left: 36px;
    }

    .ttt-mark-read-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #00285a;
        min-height: 30px;
        padding: 6px 10px;
        border-radius: 8px;
        white-space: nowrap;
    }

    .ttt-notif-perm-bar {
        margin: 8px;
        padding: 10px 12px;
        border: 1px solid #dbeafe;
        border-radius: 10px;
        background: #f8fbff;
    }

    .ttt-notif-perm-bar span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        min-width: 0;
    }

    .ttt-notif-perm-bar i {
        color: #00285a;
    }

    .btn-perm-switch {
        min-height: 28px;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        padding: 5px 10px;
    }

    .ttt-notif-list {
        max-height: min(420px, calc(100vh - 150px));
        padding: 8px;
        gap: 8px;
    }

    .ttt-notif-item {
        position: relative;
        display: grid;
        grid-template-columns: 42px minmax(0, 1fr);
        gap: 10px;
        padding: 12px;
        border: 1px solid #edf2f7;
        border-radius: 12px;
        background: #ffffff;
    }

    .ttt-notif-item.unread {
        background: #f8fbff;
        border-color: #bfdbfe;
    }

    .ttt-notif-unread-dot {
        display: none;
        position: absolute;
        top: 14px;
        right: 12px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #00285a;
    }

    .ttt-notif-item.unread .ttt-notif-unread-dot {
        display: block;
    }

    .ttt-notif-icon-box,
    .ttt-notif-thumb {
        width: 42px;
        height: 42px;
        border-radius: 10px;
    }

    .ttt-notif-icon-box {
        background: #eff6ff;
        border: 1px solid #dbeafe;
    }

    .ttt-notif-content {
        min-width: 0;
        padding-right: 10px;
    }

    .ttt-notif-item-head {
        align-items: flex-start;
        gap: 10px;
    }

    .ttt-notif-item-head strong {
        color: #0f172a;
        font-size: 12.5px;
        line-height: 1.35;
    }

    .ttt-notif-time {
        color: #94a3b8;
        font-size: 10px;
        line-height: 1.35;
    }

    .ttt-notif-item-msg {
        color: #475569;
        font-size: 11.5px;
        line-height: 1.45;
        margin: 5px 0 7px;
    }

    .ttt-notif-action-link {
        color: #00285a;
        font-size: 11px;
        gap: 2px;
    }

    .ttt-notif-optin-strip {
        margin: 0 0 2px;
        border-radius: 12px;
        background: #00285a;
        box-shadow: none;
    }

    .ttt-notif-optin-strip b {
        background: #ffffff;
        color: #00285a;
        padding: 4px 9px;
        border-radius: 8px;
        font-size: 10px;
        line-height: 1;
        white-space: nowrap;
    }

    .ttt-notif-empty,
    .ttt-notif-loading {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        padding: 34px 20px;
        color: #64748b;
        font-size: 12px;
    }

    .ttt-notif-empty strong,
    .ttt-notif-loading strong {
        color: #0f172a;
        font-size: 13px;
    }

    .ttt-notif-empty-icon,
    .ttt-notif-loading i {
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #f1f5f9;
        color: #00285a;
        font-size: 18px;
        margin-bottom: 4px;
    }

    @media (max-width: 576px) {
        .ttt-notif-dropdown {
            position: fixed;
            top: 62px;
            left: 12px;
            right: 12px;
            width: auto;
            max-height: calc(100vh - 86px);
        }

        .ttt-notif-head {
            padding: 12px;
        }

        .ttt-mark-read-btn span {
            display: none;
        }

        .ttt-notif-list {
            max-height: calc(100vh - 230px);
        }
    }

    /* ═══════════════════════════════════════════════════════════════════
       GLOBAL OFFERS & REWARDS CARD (SCREENSHOT 1)
       ═══════════════════════════════════════════════════════════════════ */
    .ttt-offers-rewards-wrap {
        margin-bottom: 20px;
        font-family: -apple-system, BlinkMacSystemFont, "Plus Jakarta Sans", "Segoe UI", Roboto, sans-serif !important;
    }

    .ttt-offers-head {
        font-size: 11.5px;
        font-weight: 800;
        color: #64748b;
        letter-spacing: 0.6px;
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    .ttt-offers-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        box-shadow: 0 2px 10px rgba(0, 40, 90, 0.03);
    }

    .ttt-coupon-input-box {
        display: flex;
        align-items: center;
        border: 1px solid #dbe3ec;
        border-radius: 8px;
        overflow: hidden;
        background: #ffffff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        margin-bottom: 12px;
    }

    .ttt-coupon-input-box:focus-within {
        border-color: #00285a;
        box-shadow: 0 0 0 3px rgba(0, 40, 90, 0.08);
    }

    .ttt-coupon-input-box input {
        flex: 1;
        height: 42px;
        border: none;
        outline: none;
        padding: 0 14px;
        font-size: 13px;
        color: #0f172a;
        font-weight: 600;
        background: transparent;
    }

    .ttt-coupon-input-box input::placeholder {
        color: #94a3b8;
        text-transform: none;
        font-weight: 500;
    }

    .ttt-coupon-apply-btn {
        height: 42px;
        padding: 0 16px;
        background: none;
        border: none;
        color: #00285a;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: color 0.15s ease;
    }

    .ttt-offers-mid-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
    }

    .ttt-offers-left {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .ttt-badge-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .ttt-available-text {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
    }

    .ttt-view-all-btn {
        background: none;
        border: none;
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
        cursor: pointer;
        padding: 0;
        transition: color 0.15s ease;
    }

    .ttt-loyalty-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-top: 12px;
        font-size: 12.5px;
        color: #b45309;
        font-weight: 500;
    }

    .ttt-loyalty-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .ttt-loyalty-text {
        color: #b45309;
        font-size: 12.5px;
    }

    .ttt-loyalty-text b {
        color: #b45309;
        font-weight: 700;
    }

    /* ═══════════════════════════════════════════════════════════════════
       COUPONS & OFFERS BOTTOM SHEET MODAL (SCREENSHOT 2)
       ═══════════════════════════════════════════════════════════════════ */
    .ttt-coupon-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 125000;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .ttt-coupon-modal-backdrop.is-open {
        opacity: 1;
        visibility: visible;
    }

    .ttt-coupon-bottom-sheet {
        position: fixed;
        left: 50%;
        bottom: 0;
        transform: translate(-50%, 100%);
        width: 100%;
        max-width: 500px;
        max-height: 85vh;
        background: #ffffff;
        border-radius: 20px 20px 0 0;
        box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.25);
        z-index: 126000;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        font-family: -apple-system, BlinkMacSystemFont, "Plus Jakarta Sans", "Segoe UI", Roboto, sans-serif !important;
    }

    .ttt-coupon-bottom-sheet.is-open {
        transform: translate(-50%, 0);
    }

    .ttt-sheet-close-btn {
        position: absolute;
        top: -46px;
        left: 50%;
        transform: translateX(-50%);
        width: 36px;
        height: 36px;
        background: #e2e8f0;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        color: #0f172a;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        transition: background 0.2s ease, transform 0.2s ease;
        z-index: 126001;
    }

    .ttt-sheet-inner {
        padding: 22px 20px 16px;
        overflow-y: auto;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .ttt-sheet-header {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ttt-sheet-head-icon {
        width: 24px;
        height: 24px;
        border: 1.5px solid #00285a;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        color: #00285a;
    }

    .ttt-sheet-title {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }

    .ttt-header-close-btn {
        background: #f1f5f9;
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        color: #475569;
        cursor: pointer;
        margin-left: auto;
        transition: all 0.2s ease;
    }

    .ttt-sheet-input-row {
        display: flex;
        align-items: center;
        border: 1px solid #dbe3ec;
        border-radius: 8px;
        overflow: hidden;
        background: #ffffff;
    }

    .ttt-sheet-input-row:focus-within {
        border-color: #00285a;
        box-shadow: 0 0 0 3px rgba(0, 40, 90, 0.08);
    }

    .ttt-sheet-input-row input {
        flex: 1;
        height: 44px;
        border: none;
        outline: none;
        padding: 0 14px;
        font-size: 13.5px;
        color: #0f172a;
        font-weight: 600;
        text-transform: uppercase;
    }

    .ttt-sheet-input-row input::placeholder {
        color: #94a3b8;
        text-transform: none;
        font-weight: 500;
    }

    .ttt-sheet-apply-btn {
        height: 44px;
        padding: 0 18px;
        background: none;
        border: none;
        font-size: 13px;
        font-weight: 800;
        color: #00285a;
        cursor: pointer;
        transition: color 0.15s ease;
    }

    .ttt-sheet-tabs {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 2px;
    }

    .ttt-tab-pill {
        padding: 6px 14px;
        background: #f1f5f9;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.15s ease;
    }

    .ttt-tab-pill.active {
        background: #e2e8f0;
        color: #0f172a;
    }

    .ttt-sheet-section-title {
        font-size: 13px;
        font-weight: 800;
        color: #334155;
        margin-top: 2px;
    }

    .ttt-coupons-cards-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .ttt-coupon-card-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .ttt-threshold-warning-bar {
        background: #fff1f2;
        color: #e11d48;
        padding: 6px 14px;
        font-size: 11.5px;
        font-weight: 700;
        text-align: center;
        border-bottom: 1px dashed #fecdd3;
    }

    .ttt-coupon-card-main {
        padding: 12px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .ttt-coupon-card-left {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        flex: 1;
    }

    .ttt-card-percent-badge {
        width: 22px;
        height: 22px;
        border: 1.5px solid #475569;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        color: #334155;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .ttt-card-body-text {
        flex: 1;
    }

    .ttt-card-title-line {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 3px;
    }

    .ttt-code-dashed-pill {
        display: inline-block;
        border: 1px dashed #94a3b8;
        background: #f8fafc;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.5px;
        color: #0f172a;
    }

    .ttt-card-sub-line {
        font-size: 11.5px;
        color: #64748b;
        line-height: 1.4;
    }

    .ttt-card-apply-btn {
        padding: 7px 16px;
        background: #ffffff;
        border: 1.5px solid #0f172a;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 800;
        color: #0f172a;
        cursor: pointer;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .ttt-sheet-bottom-bar {
        padding-top: 8px;
        margin-top: auto;
    }

    .ttt-sheet-explore-btn {
        width: 100%;
        height: 42px;
        background: #047857;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    </style>

    {{-- Global Coupons & Offers Bottom Sheet Modal (Screenshot 2 / media_1788341030416.png) --}}
    <div class="ttt-coupon-modal-backdrop" id="couponModalBackdrop" onclick="closeCouponsModal()"></div>
    <div class="ttt-coupon-bottom-sheet" id="couponBottomSheet" role="dialog" aria-modal="true" aria-labelledby="couponSheetTitle">
        {{-- Floating Top Circle Close Button --}}
        <button type="button" class="ttt-sheet-close-btn" onclick="closeCouponsModal()" aria-label="Close modal">
            <i class="bi bi-x-lg"></i>
        </button>

        <div class="ttt-sheet-inner">
            <div class="ttt-sheet-header">
                <span class="ttt-sheet-head-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2l2.4 2.4 3.4-.6 1.2 3.2 3.2 1.2-.6 3.4 2.4 2.4-2.4 2.4.6 3.4-3.2 1.2-1.2 3.2-3.4-.6-2.4 2.4-2.4-2.4-3.4.6-1.2-3.2-3.2-1.2.6-3.4-2.4-2.4 2.4-2.4-.6-3.4 3.2-1.2 1.2-3.2 3.4.6 2.4-2.4z"/>
                        <line x1="9" y1="15" x2="15" y2="9"/>
                        <circle cx="9.5" cy="9.5" r=".7" fill="#475569"/>
                        <circle cx="14.5" cy="14.5" r=".7" fill="#475569"/>
                    </svg>
                </span>
                <h3 class="ttt-sheet-title" id="couponSheetTitle">Coupons &amp; Offers</h3>
                <button type="button" class="ttt-header-close-btn" onclick="closeCouponsModal()" aria-label="Close modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="ttt-sheet-input-row">
                <input type="text" id="modalCouponInput" placeholder="Enter coupon code" autocomplete="off">
                <button type="button" class="ttt-sheet-apply-btn" onclick="applyModalCoupon()">Apply</button>
            </div>

            <div class="ttt-sheet-tabs">
                <button type="button" class="ttt-tab-pill active" onclick="filterModalCoupons('all', this)">Active Coupons</button>
                <button type="button" class="ttt-tab-pill" onclick="filterModalCoupons('payment', this)">Payment Offers</button>
                <button type="button" class="ttt-tab-pill" onclick="filterModalCoupons('other', this)">Other Coupons</button>
            </div>

            <div class="ttt-sheet-section-title" id="couponCategoryTitle">Brand Offers</div>

            @php
                $globalCoupons = \App\Models\Coupon::with('rules')->where('is_active', true)->get();
            @endphp
            <div class="ttt-coupons-cards-list" id="modalCouponsList">
                @foreach($globalCoupons as $cpn)
                    @php
                        $rules = $cpn->rules;
                        $minSpend = $rules ? (float)$rules->min_order_amount : 0;
                        $isPaymentOffer = ($cpn->code === 'PREPAID5');
                    @endphp
                    <div class="ttt-coupon-card-item {{ $isPaymentOffer ? 'cat-payment' : 'cat-brand' }}" data-category="{{ $isPaymentOffer ? 'payment' : 'brand' }}">
                        @if($minSpend > 2000)
                            <div class="ttt-threshold-warning-bar">
                                Add item worth ₹{{ number_format(round($minSpend - 1299)) }} amount more to avail
                            </div>
                        @endif
                        <div class="ttt-coupon-card-main">
                            <div class="ttt-coupon-card-left">
                                <span class="ttt-card-percent-badge">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2l2.4 2.4 3.4-.6 1.2 3.2 3.2 1.2-.6 3.4 2.4 2.4-2.4 2.4.6 3.4-3.2 1.2-1.2 3.2-3.4-.6-2.4 2.4-2.4-2.4-3.4.6-1.2-3.2-3.2-1.2.6-3.4-2.4-2.4 2.4-2.4-.6-3.4 3.2-1.2 1.2-3.2 3.4.6 2.4-2.4z"/>
                                        <line x1="9" y1="15" x2="15" y2="9"/>
                                        <circle cx="9.5" cy="9.5" r=".7" fill="#475569"/>
                                        <circle cx="14.5" cy="14.5" r=".7" fill="#475569"/>
                                    </svg>
                                </span>
                                <div class="ttt-card-body-text">
                                    <div class="ttt-card-title-line">
                                        @if($minSpend <= 2000)
                                            <span>Save ₹130 with</span>
                                        @endif
                                        <span class="ttt-code-dashed-pill">{{ $cpn->code }}</span>
                                    </div>
                                    <div class="ttt-card-sub-line">
                                        @if($minSpend > 2000)
                                            Add items worth {{ number_format($minSpend) }} to unlock {{ $cpn->type === 'percentage' ? (int)$cpn->value.'% off' : '₹'.(int)$cpn->value.' off' }} with code {{ $cpn->code }}
                                        @else
                                            {{ $cpn->description ?: 'Applicable on Prepaid & COD orders. T&C Apply' }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="ttt-card-apply-btn" onclick="applyCouponCodeSpecific('{{ $cpn->code }}')">
                                Apply
                            </button>
                        </div>

                        @if($minSpend > 2000)
                            <div class="ttt-card-eligible-btn-wrap">
                                <button type="button" class="ttt-sheet-explore-btn" onclick="closeCouponsModal(); window.location.href='{{ route('shop.index') }}';">
                                    Show me eligible products <i class="bi bi-chevron-down"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
    function openCouponsModal() {
        const sheet = document.getElementById('couponBottomSheet');
        const backdrop = document.getElementById('couponModalBackdrop');
        if (sheet && backdrop) {
            sheet.classList.add('is-open');
            backdrop.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeCouponsModal() {
        const sheet = document.getElementById('couponBottomSheet');
        const backdrop = document.getElementById('couponModalBackdrop');
        if (sheet && backdrop) {
            sheet.classList.remove('is-open');
            backdrop.classList.remove('is-open');
            document.body.style.overflow = '';
        }
    }

    function filterModalCoupons(cat, btn) {
        document.querySelectorAll('.ttt-tab-pill').forEach(el => el.classList.remove('active'));
        if (btn) btn.classList.add('active');
        
        const title = document.getElementById('couponCategoryTitle');
        if (title) {
            if (cat === 'payment') title.textContent = 'Payment Offers';
            else if (cat === 'other') title.textContent = 'Other Coupons';
            else title.textContent = 'Brand Offers';
        }

        document.querySelectorAll('.ttt-coupon-card-item').forEach(card => {
            const cardCat = card.getAttribute('data-category');
            if (cat === 'all' || cardCat === cat) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
    </script>

    {{-- ═══════════════════════════════════════════════════════════════════
         PARTY CELEBRATION COUPON POPUP & CONFETTI
         ═══════════════════════════════════════════════════════════════════ --}}
    <div class="ttt-party-modal-backdrop" id="tttPartyCouponModal" aria-hidden="true" onclick="closeCouponPartyPopup(event)">
        <div class="ttt-party-card" onclick="event.stopPropagation()">
            <button type="button" class="ttt-party-close-btn" onclick="closeCouponPartyPopup()" aria-label="Close popup">&times;</button>
            
            <div class="ttt-party-icon-burst">
                <div class="ttt-party-icon-circle">
                    🎉
                </div>
                <div class="ttt-party-sparkle s1">✨</div>
                <div class="ttt-party-sparkle s2">✨</div>
            </div>

            <div class="ttt-party-ribbon">COUPON APPLIED</div>

            <h3 class="ttt-party-heading">
                YAY! YOU SAVED <span class="ttt-party-amount" id="partySavedAmount">₹0</span>
            </h3>

            <div class="ttt-party-coupon-pill">
                <span class="ttt-party-flower-icon">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2">
                        <path d="M12 2l2.4 2.4 3.4-.6 1.2 3.2 3.2 1.2-.6 3.4 2.4 2.4-2.4 2.4.6 3.4-3.2 1.2-1.2 3.2-3.4-.6-2.4 2.4-2.4-2.4-3.4.6-1.2-3.2-3.2-1.2.6-3.4-2.4-2.4 2.4-2.4-.6-3.4 3.2-1.2 1.2-3.2 3.4.6 2.4-2.4z"/>
                    </svg>
                </span>
                <strong id="partyCouponCode">CODE</strong>
                <span class="ttt-party-applied-tag">Applied!</span>
            </div>

            <p class="ttt-party-desc" id="partyCouponDesc">
                Woohoo! Your extra savings have been successfully added to your bag.
            </p>

            <button type="button" class="ttt-party-cta-btn" onclick="closeCouponPartyPopup()">
                <span>YAY! CONTINUE</span>
                <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </div>

    <style>
    .ttt-party-modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 10000000;
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.3s ease;
    }
    .ttt-party-modal-backdrop.is-open {
        visibility: visible;
        opacity: 1;
    }
    .ttt-party-card {
        position: relative;
        background: #ffffff;
        border-radius: 24px;
        width: 100%;
        max-width: 380px;
        padding: 32px 24px 24px;
        text-align: center;
        box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(0, 0, 0, 0.05);
        transform: scale(0.6) translateY(40px) rotate(-3deg);
        transition: transform 0.45s cubic-bezier(0.34, 1.56, 0.64, 1);
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        overflow: hidden;
    }
    .ttt-party-modal-backdrop.is-open .ttt-party-card {
        transform: scale(1) translateY(0) rotate(0deg);
    }
    .ttt-party-close-btn {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        background: #f1f5f9;
        color: #64748b;
        font-size: 20px;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
        z-index: 10;
    }
    .ttt-party-icon-burst {
        position: relative;
        width: 86px;
        height: 86px;
        margin: 0 auto 16px;
        perspective: 600px;
    }
    .ttt-party-icon-circle {
        width: 86px;
        height: 86px;
        border-radius: 50%;
        background: linear-gradient(135deg, #fef08a 0%, #f59e0b 50%, #ea580c 100%);
        border: 4px solid #ffffff;
        box-shadow: 0 12px 28px rgba(245, 158, 11, 0.4), 0 0 0 8px rgba(254, 240, 138, 0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 42px;
        animation: partyBounce3D 1.8s cubic-bezier(0.28, 0.84, 0.42, 1) infinite;
        transform-style: preserve-3d;
    }
    @keyframes partyBounce3D {
        0%, 100% {
            transform: translateY(0) scale(1) rotateY(0deg) rotateZ(0deg);
            box-shadow: 0 12px 28px rgba(245, 158, 11, 0.4), 0 0 0 6px rgba(254, 240, 138, 0.35);
        }
        25% {
            transform: translateY(-8px) scale(1.12) rotateY(15deg) rotateZ(-6deg);
            box-shadow: 0 20px 36px rgba(245, 158, 11, 0.5), 0 0 0 12px rgba(254, 240, 138, 0.45);
        }
        50% {
            transform: translateY(-4px) scale(1.06) rotateY(-12deg) rotateZ(4deg);
            box-shadow: 0 16px 30px rgba(245, 158, 11, 0.45), 0 0 0 8px rgba(254, 240, 138, 0.4);
        }
        75% {
            transform: translateY(-10px) scale(1.15) rotateY(8deg) rotateZ(-4deg);
            box-shadow: 0 22px 40px rgba(245, 158, 11, 0.55), 0 0 0 14px rgba(254, 240, 138, 0.5);
        }
    }
    .ttt-party-sparkle {
        position: absolute;
        font-size: 20px;
        animation: sparkleSpin3D 2s ease-in-out infinite;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.15));
    }
    .ttt-party-sparkle.s1 {
        top: -8px;
        right: -10px;
    }
    .ttt-party-sparkle.s2 {
        bottom: -4px;
        left: -8px;
        animation-delay: 1s;
    }
    @keyframes sparkleSpin3D {
        0%, 100% { transform: scale(0.8) rotate(0deg) translateY(0); opacity: 0.7; }
        50% { transform: scale(1.3) rotate(180deg) translateY(-4px); opacity: 1; }
    }
    .ttt-party-ribbon {
        display: inline-block;
        background: #ecfdf5;
        color: #059669;
        font-size: 11px;
        font-weight: 900;
        letter-spacing: 1px;
        padding: 4px 14px;
        border-radius: 999px;
        margin-bottom: 8px;
        border: 1px solid #a7f3d0;
    }
    .ttt-party-heading {
        font-size: 20px;
        font-weight: 900;
        color: #0f172a;
        margin: 0 0 12px;
        line-height: 1.3;
    }
    .ttt-party-amount {
        color: #059669;
        display: inline-block;
        text-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
    }
    .ttt-party-coupon-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f8fafc;
        border: 1.5px dashed #10b981;
        border-radius: 8px;
        padding: 6px 14px;
        margin: 0 auto 12px;
    }
    .ttt-party-coupon-pill strong {
        font-size: 13.5px;
        font-weight: 900;
        color: #0f172a;
        letter-spacing: 0.5px;
    }
    .ttt-party-applied-tag {
        font-size: 11px;
        font-weight: 800;
        color: #059669;
        background: #ecfdf5;
        padding: 2px 8px;
        border-radius: 4px;
    }
    .ttt-party-desc {
        font-size: 12.5px;
        color: #64748b;
        margin: 0 0 22px;
        line-height: 1.45;
        font-weight: 500;
    }
    .ttt-party-cta-btn {
        width: 100%;
        height: 48px;
        background: #00285a;
        color: #ffffff;
        border: none;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 900;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 6px 20px rgba(0, 40, 90, 0.2);
    }
    </style>

    <script>
    var partyPopupTimer = null;

    window.launchPartyConfetti = function() {
        var canvas = document.getElementById('tttConfettiCanvas');
        if (!canvas) {
            canvas = document.createElement('canvas');
            canvas.id = 'tttConfettiCanvas';
            canvas.style.position = 'fixed';
            canvas.style.top = '0';
            canvas.style.left = '0';
            canvas.style.width = '100vw';
            canvas.style.height = '100vh';
            canvas.style.pointerEvents = 'none';
            canvas.style.zIndex = '99999999';
            document.body.appendChild(canvas);
        }
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        var ctx = canvas.getContext('2d');
        var particles = [];
        var colors = ['#ff3f6c', '#10b981', '#fbbf24', '#3b82f6', '#8b5cf6', '#ec4899', '#f97316', '#06b6d4', '#eab308'];

        // Dual cannon burst: Left & Right + Center burst!
        for (var i = 0; i < 150; i++) {
            var originX = canvas.width / 2;
            var originY = canvas.height * 0.45;
            var vx = (Math.random() - 0.5) * 22;
            var vy = (Math.random() - 1.3) * 18;

            if (i < 50) {
                originX = canvas.width * 0.25;
                vx = (Math.random() * 12 + 2);
                vy = (Math.random() - 1.4) * 16;
            } else if (i < 100) {
                originX = canvas.width * 0.75;
                vx = -(Math.random() * 12 + 2);
                vy = (Math.random() - 1.4) * 16;
            }

            particles.push({
                x: originX + (Math.random() - 0.5) * 40,
                y: originY + (Math.random() - 0.5) * 40,
                vx: vx,
                vy: vy,
                size: Math.random() * 9 + 5,
                color: colors[Math.floor(Math.random() * colors.length)],
                rotation: Math.random() * 360,
                rSpeed: (Math.random() - 0.5) * 14,
                opacity: 1,
                shape: Math.random() > 0.4 ? 'rect' : 'circle'
            });
        }

        var startTime = Date.now();
        function render() {
            var elapsed = Date.now() - startTime;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            var activeCount = 0;

            particles.forEach(function(p) {
                p.x += p.vx;
                p.y += p.vy;
                p.vy += 0.38; // gravity
                p.vx *= 0.985; // drag
                p.rotation += p.rSpeed;
                if (elapsed > 2200) {
                    p.opacity -= 0.03;
                }

                if (p.opacity > 0 && p.y < canvas.height + 20) {
                    activeCount++;
                    ctx.save();
                    ctx.globalAlpha = Math.max(0, p.opacity);
                    ctx.translate(p.x, p.y);
                    ctx.rotate((p.rotation * Math.PI) / 180);
                    ctx.fillStyle = p.color;

                    if (p.shape === 'rect') {
                        ctx.fillRect(-p.size / 2, -p.size / 2, p.size, p.size * 0.65);
                    } else {
                        ctx.beginPath();
                        ctx.arc(0, 0, p.size / 2, 0, Math.PI * 2);
                        ctx.fill();
                    }
                    ctx.restore();
                }
            });

            if (activeCount > 0 && elapsed < 4800) {
                requestAnimationFrame(render);
            } else {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            }
        }
        render();
    };

    window.showCouponPartyPopup = function(data) {
        var modal = document.getElementById('tttPartyCouponModal');
        var codeEl = document.getElementById('partyCouponCode');
        var amountEl = document.getElementById('partySavedAmount');
        var descEl = document.getElementById('partyCouponDesc');

        if (!modal) return;

        var code = (data && data.code) ? String(data.code).toUpperCase() : 'COUPON';
        var disc = (data && data.discount) ? Number(data.discount) : 0;
        var formatted = disc > 0 ? ('₹' + Math.round(disc).toLocaleString('en-IN')) : 'EXTRA SAVINGS';

        if (codeEl) codeEl.textContent = code;
        if (amountEl) amountEl.textContent = formatted;
        if (descEl) {
            descEl.textContent = (data && data.message) ? data.message : 'Woohoo! Your extra savings have been successfully added to your bag.';
        }

        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');

        // Trigger celebratory confetti burst
        if (typeof window.launchPartyConfetti === 'function') {
            window.launchPartyConfetti();
        }

        if (navigator.vibrate) {
            try { navigator.vibrate([80, 40, 120]); } catch(e) {}
        }

        if (partyPopupTimer) clearTimeout(partyPopupTimer);
        partyPopupTimer = setTimeout(function() {
            closeCouponPartyPopup();
        }, 4000);
    };

    window.closeCouponPartyPopup = function(e) {
        if (e && e.target && e.target.id !== 'tttPartyCouponModal' && !e.target.classList.contains('ttt-party-close-btn') && !e.target.closest('.ttt-party-cta-btn')) {
            return;
        }
        var modal = document.getElementById('tttPartyCouponModal');
        if (modal) {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
        }
        if (partyPopupTimer) clearTimeout(partyPopupTimer);
    };

    function applyCouponCodeSpecific(code) {
        const gcoInp = document.getElementById('gcoCouponInput');
        if (gcoInp) gcoInp.value = code;
        const mainInp = document.getElementById('couponCodeInput');
        if (mainInp) mainInp.value = code;

        if (typeof window.gcoApplyCoupon === 'function') {
            window.gcoApplyCoupon(code);
            closeCouponsModal();
        } else if (typeof applyCouponCode === 'function') {
            applyCouponCode(code);
        }
    }

    function applyModalCoupon() {
        const code = (document.getElementById('modalCouponInput') ? document.getElementById('modalCouponInput').value : '').trim();
        if (!code) {
            alert('Please enter a coupon code.');
            return;
        }
        applyCouponCodeSpecific(code);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCouponsModal();
            closeCouponPartyPopup();
        }
    });
    </script>

    @stack('scripts')

    {{-- ═══════════════════════════════════════════════════════════════════
         THE TREND THEORY AI STYLE ASSISTANT WIDGET
         ═══════════════════════════════════════════════════════════════════ --}}

    {{-- Floating Launcher Button --}}
    <button type="button" id="tttAILauncher" onclick="tttAIOpen()" aria-label="Open THE TREND THEORY AI Style Assistant">
        <span class="ttt-ai-avatar-ring">
            <span class="ttt-ai-avatar-inner">V</span>
        </span>
       
        <span class="ttt-ai-notif-dot"></span>
    </button>

    {{-- Chat Window --}}
    <div id="tttAIChatWindow" aria-live="polite">
        {{-- Header --}}
        <div class="ttt-ai-header">
            <div class="ttt-ai-header-left">
                <div class="ttt-ai-header-avatar">
                    <span>V</span>
                    <span class="ttt-ai-status-dot"></span>
                </div>
                <div class="ttt-ai-header-info">
                    <h5>THE TREND THEORY AI <span class="ttt-ai-badge">LIVE</span></h5>
                    <p><span class="ttt-ai-online-blink">●</span> Online · Style Support</p>
                </div>
            </div>
            <div class="ttt-ai-header-actions">
                <button type="button" onclick="tttAIClear()" title="Clear chat"><i class="bi bi-trash3"></i></button>
                <button type="button" onclick="tttAIClose()" title="Close"><i class="bi bi-x-lg"></i></button>
            </div>
        </div>

        {{-- Messages Area --}}
        <div class="ttt-ai-messages" id="tttAIMessages">
            <div class="ttt-ai-msg ttt-ai-msg--bot">
                <div class="ttt-ai-msg-avatar">V</div>
                <div class="ttt-ai-msg-bubble">
                    <p>Hi, I am <strong>THE TREND THEORY AI</strong>, your style assistant at <strong>THE TREND THEORY</strong>.</p>
                    <p style="margin-top:6px;">Ask me about sizes, delivery, offers, returns, or styling help.</p>
                    <div class="ttt-ai-quick-chips" id="tttAIQuickChips">
                        <button onclick="tttAIQuickSend('What sizes do you have?')">📏 Sizes</button>
                        <button onclick="tttAIQuickSend('How long does delivery take?')">🚚 Delivery</button>
                        <button onclick="tttAIQuickSend('Do you have any offers?')">🎁 Offers</button>
                        <button onclick="tttAIQuickSend('How can I track my order?')">📦 Track Order</button>
                        <button onclick="tttAIQuickSend('What is your return policy?')">🔄 Returns</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Typing Indicator (hidden by default) --}}
        <div class="ttt-ai-typing" id="tttAITyping" style="display:none;">
            <div class="ttt-ai-msg-avatar" style="width:28px;height:28px;font-size:11px;">V</div>
            <div class="ttt-ai-typing-bubbles">
                <span></span><span></span><span></span>
            </div>
            <span style="font-size:11px;color:#94a3b8;margin-left:4px;">THE TREND THEORY AI is typing...</span>
        </div>

        {{-- Input Bar --}}
        <div class="ttt-ai-input-bar">
            <input type="text" id="tttAIInput" placeholder="Ask anything about fashion, orders…" maxlength="300" autocomplete="off" />
            <button type="button" id="tttAISendBtn" onclick="tttAISend()" aria-label="Send">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
        <div class="ttt-ai-footer-note">THE TREND THEORY AI · Style Assistant by THE TREND THEORY</div>
    </div>

    <style>
    /* THE TREND THEORY AI WIDGET */
    #tttAILauncher {
        position: fixed;
        bottom: 26px;
        left: 26px;
        z-index: 9999990;
        display: flex;
        align-items: center;
        gap: 10px;
        background: #00285a;
        color: #fff;
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 16px;
        padding: 10px 16px 10px 10px;
        box-shadow: 0 18px 44px rgba(0, 40, 90, 0.32);
        cursor: pointer;
        transition: transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s ease;
        font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
    }
    .ttt-ai-avatar-ring {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #14b8a6, #ff3f6c);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 0 2px rgba(255,255,255,0.3);
        flex-shrink: 0;
        animation: tttAIPulse 2.5s ease infinite;
    }
    .ttt-ai-avatar-inner {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #fff;
        color: #00285a;
        font-weight: 900;
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    @keyframes tttAIPulse {
        0%, 100% { box-shadow: 0 0 0 2px rgba(255,255,255,0.3), 0 0 0 0 rgba(255,63,108,0.4); }
        50% { box-shadow: 0 0 0 2px rgba(255,255,255,0.3), 0 0 0 8px rgba(255,63,108,0); }
    }
    .ttt-ai-launcher-label {
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }
    .ttt-ai-notif-dot {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 10px;
        height: 10px;
        background: #ff3f6c;
        border-radius: 50%;
        border: 2px solid #fff;
        animation: tttAIDotBlink 1.8s ease infinite;
    }
    @keyframes tttAIDotBlink {
        0%, 100% { opacity: 1; } 50% { opacity: 0.3; }
    }

    /* ── Chat Window ── */
    #tttAIChatWindow {
        position: fixed;
        bottom: 90px;
        left: 26px;
        z-index: 9999989;
        width: 370px;
        max-width: calc(100vw - 32px);
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        box-shadow: 0 26px 70px rgba(15, 23, 42, 0.24);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transform: scale(0.85) translateY(20px);
        opacity: 0;
        pointer-events: none;
        transform-origin: bottom left;
        transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.25s ease;
        font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
        max-height: 560px;
    }
    #tttAIChatWindow.is-open {
        transform: scale(1) translateY(0);
        opacity: 1;
        pointer-events: all;
    }

    /* Header */
    .ttt-ai-header {
        background: #00285a;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }
    .ttt-ai-header-left { display: flex; align-items: center; gap: 10px; }
    .ttt-ai-header-avatar {
        position: relative;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #14b8a6, #ff3f6c);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        font-weight: 900;
        color: #ffffff;
        flex-shrink: 0;
    }
    .ttt-ai-status-dot {
        position: absolute;
        bottom: 1px;
        right: 1px;
        width: 10px;
        height: 10px;
        background: #22c55e;
        border-radius: 50%;
        border: 2px solid #00285a;
    }
    .ttt-ai-header-info h5 {
        color: #fff;
        font-size: 14px;
        font-weight: 800;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .ttt-ai-badge {
        background: rgba(255,255,255,0.18);
        color: #fff;
        font-size: 9px;
        font-weight: 900;
        letter-spacing: 1px;
        padding: 1px 6px;
        border-radius: 4px;
        vertical-align: middle;
    }
    .ttt-ai-header-info p {
        color: rgba(255,255,255,0.72);
        font-size: 11px;
        margin: 2px 0 0;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .ttt-ai-online-blink {
        color: #22c55e;
        font-size: 9px;
        animation: tttAIDotBlink 1.5s ease infinite;
    }
    .ttt-ai-header-actions {
        display: flex;
        gap: 6px;
    }
    .ttt-ai-header-actions button {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        border: none;
        background: rgba(255,255,255,0.12);
        color: rgba(255,255,255,0.85);
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.15s ease;
    }

    /* Messages */
    .ttt-ai-messages {
        flex: 1;
        overflow-y: auto;
        padding: 14px 14px 6px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        min-height: 200px;
        max-height: 340px;
        scroll-behavior: smooth;
        background: linear-gradient(180deg, #f8fafc 0%, #eef6f5 100%);
    }
    .ttt-ai-messages::-webkit-scrollbar { width: 4px; }
    .ttt-ai-messages::-webkit-scrollbar-track { background: transparent; }
    .ttt-ai-messages::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }

    .ttt-ai-msg {
        display: flex;
        align-items: flex-end;
        gap: 7px;
        animation: tttMsgIn 0.3s cubic-bezier(0.34, 1.4, 0.64, 1) both;
    }
    @keyframes tttMsgIn {
        from { opacity: 0; transform: translateY(10px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .ttt-ai-msg--user { flex-direction: row-reverse; }

    .ttt-ai-msg-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ff3f6c, #fbbf24);
        color: #fff;
        font-size: 12px;
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .ttt-ai-msg--user .ttt-ai-msg-avatar {
        background: linear-gradient(135deg, #0f172a, #334155);
    }

    .ttt-ai-msg-bubble {
        max-width: 78%;
        padding: 10px 13px;
        border-radius: 14px 14px 14px 5px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        font-size: 13px;
        color: #0f172a;
        line-height: 1.5;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .ttt-ai-msg--user .ttt-ai-msg-bubble {
        background: #0f172a;
        color: #fff;
        border: none;
        border-radius: 14px 14px 5px 14px;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.22);
    }
    .ttt-ai-msg-bubble p { margin: 0; }

    /* Quick Chips */
    .ttt-ai-quick-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-top: 9px;
    }
    .ttt-ai-quick-chips button {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 11.5px;
        font-weight: 700;
        color: #0f172a;
        padding: 4px 10px;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap;
    }

    /* Typing indicator */
    .ttt-ai-typing {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 4px 14px 6px;
        flex-shrink: 0;
        background: #f8fafc;
    }
    .ttt-ai-typing-bubbles {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 8px 12px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .ttt-ai-typing-bubbles span {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #94a3b8;
        display: inline-block;
        animation: tttTypingBounce 1.2s ease infinite;
    }
    .ttt-ai-typing-bubbles span:nth-child(2) { animation-delay: 0.2s; }
    .ttt-ai-typing-bubbles span:nth-child(3) { animation-delay: 0.4s; }
    @keyframes tttTypingBounce {
        0%, 100% { transform: translateY(0); background: #94a3b8; }
        50% { transform: translateY(-5px); background: #00285a; }
    }

    /* Input Bar */
    .ttt-ai-input-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 12px;
        border-top: 1px solid #e2e8f0;
        background: #ffffff;
        flex-shrink: 0;
    }
    .ttt-ai-input-bar input {
        flex: 1;
        height: 40px;
        border: 1.5px solid #e2e8f0;
        border-radius: 999px;
        padding: 0 14px;
        font-size: 13px;
        font-family: inherit;
        color: #0f172a;
        background: #f8fafc;
        outline: none;
        transition: border-color 0.18s ease, box-shadow 0.18s ease;
    }
    .ttt-ai-input-bar input:focus {
        border-color: #00285a;
        box-shadow: 0 0 0 3px rgba(0, 40, 90, 0.08);
        background: #fff;
    }
    #tttAISendBtn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #00285a;
        border: none;
        color: #fff;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 8px 18px rgba(0, 40, 90, 0.28);
    }
    #tttAISendBtn:disabled { opacity: 0.5; cursor: default; transform: none; }
    .ttt-ai-footer-note {
        text-align: center;
        font-size: 10px;
        color: #94a3b8;
        padding: 4px 0 8px;
        background: #fff;
        letter-spacing: 0.3px;
        flex-shrink: 0;
    }

    @media (max-width: 991px) {
        #tttAILauncher, #tttAIChatWindow {
            display: none !important;
        }
    }

    @media (max-width: 480px) {
        #tttAIChatWindow { left: 10px; bottom: 80px; width: calc(100vw - 20px); }
        #tttAILauncher { left: 14px; bottom: 20px; }
    }

    .ttt-order-loader.is-success .ttt-order-loader__spinner::after {
        transform: rotate(45deg) !important;
    }
    </style>

    <script>
    // THE TREND THEORY AI STATE
    var tttAIIsOpen = false;
    var tttAIConversation = []; // {role:'user'|'bot', text:''}
    var tttAITypingTimer = null;

    function tttAIOpen() {
        tttAIIsOpen = true;
        document.getElementById('tttAIChatWindow').classList.add('is-open');
        // Hide notif dot once opened
        var dot = document.querySelector('.ttt-ai-notif-dot');
        if (dot) dot.style.display = 'none';
        setTimeout(function() {
            var inp = document.getElementById('tttAIInput');
            if (inp) inp.focus();
        }, 350);
    }

    function tttAIClose() {
        tttAIIsOpen = false;
        document.getElementById('tttAIChatWindow').classList.remove('is-open');
    }

    function tttAIClear() {
        tttAIConversation = [];
        var container = document.getElementById('tttAIMessages');
        if (!container) return;
        container.innerHTML = '<div class="ttt-ai-msg ttt-ai-msg--bot">'
            + '<div class="ttt-ai-msg-avatar">V</div>'
            + '<div class="ttt-ai-msg-bubble"><p>Chat cleared! How can I help you today? 😊</p>'
            + '<div class="ttt-ai-quick-chips" id="tttAIQuickChips">'
            + '<button onclick="tttAIQuickSend(\'What sizes do you have?\')">📏 Sizes</button>'
            + '<button onclick="tttAIQuickSend(\'How long does delivery take?\')">🚚 Delivery</button>'
            + '<button onclick="tttAIQuickSend(\'Do you have any offers?\')">🎁 Offers</button>'
            + '<button onclick="tttAIQuickSend(\'How can I track my order?\')">📦 Track Order</button>'
            + '</div></div></div>';
    }

    function tttAIQuickSend(text) {
        var inp = document.getElementById('tttAIInput');
        if (inp) inp.value = text;
        tttAISend();
    }

    function tttAIAddMsg(role, text) {
        var container = document.getElementById('tttAIMessages');
        if (!container) return;
        var div = document.createElement('div');
        div.className = 'ttt-ai-msg' + (role === 'user' ? ' ttt-ai-msg--user' : ' ttt-ai-msg--bot');

        var avatarDiv = document.createElement('div');
        avatarDiv.className = 'ttt-ai-msg-avatar';
        avatarDiv.textContent = role === 'user' ? 'Y' : 'V';

        var bubbleDiv = document.createElement('div');
        bubbleDiv.className = 'ttt-ai-msg-bubble';
        bubbleDiv.innerHTML = '<p>' + text.replace(/\n/g, '<br>') + '</p>';

        div.appendChild(avatarDiv);
        div.appendChild(bubbleDiv);
        container.appendChild(div);

        // Scroll to bottom
        container.scrollTop = container.scrollHeight;
    }

    function tttAIShowTyping() {
        var el = document.getElementById('tttAITyping');
        if (el) el.style.display = 'flex';
        var container = document.getElementById('tttAIMessages');
        if (container) container.scrollTop = container.scrollHeight;
    }

    function tttAIHideTyping() {
        var el = document.getElementById('tttAITyping');
        if (el) el.style.display = 'none';
    }

    // ── Knowledge Base ──
    function tttAIGetResponse(msg) {
        var m = msg.toLowerCase().trim();

        // Sizes
        if (m.match(/size|sizing|fit|xs|xl|xxl|measurements|chart/)) {
            return "We offer sizes XS, S, M, L, XL, XXL for most styles. 📏\n\nEach product page has a detailed **Size Guide** button. For the best fit, check the shoulder & chest measurements shown there. Need help with a specific item?";
        }
        // Delivery
        if (m.match(/deliver|shipping|dispatch|arrive|when|days/)) {
            return "🚚 **Delivery Timeline:**\n• Express: 2–4 business days\n• Standard: 5–7 business days\n\nFree shipping on orders above ₹999. You'll get a tracking link via email/SMS as soon as your order is dispatched!";
        }
        // Tracking
        if (m.match(/track|tracking|order status|where is my order/)) {
            return "📦 You can track your order at **My Orders** section after logging in, or visit our Order Tracking page!\n\nYou'll also receive real-time updates via email and WhatsApp after dispatch.";
        }
        // Returns / Refunds
        if (m.match(/return|refund|exchange|replace|policy/)) {
            return "🔄 **Return Policy:**\n• 7-day easy returns from delivery date\n• Item must be unused with original tags\n• Refund is processed in 5–7 business days\n\nTo initiate a return, go to **My Orders** → select item → Request Return.";
        }
        // Offers / Coupons
        if (m.match(/offer|coupon|discount|promo|deal|code|sale/)) {
            return "🎁 **Current Offers:**\n• **SHARKTANK10** → 10% off on all orders\n• **EXTRA10** → Extra 10% off on ₹999+\n• 5% instant off on all Prepaid orders!\n\nApply coupons at checkout. New deals drop every weekend!";
        }
        // Payment
        if (m.match(/pay|payment|upi|gpay|phonepe|paytm|card|cod|cash|netbanking/)) {
            return "💳 **We accept:**\n• UPI (Google Pay, PhonePe, Paytm, BHIM)\n• Debit & Credit Cards (Visa, Mastercard, RuPay)\n• Net Banking\n• Cash on Delivery (COD)\n\n5% extra discount on all Prepaid (online) payments!";
        }
        // Authenticity
        if (m.match(/original|genuine|fake|authentic|quality/)) {
            return "✅ Every product at THE TREND THEORY is **100% authentic** and quality-checked before dispatch. We work directly with designers and trusted manufacturers.\n\nNot satisfied? Our 7-day return policy has you covered.";
        }
        // Contact / Support
        if (m.match(/contact|support|help|human|agent|call|email/)) {
            return "📬 **Reach our team:**\n• Email: {{ $siteEmail ?? 'support@thetrendtheory.com' }}\n• Response time: within 24 hours\n\nFor urgent queries, you can also leave a message here and I'll guide you! 😊";
        }
        // Styling tips
        if (m.match(/style|outfit|wear|pair|look|trend|fashion|suggest/)) {
            return "👗 Great taste! Here are some quick style tips:\n• Pair our oversized tees with slim-fit joggers for a streetwear look\n• Layer hoodies under structured jackets for a smart-casual vibe\n• Use a statement accessory to elevate any basic outfit\n\nWant specific recommendations? Tell me your style preference!";
        }
        // Hello/greeting
        if (m.match(/^(hi|hello|hey|good morning|good evening|hii|helo|namaste|sup|yo)/)) {
            return "Hi, great to see you. I am **THE TREND THEORY AI**, your style assistant at THE TREND THEORY. Ask me about sizes, styling tips, delivery, offers, or anything else.";
        }
        // Thank you
        if (m.match(/thank|thanks|ty|great|awesome|perfect|cool/)) {
            return "You're welcome! 😊 Happy to help. If you need anything else — sizing, styling, or order help — just ask! Happy shopping! 🛍️";
        }
        // Bye
        if (m.match(/bye|goodbye|see you|later|cya|ok thanks/)) {
            return "Goodbye! 👋 Come back anytime. Happy shopping at THE TREND THEORY! 🛍️✨";
        }
        // Short unknown
        if (m.length < 3) {
            return "Could you please tell me more? I'm here to help with sizing, orders, delivery, styling, and more! 😊";
        }
        // Default
        return "Great question! 🤔 I'm still learning, but I can help you with:\n\n📏 **Sizing & fit**\n🚚 **Delivery tracking**\n🎁 **Coupons & offers**\n🔄 **Returns & refunds**\n💳 **Payment options**\n\nFor other queries, our team will get back to you within 24 hours at {{ $siteEmail ?? 'support@thetrendtheory.com' }}.";
    }

    function tttAISend() {
        var inp = document.getElementById('tttAIInput');
        var btn = document.getElementById('tttAISendBtn');
        if (!inp) return;
        var text = inp.value.trim();
        if (!text) return;

        // Clear input immediately
        inp.value = '';
        inp.focus();

        // Add user message
        tttAIAddMsg('user', text);
        tttAIConversation.push({ role: 'user', text: text });

        // Show typing
        if (btn) btn.disabled = true;
        tttAIShowTyping();

        // Simulate AI response delay
        var delay = 900 + Math.floor(Math.random() * 600);
        clearTimeout(tttAITypingTimer);
        tttAITypingTimer = setTimeout(function() {
            tttAIHideTyping();
            var reply = tttAIGetResponse(text);
            tttAIAddMsg('bot', reply);
            tttAIConversation.push({ role: 'bot', text: reply });
            if (btn) btn.disabled = false;
        }, delay);
    }

    // ── Enter key support ──
    document.addEventListener('DOMContentLoaded', function() {
        var inp = document.getElementById('tttAIInput');
        if (inp) {
            inp.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    tttAISend();
                }
            });
        }
    });

    // ══════════════════════════════════════════════════════════════════
    // MULTI-IMAGE CLICK-ONLY NAVIGATION (< > ARROWS & DOTS)
    // ══════════════════════════════════════════════════════════════════
    (function () {
        function initProductCardSliders(container) {
            var root = container || document;
            var cards = root.querySelectorAll('.shop-card.has-multi-img:not([data-multi-img-inited])');

            cards.forEach(function (card) {
                card.setAttribute('data-multi-img-inited', 'true');
                var imagesData = card.getAttribute('data-images');
                if (!imagesData) return;

                var images = [];
                try {
                    images = JSON.parse(imagesData);
                } catch (e) {
                    return;
                }

                if (!Array.isArray(images) || images.length < 2) return;

                // Preload all product images for instant seamless slide on click
                images.forEach(function(src) {
                    if (src) {
                        var preImg = new Image();
                        preImg.src = src;
                    }
                });

                // Touch swipe support for mobile
                var touchStartX = 0;
                var touchEndX = 0;
                card.addEventListener('touchstart', function (e) {
                    touchStartX = e.changedTouches[0].screenX;
                }, { passive: true });

                card.addEventListener('touchend', function (e) {
                    touchEndX = e.changedTouches[0].screenX;
                    if (touchStartX - touchEndX > 40) {
                        // Swipe left -> next image
                        if (window.stepCardSlide) window.stepCardSlide(card.querySelector('.shop-card-nav-btn.next') || card, 1);
                    } else if (touchEndX - touchStartX > 40) {
                        // Swipe right -> prev image
                        if (window.stepCardSlide) window.stepCardSlide(card.querySelector('.shop-card-nav-btn.prev') || card, -1);
                    }
                }, { passive: true });
            });
        }

        window.stepCardSlide = function(btn, delta) {
            var card = btn ? btn.closest('.shop-card') : null;
            if (!card) return;
            var slides = card.querySelectorAll('.shop-card-slide');
            var dots = card.querySelectorAll('.shop-img-dot');
            if (slides.length < 2) return;
            var curIdx = 0;
            slides.forEach(function(s, idx) {
                if (s.classList.contains('active')) curIdx = idx;
            });
            var nextIdx = (curIdx + delta + slides.length) % slides.length;
            slides.forEach(function(s, idx) {
                s.classList.toggle('active', idx === nextIdx);
            });
            dots.forEach(function(d, idx) {
                d.classList.toggle('active', idx === nextIdx);
            });
        };

        window.jumpCardSlide = function(dot, targetIdx) {
            var card = dot ? dot.closest('.shop-card') : null;
            if (!card) return;
            var slides = card.querySelectorAll('.shop-card-slide');
            var dots = card.querySelectorAll('.shop-img-dot');
            if (targetIdx < 0 || targetIdx >= slides.length) return;
            slides.forEach(function(s, idx) {
                s.classList.toggle('active', idx === targetIdx);
            });
            dots.forEach(function(d, idx) {
                d.classList.toggle('active', idx === targetIdx);
            });
        };

        // Initialize on DOM load
        document.addEventListener('DOMContentLoaded', function () {
            initProductCardSliders(document);
        });

        // MutationObserver for dynamically loaded infinite-scroll cards
        if (typeof MutationObserver !== 'undefined') {
            var observer = new MutationObserver(function (mutations) {
                initProductCardSliders(document);
            });

            document.addEventListener('DOMContentLoaded', function () {
                var grid = document.getElementById('shopProductGrid');
                if (grid) {
                    observer.observe(grid, { childList: true, subtree: true });
                }
            });
        }

        window.initProductCardSliders = initProductCardSliders;
    })();
    </script>

</body>
</html>

