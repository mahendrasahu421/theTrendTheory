{{-- resources/views/admin/products/show.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Product: '.$product->name)
@section('content')
@php
    $disc = $product->original_price && $product->original_price > $product->price
        ? (int)round((($product->original_price - $product->price) / $product->original_price) * 100) : 0;
@endphp
<style>
.pv{max-width:1000px}
.pv-hdr{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:10px}
.pv-title{font-family:'Cinzel',serif;font-size:16px;font-weight:700;color:#00285a;letter-spacing:1px}
.card{background:white;border:1px solid #eef2f6;border-radius:14px;overflow:hidden;margin-bottom:14px}
.ch{display:flex;justify-content:space-between;align-items:center;padding:14px 18px;border-bottom:1px solid #eef2f6}
.ct{font-family:'Cinzel',serif;font-size:12px;font-weight:700;color:#00285a;letter-spacing:1px}
.g2{display:grid;grid-template-columns:1fr 1fr;gap:0}
.img-sec{padding:20px;border-right:1px solid #eef2f6}
.main-img{border-radius:12px;overflow:hidden;aspect-ratio:4/5;background:#f8fafc;margin-bottom:10px}
.main-img img{width:100%;height:100%;object-fit:cover}
.thumbs{display:flex;gap:6px;flex-wrap:wrap}
.thumb{width:54px;height:68px;border-radius:8px;overflow:hidden;border:2px solid transparent;cursor:pointer;transition:.15s}
.thumb:hover,.thumb.active{border-color:#00285a}
.thumb img{width:100%;height:100%;object-fit:cover}
.info{padding:20px;display:flex;flex-direction:column;gap:10px}
.ir{display:flex;font-size:13px;gap:10px}
.ik{color:#7a8fa6;font-weight:600;min-width:120px;flex-shrink:0;font-size:12px}
.iv{color:#333}
.bdg{display:inline-flex;align-items:center;padding:2px 9px;border-radius:20px;font-size:11px;font-weight:700}
.bdg-g{background:#e8f5e9;color:#2e7d32}.bdg-r{background:#fce4ec;color:#c62828}
.bdg-b{background:#e3f2fd;color:#1565c0}.bdg-a{background:#fff3e0;color:#e65100}
.vt{width:100%;border-collapse:collapse}
.vt th{padding:9px 14px;font-size:10px;font-weight:700;color:#7a8fa6;text-transform:uppercase;letter-spacing:.8px;background:#f8fafc;border-bottom:1px solid #eef2f6;text-align:left}
.vt td{padding:10px 14px;font-size:13px;border-bottom:1px solid rgba(0,0,0,.04);vertical-align:middle}
.vt tr:last-child td{border-bottom:none}
.cdot{width:16px;height:16px;border-radius:50%;border:1px solid rgba(0,0,0,.1);display:inline-block;vertical-align:middle;margin-right:6px}
@media(max-width:768px){.g2{grid-template-columns:1fr}.img-sec{border-right:none;border-bottom:1px solid #eef2f6}}
</style>

<div class="pv">
    <div class="pv-hdr">
        <div class="pv-title">{{ Str::upper(Str::limit($product->name,40)) }}</div>
        <div style="display:flex;gap:8px">
            <a href="{{ route('admin.products.edit',$product) }}"
               style="background:#00285a;color:white;padding:8px 18px;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('product.show',$product->slug) }}" target="_blank"
               style="background:#f0f4f8;color:#00285a;padding:8px 18px;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none">
                <i class="bi bi-eye"></i> View on Site
            </a>
            <a href="{{ route('admin.products.index') }}"
               style="background:white;color:#555;border:1px solid #e8edf5;padding:8px 18px;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none">
                ← Back
            </a>
        </div>
    </div>

    {{-- MAIN CARD --}}
    <div class="card">
        <div class="g2">
            <div class="img-sec">
                @php $imgs = $product->images; $firstImg = $imgs->first(); @endphp
                <div class="main-img">
                    <img id="pvMain"
                         src="{{ $firstImg ? $firstImg->getImageUrl(600,750) : ($product->image ?? asset('images/placeholder-product.jpg')) }}"
                         alt="{{ $product->name }}"
                         onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                </div>
                @if($imgs->count() > 1)
                    <div class="thumbs">
                        @foreach($imgs as $i => $img)
                            <div class="thumb {{ $i===0?'active':'' }}"
                                 onclick="pvSwitch('{{ $img->getImageUrl(600,750) }}',this)">
                                <img src="{{ $img->getImageUrl(80,100) }}" alt="">
                            </div>
                        @endforeach
                    </div>
                @endif
                <div style="margin-top:10px;font-size:12px;color:#7a8fa6;text-align:center">{{ $imgs->count() }} image(s)</div>
            </div>
            <div class="info">
                <div class="ir"><span class="ik">Name</span><span class="iv" style="font-weight:600;color:#00285a">{{ $product->name }}</span></div>
                <div class="ir"><span class="ik">Category</span><span class="iv">{{ $product->category->name ?? '—' }}</span></div>
                <div class="ir"><span class="ik">SKU</span><span class="iv" style="font-family:monospace;font-size:12px">{{ $product->sku ?? '—' }}</span></div>
                <div class="ir"><span class="ik">Price</span>
                    <span class="iv">
                        <strong style="font-size:16px;color:#00285a">₹{{ number_format($product->price) }}</strong>
                        @if($disc)
                            <del style="color:#aaa;font-size:12px;margin-left:6px">₹{{ number_format($product->original_price) }}</del>
                            <span class="bdg" style="background:#ff3f6c;color:white;margin-left:4px">{{ $disc }}% OFF</span>
                        @endif
                    </span>
                </div>
                @if($product->cost_price)
                <div class="ir"><span class="ik">Margin</span>
                    <span class="iv" style="color:#22c55e;font-weight:600">
                        ₹{{ number_format($product->price - $product->cost_price) }}
                        ({{ round((($product->price - $product->cost_price)/$product->price)*100) }}%)
                    </span>
                </div>
                @endif
                <div class="ir"><span class="ik">Stock</span>
                    <span class="bdg {{ $product->stock==0?'bdg-r':($product->stock<=5?'bdg-a':'bdg-g') }}">
                        {{ $product->stock==0 ? 'Out of Stock' : $product->stock.' units' }}
                    </span>
                </div>
                <div class="ir"><span class="ik">Total Sold</span><span class="iv"><strong>{{ number_format($product->total_sold??0) }}</strong> units</span></div>
                <div class="ir"><span class="ik">Revenue</span><span class="iv" style="color:#22c55e;font-weight:600">₹{{ number_format($product->price * ($product->total_sold??0)) }}</span></div>
                <div class="ir"><span class="ik">Type</span>
                    <span class="iv">{{ ($product->has_variants??false) ? $product->variants->count().' Variants' : 'Simple Product' }}</span>
                </div>
                <div class="ir"><span class="ik">Status</span>
                    <span>
                        <span class="bdg {{ $product->is_active?'bdg-g':'bdg-r' }}">{{ $product->is_active?'Active':'Inactive' }}</span>
                        @if($product->is_new??false)<span class="bdg" style="background:#e0f2fe;color:#075985;margin-left:4px">New</span>@endif
                        @if($product->is_featured??false)<span class="bdg" style="background:#fef9c3;color:#854d0e;margin-left:4px">Featured</span>@endif
                        @if($product->is_trending??false)<span class="bdg" style="background:#fce7f3;color:#9d174d;margin-left:4px">Hot</span>@endif
                        @if($product->is_on_sale??false)<span class="bdg bdg-r" style="margin-left:4px">Sale</span>@endif
                    </span>
                </div>
                @if($product->short_description)
                <div class="ir" style="align-items:flex-start"><span class="ik">Short Desc</span><span class="iv" style="font-style:italic;color:#555;font-size:12px">{{ $product->short_description }}</span></div>
                @endif
                <div class="ir"><span class="ik">Added</span><span class="iv" style="font-size:12px;color:#555">{{ $product->created_at->format('d M Y, h:i A') }}</span></div>
                <div class="ir"><span class="ik">Updated</span><span class="iv" style="font-size:12px;color:#555">{{ $product->updated_at->diffForHumans() }}</span></div>
            </div>
        </div>
    </div>

    {{-- VARIANTS --}}
    @if(($product->has_variants??false) && $product->variants->count())
    <div class="card">
        <div class="ch">
            <div class="ct">VARIANTS ({{ $product->variants->count() }})</div>
            <span style="font-size:12px;color:#7a8fa6">Total stock: {{ $product->variants->sum('stock') }}</span>
        </div>
        <div style="overflow-x:auto">
            <table class="vt">
                <thead><tr><th>#</th><th>Size</th><th>Color</th><th>SKU</th><th>Price</th><th>MRP</th><th>Stock</th><th>Status</th></tr></thead>
                <tbody>
                @foreach($product->variants as $i => $v)
                <tr>
                    <td style="color:#7a8fa6;font-size:12px">{{ $i+1 }}</td>
                    <td style="font-weight:600">{{ $v->size ?: '—' }}</td>
                    <td>
                        @if($v->color)
                            @if($v->color_hex)<span class="cdot" style="background:{{ $v->color_hex }}"></span>@endif
                            {{ $v->color }}
                        @else —
                        @endif
                    </td>
                    <td style="font-family:monospace;font-size:11px;color:#7a8fa6">{{ $v->sku ?: '—' }}</td>
                    <td style="font-weight:700;color:#00285a">₹{{ number_format($v->price) }}</td>
                    <td style="color:#aaa;{{ $v->original_price ? 'text-decoration:line-through' : '' }}">{{ $v->original_price ? '₹'.number_format($v->original_price) : '—' }}</td>
                    <td><span class="bdg {{ $v->stock==0?'bdg-r':($v->stock<=5?'bdg-a':'bdg-g') }}">{{ $v->stock==0?'Out':$v->stock }}</span></td>
                    <td><span class="bdg {{ $v->is_active?'bdg-g':'bdg-r' }}">{{ $v->is_active?'Active':'Off' }}</span></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- DESCRIPTION --}}
    @if($product->description)
    <div class="card">
        <div class="ch"><div class="ct">DESCRIPTION</div></div>
        <div style="padding:18px 20px;font-size:13px;color:#555;line-height:1.8;white-space:pre-line">{{ $product->description }}</div>
    </div>
    @endif

    {{-- REVIEWS --}}
    @if($product->reviews->count())
    <div class="card">
        <div class="ch">
            <div class="ct">REVIEWS ({{ $product->reviews->count() }})</div>
            <span style="font-size:12px;color:#7a8fa6">Avg: {{ round($product->reviews->avg('rating'),1) }} ★</span>
        </div>
        <table class="vt">
            <thead><tr><th>Customer</th><th>Rating</th><th>Comment</th><th>Verified</th><th>Date</th></tr></thead>
            <tbody>
            @foreach($product->reviews->take(5) as $r)
            <tr>
                <td style="font-weight:600">{{ $r->reviewer_name }}</td>
                <td><span style="color:#ffd700">{{ str_repeat('★',$r->rating) }}</span></td>
                <td style="font-size:12px;color:#555;max-width:250px">{{ Str::limit($r->comment,80) }}</td>
                <td><span class="bdg {{ $r->is_verified?'bdg-g':'bdg-a' }}">{{ $r->is_verified?'Yes':'No' }}</span></td>
                <td style="font-size:11px;color:#7a8fa6">{{ $r->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@push('scripts')
<script>
function pvSwitch(url, thumb) {
    document.getElementById('pvMain').src = url;
    document.querySelectorAll('.thumb').forEach(function(t){ t.classList.remove('active'); });
    thumb.classList.add('active');
}
</script>
@endpush
@endsection