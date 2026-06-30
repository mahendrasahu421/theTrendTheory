{{-- resources/views/admin/customers/index.blade.php --}}
@extends('admin.layouts.app')
@section('title','Customers')
@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title">ALL CUSTOMERS ({{ $customers->total() }})</div>
        <form method="GET" style="display:flex;gap:8px">
            <div class="search-bar">
                <i class="bi bi-search"></i>
                <input type="text" name="search" placeholder="Name, email, phone..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn-admin btn-navy btn-sm">Search</button>
            <a href="{{ route('admin.customers.index') }}" class="btn-admin btn-light btn-sm">Reset</a>
        </form>
    </div>
    <table class="admin-table">
        <thead><tr><th>Customer</th><th>Phone</th><th>Orders</th><th>Joined</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
            @forelse($customers as $customer)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#00285a,#1e3f75);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:14px;flex-shrink:0">
                                {{ strtoupper(substr($customer->name,0,1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:13px">{{ $customer->name }}</div>
                                <div style="font-size:11px;color:#7a8fa6">{{ $customer->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;color:#7a8fa6">{{ $customer->phone ?? '—' }}</td>
                    <td><span class="badge-pill badge-info">{{ $customer->orders_count }}</span></td>
                    <td style="font-size:12px;color:#7a8fa6">{{ $customer->created_at->format('d M Y') }}</td>
                    <td>
                        <span class="badge-pill {{ $customer->is_active ? 'badge-success' : 'badge-danger' }}">
                            {{ $customer->is_active ? 'Active' : 'Blocked' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('admin.customers.show',$customer) }}" class="btn-admin btn-navy btn-sm btn-icon" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.customers.toggle',$customer) }}">
                                @csrf
                                <button type="submit" class="btn-admin btn-sm btn-icon" style="background:#fef9c3;color:#854d0e" title="Toggle">
                                    <i class="bi bi-{{ $customer->is_active ? 'lock' : 'unlock' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;padding:40px;color:#7a8fa6">No customers found</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($customers->hasPages())
        <div style="padding:16px 20px">{{ $customers->links() }}</div>
    @endif
</div>
@endsection