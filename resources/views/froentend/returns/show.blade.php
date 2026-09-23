{{-- resources/views/froentend/returns/show.blade.php --}}
@extends('froentend.layouts.app')

@push('seo')
    <title>Return #{{ $return->return_number }} | THE TREND THEORY</title>
    <meta name="robots" content="noindex, nofollow">
@endpush

@section('main')
<style>
.rtn-show-container {
    max-width: 1060px;
    margin: 28px auto 70px;
    padding: 0 16px;
    font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
}

.rtn-back-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    font-weight: 700;
    color: #64748b;
    text-decoration: none;
    margin-bottom: 16px;
    transition: color 0.15s ease;
}

/* 2-Column Grid */
.rtn-show-grid {
    display: grid;
    grid-template-columns: 1.25fr 0.95fr;
    gap: 20px;
    align-items: flex-start;
}

/* Card */
.rtn-show-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    box-shadow: 0 8px 24px -8px rgba(15, 23, 42, 0.06);
    overflow: hidden;
}

.rtn-show-head {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}
.rtn-show-title {
    font-size: 16px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    letter-spacing: -0.2px;
}
.rtn-show-subtitle {
    font-size: 11.5px;
    color: #64748b;
    margin-top: 2px;
}

.rtn-show-body {
    padding: 18px 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.rtn-sec-heading {
    font-size: 11px;
    font-weight: 800;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    margin-bottom: 10px;
}

/* Status Tracker Journey */
.rtn-timeline {
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin-top: 4px;
}
.rtn-tl-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    position: relative;
}
.rtn-tl-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 11px;
    top: 24px;
    bottom: -10px;
    width: 2px;
    background: #e2e8f0;
}
.rtn-tl-dot {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #f8fafc;
    border: 2px solid #cbd5e1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10.5px;
    font-weight: 800;
    color: #64748b;
    z-index: 1;
    flex-shrink: 0;
}
.rtn-tl-dot.done {
    background: #00285a;
    border-color: #00285a;
    color: #ffffff;
}
.rtn-tl-dot.active {
    background: #00285a;
    border-color: #00285a;
    color: #ffffff;
    box-shadow: 0 0 0 4px rgba(0, 40, 90, 0.15);
}
.rtn-tl-title {
    font-size: 12.5px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}
.rtn-tl-desc {
    font-size: 11px;
    color: #64748b;
    margin-top: 1px;
}

/* Request Details Rows */
.rtn-data-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 7px 0;
    font-size: 12px;
    border-bottom: 1px solid #f8fafc;
}
.rtn-data-row:last-child { border-bottom: none; }
.rtn-data-label { color: #64748b; }
.rtn-data-val { color: #0f172a; font-weight: 700; }

/* Reason Badge */
.rtn-badge-pill {
    display: inline-block;
    padding: 3px 10px;
    background: #f0f4ff;
    color: #00285a;
    border: 1px solid #dbeafe;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
}
.rtn-status-pill {
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
}
.rtn-st-pending   { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
.rtn-st-approved  { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.rtn-st-rejected  { background: #fff1f2; color: #9f1239; border: 1px solid #fecdd3; }
.rtn-st-completed { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.rtn-st-picked_up, .rtn-st-received { background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; }

/* Panels */
.rtn-box-panel {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 14px;
}
.rtn-panel-head {
    font-size: 11px;
    font-weight: 800;
    color: #00285a;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* ── RIGHT: PRODUCT DETAILS & SUMMARY ── */
.rtn-sticky-card {
    position: sticky;
    top: 20px;
}
.rtn-prod-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f1f5f9;
}
.rtn-prod-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
.rtn-item-img {
    width: 52px;
    height: 52px;
    min-width: 52px;
    border-radius: 10px;
    object-fit: cover;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
}
.rtn-img-fallback {
    width: 52px;
    height: 52px;
    min-width: 52px;
    border-radius: 10px;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 20px;
}
.rtn-item-info { flex: 1; min-width: 0; }
.rtn-item-name {
    font-size: 12.5px;
    font-weight: 700;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.rtn-item-meta {
    font-size: 11px;
    color: #64748b;
    margin-top: 2px;
}
.rtn-item-price {
    font-size: 13px;
    font-weight: 800;
    color: #00285a;
    white-space: nowrap;
}

/* Summary Box */
.rtn-sum-box {
    background: #fafbfc;
    border-radius: 12px;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.rtn-sum-row {
    display: flex;
    justify-content: space-between;
    font-size: 11.5px;
    color: #64748b;
}
.rtn-sum-row.total {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
    border-top: 1px solid #e2e8f0;
    padding-top: 6px;
    margin-top: 2px;
}

@media (max-width: 860px) {
    .rtn-show-grid {
        grid-template-columns: 1fr;
    }
    .rtn-sticky-card {
        position: static;
    }
}
</style>

<div class="rtn-show-container">
    <a href="{{ route('order.returns') }}" class="rtn-back-link">
        <i class="bi bi-arrow-left"></i> Back to My Returns
    </a>

    <div class="rtn-show-grid">

        {{-- ═══════════════════════════════════════════
             LEFT COLUMN: STATUS JOURNEY & REQUEST DETAILS
             ═══════════════════════════════════════════ --}}
        <div class="rtn-show-card">
            <div class="rtn-show-head">
                <div>
                    <h1 class="rtn-show-title">Return Request Details</h1>
                    <p class="rtn-show-subtitle">Tracking ID: <strong>#{{ $return->return_number }}</strong></p>
                </div>
                <span class="rtn-status-pill rtn-st-{{ $return->status }}">
                    {{ ucwords(str_replace('_',' ',$return->status)) }}
                </span>
            </div>

            <div class="rtn-show-body">

                {{-- 1. Status Progress Tracker --}}
                @php
                    $steps = [
                        'pending'   => ['name'=>'Request Submitted',  'desc'=>'Your request has been received.'],
                        'approved'  => ['name'=>'Approved for Pickup', 'desc'=>'Pickup will be scheduled by our courier partner.'],
                        'picked_up' => ['name'=>'Item Picked Up',     'desc'=>'Courier partner has collected the package.'],
                        'received'  => ['name'=>'Item Received',      'desc'=>'Inspected at our fulfillment center.'],
                        'completed' => ['name'=>'Completed',          'desc'=>$return->type==='return' ? 'Refund processed successfully.' : 'Replacement dispatched!'],
                    ];
                    $statusOrder = array_keys($steps);
                    $currentIdx  = array_search($return->status, $statusOrder) ?? 0;
                    if ($return->status === 'rejected') {
                        $steps = ['rejected' => ['name'=>'Request Rejected', 'desc'=>$return->admin_notes ?: 'Your request was not approved. Contact support for help.']];
                        $currentIdx = 0;
                    }
                @endphp

                <div>
                    <div class="rtn-sec-heading">Progress Status</div>
                    <div class="rtn-timeline">
                        @foreach($steps as $key => $step)
                            @php $idx = array_search($key, array_keys($steps)); @endphp
                            <div class="rtn-tl-item">
                                <div class="rtn-tl-dot {{ $idx < $currentIdx ? 'done' : ($idx === $currentIdx ? 'active' : '') }}">
                                    @if($idx < $currentIdx)
                                        <i class="bi bi-check"></i>
                                    @else
                                        {{ $idx + 1 }}
                                    @endif
                                </div>
                                <div>
                                    <div class="rtn-tl-title" style="{{ $idx === $currentIdx ? 'color:#00285a;font-weight:800;' : '' }}">
                                        {{ $step['name'] }}
                                    </div>
                                    <div class="rtn-tl-desc">{{ $step['desc'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <hr style="border:none;border-top:1px solid #f1f5f9;margin:4px 0;">

                {{-- 2. Request Details --}}
                <div>
                    <div class="rtn-sec-heading">Request Information</div>
                    <div class="rtn-data-row">
                        <span class="rtn-data-label">Request Type</span>
                        <span class="rtn-data-val">{{ ucfirst($return->type) }}</span>
                    </div>
                    <div class="rtn-data-row">
                        <span class="rtn-data-label">Reason</span>
                        <span class="rtn-badge-pill">{{ $return->reason_label ?? ucfirst(str_replace('_',' ',$return->reason)) }}</span>
                    </div>
                    <div class="rtn-data-row">
                        <span class="rtn-data-label">Submitted On</span>
                        <span class="rtn-data-val">{{ $return->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                    @if($return->description)
                        <div class="rtn-data-row" style="align-items:flex-start;">
                            <span class="rtn-data-label">Customer Notes</span>
                            <span class="rtn-data-val" style="max-width:60%;text-align:right;">{{ $return->description }}</span>
                        </div>
                    @endif
                </div>

                {{-- 3. Exchange Details (if exchange) --}}
                @if($return->type === 'exchange')
                    <div class="rtn-box-panel">
                        <div class="rtn-panel-head">Exchange Specifications</div>
                        @if($return->exchange_size)
                            <div class="rtn-data-row">
                                <span class="rtn-data-label">Requested Size</span>
                                <span class="rtn-data-val" style="color:#1d4ed8;">{{ $return->exchange_size }}</span>
                            </div>
                        @endif
                        @if($return->exchange_color)
                            <div class="rtn-data-row">
                                <span class="rtn-data-label">Color Preference</span>
                                <span class="rtn-data-val">{{ $return->exchange_color }}</span>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- 4. Refund Details (if return) --}}
                @if($return->type === 'return' && $return->refund)
                    @php $refund = $return->refund; @endphp
                    <div class="rtn-box-panel">
                        <div class="rtn-panel-head">Refund Details</div>
                        <div class="rtn-data-row">
                            <span class="rtn-data-label">Refund Amount</span>
                            <span class="rtn-data-val" style="color:#059669;font-size:13.5px;">₹{{ number_format($refund->amount) }}</span>
                        </div>
                        @if($return->order)
                            <div class="rtn-data-row">
                                <span class="rtn-data-label">Delivery Charges Deducted</span>
                                <span class="rtn-data-val" style="color:#be123c;">₹{{ number_format($return->order->non_refundable_shipping_charge) }}</span>
                            </div>
                        @endif
                        <div class="rtn-data-row">
                            <span class="rtn-data-label">Payment Mode</span>
                            <span class="rtn-data-val">{{ ucwords(str_replace('_',' ',$refund->method)) }}</span>
                        </div>
                        <div class="rtn-data-row">
                            <span class="rtn-data-label">Refund Status</span>
                            <span class="rtn-status-pill rtn-st-{{ $refund->status }}">
                                {{ ucfirst($refund->status) }}
                            </span>
                        </div>
                        @if($refund->method === 'bank_transfer' && $refund->bank_name)
                            <div class="rtn-data-row">
                                <span class="rtn-data-label">Bank Account</span>
                                <span class="rtn-data-val">{{ $refund->bank_name }} (****{{ substr($refund->account_number, -4) }})</span>
                            </div>
                        @elseif($refund->method === 'upi' && $refund->upi_id)
                            <div class="rtn-data-row">
                                <span class="rtn-data-label">UPI ID</span>
                                <span class="rtn-data-val">{{ $refund->upi_id }}</span>
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        </div>

        {{-- ═══════════════════════════════════════════
             RIGHT COLUMN: PRODUCT DETAILS & SUMMARY
             ═══════════════════════════════════════════ --}}
        <div class="rtn-show-card rtn-sticky-card">
            <div class="rtn-show-head">
                <div>
                    <h2 class="rtn-show-title">Order Details</h2>
                    <p class="rtn-show-subtitle">Order #{{ optional($return->order)->order_number }}</p>
                </div>
                <span class="rtn-badge-pill">{{ $return->order ? $return->order->created_at->format('d M Y') : '' }}</span>
            </div>

            <div class="rtn-show-body">

                {{-- Product Items --}}
                @if($return->order && $return->order->items)
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        @foreach($return->order->items as $item)
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
                            <div class="rtn-prod-item">
                                @if($prodImg)
                                    <img class="rtn-item-img"
                                         src="{{ $prodImg }}"
                                         alt="{{ $item->product_name }}"
                                         onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\'rtn-img-fallback\'><i class=\'bi bi-bag\'></i></div>';">
                                @else
                                    <div class="rtn-img-fallback">
                                        <i class="bi bi-bag"></i>
                                    </div>
                                @endif
                                <div class="rtn-item-info">
                                    <div class="rtn-item-name">{{ $item->product_name }}</div>
                                    <div class="rtn-item-meta">
                                        @if($item->size) Size: <strong>{{ $item->size }}</strong> &bull; @endif
                                        Qty: {{ $item->quantity }}
                                    </div>
                                </div>
                                <div class="rtn-item-price">
                                    ₹{{ number_format($item->subtotal ?: ($item->unit_price * $item->quantity)) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Breakdown --}}
                @if($return->order)
                    <div class="rtn-sum-box">
                        <div class="rtn-sum-row">
                            <span>Order Status</span>
                            <span style="font-weight:700;color:#0f172a;">{{ ucfirst($return->order->status) }}</span>
                        </div>
                        <div class="rtn-sum-row">
                            <span>Total Items</span>
                            <span>{{ $return->order->items->sum('quantity') }} items</span>
                        </div>
                        <div class="rtn-sum-row">
                            <span>Order Paid Amount</span>
                            <span>₹{{ number_format($return->order->total_amount) }}</span>
                        </div>
                        <div class="rtn-sum-row">
                            <span>Delivery Charges (Non-refundable)</span>
                            <span style="color:#be123c;">- ₹{{ number_format($return->order->non_refundable_shipping_charge) }}</span>
                        </div>
                        <div class="rtn-sum-row total">
                            <span>Refundable Amount</span>
                            <span style="color:#00285a;">₹{{ number_format($return->order->refundable_amount) }}</span>
                        </div>
                    </div>
                @endif

                {{-- Need Help / Contact --}}
                <div style="background:#f8fafc;border:1px dashed #cbd5e1;border-radius:12px;padding:12px;text-align:center;">
                    <div style="font-size:12px;font-weight:700;color:#0f172a;">Need help with this return?</div>
                    <div style="font-size:11px;color:#64748b;margin-top:2px;">Our support team is available 24/7 to assist you.</div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
