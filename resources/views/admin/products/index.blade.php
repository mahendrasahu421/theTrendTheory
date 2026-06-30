{{-- resources/views/admin/products/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Products')
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
            padding: 11px 14px;
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
            padding: 2px 9px;
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
            position: relative;
            min-height: 120px
        }

        .overlay {
            display: none;
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, .8);
            align-items: center;
            justify-content: center;
            z-index: 5;
            border-radius: 0 0 14px 14px
        }

        .overlay.on {
            display: flex
        }

        .spin {
            width: 28px;
            height: 28px;
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

        .pimg {
            width: 40px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #eef2f6;
            flex-shrink: 0
        }

        .pimg-ph {
            width: 40px;
            height: 50px;
            border-radius: 8px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0
        }

        .err-msg {
            background: #fce4ec;
            color: #c62828;
            padding: 12px 16px;
            border-radius: 10px;
            margin: 14px;
            font-size: 13px;
            display: none
        }
    </style>

    <div class="page-hdr">
        <div class="page-title">Products</div>
        <a href="{{ route('admin.products.create') }}"
            style="background:#00285a;color:white;padding:9px 20px;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:6px">
            <i class="bi bi-plus-lg"></i> Add Product
        </a>
    </div>

    @if (session('success'))
        <div
            style="background:#e8f5e9;color:#2e7d32;padding:10px 16px;border-radius:10px;margin-bottom:14px;font-size:13px;font-weight:600">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-hdr">
            <div class="card-title">All Products <span id="totalCount"
                    style="color:#7a8fa6;font-weight:400;font-family:sans-serif"></span></div>
            <div class="filter-bar">
                <input type="text" id="srch" class="sbox" placeholder="Search name or SKU...">
                <select id="catFilter" class="fsel">
                    <option value="">All Categories</option>
                    @foreach (\App\Models\Category::where('is_active', true)->orderBy('parent_id')->orderBy('name')->get() as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->parent_id ? '— ' : '' }}{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select id="statusFilter" class="fsel">
                    <option value="">All Status</option>
                    <option value="1" selected>Active</option>
                    <option value="0">Inactive</option>
                </select>
                <select id="perPage" class="fsel">
                    <option value="all" selected>All products</option>
                    <option value="10">10 / page</option>
                    <option value="25">25 / page</option>
                    <option value="50">50 / page</option>
                </select>
            </div>
        </div>

        <div id="errMsg" class="err-msg"></div>

        <div class="tbl-wrap">
            <div class="overlay" id="overlay">
                <div class="spin"></div>
            </div>
            <div style="overflow-x:auto">
                <table class="dt">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Sold</th>
                            <th>Status</th>
                            <th>Tags</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tBody">
                        <tr>
                            <td colspan="9" style="text-align:center;padding:30px;color:#7a8fa6">Loading...</td>
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
            var AJAX_URL = '{{ route('admin.products.ajax') }}';
            var CSRF = document.querySelector('meta[name="csrf-token"]').content;
            var state = {
                page: 1,
                perPage: 'all',
                search: '',
                category: '',
                status: '1'
            };
            var timer;

            function load() {
                document.getElementById('overlay').classList.add('on');
                document.getElementById('errMsg').style.display = 'none';

                var p = new URLSearchParams({
                    page: state.page,
                    per_page: state.perPage,
                    search: state.search,
                    category: state.category,
                    status: state.status
                });

                fetch(AJAX_URL + '?' + p.toString(), {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF
                        }
                    })
                    .then(function(r) {
                        if (!r.ok) throw new Error('HTTP ' + r.status);
                        return r.json();
                    })
                    .then(function(res) {
                        renderRows(res.data || []);
                        renderInfo(res.from, res.to, res.total);
                        renderPages(res.current_page, res.last_page);
                        document.getElementById('totalCount').textContent = '(' + res.total + ')';
                        document.getElementById('overlay').classList.remove('on');
                    })
                    .catch(function(err) {
                        document.getElementById('overlay').classList.remove('on');
                        document.getElementById('errMsg').textContent = 'Error loading products: ' + err.message +
                            '. Check console for details.';
                        document.getElementById('errMsg').style.display = 'block';
                        document.getElementById('tBody').innerHTML =
                            '<tr><td colspan="9" style="text-align:center;padding:30px;color:#c62828">Failed to load. Refresh karo.</td></tr>';
                        console.error('Products AJAX error:', err);
                    });
            }

            function renderRows(rows) {
                var tb = document.getElementById('tBody');
                if (!rows.length) {
                    tb.innerHTML =
                        '<tr><td colspan="9" style="text-align:center;padding:40px;color:#7a8fa6"><i class="bi bi-box-seam" style="font-size:28px;display:block;margin-bottom:8px"></i>Koi product nahi mila</td></tr>';
                    return;
                }
                var offset = state.perPage === 'all' ? 0 : (state.page - 1) * state.perPage;
                tb.innerHTML = rows.map(function(p, i) {
                    var num = offset + i + 1;
                    var img = p.image ?
                        '<img src="' + esc(p.image) + '" class="pimg" alt="" onerror="this.style.display=\'none\'">' :
                        '<div class="pimg-ph"><i class="bi bi-image" style="color:#cbd5e1;font-size:14px"></i></div>';

                    var discPct = (p.original_price && p.original_price > p.price) ?
                        Math.round(((p.original_price - p.price) / p.original_price) * 100) :
                        0;

                    var priceHtml = '<strong style="color:#00285a">₹' + Number(p.price).toLocaleString('en-IN') +
                        '</strong>';
                    if (discPct > 0) priceHtml += '<br><span style="font-size:10px;color:#22c55e;font-weight:700">-' +
                        discPct + '% OFF</span>';

                    var stockBdg = p.stock === 0 ?
                        '<span class="bdg bdg-r">Out</span>' :
                        p.stock <= 5 ?
                        '<span class="bdg bdg-a">' + p.stock + '</span>' :
                        '<span class="bdg bdg-g">' + p.stock + '</span>';

                    var statusBdg = p.is_active ?
                        '<span class="bdg bdg-g">Active</span>' :
                        '<span class="bdg bdg-r">Inactive</span>';

                    var tags = '';
                    if (p.is_new) tags +=
                        '<span class="bdg" style="background:#e0f2fe;color:#075985;margin:1px">New</span>';
                    if (p.is_featured) tags +=
                        '<span class="bdg" style="background:#fef9c3;color:#854d0e;margin:1px">Feat</span>';
                    if (p.is_trending) tags +=
                        '<span class="bdg" style="background:#fce7f3;color:#9d174d;margin:1px">Hot</span>';
                    if (p.is_on_sale) tags += '<span class="bdg bdg-r" style="margin:1px">Sale</span>';

                    var varBdg = p.has_variants ?
                        '<span class="bdg bdg-b">' + (p.variants_count || 0) + ' vars</span>' :
                        '';

                    return '<tr>' +
                        '<td style="color:#7a8fa6;font-size:12px">' + num + '</td>' +
                        '<td><div style="display:flex;align-items:center;gap:10px">' +
                        img +
                        '<div>' +
                        '<div style="font-weight:600;color:#00285a;font-size:13px">' + esc(p.name) + '</div>' +
                        '<div style="font-size:11px;color:#7a8fa6">' + esc(p.sku) + '</div>' +
                        varBdg +
                        '</div>' +
                        '</div></td>' +
                        '<td style="font-size:12px;color:#555">' + esc(p.category) + '</td>' +
                        '<td>' + priceHtml + '</td>' +
                        '<td>' + stockBdg + '</td>' +
                        '<td><span class="bdg bdg-b">' + p.total_sold + '</span></td>' +
                        '<td>' + statusBdg + '</td>' +
                        '<td>' + (tags || '—') + '</td>' +
                        '<td>' +
                        '<div style="display:flex;gap:5px;flex-wrap:wrap">' +
                        '<a href="' + p.edit_url +
                        '" style="background:#00285a;color:white;padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700;text-decoration:none"><i class="bi bi-pencil"></i> Edit</a>' +
                        '<a href="' + p.show_url +
                        '" style="background:#f0f4f8;color:#555;padding:5px 10px;border-radius:20px;font-size:11px;font-weight:700;text-decoration:none" title="Admin View"><i class="bi bi-eye"></i></a>' +
                        '<a href="' + p.view_url +
                        '" target="_blank" style="background:#e8f5e9;color:#2e7d32;padding:5px 10px;border-radius:20px;font-size:11px;font-weight:700;text-decoration:none" title="Frontend View"><i class="bi bi-box-arrow-up-right"></i></a>' +
                        '</div>' +
                        '</td>' +
                        '</tr>';
                }).join('');
            }

            function renderInfo(from, to, total) {
                document.getElementById('dtInfo').textContent = total > 0 ?
                    'Showing ' + from + ' – ' + to + ' of ' + total + ' products' :
                    'No products found';
            }

            function renderPages(cur, last) {
                var el = document.getElementById('dtPages');
                if (last <= 1) {
                    el.innerHTML = '';
                    return;
                }
                var h = '';
                h += '<button class="pg-btn" onclick="go(' + (cur - 1) + ')" ' + (cur <= 1 ? 'disabled' : '') + '>‹</button>';
                getPages(cur, last).forEach(function(p) {
                    if (p === '…') h += '<span style="padding:5px 4px;color:#7a8fa6;font-size:12px">…</span>';
                    else h += '<button class="pg-btn ' + (p === cur ? 'active' : '') + '" onclick="go(' + p + ')">' +
                        p + '</button>';
                });
                h += '<button class="pg-btn" onclick="go(' + (cur + 1) + ')" ' + (cur >= last ? 'disabled' : '') +
                '>›</button>';
                el.innerHTML = h;
            }

            function getPages(cur, last) {
                if (last <= 7) return Array.from({
                    length: last
                }, function(_, i) {
                    return i + 1;
                });
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
                return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }

            // Filter events
            document.getElementById('srch').addEventListener('input', function() {
                clearTimeout(timer);
                timer = setTimeout(function() {
                    state.search = document.getElementById('srch').value;
                    state.page = 1;
                    load();
                }, 400);
            });
            document.getElementById('catFilter').addEventListener('change', function() {
                state.category = this.value;
                state.page = 1;
                load();
            });
            document.getElementById('statusFilter').addEventListener('change', function() {
                state.status = this.value;
                state.page = 1;
                load();
            });
            document.getElementById('perPage').addEventListener('change', function() {
                state.perPage = this.value === 'all' ? 'all' : parseInt(this.value);
                state.page = 1;
                load();
            });

            // Load on page ready
            document.addEventListener('DOMContentLoaded', load);
        </script>
    @endpush
@endsection
