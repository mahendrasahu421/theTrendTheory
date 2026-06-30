{{-- resources/views/admin/dashboards/product_editor.blade.php --}}
@extends('admin.layouts.app')
@section('title','Product Editor Dashboard')
@section('content')

<style>
.sec{font-size:10px;font-weight:700;color:#7a8fa6;text-transform:uppercase;letter-spacing:1.5px;margin:20px 0 10px;display:flex;align-items:center;gap:8px}
.sec::after{content:'';flex:1;height:1px;background:#eef2f6}
.g4{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px}
.g3{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px}
.g2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}
.mc{background:white;border:1px solid #eef2f6;border-radius:14px;padding:16px 18px;transition:transform .15s}
.mc:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,40,90,.07)}
.mc-top{height:3px;border-radius:3px;margin-bottom:10px}
.mc-lbl{font-size:10px;font-weight:700;color:#7a8fa6;text-transform:uppercase;letter-spacing:.5px}
.mc-val{font-size:24px;font-weight:700;color:#00285a;font-family:'Cinzel',serif;margin:3px 0 5px;line-height:1}
.mc-sub{font-size:11px;color:#7a8fa6}
.up{color:#22c55e;font-weight:600}.dn{color:#ef4444;font-weight:600}
.card{background:white;border:1px solid #eef2f6;border-radius:14px;overflow:hidden}
.card-h{display:flex;justify-content:space-between;align-items:center;padding:14px 18px;border-bottom:1px solid #eef2f6}
.card-t{font-family:'Cinzel',serif;font-size:12px;font-weight:700;color:#00285a;letter-spacing:1px}
.dt{width:100%;border-collapse:collapse}
.dt th{padding:9px 14px;font-size:10px;font-weight:700;color:#7a8fa6;text-transform:uppercase;letter-spacing:.8px;background:#f8fafc;border-bottom:1px solid #eef2f6;text-align:left}
.dt td{padding:11px 14px;font-size:13px;border-bottom:1px solid rgba(0,0,0,.04);vertical-align:middle}
.dt tr:last-child td{border-bottom:none}
.dt tr:hover td{background:#fafbff}
.bdg{display:inline-flex;align-items:center;padding:2px 9px;border-radius:20px;font-size:11px;font-weight:700}
.bdg-g{background:#e8f5e9;color:#2e7d32}.bdg-a{background:#fff3e0;color:#e65100}
.bdg-b{background:#e3f2fd;color:#1565c0}.bdg-r{background:#fce4ec;color:#c62828}
.prog{background:#f0f4f8;border-radius:20px;height:6px;overflow:hidden;margin-top:4px}
.prog-f{height:100%;border-radius:20px}
.qa{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;margin-bottom:18px}
.qa-btn{display:flex;align-items:center;gap:12px;padding:14px 18px;background:white;border-radius:12px;border:1px solid #eef2f6;text-decoration:none;transition:all .15s}
.qa-btn:hover{border-color:#00285a;transform:translateY(-1px)}
.qa-ico{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
.qa-info-lbl{font-size:12px;font-weight:700;color:#00285a}
.qa-info-sub{font-size:11px;color:#7a8fa6;margin-top:1px}
.pimg{width:40px;height:48px;border-radius:8px;object-fit:cover;border:1px solid #eef2f6}
.pimg-placeholder{width:40px;height:48px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center}
@media(max-width:1200px){.g4{grid-template-columns:repeat(2,1fr)}.qa{grid-template-columns:1fr}}
@media(max-width:768px){.g4,.g3,.g2{grid-template-columns:1fr}}
</style>

{{-- WELCOME --}}
<div style="background:#00285a;border-radius:14px;padding:20px 24px;margin-bottom:18px;display:flex;justify-content:space-between;align-items:center;color:white">
    <div>
        <div style="font-family:'Cinzel',serif;font-size:16px;font-weight:700;letter-spacing:2px">Product Editor Panel</div>
        <div style="font-size:12px;opacity:.65;margin-top:3px">Welcome, {{ auth()->user()->name }} · {{ now()->format('d M Y') }}</div>
    </div>
    <a href="{{ route('admin.products.create') }}" style="background:#ffd700;color:#00285a;padding:8px 20px;border-radius:30px;font-size:12px;font-weight:700;text-decoration:none">
        + Add New Product
    </a>
</div>

{{-- QUICK ACTIONS --}}
<div class="qa">
    <a href="{{ route('admin.products.create') }}" class="qa-btn">
        <div class="qa-ico" style="background:#e8f0fb"><i class="bi bi-plus-square" style="color:#00285a"></i></div>
        <div>
            <div class="qa-info-lbl">Add New Product</div>
            <div class="qa-info-sub">Create a new listing</div>
        </div>
    </a>
    <a href="{{ route('admin.products.index') }}" class="qa-btn">
        <div class="qa-ico" style="background:#f3e5f5"><i class="bi bi-box-seam" style="color:#7b1fa2"></i></div>
        <div>
            <div class="qa-info-lbl">All Products</div>
            <div class="qa-info-sub">{{ $totalProducts }} active products</div>
        </div>
    </a>
    <a href="{{ route('admin.categories.index') }}" class="qa-btn">
        <div class="qa-ico" style="background:#e8f5e9"><i class="bi bi-tags" style="color:#2e7d32"></i></div>
        <div>
            <div class="qa-info-lbl">Categories</div>
            <div class="qa-info-sub">{{ $categories->count() }} categories</div>
        </div>
    </a>
</div>

{{-- STATS --}}
<div class="sec">My product stats</div>
<div class="g4">
    <div class="mc">
        <div class="mc-top" style="background:#00285a"></div>
        <div class="mc-lbl">Total Active</div>
        <div class="mc-val">{{ $totalProducts }}</div>
        <div class="mc-sub">Live in store</div>
    </div>
    <div class="mc">
        <div class="mc-top" style="background:#f97316"></div>
        <div class="mc-lbl">Low Stock (≤ 5)</div>
        <div class="mc-val">{{ $lowStock }}</div>
        <div class="mc-sub {{ $lowStock > 0 ? 'dn' : 'up' }}">{{ $lowStock > 0 ? 'Needs restock' : 'All good' }}</div>
    </div>
    <div class="mc">
        <div class="mc-top" style="background:#22c55e"></div>
        <div class="mc-lbl">Categories</div>
        <div class="mc-val">{{ $categories->count() }}</div>
        <div class="mc-sub">Active categories</div>
    </div>
    <div class="mc">
        <div class="mc-top" style="background:#3b82f6"></div>
        <div class="mc-lbl">Featured</div>
        <div class="mc-val">{{ $myProducts->where('is_featured',true)->count() }}</div>
        <div class="mc-sub">Featured products</div>
    </div>
</div>

{{-- LOW STOCK ALERT --}}
@php $lowStockItems = $myProducts->where('stock','<=',5); @endphp
@if($lowStockItems->count() > 0)
<div style="background:#fff5f7;border:1px solid #fce4ec;border-radius:12px;padding:12px 16px;margin-top:12px;display:flex;align-items:center;gap:10px;font-size:13px;color:#c62828">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <span><strong>{{ $lowStockItems->count() }} products</strong> have low stock — please update them soon.</span>
</div>
@endif

{{-- PRODUCT LIST --}}
<div class="sec">All products</div>
<div class="card">
    <div class="card-h">
        <div class="card-t">Products ({{ $totalProducts }} total)</div>
        <div style="display:flex;gap:8px">
            <a href="{{ route('admin.products.index') }}" style="font-size:12px;color:#7a8fa6;text-decoration:none">View all →</a>
            <a href="{{ route('admin.products.create') }}" style="background:#00285a;color:white;padding:5px 14px;border-radius:20px;font-size:11px;font-weight:700;text-decoration:none">+ Add</a>
        </div>
    </div>
    <table class="dt">
        <thead>
            <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Tags</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse($myProducts as $p)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px">
                        @if($p->image)
                            <img src="{{ $p->image }}" class="pimg" alt="{{ $p->name }}">
                        @else
                            <div class="pimg-placeholder"><i class="bi bi-image" style="color:#cbd5e1;font-size:16px"></i></div>
                        @endif
                        <div>
                            <div style="font-weight:600;font-size:13px;color:#00285a">{{ Str::limit($p->name,28) }}</div>
                            @if($p->sku)<div style="font-size:11px;color:#7a8fa6">{{ $p->sku }}</div>@endif
                        </div>
                    </div>
                </td>
                <td style="font-size:12px;color:#7a8fa6">{{ $p->category->name ?? '—' }}</td>
                <td>
                    <div style="font-weight:700;color:#00285a">₹{{ number_format($p->price) }}</div>
                    @if($p->original_price)
                        <div style="font-size:11px;color:#7a8fa6;text-decoration:line-through">₹{{ number_format($p->original_price) }}</div>
                    @endif
                </td>
                <td>
                    <span class="bdg {{ $p->stock == 0 ? 'bdg-r' : ($p->stock <= 5 ? 'bdg-a' : 'bdg-g') }}">
                        {{ $p->stock == 0 ? 'Out' : $p->stock.' left' }}
                    </span>
                </td>
                <td>
                    <span class="bdg {{ $p->is_active ? 'bdg-g' : 'bdg-r' }}">
                        {{ $p->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <div style="display:flex;gap:4px;flex-wrap:wrap">
                        @if($p->is_new)     <span class="bdg" style="background:#e0f2fe;color:#075985">New</span>@endif
                        @if($p->is_featured)<span class="bdg" style="background:#fef9c3;color:#854d0e">Featured</span>@endif
                        @if($p->is_trending)<span class="bdg" style="background:#fce7f3;color:#9d174d">Trending</span>@endif
                        @if($p->is_on_sale) <span class="bdg bdg-r">Sale</span>@endif
                    </div>
                </td>
                <td>
                    <div style="display:flex;gap:6px">
                        <a href="{{ route('admin.products.edit',$p) }}" style="background:#00285a;color:white;padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700;text-decoration:none">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('product.show',$p->slug) }}" target="_blank" style="background:#f0f4f8;color:#555;padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700;text-decoration:none">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:40px;color:#7a8fa6">
                    <i class="bi bi-box-seam" style="font-size:32px;display:block;margin-bottom:10px"></i>
                    No products yet.
                    <a href="{{ route('admin.products.create') }}" style="color:#00285a;font-weight:700">Add your first product →</a>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- CATEGORIES --}}
<div class="sec">Category overview</div>
<div class="g3">
    @foreach($categories as $cat)
        <div class="mc" style="display:flex;align-items:center;gap:14px;padding:14px 16px">
            <div style="width:40px;height:40px;border-radius:10px;background:#e8f0fb;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="bi bi-tag" style="color:#00285a;font-size:17px"></i>
            </div>
            <div style="flex:1;min-width:0">
                <div style="font-weight:700;font-size:13px;color:#00285a">{{ $cat->name }}</div>
                <div style="font-size:11px;color:#7a8fa6;margin-top:2px">{{ $cat->products_count }} products</div>
                <div class="prog" style="margin-top:5px">
                    <div class="prog-f" style="width:{{ $totalProducts > 0 ? round(($cat->products_count/$totalProducts)*100) : 0 }}%;background:#00285a"></div>
                </div>
            </div>
            <span class="bdg {{ $cat->is_active ? 'bdg-g' : 'bdg-r' }}">{{ $cat->is_active ? 'Active' : 'Off' }}</span>
        </div>
    @endforeach
</div>

@endsection