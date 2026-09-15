@extends('admin.layouts.app')
@section('title', $cfg['title'])
@section('content')

    <style>
        .page-hdr {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .page-title {
            font-family: 'Cinzel', serif;
            font-size: 16px;
            font-weight: 700;
            color: #00285a;
            letter-spacing: 1px;
        }

        .tab-bar {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 6px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: .15s;
        }

        .tab-btn.active {
            background: #00285a;
            color: white;
        }

        .tab-btn.inactive {
            background: #f1f5f9;
            color: #555;
        }

        .tab-btn.inactive:hover {
            background: #e2e8f0;
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

        .btn-primary {
            background: #00285a;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: inherit;
            transition: .15s;
        }

        .btn-primary:hover {
            background: #1e3f75;
        }

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
            padding: 11px 14px;
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

        .btn-sm {
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            font-family: inherit;
            transition: .15s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .color-dot {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 2px solid #e8edf5;
            display: inline-block;
            flex-shrink: 0;
        }

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

        /* Modal */
        .modal-bg {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 200;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .modal-bg.open {
            display: flex;
        }

        .modal {
            background: white;
            border-radius: 16px;
            padding: 26px;
            width: 100%;
            max-width: 460px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .15);
        }

        .modal-title {
            font-family: 'Cinzel', serif;
            font-size: 14px;
            font-weight: 700;
            color: #00285a;
            margin-bottom: 20px;
        }

        .fgrp {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-bottom: 14px;
        }

        .fgrp label {
            font-size: 11px;
            font-weight: 700;
            color: #7a8fa6;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .fgrp input,
        .fgrp select,
        .fgrp textarea {
            padding: 10px 14px;
            border: 1.5px solid #e8edf5;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: .15s;
            width: 100%;
            background: white;
            box-sizing: border-box;
        }

        .fgrp input:focus,
        .fgrp select:focus {
            border-color: #00285a;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-cancel-sm {
            background: white;
            color: #555;
            border: 1px solid #e8edf5;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
        }

        .err-msg {
            color: #c62828;
            font-size: 12px;
            margin-bottom: 10px;
            display: none;
            padding: 8px 12px;
            background: #fce4ec;
            border-radius: 8px;
        }
    </style>

    <div class="page-hdr">
        <div class="page-title">Masters</div>
        <div class="tab-bar">
            <a href="{{ route('admin.masters.index', 'sizes') }}"
                class="tab-btn {{ $type === 'sizes' ? 'active' : 'inactive' }}">
                <i class="bi bi-rulers"></i> Sizes
            </a>
            <a href="{{ route('admin.masters.index', 'colors') }}"
                class="tab-btn {{ $type === 'colors' ? 'active' : 'inactive' }}">
                <i class="bi bi-palette"></i> Colors
            </a>
        </div>
    </div>

    @if (session('success'))
        <div
            style="background:#e8f5e9;color:#2e7d32;padding:10px 16px;border-radius:10px;margin-bottom:14px;font-size:13px;font-weight:600">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-hdr">
            <div class="card-title">
                All {{ $cfg['title'] }}
                <span id="totalCount" style="color:#7a8fa6;font-weight:400"></span>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <div class="filter-bar">
                    <input type="text" id="srch" class="search-box"
                        placeholder="Search {{ strtolower($cfg['single']) }}...">
                    <select id="statusFilter" class="fsel">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    @if ($cfg['has_type'])
                        <select id="typeFilter" class="fsel">
                            <option value="">All Types</option>
                            @foreach ($cfg['type_options'] as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    @endif
                    <select id="perPage" class="fsel">
                        <option value="10">10 / page</option>
                        <option value="25">25 / page</option>
                        <option value="50">50 / page</option>
                    </select>
                </div>
                <button class="btn-primary" onclick="openModal()">
                    <i class="bi bi-plus-lg"></i> Add {{ $cfg['single'] }}
                </button>
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
                            <th>Name</th>
                            @if ($cfg['has_hex'])
                                <th>Color</th>
                                <th>T-Shirt</th>
                            @endif
                            @if ($cfg['has_type'])
                                <th>Type</th>
                            @endif
                            @if ($cfg['has_measurements'])
                                <th>Label</th>
                                <th>Chest / Waist</th>
                                <th>Length</th>
                            @endif
                            <th>Sort</th>
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

    {{-- ── MODAL ── --}}
    <div class="modal-bg" id="modalBg">
        <div class="modal">
            <div class="modal-title" id="modalTitle">Add {{ $cfg['single'] }}</div>
            <div id="errMsg" class="err-msg"></div>

            <div class="fgrp">
                <label>Name *</label>
                <input type="text" id="fName"
                    placeholder="{{ $type === 'colors' ? 'e.g. Cloud Grey, Jet Black' : 'e.g. S, M, XL' }}">
            </div>

            @if ($cfg['has_hex'])
                <div class="fgrp">
                    <label>Hex Color</label>
                    <div style="display:flex;gap:10px;align-items:center">
                        <input type="color" id="fHexPicker" value="#000000"
                            style="width:52px;height:44px;padding:3px;border-radius:8px;border:1.5px solid #e8edf5;cursor:pointer;flex-shrink:0"
                            oninput="document.getElementById('fHex').value=this.value">
                        <input type="text" id="fHex" placeholder="#000000"
                            oninput="if(this.value.length>=4){document.getElementById('fHexPicker').value=this.value}">
                    </div>
                </div>
            @endif

            @if ($type === 'colors')
                <div class="fgrp">
                    <label>T-Shirt Mockup / Photo <span style="font-weight:400;color:#7a8fa6">(Optional)</span></label>
                    <div style="display:flex;gap:12px;align-items:center">
                        <div id="fImgPreviewBox" style="width:52px;height:52px;border-radius:8px;border:1.5px dashed #cbd5e1;display:flex;align-items:center;justify-content:center;background:#f8fafc;overflow:hidden;flex-shrink:0">
                            <img id="fImgPreview" src="" alt="Preview" style="display:none;width:100%;height:100%;object-fit:cover">
                            <i id="fImgPlaceholder" class="bi bi-image" style="font-size:20px;color:#94a3b8"></i>
                        </div>
                        <div style="flex:1">
                            <input type="file" id="fImage" accept="image/jpeg,image/png,image/webp" style="display:none" onchange="previewMasterImage(this)">
                            <button type="button" class="btn-cancel-sm" style="padding:6px 12px;font-size:12px" onclick="document.getElementById('fImage').click()">
                                <i class="bi bi-upload"></i> Choose Photo
                            </button>
                            <button type="button" id="fImgRemoveBtn" class="btn-cancel-sm" style="padding:6px 12px;font-size:12px;color:#c62828;border-color:#fecaca;margin-left:6px;display:none" onclick="removeMasterImage()">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                            <div style="font-size:11px;color:#94a3b8;margin-top:4px">Upload a mockup or sample photo for this t-shirt color (Max 5MB).</div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($cfg['has_type'])
                <div class="fgrp">
                    <label>Category Type</label>
                    <select id="fType">
                        @foreach ($cfg['type_options'] as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if ($cfg['has_measurements'])
                <div class="fgrp">
                    <label>Label <span style="font-weight:400;color:#7a8fa6">(e.g. Small, Medium)</span></label>
                    <input type="text" id="fLabel" placeholder="e.g. Medium">
                </div>
                <div class="form-row">
                    <div class="fgrp">
                        <label>Chest / Waist</label>
                        <input type="text" id="fChest" placeholder="e.g. 32-34 inches">
                    </div>
                    <div class="fgrp">
                        <label>Length</label>
                        <input type="text" id="fLength" placeholder="e.g. 41 inches">
                    </div>
                </div>
            @endif

            <div class="form-row">
                <div class="fgrp">
                    <label>Sort Order</label>
                    <input type="number" id="fSort" value="0" min="0">
                </div>
                <div class="fgrp" style="justify-content:flex-end;padding-top:20px">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                        <input type="checkbox" id="fActive" checked
                            style="width:17px;height:17px;accent-color:#00285a">
                        <span style="font-size:13px;font-weight:600;color:#333">Active</span>
                    </label>
                </div>
            </div>

            <div class="modal-actions">
                <button class="btn-primary" id="saveBtn" onclick="saveItem()">
                    <i class="bi bi-check-lg"></i> Save {{ $cfg['single'] }}
                </button>
                <button class="btn-cancel-sm" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            var CSRF = document.querySelector('meta[name="csrf-token"]').content;
            var TYPE = '{{ $type }}';
            var BASE = '{{ url('admin/masters') }}';
            var AJAX = '{{ route('admin.masters.ajax', $type) }}';

            var HAS_HEX = {{ $cfg['has_hex'] ? 'true' : 'false' }};
            var HAS_MEASUREMENTS = {{ $cfg['has_measurements'] ? 'true' : 'false' }};
            var HAS_TYPE = {{ $cfg['has_type'] ? 'true' : 'false' }};

            var state = {
                page: 1,
                perPage: 10,
                search: '',
                status: '',
                type: ''
            };
            var timer;
            var editId = null;
            var removeImage = false;

            function previewMasterImage(input) {
                if (input.files && input.files[0]) {
                    removeImage = false;
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var p = document.getElementById('fImgPreview');
                        var ph = document.getElementById('fImgPlaceholder');
                        var rb = document.getElementById('fImgRemoveBtn');
                        if (p) { p.src = e.target.result; p.style.display = 'block'; }
                        if (ph) ph.style.display = 'none';
                        if (rb) rb.style.display = 'inline-block';
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            function removeMasterImage() {
                removeImage = true;
                var fImg = document.getElementById('fImage');
                if (fImg) fImg.value = '';
                var p = document.getElementById('fImgPreview');
                var ph = document.getElementById('fImgPlaceholder');
                var rb = document.getElementById('fImgRemoveBtn');
                if (p) { p.src = ''; p.style.display = 'none'; }
                if (ph) ph.style.display = 'block';
                if (rb) rb.style.display = 'none';
            }

            // ── DATATABLE ──────────────────────────────────────
            function load() {
                document.getElementById('overlay').classList.add('on');
                var p = new URLSearchParams({
                    page: state.page,
                    per_page: state.perPage,
                    search: state.search,
                    status: state.status,
                    type: state.type,
                });
                fetch(AJAX + '?' + p, {
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
                var cols = 4 + (HAS_HEX ? 2 : 0) + (HAS_TYPE ? 1 : 0) + (HAS_MEASUREMENTS ? 3 : 0);
                if (!rows.length) {
                    tb.innerHTML = `<tr><td colspan="${cols}" style="text-align:center;padding:50px;color:#7a8fa6">
            <i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:10px"></i>
            No {{ strtolower($cfg['title']) }} found</td></tr>`;
                    return;
                }
                var offset = (state.page - 1) * state.perPage;
                tb.innerHTML = rows.map((item, i) => {
                    var num = offset + i + 1;
                    var status = item.is_active ?
                        '<span class="bdg bdg-g">Active</span>' :
                        '<span class="bdg bdg-r">Inactive</span>';
                    var togBg = item.is_active ? '#fce4ec' : '#e8f5e9';
                    var togCol = item.is_active ? '#c62828' : '#2e7d32';
                    var togIco = item.is_active ? 'eye-slash' : 'eye';

                    var extraCols = '';
                    if (HAS_HEX) {
                        var hexVal = esc(item.hex || item.hex_code || '#cccccc');
                        var imgHtml = item.image
                            ? `<a href="${esc(item.image)}" target="_blank" title="View Mockup"><img src="${esc(item.image)}" style="width:36px;height:36px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0" alt="T-Shirt"></a>`
                            : `<span style="font-size:11px;color:#94a3b8;font-style:italic">No Mockup</span>`;
                        extraCols += `<td>
                <div style="display:flex;align-items:center;gap:8px">
                    <span class="color-dot" style="background:${hexVal}"></span>
                    <span style="font-size:12px;color:#7a8fa6;font-family:monospace">${hexVal}</span>
                </div>
            </td>
            <td>${imgHtml}</td>`;
                    }
                    if (HAS_TYPE) {
                        extraCols += `<td><span class="bdg bdg-b">${esc(item.type||'—')}</span></td>`;
                    }
                    if (HAS_MEASUREMENTS) {
                        extraCols += `
                <td style="font-size:12px;color:#555">${esc(item.label||'—')}</td>
                <td style="font-size:12px;color:#555">${esc(item.chest||item.waist||'—')}</td>
                <td style="font-size:12px;color:#555">${esc(item.length||'—')}</td>`;
                    }

                    return `<tr>
            <td style="color:#7a8fa6;font-size:12px">${num}</td>
            <td><strong style="color:#00285a">${esc(item.name)}</strong></td>
            ${extraCols}
            <td style="color:#7a8fa6;font-size:12px">${item.sort_order}</td>
            <td>${status}</td>
            <td>
                <div style="display:flex;gap:6px">
                    <button class="btn-sm" style="background:#e3f2fd;color:#1565c0"
                        onclick='openEdit(${item.id}, ${JSON.stringify(item)})'>
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                    <button class="btn-sm" style="background:${togBg};color:${togCol}"
                        onclick="toggleItem(${item.id})">
                        <i class="bi bi-${togIco}"></i>
                    </button>
                    <button class="btn-sm" style="background:#fce4ec;color:#c62828"
                        onclick="deleteItem(${item.id}, '${esc(item.name)}')">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        </tr>`;
                }).join('');
            }

            function renderInfo(from, to, total) {
                document.getElementById('dtInfo').textContent =
                    total > 0 ? `Showing ${from} – ${to} of ${total} {{ strtolower($cfg['title']) }}` : 'No results found';
            }

            function renderPages(cur, last) {
                var el = document.getElementById('dtPages');
                if (last <= 1) {
                    el.innerHTML = '';
                    return;
                }
                var html = `<button class="pg-btn" onclick="go(${cur-1})" ${cur<=1?'disabled':''}>‹</button>`;
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

            // ── EVENTS ────────────────────────────────────────
            document.getElementById('srch').addEventListener('input', function() {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    state.search = this.value;
                    state.page = 1;
                    load();
                }, 400);
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
            @if ($cfg['has_type'])
                document.getElementById('typeFilter').addEventListener('change', function() {
                    state.type = this.value;
                    state.page = 1;
                    load();
                });
            @endif

            // ── MODAL ─────────────────────────────────────────
            function openModal() {
                editId = null;
                clearForm();
                document.getElementById('modalTitle').textContent = 'Add {{ $cfg['single'] }}';
                document.getElementById('modalBg').classList.add('open');
                setTimeout(() => document.getElementById('fName').focus(), 100);
            }

            function openEdit(id, item) {
                editId = id;
                document.getElementById('modalTitle').textContent = 'Edit {{ $cfg['single'] }}';
                document.getElementById('fName').value = item.name || '';
                document.getElementById('fSort').value = item.sort_order || 0;
                document.getElementById('fActive').checked = !!item.is_active;
                if (HAS_HEX) {
                    document.getElementById('fHex').value = item.hex || '#000000';
                    document.getElementById('fHexPicker').value = item.hex || '#000000';
                }
                if (HAS_TYPE && document.getElementById('fType')) {
                    document.getElementById('fType').value = item.type || 'clothing';
                }
                if (HAS_MEASUREMENTS) {
                    document.getElementById('fLabel').value = item.label || '';
                    document.getElementById('fChest').value = item.chest || '';
                    document.getElementById('fLength').value = item.length || '';
                }
                removeImage = false;
                if (TYPE === 'colors') {
                    var fImg = document.getElementById('fImage');
                    if (fImg) fImg.value = '';
                    var p = document.getElementById('fImgPreview');
                    var ph = document.getElementById('fImgPlaceholder');
                    var rb = document.getElementById('fImgRemoveBtn');
                    if (item.image) {
                        if (p) { p.src = item.image; p.style.display = 'block'; }
                        if (ph) ph.style.display = 'none';
                        if (rb) rb.style.display = 'inline-block';
                    } else {
                        if (p) { p.src = ''; p.style.display = 'none'; }
                        if (ph) ph.style.display = 'block';
                        if (rb) rb.style.display = 'none';
                    }
                }
                hideErr();
                document.getElementById('modalBg').classList.add('open');
                setTimeout(() => document.getElementById('fName').focus(), 100);
            }

            function closeModal() {
                document.getElementById('modalBg').classList.remove('open');
                hideErr();
            }

            function clearForm() {
                removeMasterImage();
                removeImage = false;
                document.getElementById('fName').value = '';
                document.getElementById('fSort').value = '0';
                document.getElementById('fActive').checked = true;
                if (HAS_HEX) {
                    document.getElementById('fHex').value = '#000000';
                    document.getElementById('fHexPicker').value = '#000000';
                }
                if (HAS_MEASUREMENTS) {
                    document.getElementById('fLabel').value = '';
                    document.getElementById('fChest').value = '';
                    document.getElementById('fLength').value = '';
                }
                hideErr();
            }

            async function saveItem() {
                var name = document.getElementById('fName').value.trim();
                if (!name) {
                    showErr('Name is required');
                    return;
                }

                var btn = document.getElementById('saveBtn');
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';

                var fd = new FormData();
                fd.append('name', name);
                fd.append('sort_order', parseInt(document.getElementById('fSort').value) || 0);
                fd.append('is_active', document.getElementById('fActive').checked ? '1' : '0');

                if (HAS_HEX) {
                    fd.append('hex', document.getElementById('fHex').value || '#000000');
                }
                if (HAS_MEASUREMENTS) {
                    fd.append('label', document.getElementById('fLabel').value || '');
                    fd.append('chest', document.getElementById('fChest').value || '');
                    fd.append('length', document.getElementById('fLength').value || '');
                }
                if (HAS_TYPE && document.getElementById('fType')) {
                    fd.append('type', document.getElementById('fType').value);
                }
                if (TYPE === 'colors') {
                    var fImg = document.getElementById('fImage');
                    if (fImg && fImg.files && fImg.files[0]) {
                        fd.append('image', fImg.files[0]);
                    }
                    if (removeImage) {
                        fd.append('remove_image', '1');
                    }
                }

                var url = editId ? `${BASE}/${TYPE}/${editId}` : `${BASE}/${TYPE}`;
                if (editId) {
                    fd.append('_method', 'PUT');
                }

                try {
                    var res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CSRF,
                            'Accept': 'application/json'
                        },
                        body: fd,
                    });
                    var data = await res.json();
                    if (data.success) {
                        closeModal();
                        load();
                    } else showErr(data.message || 'Something went wrong');
                } catch (e) {
                    showErr('Server error — please try again');
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-check-lg"></i> Save {{ $cfg['single'] }}';
                }
            }

            async function toggleItem(id) {
                var res = await fetch(`${BASE}/${TYPE}/${id}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                var data = await res.json();
                if (data.success) load();
            }

            async function deleteItem(id, name) {
                if (!confirm(`Delete "${name}"?\nThis action cannot be undone.`)) return;
                var res = await fetch(`${BASE}/${TYPE}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                var data = await res.json();
                if (data.success) load();
            }

            function showErr(msg) {
                var el = document.getElementById('errMsg');
                el.textContent = msg;
                el.style.display = 'block';
            }

            function hideErr() {
                document.getElementById('errMsg').style.display = 'none';
            }

            function esc(s) {
                if (!s) return '';
                return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }

            document.getElementById('modalBg').addEventListener('click', function(e) {
                if (e.target === this) closeModal();
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeModal();
                if (e.key === 'Enter' && document.getElementById('modalBg').classList.contains('open')) saveItem();
            });

            load(); // Initial load
        </script>
    @endpush
@endsection
