{{-- resources/views/admin/dashboards/admin.blade.php --}}
@extends('admin.layouts.app')
@section('title','Admin Dashboard')
@section('content')

<style>
.sec{font-size:10px;font-weight:700;color:#7a8fa6;text-transform:uppercase;letter-spacing:1.5px;margin:20px 0 10px;display:flex;align-items:center;gap:8px}
.sec::after{content:'';flex:1;height:1px;background:#eef2f6}
.g4{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px}
.g3{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px}
.g2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}
.mc{background:white;border:1px solid #eef2f6;border-radius:14px;padding:16px 18px}
.mc-top{height:3px;border-radius:3px;margin-bottom:10px}
.mc-lbl{font-size:10px;font-weight:700;color:#7a8fa6;text-transform:uppercase;letter-spacing:.5px}
.mc-val{font-size:24px;font-weight:700;color:#00285a;font-family:'Cinzel',serif;margin:3px 0 5px;line-height:1}
.mc-sub{font-size:11px;color:#7a8fa6}
.up{color:#22c55e;font-weight:600}.dn{color:#ef4444;font-weight:600}
.card{background:white;border:1px solid #eef2f6;border-radius:14px;overflow:hidden}
.card-h{display:flex;justify-content:space-between;align-items:center;padding:14px 18px;border-bottom:1px solid #eef2f6}
.card-t{font-family:'Cinzel',serif;font-size:12px;font-weight:700;color:#00285a;letter-spacing:1px}
.dt{width:100%;border-collapse:collapse}
.dt th{padding:9px 14px;font-size:10px;font-weight:700;color:#7a8fa6;text-transform:uppercase;letter-spacing:.8px;background:#f8fafc;border-bottom:1px solid #eef2f6;text-align:left}
.dt td{padding:11px 14px;font-size:13px;border-bottom:1px solid rgba(0,0,0,.04);vertical-align:middle}
.dt tr:last-child td{border-bottom:none}
.dt tr:hover td{background:#fafbff}
.bdg{display:inline-flex;align-items:center;padding:2px 9px;border-radius:20px;font-size:11px;font-weight:700}
.bdg-g{background:#e8f5e9;color:#2e7d32}.bdg-a{background:#fff3e0;color:#e65100}
.bdg-b{background:#e3f2fd;color:#1565c0}.bdg-r{background:#fce4ec;color:#c62828}
.bdg-p{background:#f3e5f5;color:#7b1fa2}
.prog{background:#f0f4f8;border-radius:20px;height:6px;overflow:hidden;margin-top:4px}
.prog-f{height:100%;border-radius:20px}
.att-dot{width:10px;height:10px;border-radius:50%;display:inline-block;margin-right:4px}
@media(max-width:1200px){.g4{grid-template-columns:repeat(2,1fr)}.g2{grid-template-columns:1fr}}
@media(max-width:768px){.g4,.g3{grid-template-columns:1fr}}
</style>

{{-- ══ INVENTORY SECTION ══ --}}
<div class="sec">Inventory management</div>
<div class="g4">
    <div class="mc">
        <div class="mc-top" style="background:#00285a"></div>
        <div class="mc-lbl">Total Active Products</div>
        <div class="mc-val">{{ $totalProducts }}</div>
        <div class="mc-sub">All live in store</div>
    </div>
    <div class="mc">
        <div class="mc-top" style="background:#f97316"></div>
        <div class="mc-lbl">Low Stock (≤ 5)</div>
        <div class="mc-val">{{ $lowStock }}</div>
        <div class="mc-sub {{ $lowStock > 0 ? 'dn' : 'up' }}">{{ $lowStock > 0 ? 'Needs attention' : 'All good' }}</div>
    </div>
    <div class="mc">
        <div class="mc-top" style="background:#ef4444"></div>
        <div class="mc-lbl">Out of Stock</div>
        <div class="mc-val">{{ $outOfStock }}</div>
        <div class="mc-sub {{ $outOfStock > 0 ? 'dn' : 'up' }}">{{ $outOfStock > 0 ? 'Restock needed' : 'All stocked' }}</div>
    </div>
    <div class="mc">
        <div class="mc-top" style="background:#22c55e"></div>
        <div class="mc-lbl">Total Stock Value</div>
        <div class="mc-val" style="font-size:18px">₹{{ number_format($totalStockValue) }}</div>
        <div class="mc-sub">Cost price × stock</div>
    </div>
</div>

{{-- LOW STOCK TABLE --}}
@if($lowStockProducts->count() > 0)
<div class="card" style="margin-top:12px;border-color:#fce4ec">
    <div class="card-h" style="background:#fff5f7;border-color:#fce4ec">
        <div class="card-t" style="color:#c62828"><i class="bi bi-exclamation-triangle-fill"></i> Low Stock Alert — {{ $lowStockProducts->count() }} products</div>
        <a href="{{ route('admin.products.index') }}" style="font-size:12px;color:#7a8fa6;text-decoration:none">View all →</a>
    </div>
    <table class="dt">
        <thead><tr><th>Product</th><th>Category</th><th>SKU</th><th>Stock</th><th>Action</th></tr></thead>
        <tbody>
        @foreach($lowStockProducts as $p)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px">
                        @if($p->image)<img src="{{ $p->image }}" style="width:36px;height:44px;border-radius:7px;object-fit:cover;border:1px solid #eef2f6" alt="">@endif
                        <div style="font-weight:600;font-size:13px;color:#00285a">{{ Str::limit($p->name,26) }}</div>
                    </div>
                </td>
                <td style="font-size:12px;color:#7a8fa6">{{ $p->category->name ?? '—' }}</td>
                <td style="font-size:12px;color:#7a8fa6">{{ $p->sku ?? '—' }}</td>
                <td>
                    <span class="bdg {{ $p->stock == 0 ? 'bdg-r' : 'bdg-a' }}">
                        {{ $p->stock == 0 ? 'Out of Stock' : $p->stock.' left' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.products.edit', $p) }}" style="background:#00285a;color:white;padding:5px 14px;border-radius:20px;font-size:11px;font-weight:700;text-decoration:none">Update Stock</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- INVENTORY LOGS --}}
<div class="card" style="margin-top:12px">
    <div class="card-h">
        <div class="card-t">Recent Inventory Activity</div>
    </div>
    <table class="dt">
        <thead><tr><th>Product</th><th>Type</th><th>Qty</th><th>Before</th><th>After</th><th>Reason</th><th>By</th><th>Time</th></tr></thead>
        <tbody>
        @forelse($recentLogs as $log)
            <tr>
                <td style="font-weight:600;font-size:13px;color:#00285a">{{ Str::limit($log->product->name ?? 'Deleted',22) }}</td>
                <td>
                    <span class="bdg {{ $log->type === 'in' ? 'bdg-g' : ($log->type === 'out' ? 'bdg-r' : 'bdg-a') }}">
                        {{ ucfirst($log->type) }}
                    </span>
                </td>
                <td style="font-weight:700;color:{{ $log->type === 'in' ? '#22c55e' : '#ef4444' }}">
                    {{ $log->type === 'in' ? '+' : '-' }}{{ $log->quantity }}
                </td>
                <td style="color:#7a8fa6">{{ $log->stock_before }}</td>
                <td style="font-weight:600;color:#00285a">{{ $log->stock_after }}</td>
                <td style="font-size:12px;color:#7a8fa6">{{ $log->reason ?? '—' }}</td>
                <td style="font-size:12px;color:#7a8fa6">{{ $log->user->name ?? 'System' }}</td>
                <td style="font-size:11px;color:#7a8fa6">{{ $log->created_at->diffForHumans() }}</td>
            </tr>
        @empty
            <tr><td colspan="8" style="text-align:center;padding:20px;color:#7a8fa6">No inventory activity yet</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- ══ ORDERS SECTION ══ --}}
<div class="sec">Today's orders</div>
<div class="g3">
    <div class="mc">
        <div class="mc-lbl">Today's Orders</div>
        <div class="mc-val">{{ $todayOrders }}</div>
        <div class="mc-sub">New orders today</div>
    </div>
    <div class="mc">
        <div class="mc-lbl">Pending Orders</div>
        <div class="mc-val">{{ $pendingOrders }}</div>
        <div class="mc-sub {{ $pendingOrders > 10 ? 'dn' : 'up' }}">{{ $pendingOrders > 10 ? 'Action needed' : 'Under control' }}</div>
    </div>
    <div class="mc">
        <div class="mc-lbl">Quick Action</div>
        <div style="margin-top:8px;display:flex;flex-direction:column;gap:6px">
            <a href="{{ route('admin.orders.index') }}?status=pending" style="background:#00285a;color:white;padding:7px 14px;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none;text-align:center">
                View Pending Orders
            </a>
            <a href="{{ route('admin.products.index') }}" style="background:#f0f4f8;color:#00285a;padding:7px 14px;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none;text-align:center">
                Manage Products
            </a>
        </div>
    </div>
</div>

<div class="card" style="margin-top:12px">
    <div class="card-h">
        <div class="card-t">Recent Orders</div>
        <a href="{{ route('admin.orders.index') }}" style="font-size:12px;color:#7a8fa6;text-decoration:none">View all →</a>
    </div>
    <table class="dt">
        <thead><tr><th>Order #</th><th>Customer</th><th>Amount</th><th>Payment</th><th>Status</th><th>Time</th></tr></thead>
        <tbody>
        @forelse($recentOrders as $order)
            @php $sb=match($order->status){'pending'=>'bdg-a','confirmed'=>'bdg-b','cancelled'=>'bdg-r',default=>'bdg-b'}; @endphp
            <tr>
                <td><a href="{{ route('admin.orders.show',$order) }}" style="font-weight:700;color:#00285a;text-decoration:none">{{ $order->order_number }}</a></td>
                <td>
                    <div style="font-weight:600;font-size:13px">{{ $order->user->name ?? 'Guest' }}</div>
                    <div style="font-size:11px;color:#7a8fa6">{{ $order->shipping_phone ?? '' }}</div>
                </td>
                <td style="font-weight:700;color:#c44536">₹{{ number_format($order->total_amount) }}</td>
                <td><span class="bdg {{ $order->payment_status === 'paid' ? 'bdg-g' : 'bdg-a' }}">{{ ucfirst($order->payment_method ?? 'COD') }}</span></td>
                <td><span class="bdg {{ $sb }}">{{ ucfirst($order->status) }}</span></td>
                <td style="font-size:11px;color:#7a8fa6">{{ $order->created_at->diffForHumans() }}</td>
            </tr>
        @empty
            <tr><td colspan="6" style="text-align:center;padding:20px;color:#7a8fa6">No orders yet</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- ══ EMPLOYEE SECTION ══ --}}
<div class="sec">Employee management</div>
<div class="g4">
    <div class="mc">
        <div class="mc-top" style="background:#3b82f6"></div>
        <div class="mc-lbl">Total Employees</div>
        <div class="mc-val">{{ $totalEmployees }}</div>
        <div class="mc-sub">Active staff</div>
    </div>
    <div class="mc">
        <div class="mc-top" style="background:#22c55e"></div>
        <div class="mc-lbl">Present Today</div>
        <div class="mc-val">{{ $presentToday }}</div>
        <div class="mc-sub up">Checked in</div>
    </div>
    <div class="mc">
        <div class="mc-top" style="background:#ef4444"></div>
        <div class="mc-lbl">Absent Today</div>
        <div class="mc-val">{{ $absentToday }}</div>
        <div class="mc-sub {{ $absentToday > 0 ? 'dn' : 'up' }}">{{ $absentToday > 0 ? 'Absent today' : 'None absent' }}</div>
    </div>
    <div class="mc">
        <div class="mc-top" style="background:#f97316"></div>
        <div class="mc-lbl">Not Marked</div>
        <div class="mc-val">{{ $notMarkedToday }}</div>
        <div class="mc-sub">Attendance pending</div>
    </div>
</div>

<div class="card" style="margin-top:12px">
    <div class="card-h">
        <div class="card-t">Employee Attendance — {{ now()->format('d M Y') }}</div>
        <div style="display:flex;gap:12px;font-size:11px;color:#7a8fa6">
            <span><span class="att-dot" style="background:#22c55e"></span>Present</span>
            <span><span class="att-dot" style="background:#ef4444"></span>Absent</span>
            <span><span class="att-dot" style="background:#f97316"></span>On Leave</span>
            <span><span class="att-dot" style="background:#e5e7eb"></span>Not Marked</span>
        </div>
    </div>
    <table class="dt">
        <thead><tr><th>Employee</th><th>Role</th><th>Department</th><th>Today</th><th>This Month</th><th>Status</th></tr></thead>
        <tbody>
        @forelse($employees as $emp)
            @php
                $todayAtt = $emp->attendance->where('date', today()->toDateString())->first();
                $todayStatus = $todayAtt ? $todayAtt->status : 'not_marked';
                $dotColor = match($todayStatus) {
                    'present'  => '#22c55e',
                    'absent'   => '#ef4444',
                    'half_day' => '#f97316',
                    'on_leave' => '#a855f7',
                    default    => '#e5e7eb'
                };
                $monthDays = now()->daysInMonth;
                $presentDays = $emp->present_count ?? 0;
                $pct = $monthDays > 0 ? round(($presentDays / $monthDays) * 100) : 0;
            @endphp
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px">
                        <div style="width:34px;height:34px;border-radius:50%;background:#e8f0fb;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;color:#00285a;flex-shrink:0">
                            {{ strtoupper(substr($emp->name,0,2)) }}
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:13px;color:#00285a">{{ $emp->name }}</div>
                            <div style="font-size:11px;color:#7a8fa6">{{ $emp->employee_id }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="bdg {{ match($emp->role){'admin'=>'bdg-b','hr'=>'bdg-g','product_manager','product_editor'=>'bdg-p',default=>'bdg-a'} }}">
                        {{ ucfirst(str_replace('_',' ',$emp->role)) }}
                    </span>
                </td>
                <td style="font-size:12px;color:#7a8fa6">{{ $emp->department ?? '—' }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:6px">
                        <span class="att-dot" style="background:{{ $dotColor }}"></span>
                        <span style="font-size:12px;color:#555">{{ ucfirst(str_replace('_',' ',$todayStatus)) }}</span>
                    </div>
                    @if($todayAtt && $todayAtt->check_in)
                        <div style="font-size:11px;color:#7a8fa6">In: {{ $todayAtt->check_in }}</div>
                    @endif
                </td>
                <td>
                    <div style="font-size:12px;font-weight:600;color:#00285a">{{ $presentDays }}/{{ $monthDays }} days</div>
                    <div class="prog" style="width:80px;margin-top:4px">
                        <div class="prog-f" style="width:{{ $pct }}%;background:{{ $pct >= 80 ? '#22c55e' : ($pct >= 60 ? '#f97316' : '#ef4444') }}"></div>
                    </div>
                </td>
                <td>
                    <span class="bdg {{ $emp->status === 'active' ? 'bdg-g' : 'bdg-r' }}">
                        {{ ucfirst($emp->status) }}
                    </span>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" style="text-align:center;padding:24px;color:#7a8fa6">No employees found. <a href="{{ route('admin.employees.create') }}" style="color:#00285a;font-weight:600">Add first employee →</a></td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection
