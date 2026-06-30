{{-- resources/views/admin/customers/show.blade.php --}}
@extends('admin.layouts.app')
@section('title','Customer: '.$user->name)
@section('content')
<div style="display:grid;grid-template-columns:300px 1fr;gap:20px;align-items:flex-start">
    <div class="admin-card">
        <div style="padding:24px;text-align:center">
            <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#00285a,#1e3f75);display:flex;align-items:center;justify-content:center;color:white;font-family:'Cinzel',serif;font-size:28px;font-weight:700;margin:0 auto 12px">
                {{ strtoupper(substr($user->name,0,1)) }}
            </div>
            <div style="font-weight:700;font-size:16px;color:#00285a">{{ $user->name }}</div>
            <div style="font-size:13px;color:#7a8fa6;margin:4px 0 16px">{{ $user->email }}</div>
            <div style="display:flex;flex-direction:column;gap:8px;text-align:left">
                <div style="display:flex;justify-content:space-between;font-size:13px;padding:8px 0;border-bottom:1px solid #f1f5f9">
                    <span style="color:#7a8fa6">Phone</span><strong>{{ $user->phone ?? '—' }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px;padding:8px 0;border-bottom:1px solid #f1f5f9">
                    <span style="color:#7a8fa6">City</span><strong>{{ $user->city ?? '—' }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px;padding:8px 0;border-bottom:1px solid #f1f5f9">
                    <span style="color:#7a8fa6">Total Orders</span><strong>{{ $user->orders->count() }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px;padding:8px 0;border-bottom:1px solid #f1f5f9">
                    <span style="color:#7a8fa6">Total Spent</span><strong style="color:#c44536">₹{{ number_format($totalSpent) }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px;padding:8px 0">
                    <span style="color:#7a8fa6">Joined</span><strong>{{ $user->created_at->format('d M Y') }}</strong>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.customers.toggle',$user) }}" style="margin-top:16px">
                @csrf
                <button type="submit" class="btn-admin {{ $user->is_active ? 'btn-pink' : 'btn-navy' }}" style="width:100%">
                    <i class="bi bi-{{ $user->is_active ? 'lock' : 'unlock' }}"></i>
                    {{ $user->is_active ? 'Block Customer' : 'Unblock Customer' }}
                </button>
            </form>
        </div>
    </div>
    <div class="admin-card">
        <div class="admin-card-header"><div class="admin-card-title">ORDER HISTORY</div></div>
        <table class="admin-table">
            <thead><tr><th>Order #</th><th>Date</th><th>Amount</th><th>Payment</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse($user->orders as $order)
                    @php $sm=['pending'=>'warning','confirmed'=>'info','shipped'=>'purple','delivered'=>'success','cancelled'=>'danger']; @endphp
                    <tr>
                        <td><strong style="color:#00285a">{{ $order->order_number }}</strong></td>
                        <td style="font-size:12px;color:#7a8fa6">{{ $order->created_at->format('d M Y') }}</td>
                        <td><strong style="color:#c44536">₹{{ number_format($order->total_amount) }}</strong></td>
                        <td><span class="badge-pill badge-{{ $order->payment_status==='paid'?'success':'warning' }}">{{ ucfirst($order->payment_status) }}</span></td>
                        <td><span class="badge-pill badge-{{ $sm[$order->status]??'gray' }}">{{ ucfirst($order->status) }}</span></td>
                        <td><a href="{{ route('admin.orders.show',$order) }}" class="btn-admin btn-light btn-sm btn-icon"><i class="bi bi-eye"></i></a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;padding:30px;color:#7a8fa6">No orders yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection