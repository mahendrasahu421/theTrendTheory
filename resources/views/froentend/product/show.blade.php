{{-- resources/views/froentend/product/show.blade.php --}}
{{-- FIXED: color-wise images, getPrimaryImageUrl accessor, proper data --}}
@extends('froentend.layouts.app')
@section('title', $product->meta_title ?? trim($product->name . (isset($selectedColor) && $selectedColor ? ' - ' . $selectedColor : '')).' | The Trend Theory')

@push('seo')
<meta name="description" content="{{ $product->meta_description ?? $product->short_description }}">
<meta property="og:title"   content="{{ $product->name }} | The Trend Theory">
<meta property="og:image"   content="{{ $product->main_image }}">
<meta property="og:url"     content="{{ url()->current() }}">
<link rel="canonical"       href="{{ $product->canonical_url ?? url()->current() }}">
@endpush

@push('styles')
<link href="{{ asset('frontend/product-show.min.css') }}" rel="stylesheet">
<link href="{{ asset('frontend/checkout-pop.css') }}" rel="stylesheet">
<style>
.pd-description-content{font-size:13px;color:#555;line-height:1.8}
.pd-description-content p,.pd-description-content div,.pd-description-content ul,.pd-description-content ol,.pd-description-content blockquote{margin:0 0 10px}
.pd-description-content h2{font-size:18px;line-height:1.35;color:#1a1a1a;margin:0 0 10px}
.pd-description-content h3{font-size:15px;line-height:1.4;color:#1a1a1a;margin:0 0 8px}
.pd-description-content ul,.pd-description-content ol{padding-left:22px}
.pd-description-content blockquote{border-left:3px solid #00285a;padding-left:12px;color:#475569}
.gokwik-pop{position:fixed;inset:0;z-index:11000;display:none;align-items:center;justify-content:center;padding:18px}
.gokwik-pop.is-open{display:flex}
.gokwik-pop__shade{position:absolute;inset:0;background:rgba(15,23,42,.48);animation:gokwikFade .2s ease both}
.gokwik-pop__card{position:relative;width:min(430px,100%);background:#fff;border-radius:16px;box-shadow:0 24px 70px rgba(15,23,42,.28);padding:24px;animation:gokwikRise .3s cubic-bezier(.2,.9,.2,1.08) both;overflow:hidden}
.gokwik-pop__card:before{content:"";position:absolute;left:0;right:0;top:0;height:5px;background:linear-gradient(90deg,#0f172a,#00b894,#2563eb)}
.gokwik-pop__close{position:absolute;right:14px;top:14px;width:34px;height:34px;border:0;border-radius:50%;background:#f1f5f9;color:#0f172a;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:.18s}
.gokwik-pop__close:hover{background:#e2e8f0;transform:rotate(90deg)}
.gokwik-pop__brand{display:grid;gap:3px;margin-bottom:14px}
.gokwik-pop__brand span{font-size:28px;font-weight:900;color:#0f172a;letter-spacing:-.3px}
.gokwik-pop__brand small{font-size:13px;color:#64748b}
.gokwik-pop__secure{display:flex;align-items:center;gap:8px;background:#ecfdf5;color:#047857;border:1px solid #bbf7d0;border-radius:12px;padding:10px 12px;font-size:13px;font-weight:700;margin-bottom:16px}
.gokwik-pop__summary{display:grid;grid-template-columns:1fr auto;gap:12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:14px;margin-bottom:16px}
.gokwik-pop__summary div{display:grid;gap:3px}
.gokwik-pop__summary span{font-size:12px;color:#64748b;text-transform:uppercase;font-weight:800}
.gokwik-pop__summary strong{font-size:20px;color:#00285a}
.gokwik-pop__methods{display:grid;gap:10px;margin-bottom:16px}
.gokwik-pop__methods label{display:flex;align-items:center;gap:10px;border:1.5px solid #e2e8f0;border-radius:12px;padding:13px;background:#fff;cursor:pointer;transition:border-color .18s,box-shadow .18s,transform .18s}
.gokwik-pop__methods label:hover,.gokwik-pop__methods label.active{border-color:#00285a;box-shadow:0 10px 28px rgba(0,40,90,.1);transform:translateY(-1px)}
.gokwik-pop__methods input{accent-color:#00285a}
.gokwik-pop__methods span{display:flex;align-items:center;gap:8px;font-size:14px;font-weight:800;color:#0f172a;flex:1}
.gokwik-pop__methods b{font-size:10px;color:#fff;background:#00a66a;border-radius:999px;padding:4px 8px;text-transform:uppercase}
.gokwik-pay-btn{position:relative;width:100%;height:52px;border:0;border-radius:999px;background:#0f172a;color:#fff;font-size:15px;font-weight:900;cursor:pointer;overflow:hidden;transition:.18s}
.gokwik-pay-btn:before{content:"";position:absolute;inset:0;background:linear-gradient(110deg,transparent,rgba(255,255,255,.22),transparent);transform:translateX(-110%);transition:.5s}
.gokwik-pay-btn:hover{background:#00a66a;box-shadow:0 14px 34px rgba(0,166,106,.26);transform:translateY(-1px)}
.gokwik-pay-btn:hover:before{transform:translateX(110%)}
.gokwik-pay-btn.is-loading{pointer-events:none;color:rgba(255,255,255,.72)}
.gokwik-pay-btn.is-loading:after{content:"";position:absolute;right:20px;top:50%;width:17px;height:17px;margin-top:-8px;border:2px solid rgba(255,255,255,.35);border-top-color:#fff;border-radius:50%;animation:gokwikSpin .75s linear infinite}
.gokwik-pop__msg{min-height:18px;margin-top:12px;text-align:center;color:#64748b;font-size:12px}
.gokwik-pop__msg.success{color:#047857}
@keyframes gokwikFade{from{opacity:0}to{opacity:1}}
@keyframes gokwikRise{from{opacity:0;transform:translateY(18px) scale(.96)}to{opacity:1;transform:translateY(0) scale(1)}}
@keyframes gokwikSpin{to{transform:rotate(360deg)}}
</style>
@endpush

@section('main')

@php
    $variants   = $product->variants ?? collect();
    $sizes      = $variants->pluck('size')->filter()->unique()->values();
    $selectedColor = $selectedColor ?? null;

    // Colors from variants — get unique colors with hex
    $colorVariants = $variants->filter(fn($v) => $v->color)
        ->unique('color')
        ->map(fn($v) => [
            'color'     => $v->color,
            'slug'      => Str::slug($v->color),
            'hex'       => $v->color_hex,
            'stock'     => $variants->where('color',$v->color)->sum('stock'),
        ])
        ->values();

    $hasDiscount = $product->original_price && $product->original_price > $product->price;
    $discPct     = $hasDiscount ? (int)round((($product->original_price - $product->price) / $product->original_price) * 100) : 0;
    $inStock     = $product->has_variants ? $variants->sum('stock') > 0 : $product->stock > 0;

    // All product images (media table)
    $firstColor = $selectedColor
        ? $colorVariants->firstWhere('color', $selectedColor)
        : $colorVariants->first();
    $selectedColor = $firstColor['color'] ?? $selectedColor;

    $productImages = $product->productImages ?? collect();
    $mediaImages = $product->media ?? collect();
    $fallbackImages = $productImages->isNotEmpty()
        ? $productImages->map(fn($i) => ['url' => $i->url, 'is_primary' => $i->is_primary])
        : $mediaImages->map(fn($i) => ['url' => $i->url, 'is_primary' => $i->is_primary]);

    // Color → images mapping from media table
    // Images are stored in media table with alt_text containing color name (if uploaded per color)
    // Otherwise all images show for all colors
    $colorImages = [];
    foreach ($colorVariants as $cv) {
        $colorSpecific = $productImages->filter(function ($img) use ($cv) {
            return strcasecmp($img->color->name ?? '', $cv['color']) === 0
                || stripos($img->alt_text ?? '', $cv['color']) !== false;
        });

        if ($colorSpecific->isEmpty()) {
            $colorSpecific = $mediaImages->filter(fn($img) => stripos($img->alt_text ?? '', $cv['color']) !== false);
        }

        $colorImages[$cv['color']] = $colorSpecific->isNotEmpty()
            ? $colorSpecific->map(fn($i) => ['url'=>$i->url, 'is_primary'=>$i->is_primary])->values()
            : $fallbackImages->values();
    }
    if (empty($colorImages)) {
        $colorImages['all'] = $fallbackImages->values();
    }

    $selectedImages = ($selectedColor && isset($colorImages[$selectedColor]))
        ? collect($colorImages[$selectedColor])
        : collect($fallbackImages);
    $images = $selectedImages->isNotEmpty() ? $selectedImages : collect([['url' => $product->main_image, 'is_primary' => true]]);
    $mainImg = $images->first()['url'] ?? $product->main_image;
    $displayName = trim($product->name . ($selectedColor ? ' - ' . $selectedColor : ''));
@endphp

<div class="pd-wrap">
    {{-- Breadcrumb --}}
    <div style="font-size:12px;color:#7a8fa6;margin-bottom:20px;display:flex;align-items:center;gap:6px;flex-wrap:wrap">
        <a href="{{ route('home') }}" style="color:#7a8fa6;text-decoration:none">Home</a>
        <span>›</span>
        @if($product->category)
            @if($product->category->parent)
                <a href="{{ route('shop.category', $product->category->parent->slug) }}" style="color:#7a8fa6;text-decoration:none">{{ $product->category->parent->name }}</a>
                <span>›</span>
            @endif
            <a href="{{ route('shop.category', $product->category->slug) }}" style="color:#7a8fa6;text-decoration:none">{{ $product->category->name }}</a>
            <span>›</span>
        @endif
        <span style="color:#333;font-weight:500">{{ Str::limit($displayName,30) }}</span>
    </div>

    <div class="pd-grid">
        {{-- ── LEFT: IMAGES ── --}}
        <div>
            <div class="pd-main-img" onclick="openProductGallery()">
                <img src="{{ $mainImg }}" alt="{{ $displayName }}" id="mainImg"
                     onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                <button type="button" class="pd-open-gallery" onclick="event.stopPropagation();openProductGallery()">
                    View all photos
                </button>
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
            @if($images->count() > 1)
            <div class="pd-thumbs" id="thumbsContainer">
                @foreach($images as $i => $img)
                    <div class="pd-thumb {{ $i===0 ? 'active':'' }}"
                         onclick='switchImg(@json($img['url']), this, {{ $i }})'
                         ondblclick="openProductGallery({{ $i }})">
                        <img src="{{ $img['url'] }}" alt="" onerror="this.style.display='none'">
                    </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ── RIGHT: INFO ── --}}
        <div class="pd-info">
            <div class="pd-brand">THE TREND THEORY</div>
            <h1 class="pd-name">{{ $displayName }}</h1>

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
                        <span class="price-disc">{{ $discPct }}% OFF</span>
                    @endif
                </div>
                @if($hasDiscount)
                    <div class="saving">You save ₹{{ number_format($product->original_price - $product->price) }}!</div>
                @endif
                <div class="tax-note">Inclusive of all taxes · Free shipping above ₹999</div>
            </div>

            <div class="pd-divider"></div>

            {{-- ── COLOR SECTION (like screenshot) ── --}}
            @if(isset($linkedColorProducts) && $linkedColorProducts->count() > 1)
            <div>
                <div class="section-label">COLOR : <span style="font-weight:400;text-transform:none">{{ $product->color_name ?? '' }}</span></div>
                <div class="color-grid">
                    @foreach($linkedColorProducts as $linkedProduct)
                        <a class="color-item {{ (int) $linkedProduct->id === (int) $product->id ? 'active':'' }}"
                           href="{{ route('product.show', $linkedProduct->slug) }}"
                           title="{{ $linkedProduct->color_name ?: $linkedProduct->name }}">
                            <div class="color-img-wrap">
                                <img src="{{ $linkedProduct->card_image }}" alt="{{ $linkedProduct->color_name ?: $linkedProduct->name }}"
                                     onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                            </div>
                            <span class="color-name">{{ $linkedProduct->color_name ?: Str::limit($linkedProduct->name, 14) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            @elseif($colorVariants->count() > 0)
            <div>
                <div class="section-label">COLOR : <span id="selectedColorLabel" style="font-weight:400;text-transform:none">{{ $selectedColor ?? '' }}</span></div>
                <div class="color-grid" id="colorGrid">
                    @foreach($colorVariants as $ci => $cv)
                        @php
                            $colorImg = collect($colorImages[$cv['color']] ?? [])->first();
                            $imgUrl = $colorImg['url'] ?? asset('images/placeholder-product.jpg');
                        @endphp
                        <a class="color-item {{ $cv['color'] === $selectedColor ? 'active':'' }}"
                             href="{{ route('product.show.color', ['slug' => $product->slug, 'colorSlug' => $cv['slug']]) }}"
                             data-color="{{ $cv['color'] }}"
                             data-hex="{{ $cv['hex'] ?? '#ccc' }}"
                             data-url="{{ route('product.show.color', ['slug' => $product->slug, 'colorSlug' => $cv['slug']]) }}"
                             onclick="event.preventDefault(); selectColor(this.dataset.color, this.dataset.hex, this)">
                            <div class="color-img-wrap">
                                <img src="{{ $imgUrl }}" alt="{{ $cv['color'] }}"
                                     onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                            </div>
                            <span class="color-name">{{ $cv['color'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── SIZE SECTION ── --}}
            @if($sizes->count() > 0)
            <div>
                <div class="size-row">
                    <div class="section-label">SIZE : <span id="selSize" style="font-weight:400;text-transform:none;color:#ff3f6c">Select</span></div>
                    <span class="size-guide" onclick="document.getElementById('sizeGuide').scrollIntoView({behavior:'smooth'})">Size Guide</span>
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
            <div id="stockStatus" style="font-size:13px;font-weight:600">
                @if(!$inStock)
                    <span style="color:#ef4444"><i class="bi bi-x-circle"></i> Out of Stock</span>
                @else
                    <span style="color:#22c55e"><i class="bi bi-check-circle"></i> In Stock</span>
                @endif
            </div>

            {{-- Buttons --}}
            <div>
                <div class="pd-purchase-row">
                    <div class="pd-qty-stepper" aria-label="Quantity">
                        <button type="button" onclick="changeQty(-1)" aria-label="Decrease quantity">-</button>
                        <span id="qtyVal">1</span>
                        <button type="button" onclick="changeQty(1)" aria-label="Increase quantity">+</button>
                    </div>
                    <button class="btn-atc" id="atcBtn" onclick="addToCart()" {{ !$inStock ? 'disabled':'' }}>
                        {{ !$inStock ? 'OUT OF STOCK' : ($sizes->count() > 0 ? 'SELECT SIZE' : 'ADD TO CART') }}
                    </button>
                </div>
                <button class="btn-buy" id="buyBtn" onclick="buyNow()" {{ !$inStock ? 'disabled':'' }}>
                    BUY IT NOW
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
                @php
                    $rawDescription = (string) $product->description;
                    if ($rawDescription === strip_tags($rawDescription)) {
                        $descriptionHtml = nl2br(e($rawDescription));
                    } else {
                        $descriptionHtml = preg_replace('#<(script|style|iframe|object|embed)\b[^>]*>.*?</\1>#is', '', $rawDescription);
                        $descriptionHtml = strip_tags($descriptionHtml, '<p><div><br><strong><b><em><i><u><h2><h3><ul><ol><li><blockquote>');
                        $descriptionHtml = preg_replace('/\s(?:on\w+|style|class|id)=("[^"]*"|\'[^\']*\'|[^\s>]*)/i', '', $descriptionHtml);
                        $descriptionHtml = preg_replace('/javascript\s*:/i', '', $descriptionHtml);
                    }
                @endphp
                <div>
                    <div style="font-weight:700;font-size:13px;color:#1a1a1a;margin-bottom:8px">PRODUCT DETAILS</div>
                    <div class="pd-description-content">{!! $descriptionHtml !!}</div>
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
    <section class="pd-related" aria-label="You may also like">
        <h3 class="pd-related-title">YOU MAY ALSO LIKE</h3>
        <div class="pd-related-grid">
            @foreach($relatedProducts as $rp)
                @php
                    $rpImg = $rp->card_image ?? $rp->main_image ?? asset('images/placeholder-product.jpg');
                    $rpHasDiscount = $rp->original_price && $rp->original_price > $rp->price;
                    $rpDiscount = $rpHasDiscount ? (int) round((($rp->original_price - $rp->price) / $rp->original_price) * 100) : 0;
                @endphp
                <article class="pd-related-card">
                    <a href="{{ route('product.show', $rp->slug) }}" class="pd-related-img">
                        <img src="{{ $rpImg }}" alt="{{ $rp->name }}" loading="lazy"
                             onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                        @if($rpHasDiscount)
                            <span class="pd-related-badge">-{{ $rpDiscount }}%</span>
                        @elseif($rp->is_new)
                            <span class="pd-related-badge">NEW</span>
                        @endif
                    </a>
                    <div class="pd-related-info">
                        <a href="{{ route('product.show', $rp->slug) }}" class="pd-related-name">{{ $rp->name }}</a>
                        <div class="pd-related-price">
                            <span>Rs. {{ number_format($rp->price) }}</span>
                            @if($rpHasDiscount)
                                <del>Rs. {{ number_format($rp->original_price) }}</del>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
    @endif
</div>

<div class="pd-lightbox" id="productLightbox" aria-hidden="true">
    <button type="button" class="pd-lightbox-close" onclick="closeProductGallery()" aria-label="Close gallery">
        <i class="bi bi-x-lg"></i>
    </button>
    <button type="button" class="pd-lightbox-nav pd-lightbox-prev" onclick="moveProductGallery(-1)" aria-label="Previous image">
        <i class="bi bi-chevron-left"></i>
    </button>
    <figure class="pd-lightbox-stage">
        <img src="" alt="{{ $displayName }}" id="productLightboxImg">
        <figcaption id="productLightboxCount"></figcaption>
    </figure>
    <button type="button" class="pd-lightbox-nav pd-lightbox-next" onclick="moveProductGallery(1)" aria-label="Next image">
        <i class="bi bi-chevron-right"></i>
    </button>
    <div class="pd-lightbox-thumbs" id="productLightboxThumbs"></div>
</div>

<div class="toast" id="toast"></div>

<div class="gokwik-pop" id="gokwikPop" aria-hidden="true">
    <div class="gokwik-pop__shade" onclick="closeGokwikPopup()"></div>
    <aside class="gokwik-pop__card" role="dialog" aria-modal="true" aria-label="GoKwik payment">
        <button type="button" class="gokwik-pop__close" onclick="closeGokwikPopup()" aria-label="Close">
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="gokwik-pop__brand">
            <span>GoKwik</span>
            <small>Fast & secure checkout</small>
        </div>
        <div class="gokwik-pop__secure">
            <i class="bi bi-shield-lock-fill"></i>
            100% secure payment powered by GoKwik
        </div>

        <div class="gokwik-pop__summary">
            <div>
                <span>Payable Amount</span>
                <strong id="gokwikAmount">₹0</strong>
            </div>
            <div>
                <span>Items</span>
                <strong id="gokwikItems">1</strong>
            </div>
        </div>

        <div class="gokwik-pop__methods">
            <label class="active">
                <input type="radio" name="gokwik_method" value="gokwik" checked>
                <span><i class="bi bi-lightning-charge-fill"></i> GoKwik Checkout</span>
                <b>Recommended</b>
            </label>
            <label>
                <input type="radio" name="gokwik_method" value="upi">
                <span><i class="bi bi-phone"></i> UPI via GoKwik</span>
            </label>
            <label>
                <input type="radio" name="gokwik_method" value="card">
                <span><i class="bi bi-credit-card"></i> Card / Netbanking</span>
            </label>
        </div>

        <button type="button" class="gokwik-pay-btn" id="gokwikPayBtn" onclick="startGokwikPayment()">
            Continue with GoKwik
        </button>
        <div class="gokwik-pop__msg" id="gokwikMsg">GoKwik API hook ready hai. Abhi demo mode me modal open ho raha hai.</div>
    </aside>
</div>

@php $checkoutUser = auth()->user(); @endphp
<div class="checkout-pop" id="checkoutPop" aria-hidden="true">
    <div class="checkout-pop__shade" onclick="closeCheckoutPop()"></div>
    <aside class="checkout-pop__panel cart-drawer-panel" role="dialog" aria-modal="true" aria-label="Cart">
        <div class="checkout-pop__top">
            <div class="cart-drawer-title">Your Cart (<span id="coCartCount">0</span> items)</div>
            <button type="button" class="checkout-pop__back" onclick="closeCheckoutPop()" aria-label="Close cart">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="cart-reward-code">Get 10% off with code SHARKTANK10</div>
        <div class="cart-reward">
            <div id="coRewardText">Add more to unlock rewards!</div>
            <div class="cart-reward-scale"><span>₹500.00</span><span>₹5,999.00</span><span>₹9,999.00</span></div>
            <div class="cart-reward-line"><i></i></div>
            <div class="cart-reward-labels"><span>10% Off</span><span>15% Off</span><span>20% Off</span></div>
        </div>

        <div class="checkout-pop__body">
            <section class="checkout-block">
                <div class="checkout-title">DELIVERY DETAILS</div>
                <div class="checkout-card checkout-summary-card" onclick="toggleCheckoutSection('order')" role="button" tabindex="0">
                    <div class="checkout-icon"><i class="bi bi-cart3"></i></div>
                    <div>
                        <div class="checkout-card-title">Order Summary</div>
                        <div class="checkout-save" id="coSavings">₹0 saved so far</div>
                    </div>
                    <div class="checkout-summary-price">
                        <span id="coItemCount">1 item</span>
                        <strong id="coTotalTop">₹0</strong>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                </div>
                <div class="checkout-order-items" id="coOrderItems"></div>

                <div class="checkout-card checkout-address-card">
                    <div class="checkout-icon"><i class="bi bi-geo-alt"></i></div>
                    <div class="checkout-address-copy">
                        <div class="checkout-card-title">Deliver To {{ $checkoutUser->name ?? 'Customer' }}</div>
                        <p>{{ $checkoutUser->address ?? 'Add your delivery address at checkout' }}</p>
                        <p>{{ $checkoutUser->phone ?? '' }}{{ $checkoutUser?->email ? ' | ' . $checkoutUser->email : '' }}</p>
                        <div class="checkout-ship">
                            <span>Standard Shipping</span>
                            <b id="coShipping">₹50</b>
                        </div>
                    </div>
                    <button type="button" class="checkout-change" onclick="showAddressEditor()">Change</button>
                </div>
                <div class="checkout-address-form" id="coAddressForm" hidden>
                    <div class="checkout-form-head">
                        <button type="button" onclick="showCheckoutMain()"><i class="bi bi-chevron-left"></i></button>
                        <b>Change Address</b>
                    </div>
                    <label>Pincode</label>
                    <div class="checkout-pin-row">
                        <input type="text" id="coPin" maxlength="10" placeholder="Enter pincode" value="{{ $checkoutUser->pincode ?? '' }}">
                        <button type="button" onclick="unlockAddressFields()">Check</button>
                    </div>
                    <div class="checkout-address-fields" id="coAddressFields" hidden>
                        <label>Name</label>
                        <input type="text" id="coName" value="{{ $checkoutUser->name ?? '' }}" placeholder="Full name">
                        <label>Phone</label>
                        <input type="text" id="coPhone" value="{{ $checkoutUser->phone ?? '' }}" placeholder="Mobile number">
                        <label>Address</label>
                        <textarea id="coAddress" rows="3" placeholder="House no, street, area">{{ $checkoutUser->address ?? '' }}</textarea>
                        <div class="checkout-field-grid">
                            <div>
                                <label>City</label>
                                <input type="text" id="coCity" value="{{ $checkoutUser->city ?? '' }}" placeholder="City">
                            </div>
                            <div>
                                <label>State</label>
                                <input type="text" id="coState" value="{{ $checkoutUser->state ?? '' }}" placeholder="State">
                            </div>
                        </div>
                        <button type="button" class="checkout-save-address" onclick="saveCheckoutAddress()">Save Address</button>
                    </div>
                    <div class="checkout-address-msg" id="coAddressMsg"></div>
                </div>
            </section>

            <section class="checkout-block">
                <div class="checkout-title">OFFERS & REWARDS</div>
                <div class="checkout-card checkout-offers">
                    <div class="checkout-coupon">
                        <i class="bi bi-patch-percent-fill"></i>
                        <input type="text" placeholder="Enter Coupon Code">
                    </div>
                    <div class="checkout-offer-row">
                        <span><i class="bi bi-patch-check"></i> 14 coupons available <b class="checkout-offer-badge">Specially For You</b></span>
                        <button type="button">View All Offers <i class="bi bi-chevron-right"></i></button>
                    </div>
                    <div class="checkout-loyalty">
                        <i class="bi bi-star"></i>
                        You're earning <b id="coPoints">0 loyalty points</b> on this order
                    </div>
                </div>
            </section>

            <section class="checkout-block">
                <div class="checkout-title">PAYMENT OPTIONS</div>
                <div class="checkout-pay-note">Additional 5 Discount on Prepaid Orders</div>
            </section>
        </div>

        <div class="checkout-pop__footer">
            <div class="checkout-cashback">Get 5% off + cashback</div>
            <div class="checkout-total-row" onclick="toggleTotalBreakdown()" role="button" tabindex="0">
                <div>
                    <span><i class="bi bi-receipt-cutoff"></i> Estimated Total <i class="bi bi-chevron-up" id="coTotalChevron"></i></span>
                    <small id="coDiscountLine">Best price applied</small>
                </div>
                <strong id="coTotalBottom">₹0</strong>
            </div>
            <div class="checkout-total-breakdown" id="coTotalBreakdown" hidden>
                <div class="checkout-break-head"><b>Order Summary</b><span id="coBreakSaved">₹0 saved so far</span></div>
                <div class="checkout-break-row"><span>MRP total</span><b id="coMrpTotal">₹0</b></div>
                <div class="checkout-break-row green"><span>Discount on MRP</span><b id="coMrpDiscount">₹0</b></div>
                <div class="checkout-break-row"><span>Cart Subtotal</span><b id="coCartSubtotal">₹0</b></div>
                <div class="checkout-break-row green"><span>Total discount</span><b id="coTotalDiscount">₹0</b></div>
                <div class="checkout-break-row green"><span>Prepaid Discount</span><b id="coPrepaidDiscount">₹0</b></div>
                <div class="checkout-break-row"><span>Shipping Charges</span><b id="coBreakShipping">₹0</b></div>
                <div class="checkout-break-row green"><span>Total savings</span><b id="coTotalSavings">₹0</b></div>
                <div class="checkout-break-final"><span>Estimated Total</span><b id="coBreakTotal">₹0</b></div>
            </div>
            <a href="{{ route('checkout.index') }}" class="checkout-main-btn" onclick="event.preventDefault(); openCheckoutMode();">
                <span class="checkout-main-copy">
                    <b>CHECKOUT</b>
                    <small>5% OFF ON PREPAID ORDERS</small>
                </span>
                <span class="checkout-pay-icons" aria-hidden="true">
                    <i>paytm</i>
                    <i>पे</i>
                    <i>G</i>
                </span>
            </a>
            <div class="checkout-powered">Powered by <b>The Trend Theory</b></div>
        </div>
    </aside>
</div>

@php
    $productShowConfig = [
        'productId' => $product->id,
        'hasVariants' => (bool) $product->has_variants,
        'csrf' => csrf_token(),
        'variants' => ($product->variants ?? collect())->map(fn($v) => [
            'id' => $v->id,
            'size' => $v->size,
            'color' => $v->color,
            'stock' => $v->stock,
            'price' => (float) $v->price,
            'color_hex' => $v->color_hex,
        ])->values(),
        'sizes' => $sizes->values(),
        'colorImages' => $colorImages,
        'galleryImages' => $images->values(),
        'selectedColor' => $selectedColor ?? '',
        'colorUrls' => $colorVariants->mapWithKeys(fn($cv) => [
            $cv['color'] => route('product.show.color', ['slug' => $product->slug, 'colorSlug' => $cv['slug']]),
        ]),
        'basePrice' => (float) $product->price,
        'cartAddUrl' => route('cart.add'),
        'cartUpdateBaseUrl' => url('/cart/update'),
        'checkoutUrl' => route('checkout.index'),
        'addressUpdateUrl' => route('profile.update'),
        'drawerProduct' => [
            'name' => $displayName,
            'image' => $mainImg,
            'price' => (float) $product->price,
            'original_price' => (float) ($product->original_price ?: $product->price),
        ],
        'user' => [
            'name' => $checkoutUser->name ?? '',
            'email' => $checkoutUser->email ?? '',
            'phone' => $checkoutUser->phone ?? '',
            'address' => $checkoutUser->address ?? '',
            'city' => $checkoutUser->city ?? '',
            'state' => $checkoutUser->state ?? '',
            'pincode' => $checkoutUser->pincode ?? '',
        ],
    ];
@endphp

@push('scripts')
<script>
window.TTT_PRODUCT_SHOW = @json($productShowConfig);
</script>
<script src="{{ asset('frontend/product-show.min.js') }}" defer></script>
@endpush

@endsection
