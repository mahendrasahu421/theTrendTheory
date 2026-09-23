{{-- resources/views/froentend/returns/index.blade.php --}}
@extends('froentend.layouts.app')

@push('seo')
    <title>My Returns &amp; Exchanges | THE TREND THEORY</title>
    <meta name="robots" content="noindex, nofollow">
@endpush

@section('main')
<style>
.myrtn-wrap {
    max-width: 860px;
    margin: 28px auto 70px;
    padding: 0 16px;
    font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
}
.myrtn-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
    flex-wrap: wrap;
    gap: 10px;
}
.myrtn-title {
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    letter-spacing: -0.3px;
}
.myrtn-sub {
    font-size: 12px;
    color: #64748b;
    margin-top: 2px;
}
.myrtn-back-link {
    font-size: 12.5px;
    font-weight: 700;
    color: #00285a;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

/* Return Card */
.myrtn-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px 20px;
    margin-bottom: 14px;
    box-shadow: 0 4px 16px -4px rgba(15, 23, 42, 0.05);
    display: flex;
    flex-direction: column;
    gap: 14px;
    transition: all 0.18s ease;
}

/* Card Header */
.myrtn-card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
    padding-bottom: 12px;
    border-bottom: 1px solid #f1f5f9;
}
.myrtn-top-left {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.myrtn-type-pill {
    padding: 3px 9px;
    border-radius: 6px;
    font-size: 10.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.myrtn-type-return {
    background: #fff1f2;
    color: #be123c;
    border: 1px solid #fecdd3;
}
.myrtn-type-exchange {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}
.myrtn-num {
    font-size: 13.5px;
    font-weight: 800;
    color: #0f172a;
}
.myrtn-meta-text {
    font-size: 11.5px;
    color: #64748b;
}

/* Status Badges */
.myrtn-status {
    padding: 3px 11px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
}
.myrtn-pending   { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
.myrtn-approved  { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.myrtn-rejected  { background: #fff1f2; color: #9f1239; border: 1px solid #fecdd3; }
.myrtn-completed { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.myrtn-picked_up, .myrtn-received { background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; }

/* Product Items List */
.myrtn-products-section {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.myrtn-prod-row {
    display: flex;
    align-items: center;
    gap: 12px;
}
.myrtn-thumb {
    width: 46px;
    height: 46px;
    min-width: 46px;
    border-radius: 8px;
    object-fit: cover;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}
.myrtn-thumb-placeholder {
    width: 46px;
    height: 46px;
    min-width: 46px;
    border-radius: 8px;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 18px;
}
.myrtn-prod-info { flex: 1; min-width: 0; }
.myrtn-prod-name {
    font-size: 12.5px;
    font-weight: 700;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.myrtn-prod-specs {
    font-size: 11px;
    color: #64748b;
    margin-top: 2px;
}
.myrtn-prod-amt {
    font-size: 12.5px;
    font-weight: 800;
    color: #00285a;
    white-space: nowrap;
}

/* Card Bottom Footer */
.myrtn-card-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
    padding-top: 10px;
    border-top: 1px solid #f1f5f9;
}
.myrtn-reason-tag {
    font-size: 11.5px;
    color: #475569;
}
.myrtn-reason-tag strong {
    color: #0f172a;
}
.myrtn-actions-group {
    display: flex;
    align-items: center;
    gap: 8px;
}
.myrtn-refund-pill {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 999px;
    padding: 3px 10px;
    font-size: 11px;
    font-weight: 700;
    color: #334155;
}
.myrtn-view-btn {
    padding: 6px 14px;
    background: #00285a;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: background 0.15s ease;
}

.myrtn-empty {
    text-align: center;
    padding: 50px 20px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    color: #64748b;
}
.myrtn-alert-success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
    padding: 10px 14px;
    border-radius: 10px;
    font-size: 12.5px;
    font-weight: 600;
    margin-bottom: 16px;
}
</style>

<div class="myrtn-wrap">
    <div class="myrtn-header">
        <div>
            <h1 class="myrtn-title">Returns &amp; Exchanges</h1>
            <p class="myrtn-sub">Track and manage your return and exchange requests.</p>
        </div>
        <a href="{{ route('order.track') }}" class="myrtn-back-link">
            <i class="bi bi-arrow-left"></i> Back to Orders
        </a>
    </div>

    @if(session('success'))
        <div class="myrtn-alert-success">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    @if($returns->count() > 0)
        @foreach($returns as $rtn)
        <div class="myrtn-card">

            {{-- 1. Card Top Header --}}
            <div class="myrtn-card-top">
                <div class="myrtn-top-left">
                    <span class="myrtn-type-pill {{ $rtn->type === 'exchange' ? 'myrtn-type-exchange' : 'myrtn-type-return' }}">
                        {{ strtoupper($rtn->type) }}
                    </span>
                    <span class="myrtn-num">#{{ $rtn->return_number }}</span>
                    <span class="myrtn-meta-text">&bull; Order #{{ optional($rtn->order)->order_number }} &bull; {{ $rtn->created_at->format('d M Y') }}</span>
                </div>
                <span class="myrtn-status myrtn-{{ $rtn->status }}">
                    {{ ucwords(str_replace('_',' ',$rtn->status)) }}
                </span>
            </div>

            {{-- 2. Products in Order --}}
            @if($rtn->order && $rtn->order->items && $rtn->order->items->count() > 0)
                <div class="myrtn-products-section">
                    @foreach($rtn->order->items as $item)
                        @php
                            $prodImg = '';
                            if (!empty($item->product_image)) {
                                if (filter_var($item->product_image, FILTER_VALIDATE_URL) || str_starts_with($item->product_image, 'http') || str_starts_with($item->product_image, '/')) {
                                    $prodImg = $item->product_image;
                                } else {
                                    $prodImg = url('/storage/' . $item->product_image);
                                }
                            } elseif ($item->product) {
                                $prodImg = $item->product->main_image ?? $item->product->image ?? '';
                            }
                        @endphp
                        <div class="myrtn-prod-row">
                            @if($prodImg)
                                <img class="myrtn-thumb"
                                     src="{{ $prodImg }}"
                                     alt="{{ $item->product_name }}"
                                     onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\'myrtn-thumb-placeholder\'><i class=\'bi bi-bag\'></i></div>';">
                            @else
                                <div class="myrtn-thumb-placeholder">
                                    <i class="bi bi-bag"></i>
                                </div>
                            @endif
                            <div class="myrtn-prod-info">
                                <div class="myrtn-prod-name">{{ $item->product_name }}</div>
                                <div class="myrtn-prod-specs">
                                    @if($item->size) Size: <strong>{{ $item->size }}</strong> &bull; @endif
                                    Qty: {{ $item->quantity }}
                                    @if($rtn->type === 'exchange' && $rtn->exchange_size)
                                        &bull; <span style="color:#1d4ed8;font-weight:700;">Exchange Size: {{ $rtn->exchange_size }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="myrtn-prod-amt">
                                ₹{{ number_format($item->subtotal ?: ($item->unit_price * $item->quantity)) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- 3. Card Bottom Info & Action --}}
            <div class="myrtn-card-bottom">
                <div class="myrtn-reason-tag">
                    Reason: <strong>{{ $rtn->reason_label ?? ucfirst(str_replace('_',' ',$rtn->reason)) }}</strong>
                </div>
                <div class="myrtn-actions-group">
                    @if($rtn->refund && $rtn->type === 'return')
                        <span class="myrtn-refund-pill">Refund ₹{{ number_format($rtn->refund->amount) }} ({{ ucfirst($rtn->refund->status) }})</span>
                    @endif
                    <a href="{{ route('order.return.show', $rtn->id) }}" class="myrtn-view-btn">
                        View Details
                    </a>
                </div>
            </div>

        </div>
        @endforeach
    @else
        <div class="myrtn-empty">
            <p style="font-size:14px;font-weight:700;color:#0f172a;margin:0 0 4px;">No Return or Exchange Requests</p>
            <p style="font-size:12px;margin:0 0 16px;">You haven't requested any returns or exchanges yet.</p>
            <a href="{{ route('shop.index') }}" style="display:inline-block;padding:8px 20px;background:#00285a;color:#fff;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none;">Shop Now</a>
        </div>
    @endif
</div>
@endsection
