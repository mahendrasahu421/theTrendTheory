{{-- resources/views/admin/variants/sizes.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Size Master')
@section('content')

<style>
    .page-hdr{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px}
    .page-hdr-title{font-family:'Cinzel',serif;font-size:16px;font-weight:700;color:#00285a;letter-spacing:1px}
    .card{background:white;border:1px solid #eef2f6;border-radius:14px;overflow:hidden}
    .card-h{display:flex;justify-content:space-between;align-items:center;padding:14px 18px;border-bottom:1px solid #eef2f6;flex-wrap:wrap;gap:10px}
    .card-t{font-family:'Cinzel',serif;font-size:12px;font-weight:700;color:#00285a;letter-spacing:1px}
    table.dt{width:100%;border-collapse:collapse}
    table.dt thead th{padding:10px 14px;font-size:10px;font-weight:700;color:#7a8fa6;text-transform:uppercase;letter-spacing:.8px;background:#f8fafc;border-bottom:1px solid #eef2f6;white-space:nowrap}
    table.dt tbody td{padding:10px 14px;font-size:13px;border-bottom:1px solid rgba(0,0,0,.04);vertical-align:middle}
    table.dt tbody tr:hover td{background:#fafbff}
    table.dt tbody tr:last-child td{border-bottom:none}
    .filter-bar{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
    .search-input{padding:8px 14px;border:1.5px solid #e8edf5;border-radius:8px;font-size:13px;outline:none;width:180px;font-family:inherit}
    .search-input:focus{border-color:#00285a}
    .filter-select{padding:8px 12px;border:1.5px solid #e8edf5;border-radius:8px;font-size:13px;outline:none;background:white;cursor:pointer;font-family:inherit}
    .bdg{display:inline-flex;align-items:center;padding:2px 9px;border-radius:20px;font-size:11px;font-weight:700}
    .bdg-g{background:#e8f5e9;color:#2e7d32}.bdg-r{background:#fce4ec;color:#c62828}.bdg-b{background:#e3f2fd;color:#1565c0}
    .dt-loading{display:none;position:absolute;inset:0;background:rgba(255,255,255,.7);align-items:center;justify-content:center;z-index:10;border-radius:14px}
    .dt-loading.show{display:flex}
    .spinner{width:28px;height:28px;border:3px solid #eef2f6;border-top-color:#00285a;border-radius:50%;animation:spin .7s linear infinite}
    @keyframes spin{to{transform:rotate(360deg)}}
    .dt-bottom{display:flex;justify-content:space-between;align-items:center;border-top:1px solid #eef2f6;flex-wrap:wrap}
    .btn-pg{padding:5px 12px;border-radius:6px;font-size:12px;font-weight:600;border:1px solid #eef2f6;background:white;color:#555;margin:0 2px;cursor:pointer}
    .btn-pg.active{background:#00285a;color:white;border-color:#00285a}
    .btn-pg:disabled{opacity:.4;cursor:not-allowed}
    /* Modal */
    .modal-bg{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1000;align-items:center;justify-content:center}
    .modal-bg.show{display:flex}
    .modal{background:white;border-radius:16px;padding:28px;width:420px;max-width:95vw}
    .modal-title{font-family:'Cinzel',serif;font-size:14px;font-weight:700;color:#00285a;margin-bottom:20px}
    .field{display:flex;flex-direction:column;gap:5px;margin-bottom:14px}
    .field label{font-size:11px;font-weight:700;color:#00285a;text-transform:uppercase;letter-spacing:.5px}
    .field input,.field select{padding:9px 13px;border:1.5px solid #e8edf5;border-radius:8px;font-size:13px;outline:none;font-family:inherit}
    .field input:focus,.field select:focus{border-color:#00285a}
    .modal-footer{display:flex;gap:8px;justify-content:flex-end;margin-top:20px;padding-top:16px;border-top:1px solid #eef2f6}
    .cat-pill{display:inline-block;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:700}
    .cat-clothing{background:#e8f5e9;color:#2e7d32}
    .cat-bottoms{background:#e3f2fd;color:#1565c0}
    .cat-footwear{background:#fff3e0;color:#e65100}
    .cat-other{background:#f1f5f9;color:#64748b}
</style>

<div class="page-hdr">
    <div>
        <div class="page-hdr-title">Size Master</div>
        <div style="font-size:12px;color:#7a8fa6;margin-top:2px">Variants › Sizes</div>
    </div>
    <button onclick="openModal()" class="btn-admin btn-navy"><i class="bi bi-plus-lg"></i> Add Size</button>
</div>

<div class="card" style="position:relative">
    <div class="dt-loading" id="dtLoading"><div class="spinner"></div></div>
    <div class="card-h">
        <div class="card-t">All Sizes</div>
        <div class="filter-bar">
            <input type="text" id="searchInput" class="search-input" placeholder="Search sizes...">
            <select id="categoryFilter" class="filter-select">
                <option value="">All Categories</option>
                <option value="clothing">Clothing</option>
                <option value="bottoms">Bottoms</option>
                <option value="footwear">Footwear</option>
                <option value="other">Other</option>
            </select>
            <select id="statusFilter" class="filter-select">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            <select id="perPage" class="filter-select">
                <option value="25">25 / page</option>
                <option value="50">50 / page</option>
            </select>
        </div>
    </div>
    <div style="overflow-x:auto">
        <table class="dt">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Display Name</th>
                    <th>Category</th>
                    <th>Sort</th>
                    <th>Used In</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>
    </div>
    <div class="dt-bottom">
        <div id="tableInfo" style="font-size:12px;color:#7a8fa6;padding:10px 18px"></div>
        <div id="tablePagination" style="padding:10px 18px;display:flex;gap:4px;flex-wrap:wrap"></div>
    </div>
</div>

{{-- Add/Edit Modal --}}
<div class="modal-bg" id="modalBg">
    <div class="modal">
        <div class="modal-title" id="modalTitle">Add Size</div>
        <input type="hidden" id="editId">
        <div class="field">
            <label>Size Name <span style="color:#e53e3e">*</span></label>
            <input type="text" id="fName" placeholder="e.g. S, M, L, XL, 28, 30">
        </div>
        <div class="field">
            <label>Display Name</label>
            <input type="text" id="fDisplayName" placeholder="e.g. Small, Medium, Large">
        </div>
        <div class="field">
            <label>Category <span style="color:#e53e3e">*</span></label>
            <select id="fCategory">
                <option value="clothing">Clothing (S/M/L/XL)</option>
                <option value="bottoms">Bottoms (28/30/32)</option>
                <option value="footwear">Footwear</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div class="field">
            <label>Sort Order</label>
            <input type="number" id="fSort" placeholder="0" min="0" value="0">
        </div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px">
            <label style="font-size:11px;font-weight:700;color:#00285a;text-transform:uppercase;letter-spacing:.5px">Active</label>
            <input type="checkbox" id="fActive" checked style="width:16px;height:16px;cursor:pointer">
        </div>
        <div id="modalError" style="color:#c62828;font-size:12px;margin-bottom:10px;display:none"></div>
        <div class="modal-footer">
            <button onclick="closeModal()" class="btn-admin" style="background:#f1f5f9;color:#334155;border:none">Cancel</button>
            <button onclick="saveSize()" class="btn-admin btn-navy" id="saveBtn">Save Size</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
var AJAX_URL   = '{{ route('admin.sizes.ajax') }}';
var STORE_URL  = '{{ route('admin.sizes.store') }}';
var CSRF       = document.querySelector('meta[name="csrf-token"]').content;
var state      = {page:1,perPage:25,search:'',category:'',status:''};
var debounceTimer;

function fetchData() {
    document.getElementById('dtLoading').classList.add('show');
    var params = new URLSearchParams({page:state.page,per_page:state.perPage,search:state.search,category:state.category,status:state.status});
    fetch(AJAX_URL+'?'+params,{headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'}})
        .then(r=>r.json()).then(res=>{
            renderTable(res.data);
            renderInfo(res.from,res.to,res.total);
            renderPagination(res.current_page,res.last_page);
            document.getElementById('dtLoading').classList.remove('show');
        }).catch(err=>{console.error(err);document.getElementById('dtLoading').classList.remove('show');});
}

function renderTable(rows) {
    var tbody = document.getElementById('tableBody');
    if (!rows||!rows.length){tbody.innerHTML='<tr><td colspan="8" style="text-align:center;padding:40px;color:#7a8fa6">No sizes found</td></tr>';return;}
    var catClass = {clothing:'cat-clothing',bottoms:'cat-bottoms',footwear:'cat-footwear',other:'cat-other'};
    tbody.innerHTML = rows.map(function(s,i){
        var offset=(state.page-1)*state.perPage;
        return '<tr>'+
            '<td style="color:#7a8fa6;font-size:12px">'+(offset+i+1)+'</td>'+
            '<td><strong style="color:#00285a;font-size:15px">'+esc(s.name)+'</strong></td>'+
            '<td style="color:#64748b">'+esc(s.display_name||'—')+'</td>'+
            '<td><span class="cat-pill '+(catClass[s.category]||'cat-other')+'">'+s.category+'</span></td>'+
            '<td style="color:#94a3b8;font-size:12px">'+s.sort_order+'</td>'+
            '<td><span class="bdg bdg-b">'+s.variants_count+' variants</span></td>'+
            '<td>'+(s.is_active?'<span class="bdg bdg-g">Active</span>':'<span class="bdg bdg-r">Inactive</span>')+'</td>'+
            '<td><div style="display:flex;gap:6px">'+
                '<button onclick="editSize('+JSON.stringify(s)+')" style="background:#00285a;color:white;padding:5px 11px;border-radius:20px;font-size:11px;font-weight:700;border:none;cursor:pointer"><i class="bi bi-pencil"></i></button>'+
                '<button onclick="toggleSize('+s.id+')" style="background:'+(s.is_active?'#fee2e2':'#e8f5e9')+';color:'+(s.is_active?'#991b1b':'#2e7d32')+';padding:5px 11px;border-radius:20px;font-size:11px;font-weight:700;border:none;cursor:pointer">'+
                (s.is_active?'<i class="bi bi-x-lg"></i>':'<i class="bi bi-check-lg"></i>')+'</button>'+
                '<button onclick="deleteSize('+s.id+','+s.variants_count+')" style="background:#f1f5f9;color:#64748b;padding:5px 11px;border-radius:20px;font-size:11px;font-weight:700;border:none;cursor:pointer"><i class="bi bi-trash"></i></button>'+
            '</div></td>'+
        '</tr>';
    }).join('');
}

function renderInfo(from,to,total){document.getElementById('tableInfo').textContent=total>0?'Showing '+from+' to '+to+' of '+total+' sizes':'0 sizes found';}
function renderPagination(current,last){
    var el=document.getElementById('tablePagination');
    if(last<=1){el.innerHTML='';return;}
    var pages=[];
    if(last<=7){for(var i=1;i<=last;i++)pages.push(i);}
    else{pages.push(1);if(current>3)pages.push('...');for(var i=Math.max(2,current-1);i<=Math.min(last-1,current+1);i++)pages.push(i);if(current<last-2)pages.push('...');pages.push(last);}
    var html='<button class="btn-pg" onclick="goPage('+(current-1)+')" '+(current<=1?'disabled':'')+'>‹</button>';
    pages.forEach(function(p){html+=p==='...'?'<span style="padding:5px 8px;color:#7a8fa6">…</span>':'<button class="btn-pg'+(p===current?' active':'')+'" onclick="goPage('+p+')">'+p+'</button>';});
    html+='<button class="btn-pg" onclick="goPage('+(current+1)+')" '+(current>=last?'disabled':'')+'>›</button>';
    el.innerHTML=html;
}
function goPage(p){if(p<1)return;state.page=p;fetchData();}

function openModal(){ document.getElementById('modalBg').classList.add('show'); document.getElementById('modalTitle').textContent='Add Size'; document.getElementById('editId').value=''; document.getElementById('fName').value=''; document.getElementById('fDisplayName').value=''; document.getElementById('fCategory').value='clothing'; document.getElementById('fSort').value='0'; document.getElementById('fActive').checked=true; hideError(); }
function closeModal(){ document.getElementById('modalBg').classList.remove('show'); }
function hideError(){ document.getElementById('modalError').style.display='none'; }
function showError(msg){ var el=document.getElementById('modalError'); el.textContent=msg; el.style.display='block'; }

function editSize(s){ document.getElementById('modalBg').classList.add('show'); document.getElementById('modalTitle').textContent='Edit Size'; document.getElementById('editId').value=s.id; document.getElementById('fName').value=s.name; document.getElementById('fDisplayName').value=s.display_name||''; document.getElementById('fCategory').value=s.category; document.getElementById('fSort').value=s.sort_order; document.getElementById('fActive').checked=s.is_active; hideError(); }

function saveSize(){
    var id=document.getElementById('editId').value;
    var url=id?'/admin/sizes/'+id:STORE_URL;
    var method=id?'PUT':'POST';
    var body={name:document.getElementById('fName').value,display_name:document.getElementById('fDisplayName').value,category:document.getElementById('fCategory').value,sort_order:parseInt(document.getElementById('fSort').value)||0,is_active:document.getElementById('fActive').checked?1:0};
    if(!body.name.trim()){showError('Size name is required.');return;}
    document.getElementById('saveBtn').textContent='Saving...';
    fetch(url,{method:method,headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json','Content-Type':'application/json'},body:JSON.stringify(body)})
        .then(r=>r.json()).then(res=>{
            document.getElementById('saveBtn').textContent='Save Size';
            if(res.error){showError(res.error);return;}
            closeModal(); fetchData();
        }).catch(()=>{document.getElementById('saveBtn').textContent='Save Size';showError('Something went wrong.');});
}

function toggleSize(id){fetch('/admin/sizes/'+id+'/toggle',{method:'POST',headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'}}).then(r=>r.json()).then(()=>fetchData());}
function deleteSize(id,count){
    if(count>0){alert('Cannot delete — this size is used in '+count+' variants.');return;}
    if(!confirm('Delete this size?'))return;
    fetch('/admin/sizes/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'}}).then(()=>fetchData());
}

function esc(str){return str?String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'):'';}

document.getElementById('searchInput').addEventListener('input',function(){clearTimeout(debounceTimer);debounceTimer=setTimeout(function(){state.search=document.getElementById('searchInput').value;state.page=1;fetchData();},400);});
document.getElementById('categoryFilter').addEventListener('change',function(){state.category=this.value;state.page=1;fetchData();});
document.getElementById('statusFilter').addEventListener('change',function(){state.status=this.value;state.page=1;fetchData();});
document.getElementById('perPage').addEventListener('change',function(){state.perPage=parseInt(this.value);state.page=1;fetchData();});
document.getElementById('modalBg').addEventListener('click',function(e){if(e.target===this)closeModal();});

fetchData();
</script>
@endpush
@endsection