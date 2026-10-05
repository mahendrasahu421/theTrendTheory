@extends('admin.layouts.app')
@section('title', 'Newsletter Subscribers')

@section('content')
<style>
    .page-hdr {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .page-title {
        font-family: 'Cinzel', serif;
        font-size: 18px;
        font-weight: 700;
        color: #00285a;
        letter-spacing: 0.5px;
    }
    .page-sub {
        font-size: 12px;
        color: #64748b;
        margin-top: 3px;
    }

    /* KPI Cards */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 14px;
        margin-bottom: 20px;
    }
    .kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 18px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .kpi-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .kpi-val {
        font-family: 'Cinzel', serif;
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.1;
    }
    .kpi-lbl {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 3px;
    }

    /* Card & Table */
    .table-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .filter-bar {
        padding: 16px 18px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        background: #fafafa;
    }
    .filter-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .search-input {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 7px 14px 7px 32px;
        font-size: 13px;
        width: 260px;
        background: #fff url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="%2394a3b8" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/></svg>') no-repeat 10px center;
        outline: none;
    }
    .search-input:focus {
        border-color: #00285a;
        box-shadow: 0 0 0 2px rgba(0,40,90,0.1);
    }
    .filter-select {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 7px 12px;
        font-size: 13px;
        background: #fff;
        color: #334155;
        outline: none;
    }

    .btn-brand {
        background: #00285a;
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-brand:hover {
        background: #001f44;
        color: #fff;
    }
    .btn-outline-custom {
        background: #fff;
        color: #334155;
        border: 1px solid #cbd5e1;
        padding: 7px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-outline-custom:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }

    /* Table styling */
    .nl-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .nl-table th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }
    .nl-table td {
        padding: 13px 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #1e293b;
    }
    .nl-table tr:last-child td {
        border-bottom: none;
    }
    .nl-table tr:hover td {
        background: #fbfcfe;
    }

    /* Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }
    .status-badge.active {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
    .status-badge.inactive {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }
    .source-badge {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    /* Modal */
    .modal-backdrop-custom {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.5);
        z-index: 1050;
        align-items: center;
        justify-content: center;
    }
    .modal-backdrop-custom.show {
        display: flex;
    }
    .modal-box {
        background: #ffffff;
        border-radius: 14px;
        width: 100%;
        max-width: 480px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    .modal-hdr {
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-title {
        font-family: 'Cinzel', serif;
        font-size: 15px;
        font-weight: 700;
        color: #00285a;
    }
    .modal-bdy {
        padding: 20px;
    }
    .modal-ftr {
        padding: 14px 20px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        background: #f8fafc;
    }
</style>

<div class="page-hdr">
    <div>
        <h1 class="page-title">Newsletter Subscribers</h1>
        <p class="page-sub">Manage email marketing subscriptions, customer reach, and drops audience.</p>
    </div>
    <div style="display:flex; gap:10px;">
        <a href="{{ route('admin.newsletter.export', request()->query()) }}" class="btn-outline-custom">
            <i class="bi bi-download"></i> Export CSV
        </a>
        <button type="button" class="btn-brand" onclick="openAddModal()">
            <i class="bi bi-plus-lg"></i> Add Subscriber
        </button>
    </div>
</div>

{{-- KPI Metric Cards --}}
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-icon" style="background:#eff6ff; color:#00285a;">
            <i class="bi bi-people-fill"></i>
        </div>
        <div>
            <div class="kpi-val">{{ number_format($kpi['total']) }}</div>
            <div class="kpi-lbl">Total Subscribers</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon" style="background:#f0fdf4; color:#16a34a;">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div>
            <div class="kpi-val" style="color:#16a34a;">{{ number_format($kpi['active']) }}</div>
            <div class="kpi-lbl">Active & Verified</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon" style="background:#fef2f2; color:#dc2626;">
            <i class="bi bi-dash-circle-fill"></i>
        </div>
        <div>
            <div class="kpi-val" style="color:#dc2626;">{{ number_format($kpi['inactive']) }}</div>
            <div class="kpi-lbl">Unsubscribed</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon" style="background:#fffbeb; color:#d97706;">
            <i class="bi bi-lightning-charge-fill"></i>
        </div>
        <div>
            <div class="kpi-val" style="color:#d97706;">{{ number_format($kpi['today']) }}</div>
            <div class="kpi-lbl">Joined Today</div>
        </div>
    </div>
</div>

{{-- Main Table Card --}}
<div class="table-card">
    {{-- Search & Filters --}}
    <form method="GET" action="{{ route('admin.newsletter.index') }}" class="filter-bar">
        <div class="filter-group">
            <input type="text" name="search" value="{{ $search }}" class="search-input" placeholder="Search email, source, IP...">
            
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active Only</option>
                <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
            </select>

            <select name="sort" class="filter-select" onchange="this.form.submit()">
                <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Newest First</option>
                <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest First</option>
            </select>

            <button type="submit" class="btn-outline-custom" style="padding:7px 12px;">Filter</button>
            @if($search || $status !== 'all' || $sort !== 'latest')
                <a href="{{ route('admin.newsletter.index') }}" class="btn-outline-custom" style="padding:7px 12px; color:#ef4444;" title="Reset filters">
                    <i class="bi bi-x-circle"></i> Reset
                </a>
            @endif
        </div>

        {{-- Bulk Action Form --}}
        <div id="bulkActionsPane" style="display:none; align-items:center; gap:8px;">
            <span id="selectedCount" style="font-size:12px; font-weight:600; color:#475569;">0 selected</span>
            <button type="button" class="btn-outline-custom" style="color:#16a34a; padding:5px 10px; font-size:12px;" onclick="submitBulk('activate')">
                <i class="bi bi-check-lg"></i> Activate
            </button>
            <button type="button" class="btn-outline-custom" style="color:#d97706; padding:5px 10px; font-size:12px;" onclick="submitBulk('deactivate')">
                <i class="bi bi-slash-circle"></i> Deactivate
            </button>
            <button type="button" class="btn-outline-custom" style="color:#dc2626; padding:5px 10px; font-size:12px;" onclick="submitBulk('delete')">
                <i class="bi bi-trash"></i> Delete
            </button>
        </div>
    </form>

    {{-- Bulk Action Hidden Form --}}
    <form id="bulkActionForm" method="POST" action="{{ route('admin.newsletter.bulk') }}" style="display:none;">
        @csrf
        <input type="hidden" name="action" id="bulkActionInput">
        <div id="bulkIdsContainer"></div>
    </form>

    {{-- Table --}}
    <div style="overflow-x:auto;">
        <table class="nl-table">
            <thead>
                <tr>
                    <th style="width:40px; text-align:center;">
                        <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)">
                    </th>
                    <th>Subscriber Email</th>
                    <th>Status</th>
                    <th>Source</th>
                    <th>IP / Location</th>
                    <th>Subscribed At</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $sub)
                    <tr id="row-{{ $sub->id }}">
                        <td style="text-align:center;">
                            <input type="checkbox" class="sub-checkbox" value="{{ $sub->id }}" onchange="onCheckboxChange()">
                        </td>
                        <td>
                            <div style="font-weight:700; color:#0f172a;">{{ $sub->email }}</div>
                            @if($sub->unsubscribed_at)
                                <div style="font-size:11px; color:#ef4444; margin-top:2px;">
                                    Unsubscribed on {{ $sub->unsubscribed_at->format('d M Y, h:i A') }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge {{ $sub->is_active ? 'active' : 'inactive' }}" id="badge-{{ $sub->id }}">
                                <i class="bi {{ $sub->is_active ? 'bi-check-circle-fill' : 'bi-dash-circle-fill' }}"></i>
                                {{ $sub->is_active ? 'Active' : 'Unsubscribed' }}
                            </span>
                        </td>
                        <td>
                            <span class="source-badge">{{ $sub->source ?: 'footer' }}</span>
                        </td>
                        <td>
                            <div style="font-size:12px; font-family:monospace; color:#475569;">{{ $sub->ip_address ?: 'Unknown IP' }}</div>
                        </td>
                        <td>
                            <div style="font-size:12px; color:#334155;">{{ $sub->created_at ? $sub->created_at->format('d M Y') : 'N/A' }}</div>
                            <div style="font-size:11px; color:#94a3b8;">{{ $sub->created_at ? $sub->created_at->format('h:i A') : '' }}</div>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:inline-flex; align-items:center; gap:6px;">
                                {{-- Status Toggle Form / Button --}}
                                <form method="POST" action="{{ route('admin.newsletter.toggle', $sub) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-outline-custom" style="padding:5px 9px; font-size:12px;" title="{{ $sub->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="bi {{ $sub->is_active ? 'bi-toggle-on text-success' : 'bi-toggle-off text-muted' }}" style="font-size:15px;"></i>
                                    </button>
                                </form>

                                {{-- Delete Form --}}
                                <form method="POST" action="{{ route('admin.newsletter.destroy', $sub) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this subscriber?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-outline-custom" style="padding:5px 9px; font-size:12px; color:#ef4444;" title="Delete subscriber">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:48px 16px; color:#94a3b8;">
                            <div style="font-size:36px; margin-bottom:8px;"><i class="bi bi-envelope-open"></i></div>
                            <div style="font-size:14px; font-weight:600; color:#475569;">No subscribers found</div>
                            <div style="font-size:12px; margin-top:4px;">No one has subscribed matching your filter criteria.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($subscribers->hasPages())
        <div style="padding:14px 18px; border-top:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
            <div style="font-size:12px; color:#64748b;">
                Showing {{ $subscribers->firstItem() }} to {{ $subscribers->lastItem() }} of {{ $subscribers->total() }} subscribers
            </div>
            <div>
                {{ $subscribers->links() }}
            </div>
        </div>
    @endif
</div>

{{-- Add Subscriber Modal --}}
<div class="modal-backdrop-custom" id="addModal">
    <div class="modal-box">
        <form method="POST" action="{{ route('admin.newsletter.store') }}">
            @csrf
            <div class="modal-hdr">
                <div class="modal-title"><i class="bi bi-envelope-plus me-1"></i> Add Newsletter Subscriber</div>
                <button type="button" onclick="closeAddModal()" style="background:none; border:none; font-size:18px; color:#94a3b8; cursor:pointer;">&times;</button>
            </div>
            <div class="modal-bdy">
                <div style="margin-bottom:14px;">
                    <label style="display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:5px;">Subscriber Email *</label>
                    <input type="email" name="email" required placeholder="user@example.com" class="form-control" style="width:100%; border:1px solid #cbd5e1; border-radius:8px; padding:8px 12px; font-size:13px;">
                </div>
                <div style="margin-bottom:14px;">
                    <label style="display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:5px;">Source / Channel</label>
                    <select name="source" class="form-control" style="width:100%; border:1px solid #cbd5e1; border-radius:8px; padding:8px 12px; font-size:13px;">
                        <option value="admin">Admin Panel Manual Entry</option>
                        <option value="checkout">Checkout Customer Opt-in</option>
                        <option value="campaign">Marketing Campaign</option>
                        <option value="popup">Website Popup</option>
                    </select>
                </div>
                <div style="display:flex; align-items:center; gap:8px;">
                    <input type="checkbox" name="is_active" id="modalActive" value="1" checked>
                    <label for="modalActive" style="font-size:13px; font-weight:600; color:#334155; cursor:pointer;">Mark as active and opted-in</label>
                </div>
            </div>
            <div class="modal-ftr">
                <button type="button" class="btn-outline-custom" onclick="closeAddModal()">Cancel</button>
                <button type="submit" class="btn-brand">Save Subscriber</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').classList.add('show');
    }
    function closeAddModal() {
        document.getElementById('addModal').classList.remove('show');
    }

    function toggleSelectAll(master) {
        const boxes = document.querySelectorAll('.sub-checkbox');
        boxes.forEach(b => b.checked = master.checked);
        onCheckboxChange();
    }

    function onCheckboxChange() {
        const checked = document.querySelectorAll('.sub-checkbox:checked');
        const pane = document.getElementById('bulkActionsPane');
        const counter = document.getElementById('selectedCount');
        if (checked.length > 0) {
            pane.style.display = 'flex';
            counter.innerText = checked.length + ' selected';
        } else {
            pane.style.display = 'none';
        }
    }

    function submitBulk(actionName) {
        const checked = document.querySelectorAll('.sub-checkbox:checked');
        if (checked.length === 0) return;
        if (actionName === 'delete' && !confirm('Are you sure you want to delete ' + checked.length + ' selected subscribers?')) {
            return;
        }

        const container = document.getElementById('bulkIdsContainer');
        container.innerHTML = '';
        checked.forEach(b => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = b.value;
            container.appendChild(input);
        });

        document.getElementById('bulkActionInput').value = actionName;
        document.getElementById('bulkActionForm').submit();
    }
</script>
@endsection
