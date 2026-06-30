{{-- resources/views/admin/categories/create.blade.php --}}
{{-- CREATE + EDIT dono ek hi file --}}
@extends('admin.layouts.app')
@section('title', isset($category) ? 'Edit: ' . $category->name : 'Add Category')
@section('content')

    @php $isEdit = isset($category); @endphp

    <style>
        .form-wrap {
            max-width: 760px;
        }

        .card {
            background: white;
            border: 1px solid #eef2f6;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .card-hdr {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid #eef2f6;
        }

        .card-title {
            font-family: 'Cinzel', serif;
            font-size: 13px;
            font-weight: 700;
            color: #00285a;
            letter-spacing: 1px;
        }

        .sec-label {
            font-size: 10px;
            font-weight: 700;
            color: #7a8fa6;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 14px 20px 0;
            display: block;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            padding: 14px 20px;
        }

        .form-grid.full-cols {
            grid-template-columns: 1fr;
        }

        .fgrp {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .fgrp.span2 {
            grid-column: 1/-1;
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
        }

        .fgrp input:focus,
        .fgrp select:focus,
        .fgrp textarea:focus {
            border-color: #00285a;
        }

        .fgrp .hint {
            font-size: 11px;
            color: #7a8fa6;
            margin-top: 3px;
        }

        .ferr {
            font-size: 11px;
            color: #ff3f6c;
            margin-top: 3px;
        }

        .toggle-row {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
            padding: 14px 20px;
        }

        .ftog {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .ftog input {
            width: 17px;
            height: 17px;
            accent-color: #00285a;
            cursor: pointer;
        }

        .ftog span {
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            padding: 16px 20px;
            border-top: 1px solid #eef2f6;
            background: #fafbff;
        }

        .btn-save {
            background: #00285a;
            color: white;
            border: none;
            padding: 10px 26px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: .15s;
            font-family: inherit;
        }

        .btn-save:hover {
            background: #1e3f75;
        }

        .btn-cancel {
            background: white;
            color: #555;
            border: 1px solid #e8edf5;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .img-prev {
            height: 80px;
            border-radius: 8px;
            border: 1px solid #eef2f6;
            margin-top: 6px;
            display: block;
        }

        .err-box {
            background: #fce4ec;
            color: #c62828;
            padding: 12px 16px;
            border-radius: 10px;
            margin: 0 20px 14px;
            font-size: 13px;
        }

        @media(max-width:640px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .fgrp.span2 {
                grid-column: 1;
            }
        }
    </style>

    <div class="form-wrap">
        <div class="card">
            <div class="card-hdr">
                <div class="card-title">{{ $isEdit ? 'EDIT: ' . Str::upper($category->name) : 'ADD CATEGORY' }}</div>
                <div style="display:flex;gap:8px">
                    @if ($isEdit)
                        <a href="{{ route('shop.category', $category->slug) }}" target="_blank" class="btn-cancel"
                            style="font-size:11px;padding:6px 12px">
                            <i class="bi bi-eye"></i>&nbsp;View
                        </a>
                    @endif
                    <a href="{{ route('admin.categories.index') }}" class="btn-cancel"
                        style="font-size:11px;padding:6px 12px">
                        <i class="bi bi-arrow-left"></i>&nbsp;Back
                    </a>
                </div>
            </div>

            @if ($errors->any())
                <div class="err-box" style="margin-top:14px">
                    @foreach ($errors->all() as $e)
                        <div>• {{ $e }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" enctype="multipart/form-data"
                action="{{ $isEdit ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                {{-- BASIC INFO --}}
                <span class="sec-label">Basic Information</span>
                <div class="form-grid">
                    <div class="fgrp span2">
                        <label>Category Name *</label>
                        <input type="text" name="name" value="{{ old('name', $isEdit ? $category->name : '') }}"
                            required placeholder="e.g. Men's T-Shirts" id="nameInput">
                        @error('name')
                            <div class="ferr">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="fgrp">
                        <label>Parent Category</label>
                        <select name="parent_id">
                            <option value="">None — Top Level</option>
                            @foreach ($parents as $p)
                                <option value="{{ $p->id }}"
                                    {{ old('parent_id', $isEdit ? $category->parent_id : '') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="hint">Select parent to make this a sub-category</div>
                    </div>

                    <div class="fgrp">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" min="0"
                            value="{{ old('sort_order', $isEdit ? $category->sort_order : 0) }}" placeholder="0">
                        <div class="hint">Lower number = shown first</div>
                    </div>

                    <div class="fgrp span2">
                        <label>Description</label>
                        <textarea name="description" rows="2" placeholder="Brief category description (optional)">{{ old('description', $isEdit ? $category->description : '') }}</textarea>
                    </div>
                </div>
                <style>
                    .ik-upload-box {
                        display: flex;
                        align-items: center;
                        gap: 12px;
                        padding: 10px 14px;
                        border: 1.5px dashed #c8d6e5;
                        border-radius: 10px;
                        background: #f8faff;
                        margin-bottom: 8px;
                    }

                    .ik-pick-btn {
                        background: #00285a;
                        color: white;
                        border: none;
                        padding: 7px 16px;
                        border-radius: 7px;
                        font-size: 12px;
                        font-weight: 700;
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        gap: 6px;
                        font-family: inherit;
                        white-space: nowrap;
                        transition: .15s;
                    }

                    .ik-pick-btn:hover {
                        background: #1e3f75;
                    }

                    .ik-pick-btn:disabled {
                        opacity: .5;
                        cursor: not-allowed;
                    }

                    .ik-status {
                        font-size: 12px;
                        color: #7a8fa6;
                    }

                    .ik-status.uploading {
                        color: #f59e0b;
                    }

                    .ik-status.done {
                        color: #16a34a;
                        font-weight: 600;
                    }

                    .ik-status.error {
                        color: #dc2626;
                    }
                </style>
                {{-- IMAGE --}}
                {{-- IMAGE --}}
                <span class="sec-label">Category Image</span>
                <div class="form-grid">
                    <div class="fgrp span2">
                        <label>Category Image</label>
                        <div class="ik-upload-box" id="box_image">
                            <input type="file" id="file_image" name="image" accept="image/*" style="display:none">
                            <button type="button" class="ik-pick-btn"
                                onclick="document.getElementById('file_image').click()">
                                <i class="bi bi-cloud-upload"></i> Choose Image
                            </button>
                            <span class="ik-status" id="status_image"></span>
                        </div>
                        <input type="hidden" name="image" id="imgInput"
                            value="{{ old('image', $isEdit ? $category->image : '') }}"
                            placeholder="URL (optional)">
                        <div class="hint">Choose image file to upload (Cloudinary on save)</div>
                        @php $imgVal = old('image', $isEdit ? $category->image : ''); @endphp
                        @if ($imgVal)
                            <img id="imgPrev" src="{{ $imgVal }}" class="img-prev" alt="preview">
                        @else
                            <img id="imgPrev" src="" class="img-prev" style="display:none" alt="preview">
                        @endif
                    </div>

                    <div class="fgrp span2">
                        <label>Banner Image <span style="font-weight:400;color:#7a8fa6">(optional)</span></label>
                        <div class="ik-upload-box" id="box_banner">
                            <input type="file" id="file_banner" name="banner_image" accept="image/*" style="display:none">
                            <button type="button" class="ik-pick-btn"
                                onclick="document.getElementById('file_banner').click()">
                                <i class="bi bi-cloud-upload"></i> Choose Banner
                            </button>
                            <span class="ik-status" id="status_banner"></span>
                        </div>
                        <input type="hidden" name="banner_image" id="bannerInput"
                            value="{{ old('banner_image', $isEdit ? $category->banner_image : '') }}"
                            placeholder="URL (optional)">
                        @php $bannerVal = old('banner_image', $isEdit ? $category->banner_image : ''); @endphp
                        @if ($bannerVal)
                            <img id="bannerPrev" src="{{ $bannerVal }}"
                                style="height:60px;border-radius:8px;border:1px solid #eef2f6;margin-top:6px"
                                alt="banner">
                        @else
                            <img id="bannerPrev" src=""
                                style="height:60px;border-radius:8px;border:1px solid #eef2f6;margin-top:6px;display:none"
                                alt="banner">
                        @endif
                    </div>
                </div>


                {{-- SEO --}}
                <span class="sec-label">SEO Settings</span>
                <div class="form-grid">
                    <div class="fgrp">
                        <label>Meta Title <span style="font-weight:400;color:#7a8fa6">(max 70 chars)</span></label>
                        <input type="text" name="meta_title" maxlength="70" id="metaTitle"
                            value="{{ old('meta_title', $isEdit ? $category->meta_title : '') }}"
                            placeholder="Men's T-Shirts Online India | The Trend Theory">
                        <div class="hint"><span id="mtCount">0</span>/70 characters</div>
                    </div>

                    <div class="fgrp">
                        <label>Meta Keywords</label>
                        <input type="text" name="meta_keywords"
                            value="{{ old('meta_keywords', $isEdit ? $category->meta_keywords ?? '' : '') }}"
                            placeholder="mens tshirt, oversized tee, cotton tshirt">
                    </div>

                    <div class="fgrp span2">
                        <label>Meta Description <span style="font-weight:400;color:#7a8fa6">(max 170 chars)</span></label>
                        <textarea name="meta_description" rows="2" maxlength="170" id="metaDesc"
                            placeholder="Shop men's t-shirts online. Oversized, graphic, plain tees. Free shipping above ₹999.">{{ old('meta_description', $isEdit ? $category->meta_description : '') }}</textarea>
                        <div class="hint"><span id="mdCount">0</span>/170 characters</div>
                    </div>
                </div>

                {{-- VISIBILITY --}}
                <span class="sec-label">Visibility & Status</span>
                <div class="toggle-row">
                    <label class="ftog">
                        <input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', $isEdit ? $category->is_active : true) ? 'checked' : '' }}>
                        <span>Active</span>
                    </label>
                    <label class="ftog">
                        <input type="checkbox" name="show_in_nav" value="1"
                            {{ old('show_in_nav', $isEdit ? $category->show_in_nav : true) ? 'checked' : '' }}>
                        <span>Show in Navigation</span>
                    </label>
                    <label class="ftog">
                        <input type="checkbox" name="show_in_home" value="1"
                            {{ old('show_in_home', $isEdit ? $category->show_in_home : false) ? 'checked' : '' }}>
                        <span>Show on Home Page</span>
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" id="submitBtn" class="btn-save">
                        <i class="bi bi-check-lg"></i>
                        {{ $isEdit ? 'Update Category' : 'Save Category' }}
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Image upload integration: removed ImageKit async upload.
            // Backend already uploads selected file to Cloudinary on form submit (CategoryController).
            // So here we only handle local preview.
        </script>
        <script>
            // Image previews
            function livePreview(inputId, previewId) {
                var inp = document.getElementById(inputId);
                var prev = document.getElementById(previewId);
                if (!inp || !prev) return;

                // If input is text/URL => use value.
                // If input is file => use FileReader preview.
                if (inp.type === 'file') {
                    inp.addEventListener('change', function() {
                        var file = this.files && this.files[0] ? this.files[0] : null;
                        if (!file) {
                            prev.style.display = 'none';
                            prev.removeAttribute('src');
                            return;
                        }
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            prev.src = e.target.result;
                            prev.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    });
                } else {
                    inp.addEventListener('input', function() {
                        if (this.value) {
                            prev.src = this.value;
                            prev.style.display = 'block';
                        } else {
                            prev.style.display = 'none';
                        }
                    });
                }
            }
            // File inputs preview
            livePreview('file_image', 'imgPrev');
            livePreview('file_banner', 'bannerPrev');


            // Hidden inputs: no need for preview listeners


            // Char counters
            function charCounter(inputId, countId) {
                var el = document.getElementById(inputId);
                var cnt = document.getElementById(countId);
                if (!el || !cnt) return;
                cnt.textContent = el.value.length;
                el.addEventListener('input', function() {
                    cnt.textContent = this.value.length;
                });
            }
            charCounter('metaTitle', 'mtCount');
            charCounter('metaDesc', 'mdCount');
        </script>
    @endpush
@endsection
