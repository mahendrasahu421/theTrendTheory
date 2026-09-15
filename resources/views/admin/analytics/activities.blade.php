@extends('admin.layouts.app')

@section('title', 'Customer Activity Stream & Direct Outreach')

@section('content')
<div class="ttt-activity-root">

    {{-- ── 1. Top Hero Banner ── --}}
    <div class="activity-hero-banner">
        <div class="hero-text-wrap">
            <div class="hero-tag">
                <span class="hero-live-dot"></span>
                <span>CUSTOMER INTENT &amp; OUTREACH HUB</span>
            </div>
            <h1 class="hero-title">Live Action Stream &amp; Outreach</h1>
            <p class="hero-subtitle">
                Track customer events in real-time (product views, bag additions, checkout drop-offs) and connect instantly via WhatsApp or Email.
            </p>
        </div>

        <div class="hero-nav-pill-group">
            <a href="{{ route('admin.analytics.index') }}" class="btn-hero-nav">
                <i class="bi bi-geo-alt-fill"></i> Visitor Traffic &amp; Geo
            </a>
            <a href="{{ route('admin.analytics.activities') }}" class="btn-hero-nav active">
                <i class="bi bi-activity"></i> Live Activity Feed
            </a>
        </div>
    </div>

    {{-- ── 2. Event Filter Pills ── --}}
    <div class="event-filter-ribbon">
        <a href="{{ route('admin.analytics.activities') }}" class="event-filter-btn {{ !$eventType ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> All Events <span class="badge-count">{{ $eventCounts['all'] }}</span>
        </a>
        <a href="{{ route('admin.analytics.activities', ['event_type' => 'checkout_started']) }}" class="event-filter-btn {{ $eventType === 'checkout_started' ? 'active' : '' }}">
            <i class="bi bi-cart-x-fill text-amber"></i> Checkout Drop-Offs <span class="badge-count bg-amber-badge">{{ $eventCounts['checkout_started'] }}</span>
        </a>
        <a href="{{ route('admin.analytics.activities', ['event_type' => 'cart_added']) }}" class="event-filter-btn {{ $eventType === 'cart_added' ? 'active' : '' }}">
            <i class="bi bi-bag-plus-fill text-indigo"></i> Added to Bag <span class="badge-count bg-indigo-badge">{{ $eventCounts['cart_added'] }}</span>
        </a>
        <a href="{{ route('admin.analytics.activities', ['event_type' => 'product_viewed']) }}" class="event-filter-btn {{ $eventType === 'product_viewed' ? 'active' : '' }}">
            <i class="bi bi-eye-fill text-blue"></i> Product Views <span class="badge-count bg-blue-badge">{{ $eventCounts['product_viewed'] }}</span>
        </a>
        <a href="{{ route('admin.analytics.activities', ['event_type' => 'order_placed']) }}" class="event-filter-btn {{ $eventType === 'order_placed' ? 'active' : '' }}">
            <i class="bi bi-check-circle-fill text-emerald"></i> Orders Placed <span class="badge-count bg-emerald-badge">{{ $eventCounts['order_placed'] }}</span>
        </a>
    </div>

    {{-- ── 3. Main Stream Card ── --}}
    <div class="premium-card">
        {{-- Filter Row --}}
        <div class="stream-filter-bar">
            <form method="GET" action="{{ route('admin.analytics.activities') }}" class="stream-search-form">
                @if($eventType)
                    <input type="hidden" name="event_type" value="{{ $eventType }}">
                @endif
                <div class="search-input-box">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search customer, phone, email, product, city or IP..." class="input-styled">
                </div>
                <button type="submit" class="btn-primary-gradient btn-sm">Search</button>
                @if($search || $eventType)
                    <a href="{{ route('admin.analytics.activities') }}" class="btn-clear-filter">
                        <i class="bi bi-x-circle"></i> Clear
                    </a>
                @endif
            </form>

            <div class="stream-stats-text">
                Showing <b>{{ $activities->total() }}</b> activity events
            </div>
        </div>

        {{-- Timeline Stream List --}}
        <div class="stream-list">
            @forelse($activities as $act)
                @php
                    $u = $act->user;
                    $phone = $u ? $u->phone : null;
                    $cleanPhone = $phone ? preg_replace('/[^0-9]/', '', $phone) : '';
                    if ($cleanPhone && strlen($cleanPhone) === 10) {
                        $cleanPhone = '91' . $cleanPhone;
                    }

                    // Smart auto-draft message for WhatsApp
                    $waText = "Hi " . ($u ? $u->name : 'there') . "! Welcome to Vayu.";
                    if ($act->event_type === 'checkout_started') {
                        $waText = "Hi " . ($u ? $u->name : '') . "! We noticed you left items in your shopping bag at Vayu. Complete your order today and get an EXTRA 10% OFF with code TREND10: " . url('/checkout');
                    } elseif ($act->event_type === 'cart_added') {
                        $waText = "Hi " . ($u ? $u->name : '') . "! Thank you for adding items to your bag at Vayu. Need any help with size or fast delivery? We are here to help: " . url('/cart');
                    } elseif ($act->event_type === 'product_viewed') {
                        $waText = "Hi " . ($u ? $u->name : '') . "! We saw you checking out our streetwear drop at Vayu. Let us know if you need any assistance with styling or sizing: " . ($act->url ?: url('/shop'));
                    }
                @endphp

                <div class="stream-item-row {{ $act->event_type === 'checkout_started' ? 'highlight-abandoned' : '' }}">
                    
                    {{-- Col 1: Icon & Relative Time --}}
                    <div class="stream-col-time">
                        <div class="stream-icon-box {{ $act->event_badge_class }}">
                            <i class="bi {{ $act->event_icon }}"></i>
                        </div>
                        <div class="stream-time-text">
                            <strong>{{ $act->created_at->diffForHumans() }}</strong>
                            <small>{{ $act->created_at->format('M d, h:i A') }}</small>
                        </div>
                    </div>

                    {{-- Col 2: Event Details & Context Tags --}}
                    <div class="stream-col-info">
                        <div class="stream-title-line">
                            <span class="event-type-badge {{ $act->event_badge_class }}">
                                {{ $act->event_label }}
                            </span>
                            <h4 class="event-title-text">{{ $act->event_title }}</h4>
                        </div>

                        {{-- Metadata Pills --}}
                        <div class="stream-meta-pills">
                            @if($u)
                                <span class="meta-tag tag-user">
                                    <i class="bi bi-person-check-fill text-emerald"></i>
                                    <b>{{ $u->name }}</b> ({{ $u->phone ?: $u->email }})
                                </span>
                            @else
                                <span class="meta-tag tag-guest">
                                    <i class="bi bi-person-circle"></i> Guest ({{ $act->ip_address }})
                                </span>
                            @endif

                            <span class="meta-tag">
                                <i class="bi bi-geo-alt-fill text-rose"></i> {{ $act->city ?: 'India' }}, {{ $act->state ?: '' }} 🇮🇳
                            </span>

                            <span class="meta-tag tag-source">
                                <i class="bi bi-share"></i> {{ $act->source ?: 'Direct' }}
                            </span>

                            <span class="meta-tag">
                                <i class="bi bi-phone text-indigo"></i> {{ $act->device_brand ?: 'Device' }} {{ $act->device_model ? '(' . $act->device_model . ')' : '' }}
                            </span>
                        </div>

                        {{-- Event Data Preview (JSON Items) --}}
                        @if(!empty($act->event_details))
                            <div class="stream-details-box">
                                @if(isset($act->event_details['price']))
                                    <span>Price: <b>₹{{ number_format($act->event_details['price']) }}</b></span>
                                @endif
                                @if(isset($act->event_details['size']) && $act->event_details['size'])
                                    <span>Size: <b>{{ $act->event_details['size'] }}</b></span>
                                @endif
                                @if(isset($act->event_details['total']))
                                    <span>Cart Total: <b class="text-emerald">₹{{ number_format($act->event_details['total']) }}</b></span>
                                @endif
                                @if(isset($act->event_details['items_count']))
                                    <span>Items: <b>{{ $act->event_details['items_count'] }}</b></span>
                                @endif
                            </div>
                        @endif

                        @if($act->contacted_at)
                            <div class="contacted-note">
                                <i class="bi bi-check2-all text-emerald"></i> Contacted via <b>{{ strtoupper($act->contacted_channel) }}</b> {{ $act->contacted_at->diffForHumans() }}
                            </div>
                        @endif
                    </div>

                    {{-- Col 3: Direct Outreach Buttons --}}
                    <div class="stream-col-actions">
                        @if($cleanPhone)
                            <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode($waText) }}" 
                               target="_blank" 
                               class="btn-outreach btn-whatsapp-outreach" 
                               title="Chat on WhatsApp">
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </a>
                        @else
                            <button type="button" class="btn-outreach btn-disabled" disabled>
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </button>
                        @endif

                        @if($u && $u->email)
                            <button type="button" 
                                    class="btn-outreach btn-email-outreach" 
                                    onclick="openEmailModal('{{ $u->id }}', '{{ $u->name }}', '{{ $u->email }}', '{{ $act->id }}', '{{ addslashes($act->event_title) }}')">
                                <i class="bi bi-envelope-fill"></i> Send Email
                            </button>
                        @else
                            <button type="button" class="btn-outreach btn-disabled" disabled>
                                <i class="bi bi-envelope"></i> Send Email
                            </button>
                        @endif
                    </div>

                </div>
            @empty
                <div class="empty-stream-state">
                    <i class="bi bi-inbox text-muted" style="font-size:36px;display:block;margin-bottom:10px;"></i>
                    <h4>No activity logs found</h4>
                    <p class="text-muted">User activities like product views, additions to bag, and checkout drop-offs will appear here in real time.</p>
                </div>
            @endforelse
        </div>

        @if($activities->hasPages())
            <div class="table-pagination-footer">
                {{ $activities->links() }}
            </div>
        @endif
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
                window.location.reload();
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

.activity-hero-banner {
    background: linear-gradient(135deg, #00285a 0%, #1e3a8a 50%, #0f172a 100%);
    border-radius: 20px;
    padding: 28px 32px;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 24px;
    box-shadow: 0 10px 30px rgba(0, 40, 90, 0.15);
}

.hero-nav-pill-group {
    display: flex;
    gap: 8px;
}

.btn-hero-nav {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-hero-nav.active, .btn-hero-nav:hover {
    background: #ffffff;
    color: #00285a;
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

/* Stream List */
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

.stream-time-text strong {
    font-size: 12px;
    color: #0f172a;
    display: block;
}

.stream-time-text small {
    font-size: 10.5px;
    color: #94a3b8;
}

.stream-col-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.stream-title-line {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.event-type-badge {
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 2px 7px;
    border-radius: 999px;
}

.event-title-text {
    font-size: 13.5px;
    font-weight: 800;
    color: #00285a;
    margin: 0;
}

.stream-meta-pills {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.meta-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #f1f5f9;
    color: #475569;
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 6px;
}

.tag-user { background: #ecfdf5; color: #047857; }
.tag-guest { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }
.tag-source { background: #eff6ff; color: #1d4ed8; }

.stream-details-box {
    display: flex;
    gap: 12px;
    font-size: 11px;
    color: #64748b;
    background: rgba(0, 40, 90, 0.03);
    padding: 3px 8px;
    border-radius: 4px;
    width: fit-content;
}

.contacted-note {
    font-size: 11px;
    color: #059669;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Outreach Buttons */
.stream-col-actions {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.btn-outreach {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
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

@media (max-width: 900px) {
    .stream-item-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    .stream-col-actions {
        flex-direction: row;
    }
}
</style>
@endsection
