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

    <!-- ===== PRODUCTS SECTION WITH INFINITE SCROLL ===== -->
    <section class="collection-slider-section">
        <div class="container-fluid position-relative px-0">
            
            <!-- Products Container -->
            <div class="collection-container">
                <div class="row g-4" id="products-container">
                    @foreach ($products as $product)
                    <div class="col-lg-3 col-md-6 product-item">
                        <div class="collection-card">
                            <div class="collection-img">
                                @php
                                    $image = $product->images->first();
                                @endphp
                                <img src="{{ optional($image)->image_url ?? asset('default.jpg') }}"
                                     alt="{{ $product->product_name }}">
                                <div class="collection-hover-btn">
                                    <a href="{{ route('product.detail', $product->slug_name) }}">
                                        <button class="btn-hover-add">Quick View</button>
                                    </a>
                                </div>
                            </div>
                            <div class="collection-info">
                                <h3>{{ ucwords(str_replace('-', ' ', $product->product_name)) }}</h3>
                                <p class="product-price">${{ number_format($product->price, 2) }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Loading Spinner (hidden by default) -->
                <div class="text-center mt-4" id="loading-spinner" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                
                <!-- End Message (hidden by default) -->
                <div class="text-center mt-4" id="end-message" style="display: none;">
                    <p class="text-muted">No more products to load</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Hidden pagination data -->
    <input type="hidden" id="next-page-url" value="{{ $products->nextPageUrl() }}">
    <input type="hidden" id="current-page" value="{{ $products->currentPage() }}">
    <input type="hidden" id="last-page" value="{{ $products->lastPage() }}">

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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let isLoading = false;
    let nextPageUrl = document.getElementById('next-page-url').value;
    let currentPage = parseInt(document.getElementById('current-page').value);
    let lastPage = parseInt(document.getElementById('last-page').value);
    let loadingSpinner = document.getElementById('loading-spinner');
    let endMessage = document.getElementById('end-message');
    let productsContainer = document.getElementById('products-container');
    
    // Check if we have more pages
    if (currentPage >= lastPage) {
        endMessage.style.display = 'block';
    }

    function loadMoreProducts() {
        if (isLoading || !nextPageUrl || currentPage >= lastPage) return;
        
        isLoading = true;
        loadingSpinner.style.display = 'block';
        
        fetch(nextPageUrl + '&ajax=1', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.products && data.products.data.length > 0) {
                data.products.data.forEach(product => {
                    const imageUrl = product.images && product.images.length > 0 
                        ? product.images[0].image_url 
                        : '{{ asset("default.jpg") }}';
                        
                    const productHtml = `
                        <div class="col-lg-3 col-md-6 product-item">
                            <div class="collection-card">
                                <div class="collection-img">
                                    <img src="${imageUrl}" alt="${product.product_name}">
                                    <div class="collection-hover-btn">
                                        <a href="/product/${product.slug_name}">
                                            <button class="btn-hover-add">Quick View</button>
                                        </a>
                                    </div>
                                </div>
                                <div class="collection-info">
                                    <h3>${product.product_name.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</h3>
                                    <p class="product-price">$${parseFloat(product.price).toFixed(2)}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    productsContainer.insertAdjacentHTML('beforeend', productHtml);
                });
                
                // Update pagination data
                currentPage = data.products.current_page;
                nextPageUrl = data.products.next_page_url;
                lastPage = data.products.last_page;
                
                // Update hidden inputs
                document.getElementById('current-page').value = currentPage;
                document.getElementById('next-page-url').value = nextPageUrl || '';
                document.getElementById('last-page').value = lastPage;
                
                if (!nextPageUrl) {
                    endMessage.style.display = 'block';
                }
            }
            
            isLoading = false;
            loadingSpinner.style.display = 'none';
        })
        .catch(error => {
            console.error('Error:', error);
            isLoading = false;
            loadingSpinner.style.display = 'none';
        });
    }

    // Intersection Observer for infinite scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && nextPageUrl && currentPage < lastPage) {
                loadMoreProducts();
            }
        });
    }, {
        rootMargin: '200px',
        threshold: 0.1
    });

    // Add sentinel element for scroll detection
    const sentinel = document.createElement('div');
    sentinel.id = 'scroll-sentinel';
    sentinel.style.height = '10px';
    document.querySelector('.collection-container').appendChild(sentinel);
    observer.observe(sentinel);
});
</script>
@endpush