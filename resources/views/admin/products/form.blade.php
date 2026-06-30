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

        function buildCategoryOptions($categories, $selectedId = null, $prefix = '')
        {
            $html = '';
            foreach ($categories as $category) {
                $selected = $selectedId == $category->id ? 'selected' : '';
                $html .=
                    '<option value="' .
                    $category->id .
                    '" ' .
                    $selected .
                    '>' .
                    $prefix .
                    $category->name .
                    '</option>';
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
        .fw {
            max-width: 1000px;
        }

        .pc {
            background: white;
            border: 1px solid #eef2f6;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 14px;
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

        .fg {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            padding: 14px 20px;
        }

        .fgrp {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .fgrp.full {
            grid-column: 1/-1;
        }

        .fgrp label {
            font-size: 11px;
            font-weight: 700;
            color: #7a8fa6;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .fc {
            padding: 10px 14px;
            border: 1.5px solid #e8edf5;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            width: 100%;
            background: white;
        }

        .fc:focus {
            border-color: #00285a;
        }

        .ferr {
            font-size: 11px;
            color: #ff3f6c;
            margin-top: 3px;
        }

        .tog {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .tog input {
            width: 16px;
            height: 16px;
            accent-color: #00285a;
        }

        .tog span {
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }

        .tr {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            padding: 14px 20px;
        }

        .fa {
            display: flex;
            gap: 10px;
            padding: 16px 20px;
            border-top: 1px solid #eef2f6;
            background: #fafbff;
        }

        .bs {
            background: #00285a;
            color: white;
            border: none;
            padding: 10px 26px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .bs:hover {
            background: #1e3f75;
        }

        .bc {
            background: white;
            color: #555;
            border: 1px solid #e8edf5;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
        }

        .vtog-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            background: #f8fafc;
            border-bottom: 1px solid #eef2f6;
            font-size: 13px;
            font-weight: 600;
            color: #00285a;
        }

        .vt {
            width: 100%;
            border-collapse: collapse;
        }

        .vt th {
            padding: 9px 10px;
            font-size: 10px;
            font-weight: 700;
            color: #7a8fa6;
            text-transform: uppercase;
            background: #f8fafc;
            border-bottom: 1px solid #eef2f6;
            text-align: left;
        }

        .vt td {
            padding: 7px 8px;
            border-bottom: 1px solid #f0f4f8;
            vertical-align: middle;
        }

        .vi {
            padding: 7px 10px;
            border: 1.5px solid #e8edf5;
            border-radius: 7px;
            font-size: 13px;
            font-family: inherit;
            outline: none;
            width: 100%;
        }

        .vi:focus {
            border-color: #00285a;
        }

        .ci {
            width: 42px;
            height: 34px;
            padding: 2px 4px;
            border: 1.5px solid #e8edf5;
            border-radius: 7px;
            cursor: pointer;
        }

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

        .bdel:hover {
            background: #ff3f6c;
            color: white;
        }

        .badd {
            background: #f0f4f8;
            color: #00285a;
            border: 1.5px solid #e8edf5;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin: 10px 20px;
        }

        .badd:hover {
            background: #e8f0fb;
            border-color: #00285a;
        }

        .izone {
            border: 2px dashed #d9dee6;
            border-radius: 12px;
            padding: 28px 20px;
            text-align: center;
            cursor: pointer;
            transition: .2s;
            background: #fafbff;
            margin: 0 20px 14px;
        }

        .izone:hover {
            border-color: #00285a;
            background: #f0f4ff;
        }

        .izone input {
            display: none;
        }

        .igrid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            gap: 10px;
            padding: 0 20px 14px;
        }

        .icard {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid #eef2f6;
            aspect-ratio: 4/5;
            background: #f8fafc;
        }

        .icard.main {
            border-color: #ffd700;
        }

        .icard img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .ibadge {
            position: absolute;
            top: 5px;
            left: 5px;
            background: #ffd700;
            color: #00285a;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 20px;
        }

        .iact {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, .55);
            display: flex;
            gap: 3px;
            padding: 4px;
            opacity: 0;
            transition: .15s;
        }

        .icard:hover .iact {
            opacity: 1;
        }

        .iab {
            flex: 1;
            padding: 3px;
            border: none;
            border-radius: 5px;
            font-size: 10px;
            font-weight: 700;
            cursor: pointer;
        }

        .category-hint {
            font-size: 11px;
            color: #7a8fa6;
            margin-top: 5px;
            padding: 5px 0;
        }

        .upload-msg {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .color-upload-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 12px;
            padding: 14px 20px 18px;
        }

        .color-upload-card {
            border: 1.5px dashed #d9dee6;
            border-radius: 12px;
            background: #fafbff;
            padding: 12px;
        }

        .color-upload-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 800;
            color: #00285a;
            margin-bottom: 8px;
        }

        .color-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 1px solid #d9dee6;
            flex-shrink: 0;
        }

        .color-upload-card input[type="file"] {
            width: 100%;
            font-size: 11px;
            color: #555;
        }

        @media(max-width:640px) {
            .fg {
                grid-template-columns: 1fr;
            }

            .fgrp.full {
                grid-column: 1;
            }
        }
    </style>

    <div class="fw">
        <form method="POST" action="{{ $isEdit ? route('admin.products.update', $product) : route('admin.products.store') }}"
            enctype="multipart/form-data" id="productForm">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            {{-- CARD 1: BASIC INFO --}}
            <div class="pc">
                <div class="ph">
                    <div class="pt">
                        {{ $isEdit ? 'EDIT: ' . Str::upper(Str::limit($product->name, 32)) : 'ADD NEW PRODUCT' }}</div>
                    <div style="display:flex;gap:8px">
                        @if ($isEdit)
                            <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="bc"
                                style="font-size:11px;padding:6px 12px"><i class="bi bi-eye"></i></a>
                        @endif
                        <a href="{{ route('admin.products.index') }}" class="bc"
                            style="font-size:11px;padding:6px 12px"><i class="bi bi-arrow-left"></i> Back</a>
                    </div>
                </div>

                @if ($errors->any())
                    <div
                        style="background:#fce4ec;color:#c62828;padding:12px 16px;margin:14px 20px 0;border-radius:10px;font-size:13px">
                        @foreach ($errors->all() as $e)
                            <div>• {{ $e }}</div>
                        @endforeach
                    </div>
                @endif
                @if (session('success'))
                    <div
                        style="background:#e8f5e9;color:#2e7d32;padding:10px 16px;margin:14px 20px 0;border-radius:10px;font-size:13px;font-weight:600">
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    </div>
                @endif

                <span class="sl">Basic Information</span>
                <div class="fg">
                    <div class="fgrp full">
                        <label>Product Name *</label>
                        <input class="fc" type="text" name="name"
                            value="{{ old('name', $isEdit ? $product->name : '') }}" required>
                    </div>
                    <div class="fgrp">
                        <label>Category *</label>
                        <select class="fc" name="category_id" required>
                            <option value="">-- Select Category --</option>
                            @php echo buildCategoryOptions($parentCategories, $selectedCategoryId, ''); @endphp
                        </select>
                    </div>
                    <div class="fgrp">
                        <label>SKU</label>
                        <input class="fc" type="text" name="sku"
                            value="{{ old('sku', $isEdit ? $product->sku : '') }}" placeholder="TTT-TS-001">
                    </div>
                    <div class="fgrp full">
                        <label>Short Description</label>
                        <input class="fc" type="text" name="short_description"
                            value="{{ old('short_description', $isEdit ? $product->short_description : '') }}">
                    </div>
                    <div class="fgrp full">
                        <label>Full Description</label>
                        <textarea class="fc" name="description" rows="5">{{ old('description', $isEdit ? $product->description : '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- CARD 2: PRICE + VARIANTS --}}
            <div class="pc">
                <div class="vtog-bar">
                    <input type="checkbox" name="has_variants" id="hasVar" value="1"
                        {{ old('has_variants', $isEdit ? $product->has_variants ?? false : false) ? 'checked' : '' }}
                        onchange="toggleVar(this.checked)">
                    <label for="hasVar" style="cursor:pointer"><i class="bi bi-layers"></i> Yeh product alag sizes /
                        colors mein available hai</label>
                </div>

                <div id="simpleSection">
                    <span class="sl">Price & Stock</span>
                    <div class="fg">
                        <div class="fgrp"><label>Selling Price (₹) *</label><input class="fc" type="number"
                                name="price" id="simplePrice" value="{{ old('price', $isEdit ? $product->price : '') }}"
                                min="0" step="0.01" required></div>
                        <div class="fgrp"><label>MRP / Original Price (₹)</label><input class="fc" type="number"
                                name="original_price"
                                value="{{ old('original_price', $isEdit ? $product->original_price : '') }}" min="0"
                                step="0.01"></div>
                        <div class="fgrp"><label>Cost Price (₹)</label><input class="fc" type="number"
                                name="cost_price" value="{{ old('cost_price', $isEdit ? $product->cost_price : '') }}"
                                min="0" step="0.01"></div>
                        <div class="fgrp"><label>Stock Quantity *</label><input class="fc" type="number"
                                name="stock" id="simpleStock" value="{{ old('stock', $isEdit ? $product->stock : 0) }}"
                                min="0" required></div>
                    </div>
                </div>

                    <div id="varSection" style="display:none">
                    <div class="ph">
                        <div class="pt">SIZE + COLOR VARIANTS</div>
                        <div style="display:flex;gap:10px;align-items:center">
                            <button type="button" class="badd" style="margin:0" onclick="addRow()"><i class="bi bi-plus-lg"></i> Add Row</button>
                            <button type="button" class="bs" style="padding:10px 18px" onclick="generateVariants()"><i class="bi bi-diagram-3"></i> Generate Variants</button>
                        </div>
                    </div>

                    {{-- MATRIX GENERATOR (Black has 5 sizes, White has 6 sizes, etc.) --}}
                    <div class="fg" style="padding-top:16px">
                        <div class="fgrp full">
                            <label>Generate by Color (select sizes separately for each color)</label>
                            <div class="category-hint">Example: Black(5 sizes) + White(6 sizes) => 11 rows will be created.</div>
                        </div>

                        <div class="fgrp full">
                            <div style="display:flex;gap:12px;flex-wrap:wrap">
                                @foreach ($colorsArray as $color)
                                    <label class="tog" style="gap:10px">
                                        <input type="checkbox" class="colorPick" value="{{ $color->name }}" data-color-hex="{{ $color->hex_code }}" onchange="toggleColorBlock(this)" />
                                        <span style="display:inline-flex;align-items:center;gap:8px">
                                            <span style="width:14px;height:14px;border-radius:4px;border:1px solid #e8edf5;background:{{ $color->hex_code }}"></span>
                                            {{ $color->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Per-color size checkboxes --}}
                        <div class="fgrp full">
                            <div id="colorSizeBlocks" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px">
                                @foreach ($colorsArray as $color)
                                    <div class="pc" style="margin:0;padding:0;display:none" data-color-block="{{ $color->name }}" id="block_{{ $color->name }}">
                                        <div class="ph" style="border-bottom:0">
                                            <div class="pt" style="letter-spacing:.4px;font-size:12px">
                                                {{ $color->name }}
                                            </div>
                                        </div>
                                        <div style="padding:10px 20px 16px">
                                            <div style="display:flex;flex-wrap:wrap;gap:10px">
                                                @foreach ($sizesArray as $size)
                                                    <label class="tog" style="cursor:pointer">
                                                        <input type="checkbox" class="sizePick" value="{{ $size->name }}" data-for-color="{{ $color->name }}" />
                                                        <span>{{ $size->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="fgrp full">
                            <div class="category-hint">After clicking <b>Generate Variants</b>, price/stock inputs will be empty for new rows (except swatch).</div>
                        </div>
                    </div>

                    <div style="overflow-x:auto;padding:0 20px 14px">
                        <table class="vt">
                            <thead>
                                <tr>
                                    <th style="width:100px">Size</th>
                                    <th style="width:100px">Color</th>
                                    <th style="width:50px">Swatch</th>
                                    <th style="width:100px">Price ₹</th>
                                    <th style="width:100px">MRP ₹</th>
                                    <th style="width:100px">Cost ₹</th>
                                    <th style="width:80px">Stock</th>
                                    <th style="width:120px">SKU</th>
                                    <th style="width:40px"></th>
                                </tr>
                            </thead>
                            <tbody id="varBody">
                                @if ($isEdit && ($product->has_variants ?? false) && $variants->count())
                                    @foreach ($variants as $i => $v)
                                        <tr class="vrow">
                                            <td><select class="vi" name="variants[{{ $i }}][size]">
                                                    <option value="">-- Size --</option>
                                                    @foreach ($sizesArray as $size)
                                                        <option value="{{ $size->name }}"
                                                            {{ $v->size == $size->name ? 'selected' : '' }}>
                                                            {{ $size->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><select class="vi" name="variants[{{ $i }}][color]"
                                                    onchange="updateSwatch(this)">
                                                    <option value="">-- Color --</option>
                                                    @foreach ($colorsArray as $color)
                                                        <option value="{{ $color->name }}"
                                                            data-hex="{{ $color->hex_code }}"
                                                            {{ $v->color == $color->name ? 'selected' : '' }}>
                                                            {{ $color->name }}</option>
                                                    @endforeach
                                                </select></td>
                                            <td><input class="ci" type="color"
                                                    name="variants[{{ $i }}][color_hex]"
                                                    value="{{ $v->color_hex ?? '#000000' }}"></td>
                                            <td><input class="vi" type="number"
                                                    name="variants[{{ $i }}][price]"
                                                    value="{{ $v->price }}" min="0" step="0.01" required>
                                            </td>
                                            <td><input class="vi" type="number"
                                                    name="variants[{{ $i }}][original_price]"
                                                    value="{{ $v->original_price }}" min="0" step="0.01"></td>
                                            <td><input class="vi" type="number"
                                                    name="variants[{{ $i }}][cost_price]"
                                                    value="{{ $v->cost_price ?? '' }}" min="0" step="0.01">
                                            </td>
                                            <td><input class="vi" type="number"
                                                    name="variants[{{ $i }}][stock]"
                                                    value="{{ $v->stock }}" min="0" required></td>
                                            <td><input class="vi" type="text"
                                                    name="variants[{{ $i }}][sku]"
                                                    value="{{ $v->sku }}" placeholder="SKU"></td>
                                            <input type="hidden" name="variants[{{ $i }}][id]"
                                                value="{{ $v->id }}">
                                            <td><button type="button" class="bdel"
                                                    onclick="this.closest('tr').remove()">×</button></td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- CARD 3: IMAGES --}}
            <div class="pc">
                <div class="ph">
                    <div class="pt">PRODUCT IMAGES</div><span style="font-size:12px;color:#7a8fa6"><i
                            class="bi bi-cloud-upload"></i> ImageKit CDN</span>
                </div>

                @if ($colors->count())
                    <span class="sl">Color-wise Images</span>
                    <div style="padding:8px 20px 0;font-size:12px;color:#7a8fa6">
                        Product add/update karte time yahin images select karo. Black box me black product images, White box me white product images.
                    </div>
                    <div class="color-upload-grid">
                        @foreach ($colors as $color)
                            <div class="color-upload-card">
                                <div class="color-upload-title">
                                    <span class="color-dot" style="background:{{ $color->hex_code }}"></span>
                                    {{ $color->name }}
                                </div>
                                <input type="file"
                                    name="color_images[{{ $color->id }}][]"
                                    accept="image/jpeg,image/jpg,image/png,image/webp"
                                    multiple>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if (!$isEdit)
                    <div style="padding:16px 20px">
                        <div
                            style="background:#fffbe6;border:1px solid #ffd700;border-radius:10px;padding:14px;font-size:13px">
                            <i class="bi bi-info-circle-fill"></i> Color-wise images product save hote hi upload ho jayengi. Extra images baad me edit page se bhi add kar sakte ho.</div>
                    </div>
                @else
                    @if (($product->has_variants ?? false) && $colors->count())
                        <div style="padding:14px 20px 0">
                            <label style="font-size:11px;font-weight:700;color:#7a8fa6;text-transform:uppercase;letter-spacing:.5px">
                                Image Color
                            </label>
                            <select class="fc" id="imageColorSelect" style="margin-top:6px;max-width:260px">
                                <option value="">General product image</option>
                                @foreach ($colors as $color)
                                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                                @endforeach
                            </select>
                            <div class="category-hint">Black image upload karne se pehle Black select karo, White image ke liye White select karo.</div>
                        </div>
                    @endif
                    <div class="igrid" id="igrid">
                        @foreach ($images as $img)
                            <div class="icard {{ $img->is_primary ? 'main' : '' }}" id="img_{{ $img->id }}">
                                <img src="{{ $img->url }}" alt="{{ $img->alt_text ?? 'Product image' }}">
                                @if ($img->is_primary)
                                    <div class="ibadge">MAIN</div>
                                @endif
                                <div class="iact">
                                    @if (!$img->is_primary)
                                        <button type="button" class="iab" style="background:#ffd700;color:#00285a"
                                            onclick="setMain({{ $img->id }})">Main</button>
                                    @endif
                                    <button type="button" class="iab" style="background:#ff3f6c;color:white"
                                    onclick="deleteImage({{ $img->id }})">Del</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="izone" id="dropzone">
                        <i class="bi bi-cloud-upload"
                            style="font-size:28px;color:#b0bec5;display:block;margin-bottom:8px"></i>
                        <div style="font-size:13px;color:#7a8fa6"><strong style="color:#00285a">Click</strong> ya drag &
                            drop images</div>
                        <div style="font-size:11px;color:#b0bec5;margin-top:4px">JPG, PNG, WEBP · Max 5MB each</div>
                        <input type="file" id="fileInput" accept="image/jpeg,image/jpg,image/png,image/webp" multiple
                            style="display:none">
                    </div>
                @endif
            </div>

            {{-- CARD 4: SEO + STATUS --}}
            <div class="pc">
                <div class="ph">
                    <div class="pt">SEO & STATUS</div>
                </div>
                <div class="fg">
                    <div class="fgrp"><label>Meta Title</label><input class="fc" type="text" name="meta_title"
                            maxlength="70" value="{{ old('meta_title', $isEdit ? $product->meta_title : '') }}"></div>
                    <div class="fgrp"><label>Meta Keywords</label><input class="fc" type="text"
                            name="meta_keywords"
                            value="{{ old('meta_keywords', $isEdit ? $product->meta_keywords : '') }}"></div>
                    <div class="fgrp"><label>OG Image URL</label><input class="fc" type="text" name="og_image"
                            value="{{ old('og_image', $isEdit ? $product->og_image : '') }}"></div>
                    <div class="fgrp full"><label>Meta Description</label>
                        <textarea class="fc" name="meta_description" rows="2" maxlength="170">{{ old('meta_description', $isEdit ? $product->meta_description : '') }}</textarea>
                    </div>
                </div>
                <span class="sl">Tags & Visibility</span>
                <div class="tr">
                    <label class="tog"><input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', $isEdit ? $product->is_active ?? true : true) ? 'checked' : '' }}><span>Active</span></label>
                    <label class="tog"><input type="checkbox" name="is_new" value="1"
                            {{ old('is_new', $isEdit ? $product->is_new ?? false : false) ? 'checked' : '' }}><span>New
                            Arrival</span></label>
                    <label class="tog"><input type="checkbox" name="is_featured" value="1"
                            {{ old('is_featured', $isEdit ? $product->is_featured ?? false : false) ? 'checked' : '' }}><span>Featured</span></label>
                    <label class="tog"><input type="checkbox" name="is_trending" value="1"
                            {{ old('is_trending', $isEdit ? $product->is_trending ?? false : false) ? 'checked' : '' }}><span>Trending</span></label>
                    <label class="tog"><input type="checkbox" name="is_on_sale" value="1"
                            {{ old('is_on_sale', $isEdit ? $product->is_on_sale ?? false : false) ? 'checked' : '' }}><span>On
                            Sale</span></label>
                </div>
                <div class="fa">
                    <button type="submit" class="bs" id="productSubmitBtn"><i class="bi bi-check-lg"></i>
                        {{ $isEdit ? 'Update Product' : 'Save Product' }}</button>
                    <a href="{{ route('admin.products.index') }}" class="bc">Cancel</a>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            var varIdx = {{ $isEdit && ($product->has_variants ?? false) && $variants->count() ? $variants->count() : 0 }};
            var CSRF = document.querySelector('meta[name="csrf-token"]').content;
            var PRODUCT_ID = {{ $isEdit ? $product->id : 'null' }};

            // Size and color data from PHP
            var sizes = @json($sizesArray->pluck('name'));
            var colors = @json(
                $colorsArray->map(function ($c) {
                    return ['name' => $c->name, 'hex' => $c->hex_code];
                }));

            function toggleVar(on) {
                document.getElementById('varSection').style.display = on ? 'block' : 'none';
                document.getElementById('simpleSection').style.display = on ? 'none' : 'block';
                var sp = document.getElementById('simplePrice');
                var ss = document.getElementById('simpleStock');
                if (sp) sp.required = !on;
                if (ss) ss.required = !on;
                if (on && document.getElementById('varBody').children.length === 0) addRow();
            }
            toggleVar(document.getElementById('hasVar').checked);

            function updateSwatch(select) {
                var hex = select.options[select.selectedIndex]?.getAttribute('data-hex') || '#000000';
                var swatch = select.closest('tr').querySelector('input[type="color"]');
                if (swatch) swatch.value = hex;
            }

            function toggleColorBlock(cb) {
                var block = document.getElementById('block_' + CSS.escape(cb.value));
                if (!block) return;
                block.style.display = cb.checked ? 'block' : 'none';
            }

            function generateVariants() {
                // Build rows from selected colors + selected sizes per color
                var body = document.getElementById('varBody');
                if (!body) return;

                // Clear existing rows (keep manual ones only if you want; here we replace)
                body.innerHTML = '';

                var selectedColorBlocks = document.querySelectorAll('.colorPick:checked');
                var selectedAny = selectedColorBlocks.length > 0;
                if (!selectedAny) {
                    alert('Please select at least 1 color');
                    return;
                }

                selectedColorBlocks.forEach(function(colorCb) {
                    var colorName = colorCb.value;
                    var colorHex = colorCb.getAttribute('data-color-hex') || '#000000';
                    var block = document.getElementById('block_' + CSS.escape(colorName));
                    if (!block) return;

                    var sizeChecks = block.querySelectorAll('.sizePick:checked');
                    if (!sizeChecks.length) return;

                    sizeChecks.forEach(function(sizeCb) {
                        var sizeName = sizeCb.value;
                        var i = varIdx++;
                        var tr = document.createElement('tr');
                        tr.className = 'vrow';
                        tr.innerHTML = `
                            <td><select class="vi" name="variants[${i}][size]" style="width:100%">
                                <option value="">-- Size --</option>
                                ${sizes.map(function(s) {
                                    return '<option value="' + s + '" ' + (s === sizeName ? 'selected' : '') + '>' + s + '</option>'; 
                                }).join('')}
                            </select></td>
                            <td><select class="vi" name="variants[${i}][color]" style="width:100%" onchange="updateSwatch(this)">
                                <option value="">-- Color --</option>
                                ${colors.map(function(c) {
                                    return '<option value="' + c.name + '" data-hex="' + c.hex + '" ' + (c.name === colorName ? 'selected' : '') + '>' + c.name + '</option>'; 
                                }).join('')}
                            </select></td>
                            <td><input class="ci" type="color" name="variants[${i}][color_hex]" value="${colorHex}"></td>
                            <td><input class="vi" type="number" name="variants[${i}][price]" placeholder="Price" min="0" step="0.01" required></td>
                            <td><input class="vi" type="number" name="variants[${i}][original_price]" placeholder="MRP" min="0" step="0.01"></td>
                            <td><input class="vi" type="number" name="variants[${i}][cost_price]" placeholder="Cost" min="0" step="0.01"></td>
                            <td><input class="vi" type="number" name="variants[${i}][stock]" placeholder="Stock" min="0" required></td>
                            <td><input class="vi" type="text" name="variants[${i}][sku]" placeholder="SKU"></td>
                            <td><button type="button" class="bdel" onclick="this.closest('tr').remove()">×</button></td>
                        `;
                        body.appendChild(tr);
                    });
                });

                if (body.children.length === 0) {
                    alert('Select sizes for the chosen colors');
                }
            }

            // Auto open blocks based on checked colors when editing
            document.addEventListener('DOMContentLoaded', function() {
                var picks = document.querySelectorAll('.colorPick');
                picks.forEach(function(cb) {
                    toggleColorBlock(cb);
                });
            });

            
            function addRow() {
                var i = varIdx++;
                var sizeOptions = '<option value="">-- Size --</option>' + sizes.map(function(s) {
                    return '<option value="' + s + '">' + s + '</option>';
                }).join('');
                var colorOptions = '<option value="">-- Color --</option>' + colors.map(function(c) {
                    return '<option value="' + c.name + '" data-hex="' + (c.hex || '#000000') + '">' + c.name +
                        '</option>';
                }).join('');

                var tr = document.createElement('tr');
                tr.className = 'vrow';
                tr.innerHTML = `
            <td><select class="vi" name="variants[${i}][size]" style="width:100%">${sizeOptions}</select></td>
            <td><select class="vi" name="variants[${i}][color]" style="width:100%" onchange="updateSwatch(this)">${colorOptions}</select></td>
            <td><input class="ci" type="color" name="variants[${i}][color_hex]" value="#000000"></td>
            <td><input class="vi" type="number" name="variants[${i}][price]" placeholder="Price" min="0" step="0.01" required></td>
            <td><input class="vi" type="number" name="variants[${i}][original_price]" placeholder="MRP" min="0" step="0.01"></td>
            <td><input class="vi" type="number" name="variants[${i}][cost_price]" placeholder="Cost" min="0" step="0.01"></td>
            <td><input class="vi" type="number" name="variants[${i}][stock]" placeholder="Stock" min="0" required></td>
            <td><input class="vi" type="text" name="variants[${i}][sku]" placeholder="SKU"></td>
            <td><button type="button" class="bdel" onclick="this.closest('tr').remove()">×</button></td>
        `;
                document.getElementById('varBody').appendChild(tr);
            }


            function addRow() {
                var i = varIdx++;
                var sizeOptions = '<option value="">-- Size --</option>' + sizes.map(function(s) {
                    return '<option value="' + s + '">' + s + '</option>';
                }).join('');
                var colorOptions = '<option value="">-- Color --</option>' + colors.map(function(c) {
                    return '<option value="' + c.name + '" data-hex="' + (c.hex || '#000000') + '">' + c.name +
                        '</option>';
                }).join('');

                var tr = document.createElement('tr');
                tr.className = 'vrow';
                tr.innerHTML = `
            <td><select class="vi" name="variants[${i}][size]" style="width:100%">${sizeOptions}</select></td>
            <td><select class="vi" name="variants[${i}][color]" style="width:100%" onchange="updateSwatch(this)">${colorOptions}</select></td>
            <td><input class="ci" type="color" name="variants[${i}][color_hex]" value="#000000"></td>
            <td><input class="vi" type="number" name="variants[${i}][price]" placeholder="Price" min="0" step="0.01" required></td>
            <td><input class="vi" type="number" name="variants[${i}][original_price]" placeholder="MRP" min="0" step="0.01"></td>
            <td><input class="vi" type="number" name="variants[${i}][cost_price]" placeholder="Cost" min="0" step="0.01"></td>
            <td><input class="vi" type="number" name="variants[${i}][stock]" placeholder="Stock" min="0" required></td>
            <td><input class="vi" type="text" name="variants[${i}][sku]" placeholder="SKU"></td>
            <td><button type="button" class="bdel" onclick="this.closest('tr').remove()">×</button></td>
        `;
                document.getElementById('varBody').appendChild(tr);
            }

            document.getElementById('productForm').addEventListener('submit', function() {
                var btn = document.getElementById('productSubmitBtn');
                if (!btn || btn.disabled) return;

                btn.disabled = true;
                btn.style.opacity = '.75';
                btn.style.cursor = 'wait';
                btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';
            });

            @if ($isEdit)
                // ==================== IMAGE UPLOAD ====================
                var dropzone = document.getElementById('dropzone');
                var fileInput = document.getElementById('fileInput');
                var uploading = false;

                // Click on dropzone to select files
                dropzone.addEventListener('click', function() {
                    fileInput.click();
                });

                // Handle file selection
                fileInput.addEventListener('change', function(e) {
                    if (e.target.files.length > 0) {
                        uploadFiles(Array.from(e.target.files));
                        fileInput.value = '';
                    }
                });

                // Drag and drop
                dropzone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    dropzone.style.borderColor = '#00285a';
                    dropzone.style.background = '#f0f4ff';
                });

                dropzone.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    dropzone.style.borderColor = '#d9dee6';
                    dropzone.style.background = '#fafbff';
                });

                dropzone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    dropzone.style.borderColor = '#d9dee6';
                    dropzone.style.background = '#fafbff';
                    var files = Array.from(e.dataTransfer.files).filter(function(f) {
                        return f.type.startsWith('image/');
                    });
                    if (files.length > 0) uploadFiles(files);
                });

                function uploadFiles(files) {
                    if (uploading) return;
                    uploading = true;

                    var total = files.length;
                    var completed = 0;
                    showToast('Uploading ' + total + ' image(s)...', 'info');

                    files.forEach(function(file, index) {
                        if (file.size > 5 * 1024 * 1024) {
                            showToast(file.name + ' is too large (max 5MB)', 'error');
                            completed++;
                            if (completed === total) uploading = false;
                            return;
                        }

                        var formData = new FormData();
                        var imageColorSelect = document.getElementById('imageColorSelect');
                        var selectedColorId = imageColorSelect ? imageColorSelect.value : '';
                        var selectedColorName = imageColorSelect && imageColorSelect.selectedIndex >= 0
                            ? imageColorSelect.options[imageColorSelect.selectedIndex].text
                            : '';

                        formData.append('file', file);
                        formData.append('model_type', 'product');
                        formData.append('model_id', PRODUCT_ID);
                        formData.append('collection', 'default');
                        formData.append('is_primary', document.querySelectorAll('.icard').length === 0 ? '1' : '0');
                        if (selectedColorId) {
                            formData.append('color_id', selectedColorId);
                            formData.append('alt_text', selectedColorName);
                        }

                        fetch('{{ route('admin.media.upload') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': CSRF
                                },
                                body: formData
                            })
                            .then(function(response) {
                                return response.json();
                            })
                            .then(function(data) {
                                completed++;
                                if (data.success) {
                                    addImageCard(data.media);
                                    showToast('Uploaded: ' + (selectedColorId ? selectedColorName + ' image' : 'Image'), 'success');
                                } else {
                                    showToast('Upload failed: ' + (data.message || 'Unknown error'), 'error');
                                }
                                if (completed === total) uploading = false;
                            })
                            .catch(function(error) {
                                completed++;
                                showToast('Upload failed: ' + error.message, 'error');
                                if (completed === total) uploading = false;
                            });
                    });
                }

                function addImageCard(media) {
                    var grid = document.getElementById('igrid');
                    var div = document.createElement('div');
                    div.className = 'icard' + (media.is_primary ? ' main' : '');
                    div.id = 'img_' + media.id;
                    div.innerHTML = `
            <img src="${media.thumb_url || media.url}" alt="${media.alt_text || 'Product image'}">
            ${media.is_primary ? '<div class="ibadge">MAIN</div>' : ''}
            <div class="iact">
                ${!media.is_primary ? '<button type="button" class="iab" style="background:#ffd700;color:#00285a" onclick="setMain(' + media.id + ')">Main</button>' : ''}
                <button type="button" class="iab" style="background:#ff3f6c;color:white" onclick="deleteImage(' + media.id + ')">Del</button>
            </div>
        `;
                    grid.appendChild(div);
                }

                function setMain(id) {
                    fetch('/admin/media/' + id + '/primary', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': CSRF,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: '{}'
                        })
                        .then(function(r) {
                            return r.json();
                        })
                        .then(function(res) {
                            if (res.success) {
                                document.querySelectorAll('.icard').forEach(function(card) {
                                    card.classList.remove('main');
                                    var badge = card.querySelector('.ibadge');
                                    if (badge) badge.remove();
                                    var mainBtn = card.querySelector('.iab:first-child');
                                    if (mainBtn && mainBtn.textContent === 'Main') {
                                        card.querySelector('.iact').insertAdjacentHTML('afterbegin',
                                            '<button type="button" class="iab" style="background:#ffd700;color:#00285a" onclick="setMain(' +
                                            card.id.split('_')[1] + ')">Main</button>');
                                        mainBtn.remove();
                                    }
                                });
                                var card = document.getElementById('img_' + id);
                                if (card) {
                                    card.classList.add('main');
                                    card.insertAdjacentHTML('afterbegin', '<div class="ibadge">MAIN</div>');
                                    var mainBtn = card.querySelector('.iab:first-child');
                                    if (mainBtn && mainBtn.textContent === 'Main') mainBtn.remove();
                                }
                                showToast('Main image updated', 'success');
                            }
                        });
                }

                function deleteImage(id) {
                    if (!confirm('Delete this image?')) return;
                    fetch('{{ route('admin.media.destroy', ['media' => '__ID__']) }}'.replace('__ID__', id), {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': CSRF,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: '{}'
                        })
                        .then(function(r) {
                            return r.json();
                        })
                        .then(function(res) {
                            if (res.success) {
                                var el = document.getElementById('img_' + id);
                                if (el) el.remove();
                                showToast('Image deleted', 'success');
                            }
                        });
                }

                function showToast(message, type) {
                    var existing = document.querySelector('.upload-msg');
                    if (existing) existing.remove();

                    var toast = document.createElement('div');
                    toast.className = 'upload-msg';
                    toast.style.cssText =
                        'position:fixed;bottom:20px;right:20px;z-index:9999;padding:12px 20px;border-radius:8px;font-size:13px;font-weight:600;box-shadow:0 4px 12px rgba(0,0,0,0.15);' +
                        (type === 'error' ? 'background:#fce4ec;color:#c62828;' :
                            type === 'success' ? 'background:#e8f5e9;color:#2e7d32;' :
                            'background:#fffbe6;color:#854d0e;');
                    toast.textContent = message;
                    document.body.appendChild(toast);
                    setTimeout(function() {
                        toast.remove();
                    }, 3000);
                }
            @endif
        </script>
    @endpush
@endsection
