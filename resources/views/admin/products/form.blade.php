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

    if (!function_exists('buildCategoryOptions')) {
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
.icard { position: relative; border-radius: 12px; overflow: hidden; border: 2.5px solid #eef2f6; aspect-ratio: 4/5; background: #f8fafc; transition: all .2s; }
.icard.main { border-color: #f59e0b; box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.28); }
.icard.is-cover { border-color: #00285a; box-shadow: 0 0 0 2px rgba(0, 40, 90, 0.35); }
.icard.main.is-cover { border-color: #00285a; box-shadow: 0 0 0 2px #f59e0b; }
.icard img { width: 100%; height: 100%; object-fit: cover; }
.ibadge { position: absolute; top: 6px; left: 6px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #ffffff; font-size: 10px; font-weight: 800; padding: 3px 8px; border-radius: 20px; letter-spacing: 0.5px; box-shadow: 0 2px 5px rgba(0,0,0,0.25); z-index: 2; }
.ibadge-cover { position: absolute; top: 6px; right: 6px; background: linear-gradient(135deg, #00285a 0%, #1e3f75 100%); color: #ffffff; font-size: 9px; font-weight: 800; padding: 3px 8px; border-radius: 20px; letter-spacing: 0.5px; box-shadow: 0 2px 5px rgba(0,0,0,0.25); z-index: 2; }
.isource { position: absolute; top: 6px; right: 6px; background: rgba(15,23,42,.78); color: #fff; font-size: 9px; font-weight: 700; padding: 2.5px 7px; border-radius: 20px; max-width: 80px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; z-index: 2; }
.iact { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(15,23,42,0.96) 0%, rgba(15,23,42,0.8) 75%, transparent 100%); display: flex; flex-wrap: wrap; gap: 3px; padding: 5px; opacity: 0; transition: .18s ease; z-index: 3; }
.icard:hover .iact, .icard:focus-within .iact { opacity: 1; }
.iab { flex: 1 1 auto; padding: 4px 4px; border: none; border-radius: 5px; font-size: 9.5px; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 2px; text-decoration: none; transition: transform .1s, filter .15s; white-space: nowrap; }
.iab:hover { transform: scale(1.03); filter: brightness(1.1); }
.iab-cover { background: #00285a; color: #ffffff; }
.iab-main { background: #ffd700; color: #00285a; }
.iab-del { background: #ef4444; color: #ffffff; }
.iab-view { background: rgba(255,255,255,0.22); color: #ffffff; }
.img-manage-bar { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 14px 20px 0; flex-wrap: wrap; }
.img-manage-title { font-size: 12px; font-weight: 800; color: #00285a; display: flex; align-items: center; gap: 8px; }
.img-count-badge { background: #eef5ff; color: #00285a; border: 1px solid #dbeafe; border-radius: 999px; padding: 4px 10px; font-size: 11px; font-weight: 800; }
.img-empty-note { margin: 0 20px 14px; padding: 16px; border: 1px dashed #cbd5e1; border-radius: 10px; color: #64748b; background: #f8fafc; font-size: 12px; text-align: center; }

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

/* ─── New Product Image Queue (Create Mode) ───────────── */
.new-img-queue { display: grid; grid-template-columns: repeat(auto-fill, minmax(105px, 1fr)); gap: 10px; padding: 0 20px 16px; }
.new-img-thumb { position: relative; aspect-ratio: 4/5; border-radius: 10px; overflow: hidden; border: 2.5px solid #eef2f6; background: #f8fafc; transition: all .2s; }
.new-img-thumb.main { border-color: #f59e0b; box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.28); }
.new-img-thumb.is-cover { border-color: #00285a; box-shadow: 0 0 0 2px rgba(0,40,90,0.35); }
.new-img-thumb.main.is-cover { border-color: #00285a; box-shadow: 0 0 0 2px #f59e0b; }
.new-img-thumb img { width: 100%; height: 100%; object-fit: cover; }
.new-img-badge { position: absolute; top: 4px; left: 4px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #fff; font-size: 8.5px; font-weight: 800; padding: 2px 6px; border-radius: 12px; z-index: 2; }
.new-img-badge-cover { position: absolute; bottom: 32px; left: 4px; background: linear-gradient(135deg, #00285a 0%, #1e3f75 100%); color: #fff; font-size: 8.5px; font-weight: 800; padding: 2px 6px; border-radius: 12px; z-index: 2; }
.new-img-act { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(15,23,42,0.95) 0%, transparent 100%); display: flex; flex-wrap: wrap; gap: 2px; padding: 4px; z-index: 2; }
.new-img-btn-main { flex: 1 1 45%; border: none; border-radius: 4px; font-size: 9px; font-weight: 800; background: #ffd700; color: #00285a; cursor: pointer; padding: 3px 2px; text-align: center; }
.new-img-btn-cover { flex: 1 1 45%; border: none; border-radius: 4px; font-size: 9px; font-weight: 800; background: #00285a; color: #fff; cursor: pointer; padding: 3px 2px; text-align: center; }
.new-img-rm { position: absolute; top: 4px; right: 4px; background: #ef4444; color: #fff; border: none; width: 22px; height: 22px; border-radius: 50%; font-size: 13px; font-weight: 900; cursor: pointer; display: flex; align-items: center; justify-content: center; z-index: 3; box-shadow: 0 2px 5px rgba(0,0,0,0.25); }

/* ─── Print Sides (Front & Back) ─────────────────────── */
.print-sides-selector {
    display: flex;
    gap: 8px;
    padding: 12px 20px;
    flex-wrap: wrap;
    align-items: center;
    border-bottom: 1px solid #eef2f6;
    background: #f8fafc;
}
.print-side-pill {
    padding: 6px 14px;
    border-radius: 20px;
    border: 1.5px solid #cbd5e1;
    font-size: 12px;
    font-weight: 700;
    color: #475569;
    cursor: pointer;
    background: #fff;
    transition: all .15s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.print-side-pill:hover { border-color: #00285a; color: #00285a; }
.print-side-pill.active {
    background: #00285a;
    color: #fff;
    border-color: #00285a;
    box-shadow: 0 2px 6px rgba(0,40,90,0.22);
}
.print-sides-wrap {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    padding: 16px 20px;
}
@media (max-width: 820px) {
    .print-sides-wrap { grid-template-columns: 1fr; }
}
.print-side-box {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    transition: opacity .2s, border-color .2s;
}
.print-side-box:hover { border-color: #cbd5e1; }
.print-side-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.print-side-title {
    font-size: 13px;
    font-weight: 800;
    color: #00285a;
    display: flex;
    align-items: center;
    gap: 7px;
}
.print-side-badge {
    font-size: 11px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
    background: #e0f2fe;
    color: #0369a1;
}
.izone-compact {
    border: 2px dashed #cbd5e1;
    border-radius: 10px;
    padding: 16px 12px;
    text-align: center;
    background: #fafbff;
    cursor: pointer;
    transition: all .2s;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
}
.izone-compact:hover { border-color: #00285a; background: #f0f4ff; }
.side-img-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(95px, 1fr));
    gap: 8px;
    min-height: 40px;
}

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
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
                            <label style="margin-bottom:0">SKU <span style="font-weight:400;text-transform:none">(unique identifier)</span></label>
                            <button type="button" class="btn btn-sm" id="btnAutoSku" onclick="fillProductSku()"
                                    title="Auto-calculate unique non-colliding SKU"
                                    style="background:#00285a;color:#fff;border:none;border-radius:6px;font-size:11px;font-weight:700;padding:4px 10px;display:inline-flex;align-items:center;gap:5px;cursor:pointer;transition:all .15s">
                                <i class="bi bi-magic"></i> Auto SKU
                            </button>
                        </div>
                        <div style="position:relative">
                            <input class="fc" type="text" name="sku" id="fieldSku"
                                   value="{{ old('sku', $isEdit ? $product->sku : '') }}"
                                   oninput="debounceCheckSku(this.value)"
                                   placeholder="TTT-OTS-001" style="text-transform:uppercase;letter-spacing:0.5px;font-weight:600">
                            <span id="skuSpinner" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);display:none;font-size:14px;color:#00285a">
                                <i class="bi bi-arrow-repeat spin"></i>
                            </span>
                        </div>
                        <div id="skuFeedback" style="font-size:11px;margin-top:4px;font-weight:600;min-height:16px;display:none"></div>
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
                        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;margin-bottom:10px">
                            <div class="vb-title" style="margin-bottom:0"><i class="bi bi-palette"></i> Step 1 — Select Colors</div>
                            <button type="button" class="btn btn-sm" onclick="openQuickColorModal()"
                                    style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;border-radius:6px;font-size:11.5px;font-weight:700;padding:5px 12px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;transition:all .15s">
                                <i class="bi bi-plus-circle-fill"></i> + Add New Color
                            </button>
                        </div>
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

                    {{-- Step B: Pick Sizes per Color & Upload T-Shirt Photos --}}
                    <div class="vb-section" id="sizeBlocksWrap" style="display:none;border-bottom:1px solid #eef2f6">
                        <div class="vb-title"><i class="bi bi-rulers"></i> Step 2 — Select Sizes &amp; Upload T-Shirt Photos for each Color</div>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:12px" id="sizeBlocksGrid">
                            @foreach ($colorsArray as $color)
                                <div class="size-block" data-color-block="{{ $color->name }}" id="sblock_{{ $color->name }}">
                                    <div class="size-block-title" style="display:flex;justify-content:space-between;align-items:center">
                                        <div style="display:flex;align-items:center;gap:6px">
                                            <span class="color-dot-sm" style="background:{{ $color->hex_code }}"></span>
                                            {{ $color->name }}
                                        </div>
                                        <button type="button" class="btn btn-link btn-sm"
                                                style="color:#0284c7;font-size:11px;font-weight:700;padding:0;text-decoration:none;cursor:pointer"
                                                onclick="toggleAllSizesForColor('{{ $color->name }}')">
                                            Select All Sizes
                                        </button>
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

                                    {{-- Color T-Shirt Photos Section --}}
                                    <div class="color-tshirt-box" style="margin-top:12px;padding-top:10px;border-top:1px dashed #cbd5e1">
                                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
                                            <span style="font-size:11px;font-weight:700;color:#00285a;display:flex;align-items:center;gap:5px">
                                                <i class="bi bi-camera-fill" style="color:#0284c7"></i> {{ $color->name }} T-Shirt Photos
                                            </span>
                                            @if ($color->image)
                                                <a href="{{ $color->image }}" target="_blank" title="View Color Mockup" style="font-size:10.5px;color:#0284c7;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:4px">
                                                    <img src="{{ $color->image }}" alt="{{ $color->name }}" style="width:18px;height:18px;border-radius:4px;object-fit:cover;border:1px solid #cbd5e1"> Base Mockup
                                                </a>
                                            @endif
                                        </div>

                                        @if ($isEdit)
                                            @php
                                                $colorImgs = $product->productImages->where('color_id', $color->id);
                                            @endphp
                                            <div class="color-img-grid" id="colorGrid_{{ $color->id }}" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(65px,1fr));gap:6px;margin-bottom:8px">
                                                @foreach ($colorImgs as $cImg)
                                                    @php $isCover = ($product->image === $cImg->url); @endphp
                                                    <div class="icard {{ $cImg->is_primary ? 'main' : '' }} {{ $isCover ? 'is-cover' : '' }}" id="cimg_{{ $cImg->id }}" data-id="{{ $cImg->id }}" data-url="{{ $cImg->url }}" style="aspect-ratio:1;border-radius:8px">
                                                        <img src="{{ $cImg->url }}" alt="{{ $color->name }}">
                                                        <div class="ibadge-cover" style="{{ $isCover ? '' : 'display:none;' }}">★ COVER</div>
                                                        <div class="iact" style="padding:2px">
                                                            <button type="button" class="iab iab-cover js-set-cover" style="{{ $isCover ? 'display:none;' : '' }};font-size:8.5px;padding:2px" onclick="setMain('{{ $cImg->id }}', '{{ $cImg->url }}', 'product_image')">
                                                                Cover
                                                            </button>
                                                            <button type="button" class="iab iab-del" style="font-size:8.5px;padding:2px" onclick="deleteColorImageAjax('{{ $cImg->id }}', '{{ $cImg->url }}', {{ $color->id }})">
                                                                Del
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="izone-compact" onclick="document.getElementById('step2_cFileInput_{{ $color->id }}').click()" style="padding:10px 8px">
                                                <i class="bi bi-cloud-arrow-up" style="font-size:18px;color:#0284c7"></i>
                                                <div style="font-size:11.5px;font-weight:700;color:#00285a">+ Upload {{ $color->name }} Photos</div>
                                                <div style="font-size:10px;color:#94a3b8">Multiple photos · JPG, PNG, WEBP</div>
                                                <input type="file" id="step2_cFileInput_{{ $color->id }}" accept="image/jpeg,image/png,image/webp" multiple style="display:none" onchange="uploadColorPhotosAjax(this, {{ $color->id }}, '{{ $color->name }}')">
                                            </div>
                                        @else
                                            <div class="izone-compact" onclick="document.getElementById('step2_cFileInput_{{ $color->id }}').click()" style="padding:10px 8px">
                                                <i class="bi bi-cloud-arrow-up" style="font-size:18px;color:#0284c7"></i>
                                                <div style="font-size:11.5px;font-weight:700;color:#00285a">+ Select {{ $color->name }} Photos</div>
                                                <div style="font-size:10px;color:#94a3b8">Upload photos for this color</div>
                                                <input type="file" id="step2_cFileInput_{{ $color->id }}" accept="image/jpeg,image/png,image/webp" multiple style="display:none" onchange="handleStep2ColorFiles(this, '{{ $color->id }}', '{{ $color->name }}')">
                                            </div>
                                            <div class="new-img-queue" id="step2_colorQueue_{{ $color->id }}" style="padding:8px 0 0;grid-template-columns:repeat(auto-fill,minmax(65px,1fr));gap:6px"></div>
                                        @endif
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

            {{-- 1. PRINT SIDES CONFIGURATION & MOCKUP IMAGES (FRONT & BACK) --}}
            <div class="pc" style="margin-bottom:20px">
                <div class="ph" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
                    <div>
                        <div class="pt" style="display:flex;align-items:center;gap:8px">
                            <i class="bi bi-layers-fill" style="color:#00285a"></i> PRINT SIDES & MOCKUPS (FRONT & BACK)
                        </div>
                        <div style="font-size:11px;color:#7a8fa6;margin-top:2px">Upload multiple mockup / design photos for Front Side and Back Side prints.</div>
                    </div>
                    <span style="background:#e0f2fe;color:#0369a1;font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;display:inline-flex;align-items:center;gap:5px">
                        <i class="bi bi-aspect-ratio"></i> Front & Back Views
                    </span>
                </div>

                {{-- Available Print Sides Selector --}}
                <div class="print-sides-selector">
                    <span style="font-size:12px;font-weight:700;color:#00285a;margin-right:8px">Print Options:</span>
                    @php
                        $curSides = old('available_print_sides', $isEdit ? ($product->available_print_sides ?? 'both') : 'both');
                    @endphp
                    <input type="hidden" name="available_print_sides" id="fieldPrintSides" value="{{ $curSides }}">
                    
                    <button type="button" class="print-side-pill {{ $curSides === 'both' ? 'active' : '' }}" id="pill_both" onclick="selectPrintSides('both')">
                        <i class="bi bi-arrows-expand"></i> Both (Front & Back)
                    </button>
                    <button type="button" class="print-side-pill {{ $curSides === 'front_only' ? 'active' : '' }}" id="pill_front_only" onclick="selectPrintSides('front_only')">
                        <i class="bi bi-arrow-left-circle"></i> Front Only
                    </button>
                    <button type="button" class="print-side-pill {{ $curSides === 'back_only' ? 'active' : '' }}" id="pill_back_only" onclick="selectPrintSides('back_only')">
                        <i class="bi bi-arrow-right-circle"></i> Back Only
                    </button>
                </div>

                <div class="print-sides-wrap" id="printSidesWrap">
                    {{-- ── FRONT SIDE IMAGES ── --}}
                    <div class="print-side-box" id="boxFrontSide">
                        <div class="print-side-header">
                            <div class="print-side-title">
                                <i class="bi bi-person-bounding-box" style="color:#0284c7;font-size:16px"></i>
                                <span>Front Side Print Images</span>
                            </div>
                            <span class="print-side-badge" id="frontCountBadge">
                                {{ count($frontPrintImages ?? []) }} File{{ count($frontPrintImages ?? []) === 1 ? '' : 's' }}
                            </span>
                        </div>
                        <div style="font-size:11px;color:#64748b">
                            Multiple images supported. Select <strong>★ Main</strong> on any photo to set it as the primary front print.
                        </div>

                        {{-- In Edit mode, show existing Front Images --}}
                        @if ($isEdit)
                            <div class="side-img-grid" id="frontGrid">
                                @forelse ($frontPrintImages as $fImg)
                                    @php
                                        $isProductCover = ($product->image === $fImg->url);
                                    @endphp
                                    <div class="icard {{ $fImg->is_primary ? 'main' : '' }} {{ $isProductCover ? 'is-cover' : '' }}" id="fimg_{{ $fImg->id ?: 'front' }}"
                                         data-id="{{ $fImg->id ?: 'front' }}"
                                         data-url="{{ $fImg->url }}"
                                         data-source="{{ $fImg->source ?? 'media' }}"
                                         data-side="front">
                                        <img src="{{ $fImg->thumb_url ?: $fImg->url }}" alt="{{ $fImg->label }}">
                                        <div class="ibadge" style="{{ $fImg->is_primary ? '' : 'display:none;' }}">★ FRONT MAIN</div>
                                        <div class="ibadge-cover" style="{{ $isProductCover ? '' : 'display:none;' }}">★ COVER</div>
                                        <div class="iact">
                                            <button type="button" class="iab iab-cover js-set-cover"
                                                    style="{{ $isProductCover ? 'display:none;' : '' }}"
                                                    onclick="setMain('{{ $fImg->id ?: 'front' }}', '{{ $fImg->url }}', '{{ $fImg->source ?? 'media' }}')">
                                                <i class="bi bi-star"></i> Cover
                                            </button>
                                            <button type="button" class="iab iab-main js-set-side-main"
                                                    style="{{ $fImg->is_primary ? 'display:none;' : '' }}"
                                                    onclick="setSideMain('front', '{{ $fImg->id ?: 'front' }}', '{{ $fImg->url }}', '{{ $fImg->source ?? 'media' }}')">
                                                <i class="bi bi-star-fill"></i> Front
                                            </button>
                                            <button type="button" class="iab iab-del"
                                                    onclick="deleteSideImage('front', '{{ $fImg->id ?: 'front' }}', '{{ $fImg->url }}', '{{ $fImg->source ?? 'media' }}')">
                                                <i class="bi bi-trash-fill"></i> Del
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="img-empty-note" id="frontEmptyNote" style="grid-column:1/-1;margin:0;padding:12px;font-size:11px">
                                        No front side images uploaded yet.
                                    </div>
                                @endforelse
                            </div>

                            {{-- Dropzone / Upload for Front Side (Edit Mode) --}}
                            <div class="izone-compact" onclick="document.getElementById('frontFilesInputEdit').click()">
                                <i class="bi bi-cloud-arrow-up" style="font-size:22px;color:#0284c7"></i>
                                <div style="font-size:12px;font-weight:700;color:#00285a">+ Upload Front Side Photos</div>
                                <div style="font-size:10.5px;color:#94a3b8">Multiple files allowed · JPG, PNG, WEBP</div>
                                <input type="file" id="frontFilesInputEdit" accept="image/jpeg,image/png,image/webp" multiple style="display:none" onchange="uploadSideFiles(this, 'front')">
                            </div>

                        @else
                            {{-- Create Mode Front Side --}}
                            <input type="hidden" name="designated_front_image_name" id="designatedFrontImageName" value="">
                            <div class="izone-compact" onclick="document.getElementById('frontFilesInput').click()">
                                <i class="bi bi-cloud-arrow-up" style="font-size:22px;color:#0284c7"></i>
                                <div style="font-size:12px;font-weight:700;color:#00285a">+ Select Front Side Photos</div>
                                <div style="font-size:10.5px;color:#94a3b8">Upload multiple images (Click or drop)</div>
                                <input type="file" id="frontFilesInput" name="front_image_files[]" accept="image/jpeg,image/png,image/webp" multiple style="display:none" onchange="previewSideFiles(this, 'front')">
                            </div>
                            <div class="new-img-queue" id="frontQueue" style="padding:0"></div>
                        @endif
                    </div>

                    {{-- ── BACK SIDE IMAGES ── --}}
                    <div class="print-side-box" id="boxBackSide">
                        <div class="print-side-header">
                            <div class="print-side-title">
                                <i class="bi bi-person-bounding-box" style="color:#7c3aed;font-size:16px"></i>
                                <span>Back Side Print Images</span>
                            </div>
                            <span class="print-side-badge" style="background:#f3e8ff;color:#7c3aed" id="backCountBadge">
                                {{ count($backPrintImages ?? []) }} File{{ count($backPrintImages ?? []) === 1 ? '' : 's' }}
                            </span>
                        </div>
                        <div style="font-size:11px;color:#64748b">
                            Multiple images supported. Select <strong>★ Main</strong> on any photo to set it as the primary back print.
                        </div>

                        {{-- In Edit mode, show existing Back Images --}}
                        @if ($isEdit)
                            <div class="side-img-grid" id="backGrid">
                                @forelse ($backPrintImages as $bImg)
                                    @php
                                        $isProductCover = ($product->image === $bImg->url);
                                    @endphp
                                    <div class="icard {{ $bImg->is_primary ? 'main' : '' }} {{ $isProductCover ? 'is-cover' : '' }}" id="bimg_{{ $bImg->id ?: 'back' }}"
                                         data-id="{{ $bImg->id ?: 'back' }}"
                                         data-url="{{ $bImg->url }}"
                                         data-source="{{ $bImg->source ?? 'media' }}"
                                         data-side="back">
                                        <img src="{{ $bImg->thumb_url ?: $bImg->url }}" alt="{{ $bImg->label }}">
                                        <div class="ibadge" style="{{ $bImg->is_primary ? '' : 'display:none;' }}">★ BACK MAIN</div>
                                        <div class="ibadge-cover" style="{{ $isProductCover ? '' : 'display:none;' }}">★ COVER</div>
                                        <div class="iact">
                                            <button type="button" class="iab iab-cover js-set-cover"
                                                    style="{{ $isProductCover ? 'display:none;' : '' }}"
                                                    onclick="setMain('{{ $bImg->id ?: 'back' }}', '{{ $bImg->url }}', '{{ $bImg->source ?? 'media' }}')">
                                                <i class="bi bi-star"></i> Cover
                                            </button>
                                            <button type="button" class="iab iab-main js-set-side-main"
                                                    style="{{ $bImg->is_primary ? 'display:none;' : '' }}"
                                                    onclick="setSideMain('back', '{{ $bImg->id ?: 'back' }}', '{{ $bImg->url }}', '{{ $bImg->source ?? 'media' }}')">
                                                <i class="bi bi-star-fill"></i> Back
                                            </button>
                                            <button type="button" class="iab iab-del"
                                                    onclick="deleteSideImage('back', '{{ $bImg->id ?: 'back' }}', '{{ $bImg->url }}', '{{ $bImg->source ?? 'media' }}')">
                                                <i class="bi bi-trash-fill"></i> Del
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="img-empty-note" id="backEmptyNote" style="grid-column:1/-1;margin:0;padding:12px;font-size:11px">
                                        No back side images uploaded yet.
                                    </div>
                                @endforelse
                            </div>

                            {{-- Dropzone / Upload for Back Side (Edit Mode) --}}
                            <div class="izone-compact" onclick="document.getElementById('backFilesInputEdit').click()">
                                <i class="bi bi-cloud-arrow-up" style="font-size:22px;color:#7c3aed"></i>
                                <div style="font-size:12px;font-weight:700;color:#00285a">+ Upload Back Side Photos</div>
                                <div style="font-size:10.5px;color:#94a3b8">Multiple files allowed · JPG, PNG, WEBP</div>
                                <input type="file" id="backFilesInputEdit" accept="image/jpeg,image/png,image/webp" multiple style="display:none" onchange="uploadSideFiles(this, 'back')">
                            </div>

                        @else
                            {{-- Create Mode Back Side --}}
                            <input type="hidden" name="designated_back_image_name" id="designatedBackImageName" value="">
                            <div class="izone-compact" onclick="document.getElementById('backFilesInput').click()">
                                <i class="bi bi-cloud-arrow-up" style="font-size:22px;color:#7c3aed"></i>
                                <div style="font-size:12px;font-weight:700;color:#00285a">+ Select Back Side Photos</div>
                                <div style="font-size:10.5px;color:#94a3b8">Upload multiple images (Click or drop)</div>
                                <input type="file" id="backFilesInput" name="back_image_files[]" accept="image/jpeg,image/png,image/webp" multiple style="display:none" onchange="previewSideFiles(this, 'back')">
                            </div>
                            <div class="new-img-queue" id="backQueue" style="padding:0"></div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 2. ALL GALLERY & COLOR-SPECIFIC IMAGES --}}
            <div class="pc">
                <div class="ph">
                    <div class="pt"><i class="bi bi-images" style="color:#00285a"></i> GALLERY & COLOR-SPECIFIC IMAGES</div>
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

                    <div class="img-manage-bar">
                        <div class="img-manage-title">
                            <span><i class="bi bi-images" style="color:#00285a"></i> Product Gallery</span>
                            <span class="img-count-badge" id="imageCountBadge">{{ count($images) }} Image{{ count($images) == 1 ? '' : 's' }}</span>
                        </div>
                        <div style="font-size:11px;color:#64748b">
                            <i class="bi bi-info-circle"></i> Hover over any image to set as <strong>★ Main</strong> or <strong>Delete</strong>
                        </div>
                    </div>

                    {{-- Image grid --}}
                    <div class="igrid" id="igrid" style="margin-top:10px">
                        @forelse ($images as $img)
                            @php
                                $isProductCover = ((bool)$img->is_primary || $product->image === $img->url);
                            @endphp
                            <div class="icard {{ $isProductCover ? 'main is-cover' : '' }}" id="img_{{ $img->id }}"
                                 data-id="{{ $img->id }}"
                                 data-url="{{ $img->url }}"
                                 data-source="{{ $img->source ?? 'media' }}"
                                 data-color-id="{{ $img->color_id ?? '' }}">
                                <img src="{{ $img->thumb_url ?: $img->url }}" alt="{{ $img->alt_text ?? 'Product image' }}">
                                <div class="ibadge" style="{{ $isProductCover ? '' : 'display:none;' }}">★ COVER</div>
                                @if (!empty($img->source_label))
                                    <div class="isource">{{ $img->source_label }}</div>
                                @endif
                                <div class="iact">
                                    <button type="button" class="iab iab-main js-set-main"
                                            style="{{ $isProductCover ? 'display:none;' : '' }}"
                                            onclick="setMain('{{ $img->id }}', '{{ $img->url }}', '{{ $img->source ?? 'media' }}')">
                                        <i class="bi bi-star-fill"></i> Cover
                                    </button>
                                    <button type="button" class="iab iab-del js-delete-image"
                                            onclick="deleteImage('{{ $img->id }}', '{{ $img->url }}', '{{ $img->source ?? 'media' }}')">
                                        <i class="bi bi-trash-fill"></i> Del
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="img-empty-note" id="imgEmptyNote" style="grid-column:1/-1">
                                No images uploaded yet. Upload images using the dropzone below.
                            </div>
                        @endforelse
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
                    <input type="hidden" name="designated_main_image_name" id="designatedMainImageName" value="">

                    <div style="padding:14px 20px 0">
                        <div style="background:#e8f5e9;color:#2e7d32;padding:10px 14px;border-radius:8px;font-size:12px;font-weight:600;display:flex;align-items:center;justify-content:space-between">
                            <div><i class="bi bi-info-circle-fill"></i> Upload images below. Select <strong>★ Main</strong> on any photo to set it as the primary cover image.</div>
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
                <button type="button" class="btn-next" onclick="goStep(4); return false;">Status & SEO <i class="bi bi-arrow-right"></i></button>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════ --}}
        {{-- STEP 4 — STATUS & SEO                           --}}
        {{-- ═══════════════════════════════════════════════ --}}
        <div class="wiz-panel" id="step4" style="display:none">

            {{-- VISIBILITY --}}
            <div class="pc">
                <div class="ph"><div class="pt">VISIBILITY</div></div>

                <input type="hidden" name="is_active" id="status_is_active" value="{{ old('is_active', $isEdit ? $product->is_active ?? true : true) ? 1 : 0 }}">
                <input type="hidden" name="is_new" id="status_is_new" value="{{ old('is_new', $isEdit ? $product->is_new ?? false : true) ? 1 : 0 }}">
                <input type="hidden" name="is_featured" id="status_is_featured" value="{{ old('is_featured', $isEdit ? $product->is_featured ?? false : false) ? 1 : 0 }}">
                <input type="hidden" name="is_trending" id="status_is_trending" value="{{ old('is_trending', $isEdit ? $product->is_trending ?? false : false) ? 1 : 0 }}">
                <input type="hidden" name="is_on_sale" id="status_is_on_sale" value="{{ old('is_on_sale', $isEdit ? $product->is_on_sale ?? false : false) ? 1 : 0 }}">

                <div class="status-grid">
                    <label class="status-toggle {{ old('is_active', $isEdit ? $product->is_active ?? true : true) ? 'on' : '' }}" id="tog_active">
                        <input type="checkbox" value="1" onchange="updateToggleStyle(this,'tog_active','status_is_active')"
                               {{ old('is_active', $isEdit ? $product->is_active ?? true : true) ? 'checked' : '' }}>
                        <span class="status-icon">✅</span>
                        <div><div class="status-name">Active</div><div class="help" style="margin:0">Visible on site</div></div>
                    </label>

                    <label class="status-toggle {{ old('is_new', $isEdit ? $product->is_new ?? false : true) ? 'on' : '' }}" id="tog_new">
                        <input type="checkbox" value="1" onchange="updateToggleStyle(this,'tog_new','status_is_new')"
                               {{ old('is_new', $isEdit ? $product->is_new ?? false : true) ? 'checked' : '' }}>
                        <span class="status-icon">🆕</span>
                        <div><div class="status-name">New Arrival</div><div class="help" style="margin:0">Shows in New Arrivals</div></div>
                    </label>

                    <label class="status-toggle {{ old('is_featured', $isEdit ? $product->is_featured ?? false : false) ? 'on' : '' }}" id="tog_featured">
                        <input type="checkbox" value="1" onchange="updateToggleStyle(this,'tog_featured','status_is_featured')"
                               {{ old('is_featured', $isEdit ? $product->is_featured ?? false : false) ? 'checked' : '' }}>
                        <span class="status-icon">⭐</span>
                        <div><div class="status-name">Featured</div><div class="help" style="margin:0">Homepage featured section</div></div>
                    </label>

                    <label class="status-toggle {{ old('is_trending', $isEdit ? $product->is_trending ?? false : false) ? 'on' : '' }}" id="tog_trending">
                        <input type="checkbox" value="1" onchange="updateToggleStyle(this,'tog_trending','status_is_trending')"
                               {{ old('is_trending', $isEdit ? $product->is_trending ?? false : false) ? 'checked' : '' }}>
                        <span class="status-icon">🔥</span>
                        <div><div class="status-name">Trending</div><div class="help" style="margin:0">Shows in Trending Now</div></div>
                    </label>

                    <label class="status-toggle {{ old('is_on_sale', $isEdit ? $product->is_on_sale ?? false : false) ? 'on' : '' }}" id="tog_sale">
                        <input type="checkbox" value="1" onchange="updateToggleStyle(this,'tog_sale','status_is_on_sale')"
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

{{-- Quick Add Color Modal --}}
<div class="modal-bg" id="quickColorModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:99999;align-items:center;justify-content:center;padding:16px">
    <div style="background:#fff;border-radius:16px;padding:24px;width:100%;max-width:440px;box-shadow:0 20px 60px rgba(0,0,0,.2)">
        <div style="font-size:16px;font-weight:800;color:#00285a;margin-bottom:16px;display:flex;align-items:center;gap:8px">
            <i class="bi bi-palette-fill" style="color:#0284c7"></i> Add New Color
        </div>
        <div class="fgrp" style="margin-bottom:12px">
            <label>Color Name *</label>
            <input type="text" id="qcName" class="fc" placeholder="e.g. Sage Green, Maroon">
        </div>
        <div class="fgrp" style="margin-bottom:12px">
            <label>Hex Code</label>
            <div style="display:flex;gap:8px;align-items:center">
                <input type="color" id="qcHexPicker" value="#000000" style="width:44px;height:40px;border:1px solid #cbd5e1;border-radius:8px;cursor:pointer;padding:2px" oninput="document.getElementById('qcHex').value=this.value">
                <input type="text" id="qcHex" class="fc" placeholder="#000000" maxlength="7" value="#000000" oninput="if(/^#[0-9A-Fa-f]{6}$/.test(this.value)){document.getElementById('qcHexPicker').value=this.value}">
            </div>
        </div>
        <div class="fgrp" style="margin-bottom:16px">
            <label>T-Shirt Mockup / Photo</label>
            <div style="display:flex;align-items:center;gap:10px">
                <div id="qcImgWrap" style="width:48px;height:48px;border-radius:8px;border:1.5px dashed #cbd5e1;background:#f8fafc;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0">
                    <img id="qcImgPreview" src="" alt="Preview" style="width:100%;height:100%;object-fit:cover;display:none">
                    <i id="qcImgPlaceholder" class="bi bi-image" style="font-size:18px;color:#94a3b8"></i>
                </div>
                <div style="flex:1">
                    <input type="file" id="qcImage" accept="image/jpeg,image/png,image/webp" onchange="previewQuickColorImg(this)" style="font-size:12px;width:100%">
                    <div style="font-size:10.5px;color:#94a3b8;margin-top:2px">Base t-shirt photo for this color (optional)</div>
                </div>
            </div>
        </div>
        <div id="qcError" style="color:#c62828;font-size:12px;margin-bottom:12px;display:none;background:#fce4ec;padding:8px 12px;border-radius:6px"></div>
        <div style="display:flex;justify-content:flex-end;gap:8px">
            <button type="button" class="btn btn-back" onclick="closeQuickColorModal()" style="padding:8px 18px">Cancel</button>
            <button type="button" class="btn btn-next" id="btnSaveQuickColor" onclick="saveQuickColor()" style="padding:8px 18px">
                <i class="bi bi-check-lg"></i> Save &amp; Use Color
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// ── Data from PHP ────────────────────────────────────────
var varIdx = {{ $isEdit && ($product->has_variants ?? false) && $variants->count() ? $variants->count() : 0 }};
var CSRF   = document.querySelector('meta[name="csrf-token"]').content;
var PRODUCT_ID = {{ $isEdit ? $product->id : 'null' }};
var sizes  = {!! json_encode($sizesArray->pluck('name')->values()) !!};
var colors = {!! json_encode($colorsArray->map(fn($c) => ['id'=>$c->id, 'name'=>$c->name, 'hex'=>$c->hex_code, 'image'=>$c->image])->values()) !!};

// ── Current wizard step ──────────────────────────────────
var currentStep = 1;

// Jump to a step (with light validation on forward navigation)
function goStep(n) {
    if (n > currentStep && !validateStep(currentStep)) return;

    document.querySelectorAll('.wiz-panel').forEach(function(p) { p.style.display = 'none'; });
    var targetPanel = document.getElementById('step' + n);
    if (!targetPanel) {
        return;
    }
    targetPanel.style.display = 'block';

    // Update nav
    for (var i = 1; i <= 4; i++) {
        var nav = document.getElementById('wn' + i);
        var num = document.getElementById('wnum' + i);
        if (!nav || !num) continue;
        nav.className = 'wiz-step';
        if (i < n) { nav.classList.add('done'); num.innerHTML = '<i class="bi bi-check-lg"></i>'; }
        else if (i === n) { nav.classList.add('active'); num.textContent = i; }
        else { num.textContent = i; }
    }

    currentStep = n;
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Sync image tabs when entering step 3
    if (n === 3 && typeof syncImageTabs === 'function') syncImageTabs();
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

function toggleAllSizesForColor(colorName) {
    var block = document.getElementById('sblock_' + CSS.escape(colorName));
    if (!block) return;
    var pills = block.querySelectorAll('.size-pill');
    var anyUnchecked = Array.from(pills).some(function(p) {
        var inp = p.querySelector('input');
        return inp && !inp.checked;
    });
    pills.forEach(function(p) {
        var inp = p.querySelector('input');
        if (inp) {
            inp.checked = anyUnchecked;
            p.classList.toggle('selected', anyUnchecked);
        }
    });
}

function updateVariantRowSku(el) {
    var tr = el.closest('tr');
    if (!tr) return;
    var skuInput = tr.querySelector('[name*="[sku]"]');
    var sizeSel = tr.querySelector('[name*="[size]"]');
    var colorSel = tr.querySelector('[name*="[color]"]');
    if (!skuInput || skuInput.dataset.manualEdit === '1') return;

    var baseSku = document.getElementById('fieldSku')?.value.trim().toUpperCase() || 'PRD';
    var colorCode = (colorSel?.value || '').toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 4);
    var sizeCode = (sizeSel?.value || '').toUpperCase().replace(/[^A-Z0-9]/g, '');
    skuInput.value = baseSku + (colorCode ? '-' + colorCode : '') + (sizeCode ? '-' + sizeCode : '');
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

    var baseSku = document.getElementById('fieldSku')?.value.trim().toUpperCase() || 'PRD';
    var colorCode = (colorName || '').toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 4);
    var sizeCode = (sizeName || '').toUpperCase().replace(/[^A-Z0-9]/g, '');
    var rowSku = baseSku + (colorCode ? '-' + colorCode : '') + (sizeCode ? '-' + sizeCode : '');

    tr.innerHTML = `
        <td><select class="vi" name="variants[${i}][size]" style="width:100%" onchange="updateVariantRowSku(this)">${sizeOpts}</select></td>
        <td><select class="vi" name="variants[${i}][color]" style="width:100%" onchange="updateSwatch(this);updateVariantRowSku(this);syncImageTabs()">${colorOpts}</select></td>
        <td><input class="ci" type="color" name="variants[${i}][color_hex]" value="${colorHex}"></td>
        <td><input class="vi" type="number" name="variants[${i}][price]" placeholder="Price" min="0" step="0.01" required></td>
        <td><input class="vi" type="number" name="variants[${i}][original_price]" placeholder="MRP" min="0" step="0.01"></td>
        <td><input class="vi" type="number" name="variants[${i}][cost_price]" placeholder="Cost" min="0" step="0.01"></td>
        <td><input class="vi" type="number" name="variants[${i}][stock]" placeholder="Qty" min="0" required></td>
        <td><input class="vi" type="text" name="variants[${i}][sku]" value="${rowSku}" placeholder="SKU" oninput="this.dataset.manualEdit='1'"></td>
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
function updateToggleStyle(input, id, hiddenId) {
    var wrap = document.getElementById(id);
    if (wrap) wrap.classList.toggle('on', input.checked);
    var hidden = hiddenId ? document.getElementById(hiddenId) : null;
    if (hidden) hidden.value = input.checked ? '1' : '0';
}

// ── Print Sides (Front & Back) Management ─────────────────
var _sideTransfers = { front: new DataTransfer(), back: new DataTransfer() };

function selectPrintSides(mode) {
    var hidden = document.getElementById('fieldPrintSides');
    if (hidden) hidden.value = mode;

    document.querySelectorAll('.print-side-pill').forEach(function(p) { p.classList.remove('active'); });
    var pill = document.getElementById('pill_' + mode);
    if (pill) pill.classList.add('active');

    var boxF = document.getElementById('boxFrontSide');
    var boxB = document.getElementById('boxBackSide');
    if (boxF && boxB) {
        if (mode === 'front_only') {
            boxF.style.opacity = '1';
            boxF.style.filter = 'none';
            boxB.style.opacity = '0.45';
            boxB.style.filter = 'grayscale(40%)';
        } else if (mode === 'back_only') {
            boxB.style.opacity = '1';
            boxB.style.filter = 'none';
            boxF.style.opacity = '0.45';
            boxF.style.filter = 'grayscale(40%)';
        } else {
            boxF.style.opacity = '1';
            boxF.style.filter = 'none';
            boxB.style.opacity = '1';
            boxB.style.filter = 'none';
        }
    }
}

function setSideMain(side, id, url, source) {
    if (!url) {
        var card = document.getElementById((side === 'front' ? 'fimg_' : 'bimg_') + id);
        if (card) url = card.getAttribute('data-url');
        if (!source && card) source = card.getAttribute('data-source') || 'media';
    }

    var setMainRoute = '{{ $isEdit ? route('admin.products.set-main-image', $product->id) : '' }}';
    if (!setMainRoute) return;

    fetch(setMainRoute, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ url: url, image_id: id, source: source, target: side })
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (!res.success) {
            showToast(res.message || 'Failed to update', 'error');
            return;
        }

        var gridId = side === 'front' ? 'frontGrid' : 'backGrid';
        var prefix = side === 'front' ? 'fimg_' : 'bimg_';
        var badgeText = side === 'front' ? '★ FRONT MAIN' : '★ BACK MAIN';

        document.querySelectorAll('#' + gridId + ' .icard').forEach(function(c) {
            c.classList.remove('main');
            var b = c.querySelector('.ibadge');
            if (b) b.style.display = 'none';
            var mainBtn = c.querySelector('.js-set-side-main');
            if (mainBtn) mainBtn.style.display = '';
        });

        var card = document.getElementById(prefix + id);
        if (card) {
            card.classList.add('main');
            var b = card.querySelector('.ibadge');
            if (b) {
                b.textContent = badgeText;
                b.style.display = '';
            } else {
                card.insertAdjacentHTML('afterbegin', '<div class="ibadge">' + badgeText + '</div>');
            }
            var mainBtn = card.querySelector('.js-set-side-main');
            if (mainBtn) mainBtn.style.display = 'none';
        }

        showToast(res.message || '★ Primary ' + side + ' image updated!', 'success');
    })
    .catch(function(err) {
        showToast('Error: ' + err.message, 'error');
    });
}

function deleteSideImage(side, id, url, source) {
    if (!confirm('Delete this ' + side + ' side image?')) return;

    if (!url) {
        var card = document.getElementById((side === 'front' ? 'fimg_' : 'bimg_') + id);
        if (card) url = card.getAttribute('data-url');
        if (!source && card) source = card.getAttribute('data-source') || 'media';
    }

    var delRoute = '{{ $isEdit ? route('admin.products.delete-image', $product->id) : '' }}';
    if (!delRoute) return;

    fetch(delRoute, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ url: url, image_id: id, source: source, collection: side + '_print' })
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (!res.success) {
            showToast(res.message || 'Failed to delete image', 'error');
            return;
        }

        var prefix = side === 'front' ? 'fimg_' : 'bimg_';
        var gridId = side === 'front' ? 'frontGrid' : 'backGrid';
        var countBadgeId = side === 'front' ? 'frontCountBadge' : 'backCountBadge';
        var emptyNoteId = side === 'front' ? 'frontEmptyNote' : 'backEmptyNote';

        var card = document.getElementById(prefix + id);
        if (card) {
            card.style.transition = 'transform .2s, opacity .2s';
            card.style.transform = 'scale(0.8)';
            card.style.opacity = '0';
            setTimeout(function() {
                card.remove();
                var remaining = document.querySelectorAll('#' + gridId + ' .icard').length;
                var badge = document.getElementById(countBadgeId);
                if (badge) badge.textContent = remaining + ' File' + (remaining === 1 ? '' : 's');
                if (remaining === 0) {
                    var grid = document.getElementById(gridId);
                    if (grid && !document.getElementById(emptyNoteId)) {
                        grid.innerHTML = '<div class="img-empty-note" id="' + emptyNoteId + '" style="grid-column:1/-1;margin:0;padding:12px;font-size:11px">No ' + side + ' side images uploaded yet.</div>';
                    }
                }
            }, 200);
        }

        showToast(side.toUpperCase() + ' image deleted ✓', 'success');
    })
    .catch(function(err) {
        showToast('Error: ' + err.message, 'error');
    });
}

function uploadSideFiles(input, side) {
    var files = Array.from(input.files || []).filter(function(f) { return f.type.startsWith('image/'); });
    if (!files.length) return;

    var total = files.length, done = 0;
    showToast('Uploading ' + total + ' ' + side + ' image(s)…', 'info');

    var collection = side + '_print';
    var gridId = side === 'front' ? 'frontGrid' : 'backGrid';
    var countBadgeId = side === 'front' ? 'frontCountBadge' : 'backCountBadge';
    var emptyNoteId = side === 'front' ? 'frontEmptyNote' : 'backEmptyNote';
    var prefix = side === 'front' ? 'fimg_' : 'bimg_';
    var badgeText = side === 'front' ? '★ FRONT MAIN' : '★ BACK MAIN';

    files.forEach(function(file) {
        if (file.size > 5 * 1024 * 1024) {
            showToast(file.name + ' too large (max 5MB)', 'error');
            done++; return;
        }

        var fd = new FormData();
        fd.append('file', file);
        fd.append('model_type', 'product');
        fd.append('model_id', PRODUCT_ID);
        fd.append('collection', collection);
        fd.append('alt_text', side === 'front' ? 'Front Print' : 'Back Print');
        var isFirst = document.querySelectorAll('#' + gridId + ' .icard').length === 0;
        fd.append('is_primary', isFirst ? '1' : '0');

        fetch('{{ route('admin.media.upload') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF },
            body: fd
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            done++;
            if (data.success) {
                var emptyNote = document.getElementById(emptyNoteId);
                if (emptyNote) emptyNote.remove();

                var grid = document.getElementById(gridId);
                var media = data.media;
                var isPrimary = media.is_primary || isFirst;

                var card = document.createElement('div');
                card.className = 'icard' + (isPrimary ? ' main' : '');
                card.id = prefix + media.id;
                card.setAttribute('data-id', media.id);
                card.setAttribute('data-url', media.url);
                card.setAttribute('data-source', 'media');
                card.setAttribute('data-side', side);

                var badgeStyle = isPrimary ? '' : 'display:none;';
                var mainBtnStyle = isPrimary ? 'display:none;' : '';

                card.innerHTML = `
                    <img src="${media.thumb_url || media.url}" alt="${media.alt_text || side}">
                    <div class="ibadge" style="${badgeStyle}">${badgeText}</div>
                    <div class="iact">
                        <button type="button" class="iab iab-main js-set-side-main" style="${mainBtnStyle}" onclick="setSideMain('${side}', '${media.id}', '${media.url}', 'media')">
                            <i class="bi bi-star-fill"></i> Main
                        </button>
                        <button type="button" class="iab iab-del" onclick="deleteSideImage('${side}', '${media.id}', '${media.url}', 'media')">
                            <i class="bi bi-trash-fill"></i> Del
                        </button>
                    </div>
                `;
                grid.appendChild(card);

                var badge = document.getElementById(countBadgeId);
                if (badge) {
                    var totalCards = grid.querySelectorAll('.icard').length;
                    badge.textContent = totalCards + ' File' + (totalCards === 1 ? '' : 's');
                }

                if (typeof addImageCard === 'function') {
                    addImageCard(media);
                }

                showToast(side.toUpperCase() + ' image uploaded ✓', 'success');
            } else {
                showToast('Upload failed: ' + (data.message || 'Unknown'), 'error');
            }
        })
        .catch(function(err) {
            done++;
            showToast('Error: ' + err.message, 'error');
        });
    });

    input.value = '';
}

function previewSideFiles(input, side) {
    var queue = document.getElementById(side + 'Queue');
    if (!queue) return;

    var dt = _sideTransfers[side];
    var newFiles = Array.from(input.files || []);

    newFiles.forEach(function(file) {
        if (!file.type.startsWith('image/')) return;

        for (var i = 0; i < dt.items.length; i++) {
            var ex = dt.items[i].getAsFile();
            if (ex && ex.name === file.name && ex.size === file.size) return;
        }

        dt.items.add(file);

        var thumb = document.createElement('div');
        thumb.className = 'new-img-thumb';
        thumb.setAttribute('data-file-name', file.name);

        var hiddenSideMain = document.getElementById('designated' + (side === 'front' ? 'Front' : 'Back') + 'ImageName');
        var isSideMain = (hiddenSideMain && hiddenSideMain.value === file.name);
        if (hiddenSideMain && !hiddenSideMain.value) {
            hiddenSideMain.value = file.name;
            isSideMain = true;
        }
        if (isSideMain) thumb.classList.add('main');

        var hiddenCover = document.getElementById('designatedMainImageName');
        var isCover = (hiddenCover && hiddenCover.value === file.name);
        if (hiddenCover && !hiddenCover.value && side === 'front' && isSideMain) {
            hiddenCover.value = file.name;
            isCover = true;
        }
        if (isCover) thumb.classList.add('is-cover');

        var img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.onload = function() { URL.revokeObjectURL(img.src); };

        var badge = document.createElement('div');
        badge.className = 'new-img-badge';
        badge.textContent = side === 'front' ? '★ FRONT MAIN' : '★ BACK MAIN';
        badge.style.display = isSideMain ? 'block' : 'none';

        var coverBadge = document.createElement('div');
        coverBadge.className = 'new-img-badge-cover';
        coverBadge.textContent = '★ COVER';
        coverBadge.style.display = isCover ? 'block' : 'none';

        var act = document.createElement('div');
        act.className = 'new-img-act';

        var btnCover = document.createElement('button');
        btnCover.type = 'button';
        btnCover.className = 'new-img-btn-cover';
        btnCover.innerHTML = '<i class="bi bi-star"></i> Cover';
        btnCover.style.display = isCover ? 'none' : '';
        btnCover.onclick = function() {
            setNewDesignatedMain(file.name);
        };

        var btnSideMain = document.createElement('button');
        btnSideMain.type = 'button';
        btnSideMain.className = 'new-img-btn-main';
        btnSideMain.innerHTML = '<i class="bi bi-star-fill"></i> ' + (side === 'front' ? 'Front' : 'Back');
        btnSideMain.style.display = isSideMain ? 'none' : '';
        btnSideMain.onclick = function() {
            setDesignatedSideMain(side, file.name);
        };

        act.appendChild(btnCover);
        act.appendChild(btnSideMain);

        var rm = document.createElement('button');
        rm.type = 'button';
        rm.className = 'new-img-rm';
        rm.innerHTML = '×';
        rm.title = 'Remove image';
        rm.onclick = function() {
            removeSideFile(input, side, file.name, thumb);
        };

        thumb.appendChild(img);
        thumb.appendChild(badge);
        thumb.appendChild(coverBadge);
        thumb.appendChild(act);
        thumb.appendChild(rm);
        queue.appendChild(thumb);
    });

    input.files = dt.files;
    var countBadge = document.getElementById(side + 'CountBadge');
    if (countBadge) {
        countBadge.textContent = dt.files.length + ' File' + (dt.files.length === 1 ? '' : 's');
    }
}

function setDesignatedSideMain(side, fileName) {
    var hiddenMain = document.getElementById('designated' + (side === 'front' ? 'Front' : 'Back') + 'ImageName');
    if (hiddenMain) hiddenMain.value = fileName;

    var queue = document.getElementById(side + 'Queue');
    if (queue) {
        queue.querySelectorAll('.new-img-thumb').forEach(function(t) {
            var match = (t.getAttribute('data-file-name') === fileName);
            t.classList.toggle('main', match);
            var b = t.querySelector('.new-img-badge');
            if (b) b.style.display = match ? 'block' : 'none';
            var btn = t.querySelector('.new-img-btn-main');
            if (btn) btn.style.display = match ? 'none' : '';
        });
    }
    showToast('★ ' + side.toUpperCase() + ' primary set to ' + fileName, 'success');
}

function removeSideFile(input, side, fileName, thumbElement) {
    var dt = _sideTransfers[side];
    if (dt) {
        var newDt = new DataTransfer();
        for (var i = 0; i < dt.items.length; i++) {
            var f = dt.items[i].getAsFile();
            if (f && f.name !== fileName) {
                newDt.items.add(f);
            }
        }
        _sideTransfers[side] = newDt;
        input.files = newDt.files;
    }

    var wasSideMain = thumbElement.classList.contains('main');
    var wasCover = thumbElement.classList.contains('is-cover');
    thumbElement.remove();

    var countBadge = document.getElementById(side + 'CountBadge');
    if (countBadge && dt) {
        countBadge.textContent = dt.files.length + ' File' + (dt.files.length === 1 ? '' : 's');
    }

    if (wasSideMain) {
        var queue = document.getElementById(side + 'Queue');
        var firstRemaining = queue ? queue.querySelector('.new-img-thumb') : null;
        if (firstRemaining) {
            setDesignatedSideMain(side, firstRemaining.getAttribute('data-file-name'));
        } else {
            var hiddenMain = document.getElementById('designated' + (side === 'front' ? 'Front' : 'Back') + 'ImageName');
            if (hiddenMain) hiddenMain.value = '';
        }
    }

    if (wasCover) {
        var anyFirstThumb = document.querySelector('.new-img-thumb:not([data-color-id]), .new-img-thumb[data-color-id=""]');
        if (anyFirstThumb) {
            setNewDesignatedMain(anyFirstThumb.getAttribute('data-file-name'));
        } else {
            var hiddenCover = document.getElementById('designatedMainImageName');
            if (hiddenCover) hiddenCover.value = '';
        }
    }
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

// ── SKU Generation & Validation ───────────────────────────
var skuCheckTimeout = null;

function fillProductSku() {
    var nameVal = document.getElementById('fieldName')?.value || '';
    var catVal  = document.getElementById('fieldCategory')?.value || '';
    var prodId  = typeof PRODUCT_ID !== 'undefined' ? PRODUCT_ID : '';

    var btn = document.getElementById('btnAutoSku');
    var spinner = document.getElementById('skuSpinner');
    var skuInput = document.getElementById('fieldSku');

    if (btn) btn.disabled = true;
    if (spinner) spinner.style.display = 'block';

    var url = '{{ route('admin.products.generate-sku') }}?category_id=' + encodeURIComponent(catVal) +
              '&name=' + encodeURIComponent(nameVal) +
              (prodId ? '&product_id=' + encodeURIComponent(prodId) : '');

    fetch(url, { headers: { 'Accept': 'application/json' } })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (btn) btn.disabled = false;
            if (spinner) spinner.style.display = 'none';

            if (data.sku) {
                if (skuInput) {
                    skuInput.value = data.sku;
                    checkSkuAvailability(data.sku);
                }
                showToast('Unique SKU generated: ' + data.sku + ' ✓', 'success');
                syncVariantSkus(data.sku);
            }
        })
        .catch(function(err) {
            if (btn) btn.disabled = false;
            if (spinner) spinner.style.display = 'none';
            showToast('Error generating SKU', 'error');
        });
}

function debounceCheckSku(val) {
    clearTimeout(skuCheckTimeout);
    var sku = (val || '').trim().toUpperCase();
    var skuInput = document.getElementById('fieldSku');
    if (skuInput && skuInput.value !== sku) {
        skuInput.value = sku;
    }

    if (!sku) {
        var fb = document.getElementById('skuFeedback');
        if (fb) { fb.style.display = 'none'; fb.textContent = ''; }
        return;
    }

    skuCheckTimeout = setTimeout(function() {
        checkSkuAvailability(sku);
    }, 350);
}

function checkSkuAvailability(sku) {
    var fb = document.getElementById('skuFeedback');
    var spinner = document.getElementById('skuSpinner');
    var prodId = typeof PRODUCT_ID !== 'undefined' ? PRODUCT_ID : '';

    if (spinner) spinner.style.display = 'block';

    var url = '{{ route('admin.products.check-sku') }}?sku=' + encodeURIComponent(sku) +
              (prodId ? '&product_id=' + encodeURIComponent(prodId) : '');

    fetch(url, { headers: { 'Accept': 'application/json' } })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (spinner) spinner.style.display = 'none';
            if (!fb) return;

            fb.style.display = 'block';
            if (data.exists) {
                fb.style.color = '#dc2626';
                fb.innerHTML = '<i class="bi bi-x-circle-fill"></i> SKU "' + sku + '" already exists! Click "Auto SKU" to auto-increment.';
            } else {
                fb.style.color = '#16a34a';
                fb.innerHTML = '<i class="bi bi-check-circle-fill"></i> SKU "' + sku + '" is 100% unique & available!';
            }
        })
        .catch(function() {
            if (spinner) spinner.style.display = 'none';
        });
}

function syncVariantSkus(baseSku) {
    if (!baseSku) return;
    var rows = document.querySelectorAll('#varBody .vrow');
    rows.forEach(function(tr) {
        var sizeSel = tr.querySelector('[name*="[size]"]');
        var colorSel = tr.querySelector('[name*="[color]"]');
        var skuInput = tr.querySelector('[name*="[sku]"]');
        if (skuInput && !skuInput.value && sizeSel && colorSel) {
            var size = sizeSel.value ? sizeSel.value.toUpperCase().replace(/[^A-Z0-9]/g, '') : '';
            var color = colorSel.value ? colorSel.value.toUpperCase().replace(/[^A-Z0-9]/g, '') : '';
            if (size && color) {
                skuInput.value = baseSku + '-' + color.substring(0, 3) + '-' + size;
            }
        }
    });
}

// ── Image Tabs (Create mode) ──────────────────────────────
var _fileTransfers = {};

function previewNewImages(input, colorId) {
    var queue = document.getElementById('newqueue_' + colorId);
    if (!queue) return;

    var key = colorId || '0';
    if (!_fileTransfers[key]) {
        _fileTransfers[key] = new DataTransfer();
    }

    var dt = _fileTransfers[key];
    var newFiles = Array.from(input.files || []);

    newFiles.forEach(function(file) {
        if (!file.type.startsWith('image/')) return;

        // Check if already added
        for (var i = 0; i < dt.items.length; i++) {
            var existing = dt.items[i].getAsFile();
            if (existing && existing.name === file.name && existing.size === file.size) {
                return;
            }
        }

        dt.items.add(file);

        var thumb = document.createElement('div');
        thumb.className = 'new-img-thumb';
        thumb.setAttribute('data-file-name', file.name);
        thumb.setAttribute('data-color-id', colorId || '');

        var mainInput = document.getElementById('designatedMainImageName');
        var isDesignatedMain = (mainInput && mainInput.value === file.name);
        if (!colorId && mainInput && !mainInput.value) {
            mainInput.value = file.name;
            isDesignatedMain = true;
        }
        if (colorId) {
            isDesignatedMain = false;
        }

        if (isDesignatedMain) {
            thumb.classList.add('is-cover', 'main');
        }

        var img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.onload = function() { URL.revokeObjectURL(img.src); };

        var badge = document.createElement('div');
        badge.className = 'new-img-badge';
        badge.textContent = '★ COVER';
        badge.style.display = isDesignatedMain ? 'block' : 'none';

        var act = document.createElement('div');
        act.className = 'new-img-act';
        var mainBtn = document.createElement('button');
        mainBtn.type = 'button';
        mainBtn.className = 'new-img-btn-main';
        mainBtn.style.flex = '1';
        mainBtn.style.display = colorId || isDesignatedMain ? 'none' : '';
        mainBtn.innerHTML = '<i class="bi bi-star-fill"></i> Set Cover';
        mainBtn.onclick = function() {
            setNewDesignatedMain(file.name);
        };
        act.appendChild(mainBtn);

        var rm = document.createElement('button');
        rm.type = 'button';
        rm.className = 'new-img-rm';
        rm.innerHTML = '×';
        rm.title = 'Remove image';
        rm.onclick = function() {
            removeNewFile(input, key, file.name, thumb);
        };

        thumb.appendChild(img);
        thumb.appendChild(badge);
        thumb.appendChild(act);
        thumb.appendChild(rm);
        queue.appendChild(thumb);
    });

    input.files = dt.files;
    if (colorId) {
        renderStep2ColorQueue(colorId);
    }
}

function setNewDesignatedMain(fileName) {
    var targetThumb = Array.from(document.querySelectorAll('.new-img-thumb')).find(function(t) {
        return t.getAttribute('data-file-name') === fileName && !t.getAttribute('data-color-id');
    });
    if (!targetThumb) {
        return;
    }

    var mainInput = document.getElementById('designatedMainImageName');
    if (mainInput) mainInput.value = fileName;

    document.querySelectorAll('.new-img-thumb').forEach(function(t) {
        var isThis = (t.getAttribute('data-file-name') === fileName && !t.getAttribute('data-color-id'));
        t.classList.toggle('is-cover', isThis);

        // General/color queue badge
        var b = t.querySelector('.new-img-badge');
        if (b && !t.closest('#frontQueue') && !t.closest('#backQueue')) {
            b.textContent = '★ COVER';
            b.style.display = isThis ? 'block' : 'none';
        }

        // Side queues cover badge
        var cb = t.querySelector('.new-img-badge-cover');
        if (cb) cb.style.display = isThis ? 'block' : 'none';

        var btnCover = t.querySelector('.new-img-btn-cover');
        if (btnCover) btnCover.style.display = isThis ? 'none' : '';

        var btnMain = t.querySelector('.new-img-btn-main');
        if (btnMain && !t.closest('#frontQueue') && !t.closest('#backQueue')) {
            btnMain.style.display = isThis ? 'none' : '';
        }
    });

    document.querySelectorAll('[id^="step2_colorQueue_"] .new-img-thumb').forEach(function(t) {
        var isThis = false;
        t.classList.toggle('is-cover', isThis);
        t.classList.toggle('main', isThis);
        var b = t.querySelector('.new-img-badge');
        if (b) b.style.display = isThis ? 'block' : 'none';
        var btnCover = t.querySelector('.new-img-btn-cover');
        if (btnCover) btnCover.style.display = isThis ? 'none' : '';
    });

    showToast('★ Cover image set to ' + fileName, 'success');
}

function removeNewFile(input, key, fileName, thumbElement) {
    var dt = _fileTransfers[key];
    if (dt) {
        var newDt = new DataTransfer();
        for (var i = 0; i < dt.items.length; i++) {
            var f = dt.items[i].getAsFile();
            if (f && f.name !== fileName) {
                newDt.items.add(f);
            }
        }
        _fileTransfers[key] = newDt;
        input.files = newDt.files;
    }

    if (key && key !== '0') {
        var step2Queue = document.getElementById('step2_colorQueue_' + key);
        if (step2Queue) {
            var step2Thumb = step2Queue.querySelector('.new-img-thumb[data-file-name="' + CSS.escape(fileName) + '"]');
            if (step2Thumb) step2Thumb.remove();
        }
    }

    var wasCover = thumbElement.classList.contains('is-cover') || thumbElement.classList.contains('main');
    thumbElement.remove();

    if (wasCover) {
        var anyFirstThumb = document.querySelector('.new-img-thumb:not([data-color-id]), .new-img-thumb[data-color-id=""]');
        if (anyFirstThumb) {
            setNewDesignatedMain(anyFirstThumb.getAttribute('data-file-name'));
        } else {
            var mainInput = document.getElementById('designatedMainImageName');
            if (mainInput) mainInput.value = '';
        }
    }
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

if (dropzone && fileInput) {
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
}

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
        fd.append('is_primary', !window._currentUploadColorId && document.querySelectorAll('#igrid .icard').length === 0 ? '1' : '0');
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
    var imgUrl = media.thumb_url || media.url;

    if (media.color_id) {
        var cGrid = document.getElementById('colorGrid_' + media.color_id);
        if (cGrid && !document.getElementById('cimg_' + media.id)) {
            var cCard = document.createElement('div');
            cCard.className = 'icard' + (media.is_primary ? ' main is-cover' : '');
            cCard.id = 'cimg_' + media.id;
            cCard.setAttribute('data-id', media.id);
            cCard.setAttribute('data-url', media.url);
            cCard.style.aspectRatio = '1';
            cCard.style.borderRadius = '8px';
            cCard.innerHTML = `
                <img src="${imgUrl}" alt="${media.alt_text || 'Color photo'}">
                <div class="ibadge-cover" ${media.is_primary ? '' : 'style="display:none;"'}>â˜… COVER</div>
                <div class="iact" style="padding:2px">
                    <button type="button" class="iab iab-cover js-set-cover" ${media.is_primary ? 'style="display:none;"' : ''} style="font-size:8.5px;padding:2px" onclick="setMain('${media.id}', '${media.url}', 'product_image')">
                        Cover
                    </button>
                    <button type="button" class="iab iab-del" style="font-size:8.5px;padding:2px" onclick="deleteColorImageAjax('${media.id}', '${media.url}', ${media.color_id})">
                        Del
                    </button>
                </div>
            `;
            cGrid.appendChild(cCard);
        }
        return;
    }

    var emptyNote = document.getElementById('imgEmptyNote');
    if (emptyNote) emptyNote.remove();

    var grid = document.getElementById('igrid');
    var isFirst = grid.querySelectorAll('.icard').length === 0;
    var isMain = media.is_primary || isFirst;

    var div  = document.createElement('div');
    div.className = 'icard' + (isMain ? ' main' : '');
    div.id = 'img_' + media.id;
    div.setAttribute('data-id', media.id);
    div.setAttribute('data-url', media.url);
    div.setAttribute('data-source', 'media');
    div.setAttribute('data-color-id', media.color_id || '');

    var mainDisplay = isMain ? 'style="display:none;"' : '';
    var badgeDisplay = isMain ? '' : 'style="display:none;"';

    div.innerHTML = `
        <img src="${imgUrl}" alt="${media.alt_text || 'Product image'}">
        <div class="ibadge" ${badgeDisplay}>★ COVER</div>
        <div class="iact">
            <button type="button" class="iab iab-main js-set-main" ${mainDisplay} onclick="setMain('${media.id}', '${media.url}', 'media')">
                <i class="bi bi-star-fill"></i> Cover
            </button>
            <button type="button" class="iab iab-del js-delete-image" onclick="deleteImage('${media.id}', '${media.url}', 'media')">
                <i class="bi bi-trash-fill"></i> Del
            </button>
        </div>`;
    grid.appendChild(div);

    if (media.color_id) {
        var cGrid = document.getElementById('colorGrid_' + media.color_id);
        if (cGrid && !document.getElementById('cimg_' + media.id)) {
            var cCard = document.createElement('div');
            cCard.className = 'icard' + (isMain ? ' main is-cover' : '');
            cCard.id = 'cimg_' + media.id;
            cCard.setAttribute('data-id', media.id);
            cCard.setAttribute('data-url', media.url);
            cCard.style.aspectRatio = '1';
            cCard.style.borderRadius = '8px';
            cCard.innerHTML = `
                <img src="${imgUrl}" alt="${media.alt_text || 'Color photo'}">
                <div class="ibadge-cover" ${isMain ? '' : 'style="display:none;"'}>★ COVER</div>
                <div class="iact" style="padding:2px">
                    <button type="button" class="iab iab-cover js-set-cover" ${isMain ? 'style="display:none;"' : ''} style="font-size:8.5px;padding:2px" onclick="setMain('${media.id}', '${media.url}', 'product_image')">
                        Cover
                    </button>
                    <button type="button" class="iab iab-del" style="font-size:8.5px;padding:2px" onclick="deleteColorImageAjax('${media.id}', '${media.url}', ${media.color_id})">
                        Del
                    </button>
                </div>
            `;
            cGrid.appendChild(cCard);
        }
    }

    var countBadge = document.getElementById('imageCountBadge');
    if (countBadge) {
        var total = grid.querySelectorAll('.icard').length;
        countBadge.textContent = total + ' Image' + (total === 1 ? '' : 's');
    }
}

function setMain(id, url, source) {
    if (!url) {
        var card = document.getElementById('img_' + id) || document.getElementById('fimg_' + id) || document.getElementById('bimg_' + id) || document.getElementById('cimg_' + id);
        if (card) url = card.getAttribute('data-url');
        if (!source && card) source = card.getAttribute('data-source') || 'media';
    }

    var setMainRoute = '{{ $isEdit ? route('admin.products.set-main-image', $product->id) : '' }}';
    if (!setMainRoute) return;

    fetch(setMainRoute, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ url: url, image_id: id, source: source, target: 'main' })
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (!res.success) {
            showToast(res.message || 'Failed to set main cover image', 'error');
            return;
        }

        // Reset all cards across igrid, frontGrid, backGrid, color grids
        document.querySelectorAll('.icard').forEach(function(c) {
            c.classList.remove('is-cover');
            var coverBadge = c.querySelector('.ibadge-cover');
            if (coverBadge) coverBadge.style.display = 'none';
            var coverBtn = c.querySelector('.js-set-cover');
            if (coverBtn) coverBtn.style.display = '';
        });
        document.querySelectorAll('#igrid .icard').forEach(function(c) {
            c.classList.remove('main');
            var b = c.querySelector('.ibadge');
            if (b) b.style.display = 'none';
            var mainBtn = c.querySelector('.js-set-main');
            if (mainBtn) mainBtn.style.display = '';
        });

        // Activate clicked card and any matching URL cards
        var card = document.getElementById('img_' + id) || document.getElementById('fimg_' + id) || document.getElementById('bimg_' + id) || document.getElementById('cimg_' + id);
        if (!card && url) {
            card = document.querySelector('.icard[data-url="' + CSS.escape(url) + '"]');
        }

        if (card) {
            card.classList.add('is-cover');
            var coverBadge = card.querySelector('.ibadge-cover');
            if (coverBadge) {
                coverBadge.style.display = '';
            } else if (!card.closest('#igrid')) {
                card.insertAdjacentHTML('beforeend', '<div class="ibadge-cover">★ COVER</div>');
            }
            var coverBtn = card.querySelector('.js-set-cover');
            if (coverBtn) coverBtn.style.display = 'none';

            if (card.closest('#igrid')) {
                card.classList.add('main');
                var b = card.querySelector('.ibadge');
                if (b) b.style.display = '';
                var mainBtn = card.querySelector('.js-set-main');
                if (mainBtn) mainBtn.style.display = 'none';
            }
        }

        if (url) {
            document.querySelectorAll('.icard[data-url="' + CSS.escape(url) + '"]').forEach(function(c) {
                c.classList.add('is-cover');
                var cb = c.querySelector('.ibadge-cover');
                if (cb) cb.style.display = '';
                var cbtn = c.querySelector('.js-set-cover');
                if (cbtn) cbtn.style.display = 'none';
            });
        }
        showToast('★ Primary cover photo updated!', 'success');
    })
    .catch(function(err) {
        showToast('Error: ' + err.message, 'error');
    });
}

function deleteImage(id, url, source) {
    if (!confirm('Are you sure you want to delete this image?')) return;

    if (!url) {
        var card = document.getElementById('img_' + id) || document.getElementById('fimg_' + id) || document.getElementById('bimg_' + id);
        if (card) url = card.getAttribute('data-url');
        if (!source && card) source = card.getAttribute('data-source') || 'media';
    }

    var delRoute = '{{ $isEdit ? route('admin.products.delete-image', $product->id) : '' }}';
    if (!delRoute) return;

    fetch(delRoute, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ url: url, image_id: id, source: source })
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (!res.success) {
            showToast(res.message || 'Failed to delete image', 'error');
            return;
        }

        var card = document.getElementById('img_' + id);
        if (card) {
            card.style.transition = 'transform .2s, opacity .2s';
            card.style.transform = 'scale(0.8)';
            card.style.opacity = '0';
            setTimeout(function() {
                card.remove();
                var remaining = document.querySelectorAll('#igrid .icard').length;
                var badge = document.getElementById('imageCountBadge');
                if (badge) badge.textContent = remaining + ' Image' + (remaining === 1 ? '' : 's');
                if (remaining === 0) {
                    var grid = document.getElementById('igrid');
                    if (grid && !document.getElementById('imgEmptyNote')) {
                        grid.innerHTML = '<div class="img-empty-note" id="imgEmptyNote" style="grid-column:1/-1">No images uploaded yet. Upload images using the dropzone below.</div>';
                    }
                }
            }, 200);
        }

        // If new main image was elected by backend
        var targetUrl = res.new_main_url || res.image_url;
        if (targetUrl) {
            document.querySelectorAll('.icard').forEach(function(c) {
                var cardUrl = c.getAttribute('data-url');
                var isThis = (cardUrl === targetUrl);
                c.classList.toggle('is-cover', isThis);
                var cb = c.querySelector('.ibadge-cover');
                if (cb) cb.style.display = isThis ? '' : 'none';
                var cbtn = c.querySelector('.js-set-cover');
                if (cbtn) cbtn.style.display = isThis ? 'none' : '';

                if (c.closest('#igrid')) {
                    c.classList.toggle('main', isThis);
                    var b = c.querySelector('.ibadge');
                    if (b) b.style.display = isThis ? '' : 'none';
                    var mainBtn = c.querySelector('.js-set-main');
                    if (mainBtn) mainBtn.style.display = isThis ? 'none' : '';
                }
            });
        }

        var cCard = document.getElementById('cimg_' + id);
        if (cCard) cCard.remove();

        showToast('Image deleted successfully ✓', 'success');
    })
    .catch(function(err) {
        showToast('Error: ' + err.message, 'error');
    });
}

function uploadColorPhotosAjax(input, colorId, colorName) {
    var files = Array.from(input.files || []).filter(function(f) { return f.type.startsWith('image/'); });
    if (!files.length) return;

    var total = files.length, done = 0;
    showToast('Uploading ' + total + ' ' + colorName + ' photo(s)...', 'info');

    files.forEach(function(file) {
        if (file.size > 5 * 1024 * 1024) {
            showToast(file.name + ' too large (max 5MB)', 'error');
            done++; return;
        }

        var fd = new FormData();
        fd.append('file', file);
        fd.append('model_type', 'product');
        fd.append('model_id', PRODUCT_ID);
        fd.append('color_id', colorId);
        fd.append('alt_text', colorName + ' photo');
        fd.append('is_primary', document.querySelectorAll('#igrid .icard').length === 0 ? '1' : '0');

        fetch('{{ route('admin.media.upload') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF },
            body: fd
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            done++;
            if (data.success) {
                var media = data.media;
                // Add to Step 2 color grid
                var grid = document.getElementById('colorGrid_' + colorId);
                if (grid && !document.getElementById('cimg_' + media.id)) {
                    var card = document.createElement('div');
                    card.className = 'icard' + (media.is_primary ? ' main is-cover' : '');
                    card.id = 'cimg_' + media.id;
                    card.setAttribute('data-id', media.id);
                    card.setAttribute('data-url', media.url);
                    card.style.aspectRatio = '1';
                    card.style.borderRadius = '8px';
                    card.innerHTML = `
                        <img src="${media.thumb_url || media.url}" alt="${colorName}">
                        <div class="ibadge-cover" style="${media.is_primary ? '' : 'display:none;'}">★ COVER</div>
                        <div class="iact" style="padding:2px">
                            <button type="button" class="iab iab-cover js-set-cover" style="${media.is_primary ? 'display:none;' : ''};font-size:8.5px;padding:2px" onclick="setMain('${media.id}', '${media.url}', 'product_image')">
                                Cover
                            </button>
                            <button type="button" class="iab iab-del" style="font-size:8.5px;padding:2px" onclick="deleteColorImageAjax('${media.id}', '${media.url}', ${colorId})">
                                Del
                            </button>
                        </div>
                    `;
                    grid.appendChild(card);
                }

                // Add to Step 3 general gallery
                if (typeof addImageCard === 'function') {
                    addImageCard(media);
                }

                showToast(colorName + ' photo uploaded ✓', 'success');
            } else {
                showToast('Upload failed: ' + (data.message || 'Unknown'), 'error');
            }
        })
        .catch(function(err) {
            done++;
            showToast('Error uploading: ' + err.message, 'error');
        });
    });

    input.value = '';
}

function deleteColorImageAjax(id, url, colorId) {
    if (!confirm('Delete this color photo?')) return;
    var cCard = document.getElementById('cimg_' + id);
    if (cCard) cCard.remove();

    if (typeof deleteImage === 'function') {
        deleteImage(id, url, 'product_image');
    }
}
@endif

// ── Quick Add Color Modal Functions ───────────────────────
function openQuickColorModal() {
    var m = document.getElementById('quickColorModal');
    if (!m) return;
    document.getElementById('qcName').value = '';
    document.getElementById('qcHex').value = '#000000';
    document.getElementById('qcHexPicker').value = '#000000';
    document.getElementById('qcImage').value = '';
    document.getElementById('qcImgPreview').src = '';
    document.getElementById('qcImgPreview').style.display = 'none';
    document.getElementById('qcImgPlaceholder').style.display = 'inline-block';
    document.getElementById('qcError').style.display = 'none';
    m.style.display = 'flex';
}

function closeQuickColorModal() {
    var m = document.getElementById('quickColorModal');
    if (m) m.style.display = 'none';
}

function previewQuickColorImg(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('qcImgPreview').src = e.target.result;
            document.getElementById('qcImgPreview').style.display = 'block';
            document.getElementById('qcImgPlaceholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function saveQuickColor() {
    var name = document.getElementById('qcName').value.trim();
    var hex = document.getElementById('qcHex').value.trim();
    var errEl = document.getElementById('qcError');

    if (!name) {
        errEl.textContent = 'Color name is required.';
        errEl.style.display = 'block';
        return;
    }
    if (hex && !/^#[0-9A-Fa-f]{6}$/.test(hex)) {
        errEl.textContent = 'Invalid hex code. Use format: #RRGGBB';
        errEl.style.display = 'block';
        return;
    }

    var fd = new FormData();
    fd.append('name', name);
    if (hex) fd.append('hex_code', hex);
    var fileInput = document.getElementById('qcImage');
    if (fileInput && fileInput.files && fileInput.files[0]) {
        fd.append('image', fileInput.files[0]);
    }

    var btn = document.getElementById('btnSaveQuickColor');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';

    fetch('{{ route('admin.colors.store') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json'
        },
        body: fd
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Save &amp; Use Color';
        if (res.errors || res.error) {
            var msg = res.error || Object.values(res.errors).flat().join(' ');
            errEl.textContent = msg;
            errEl.style.display = 'block';
            return;
        }

        var newCol = res.color;
        // 1. Add to colors array
        colors.push({
            id: newCol.id,
            name: newCol.name,
            hex: newCol.hex_code || '#000000',
            image: newCol.image || null
        });

        // 2. Append color pill to #colorPillsWrap
        var pillsWrap = document.getElementById('colorPillsWrap');
        if (pillsWrap) {
            var label = document.createElement('label');
            label.className = 'color-pill selected';
            label.id = 'cpill_' + newCol.name;
            label.innerHTML = `
                <input type="checkbox" class="colorPick"
                       value="${newCol.name}"
                       data-color-id="${newCol.id}"
                       data-color-hex="${newCol.hex_code || '#000000'}"
                       checked
                       onchange="onColorToggle(this)">
                <span class="color-dot-sm" style="background:${newCol.hex_code || '#000000'}"></span>
                ${newCol.name}
            `;
            pillsWrap.appendChild(label);
        }

        // 3. Append size block to #sizeBlocksGrid
        var grid = document.getElementById('sizeBlocksGrid');
        if (grid) {
            var block = document.createElement('div');
            block.className = 'size-block visible';
            block.setAttribute('data-color-block', newCol.name);
            block.id = 'sblock_' + newCol.name;

            var sizePillsHtml = sizes.map(function(s) {
                return `<label class="size-pill">
                    <input type="checkbox" class="sizePick" value="${s}" data-for-color="${newCol.name}">
                    ${s}
                </label>`;
            }).join('');

            var mockupBadge = newCol.image ? `
                <a href="${newCol.image}" target="_blank" title="View Color Mockup" style="font-size:10.5px;color:#0284c7;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:4px">
                    <img src="${newCol.image}" alt="${newCol.name}" style="width:18px;height:18px;border-radius:4px;object-fit:cover;border:1px solid #cbd5e1"> Base Mockup
                </a>
            ` : '';

            var uploadBoxHtml = PRODUCT_ID ? `
                <div class="color-img-grid" id="colorGrid_${newCol.id}" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(65px,1fr));gap:6px;margin-bottom:8px"></div>
                <div class="izone-compact" onclick="document.getElementById('step2_cFileInput_${newCol.id}').click()" style="padding:10px 8px">
                    <i class="bi bi-cloud-arrow-up" style="font-size:18px;color:#0284c7"></i>
                    <div style="font-size:11.5px;font-weight:700;color:#00285a">+ Upload ${newCol.name} Photos</div>
                    <div style="font-size:10px;color:#94a3b8">Multiple photos · JPG, PNG, WEBP</div>
                    <input type="file" id="step2_cFileInput_${newCol.id}" accept="image/jpeg,image/png,image/webp" multiple style="display:none" onchange="uploadColorPhotosAjax(this, ${newCol.id}, '${newCol.name}')">
                </div>
            ` : `
                <div class="izone-compact" onclick="document.getElementById('step2_cFileInput_${newCol.id}').click()" style="padding:10px 8px">
                    <i class="bi bi-cloud-arrow-up" style="font-size:18px;color:#0284c7"></i>
                    <div style="font-size:11.5px;font-weight:700;color:#00285a">+ Select ${newCol.name} Photos</div>
                    <div style="font-size:10px;color:#94a3b8">Upload photos for this color</div>
                    <input type="file" id="step2_cFileInput_${newCol.id}" accept="image/jpeg,image/png,image/webp" multiple style="display:none" onchange="handleStep2ColorFiles(this, '${newCol.id}', '${newCol.name}')">
                </div>
                <div class="new-img-queue" id="step2_colorQueue_${newCol.id}" style="padding:8px 0 0;grid-template-columns:repeat(auto-fill,minmax(65px,1fr));gap:6px"></div>
            `;

            block.innerHTML = `
                <div class="size-block-title" style="display:flex;justify-content:space-between;align-items:center">
                    <div style="display:flex;align-items:center;gap:6px">
                        <span class="color-dot-sm" style="background:${newCol.hex_code || '#000000'}"></span>
                        ${newCol.name}
                    </div>
                    <button type="button" class="btn btn-link btn-sm"
                            style="color:#0284c7;font-size:11px;font-weight:700;padding:0;text-decoration:none;cursor:pointer"
                            onclick="toggleAllSizesForColor('${newCol.name}')">
                        Select All Sizes
                    </button>
                </div>
                <div class="size-pills">
                    ${sizePillsHtml}
                </div>
                <div class="color-tshirt-box" style="margin-top:12px;padding-top:10px;border-top:1px dashed #cbd5e1">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
                        <span style="font-size:11px;font-weight:700;color:#00285a;display:flex;align-items:center;gap:5px">
                            <i class="bi bi-camera-fill" style="color:#0284c7"></i> ${newCol.name} T-Shirt Photos
                        </span>
                        ${mockupBadge}
                    </div>
                    ${uploadBoxHtml}
                </div>
            `;
            grid.appendChild(block);
        }

        // 4. In Create mode, append color tab and panel in Step 3 if not present
        var newTabs = document.getElementById('newImgColorTabs');
        if (newTabs && !document.querySelector('#newImgColorTabs .color-tab[data-color-id="' + newCol.id + '"]')) {
            var tab = document.createElement('div');
            tab.className = 'color-tab';
            tab.setAttribute('data-color-id', newCol.id);
            tab.onclick = function() { switchNewTab(this, newCol.id); };
            tab.innerHTML = `<span class="color-tab-dot" style="background:${newCol.hex_code || '#000000'}"></span> ${newCol.name}`;
            newTabs.appendChild(tab);

            var panel = document.createElement('div');
            panel.className = 'color-tab-panel';
            panel.id = 'newpanel_' + newCol.id;
            panel.innerHTML = `
                <div class="izone new-zone" id="newdrop_${newCol.id}" onclick="document.getElementById('newinput_${newCol.id}').click()" style="margin:14px 20px">
                    <i class="bi bi-cloud-arrow-up" style="font-size:28px;color:#b0bec5;display:block;margin-bottom:6px"></i>
                    <div style="font-size:13px;color:#7a8fa6">
                        <span class="color-tab-dot" style="background:${newCol.hex_code || '#000000'};display:inline-block;vertical-align:middle;margin-right:4px"></span>
                        <strong style="color:#00285a">${newCol.name}</strong> images
                    </div>
                    <div style="font-size:11px;color:#b0bec5;margin-top:3px">JPG, PNG, WEBP · Max 5MB</div>
                    <input type="file" id="newinput_${newCol.id}" name="color_images[${newCol.id}][]" accept="image/jpeg,image/jpg,image/png,image/webp" multiple style="display:none" onchange="previewNewImages(this, '${newCol.id}')">
                </div>
                <div class="new-img-queue" id="newqueue_${newCol.id}"></div>
            `;
            var step3Panel = document.getElementById('step3');
            if (step3Panel) {
                var container = step3Panel.querySelector('.pc:last-child');
                if (container) container.appendChild(panel);
            }
        }

        // 5. In Edit mode, append tab in Step 3
        var editTabs = document.getElementById('imgColorTabs');
        if (editTabs && !document.querySelector('#imgColorTabs .color-tab[data-color-id="' + newCol.id + '"]')) {
            var eTab = document.createElement('div');
            eTab.className = 'color-tab';
            eTab.setAttribute('data-color-id', newCol.id);
            eTab.onclick = function() { switchColorTab(this, String(newCol.id)); };
            eTab.innerHTML = `<span class="color-tab-dot" style="background:${newCol.hex_code || '#000000'}"></span> ${newCol.name}`;
            editTabs.appendChild(eTab);
        }

        document.getElementById('sizeBlocksWrap').style.display = 'block';
        closeQuickColorModal();
        showToast('Color "' + newCol.name + '" created and selected! ✓', 'success');
    })
    .catch(function(err) {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Save &amp; Use Color';
        errEl.textContent = 'Error saving color.';
        errEl.style.display = 'block';
    });
}

// ── Step 2 Color Queue Functions (Create Mode) ────────────
function handleStep2ColorFiles(input, colorId, colorName) {
    if (!input.files || !input.files.length) return;
    var step3Input = document.getElementById('newinput_' + colorId);
    var key = colorId || '0';
    if (!_fileTransfers[key]) {
        _fileTransfers[key] = new DataTransfer();
    }
    var dt = _fileTransfers[key];
    Array.from(input.files).forEach(function(file) {
        if (!file.type.startsWith('image/')) return;
        for (var i = 0; i < dt.items.length; i++) {
            var ex = dt.items[i].getAsFile();
            if (ex && ex.name === file.name && ex.size === file.size) return;
        }
        dt.items.add(file);
    });

    if (step3Input) {
        step3Input.files = dt.files;
        previewNewImages(step3Input, colorId);
    }
    renderStep2ColorQueue(colorId);
    showToast('Added ' + input.files.length + ' photo(s) for ' + colorName + ' ✓', 'success');
    input.value = '';
}

function renderStep2ColorQueue(colorId) {
    var queue = document.getElementById('step2_colorQueue_' + colorId);
    if (!queue) return;
    queue.innerHTML = '';

    var key = colorId || '0';
    var dt = _fileTransfers[key];
    if (!dt || !dt.files.length) return;

    Array.from(dt.files).forEach(function(file) {
        var isCover = false;
        var thumb = document.createElement('div');
        thumb.className = 'new-img-thumb' + (isCover ? ' is-cover main' : '');
        thumb.setAttribute('data-file-name', file.name);
        thumb.setAttribute('data-color-id', colorId || '');
        thumb.style.aspectRatio = '1';

        var img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.onload = function() { URL.revokeObjectURL(img.src); };

        var badge = document.createElement('div');
        badge.className = 'new-img-badge';
        badge.textContent = '★ COVER';
        badge.style.display = isCover ? 'block' : 'none';

        var act = document.createElement('div');
        act.className = 'new-img-act';
        act.style.padding = '2px';

        var btnCover = document.createElement('button');
        btnCover.type = 'button';
        btnCover.className = 'new-img-btn-cover';
        btnCover.style.fontSize = '8.5px';
        btnCover.style.padding = '2px 4px';
        btnCover.innerHTML = '<i class="bi bi-star"></i> Cover';
        btnCover.style.display = 'none';
        btnCover.onclick = function() {
            setNewDesignatedMain(file.name);
            renderStep2ColorQueue(colorId);
        };
        act.appendChild(btnCover);

        var rm = document.createElement('button');
        rm.type = 'button';
        rm.className = 'new-img-rm';
        rm.innerHTML = '×';
        rm.onclick = function() {
            removeStep2ColorFile(colorId, file.name);
        };

        thumb.appendChild(img);
        thumb.appendChild(badge);
        thumb.appendChild(act);
        thumb.appendChild(rm);
        queue.appendChild(thumb);
    });
}

function removeStep2ColorFile(colorId, fileName) {
    var key = colorId || '0';
    var dt = _fileTransfers[key];
    if (dt) {
        var newDt = new DataTransfer();
        for (var i = 0; i < dt.items.length; i++) {
            var f = dt.items[i].getAsFile();
            if (f && f.name !== fileName) {
                newDt.items.add(f);
            }
        }
        _fileTransfers[key] = newDt;
        var step3Input = document.getElementById('newinput_' + colorId);
        if (step3Input) {
            step3Input.files = newDt.files;
            var step3Queue = document.getElementById('newqueue_' + colorId);
            if (step3Queue) {
                var matching = step3Queue.querySelector('.new-img-thumb[data-file-name="' + CSS.escape(fileName) + '"]');
                if (matching) matching.remove();
            }
        }
    }
    renderStep2ColorQueue(colorId);

    var mainInput = document.getElementById('designatedMainImageName');
    if (mainInput && mainInput.value === fileName) {
        var anyThumb = document.querySelector('.new-img-thumb:not([data-color-id]), .new-img-thumb[data-color-id=""]');
        if (anyThumb) {
            setNewDesignatedMain(anyThumb.getAttribute('data-file-name'));
        } else {
            mainInput.value = '';
        }
    }
}

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
