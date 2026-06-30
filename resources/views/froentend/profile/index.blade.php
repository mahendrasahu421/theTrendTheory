{{-- resources/views/froentend/profile/index.blade.php --}}
@extends('froentend.layouts.app')

@push('seo')
    <title>My Account | The Trend Theory</title>
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
.profile-nav a { display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:10px; text-decoration:none; font-size:13px; font-weight:600; color:#444; margin-bottom:4px; transition:.15s; }
.profile-nav a:hover, .profile-nav a.active { background:#f0f4ff; color:#00285a; }
.profile-nav a i { font-size:16px; color:#00285a; width:20px; }
.profile-logout { margin-top:16px; padding-top:16px; border-top:1px solid #eef2f6; }
.profile-logout form button { width:100%; padding:10px; background:#fff; border:1.5px solid #eef2f6; border-radius:10px; font-size:13px; font-weight:600; color:#e53935; cursor:pointer; transition:.15s; }
.profile-logout form button:hover { background:#fff5f5; border-color:#e53935; }

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
.save-btn:hover { background:#ff3f6c; }
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
                <div class="profile-card-title">RECENT ORDERS</div>
                @if($orders->count() > 0)
                    @foreach($orders as $order)
                        <div class="order-row">
                            <div>
                                <div class="order-id">{{ $order->order_number }}</div>
                                <div class="order-date">{{ $order->created_at->format('d M Y') }}</div>
                            </div>
                            <span class="order-status status-{{ strtolower($order->status) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                            <div class="order-amt">₹{{ number_format($order->total_amount) }}</div>
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
@endsection