@extends('admin.layouts.app')

@section('title', 'Customer Activity Stream & Direct Outreach')

@section('content')
<div class="ttt-activity-root">

    {{-- ── 1. Clean Page Header ── --}}
    <div class="activity-clean-header">
        <div>
            <h1 class="activity-clean-title">Live Action Stream &amp; Outreach</h1>
            <p class="activity-clean-sub">Track customer events in real-time (product views, bag additions, checkout drop-offs) and connect instantly via WhatsApp or Email.</p>
        </div>
    </div>

    {{-- ── 2. Event Filter Pills ── --}}
    <div class="event-filter-ribbon">
        <a href="{{ route('admin.analytics.activities') }}" 
           class="event-filter-btn {{ !$eventType ? 'active' : '' }}"
           data-event-type=""
           onclick="event.preventDefault(); selectEventType('');">
            <i class="bi bi-grid-fill"></i> All Events <span class="badge-count" id="countAll">{{ $eventCounts['all'] }}</span>
        </a>
        <a href="{{ route('admin.analytics.activities', ['event_type' => 'checkout_started']) }}" 
           class="event-filter-btn {{ $eventType === 'checkout_started' ? 'active' : '' }}"
           data-event-type="checkout_started"
           onclick="event.preventDefault(); selectEventType('checkout_started');">
            <i class="bi bi-cart-x-fill text-amber"></i> Checkout Drop-Offs <span class="badge-count bg-amber-badge" id="countCheckout">{{ $eventCounts['checkout_started'] }}</span>
        </a>
        <a href="{{ route('admin.analytics.activities', ['event_type' => 'cart_added']) }}" 
           class="event-filter-btn {{ $eventType === 'cart_added' ? 'active' : '' }}"
           data-event-type="cart_added"
           onclick="event.preventDefault(); selectEventType('cart_added');">
            <i class="bi bi-bag-plus-fill text-indigo"></i> Added to Bag <span class="badge-count bg-indigo-badge" id="countCart">{{ $eventCounts['cart_added'] }}</span>
        </a>
        <a href="{{ route('admin.analytics.activities', ['event_type' => 'product_viewed']) }}" 
           class="event-filter-btn {{ $eventType === 'product_viewed' ? 'active' : '' }}"
           data-event-type="product_viewed"
           onclick="event.preventDefault(); selectEventType('product_viewed');">
            <i class="bi bi-eye-fill text-blue"></i> Product Views <span class="badge-count bg-blue-badge" id="countProduct">{{ $eventCounts['product_viewed'] }}</span>
        </a>
        <a href="{{ route('admin.analytics.activities', ['event_type' => 'order_placed']) }}" 
           class="event-filter-btn {{ $eventType === 'order_placed' ? 'active' : '' }}"
           data-event-type="order_placed"
           onclick="event.preventDefault(); selectEventType('order_placed');">
            <i class="bi bi-check-circle-fill text-emerald"></i> Orders Placed <span class="badge-count bg-emerald-badge" id="countOrder">{{ $eventCounts['order_placed'] }}</span>
        </a>
    </div>

    {{-- ── 3. Main Stream Card ── --}}
    <div class="premium-card">
        {{-- Loading Overlay --}}
        <div class="table-loading-overlay" id="activitiesLoadingOverlay" style="display: none;">
            <div class="spinner-border text-primary" role="status" style="width: 2.2rem; height: 2.2rem; color: #00285a !important;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        {{-- Filter Row --}}
        <div class="stream-filter-bar">
            <form id="activitiesFilterForm" onsubmit="handleActivitiesSearch(event)" class="stream-search-form">
                <input type="hidden" name="event_type" id="filterEventType" value="{{ $eventType }}">
                <div class="search-input-box">
                    <i class="bi bi-search"></i>
                    <input type="text" 
                           name="search" 
                           id="activitiesSearchInput" 
                           value="{{ $search }}" 
                           placeholder="Search customer, phone, email, product, city or IP..." 
                           class="input-styled"
                           oninput="handleSearchDebounce(this.value)">
                </div>
                <button type="submit" class="btn-primary-gradient btn-sm">Search</button>
                <button type="button" 
                        id="btnResetFilters" 
                        class="btn-clear-filter" 
                        onclick="resetAllFilters()" 
                        style="{{ ($search || $eventType) ? '' : 'display:none;' }}">
                    <i class="bi bi-x-circle"></i> Clear
                </button>
            </form>

            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="per-page-wrap">
                    <span class="font-xs text-muted fw-bold">Per page:</span>
                    <select id="activitiesPerPageSelect" class="per-page-select" onchange="handlePerPageChange(this.value)">
                        <option value="15" {{ ($perPage ?? 30) == 15 ? 'selected' : '' }}>15</option>
                        <option value="30" {{ ($perPage ?? 30) == 30 ? 'selected' : '' }}>30</option>
                        <option value="50" {{ ($perPage ?? 30) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ ($perPage ?? 30) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>

                <div class="stream-stats-text" id="activitiesShowingStats">
                    Showing <b>{{ $activities->firstItem() ?? 0 }}–{{ $activities->lastItem() ?? 0 }}</b> of <b>{{ $activities->total() }}</b> activity events
                </div>
            </div>
        </div>

        {{-- Timeline Stream List --}}
        <div class="stream-list" id="activitiesStreamList">
            @include('admin.analytics.partials.activities_list', ['activities' => $activities])
        </div>

        {{-- Luxury Pagination Footer --}}
        <div class="table-pagination-footer" id="activitiesPaginationContainer" style="{{ $activities->hasPages() || $activities->total() > 0 ? '' : 'display:none;' }}">
            <div class="stream-showing-footer" id="activitiesShowingFooter">
                Showing <strong>{{ $activities->firstItem() ?? 0 }}</strong> to <strong>{{ $activities->lastItem() ?? 0 }}</strong> of <strong>{{ $activities->total() }}</strong> activities
            </div>
            <div id="activitiesPaginationWrap">
                @include('admin.analytics.partials.pagination', ['activities' => $activities])
            </div>
        </div>
    </div>

</div>

{{-- ── Email Outreach Modal ── --}}
<div class="admin-modal-backdrop" id="emailModal" style="display:none;">
    <div class="admin-modal-card">
        <div class="admin-modal-header">
            <h3><i class="bi bi-envelope-paper-fill text-indigo"></i> Send Direct Customer Notification</h3>
            <button type="button" onclick="closeEmailModal()" class="close-btn">&times;</button>
        </div>
        <form id="emailOutreachForm" onsubmit="handleSendEmail(event)">
            @csrf
            <input type="hidden" name="user_id" id="modalUserId">
            <input type="hidden" name="activity_id" id="modalActivityId">
            <input type="hidden" name="channel" value="email">

            <div class="admin-modal-body">
                <div class="form-row-group mb-3">
                    <label class="field-label">Recipient Customer:</label>
                    <input type="text" id="modalRecipientDisplay" class="input-styled" readonly style="background:#f8fafc;font-weight:700;">
                    <input type="hidden" name="email" id="modalUserEmail">
                </div>

                <div class="form-row-group mb-3">
                    <label class="field-label">Quick Template:</label>
                    <select class="input-styled" id="emailTemplateSelect" onchange="applyEmailTemplate(this.value)">
                        <option value="custom">Custom Message</option>
                        <option value="cart_recovery">Cart Recovery (10% Extra Discount Promo)</option>
                        <option value="welcome_offer">Welcome to Vayu (VIP Drops)</option>
                        <option value="styling_help">Sizing &amp; Styling Support</option>
                    </select>
                </div>

                <div class="form-row-group mb-3">
                    <label class="field-label">Email Subject:</label>
                    <input type="text" name="subject" id="modalEmailSubject" class="input-styled" required value="Special Update from Vayu">
                </div>

                <div class="form-row-group mb-3">
                    <label class="field-label">Message Content:</label>
                    <textarea name="message" id="modalEmailMessage" rows="5" class="input-styled text-area-styled" required></textarea>
                </div>

                <div id="emailSendStatus" style="display:none;font-size:12.5px;font-weight:700;margin-top:8px;"></div>
            </div>

            <div class="admin-modal-footer">
                <button type="button" class="btn-clear-filter" onclick="closeEmailModal()">Cancel</button>
                <button type="submit" class="btn-primary-gradient btn-sm" id="emailSubmitBtn">
                    <i class="bi bi-send-fill"></i> Send Email
                </button>
            </div>
        </form>
    </div>
</div>

<script>
var currentCustomerName = '';
var activitiesAjaxUrl = "{{ route('admin.analytics.activities') }}";
var searchDebounceTimer = null;
var activitiesRequestSeq = 0;

function showActivitiesLoader() {
    var overlay = document.getElementById('activitiesLoadingOverlay');
    if (overlay) overlay.style.display = 'flex';
}

function hideActivitiesLoader() {
    var overlay = document.getElementById('activitiesLoadingOverlay');
    if (overlay) overlay.style.display = 'none';
}

function scrollToTableTop() {
    var card = document.querySelector('.premium-card');
    if (card) {
        var cardTop = card.getBoundingClientRect().top + window.pageYOffset - 80;
        if (window.pageYOffset > cardTop + 40) {
            window.scrollTo({ top: cardTop, behavior: 'smooth' });
        }
    }
}

function buildActivitiesParams(page) {
    var params = new URLSearchParams();
    if (page) params.set('page', page);

    var eventType = document.getElementById('filterEventType') ? document.getElementById('filterEventType').value : '';
    if (eventType) params.set('event_type', eventType);

    var search = document.getElementById('activitiesSearchInput') ? document.getElementById('activitiesSearchInput').value.trim() : '';
    if (search) params.set('search', search);

    var perPage = document.getElementById('activitiesPerPageSelect') ? document.getElementById('activitiesPerPageSelect').value : '30';
    if (perPage && perPage !== '30') params.set('per_page', perPage);

    return params;
}

function loadActivitiesUrl(url, pushState) {
    if (typeof pushState === 'undefined') pushState = true;
    showActivitiesLoader();
    var currentSeq = ++activitiesRequestSeq;

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(function(res) {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
    })
    .then(function(data) {
        if (currentSeq !== activitiesRequestSeq) return;
        hideActivitiesLoader();

        if (data.success) {
            // 1. Update Stream List HTML
            var listEl = document.getElementById('activitiesStreamList');
            if (listEl) listEl.innerHTML = data.list_html;

            // 2. Update Pagination HTML
            var pagWrap = document.getElementById('activitiesPaginationWrap');
            if (pagWrap) {
                pagWrap.innerHTML = data.pagination_html || '';
            }

            // 3. Update Showing Info & Stats
            var footerEl = document.getElementById('activitiesShowingFooter');
            if (footerEl && data.showing_footer) {
                footerEl.innerHTML = data.showing_footer;
            }
            var statsEl = document.getElementById('activitiesShowingStats');
            if (statsEl && data.showing_text) {
                statsEl.innerHTML = data.showing_text;
            }

            var pagContainer = document.getElementById('activitiesPaginationContainer');
            if (pagContainer) {
                pagContainer.style.display = (data.total_count > 0) ? 'flex' : 'none';
            }

            // 4. Update Event Counts in Ribbon
            if (data.event_counts) {
                if (document.getElementById('countAll')) document.getElementById('countAll').textContent = data.event_counts.all;
                if (document.getElementById('countCheckout')) document.getElementById('countCheckout').textContent = data.event_counts.checkout_started;
                if (document.getElementById('countCart')) document.getElementById('countCart').textContent = data.event_counts.cart_added;
                if (document.getElementById('countProduct')) document.getElementById('countProduct').textContent = data.event_counts.product_viewed;
                if (document.getElementById('countOrder')) document.getElementById('countOrder').textContent = data.event_counts.order_placed;
            }

            // 5. Update Clear button visibility
            var hasSearch = document.getElementById('activitiesSearchInput') && document.getElementById('activitiesSearchInput').value.trim() !== '';
            var hasEvent = document.getElementById('filterEventType') && document.getElementById('filterEventType').value !== '';
            var clearBtn = document.getElementById('btnResetFilters');
            if (clearBtn) {
                clearBtn.style.display = (hasSearch || hasEvent) ? 'inline-flex' : 'none';
            }

            // 6. Push history state
            if (pushState) {
                window.history.pushState({}, '', url);
            }

            // 7. Scroll to table top
            scrollToTableTop();
        }
    })
    .catch(function(err) {
        if (currentSeq !== activitiesRequestSeq) return;
        hideActivitiesLoader();
        console.error('Activities AJAX load error:', err);
    });
}

function fetchActivitiesPage(page, pushState) {
    if (typeof pushState === 'undefined') pushState = true;
    var params = buildActivitiesParams(page);
    var url = activitiesAjaxUrl + (params.toString() ? '?' + params.toString() : '');
    loadActivitiesUrl(url, pushState);
}

function handleActivitiesSearch(e) {
    if (e) e.preventDefault();
    if (searchDebounceTimer) clearTimeout(searchDebounceTimer);
    fetchActivitiesPage(1);
}

function handleSearchDebounce(val) {
    if (searchDebounceTimer) clearTimeout(searchDebounceTimer);
    searchDebounceTimer = setTimeout(function() {
        fetchActivitiesPage(1);
    }, 350);
}

function handlePerPageChange(val) {
    fetchActivitiesPage(1);
}

function selectEventType(eventType) {
    document.getElementById('filterEventType').value = eventType;
    document.querySelectorAll('.event-filter-btn').forEach(function(btn) {
        var btnType = btn.getAttribute('data-event-type') || '';
        if (btnType === eventType) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
    fetchActivitiesPage(1);
}

function resetAllFilters() {
    if (document.getElementById('activitiesSearchInput')) {
        document.getElementById('activitiesSearchInput').value = '';
    }
    if (document.getElementById('filterEventType')) {
        document.getElementById('filterEventType').value = '';
    }
    document.querySelectorAll('.event-filter-btn').forEach(function(btn) {
        var btnType = btn.getAttribute('data-event-type') || '';
        if (btnType === '') {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
    fetchActivitiesPage(1);
}

// Handle Back / Forward Browser History
window.addEventListener('popstate', function() {
    var params = new URLSearchParams(window.location.search);
    var eventType = params.get('event_type') || '';
    var search = params.get('search') || '';
    var perPage = params.get('per_page') || '30';

    if (document.getElementById('filterEventType')) document.getElementById('filterEventType').value = eventType;
    if (document.getElementById('activitiesSearchInput')) document.getElementById('activitiesSearchInput').value = search;
    if (document.getElementById('activitiesPerPageSelect')) document.getElementById('activitiesPerPageSelect').value = perPage;

    document.querySelectorAll('.event-filter-btn').forEach(function(btn) {
        var btnType = btn.getAttribute('data-event-type') || '';
        if (btnType === eventType) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    loadActivitiesUrl(window.location.href, false);
});

// Email modal logic
function openEmailModal(userId, userName, userEmail, activityId, activityTitle) {
    currentCustomerName = userName || 'Valued Customer';
    document.getElementById('modalUserId').value = userId || '';
    document.getElementById('modalActivityId').value = activityId || '';
    document.getElementById('modalUserEmail').value = userEmail || '';
    document.getElementById('modalRecipientDisplay').value = (userName || 'Customer') + ' (' + userEmail + ')';
    
    if (activityTitle.toLowerCase().includes('checkout') || activityTitle.toLowerCase().includes('bag')) {
        document.getElementById('emailTemplateSelect').value = 'cart_recovery';
        applyEmailTemplate('cart_recovery');
    } else {
        document.getElementById('emailTemplateSelect').value = 'welcome_offer';
        applyEmailTemplate('welcome_offer');
    }

    document.getElementById('emailSendStatus').style.display = 'none';
    document.getElementById('emailModal').style.display = 'flex';
}

function closeEmailModal() {
    document.getElementById('emailModal').style.display = 'none';
}

function applyEmailTemplate(type) {
    var sub = document.getElementById('modalEmailSubject');
    var msg = document.getElementById('modalEmailMessage');
    var name = currentCustomerName || 'there';

    if (type === 'cart_recovery') {
        sub.value = "You left something stylish behind! Enjoy 10% OFF at Vayu";
        msg.value = "Hi " + name + ",\n\nWe noticed you left some exclusive pieces in your shopping bag. Complete your order today and use code TREND10 to enjoy an extra 10% instant discount at checkout!\n\nFinish your order here: " + window.location.origin + "/checkout\n\nWarm regards,\nThe Trend Theory Team";
    } else if (type === 'welcome_offer') {
        sub.value = "Welcome to Vayu — Exclusive VIP drops for you";
        msg.value = "Hi " + name + ",\n\nWelcome to Vayu. We craft premium oversized streetwear and modern luxury fits.\n\nExplore our latest arrivals and signature collection online: " + window.location.origin + "/shop\n\nNeed any help? Reply directly to this email or connect with us on WhatsApp anytime!\n\nBest,\nThe Trend Theory Crew";
    } else if (type === 'styling_help') {
        sub.value = "Can we help you find the perfect size & fit?";
        msg.value = "Hi " + name + ",\n\nWe saw you exploring our collection. If you have any questions regarding sizing, heavyweight cotton fabric, or express delivery, our styling team is ready to assist you!\n\nFeel free to reply to this email anytime.\n\nThe Trend Theory Team";
    }
}

function handleSendEmail(e) {
    e.preventDefault();
    var form = document.getElementById('emailOutreachForm');
    var btn = document.getElementById('emailSubmitBtn');
    var status = document.getElementById('emailSendStatus');
    var origHtml = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Sending...';
    status.style.display = 'none';

    var formData = new FormData(form);

    fetch('{{ route('admin.analytics.notify') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        btn.disabled = false;
        btn.innerHTML = origHtml;
        status.style.display = 'block';

        if (data.success) {
            status.style.color = '#059669';
            status.innerHTML = '<i class="bi bi-check-circle-fill"></i> ' + data.message;
            setTimeout(function() {
                closeEmailModal();
                loadActivitiesUrl(window.location.href, false);
            }, 1200);
        } else {
            status.style.color = '#dc2626';
            status.innerHTML = '<i class="bi bi-exclamation-circle-fill"></i> ' + (data.message || 'Error sending email');
        }
    })
    .catch(function() {
        btn.disabled = false;
        btn.innerHTML = origHtml;
        status.style.display = 'block';
        status.style.color = '#dc2626';
        status.innerHTML = '<i class="bi bi-exclamation-circle-fill"></i> Network error.';
    });
}
</script>

<style>
/* ─── Luxury Activity Stream Styles ─── */
.ttt-activity-root {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.activity-clean-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 4px;
}

.activity-clean-title {
    font-size: 22px;
    font-weight: 800;
    color: #00285a;
    margin: 0;
    letter-spacing: -0.3px;
}

.activity-clean-sub {
    font-size: 13px;
    color: #64748b;
    margin: 3px 0 0 0;
}

/* Ribbon Filters */
.event-filter-ribbon {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.event-filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 16px;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    font-size: 12.5px;
    font-weight: 700;
    color: #475569;
    text-decoration: none;
    transition: all 0.15s ease;
    cursor: pointer;
}

.event-filter-btn:hover {
    border-color: #00285a;
    color: #00285a;
}

.event-filter-btn.active {
    background: #00285a;
    color: #ffffff !important;
    border-color: #00285a;
    box-shadow: 0 4px 12px rgba(0, 40, 90, 0.2);
}

.badge-count {
    font-size: 10px;
    font-weight: 800;
    padding: 2px 7px;
    border-radius: 999px;
    background: #f1f5f9;
    color: #475569;
}

.event-filter-btn.active .badge-count {
    background: rgba(255, 255, 255, 0.25);
    color: #ffffff;
}

.bg-amber-badge { background: #fef3c7; color: #b45309; }
.bg-indigo-badge { background: #e0e7ff; color: #4338ca; }
.bg-blue-badge { background: #eff6ff; color: #1d4ed8; }
.bg-emerald-badge { background: #d1fae5; color: #047857; }

/* Stream List & Main Card */
.premium-card {
    position: relative;
    min-height: 250px;
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px rgba(0, 40, 90, 0.04);
    overflow: hidden;
}

.table-loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.78);
    backdrop-filter: blur(2px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 50;
    border-radius: 16px;
    transition: opacity 0.2s ease;
}

.stream-filter-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 14px;
    padding: 14px 20px;
    background: #f8fafc;
    border-bottom: 1px solid #eef2f6;
}

.stream-search-form {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 1;
    max-width: 500px;
}

.per-page-wrap {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.per-page-select {
    padding: 4px 10px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #00285a;
    background-color: #ffffff;
    cursor: pointer;
    outline: none;
}
.per-page-select:focus {
    border-color: #00285a;
}

.stream-stats-text {
    font-size: 12px;
    color: #64748b;
}

.stream-list {
    display: flex;
    flex-direction: column;
}

.stream-item-row {
    display: grid;
    grid-template-columns: 140px 1fr 200px;
    gap: 18px;
    padding: 18px 20px;
    align-items: center;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.15s ease;
}

.stream-item-row:hover {
    background: #f8fafc;
}

.stream-item-row.highlight-abandoned {
    background: #fffdf5;
    border-left: 4px solid #f59e0b;
}

.stream-col-time {
    display: flex;
    align-items: center;
    gap: 12px;
}

.stream-icon-box {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.badge-checkout_started { background: #fef3c7; color: #b45309; }
.badge-cart_added { background: #e0e7ff; color: #4338ca; }
.badge-product_viewed { background: #eff6ff; color: #1d4ed8; }
.badge-order_placed { background: #d1fae5; color: #047857; }

.stream-time-text strong {
    display: block;
    font-size: 12.5px;
    color: #1e293b;
}

.stream-time-text small {
    font-size: 11px;
    color: #94a3b8;
}

/* Col Info */
.stream-title-line {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 6px;
}

.event-type-badge {
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 3px 8px;
    border-radius: 6px;
}

.event-title-text {
    font-size: 14px;
    font-weight: 700;
    color: #00285a;
    margin: 0;
}

.stream-meta-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 4px;
}

.meta-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    background: #f1f5f9;
    color: #475569;
    padding: 3px 8px;
    border-radius: 6px;
}

.meta-tag.tag-user {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.meta-tag.tag-guest {
    background: #f8fafc;
    color: #64748b;
    border: 1px solid #e2e8f0;
}

.stream-details-box {
    display: flex;
    gap: 12px;
    margin-top: 6px;
    font-size: 11.5px;
    color: #64748b;
    background: #f8fafc;
    padding: 4px 10px;
    border-radius: 6px;
    width: fit-content;
}

.contacted-note {
    margin-top: 6px;
    font-size: 11.5px;
    color: #059669;
    font-weight: 600;
}

/* Actions */
.stream-col-actions {
    display: flex;
    flex-direction: column;
    gap: 6px;
    align-items: flex-end;
}

.btn-outreach {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    width: 130px;
    padding: 7px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-whatsapp-outreach {
    background: #25d366;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(37, 211, 102, 0.25);
}

.btn-whatsapp-outreach:hover {
    background: #1ebc59;
    transform: translateY(-1px);
}

.btn-email-outreach {
    background: #00285a;
    color: #ffffff;
}

.btn-email-outreach:hover {
    background: #1e3f75;
    transform: translateY(-1px);
}

.btn-disabled {
    background: #f1f5f9;
    color: #94a3b8;
    cursor: not-allowed;
}

.empty-stream-state {
    padding: 50px 20px;
    text-align: center;
}

/* Luxury Pagination Footer */
.table-pagination-footer {
    padding: 16px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid #eef2f6;
    background: #ffffff;
    flex-wrap: wrap;
    gap: 14px;
}

.stream-showing-footer {
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
}

.stream-showing-footer strong {
    color: #00285a;
    font-weight: 700;
}

.custom-pagination-wrap {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-left: auto;
}

.page-nav-btn {
    min-width: 34px;
    height: 34px;
    padding: 0 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #334155;
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.18s ease;
    user-select: none;
}

.page-nav-btn:hover:not(.disabled):not(.active) {
    background: #f1f5f9;
    color: #00285a;
    border-color: #cbd5e1;
    transform: translateY(-1px);
}

.page-nav-btn.active {
    background: #00285a !important;
    color: #ffffff !important;
    border-color: #00285a !important;
    box-shadow: 0 4px 12px rgba(0, 40, 90, 0.25);
}

.page-nav-btn.disabled {
    background: #f8fafc;
    color: #cbd5e1;
    border-color: #f1f5f9;
    cursor: not-allowed;
}

@media (max-width: 900px) {
    .stream-item-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    .stream-col-actions {
        flex-direction: row;
        align-items: flex-start;
    }
    .table-pagination-footer {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .custom-pagination-wrap {
        margin-left: 0;
        flex-wrap: wrap;
        justify-content: center;
    }
}
</style>
@endsection
