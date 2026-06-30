@extends('admin.layouts.app')
@section('title', isset($page) ? 'Edit Page' : 'Add Page')
@section('content')
@php $isEdit = isset($page); @endphp

<style>
.form-wrap { max-width:860px; }
.card { background:white; border:1px solid #eef2f6; border-radius:14px; overflow:hidden; margin-bottom:16px; }
.card-hdr { display:flex; justify-content:space-between; align-items:center; padding:16px 20px; border-bottom:1px solid #eef2f6; }
.card-title { font-family:'Cinzel',serif; font-size:13px; font-weight:700; color:#00285a; letter-spacing:1px; }
.sec-label { font-size:10px; font-weight:700; color:#7a8fa6; text-transform:uppercase; letter-spacing:1px; padding:14px 20px 0; display:block; }
.form-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; padding:14px 20px; }
.fgrp { display:flex; flex-direction:column; gap:5px; }
.fgrp.span2 { grid-column:1/-1; }
.fgrp label { font-size:11px; font-weight:700; color:#7a8fa6; text-transform:uppercase; letter-spacing:.5px; }
.fgrp input, .fgrp textarea { padding:10px 14px; border:1.5px solid #e8edf5; border-radius:10px; font-size:14px; font-family:inherit; outline:none; transition:.15s; width:100%; background:white; box-sizing:border-box; }
.fgrp input:focus, .fgrp textarea:focus { border-color:#00285a; }
.fgrp .hint { font-size:11px; color:#7a8fa6; }
.ferr { font-size:11px; color:#ff3f6c; }
.ftog { display:flex; align-items:center; gap:8px; cursor:pointer; }
.form-actions { display:flex; gap:10px; padding:16px 20px; border-top:1px solid #eef2f6; background:#fafbff; }
.btn-save { background:#00285a; color:white; border:none; padding:10px 26px; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px; font-family:inherit; }
.btn-save:hover { background:#1e3f75; }
.btn-cancel { background:white; color:#555; border:1px solid #e8edf5; padding:10px 20px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; }
</style>

<div class="form-wrap">
<div class="card">
    <div class="card-hdr">
        <div class="card-title">{{ $isEdit ? 'EDIT PAGE' : 'ADD PAGE' }}</div>
        <a href="{{ route('admin.pages.index') }}" class="btn-cancel" style="font-size:11px;padding:6px 12px">
            <i class="bi bi-arrow-left"></i>&nbsp;Back
        </a>
    </div>

    @if($errors->any())
    <div style="background:#fce4ec;color:#c62828;padding:12px 16px;border-radius:10px;margin:14px 20px;font-size:13px">
        @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
    </div>
    @endif

    <form method="POST" action="{{ $isEdit ? route('admin.pages.update', $page) : route('admin.pages.store') }}">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <span class="sec-label">Page Content</span>
        <div class="form-grid">
            <div class="fgrp span2">
                <label>Page Title *</label>
                <input type="text" name="title"
                       value="{{ old('title', $isEdit ? $page->title : '') }}"
                       required placeholder="e.g. About Us">
                @error('title')<div class="ferr">{{ $message }}</div>@enderror
            </div>

            <div class="fgrp span2">
                <label>Content *</label>
                <textarea name="content" rows="15"
                          placeholder="Write page content here (HTML supported)...">{{ old('content', $isEdit ? $page->content : '') }}</textarea>
                @error('content')<div class="ferr">{{ $message }}</div>@enderror
            </div>
        </div>

        <span class="sec-label">SEO</span>
        <div class="form-grid">
            <div class="fgrp">
                <label>Meta Title</label>
                <input type="text" name="meta_title" maxlength="70"
                       value="{{ old('meta_title', $isEdit ? $page->meta_title : '') }}"
                       placeholder="Page title for search engines">
            </div>
            <div class="fgrp">
                <label>Meta Description</label>
                <input type="text" name="meta_description" maxlength="170"
                       value="{{ old('meta_description', $isEdit ? $page->meta_description : '') }}"
                       placeholder="Brief description for search engines">
            </div>
        </div>

        <div style="padding:14px 20px">
            <label class="ftog">
                <input type="checkbox" name="is_active" value="1"
                    {{ old('is_active', $isEdit ? $page->is_active : true) ? 'checked' : '' }}
                    style="width:17px;height:17px;accent-color:#00285a">
                <span style="font-size:13px;font-weight:600;color:#333">Published (visible on site)</span>
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">
                <i class="bi bi-check-lg"></i> {{ $isEdit ? 'Update Page' : 'Save Page' }}
            </button>
            <a href="{{ route('admin.pages.index') }}" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>
</div>
@endsection