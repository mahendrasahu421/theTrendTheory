{{-- resources/views/admin/categories/create.blade.php --}}
{{-- CREATE + EDIT dono ek hi file --}}
@extends('admin.layouts.app')
@section('title', isset($category) ? 'Edit: ' . $category->name : 'Add Category')
@section('content')

    @php $isEdit = isset($category); @endphp

    <style>
        .cat-wrap {
            max-width: 860px;
            margin: 0 auto;
        }

        /* ── Header ─────────────────────────────────────────────── */
        .cat-hdr {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .cat-hdr-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .cat-hdr-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #00285a, #1e3f75);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(0, 40, 90, 0.2);
        }

        .cat-hdr-heading {
            font-family: 'Cinzel', serif, sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            line-height: 1.2;
        }

        .cat-hdr-sub {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }

        .cat-hdr-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* ── Cards ───────────────────────────────────────────────── */
        .pc-card {
            background: #ffffff;
            border: 1px solid #eef2f6;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0, 40, 90, 0.03);
            transition: all 0.25s ease;
        }

        .pc-card:hover {
            box-shadow: 0 8px 28px rgba(0, 40, 90, 0.07);
        }

        .pc-hdr {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            background: #fafbff;
            border-bottom: 1px solid #eef2f6;
        }

        .pc-title {
            font-family: 'Cinzel', serif, sans-serif;
            font-size: 12.5px;
            font-weight: 700;
            color: #00285a;
            letter-spacing: 0.8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pc-body {
            padding: 24px;
        }

        /* ── Form Inputs ─────────────────────────────────────────── */
        .fg-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .fgrp {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .fgrp.span2 {
            grid-column: 1 / -1;
        }

        .fgrp label {
            font-size: 11.5px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .fc-input {
            padding: 11px 16px;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            font-size: 13.5px;
            font-family: inherit;
            outline: none;
            transition: all 0.2s ease;
            width: 100%;
            background: #ffffff;
            color: #0f172a;
        }

        .fc-input:focus {
            border-color: #00285a;
            box-shadow: 0 0 0 3px rgba(0, 40, 90, 0.1);
        }

        .fc-hint {
            font-size: 11px;
            color: #64748b;
            margin-top: 3px;
        }

        .slug-preview {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            color: #475569;
            background: #f1f5f9;
            padding: 6px 12px;
            border-radius: 8px;
            margin-top: 4px;
            font-family: monospace;
        }

        /* ── Upload Box ─────────────────────────────────────────── */
        .upload-dropzone {
            position: relative;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .upload-dropzone:hover {
            border-color: #00285a;
            background: #eff6ff;
        }

        .upload-dropzone i {
            font-size: 28px;
            color: #64748b;
            display: block;
            margin-bottom: 6px;
        }

        .upload-dropzone p {
            margin: 0;
            font-size: 13px;
            font-weight: 700;
            color: #00285a;
        }

        .upload-dropzone span {
            font-size: 11px;
            color: #64748b;
        }

        .preview-box {
            position: relative;
            margin-top: 12px;
            display: inline-block;
        }

        .preview-box img {
            border-radius: 10px;
            border: 1.5px solid #cbd5e1;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            object-fit: cover;
        }

        .preview-box.thumb-img img {
            width: 100px;
            height: 100px;
        }

        .preview-box.banner-img img {
            width: 100%;
            max-height: 140px;
        }

        .preview-remove {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }

        /* ── SERP Preview Box ────────────────────────────────────── */
        .serp-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            margin-top: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }

        .serp-title {
            color: #1a0dab;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }

        .serp-url {
            color: #006621;
            font-size: 12px;
            margin-top: 2px;
        }

        .serp-desc {
            color: #545454;
            font-size: 12.5px;
            line-height: 1.4;
            margin-top: 4px;
        }

        /* ── Toggle Cards ────────────────────────────────────────── */
        .toggle-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
        }

        .toggle-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            cursor: pointer;
            background: #fff;
            transition: all 0.2s ease;
            user-select: none;
        }

        .toggle-card:hover {
            border-color: #00285a;
            background: #f8fafc;
        }

        .toggle-card.selected {
            border-color: #00285a;
            background: #eff6ff;
        }

        .toggle-card input {
            width: 18px;
            height: 18px;
            accent-color: #00285a;
            cursor: pointer;
        }

        .toggle-card-info {
            display: flex;
            flex-direction: column;
        }

        .toggle-card-label {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        .toggle-card-desc {
            font-size: 11px;
            color: #64748b;
        }

        /* ── Action Buttons ──────────────────────────────────────── */
        .btn-act {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 24px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            border: none;
            text-decoration: none;
        }

        .btn-act-navy {
            background: linear-gradient(135deg, #00285a, #1e3f75);
            color: white;
            box-shadow: 0 4px 12px rgba(0, 40, 90, 0.25);
        }

        .btn-act-navy:hover {
            background: linear-gradient(135deg, #001e44, #15315e);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 40, 90, 0.35);
            color: white;
        }

        .btn-act-light {
            background: #fff;
            color: #475569;
            border: 1.5px solid #cbd5e1;
        }

        .btn-act-light:hover {
            background: #f8fafc;
            border-color: #00285a;
            color: #00285a;
        }

        .err-banner {
            background: #fef2f2;
            color: #991b1b;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 13.5px;
            font-weight: 600;
            border: 1px solid #fecaca;
        }

        @media(max-width: 640px) {
            .fg-grid {
                grid-template-columns: 1fr;
            }
            .fgrp.span2 {
                grid-column: 1;
            }
        }
    </style>

    <div class="cat-wrap">

        {{-- ── Header ─────────────────────────────────────────────── --}}
        <div class="cat-hdr">
            <div class="cat-hdr-title">
                <div class="cat-hdr-icon">
                    <i class="{{ $isEdit ? 'bi-pencil-square' : 'bi-folder-plus' }}"></i>
                </div>
                <div>
                    <h1 class="cat-hdr-heading">{{ $isEdit ? 'Edit Category' : 'Add New Category' }}</h1>
                    <div class="cat-hdr-sub">{{ $isEdit ? 'Update details for "' . $category->name . '"' : 'Create a main category or sub-category for your shop' }}</div>
                </div>
            </div>
            <div class="cat-hdr-actions">
                @if ($isEdit)
                    <a href="{{ route('shop.category', $category->slug) }}" target="_blank" class="btn-act btn-act-light" style="padding:8px 16px; font-size:12px">
                        <i class="bi bi-eye"></i> View on Shop
                    </a>
                @endif
                <a href="{{ route('admin.categories.index') }}" class="btn-act btn-act-light" style="padding:8px 16px; font-size:12px">
                    <i class="bi bi-arrow-left"></i> All Categories
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="err-banner">
                @foreach ($errors->all() as $e)
                    <div>• {{ $e }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" enctype="multipart/form-data" id="categoryForm"
            action="{{ $isEdit ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            {{-- CARD 1: BASIC INFO --}}
            <div class="pc-card">
                <div class="pc-hdr">
                    <div class="pc-title"><i class="bi bi-info-circle-fill"></i> BASIC INFORMATION</div>
                </div>
                <div class="pc-body">
                    <div class="fg-grid">
                        <div class="fgrp span2">
                            <label>Category Name *</label>
                            <input type="text" name="name" class="fc-input" id="nameInput"
                                value="{{ old('name', $isEdit ? $category->name : '') }}"
                                required placeholder="e.g. Men's T-Shirts">
                            <div class="slug-preview">
                                <i class="bi bi-link-45deg"></i>
                                <span>URL Slug: </span>
                                <strong id="slugVal">{{ old('name', $isEdit ? $category->slug : 'mens-t-shirts') }}</strong>
                            </div>
                        </div>

                        <div class="fgrp">
                            <label>Parent Category</label>
                            <select name="parent_id" class="fc-input">
                                <option value="">None — Top Level Category</option>
                                @foreach ($parents as $p)
                                    <option value="{{ $p->id }}"
                                        {{ old('parent_id', $isEdit ? $category->parent_id : '') == $p->id ? 'selected' : '' }}>
                                        📁 {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="fc-hint">Select a parent to make this a sub-category.</div>
                        </div>

                        <div class="fgrp">
                            <label>Sort Order</label>
                            <input type="number" name="sort_order" class="fc-input" min="0"
                                value="{{ old('sort_order', $isEdit ? $category->sort_order : 0) }}" placeholder="0">
                            <div class="fc-hint">Lower number = appears earlier in menu.</div>
                        </div>

                        <div class="fgrp span2">
                            <label>Description</label>
                            <textarea name="description" class="fc-input" rows="3"
                                placeholder="Brief summary of this category (appears on category page header)...">{{ old('description', $isEdit ? $category->description : '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD 2: MEDIA & IMAGES --}}
            <div class="pc-card">
                <div class="pc-hdr">
                    <div class="pc-title"><i class="bi bi-images"></i> CATEGORY IMAGES</div>
                </div>
                <div class="pc-body">
                    <div class="fg-grid">

                        {{-- Thumbnail Image --}}
                        <div class="fgrp span2">
                            <label>Category Thumbnail Image (Square / Grid Preview)</label>
                            <div class="upload-dropzone" onclick="document.getElementById('file_image').click()">
                                <i class="bi bi-cloud-arrow-up"></i>
                                <p id="file_image_text">Click or Drag & Drop Image</p>
                                <span>Recommended: 600x600px | Max 4MB</span>
                                <input type="file" id="file_image" name="image_file" accept="image/*" style="display:none">
                            </div>
                            <input type="hidden" name="image" id="imgInput" value="{{ old('image', $isEdit ? $category->image : '') }}">
                            
                            @php $imgVal = old('image', $isEdit ? $category->image : ''); @endphp
                            <div class="preview-box thumb-img" id="imgPrevWrap" style="{{ $imgVal ? '' : 'display:none' }}">
                                <img id="imgPrev" src="{{ $imgVal ?: '' }}" alt="Thumbnail preview">
                                <button type="button" class="preview-remove" onclick="clearPreview('file_image', 'imgInput', 'imgPrevWrap', 'file_image_text')">&times;</button>
                            </div>
                        </div>

                        {{-- Banner Image --}}
                        <div class="fgrp span2">
                            <label>Hero Banner Image (Header Cover)</label>
                            <div class="upload-dropzone" onclick="document.getElementById('file_banner').click()">
                                <i class="bi bi-card-image"></i>
                                <p id="file_banner_text">Click or Drag & Drop Banner</p>
                                <span>Recommended: 1400x400px Wide Banner | Max 6MB</span>
                                <input type="file" id="file_banner" name="banner_image_file" accept="image/*" style="display:none">
                            </div>
                            <input type="hidden" name="banner_image" id="bannerInput" value="{{ old('banner_image', $isEdit ? $category->banner_image : '') }}">
                            
                            @php $bannerVal = old('banner_image', $isEdit ? $category->banner_image : ''); @endphp
                            <div class="preview-box banner-img" id="bannerPrevWrap" style="{{ $bannerVal ? '' : 'display:none' }}">
                                <img id="bannerPrev" src="{{ $bannerVal ?: '' }}" alt="Banner preview">
                                <button type="button" class="preview-remove" onclick="clearPreview('file_banner', 'bannerInput', 'bannerPrevWrap', 'file_banner_text')">&times;</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- CARD 3: SEO --}}
            <div class="pc-card">
                <div class="pc-hdr">
                    <div class="pc-title"><i class="bi bi-search"></i> SEARCH ENGINE OPTIMIZATION (SEO)</div>
                </div>
                <div class="pc-body">
                    <div class="fg-grid">
                        <div class="fgrp">
                            <label>
                                Meta Title
                                <span style="text-transform:none; font-weight:600;"><span id="mtCount">0</span>/70</span>
                            </label>
                            <input type="text" name="meta_title" maxlength="70" class="fc-input" id="metaTitle"
                                value="{{ old('meta_title', $isEdit ? $category->meta_title : '') }}"
                                placeholder="Buy Men's T-Shirts Online | The Trend Theory">
                        </div>

                        <div class="fgrp">
                            <label>Meta Keywords</label>
                            <input type="text" name="meta_keywords" class="fc-input"
                                value="{{ old('meta_keywords', $isEdit ? $category->meta_keywords : '') }}"
                                placeholder="tshirts, oversized tees, streetwear">
                        </div>

                        <div class="fgrp span2">
                            <label>
                                Meta Description
                                <span style="text-transform:none; font-weight:600;"><span id="mdCount">0</span>/170</span>
                            </label>
                            <textarea name="meta_description" class="fc-input" rows="2" maxlength="170" id="metaDesc"
                                placeholder="Discover the finest collection of t-shirts online. Free shipping above ₹999.">{{ old('meta_description', $isEdit ? $category->meta_description : '') }}</textarea>
                        </div>

                        {{-- SERP Snippet Preview --}}
                        <div class="fgrp span2">
                            <label style="margin-bottom:2px"><i class="bi bi-google"></i> Google Search Preview</label>
                            <div class="serp-box">
                                <span class="serp-title" id="serpTitle">
                                    {{ old('meta_title', $isEdit ? ($category->meta_title ?: $category->name . ' | The Trend Theory') : 'Category Name | The Trend Theory') }}
                                </span>
                                <div class="serp-url">https://thetrendtheory.com › category › <span id="serpSlug">{{ old('slug', $isEdit ? $category->slug : 'category-slug') }}</span></div>
                                <div class="serp-desc" id="serpDesc">
                                    {{ old('meta_description', $isEdit ? ($category->meta_description ?: 'Explore our latest collection of premium fashion items. Free shipping and easy returns.') : 'Explore our latest collection of premium fashion items. Free shipping and easy returns.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD 4: VISIBILITY & STATUS --}}
            <div class="pc-card">
                <div class="pc-hdr">
                    <div class="pc-title"><i class="bi bi-eye-fill"></i> VISIBILITY & DISPLAY</div>
                </div>
                <div class="pc-body">
                    <div class="toggle-cards-grid">
                        <label class="toggle-card {{ old('is_active', $isEdit ? $category->is_active : true) ? 'selected' : '' }}" id="card_active">
                            <input type="checkbox" name="is_active" value="1" onchange="toggleCardStyle(this, 'card_active')"
                                {{ old('is_active', $isEdit ? $category->is_active : true) ? 'checked' : '' }}>
                            <div class="toggle-card-info">
                                <span class="toggle-card-label">Active</span>
                                <span class="toggle-card-desc">Visible on site and search</span>
                            </div>
                        </label>

                        <label class="toggle-card {{ old('show_in_nav', $isEdit ? $category->show_in_nav : true) ? 'selected' : '' }}" id="card_nav">
                            <input type="checkbox" name="show_in_nav" value="1" onchange="toggleCardStyle(this, 'card_nav')"
                                {{ old('show_in_nav', $isEdit ? $category->show_in_nav : true) ? 'checked' : '' }}>
                            <div class="toggle-card-info">
                                <span class="toggle-card-label">Navigation Menu</span>
                                <span class="toggle-card-desc">Show in header navbar</span>
                            </div>
                        </label>

                        <label class="toggle-card {{ old('show_in_home', $isEdit ? $category->show_in_home : false) ? 'selected' : '' }}" id="card_home">
                            <input type="checkbox" name="show_in_home" value="1" onchange="toggleCardStyle(this, 'card_home')"
                                {{ old('show_in_home', $isEdit ? $category->show_in_home : false) ? 'checked' : '' }}>
                            <div class="toggle-card-info">
                                <span class="toggle-card-label">Homepage Section</span>
                                <span class="toggle-card-desc">Show in homepage category grid</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-top:28px; margin-bottom:40px;">
                <a href="{{ route('admin.categories.index') }}" class="btn-act btn-act-light">
                    <i class="bi bi-x-lg"></i> Cancel
                </a>
                <button type="submit" id="submitBtn" class="btn-act btn-act-navy">
                    <i class="bi bi-check-lg"></i>
                    {{ $isEdit ? 'Update Category' : 'Save Category' }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Live Slug & Title Generator
            function slugify(text) {
                return text.toString().toLowerCase()
                    .replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-')
                    .replace(/^-+/, '')
                    .replace(/-+$/, '');
            }

            const nameInput = document.getElementById('nameInput');
            const slugVal = document.getElementById('slugVal');
            const serpSlug = document.getElementById('serpSlug');

            if (nameInput) {
                nameInput.addEventListener('input', function() {
                    let slug = slugify(this.value) || 'category-slug';
                    if (slugVal) slugVal.textContent = slug;
                    if (serpSlug) serpSlug.textContent = slug;

                    let metaTitleInput = document.getElementById('metaTitle');
                    if (metaTitleInput && !metaTitleInput.dataset.touched) {
                        let title = this.value ? `${this.value} | The Trend Theory` : '';
                        document.getElementById('serpTitle').textContent = title || 'Category Name | The Trend Theory';
                    }
                });
            }

            // Image Preview Handlers
            function setupImagePreview(fileInputId, previewWrapId, previewImgId, textId) {
                const fileInput = document.getElementById(fileInputId);
                const previewWrap = document.getElementById(previewWrapId);
                const previewImg = document.getElementById(previewImgId);
                const textEl = document.getElementById(textId);

                if (!fileInput) return;

                fileInput.addEventListener('change', function(e) {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        const reader = new FileReader();
                        reader.onload = function(evt) {
                            previewImg.src = evt.target.result;
                            previewWrap.style.display = 'inline-block';
                            if (textEl) textEl.textContent = file.name;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            setupImagePreview('file_image', 'imgPrevWrap', 'imgPrev', 'file_image_text');
            setupImagePreview('file_banner', 'bannerPrevWrap', 'bannerPrev', 'file_banner_text');

            function clearPreview(fileInputId, hiddenInputId, previewWrapId, textId) {
                document.getElementById(fileInputId).value = '';
                if (hiddenInputId) document.getElementById(hiddenInputId).value = '';
                document.getElementById(previewWrapId).style.display = 'none';
                if (textId) {
                    document.getElementById(textId).textContent = fileInputId === 'file_image' ? 'Click or Drag & Drop Image' : 'Click or Drag & Drop Banner';
                }
            }

            // Toggle Card Active State
            function toggleCardStyle(input, cardId) {
                const card = document.getElementById(cardId);
                if (card) card.classList.toggle('selected', input.checked);
            }

            // Character counters & SERP Live Preview
            function setupSerpPreview() {
                const metaTitle = document.getElementById('metaTitle');
                const metaDesc = document.getElementById('metaDesc');
                const mtCount = document.getElementById('mtCount');
                const mdCount = document.getElementById('mdCount');
                const serpTitle = document.getElementById('serpTitle');
                const serpDesc = document.getElementById('serpDesc');

                if (metaTitle) {
                    mtCount.textContent = metaTitle.value.length;
                    metaTitle.addEventListener('input', function() {
                        this.dataset.touched = "true";
                        mtCount.textContent = this.value.length;
                        serpTitle.textContent = this.value || (nameInput.value ? `${nameInput.value} | The Trend Theory` : 'Category Name | The Trend Theory');
                    });
                }

                if (metaDesc) {
                    mdCount.textContent = metaDesc.value.length;
                    metaDesc.addEventListener('input', function() {
                        mdCount.textContent = this.value.length;
                        serpDesc.textContent = this.value || 'Explore our latest collection of premium fashion items. Free shipping and easy returns.';
                    });
                }
            }
            setupSerpPreview();

            // Submit Loading State
            const categoryForm = document.getElementById('categoryForm');
            const submitBtn = document.getElementById('submitBtn');
            if (categoryForm && submitBtn) {
                categoryForm.addEventListener('submit', function() {
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.7';
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';
                });
            }
        </script>
    @endpush
@endsection
