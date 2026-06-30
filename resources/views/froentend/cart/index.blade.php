{{-- resources/views/froentend/cart/index.blade.php --}}
@extends('froentend.layouts.app')

@push('seo')
    <title>My Cart | The Trend Theory</title>
    <meta name="robots" content="noindex, nofollow">
@endpush

@section('main')
<style>
.cart-wrap { max-width:1100px; margin:40px auto; padding:0 20px 60px; }
.cart-title { font-family:'Cinzel',serif; font-size:1.6rem; font-weight:700; color:#00285a; letter-spacing:2px; margin-bottom:28px; }
.cart-layout { display:grid; grid-template-columns:1fr 340px; gap:24px; align-items:flex-start; }

/* Cart Items */
.cart-items { display:flex; flex-direction:column; gap:16px; }
.cart-item { background:white; border-radius:16px; border:1px solid #eef2f6; padding:16px; display:flex; gap:16px; align-items:flex-start; }
.cart-item-img { width:90px; height:110px; border-radius:10px; overflow:hidden; flex-shrink:0; }
.cart-item-img img { width:100%; height:100%; object-fit:cover; }
.cart-item-body { flex:1; }
.cart-item-name { font-size:14px; font-weight:700; color:#00285a; margin-bottom:4px; text-decoration:none; display:block; }
.cart-item-name:hover { color:#ff3f6c; }
.cart-item-size { font-size:12px; color:#7a8fa6; margin-bottom:8px; }
.cart-item-price { font-size:16px; font-weight:800; color:#c44536; }
.cart-item-oldprice { font-size:12px; color:#aaa; text-decoration:line-through; margin-left:6px; }
.cart-item-actions { display:flex; align-items:center; gap:12px; margin-top:10px; }
.qty-ctrl { display:flex; align-items:center; border:1.5px solid #e8edf5; border-radius:8px; overflow:hidden; }
.qty-ctrl button { width:32px; height:32px; border:none; background:white; font-size:16px; cursor:pointer; color:#00285a; transition:.15s; }
.qty-ctrl button:hover { background:#f0f4ff; }
.qty-ctrl span { width:36px; text-align:center; font-size:14px; font-weight:700; color:#00285a; }
.remove-btn { background:none; border:none; color:#aaa; font-size:18px; cursor:pointer; transition:.15s; }
.remove-btn:hover { color:#ff3f6c; }

/* Empty cart */
.cart-empty { text-align:center; padding:60px 20px; background:white; border-radius:16px; border:1px solid #eef2f6; }
.cart-empty i { font-size:56px; color:#d9dee6; display:block; margin-bottom:16px; }
.cart-empty h3 { font-family:'Cinzel',serif; font-size:1.2rem; color:#00285a; margin-bottom:8px; }
.cart-empty p { color:#7a8fa6; font-size:14px; margin-bottom:20px; }
.btn-shop { background:#00285a; color:white; padding:12px 32px; border-radius:40px; text-decoration:none; font-weight:600; display:inline-block; }

/* Summary */
.cart-summary { background:white; border-radius:16px; border:1px solid #eef2f6; padding:24px; position:sticky; top:90px; }
.summary-title { font-family:'Cinzel',serif; font-size:1rem; font-weight:700; color:#00285a; letter-spacing:1px; margin-bottom:20px; padding-bottom:12px; border-bottom:1px solid #eef2f6; }
.summary-row { display:flex; justify-content:space-between; font-size:14px; margin-bottom:10px; color:#555; }
.summary-row.free { color:#2e7d32; font-weight:600; }
.summary-row.discount { color:#c44536; font-weight:600; }
.summary-row.total { font-size:17px; font-weight:800; color:#00285a; border-top:1px solid #eef2f6; padding-top:14px; margin-top:4px; }
.coupon-row { display:flex; gap:8px; margin:16px 0; }
.coupon-input { flex:1; padding:9px 14px; border:1.5px solid #e8edf5; border-radius:30px; font-size:13px; outline:none; font-family:monospace; text-transform:uppercase; }
.coupon-input:focus { border-color:#00285a; }
.coupon-btn { padding:9px 18px; background:#00285a; color:white; border:none; border-radius:30px; font-size:13px; font-weight:700; cursor:pointer; transition:.2s; }
.coupon-btn:hover { background:#ff3f6c; }
.coupon-remove-btn { padding:9px 18px; background:#dc3545; color:white; border:none; border-radius:30px; font-size:13px; font-weight:700; cursor:pointer; transition:.2s; display:none; }
.coupon-remove-btn:hover { background:#c82333; }
.coupon-message { font-size:12px; margin-top:8px; padding:8px; border-radius:8px; text-align:center; }
.coupon-message.success { background:#e8f5e9; color:#2e7d32; }
.coupon-message.error { background:#fce4ec; color:#c62828; }
.coupon-message.info { background:#e3f2fd; color:#1565c0; }
.checkout-btn { width:100%; padding:14px; background:linear-gradient(135deg,#00285a,#1e3f75); color:white; border:none; border-radius:50px; font-size:14px; font-weight:700; letter-spacing:1px; cursor:pointer; margin-top:16px; transition:.2s; }
.checkout-btn:hover { background:linear-gradient(135deg,#ff3f6c,#ff6b6b); transform:translateY(-2px); }
.continue-btn { display:block; text-align:center; margin-top:12px; color:#7a8fa6; font-size:13px; text-decoration:none; }
.continue-btn:hover { color:#00285a; }
.free-shipping-note { background:#e8f5e9; color:#2e7d32; border-radius:8px; padding:8px 12px; font-size:12px; font-weight:600; text-align:center; margin-bottom:16px; }
.available-coupons { margin-top:20px; padding-top:16px; border-top:1px solid #eef2f6; }
.available-coupons-title { font-size:12px; font-weight:700; color:#7a8fa6; text-transform:uppercase; letter-spacing:1px; margin-bottom:12px; }
.available-coupon-item { background:#f8fafc; border:1px solid #eef2f6; border-radius:8px; padding:10px; margin-bottom:8px; cursor:pointer; transition:.2s; }
.available-coupon-item:hover { border-color:#00285a; background:#fff; transform:translateX(4px); }
.available-coupon-code { font-size:13px; font-weight:700; color:#00285a; font-family:monospace; letter-spacing:1px; }
.available-coupon-desc { font-size:11px; color:#7a8fa6; margin-top:3px; }
.available-coupon-save { font-size:12px; color:#c44536; font-weight:600; margin-top:3px; }
.available-coupon-restriction { font-size:10px; color:#aaa; margin-top:3px; }

.alert-success { background:#e8f5e9; color:#2e7d32; padding:10px 14px; border-radius:8px; font-size:13px; margin-bottom:16px; }
.alert-error   { background:#fce4ec; color:#c62828; padding:10px 14px; border-radius:8px; font-size:13px; margin-bottom:16px; }

@media(max-width:768px) {
    .cart-layout { grid-template-columns:1fr; }
    .cart-summary { position:static; }
}
</style>

<div class="cart-wrap">
    <h1 class="cart-title">MY CART</h1>

    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    @if(count($cart) > 0)
        <div class="cart-layout">

            {{-- LEFT: Cart Items --}}
            <div class="cart-items" id="cartItemsList">
                @foreach($cart as $key => $item)
                    <div class="cart-item" id="item-{{ $key }}" data-product-id="{{ $item['product_id'] ?? $key }}">
                        <div class="cart-item-img">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" loading="lazy">
                        </div>
                        <div class="cart-item-body">
                            <a href="{{ route('product.show', $item['slug']) }}" class="cart-item-name">
                                {{ $item['name'] }}
                            </a>
                            @if($item['size'] ?? false)
                                <div class="cart-item-size">Size: {{ $item['size'] }}</div>
                            @endif
                            <div>
                                <span class="cart-item-price">₹{{ number_format($item['price']) }}</span>
                            </div>
                            <div class="cart-item-actions">
                                <div class="qty-ctrl">
                                    <button onclick="updateQty('{{ $key }}', -1)">−</button>
                                    <span id="qty-{{ $key }}">{{ $item['quantity'] }}</span>
                                    <button onclick="updateQty('{{ $key }}', 1)">+</button>
                                </div>
                                <span style="font-size:13px;color:#7a8fa6;font-weight:600">
                                    = ₹<span id="subtotal-{{ $key }}">{{ number_format($item['price'] * $item['quantity']) }}</span>
                                </span>
                                <button class="remove-btn" onclick="removeItem('{{ $key }}')" aria-label="Remove">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- RIGHT: Summary with Coupon --}}
            <div class="cart-summary">
                <div class="summary-title">ORDER SUMMARY</div>

                @if($subtotal < 999)
                    <div class="free-shipping-note">
                        Add ₹{{ number_format(999 - $subtotal) }} more for FREE shipping!
                    </div>
                @else
                    <div class="free-shipping-note">
                        🎉 You got FREE shipping!
                    </div>
                @endif

                <div class="summary-row">
                    <span>Subtotal (<span id="totalItems">{{ collect($cart)->sum('quantity') }}</span> items)</span>
                    <span>₹<span id="summarySubtotal">{{ number_format($subtotal) }}</span></span>
                </div>
                <div class="summary-row {{ $shipping == 0 ? 'free' : '' }}">
                    <span>Shipping</span>
                    <span id="summaryShipping">{{ $shipping == 0 ? 'FREE' : '₹'.$shipping }}</span>
                </div>
                
                {{-- Discount Row - Dynamically shown when coupon applied --}}
                <div id="discountRow" class="summary-row discount" style="display: {{ isset($couponDiscount) && $couponDiscount > 0 ? 'flex' : 'none' }}">
                    <span>Discount (<span id="couponCodeDisplay">{{ session('coupon_code', '') }}</span>)</span>
                    <span>-₹<span id="summaryDiscount">{{ number_format($couponDiscount ?? 0) }}</span></span>
                </div>
                
                <div class="summary-row total">
                    <span>Total</span>
                    <span>₹<span id="summaryTotal">{{ number_format($total) }}</span></span>
                </div>

                {{-- Coupon Section --}}
                <div class="coupon-row">
                    <input type="text" class="coupon-input" id="couponInput" 
                           placeholder="Enter coupon code" 
                           value="{{ session('coupon_code', '') }}"
                           {{ session('coupon_code') ? 'disabled' : '' }}>
                    <button class="coupon-btn" id="applyCouponBtn" 
                            onclick="applyCoupon()" 
                            {{ session('coupon_code') ? 'style=display:none' : '' }}>
                        Apply
                    </button>
                    <button class="coupon-remove-btn" id="removeCouponBtn" 
                            onclick="removeCoupon()"
                            {{ session('coupon_code') ? 'style=display:inline-block' : 'style=display:none' }}>
                        Remove
                    </button>
                </div>
                <div id="couponMessage" class="coupon-message" style="display:none"></div>

                {{-- Available Coupons Section --}}
                <div id="availableCouponsSection" class="available-coupons" style="display:none">
                    <div class="available-coupons-title">✨ AVAILABLE COUPONS</div>
                    <div id="availableCouponsList"></div>
                </div>

                <a href="{{ route('checkout.index') }}">
                    <button class="checkout-btn">PROCEED TO CHECKOUT →</button>
                </a>
                <a href="{{ route('shop.index') }}" class="continue-btn">← Continue Shopping</a>
            </div>

        </div>
    @else
        <div class="cart-empty">
            <i class="bi bi-bag-x"></i>
            <h3>Your cart is empty</h3>
            <p>Looks like you haven't added anything yet.</p>
            <a href="{{ route('shop.index') }}" class="btn-shop">START SHOPPING</a>
        </div>
    @endif
</div>

@push('scripts')
<script>
var csrfToken = '{{ csrf_token() }}';
var cartData = @json($cart);
var subtotal = {{ $subtotal }};
var shipping = {{ $shipping }};
var couponApplied = {{ isset($couponDiscount) && $couponDiscount > 0 ? 'true' : 'false' }};
var couponCode = '{{ session('coupon_code', '') }}';

// Update quantity
function updateQty(key, delta) {
    var qtyEl = document.getElementById('qty-' + key);
    var current = parseInt(qtyEl.textContent);
    var newQty = Math.max(1, current + delta);
    qtyEl.textContent = newQty;

    // Update subtotal display
    var price = parseFloat(document.querySelector('#item-' + CSS.escape(key) + ' .cart-item-price')
        .textContent.replace('₹','').replace(/,/g,''));
    document.getElementById('subtotal-' + key).textContent = (price * newQty).toLocaleString('en-IN');

    fetch('/cart/update/' + encodeURIComponent(key), {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify({ quantity: newQty })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            updateCartBadge(data.cart_count);
            refreshSummary();
            // Re-validate coupon after cart update
            if (couponApplied) {
                revalidateCoupon();
            }
        }
    });
}

// Remove item
function removeItem(key) {
    var el = document.getElementById('item-' + key);
    if (el) { el.style.opacity = '0.4'; el.style.pointerEvents = 'none'; }

    fetch('/cart/remove/' + encodeURIComponent(key), {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify({})
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            if (el) el.remove();
            updateCartBadge(data.cart_count);
            
            // If coupon was applied, remove it
            if (couponApplied) {
                removeCoupon();
            }
            
            var remaining = document.querySelectorAll('.cart-item').length;
            if (remaining === 0) location.reload();
            else refreshSummary();
        }
    });
}

// Refresh cart summary
function refreshSummary() {
    var newSubtotal = 0;
    var totalItems = 0;
    document.querySelectorAll('.cart-item').forEach(function(item) {
        var key = item.id.replace('item-', '');
        var qty = parseInt(document.getElementById('qty-' + key).textContent);
        var price = parseFloat(item.querySelector('.cart-item-price').textContent.replace('₹','').replace(/,/g,''));
        newSubtotal += price * qty;
        totalItems += qty;
    });
    
    subtotal = newSubtotal;
    var newShipping = subtotal >= 999 ? 0 : 50;
    shipping = newShipping;
    var newTotal = subtotal + shipping;
    
    document.getElementById('summarySubtotal').textContent = subtotal.toLocaleString('en-IN');
    document.getElementById('summaryShipping').textContent = shipping === 0 ? 'FREE' : '₹' + shipping;
    document.getElementById('totalItems').textContent = totalItems;
    
    // If coupon is applied, recalculate total with discount
    if (couponApplied && couponCode) {
        fetch('/coupon/calculate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ coupon_code: couponCode })
        })
        .then(r => r.json())
        .then(data => {
            if (data.valid) {
                var discount = data.discount;
                var finalTotal = subtotal + shipping - discount;
                document.getElementById('summaryDiscount').textContent = discount.toLocaleString('en-IN');
                document.getElementById('summaryTotal').textContent = finalTotal.toLocaleString('en-IN');
            } else {
                // Coupon no longer valid, remove it
                removeCoupon();
            }
        });
    } else {
        document.getElementById('summaryTotal').textContent = (subtotal + shipping).toLocaleString('en-IN');
    }
}

// Apply coupon
function applyCoupon() {
    var code = document.getElementById('couponInput').value.trim();
    if (!code) {
        showCouponMessage('Please enter a coupon code', 'error');
        return;
    }
    
    showCouponMessage('Applying coupon...', 'info');
    
    fetch('/coupon/apply', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ coupon_code: code })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            couponApplied = true;
            couponCode = code;
            
            // Update UI
            document.getElementById('couponInput').value = code;
            document.getElementById('couponInput').disabled = true;
            document.getElementById('applyCouponBtn').style.display = 'none';
            document.getElementById('removeCouponBtn').style.display = 'inline-block';
            document.getElementById('couponCodeDisplay').textContent = code;
            document.getElementById('summaryDiscount').textContent = data.discount.toLocaleString('en-IN');
            document.getElementById('discountRow').style.display = 'flex';
            
            var finalTotal = subtotal + shipping - data.discount;
            document.getElementById('summaryTotal').textContent = finalTotal.toLocaleString('en-IN');
            
            showCouponMessage(data.message, 'success');
            
            // Refresh available coupons
            loadAvailableCoupons();
        } else {
            showCouponMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showCouponMessage('Error applying coupon. Please try again.', 'error');
    });
}

// Remove coupon
function removeCoupon() {
    showCouponMessage('Removing coupon...', 'info');
    
    fetch('/coupon/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            couponApplied = false;
            couponCode = '';
            
            // Update UI
            document.getElementById('couponInput').value = '';
            document.getElementById('couponInput').disabled = false;
            document.getElementById('applyCouponBtn').style.display = 'inline-block';
            document.getElementById('removeCouponBtn').style.display = 'none';
            document.getElementById('discountRow').style.display = 'none';
            
            var finalTotal = subtotal + shipping;
            document.getElementById('summaryTotal').textContent = finalTotal.toLocaleString('en-IN');
            
            showCouponMessage(data.message, 'success');
            
            // Refresh available coupons
            loadAvailableCoupons();
        } else {
            showCouponMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showCouponMessage('Error removing coupon', 'error');
    });
}

// Revalidate existing coupon (called after cart changes)
function revalidateCoupon() {
    if (!couponCode) return;
    
    fetch('/coupon/check', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ coupon_code: couponCode })
    })
    .then(r => r.json())
    .then(data => {
        if (!data.valid) {
            // Coupon no longer valid, remove it
            removeCoupon();
            showCouponMessage(data.message + ' Coupon has been removed.', 'error');
        } else if (data.discount) {
            // Update discount amount
            var newTotal = subtotal + shipping - data.discount;
            document.getElementById('summaryDiscount').textContent = data.discount.toLocaleString('en-IN');
            document.getElementById('summaryTotal').textContent = newTotal.toLocaleString('en-IN');
        }
    });
}

// Load available coupons
function loadAvailableCoupons() {
    fetch('/coupon/available', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(coupons => {
        var container = document.getElementById('availableCouponsList');
        var section = document.getElementById('availableCouponsSection');
        
        if (coupons && coupons.length > 0 && !couponApplied) {
            container.innerHTML = coupons.map(coupon => `
                <div class="available-coupon-item" onclick="applySpecificCoupon('${coupon.code}')">
                    <div class="available-coupon-code">${coupon.code}</div>
                    <div class="available-coupon-desc">${coupon.description || ''}</div>
                    <div class="available-coupon-save">
                        Save ${coupon.type === 'percent' ? coupon.value + '%' : '₹' + coupon.value.toLocaleString('en-IN')}
                        ${coupon.discount_value ? ' (₹${coupon.discount_value.toLocaleString('en-IN')})' : ''}
                    </div>
                    ${coupon.has_restrictions ? `<div class="available-coupon-restriction">🎯 ${coupon.restricted_to}</div>` : ''}
                    ${coupon.rules?.min_order_amount > 0 ? `<div class="available-coupon-restriction">Min order: ₹${coupon.rules.min_order_amount.toLocaleString('en-IN')}</div>` : ''}
                </div>
            `).join('');
            section.style.display = 'block';
        } else {
            container.innerHTML = '';
            section.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error loading coupons:', error);
    });
}

// Apply specific coupon from available list
function applySpecificCoupon(code) {
    document.getElementById('couponInput').value = code;
    applyCoupon();
}

// Show coupon message
function showCouponMessage(message, type) {
    var msgDiv = document.getElementById('couponMessage');
    msgDiv.textContent = message;
    msgDiv.className = 'coupon-message ' + type;
    msgDiv.style.display = 'block';
    
    setTimeout(function() {
        msgDiv.style.display = 'none';
    }, 5000);
}

// Update cart badge
function updateCartBadge(count) {
    var badge = document.getElementById('cart-count');
    if (badge) badge.textContent = count;
}

// Auto-check coupon validity when typing (optional)
let couponCheckTimeout;
function checkCouponOnType() {
    var code = document.getElementById('couponInput').value.trim();
    if (!code || couponApplied) return;
    
    clearTimeout(couponCheckTimeout);
    couponCheckTimeout = setTimeout(function() {
        fetch('/coupon/check', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ coupon_code: code })
        })
        .then(r => r.json())
        .then(data => {
            if (data.valid && data.discount) {
                showCouponMessage(`✓ Valid! You'll save ₹${data.discount.toLocaleString('en-IN')}`, 'success');
            } else if (!data.valid) {
                showCouponMessage(data.message, 'error');
            }
        });
    }, 500);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    var couponInput = document.getElementById('couponInput');
    if (couponInput && !couponApplied) {
        couponInput.addEventListener('input', checkCouponOnType);
    }
    
    // Load available coupons
    loadAvailableCoupons();
});

// Make functions global for onclick handlers
window.updateQty = updateQty;
window.removeItem = removeItem;
window.applyCoupon = applyCoupon;
window.removeCoupon = removeCoupon;
window.applySpecificCoupon = applySpecificCoupon;
</script>
@endpush
@endsection