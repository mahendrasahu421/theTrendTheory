@extends('admin.layouts.app')

@section('title', 'Marketing & Automated Notifications')

@section('content')
<div class="ttt-notif-root">

    {{-- ── 1. Top Header Banner ── --}}
    <div class="notif-hero-banner">
        <div class="notif-hero-content">
            <div class="hero-tag">
                <span class="hero-pulse"></span>
                <span>CUSTOMER ENGAGEMENT &amp; AUTOMATION</span>
            </div>
            <h1 class="hero-title">Smart Notification Engine</h1>
            <p class="hero-desc">
                Engage visitors &amp; buyers automatically. Send targeted broadcasts, recover abandoned carts every 2–3 hours, and notify users about new drops &amp; offers.
            </p>
        </div>

        {{-- Top KPI Cards --}}
        <div class="hero-kpis">
            <div class="kpi-mini-card">
                <div class="kpi-icon-square bg-indigo-subtle">
                    <i class="bi bi-send-fill text-indigo"></i>
                </div>
                <div class="kpi-meta">
                    <span class="kpi-caption">Total Sent</span>
                    <strong class="kpi-number">{{ number_format($totalSent) }}</strong>
                </div>
            </div>

            <div class="kpi-mini-card">
                <div class="kpi-icon-square bg-amber-subtle">
                    <i class="bi bi-bag-x-fill text-amber"></i>
                </div>
                <div class="kpi-meta">
                    <span class="kpi-caption">Cart Recoveries</span>
                    <strong class="kpi-number text-amber">{{ number_format($cartCount) }}</strong>
                </div>
            </div>

            <div class="kpi-mini-card">
                <div class="kpi-icon-square bg-emerald-subtle">
                    <i class="bi bi-robot text-emerald"></i>
                </div>
                <div class="kpi-meta">
                    <span class="kpi-caption">Auto-Triggers</span>
                    <strong class="kpi-number text-emerald">{{ number_format($autoCount) }}</strong>
                </div>
            </div>

            <div class="kpi-mini-card">
                <div class="kpi-icon-square" style="background:#f3e8ff;">
                    <i class="bi bi-broadcast-pin text-purple"></i>
                </div>
                <div class="kpi-meta">
                    <span class="kpi-caption">Push Subscribers</span>
                    <strong class="kpi-number text-purple">{{ number_format($pushSubscribersCount ?? 0) }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 2. Navigation Tabs Bar ── --}}
    <div class="notif-pill-tabs">
        <a href="{{ route('admin.notifications.index', ['tab' => 'broadcast']) }}" 
           class="notif-tab-item {{ $tab === 'broadcast' ? 'active' : '' }}">
            <i class="bi bi-megaphone-fill"></i>
            <span>Create Broadcast</span>
        </a>

        <a href="{{ route('admin.notifications.index', ['tab' => 'automations']) }}" 
           class="notif-tab-item {{ $tab === 'automations' ? 'active' : '' }}">
            <i class="bi bi-gear-wide-connected"></i>
            <span>Automation Workflows &amp; Rules</span>
            @if($settings['auto_new_product'] || $settings['auto_abandoned_cart'])
                <span class="tab-badge-active">ACTIVE</span>
            @endif
        </a>

        <a href="{{ route('admin.notifications.index', ['tab' => 'logs']) }}" 
           class="notif-tab-item {{ $tab === 'logs' ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            <span>Delivery Logs &amp; History</span>
        </a>
    </div>

    {{-- ═══════════════════════════════════════════════════
         TAB 1: CREATE & BROADCAST NOTIFICATION
         ═══════════════════════════════════════════════════ --}}
    @if($tab === 'broadcast')
    <div class="broadcast-layout">
        
        {{-- Composer Form Card --}}
        <div class="premium-card form-pane">
            <div class="premium-card-head">
                <div class="head-title-wrap">
                    <i class="bi bi-pencil-square text-indigo"></i>
                    <div>
                        <h3>Compose New Notification</h3>
                        <p>Configure message, delivery channels, and audience targeting</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.notifications.store') }}" class="composer-form" id="notifForm">
                @csrf

                {{-- Target Audience Selector --}}
                <div class="form-row-group">
                    <label class="field-label">
                        <span>Target Audience</span>
                        <span class="req-star">*</span>
                    </label>
                    <div class="audience-grid-selector">
                        <label class="audience-radio-card">
                            <input type="radio" name="target_audience" value="all" checked onchange="toggleSpecificUser(this.value)">
                            <div class="radio-card-content">
                                <i class="bi bi-people-fill text-indigo"></i>
                                <div>
                                    <strong>All Customers</strong>
                                    <small>General website broadcast</small>
                                </div>
                            </div>
                        </label>

                        <label class="audience-radio-card">
                            <input type="radio" name="target_audience" value="cart_abandoned" onchange="toggleSpecificUser(this.value)">
                            <div class="radio-card-content">
                                <i class="bi bi-cart-x-fill text-amber"></i>
                                <div>
                                    <strong>Cart Drop-Offs</strong>
                                    <small>Users with items in bag</small>
                                </div>
                            </div>
                        </label>

                        <label class="audience-radio-card">
                            <input type="radio" name="target_audience" value="inactive" onchange="toggleSpecificUser(this.value)">
                            <div class="radio-card-content">
                                <i class="bi bi-compass-fill text-emerald"></i>
                                <div>
                                    <strong>Inactive Signups</strong>
                                    <small>Registered with 0 orders</small>
                                </div>
                            </div>
                        </label>

                        <label class="audience-radio-card">
                            <input type="radio" name="target_audience" value="buyers" onchange="toggleSpecificUser(this.value)">
                            <div class="radio-card-content">
                                <i class="bi bi-gem text-purple"></i>
                                <div>
                                    <strong>VIP Buyers</strong>
                                    <small>Existing customers</small>
                                </div>
                            </div>
                        </label>

                        <label class="audience-radio-card">
                            <input type="radio" name="target_audience" value="single_user" onchange="toggleSpecificUser(this.value)">
                            <div class="radio-card-content">
                                <i class="bi bi-person-fill text-blue"></i>
                                <div>
                                    <strong>Single Customer</strong>
                                    <small>Specific individual</small>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Specific User Dropdown (conditional) --}}
                <div class="form-row-group" id="specificUserWrap" style="display:none;">
                    <label class="field-label">Select Specific Customer</label>
                    <select name="specific_user_id" class="input-styled">
                        <option value="">-- Choose Customer --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->email ?: $c->phone }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Notification Title --}}
                <div class="form-row-group">
                    <label class="field-label">
                        <span>Notification Title</span>
                        <span class="req-star">*</span>
                    </label>
                    <input type="text" name="title" id="inpTitle" class="input-styled" 
                           placeholder="e.g. 🔥 New Drop Alert: Acid Wash Heavyweight Tee" 
                           required oninput="updateLivePreview()">
                </div>

                {{-- Notification Message --}}
                <div class="form-row-group">
                    <label class="field-label">
                        <span>Message Description</span>
                        <span class="req-star">*</span>
                    </label>
                    <textarea name="message" id="inpMessage" rows="3" class="input-styled text-area-styled" 
                              placeholder="Discover our new luxury streetwear collection crafted in heavy 240 GSM cotton. Limited pieces in stock!" 
                              required oninput="updateLivePreview()"></textarea>
                </div>

                {{-- Action URL & Button Label --}}
                <div class="form-grid-2">
                    <div class="form-row-group">
                        <label class="field-label">Action Link URL</label>
                        <input type="text" name="action_url" id="inpUrl" class="input-styled" 
                               placeholder="e.g. /shop or /checkout" value="{{ url('/shop') }}">
                    </div>
                    <div class="form-row-group">
                        <label class="field-label">Button Text</label>
                        <input type="text" name="action_label" id="inpBtnText" class="input-styled" 
                               placeholder="e.g. Shop Now" value="Shop Now" oninput="updateLivePreview()">
                    </div>
                </div>

                {{-- Banner Image URL (Optional) --}}
                <div class="form-row-group">
                    <label class="field-label">
                        <span>Banner Image URL</span>
                        <small class="text-muted">(Optional product/promo image)</small>
                    </label>
                    <input type="text" name="image_url" id="inpImage" class="input-styled" 
                           placeholder="https://images.unsplash.com/... or /images/..." oninput="updateLivePreview()">
                </div>

                {{-- Channels Checkboxes --}}
                <div class="form-row-group">
                    <label class="field-label">Notification Channels</label>
                    <div class="channels-picker">
                        <label class="channel-check-pill active">
                            <input type="checkbox" name="channels[]" value="in_app" checked disabled>
                            <i class="bi bi-bell-fill text-indigo"></i>
                            <span>In-App Website Bell</span>
                        </label>
                        <label class="channel-check-pill">
                            <input type="checkbox" name="channels[]" value="web_push" checked>
                            <i class="bi bi-broadcast text-purple"></i>
                            <span>Web Browser Push Notification</span>
                        </label>
                        <label class="channel-check-pill">
                            <input type="checkbox" name="channels[]" value="email" checked>
                            <i class="bi bi-envelope-fill text-rose"></i>
                            <span>Direct Email Notification</span>
                        </label>
                    </div>
                </div>

                <div class="form-submit-row">
                    <button type="submit" class="btn-primary-gradient">
                        <i class="bi bi-send-fill"></i>
                        <span>Send Broadcast Now</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Live Realistic Preview Column --}}
        <div class="preview-pane">
            <div class="preview-sticky-card">
                <div class="preview-header">
                    <i class="bi bi-phone"></i>
                    <span>Live Customer View Preview</span>
                </div>
                
                <div class="phone-mockup-frame">
                    <div class="phone-notch"></div>
                    <div class="phone-screen">
                        <div class="mockup-site-header">
                            <span class="mock-brand-title">Vayu</span>
                            <div class="mock-bell-icon">
                                <i class="bi bi-bell-fill"></i>
                                <span class="mock-badge">1</span>
                            </div>
                        </div>

                        {{-- Floating Notification Card --}}
                        <div class="live-mockup-notif-box">
                            <div class="mock-notif-top">
                                <div class="mock-notif-origin">
                                    <span class="mock-origin-dot"></span>
                                    <span>TREND THEORY DROPS</span>
                                </div>
                                <span class="mock-notif-ago">Just now</span>
                            </div>

                            <div class="mock-notif-main">
                                <div class="mock-notif-icon-col">
                                    <div class="mock-icon-avatar">
                                        <i class="bi bi-stars"></i>
                                    </div>
                                </div>
                                <div class="mock-notif-text-col">
                                    <h4 id="prevTitle">🔥 New Drop Alert: Acid Wash Heavyweight Tee</h4>
                                    <p id="prevMessage">Discover our new luxury streetwear collection crafted in heavy 240 GSM cotton. Limited pieces in stock!</p>
                                    
                                    <div id="prevImgWrap" style="display:none;" class="mock-img-container">
                                        <img id="prevImg" src="" alt="Banner Preview">
                                    </div>

                                    <div class="mock-action-btn" id="prevBtn">
                                        Shop Now &rarr;
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mockup-feed-placeholder">
                            <div class="mock-skeleton-line"></div>
                            <div class="mock-skeleton-card"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════
         TAB 2: AUTOMATION WORKFLOWS & RULES
         ═══════════════════════════════════════════════════ --}}
    @if($tab === 'automations')
    <div class="automations-layout">
        
        {{-- Left: Rules Configuration --}}
        <div class="premium-card">
            <div class="premium-card-head">
                <div class="head-title-wrap">
                    <i class="bi bi-sliders text-indigo"></i>
                    <div>
                        <h3>Automated Marketing Triggers</h3>
                        <p>Set autonomous conditions that notify customers automatically based on real-time actions</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.notifications.settings') }}" class="automations-form p-4">
                @csrf

                {{-- Workflow 1: New Product Launch --}}
                <div class="workflow-card">
                    <div class="workflow-left">
                        <div class="wf-icon-box bg-indigo-subtle">
                            <i class="bi bi-stars text-indigo"></i>
                        </div>
                        <div class="wf-details">
                            <div class="wf-head-line">
                                <h4>New Product Launch Trigger</h4>
                                <span class="wf-badge-auto">AUTOMATIC</span>
                            </div>
                            <p>Whenever a new product is added/published in the Admin Catalog, the system immediately sends a drop alert to registered customers.</p>
                        </div>
                    </div>
                    <label class="modern-switch">
                        <input type="checkbox" name="auto_new_product" value="1" {{ $settings['auto_new_product'] ? 'checked' : '' }}>
                        <span class="switch-slider"></span>
                    </label>
                </div>

                {{-- Workflow 2: New Coupon / Offer Promo --}}
                <div class="workflow-card">
                    <div class="workflow-left">
                        <div class="wf-icon-box bg-amber-subtle">
                            <i class="bi bi-tag-fill text-amber"></i>
                        </div>
                        <div class="wf-details">
                            <div class="wf-head-line">
                                <h4>New Coupon &amp; Discount Offer Trigger</h4>
                                <span class="wf-badge-auto">AUTOMATIC</span>
                            </div>
                            <p>Whenever you create a new coupon or promotional discount, an announcement notification with the promo code is broadcast to users.</p>
                        </div>
                    </div>
                    <label class="modern-switch">
                        <input type="checkbox" name="auto_new_offer" value="1" {{ $settings['auto_new_offer'] ? 'checked' : '' }}>
                        <span class="switch-slider"></span>
                    </label>
                </div>

                {{-- Workflow 3: Abandoned Cart Recovery (2-3 hours) --}}
                <div class="workflow-card highlight-workflow">
                    <div class="workflow-left">
                        <div class="wf-icon-box bg-rose-subtle">
                            <i class="bi bi-bag-x-fill text-rose"></i>
                        </div>
                        <div class="wf-details">
                            <div class="wf-head-line">
                                <h4>Abandoned Cart Auto-Recovery</h4>
                                <span class="wf-badge-rec">HIGH CONVERSION</span>
                            </div>
                            <p>Monitors shoppers who added items to bag or initiated checkout but didn't finish payment. Sends reminder alerts with direct checkout link.</p>
                            
                            <div class="wf-inline-config">
                                <span><i class="bi bi-clock-history"></i> Send recovery alert after:</span>
                                <select name="abandoned_cart_hours" class="config-select">
                                    <option value="1" {{ $settings['abandoned_cart_hours'] == '1' ? 'selected' : '' }}>1 Hour</option>
                                    <option value="2" {{ $settings['abandoned_cart_hours'] == '2' ? 'selected' : '' }}>2 Hours (Recommended)</option>
                                    <option value="3" {{ $settings['abandoned_cart_hours'] == '3' ? 'selected' : '' }}>3 Hours</option>
                                    <option value="6" {{ $settings['abandoned_cart_hours'] == '6' ? 'selected' : '' }}>6 Hours</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <label class="modern-switch">
                        <input type="checkbox" name="auto_abandoned_cart" value="1" {{ $settings['auto_abandoned_cart'] ? 'checked' : '' }}>
                        <span class="switch-slider"></span>
                    </label>
                </div>

                {{-- Workflow 4: Inactive User Re-Engagement --}}
                <div class="workflow-card">
                    <div class="workflow-left">
                        <div class="wf-icon-box bg-emerald-subtle">
                            <i class="bi bi-compass-fill text-emerald"></i>
                        </div>
                        <div class="wf-details">
                            <div class="wf-head-line">
                                <h4>Inactive User "Explore Products" Nudge</h4>
                                <span class="wf-badge-auto">AUTOMATIC</span>
                            </div>
                            <p>Automatically sends a curated welcome &amp; bestseller recommendation to newly registered users who have not placed an order yet.</p>
                        </div>
                    </div>
                    <label class="modern-switch">
                        <input type="checkbox" name="auto_inactive_user" value="1" {{ $settings['auto_inactive_user'] ? 'checked' : '' }}>
                        <span class="switch-slider"></span>
                    </label>
                </div>

                <div class="form-submit-row mt-4">
                    <button type="submit" class="btn-primary-gradient">
                        <i class="bi bi-check2-circle"></i>
                        <span>Save Automation Rules</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Right: Instant Execution Tools --}}
        <div class="trigger-tools-pane">
            <div class="premium-card">
                <div class="premium-card-head">
                    <div class="head-title-wrap">
                        <i class="bi bi-lightning-charge-fill text-amber"></i>
                        <div>
                            <h3>Instant Manual Triggers</h3>
                            <p>Execute background jobs on demand</p>
                        </div>
                    </div>
                </div>

                <div class="instant-triggers-list p-4">
                    <div class="instant-trigger-card">
                        <div class="it-info">
                            <strong>Run Abandoned Cart Recovery</strong>
                            <p>Scans all open shopping sessions and sends recovery notifications immediately.</p>
                        </div>
                        <form method="POST" action="{{ route('admin.notifications.trigger') }}">
                            @csrf
                            <input type="hidden" name="trigger_type" value="abandoned_cart">
                            <button type="submit" class="btn-trigger-action">
                                <i class="bi bi-play-fill"></i> Run Now
                            </button>
                        </form>
                    </div>

                    <div class="instant-trigger-card">
                        <div class="it-info">
                            <strong>Nudge Inactive Users</strong>
                            <p>Sends "Explore Products" collection alerts to signups with zero orders.</p>
                        </div>
                        <form method="POST" action="{{ route('admin.notifications.trigger') }}">
                            @csrf
                            <input type="hidden" name="trigger_type" value="inactive_users">
                            <button type="submit" class="btn-trigger-action">
                                <i class="bi bi-play-fill"></i> Run Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════
         TAB 3: DELIVERY LOGS & HISTORY
         ═══════════════════════════════════════════════════ --}}
    @if($tab === 'logs')
    <div class="premium-card">
        <div class="premium-card-head">
            <div class="head-title-wrap">
                <i class="bi bi-clock-history text-indigo"></i>
                <div>
                    <h3>Notification History &amp; Delivery Logs</h3>
                    <p>Total {{ $notifications->total() }} notifications dispatched</p>
                </div>
            </div>
        </div>

        {{-- Filter Bar --}}
        <div class="logs-filter-bar">
            <form method="GET" action="{{ route('admin.notifications.index') }}" class="filter-form-flex">
                <input type="hidden" name="tab" value="logs">
                <div class="search-input-box">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search title, message, customer..." class="input-styled">
                </div>

                <select name="type" class="input-styled select-type-styled" onchange="this.form.submit()">
                    <option value="">All Notification Types</option>
                    <option value="manual_broadcast" {{ $typeFilter === 'manual_broadcast' ? 'selected' : '' }}>📢 Manual Broadcast</option>
                    <option value="product_launched" {{ $typeFilter === 'product_launched' ? 'selected' : '' }}>🔥 Product Drop</option>
                    <option value="offer_created" {{ $typeFilter === 'offer_created' ? 'selected' : '' }}>🎉 Offer / Promo</option>
                    <option value="cart_abandoned" {{ $typeFilter === 'cart_abandoned' ? 'selected' : '' }}>🛒 Cart Abandoned</option>
                    <option value="inactive_welcome" {{ $typeFilter === 'inactive_welcome' ? 'selected' : '' }}>✨ Inactive User</option>
                </select>

                <button type="submit" class="btn-primary-gradient btn-sm">Search</button>
                @if($search || $typeFilter)
                    <a href="{{ route('admin.notifications.index', ['tab' => 'logs']) }}" class="btn-clear-filter">
                        <i class="bi bi-x-circle"></i> Clear
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="luxury-table">
                <thead>
                    <tr>
                        <th>Time Dispatched</th>
                        <th>Workflow Type</th>
                        <th>Notification Details</th>
                        <th>Target Recipient</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $notif)
                        <tr>
                            <td>
                                <strong class="time-main">{{ $notif->created_at->diffForHumans() }}</strong>
                                <span class="time-sub">{{ $notif->created_at->format('M d, h:i A') }}</span>
                            </td>

                            <td>
                                <span class="type-pill {{ $notif->type_badge }}">
                                    {{ $notif->type_label }}
                                </span>
                            </td>

                            <td>
                                <div class="notif-content-cell">
                                    <strong>{{ $notif->title }}</strong>
                                    <p>{{ Str::limit($notif->message, 120) }}</p>
                                    @if($notif->action_url)
                                        <a href="{{ $notif->action_url }}" target="_blank" class="action-link-preview">
                                            Link: {{ $notif->action_label ?: 'View' }} &rarr;
                                        </a>
                                    @endif
                                </div>
                            </td>

                            <td>
                                @if($notif->user)
                                    <div class="recipient-badge-box">
                                        <i class="bi bi-person-check-fill text-emerald"></i>
                                        <div>
                                            <strong>{{ $notif->user->name }}</strong>
                                            <small>{{ $notif->user->email ?: $notif->user->phone }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="broadcast-audience-tag">
                                        <i class="bi bi-megaphone-fill"></i> All Customers
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($notif->is_read)
                                    <span class="status-pill status-read">
                                        <i class="bi bi-check2-all"></i> Read
                                    </span>
                                @else
                                    <span class="status-pill status-delivered">
                                        <i class="bi bi-check-circle"></i> Delivered
                                    </span>
                                @endif
                            </td>

                            <td style="text-align:right;">
                                <form method="POST" action="{{ route('admin.notifications.destroy', $notif) }}" onsubmit="return confirm('Delete notification?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-trash" title="Delete record">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-bell-slash" style="font-size:32px;display:block;margin-bottom:8px;color:#cbd5e1;"></i>
                                No notifications found matching your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($notifications->hasPages())
            <div class="table-pagination-footer">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
    @endif

</div>

<script>
function toggleSpecificUser(val) {
    var wrap = document.getElementById('specificUserWrap');
    if (wrap) {
        wrap.style.display = (val === 'single_user') ? 'block' : 'none';
    }
}

function updateLivePreview() {
    var title = document.getElementById('inpTitle').value || '🔥 New Drop Alert: Acid Wash Heavyweight Tee';
    var msg = document.getElementById('inpMessage').value || 'Discover our new luxury streetwear collection crafted in heavy 240 GSM cotton. Limited pieces in stock!';
    var btn = document.getElementById('inpBtnText').value || 'Shop Now';
    var img = document.getElementById('inpImage').value;

    document.getElementById('prevTitle').innerText = title;
    document.getElementById('prevMessage').innerText = msg;
    document.getElementById('prevBtn').innerText = btn + ' \u2192';

    var imgWrap = document.getElementById('prevImgWrap');
    var imgEl = document.getElementById('prevImg');
    if (img && img.trim() !== '') {
        imgEl.src = img;
        imgWrap.style.display = 'block';
    } else {
        imgWrap.style.display = 'none';
    }
}
</script>

<style>
/* ─── Modern Luxury UI Palette & Styles ─── */
:root {
    --brand-navy: #00285a;
    --brand-dark: #0f172a;
    --brand-indigo: #4f46e5;
    --brand-amber: #d97706;
    --brand-rose: #e11d48;
    --brand-emerald: #059669;
}

.ttt-notif-root {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Hero Banner */
.notif-hero-banner {
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
    position: relative;
    overflow: hidden;
}

.hero-tag {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(8px);
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1px;
    margin-bottom: 8px;
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.hero-pulse {
    width: 6px;
    height: 6px;
    background: #10b981;
    border-radius: 50%;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3);
}

.hero-title {
    font-size: 22px;
    font-weight: 800;
    margin: 0 0 6px;
    letter-spacing: -0.5px;
}

.hero-desc {
    font-size: 13px;
    color: #cbd5e1;
    margin: 0;
    max-width: 600px;
    line-height: 1.5;
}

.hero-kpis {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.kpi-mini-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 14px;
    padding: 12px 18px;
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 140px;
}

.kpi-icon-square {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.bg-indigo-subtle { background: #e0e7ff; }
.bg-amber-subtle { background: #fef3c7; }
.bg-emerald-subtle { background: #d1fae5; }
.bg-rose-subtle { background: #ffe4e6; }

.text-indigo { color: #4338ca; }
.text-amber { color: #b45309; }
.text-emerald { color: #047857; }
.text-rose { color: #be123c; }

.kpi-meta {
    display: flex;
    flex-direction: column;
}

.kpi-caption {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    color: #cbd5e1;
    letter-spacing: 0.5px;
}

.kpi-number {
    font-size: 18px;
    font-weight: 800;
    color: #ffffff;
}

/* ─── Pill Tabs ─── */
.notif-pill-tabs {
    display: flex;
    gap: 10px;
    background: #ffffff;
    padding: 8px;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
}

.notif-tab-item {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    font-size: 13px;
    font-weight: 700;
    color: #64748b;
    text-decoration: none;
    border-radius: 10px;
    transition: all 0.2s ease;
}

.notif-tab-item:hover {
    color: #00285a;
    background: #f1f5f9;
}

.notif-tab-item.active {
    background: #00285a;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(0, 40, 90, 0.25);
}

.tab-badge-active {
    background: #10b981;
    color: #ffffff;
    font-size: 9px;
    font-weight: 800;
    padding: 2px 6px;
    border-radius: 999px;
}

/* ─── Premium Cards ─── */
.premium-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03);
    overflow: hidden;
}

.premium-card-head {
    padding: 18px 24px;
    border-bottom: 1px solid #f1f5f9;
    background: #ffffff;
}

.head-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
}

.head-title-wrap i {
    font-size: 22px;
}

.head-title-wrap h3 {
    font-size: 15px;
    font-weight: 800;
    color: #00285a;
    margin: 0;
}

.head-title-wrap p {
    font-size: 12px;
    color: #64748b;
    margin: 2px 0 0;
}

/* ─── Broadcast Composer Form ─── */
.broadcast-layout {
    display: grid;
    grid-template-columns: 1.25fr 0.75fr;
    gap: 24px;
    align-items: flex-start;
}

@media (max-width: 1024px) {
    .broadcast-layout {
        grid-template-columns: 1fr;
    }
}

.composer-form {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.form-row-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.field-label {
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    color: #475569;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.req-star {
    color: #e11d48;
}

.input-styled {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 13.5px;
    color: #1e293b;
    outline: none;
    transition: all 0.2s ease;
    background: #ffffff;
}

.input-styled:focus {
    border-color: #00285a;
    box-shadow: 0 0 0 3px rgba(0, 40, 90, 0.08);
}

.text-area-styled {
    resize: vertical;
    line-height: 1.5;
}

.form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

/* Audience Radio Cards */
.audience-grid-selector {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 10px;
}

.audience-radio-card {
    position: relative;
    cursor: pointer;
}

.audience-radio-card input {
    position: absolute;
    opacity: 0;
}

.radio-card-content {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    background: #ffffff;
    transition: all 0.2s ease;
}

.audience-radio-card input:checked + .radio-card-content {
    border-color: #00285a;
    background: #f0f7ff;
    box-shadow: 0 2px 8px rgba(0, 40, 90, 0.08);
}

.radio-card-content i {
    font-size: 20px;
    flex-shrink: 0;
}

.radio-card-content strong {
    display: block;
    font-size: 12.5px;
    color: #00285a;
}

.radio-card-content small {
    display: block;
    font-size: 10.5px;
    color: #64748b;
}

/* Channels Picker */
.channels-picker {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.channel-check-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    cursor: pointer;
    font-size: 12px;
    font-weight: 700;
    color: #334155;
    background: #ffffff;
    transition: all 0.15s ease;
}

.channel-check-pill input {
    accent-color: #00285a;
}

/* Button Gradient */
.btn-primary-gradient {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: linear-gradient(135deg, #00285a 0%, #1e3f75 100%);
    color: #ffffff;
    padding: 11px 24px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(0, 40, 90, 0.25);
    transition: all 0.2s ease;
}

.btn-primary-gradient:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(0, 40, 90, 0.35);
}

.btn-primary-gradient.btn-sm {
    padding: 8px 16px;
    font-size: 12px;
}

/* ─── Realistic Phone Mockup Preview ─── */
.preview-sticky-card {
    position: sticky;
    top: 20px;
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03);
    overflow: hidden;
    padding: 20px;
}

.preview-header {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    margin-bottom: 16px;
}

.phone-mockup-frame {
    background: #0f172a;
    border-radius: 36px;
    padding: 12px;
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.25);
    max-width: 320px;
    margin: 0 auto;
}

.phone-notch {
    width: 90px;
    height: 18px;
    background: #0f172a;
    border-radius: 0 0 12px 12px;
    margin: 0 auto 8px;
}

.phone-screen {
    background: #f8fafc;
    border-radius: 26px;
    padding: 16px 14px;
    min-height: 420px;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.mockup-site-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 8px;
    border-bottom: 1px solid #e2e8f0;
}

.mock-brand-title {
    font-size: 11px;
    font-weight: 800;
    color: #00285a;
    letter-spacing: 0.5px;
}

.mock-bell-icon {
    position: relative;
    font-size: 15px;
    color: #00285a;
}

.mock-badge {
    position: absolute;
    top: -4px;
    right: -6px;
    background: #ff3f6c;
    color: #fff;
    font-size: 8px;
    font-weight: 800;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.live-mockup-notif-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    animation: dropIn .3s ease;
}

@keyframes dropIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.mock-notif-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    font-size: 9.5px;
}

.mock-notif-origin {
    display: flex;
    align-items: center;
    gap: 4px;
    font-weight: 800;
    color: #00285a;
}

.mock-origin-dot {
    width: 5px;
    height: 5px;
    background: #ff3f6c;
    border-radius: 50%;
}

.mock-notif-ago {
    color: #94a3b8;
}

.mock-notif-main {
    display: flex;
    gap: 8px;
}

.mock-icon-avatar {
    width: 28px;
    height: 28px;
    background: #eff6ff;
    color: #1d4ed8;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.mock-notif-text-col {
    flex: 1;
}

.mock-notif-text-col h4 {
    font-size: 11px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 3px;
    line-height: 1.3;
}

.mock-notif-text-col p {
    font-size: 10px;
    color: #64748b;
    margin: 0 0 6px;
    line-height: 1.35;
}

.mock-img-container img {
    width: 100%;
    max-height: 100px;
    object-fit: cover;
    border-radius: 6px;
    margin-bottom: 6px;
}

.mock-action-btn {
    display: inline-block;
    background: #00285a;
    color: #ffffff;
    font-size: 9.5px;
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 4px;
}

.mockup-feed-placeholder {
    display: flex;
    flex-direction: column;
    gap: 8px;
    opacity: 0.4;
}

.mock-skeleton-line {
    height: 8px;
    background: #cbd5e1;
    border-radius: 4px;
    width: 60%;
}

.mock-skeleton-card {
    height: 100px;
    background: #e2e8f0;
    border-radius: 12px;
}

/* ─── Automations Layout ─── */
.automations-layout {
    display: grid;
    grid-template-columns: 1.25fr 0.75fr;
    gap: 24px;
}

@media (max-width: 1024px) {
    .automations-layout {
        grid-template-columns: 1fr;
    }
}

.workflow-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 20px;
    border-radius: 14px;
    border: 1.5px solid #f1f5f9;
    background: #ffffff;
    margin-bottom: 12px;
    transition: all 0.2s ease;
}

.workflow-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
}

.workflow-card.highlight-workflow {
    background: #fffdf5;
    border-color: #fef08a;
}

.workflow-left {
    display: flex;
    gap: 14px;
    align-items: flex-start;
}

.wf-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.wf-details {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.wf-head-line {
    display: flex;
    align-items: center;
    gap: 8px;
}

.wf-head-line h4 {
    font-size: 14px;
    font-weight: 800;
    color: #00285a;
    margin: 0;
}

.wf-badge-auto {
    background: #eff6ff;
    color: #1d4ed8;
    font-size: 9px;
    font-weight: 800;
    padding: 2px 6px;
    border-radius: 999px;
}

.wf-badge-rec {
    background: #fef3c7;
    color: #b45309;
    font-size: 9px;
    font-weight: 800;
    padding: 2px 6px;
    border-radius: 999px;
}

.wf-details p {
    font-size: 12px;
    color: #64748b;
    margin: 0;
    max-width: 460px;
    line-height: 1.4;
}

.wf-inline-config {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #00285a;
    margin-top: 8px;
}

.config-select {
    padding: 4px 10px;
    border-radius: 8px;
    border: 1.5px solid #cbd5e1;
    font-size: 12px;
    font-weight: 600;
}

/* Modern Switch */
.modern-switch {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 26px;
    flex-shrink: 0;
}

.modern-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.switch-slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background-color: #cbd5e1;
    transition: .25s;
    border-radius: 26px;
}

.switch-slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .25s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

input:checked + .switch-slider {
    background-color: #00285a;
}

input:checked + .switch-slider:before {
    transform: translateX(22px);
}

/* Instant Triggers List */
.instant-triggers-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.instant-trigger-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 16px;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #eef2f6;
    gap: 12px;
}

.it-info strong {
    display: block;
    font-size: 13px;
    color: #00285a;
}

.it-info p {
    font-size: 11px;
    color: #64748b;
    margin: 2px 0 0;
}

.btn-trigger-action {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #00285a;
    color: #ffffff;
    padding: 7px 14px;
    border-radius: 8px;
    font-size: 11.5px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.15s ease;
}

.btn-trigger-action:hover {
    background: #1e3f75;
}

/* ─── Logs & Tables ─── */
.logs-filter-bar {
    padding: 14px 24px;
    background: #f8fafc;
    border-bottom: 1px solid #f1f5f9;
}

.filter-form-flex {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.search-input-box {
    position: relative;
    flex: 1;
    min-width: 240px;
}

.search-input-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
}

.search-input-box input {
    padding-left: 34px;
}

.select-type-styled {
    max-width: 220px;
}

.btn-clear-filter {
    color: #64748b;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    padding: 6px 12px;
}

/* Luxury Table */
.luxury-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12.5px;
}

.luxury-table th {
    background: #f8fafc;
    color: #64748b;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 20px;
    border-bottom: 1px solid #eef2f6;
    text-align: left;
}

.luxury-table td {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.luxury-table tr:hover td {
    background: #f8fafc;
}

.time-main {
    font-weight: 700;
    color: #0f172a;
    display: block;
}

.time-sub {
    font-size: 11px;
    color: #94a3b8;
}

.type-pill {
    display: inline-flex;
    font-size: 10.5px;
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 6px;
    text-transform: uppercase;
}

.badge-notif-product { background: #eff6ff; color: #1d4ed8; }
.badge-notif-offer { background: #fffbeb; color: #b45309; }
.badge-notif-cart { background: #ffe4e6; color: #be123c; }
.badge-notif-welcome { background: #ecfdf5; color: #047857; }
.badge-notif-manual { background: #f1f5f9; color: #334155; }

.notif-content-cell strong {
    font-size: 13px;
    color: #00285a;
    display: block;
}

.notif-content-cell p {
    margin: 2px 0 4px;
    font-size: 11.5px;
    color: #64748b;
    line-height: 1.4;
}

.action-link-preview {
    font-size: 11px;
    font-weight: 700;
    color: #00285a;
    text-decoration: none;
}

.recipient-badge-box {
    display: flex;
    align-items: center;
    gap: 8px;
}

.recipient-badge-box strong {
    font-size: 12px;
    color: #00285a;
    display: block;
}

.recipient-badge-box small {
    font-size: 10.5px;
    color: #64748b;
    display: block;
}

.broadcast-audience-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 700;
    color: #475569;
    background: #f1f5f9;
    padding: 3px 8px;
    border-radius: 6px;
}

.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 999px;
}

.status-read {
    background: #ecfdf5;
    color: #047857;
}

.status-delivered {
    background: #eff6ff;
    color: #1d4ed8;
}

.btn-trash {
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    font-size: 14px;
    padding: 6px;
    border-radius: 6px;
    transition: all 0.15s ease;
}

.btn-trash:hover {
    color: #dc2626;
    background: #fee2e2;
}

.table-pagination-footer {
    padding: 14px 24px;
    display: flex;
    justify-content: flex-end;
    border-top: 1px solid #f1f5f9;
}
</style>
@endsection
