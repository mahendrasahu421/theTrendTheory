{{-- resources/views/admin/pincodes/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Pincodes & Logistics Intelligence Hub')

@section('content')
@php
    $brandName = config('app.name', 'The Trend');
@endphp

<div class="pin-hub-container">

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- 1. LUXURY EXECUTIVE HERO BANNER                                  --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="pin-hero-card">
        <div class="pin-aurora-orb pin-orb-1"></div>
        <div class="pin-aurora-orb pin-orb-2"></div>

        <div class="pin-hero-inner">
            <div class="pin-hero-left">
                <div class="pin-hero-badge">
                    <span class="pin-beacon-pulse"></span>
                    <span>LOGISTICS &amp; RTO INTELLIGENCE ENGINE</span>
                </div>
                <h1 class="pin-hero-title">Pincodes &amp; Delivery Matrix</h1>
                <p class="pin-hero-desc">
                    Configure delivery speeds, enforce COD restrictions in high-RTO zones, restrict returns to exchange-only, and manage custom postal rules across India.
                </p>
            </div>

            <div class="pin-hero-actions">
                {{-- Auto-Analyze Risk Action --}}
                <form method="POST" action="{{ route('admin.pincodes.auto-analyze') }}" class="d-inline" onsubmit="return confirm('Run automated RTO risk analysis across all historical customer orders?');">
                    @csrf
                    <button type="submit" class="pin-btn-glass pin-btn-analyze" title="Analyze return and RTO rates across all orders">
                        <i class="bi bi-lightning-charge-fill text-warning"></i>
                        <span>Auto-Analyze Risk</span>
                    </button>
                </form>

                {{-- Add Pincode Override Modal Trigger --}}
                <button type="button" class="pin-btn-glass pin-btn-add" data-bs-toggle="modal" data-bs-target="#addPincodeModal">
                    <i class="bi bi-plus-lg"></i>
                    <span>Add Pincode Override</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Session Flash Feedback --}}
    @if(session('success'))
        <div class="pin-flash-alert alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- 2. ANIMATED BENTO KPI METRIC CARDS                               --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="pin-kpi-grid">
        
        {{-- Card 1: Total Custom Rules --}}
        <div class="pin-kpi-glass-card" style="--anim-delay: 0.05s;">
            <div class="pin-kpi-top">
                <div class="pin-kpi-meta">
                    <span class="pin-kpi-tag">RULE INVENTORY</span>
                    <h3 class="pin-kpi-heading">Active Overrides</h3>
                </div>
                <div class="pin-kpi-icon-pod pod-blue">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
            </div>
            <div class="pin-kpi-val">{{ number_format($totalTracked) }}</div>
            <div class="pin-kpi-foot">
                <i class="bi bi-shield-check text-primary"></i>
                <span>Custom logistics rules defined</span>
            </div>
        </div>

        {{-- Card 2: COD Blocked / Prepaid Mandatory --}}
        <div class="pin-kpi-glass-card" style="--anim-delay: 0.10s;">
            <div class="pin-kpi-top">
                <div class="pin-kpi-meta">
                    <span class="pin-kpi-tag">RTO DEFENSE</span>
                    <h3 class="pin-kpi-heading">COD Restricted</h3>
                </div>
                <div class="pin-kpi-icon-pod pod-orange">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
            <div class="pin-kpi-val text-orange">{{ number_format($codBlockedCount) }}</div>
            <div class="pin-kpi-foot">
                <i class="bi bi-lock-fill text-orange"></i>
                <span>Prepaid / UPI mandatory zones</span>
            </div>
        </div>

        {{-- Card 3: Exchange Only Zones --}}
        <div class="pin-kpi-glass-card" style="--anim-delay: 0.15s;">
            <div class="pin-kpi-top">
                <div class="pin-kpi-meta">
                    <span class="pin-kpi-tag">POLICY RESTRICTION</span>
                    <h3 class="pin-kpi-heading">Exchange-Only Areas</h3>
                </div>
                <div class="pin-kpi-icon-pod pod-amber">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
            </div>
            <div class="pin-kpi-val text-amber">{{ number_format($exchangeOnlyCount) }}</div>
            <div class="pin-kpi-foot">
                <i class="bi bi-arrow-repeat text-amber"></i>
                <span>Zero refund &bull; Size swap only</span>
            </div>
        </div>

        {{-- Card 4: High Risk Flags --}}
        <div class="pin-kpi-glass-card" style="--anim-delay: 0.20s;">
            <div class="pin-kpi-top">
                <div class="pin-kpi-meta">
                    <span class="pin-kpi-tag">RISK TELEMETRY</span>
                    <h3 class="pin-kpi-heading">High Risk Flags</h3>
                </div>
                <div class="pin-kpi-icon-pod pod-rose">
                    <i class="bi bi-shield-exclamation"></i>
                </div>
            </div>
            <div class="pin-kpi-val text-rose">{{ number_format($highRiskCount) }}</div>
            <div class="pin-kpi-foot">
                <i class="bi bi-exclamation-octagon text-rose"></i>
                <span>Elevated RTO / cancellation rate</span>
            </div>
        </div>

    </div>

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- 3. MAIN LOGISTICS DATA TABLE & FILTER SUITE                      --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="pin-table-container">

        {{-- Interactive Filter Toolbar --}}
        <form method="GET" action="{{ route('admin.pincodes.index') }}" class="pin-filter-toolbar">
            <div class="pin-search-group">
                <i class="bi bi-search pin-search-icon"></i>
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Search by 6-digit Pincode, City, or State..." 
                       class="pin-search-input"
                       autocomplete="off">
                @if($search)
                    <a href="{{ route('admin.pincodes.index', request()->except('search')) }}" class="pin-search-clear" title="Clear search">
                        <i class="bi bi-x"></i>
                    </a>
                @endif
            </div>

            <div class="pin-filter-dropdowns">
                {{-- Zone Tier --}}
                <select name="zone" class="pin-select-box" onchange="this.form.submit()">
                    <option value="">All Zones</option>
                    <option value="Metro" {{ $zone === 'Metro' ? 'selected' : '' }}>⚡ Metro (Fast 2-3 Days)</option>
                    <option value="Tier-1" {{ $zone === 'Tier-1' ? 'selected' : '' }}>🏙️ Tier-1 (3-4 Days)</option>
                    <option value="Standard" {{ $zone === 'Standard' ? 'selected' : '' }}>📦 Standard (4-5 Days)</option>
                    <option value="Remote" {{ $zone === 'Remote' ? 'selected' : '' }}>🏔️ Remote / Special (5-7 Days)</option>
                </select>

                {{-- COD Status --}}
                <select name="cod" class="pin-select-box" onchange="this.form.submit()">
                    <option value="">COD Status (All)</option>
                    <option value="1" {{ $cod === '1' ? 'selected' : '' }}>🟢 COD Allowed</option>
                    <option value="0" {{ $cod === '0' ? 'selected' : '' }}>🔴 COD Blocked</option>
                </select>

                {{-- Return / Exchange Policy --}}
                <select name="exchange" class="pin-select-box" onchange="this.form.submit()">
                    <option value="">Policy (All)</option>
                    <option value="0" {{ $exchange === '0' ? 'selected' : '' }}>🔄 Standard Return &amp; Refund</option>
                    <option value="1" {{ $exchange === '1' ? 'selected' : '' }}>⚠️ Exchange Only (No Refund)</option>
                </select>

                {{-- Risk Level --}}
                <select name="risk" class="pin-select-box" onchange="this.form.submit()">
                    <option value="">Risk Level (All)</option>
                    <option value="low" {{ $risk === 'low' ? 'selected' : '' }}>🟢 Low Risk</option>
                    <option value="medium" {{ $risk === 'medium' ? 'selected' : '' }}>🟡 Medium Risk</option>
                    <option value="high" {{ $risk === 'high' ? 'selected' : '' }}>🔴 High Risk</option>
                </select>

                @if($search || $zone || $cod !== '' || $exchange !== '' || $risk)
                    <a href="{{ route('admin.pincodes.index') }}" class="pin-btn-reset" title="Reset all filters">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                    </a>
                @endif
            </div>
        </form>

        {{-- Logistics Rules Table --}}
        <div class="pin-table-scroll">
            <table class="pin-luxury-table">
                <thead>
                    <tr>
                        <th style="width: 150px;">PINCODE</th>
                        <th>CITY &amp; REGION</th>
                        <th>ZONE &amp; DELIVERY SPEED</th>
                        <th>COD CHANNEL</th>
                        <th>RETURN / EXCHANGE POLICY</th>
                        <th>HISTORICAL FOOTPRINT</th>
                        <th>RISK LEVEL</th>
                        <th style="text-align: right; width: 110px;">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pincodes as $pin)
                        <tr id="pinRow{{ $pin->id }}">
                            {{-- Pincode with Copy Button --}}
                            <td>
                                <div class="pincode-cell">
                                    <span class="pincode-mono">{{ $pin->pincode }}</span>
                                    <button type="button" 
                                            class="btn-copy-pin" 
                                            onclick="copyPinText('{{ $pin->pincode }}', this)" 
                                            title="Copy Pincode">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </td>

                            {{-- City & State --}}
                            <td>
                                <div class="city-name">{{ $pin->city ?: '—' }}</div>
                                <div class="state-sub">{{ $pin->state ?: 'All India Coverage' }}</div>
                            </td>

                            {{-- Zone & Speed --}}
                            <td>
                                <span class="badge-zone {{ strtolower($pin->zone ?? 'standard') }}">
                                    <i class="bi bi-truck"></i>
                                    <span>{{ $pin->zone ?: 'Standard' }}</span>
                                    <strong class="days-spec">({{ $pin->delivery_days_min }}–{{ $pin->delivery_days_max }} Days)</strong>
                                </span>
                            </td>

                            {{-- Instant Ajax COD Toggle --}}
                            <td>
                                <button type="button" 
                                        class="badge-toggle-btn {{ $pin->is_cod_allowed ? 'btn-toggle-cod-on' : 'btn-toggle-cod-off' }}"
                                        onclick="togglePincodeCod({{ $pin->id }}, this)"
                                        title="Click to toggle Cash on Delivery availability">
                                    <i class="bi bi-{{ $pin->is_cod_allowed ? 'check-circle-fill' : 'slash-circle-fill' }}"></i>
                                    <span class="toggle-text">{{ $pin->is_cod_allowed ? 'COD Allowed' : 'COD Blocked' }}</span>
                                </button>
                            </td>

                            {{-- Instant Ajax Policy Toggle --}}
                            <td>
                                <button type="button" 
                                        class="badge-toggle-btn {{ $pin->is_exchange_only ? 'btn-toggle-exchange-only' : 'btn-toggle-returns-ok' }}"
                                        onclick="togglePincodeExchange({{ $pin->id }}, this)"
                                        title="Click to toggle between Exchange-Only and Standard Return policies">
                                    <i class="bi bi-{{ $pin->is_exchange_only ? 'arrow-left-right' : 'arrow-counterclockwise' }}"></i>
                                    <span class="toggle-text">{{ $pin->is_exchange_only ? 'Exchange Only' : 'Returns & Refund OK' }}</span>
                                </button>
                            </td>

                            {{-- Historical Stats --}}
                            <td>
                                <div class="stats-orders-line">
                                    <strong>{{ $pin->total_orders }}</strong> Orders &bull; 
                                    <span class="cod-stat">{{ $pin->cod_orders }} COD</span>
                                </div>
                                <div class="stats-rto-line">
                                    <span>Ret: <strong>{{ $pin->returned_orders }} ({{ $pin->return_rate }}%)</strong></span>
                                    <span>RTO: <strong class="{{ $pin->rto_rate >= 15 ? 'text-danger font-bold' : '' }}">{{ $pin->rto_orders }} ({{ $pin->rto_rate }}%)</strong></span>
                                </div>
                            </td>

                            {{-- Risk Level Badge --}}
                            <td>
                                @if($pin->risk_level === 'high')
                                    <span class="badge-risk-pill risk-high">
                                        <span class="risk-beacon-red"></span>
                                        <i class="bi bi-exclamation-triangle-fill"></i> High Risk
                                    </span>
                                @elseif($pin->risk_level === 'medium')
                                    <span class="badge-risk-pill risk-medium">
                                        <i class="bi bi-shield-exclamation"></i> Medium Risk
                                    </span>
                                @else
                                    <span class="badge-risk-pill risk-low">
                                        <i class="bi bi-shield-check"></i> Low Risk
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td style="text-align: right;">
                                <div class="action-btn-group">
                                    <form method="POST" action="{{ route('admin.pincodes.destroy', $pin->id) }}" onsubmit="return confirm('Remove custom rule override for pincode {{ $pin->pincode }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action-delete" title="Delete custom rule">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state-pincode">
                                    <div class="empty-icon-pod">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <h3 class="empty-title">No Custom Pincode Rules Found</h3>
                                    <p class="empty-desc">
                                        All unlisted Indian pincodes are automatically resolved by the intelligent delivery engine with standard zone transit times and COD enabled.
                                    </p>
                                    <button type="button" class="btn-add-first-rule" data-bs-toggle="modal" data-bs-target="#addPincodeModal">
                                        <i class="bi bi-plus-lg"></i> Add First Pincode Override
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Footer --}}
        @if($pincodes->hasPages())
            <div class="pin-pagination-wrapper">
                {{ $pincodes->links() }}
            </div>
        @endif
    </div>

</div>

{{-- ════════════════════════════════════════════════════════════════ --}}
{{-- MODAL: ADD / OVERRIDE PINCODE RULE                               --}}
{{-- ════════════════════════════════════════════════════════════════ --}}
<div class="modal fade pin-custom-modal" id="addPincodeModal" tabindex="-1" aria-labelledby="addPincodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content pin-modal-box">
            
            {{-- Top Accent Line --}}
            <div class="pin-modal-accent-bar"></div>

            <div class="pin-modal-top">
                <div class="d-flex align-items-center gap-3">
                    <div class="modal-title-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <h4 class="pin-modal-title" id="addPincodeModalLabel">Add / Override Pincode Rule</h4>
                        <span class="pin-modal-sub">Configure transit SLA, payment gates &amp; return policies</span>
                    </div>
                </div>
                <button type="button" class="pin-modal-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.pincodes.store') }}">
                @csrf
                <div class="pin-modal-fields">
                    
                    {{-- Row 1: Pincode & Zone Tier --}}
                    <div class="pin-form-grid-2">
                        <div class="pin-field-wrap">
                            <label class="pin-label"><i class="bi bi-hash"></i> 6-Digit Pincode <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="pincode" 
                                   maxlength="6" 
                                   class="pin-input pin-pincode-input" 
                                   placeholder="e.g. 110001" 
                                   pattern="\d{6}" 
                                   required>
                        </div>
                        <div class="pin-field-wrap">
                            <label class="pin-label"><i class="bi bi-layers-fill"></i> Zone Tier <span class="text-danger">*</span></label>
                            <select name="zone" class="pin-input pin-select" id="modalZoneTier" onchange="onZoneTierChange(this.value)" required>
                                <option value="Metro">⚡ Metro (Fast 2-3 Days)</option>
                                <option value="Tier-1">🏙️ Tier-1 (3-4 Days)</option>
                                <option value="Standard" selected>📦 Standard Zone (4-5 Days)</option>
                                <option value="Remote">🏔️ Remote / Special (5-7 Days)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Row 2: City & State --}}
                    <div class="pin-form-grid-2">
                        <div class="pin-field-wrap">
                            <label class="pin-label"><i class="bi bi-building"></i> City / District</label>
                            <input type="text" name="city" class="pin-input" placeholder="e.g. New Delhi">
                        </div>
                        <div class="pin-field-wrap">
                            <label class="pin-label"><i class="bi bi-map"></i> State</label>
                            <input type="text" name="state" class="pin-input" placeholder="e.g. Delhi">
                        </div>
                    </div>

                    {{-- Row 3: Transit SLA Group --}}
                    <div class="pin-field-wrap">
                        <label class="pin-label"><i class="bi bi-clock-history"></i> Estimated Delivery Transit SLA <span class="text-danger">*</span></label>
                        <div class="pin-sla-pod">
                            <div class="sla-stepper-box">
                                <span class="sla-sublabel">Min Days</span>
                                <input type="number" name="delivery_days_min" id="modalDeliveryDaysMin" value="4" min="1" max="30" class="sla-stepper-input" required>
                            </div>
                            <div class="sla-connector-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </div>
                            <div class="sla-stepper-box">
                                <span class="sla-sublabel">Max Days</span>
                                <input type="number" name="delivery_days_max" id="modalDeliveryDaysMax" value="5" min="1" max="30" class="sla-stepper-input" required>
                            </div>
                            <div class="sla-info-chip">
                                <i class="bi bi-info-circle"></i> Auto-synced with Zone Tier
                            </div>
                        </div>
                    </div>

                    {{-- Row 4: Interactive Policy Gatekeeper Toggles --}}
                    <div class="pin-policy-switches-grid">
                        
                        {{-- Policy 1: COD Switch --}}
                        <div class="pin-switch-card">
                            <div class="switch-card-info">
                                <div class="switch-card-icon switch-icon-cod">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div>
                                    <div class="switch-card-title">Allow Cash on Delivery (COD)</div>
                                    <div class="switch-card-desc">Enable doorstep cash checkout. Turn off for prepaid-only.</div>
                                </div>
                            </div>
                            <label class="pin-ios-switch">
                                <input type="checkbox" name="is_cod_allowed" value="1" checked class="ios-switch-input">
                                <span class="ios-switch-slider slider-emerald"></span>
                            </label>
                        </div>

                        {{-- Policy 2: Exchange Only Switch --}}
                        <div class="pin-switch-card">
                            <div class="switch-card-info">
                                <div class="switch-card-icon switch-icon-exchange">
                                    <i class="bi bi-arrow-left-right"></i>
                                </div>
                                <div>
                                    <div class="switch-card-title">Enforce Exchange Only</div>
                                    <div class="switch-card-desc">Disallow return refunds. Permit only size/color replacement.</div>
                                </div>
                            </div>
                            <label class="pin-ios-switch">
                                <input type="checkbox" name="is_exchange_only" value="1" class="ios-switch-input">
                                <span class="ios-switch-slider slider-amber"></span>
                            </label>
                        </div>

                    </div>

                    {{-- Row 5: Risk Telemetry Level --}}
                    <div class="pin-field-wrap">
                        <label class="pin-label"><i class="bi bi-shield-check"></i> Risk Telemetry Level <span class="text-danger">*</span></label>
                        <div class="pin-risk-options-grid">
                            <label class="pin-risk-option-card">
                                <input type="radio" name="risk_level" value="low" checked class="risk-radio-input">
                                <div class="risk-option-content risk-low-style">
                                    <span class="risk-dot-circle dot-emerald"></span>
                                    <div class="risk-option-text">
                                        <strong>Low Risk</strong>
                                        <span>Standard serviceability</span>
                                    </div>
                                </div>
                            </label>

                            <label class="pin-risk-option-card">
                                <input type="radio" name="risk_level" value="medium" class="risk-radio-input">
                                <div class="risk-option-content risk-medium-style">
                                    <span class="risk-dot-circle dot-amber"></span>
                                    <div class="risk-option-text">
                                        <strong>Medium Risk</strong>
                                        <span>Moderate RTO watch</span>
                                    </div>
                                </div>
                            </label>

                            <label class="pin-risk-option-card">
                                <input type="radio" name="risk_level" value="high" class="risk-radio-input">
                                <div class="risk-option-content risk-high-style">
                                    <span class="risk-dot-circle dot-rose"></span>
                                    <div class="risk-option-text">
                                        <strong>High Risk</strong>
                                        <span>Fraud / RTO Alert</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Row 6: Internal Notes --}}
                    <div class="pin-field-wrap">
                        <label class="pin-label"><i class="bi bi-pencil-square"></i> Internal Dispatch Notes / Remarks</label>
                        <textarea name="admin_notes" rows="2" class="pin-input pin-textarea" placeholder="Internal courier notes or special area delivery instructions..."></textarea>
                    </div>

                </div>

                <div class="pin-modal-bottom">
                    <button type="button" class="pin-btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="pin-btn-modal-save">
                        <i class="bi bi-check2-circle"></i> Save Pincode Rule
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- ── Floating Interactive Toast ── --}}
<div id="pinToast" class="pin-toast">
    <div class="pin-toast-icon"><i class="bi bi-check2-circle"></i></div>
    <span id="pinToastText">Copied to clipboard</span>
</div>

<script>
const CSRF_TOKEN = '{{ csrf_token() }}';

/* ── Auto-Sync Transit SLA with Zone Tier ── */
function onZoneTierChange(zoneVal) {
    const minInput = document.getElementById('modalDeliveryDaysMin');
    const maxInput = document.getElementById('modalDeliveryDaysMax');
    if (!minInput || !maxInput) return;
    if (zoneVal === 'Metro') { 
        minInput.value = 2; 
        maxInput.value = 3; 
    } else if (zoneVal === 'Tier-1') { 
        minInput.value = 3; 
        maxInput.value = 4; 
    } else if (zoneVal === 'Standard') { 
        minInput.value = 4; 
        maxInput.value = 5; 
    } else if (zoneVal === 'Remote') { 
        minInput.value = 5; 
        maxInput.value = 7; 
    }
}

/* ── Interactive 1-Click Copy with Feedback ── */
function copyPinText(text, btnElement) {
    if (!text) return;
    navigator.clipboard.writeText(text).then(() => {
        showPinToast('Copied Pincode: ' + text);
        if (btnElement) {
            const icon = btnElement.querySelector('i');
            if (icon) {
                const orig = icon.className;
                icon.className = 'bi bi-check-lg text-success';
                setTimeout(() => icon.className = orig, 1800);
            }
        }
    }).catch(() => {
        showPinToast('Copied: ' + text);
    });
}

function showPinToast(msg) {
    const toast = document.getElementById('pinToast');
    const toastText = document.getElementById('pinToastText');
    if (toast && toastText) {
        toastText.textContent = msg;
        toast.classList.add('active');
        clearTimeout(window.__pinToastTimer);
        window.__pinToastTimer = setTimeout(() => {
            toast.classList.remove('active');
        }, 2500);
    }
}

/* ── Instant AJAX Toggle for COD ── */
function togglePincodeCod(ruleId, btn) {
    if (!ruleId || btn.disabled) return;
    btn.disabled = true;
    const origHtml = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> <span>Updating...</span>';

    fetch(`{{ url('admin/pincodes') }}/${ruleId}/toggle-cod`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        if (data.success) {
            const isAllowed = data.is_cod_allowed;
            btn.className = `badge-toggle-btn ${isAllowed ? 'btn-toggle-cod-on' : 'btn-toggle-cod-off'}`;
            btn.innerHTML = `<i class="bi bi-${isAllowed ? 'check-circle-fill' : 'slash-circle-fill'}"></i> <span class="toggle-text">${isAllowed ? 'COD Allowed' : 'COD Blocked'}</span>`;
            showPinToast(data.message || (isAllowed ? 'COD Enabled' : 'COD Blocked'));
        } else {
            btn.innerHTML = origHtml;
            showPinToast('Error updating COD status');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = origHtml;
        showPinToast('Network error while updating COD');
    });
}

/* ── Instant AJAX Toggle for Exchange Only ── */
function togglePincodeExchange(ruleId, btn) {
    if (!ruleId || btn.disabled) return;
    btn.disabled = true;
    const origHtml = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> <span>Updating...</span>';

    fetch(`{{ url('admin/pincodes') }}/${ruleId}/toggle-exchange`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        if (data.success) {
            const isExchangeOnly = data.is_exchange_only;
            btn.className = `badge-toggle-btn ${isExchangeOnly ? 'btn-toggle-exchange-only' : 'btn-toggle-returns-ok'}`;
            btn.innerHTML = `<i class="bi bi-${isExchangeOnly ? 'arrow-left-right' : 'arrow-counterclockwise'}"></i> <span class="toggle-text">${isExchangeOnly ? 'Exchange Only' : 'Returns & Refund OK'}</span>`;
            showPinToast(data.message || (isExchangeOnly ? 'Exchange-only policy enforced' : 'Full returns permitted'));
        } else {
            btn.innerHTML = origHtml;
            showPinToast('Error updating policy');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = origHtml;
        showPinToast('Network error while updating policy');
    });
}
</script>

<style>
/* ─── Ultra-Modern Pincodes & Logistics Hub Design System ───────────── */
:root {
    --pin-midnight: #091224;
    --pin-navy: #00285a;
    --pin-navy-light: #1e3f75;
    --pin-card-border: rgba(226, 232, 240, 0.85);
    --pin-card-shadow: 0 4px 20px -2px rgba(0, 40, 90, 0.04);
}

.pin-hub-container {
    display: flex;
    flex-direction: column;
    gap: 22px;
    max-width: 1460px;
    margin: 0 auto;
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* ══════════════════════════════════════════════════════════════════════
   1. LUXURY EXECUTIVE HERO BANNER
   ══════════════════════════════════════════════════════════════════════ */
.pin-hero-card {
    position: relative;
    border-radius: 20px;
    background: radial-gradient(circle at 10% 20%, rgba(30, 64, 175, 0.45) 0%, transparent 50%),
                radial-gradient(circle at 90% 80%, rgba(16, 185, 129, 0.18) 0%, transparent 50%),
                linear-gradient(135deg, #091224 0%, #001f4d 55%, #081e3d 100%);
    box-shadow: 0 20px 45px -10px rgba(0, 31, 77, 0.35);
    border: 1px solid rgba(255, 255, 255, 0.12);
    overflow: hidden;
    padding: 28px 32px;
    color: #ffffff;
    animation: heroFadeSlide 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes heroFadeSlide {
    0% { opacity: 0; transform: translateY(-10px); }
    100% { opacity: 1; transform: translateY(0); }
}

.pin-aurora-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(60px);
    pointer-events: none;
    z-index: 1;
    opacity: 0.35;
    animation: orbFloat 8s ease-in-out infinite alternate;
}
.pin-orb-1 {
    width: 260px;
    height: 260px;
    background: #38bdf8;
    top: -60px;
    right: 15%;
}
.pin-orb-2 {
    width: 220px;
    height: 220px;
    background: #6366f1;
    bottom: -50px;
    left: 20%;
    animation-delay: -4s;
}

@keyframes orbFloat {
    0% { transform: scale(1) translate(0, 0); }
    100% { transform: scale(1.15) translate(15px, -15px); }
}

.pin-hero-inner {
    position: relative;
    z-index: 2;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.pin-hero-left {
    display: flex;
    flex-direction: column;
    gap: 8px;
    max-width: 720px;
}

.pin-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.6px;
    color: #93c5fd;
    width: fit-content;
}

.pin-beacon-pulse {
    width: 7px;
    height: 7px;
    background: #38bdf8;
    border-radius: 50%;
    box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.35);
    animation: beaconPing 2s infinite;
}

@keyframes beaconPing {
    0% { transform: scale(0.9); opacity: 1; }
    50% { transform: scale(1.3); opacity: 0.5; }
    100% { transform: scale(0.9); opacity: 1; }
}

.pin-hero-title {
    font-family: 'Cinzel', serif, sans-serif;
    font-size: 25px;
    font-weight: 800;
    color: #ffffff;
    margin: 0;
    letter-spacing: 0.4px;
}

.pin-hero-desc {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.82);
    margin: 0;
    line-height: 1.55;
}

.pin-hero-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.pin-btn-glass {
    padding: 10px 18px;
    border-radius: 11px;
    font-size: 12.5px;
    font-weight: 700;
    border: 1px solid transparent;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    backdrop-filter: blur(8px);
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.pin-btn-glass:hover {
    transform: translateY(-2px);
}

.pin-btn-analyze {
    background: rgba(255, 255, 255, 0.12);
    border-color: rgba(255, 255, 255, 0.22);
    color: #ffffff;
}
.pin-btn-analyze:hover {
    background: rgba(255, 255, 255, 0.22);
    color: #ffffff;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
}

.pin-btn-add {
    background: #ffffff;
    color: var(--pin-navy);
    font-weight: 800;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
}
.pin-btn-add:hover {
    background: #f8fafc;
    color: var(--pin-navy-light);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

/* Flash Alert */
.pin-flash-alert {
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    border-radius: 14px;
    padding: 14px 20px;
    color: #065f46;
    font-size: 13px;
    font-weight: 700;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* ══════════════════════════════════════════════════════════════════════
   2. ANIMATED BENTO KPI GRID
   ══════════════════════════════════════════════════════════════════════ */
.pin-kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}
@media (max-width: 1024px) {
    .pin-kpi-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
    .pin-kpi-grid { grid-template-columns: 1fr; }
}

.pin-kpi-glass-card {
    background: #ffffff;
    border: 1px solid var(--pin-card-border);
    border-radius: 16px;
    padding: 20px;
    box-shadow: var(--pin-card-shadow);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 12px;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    animation: cardSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
    animation-delay: var(--anim-delay, 0s);
}

@keyframes cardSlideUp {
    0% { opacity: 0; transform: translateY(16px); }
    100% { opacity: 1; transform: translateY(0); }
}

.pin-kpi-glass-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px -5px rgba(0, 40, 90, 0.08);
    border-color: #cbd5e1;
}

.pin-kpi-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.pin-kpi-meta {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.pin-kpi-tag {
    font-size: 10px;
    font-weight: 800;
    color: #94a3b8;
    letter-spacing: 0.6px;
}

.pin-kpi-heading {
    font-size: 13.5px;
    font-weight: 800;
    color: var(--pin-navy);
    margin: 0;
}

.pin-kpi-icon-pod {
    width: 38px;
    height: 38px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}
.pod-blue   { background: #eff6ff; color: #1d4ed8; }
.pod-orange { background: #fff7ed; color: #c2410c; }
.pod-amber  { background: #fffbeb; color: #b45309; }
.pod-rose   { background: #fff1f2; color: #be123c; }

.pin-kpi-val {
    font-size: 28px;
    font-weight: 900;
    color: var(--pin-navy);
    line-height: 1.1;
    letter-spacing: -0.5px;
}
.text-orange { color: #c2410c; }
.text-amber  { color: #b45309; }
.text-rose   { color: #be123c; }

.pin-kpi-foot {
    font-size: 11.5px;
    color: #64748b;
    border-top: 1px solid #f1f5f9;
    padding-top: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* ══════════════════════════════════════════════════════════════════════
   3. TABLE CONTAINER & TOOLBAR
   ══════════════════════════════════════════════════════════════════════ */
.pin-table-container {
    background: #ffffff;
    border: 1px solid var(--pin-card-border);
    border-radius: 18px;
    box-shadow: var(--pin-card-shadow);
    overflow: hidden;
}

.pin-filter-toolbar {
    padding: 16px 22px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    flex-wrap: wrap;
    background: #ffffff;
}

.pin-search-group {
    position: relative;
    flex: 1;
    min-width: 260px;
    max-width: 380px;
}

.pin-search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 13px;
}

.pin-search-input {
    width: 100%;
    padding: 9px 32px 9px 36px;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    font-size: 12.5px;
    font-family: inherit;
    outline: none;
    transition: all 0.15s;
}
.pin-search-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.pin-search-clear {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 16px;
    text-decoration: none;
}
.pin-search-clear:hover {
    color: #ef4444;
}

.pin-filter-dropdowns {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.pin-select-box {
    padding: 9px 13px;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    font-size: 12px;
    font-family: inherit;
    font-weight: 700;
    color: #334155;
    background: #ffffff;
    outline: none;
    cursor: pointer;
    transition: all 0.15s;
}
.pin-select-box:focus {
    border-color: #3b82f6;
}

.pin-btn-reset {
    padding: 8px 14px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
    color: #475569;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.15s;
}
.pin-btn-reset:hover {
    background: #e2e8f0;
    color: #0f172a;
}

/* ══════════════════════════════════════════════════════════════════════
   4. LUXURY TABLE
   ══════════════════════════════════════════════════════════════════════ */
.pin-table-scroll {
    overflow-x: auto;
}

.pin-luxury-table {
    width: 100%;
    border-collapse: collapse;
}

.pin-luxury-table th {
    padding: 13px 20px;
    font-size: 11px;
    font-weight: 800;
    color: #64748b;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    background: #f8fafc;
    border-bottom: 1.5px solid #eef2f6;
    text-align: left;
    white-space: nowrap;
}

.pin-luxury-table td {
    padding: 14px 20px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    font-size: 13px;
}
.pin-luxury-table tr:last-child td {
    border-bottom: none;
}
.pin-luxury-table tr:hover td {
    background: #fafcff;
}

/* Pincode Cell */
.pincode-cell {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.pincode-mono {
    font-family: monospace;
    font-size: 15px;
    font-weight: 800;
    color: var(--pin-navy);
    letter-spacing: 0.5px;
}
.btn-copy-pin {
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    font-size: 12px;
    padding: 2px 5px;
    border-radius: 4px;
    transition: all 0.15s;
}
.btn-copy-pin:hover {
    color: var(--pin-navy);
    background: #eff6ff;
}

.city-name {
    font-weight: 700;
    color: #0f172a;
}
.state-sub {
    font-size: 11px;
    color: #64748b;
    margin-top: 2px;
}

/* Zone Badge */
.badge-zone {
    font-size: 11.5px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #f1f5f9;
    color: #334155;
}
.badge-zone.metro { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.badge-zone.tier-1 { background: #faf5ff; color: #7e22ce; border: 1px solid #e9d5ff; }
.badge-zone.standard { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
.badge-zone.remote { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
.days-spec {
    font-weight: 800;
    opacity: 0.9;
}

/* Instant Toggle Button Badges */
.badge-toggle-btn {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.18s ease;
    white-space: nowrap;
}
.badge-toggle-btn:hover {
    transform: scale(1.02);
}

/* COD on/off */
.btn-toggle-cod-on {
    background: #ecfdf5;
    color: #047857;
    border-color: #a7f3d0;
}
.btn-toggle-cod-on:hover {
    background: #d1fae5;
}
.btn-toggle-cod-off {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fecaca;
}
.btn-toggle-cod-off:hover {
    background: #fee2e2;
}

/* Exchange vs Return */
.btn-toggle-returns-ok {
    background: #eff6ff;
    color: #1d4ed8;
    border-color: #bfdbfe;
}
.btn-toggle-returns-ok:hover {
    background: #dbeafe;
}
.btn-toggle-exchange-only {
    background: #fffbeb;
    color: #b45309;
    border-color: #fde68a;
}
.btn-toggle-exchange-only:hover {
    background: #fef3c7;
}

/* Stats lines */
.stats-orders-line {
    font-size: 12px;
    color: #334155;
}
.cod-stat {
    color: #c2410c;
    font-weight: 600;
}
.stats-rto-line {
    font-size: 11px;
    color: #64748b;
    margin-top: 3px;
    display: flex;
    gap: 8px;
}

/* Risk Badges */
.badge-risk-pill {
    font-size: 11px;
    font-weight: 800;
    padding: 3px 9px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.risk-low { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
.risk-medium { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
.risk-high { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }

.risk-beacon-red {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #ef4444;
    animation: dangerPulse 1.6s infinite;
}
@keyframes dangerPulse {
    0% { transform: scale(0.9); opacity: 1; }
    50% { transform: scale(1.4); opacity: 0.4; }
    100% { transform: scale(0.9); opacity: 1; }
}

/* Actions */
.btn-action-delete {
    background: #fef2f2;
    border: 1px solid #fee2e2;
    color: #dc2626;
    padding: 6px 10px;
    border-radius: 8px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.15s;
}
.btn-action-delete:hover {
    background: #dc2626;
    color: #ffffff;
    border-color: #dc2626;
}

/* Empty State */
.empty-state-pincode {
    padding: 48px 20px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
}
.empty-icon-pod {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #94a3b8;
    font-size: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.empty-title {
    font-size: 16px;
    font-weight: 800;
    color: var(--pin-navy);
    margin: 0;
}
.empty-desc {
    font-size: 12.5px;
    color: #64748b;
    max-width: 480px;
    margin: 0;
    line-height: 1.5;
}
.btn-add-first-rule {
    margin-top: 6px;
    background: var(--pin-navy);
    color: #ffffff;
    border: none;
    padding: 9px 18px;
    border-radius: 10px;
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* Pagination */
.pin-pagination-wrapper {
    padding: 14px 22px;
    border-top: 1px solid #f1f5f9;
}

/* ══════════════════════════════════════════════════════════════════════
   5. LUXURY MODAL STYLING
   ══════════════════════════════════════════════════════════════════════ */
#addPincodeModal .modal-dialog {
    max-width: 620px !important;
    margin: 2rem auto !important;
}

.pin-modal-box {
    border-radius: 20px;
    border: 1px solid rgba(0, 40, 90, 0.1);
    box-shadow: 0 25px 60px -10px rgba(0, 20, 60, 0.35);
    overflow: hidden;
    background: #ffffff;
}

.pin-modal-accent-bar {
    height: 4px;
    background: linear-gradient(90deg, #3b82f6 0%, #10b981 50%, #f59e0b 100%);
    width: 100%;
}

.pin-modal-top {
    background: linear-gradient(180deg, #fafbff 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    padding: 18px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: linear-gradient(135deg, #00285a 0%, #1e40af 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    box-shadow: 0 4px 12px rgba(0, 40, 90, 0.2);
    flex-shrink: 0;
}

.pin-modal-title {
    font-size: 16px;
    font-weight: 800;
    color: var(--pin-navy);
    margin: 0;
    line-height: 1.2;
}

.pin-modal-sub {
    font-size: 11.5px;
    color: #64748b;
    font-weight: 500;
}

.pin-modal-close {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    font-size: 20px;
    line-height: 1;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;
}
.pin-modal-close:hover {
    background: #fee2e2;
    color: #ef4444;
    border-color: #fca5a5;
    transform: rotate(90deg);
}

.pin-modal-fields {
    padding: 22px 24px;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.pin-form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}
@media (max-width: 500px) {
    .pin-form-grid-2 { grid-template-columns: 1fr; }
}

.pin-field-wrap {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.pin-label {
    font-size: 11.5px;
    font-weight: 800;
    color: #1e293b;
    letter-spacing: 0.3px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.pin-input {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    color: #0f172a;
    background: #f8fafc;
    font-family: inherit;
    outline: none;
    transition: all 0.2s ease;
}
.pin-input:focus {
    background: #ffffff;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3.5px rgba(59, 130, 246, 0.12);
}

.pin-pincode-input {
    font-family: 'JetBrains Mono', 'Fira Code', Consolas, monospace;
    font-size: 15px;
    font-weight: 800;
    letter-spacing: 2px;
    color: #00285a;
    background: #f0fdf4;
    border-color: #bbf7d0;
}
.pin-pincode-input:focus {
    border-color: #10b981;
    box-shadow: 0 0 0 3.5px rgba(16, 185, 129, 0.15);
    background: #ffffff;
}

.pin-select {
    cursor: pointer;
}

.pin-textarea {
    resize: vertical;
    min-height: 56px;
}

/* Transit SLA Pod */
.pin-sla-pod {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 14px;
}
.sla-stepper-box {
    display: flex;
    flex-direction: column;
    gap: 3px;
    flex: 1;
}
.sla-sublabel {
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    color: #64748b;
    letter-spacing: 0.5px;
}
.sla-stepper-input {
    width: 100%;
    padding: 7px 10px;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    font-size: 13.5px;
    font-weight: 800;
    color: #0f172a;
    background: #ffffff;
    outline: none;
    transition: all 0.15s;
    text-align: center;
}
.sla-stepper-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}
.sla-connector-arrow {
    color: #94a3b8;
    font-size: 16px;
    display: flex;
    align-items: center;
    padding-top: 14px;
}
.sla-info-chip {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 700;
    color: #0369a1;
    background: #e0f2fe;
    padding: 7px 12px;
    border-radius: 8px;
    white-space: nowrap;
    border: 1px solid #bae6fd;
}

/* Policy Gatekeeper Switches */
.pin-policy-switches-grid {
    display: flex;
    flex-direction: column;
    gap: 9px;
}
.pin-switch-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 14px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    transition: all 0.2s ease;
}
.pin-switch-card:hover {
    background: #ffffff;
    border-color: #cbd5e1;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
}
.switch-card-info {
    display: flex;
    align-items: center;
    gap: 12px;
}
.switch-card-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}
.switch-icon-cod {
    background: #dcfce7;
    color: #15803d;
}
.switch-icon-exchange {
    background: #fef3c7;
    color: #b45309;
}
.switch-card-title {
    font-size: 12.5px;
    font-weight: 800;
    color: #0f172a;
}
.switch-card-desc {
    font-size: 11px;
    color: #64748b;
    margin-top: 1px;
}

/* iOS Toggle Switches */
.pin-ios-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
    flex-shrink: 0;
    cursor: pointer;
    margin: 0;
}
.pin-ios-switch .ios-switch-input {
    opacity: 0;
    width: 0;
    height: 0;
    position: absolute;
}
.ios-switch-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    border-radius: 24px;
}
.ios-switch-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    border-radius: 50%;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25);
}
.ios-switch-input:checked + .slider-emerald {
    background-color: #10b981;
}
.ios-switch-input:checked + .slider-amber {
    background-color: #f59e0b;
}
.ios-switch-input:checked + .ios-switch-slider:before {
    transform: translateX(20px);
}

/* Risk Telemetry Radio Cards */
.pin-risk-options-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}
@media (max-width: 500px) {
    .pin-risk-options-grid { grid-template-columns: 1fr; }
}
.pin-risk-option-card {
    cursor: pointer;
    margin: 0;
    display: block;
}
.pin-risk-option-card .risk-radio-input {
    display: none;
}
.risk-option-content {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 12px;
    border-radius: 12px;
    border: 1.5px solid #e2e8f0;
    background: #f8fafc;
    transition: all 0.2s ease;
}
.risk-option-content:hover {
    background: #ffffff;
    border-color: #cbd5e1;
}
.risk-dot-circle {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}
.dot-emerald { background: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2); }
.dot-amber { background: #f59e0b; box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2); }
.dot-rose { background: #ef4444; box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2); }

.risk-option-text {
    display: flex;
    flex-direction: column;
}
.risk-option-text strong {
    font-size: 12px;
    font-weight: 800;
    color: #1e293b;
}
.risk-option-text span {
    font-size: 10px;
    color: #64748b;
}
.risk-radio-input:checked + .risk-low-style {
    border-color: #10b981;
    background: #ecfdf5;
}
.risk-radio-input:checked + .risk-low-style strong {
    color: #065f46;
}
.risk-radio-input:checked + .risk-medium-style {
    border-color: #f59e0b;
    background: #fffbeb;
}
.risk-radio-input:checked + .risk-medium-style strong {
    color: #92400e;
}
.risk-radio-input:checked + .risk-high-style {
    border-color: #ef4444;
    background: #fef2f2;
}
.risk-radio-input:checked + .risk-high-style strong {
    color: #991b1b;
}

.pin-modal-bottom {
    padding: 16px 24px;
    background: #fafbff;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.pin-btn-modal-cancel {
    padding: 9px 18px;
    border-radius: 10px;
    font-size: 12.5px;
    font-weight: 700;
    color: #64748b;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    cursor: pointer;
}
.pin-btn-modal-cancel:hover {
    background: #e2e8f0;
    color: #0f172a;
}

.pin-btn-modal-save {
    padding: 9px 20px;
    border-radius: 10px;
    font-size: 12.5px;
    font-weight: 800;
    color: #ffffff;
    background: linear-gradient(135deg, #00285a 0%, #1e40af 100%);
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 4px 14px rgba(0, 40, 90, 0.25);
    transition: all 0.2s ease;
}
.pin-btn-modal-save:hover {
    background: linear-gradient(135deg, #001f4d 0%, #1d4ed8 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(0, 40, 90, 0.35);
}

/* ══════════════════════════════════════════════════════════════════════
   6. FLOATING TOAST FEEDBACK
   ══════════════════════════════════════════════════════════════════════ */
.pin-toast {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: #091224;
    color: #ffffff;
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.15);
    z-index: 999999;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    pointer-events: none;
}

.pin-toast.active {
    opacity: 1;
    transform: translateY(0);
}

.pin-toast-icon {
    color: #10b981;
    font-size: 17px;
    display: flex;
    align-items: center;
}
</style>
@endsection