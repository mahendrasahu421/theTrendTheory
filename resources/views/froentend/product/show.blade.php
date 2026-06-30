{{-- resources/views/froentend/product/show.blade.php --}}
{{-- FIXED: color-wise images, getPrimaryImageUrl accessor, proper data --}}
@extends('froentend.layouts.app')
@section('title', $product->meta_title ?? $product->name.' | The Trend Theory')

@push('seo')
<meta name="description" content="{{ $product->meta_description ?? $product->short_description }}">
<meta property="og:title"   content="{{ $product->name }} | The Trend Theory">
<meta property="og:image"   content="{{ $product->main_image }}">
<meta property="og:url"     content="{{ url()->current() }}">
<link rel="canonical"       href="{{ $product->canonical_url ?? url()->current() }}">
@endpush

@section('main')

@php
    // FIXED: Use main_image accessor (works with both media & image column)
    $mainImg    = $product->main_image;
    $variants   = $product->variants ?? collect();
    $sizes      = $variants->pluck('size')->filter()->unique()->values();

    // Colors from variants — get unique colors with hex
    $colorVariants = $variants->filter(fn($v) => $v->color)
        ->unique('color')
        ->map(fn($v) => [
            'color_id'  => $v->color_id,
            'color'     => $v->color,
            'hex'       => $v->color_hex,
            'stock'     => $variants->where('color',$v->color)->sum('stock'),
        ])
        ->values();

    $hasDiscount = $product->original_price && $product->original_price > $product->price;
    $discPct     = $hasDiscount ? (int)round((($product->original_price - $product->price) / $product->original_price) * 100) : 0;
    $inStock     = $product->has_variants ? $variants->sum('stock') > 0 : $product->stock > 0;

    // All product images. Prefer color-wise product_images records when available.
    $productImages = $product->productImages ?? collect();
    $images = $productImages->isNotEmpty()
        ? $productImages->sortByDesc('is_primary')->filter(fn($pi) => !empty($pi->url))->values()
        : ($product->media ?? collect());
    // Selected color (first available)
    $firstColor = $colorVariants->first();

    // Color → images mapping from media table
    // Images are stored in media table with alt_text containing color name (if uploaded per color)
    // Otherwise all images show for all colors
    $colorImages = [];
    foreach ($colorVariants as $cv) {
        $colorSpecific = $productImages
            ->filter(function ($pi) use ($cv) {
                if (empty($pi->url)) {
                    return false;
                }

                if (!empty($cv['color_id']) && (int) $pi->color_id === (int) $cv['color_id']) {
                    return true;
                }

                return strtolower($pi->color?->name ?? '') === strtolower($cv['color']);
            })
            ->sortByDesc('is_primary')
            ->values();

        if ($colorSpecific->isNotEmpty()) {
            $colorImages[$cv['color']] = $colorSpecific
                ->map(fn($pi) => [
                    'url' => $pi->url,
                    'thumb' => $pi->thumb_url,
                    'is_primary' => $pi->is_primary,
                ])
                ->values();
            continue;
        }

        $variantImage = $variants->where('color', $cv['color'])->first(fn($v) => !empty($v->image));
        if ($variantImage) {
            $colorImages[$cv['color']] = collect([[
                'url' => $variantImage->image_url,
                'thumb' => $variantImage->image_url,
                'is_primary' => true,
            ]]);
            continue;
        }

        $altMatched = $images->filter(fn($img) => stripos($img->alt_text ?? '', $cv['color']) !== false);
        $colorImages[$cv['color']] = ($altMatched->isNotEmpty() ? $altMatched : $images)
            ->map(fn($i) => ['url'=>$i->url, 'thumb'=>$i->url, 'is_primary'=>$i->is_primary ?? false])
            ->values();
    }
    if (empty($colorImages)) {
        $colorImages['all'] = $images->map(fn($i) => ['url'=>$i->url, 'thumb'=>$i->url, 'is_primary'=>$i->is_primary ?? false]);
    }

    if ($firstColor && !empty($colorImages[$firstColor['color']])) {
        $firstColorImage = collect($colorImages[$firstColor['color']])->first();
        $mainImg = $firstColorImage['url'] ?? $mainImg;
    }

    $initialThumbs = $firstColor && !empty($colorImages[$firstColor['color']])
        ? collect($colorImages[$firstColor['color']])
        : $images->map(fn($i) => ['url'=>$i->url, 'thumb'=>$i->url, 'is_primary'=>$i->is_primary ?? false]);
@endphp

<style>
.pd-wrap{max-width:none;margin:0 auto;padding:0 0 60px;background:#fff}
.pd-breadcrumb{max-width:1800px;margin:0 auto;padding:12px 3.3vw;font-size:12px;color:#7a8fa6;display:flex;align-items:center;gap:6px;flex-wrap:wrap;border-top:1px solid #eee;border-bottom:1px solid #eee}
.pd-breadcrumb a{color:#7a8fa6;text-decoration:none}
.pd-grid{display:grid;grid-template-columns:minmax(0,1.52fr) minmax(390px,.9fr);gap:0;align-items:start;max-width:1800px;margin:0 auto}
/* Left images */
.pd-gallery{position:relative;background:#eee;min-height:calc(100vh - 86px)}
.pd-main-img{border-radius:0;overflow:hidden;height:calc(100vh - 86px);min-height:620px;background:#f8fafc;position:sticky;top:0;cursor:zoom-in}
.pd-main-img img{width:100%;height:100%;object-fit:cover;transition:transform .4s}
.pd-main-img:hover img{transform:scale(1.02)}
.pd-share{position:absolute;top:16px;right:16px;width:50px;height:50px;border:0;border-radius:50%;background:#fff;color:#111;box-shadow:0 3px 12px rgba(0,0,0,.18);display:flex;align-items:center;justify-content:center;font-size:20px;z-index:4;cursor:pointer}
.pd-thumbs{display:flex;gap:8px;margin-top:0;flex-wrap:wrap;padding:10px 3.3vw;background:#fff}
.pd-thumb{width:66px;height:86px;border-radius:0;overflow:hidden;cursor:pointer;border:1px solid #ddd;flex-shrink:0;transition:.15s;background:#f8fafc}
.pd-thumb.active,.pd-thumb:hover{border-color:#111;box-shadow:0 0 0 1px #111}
.pd-thumb img{width:100%;height:100%;object-fit:cover}
/* Right info */
.pd-info{display:flex;flex-direction:column;gap:18px;position:sticky;top:0;min-height:calc(100vh - 86px);padding:28px 3.2vw 40px;background:#fff}
.pd-title-row{display:flex;align-items:flex-start;justify-content:space-between;gap:16px}
.pd-brand{display:none}
.pd-name{font-family:Arial,Helvetica,sans-serif;font-size:33px;font-weight:500;color:#4a4f57;line-height:1.15;margin:0;text-transform:uppercase;letter-spacing:0}
.pd-heart{width:42px;height:42px;border:0;background:transparent;color:#111;font-size:25px;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0}
.pd-heart.wished{color:#ff3f6c}
/* Price */
.price-row{display:flex;align-items:baseline;gap:12px;flex-wrap:wrap}
.price-curr{font-size:22px;font-weight:500;color:#e32121}
.price-orig{font-size:22px;color:#c7c7c7;text-decoration:line-through}
.price-disc{background:#d9292f;color:white;padding:7px 13px;border-radius:7px;font-size:11px;font-weight:800;text-transform:uppercase}
.saving{display:none}
.tax-note{font-size:13px;color:#777;text-decoration:underline;margin-top:4px}
.guarantee{display:flex;align-items:center;gap:12px;background:#f5f5f5;color:#111;padding:14px 14px;font-size:14px;line-height:1.35}
.guarantee i{font-size:32px;color:#111}
.guarantee strong{font-weight:800;margin-right:4px}
/* Color section */
.section-label{font-size:14px;font-weight:800;text-transform:uppercase;letter-spacing:0;color:#4a4f57;margin-bottom:10px}
.section-label span{font-weight:400}
/* Color thumbnails like screenshot */
.color-grid{display:flex;gap:10px;flex-wrap:wrap}
.color-item{cursor:pointer;text-align:center;flex-shrink:0}
.color-img-wrap{width:74px;height:104px;border-radius:0;overflow:hidden;border:1px solid transparent;transition:.15s;background:#f8fafc;padding:0}
.color-item.active .color-img-wrap,.color-item:hover .color-img-wrap{border-color:#111;box-shadow:0 0 0 1px #111}
.color-img-wrap img{width:100%;height:100%;object-fit:cover}
.color-name{display:none}
/* Size */
.size-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;gap:12px}
.size-meta{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.fit-pill{display:inline-flex;align-items:center;gap:7px;border:1px solid #ddd;border-radius:6px;padding:4px 8px;font-size:13px;color:#555;background:#f7f7f7}
.size-opts{display:grid;grid-template-columns:repeat(6,minmax(48px,1fr));gap:0;max-width:360px}
.size-btn{height:60px;padding:0 14px;border:1px solid #ddd;border-radius:0;cursor:pointer;font-size:14px;font-weight:500;color:#4a4f57;background:white;transition:.15s;min-width:58px;text-align:center;margin-left:-1px}
.size-btn:hover{border-color:#111;z-index:1}
.size-btn.active{border-color:#111;background:white;color:#111;box-shadow:inset 0 0 0 1px #111;z-index:2}
.size-btn.oos{opacity:.35;cursor:not-allowed;text-decoration:line-through}
.size-guide{font-size:14px;color:#111;font-weight:500;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px;white-space:nowrap}
.variant-msg{font-size:12px;color:#7a8fa6;margin-top:6px}
/* Buttons */
.purchase-row{display:grid;grid-template-columns:110px 1fr;gap:12px;margin-top:14px}
.qty-stepper{height:58px;border:1px solid #111;border-radius:11px;display:flex;align-items:center;justify-content:space-around}
.qty-stepper button{border:0;background:transparent;color:#9ca3af;font-size:22px;cursor:pointer;width:32px;height:40px}
.qty-stepper span{font-size:18px;color:#4a4f57;min-width:20px;text-align:center}
.btn-atc{width:100%;height:58px;padding:0;background:#fff;color:#111;border:1px solid #111;border-radius:11px;font-size:17px;font-weight:800;cursor:pointer;transition:.15s;letter-spacing:0;text-transform:uppercase}
.btn-atc:hover{background:#111;color:#fff}
.btn-atc:disabled{background:#f1f5f9;color:#94a3b8;cursor:not-allowed}
.btn-atc.adding{background:#22c55e}
.btn-buy{width:100%;height:58px;padding:0;background:#111;color:white;border:none;border-radius:9px;font-size:17px;font-weight:800;cursor:pointer;transition:.15s;margin-top:14px;text-transform:uppercase}
.btn-buy:hover{background:#333}
.btn-wish-row{display:none}
.btn-wish{width:50px;height:50px;border:1.5px solid #e2e8f0;border-radius:8px;background:white;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:20px;color:#555;transition:.15s}
.btn-wish:hover,.btn-wish.wished{border-color:#ff3f6c;color:#ff3f6c;background:#fff0f3}
.pd-divider{height:1px;background:#f0f4f8}
.toast{position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(20px);background:#1a1a1a;color:white;padding:12px 24px;border-radius:30px;font-size:13px;font-weight:600;opacity:0;transition:.3s;z-index:9999;white-space:nowrap}
.toast.show{opacity:1;transform:translateX(-50%) translateY(0)}
.recommend-section{margin-top:52px}
.recommend-head{display:flex;align-items:flex-end;justify-content:space-between;gap:16px;margin-bottom:18px}
.recommend-title{font-family:'Cinzel',serif;font-size:16px;color:#00285a;letter-spacing:1.4px;margin:0;font-weight:700}
.recommend-subtitle{font-size:12px;color:#7a8fa6;margin:4px 0 0}
.recommend-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px}
.recommend-card{background:#fff;border:1px solid #eef2f6;border-radius:12px;overflow:hidden;position:relative;box-shadow:0 8px 22px rgba(0,40,90,.06);transition:transform .22s,box-shadow .22s,border-color .22s}
.recommend-card:hover{transform:translateY(-4px);box-shadow:0 18px 34px rgba(0,40,90,.12);border-color:#dbe6f2}
.recommend-img{display:block;position:relative;aspect-ratio:3/4;background:#f8fafc;overflow:hidden}
.recommend-img img{width:100%;height:100%;object-fit:cover;transition:transform .35s}
.recommend-card:hover .recommend-img img{transform:scale(1.05)}
.recommend-badge{position:absolute;top:10px;left:10px;background:#00285a;color:#fff;border-radius:999px;padding:4px 10px;font-size:10px;font-weight:700;letter-spacing:.4px;z-index:2}
.recommend-badge.sale{background:#ff3f6c}
.recommend-wish{position:absolute;top:10px;right:10px;width:34px;height:34px;border-radius:50%;border:1px solid rgba(255,255,255,.85);background:rgba(255,255,255,.92);display:flex;align-items:center;justify-content:center;color:#1a1a1a;z-index:2;transition:.18s}
.recommend-wish:hover,.recommend-wish.wished{color:#ff3f6c;border-color:#ff3f6c;background:#fff}
.recommend-info{padding:12px 13px 14px}
.recommend-name{display:block;color:#1a1a1a;text-decoration:none;font-size:13px;font-weight:700;line-height:1.35;min-height:36px;margin-bottom:8px}
.recommend-name:hover{color:#ff3f6c}
.recommend-price{display:flex;align-items:center;gap:7px;flex-wrap:wrap;font-size:13px}
.recommend-price .curr{font-weight:800;color:#00285a}
.recommend-price .orig{font-size:11px;color:#a0a9b4;text-decoration:line-through}
.recommend-empty{border:1px dashed #dbe6f2;border-radius:12px;padding:22px;color:#7a8fa6;font-size:13px;text-align:center;background:#fbfdff}
@media(max-width:992px){.pd-grid{grid-template-columns:1fr}.pd-main-img{position:relative;height:auto;min-height:0;aspect-ratio:4/5}.pd-info{position:static;min-height:0}.recommend-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}
@media(max-width:768px){.pd-info{padding:22px 16px}.pd-name{font-size:24px}.price-curr,.price-orig{font-size:19px}.purchase-row{grid-template-columns:96px 1fr}.size-opts{grid-template-columns:repeat(3,1fr);max-width:none}.color-img-wrap{width:62px;height:84px}.recommend-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.recommend-title{font-size:14px}}
@media(max-width:420px){.recommend-grid{grid-template-columns:1fr}}
</style>

<div class="pd-wrap">
    {{-- Breadcrumb --}}
    <div class="pd-breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>›</span>
        @if($product->category)
            @if($product->category->parent)
                <a href="{{ route('shop.category', $product->category->parent->slug) }}">{{ $product->category->parent->name }}</a>
                <span>›</span>
            @endif
            <a href="{{ route('shop.category', $product->category->slug) }}">{{ $product->category->name }}</a>
            <span>›</span>
        @endif
        <span style="color:#333;font-weight:500">{{ Str::limit($product->name, 42) }}</span>
    </div>

    <div class="pd-grid">
        {{-- ── LEFT: IMAGES ── --}}
        <div class="pd-gallery">
            <div class="pd-main-img">
                <button type="button" class="pd-share" onclick="shareProduct()" aria-label="Share product">
                    <i class="bi bi-share-fill"></i>
                </button>
                <img src="{{ $mainImg }}" alt="{{ $product->name }}" id="mainImg"
                     onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                @if($discPct >= 70)
                    <div style="position:absolute;top:12px;left:12px;background:#ff3f6c;color:white;padding:6px 14px;border-radius:20px;font-size:14px;font-weight:700;z-index:2">🔥 {{ $discPct }}% OFF</div>
                @elseif($hasDiscount)
                    <div style="position:absolute;top:12px;left:12px;background:#22c55e;color:white;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;z-index:2">-{{ $discPct }}%</div>
                @endif
                @if($product->is_new && !$hasDiscount)
                    <div style="position:absolute;top:12px;left:12px;background:#00285a;color:white;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;z-index:2">NEW</div>
                @endif
            </div>

            {{-- Thumbnails --}}
            @if($initialThumbs->count() > 1)
            <div class="pd-thumbs" id="thumbsContainer">
                @foreach($initialThumbs as $i => $img)
                    <div class="pd-thumb {{ $i===0 ? 'active':'' }}"
                         onclick="switchImg('{{ $img['url'] }}', this)">
                        <img src="{{ $img['thumb'] ?? $img['url'] }}" alt="" onerror="this.style.display='none'">
                    </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ── RIGHT: INFO ── --}}
        <div class="pd-info">
            <div class="pd-brand">THE TREND THEORY</div>
            <div class="pd-title-row">
                <h1 class="pd-name">{{ $product->name }}</h1>
                <button class="pd-heart {{ in_array($product->id, session('wishlist',[])) ? 'wished':'' }}"
                        onclick="toggleWishlist({{ $product->id }}, this)" aria-label="Add to wishlist">
                    <i class="bi {{ in_array($product->id, session('wishlist',[])) ? 'bi-heart-fill':'bi-heart' }}"></i>
                </button>
            </div>

            {{-- Rating --}}
            @if(isset($product->reviews) && $product->reviews->count() > 0)
                @php $avg = round($product->reviews->avg('rating'),1); @endphp
                <div style="display:flex;align-items:center;gap:8px;font-size:13px">
                    <span style="color:#ffd700">@for($i=1;$i<=5;$i++){{ $i<=$avg?'★':'☆' }}@endfor</span>
                    <span style="color:#555;font-weight:600">{{ $avg }}</span>
                    <span style="color:#7a8fa6">({{ $product->reviews->count() }})</span>
                </div>
            @endif

            {{-- Price --}}
            <div>
                <div class="price-row">
                    <span class="price-curr" id="displayPrice">₹{{ number_format($product->price) }}</span>
                    @if($hasDiscount)
                        <span class="price-orig">₹{{ number_format($product->original_price) }}</span>
                        <span class="price-disc">SAVE {{ $discPct }}%</span>
                    @endif
                </div>
                @if($hasDiscount)
                    <div class="saving">You save ₹{{ number_format($product->original_price - $product->price) }}!</div>
                @endif
                <div class="tax-note">Shipping calculated at checkout.</div>
            </div>

            <div class="guarantee">
                <i class="bi bi-shield-check"></i>
                <div><strong>90-DAY GUARANTEE</strong> Stitch & fabric defects? We cover it</div>
            </div>

            {{-- ── COLOR SECTION (like screenshot) ── --}}
            @if($colorVariants->count() > 0)
            <div>
                <div class="section-label">COLOR : <span id="selectedColorLabel" style="font-weight:400;text-transform:none">{{ $firstColor['color'] ?? '' }}</span></div>
                <div class="color-grid" id="colorGrid">
                    @foreach($colorVariants as $ci => $cv)
                        @php
                            $mappedColorImages = collect($colorImages[$cv['color']] ?? []);
                            $firstMappedImage = $mappedColorImages->first();
                            $imgUrl = $firstMappedImage['thumb'] ?? ($firstMappedImage['url'] ?? asset('images/placeholder-product.jpg'));
                        @endphp
                        <div class="color-item {{ $ci===0 ? 'active':'' }}"
                             data-color="{{ $cv['color'] }}"
                             data-hex="{{ $cv['hex'] ?? '#ccc' }}"
                             onclick="selectColor('{{ $cv['color'] }}', '{{ $cv['hex'] ?? '#ccc' }}', this)">
                            <div class="color-img-wrap">
                                <img src="{{ $imgUrl }}" alt="{{ $cv['color'] }}"
                                     onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                            </div>
                            <span class="color-name">{{ $cv['color'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── SIZE SECTION ── --}}
            @if($sizes->count() > 0)
            <div>
                <div class="size-row">
                    <div class="size-meta">
                        <div class="section-label" style="margin:0">SIZE: <span id="selSize">Select</span></div>
                        <span class="fit-pill"><i class="bi bi-rulers"></i> WAIST 26 - 28 INCHES</span>
                        <span class="fit-pill">LENGTH 40 INCHES</span>
                    </div>
                    <span class="size-guide" onclick="document.getElementById('sizeGuide').scrollIntoView({behavior:'smooth'})">
                        <i class="bi bi-pencil"></i> Sizing guide
                    </span>
                </div>
                <div class="size-opts" id="sizeOpts">
                    @foreach($sizes as $size)
                        @php
                            $selColor    = $colorVariants->first()['color'] ?? null;
                            $sv          = $variants->where('size',$size)->when($selColor, fn($c)=>$c->where('color',$selColor))->first()
                                        ?? $variants->where('size',$size)->first();
                            $sStock      = $sv ? $sv->stock : 0;
                        @endphp
                        <button type="button"
                                class="size-btn {{ $sStock<=0 ? 'oos':'' }}"
                                data-size="{{ $size }}"
                                data-stock="{{ $sStock }}"
                                {{ $sStock<=0 ? 'disabled':'' }}
                                onclick="selectSize(this)">
                            {{ $size }}
                        </button>
                    @endforeach
                </div>
                <div class="variant-msg" id="variantMsg">Please select a size</div>
            </div>
            @endif

            {{-- Stock --}}
            <div id="stockStatus" style="font-size:13px;font-weight:600;display:none">
                @if(!$inStock)
                    <span style="color:#ef4444"><i class="bi bi-x-circle"></i> Out of Stock</span>
                @else
                    <span style="color:#22c55e"><i class="bi bi-check-circle"></i> In Stock</span>
                @endif
            </div>

            {{-- Buttons --}}
            <div>
                <div class="purchase-row">
                    <div class="qty-stepper" aria-label="Quantity">
                        <button type="button" onclick="changeQty(-1)" aria-label="Decrease quantity">-</button>
                        <span id="qtyVal">1</span>
                        <button type="button" onclick="changeQty(1)" aria-label="Increase quantity">+</button>
                    </div>
                    <button class="btn-atc" id="atcBtn" onclick="addToCart()" {{ !$inStock ? 'disabled':'' }}>
                        {{ !$inStock ? 'OUT OF STOCK' : ($sizes->count() > 0 ? 'SELECT SIZE' : 'ADD TO CART') }}
                    </button>
                </div>
                <button class="btn-buy" id="buyBtn" onclick="buyNow()" {{ !$inStock ? 'disabled':'' }}>
                    BUY NOW
                </button>
                <div class="btn-wish-row">
                    <button class="btn-wish {{ in_array($product->id, session('wishlist',[])) ? 'wished':'' }}"
                            onclick="toggleWishlist({{ $product->id }}, this)">
                        <i class="bi {{ in_array($product->id, session('wishlist',[])) ? 'bi-heart-fill':'bi-heart' }}"></i>
                    </button>
                    <span style="font-size:12px;color:#7a8fa6">Add to Wishlist</span>
                </div>
            </div>

            {{-- Short desc --}}
            @if($product->short_description)
                <div style="font-size:13px;color:#555;background:#f8fafc;padding:12px 16px;border-radius:10px;border-left:3px solid #00285a">
                    {{ $product->short_description }}
                </div>
            @endif

            <div class="pd-divider"></div>

            {{-- Description --}}
            @if($product->description)
                <div>
                    <div style="font-weight:700;font-size:13px;color:#1a1a1a;margin-bottom:8px">PRODUCT DETAILS</div>
                    <div style="font-size:13px;color:#555;line-height:1.8;white-space:pre-line">{{ $product->description }}</div>
                </div>
            @endif

            {{-- USPs --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                @foreach(['Free shipping above ₹999','Easy 15-day returns','Cash on delivery','100% authentic'] as $u)
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#555">
                        <i class="bi bi-check-circle-fill" style="color:#22c55e;font-size:14px;flex-shrink:0"></i>{{ $u }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Size Guide --}}
    <div id="sizeGuide" style="margin-top:48px;background:white;border:1px solid #eef2f6;border-radius:14px;padding:24px">
        <h3 style="font-family:'Cinzel',serif;font-size:14px;color:#00285a;margin-bottom:16px;letter-spacing:1px">SIZE GUIDE</h3>
        <div style="overflow-x:auto">
            <table style="width:100%;border-collapse:collapse;font-size:13px">
                <thead><tr>
                    @foreach(['Size','Chest (in)','Waist (in)','Hip (in)','Length (in)'] as $h)
                        <th style="padding:10px 14px;background:#f8fafc;border-bottom:1px solid #eef2f6;text-align:left;font-weight:700;color:#7a8fa6;font-size:11px;text-transform:uppercase">{{ $h }}</th>
                    @endforeach
                </tr></thead>
                <tbody>
                @foreach(['XS'=>[32,26,34,25],'S'=>[34,28,36,26],'M'=>[36,30,38,27],'L'=>[38,32,40,28],'XL'=>[40,34,42,29],'XXL'=>[42,36,44,30]] as $s=>$m)
                    <tr style="border-bottom:1px solid #f0f4f8">
                        <td style="padding:10px 14px;font-weight:700;color:#00285a">{{ $s }}</td>
                        @foreach($m as $v)<td style="padding:10px 14px;color:#555">{{ $v }}</td>@endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Reviews --}}
    @if(isset($product->reviews) && $product->reviews->count() > 0)
    <div style="margin-top:48px">
        <h3 style="font-family:'Cinzel',serif;font-size:14px;color:#00285a;margin-bottom:20px;letter-spacing:1px">CUSTOMER REVIEWS ({{ $product->reviews->count() }})</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px">
            @foreach($product->reviews->where('is_active',true)->take(6) as $r)
            <div style="background:white;border:1px solid #eef2f6;border-radius:12px;padding:16px">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
                    <div style="width:36px;height:36px;border-radius:50%;background:#e8f0fb;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:#00285a">{{ strtoupper(substr($r->reviewer_name,0,2)) }}</div>
                    <div>
                        <div style="font-weight:600;font-size:13px">{{ $r->reviewer_name }}</div>
                        <div style="color:#ffd700">@for($i=1;$i<=5;$i++){{ $i<=$r->rating?'★':'☆' }}@endfor</div>
                    </div>
                    @if($r->is_verified)<span style="margin-left:auto;background:#e8f5e9;color:#2e7d32;padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700">✓ Verified</span>@endif
                </div>
                <p style="font-size:13px;color:#555;line-height:1.6;margin:0">{{ $r->comment }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Related --}}
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
    <section class="recommend-section" aria-label="You may also like">
        <div class="recommend-head">
            <div>
                <h3 class="recommend-title">YOU MAY ALSO LIKE</h3>
                <p class="recommend-subtitle">More picks from the same category</p>
            </div>
        </div>
        <div class="recommend-grid">
            @foreach($relatedProducts as $rp)
                @php
                    $rpHasDiscount = $rp->original_price && $rp->original_price > $rp->price;
                    $rpDiscount = $rpHasDiscount ? (int) round((($rp->original_price - $rp->price) / $rp->original_price) * 100) : 0;
                @endphp
                <article class="recommend-card">
                    <a href="{{ route('product.show', $rp->slug) }}" class="recommend-img">
                        <img src="{{ $rp->image_url ?? asset('images/placeholder-product.jpg') }}"
                             alt="{{ $rp->name }}" loading="lazy"
                             onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                        @if($rpHasDiscount)
                            <span class="recommend-badge sale">-{{ $rpDiscount }}%</span>
                        @elseif($rp->is_new)
                            <span class="recommend-badge">NEW</span>
                        @endif
                    </a>
                    <button class="recommend-wish {{ in_array($rp->id, session('wishlist',[])) ? 'wished':'' }}"
                            onclick="toggleWishlist({{ $rp->id }}, this)" aria-label="Add to wishlist">
                        <i class="bi {{ in_array($rp->id, session('wishlist',[])) ? 'bi-heart-fill':'bi-heart' }}"></i>
                    </button>
                    <div class="recommend-info">
                        <a href="{{ route('product.show', $rp->slug) }}" class="recommend-name">{{ $rp->name }}</a>
                        <div class="recommend-price">
                            <span class="curr">Rs. {{ number_format($rp->price) }}</span>
                            @if($rpHasDiscount)
                                <span class="orig">Rs. {{ number_format($rp->original_price) }}</span>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Recently Viewed --}}
    @if(isset($recentlyViewedProducts) && $recentlyViewedProducts->count() > 0)
    <section class="recommend-section" aria-label="Recently viewed products">
        <div class="recommend-head">
            <div>
                <h3 class="recommend-title">RECENTLY VIEWED</h3>
                <p class="recommend-subtitle">Products you checked earlier</p>
            </div>
        </div>
        <div class="recommend-grid">
            @foreach($recentlyViewedProducts->take(4) as $rv)
                @php
                    $rvHasDiscount = $rv->original_price && $rv->original_price > $rv->price;
                    $rvDiscount = $rvHasDiscount ? (int) round((($rv->original_price - $rv->price) / $rv->original_price) * 100) : 0;
                @endphp
                <article class="recommend-card">
                    <a href="{{ route('product.show', $rv->slug) }}" class="recommend-img">
                        <img src="{{ $rv->image_url ?? asset('images/placeholder-product.jpg') }}"
                             alt="{{ $rv->name }}" loading="lazy"
                             onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                        @if($rvHasDiscount)
                            <span class="recommend-badge sale">-{{ $rvDiscount }}%</span>
                        @elseif($rv->is_new)
                            <span class="recommend-badge">NEW</span>
                        @endif
                    </a>
                    <button class="recommend-wish {{ in_array($rv->id, session('wishlist',[])) ? 'wished':'' }}"
                            onclick="toggleWishlist({{ $rv->id }}, this)" aria-label="Add to wishlist">
                        <i class="bi {{ in_array($rv->id, session('wishlist',[])) ? 'bi-heart-fill':'bi-heart' }}"></i>
                    </button>
                    <div class="recommend-info">
                        <a href="{{ route('product.show', $rv->slug) }}" class="recommend-name">{{ $rv->name }}</a>
                        <div class="recommend-price">
                            <span class="curr">Rs. {{ number_format($rv->price) }}</span>
                            @if($rvHasDiscount)
                                <span class="orig">Rs. {{ number_format($rv->original_price) }}</span>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
    @endif
</div>

<div class="toast" id="toast"></div>

@push('scripts')
<script>
var PRODUCT_ID  = {{ $product->id }};
var HAS_VARIANTS= {{ $product->has_variants ? 'true':'false' }};
var CSRF        = document.querySelector('meta[name="csrf-token"]').content;
var VARIANTS    = {!! json_encode(($product->variants ?? collect())->map(fn($v)=>['id'=>$v->id,'size'=>$v->size,'color'=>$v->color,'stock'=>$v->stock,'price'=>(float)$v->price,'color_hex'=>$v->color_hex])->values()) !!};

// Color images mapping from PHP
var COLOR_IMAGES = {!! json_encode($colorImages) !!};

var selectedSize  = null;
var selectedColor = '{{ $firstColor["color"] ?? "" }}';
var selectedQty = 1;

// Switch main image
function switchImg(url, thumb) {
    document.getElementById('mainImg').src = url;
    document.querySelectorAll('.pd-thumb').forEach(function(t){ t.classList.remove('active'); });
    if (thumb) thumb.classList.add('active');
}

// ── Color selection ──────────────────────────────────
function selectColor(color, hex, el) {
    selectedColor = color;
    selectedSize  = null;

    // Update UI
    document.querySelectorAll('.color-item').forEach(function(c){ c.classList.remove('active'); });
    el.classList.add('active');
    document.getElementById('selectedColorLabel').textContent = color;

    // Change images based on color
    var imgs = COLOR_IMAGES[color] || COLOR_IMAGES['all'] || [];
    if (imgs.length > 0) {
        document.getElementById('mainImg').src = imgs[0].url;

        // Update thumbnails
        var thumbs = document.getElementById('thumbsContainer');
        if (thumbs) {
            thumbs.innerHTML = imgs.map(function(img, i) {
                return '<div class="pd-thumb '+(i===0?'active':'')+'" onclick="switchImg(\''+img.url+'\', this)">'
                    + '<img src="'+(img.thumb || img.url)+'" alt="" onerror="this.style.display=\'none\'">'
                    + '</div>';
            }).join('');
        }
    }

    // Update size availability for this color
    updateSizesForColor(color);

    // Reset size selection
    document.getElementById('selSize').textContent = 'Select';
    document.getElementById('selSize').style.color = '#ff3f6c';
    document.querySelectorAll('.size-btn').forEach(function(b){ b.classList.remove('active'); });

    // Update ATC button
    var atcBtn = document.getElementById('atcBtn');
    atcBtn.textContent = 'SELECT SIZE';
    atcBtn.style.background = '';
    atcBtn.disabled = false;
}

// Update size buttons based on color stock
function updateSizesForColor(color) {
    document.querySelectorAll('.size-btn').forEach(function(btn) {
        var size = btn.getAttribute('data-size');
        var colorVariants = VARIANTS.filter(function(v){ return v.color === color && v.size === size; });
        var hasStock = colorVariants.some(function(v){ return v.stock > 0; });
        btn.classList.toggle('oos', !hasStock);
        btn.disabled = !hasStock;
    });
}

// ── Size selection ──────────────────────────────────
function selectSize(btn) {
    if (btn.classList.contains('oos') || btn.disabled) return;

    document.querySelectorAll('.size-btn').forEach(function(b){ b.classList.remove('active'); });
    btn.classList.add('active');
    selectedSize = btn.getAttribute('data-size');

    document.getElementById('selSize').textContent = selectedSize;
    document.getElementById('selSize').style.color = '#1a1a1a';

    // Check stock for this size+color
    var v = VARIANTS.find(function(x){ return x.size === selectedSize && (!selectedColor || x.color === selectedColor); });
    var msg = document.getElementById('variantMsg');
    if (v) {
        if (v.stock <= 0) msg.textContent = '❌ Out of stock';
        else if (v.stock <= 5) msg.textContent = '⚠️ Only '+v.stock+' left!';
        else msg.textContent = '✓ In stock ('+v.stock+' available)';

        // Update price if variant has different price
        if (v.price && v.price !== {{ (float)$product->price }}) {
            document.getElementById('displayPrice').textContent = '₹' + v.price.toLocaleString('en-IN');
        }
    }

    // Update ATC button
    var atcBtn = document.getElementById('atcBtn');
    atcBtn.textContent = 'ADD TO CART';
    atcBtn.style.background = '';
    atcBtn.disabled = false;
}

// ── Add to Cart ──────────────────────────────────────
function changeQty(delta) {
    selectedQty = Math.max(1, Math.min(10, selectedQty + delta));
    document.getElementById('qtyVal').textContent = selectedQty;
}

function shareProduct() {
    var data = { title: document.title, text: '{{ addslashes($product->name) }}', url: window.location.href };
    if (navigator.share) {
        navigator.share(data).catch(function(){});
        return;
    }
    navigator.clipboard.writeText(window.location.href).then(function() {
        showToast('Product link copied');
    });
}

function addToCart() {
    var btn = document.getElementById('atcBtn');
    var size = selectedSize;

    if (HAS_VARIANTS && document.querySelectorAll('.size-btn').length > 0 && !size) {
        btn.textContent = 'SELECT SIZE FIRST!';
        btn.style.background = '#f97316';
        setTimeout(function(){ btn.style.background=''; btn.textContent='SELECT SIZE'; }, 1500);
        return;
    }

    btn.textContent = 'Adding...';
    btn.disabled = true;

    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json' },
        body: JSON.stringify({ product_id: PRODUCT_ID, size: size, color: selectedColor||null, quantity: selectedQty })
    })
    .then(function(r){ return r.json(); })
    .then(function(res) {
        if (res.success) {
            btn.textContent = '✓ Added!';
            btn.classList.add('adding');
            showToast('Added to cart!');
            var badge = document.getElementById('cart-count');
            if (badge && (res.cart_count || res.count)) badge.textContent = res.cart_count || res.count;
            setTimeout(function(){ btn.textContent='ADD TO CART'; btn.classList.remove('adding'); btn.disabled=false; }, 2000);
        } else if (res.redirect) {
            window.location.href = res.redirect;
        } else {
            btn.textContent = res.message || 'Error'; btn.disabled=false;
        }
    })
    .catch(function(){ btn.textContent='ADD TO CART'; btn.disabled=false; });
}

function buyNow() {
    addToCart();
    setTimeout(function(){ window.location.href = '{{ route("checkout.index") }}'; }, 600);
}

function showToast(msg) {
    var t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(function(){ t.classList.remove('show'); }, 2500);
}

// Init
if (selectedColor) updateSizesForColor(selectedColor);
</script>
@endpush

@endsection
