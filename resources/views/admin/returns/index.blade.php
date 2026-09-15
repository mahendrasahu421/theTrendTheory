{{-- resources/views/admin/returns/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Returns & Refunds Management')
@section('content')

<style>
.rtn-admin-wrap {
    display: flex;
    flex-direction: column;
    gap: 18px;
    font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
}

/* Header */
.rtn-admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}
.rtn-admin-title {
    font-size: 22px;
    font-weight: 800;
    color: #0b192e;
    margin: 0;
    letter-spacing: -0.3px;
}
.rtn-admin-sub {
    font-size: 12.5px;
    color: #64748b;
    margin-top: 2px;
}

/* KPI Bento Grid */
.rtn-stats-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 12px;
}
.rtn-stat-card {
    background: #ffffff;
    border: 1px solid #edf2f7;
    border-radius: 14px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
}
.rtn-stat-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.rtn-stat-val {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.2;
}
.rtn-stat-lbl {
    font-size: 11px;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

/* Filter Bar */
.rtn-filter-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.rtn-search-input {
    min-width: 260px;
    padding: 8px 12px;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    font-size: 12.5px;
    font-family: inherit;
    outline: none;
}
.rtn-search-input:focus { border-color: #00285a; }

.rtn-filter-select {
    padding: 8px 12px;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    font-size: 12.5px;
    font-family: inherit;
    outline: none;
    background: #ffffff;
}

/* Table Card */
.rtn-table-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 16px -4px rgba(15, 23, 42, 0.04);
}
.rtn-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12.5px;
}
.rtn-table th {
    background: #f8fafc;
    padding: 12px 16px;
    font-size: 11px;
    font-weight: 800;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
}
.rtn-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}
.rtn-table tr:hover { background: #fafbfc; }

/* Status Badges */
.rtn-pill {
    display: inline-block;
    padding: 3px 9px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
}
.rtn-p-pending   { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
.rtn-p-approved  { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.rtn-p-rejected  { background: #fff1f2; color: #9f1239; border: 1px solid #fecdd3; }
.rtn-p-completed { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.rtn-p-picked_up, .rtn-p-received { background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; }

.rtn-type-badge {
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 10.5px;
    font-weight: 800;
    text-transform: uppercase;
}
.rtn-t-return { background: #fff1f2; color: #be123c; }
.rtn-t-exchange { background: #eff6ff; color: #1d4ed8; }

.rtn-action-btn {
    padding: 5px 12px;
    background: #00285a;
    color: #ffffff;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.rtn-action-btn:hover { background: #0f4c81; color: #ffffff; }

@media (max-width: 1024px) {
    .rtn-stats-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>

<div class="rtn-admin-wrap">

    {{-- 1. Header --}}
    <div class="rtn-admin-header">
        <div>
            <h1 class="rtn-admin-title">Returns &amp; Refunds Management</h1>
            <p class="rtn-admin-sub">Review customer return requests, process pickups, inspect items, and issue refunds.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:10px;font-size:12.5px;font-weight:600;">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- 2. Stats Bento Grid --}}
    <div class="rtn-stats-grid">
        <div class="rtn-stat-card">
            <div class="rtn-stat-icon" style="background:#eff6ff;color:#2563eb;"><i class="bi bi-arrow-repeat"></i></div>
            <div>
                <div class="rtn-stat-val">{{ number_format($stats['total']) }}</div>
                <div class="rtn-stat-lbl">Total Requests</div>
            </div>
        </div>
        <div class="rtn-stat-card">
            <div class="rtn-stat-icon" style="background:#fff7ed;color:#ea580c;"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="rtn-stat-val">{{ number_format($stats['pending']) }}</div>
                <div class="rtn-stat-lbl">Pending Review</div>
            </div>
        </div>
        <div class="rtn-stat-card">
            <div class="rtn-stat-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-check2-circle"></i></div>
            <div>
                <div class="rtn-stat-val">{{ number_format($stats['approved']) }}</div>
                <div class="rtn-stat-lbl">Approved</div>
            </div>
        </div>
        <div class="rtn-stat-card">
            <div class="rtn-stat-icon" style="background:#f0fdf4;color:#15803d;"><i class="bi bi-shield-check"></i></div>
            <div>
                <div class="rtn-stat-val">{{ number_format($stats['completed']) }}</div>
                <div class="rtn-stat-lbl">Completed</div>
            </div>
        </div>
        <div class="rtn-stat-card">
            <div class="rtn-stat-icon" style="background:#faf5ff;color:#9333ea;"><i class="bi bi-currency-rupee"></i></div>
            <div>
                <div class="rtn-stat-val">₹{{ number_format($stats['refunded']) }}</div>
                <div class="rtn-stat-lbl">Refunded Total</div>
            </div>
        </div>
    </div>

    {{-- 3. Filter Bar --}}
    <form method="GET" action="{{ route('admin.returns.index') }}" class="rtn-filter-card">
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <input type="text" name="search" class="rtn-search-input" value="{{ request('search') }}" placeholder="Search Return #, Order #, Name, Phone...">
            
            <select name="status" class="rtn-filter-select" onchange="this.form.submit()">
                <option value="all">All Statuses</option>
                @foreach(['pending'=>'Pending', 'approved'=>'Approved', 'picked_up'=>'Picked Up', 'received'=>'Received', 'completed'=>'Completed', 'rejected'=>'Rejected'] as $sVal => $sLbl)
                    <option value="{{ $sVal }}" {{ request('status') === $sVal ? 'selected' : '' }}>{{ $sLbl }}</option>
                @endforeach
            </select>

            <select name="type" class="rtn-filter-select" onchange="this.form.submit()">
                <option value="all">All Types</option>
                <option value="return" {{ request('type') === 'return' ? 'selected' : '' }}>Return &amp; Refund</option>
                <option value="exchange" {{ request('type') === 'exchange' ? 'selected' : '' }}>Exchange</option>
            </select>
        </div>

        <div style="display:flex;align-items:center;gap:8px;">
            <button type="submit" class="btn btn-dark btn-sm px-3" style="border-radius:8px;font-weight:700;">Filter</button>
            @if(request()->hasAny(['search', 'status', 'type']))
                <a href="{{ route('admin.returns.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">Clear</a>
            @endif
        </div>
    </form>

    {{-- 4. Table --}}
    <div class="rtn-table-card">
        <div class="table-responsive">
            <table class="rtn-table">
                <thead>
                    <tr>
                        <th>Return #</th>
                        <th>Order Ref</th>
                        <th>Customer</th>
                        <th>Type</th>
                        <th>Reason</th>
                        <th>Refund / Specs</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returns as $rtn)
                    <tr>
                        <td>
                            <strong style="color:#0f172a;">#{{ $rtn->return_number }}</strong>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $rtn->order_id) }}" style="color:#2563eb;font-weight:700;text-decoration:none;">
                                #{{ optional($rtn->order)->order_number }}
                            </a>
                        </td>
                        <td>
                            <div style="font-weight:700;color:#0f172a;">{{ optional($rtn->user)->name ?? 'Customer' }}</div>
                            <div style="font-size:11px;color:#64748b;">{{ optional($rtn->user)->phone ?: optional($rtn->user)->email }}</div>
                        </td>
                        <td>
                            <span class="rtn-type-badge {{ $rtn->type === 'exchange' ? 'rtn-t-exchange' : 'rtn-t-return' }}">
                                {{ $rtn->type }}
                            </span>
                        </td>
                        <td>
                            <span style="font-weight:600;color:#334155;">{{ $rtn->reason_label ?? ucfirst($rtn->reason) }}</span>
                        </td>
                        <td>
                            @if($rtn->type === 'return' && $rtn->refund)
                                <div style="font-weight:800;color:#059669;">₹{{ number_format($rtn->refund->amount) }}</div>
                                <div style="font-size:10.5px;color:#64748b;">{{ ucwords(str_replace('_',' ',$rtn->refund->method)) }}</div>
                            @elseif($rtn->type === 'exchange')
                                <div style="font-weight:700;color:#1d4ed8;">Size: {{ $rtn->exchange_size ?? 'N/A' }}</div>
                                @if($rtn->exchange_color)<div style="font-size:10.5px;color:#64748b;">Color: {{ $rtn->exchange_color }}</div>@endif
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td>
                            <span class="rtn-pill rtn-p-{{ $rtn->status }}">
                                {{ ucwords(str_replace('_',' ',$rtn->status)) }}
                            </span>
                        </td>
                        <td style="color:#64748b;font-size:11.5px;">
                            {{ $rtn->created_at->format('d M Y, h:i A') }}
                        </td>
                        <td style="text-align:right;">
                            <a href="{{ route('admin.returns.show', $rtn->id) }}" class="rtn-action-btn">
                                Manage <i class="bi bi-chevron-right"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align:center;padding:40px;color:#64748b;">
                            <i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:8px;color:#cbd5e1;"></i>
                            No return or exchange requests found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($returns->hasPages())
            <div style="padding:14px 18px;border-top:1px solid #f1f5f9;display:flex;justify-content:flex-end;">
                {{ $returns->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>
@endsection