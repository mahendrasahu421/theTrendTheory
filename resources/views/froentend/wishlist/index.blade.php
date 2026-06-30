{{-- resources/views/froentend/wishlist/index.blade.php --}}
@extends('froentend.layouts.app')

@push('seo')
    <title>My Wishlist | The Trend Theory</title>
    <meta name="robots" content="noindex, nofollow">
@endpush

@section('main')
<style>
.wishlist-wrap  { max-width:1100px; margin:40px auto; padding:0 20px 60px; }
.wishlist-title { font-family:'Cinzel',serif; font-size:1.6rem; font-weight:700; color:#00285a; letter-spacing:2px; margin-bottom:28px; }
.wishlist-grid  { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }
.wish-card      { background:white; border-radius:18px; border:1px solid #eef2f6; overflow:hidden; position:relative; transition:all .25s; }
.wish-card:hover { transform:translateY(-5px); box-shadow:0 16px 32px rgba(0,40,90,.1); }
.wish-card-img  { height:280px; overflow:hidden; position:relative; }
.wish-card-img img { width:100%; height:100%; object-fit:cover; transition:transform .4s; }
.wish-card:hover .wish-card-img img { transform:scale(1.06); }
.wish-remove    { position:absolute; top:10px; right:10px; width:32px; height:32px; background:white; border:none; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 2px 8px rgba(0,0,0,.1); font-size:16px; color:#aaa; transition:.2s; z-index:2; }
.wish-remove:hover { color:#ff3f6c; transform:scale(1.1); }
.wish-card-info { padding:12px 14px 16px; }
.wish-card-name { font-size:13px; font-weight:700; color:#00285a; margin-bottom:6px; text-decoration:none; display:block; }
.wish-card-name:hover { color:#ff3f6c; }
.wish-card-price { font-size:15px; font-weight:800; color:#c44536; margin-bottom:10px; }
.wish-add-btn   { width:100%; padding:9px; background:#00285a; color:white; border:none; border-radius:30px; font-size:12px; font-weight:700; letter-spacing:.5px; cursor:pointer; transition:.2s; }
.wish-add-btn:hover { background:#ff3f6c; }

/* Empty */
.wishlist-empty { text-align:center; padding:60px 20px; background:white; border-radius:16px; border:1px solid #eef2f6; }
.wishlist-empty i { font-size:56px; color:#d9dee6; display:block; margin-bottom:16px; }
.wishlist-empty h3 { font-family:'Cinzel',serif; font-size:1.2rem; color:#00285a; margin-bottom:8px; }
.wishlist-empty p  { color:#7a8fa6; font-size:14px; margin-bottom:20px; }

@media(max-width:900px) { .wishlist-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:480px) { .wishlist-grid { grid-template-columns:1fr; } }
</style>

<div class="wishlist-wrap">
    <h1 class="wishlist-title">MY WISHLIST</h1>

    @if($products->count() > 0)
        <div class="wishlist-grid" id="wishlistGrid">
            @foreach($products as $product)
                <div class="wish-card" id="wish-{{ $product->id }}">
                    <div class="wish-card-img">
                        <img src="{{ $product->image ?? asset('images/placeholder-product.jpg') }}"
                             alt="{{ $product->name }}" loading="lazy">
                        <button class="wish-remove" onclick="removeWish({{ $product->id }})" aria-label="Remove">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                    <div class="wish-card-info">
                        <a href="{{ route('product.show', $product->slug) }}" class="wish-card-name">
                            {{ $product->name }}
                        </a>
                        <div class="wish-card-price">₹{{ number_format($product->price) }}</div>
                        <button class="wish-add-btn open-product-slider"
                                data-product-id="{{ $product->id }}"
                                data-product-name="{{ $product->name }}">
                            ADD TO CART
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="wishlist-empty">
            <i class="bi bi-heart"></i>
            <h3>Your wishlist is empty</h3>
            <p>Save items you love by clicking the heart icon.</p>
            <a href="{{ route('shop.index') }}" class="btn-shop">START SHOPPING</a>
        </div>
    @endif
</div>

@push('scripts')
<script>
function removeWish(id) {
    fetch('/wishlist/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            var el = document.getElementById('wish-' + id);
            if (el) el.remove();
            var remaining = document.querySelectorAll('.wish-card').length;
            if (remaining === 0) location.reload();
        }
    });
}
</script>
@endpush
@endsection