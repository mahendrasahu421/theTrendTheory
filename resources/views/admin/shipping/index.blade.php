@extends('admin.layouts.app')
@section('title', 'Shipping Settings')
@section('content')

<style>
.form-wrap { max-width:700px; }
.card { background:white; border:1px solid #eef2f6; border-radius:14px; overflow:hidden; margin-bottom:16px; }
.card-hdr { padding:16px 20px; border-bottom:1px solid #eef2f6; }
.card-title { font-family:'Cinzel',serif; font-size:13px; font-weight:700; color:#00285a; letter-spacing:1px; }
.sec-label { font-size:10px; font-weight:700; color:#7a8fa6; text-transform:uppercase; letter-spacing:1px; padding:14px 20px 0; display:block; }
.form-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; padding:14px 20px; }
.fgrp { display:flex; flex-direction:column; gap:5px; }
.fgrp.span2 { grid-column:1/-1; }
.fgrp label { font-size:11px; font-weight:700; color:#7a8fa6; text-transform:uppercase; letter-spacing:.5px; }
.fgrp input { padding:10px 14px; border:1.5px solid #e8edf5; border-radius:10px; font-size:14px; font-family:inherit; outline:none; transition:.15s; width:100%; background:white; box-sizing:border-box; }
.fgrp input:focus { border-color:#00285a; }
.fgrp .hint { font-size:11px; color:#7a8fa6; }
.ftog { display:flex; align-items:center; gap:8px; cursor:pointer; }
.ftog input { width:17px; height:17px; accent-color:#00285a; cursor:pointer; }
.ftog span { font-size:13px; font-weight:600; color:#333; }
.form-actions { display:flex; gap:10px; padding:16px 20px; border-top:1px solid #eef2f6; background:#fafbff; }
.btn-save { background:#00285a; color:white; border:none; padding:10px 26px; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px; font-family:inherit; }
.btn-save:hover { background:#1e3f75; }
.preview-box { background:#f8faff; border:1px solid #e3f2fd; border-radius:10px; padding:14px 18px; margin:0 20px 14px; }
.preview-box .p-row { display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid #eef2f6; font-size:13px; }
.preview-box .p-row:last-child { border-bottom:none; }
</style>

<div class="form-wrap">
    <div style="font-family:'Cinzel',serif;font-size:16px;font-weight:700;color:#00285a;margin-bottom:16px;letter-spacing:1px">
        Shipping Settings
    </div>

    @if(session('success'))
    <div style="background:#e8f5e9;color:#2e7d32;padding:10px 16px;border-radius:10px;margin-bottom:14px;font-size:13px;font-weight:600">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="card-hdr"><div class="card-title">SHIPPING RATES</div></div>

        <form method="POST" action="{{ route('admin.shipping.update') }}">
            @csrf

            <span class="sec-label">Standard Delivery</span>
            <div class="form-grid">
                <div class="fgrp">
                    <label>Flat Shipping Rate (₹)</label>
                    <input type="number" name="flat_rate" step="0.01" min="0"
                           value="{{ $shipping->flat_rate }}" placeholder="99">
                    <div class="hint">Charged when order is below free shipping threshold</div>
                </div>
                <div class="fgrp">
                    <label>Free Shipping Above (₹)</label>
                    <input type="number" name="free_shipping_above" step="0.01" min="0"
                           value="{{ $shipping->free_shipping_above }}" placeholder="999">
                    <div class="hint">Set 0 to always charge shipping</div>
                </div>
                <div class="fgrp">
                    <label>Estimated Delivery (Min Days)</label>
                    <input type="number" name="estimated_days_min" min="1"
                           value="{{ $shipping->estimated_days_min }}" placeholder="3">
                </div>
                <div class="fgrp">
                    <label>Estimated Delivery (Max Days)</label>
                    <input type="number" name="estimated_days_max" min="1"
                           value="{{ $shipping->estimated_days_max }}" placeholder="7">
                </div>
            </div>

            <span class="sec-label">Cash On Delivery</span>
            <div class="form-grid">
                <div class="fgrp span2">
                    <label class="ftog">
                        <input type="checkbox" name="cod_enabled" value="1"
                            {{ $shipping->cod_enabled ? 'checked' : '' }}>
                        <span>Enable Cash on Delivery (COD)</span>
                    </label>
                </div>
                <div class="fgrp">
                    <label>COD Charges (₹)</label>
                    <input type="number" name="cod_charges" step="0.01" min="0"
                           value="{{ $shipping->cod_charges }}" placeholder="49">
                    <div class="hint">Extra charge for COD orders (0 = free)</div>
                </div>
            </div>

            {{-- Live Preview --}}
            <span class="sec-label">Preview</span>
            <div class="preview-box">
                <div class="p-row">
                    <span style="color:#555">Order below ₹<span id="preThreshold">{{ $shipping->free_shipping_above }}</span></span>
                    <strong>Shipping: ₹<span id="preFlat">{{ $shipping->flat_rate }}</span></strong>
                </div>
                <div class="p-row">
                    <span style="color:#555">Order above ₹<span id="preThreshold2">{{ $shipping->free_shipping_above }}</span></span>
                    <strong style="color:#2e7d32">FREE Shipping 🎉</strong>
                </div>
                <div class="p-row">
                    <span style="color:#555">COD charges</span>
                    <strong>₹<span id="preCod">{{ $shipping->cod_charges }}</span></strong>
                </div>
                <div class="p-row">
                    <span style="color:#555">Estimated delivery</span>
                    <strong><span id="preMin">{{ $shipping->estimated_days_min }}</span>–<span id="preMax">{{ $shipping->estimated_days_max }}</span> days</strong>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-lg"></i> Save Shipping Settings
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function bind(inputName, spanIds) {
    var el = document.querySelector('[name="'+inputName+'"]');
    if (!el) return;
    spanIds.forEach(id => {
        var sp = document.getElementById(id);
        if (sp) sp.textContent = el.value;
    });
    el.addEventListener('input', function() {
        spanIds.forEach(id => {
            var sp = document.getElementById(id);
            if (sp) sp.textContent = this.value;
        });
    });
}
bind('flat_rate',           ['preFlat']);
bind('free_shipping_above', ['preThreshold', 'preThreshold2']);
bind('cod_charges',         ['preCod']);
bind('estimated_days_min',  ['preMin']);
bind('estimated_days_max',  ['preMax']);
</script>
@endpush
@endsection