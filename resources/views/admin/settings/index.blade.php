{{-- resources/views/admin/settings/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Settings')
@section('content')

    <style>
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .settings-tab {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #eef2f6;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 10px 20px;
            background: none;
            border: none;
            font-size: 13px;
            font-weight: 600;
            color: #7a8fa6;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .tab-btn.active {
            color: #00285a;
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: #00285a;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        /* Slide Item Styles */
        .slide-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s;
        }

        .slide-item:hover {
            background: #fafbff;
        }

        .slide-image {
            width: 70px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e8edf5;
        }

        .slide-info {
            flex: 1;
        }

        .slide-title {
            font-weight: 600;
            font-size: 14px;
            color: #00285a;
            margin-bottom: 4px;
        }

        .slide-meta {
            font-size: 11px;
            color: #7a8fa6;
        }

        .slide-actions {
            display: flex;
            gap: 8px;
        }

        /* Media Grid Styles */
        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .media-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
        }

        .media-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .media-preview {
            position: relative;
            width: 100%;
            height: 180px;
            background: #f5f5f5;
            overflow: hidden;
            cursor: pointer;
        }

        .media-preview img,
        .media-preview video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .media-type-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 600;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            z-index: 1;
        }

        .media-type-badge.video {
            background: #dc3545;
        }

        .media-type-badge.image {
            background: #28a745;
        }

        .play-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 40px;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .media-preview:hover .play-icon {
            opacity: 1;
        }

        .media-info {
            padding: 12px;
        }

        .media-title {
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 4px;
            color: #00285a;
        }

        .media-meta {
            font-size: 10px;
            color: #7a8fa6;
        }

        .media-actions {
            display: flex;
            gap: 6px;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid #eef2f6;
        }

        /* Alert Styles */
        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            border-left: 4px solid #2e7d32;
        }

        .alert-error {
            background: #fce4ec;
            color: #c62828;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            border-left: 4px solid #c62828;
        }

        /* File Upload Styles */
        .file-upload-area {
            position: relative;
            margin-top: 5px;
        }

        .file-upload-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #f8fafc;
            border: 1.5px dashed #e8edf5;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 13px;
            color: #7a8fa6;
        }

        .file-upload-label:hover {
            border-color: #00285a;
            background: #f0f4ff;
        }

        .file-upload-label i {
            font-size: 16px;
        }

        .file-upload-input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .image-preview {
            margin-top: 10px;
            display: none;
            position: relative;
            display: inline-block;
        }

        .image-preview img {
            max-height: 80px;
            border-radius: 8px;
            border: 1px solid #e8edf5;
        }

        .image-preview .remove-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff3f6c;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 11px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Button Styles */
        .btn-admin {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            text-decoration: none;
        }

        .btn-navy {
            background: #00285a;
            color: white;
        }

        .btn-navy:hover {
            background: #1e3f75;
            transform: translateY(-1px);
        }

        .btn-light {
            background: white;
            color: #555;
            border: 1px solid #e8edf5;
        }

        .btn-light:hover {
            background: #f8fafc;
            border-color: #00285a;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 11px;
        }

        .btn-danger {
            background: #fee2e2;
            color: #c62828;
        }

        .btn-danger:hover {
            background: #fce4ec;
            color: #991b1b;
        }

        .btn-block {
            width: 100%;
            justify-content: center;
        }

        .form-grp {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-grp label {
            font-size: 11px;
            font-weight: 700;
            color: #7a8fa6;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control-admin {
            padding: 10px 14px;
            border: 1.5px solid #e8edf5;
            border-radius: 10px;
            font-size: 14px;
            outline: none;
            transition: 0.15s;
            width: 100%;
            background: white;
        }

        .form-control-admin:focus {
            border-color: #00285a;
        }

        .hint {
            font-size: 11px;
            color: #7a8fa6;
            margin-top: 3px;
        }

        .form-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .form-toggle input {
            width: 16px;
            height: 16px;
            accent-color: #00285a;
            cursor: pointer;
        }

        .form-toggle span {
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eef2f6;
        }

        .filter-btns {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 6px 14px;
            background: #f8fafc;
            border: 1px solid #e8edf5;
            border-radius: 20px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-btn.active {
            background: #00285a;
            color: white;
            border-color: #00285a;
        }

        .upload-area {
            border: 2px dashed #e8edf5;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            background: #fafbff;
            transition: all 0.2s;
            cursor: pointer;
        }

        .upload-area:hover {
            border-color: #00285a;
            background: #f0f4ff;
        }

        @media (max-width: 768px) {
            .settings-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div>
        @if (session('success'))
            <div class="alert-success">
                <i class="bi bi-check-circle-fill"></i> {!! session('success') !!}
            </div>
        @endif

        @if (session('error'))
            <div class="alert-error">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
            </div>
        @endif

        <div class="settings-tab">
            <button class="tab-btn active" onclick="showTab('general')">General Settings</button>
            <button class="tab-btn" onclick="showTab('hero')">Hero Slides</button>
            <button class="tab-btn" onclick="showTab('media')">Video & Image Gallery</button>
            <button class="tab-btn" onclick="showTab('seo')">SEO Settings</button>
            <button class="tab-btn" onclick="showTab('social')">Social Media</button>
            <button class="tab-btn" onclick="showTab('system')">System</button>
        </div>

        {{-- =============================================== --}}
        {{-- TAB 1: GENERAL SETTINGS (Only ONE) --}}
        {{-- =============================================== --}}
        <div id="generalTab" class="tab-pane active">
            <div class="settings-grid">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <div class="admin-card-title">🏢 SITE INFORMATION</div>
                    </div>
                    <div style="padding:24px">
                        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                            @csrf
                            <div style="display:flex;flex-direction:column;gap:14px">
                                @foreach ([
            'site_name' => 'Site Name',
            'site_tagline' => 'Tagline',
            'phone' => 'Phone Number',
            'email' => 'Email Address',
            'address' => 'Business Address',
            'currency_symbol' => 'Currency Symbol',
            'currency_code' => 'Currency Code (INR/USD)',
            'timezone' => 'Timezone',
        ] as $key => $label)
                                    <div class="form-grp">
                                        <label>{{ $label }}</label>
                                        <input type="text" name="{{ $key }}" class="form-control-admin"
                                            value="{{ $settings[$key] ?? '' }}" placeholder="{{ $label }}">
                                    </div>
                                @endforeach

                                {{-- Logo Upload --}}
                                <div class="form-grp">
                                    <label>Logo</label>
                                    <div class="file-upload-area">
                                        <label class="file-upload-label">
                                            <i class="bi bi-cloud-upload"></i> Choose Logo
                                            <input type="file" name="logo_file" class="file-upload-input"
                                                accept="image/jpeg,image/jpg,image/png,image/webp"
                                                onchange="previewImage(this, 'logo_preview', 'logo_preview_img')">
                                        </label>
                                    </div>
                                    <div class="hint">Max: 2MB | Recommended: 200x60px</div>
                                    <div id="logo_preview" class="image-preview">
                                        <img id="logo_preview_img" src="">
                                        <button type="button" class="remove-btn"
                                            onclick="clearImageUpload('logo_file', 'logo_preview')">×</button>
                                    </div>
                                    @if (!empty($settings['logo']))
                                        <div class="existing-image">
                                            <img src="https://ik.imagekit.io/zjhpv2mbz/{{ $settings['logo'] }}"
                                                alt="Current Logo">
                                            <span style="font-size:11px; color:#7a8fa6; margin-left:8px;">Current
                                                Logo</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Favicon Upload --}}
                                <div class="form-grp">
                                    <label>Favicon</label>
                                    <div class="file-upload-area">
                                        <label class="file-upload-label">
                                            <i class="bi bi-cloud-upload"></i> Choose Favicon
                                            <input type="file" name="favicon_file" class="file-upload-input"
                                                accept="image/x-icon,image/png"
                                                onchange="previewImage(this, 'favicon_preview', 'favicon_preview_img')">
                                        </label>
                                    </div>
                                    <div class="hint">Max: 1MB | Format: .ico or .png</div>
                                    <div id="favicon_preview" class="image-preview">
                                        <img id="favicon_preview_img" src="">
                                        <button type="button" class="remove-btn"
                                            onclick="clearImageUpload('favicon_file', 'favicon_preview')">×</button>
                                    </div>
                                </div>

                                <button type="submit" class="btn-admin btn-navy">
                                    <i class="bi bi-check-lg"></i> Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-header">
                        <div class="admin-card-title">💰 BUSINESS SETTINGS</div>
                    </div>
                    <div style="padding:24px">
                        <form method="POST" action="{{ route('admin.settings.update') }}">
                            @csrf
                            <div style="display:flex;flex-direction:column;gap:14px">
                                <div class="form-grp">
                                    <label>Free Shipping Above (₹)</label>
                                    <input type="number" name="shipping_free_above" class="form-control-admin"
                                        value="{{ $settings['shipping_free_above'] ?? 999 }}" step="0.01">
                                </div>
                                <div class="form-grp">
                                    <label>Flat Shipping Rate (₹)</label>
                                    <input type="number" name="shipping_rate" class="form-control-admin"
                                        value="{{ $settings['shipping_rate'] ?? 50 }}" step="0.01">
                                </div>
                                <div class="form-grp">
                                    <label>Return Policy (Days)</label>
                                    <input type="number" name="return_days" class="form-control-admin"
                                        value="{{ $settings['return_days'] ?? 7 }}">
                                </div>
                                <div class="form-grp">
                                    <label>Tax Rate (%)</label>
                                    <input type="number" name="tax_rate" class="form-control-admin"
                                        value="{{ $settings['tax_rate'] ?? 18 }}" step="0.01">
                                </div>
                                <div class="form-grp">
                                    <label>Minimum Order Amount (₹)</label>
                                    <input type="number" name="min_order_amount" class="form-control-admin"
                                        value="{{ $settings['min_order_amount'] ?? 0 }}">
                                </div>
                                <button type="submit" class="btn-admin btn-navy">
                                    <i class="bi bi-check-lg"></i> Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- =============================================== --}}
        {{-- TAB 2: HERO SLIDES --}}
        {{-- =============================================== --}}
        <div id="heroTab" class="tab-pane">
            <div class="settings-grid">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <div class="admin-card-title">📸 HERO SLIDES ({{ $heroSlides->count() }})</div>
                        <button type="button" class="btn-admin btn-navy btn-sm"
                            onclick="document.getElementById('addSlideForm').scrollIntoView({behavior:'smooth'})">
                            <i class="bi bi-plus-lg"></i> Add New
                        </button>
                    </div>
                    <div style="max-height:500px; overflow-y:auto">
                        @forelse($heroSlides as $slide)
                            <div class="slide-item">
                                @if ($slide->image)
                                    <img src="{{ $slide->image_url }}" class="slide-image">
                                @endif
                                <div class="slide-info">
                                    <div class="slide-title">{{ $slide->title }}</div>
                                    <div class="slide-meta">
                                        Order: {{ $slide->sort_order }} |
                                        {{ $slide->is_active ? '✓ Active' : '✗ Inactive' }}
                                        @if ($slide->button_text)
                                            | Button: {{ $slide->button_text }}
                                        @endif
                                    </div>
                                </div>
                                <div class="slide-actions">
                                    <button type="button" class="btn-admin btn-light btn-sm"
                                        onclick='editSlide({{ $slide->id }}, {{ json_encode($slide->title) }}, {{ json_encode($slide->subtitle) }}, {{ json_encode($slide->image) }}, {{ json_encode($slide->button_text) }}, {{ json_encode($slide->button_link) }}, {{ $slide->sort_order }}, {{ $slide->is_active ? 'true' : 'false' }})'>
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <form method="POST" action="{{ route('admin.settings.destroySlide', $slide) }}"
                                        onsubmit="return confirm('Delete this slide?')" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-admin btn-danger btn-sm">
                                            <i class="bi bi-trash3"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div style="padding:40px;text-align:center;color:#7a8fa6">
                                <i class="bi bi-images" style="font-size:40px;opacity:0.5"></i>
                                <p>No hero slides yet. Add your first slide!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="admin-card" id="addSlideForm">
                    <div class="admin-card-header">
                        <div class="admin-card-title" id="formTitle">➕ ADD NEW SLIDE</div>
                    </div>
                    <div style="padding:20px">
                        <form method="POST" action="{{ route('admin.settings.storeSlide') }}" id="slideForm"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="slide_id" id="slide_id">
                            <div style="display:flex;flex-direction:column;gap:12px">
                                <div class="form-grp">
                                    <label>Title *</label>
                                    <input type="text" name="title" id="slide_title" class="form-control-admin"
                                        required placeholder="e.g., NEW ARRIVALS">
                                </div>

                                <div class="form-grp">
                                    <label>Subtitle</label>
                                    <input type="text" name="subtitle" id="slide_subtitle" class="form-control-admin"
                                        placeholder="Discover the latest styles">
                                </div>

                                <div class="form-grp">
                                    <label>Desktop Image *</label>
                                    <div class="file-upload-area">
                                        <label class="file-upload-label">
                                            <i class="bi bi-cloud-upload"></i> Choose Desktop Image
                                            <input type="file" name="image_file" id="slide_image_file"
                                                class="file-upload-input"
                                                accept="image/jpeg,image/jpg,image/png,image/webp"
                                                onchange="previewImage(this, 'image_preview', 'image_preview_img')"
                                                required>
                                        </label>
                                    </div>
                                    <div class="hint">Recommended: 1200x600px | Max: 5MB</div>
                                    <div id="image_preview" class="image-preview">
                                        <img id="image_preview_img" src="">
                                        <button type="button" class="remove-btn"
                                            onclick="clearImageUpload('image_file', 'image_preview')">×</button>
                                    </div>
                                </div>

                                <div class="form-grp">
                                    <label>Mobile Image (Optional)</label>
                                    <div class="file-upload-area">
                                        <label class="file-upload-label">
                                            <i class="bi bi-cloud-upload"></i> Choose Mobile Image
                                            <input type="file" name="mobile_image_file" id="slide_mobile_image_file"
                                                class="file-upload-input"
                                                accept="image/jpeg,image/jpg,image/png,image/webp"
                                                onchange="previewImage(this, 'mobile_image_preview', 'mobile_image_preview_img')">
                                        </label>
                                    </div>
                                    <div class="hint">Recommended: 768x500px | Max: 5MB</div>
                                    <div id="mobile_image_preview" class="image-preview">
                                        <img id="mobile_image_preview_img" src="">
                                        <button type="button" class="remove-btn"
                                            onclick="clearImageUpload('mobile_image_file', 'mobile_image_preview')">×</button>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-grp">
                                        <label>Button Text</label>
                                        <input type="text" name="button_text" id="slide_button_text"
                                            class="form-control-admin" placeholder="SHOP NOW">
                                    </div>
                                    <div class="form-grp">
                                        <label>Button Link</label>
                                        <input type="text" name="button_link" id="slide_button_link"
                                            class="form-control-admin" placeholder="/shop or https://">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-grp">
                                        <label>Sort Order</label>
                                        <input type="number" name="sort_order" id="slide_order"
                                            class="form-control-admin" value="0" min="0">
                                    </div>
                                    <div class="form-grp"
                                        style="display:flex; align-items:center; gap:10px; padding-top:20px">
                                        <label class="form-toggle">
                                            <input type="checkbox" name="is_active" value="1" id="slide_active"
                                                checked>
                                            <span>Active</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="action-buttons">
                                    <button type="submit" class="btn-admin btn-navy" id="submitBtn">
                                        <i class="bi bi-plus-lg"></i> Add Slide
                                    </button>
                                    <button type="button" class="btn-admin btn-light" id="cancelEditBtn"
                                        onclick="resetSlideForm()" style="display:none">
                                        Cancel Edit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- =============================================== --}}
        {{-- TAB 3: VIDEO & IMAGE GALLERY (MEDIA) --}}
        {{-- =============================================== --}}
        <div id="mediaTab" class="tab-pane">
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title">🎬 VIDEO & IMAGE GALLERY</div>
                    <button type="button" class="btn-admin btn-navy btn-sm" onclick="showMediaUploadModal()">
                        <i class="bi bi-plus-lg"></i> Upload Media
                    </button>
                </div>
                <div style="padding:20px">
                    <div class="filter-btns">
                        <button class="filter-btn active" data-filter="all" onclick="filterMedia('all')">All</button>
                        <button class="filter-btn" data-filter="image" onclick="filterMedia('image')">Images</button>
                        <button class="filter-btn" data-filter="video" onclick="filterMedia('video')">Videos</button>
                    </div>

                    <div id="mediaGrid" class="media-grid">
    @forelse($media as $item)
        @php
            $fileType = str_starts_with($item->mime_type, 'video/') ? 'video' : 'image';
            $thumbnailUrl = $item->thumb_url ?? ($fileType === 'video' ? $item->url . '?tr=iv-1' : $item->url);
        @endphp
        <div class="media-card" data-id="{{ $item->id }}" data-type="{{ $fileType }}">
            <div class="media-preview" onclick="previewMedia({{ $item->id }})">
                <img src="{{ $thumbnailUrl }}" alt="{{ $item->alt_text ?? 'Media' }}">
                @if($fileType == 'video')
                    <div class="play-icon"><i class="bi bi-play-circle-fill"></i></div>
                @endif
                <span class="media-type-badge {{ $fileType }}">{{ strtoupper($fileType) }}</span>
            </div>
            <div class="media-info">
                <div class="media-title">{{ $item->alt_text ?? 'Untitled' }}</div>
                <div class="media-meta">{{ $item->created_at ? $item->created_at->format('d M Y') : 'N/A' }} • Order: {{ $item->sort_order }}</div>
                <div class="media-actions">
                    <button class="btn-admin btn-light btn-sm" onclick="editMedia({{ $item->id }})"><i class="bi bi-pencil"></i> Edit</button>
                    <button class="btn-admin btn-danger btn-sm" onclick="deleteMedia({{ $item->id }})"><i class="bi bi-trash3"></i> Delete</button>
                </div>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 60px; grid-column: 1/-1; color: #999;">
            <i class="bi bi-cloud-upload" style="font-size: 48px;"></i>
            <p style="margin-top: 15px;">No media uploaded yet. Click "Upload Media" to add images or videos.</p>
        </div>
    @endforelse
</div>
                </div>
            </div>
        </div>

        {{-- =============================================== --}}
        {{-- TAB 4: SEO SETTINGS --}}
        {{-- =============================================== --}}
        <div id="seoTab" class="tab-pane">
            <div class="settings-grid">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <div class="admin-card-title">🔍 SEO SETTINGS</div>
                    </div>
                    <div style="padding:24px">
                        <form method="POST" action="{{ route('admin.settings.update') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-grp"><label>Default Meta Title</label><input type="text"
                                    name="meta_title" class="form-control-admin"
                                    value="{{ $settings['meta_title'] ?? '' }}"></div>
                            <div class="form-grp"><label>Default Meta Description</label>
                                <textarea name="meta_description" class="form-control-admin" rows="3">{{ $settings['meta_description'] ?? '' }}</textarea>
                            </div>
                            <div class="form-grp"><label>OG Image</label>
                                <div class="file-upload-area">
                                    <label class="file-upload-label"><i class="bi bi-cloud-upload"></i> Choose OG Image
                                        <input type="file" name="og_image_file" class="file-upload-input"
                                            accept="image/*"
                                            onchange="previewImage(this, 'og_preview', 'og_preview_img')">
                                    </label>
                                </div>
                                <div id="og_preview" class="image-preview"><img id="og_preview_img"
                                        src=""><button type="button" class="remove-btn"
                                        onclick="clearImageUpload('og_image_file', 'og_preview')">×</button></div>
                            </div>
                            <div class="form-grp"><label>Google Analytics ID</label><input type="text"
                                    name="google_analytics_id" class="form-control-admin"
                                    value="{{ $settings['google_analytics_id'] ?? '' }}"></div>
                            <div class="form-grp"><label>Meta Keywords</label><input type="text" name="meta_keywords"
                                    class="form-control-admin" value="{{ $settings['meta_keywords'] ?? '' }}"></div>
                            <button type="submit" class="btn-admin btn-navy">Save SEO Settings</button>
                        </form>
                    </div>
                </div>
                <div class="admin-card">
                    <div class="admin-card-header">
                        <div class="admin-card-title">🤖 VERIFICATION</div>
                    </div>
                    <div style="padding:24px">
                        <form method="POST" action="{{ route('admin.settings.update') }}">
                            @csrf
                            <div class="form-grp"><label>Google Site Verification</label><input type="text"
                                    name="google_verification" class="form-control-admin"
                                    value="{{ $settings['google_verification'] ?? '' }}"></div>
                            <div class="form-grp"><label>Bing Site Verification</label><input type="text"
                                    name="bing_verification" class="form-control-admin"
                                    value="{{ $settings['bing_verification'] ?? '' }}"></div>
                            <div class="form-grp"><label>Robots.txt Content</label>
                                <textarea name="robots_txt" class="form-control-admin" rows="5">{{ $settings['robots_txt'] ?? "User-agent: *\nAllow: /\nDisallow: /admin" }}</textarea>
                            </div>
                            <button type="submit" class="btn-admin btn-navy">Save Settings</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- =============================================== --}}
        {{-- TAB 5: SOCIAL MEDIA --}}
        {{-- =============================================== --}}
        <div id="socialTab" class="tab-pane">
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title">📱 SOCIAL MEDIA LINKS</div>
                </div>
                <div style="padding:24px">
                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-grp"><label>Facebook URL</label><input type="url" name="facebook_url"
                                    class="form-control-admin" value="{{ $settings['facebook_url'] ?? '' }}"></div>
                            <div class="form-grp"><label>Instagram URL</label><input type="url" name="instagram_url"
                                    class="form-control-admin" value="{{ $settings['instagram_url'] ?? '' }}"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-grp"><label>Twitter/X URL</label><input type="url" name="twitter_url"
                                    class="form-control-admin" value="{{ $settings['twitter_url'] ?? '' }}"></div>
                            <div class="form-grp"><label>YouTube URL</label><input type="url" name="youtube_url"
                                    class="form-control-admin" value="{{ $settings['youtube_url'] ?? '' }}"></div>
                        </div>
                        <div class="form-grp"><label>WhatsApp Number</label><input type="text" name="whatsapp_number"
                                class="form-control-admin" value="{{ $settings['whatsapp_number'] ?? '' }}"></div>
                        <button type="submit" class="btn-admin btn-navy">Save Social Links</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- =============================================== --}}
        {{-- TAB 6: SYSTEM --}}
        {{-- =============================================== --}}
        <div id="systemTab" class="tab-pane">
            <div class="settings-grid">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <div class="admin-card-title">⚙️ SYSTEM TOOLS</div>
                    </div>
                    <div style="padding:24px">
                        <form method="POST" action="{{ route('admin.cache.clear') }}">@csrf<button type="submit"
                                class="btn-admin btn-light btn-block"><i class="bi bi-arrow-clockwise"></i> Clear All
                                Cache</button></form>
                        <form method="POST" action="{{ route('admin.cache.clear', ['type' => 'view']) }}"
                            style="margin-top:12px">@csrf<button type="submit" class="btn-admin btn-light btn-block"><i
                                    class="bi bi-eye"></i> Clear View Cache</button></form>
                        <form method="POST" action="{{ route('admin.backup.create') }}" style="margin-top:12px">
                            @csrf<button type="submit" class="btn-admin btn-navy btn-block"><i
                                    class="bi bi-database"></i> Create Database Backup</button></form>
                    </div>
                </div>
                <div class="admin-card">
                    <div class="admin-card-header">
                        <div class="admin-card-title">ℹ️ SYSTEM INFO</div>
                    </div>
                    <div style="padding:24px">
                        <div
                            style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #eef2f6">
                            <span>Laravel Version:</span><strong>{{ app()->version() }}</strong>
                        </div>
                        <div
                            style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #eef2f6">
                            <span>PHP Version:</span><strong>{{ phpversion() }}</strong>
                        </div>
                        <div
                            style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #eef2f6">
                            <span>Environment:</span><strong>{{ app()->environment() }}</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; padding:8px 0"><span>Last
                                Backup:</span><strong>{{ $lastBackup ?? 'No backup yet' }}</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Media Upload Modal --}}
    {{-- Media Upload Modal --}}
<div id="mediaUploadModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Upload Media</h3>
            <button class="modal-close" onclick="closeModal('mediaUploadModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="mediaUploadForm" enctype="multipart/form-data">
                @csrf
                <div class="form-grp">
                    <label>Title *</label>
                    <input type="text" name="title" id="media_title" class="form-control-admin" required>
                </div>
                <div class="form-grp">
                    <label>File Type *</label>
                    <select name="file_type" id="media_file_type" class="form-control-admin" required onchange="updateFileAccept()">
                        <option value="image">Image (JPG, PNG, WebP)</option>
                        <option value="video">Video (MP4, MOV)</option>
                    </select>
                </div>
                <div class="form-grp">
                    <label>File *</label>
                    <div class="upload-area" onclick="document.getElementById('media_file').click()">
                        <i class="bi bi-cloud-upload" style="font-size: 28px;"></i>
                        <p>Click to upload</p>
                        <small>Max: 50MB</small>
                    </div>
                    <input type="file" name="file" id="media_file" style="display: none;" accept="image/*,video/*" required>
                </div>
                <div class="form-grp">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" id="media_sort_order" class="form-control-admin" value="0">
                </div>
                <input type="hidden" name="section" value="gallery">
                <div class="action-buttons">
                    <button type="submit" class="btn-admin btn-navy">Upload</button>
                    <button type="button" class="btn-admin btn-light" onclick="closeModal('mediaUploadModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

    {{-- Edit Media Modal --}}
    <div id="editMediaModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3>Edit Media</h3>
                <button class="modal-close" onclick="closeModal('editMediaModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editMediaForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="media_id" id="edit_media_id">
                    <div class="form-grp">
                        <label>Title *</label>
                        <input type="text" name="title" id="edit_media_title" class="form-control-admin" required>
                    </div>
                    <div class="form-grp">
                        <label>Description</label>
                        <textarea name="description" id="edit_media_description" class="form-control-admin" rows="2"></textarea>
                    </div>
                    <div class="form-grp">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" id="edit_media_sort_order" class="form-control-admin">
                    </div>
                    <div class="action-buttons">
                        <button type="submit" class="btn-admin btn-navy">Update</button>
                        <button type="button" class="btn-admin btn-light"
                            onclick="closeModal('editMediaModal')">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentSlideId = null;

            function showTab(tabName) {
                document.querySelectorAll('.tab-pane').forEach(tab => tab.classList.remove('active'));
                document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                document.getElementById(tabName + 'Tab').classList.add('active');
                if (event && event.target) event.target.classList.add('active');
            }

            function previewImage(input, previewId, imgId) {
                const file = input.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById(imgId).src = e.target.result;
                        document.getElementById(previewId).style.display = 'inline-block';
                    };
                    reader.readAsDataURL(file);
                }
            }

            function clearImageUpload(fileInputId, previewId) {
                document.getElementById(fileInputId).value = '';
                document.getElementById(previewId).style.display = 'none';
            }

            // Hero Slide Functions
            function editSlide(id, title, subtitle, image, buttonText, buttonLink, sortOrder, isActive) {
                currentSlideId = id;
                document.getElementById('slide_id').value = id;
                document.getElementById('slide_title').value = title;
                document.getElementById('slide_subtitle').value = subtitle || '';
                document.getElementById('slide_button_text').value = buttonText || '';
                document.getElementById('slide_button_link').value = buttonLink || '';
                document.getElementById('slide_order').value = sortOrder;
                document.getElementById('slide_active').checked = isActive;
                document.getElementById('image_preview').style.display = 'none';
                document.getElementById('mobile_image_preview').style.display = 'none';
                document.getElementById('slide_image_file').required = false;
                document.getElementById('formTitle').innerHTML = '✏️ EDIT SLIDE';
                document.getElementById('submitBtn').innerHTML = '<i class="bi bi-pencil"></i> Update Slide';
                document.getElementById('cancelEditBtn').style.display = 'inline-block';
                var form = document.getElementById('slideForm');
                var updateUrl = '{{ route('admin.settings.updateSlide', '__SLIDE_ID__') }}'.replace('__SLIDE_ID__', id);
                form.action = updateUrl;
                if (!document.getElementById('method_field')) {
                    var methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'PUT';
                    methodField.id = 'method_field';
                    form.appendChild(methodField);
                }
                document.getElementById('addSlideForm').scrollIntoView({
                    behavior: 'smooth'
                });
            }

            function resetSlideForm() {
                currentSlideId = null;
                document.getElementById('slide_id').value = '';
                document.getElementById('slide_title').value = '';
                document.getElementById('slide_subtitle').value = '';
                document.getElementById('slide_image_file').value = '';
                document.getElementById('slide_mobile_image_file').value = '';
                document.getElementById('slide_button_text').value = '';
                document.getElementById('slide_button_link').value = '';
                document.getElementById('slide_order').value = '0';
                document.getElementById('slide_active').checked = true;
                document.getElementById('image_preview').style.display = 'none';
                document.getElementById('mobile_image_preview').style.display = 'none';
                document.getElementById('slide_image_file').required = true;
                document.getElementById('formTitle').innerHTML = '➕ ADD NEW SLIDE';
                document.getElementById('submitBtn').innerHTML = '<i class="bi bi-plus-lg"></i> Add Slide';
                document.getElementById('cancelEditBtn').style.display = 'none';
                var form = document.getElementById('slideForm');
                form.action = '{{ route('admin.settings.storeSlide') }}';
                if (document.getElementById('method_field')) document.getElementById('method_field').remove();
            }

            // Media Functions
           
            

            

           

            


           

           
// Media Functions
function showMediaUploadModal() {
    document.getElementById('mediaUploadModal').style.display = 'flex';
    document.getElementById('mediaUploadForm').reset();
    // Reset upload area
    const uploadArea = document.querySelector('#mediaUploadModal .upload-area');
    if (uploadArea) {
        uploadArea.innerHTML = `<i class="bi bi-cloud-upload" style="font-size: 28px;"></i>
            <p>Click to upload</p>
            <small>Max: 50MB</small>`;
    }
    // Clear file input
    const fileInput = document.getElementById('media_file');
    if (fileInput) fileInput.value = '';
}

function updateFileAccept() {
    const type = document.getElementById('media_file_type').value;
    const fileInput = document.getElementById('media_file');
    if (type === 'image') {
        fileInput.accept = 'image/jpeg,image/jpg,image/png,image/webp';
    } else {
        fileInput.accept = 'video/mp4,video/quicktime';
    }
}

// Handle file selection
document.getElementById('media_file')?.addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const file = e.target.files[0];
        const uploadArea = document.querySelector('#mediaUploadModal .upload-area');
        if (uploadArea) {
            uploadArea.innerHTML = `<i class="bi bi-file-check" style="font-size: 28px;"></i>
                <p>${file.name}</p>
                <small>${(file.size / 1024 / 1024).toFixed(2)} MB</small>`;
        }
    }
});

// Handle form submission
document.getElementById('mediaUploadForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    // Get form data
    const formData = new FormData();
    const title = document.getElementById('media_title').value;
    const fileType = document.getElementById('media_file_type').value;
    const file = document.getElementById('media_file').files[0];
    const sortOrder = document.getElementById('media_sort_order').value;
    const section = document.querySelector('input[name="section"]').value;
    
    // Validate file
    if (!file) {
        alert('Please select a file to upload');
        return;
    }
    
    if (!title) {
        alert('Please enter a title');
        return;
    }
    
    // Append to FormData
    formData.append('title', title);
    formData.append('file_type', fileType);
    formData.append('file', file);
    formData.append('sort_order', sortOrder || 0);
    formData.append('section', section || 'gallery');
    
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Uploading...';
    submitBtn.disabled = true;

    try {
        const response = await fetch('{{ route("admin.media.upload-gallery") }}', {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Upload failed');
            console.error('Upload error:', data);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error uploading file: ' + error.message);
    } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
});

// Edit Media Function
function editMedia(id) {
    fetch(`/admin/media/gallery/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_media_id').value = data.id;
            document.getElementById('edit_media_title').value = data.title;
            document.getElementById('edit_media_sort_order').value = data.sort_order;
            document.getElementById('editMediaModal').style.display = 'flex';
        })
        .catch(error => console.error('Error:', error));
}

// Update Media Function
document.getElementById('editMediaForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('edit_media_id').value;
    const formData = {
        title: document.getElementById('edit_media_title').value,
        sort_order: document.getElementById('edit_media_sort_order').value
    };
    
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Updating...';
    submitBtn.disabled = true;

    try {
        const response = await fetch(`/admin/media/gallery/${id}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });
        const data = await response.json();
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Update failed');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error updating media');
    } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
});

// Delete Media Function
function deleteMedia(id) {
    if (confirm('Are you sure you want to delete this media?')) {
        fetch(`/admin/media/gallery/${id}`, {
            method: 'DELETE',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                'Accept': 'application/json' 
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Delete failed');
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Preview Media Function
function previewMedia(id) {
    fetch(`/admin/media/gallery/${id}/preview`)
        .then(response => response.json())
        .then(data => {
            const previewWindow = window.open('', '_blank');
            if (data.type === 'video') {
                previewWindow.document.write(`
                    <html>
                        <head><title>${data.title}</title></head>
                        <body style="margin:0;display:flex;justify-content:center;align-items:center;min-height:100vh;background:#000;">
                            <video controls autoplay style="max-width:100%;max-height:100vh;">
                                <source src="${data.url}" type="${data.mime_type}">
                                Your browser does not support the video tag.
                            </video>
                        </body>
                    </html>
                `);
            } else {
                previewWindow.document.write(`
                    <html>
                        <head><title>${data.title}</title></head>
                        <body style="margin:0;display:flex;justify-content:center;align-items:center;min-height:100vh;background:#000;">
                            <img src="${data.url}" alt="${data.title}" style="max-width:100%;max-height:100vh;">
                        </body>
                    </html>
                `);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Filter Media Function
function filterMedia(type) {
    const cards = document.querySelectorAll('.media-card');
    cards.forEach(card => {
        if (type === 'all' || card.dataset.type === type) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.filter === type) btn.classList.add('active');
    });
}

// Close Modal Function
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}

            
            const style = document.createElement('style');
            style.textContent =
                `.spin { animation: spin 1s linear infinite; } @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } } .modal { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; } .modal-content { background: white; border-radius: 12px; max-width: 90%; max-height: 90vh; overflow-y: auto; } .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px; border-bottom: 1px solid #eef2f6; } .modal-header h3 { margin: 0; } .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; } .modal-body { padding: 20px; }`;
            document.head.appendChild(style);
        </script>
    @endpush
@endsection
