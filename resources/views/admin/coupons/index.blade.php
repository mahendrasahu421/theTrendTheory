{{-- resources/views/admin/coupons/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Coupons')
@section('content')

    <style>
        .page-hdr {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px
        }

        .page-title {
            font-family: 'Cinzel', serif;
            font-size: 16px;
            font-weight: 700;
            color: #00285a;
            letter-spacing: 1px
        }

        .card {
            background: white;
            border: 1px solid #eef2f6;
            border-radius: 14px;
            overflow: hidden
        }

        .card-hdr {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 18px;
            border-bottom: 1px solid #eef2f6;
            flex-wrap: wrap;
            gap: 10px
        }

        .card-title {
            font-family: 'Cinzel', serif;
            font-size: 12px;
            font-weight: 700;
            color: #00285a;
            letter-spacing: 1px
        }

        .filter-bar {
            display: flex;
            gap: 8px;
            flex-wrap: wrap
        }

        .sbox {
            padding: 8px 14px;
            border: 1.5px solid #e8edf5;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            outline: none;
            transition: .15s;
            width: 200px
        }

        .sbox:focus {
            border-color: #00285a
        }

        .fsel {
            padding: 8px 12px;
            border: 1.5px solid #e8edf5;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            outline: none;
            background: white;
            cursor: pointer
        }

        .fsel:focus {
            border-color: #00285a
        }

        .dt {
            width: 100%;
            border-collapse: collapse
        }

        .dt th {
            padding: 10px 14px;
            font-size: 10px;
            font-weight: 700;
            color: #7a8fa6;
            text-transform: uppercase;
            letter-spacing: .8px;
            background: #f8fafc;
            border-bottom: 1px solid #eef2f6;
            text-align: left;
            white-space: nowrap
        }

        .dt td {
            padding: 12px 14px;
            font-size: 13px;
            border-bottom: 1px solid rgba(0, 0, 0, .04);
            vertical-align: middle
        }

        .dt tr:last-child td {
            border-bottom: none
        }

        .dt tr:hover td {
            background: #fafbff
        }

        .bdg {
            display: inline-flex;
            align-items: center;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700
        }

        .bdg-g {
            background: #e8f5e9;
            color: #2e7d32
        }

        .bdg-r {
            background: #fce4ec;
            color: #c62828
        }

        .bdg-b {
            background: #e3f2fd;
            color: #1565c0
        }

        .bdg-a {
            background: #fff3e0;
            color: #e65100
        }

        .bdg-p {
            background: #f3e5f5;
            color: #7b1fa2
        }

        .code-pill {
            background: #00285a;
            color: #ffd700;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
            font-family: monospace;
            letter-spacing: 1px;
            display: inline-block
        }

        .dt-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 18px;
            border-top: 1px solid #eef2f6;
            flex-wrap: wrap;
            gap: 8px
        }

        .dt-info {
            font-size: 12px;
            color: #7a8fa6
        }

        .dt-pages {
            display: flex;
            gap: 3px;
            flex-wrap: wrap
        }

        .pg-btn {
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid #eef2f6;
            background: white;
            color: #555;
            cursor: pointer;
            transition: .15s
        }

        .pg-btn:hover {
            background: #f0f4f8
        }

        .pg-btn.active {
            background: #00285a;
            color: white;
            border-color: #00285a
        }

        .pg-btn:disabled {
            opacity: .4;
            cursor: not-allowed
        }

        .tbl-wrap {
            position: relative
        }

        .overlay {
            display: none;
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, .75);
            align-items: center;
            justify-content: center;
            z-index: 5
        }

        .overlay.on {
            display: flex
        }

        .spin {
            width: 26px;
            height: 26px;
            border: 3px solid #eef2f6;
            border-top-color: #00285a;
            border-radius: 50%;
            animation: spin .7s linear infinite
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }

        .prog {
            background: #f0f4f8;
            border-radius: 20px;
            height: 6px;
            overflow: hidden;
            margin-top: 4px
        }

        .prog-f {
            height: 100%;
            border-radius: 20px;
            background: #00285a
        }
    </style>

    <div class="page-hdr">
        <div class="page-title">Coupons</div>
        <a href="{{ route('admin.coupons.create') }}"
            style="background:#00285a;color:white;padding:9px 20px;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:6px">
            <i class="bi bi-plus-lg"></i> Add Coupon
        </a>
    </div>

    @if (session('success'))
        <div
            style="background:#e8f5e9;color:#2e7d32;padding:10px 16px;border-radius:10px;margin-bottom:14px;font-size:13px;font-weight:600">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    {{-- STATS ROW --}}
    <div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px;margin-bottom:16px">
        @php
            $totalCoupons = \App\Models\Coupon::count();
            $activeCoupons = \App\Models\Coupon::where('is_active', true)->count();
            $totalUsed = \App\Models\CouponUsage::count();
            $totalDiscount = \App\Models\CouponUsage::sum('discount_applied');
        @endphp
        <div style="background:white;border:1px solid #eef2f6;border-radius:14px;padding:16px 18px">
            <div style="font-size:10px;font-weight:700;color:#7a8fa6;text-transform:uppercase;letter-spacing:.5px">Total
                Coupons</div>
            <div style="font-size:24px;font-weight:700;color:#00285a;font-family:'Cinzel',serif;margin:4px 0">
                {{ $totalCoupons }}</div>
        </div>
        <div style="background:white;border:1px solid #eef2f6;border-radius:14px;padding:16px 18px">
            <div style="font-size:10px;font-weight:700;color:#7a8fa6;text-transform:uppercase;letter-spacing:.5px">Active
            </div>
            <div style="font-size:24px;font-weight:700;color:#22c55e;font-family:'Cinzel',serif;margin:4px 0">
                {{ $activeCoupons }}</div>
        </div>
        <div style="background:white;border:1px solid #eef2f6;border-radius:14px;padding:16px 18px">
            <div style="font-size:10px;font-weight:700;color:#7a8fa6;text-transform:uppercase;letter-spacing:.5px">Total
                Used</div>
            <div style="font-size:24px;font-weight:700;color:#00285a;font-family:'Cinzel',serif;margin:4px 0">
                {{ $totalUsed }}</div>
        </div>
        <div style="background:white;border:1px solid #eef2f6;border-radius:14px;padding:16px 18px">
            <div style="font-size:10px;font-weight:700;color:#7a8fa6;text-transform:uppercase;letter-spacing:.5px">Total
                Discount Given</div>
            <div style="font-size:24px;font-weight:700;color:#c44536;font-family:'Cinzel',serif;margin:4px 0">
                ₹{{ number_format($totalDiscount) }}</div>
        </div>
    </div>

    <div class="card">
        <div class="card-hdr">
            <div class="card-title">All Coupons <span id="totalCount" style="color:#7a8fa6;font-weight:400"></span></div>
            <div class="filter-bar">
                <input type="text" id="srch" class="sbox" placeholder="Search code...">
                <select id="typeFilter" class="fsel">
                    <option value="">All Types</option>
                    <option value="percent">Percent Off</option>
                    <option value="flat">Flat Off</option>
                </select>
                <select id="statusFilter" class="fsel">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>

        <div class="tbl-wrap">
            <div class="overlay" id="overlay">
                <div class="spin"></div>
            </div>
            <div style="overflow-x:auto">
                <table class="dt">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Min Order</th>
                            <th>Max Discount</th>
                            <th>Usage</th>
                            <th>Valid Until</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tBody">
                        <tr>
                            <td colspan="10" style="text-align:center;padding:40px;color:#7a8fa6">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="dt-bottom">
            <div class="dt-info" id="dtInfo"></div>
            <div class="dt-pages" id="dtPages"></div>
        </div>
    </div>

    @push('scripts')
        <script>
            var AJAX_URL = '{{ route('admin.coupons.ajax') }}';
            var CSRF = document.querySelector('meta[name="csrf-token"]').content;
            var state = {
                page: 1,
                perPage: 15,
                search: '',
                type: '',
                status: ''
            };
            var timer;

            function load() {
                document.getElementById('overlay').classList.add('on');
                var p = new URLSearchParams({
                    page: state.page,
                    per_page: state.perPage,
                    search: state.search,
                    type: state.type,
                    status: state.status
                });
                fetch(AJAX_URL + '?' + p, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF
                        }
                    })
                    .then(r => r.json()).then(res => {
                        renderRows(res.data);
                        renderInfo(res.from, res.to, res.total);
                        renderPages(res.current_page, res.last_page);
                        document.getElementById('totalCount').textContent = '(' + res.total + ')';
                        document.getElementById('overlay').classList.remove('on');
                    }).catch(() => document.getElementById('overlay').classList.remove('on'));
            }

            function renderRows(rows) {
                var tb = document.getElementById('tBody');
                if (!rows.length) {
                    tb.innerHTML =
                        '<tr><td colspan="10" style="text-align:center;padding:40px;color:#7a8fa6"><i class="bi bi-ticket-perforated" style="font-size:28px;display:block;margin-bottom:8px"></i>No coupons found</td></tr>';
                    return;
                }
                tb.innerHTML = rows.map(c => {
                    var typeBadge = c.type === 'percent' ?
                        '<span class="bdg bdg-b">' + c.value + '% Off</span>' :
                        '<span class="bdg bdg-p">Flat ₹' + Math.round(Number(c.value || 0)).toLocaleString('en-IN') + '</span>';

                    var minOrder = c.min_order ? '₹' + Math.round(Number(c.min_order || 0)).toLocaleString('en-IN') :
                        '<span style="color:#cbd5e1">—</span>';
                    var maxDisc = c.max_discount ? '₹' + Math.round(Number(c.max_discount || 0)).toLocaleString('en-IN') :
                        '<span style="color:#cbd5e1">—</span>';

                    var usageBar = c.usage_limit ?
                        `<div style="font-size:12px;color:#00285a;font-weight:600">${c.used_count} / ${c.usage_limit}</div>
               <div class="prog"><div class="prog-f" style="width:${Math.min(100,Math.round((c.used_count/c.usage_limit)*100))}%"></div></div>` :
                        `<div style="font-size:12px;color:#555">${c.used_count} used</div><div style="font-size:11px;color:#7a8fa6">No limit</div>`;

                    var validUntil = c.valid_until ?
                        `<div style="font-size:12px;font-weight:600;color:${c.is_expired?'#c62828':'#00285a'}">${c.valid_until}</div>
               ${c.is_expired?'<span class="bdg bdg-r" style="font-size:10px">Expired</span>':''}` :
                        '<span style="color:#cbd5e1;font-size:12px">No expiry</span>';

                    var statusBadge = c.is_active ?
                        '<span class="bdg bdg-g">Active</span>' :
                        '<span class="bdg bdg-r">Inactive</span>';

                    return `<tr>
            <td><span class="code-pill">${esc(c.code)}</span></td>
            <td style="font-size:12px;color:#555;max-width:180px">${esc(c.description||'—')}</td>
            <td>${typeBadge}</td>
            <td style="font-weight:700;color:#00285a">${c.type==='percent'?c.value+'%':'₹'+Math.round(Number(c.value || 0)).toLocaleString('en-IN')}</td>
            <td>${minOrder}</td>
            <td>${maxDisc}</td>
            <td>${usageBar}</td>
            <td>${validUntil}</td>
            <td>${statusBadge}</td>
            <td>
                <div style="display:flex;gap:5px">
                    <a href="${c.edit_url}" style="background:#00285a;color:white;padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700;text-decoration:none">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <button onclick="toggleStatus(${c.id},${c.is_active?1:0})"
                        style="background:${c.is_active?'#fce4ec':'#e8f5e9'};color:${c.is_active?'#c62828':'#2e7d32'};padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700;border:none;cursor:pointer">
                        ${c.is_active?'Off':'On'}
                    </button>
                </div>
            </td>
        </tr>`;
                }).join('');
            }

            function toggleStatus(id, current) {
                if (!confirm(current ? 'Deactivate this coupon?' : 'Activate this coupon?')) return;
                fetch('/admin/coupons/' + id + '/toggle', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                }).then(r => r.json()).then(res => {
                    if (res.success) load();
                });
            }

            function renderInfo(from, to, total) {
                document.getElementById('dtInfo').textContent = total > 0 ? 'Showing ' + from + ' – ' + to + ' of ' + total +
                    ' coupons' : 'No coupons found';
            }

            function renderPages(cur, last) {
                var el = document.getElementById('dtPages');
                if (last <= 1) {
                    el.innerHTML = '';
                    return;
                }
                var h = '';
                h += `<button class="pg-btn" onclick="go(${cur-1})" ${cur<=1?'disabled':''}>‹</button>`;
                pages(cur, last).forEach(p => {
                    if (p === '…') h += `<span style="padding:5px 4px;color:#7a8fa6;font-size:12px">…</span>`;
                    else h += `<button class="pg-btn ${p===cur?'active':''}" onclick="go(${p})">${p}</button>`;
                });
                h += `<button class="pg-btn" onclick="go(${cur+1})" ${cur>=last?'disabled':''}>›</button>`;
                el.innerHTML = h;
            }

            function pages(cur, last) {
                if (last <= 7) return Array.from({
                    length: last
                }, (_, i) => i + 1);
                var r = [1];
                if (cur > 3) r.push('…');
                for (var i = Math.max(2, cur - 1); i <= Math.min(last - 1, cur + 1); i++) r.push(i);
                if (cur < last - 2) r.push('…');
                r.push(last);
                return r;
            }

            function go(p) {
                if (p < 1) return;
                state.page = p;
                load();
            }

            function esc(s) {
                if (!s) return '';
                return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            }

            document.getElementById('srch').addEventListener('input', function() {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    state.search = this.value;
                    state.page = 1;
                    load();
                }, 400);
            });
            document.getElementById('typeFilter').addEventListener('change', function() {
                state.type = this.value;
                state.page = 1;
                load();
            });
            document.getElementById('statusFilter').addEventListener('change', function() {
                state.status = this.value;
                state.page = 1;
                load();
            });

            load();
        </script>
    @endpush
@endsection
