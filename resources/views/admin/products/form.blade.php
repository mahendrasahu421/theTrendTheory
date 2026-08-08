{{-- resources/views/admin/products/form.blade.php --}}
@extends('admin.layouts.app')
@section('title', isset($product) ? 'Edit: ' . $product->name : 'Add Product')
@section('content')

@php
    $isEdit = isset($product);
    $variants = $isEdit && isset($product->variants) ? $product->variants : collect();
    $images = $isEdit && isset($product->images) ? $product->images : collect();
    $sizesArray = isset($sizes) ? $sizes : collect();
    $colorsArray = isset($colors) ? $colors : collect();
    $tagsArray = isset($tags) ? $tags : collect();
    $selectedTagIds = collect(old('tag_ids', $isEdit ? $product->tags->pluck('id')->all() : []))
        ->map(fn($id) => (int) $id)
        ->all();

    function buildCategoryOptions($categories, $selectedId = null, $prefix = '')
    {
        $html = '';
        foreach ($categories as $category) {
            $selected = $selectedId == $category->id ? 'selected' : '';
            $html .= '<option value="' . $category->id . '" ' . $selected . '>' . $prefix . $category->name . '</option>';
            if ($category->children && $category->children->count() > 0) {
                $html .= buildCategoryOptions($category->children, $selectedId, $prefix . '— ');
            }
        }
        return $html;
    }

    $parentCategories = $categories->filter(function ($cat) {
        return is_null($cat->parent_id);
    });

    $selectedCategoryId = old('category_id', $isEdit ? $product->category_id : '');
@endphp

<style>
/* ─── Wizard Shell ─────────────────────────────────── */
.wiz-wrap { max-width: 860px; margin: 0 auto; }

/* ─── Wizard Nav ───────────────────────────────────── */
.wiz-nav {
    display: flex;
    align-items: center;
    background: #fff;
    border: 1px solid #eef2f6;
    border-radius: 14px;
    padding: 0 20px;
    margin-bottom: 20px;
    overflow: hidden;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 12px rgba(0,40,90,.07);
}

.wiz-step {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 16px 18px;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    transition: all .2s;
    flex: 1;
    justify-content: center;
    user-select: none;
}

.wiz-step:hover { background: #f8fafc; }

.wiz-step.active { border-bottom-color: #00285a; }

.wiz-num {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #eef2f6;
    color: #7a8fa6;
    font-size: 11px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .2s;
    flex-shrink: 0;
}

.wiz-step.active .wiz-num { background: #00285a; color: #fff; }
.wiz-step.done .wiz-num { background: #22c55e; color: #fff; }

.wiz-label {
    font-size: 12px;
    font-weight: 700;
    color: #7a8fa6;
    letter-spacing: .4px;
    white-space: nowrap;
}

.wiz-step.active .wiz-label { color: #00285a; }
.wiz-step.done .wiz-label { color: #22c55e; }

.wiz-sep { color: #d9dee6; font-size: 18px; }

/* ─── Card ─────────────────────────────────────────── */
.pc {
    background: #fff;
    border: 1px solid #eef2f6;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 16px;
}

.ph {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 20px;
    border-bottom: 1px solid #eef2f6;
}

.pt {
    font-family: 'Cinzel', serif;
    font-size: 12px;
    font-weight: 700;
    color: #00285a;
    letter-spacing: 1px;
}

.sl {
    font-size: 10px;
    font-weight: 700;
    color: #7a8fa6;
    text-transform: uppercase;
    letter-spacing: 1px;
    display: block;
    padding: 14px 20px 0;
}

/* ─── Form Grid ─────────────────────────────────────── */
.fg {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    padding: 14px 20px;
}

.fgrp { display: flex; flex-direction: column; gap: 5px; }
.fgrp.full { grid-column: 1/-1; }
.fgrp label { font-size: 11px; font-weight: 700; color: #7a8fa6; text-transform: uppercase; letter-spacing: .5px; }

.fc {
    padding: 10px 14px;
    border: 1.5px solid #e8edf5;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    outline: none;
    width: 100%;
    background: #fff;
    transition: border-color .15s;
}
.fc:focus { border-color: #00285a; }
.ferr { font-size: 11px; color: #ff3f6c; margin-top: 3px; }
.help { font-size: 11px; color: #7a8fa6; line-height: 1.5; margin-top: 4px; }

/* ─── Toggle ─────────────────────────────────────────── */
.tog { display: flex; align-items: center; gap: 8px; cursor: pointer; }
.tog input { width: 16px; height: 16px; accent-color: #00285a; }
.tog span { font-size: 13px; font-weight: 600; color: #333; }

/* ─── Step Actions ───────────────────────────────────── */
.step-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 0;
}

.btn-next, .btn-submit {
    background: #00285a;
    color: #fff;
    border: none;
    padding: 10px 28px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    transition: background .15s;
}
.btn-next:hover, .btn-submit:hover { background: #1e3f75; }

.btn-back {
    background: #fff;
    color: #555;
    border: 1.5px solid #e8edf5;
    padding: 10px 22px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    transition: all .15s;
}
.btn-back:hover { border-color: #00285a; color: #00285a; }

.btn-link {
    color: #7a8fa6;
    font-size: 12px;
    text-decoration: none;
    padding: 10px 0;
}
.btn-link:hover { color: #00285a; }

/* ─── Rich Editor ───────────────────────────────────── */
.rich-wrap { border: 1.5px solid #e8edf5; border-radius: 8px; background: #fff; overflow: hidden; }
.rich-toolbar {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
    padding: 8px;
    background: #f8fafc;
    border-bottom: 1px solid #e8edf5;
}
.rich-style {
    height: 32px;
    min-width: 120px;
    border: 1px solid #d9dee6;
    border-radius: 7px;
    background: #fff;
    color: #334155;
    font-size: 12px;
    font-weight: 700;
    padding: 0 8px;
    outline: none;
}
.rich-btn {
    width: 32px;
    height: 32px;
    border: 1px solid #d9dee6;
    border-radius: 7px;
    background: #fff;
    color: #00285a;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 14px;
}
.rich-btn:hover, .rich-style:focus { border-color: #00285a; background: #eef5ff; }
.rich-editor {
    min-height: 160px;
    padding: 14px;
    outline: none;
    font-size: 14px;
    color: #1f2937;
    line-height: 1.7;
}
.rich-editor:empty::before { content: attr(data-placeholder); color: #94a3b8; }
.rich-editor h2 { font-size: 20px; color: #00285a; margin: 0 0 8px; }
.rich-editor h3 { font-size: 16px; color: #1a1a1a; margin: 0 0 8px; }
.rich-editor p, .rich-editor ul, .rich-editor ol, .rich-editor blockquote { margin: 0 0 8px; }
.rich-editor ul, .rich-editor ol { padding-left: 22px; }
.rich-editor blockquote { border-left: 3px solid #00285a; padding-left: 12px; color: #475569; }
.rich-source { display: none; }

/* ─── Variant Toggle Banner ──────────────────────────── */
.var-toggle-banner {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 20px;
    background: #f8fafc;
    border-bottom: 1px solid #eef2f6;
}
.var-toggle-label {
    font-size: 13px;
    font-weight: 600;
    color: #00285a;
}

/* ─── Switch ─────────────────────────────────────────── */
.switch { position: relative; display: inline-block; width: 40px; height: 22px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background: #d9dee6;
    border-radius: 22px;
    transition: .25s;
}
.slider:before {
    content: '';
    position: absolute;
    height: 16px;
    width: 16px;
    left: 3px;
    bottom: 3px;
    background: #fff;
    border-radius: 50%;
    transition: .25s;
}
.switch input:checked + .slider { background: #00285a; }
.switch input:checked + .slider:before { transform: translateX(18px); }

/* ─── Simple Price Section ───────────────────────────── */
#simpleSection .price-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    padding: 16px 20px;
}
@media(max-width:640px) { #simpleSection .price-grid { grid-template-columns: 1fr 1fr; } }

/* ─── Variant Builder ────────────────────────────────── */
.vb-section { padding: 16px 20px; }
.vb-title { font-size: 11px; font-weight: 700; color: #7a8fa6; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 10px; }

.color-pills { display: flex; flex-wrap: wrap; gap: 8px; }
.color-pill {
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 6px 14px 6px 8px;
    border: 1.5px solid #e8edf5;
    border-radius: 30px;
    cursor: pointer;
    font-size: 12px;
    font-weight: 700;
    color: #334155;
    background: #fff;
    transition: all .15s;
    user-select: none;
}
.color-pill:hover { border-color: #00285a; }
.color-pill.selected { border-color: #00285a; background: #eef5ff; color: #00285a; }
.color-pill input { display: none; }
.color-dot-sm { width: 14px; height: 14px; border-radius: 50%; border: 1px solid rgba(0,0,0,.1); flex-shrink: 0; }

.size-block {
    border: 1.5px solid #e8edf5;
    border-radius: 10px;
    background: #fafbff;
    padding: 12px 14px;
    display: none;
}
.size-block.visible { display: block; }
.size-block-title {
    font-size: 11px;
    font-weight: 800;
    color: #00285a;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.size-pills { display: flex; flex-wrap: wrap; gap: 6px; }
.size-pill {
    padding: 4px 12px;
    border: 1.5px solid #e8edf5;
    border-radius: 20px;
    cursor: pointer;
    font-size: 12px;
    font-weight: 700;
    color: #7a8fa6;
    background: #fff;
    transition: all .15s;
    user-select: none;
}
.size-pill:hover { border-color: #00285a; }
.size-pill.selected { border-color: #00285a; background: #00285a; color: #fff; }
.size-pill input { display: none; }

.gen-btn {
    background: #00285a;
    color: #fff;
    border: none;
    padding: 10px 22px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
}
.gen-btn:hover { background: #1e3f75; }

/* ─── Variant Table ──────────────────────────────────── */
.vt-wrap { overflow-x: auto; padding: 0 20px 16px; }
.vt { width: 100%; border-collapse: collapse; min-width: 640px; }
.vt th {
    padding: 9px 8px;
    font-size: 10px;
    font-weight: 700;
    color: #7a8fa6;
    text-transform: uppercase;
    background: #f8fafc;
    border-bottom: 1px solid #eef2f6;
    text-align: left;
}
.vt td { padding: 6px 6px; border-bottom: 1px solid #f0f4f8; vertical-align: middle; }
.vi {
    padding: 7px 10px;
    border: 1.5px solid #e8edf5;
    border-radius: 7px;
    font-size: 13px;
    font-family: inherit;
    outline: none;
    width: 100%;
}
.vi:focus { border-color: #00285a; }
.ci { width: 40px; height: 32px; padding: 2px 3px; border: 1.5px solid #e8edf5; border-radius: 7px; cursor: pointer; }
.bdel {
    background: #fce4ec;
    color: #c62828;
    border: none;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.bdel:hover { background: #ff3f6c; color: #fff; }
.badd {
    background: #f0f4f8;
    color: #00285a;
    border: 1.5px solid #e8edf5;
    padding: 7px 16px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.badd:hover { background: #e8f0fb; border-color: #00285a; }

/* ─── Bulk Price Bar ─────────────────────────────────── */
.bulk-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 20px;
    background: #fffbe6;
    border-bottom: 1px solid #ffd700;
    flex-wrap: wrap;
}
.bulk-bar label { font-size: 11px; font-weight: 700; color: #854d0e; white-space: nowrap; }
.bulk-bar .bi { font-size: 12px; }
.bulk-input {
    padding: 6px 10px;
    border: 1.5px solid #ffd700;
    border-radius: 7px;
    font-size: 13px;
    font-family: inherit;
    outline: none;
    width: 110px;
    background: #fff;
}
.bulk-apply {
    background: #ffd700;
    color: #00285a;
    border: none;
    padding: 7px 16px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}

/* ─── Images ─────────────────────────────────────────── */
.izone {
    border: 2px dashed #d9dee6;
    border-radius: 12px;
    padding: 32px 20px;
    text-align: center;
    cursor: pointer;
    transition: .2s;
    background: #fafbff;
    margin: 16px 20px;
}
.izone:hover, .izone.dragover { border-color: #00285a; background: #f0f4ff; }
.izone input { display: none; }
.igrid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
    gap: 10px;
    padding: 0 20px 16px;
}
.icard { position: relative; border-radius: 10px; overflow: hidden; border: 2px solid #eef2f6; aspect-ratio: 4/5; background: #f8fafc; }
.icard.main { border-color: #ffd700; }
.icard img { width: 100%; height: 100%; object-fit: cover; }
.ibadge { position: absolute; top: 5px; left: 5px; background: #ffd700; color: #00285a; font-size: 9px; font-weight: 700; padding: 2px 6px; border-radius: 20px; }
.iact { position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,.55); display: flex; gap: 3px; padding: 4px; opacity: 0; transition: .15s; }
.icard:hover .iact { opacity: 1; }
.iab { flex: 1; padding: 3px; border: none; border-radius: 5px; font-size: 10px; font-weight: 700; cursor: pointer; }

/* ─── Color Tabs for Images ─────────────────────────── */
.color-tabs { display: flex; gap: 0; padding: 0 20px; border-bottom: 1px solid #eef2f6; flex-wrap: wrap; }
.color-tab {
    padding: 10px 16px;
    font-size: 12px;
    font-weight: 700;
    color: #7a8fa6;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all .15s;
}
.color-tab:hover { color: #00285a; }
.color-tab.active { color: #00285a; border-bottom-color: #00285a; }
.color-tab-dot { width: 10px; height: 10px; border-radius: 50%; border: 1px solid rgba(0,0,0,.1); }

.color-tab-panel { display: none; }
.color-tab-panel.active { display: block; }

/* ─── New Product Image Queue ─────────────────────────── */
.new-img-queue { display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap: 8px; padding: 0 20px 16px; }
.new-img-thumb { position: relative; aspect-ratio: 4/5; border-radius: 8px; overflow: hidden; border: 2px solid #eef2f6; background: #f8fafc; }
.new-img-thumb img { width: 100%; height: 100%; object-fit: cover; }
.new-img-rm { position: absolute; top: 3px; right: 3px; background: #ff3f6c; color: #fff; border: none; width: 20px; height: 20px; border-radius: 50%; font-size: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; }

/* ─── Status Toggles ─────────────────────────────────── */
.status-grid { display: flex; flex-wrap: wrap; gap: 10px; padding: 14px 20px; }
.status-toggle {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 18px;
    border: 1.5px solid #e8edf5;
    border-radius: 10px;
    cursor: pointer;
    transition: all .15s;
    background: #fff;
    user-select: none;
}
.status-toggle:hover { border-color: #00285a; background: #f8fafc; }
.status-toggle.on { border-color: #00285a; background: #eef5ff; }
.status-toggle input { display: none; }
.status-icon { font-size: 18px; }
.status-name { font-size: 12px; font-weight: 700; color: #334155; }

/* ─── Alerts ─────────────────────────────────────────── */
.alert-err { background:#fce4ec; color:#c62828; padding:12px 16px; border-radius:10px; font-size:13px; margin: 14px 20px 0; }
.alert-ok { background:#e8f5e9; color:#2e7d32; padding:10px 16px; border-radius:10px; font-size:13px; font-weight:600; margin: 14px 20px 0; }

.upload-toast {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    padding: 12px 20px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    box-shadow: 0 4px 20px rgba(0,0,0,.14);
    animation: slideUp .25s ease;
}
@keyframes slideUp { from { opacity:0; transform: translateY(12px); } to { opacity:1; transform: none; } }

@media(max-width:640px) {
    .fg { grid-template-columns: 1fr; }
    .fgrp.full { grid-column: 1; }
    .wiz-label { display: none; }
    .wiz-step { padding: 14px 10px; }
}
</style>

<div class="wiz-wrap">

    {{-- ── WIZARD NAV ───────────────────────────────────── --}}
    <div class="wiz-nav" id="wizNav">
        <div class="wiz-step active" id="wn1" onclick="goStep(1)">
            <div class="wiz-num" id="wnum1">1</div>
            <div class="wiz-label">Basic Info</div>
        </div>
        <div class="wiz-sep">›</div>
        <div class="wiz-step" id="wn2" onclick="goStep(2)">
            <div class="wiz-num" id="wnum2">2</div>
            <div class="wiz-label">Pricing</div>
        </div>
        <div class="wiz-sep">›</div>
        <div class="wiz-step" id="wn3" onclick="goStep(3)">
            <div class="wiz-num" id="wnum3">3</div>
            <div class="wiz-label">Images</div>
        </div>
        <div class="wiz-sep">›</div>
        <div class="wiz-step" id="wn4" onclick="goStep(4)">
            <div class="wiz-num" id="wnum4">4</div>
            <div class="wiz-label">Publish</div>
        </div>
    </div>

    <form method="POST"
          action="{{ $isEdit ? route('admin.products.update', $product) : route('admin.products.store') }}"
          enctype="multipart/form-data"
          id="productForm">
        @csrf
        @if ($isEdit) @method('PUT') @endif

        {{-- ═══════════════════════════════════════════════ --}}
        {{-- STEP 1 — BASIC INFO                            --}}
        {{-- ═══════════════════════════════════════════════ --}}
        <div class="wiz-panel" id="step1">

            <div class="pc">
                <div class="ph">
                    <div class="pt">{{ $isEdit ? 'EDIT: ' . Str::upper(Str::limit($product->name, 30)) : 'NEW PRODUCT' }}</div>
                    <div style="display:flex;gap:8px">
                        @if ($isEdit)
                            <a href="{{ route('product.show', $product->slug) }}" target="_blank"
                               style="font-size:11px;padding:6px 12px;background:#fff;border:1px solid #e8edf5;border-radius:8px;color:#555;text-decoration:none">
                               <i class="bi bi-eye"></i> Preview
                            </a>
                        @endif
                        <a href="{{ route('admin.products.index') }}"
                           style="font-size:11px;padding:6px 12px;background:#fff;border:1px solid #e8edf5;border-radius:8px;color:#555;text-decoration:none">
                           <i class="bi bi-arrow-left"></i> Products
                        </a>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert-err">
                        @foreach ($errors->all() as $e) <div>• {{ $e }}</div> @endforeach
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert-ok"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
                @endif

                <span class="sl">Basic Information</span>
                <div class="fg">
                    <div class="fgrp full">
                        <label>Product Name *</label>
                        <input class="fc" type="text" name="name"
                               value="{{ old('name', $isEdit ? $product->name : '') }}"
                               id="fieldName" required placeholder="e.g. Classic Oversized Tee">
                    </div>

                    <div class="fgrp">
                        <label>Category *</label>
                        <select class="fc" name="category_id" id="fieldCategory" required>
                            <option value="">— Select Category —</option>
                            @php echo buildCategoryOptions($parentCategories, $selectedCategoryId, ''); @endphp
                        </select>
                    </div>

                    <div class="fgrp">
                        <label>SKU <span style="font-weight:400;text-transform:none">(optional)</span></label>
                        <input class="fc" type="text" name="sku"
                               value="{{ old('sku', $isEdit ? $product->sku : '') }}"
                               placeholder="TTT-TS-001">
                    </div>

                    <div class="fgrp full">
                        <label>Short Description <span style="font-weight:400;text-transform:none">(shows on listing cards)</span></label>
                        <input class="fc" type="text" name="short_description"
                               value="{{ old('short_description', $isEdit ? $product->short_description : '') }}"
                               placeholder="One-line product summary...">
                    </div>

                    <div class="fgrp full">
                        <label>Full Description</label>
                        <div class="rich-wrap">
                            <div class="rich-toolbar">
                                <select class="rich-style" id="descBlockStyle" onchange="fmtBlock(this.value)" title="Text style">
                                    <option value="P">Paragraph</option>
                                    <option value="H2">Heading</option>
                                    <option value="H3">Subheading</option>
                                </select>
                                <button type="button" class="rich-btn" onclick="fmt('bold')" title="Bold"><i class="bi bi-type-bold"></i></button>
                                <button type="button" class="rich-btn" onclick="fmt('italic')" title="Italic"><i class="bi bi-type-italic"></i></button>
                                <button type="button" class="rich-btn" onclick="fmt('underline')" title="Underline"><i class="bi bi-type-underline"></i></button>
                                <button type="button" class="rich-btn" onclick="fmt('insertUnorderedList')" title="Bullet list"><i class="bi bi-list-ul"></i></button>
                                <button type="button" class="rich-btn" onclick="fmt('insertOrderedList')" title="Numbered list"><i class="bi bi-list-ol"></i></button>
                                <button type="button" class="rich-btn" onclick="fmtBlock('BLOCKQUOTE')" title="Quote"><i class="bi bi-quote"></i></button>
                                <button type="button" class="rich-btn" onclick="fmt('removeFormat')" title="Clear"><i class="bi bi-eraser"></i></button>
                            </div>
                            <div id="descEditor" class="rich-editor" contenteditable="true"
                                 data-placeholder="Write product details, fabric info, style tips..."></div>
                        </div>
                        <textarea class="rich-source" id="descInput" name="description">{{ old('description', $isEdit ? $product->description : '') }}</textarea>
                    </div>

                    <div class="fgrp">
                        <label>Fabric</label>
                        <input class="fc" type="text" name="fabric"
                               value="{{ old('fabric', $isEdit ? $product->fabric : '') }}"
                               placeholder="e.g. 100% Cotton">
                    </div>

                    <div class="fgrp">
                        <label>Fit</label>
                        <input class="fc" type="text" name="fit"
                               value="{{ old('fit', $isEdit ? $product->fit : '') }}"
                               placeholder="e.g. Oversized, Regular, Slim">
                    </div>

                    <div class="fgrp full">
                        <label>Care Instructions</label>
                        <input class="fc" type="text" name="care_instructions"
                               value="{{ old('care_instructions', $isEdit ? $product->care_instructions : '') }}"
                               placeholder="e.g. Machine wash cold, tumble dry low">
                    </div>
                </div>
            </div>

            <div class="step-actions">
                <a href="{{ route('admin.products.index') }}" class="btn-link"><i class="bi bi-x-lg"></i> Cancel</a>
                <button type="button" class="btn-next" onclick="goStep(2)">
                    Pricing & Variants <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════ --}}
        {{-- STEP 2 — PRICING & VARIANTS                     --}}
        {{-- ═══════════════════════════════════════════════ --}}
        <div class="wiz-panel" id="step2" style="display:none">

            <div class="pc">
                <div class="var-toggle-banner">
                    <label class="switch">
                        <input type="checkbox" name="has_variants" id="hasVar" value="1"
                               {{ old('has_variants', $isEdit ? $product->has_variants ?? false : false) ? 'checked' : '' }}
                               onchange="toggleVar(this.checked)">
                        <span class="slider"></span>
                    </label>
                    <span class="var-toggle-label">This product has multiple sizes / colors</span>
                </div>

                {{-- ── SIMPLE PRICING ──────────────────────── --}}
                <div id="simpleSection">
                    <span class="sl">Price & Stock</span>
                    <div class="price-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;padding:16px 20px">
                        <div class="fgrp">
                            <label>Selling Price (₹) *</label>
                            <input class="fc" type="number" name="price" id="simplePrice"
                                   value="{{ old('price', $isEdit ? $product->price : '') }}"
                                   min="0" step="0.01" required placeholder="0">
                        </div>
                        <div class="fgrp">
                            <label>MRP / Original (₹)</label>
                            <input class="fc" type="number" name="original_price"
                                   value="{{ old('original_price', $isEdit ? $product->original_price : '') }}"
                                   min="0" step="0.01" placeholder="0">
                        </div>
                        <div class="fgrp">
                            <label>Cost Price (₹)</label>
                            <input class="fc" type="number" name="cost_price"
                                   value="{{ old('cost_price', $isEdit ? $product->cost_price : '') }}"
                                   min="0" step="0.01" placeholder="0">
                        </div>
                        <div class="fgrp">
                            <label>Stock Qty *</label>
                            <input class="fc" type="number" name="stock" id="simpleStock"
                                   value="{{ old('stock', $isEdit ? $product->stock : 0) }}"
                                   min="0" required>
                        </div>
                    </div>
                </div>

                {{-- ── VARIANT SECTION ─────────────────────── --}}
                <div id="varSection" style="display:none">

                    {{-- Step A: Pick Colors --}}
                    <div class="vb-section" style="border-bottom:1px solid #eef2f6">
                        <div class="vb-title"><i class="bi bi-palette"></i> Step 1 — Select Colors</div>
                        <div class="color-pills" id="colorPillsWrap">
                            @foreach ($colorsArray as $color)
                                <label class="color-pill" id="cpill_{{ $color->name }}">
                                    <input type="checkbox" class="colorPick"
                                           value="{{ $color->name }}"
                                           data-color-id="{{ $color->id }}"
                                           data-color-hex="{{ $color->hex_code }}"
                                           onchange="onColorToggle(this)">
                                    <span class="color-dot-sm" style="background:{{ $color->hex_code }}"></span>
                                    {{ $color->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Step B: Pick Sizes per Color --}}
                    <div class="vb-section" id="sizeBlocksWrap" style="display:none;border-bottom:1px solid #eef2f6">
                        <div class="vb-title"><i class="bi bi-rulers"></i> Step 2 — Select Sizes for each Color</div>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px" id="sizeBlocksGrid">
                            @foreach ($colorsArray as $color)
                                <div class="size-block" data-color-block="{{ $color->name }}" id="sblock_{{ $color->name }}">
                                    <div class="size-block-title">
                                        <span class="color-dot-sm" style="background:{{ $color->hex_code }}"></span>
                                        {{ $color->name }}
                                    </div>
                                    <div class="size-pills">
                                        @foreach ($sizesArray as $size)
                                            <label class="size-pill">
                                                <input type="checkbox" class="sizePick"
                                                       value="{{ $size->name }}"
                                                       data-for-color="{{ $color->name }}">
                                                {{ $size->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Step C: Generate --}}
                    <div class="vb-section" style="border-bottom:1px solid #eef2f6;display:flex;align-items:center;gap:14px;flex-wrap:wrap">
                        <button type="button" class="gen-btn" onclick="generateVariants()">
                            <i class="bi bi-diagram-3"></i> Generate Variant Rows
                        </button>
                        <button type="button" class="badd" onclick="addRow()">
                            <i class="bi bi-plus-lg"></i> Add Row Manually
                        </button>
                        <span class="help" style="margin:0">After generating, fill Price & Stock for each row.</span>
                    </div>

                    {{-- Bulk Price Fill --}}
                    <div class="bulk-bar" id="bulkBar" style="display:none">
                        <span><i class="bi bi-lightning-fill"></i></span>
                        <label>Apply to all rows →</label>
                        <input class="bulk-input" type="number" id="bulkPrice" placeholder="Price ₹" min="0" step="0.01">
                        <input class="bulk-input" type="number" id="bulkMrp" placeholder="MRP ₹" min="0" step="0.01">
                        <input class="bulk-input" type="number" id="bulkStock" placeholder="Stock" min="0">
                        <button type="button" class="bulk-apply" onclick="applyBulk()">Apply</button>
                    </div>

                    {{-- Variant Table --}}
                    <div class="vt-wrap">
                        <table class="vt">
                            <thead>
                                <tr>
                                    <th style="width:100px">Size</th>
                                    <th style="width:110px">Color</th>
                                    <th style="width:44px">Swatch</th>
                                    <th style="width:90px">Price ₹ *</th>
                                    <th style="width:90px">MRP ₹</th>
                                    <th style="width:90px">Cost ₹</th>
                                    <th style="width:72px">Stock *</th>
                                    <th style="width:100px">SKU</th>
                                    <th style="width:36px"></th>
                                </tr>
                            </thead>
                            <tbody id="varBody">
                                @if ($isEdit && ($product->has_variants ?? false) && $variants->count())
                                    @foreach ($variants as $i => $v)
                                        <tr class="vrow">
                                            <td><select class="vi" name="variants[{{ $i }}][size]">
                                                <option value="">— Size —</option>
                                                @foreach ($sizesArray as $size)
                                                    <option value="{{ $size->name }}" {{ $v->size == $size->name ? 'selected' : '' }}>{{ $size->name }}</option>
                                                @endforeach
                                            </select></td>
                                            <td><select class="vi" name="variants[{{ $i }}][color]" onchange="updateSwatch(this);syncImageTabs()">
                                                <option value="">— Color —</option>
                                                @foreach ($colorsArray as $color)
                                                    <option value="{{ $color->name }}" data-id="{{ $color->id }}" data-hex="{{ $color->hex_code }}" {{ $v->color == $color->name ? 'selected' : '' }}>{{ $color->name }}</option>
                                                @endforeach
                                            </select></td>
                                            <td><input class="ci" type="color" name="variants[{{ $i }}][color_hex]" value="{{ $v->color_hex ?? '#000000' }}"></td>
                                            <td><input class="vi" type="number" name="variants[{{ $i }}][price]" value="{{ $v->price }}" min="0" step="0.01" required></td>
                                            <td><input class="vi" type="number" name="variants[{{ $i }}][original_price]" value="{{ $v->original_price }}" min="0" step="0.01"></td>
                                            <td><input class="vi" type="number" name="variants[{{ $i }}][cost_price]" value="{{ $v->cost_price ?? '' }}" min="0" step="0.01"></td>
                                            <td><input class="vi" type="number" name="variants[{{ $i }}][stock]" value="{{ $v->stock }}" min="0" required></td>
                                            <td><input class="vi" type="text" name="variants[{{ $i }}][sku]" value="{{ $v->sku }}" placeholder="SKU"></td>
                                            <input type="hidden" name="variants[{{ $i }}][id]" value="{{ $v->id }}">
                                            <td><button type="button" class="bdel" onclick="this.closest('tr').remove()">×</button></td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="step-actions">
                <button type="button" class="btn-back" onclick="goStep(1)"><i class="bi bi-arrow-left"></i> Back</button>
                <button type="button" class="btn-next" onclick="goStep(3)">Images <i class="bi bi-arrow-right"></i></button>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════ --}}
        {{-- STEP 3 — IMAGES                                 --}}
        {{-- ═══════════════════════════════════════════════ --}}
        <div class="wiz-panel" id="step3" style="display:none">

            <div class="pc">
                <div class="ph">
                    <div class="pt">PRODUCT IMAGES</div>
                    <span style="font-size:12px;color:#7a8fa6"><i class="bi bi-cloud-upload"></i> ImageKit CDN</span>
                </div>

                @if ($isEdit)
                    {{-- EDIT MODE: color tabs + AJAX upload --}}
                    <div class="color-tabs" id="imgColorTabs">
                        <div class="color-tab active" data-color-id="" onclick="switchColorTab(this, '')">
                            <i class="bi bi-images"></i> All Images
                        </div>
                        @foreach ($colorsArray as $color)
                            <div class="color-tab" data-color-id="{{ $color->id }}" onclick="switchColorTab(this, '{{ $color->id }}')">
                                <span class="color-tab-dot" style="background:{{ $color->hex_code }}"></span>
                                {{ $color->name }}
                            </div>
                        @endforeach
                    </div>

                    {{-- Image grid --}}
                    <div class="igrid" id="igrid">
                        @foreach ($images as $img)
                            <div class="icard {{ $img->is_primary ? 'main' : '' }}" id="img_{{ $img->id }}" data-color-id="{{ $img->color_id ?? '' }}">
                                <img src="{{ $img->url }}" alt="{{ $img->alt_text ?? 'Product image' }}">
                                @if ($img->is_primary) <div class="ibadge">MAIN</div> @endif
                                <div class="iact">
                                    @if (!$img->is_primary)
                                        <button type="button" class="iab" style="background:#ffd700;color:#00285a" onclick="setMain({{ $img->id }})">Main</button>
                                    @endif
                                    <button type="button" class="iab" style="background:#ff3f6c;color:white" onclick="deleteImage({{ $img->id }})">Del</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Upload zone --}}
                    <div class="izone" id="dropzone">
                        <i class="bi bi-cloud-arrow-up" style="font-size:32px;color:#b0bec5;display:block;margin-bottom:8px"></i>
                        <div style="font-size:13px;color:#7a8fa6"><strong style="color:#00285a">Click</strong> or drag & drop images here</div>
                        <div style="font-size:11px;color:#b0bec5;margin-top:4px">JPG, PNG, WEBP · Max 5MB each</div>
                        <div style="font-size:11px;color:#7a8fa6;margin-top:8px">Select a color tab above to upload color-specific images</div>
                        <input type="file" id="fileInput" accept="image/jpeg,image/jpg,image/png,image/webp" multiple style="display:none">
                    </div>

                @else
                    {{-- CREATE MODE: queue images per color for form submit --}}
                    <div style="padding:14px 20px 0">
                        <div style="background:#e8f5e9;color:#2e7d32;padding:10px 14px;border-radius:8px;font-size:12px;font-weight:600">
                            <i class="bi bi-info-circle-fill"></i>
                            Upload images below. They will be saved when you submit the form.
                        </div>
                    </div>

                    <div class="color-tabs" id="newImgColorTabs">
                        <div class="color-tab active" data-color-id="" onclick="switchNewTab(this, '')">
                            <i class="bi bi-images"></i> General
                        </div>
                        @foreach ($colorsArray as $color)
                            <div class="color-tab" data-color-id="{{ $color->id }}" onclick="switchNewTab(this, '{{ $color->id }}')">
                                <span class="color-tab-dot" style="background:{{ $color->hex_code }}"></span>
                                {{ $color->name }}
                            </div>
                        @endforeach
                    </div>

                    {{-- General tab panel --}}
                    <div class="color-tab-panel active" id="newpanel_">
                        <div class="izone new-zone" id="newdrop_" onclick="document.getElementById('newinput_').click()"
                             style="margin:14px 20px">
                            <i class="bi bi-cloud-arrow-up" style="font-size:28px;color:#b0bec5;display:block;margin-bottom:6px"></i>
                            <div style="font-size:13px;color:#7a8fa6"><strong style="color:#00285a">Click</strong> or drag & drop</div>
                            <div style="font-size:11px;color:#b0bec5;margin-top:3px">General product images (no specific color)</div>
                            <input type="file" id="newinput_" name="color_images[0][]" accept="image/jpeg,image/jpg,image/png,image/webp" multiple style="display:none" onchange="previewNewImages(this, '')">
                        </div>
                        <div class="new-img-queue" id="newqueue_"></div>
                    </div>

                    @foreach ($colorsArray as $color)
                        <div class="color-tab-panel" id="newpanel_{{ $color->id }}">
                            <div class="izone new-zone" id="newdrop_{{ $color->id }}" onclick="document.getElementById('newinput_{{ $color->id }}').click()"
                                 style="margin:14px 20px">
                                <i class="bi bi-cloud-arrow-up" style="font-size:28px;color:#b0bec5;display:block;margin-bottom:6px"></i>
                                <div style="font-size:13px;color:#7a8fa6">
                                    <span class="color-tab-dot" style="background:{{ $color->hex_code }};display:inline-block;vertical-align:middle;margin-right:4px"></span>
                                    <strong style="color:#00285a">{{ $color->name }}</strong> images
                                </div>
                                <div style="font-size:11px;color:#b0bec5;margin-top:3px">JPG, PNG, WEBP · Max 5MB</div>
                                <input type="file" id="newinput_{{ $color->id }}" name="color_images[{{ $color->id }}][]"
                                       accept="image/jpeg,image/jpg,image/png,image/webp" multiple style="display:none"
                                       onchange="previewNewImages(this, '{{ $color->id }}')">
                            </div>
                            <div class="new-img-queue" id="newqueue_{{ $color->id }}"></div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="step-actions">
                <button type="button" class="btn-back" onclick="goStep(2)"><i class="bi bi-arrow-left"></i> Back</button>
                <button type="button" class="btn-next" onclick="goStep(4)">Status & SEO <i class="bi bi-arrow-right"></i></button>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════ --}}
        {{-- STEP 4 — STATUS & SEO                           --}}
        {{-- ═══════════════════════════════════════════════ --}}
        <div class="wiz-panel" id="step4" style="display:none">

            {{-- VISIBILITY --}}
            <div class="pc">
                <div class="ph"><div class="pt">VISIBILITY</div></div>

                <input type="hidden" name="is_active" value="0">
                <input type="hidden" name="is_new" value="0">
                <input type="hidden" name="is_featured" value="0">
                <input type="hidden" name="is_trending" value="0">
                <input type="hidden" name="is_on_sale" value="0">

                <div class="status-grid">
                    <label class="status-toggle {{ old('is_active', $isEdit ? $product->is_active ?? true : true) ? 'on' : '' }}" id="tog_active">
                        <input type="checkbox" name="is_active" value="1" onchange="updateToggleStyle(this,'tog_active')"
                               {{ old('is_active', $isEdit ? $product->is_active ?? true : true) ? 'checked' : '' }}>
                        <span class="status-icon">✅</span>
                        <div><div class="status-name">Active</div><div class="help" style="margin:0">Visible on site</div></div>
                    </label>

                    <label class="status-toggle {{ old('is_new', $isEdit ? $product->is_new ?? false : true) ? 'on' : '' }}" id="tog_new">
                        <input type="checkbox" name="is_new" value="1" onchange="updateToggleStyle(this,'tog_new')"
                               {{ old('is_new', $isEdit ? $product->is_new ?? false : true) ? 'checked' : '' }}>
                        <span class="status-icon">🆕</span>
                        <div><div class="status-name">New Arrival</div><div class="help" style="margin:0">Shows in New Arrivals</div></div>
                    </label>

                    <label class="status-toggle {{ old('is_featured', $isEdit ? $product->is_featured ?? false : false) ? 'on' : '' }}" id="tog_featured">
                        <input type="checkbox" name="is_featured" value="1" onchange="updateToggleStyle(this,'tog_featured')"
                               {{ old('is_featured', $isEdit ? $product->is_featured ?? false : false) ? 'checked' : '' }}>
                        <span class="status-icon">⭐</span>
                        <div><div class="status-name">Featured</div><div class="help" style="margin:0">Homepage featured section</div></div>
                    </label>

                    <label class="status-toggle {{ old('is_trending', $isEdit ? $product->is_trending ?? false : false) ? 'on' : '' }}" id="tog_trending">
                        <input type="checkbox" name="is_trending" value="1" onchange="updateToggleStyle(this,'tog_trending')"
                               {{ old('is_trending', $isEdit ? $product->is_trending ?? false : false) ? 'checked' : '' }}>
                        <span class="status-icon">🔥</span>
                        <div><div class="status-name">Trending</div><div class="help" style="margin:0">Shows in Trending Now</div></div>
                    </label>

                    <label class="status-toggle {{ old('is_on_sale', $isEdit ? $product->is_on_sale ?? false : false) ? 'on' : '' }}" id="tog_sale">
                        <input type="checkbox" name="is_on_sale" value="1" onchange="updateToggleStyle(this,'tog_sale')"
                               {{ old('is_on_sale', $isEdit ? $product->is_on_sale ?? false : false) ? 'checked' : '' }}>
                        <span class="status-icon">🏷️</span>
                        <div><div class="status-name">On Sale</div><div class="help" style="margin:0">Shows sale badge</div></div>
                    </label>
                </div>
            </div>

            {{-- TAGS --}}
            <div class="pc">
                <div class="ph"><div class="pt">PRODUCT TAGS</div></div>
                <div class="fg">
                    <div class="fgrp">
                        <label>Select Existing Tags</label>
                        <select class="fc" name="tag_ids[]" multiple size="5">
                            @forelse ($tagsArray as $tag)
                                <option value="{{ $tag->id }}" {{ in_array((int) $tag->id, $selectedTagIds, true) ? 'selected' : '' }}>{{ $tag->name }}</option>
                            @empty
                                <option value="" disabled>No tags yet</option>
                            @endforelse
                        </select>
                        <div class="help">Hold Ctrl / Cmd to select multiple.</div>
                    </div>
                    <div class="fgrp">
                        <label>Add New Tags</label>
                        <input class="fc" type="text" name="tag_names"
                               value="{{ old('tag_names') }}"
                               placeholder="streetwear, oversized, summer">
                        <div class="help">Comma-separated. New tags are created automatically.</div>
                    </div>
                </div>
            </div>

            {{-- SEO --}}
            <div class="pc">
                <div class="ph"><div class="pt">SEO</div></div>
                <div class="fg">
                    <div class="fgrp">
                        <label>Meta Title <span style="font-weight:400;text-transform:none">(max 70 chars)</span></label>
                        <input class="fc" type="text" name="meta_title" maxlength="70"
                               value="{{ old('meta_title', $isEdit ? $product->meta_title : '') }}"
                               placeholder="Leave blank to use product name">
                    </div>
                    <div class="fgrp">
                        <label>Meta Keywords</label>
                        <input class="fc" type="text" name="meta_keywords"
                               value="{{ old('meta_keywords', $isEdit ? $product->meta_keywords : '') }}"
                               placeholder="tshirt, streetwear, oversized">
                    </div>
                    <div class="fgrp full">
                        <label>Meta Description <span style="font-weight:400;text-transform:none">(max 170 chars)</span></label>
                        <textarea class="fc" name="meta_description" rows="2" maxlength="170"
                                  placeholder="Brief description for search engines...">{{ old('meta_description', $isEdit ? $product->meta_description : '') }}</textarea>
                    </div>
                    <div class="fgrp full">
                        <label>OG Image URL</label>
                        <input class="fc" type="text" name="og_image"
                               value="{{ old('og_image', $isEdit ? $product->og_image : '') }}"
                               placeholder="https://...">
                    </div>
                </div>

                {{-- Weight --}}
                <span class="sl">Shipping</span>
                <div class="fg">
                    <div class="fgrp">
                        <label>Weight (grams)</label>
                        <input class="fc" type="number" name="weight_grams"
                               value="{{ old('weight_grams', $isEdit ? $product->weight_grams : '') }}"
                               min="0" placeholder="e.g. 250">
                    </div>
                </div>
            </div>

            {{-- SUBMIT --}}
            <div class="step-actions">
                <button type="button" class="btn-back" onclick="goStep(3)"><i class="bi bi-arrow-left"></i> Back</button>
                <button type="submit" class="btn-submit" id="productSubmitBtn">
                    <i class="bi bi-check-lg"></i>
                    {{ $isEdit ? 'Update Product' : 'Save Product' }}
                </button>
            </div>

        </div>

    </form>

</div>

@push('scripts')
<script>
// ── Data from PHP ────────────────────────────────────────
var varIdx = {{ $isEdit && ($product->has_variants ?? false) && $variants->count() ? $variants->count() : 0 }};
var CSRF   = document.querySelector('meta[name="csrf-token"]').content;
var PRODUCT_ID = {{ $isEdit ? $product->id : 'null' }};
var sizes  = @json($sizesArray->pluck('name'));
var colors = @json($colorsArray->map(fn($c) => ['id'=>$c->id,'name'=>$c->name,'hex'=>$c->hex_code]));

// ── Current wizard step ──────────────────────────────────
var currentStep = 1;

// Jump to a step (with light validation on forward navigation)
function goStep(n) {
    if (n > currentStep && !validateStep(currentStep)) return;

    document.querySelectorAll('.wiz-panel').forEach(function(p) { p.style.display = 'none'; });
    document.getElementById('step' + n).style.display = 'block';

    // Update nav
    for (var i = 1; i <= 4; i++) {
        var nav = document.getElementById('wn' + i);
        var num = document.getElementById('wnum' + i);
        nav.className = 'wiz-step';
        if (i < n) { nav.classList.add('done'); num.innerHTML = '<i class="bi bi-check-lg"></i>'; }
        else if (i === n) { nav.classList.add('active'); num.textContent = i; }
        else { num.textContent = i; }
    }

    currentStep = n;
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Sync image tabs when entering step 3
    if (n === 3) syncImageTabs();
}

// ── Validation ────────────────────────────────────────────
function validateStep(s) {
    if (s === 1) {
        var name = document.getElementById('fieldName');
        var cat  = document.getElementById('fieldCategory');
        if (!name.value.trim()) { name.focus(); flash(name, 'Product name is required.'); return false; }
        if (!cat.value) { cat.focus(); flash(cat, 'Please select a category.'); return false; }
    }
    if (s === 2) {
        var hasVar = document.getElementById('hasVar').checked;
        if (!hasVar) {
            var sp = document.getElementById('simplePrice');
            var ss = document.getElementById('simpleStock');
            if (!sp.value || parseFloat(sp.value) < 0) { sp.focus(); flash(sp, 'Enter a valid price.'); return false; }
            if (ss.value === '') { ss.focus(); flash(ss, 'Enter stock quantity.'); return false; }
        }
    }
    return true;
}

function flash(el, msg) {
    var old = el.style.borderColor;
    el.style.borderColor = '#ff3f6c';
    showToast(msg, 'error');
    setTimeout(function() { el.style.borderColor = old; }, 2000);
}

// ── Rich Description Editor ───────────────────────────────
function sanitizeHtml(html) {
    var t = document.createElement('template');
    t.innerHTML = html || '';
    var allowed = { P:1,DIV:1,BR:1,STRONG:1,B:1,EM:1,I:1,U:1,H2:1,H3:1,UL:1,OL:1,LI:1,BLOCKQUOTE:1 };
    t.content.querySelectorAll('script,style,iframe,object,embed').forEach(function(el) { el.remove(); });
    function clean(node) {
        Array.from(node.childNodes).forEach(clean);
        if (node.nodeType !== 1) return;
        if (!allowed[node.tagName]) { node.replaceWith(...Array.from(node.childNodes)); return; }
        Array.from(node.attributes).forEach(function(a) { node.removeAttribute(a.name); });
    }
    Array.from(t.content.childNodes).forEach(clean);
    return t.innerHTML.trim();
}

function syncDesc() {
    var e = document.getElementById('descEditor');
    var s = document.getElementById('descInput');
    if (e && s) s.value = sanitizeHtml(e.innerHTML);
}

function fmt(cmd) {
    document.getElementById('descEditor')?.focus();
    document.execCommand(cmd, false, null);
    syncDesc();
}

function fmtBlock(tag) {
    document.getElementById('descEditor')?.focus();
    document.execCommand('formatBlock', false, tag);
    syncDesc();
}

(function initEditor() {
    var editor = document.getElementById('descEditor');
    var source = document.getElementById('descInput');
    if (!editor || !source) return;
    document.execCommand('styleWithCSS', false, false);
    document.execCommand('defaultParagraphSeparator', false, 'p');
    var val = source.value.trim();
    if (val) {
        editor.innerHTML = /<[a-z]/i.test(val) ? sanitizeHtml(val) :
            val.split(/\n{2,}/).map(function(p) { return '<p>' + p.replace(/\n/g,'<br>') + '</p>'; }).join('');
    }
    syncDesc();
    editor.addEventListener('input', syncDesc);
    editor.addEventListener('blur', syncDesc);
    editor.addEventListener('paste', function(e) {
        e.preventDefault();
        var text = (e.clipboardData || window.clipboardData).getData('text/plain');
        document.execCommand('insertText', false, text);
        syncDesc();
    });
})();

// ── Variant Toggle ────────────────────────────────────────
function toggleVar(on) {
    document.getElementById('varSection').style.display   = on ? 'block' : 'none';
    document.getElementById('simpleSection').style.display = on ? 'none'  : 'block';
    var sp = document.getElementById('simplePrice');
    var ss = document.getElementById('simpleStock');
    if (sp) sp.required = !on;
    if (ss) ss.required = !on;
}
toggleVar(document.getElementById('hasVar').checked);

// ── Color Pill Toggle ─────────────────────────────────────
function onColorToggle(cb) {
    var pill = document.getElementById('cpill_' + CSS.escape(cb.value));
    var block = document.getElementById('sblock_' + CSS.escape(cb.value));

    if (pill) pill.classList.toggle('selected', cb.checked);
    if (block) block.classList.toggle('visible', cb.checked);

    // Show size blocks wrap if any color selected
    var anySelected = document.querySelectorAll('.colorPick:checked').length > 0;
    document.getElementById('sizeBlocksWrap').style.display = anySelected ? 'block' : 'none';
    syncImageTabs();
}

// ── Size Pill Toggle ──────────────────────────────────────
document.addEventListener('click', function(e) {
    var pill = e.target.closest('.size-pill');
    if (pill) {
        var input = pill.querySelector('input');
        if (input) {
            input.checked = !input.checked;
            pill.classList.toggle('selected', input.checked);
        }
    }
});

// ── Generate Variants ─────────────────────────────────────
function generateVariants() {
    var body = document.getElementById('varBody');
    if (!body) return;
    var selectedColors = document.querySelectorAll('.colorPick:checked');
    if (!selectedColors.length) { showToast('Select at least one color first.', 'error'); return; }

    body.innerHTML = '';

    selectedColors.forEach(function(colorCb) {
        var colorName = colorCb.value;
        var colorHex  = colorCb.getAttribute('data-color-hex') || '#000000';
        var sizeChecks = document.querySelectorAll('.sizePick[data-for-color="' + colorName + '"]:checked');
        if (!sizeChecks.length) return;

        sizeChecks.forEach(function(sizeCb) {
            body.appendChild(makeVariantRow(varIdx++, sizeCb.value, colorName, colorHex));
        });
    });

    if (!body.children.length) { showToast('Select sizes for the chosen colors.', 'error'); return; }
    document.getElementById('bulkBar').style.display = 'flex';
    syncImageTabs();
}

function makeVariantRow(i, sizeName, colorName, colorHex) {
    var tr = document.createElement('tr');
    tr.className = 'vrow';
    var sizeOpts = '<option value="">— Size —</option>' + sizes.map(function(s) {
        return '<option value="' + s + '"' + (s === sizeName ? ' selected' : '') + '>' + s + '</option>';
    }).join('');
    var colorOpts = '<option value="">— Color —</option>' + colors.map(function(c) {
        return '<option value="' + c.name + '" data-id="' + c.id + '" data-hex="' + (c.hex||'#000000') + '"' +
               (c.name === colorName ? ' selected' : '') + '>' + c.name + '</option>';
    }).join('');

    tr.innerHTML = `
        <td><select class="vi" name="variants[${i}][size]" style="width:100%">${sizeOpts}</select></td>
        <td><select class="vi" name="variants[${i}][color]" style="width:100%" onchange="updateSwatch(this);syncImageTabs()">${colorOpts}</select></td>
        <td><input class="ci" type="color" name="variants[${i}][color_hex]" value="${colorHex}"></td>
        <td><input class="vi" type="number" name="variants[${i}][price]" placeholder="Price" min="0" step="0.01" required></td>
        <td><input class="vi" type="number" name="variants[${i}][original_price]" placeholder="MRP" min="0" step="0.01"></td>
        <td><input class="vi" type="number" name="variants[${i}][cost_price]" placeholder="Cost" min="0" step="0.01"></td>
        <td><input class="vi" type="number" name="variants[${i}][stock]" placeholder="Qty" min="0" required></td>
        <td><input class="vi" type="text" name="variants[${i}][sku]" placeholder="SKU"></td>
        <td><button type="button" class="bdel" onclick="this.closest('tr').remove()">×</button></td>
    `;
    return tr;
}

function addRow() {
    document.getElementById('varBody').appendChild(makeVariantRow(varIdx++, '', '', '#000000'));
    document.getElementById('bulkBar').style.display = 'flex';
}

function updateSwatch(sel) {
    var hex = sel.options[sel.selectedIndex]?.getAttribute('data-hex') || '#000000';
    var swatch = sel.closest('tr').querySelector('input[type="color"]');
    if (swatch) swatch.value = hex;
}

// ── Bulk Price Fill ───────────────────────────────────────
function applyBulk() {
    var price = document.getElementById('bulkPrice').value;
    var mrp   = document.getElementById('bulkMrp').value;
    var stock = document.getElementById('bulkStock').value;
    document.querySelectorAll('#varBody .vrow').forEach(function(tr) {
        if (price !== '') tr.querySelector('[name*="[price]"]').value = price;
        if (mrp   !== '') tr.querySelector('[name*="[original_price]"]').value = mrp;
        if (stock !== '') tr.querySelector('[name*="[stock]"]').value = stock;
    });
    showToast('Applied to all ' + document.querySelectorAll('#varBody .vrow').length + ' rows.', 'success');
}

// ── Visibility Toggles ────────────────────────────────────
function updateToggleStyle(input, id) {
    document.getElementById(id).classList.toggle('on', input.checked);
}

// ── Image Tabs (Edit mode) ────────────────────────────────
function switchColorTab(el, colorId) {
    document.querySelectorAll('#imgColorTabs .color-tab').forEach(function(t) { t.classList.remove('active'); });
    el.classList.add('active');

    // Filter image cards
    document.querySelectorAll('#igrid .icard').forEach(function(card) {
        var cid = String(card.getAttribute('data-color-id') || '');
        card.style.display = (colorId === '' || cid === String(colorId)) ? '' : 'none';
    });

    // Update AJAX upload colorId
    window._currentUploadColorId = colorId;
    window._currentUploadColorName = el.textContent.trim();
}

// ── Image Tabs (Create mode) ──────────────────────────────
function switchNewTab(el, colorId) {
    document.querySelectorAll('#newImgColorTabs .color-tab').forEach(function(t) { t.classList.remove('active'); });
    el.classList.add('active');
    document.querySelectorAll('.color-tab-panel').forEach(function(p) { p.classList.remove('active'); });
    var panel = document.getElementById('newpanel_' + colorId);
    if (panel) panel.classList.add('active');
}

function previewNewImages(input, colorId) {
    var queue = document.getElementById('newqueue_' + colorId);
    if (!queue) return;
    Array.from(input.files || []).forEach(function(file) {
        if (!file.type.startsWith('image/')) return;
        var thumb = document.createElement('div');
        thumb.className = 'new-img-thumb';
        var img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.onload = function() { URL.revokeObjectURL(img.src); };
        var rm = document.createElement('button');
        rm.type = 'button';
        rm.className = 'new-img-rm';
        rm.textContent = '×';
        rm.onclick = function() { thumb.remove(); };
        thumb.appendChild(img);
        thumb.appendChild(rm);
        queue.appendChild(thumb);
    });
}

// ── Sync image tabs based on selected variant colors ──────
function syncImageTabs() {
    // For edit mode: nothing to do (tabs are static)
    // For new product mode: no-op (tabs are always visible)
}

// ── AJAX Image Upload (Edit mode only) ───────────────────
@if ($isEdit)
var dropzone  = document.getElementById('dropzone');
var fileInput = document.getElementById('fileInput');
var uploading = false;
window._currentUploadColorId   = '';
window._currentUploadColorName = 'All Images';

dropzone.addEventListener('click', function() { fileInput.click(); });
fileInput.addEventListener('change', function(e) {
    if (e.target.files.length > 0) { uploadFiles(Array.from(e.target.files)); fileInput.value = ''; }
});
dropzone.addEventListener('dragover',  function(e) { e.preventDefault(); dropzone.classList.add('dragover'); });
dropzone.addEventListener('dragleave', function()  { dropzone.classList.remove('dragover'); });
dropzone.addEventListener('drop', function(e) {
    e.preventDefault();
    dropzone.classList.remove('dragover');
    var files = Array.from(e.dataTransfer.files).filter(function(f) { return f.type.startsWith('image/'); });
    if (files.length) uploadFiles(files);
});

function uploadFiles(files) {
    if (uploading) return;
    uploading = true;
    var total = files.length, done = 0;
    showToast('Uploading ' + total + ' image(s)…', 'info');

    files.forEach(function(file) {
        if (file.size > 5 * 1024 * 1024) {
            showToast(file.name + ' too large (max 5MB)', 'error');
            done++; if (done === total) uploading = false; return;
        }
        var fd = new FormData();
        fd.append('file', file);
        fd.append('model_type', 'product');
        fd.append('model_id', PRODUCT_ID);
        fd.append('collection', 'default');
        fd.append('is_primary', document.querySelectorAll('.icard').length === 0 ? '1' : '0');
        if (window._currentUploadColorId) {
            fd.append('color_id', window._currentUploadColorId);
            fd.append('alt_text', window._currentUploadColorName);
        }
        fetch('{{ route('admin.media.upload') }}', { method:'POST', headers:{'X-CSRF-TOKEN':CSRF}, body:fd })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                done++;
                if (data.success) {
                    addImageCard(data.media);
                    showToast('Uploaded' + (window._currentUploadColorId ? ' (' + window._currentUploadColorName + ')' : '') + ' ✓', 'success');
                } else { showToast('Upload failed: ' + (data.message || 'Unknown'), 'error'); }
                if (done === total) uploading = false;
            })
            .catch(function(err) { done++; showToast('Error: ' + err.message, 'error'); if (done === total) uploading = false; });
    });
}

function addImageCard(media) {
    var grid = document.getElementById('igrid');
    var div  = document.createElement('div');
    div.className = 'icard' + (media.is_primary ? ' main' : '');
    div.id = 'img_' + media.id;
    div.setAttribute('data-color-id', media.color_id || '');
    div.innerHTML = `
        <img src="${media.thumb_url || media.url}" alt="${media.alt_text || 'Product image'}">
        ${media.is_primary ? '<div class="ibadge">MAIN</div>' : ''}
        <div class="iact">
            ${!media.is_primary ? '<button type="button" class="iab" style="background:#ffd700;color:#00285a" onclick="setMain(' + media.id + ')">Main</button>' : ''}
            <button type="button" class="iab" style="background:#ff3f6c;color:white" onclick="deleteImage(' + media.id + ')">Del</button>
        </div>`;
    grid.appendChild(div);
}

function setMain(id) {
    fetch('/admin/media/' + id + '/primary', { method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json','Content-Type':'application/json'}, body:'{}' })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (!res.success) return;
            document.querySelectorAll('.icard').forEach(function(card) {
                card.classList.remove('main');
                var badge = card.querySelector('.ibadge'); if (badge) badge.remove();
            });
            var card = document.getElementById('img_' + id);
            if (card) { card.classList.add('main'); card.insertAdjacentHTML('afterbegin','<div class="ibadge">MAIN</div>'); }
            showToast('Main image updated', 'success');
        });
}

function deleteImage(id) {
    if (!confirm('Delete this image?')) return;
    fetch('{{ route('admin.media.destroy', ['media' => '__ID__']) }}'.replace('__ID__', id),
          { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json','Content-Type':'application/json'}, body:'{}' })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) { var el = document.getElementById('img_' + id); if (el) el.remove(); showToast('Image deleted', 'success'); }
        });
}
@endif

// ── Toast Notification ────────────────────────────────────
function showToast(message, type) {
    var old = document.querySelector('.upload-toast'); if (old) old.remove();
    var t = document.createElement('div');
    t.className = 'upload-toast';
    t.style.cssText = (type === 'error' ? 'background:#fce4ec;color:#c62828;' :
                       type === 'success' ? 'background:#e8f5e9;color:#2e7d32;' :
                       'background:#fffbe6;color:#854d0e;');
    t.textContent = message;
    document.body.appendChild(t);
    setTimeout(function() { t.style.opacity = '0'; t.style.transition = 'opacity .3s'; setTimeout(function() { t.remove(); }, 300); }, 3000);
}

// ── Form Submit ───────────────────────────────────────────
document.getElementById('productForm').addEventListener('submit', function() {
    syncDesc();
    var btn = document.getElementById('productSubmitBtn');
    if (!btn || btn.disabled) return;
    btn.disabled = true;
    btn.style.opacity = '.7';
    btn.style.cursor = 'wait';
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving…';
});

// ── On load: restore step if errors ──────────────────────
@if ($errors->any())
    goStep(1);
@elseif (session('success') && $isEdit)
    goStep(1);
@endif
</script>
@endpush
@endsection
