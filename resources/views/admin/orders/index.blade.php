{{-- resources/views/admin/orders/index.blade.php --}}
@extends('admin.layouts.app')
@section('title','Orders')
@section('content')

<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title">ALL ORDERS ({{ $orders->total() }})</div>
        <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap">
            <div class="search-bar">
                <i class="bi bi-search"></i>
                <input type="text" name="search" placeholder="Order # or name..." value="{{ request('search') }}">
            </div>
            <select name="status" class="form-control-admin" style="width:150px">
                <option value="">All Status</option>
                @foreach(['pending','confirmed','processing','shipped','delivered','cancelled','refunded'] as $s)
                    <option value="{{ $s }}" {{ request('status')===$s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <select name="payment" class="form-control-admin" style="width:150px">
                <option value="">All Payments</option>
                <option value="pending" {{ request('payment')==='pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid"    {{ request('payment')==='paid'    ? 'selected' : '' }}>Paid</option>
                <option value="failed"  {{ request('payment')==='failed'  ? 'selected' : '' }}>Failed</option>
            </select>
            <button type="submit" class="btn-admin btn-navy btn-sm">Filter</button>
            <a href="{{ route('admin.orders.index') }}" class="btn-admin btn-light btn-sm">Reset</a>
        </form>
    </div>

    <table class="admin-table">
        <thead><tr>
            <th>Order #</th><th>Customer</th><th>Items</th>
            <th>Amount</th><th>Payment</th><th>Status</th><th>Date</th><th>Action</th>
        </tr></thead>
        <tbody>
            @forelse($orders as $order)
                @php
                    $statusMap  = ['pending'=>'warning','confirmed'=>'info','processing'=>'info','shipped'=>'purple','delivered'=>'success','cancelled'=>'danger','refunded'=>'gray'];
                    $paymentMap = ['pending'=>'warning','paid'=>'success','failed'=>'danger','refunded'=>'gray'];
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('admin.orders.show',$order) }}" style="font-weight:700;color:#00285a;text-decoration:none">
                            {{ $order->order_number }}
                        </a>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px">{{ $order->shipping_name }}</div>
                        <div style="font-size:11px;color:#7a8fa6">{{ $order->shipping_phone }}</div>
                    </td>
                    <td style="font-size:13px;color:#7a8fa6">{{ $order->items->count() ?? '—' }} items</td>
                    <td><strong style="color:#c44536">₹{{ number_format($order->total_amount) }}</strong></td>
                    <td>
                        <span class="badge-pill badge-{{ $paymentMap[$order->payment_status] ?? 'gray' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-pill badge-{{ $statusMap[$order->status] ?? 'gray' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td style="font-size:12px;color:#7a8fa6">{{ $order->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show',$order) }}" class="btn-admin btn-navy btn-sm btn-icon" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;padding:40px;color:#7a8fa6">No orders found</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($orders->hasPages())
        <div style="padding:16px 20px">{{ $orders->links() }}</div>
    @endif
</div>
@endsection


{{-- resources/views/admin/orders/show.blade.php --}}
@extends('admin.layouts.app')
@section('title','Order: '.$order->order_number)
@section('content')

<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:flex-start">

    {{-- LEFT --}}
    <div>
        {{-- Order Items --}}
        <div class="admin-card" style="margin-bottom:20px">
            <div class="admin-card-header">
                <div class="admin-card-title">ORDER ITEMS</div>
            </div>
            <table class="admin-table">
                <thead><tr><th>Product</th><th>Size</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px">
                                    @if($item->product_image)
                                        <img src="{{ $item->product_image }}" class="prod-thumb" alt="">
                                    @endif
                                    <span style="font-weight:600;font-size:13px">{{ $item->product_name }}</span>
                                </div>
                            </td>
                            <td style="color:#7a8fa6">{{ $item->size ?: '—' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format($item->unit_price) }}</td>
                            <td><strong>₹{{ number_format($item->subtotal) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Shipping Address --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">SHIPPING ADDRESS</div>
            </div>
            <div style="padding:20px;font-size:14px;line-height:1.8">
                <strong>{{ $order->shipping_name }}</strong><br>
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_city }}, {{ $order->shipping_state }} — {{ $order->shipping_pincode }}<br>
                <i class="bi bi-telephone"></i> {{ $order->shipping_phone }}
            </div>
        </div>
    </div>

    {{-- RIGHT --}}
    <div>
        {{-- Summary --}}
        <div class="admin-card" style="margin-bottom:16px">
            <div class="admin-card-header"><div class="admin-card-title">ORDER SUMMARY</div></div>
            <div style="padding:16px">
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:13px">
                    <span style="color:#7a8fa6">Subtotal</span>
                    <span>₹{{ number_format($order->subtotal) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:13px">
                    <span style="color:#7a8fa6">Shipping</span>
                    <span>{{ $order->shipping_charge > 0 ? '₹'.number_format($order->shipping_charge) : 'FREE' }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:13px">
                        <span style="color:#22c55e">Discount</span>
                        <span style="color:#22c55e">-₹{{ number_format($order->discount_amount) }}</span>
                    </div>
                @endif
                <div style="display:flex;justify-content:space-between;padding-top:10px;border-top:1px solid #e8edf5;font-weight:700;font-size:16px">
                    <span>Total</span>
                    <span style="color:#c44536">₹{{ number_format($order->total_amount) }}</span>
                </div>
                <div style="margin-top:10px;font-size:12px;color:#7a8fa6">
                    Payment: <strong>{{ ucfirst($order->payment_method) }}</strong>
                </div>
                @if($order->tracking_number)
                    <div style="margin-top:6px;font-size:12px;color:#7a8fa6">
                        Tracking: <strong>{{ $order->tracking_number }}</strong> ({{ $order->courier_name }})
                    </div>
                @endif
            </div>
        </div>

        {{-- Update Status --}}
        <div class="admin-card">
            <div class="admin-card-header"><div class="admin-card-title">UPDATE ORDER</div></div>
            <div style="padding:16px">
                <form method="POST" action="{{ route('admin.orders.update',$order) }}">
                    @csrf @method('PUT')
                    <div class="form-grp" style="margin-bottom:12px">
                        <label>Order Status</label>
                        <select name="status" class="form-control-admin">
                            @foreach(['pending','confirmed','processing','shipped','delivered','cancelled','refunded'] as $s)
                                <option value="{{ $s }}" {{ $order->status===$s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-grp" style="margin-bottom:12px">
                        <label>Payment Status</label>
                        <select name="payment_status" class="form-control-admin">
                            @foreach(['pending','paid','failed','refunded'] as $s)
                                <option value="{{ $s }}" {{ $order->payment_status===$s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-grp" style="margin-bottom:12px">
                        <label>Courier Name</label>
                        <input type="text" name="courier_name" class="form-control-admin" value="{{ $order->courier_name }}" placeholder="Delhivery, BlueDart...">
                    </div>
                    <div class="form-grp" style="margin-bottom:16px">
                        <label>Tracking Number</label>
                        <input type="text" name="tracking_number" class="form-control-admin" value="{{ $order->tracking_number }}" placeholder="AWB123456789">
                    </div>
                    <button type="submit" class="btn-admin btn-navy" style="width:100%">
                        <i class="bi bi-check-lg"></i> Update Order
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection