{{-- resources/views/admin/categories/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Categories')
@section('content')

    <style>
        .page-hdr {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .page-title {
            font-family: 'Cinzel', serif;
            font-size: 16px;
            font-weight: 700;
            color: #00285a;
            letter-spacing: 1px;
        }

        .card {
            background: white;
            border: 1px solid #eef2f6;
            border-radius: 14px;
            overflow: hidden;
        }

        .card-hdr {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 18px;
            border-bottom: 1px solid #eef2f6;
            flex-wrap: wrap;
            gap: 10px;
        }

        .card-title {
            font-family: 'Cinzel', serif;
            font-size: 12px;
            font-weight: 700;
            color: #00285a;
            letter-spacing: 1px;
        }

        .filter-bar {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
        }

        .search-box {
            padding: 8px 14px;
            border: 1.5px solid #e8edf5;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            outline: none;
            transition: .15s;
            width: 200px;
        }

        .search-box:focus {
            border-color: #00285a;
        }

        .fsel {
            padding: 8px 12px;
            border: 1.5px solid #e8edf5;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            outline: none;
            background: white;
            cursor: pointer;
        }

        .fsel:focus {
            border-color: #00285a;
        }

        /* Table */
        .dt {
            width: 100%;
            border-collapse: collapse;
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
            white-space: nowrap;
        }

        .dt td {
            padding: 12px 14px;
            font-size: 13px;
            border-bottom: 1px solid rgba(0, 0, 0, .04);
            vertical-align: middle;
        }

        .dt tr:last-child td {
            border-bottom: none;
        }

        .dt tr:hover td {
            background: #fafbff;
        }

        /* Badges */
        .bdg {
            display: inline-flex;
            align-items: center;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .bdg-g {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .bdg-r {
            background: #fce4ec;
            color: #c62828;
        }

        .bdg-b {
            background: #e3f2fd;
            color: #1565c0;
        }

        .bdg-off {
            background: #f1f5f9;
            color: #64748b;
        }

        /* Action buttons */
        .btn-edit {
            background: #00285a;
            color: white;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: .15s;
        }

        .btn-edit:hover {
            background: #1e3f75;
        }

        .btn-tog {
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: .15s;
        }

        /* Bottom bar */
        .dt-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 18px;
            border-top: 1px solid #eef2f6;
            flex-wrap: wrap;
            gap: 8px;
        }

        .dt-info {
            font-size: 12px;
            color: #7a8fa6;
        }

        .dt-pages {
            display: flex;
            gap: 3px;
            flex-wrap: wrap;
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
            transition: .15s;
        }

        .pg-btn:hover {
            background: #f0f4f8;
        }

        .pg-btn.active {
            background: #00285a;
            color: white;
            border-color: #00285a;
        }

        .pg-btn:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        /* Loading */
        .tbl-wrap {
            position: relative;
        }

        .dt-overlay {
            display: none;
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, .75);
            align-items: center;
            justify-content: center;
            z-index: 5;
        }

        .dt-overlay.on {
            display: flex;
        }

        .spin {
            width: 26px;
            height: 26px;
            border: 3px solid #eef2f6;
            border-top-color: #00285a;
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Category image thumb */
        .cat-img {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #eef2f6;
        }

        .cat-img-ph {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Parent indent */
        .sub-arrow {
            color: #cbd5e1;
            margin-right: 4px;
            font-size: 12px;
        }
    </style>

    <div class="page-hdr">
        <div class="page-title">Categories</div>
        <a href="{{ route('admin.categories.create') }}" class="btn-edit"
            style="border-radius:8px;padding:8px 18px;font-size:13px">
            <i class="bi bi-plus-lg"></i> Add Category
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
            <div class="card-title">All Categories <span id="totalCount" style="color:#7a8fa6;font-weight:400"></span></div>
            <div class="filter-bar">
                <input type="text" id="srch" class="search-box" placeholder="Search name or slug...">
                <select id="typeFilter" class="fsel">
                    <option value="">All Types</option>
                    <option value="parent">Parent Only</option>
                    <option value="child">Sub Categories</option>
                </select>
                <select id="statusFilter" class="fsel">
                    <option value="">All Status</option>
                    <option value="1" selected>Active</option>
                    <option value="0">Inactive</option>
                </select>
                <select id="perPage" class="fsel">
                    <option value="10">10 / page</option>
                    <option value="25">25 / page</option>
                    <option value="50">50 / page</option>
                </select>
            </div>
        </div>

        <div class="tbl-wrap">
            <div class="dt-overlay" id="overlay">
                <div class="spin"></div>
            </div>
            <div style="overflow-x:auto">
                <table class="dt">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th>Parent</th>
                            <th>Products</th>
                            <th style="text-align:center">Nav</th>
                            <th style="text-align:center">Home</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tBody">
                        <tr>
                            <td colspan="8" style="text-align:center;padding:40px;color:#7a8fa6">Loading...</td>
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
            var URL_AJAX = '{{ route('admin.categories.ajax') }}';
            var URL_TOGGLE = '/admin/categories/{id}/toggle';
            var CSRF = document.querySelector('meta[name="csrf-token"]').content;

            var state = {
                page: 1,
                perPage: 10,
                search: '',
                type: '',
                status: '1'
            };
            var timer;

            function load() {
                document.getElementById('overlay').classList.add('on');
                var p = new URLSearchParams({
                    page: state.page,
                    per_page: state.perPage,
                    search: state.search,
                    parent: state.type,
                    status: state.status,
                });
                fetch(URL_AJAX + '?' + p, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF
                        }
                    })
                    .then(r => r.json())
                    .then(res => {
                        renderRows(res.data);
                        renderInfo(res.from, res.to, res.total);
                        renderPages(res.current_page, res.last_page);
                        document.getElementById('totalCount').textContent = '(' + res.total + ')';
                        document.getElementById('overlay').classList.remove('on');
                    })
                    .catch(() => document.getElementById('overlay').classList.remove('on'));
            }

            function renderRows(rows) {
                var tb = document.getElementById('tBody');
                if (!rows.length) {
                    tb.innerHTML =
                        '<tr><td colspan="8" style="text-align:center;padding:40px;color:#7a8fa6"><i class="bi bi-tags" style="font-size:28px;display:block;margin-bottom:8px"></i>No categories found</td></tr>';
                    return;
                }
                var offset = (state.page - 1) * state.perPage;
                tb.innerHTML = rows.map((c, i) => {
                    var num = offset + i + 1;
                    var img = c.image ?
                        '<img src="' + esc(c.image) + '" class="cat-img" alt="">' :
                        '<div class="cat-img-ph"><i class="bi bi-image" style="color:#cbd5e1;font-size:14px"></i></div>';
                    var name = (c.parent_id ? '<span class="sub-arrow">↳</span>' : '') + esc(c.name);
                    var parent = c.parent_name ? '<span style="color:#555;font-size:12px">' + esc(c.parent_name) +
                        '</span>' : '<span style="color:#cbd5e1">—</span>';
                    var nav = c.show_in_nav ?
                        '<i class="bi bi-check-circle-fill" style="color:#22c55e;font-size:15px"></i>' :
                        '<i class="bi bi-x-circle" style="color:#e2e8f0;font-size:15px"></i>';
                    var home = c.show_in_home ?
                        '<i class="bi bi-check-circle-fill" style="color:#22c55e;font-size:15px"></i>' :
                        '<i class="bi bi-x-circle" style="color:#e2e8f0;font-size:15px"></i>';
                    var status = c.is_active ?
                        '<span class="bdg bdg-g">Active</span>' :
                        '<span class="bdg bdg-r">Inactive</span>';
                    var togBg = c.is_active ? '#fce4ec' : '#e8f5e9';
                    var togCol = c.is_active ? '#c62828' : '#2e7d32';
                    var togTxt = c.is_active ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';

                    return `<tr>
            <td style="color:#7a8fa6;font-size:12px">${num}</td>
            <td>
                <div style="display:flex;align-items:center;gap:10px">
                    ${img}
                    <div>
                        <div style="font-weight:600;color:#00285a">${name}</div>
                        <div style="font-size:11px;color:#7a8fa6">${esc(c.slug)}</div>
                    </div>
                </div>
            </td>
            <td>${parent}</td>
            <td><span class="bdg bdg-b">${c.products_count}</span></td>
            <td style="text-align:center">${nav}</td>
            <td style="text-align:center">${home}</td>
            <td>${status}</td>
            <td>
                <div style="display:flex;gap:6px">
                    <a href="${c.edit_url}" class="btn-edit"><i class="bi bi-pencil"></i> Edit</a>
                    <button class="btn-tog" style="background:${togBg};color:${togCol}" onclick="toggle(${c.id})">${togTxt}</button>
                </div>
            </td>
        </tr>`;
                }).join('');
            }

            function renderInfo(from, to, total) {
                document.getElementById('dtInfo').textContent =
                    total > 0 ? 'Showing ' + from + ' – ' + to + ' of ' + total + ' categories' : 'No results found';
            }

            function renderPages(cur, last) {
                var el = document.getElementById('dtPages');
                if (last <= 1) {
                    el.innerHTML = '';
                    return;
                }
                var html = '';
                html += `<button class="pg-btn" onclick="go(${cur-1})" ${cur<=1?'disabled':''}>‹</button>`;
                pages(cur, last).forEach(p => {
                    if (p === '…') html += `<span style="padding:5px 4px;color:#7a8fa6;font-size:12px">…</span>`;
                    else html += `<button class="pg-btn ${p===cur?'active':''}" onclick="go(${p})">${p}</button>`;
                });
                html += `<button class="pg-btn" onclick="go(${cur+1})" ${cur>=last?'disabled':''}>›</button>`;
                el.innerHTML = html;
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

            function toggle(id) {
                fetch('/admin/categories/' + id + '/toggle', {
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

            function esc(s) {
                if (!s) return '';
                return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }

            // Events
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
            document.getElementById('perPage').addEventListener('change', function() {
                state.perPage = parseInt(this.value);
                state.page = 1;
                load();
            });

            load();
        </script>
    @endpush
@endsection
