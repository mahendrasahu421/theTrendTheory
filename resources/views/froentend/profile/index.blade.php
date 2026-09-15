{{-- resources/views/froentend/profile/index.blade.php --}}
@extends('froentend.layouts.app')

@push('seo')
    <title>My Account | Vayu</title>
    <meta name="robots" content="noindex, nofollow">
@endpush

@section('main')
<style>
.profile-wrap { max-width:900px; margin:40px auto; padding:0 20px 60px; }
.profile-grid { display:grid; grid-template-columns:240px 1fr; gap:24px; }
.profile-sidebar { background:#fff; border-radius:16px; border:1px solid #eef2f6; padding:24px; height:fit-content; }
.profile-avatar { width:72px; height:72px; border-radius:50%; background:linear-gradient(135deg,#00285a,#1e3f75); color:#fff; display:flex; align-items:center; justify-content:center; font-size:28px; font-weight:700; font-family:'Cinzel',serif; margin:0 auto 12px; }
.profile-name { text-align:center; font-weight:700; font-size:15px; color:#00285a; margin-bottom:4px; }
.profile-email { text-align:center; font-size:12px; color:#7a8fa6; margin-bottom:20px; }
.profile-nav a { display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:10px; text-decoration:none; font-size:13px; font-weight:600; color:#444; margin-bottom:4px; transition:.15s; }.profile-nav a.active { background:#f0f4ff; color:#00285a; }
.profile-nav a i { font-size:16px; color:#00285a; width:20px; }
.profile-logout { margin-top:16px; padding-top:16px; border-top:1px solid #eef2f6; }
.profile-logout form button { width:100%; padding:10px; background:#fff; border:1.5px solid #eef2f6; border-radius:10px; font-size:13px; font-weight:600; color:#e53935; cursor:pointer; transition:.15s; }

.profile-main { display:flex; flex-direction:column; gap:20px; }
.profile-card { background:#fff; border-radius:16px; border:1px solid #eef2f6; padding:24px; }
.profile-card-title { font-family:'Cinzel',serif; font-size:1rem; font-weight:700; color:#00285a; letter-spacing:1px; margin-bottom:20px; padding-bottom:12px; border-bottom:1px solid #eef2f6; }
.form-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
.form-grp { display:flex; flex-direction:column; gap:6px; }
.form-grp.full { grid-column:1/-1; }
.form-grp label { font-size:12px; font-weight:700; color:#7a8fa6; text-transform:uppercase; letter-spacing:.5px; }
.form-grp input { padding:10px 14px; border:1.5px solid #e8edf5; border-radius:10px; font-size:14px; outline:none; transition:.2s; font-family:inherit; }
.form-grp input:focus { border-color:#00285a; }
.save-btn { padding:10px 28px; background:#00285a; color:#fff; border:none; border-radius:30px; font-size:13px; font-weight:700; cursor:pointer; transition:.2s; margin-top:4px; }
.alert-success { background:#e8f5e9; color:#2e7d32; padding:10px 14px; border-radius:8px; font-size:13px; margin-bottom:16px; }
.alert-error   { background:#fce4ec; color:#c62828; padding:10px 14px; border-radius:8px; font-size:13px; margin-bottom:16px; }

/* Orders */
.order-row { display:flex; align-items:center; justify-content:space-between; padding:14px 0; border-bottom:1px solid #f0f4f8; flex-wrap:wrap; gap:8px; }
.order-row:last-child { border-bottom:none; }
.order-id   { font-weight:700; color:#00285a; font-size:13px; }
.order-date { font-size:12px; color:#7a8fa6; }
.order-amt  { font-weight:700; color:#c44536; font-size:14px; }
.order-status { padding:3px 12px; border-radius:20px; font-size:11px; font-weight:700; }
.status-delivered  { background:#e8f5e9; color:#2e7d32; }
.status-shipped    { background:#e3f2fd; color:#1565c0; }
.status-processing { background:#fff3e0; color:#e65100; }
.status-pending    { background:#fce4ec; color:#c62828; }
.status-cancelled  { background:#f5f5f5; color:#757575; }
.no-orders { text-align:center; padding:30px; color:#7a8fa6; font-size:14px; }

@media(max-width:768px) {
    .profile-grid { grid-template-columns:1fr; }
    .form-row { grid-template-columns:1fr; }
}
</style>

<div class="profile-wrap">

    {{-- Page title --}}
    <h1 style="font-family:'Cinzel',serif;font-size:1.6rem;font-weight:700;color:#00285a;margin-bottom:24px;letter-spacing:2px">
        MY ACCOUNT
    </h1>

    <div class="profile-grid">

        {{-- SIDEBAR --}}
        <div class="profile-sidebar">
            <div class="profile-avatar">{{ strtoupper(substr($user->name,0,1)) }}</div>
            <div class="profile-name">{{ $user->name }}</div>
            <div class="profile-email">{{ $user->email }}</div>
            <nav class="profile-nav">
                <a href="{{ url('/profile') }}" class="active">
                    <i class="bi bi-person"></i> My Profile
                </a>
                <a href="{{ route('order.track') }}">
                    <i class="bi bi-truck"></i> Track Orders
                </a>
                <a href="{{ route('order.returns') }}">
                    <i class="bi bi-arrow-return-left"></i> Returns &amp; Exchanges
                </a>
                <a href="{{ url('/wishlist') }}">
                    <i class="bi bi-heart"></i> Wishlist
                </a>
                <a href="{{ url('/cart') }}">
                    <i class="bi bi-bag"></i> My Cart
                </a>
            </nav>
            <div class="profile-logout">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"><i class="bi bi-box-arrow-right"></i> Logout</button>
                </form>
            </div>
        </div>

        {{-- MAIN --}}
        <div class="profile-main">

            {{-- Success / Error --}}
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert-error">{{ $errors->first() }}</div>
            @endif

            {{-- Edit Profile --}}
            <div class="profile-card">
                <div class="profile-card-title">PERSONAL INFORMATION</div>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PATCH')
                    <div class="form-row">
                        <div class="form-grp">
                            <label>Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="form-grp">
                            <label>Email</label>
                            <input type="email" value="{{ $user->email }}" disabled style="opacity:.6;cursor:not-allowed">
                        </div>
                        <div class="form-grp">
                            <label>Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+91 XXXXX XXXXX">
                        </div>
                        <div class="form-grp">
                            <label>City</label>
                            <input type="text" name="city" value="{{ old('city', $user->city) }}" placeholder="Kanpur">
                        </div>
                    </div>
                    <button type="submit" class="save-btn">SAVE CHANGES</button>
                </form>
            </div>

            {{-- Change Password --}}
            <div class="profile-card">
                <div class="profile-card-title">CHANGE PASSWORD</div>
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf @method('PATCH')
                    <div class="form-row">
                        <div class="form-grp full">
                            <label>Current Password</label>
                            <input type="password" name="current_password" placeholder="••••••••" required>
                        </div>
                        <div class="form-grp">
                            <label>New Password</label>
                            <input type="password" name="password" placeholder="Min 8 characters" required>
                        </div>
                        <div class="form-grp">
                            <label>Confirm Password</label>
                            <input type="password" name="password_confirmation" placeholder="Repeat password" required>
                        </div>
                    </div>
                    <button type="submit" class="save-btn">UPDATE PASSWORD</button>
                </form>
            </div>

            {{-- Recent Orders --}}
            <div class="profile-card">
                <div class="profile-card-title" style="display:flex;justify-content:space-between;align-items:center;">
                    <span>RECENT ORDERS</span>
                    <a href="{{ route('order.track') }}" style="font-size:12px;font-family:sans-serif;color:#00285a;font-weight:700;text-decoration:none;"><i class="bi bi-search"></i> Track by ID</a>
                </div>
                @if($orders->count() > 0)
                    @foreach($orders as $order)
                        @php $cancellable = in_array(strtolower($order->status), ['pending', 'confirmed']); @endphp
                        <div class="order-row">
                            <div>
                                <div class="order-id">
                                    <a href="{{ route('order.track.detail', $order->order_number) }}" style="color:#00285a;text-decoration:none;">
                                        #{{ $order->order_number }}
                                    </a>
                                </div>
                                <div class="order-date">{{ $order->created_at->format('d M Y') }} &bull; {{ $order->items->count() }} {{ $order->items->count() === 1 ? 'item' : 'items' }}</div>
                            </div>
                            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                <span class="order-status status-{{ strtolower($order->status) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <div class="order-amt">₹{{ number_format($order->total_amount) }}</div>
                                <a href="{{ route('order.track.detail', $order->order_number) }}" class="save-btn" style="padding:6px 14px;font-size:11.5px;text-decoration:none;display:inline-flex;align-items:center;gap:4px;margin-top:0;">
                                    <i class="bi bi-truck"></i> Track
                                </a>
                                @if($cancellable)
                                    <button type="button"
                                        class="ttt-cancel-btn"
                                        onclick="tttOpenCancelModal('{{ $order->id }}', '{{ $order->order_number }}')"
                                        title="Cancel Order">
                                        <i class="bi bi-x-circle"></i> Cancel
                                    </button>
                                @endif
                                @if(in_array(strtolower($order->status), ['delivered', 'completed']) && !$order->return()->exists())
                                    <a href="{{ route('order.return.create', $order->id) }}"
                                       style="background:#fff3e0;color:#e65100;border:1.5px solid #fed7aa;border-radius:30px;font-size:11.5px;font-weight:700;padding:5px 13px;text-decoration:none;display:inline-flex;align-items:center;gap:4px;transition:all .15s;"
                                       title="Return or Exchange">
                                        <i class="bi bi-arrow-return-left"></i> Return
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="no-orders">
                        <i class="bi bi-bag-x" style="font-size:32px;display:block;margin-bottom:8px;color:#d9dee6"></i>
                        No orders yet. <a href="{{ route('shop.index') }}" style="color:#00285a;font-weight:600">Start shopping!</a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     ORDER CANCEL CONFIRMATION MODAL (Premium UI)
     ═══════════════════════════════════════════ --}}
<div id="tttCancelModal" style="display:none;position:fixed;inset:0;z-index:99999;align-items:center;justify-content:center;padding:16px;">
    <div class="tttc-backdrop" onclick="tttCloseCancelModal()"></div>
    <div class="tttc-modal-box">

        {{-- Header --}}
        <div class="tttc-head">
            <div class="tttc-icon-ring">
                <i class="bi bi-shield-exclamation"></i>
            </div>
            <div class="tttc-head-content">
                <div class="tttc-tag">Cancellation Request</div>
                <h3 class="tttc-main-title">Cancel Order?</h3>
                <span class="tttc-order-pill" id="tttCancelOrderLabel">Order #---</span>
            </div>
            <button type="button" class="tttc-btn-close" onclick="tttCloseCancelModal()" aria-label="Close modal">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        {{-- Notice Banner --}}
        <div class="tttc-notice-card">
            <div class="tttc-notice-item">
                <div class="tttc-notice-icon"><i class="bi bi-arrow-counterclockwise"></i></div>
                <div class="tttc-notice-text">
                    <strong>Action is Irreversible</strong>
                    <span>Once cancelled, items return to inventory &amp; order cannot be restored.</span>
                </div>
            </div>
            <div class="tttc-notice-divider"></div>
            <div class="tttc-notice-item">
                <div class="tttc-notice-icon"><i class="bi bi-wallet2"></i></div>
                <div class="tttc-notice-text">
                    <strong>Refund Timeline</strong>
                    <span>Prepaid amounts refunded to original source in <strong>5–7 business days</strong>.</span>
                </div>
            </div>
        </div>

        {{-- Reason Section --}}
        <div class="tttc-body">
            <div class="tttc-field-header">
                <label class="tttc-label">Please tell us why you are cancelling <span class="tttc-req">*</span></label>
                <span class="tttc-helper">Select one option</span>
            </div>

            <div class="tttc-reasons-grid" id="tttCancelChips">
                <button type="button" class="tttc-reason-opt" onclick="tttSelectReason(this, 'Changed my mind')">
                    <span class="tttc-opt-emoji">💭</span>
                    <span class="tttc-opt-label">Changed my mind</span>
                    <i class="bi bi-check-circle-fill tttc-opt-check"></i>
                </button>

                <button type="button" class="tttc-reason-opt" onclick="tttSelectReason(this, 'Found better price elsewhere')">
                    <span class="tttc-opt-emoji">🏷️</span>
                    <span class="tttc-opt-label">Found better price</span>
                    <i class="bi bi-check-circle-fill tttc-opt-check"></i>
                </button>

                <button type="button" class="tttc-reason-opt" onclick="tttSelectReason(this, 'Ordered by mistake')">
                    <span class="tttc-opt-emoji">⚡</span>
                    <span class="tttc-opt-label">Ordered by mistake</span>
                    <i class="bi bi-check-circle-fill tttc-opt-check"></i>
                </button>

                <button type="button" class="tttc-reason-opt" onclick="tttSelectReason(this, 'Delivery time is too long')">
                    <span class="tttc-opt-emoji">⏱️</span>
                    <span class="tttc-opt-label">Delivery too long</span>
                    <i class="bi bi-check-circle-fill tttc-opt-check"></i>
                </button>

                <button type="button" class="tttc-reason-opt" onclick="tttSelectReason(this, 'Wrong item ordered')">
                    <span class="tttc-opt-emoji">📦</span>
                    <span class="tttc-opt-label">Wrong item / size</span>
                    <i class="bi bi-check-circle-fill tttc-opt-check"></i>
                </button>

                <button type="button" class="tttc-reason-opt" onclick="tttSelectReason(this, 'Other')">
                    <span class="tttc-opt-emoji">✍️</span>
                    <span class="tttc-opt-label">Other reason</span>
                    <i class="bi bi-check-circle-fill tttc-opt-check"></i>
                </button>
            </div>

            <div id="tttOtherInputWrap" style="display:none;margin-top:12px;">
                <textarea id="tttCancelReasonInput"
                    placeholder="Please specify your reason in detail..."
                    maxlength="250"
                    rows="2"
                    class="tttc-textarea"></textarea>
            </div>
        </div>

        {{-- Actions --}}
        <div class="tttc-actions-bar">
            <button type="button" class="tttc-btn-keep" onclick="tttCloseCancelModal()">
                <i class="bi bi-arrow-left"></i>
                <span>Keep My Order</span>
            </button>
            <button type="button" class="tttc-btn-confirm" id="tttCancelConfirmBtn" onclick="tttSubmitCancel()">
                <span id="tttCancelBtnText">
                    <i class="bi bi-x-circle-fill"></i>
                    <span>Confirm Cancel</span>
                </span>
            </button>
        </div>

    </div>
</div>

<style>
/* ── Inline Cancel Trigger on Order Row ── */
.ttt-cancel-btn {
    background: #fff1f2;
    color: #e11d48;
    border: 1.5px solid #fecdd3;
    border-radius: 999px;
    font-size: 11.5px;
    font-weight: 700;
    padding: 5px 14px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    font-family: inherit;
}

/* ── Modal Backdrop ── */
.tttc-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    animation: tttcFadeIn 0.25s ease-out both;
}
@keyframes tttcFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* ── Modal Card ── */
.tttc-modal-box {
    position: relative;
    width: 100%;
    max-width: 480px;
    background: #ffffff;
    border-radius: 24px;
    box-shadow: 0 25px 60px -12px rgba(15, 23, 42, 0.35), 0 0 0 1px rgba(226, 232, 240, 0.8);
    overflow: hidden;
    animation: tttcPopIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) both;
    font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
}
@keyframes tttcPopIn {
    from {
        opacity: 0;
        transform: scale(0.94) translateY(14px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

/* ── Header ── */
.tttc-head {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 24px 24px 18px;
    border-bottom: 1px solid #f1f5f9;
}
.tttc-icon-ring {
    width: 48px;
    height: 48px;
    min-width: 48px;
    border-radius: 16px;
    background: linear-gradient(135deg, #fff1f2, #ffe4e6);
    border: 1px solid #fecdd3;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #e11d48;
    box-shadow: 0 4px 14px rgba(225, 29, 72, 0.12);
}
.tttc-head-content {
    flex: 1;
    min-width: 0;
}
.tttc-tag {
    font-size: 10.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #e11d48;
    margin-bottom: 2px;
}
.tttc-main-title {
    font-size: 19px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 6px;
    letter-spacing: -0.4px;
    line-height: 1.2;
}
.tttc-order-pill {
    display: inline-block;
    padding: 3px 10px;
    background: #f1f5f9;
    color: #475569;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.2px;
}
.tttc-btn-close {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    font-size: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
    flex-shrink: 0;
}

/* ── Notice Banner ── */
.tttc-notice-card {
    margin: 18px 24px 0;
    background: #fff8f6;
    border: 1px solid #ffedd5;
    border-radius: 16px;
    padding: 14px 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.tttc-notice-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}
.tttc-notice-icon {
    width: 26px;
    height: 26px;
    min-width: 26px;
    border-radius: 8px;
    background: #ffedd5;
    color: #c2410c;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    margin-top: 1px;
}
.tttc-notice-text {
    font-size: 12px;
    color: #7c2d12;
    line-height: 1.45;
}
.tttc-notice-text strong {
    display: block;
    color: #9a3412;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 1px;
}
.tttc-notice-divider {
    height: 1px;
    background: #fed7aa;
    opacity: 0.6;
}

/* ── Body & Reason Section ── */
.tttc-body {
    padding: 18px 24px 0;
}
.tttc-field-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}
.tttc-label {
    font-size: 12px;
    font-weight: 800;
    color: #334155;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
}
.tttc-req {
    color: #e11d48;
}
.tttc-helper {
    font-size: 11px;
    color: #94a3b8;
    font-weight: 600;
}

/* ── Reason Options (2 Columns) ── */
.tttc-reasons-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
}
.tttc-reason-opt {
    position: relative;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 12px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    color: #334155;
    cursor: pointer;
    transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
    font-family: inherit;
    text-align: left;
    outline: none;
}
.tttc-opt-emoji {
    font-size: 15px;
    line-height: 1;
    flex-shrink: 0;
}
.tttc-opt-label {
    font-size: 12px;
    font-weight: 700;
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.tttc-opt-check {
    font-size: 14px;
    color: #00285a;
    display: none;
    flex-shrink: 0;
}
.tttc-reason-opt.active {
    background: #f0f4ff;
    border-color: #00285a;
    color: #00285a;
    box-shadow: 0 0 0 1px #00285a;
}
.tttc-reason-opt.active .tttc-opt-check {
    display: inline-block;
}

/* Textarea for Other */
.tttc-textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid #cbd5e1;
    border-radius: 12px;
    font-size: 12.5px;
    font-family: inherit;
    color: #0f172a;
    outline: none;
    box-sizing: border-box;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    resize: vertical;
}
.tttc-textarea:focus {
    border-color: #00285a;
    box-shadow: 0 0 0 3px rgba(0, 40, 90, 0.08);
}

/* ── Actions Bar ── */
.tttc-actions-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 20px 24px 24px;
}
.tttc-btn-keep {
    flex: 1;
    height: 44px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    background: #ffffff;
    color: #475569;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.18s ease;
}
.tttc-btn-confirm {
    flex: 1.25;
    height: 44px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #e11d48 0%, #be123c 100%);
    color: #ffffff;
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
    font-family: inherit;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    box-shadow: 0 4px 16px rgba(225, 29, 72, 0.35);
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.tttc-btn-confirm:disabled {
    opacity: 0.65;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

@media (max-width: 480px) {
    .tttc-reasons-grid {
        grid-template-columns: 1fr;
    }
    .tttc-actions-bar {
        flex-direction: column-reverse;
    }
    .tttc-btn-keep, .tttc-btn-confirm {
        width: 100%;
    }
}
</style>

<script>
// ── Order Cancel Modal ──
var tttCancelOrderId   = null;
var tttCancelOrderNum  = null;
var tttSelectedReason  = '';

function tttOpenCancelModal(orderId, orderNum) {
    tttCancelOrderId  = orderId;
    tttCancelOrderNum = orderNum;
    tttSelectedReason = '';
    document.getElementById('tttCancelOrderLabel').textContent = 'Order #' + orderNum;
    var inp = document.getElementById('tttCancelReasonInput');
    inp.value = '';
    document.getElementById('tttOtherInputWrap').style.display = 'none';
    document.querySelectorAll('.tttc-reason-opt').forEach(c => c.classList.remove('active'));
    document.getElementById('tttCancelModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function tttCloseCancelModal() {
    document.getElementById('tttCancelModal').style.display = 'none';
    document.body.style.overflow = '';
}

function tttSelectReason(el, reason) {
    document.querySelectorAll('.tttc-reason-opt').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    tttSelectedReason = reason;
    var wrap = document.getElementById('tttOtherInputWrap');
    var inp = document.getElementById('tttCancelReasonInput');
    if (reason === 'Other') {
        wrap.style.display = 'block';
        inp.focus();
        tttSelectedReason = '';
    } else {
        wrap.style.display = 'none';
        inp.value = '';
    }
}

function tttSubmitCancel() {
    var inp = document.getElementById('tttCancelReasonInput');
    var reason = tttSelectedReason || inp.value.trim();
    if (!reason) {
        alert('Please select or type a reason for cancellation.');
        return;
    }

    var btn = document.getElementById('tttCancelConfirmBtn');
    btn.disabled = true;
    document.getElementById('tttCancelBtnText').innerHTML = '<span class="spinner-border spinner-border-sm" style="width:14px;height:14px;border-width:2px;margin-right:6px;"></span> Cancelling…';

    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/orders/' + tttCancelOrderId + '/cancel', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ cancel_reason: reason }),
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            tttCloseCancelModal();
            // Show success toast
            var toast = document.createElement('div');
            toast.style.cssText = 'position:fixed;bottom:80px;left:50%;transform:translateX(-50%);background:#1a1a2e;color:#fff;padding:12px 24px;border-radius:30px;font-size:13px;font-weight:700;z-index:999999;box-shadow:0 8px 24px rgba(0,0,0,0.25);display:flex;align-items:center;gap:8px;animation:slideInToast .3s ease;';
            toast.innerHTML = '<i class="bi bi-check-circle-fill" style="color:#22c55e;font-size:16px;"></i> Order #' + tttCancelOrderNum + ' cancelled!';
            document.body.appendChild(toast);
            setTimeout(function() {
                if (data.redirect) window.location.href = data.redirect;
                else window.location.reload();
            }, 1800);
        } else {
            tttCloseCancelModal();
            alert(data.message || 'Could not cancel this order. Please try again.');
            btn.disabled = false;
            document.getElementById('tttCancelBtnText').innerHTML = '<i class="bi bi-x-circle"></i> Yes, Cancel Order';
        }
    })
    .catch(function() {
        tttCloseCancelModal();
        alert('Connection error. Please try again.');
        btn.disabled = false;
        document.getElementById('tttCancelBtnText').innerHTML = '<i class="bi bi-x-circle"></i> Yes, Cancel Order';
    });
}

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') tttCloseCancelModal();
});
</script>

@endsection
