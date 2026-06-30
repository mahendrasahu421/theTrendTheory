@extends('admin.layouts.app')
@section('title', 'Announcement Bar')
@section('content')

<style>
.page-hdr { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; }
.page-title { font-family:'Cinzel',serif; font-size:16px; font-weight:700; color:#00285a; letter-spacing:1px; }
.card { background:white; border:1px solid #eef2f6; border-radius:14px; overflow:hidden; margin-bottom:16px; }
.card-hdr { display:flex; justify-content:space-between; align-items:center; padding:14px 18px; border-bottom:1px solid #eef2f6; }
.card-title { font-family:'Cinzel',serif; font-size:12px; font-weight:700; color:#00285a; letter-spacing:1px; }
.btn-primary { background:#00285a; color:white; border:none; padding:8px 18px; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px; font-family:inherit; }
.btn-primary:hover { background:#1e3f75; }
.ann-list { padding:16px; display:flex; flex-direction:column; gap:10px; }
.ann-item { border:1.5px solid #e8edf5; border-radius:12px; overflow:hidden; }
.ann-preview { padding:12px 18px; font-size:13px; font-weight:600; text-align:center; display:flex; justify-content:center; align-items:center; gap:10px; flex-wrap:wrap; }
.ann-preview img { width:34px; height:34px; object-fit:cover; border-radius:8px; border:1px solid rgba(255,255,255,.45); }
.ann-footer { display:flex; justify-content:space-between; align-items:center; padding:10px 16px; background:#f8fafc; border-top:1px solid #eef2f6; flex-wrap:wrap; gap:8px; }
.bdg { display:inline-flex; align-items:center; padding:2px 10px; border-radius:20px; font-size:11px; font-weight:700; }
.bdg-g { background:#e8f5e9; color:#2e7d32; }
.bdg-r { background:#fce4ec; color:#c62828; }
.btn-sm { padding:5px 12px; border-radius:6px; font-size:11px; font-weight:700; border:none; cursor:pointer; font-family:inherit; display:inline-flex; align-items:center; gap:4px; }
.modal-bg { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:200; align-items:center; justify-content:center; padding:16px; }
.modal-bg.open { display:flex; }
.modal { background:white; border-radius:16px; padding:26px; width:100%; max-width:480px; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,.15); }
.modal-title { font-family:'Cinzel',serif; font-size:14px; font-weight:700; color:#00285a; margin-bottom:20px; }
.fgrp { display:flex; flex-direction:column; gap:5px; margin-bottom:14px; }
.fgrp label { font-size:11px; font-weight:700; color:#7a8fa6; text-transform:uppercase; letter-spacing:.5px; }
.fgrp input, .fgrp textarea { padding:10px 14px; border:1.5px solid #e8edf5; border-radius:10px; font-size:14px; font-family:inherit; outline:none; transition:.15s; width:100%; background:white; box-sizing:border-box; }
.fgrp input:focus { border-color:#00285a; }
.fgrp .hint { font-size:11px; color:#7a8fa6; }
.image-preview { display:none; width:100%; max-height:160px; object-fit:cover; border-radius:10px; border:1.5px solid #e8edf5; margin-top:8px; }
.form-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.modal-actions { display:flex; gap:10px; margin-top:20px; }
.btn-cancel-sm { background:white; color:#555; border:1px solid #e8edf5; padding:9px 18px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; font-family:inherit; }
.err-msg { color:#c62828; font-size:12px; margin-bottom:10px; display:none; padding:8px 12px; background:#fce4ec; border-radius:8px; }
.live-preview { border-radius:8px; padding:10px 16px; text-align:center; font-size:13px; font-weight:600; margin-bottom:14px; transition:.3s; }
</style>

<div class="page-hdr">
    <div class="page-title">Announcement Bar</div>
    <button class="btn-primary" onclick="openModal()">
        <i class="bi bi-plus-lg"></i> Add Announcement
    </button>
</div>

<div class="card">
    <div class="card-hdr">
        <div class="card-title">Active Announcements</div>
        <span style="font-size:12px;color:#7a8fa6">First active one shows on frontend</span>
    </div>

    <div class="ann-list">
        @forelse($announcements as $ann)
        <div class="ann-item" id="annItem{{ $ann->id }}">
            <div class="ann-preview" style="background:{{ $ann->bg_color }};color:{{ $ann->text_color }}">
                @if($ann->image_url)
                    <img src="{{ $ann->image_url }}" alt="">
                @endif
                {{ $ann->text }}
                @if($ann->link_text) · <u>{{ $ann->link_text }}</u> @endif
            </div>
            <div class="ann-footer">
                <div style="display:flex;align-items:center;gap:10px">
                    <span class="bdg {{ $ann->is_active ? 'bdg-g' : 'bdg-r' }}">
                        {{ $ann->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <span style="font-size:12px;color:#7a8fa6">Sort: {{ $ann->sort_order }}</span>
                </div>
                <div style="display:flex;gap:6px">
                    <button class="btn-sm" style="background:#e3f2fd;color:#1565c0"
                        onclick='openEdit({{ $ann->id }}, {{ json_encode($ann) }})'>
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                    <button class="btn-sm"
                        style="background:{{ $ann->is_active ? '#fce4ec' : '#e8f5e9' }};color:{{ $ann->is_active ? '#c62828' : '#2e7d32' }}"
                        onclick="toggleItem({{ $ann->id }})">
                        <i class="bi bi-{{ $ann->is_active ? 'eye-slash' : 'eye' }}"></i>
                    </button>
                    <button class="btn-sm" style="background:#fce4ec;color:#c62828"
                        onclick="deleteItem({{ $ann->id }})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:40px;color:#7a8fa6">
            <i class="bi bi-megaphone" style="font-size:32px;display:block;margin-bottom:10px"></i>
            No announcements yet — add one!
        </div>
        @endforelse
    </div>
</div>

{{-- MODAL --}}
<div class="modal-bg" id="modalBg">
    <div class="modal">
        <div class="modal-title" id="modalTitle">Add Announcement</div>
        <div id="errMsg" class="err-msg"></div>

        {{-- Live Preview --}}
        <div class="live-preview" id="livePreview" style="background:#00285a;color:#ffffff">
            Preview text here
        </div>

        <div class="fgrp">
            <label>Announcement Text *</label>
            <input type="text" id="fText" maxlength="200"
                   placeholder="🚚 Free shipping on orders above ₹999!"
                   oninput="updatePreview()">
        </div>

        <div class="fgrp">
            <label>Announcement Image <span style="font-weight:400;color:#7a8fa6">(optional)</span></label>
            <input type="file" id="fImage" accept="image/*" onchange="previewImage(this)">
            <img id="fImagePreview" class="image-preview" alt="">
            <div class="hint">Images upload to Cloudinary when you save.</div>
        </div>

        <div class="form-row">
            <div class="fgrp">
                <label>Background Color</label>
                <div style="display:flex;gap:8px;align-items:center">
                    <input type="color" id="fBgPicker" value="#00285a"
                           style="width:44px;height:40px;padding:2px;border-radius:6px;border:1.5px solid #e8edf5;cursor:pointer;flex-shrink:0"
                           oninput="document.getElementById('fBg').value=this.value;updatePreview()">
                    <input type="text" id="fBg" value="#00285a"
                           oninput="if(this.value.length>=4){document.getElementById('fBgPicker').value=this.value;updatePreview()}">
                </div>
            </div>
            <div class="fgrp">
                <label>Text Color</label>
                <div style="display:flex;gap:8px;align-items:center">
                    <input type="color" id="fTxtPicker" value="#ffffff"
                           style="width:44px;height:40px;padding:2px;border-radius:6px;border:1.5px solid #e8edf5;cursor:pointer;flex-shrink:0"
                           oninput="document.getElementById('fTxt').value=this.value;updatePreview()">
                    <input type="text" id="fTxt" value="#ffffff"
                           oninput="if(this.value.length>=4){document.getElementById('fTxtPicker').value=this.value;updatePreview()}">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="fgrp">
                <label>Link URL <span style="font-weight:400;color:#7a8fa6">(optional)</span></label>
                <input type="text" id="fLink" placeholder="/shop/sale">
            </div>
            <div class="fgrp">
                <label>Link Text</label>
                <input type="text" id="fLinkText" placeholder="Shop Now">
            </div>
        </div>

        <div class="form-row">
            <div class="fgrp">
                <label>Sort Order</label>
                <input type="number" id="fSort" value="0" min="0">
            </div>
            <div class="fgrp" style="justify-content:flex-end;padding-top:20px">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                    <input type="checkbox" id="fActive" checked style="width:17px;height:17px;accent-color:#00285a">
                    <span style="font-size:13px;font-weight:600;color:#333">Active</span>
                </label>
            </div>
        </div>

        <div class="modal-actions">
            <button class="btn-primary" id="saveBtn" onclick="saveItem()">
                <i class="bi bi-check-lg"></i> Save
            </button>
            <button class="btn-cancel-sm" onclick="closeModal()">Cancel</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
var CSRF   = document.querySelector('meta[name="csrf-token"]').content;
var BASE   = '{{ url("admin/announcements") }}';
var editId = null;

function updatePreview() {
    var prev = document.getElementById('livePreview');
    prev.textContent = document.getElementById('fText').value || 'Preview text here';
    prev.style.background = document.getElementById('fBg').value || '#00285a';
    prev.style.color      = document.getElementById('fTxt').value || '#ffffff';
}

function openModal() {
    editId = null;
    clearForm();
    document.getElementById('modalTitle').textContent = 'Add Announcement';
    document.getElementById('modalBg').classList.add('open');
    setTimeout(() => document.getElementById('fText').focus(), 100);
}

function openEdit(id, item) {
    editId = id;
    document.getElementById('modalTitle').textContent = 'Edit Announcement';
    document.getElementById('fText').value     = item.text || '';
    document.getElementById('fBg').value       = item.bg_color || '#00285a';
    document.getElementById('fBgPicker').value = item.bg_color || '#00285a';
    document.getElementById('fTxt').value      = item.text_color || '#ffffff';
    document.getElementById('fTxtPicker').value= item.text_color || '#ffffff';
    document.getElementById('fLink').value     = item.link || '';
    document.getElementById('fLinkText').value = item.link_text || '';
    document.getElementById('fSort').value     = item.sort_order || 0;
    document.getElementById('fActive').checked = !!item.is_active;
    document.getElementById('fImage').value    = '';
    setImagePreview(item.image_url || '');
    updatePreview();
    hideErr();
    document.getElementById('modalBg').classList.add('open');
}

function closeModal() {
    document.getElementById('modalBg').classList.remove('open');
    hideErr();
}

function clearForm() {
    document.getElementById('fText').value      = '';
    document.getElementById('fBg').value        = '#00285a';
    document.getElementById('fBgPicker').value  = '#00285a';
    document.getElementById('fTxt').value       = '#ffffff';
    document.getElementById('fTxtPicker').value = '#ffffff';
    document.getElementById('fLink').value      = '';
    document.getElementById('fLinkText').value  = '';
    document.getElementById('fImage').value     = '';
    document.getElementById('fSort').value      = '0';
    document.getElementById('fActive').checked  = true;
    setImagePreview('');
    updatePreview();
    hideErr();
}

function previewImage(input) {
    var file = input.files && input.files[0];
    if (!file) { setImagePreview(''); return; }
    setImagePreview(URL.createObjectURL(file));
}

function setImagePreview(src) {
    var preview = document.getElementById('fImagePreview');
    preview.src = src || '';
    preview.style.display = src ? 'block' : 'none';
}

async function saveItem() {
    var text = document.getElementById('fText').value.trim();
    if (!text) { showErr('Text is required'); return; }

    var btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';

    var body = new FormData();
    body.append('text', text);
    body.append('bg_color', document.getElementById('fBg').value || '#00285a');
    body.append('text_color', document.getElementById('fTxt').value || '#ffffff');
    body.append('link', document.getElementById('fLink').value);
    body.append('link_text', document.getElementById('fLinkText').value);
    body.append('sort_order', parseInt(document.getElementById('fSort').value) || 0);
    body.append('is_active', document.getElementById('fActive').checked ? 1 : 0);

    var file = document.getElementById('fImage').files[0];
    if (file) body.append('image', file);
    if (editId) body.append('_method', 'PUT');

    var url    = editId ? `${BASE}/${editId}` : BASE;
    var method = 'POST';

    try {
        var res  = await fetch(url, {
            method,
            headers: {'X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
            body,
        });
        var data = await res.json();
        if (data.success) { closeModal(); window.location.reload(); }
        else showErr(data.message || 'Error');
    } catch(e) {
        showErr('Server error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Save';
    }
}

async function toggleItem(id) {
    var res = await fetch(`${BASE}/${id}/toggle`, {
        method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json','Content-Type':'application/json'}
    });
    if ((await res.json()).success) window.location.reload();
}

async function deleteItem(id) {
    if (!confirm('Delete this announcement?')) return;
    var res = await fetch(`${BASE}/${id}`, {
        method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json','Content-Type':'application/json'}
    });
    if ((await res.json()).success) window.location.reload();
}

function showErr(msg) { var el=document.getElementById('errMsg'); el.textContent=msg; el.style.display='block'; }
function hideErr()    { document.getElementById('errMsg').style.display='none'; }

document.getElementById('modalBg').addEventListener('click', function(e) { if(e.target===this) closeModal(); });
document.addEventListener('keydown', e => { if(e.key==='Escape') closeModal(); });
</script>
@endpush
@endsection
