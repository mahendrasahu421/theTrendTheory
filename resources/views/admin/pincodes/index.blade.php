{{-- resources/views/admin/pincodes/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Pincode Logistics & Risk Intelligence')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

    .pin-studio-wrap {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        display: flex;
        flex-direction: column;
        gap: 20px;
        color: #0f172a;
        max-width: 1440px;
        margin: 0 auto;
    }

    /* ── 1. Top Executive Banner ── */
    .pin-banner {
        background: linear-gradient(135deg, #0b192e 0%, #0f2b54 50%, #1e3a8a 100%);
        border-radius: 20px;
        padding: 26px 32px;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(11, 25, 46, 0.2);
    }
    .pin-banner-glow {
        position: absolute;
        top: -60px;
        right: -60px;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(96, 165, 250, 0.22) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .pin-banner-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        color: #93c5fd;
        margin-bottom: 8px;
    }
    .pin-pulse-dot {
        width: 7px;
        height: 7px;
        background: #60a5fa;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.4);
    }
    .pin-banner-title {
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -0.4px;
        margin: 0 0 6px;
        color: #ffffff;
    }
    .pin-banner-desc {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.82);
        margin: 0;
        max-width: 600px;
        line-height: 1.5;
    }
    .pin-banner-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        position: relative;
        z-index: 2;
    }
    .pin-btn {
        padding: 9px 16px;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: none;
        transition: all 0.18s ease;
    }
    .pin-btn-white {
        background: #ffffff;
        color: #00285a;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .pin-btn-white:hover {
        background: #f8fafc;
        transform: translateY(-1px);
        color: #001636;
    }
    .pin-btn-glass {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.25);
    }
    .pin-btn-glass:hover {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    /* ── 2. Top 4 KPI Bento Cards ── */
    .pin-kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    .pin-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px 20px;
        box-shadow: 0 4px 16px -4px rgba(15, 23, 42, 0.04);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .pin-kpi-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    .pin-kpi-title {
        font-size: 11.5px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .pin-kpi-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }
    .pin-kpi-num {
        font-size: 26px;
        font-weight: 900;
        letter-spacing: -0.5px;
        color: #0f172a;
        line-height: 1.1;
    }
    .pin-kpi-sub {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 4px;
    }

    /* ── 3. Table Card ── */
    .pin-table-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 4px 16px -4px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }
    .pin-filter-bar {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        background: #ffffff;
    }
    .pin-search-box {
        position: relative;
        flex: 1;
        min-width: 240px;
        max-width: 360px;
    }
    .pin-search-input {
        width: 100%;
        padding: 9px 12px 9px 36px;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        font-size: 12.5px;
        font-family: inherit;
        outline: none;
    }
    .pin-search-input:focus {
        border-color: #00285a;
        box-shadow: 0 0 0 3px rgba(0, 40, 90, 0.08);
    }
    .pin-select {
        padding: 9px 14px;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        font-size: 12.5px;
        font-family: inherit;
        font-weight: 600;
        color: #0f172a;
        background: #ffffff;
        outline: none;
    }

    /* Table */
    .pin-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .pin-table th {
        padding: 13px 18px;
        font-size: 11px;
        font-weight: 800;
        color: #475569;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        background: #f8fafc;
        border-bottom: 1.5px solid #e2e8f0;
        text-align: left;
    }
    .pin-table td {
        padding: 13px 18px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 12.5px;
    }
    .pin-table tr:last-child td { border-bottom: none; }
    .pin-table tr:hover { background: #fafbfc; }

    /* Badges */
    .pin-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
    }
    .pin-b-green  { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .pin-b-amber  { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .pin-b-red    { background: #fff1f2; color: #9f1239; border: 1px solid #fecdd3; }
    .pin-b-blue   { background: #f0f4ff; color: #00285a; border: 1px solid #dbeafe; }
    .pin-b-purple { background: #faf5ff; color: #6b21a8; border: 1px solid #e9d5ff; }

    /* Modal Form */
    .pin-modal-body {
        padding: 20px 24px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .pin-form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .pin-form-label {
        font-size: 11.5px;
        font-weight: 800;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .pin-form-input, .pin-form-select, .pin-form-textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
        font-family: inherit;
        outline: none;
        box-sizing: border-box;
    }
    .pin-form-input:focus, .pin-form-select:focus, .pin-form-textarea:focus {
        border-color: #00285a;
        box-shadow: 0 0 0 3px rgba(0, 40, 90, 0.08);
    }

    @media (max-width: 960px) {
        .pin-kpi-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 600px) {
        .pin-kpi-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="pin-studio-wrap">

   
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:12px;font-size:12.5px;font-weight:700;">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- 2. Top 4 KPI Bento Cards --}}
    <div class="pin-kpi-grid">
        <div class="pin-kpi-card">
            <div class="pin-kpi-head">
                <span class="pin-kpi-title">Custom Rules Set</span>
                <div class="pin-kpi-icon" style="background:#eff6ff;color:#1d4ed8;"><i class="bi bi-geo-alt-fill"></i></div>
            </div>
            <div class="pin-kpi-num">{{ number_format($totalTracked) }}</div>
            <div class="pin-kpi-sub">Pincodes with customized rules</div>
        </div>

        <div class="pin-kpi-card" style="{{ $codBlockedCount > 0 ? 'border-color:#fed7aa;' : '' }}">
            <div class="pin-kpi-head">
                <span class="pin-kpi-title" style="{{ $codBlockedCount > 0 ? 'color:#c2410c;' : '' }}">COD Restricted</span>
                <div class="pin-kpi-icon" style="background:#fff7ed;color:#c2410c;"><i class="bi bi-cash-stack"></i></div>
            </div>
            <div class="pin-kpi-num" style="color:#c2410c;">{{ number_format($codBlockedCount) }}</div>
            <div class="pin-kpi-sub">Prepaid / UPI mandatory areas</div>
        </div>

        <div class="pin-kpi-card" style="{{ $exchangeOnlyCount > 0 ? 'border-color:#fde68a;' : '' }}">
            <div class="pin-kpi-head">
                <span class="pin-kpi-title" style="{{ $exchangeOnlyCount > 0 ? 'color:#b45309;' : '' }}">Exchange-Only Areas</span>
                <div class="pin-kpi-icon" style="background:#fffbeb;color:#b45309;"><i class="bi bi-arrow-left-right"></i></div>
            </div>
            <div class="pin-kpi-num" style="color:#b45309;">{{ number_format($exchangeOnlyCount) }}</div>
            <div class="pin-kpi-sub">No refund &bull; Size/Color replacement only</div>
        </div>

        <div class="pin-kpi-card" style="{{ $highRiskCount > 0 ? 'border-color:#fecdd3;' : '' }}">
            <div class="pin-kpi-head">
                <span class="pin-kpi-title" style="{{ $highRiskCount > 0 ? 'color:#9f1239;' : '' }}">High Risk Flags</span>
                <div class="pin-kpi-icon" style="background:#fff1f2;color:#9f1239;"><i class="bi bi-shield-exclamation"></i></div>
            </div>
            <div class="pin-kpi-num" style="color:#9f1239;">{{ number_format($highRiskCount) }}</div>
            <div class="pin-kpi-sub">High return / RTO cancellation rate</div>
        </div>
    </div>

    {{-- 3. Main Data Card --}}
    <div class="pin-table-card">
        {{-- Filter Toolbar --}}
        <form method="GET" action="{{ route('admin.pincodes.index') }}" class="pin-filter-bar">
            <div class="pin-search-box">
                <i class="bi bi-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:13px;"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search Pincode, City, State..." class="pin-search-input">
            </div>

            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                <select name="zone" class="pin-select" onchange="this.form.submit()">
                    <option value="">All Zones</option>
                    <option value="Metro" {{ $zone === 'Metro' ? 'selected' : '' }}>Metro (Fast 2-3 Days)</option>
                    <option value="Tier-1" {{ $zone === 'Tier-1' ? 'selected' : '' }}>Tier-1 (3-4 Days)</option>
                    <option value="Standard" {{ $zone === 'Standard' ? 'selected' : '' }}>Standard (4-5 Days)</option>
                    <option value="Remote" {{ $zone === 'Remote' ? 'selected' : '' }}>Remote / Special (5-7 Days)</option>
                </select>

                <select name="cod" class="pin-select" onchange="this.form.submit()">
                    <option value="">COD Status (All)</option>
                    <option value="1" {{ $cod === '1' ? 'selected' : '' }}>COD Allowed</option>
                    <option value="0" {{ $cod === '0' ? 'selected' : '' }}>COD Blocked</option>
                </select>

                <select name="exchange" class="pin-select" onchange="this.form.submit()">
                    <option value="">Policy (All)</option>
                    <option value="0" {{ $exchange === '0' ? 'selected' : '' }}>Standard Return &amp; Refund</option>
                    <option value="1" {{ $exchange === '1' ? 'selected' : '' }}>Exchange Only (No Refund)</option>
                </select>

                <select name="risk" class="pin-select" onchange="this.form.submit()">
                    <option value="">Risk Level (All)</option>
                    <option value="low" {{ $risk === 'low' ? 'selected' : '' }}>Low Risk</option>
                    <option value="medium" {{ $risk === 'medium' ? 'selected' : '' }}>Medium Risk</option>
                    <option value="high" {{ $risk === 'high' ? 'selected' : '' }}>High Risk</option>
                </select>

                @if($search || $zone || $cod !== '' || $exchange !== '' || $risk)
                    <a href="{{ route('admin.pincodes.index') }}" class="pin-btn" style="background:#f1f5f9;color:#475569;" title="Reset filters">
                        <i class="bi bi-x-circle"></i> Clear
                    </a>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div style="overflow-x:auto;">
            <table class="pin-table">
                <thead>
                    <tr>
                        <th style="width: 140px;">PINCODE</th>
                        <th>CITY / REGION</th>
                        <th>ZONE &amp; SPEED</th>
                        <th>COD STATUS</th>
                        <th>RETURN POLICY</th>
                        <th>ORDER STATS</th>
                        <th>RISK LEVEL</th>
                        <th style="text-align: right; width: 140px;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pincodes as $pin)
                        <tr>
                            <td>
                                <strong style="font-size:14px;font-family:monospace;color:#00285a;letter-spacing:0.5px;">
                                    {{ $pin->pincode }}
                                </strong>
                            </td>

                            <td>
                                <div style="font-weight:700;color:#0f172a;">{{ $pin->city ?: '—' }}</div>
                                <div style="font-size:11px;color:#64748b;">{{ $pin->state ?: 'All India' }}</div>
                            </td>

                            <td>
                                <span class="pin-badge pin-b-blue">
                                    <i class="bi bi-truck"></i> {{ $pin->zone ?: 'Standard' }} ({{ $pin->delivery_days_min }}-{{ $pin->delivery_days_max }} Days)
                                </span>
                            </td>

                            <td>
                                <form method="POST" action="{{ route('admin.pincodes.toggle-cod', $pin->id) }}" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    @if($pin->is_cod_allowed)
                                        <button type="submit" class="pin-badge pin-b-green" style="border:none;cursor:pointer;" title="Click to block COD for this pincode">
                                            <i class="bi bi-check-circle-fill"></i> COD Allowed
                                        </button>
                                    @else
                                        <button type="submit" class="pin-badge pin-b-red" style="border:none;cursor:pointer;" title="Click to enable COD for this pincode">
                                            <i class="bi bi-slash-circle-fill"></i> COD Blocked
                                        </button>
                                    @endif
                                </form>
                            </td>

                            <td>
                                <form method="POST" action="{{ route('admin.pincodes.toggle-exchange', $pin->id) }}" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    @if($pin->is_exchange_only)
                                        <button type="submit" class="pin-badge pin-b-amber" style="border:none;cursor:pointer;" title="Click to allow full returns">
                                            <i class="bi bi-arrow-left-right"></i> Exchange Only
                                        </button>
                                    @else
                                        <button type="submit" class="pin-badge pin-b-green" style="border:none;cursor:pointer;" title="Click to restrict to exchange only">
                                            <i class="bi bi-arrow-counterclockwise"></i> Returns &amp; Refund OK
                                        </button>
                                    @endif
                                </form>
                            </td>

                            <td>
                                <div style="font-size:11.5px;color:#334155;">
                                    <strong>{{ $pin->total_orders }}</strong> Orders &bull; 
                                    <span style="color:#c2410c;">{{ $pin->cod_orders }} COD</span>
                                </div>
                                <div style="font-size:11px;color:#64748b;margin-top:2px;">
                                    Ret: <strong>{{ $pin->returned_orders }} ({{ $pin->return_rate }}%)</strong> &bull; 
                                    RTO: <strong>{{ $pin->rto_orders }} ({{ $pin->rto_rate }}%)</strong>
                                </div>
                            </td>

                            <td>
                                @if($pin->risk_level === 'high')
                                    <span class="pin-badge pin-b-red"><i class="bi bi-exclamation-triangle-fill"></i> High Risk</span>
                                @elseif($pin->risk_level === 'medium')
                                    <span class="pin-badge pin-b-amber">Medium Risk</span>
                                @else
                                    <span class="pin-badge pin-b-green"><i class="bi bi-shield-check"></i> Low Risk</span>
                                @endif
                            </td>

                            <td style="text-align: right;">
                                <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;">
                                    <form method="POST" action="{{ route('admin.pincodes.destroy', $pin->id) }}" onsubmit="return confirm('Remove custom override for pincode {{ $pin->pincode }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="pin-btn" style="background:#fff1f2;color:#9f1239;padding:6px 10px;font-size:11.5px;" title="Delete rule">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:50px 20px;color:#64748b;">
                                <i class="bi bi-geo-alt" style="font-size:36px;color:#cbd5e1;display:block;margin-bottom:8px;"></i>
                                <strong style="color:#0f172a;font-size:14px;display:block;">No Pincode Rules Found</strong>
                                <p style="font-size:12px;margin:4px 0 14px;">All standard Indian pincodes are automatically resolved by the intelligent delivery engine.</p>
                                <button type="button" class="pin-btn pin-btn-white" style="background:#00285a;color:#ffffff;" data-bs-toggle="modal" data-bs-target="#addPincodeModal">
                                    + Add First Rule
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pincodes->hasPages())
            <div style="padding:14px 20px;border-top:1px solid #f1f5f9;">
                {{ $pincodes->links() }}
            </div>
        @endif
    </div>

</div>

{{-- ── Add / Edit Pincode Modal ── --}}
<div class="modal fade" id="addPincodeModal" tabindex="-1" aria-labelledby="addPincodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:18px;border:none;box-shadow:0 20px 40px rgba(0,0,0,0.15);overflow:hidden;">
            <div class="modal-header" style="background:#00285a;color:#ffffff;padding:16px 22px;">
                <h5 class="modal-title" id="addPincodeModalLabel" style="font-size:15px;font-weight:800;letter-spacing:-0.2px;">
                    <i class="bi bi-geo-alt-fill me-1"></i> Add / Override Pincode Rule
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('admin.pincodes.store') }}">
                @csrf
                <div class="pin-modal-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div class="pin-form-group">
                            <label class="pin-form-label">6-Digit Pincode *</label>
                            <input type="text" name="pincode" maxlength="6" class="pin-form-input" placeholder="e.g. 110001" required>
                        </div>
                        <div class="pin-form-group">
                            <label class="pin-form-label">Zone Tier *</label>
                            <select name="zone" class="pin-form-select" required>
                                <option value="Metro">Metro (Fast)</option>
                                <option value="Tier-1">Tier-1 City</option>
                                <option value="Standard" selected>Standard Zone</option>
                                <option value="Remote">Remote / Special</option>
                            </select>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div class="pin-form-group">
                            <label class="pin-form-label">City / District</label>
                            <input type="text" name="city" class="pin-form-input" placeholder="e.g. New Delhi">
                        </div>
                        <div class="pin-form-group">
                            <label class="pin-form-label">State</label>
                            <input type="text" name="state" class="pin-form-input" placeholder="e.g. Delhi">
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div class="pin-form-group">
                            <label class="pin-form-label">Delivery Days (Min) *</label>
                            <input type="number" name="delivery_days_min" value="2" min="1" max="30" class="pin-form-input" required>
                        </div>
                        <div class="pin-form-group">
                            <label class="pin-form-label">Delivery Days (Max) *</label>
                            <input type="number" name="delivery_days_max" value="4" min="1" max="30" class="pin-form-input" required>
                        </div>
                    </div>

                    <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;padding:12px 14px;display:flex;flex-direction:column;gap:8px;">
                        <label style="font-size:12px;font-weight:700;display:flex;align-items:center;gap:8px;cursor:pointer;">
                            <input type="checkbox" name="is_cod_allowed" value="1" checked style="width:16px;height:16px;">
                            <span>Allow Cash on Delivery (COD)</span>
                        </label>
                        <label style="font-size:12px;font-weight:700;display:flex;align-items:center;gap:8px;cursor:pointer;">
                            <input type="checkbox" name="is_exchange_only" value="1" style="width:16px;height:16px;">
                            <span style="color:#b45309;">Enforce Exchange Only (Disallow Cash/Bank Returns)</span>
                        </label>
                    </div>

                    <div class="pin-form-group">
                        <label class="pin-form-label">Risk Level *</label>
                        <select name="risk_level" class="pin-form-select" required>
                            <option value="low">Low Risk (Normal)</option>
                            <option value="medium">Medium Risk</option>
                            <option value="high">High Risk (Fraud / High RTO alert)</option>
                        </select>
                    </div>

                    <div class="pin-form-group">
                        <label class="pin-form-label">Admin Notes / Remarks</label>
                        <textarea name="admin_notes" rows="2" class="pin-form-textarea" placeholder="Internal courier notes..."></textarea>
                    </div>
                </div>

                <div class="modal-footer" style="padding:14px 22px;background:#f8fafc;border-top:1px solid #e2e8f0;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="font-weight:700;font-size:13px;border-radius:10px;">Cancel</button>
                    <button type="submit" class="btn" style="background:#00285a;color:#ffffff;font-weight:800;font-size:13px;border-radius:10px;padding:8px 18px;">
                        <i class="bi bi-check2-circle me-1"></i> Save Pincode Rule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection