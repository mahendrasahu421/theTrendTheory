@php
    // --- Dynamic Settings ---
    $siteName = \App\Models\SiteSetting::get('site_name', 'THE TREND THEORY');
    $siteTagline = \App\Models\SiteSetting::get('site_tagline', "It's Not Just a Trend, It's a Theory.");
    $siteAddress = \App\Models\SiteSetting::get('address', 'India');
    $sitePhone = \App\Models\SiteSetting::get('phone', '+91 7800789705');
    $siteEmail = \App\Models\SiteSetting::get('email', 'support@thetrendtheory.com');
    $whatsappNumber = \App\Models\SiteSetting::get('whatsapp_number', '+91 7800789705');
    $freeShippingAbove = \App\Models\SiteSetting::get('shipping_free_above', '999');
    $returnDays = \App\Models\SiteSetting::get('return_days', '7');
    $instagramUrl = \App\Models\SiteSetting::get('instagram_url', 'https://instagram.com/thetrendtheory');
    $facebookUrl = \App\Models\SiteSetting::get('facebook_url', 'https://facebook.com/thetrendtheory');
    $twitterUrl = \App\Models\SiteSetting::get('twitter_url', 'https://twitter.com/thetrendtheory');
    $youtubeUrl = \App\Models\SiteSetting::get('youtube_url', 'https://youtube.com/@thetrendtheory');

    // Clean whatsapp number for link
    $cleanWhatsapp = preg_replace('/[^0-9]/', '', $whatsappNumber);

    // --- Dynamic Categories ---
    $navCats = \App\Models\Category::navCategories();
    $footerMainCategories = $navCats->isNotEmpty()
        ? $navCats
        : \App\Models\Category::active()->whereNull('parent_id')->ordered()->limit(6)->get();

    $footerAllCategories = \App\Models\Category::active()
        ->whereHas('products', fn($q) => $q->where('is_active', true))
        ->with('parent')
        ->withCount(['products' => fn($q) => $q->where('is_active', true)])
        ->orderByDesc('products_count')
        ->orderBy('sort_order')
        ->limit(24)
        ->get();

    $popularCategories = $footerAllCategories->take(12);

    // --- Dynamic Product Highlights ---
    $hasProducts = \App\Models\Product::active()->exists();
    $hasNewArrivals = \App\Models\Product::active()->where('is_new', true)->exists();
    $hasSaleProducts = \App\Models\Product::active()
        ->where(function ($q) {
            $q->where('is_on_sale', true)->orWhereRaw('original_price > price');
        })->exists();
    $hasBestSellers = \App\Models\Product::active()
        ->where(function ($q) {
            $q->where('total_sold', '>', 0)->orWhere('is_featured', true);
        })->exists();

    $trendingLinks = collect([
        $hasNewArrivals ? ['label' => 'New Arrivals', 'url' => route('shop.new-arrivals'), 'title' => 'Shop latest streetwear & fashion drops'] : null,
        $hasBestSellers ? ['label' => 'Bestsellers', 'url' => route('collections.show', 'best-sellers'), 'title' => 'Shop best-selling streetwear products'] : null,
        $hasSaleProducts ? ['label' => 'Sale & Offers', 'url' => route('collections.show', 'sale'), 'title' => 'Shop discount fashion offers'] : null,
        $hasProducts ? ['label' => 'All Products', 'url' => route('shop.index'), 'title' => 'Browse full fashion catalog online'] : null,
    ])->filter()->values();

    // --- Dynamic CMS Pages ---
    $allPages = \Illuminate\Support\Facades\Schema::hasTable('cms_pages')
        ? \App\Models\Page::where('is_active', true)->orderBy('title')->get()
        : collect();

    // Group pages logically for SEO and User Navigation
    $helpPages = $allPages->filter(function($p) {
        return in_array($p->slug, ['faq', 'faqs', 'track-order', 'shipping-policy', 'return-policy', 'returns-refunds', 'size-guide', 'contact-us', 'help-center']);
    });

    $legalPages = $allPages->filter(function($p) {
        return in_array($p->slug, ['privacy-policy', 'terms-of-use', 'terms-and-conditions', 'disclaimer']);
    });

    $companyPages = $allPages->filter(function($p) use ($helpPages, $legalPages) {
        return !$helpPages->contains('id', $p->id) && !$legalPages->contains('id', $p->id);
    });

    // --- Dynamic FAQs for AEO (Answer Engine Optimization) ---
    $faqsList = collect();
    if (\Illuminate\Support\Facades\Schema::hasTable('faqs')) {
        $faqsList = \App\Models\Faq::where('is_active', true)->orderBy('sort_order')->limit(5)->get();
    }

    if ($faqsList->isEmpty()) {
        $faqsList = collect([
            (object)[
                'question' => "What is {$siteName} and what makes your clothing unique?",
                'answer' => "{$siteName} is a premium modern fashion and streetwear label in India. We engineer heavyweight oversized tees, luxury hoodies, tailored shirts, and urban essentials with high-grade combed cotton, modern drapes, and enduring craftsmanship."
            ],
            (object)[
                'question' => "How long does delivery take and is shipping free?",
                'answer' => "We offer Free Express Shipping across India on orders above ₹{$freeShippingAbove}. Orders are processed within 24–48 hours and typically delivered within 3–5 business days."
            ],
            (object)[
                'question' => "What is {$siteName}'s Return & Exchange Policy?",
                'answer' => "We provide an easy {$returnDays}-day return and exchange policy. Sizing or quality issues can be submitted directly from your profile account for instant pickup."
            ],
            (object)[
                'question' => "Which payment modes are accepted and are they secure?",
                'answer' => "We support 100% secure payments via UPI (Google Pay, PhonePe, Paytm), Credit & Debit Cards (Visa, Mastercard, RuPay), NetBanking, and Cash on Delivery (COD) with 256-bit SSL encryption."
            ],
            (object)[
                'question' => "How do I choose the right fit and size?",
                'answer' => "Detailed size charts are available on every product page. For our oversized collection, stick to your standard size for a relaxed drop or size down for a classic regular fit."
            ],
        ]);
    }

    // Category grouping for SEO deep links
    $categoryGroups = $footerMainCategories->map(function ($parent) use ($footerAllCategories) {
        $children = $footerAllCategories
            ->filter(fn($c) => (int)$c->parent_id === (int)$parent->id)
            ->take(6)
            ->values();

        if ($children->isEmpty() && $footerAllCategories->contains('id', $parent->id)) {
            $children = collect([$parent]);
        }

        return [
            'name' => $parent->name,
            'slug' => $parent->slug,
            'items' => $children,
        ];
    })->filter(fn($g) => $g['items']->isNotEmpty())->values();

    // Prepare JSON-LD Schema
    $faqSchemaItems = [];
    foreach ($faqsList as $faqItem) {
        $faqSchemaItems[] = [
            '@type' => 'Question',
            'name' => $faqItem->question,
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => strip_tags($faqItem->answer),
            ],
        ];
    }

    $footerSchema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                '@id' => url('/') . '#organization',
                'name' => $siteName,
                'url' => url('/'),
                'logo' => asset('TheTrendTheory.jpg'),
                'description' => $siteTagline,
                'email' => $siteEmail,
                'telephone' => $sitePhone,
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressCountry' => 'IN',
                    'addressLocality' => $siteAddress,
                ],
                'sameAs' => array_values(array_filter([
                    $instagramUrl,
                    $facebookUrl,
                    $twitterUrl,
                    $youtubeUrl,
                ])),
            ],
            [
                '@type' => 'WebSite',
                '@id' => url('/') . '#website',
                'url' => url('/'),
                'name' => $siteName,
                'description' => $siteTagline,
                'publisher' => [
                    '@id' => url('/') . '#organization',
                ],
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => route('shop.search') . '?q={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ],
            [
                '@type' => 'FAQPage',
                '@id' => url('/') . '#faq',
                'mainEntity' => $faqSchemaItems,
            ],
        ],
    ];
@endphp

{{-- =========================================================================
     AEO & SEO JSON-LD STRUCTURED DATA (Schema.org)
========================================================================= --}}
<script type="application/ld+json">
{!! json_encode($footerSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
</script>

{{-- =========================================================================
     INLINE SELF-CONTAINED LUXURY STYLES (Zero CSS Cache Issues)
========================================================================= --}}
<style>
/* Reset & Scope */
.ttt-footer-wrap {
    background-color: #050d1a !important;
    color: #94a3b8 !important;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
    position: relative !important;
    width: 100% !important;
    margin-top: 60px !important;
    border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
    box-sizing: border-box !important;
    line-height: 1.5 !important;
}

.ttt-footer-wrap *, .ttt-footer-wrap *::before, .ttt-footer-wrap *::after {
    box-sizing: border-box !important;
}

.ttt-footer-wrap a {
    text-decoration: none !important;
    transition: color 0.2s ease, transform 0.2s ease !important;
}

/* 1. Perks Bar */
.ttt-perks-bar {
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    padding: 24px 0;
    background: rgba(255, 255, 255, 0.02);
}

.ttt-perks-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.ttt-perk-item {
    display: flex;
    align-items: center;
    gap: 14px;
}

.ttt-perk-item i {
    font-size: 1.5rem;
    color: #e2e8f0;
    line-height: 1;
}

.ttt-perk-text strong {
    display: block;
    font-size: 0.86rem;
    font-weight: 700;
    color: #ffffff;
    letter-spacing: 0.3px;
    margin-bottom: 2px;
}

.ttt-perk-text span {
    display: block;
    font-size: 0.75rem;
    color: #64748b;
}

/* 2. Main Footer Body */
.ttt-footer-body {
    padding: 55px 0 35px;
}

.ttt-footer-grid {
    display: grid;
    grid-template-columns: 1.6fr 1fr 1fr 1fr 1.3fr;
    gap: 36px;
    margin-bottom: 40px;
}

/* Brand Section */
.ttt-brand-title {
    font-family: 'Cinzel', Georgia, serif !important;
    font-size: 1.45rem;
    font-weight: 800;
    letter-spacing: 2px;
    color: #ffffff !important;
    display: inline-block;
    margin-bottom: 6px;
    text-transform: uppercase;
}

.ttt-brand-tagline {
    font-size: 0.78rem;
    color: #cbd5e1;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 14px;
}

.ttt-brand-bio {
    font-size: 0.82rem;
    color: #64748b;
    line-height: 1.65;
    margin-bottom: 20px;
    max-width: 320px;
}

.ttt-social-row {
    display: flex;
    gap: 10px;
}

.ttt-social-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #cbd5e1 !important;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
}

/* Nav Columns */
.ttt-col-title {
    font-family: 'Cinzel', Georgia, serif !important;
    font-size: 0.88rem;
    font-weight: 700;
    letter-spacing: 1.5px;
    color: #ffffff !important;
    text-transform: uppercase;
    margin-bottom: 20px;
    position: relative;
    padding-bottom: 6px;
}

.ttt-col-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 24px;
    height: 1.5px;
    background: #64748b;
}

.ttt-nav-list {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
    display: flex;
    flex-direction: column;
    gap: 11px;
}

.ttt-nav-list li {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
}

.ttt-nav-list li a {
    color: #8c9cb3 !important;
    font-size: 0.84rem !important;
    display: inline-block;
}

/* Newsletter Column */
.ttt-newsletter-desc {
    font-size: 0.8rem;
    color: #64748b;
    line-height: 1.55;
    margin-bottom: 14px;
}

.ttt-input-box {
    display: flex;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 6px;
    overflow: hidden;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.ttt-input-box:focus-within {
    border-color: #cbd5e1;
    box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.08);
}

.ttt-input-box input {
    flex: 1;
    background: transparent;
    border: none;
    padding: 11px 14px;
    font-size: 0.82rem;
    color: #ffffff;
    outline: none;
}

.ttt-input-box input::placeholder {
    color: #475569;
}

.ttt-input-box button {
    background: #ffffff;
    color: #050d1a;
    border: none;
    font-size: 0.78rem;
    font-weight: 800;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    padding: 0 18px;
    cursor: pointer;
    transition: background 0.2s ease;
}

.ttt-contact-support {
    margin-top: 16px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    font-size: 0.78rem;
    color: #64748b;
}

.ttt-contact-support a {
    color: #8c9cb3 !important;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* 3. AEO Knowledge Accordion */
.ttt-aeo-box {
    margin-top: 10px;
    padding-top: 24px;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
}

.ttt-accordion {
    background: rgba(255, 255, 255, 0.015);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 8px;
    overflow: hidden;
}

.ttt-accordion-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 13px 20px;
    font-size: 0.82rem;
    font-weight: 600;
    color: #cbd5e1;
    cursor: pointer;
    list-style: none !important;
    user-select: none;
    transition: background 0.2s ease;
}

.ttt-accordion-summary::-webkit-details-marker {
    display: none !important;
}

.ttt-summary-left {
    display: flex;
    align-items: center;
    gap: 8px;
}

.ttt-summary-plus {
    font-size: 1.15rem;
    color: #64748b;
    font-weight: 400;
    transition: transform 0.25s ease;
}

.ttt-accordion[open] .ttt-summary-plus {
    transform: rotate(45deg);
}

.ttt-accordion-body {
    padding: 0 20px 20px;
}

.ttt-qas-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-top: 10px;
}

.ttt-qa-card {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.04);
    border-radius: 6px;
    padding: 14px 16px;
}

.ttt-qa-q {
    font-size: 0.82rem;
    font-weight: 700;
    color: #f1f5f9;
    margin-bottom: 6px;
}

.ttt-qa-a {
    font-size: 0.76rem;
    color: #64748b;
    line-height: 1.55;
}

/* 4. SEO Keyword Tags */
.ttt-seo-strip {
    display: flex;
    align-items: baseline;
    gap: 12px;
    padding: 22px 0 0;
    margin-top: 22px;
    border-top: 1px solid rgba(255, 255, 255, 0.04);
    flex-wrap: wrap;
}

.ttt-seo-label {
    font-size: 0.76rem;
    font-weight: 700;
    color: #cbd5e1;
    white-space: nowrap;
}

.ttt-seo-links {
    display: flex;
    flex-wrap: wrap;
    gap: 6px 14px;
}

.ttt-seo-links a {
    font-size: 0.75rem !important;
    color: #64748b !important;
}

/* 5. Minimal Sub-Bar */
.ttt-sub-bar {
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    padding: 20px 0;
    background: rgba(0, 0, 0, 0.25);
}

.ttt-sub-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.ttt-sub-copyright {
    font-size: 0.76rem;
    color: #64748b;
    margin: 0;
}

.ttt-sub-copyright strong {
    color: #cbd5e1;
}

.ttt-payments-strip {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.ttt-pay-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 6px;
    padding: 4px 9px;
    height: 26px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    transition: all 0.2s ease;
    cursor: default;
}

.ttt-top-link {
    font-size: 0.76rem !important;
    color: #8c9cb3 !important;
    cursor: pointer;
}

/* Responsive Breakpoints */
@media (max-width: 1024px) {
    .ttt-footer-grid {
        grid-template-columns: 1.4fr 1fr 1fr 1fr;
    }
    .ttt-col-newsletter {
        grid-column: span 4;
        margin-top: 10px;
    }
    .ttt-qas-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .ttt-perks-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }
    .ttt-footer-grid {
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }
    .ttt-col-brand, .ttt-col-newsletter {
        grid-column: span 2;
    }
    .ttt-sub-flex {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
}

@media (max-width: 480px) {
    .ttt-perks-grid {
        grid-template-columns: 1fr;
    }
    .ttt-footer-grid {
        grid-template-columns: 1fr;
        gap: 26px;
    }
    .ttt-col-brand, .ttt-col-newsletter {
        grid-column: span 1;
    }
}
</style>

{{-- =========================================================================
     LUXURY CLEAN DYNAMIC FOOTER HTML
========================================================================= --}}
<footer class="ttt-footer-wrap" itemscope itemtype="https://schema.org/WPFooter" aria-label="Site Footer">
    
    {{-- 1. ELEGANT VALUE & TRUST BAR --}}
    <div class="ttt-perks-bar">
        <div class="container">
            <div class="ttt-perks-grid">
                <div class="ttt-perk-item">
                    <i class="bi bi-box-seam"></i>
                    <div class="ttt-perk-text">
                        <strong>Free Shipping</strong>
                        <span>On orders above ₹{{ $freeShippingAbove }}</span>
                    </div>
                </div>

                <div class="ttt-perk-item">
                    <i class="bi bi-arrow-left-right"></i>
                    <div class="ttt-perk-text">
                        <strong>{{ $returnDays }} Days Returns</strong>
                        <span>Hassle-free exchanges</span>
                    </div>
                </div>

                <div class="ttt-perk-item">
                    <i class="bi bi-patch-check"></i>
                    <div class="ttt-perk-text">
                        <strong>100% Genuine</strong>
                        <span>Crafted with tested GSM</span>
                    </div>
                </div>

                <div class="ttt-perk-item">
                    <i class="bi bi-shield-lock"></i>
                    <div class="ttt-perk-text">
                        <strong>Secure Checkout</strong>
                        <span>UPI, Cards & NetBanking</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. MAIN FOOTER BODY --}}
    <div class="ttt-footer-body">
        <div class="container">
            <div class="ttt-footer-grid">
                
                {{-- Brand Identity Column --}}
                <div class="ttt-col-brand">
                    <a href="{{ url('/') }}" class="ttt-brand-title d-inline-flex align-items-center gap-2" style="text-decoration:none;">
                        {{-- <img src="{{ asset('images/THE TREND THEORY-logo-white-trans.png') }}" alt="THE TREND THEORY" style="height: 36px; width: auto; object-fit: contain;"> --}}
                        <span>{{ strtoupper($siteName) }}</span>
                    </a>
                    <div class="ttt-brand-tagline">{{ $siteTagline }}</div>
                    <p class="ttt-brand-bio">
                        Fashion meets theory. We build streetwear and wardrobe staples inspired by modern youth culture and minimalist luxury aesthetics.
                    </p>

                    <div class="ttt-social-row">
                        @if($instagramUrl)
                            <a href="{{ $instagramUrl }}" target="_blank" rel="noopener" aria-label="Instagram" class="ttt-social-btn"><i class="bi bi-instagram"></i></a>
                        @endif
                        @if($facebookUrl)
                            <a href="{{ $facebookUrl }}" target="_blank" rel="noopener" aria-label="Facebook" class="ttt-social-btn"><i class="bi bi-facebook"></i></a>
                        @endif
                        @if($twitterUrl)
                            <a href="{{ $twitterUrl }}" target="_blank" rel="noopener" aria-label="Twitter" class="ttt-social-btn"><i class="bi bi-twitter-x"></i></a>
                        @endif
                        @if($youtubeUrl)
                            <a href="{{ $youtubeUrl }}" target="_blank" rel="noopener" aria-label="YouTube" class="ttt-social-btn"><i class="bi bi-youtube"></i></a>
                        @endif
                    </div>
                </div>

                {{-- Shop Column --}}
                <div class="ttt-col-nav">
                    <h4 class="ttt-col-title">Collections</h4>
                    <ul class="ttt-nav-list">
                        @foreach($footerMainCategories->take(5) as $category)
                            <li><a href="{{ route('shop.category', $category->slug) }}">{{ $category->name }}</a></li>
                        @endforeach
                        @foreach($trendingLinks as $trend)
                            <li><a href="{{ $trend['url'] }}">{{ $trend['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>

                {{-- Customer Service Column --}}
                <div class="ttt-col-nav">
                    <h4 class="ttt-col-title">Customer Care</h4>
                    <ul class="ttt-nav-list">
                        <li><a href="{{ route('cart.index') }}">Shopping Bag</a></li>
                        <li><a href="{{ route('wishlist.index') }}">Wishlist</a></li>
                        <li><a href="{{ route('order.track') }}">Track Order</a></li>
                        @if($helpPages->isNotEmpty())
                            @foreach($helpPages->take(4) as $page)
                                <li><a href="{{ route('pages.show', $page->slug) }}">{{ $page->title }}</a></li>
                            @endforeach
                        @else
                            <li><a href="{{ url('/page/shipping-policy') }}">Shipping Policy</a></li>
                            <li><a href="{{ url('/page/return-policy') }}">Returns & Refunds</a></li>
                            <li><a href="{{ url('/page/faq') }}">Help & FAQs</a></li>
                        @endif
                    </ul>
                </div>

                {{-- Brand & Legal Column --}}
                <div class="ttt-col-nav">
                    <h4 class="ttt-col-title">Company</h4>
                    <ul class="ttt-nav-list">
                        @if($companyPages->isNotEmpty())
                            @foreach($companyPages->take(3) as $page)
                                <li><a href="{{ route('pages.show', $page->slug) }}">{{ $page->title }}</a></li>
                            @endforeach
                        @else
                            <li><a href="{{ url('/page/about-us') }}">Our Story</a></li>
                        @endif
                        @if($legalPages->isNotEmpty())
                            @foreach($legalPages->take(3) as $page)
                                <li><a href="{{ route('pages.show', $page->slug) }}">{{ $page->title }}</a></li>
                            @endforeach
                        @else
                            <li><a href="{{ url('/page/privacy-policy') }}">Privacy Policy</a></li>
                            <li><a href="{{ url('/page/terms-of-use') }}">Terms of Service</a></li>
                        @endif
                        <li><a href="{{ route('blogs.index') }}">Journal &amp; Blogs</a></li>
                        <li><a href="{{ route('news.index') }}">News &amp; Press</a></li>
                        <li><a href="{{ url('/sitemap.xml') }}">Sitemap</a></li>
                    </ul>
                </div>

                {{-- Newsletter Column --}}
                <div class="ttt-col-newsletter">
                    <h4 class="ttt-col-title">Join THE TREND THEORY</h4>
                    <p class="ttt-newsletter-desc">Get exclusive access to private drops, secret discounts, and style lookbooks.</p>
                    
                    <form action="{{ route('newsletter.subscribe') }}" method="POST">
                        @csrf
                        <div class="ttt-input-box">
                            <input type="email" name="email" placeholder="Your email address" required autocomplete="email" aria-label="Email">
                            <button type="submit" aria-label="Join newsletter">Join</button>
                        </div>
                    </form>

                    <div class="ttt-contact-support">
                        <span><i class="bi bi-envelope"></i> {{ $siteEmail }}</span>
                        <a href="javascript:void(0)" onclick="tttAIOpen()" style="cursor:pointer;">
                            <i class="bi bi-robot"></i> AI Assistant - THE TREND THEORY
                        </a>
                    </div>
                </div>

            </div>

            {{-- 3. AEO (ANSWER ENGINE OPTIMIZATION) KNOWLEDGE GUIDE --}}
            <div class="ttt-aeo-box">
                <details class="ttt-accordion">
                    <summary class="ttt-accordion-summary">
                        <div class="ttt-summary-left">
                            <i class="bi bi-info-circle"></i>
                            <span>Quick Answers & Shopping Guide for {{ $siteName }}</span>
                        </div>
                        <span class="ttt-summary-plus">+</span>
                    </summary>
                    <div class="ttt-accordion-body">
                        <div class="ttt-qas-grid">
                            @foreach($faqsList as $faq)
                                <div class="ttt-qa-card">
                                    <div class="ttt-qa-q">{{ $faq->question }}</div>
                                    <div class="ttt-qa-a">{!! nl2br(e($faq->answer)) !!}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </details>
            </div>

            {{-- 4. SEO DEEP SEARCH & CATEGORY MATRIX --}}
            @if($popularCategories->isNotEmpty() || $categoryGroups->isNotEmpty())
            <div class="ttt-seo-strip">
                <div class="ttt-seo-label">Popular Searches:</div>
                <div class="ttt-seo-links">
                    @foreach($popularCategories->take(8) as $cat)
                        <a href="{{ route('shop.category', $cat->slug) }}" title="Shop {{ $cat->name }}">{{ $cat->name }}</a>
                    @endforeach
                    <a href="{{ route('shop.index') }}?category=oversized-tshirts">Oversized Tees</a>
                    <a href="{{ route('shop.index') }}?category=hoodies">Streetwear Hoodies</a>
                    <a href="{{ route('shop.index') }}?category=graphic-tees">Graphic Print Tees</a>
                    <a href="{{ route('shop.index') }}?category=casual-shirts">Casual Shirts</a>
                    <a href="{{ route('collections.show', 'sale') }}">Sale Offers</a>
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- 5. MINIMAL SUB-BAR --}}
    <div class="ttt-sub-bar">
        <div class="container">
            <div class="ttt-sub-flex">
                <div class="ttt-sub-copyright">
                    © {{ date('Y') }} <strong>{{ strtoupper($siteName) }}</strong>. All rights reserved. 
                    <span style="margin-left: 6px; color: #475569;">India 🇮🇳</span>
                </div>

                <div class="ttt-payments-strip">
                    {{-- UPI --}}
                    <div class="ttt-pay-badge" title="UPI - Unified Payments Interface">
                        <svg viewBox="0 0 48 24" style="height: 13px; width: auto; vertical-align: middle;">
                            <path fill="#097939" d="M12.5 3.5l-4.2 8.5 4.2 8.5h4.8l-4.2-8.5 4.2-8.5z"/>
                            <path fill="#ed7524" d="M19.5 3.5l-4.2 8.5 4.2 8.5h4.8l-4.2-8.5 4.2-8.5z"/>
                            <text x="25" y="16" font-family="'Arial', sans-serif" font-size="11" font-weight="900" fill="#ffffff" letter-spacing="0.5">UPI</text>
                        </svg>
                    </div>

                    {{-- Google Pay --}}
                    <div class="ttt-pay-badge" title="Google Pay">
                        <svg viewBox="0 0 48 48" style="width: 14px; height: 14px; vertical-align: middle;">
                            <path fill="#4285F4" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                            <path fill="#34A853" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                            <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                            <path fill="#EA4335" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                        </svg>
                        <span style="font-weight: 800; font-size: 10px; color: #ffffff; letter-spacing: 0.2px;">GPay</span>
                    </div>

                    {{-- PhonePe --}}
                    <div class="ttt-pay-badge" title="PhonePe">
                        <svg viewBox="0 0 24 24" style="width: 13px; height: 13px; fill: #a855f7; vertical-align: middle;">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.6 15.5l-3.2-4.5v4.5H8.8V6.5h3.6c2.4 0 4.1 1.5 4.1 3.7 0 1.6-.9 2.8-2.2 3.3l3.6 4h-4.3zm0-8.2c0-.9-.7-1.6-1.7-1.6h-1.5v3.2h1.5c1 0 1.7-.7 1.7-1.6z"/>
                        </svg>
                        <span style="font-weight: 800; font-size: 10px; color: #d8b4fe; letter-spacing: 0.2px;">PhonePe</span>
                    </div>

                    {{-- Paytm --}}
                    <div class="ttt-pay-badge" title="Paytm">
                        <span style="color: #00b9f5; font-weight: 900; font-size: 11px; letter-spacing: -0.5px;">Pay</span><span style="color: #ffffff; font-weight: 900; font-size: 11px; letter-spacing: -0.5px;">tm</span>
                    </div>

                    {{-- Visa --}}
                    <div class="ttt-pay-badge" title="Visa">
                        <svg viewBox="0 0 36 12" style="height: 11px; width: auto; vertical-align: middle;">
                            <path fill="#2563eb" d="M14.5 11.5L12 0.5H9.6L6.5 8.2 5.3 1.8C5.2 1 4.5 0.5 3.8 0.5H0.1L0 0.9C0.8 1.1 1.7 1.4 2.3 1.7 2.6 1.9 2.8 2.2 2.9 2.5L5 11.5H7.7L11.8 11.5 14.5 11.5z"/>
                            <path fill="#f59e0b" d="M2.3 1.7C1.7 1.4 0.8 1.1 0 0.9L0.1 0.5H3.8C4.5 0.5 5.2 1 5.3 1.8L6.5 8.2 4.2 2.3C4 2 3.8 1.7 3.4 1.7H2.3z"/>
                            <path fill="#2563eb" d="M19 0.5H16.8L15 11.5H17.2L19 0.5zM26.2 3.8C26.2 2.4 24.3 2.3 22.8 2.3 21.4 2.3 20.3 2.6 19.8 2.8L20.2 4.6C20.7 4.4 21.6 4.1 22.6 4.1 23.5 4.1 24 4.5 24 5 24 6.6 20.9 6.2 20.9 8.7 20.9 10.4 22.8 11.7 25 11.7 26.2 11.7 27 11.4 27.5 11.1L27.1 9.3C26.6 9.5 25.8 9.8 24.8 9.8 23.8 9.8 23.2 9.3 23.2 8.7 23.2 7 26.2 6.8 26.2 3.8zM33.8 0.5H31.6C30.9 0.5 30.4 0.8 30.1 1.5L26 11.5H28.4L29 9.8H32.5L32.8 11.5H35L33.8 0.5zM29.6 7.9L30.9 4.2C30.9 4.2 31.4 2.8 31.5 2.4L32.1 7.9H29.6z"/>
                        </svg>
                    </div>

                    {{-- Mastercard --}}
                    <div class="ttt-pay-badge" title="Mastercard">
                        <svg viewBox="0 0 24 16" style="height: 13px; width: auto; vertical-align: middle;">
                            <circle cx="7" cy="8" r="6" fill="#ef4444"/>
                            <circle cx="15" cy="8" r="6" fill="#f59e0b"/>
                            <path d="M11 3.2a6 6 0 0 0 0 9.6 6 6 0 0 0 0-9.6z" fill="#f97316"/>
                        </svg>
                    </div>

                    {{-- RuPay --}}
                    <div class="ttt-pay-badge" title="RuPay">
                        <span style="color: #06b6d4; font-weight: 900; font-size: 11px; letter-spacing: -0.3px;">Ru</span><span style="color: #f97316; font-weight: 900; font-size: 11px; letter-spacing: -0.3px;">Pay</span>
                    </div>

                    {{-- NetBanking --}}
                    <div class="ttt-pay-badge" title="Net Banking">
                        <i class="bi bi-bank2" style="color: #94a3b8; font-size: 11px;"></i>
                        <span style="font-weight: 700; font-size: 9.5px; color: #cbd5e1;">NetBanking</span>
                    </div>

                    {{-- COD --}}
                    <div class="ttt-pay-badge" title="Cash on Delivery">
                        <i class="bi bi-cash-stack" style="color: #22c55e; font-size: 11px;"></i>
                        <span style="font-weight: 800; font-size: 9.5px; color: #ffffff;">COD</span>
                    </div>
                </div>

                <div>
                    <a href="#mainNav" onclick="window.scrollTo({top:0, behavior:'smooth'}); return false;" class="ttt-top-link">
                        Back to top ↑
                    </a>
                </div>
            </div>
        </div>
    </div>

</footer>
