(function (w, d) {
    var cfg = w.TTT_CART_CHECKOUT || {};
    var CSRF = cfg.csrf || (d.querySelector('meta[name="csrf-token"]') || {}).content;
    var lastCart = cfg.cart || {};

    function byId(id) { return d.getElementById(id); }
    function setText(id, text) {
        var el = byId(id);
        if (el) el.textContent = text;
    }
    function money(value) {
        return '\u20b9' + Math.round(Number(value || 0)).toLocaleString('en-IN');
    }
    function escapeHtml(value) {
        return String(value || '').replace(/[&<>"']/g, function (m) {
            return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[m];
        });
    }

    function normalizeCart(cart) {
        cart = cart || {};
        cart.items = cart.items || [];
        cart.subtotal = Number(cart.subtotal || 0);
        cart.shipping = Number(cart.shipping || 0);
        cart.discount = Number(cart.discount || 0);
        cart.savings = Number(cart.savings || cart.discount || 0);
        cart.count = Number(cart.count || cart.items.reduce(function (sum, item) {
            return sum + Number(item.quantity || 1);
        }, 0));
        cart.total = Number(cart.total || Math.max(0, cart.subtotal + cart.shipping - cart.discount));
        return cart;
    }

    function renderCheckout(cart) {
        cart = normalizeCart(cart || lastCart);
        lastCart = cart;

        var prepaid = Math.round(cart.subtotal * 0.05 * 100) / 100;
        var displayTotal = Math.max(0, cart.total - prepaid);
        var rewardLine = d.querySelector('.cart-reward-line i');

        setText('coItemCount', cart.count + ' item' + (cart.count === 1 ? '' : 's'));
        setText('coTotalTop', money(displayTotal));
        setText('coTotalBottom', money(displayTotal));
        setText('coShipping', cart.shipping === 0 ? 'FREE' : money(cart.shipping));
        setText('coSavings', money(cart.savings) + ' saved so far');
        setText('coPoints', Math.max(1, Math.floor(cart.total / 100)) + ' loyalty points');
        setText('coDiscountLine', cart.savings > 0 ? 'You saved ' + money(cart.savings) : 'Best price applied');
        setText('coRewardText', cart.total >= 9999 ? 'You have unlocked all rewards!' : 'Add items worth ' + money(Math.max(0, 9999 - cart.total)) + ' more to unlock 20% off');

        if (rewardLine) rewardLine.style.width = Math.min(100, Math.max(8, (cart.total / 9999) * 100)) + '%';
        renderOrderItems(cart);
        renderTotalBreakdown(cart);
    }

    function renderOrderItems(cart) {
        var wrap = byId('coOrderItems');
        if (!wrap) return;

        wrap.innerHTML = (cart.items || []).map(function (item, index) {
            var qty = Number(item.quantity || 1);
            var price = Number(item.price || 0);
            var meta = [item.size ? 'Size: ' + item.size : '', item.color ? 'Color: ' + item.color : ''].filter(Boolean).join(' | ');

            return '<div class="checkout-order-item" data-cart-index="' + index + '">' +
                '<img src="' + escapeHtml(item.image || '') + '" alt="">' +
                '<div class="checkout-order-main">' +
                    '<div class="checkout-order-name">' + escapeHtml(item.name || 'Product') + '</div>' +
                    '<div class="checkout-order-meta">' + escapeHtml(meta || 'Qty: ' + qty) + '</div>' +
                '</div>' +
                '<div class="checkout-order-side">' +
                    '<div class="checkout-order-price" id="coItemPrice' + index + '">' + money(price * qty) + '<small>' + money(price) + ' each</small></div>' +
                    '<div class="checkout-drawer-qty"><button type="button" onclick="changeDrawerQty(' + index + ',-1)">-</button><span id="coItemQty' + index + '">' + qty + '</span><button type="button" onclick="changeDrawerQty(' + index + ',1)">+</button></div>' +
                '</div>' +
            '</div>';
        }).join('');
    }

    function renderTotalBreakdown(cart) {
        var prepaid = Math.round(cart.subtotal * 0.05 * 100) / 100;
        var mrp = cart.subtotal + cart.savings;
        var estimated = Math.max(0, cart.total - prepaid);

        setText('coBreakSaved', money(cart.savings) + ' saved so far');
        setText('coMrpTotal', money(mrp));
        setText('coMrpDiscount', money(cart.savings));
        setText('coCartSubtotal', money(cart.subtotal));
        setText('coTotalDiscount', money(cart.discount));
        setText('coPrepaidDiscount', money(prepaid));
        setText('coBreakShipping', cart.shipping === 0 ? 'FREE' : money(cart.shipping));
        setText('coTotalSavings', money(cart.savings + prepaid));
        setText('coBreakTotal', money(estimated));
    }

    function recalcCart() {
        lastCart.subtotal = (lastCart.items || []).reduce(function (sum, item) {
            return sum + Number(item.price || 0) * Number(item.quantity || 1);
        }, 0);
        lastCart.count = (lastCart.items || []).reduce(function (sum, item) {
            return sum + Number(item.quantity || 1);
        }, 0);
        lastCart.shipping = lastCart.subtotal >= 999 ? 0 : 50;
        lastCart.total = Math.max(0, lastCart.subtotal + lastCart.shipping - Number(lastCart.discount || 0));
        renderCheckout(lastCart);
    }

    function openCartCheckoutPopup() {
        var pop = byId('checkoutPop');
        if (!pop) return;
        renderCheckout(lastCart);
        pop.classList.add('is-open', 'checkout-mode');
        pop.setAttribute('aria-hidden', 'false');
        d.body.style.overflow = 'hidden';
        showCheckoutMain();
    }

    function closeCheckoutPop() {
        var pop = byId('checkoutPop');
        if (!pop) return;
        pop.classList.remove('is-open', 'checkout-mode', 'address-mode');
        pop.setAttribute('aria-hidden', 'true');
        d.body.style.overflow = '';
    }

    function toggleCheckoutSection(name) {
        if (name !== 'order') return;
        var el = byId('coOrderItems');
        if (el) el.hidden = !el.hidden;
    }

    function toggleTotalBreakdown() {
        var panel = byId('coTotalBreakdown');
        var icon = byId('coTotalChevron');
        if (!panel) return;
        panel.hidden = !panel.hidden;
        if (icon) {
            icon.classList.toggle('bi-chevron-up', !panel.hidden);
            icon.classList.toggle('bi-chevron-down', panel.hidden);
        }
    }

    function changeDrawerQty(index, delta) {
        if (!lastCart.items || !lastCart.items[index]) return;
        var item = lastCart.items[index];
        item.quantity = Math.max(1, Math.min(99, Number(item.quantity || 1) + Number(delta || 0)));

        if (item.key && cfg.cartUpdateBaseUrl) {
            fetch(cfg.cartUpdateBaseUrl + '/' + encodeURIComponent(item.key), {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ quantity: item.quantity })
            }).catch(function () {});
        }

        recalcCart();
    }

    function showAddressEditor() {
        var pop = byId('checkoutPop');
        var form = byId('coAddressForm');
        var fields = byId('coAddressFields');
        var pin = byId('coPin');

        if (pop) pop.classList.add('address-mode');
        if (form) form.hidden = false;
        if (fields && pin && pin.value.trim().length >= 5) fields.hidden = false;
        setText('coAddressMsg', '');
    }

    function showCheckoutMain() {
        var pop = byId('checkoutPop');
        var form = byId('coAddressForm');

        if (pop) pop.classList.remove('address-mode');
        if (form) form.hidden = true;
    }

    function unlockAddressFields() {
        var pin = byId('coPin');
        var fields = byId('coAddressFields');
        var msg = byId('coAddressMsg');

        if (!pin || pin.value.trim().length < 5) {
            if (msg) msg.textContent = 'Please enter valid pincode.';
            return;
        }

        if (fields) fields.hidden = false;
        if (msg) msg.textContent = 'Pincode added. Complete your address below.';
    }

    function updatePlaceOrderFields(user) {
        var map = {
            placeName: user.name,
            placePhone: user.phone,
            placeAddress: user.address,
            placeCity: user.city,
            placeState: user.state,
            placePincode: user.pincode
        };

        Object.keys(map).forEach(function (id) {
            var el = byId(id);
            if (el) el.value = map[id] || '';
        });
    }

    function updateAddressPreview(user) {
        var name = d.querySelector('.checkout-address-copy .checkout-card-title');
        var lines = d.querySelectorAll('.checkout-address-copy p');
        if (name) name.textContent = 'Deliver To ' + (user.name || 'Customer');
        if (lines[0]) lines[0].textContent = [user.address, user.city, user.state, user.pincode].filter(Boolean).join(', ') || 'Add your delivery address';
        if (lines[1]) lines[1].textContent = [user.phone, user.email].filter(Boolean).join(' | ');
        updatePlaceOrderFields(user);
    }

    function saveCheckoutAddress() {
        var msg = byId('coAddressMsg');
        var payload = {
            name: (byId('coName') || {}).value || (cfg.user || {}).name || 'Customer',
            phone: (byId('coPhone') || {}).value || '',
            address: (byId('coAddress') || {}).value || '',
            city: (byId('coCity') || {}).value || '',
            state: (byId('coState') || {}).value || '',
            pincode: (byId('coPin') || {}).value || ''
        };

        if (!payload.pincode || !payload.address) {
            if (msg) msg.textContent = 'Pincode and address are required.';
            return;
        }

        cfg.user = Object.assign({}, cfg.user || {}, payload);
        updateAddressPreview(cfg.user);

        if (!cfg.addressUpdateUrl) {
            if (msg) msg.textContent = 'Address updated.';
            setTimeout(showCheckoutMain, 500);
            return;
        }

        if (msg) msg.textContent = 'Saving address...';
        fetch(cfg.addressUpdateUrl, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify(payload)
        }).then(function (r) {
            return r.json();
        }).then(function (res) {
            cfg.user = Object.assign({}, cfg.user || {}, res.user || payload);
            updateAddressPreview(cfg.user);
            if (msg) msg.textContent = 'Address saved.';
            setTimeout(showCheckoutMain, 500);
        }).catch(function () {
            if (msg) msg.textContent = 'Address updated for this order.';
            setTimeout(showCheckoutMain, 700);
        });
    }

    d.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeCheckoutPop();
    });

    w.openCartCheckoutPopup = openCartCheckoutPopup;
    w.closeCheckoutPop = closeCheckoutPop;
    w.toggleCheckoutSection = toggleCheckoutSection;
    w.toggleTotalBreakdown = toggleTotalBreakdown;
    w.changeDrawerQty = changeDrawerQty;
    w.showAddressEditor = showAddressEditor;
    w.showCheckoutMain = showCheckoutMain;
    w.unlockAddressFields = unlockAddressFields;
    w.saveCheckoutAddress = saveCheckoutAddress;
})(window, document);
