{{-- resources/views/froentend/returns/create.blade.php --}}
@extends('froentend.layouts.app')

@push('seo')
    <title>Return / Exchange #{{ $order->order_number }} | Vayu</title>
    <meta name="robots" content="noindex, nofollow">
@endpush

@section('main')
<style>
.rtn-container {
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
.rtn-2col-grid {
    display: grid;
    grid-template-columns: 1.25fr 0.95fr;
    gap: 20px;
    align-items: flex-start;
}

/* Card Style */
.rtn-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    box-shadow: 0 8px 24px -8px rgba(15, 23, 42, 0.06);
    overflow: hidden;
}

.rtn-card-head {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
}
.rtn-card-title {
    font-size: 16px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    letter-spacing: -0.2px;
}
.rtn-card-sub {
    font-size: 11.5px;
    color: #64748b;
    margin-top: 2px;
}

.rtn-card-body {
    padding: 18px 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.rtn-section-title {
    font-size: 11px;
    font-weight: 800;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* ── LEFT: FORM CONTROLS ── */
.rtn-type-toggle {
    display: grid;
    grid-template-columns: 1fr 1fr;
    background: #f1f5f9;
    padding: 4px;
    border-radius: 10px;
    gap: 4px;
}
.rtn-type-btn {
    border: none;
    background: transparent;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    cursor: pointer;
    transition: all 0.15s ease;
    font-family: inherit;
    text-align: center;
}
.rtn-type-btn.active {
    background: #ffffff;
    color: #00285a;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.08);
}

/* Exchange Details */
.rtn-exchange-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 12px;
    margin-top: 8px;
}
.rtn-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.rtn-input-label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    display: block;
    margin-bottom: 4px;
}
.rtn-input, .rtn-select {
    width: 100%;
    padding: 8px 10px;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    font-size: 12px;
    font-family: inherit;
    color: #0f172a;
    background: #ffffff;
    outline: none;
    box-sizing: border-box;
    transition: border-color 0.15s ease;
}
.rtn-input:focus, .rtn-select:focus { border-color: #00285a; }

/* ── REASONS BADGES (Pill Style) ── */
.rtn-reasons-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.rtn-reason-badge {
    padding: 7px 14px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
    transition: all 0.18s ease;
    font-family: inherit;
    display: inline-flex;
    align-items: center;
    line-height: 1;
    white-space: nowrap;
}
.rtn-reason-badge.active {
    background: #00285a;
    border-color: #00285a;
    color: #ffffff;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(0, 40, 90, 0.25);
}

.rtn-textarea {
    width: 100%;
    margin-top: 8px;
    padding: 8px 12px;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    font-size: 12px;
    font-family: inherit;
    color: #0f172a;
    outline: none;
    resize: vertical;
    min-height: 55px;
    box-sizing: border-box;
}
.rtn-textarea:focus { border-color: #00285a; }

/* Refund Methods */
.rtn-methods-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 6px;
}
.rtn-method-card {
    padding: 8px 10px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.15s ease;
    display: block;
}
.rtn-method-card.selected {
    background: #f0f4ff;
    border-color: #00285a;
}
.rtn-method-card.selected .rtn-m-title { color: #00285a; font-weight: 800; }
.rtn-m-title {
    font-size: 11.5px;
    font-weight: 700;
    color: #0f172a;
}
.rtn-m-sub {
    font-size: 10px;
    color: #64748b;
    margin-top: 1px;
}

.rtn-panel-details {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 12px;
    margin-top: 8px;
}

/* Submit */
.rtn-submit-btn {
    width: 100%;
    height: 42px;
    background: #00285a;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.2s ease;
    margin-top: 4px;
}
.rtn-submit-btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
    transform: none;
}

/* ── RIGHT: PRODUCT DETAILS & SUMMARY ── */
.rtn-summary-card {
    position: sticky;
    top: 20px;
}
.rtn-order-tag {
    display: inline-block;
    padding: 2px 8px;
    background: #f0f4ff;
    color: #00285a;
    border: 1px solid #dbeafe;
    border-radius: 5px;
    font-size: 11px;
    font-weight: 700;
}

.rtn-product-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.rtn-product-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f1f5f9;
}
.rtn-product-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
.rtn-thumb {
    width: 52px;
    height: 52px;
    min-width: 52px;
    border-radius: 10px;
    object-fit: cover;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    display: block;
}
.rtn-thumb-fallback {
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
.rtn-prod-info { flex: 1; min-width: 0; }
.rtn-prod-title {
    font-size: 12.5px;
    font-weight: 700;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.rtn-prod-meta {
    font-size: 11px;
    color: #64748b;
    margin-top: 2px;
}
.rtn-prod-price {
    font-size: 13px;
    font-weight: 800;
    color: #00285a;
    white-space: nowrap;
}

/* Price Breakdown Box */
.rtn-calc-box {
    background: #fafbfc;
    border-radius: 12px;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.rtn-calc-row {
    display: flex;
    justify-content: space-between;
    font-size: 11.5px;
    color: #64748b;
}
.rtn-calc-row.total {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
    border-top: 1px solid #e2e8f0;
    padding-top: 6px;
    margin-top: 2px;
}

/* Policy checklist */
.rtn-policy-box {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    padding: 10px 12px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.rtn-policy-item {
    font-size: 11px;
    color: #166534;
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 600;
}

.rtn-alert-danger {
    background: #fff1f2;
    border: 1px solid #fecdd3;
    color: #be123c;
    padding: 10px 14px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 14px;
}

@media (max-width: 860px) {
    .rtn-2col-grid {
        grid-template-columns: 1fr;
    }
    .rtn-summary-card {
        position: static;
    }
}
@media (max-width: 480px) {
    .rtn-methods-grid,
    .rtn-grid-2 {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="rtn-container">
    <a href="{{ route('order.track') }}" class="rtn-back-link">
        <i class="bi bi-arrow-left"></i> Back to Orders
    </a>

    @if(session('error'))
        <div class="rtn-alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="rtn-alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="rtn-2col-grid">

        {{-- ═══════════════════════════════════════════
             LEFT COLUMN: RETURN / EXCHANGE REQUEST FORM
             ═══════════════════════════════════════════ --}}
        <div class="rtn-card">
            <div class="rtn-card-head">
                <h1 class="rtn-card-title">Request Return or Exchange</h1>
                <p class="rtn-card-sub">Choose your request type and fill in the required details.</p>
            </div>

            <form method="POST" action="{{ route('order.return.store', $order->id) }}" id="rtnForm">
                @csrf
                <input type="hidden" name="type" id="rtnTypeInput" value="{{ !empty($isExchangeOnly) ? 'exchange' : old('type', 'return') }}">
                <input type="hidden" name="reason" id="rtnReasonHidden" value="{{ old('reason') }}">
                <input type="hidden" name="refund_method" id="rtnMethodHidden" value="{{ old('refund_method', 'original_payment') }}">

                <div class="rtn-card-body">

                    @if(!empty($isExchangeOnly))
                        <div style="background:#fffbeb;border:1.5px solid #fde68a;border-radius:12px;padding:12px 14px;display:flex;align-items:flex-start;gap:10px;">
                            <i class="bi bi-info-circle-fill" style="color:#b45309;font-size:17px;margin-top:1px;"></i>
                            <div style="font-size:12px;color:#92400e;line-height:1.45;">
                                <strong>Exchange-Only Policy for Pincode {{ $order->shipping_pincode }}:</strong> Direct refund/return is not supported for this delivery location due to regional transit policy. You can exchange this item for another size or color.
                            </div>
                        </div>
                    @endif

                    {{-- 1. Request Type --}}
                    <div>
                        <div class="rtn-section-title">1. Request Type</div>
                        <div class="rtn-type-toggle">
                            <button type="button"
                                    class="rtn-type-btn {{ (!empty($isExchangeOnly) ? '' : (old('type', 'return') === 'return' ? 'active' : '')) }}"
                                    style="{{ !empty($isExchangeOnly) ? 'opacity:0.4;cursor:not-allowed;background:transparent;color:#94a3b8;' : '' }}"
                                    @if(!empty($isExchangeOnly)) disabled title="Return (Refund) is unavailable for this delivery area" @else onclick="rtnSelectType('return', this)" @endif>
                                Return &amp; Refund @if(!empty($isExchangeOnly)) (Unavailable) @endif
                            </button>
                            <button type="button"
                                    class="rtn-type-btn {{ (!empty($isExchangeOnly) || old('type') === 'exchange') ? 'active' : '' }}"
                                    onclick="rtnSelectType('exchange', this)">
                                Exchange Item
                            </button>
                        </div>

                        {{-- Exchange details panel --}}
                        <div id="exchangeFields" class="rtn-exchange-box" style="{{ (!empty($isExchangeOnly) || old('type') === 'exchange') ? '' : 'display:none;' }}">
                            <div class="rtn-grid-2">
                                <div>
                                    <label class="rtn-input-label">Required Size</label>
                                    <select name="exchange_size" class="rtn-select">
                                        <option value="">Select Size</option>
                                        @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL'] as $sz)
                                            <option value="{{ $sz }}" {{ old('exchange_size') === $sz ? 'selected' : '' }}>{{ $sz }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="rtn-input-label">Color (optional)</label>
                                    <input type="text" name="exchange_color" class="rtn-input" value="{{ old('exchange_color') }}" placeholder="e.g. Black, White">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Reason Badges --}}
                    <div>
                        <div class="rtn-section-title">
                            <span>2. Reason for Request</span>
                            <span style="color:#e11d48;font-size:10px;">*Required</span>
                        </div>
                        <div class="rtn-reasons-badges">
                            @foreach([
                                'size_issue'       => 'Wrong Size / Fit',
                                'damaged'          => 'Damaged / Defective',
                                'wrong_item'       => 'Wrong Item Received',
                                'not_as_described' => 'Item Not as Shown',
                                'changed_mind'     => 'Changed Mind',
                                'other'            => 'Other Reason'
                            ] as $val => $label)
                                <button type="button"
                                        class="rtn-reason-badge {{ old('reason') === $val ? 'active' : '' }}"
                                        onclick="rtnPickReason('{{ $val }}', this)">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>

                        <textarea name="description"
                                  class="rtn-textarea"
                                  placeholder="Additional comments or details (optional)...">{{ old('description') }}</textarea>
                    </div>

                    {{-- 3. Refund Method (Only for Returns) --}}
                    <div id="refundMethodSection" style="{{ old('type') === 'exchange' ? 'display:none;' : '' }}">
                        <div class="rtn-section-title">3. Refund Destination</div>
                        <div class="rtn-methods-grid">
                            <div class="rtn-method-card {{ old('refund_method', 'original_payment') === 'original_payment' ? 'selected' : '' }}"
                                 onclick="rtnPickMethod('original_payment', this)">
                                <div class="rtn-m-title">Original Payment</div>
                                <div class="rtn-m-sub">Reversed in 5–7 days</div>
                            </div>

                            <div class="rtn-method-card {{ old('refund_method') === 'bank_transfer' ? 'selected' : '' }}"
                                 onclick="rtnPickMethod('bank_transfer', this)">
                                <div class="rtn-m-title">Bank Transfer</div>
                                <div class="rtn-m-sub">Account credit in 5–7 days</div>
                            </div>

                            <div class="rtn-method-card {{ old('refund_method') === 'upi' ? 'selected' : '' }}"
                                 onclick="rtnPickMethod('upi', this)">
                                <div class="rtn-m-title">UPI Transfer</div>
                                <div class="rtn-m-sub">Direct UPI in 1–3 days</div>
                            </div>

                            <div class="rtn-method-card {{ old('refund_method') === 'store_credit' ? 'selected' : '' }}"
                                 onclick="rtnPickMethod('store_credit', this)">
                                <div class="rtn-m-title">Store Credit (+5%)</div>
                                <div class="rtn-m-sub">Wallet bonus credit</div>
                            </div>
                        </div>

                        {{-- Bank fields --}}
                        <div id="bankFields" class="rtn-panel-details" style="{{ old('refund_method') === 'bank_transfer' ? '' : 'display:none;' }}">
                            <div class="rtn-grid-2" style="margin-bottom:8px;">
                                <div>
                                    <label class="rtn-input-label">Account Holder</label>
                                    <input type="text" name="account_holder" class="rtn-input" value="{{ old('account_holder') }}" placeholder="Full name">
                                </div>
                                <div>
                                    <label class="rtn-input-label">Bank Name</label>
                                    <input type="text" name="bank_name" class="rtn-input" value="{{ old('bank_name') }}" placeholder="e.g. SBI, HDFC">
                                </div>
                            </div>
                            <div class="rtn-grid-2">
                                <div>
                                    <label class="rtn-input-label">Account Number</label>
                                    <input type="text" name="account_number" class="rtn-input" value="{{ old('account_number') }}" placeholder="Account no.">
                                </div>
                                <div>
                                    <label class="rtn-input-label">IFSC Code</label>
                                    <input type="text" name="ifsc_code" class="rtn-input" value="{{ old('ifsc_code') }}" placeholder="e.g. SBIN0001234">
                                </div>
                            </div>
                        </div>

                        {{-- UPI field --}}
                        <div id="upiFields" class="rtn-panel-details" style="{{ old('refund_method') === 'upi' ? '' : 'display:none;' }}">
                            <label class="rtn-input-label">UPI ID / VPA</label>
                            <input type="text" name="upi_id" class="rtn-input" value="{{ old('upi_id') }}" placeholder="e.g. username@okhdfcbank">
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="rtn-submit-btn" id="rtnSubmitBtn">
                        Submit Request
                    </button>

                </div>
            </form>
        </div>

        {{-- ═══════════════════════════════════════════
             RIGHT COLUMN: PRODUCT DETAILS & SUMMARY
             ═══════════════════════════════════════════ --}}
        <div class="rtn-card rtn-summary-card">
            <div class="rtn-card-head" style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <h2 class="rtn-card-title">Order Details</h2>
                    <p class="rtn-card-sub">Placed on {{ $order->created_at->format('d M Y') }}</p>
                </div>
                <span class="rtn-order-tag">#{{ $order->order_number }}</span>
            </div>

            <div class="rtn-card-body">

                {{-- Products List --}}
                <div class="rtn-product-list">
                    @foreach($order->items as $item)
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
                        <div class="rtn-product-row">
                            @if($prodImg)
                                <img class="rtn-thumb"
                                     src="{{ $prodImg }}"
                                     alt="{{ $item->product_name }}"
                                     onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\'rtn-thumb-fallback\'><i class=\'bi bi-bag\'></i></div>';">
                            @else
                                <div class="rtn-thumb-fallback">
                                    <i class="bi bi-bag"></i>
                                </div>
                            @endif
                            <div class="rtn-prod-info">
                                <div class="rtn-prod-title">{{ $item->product_name }}</div>
                                <div class="rtn-prod-meta">
                                    @if($item->size) Size: <strong>{{ $item->size }}</strong> &bull; @endif
                                    Qty: {{ $item->quantity }}
                                </div>
                            </div>
                            <div class="rtn-prod-price">
                                ₹{{ number_format($item->subtotal ?: ($item->unit_price * $item->quantity)) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Summary Breakdown --}}
                <div class="rtn-calc-box">
                    <div class="rtn-calc-row">
                        <span>Items Count</span>
                        <span>{{ $order->items->sum('quantity') }} items</span>
                    </div>
                    <div class="rtn-calc-row">
                        <span>Payment Mode</span>
                        <span>{{ strtoupper($order->payment_method ?: 'ONLINE') }}</span>
                    </div>
                    <div class="rtn-calc-row total">
                        <span>Refundable Amount</span>
                        <span style="color:#00285a;">₹{{ number_format($order->total_amount) }}</span>
                    </div>
                </div>

                {{-- Policy Highlights --}}
                <div class="rtn-policy-box">
                    <div class="rtn-policy-item">
                        <i class="bi bi-shield-check"></i>
                        <span>7 Days Easy Return &amp; Replacement</span>
                    </div>
                    <div class="rtn-policy-item">
                        <i class="bi bi-truck"></i>
                        <span>Doorstep courier pickup available</span>
                    </div>
                    <div class="rtn-policy-item">
                        <i class="bi bi-check-circle"></i>
                        <span>100% secure refund guarantee</span>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
function rtnSelectType(type, el) {
    document.getElementById('rtnTypeInput').value = type;
    document.querySelectorAll('.rtn-type-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');

    document.getElementById('exchangeFields').style.display = (type === 'exchange') ? 'block' : 'none';
    document.getElementById('refundMethodSection').style.display = (type === 'return') ? 'block' : 'none';
}

function rtnPickReason(val, el) {
    document.getElementById('rtnReasonHidden').value = val;
    document.querySelectorAll('.rtn-reason-badge').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
}

function rtnPickMethod(method, el) {
    document.getElementById('rtnMethodHidden').value = method;
    document.querySelectorAll('.rtn-method-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');

    document.getElementById('bankFields').style.display = (method === 'bank_transfer') ? 'block' : 'none';
    document.getElementById('upiFields').style.display  = (method === 'upi') ? 'block' : 'none';
}

document.getElementById('rtnForm').addEventListener('submit', function(e) {
    if (!document.getElementById('rtnReasonHidden').value) {
        e.preventDefault();
        alert('Please select a reason for your request.');
        return;
    }
    var btn = document.getElementById('rtnSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:14px;height:14px;border-width:2px;margin-right:6px"></span> Submitting...';
});
</script>
@endsection
