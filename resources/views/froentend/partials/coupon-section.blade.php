{{-- resources/views/froentend/partials/coupon-section.blade.php --}}
@php
    $subtotalVal = (float) ($subtotal ?? 0);
    $loyaltyPoints = max(5, (int) round($subtotalVal * 0.02));
    
    $allCoupons = \App\Models\Coupon::with(['rules'])
        ->where('is_active', true)
        ->get();
        
    $appliedCode = session('coupon_code', '');
@endphp

{{-- ── 1. OFFERS & REWARDS CARD (SCREENSHOT 1) ── --}}
<div class="ttt-offers-rewards-wrap">
    <div class="ttt-offers-head">OFFERS &amp; REWARDS</div>
    <div class="ttt-offers-card">
        {{-- Input Row or Applied Pill --}}
        @if($appliedCode)
            <div class="ttt-coupon-applied-pill-row">
                <div class="ttt-applied-left">
                    <i class="bi bi-patch-check-fill text-success"></i>
                    <span>Coupon <b>{{ $appliedCode }}</b> applied</span>
                </div>
                <button type="button" onclick="removeCouponCode()" class="ttt-remove-coupon-btn">REMOVE</button>
            </div>
        @else
            <div class="ttt-coupon-input-box">
                <input type="text" id="couponCodeInput" placeholder="Enter coupon code" autocomplete="off">
                <button type="button" class="ttt-coupon-apply-btn" onclick="applyCouponCode()">APPLY</button>
            </div>
        @endif

        {{-- Available Coupons Row with View All --}}
        <div class="ttt-offers-mid-row">
            <div class="ttt-offers-left" onclick="openCouponsModal()">
                <span class="ttt-badge-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2l2.4 2.4 3.4-.6 1.2 3.2 3.2 1.2-.6 3.4 2.4 2.4-2.4 2.4.6 3.4-3.2 1.2-1.2 3.2-3.4-.6-2.4 2.4-2.4-2.4-3.4.6-1.2-3.2-3.2-1.2.6-3.4-2.4-2.4 2.4-2.4-.6-3.4 3.2-1.2 1.2-3.2 3.4.6 2.4-2.4z"/>
                        <line x1="9" y1="15" x2="15" y2="9"/>
                        <circle cx="9.5" cy="9.5" r=".7" fill="#475569"/>
                        <circle cx="14.5" cy="14.5" r=".7" fill="#475569"/>
                    </svg>
                </span>
                <span class="ttt-available-text">
                    <span id="availableCouponsCount">{{ $allCoupons->count() }}</span> coupons available
                </span>
            </div>
            <button type="button" class="ttt-view-all-btn" onclick="openCouponsModal()">View All</button>
        </div>

        {{-- Loyalty Points Row --}}
        <div class="ttt-loyalty-row">
            <span class="ttt-loyalty-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#526071" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2l2.4 2.4 3.4-.6 1.2 3.2 3.2 1.2-.6 3.4 2.4 2.4-2.4 2.4.6 3.4-3.2 1.2-1.2 3.2-3.4-.6-2.4 2.4-2.4-2.4-3.4.6-1.2-3.2-3.2-1.2.6-3.4-2.4-2.4 2.4-2.4-.6-3.4 3.2-1.2 1.2-3.2 3.4.6 2.4-2.4z"/>
                    <polygon points="12 8 13.2 10.8 16 11.2 14 13.1 14.5 16 12 14.6 9.5 16 10 13.1 8 11.2 10.8 10.8 12 8" fill="#526071" stroke="#526071" stroke-width="0.5"/>
                </svg>
            </span>
            <span class="ttt-loyalty-text">
                You're earning <b id="loyaltyPointsCount">{{ $loyaltyPoints }} loyalty points</b> on this order
            </span>
        </div>
    </div>
</div>

{{-- ── 2. COUPONS & OFFERS BOTTOM SHEET MODAL (SCREENSHOT 2 / media_1788341030416.png) ── --}}
<div class="ttt-coupon-modal-backdrop" id="couponModalBackdrop" onclick="closeCouponsModal()"></div>
<div class="ttt-coupon-bottom-sheet" id="couponBottomSheet" role="dialog" aria-modal="true" aria-labelledby="couponSheetTitle">
    {{-- Floating Circle Close Button --}}
    <button type="button" class="ttt-sheet-close-btn" onclick="closeCouponsModal()" aria-label="Close modal">
        <i class="bi bi-x-lg"></i>
    </button>

    <div class="ttt-sheet-inner">
        {{-- Modal Header --}}
        <div class="ttt-sheet-header">
            <span class="ttt-sheet-head-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2l2.4 2.4 3.4-.6 1.2 3.2 3.2 1.2-.6 3.4 2.4 2.4-2.4 2.4.6 3.4-3.2 1.2-1.2 3.2-3.4-.6-2.4 2.4-2.4-2.4-3.4.6-1.2-3.2-3.2-1.2.6-3.4-2.4-2.4 2.4-2.4-.6-3.4 3.2-1.2 1.2-3.2 3.4.6 2.4-2.4z"/>
                    <line x1="9" y1="15" x2="15" y2="9"/>
                    <circle cx="9.5" cy="9.5" r=".7" fill="#475569"/>
                    <circle cx="14.5" cy="14.5" r=".7" fill="#475569"/>
                </svg>
            </span>
            <h3 class="ttt-sheet-title" id="couponSheetTitle">Coupons &amp; Offers</h3>
            <button type="button" class="ttt-header-close-btn" onclick="closeCouponsModal()" aria-label="Close modal">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        {{-- Input Field --}}
        <div class="ttt-sheet-input-row">
            <input type="text" id="modalCouponInput" placeholder="Enter coupon code" value="{{ $appliedCode }}" autocomplete="off">
            <button type="button" class="ttt-sheet-apply-btn" onclick="applyModalCoupon()">Apply</button>
        </div>

        {{-- Filter Tabs --}}
        <div class="ttt-sheet-tabs">
            <button type="button" class="ttt-tab-pill active" onclick="filterModalCoupons('all', this)">Active Coupons</button>
            <button type="button" class="ttt-tab-pill" onclick="filterModalCoupons('payment', this)">Payment Offers</button>
            <button type="button" class="ttt-tab-pill" onclick="filterModalCoupons('other', this)">Other Coupons</button>
        </div>

        {{-- Section Subheading --}}
        <div class="ttt-sheet-section-title" id="couponCategoryTitle">Brand Offers</div>

        {{-- Coupons List Container --}}
        <div class="ttt-coupons-cards-list" id="modalCouponsList">
            @foreach($allCoupons as $cpn)
                @php
                    $rules = $cpn->rules;
                    $minSpend = $rules ? (float)$rules->min_order_amount : 0;
                    $isEligible = ($subtotalVal >= $minSpend);
                    
                    if ($subtotalVal > 0) {
                        if ($cpn->type === 'percentage') {
                            $saving = ($subtotalVal * (float)$cpn->value) / 100;
                            if ($rules && $rules->max_discount_amount > 0) {
                                $saving = min($saving, (float)$rules->max_discount_amount);
                            }
                        } else {
                            $saving = min($subtotalVal, (float)$cpn->value);
                        }
                    } else {
                        $saving = $cpn->type === 'percentage' ? 130 : round((float)$cpn->value);
                    }
                    $shortage = max(0, $minSpend - $subtotalVal);
                    $isPaymentOffer = ($cpn->code === 'PREPAID5');
                @endphp
                
                <div class="ttt-coupon-card-item {{ $isPaymentOffer ? 'cat-payment' : 'cat-brand' }}" data-category="{{ $isPaymentOffer ? 'payment' : 'brand' }}">
                    @if(!$isEligible && $shortage > 0)
                        <div class="ttt-threshold-warning-bar">
                            Add item worth ₹{{ number_format(round($shortage)) }} amount more to avail
                        </div>
                    @endif
                    <div class="ttt-coupon-card-main">
                        <div class="ttt-coupon-card-left">
                            <span class="ttt-card-percent-badge">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2l2.4 2.4 3.4-.6 1.2 3.2 3.2 1.2-.6 3.4 2.4 2.4-2.4 2.4.6 3.4-3.2 1.2-1.2 3.2-3.4-.6-2.4 2.4-2.4-2.4-3.4.6-1.2-3.2-3.2-1.2.6-3.4-2.4-2.4 2.4-2.4-.6-3.4 3.2-1.2 1.2-3.2 3.4.6 2.4-2.4z"/>
                                    <line x1="9" y1="15" x2="15" y2="9"/>
                                    <circle cx="9.5" cy="9.5" r=".7" fill="#475569"/>
                                    <circle cx="14.5" cy="14.5" r=".7" fill="#475569"/>
                                </svg>
                            </span>
                            <div class="ttt-card-body-text">
                                <div class="ttt-card-title-line">
                                    @if($saving > 0 && $isEligible)
                                        <span>Save ₹{{ number_format($saving) }} with</span>
                                    @endif
                                    <span class="ttt-code-dashed-pill">{{ $cpn->code }}</span>
                                </div>
                                <div class="ttt-card-sub-line">
                                    @if($minSpend > 0 && !$isEligible)
                                        Add items worth {{ number_format($minSpend) }} to unlock {{ $cpn->type === 'percentage' ? (int)$cpn->value.'% off' : '₹'.(int)$cpn->value.' off' }} with code {{ $cpn->code }}
                                    @else
                                        {{ $cpn->description ?: 'Applicable on Prepaid & COD orders. T&C Apply' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <button type="button" class="ttt-card-apply-btn" onclick="applyCouponCodeSpecific('{{ $cpn->code }}')">
                            {{ $appliedCode === $cpn->code ? 'Applied' : 'Apply' }}
                        </button>
                    </div>

                    @if(!$isEligible && $shortage > 0)
                        <div class="ttt-card-eligible-btn-wrap">
                            <button type="button" class="ttt-sheet-explore-btn" onclick="closeCouponsModal(); window.location.href='{{ route('shop.index') }}';">
                                Show me eligible products <i class="bi bi-chevron-down"></i>
                            </button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
/* ═══════════════════════════════════════════════════════════════════
   OFFERS & REWARDS CARD (SCREENSHOT 1)
   ═══════════════════════════════════════════════════════════════════ */
.ttt-offers-rewards-wrap {
    margin-bottom: 20px;
    font-family: -apple-system, BlinkMacSystemFont, "Plus Jakarta Sans", "Segoe UI", Roboto, sans-serif !important;
}

.ttt-offers-head {
    font-size: 11px;
    font-weight: 800;
    color: #64748b;
    letter-spacing: 0.6px;
    margin-bottom: 8px;
    text-transform: uppercase;
}

.ttt-offers-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
}

.ttt-coupon-applied-pill-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #ecfdf5;
    border: 1.5px solid #a7f3d0;
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 12px;
}

.ttt-applied-left {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 700;
    color: #065f46;
}

.ttt-remove-coupon-btn {
    background: none;
    border: none;
    color: #ef4444;
    font-size: 11.5px;
    font-weight: 800;
    cursor: pointer;
}

.ttt-coupon-input-box {
    display: flex;
    align-items: center;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    overflow: hidden;
    background: #ffffff;
    margin-bottom: 12px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.ttt-coupon-input-box:focus-within {
    border-color: #00285a;
    box-shadow: 0 0 0 2px rgba(0, 40, 90, 0.08);
}

.ttt-coupon-input-box input {
    flex: 1;
    height: 40px;
    border: none;
    outline: none;
    padding: 0 14px;
    font-size: 13px;
    color: #0f172a;
    font-weight: 600;
    background: transparent;
    text-transform: uppercase;
}

.ttt-coupon-input-box input::placeholder {
    color: #9ca3af;
    text-transform: none;
    font-weight: 400;
}

.ttt-coupon-apply-btn {
    height: 40px;
    padding: 0 16px;
    background: none;
    border: none;
    color: #00285a;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: color 0.15s ease;
}

.ttt-offers-mid-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 12px;
    border-bottom: 1px solid #f3f4f6;
}

.ttt-offers-left {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.ttt-badge-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.ttt-available-text {
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
}

.ttt-view-all-btn {
    background: none;
    border: none;
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
    cursor: pointer;
    padding: 0;
    transition: color 0.15s ease;
}

.ttt-loyalty-row {
    display: flex;
    align-items: center;
    gap: 8px;
    padding-top: 10px;
    font-size: 12px;
    color: #92400e;
    font-weight: 600;
}

.ttt-loyalty-icon {
    width: 20px;
    height: 20px;
    border: 1.5px solid #d97706;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    color: #d97706;
    flex-shrink: 0;
}

.ttt-loyalty-text b {
    color: #b45309;
    font-weight: 800;
}

/* ═══════════════════════════════════════════════════════════════════
   COUPONS & OFFERS BOTTOM SHEET MODAL (SCREENSHOT 2 / EXACT MATCH)
   ═══════════════════════════════════════════════════════════════════ */
.ttt-coupon-modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    z-index: 125000;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.ttt-coupon-modal-backdrop.is-open {
    opacity: 1;
    visibility: visible;
}

.ttt-coupon-bottom-sheet {
    position: fixed;
    left: 50%;
    bottom: 0;
    transform: translate(-50%, 100%);
    width: 100%;
    max-width: 480px;
    max-height: 88vh;
    background: #ffffff;
    border-radius: 20px 20px 0 0;
    box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.25);
    z-index: 126000;
    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    display: flex;
    flex-direction: column;
    overflow: visible;
    font-family: -apple-system, BlinkMacSystemFont, "Plus Jakarta Sans", "Segoe UI", Roboto, sans-serif !important;
}

.ttt-coupon-bottom-sheet.is-open {
    transform: translate(-50%, 0);
}

.ttt-sheet-close-btn {
    position: absolute;
    top: -24px;
    left: 50%;
    transform: translateX(-50%);
    width: 46px;
    height: 46px;
    background: #dce5d6;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #1e293b;
    cursor: pointer;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    transition: all 0.2s ease;
    z-index: 126001;
}

.ttt-sheet-inner {
    padding: 22px 18px 24px;
    overflow-y: auto;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 12px;
    border-radius: 20px 20px 0 0;
    background: #ffffff;
}

.ttt-sheet-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 2px;
}

.ttt-sheet-head-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.ttt-sheet-title {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.ttt-header-close-btn {
    background: #f1f5f9;
    border: none;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    color: #475569;
    cursor: pointer;
    margin-left: auto;
    transition: all 0.2s ease;
}

.ttt-sheet-input-row {
    display: flex;
    align-items: center;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    overflow: hidden;
    background: #ffffff;
    margin-bottom: 2px;
}

.ttt-sheet-input-row:focus-within {
    border-color: #00285a;
    box-shadow: 0 0 0 2px rgba(0, 40, 90, 0.08);
}

.ttt-sheet-input-row input {
    flex: 1;
    height: 42px;
    border: none;
    outline: none;
    padding: 0 14px;
    font-size: 13.5px;
    color: #0f172a;
    font-weight: 600;
    text-transform: uppercase;
}

.ttt-sheet-input-row input::placeholder {
    color: #9ca3af;
    text-transform: none;
    font-weight: 400;
}

.ttt-sheet-apply-btn {
    height: 42px;
    padding: 0 18px;
    background: none;
    border: none;
    font-size: 13px;
    font-weight: 800;
    color: #00285a;
    cursor: pointer;
    transition: color 0.15s ease;
}

/* Tabs */
.ttt-sheet-tabs {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding-bottom: 2px;
    margin-bottom: 4px;
}

.ttt-tab-pill {
    padding: 6px 14px;
    background: #f1f5f9;
    border: none;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.15s ease;
}

.ttt-tab-pill.active {
    background: #e2e8f0;
    color: #0f172a;
    font-weight: 700;
}

.ttt-sheet-section-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 2px;
}

/* Coupon Cards List */
.ttt-coupons-cards-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.ttt-coupon-card-item {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.ttt-threshold-warning-bar {
    background: #fff1f2;
    color: #b91c1c;
    padding: 8px 14px;
    font-size: 11.5px;
    font-weight: 700;
    text-align: center;
    border-bottom: 1px solid #fee2e2;
}

.ttt-coupon-card-main {
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.ttt-coupon-card-left {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    flex: 1;
}

.ttt-card-percent-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-top: 2px;
    flex-shrink: 0;
}

.ttt-card-body-text {
    flex: 1;
}

.ttt-card-title-line {
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 4px;
}

.ttt-code-dashed-pill {
    display: inline-block;
    border: 1px dashed #d1d5db;
    background: #f9fafb;
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 12.5px;
    font-weight: 700;
    letter-spacing: 0.5px;
    color: #0f172a;
}

.ttt-card-sub-line {
    font-size: 11.5px;
    color: #6b7280;
    line-height: 1.4;
}

.ttt-card-apply-btn {
    padding: 7px 18px;
    background: #ffffff;
    border: 1.5px solid #0f172a;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 700;
    color: #0f172a;
    cursor: pointer;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.ttt-card-eligible-btn-wrap {
    padding: 0 14px 14px;
}

.ttt-sheet-explore-btn {
    width: 100%;
    height: 40px;
    background: #059669;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: pointer;
    transition: background 0.15s ease;
}
</style>

<script>
function openCouponsModal() {
    const sheet = document.getElementById('couponBottomSheet');
    const backdrop = document.getElementById('couponModalBackdrop');
    if (sheet && backdrop) {
        sheet.classList.add('is-open');
        backdrop.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }
}

function closeCouponsModal() {
    const sheet = document.getElementById('couponBottomSheet');
    const backdrop = document.getElementById('couponModalBackdrop');
    if (sheet && backdrop) {
        sheet.classList.remove('is-open');
        backdrop.classList.remove('is-open');
        document.body.style.overflow = '';
    }
}

function filterModalCoupons(cat, btn) {
    document.querySelectorAll('.ttt-tab-pill').forEach(el => el.classList.remove('active'));
    if (btn) btn.classList.add('active');
    
    const title = document.getElementById('couponCategoryTitle');
    if (title) {
        if (cat === 'payment') title.textContent = 'Payment Offers';
        else if (cat === 'other') title.textContent = 'Other Coupons';
        else title.textContent = 'Brand Offers';
    }

    document.querySelectorAll('.ttt-coupon-card-item').forEach(card => {
        const cardCat = card.getAttribute('data-category');
        if (cat === 'all' || cardCat === cat) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function applyCouponCodeSpecific(code) {
    const input = document.getElementById('couponCodeInput');
    if (input) input.value = code;
    applyCouponCode(code);
}

function applyModalCoupon() {
    const code = document.getElementById('modalCouponInput').value.trim();
    if (!code) {
        alert('Please enter a coupon code.');
        return;
    }
    applyCouponCode(code);
}

function applyCouponCode(forcedCode) {
    const code = forcedCode || (document.getElementById('couponCodeInput') ? document.getElementById('couponCodeInput').value.trim() : '');
    if (!code) {
        alert('Please enter a coupon code.');
        return;
    }
    
    fetch('{{ route("coupon.apply") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ code: code, coupon_code: code })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            closeCouponsModal();
            window.location.reload();
        } else {
            alert(data.message || 'Invalid coupon code.');
        }
    })
    .catch(() => {
        alert('Failed to apply coupon. Please try again.');
    });
}

function removeCouponCode() {
    fetch('{{ route("coupon.remove") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        window.location.reload();
    })
    .catch(() => {
        window.location.reload();
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const cardInp = document.getElementById('couponCodeInput');
    if (cardInp) {
        cardInp.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyCouponCode();
            }
        });
    }
    const modalInp = document.getElementById('modalCouponInput');
    if (modalInp) {
        modalInp.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyModalCoupon();
            }
        });
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCouponsModal();
        }
    });
});
</script>
