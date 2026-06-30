@extends('admin.layouts.app')
@section('title', 'FAQs')
@section('content')

<style>
.page-hdr { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:10px; }
.page-title { font-family:'Cinzel',serif; font-size:16px; font-weight:700; color:#00285a; letter-spacing:1px; }
.card { background:white; border:1px solid #eef2f6; border-radius:14px; overflow:hidden; }
.card-hdr { display:flex; justify-content:space-between; align-items:center; padding:14px 18px; border-bottom:1px solid #eef2f6; flex-wrap:wrap; gap:10px; }
.card-title { font-family:'Cinzel',serif; font-size:12px; font-weight:700; color:#00285a; letter-spacing:1px; }
.filter-bar { display:flex; gap:8px; flex-wrap:wrap; align-items:center; }
.search-box { padding:8px 14px; border:1.5px solid #e8edf5; border-radius:8px; font-size:13px; font-family:inherit; outline:none; width:200px; }
.search-box:focus { border-color:#00285a; }
.fsel { padding:8px 12px; border:1.5px solid #e8edf5; border-radius:8px; font-size:13px; font-family:inherit; outline:none; background:white; cursor:pointer; }
.btn-primary { background:#00285a; color:white; border:none; padding:8px 18px; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px; font-family:inherit; }
.btn-primary:hover { background:#1e3f75; }
.dt { width:100%; border-collapse:collapse; }
.dt th { padding:10px 14px; font-size:10px; font-weight:700; color:#7a8fa6; text-transform:uppercase; letter-spacing:.8px; background:#f8fafc; border-bottom:1px solid #eef2f6; text-align:left; white-space:nowrap; }
.dt td { padding:11px 14px; font-size:13px; border-bottom:1px solid rgba(0,0,0,.04); vertical-align:middle; }
.dt tr:last-child td { border-bottom:none; }
.dt tr:hover td { background:#fafbff; }
.bdg { display:inline-flex; align-items:center; padding:2px 10px; border-radius:20px; font-size:11px; font-weight:700; }
.bdg-g { background:#e8f5e9; color:#2e7d32; }
.bdg-r { background:#fce4ec; color:#c62828; }
.bdg-b { background:#e3f2fd; color:#1565c0; }
.bdg-y { background:#fff8e1; color:#f57f17; }
.bdg-p { background:#f3e5f5; color:#6a1b9a; }
.btn-sm { padding:5px 12px; border-radius:6px; font-size:11px; font-weight:700; border:none; cursor:pointer; font-family:inherit; display:inline-flex; align-items:center; gap:4px; }
.tbl-wrap { position:relative; }
.dt-overlay { display:none; position:absolute; inset:0; background:rgba(255,255,255,.75); align-items:center; justify-content:center; z-index:5; }
.dt-overlay.on { display:flex; }
.spin { width:26px; height:26px; border:3px solid #eef2f6; border-top-color:#00285a; border-radius:50%; animation:spin .7s linear infinite; }
@keyframes spin { to { transform:rotate(360deg); } }
.dt-bottom { display:flex; justify-content:space-between; align-items:center; padding:12px 18px; border-top:1px solid #eef2f6; flex-wrap:wrap; gap:8px; }
.dt-info { font-size:12px; color:#7a8fa6; }
.dt-pages { display:flex; gap:3px; }
.pg-btn { padding:5px 12px; border-radius:6px; font-size:12px; font-weight:600; border:1px solid #eef2f6; background:white; color:#555; cursor:pointer; }
.pg-btn:hover { background:#f0f4f8; }
.pg-btn.active { background:#00285a; color:white; border-color:#00285a; }
.pg-btn:disabled { opacity:.4; cursor:not-allowed; }
.modal-bg { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:200; align-items:center; justify-content:center; padding:16px; }
.modal-bg.open { display:flex; }
.modal { background:white; border-radius:16px; padding:26px; width:100%; max-width:540px; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,.15); }
.modal-title { font-family:'Cinzel',serif; font-size:14px; font-weight:700; color:#00285a; margin-bottom:20px; }
.fgrp { display:flex; flex-direction:column; gap:5px; margin-bottom:14px; }
.fgrp label { font-size:11px; font-weight:700; color:#7a8fa6; text-transform:uppercase; letter-spacing:.5px; }
.fgrp input, .fgrp select, .fgrp textarea { padding:10px 14px; border:1.5px solid #e8edf5; border-radius:10px; font-size:14px; font-family:inherit; outline:none; transition:.15s; width:100%; background:white; box-sizing:border-box; }
.fgrp input:focus, .fgrp select:focus, .fgrp textarea:focus { border-color:#00285a; }
.form-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.modal-actions { display:flex; gap:10px; margin-top:20px; }
.btn-cancel-sm { background:white; color:#555; border:1px solid #e8edf5; padding:9px 18px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; font-family:inherit; }
.err-msg { color:#c62828; font-size:12px; margin-bottom:10px; display:none; padding:8px 12px; background:#fce4ec; border-radius:8px; }
</style>

<div class="page-hdr">
    <div class="page-title">FAQs</div>
    <button class="btn-primary" onclick="openModal()">
        <i class="bi bi-plus-lg"></i> Add FAQ
    </button>
</div>

<div class="card">
    <div class="card-hdr">
        <div class="card-title">All FAQs <span id="totalCount" style="color:#7a8fa6;font-weight:400"></span></div>
        <div class="filter-bar">
            <input type="text" id="srch" class="search-box" placeholder="Search question...">
            <select id="catFilter" class="fsel">
                <option value="">All Categories</option>
                <option value="general">General</option>
                <option value="shipping">Shipping</option>
                <option value="returns">Returns</option>
                <option value="product">Product</option>
            </select>
            <select id="statusFilter" class="fsel">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            <select id="perPage" class="fsel">
                <option value="10">10 / page</option>
                <option value="25">25 / page</option>
            </select>
        </div>
    </div>

    <div class="tbl-wrap">
        <div class="dt-overlay" id="overlay"><div class="spin"></div></div>
        <div style="overflow-x:auto">
            <table class="dt">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Question</th>
                        <th>Category</th>
                        <th>Sort</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tBody">
                    <tr><td colspan="6" style="text-align:center;padding:40px;color:#7a8fa6">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="dt-bottom">
        <div class="dt-info" id="dtInfo"></div>
        <div class="dt-pages" id="dtPages"></div>
    </div>
</div>

{{-- MODAL --}}
<div class="modal-bg" id="modalBg">
    <div class="modal">
        <div class="modal-title" id="modalTitle">Add FAQ</div>
        <div id="errMsg" class="err-msg"></div>

        <div class="fgrp">
            <label>Question *</label>
            <input type="text" id="fQuestion" placeholder="e.g. What is your return policy?">
        </div>

        <div class="fgrp">
            <label>Answer *</label>
            <textarea id="fAnswer" rows="4" placeholder="Write the answer here..."></textarea>
        </div>

        <div class="form-row">
            <div class="fgrp">
                <label>Category *</label>
                <select id="fCategory">
                    <option value="general">General</option>
                    <option value="shipping">Shipping</option>
                    <option value="returns">Returns</option>
                    <option value="product">Product</option>
                </select>
            </div>
            <div class="fgrp">
                <label>Sort Order</label>
                <input type="number" id="fSort" value="0" min="0">
            </div>
        </div>

        <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px">
            <input type="checkbox" id="fActive" checked style="width:17px;height:17px;accent-color:#00285a">
            <label for="fActive" style="font-size:13px;font-weight:600;color:#333;cursor:pointer">Active</label>
        </div>

        <div class="modal-actions">
            <button class="btn-primary" id="saveBtn" onclick="saveItem()">
                <i class="bi bi-check-lg"></i> Save FAQ
            </button>
            <button class="btn-cancel-sm" onclick="closeModal()">Cancel</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
var CSRF   = document.querySelector('meta[name="csrf-token"]').content;
var AJAX   = '{{ route("admin.faqs.ajax") }}';
var BASE   = '{{ url("admin/faqs") }}';
var editId = null;
var state  = { page:1, perPage:10, search:'', category:'', status:'' };
var timer;

var CAT_COLORS = {
    general:  'bdg-b',
    shipping: 'bdg-y',
    returns:  'bdg-r',
    product:  'bdg-p',
};

function load() {
    document.getElementById('overlay').classList.add('on');
    var p = new URLSearchParams({ page:state.page, per_page:state.perPage, search:state.search, category:state.category, status:state.status });
    fetch(AJAX + '?' + p, { headers:{ 'Accept':'application/json','X-CSRF-TOKEN':CSRF }})
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
        tb.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:40px;color:#7a8fa6"><i class="bi bi-question-circle" style="font-size:32px;display:block;margin-bottom:10px"></i>No FAQs found</td></tr>';
        return;
    }
    var offset = (state.page - 1) * state.perPage;
    tb.innerHTML = rows.map((f, i) => {
        var num    = offset + i + 1;
        var catCls = CAT_COLORS[f.category] || 'bdg-b';
        var status = f.is_active ? '<span class="bdg bdg-g">Active</span>' : '<span class="bdg bdg-r">Inactive</span>';
        var togBg  = f.is_active ? '#fce4ec' : '#e8f5e9';
        var togCol = f.is_active ? '#c62828' : '#2e7d32';

        return `<tr>
            <td style="color:#7a8fa6;font-size:12px">${num}</td>
            <td>
                <div style="font-weight:600;color:#00285a;max-width:300px">${esc(f.question)}</div>
                <div style="font-size:11px;color:#7a8fa6;margin-top:3px;max-width:300px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${esc(f.answer)}</div>
            </td>
            <td><span class="bdg ${catCls}">${f.category}</span></td>
            <td style="font-size:12px;color:#7a8fa6">${f.sort_order}</td>
            <td>${status}</td>
            <td>
                <div style="display:flex;gap:6px">
                    <button class="btn-sm" style="background:#e3f2fd;color:#1565c0"
                        onclick='openEdit(${f.id}, ${JSON.stringify(f)})'>
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn-sm" style="background:${togBg};color:${togCol}"
                        onclick="toggleItem(${f.id})">
                        <i class="bi bi-${f.is_active ? 'eye-slash' : 'eye'}"></i>
                    </button>
                    <button class="btn-sm" style="background:#fce4ec;color:#c62828"
                        onclick="deleteItem(${f.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function renderInfo(from, to, total) {
    document.getElementById('dtInfo').textContent = total > 0 ? `Showing ${from} – ${to} of ${total} FAQs` : 'No results';
}

function renderPages(cur, last) {
    var el = document.getElementById('dtPages');
    if (last <= 1) { el.innerHTML = ''; return; }
    var html = `<button class="pg-btn" onclick="go(${cur-1})" ${cur<=1?'disabled':''}>‹</button>`;
    pages(cur, last).forEach(p => {
        if (p==='…') html += `<span style="padding:5px 4px;color:#7a8fa6">…</span>`;
        else html += `<button class="pg-btn ${p===cur?'active':''}" onclick="go(${p})">${p}</button>`;
    });
    html += `<button class="pg-btn" onclick="go(${cur+1})" ${cur>=last?'disabled':''}>›</button>`;
    el.innerHTML = html;
}

function pages(cur, last) {
    if (last<=7) return Array.from({length:last},(_,i)=>i+1);
    var r=[1];
    if(cur>3) r.push('…');
    for(var i=Math.max(2,cur-1);i<=Math.min(last-1,cur+1);i++) r.push(i);
    if(cur<last-2) r.push('…');
    r.push(last);
    return r;
}

function go(p) { if(p<1) return; state.page=p; load(); }

function openModal() {
    editId = null; clearForm();
    document.getElementById('modalTitle').textContent = 'Add FAQ';
    document.getElementById('modalBg').classList.add('open');
    setTimeout(() => document.getElementById('fQuestion').focus(), 100);
}

function openEdit(id, f) {
    editId = id;
    document.getElementById('modalTitle').textContent = 'Edit FAQ';
    document.getElementById('fQuestion').value  = f.question || '';
    document.getElementById('fAnswer').value    = f.answer   || '';
    document.getElementById('fCategory').value  = f.category || 'general';
    document.getElementById('fSort').value      = f.sort_order || 0;
    document.getElementById('fActive').checked  = !!f.is_active;
    hideErr();
    document.getElementById('modalBg').classList.add('open');
}

function closeModal() { document.getElementById('modalBg').classList.remove('open'); hideErr(); }

function clearForm() {
    document.getElementById('fQuestion').value = '';
    document.getElementById('fAnswer').value   = '';
    document.getElementById('fCategory').value = 'general';
    document.getElementById('fSort').value     = '0';
    document.getElementById('fActive').checked = true;
    hideErr();
}

async function saveItem() {
    var question = document.getElementById('fQuestion').value.trim();
    var answer   = document.getElementById('fAnswer').value.trim();
    if (!question) { showErr('Question is required'); return; }
    if (!answer)   { showErr('Answer is required'); return; }

    var btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';

    var body = {
        question:   question,
        answer:     answer,
        category:   document.getElementById('fCategory').value,
        sort_order: parseInt(document.getElementById('fSort').value) || 0,
        is_active:  document.getElementById('fActive').checked ? 1 : 0,
    };

    var url = editId ? `${BASE}/${editId}` : BASE;
    var method = editId ? 'PUT' : 'POST';

    try {
        var res  = await fetch(url, {
            method,
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
            body: JSON.stringify(body),
        });
        var data = await res.json();
        if (data.success) { closeModal(); load(); }
        else showErr(data.message || 'Error');
    } catch(e) {
        showErr('Server error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Save FAQ';
    }
}

async function toggleItem(id) {
    var res = await fetch(`${BASE}/${id}/toggle`, {
        method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json','Content-Type':'application/json'}
    });
    if ((await res.json()).success) load();
}

async function deleteItem(id) {
    if (!confirm('Delete this FAQ?')) return;
    var res = await fetch(`${BASE}/${id}`, {
        method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json','Content-Type':'application/json'}
    });
    if ((await res.json()).success) load();
}

function showErr(msg) { var el=document.getElementById('errMsg'); el.textContent=msg; el.style.display='block'; }
function hideErr()    { document.getElementById('errMsg').style.display='none'; }
function esc(s)       { return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

document.getElementById('srch').addEventListener('input', function() {
    clearTimeout(timer); timer = setTimeout(()=>{ state.search=this.value; state.page=1; load(); }, 400);
});
document.getElementById('catFilter').addEventListener('change', function() { state.category=this.value; state.page=1; load(); });
document.getElementById('statusFilter').addEventListener('change', function() { state.status=this.value; state.page=1; load(); });
document.getElementById('perPage').addEventListener('change', function() { state.perPage=parseInt(this.value); state.page=1; load(); });
document.getElementById('modalBg').addEventListener('click', function(e) { if(e.target===this) closeModal(); });
document.addEventListener('keydown', e => { if(e.key==='Escape') closeModal(); });

load();
</script>
@endpush
@endsection