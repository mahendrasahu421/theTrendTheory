{{-- resources/views/admin/settings/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Settings')
@section('content')

    <style>
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .settings-tab {
            display: flex;
            gap: 8px;
            margin-bottom: 24px;
            border-bottom: 2px solid #eef2f6;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 12px 22px;
            background: none;
            border: none;
            font-size: 13px;
            font-weight: 700;
            color: #7a8fa6;
            cursor: pointer;
            transition: all 0.25s ease;
            position: relative;
            border-radius: 8px 8px 0 0;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .tab-btn:hover {
            color: #00285a;
            background: #f8fafc;
        }

        .tab-btn.active {
            color: #00285a;
            background: #fff;
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #00285a, #0056b3);
            border-radius: 3px 3px 0 0;
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
            gap: 16px;
        }

        /* ── Modern Card Styling ─────────────────────────────────────── */
        .admin-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #eef2f6;
            box-shadow: 0 4px 20px rgba(0, 40, 90, 0.04);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .admin-card:hover {
            box-shadow: 0 8px 30px rgba(0, 40, 90, 0.08);
        }

        .admin-card-header {
            padding: 18px 24px;
            background: #fafbff;
            border-bottom: 1px solid #eef2f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-card-title {
            font-family: 'Cinzel', serif, sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #00285a;
            letter-spacing: 0.8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── Slide Item Styles ───────────────────────────────────────── */
        .slide-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .slide-item:last-child {
            border-bottom: none;
        }

        .slide-item:hover {
            background: #f8fafc;
        }

        .slide-image {
            width: 80px;
            height: 56px;
            object-fit: cover;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            background: #f1f5f9;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .slide-info {
            flex: 1;
        }

        .slide-title {
            font-weight: 700;
            font-size: 14px;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .slide-meta {
            font-size: 12px;
            color: #64748b;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }

        .slide-meta-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 6px;
            background: #f1f5f9;
            color: #475569;
            font-size: 11px;
            font-weight: 600;
        }

        .slide-meta-badge.active {
            background: #dcfce7;
            color: #15803d;
        }

        .slide-meta-badge.inactive {
            background: #fee2e2;
            color: #b91c1c;
        }

        .slide-meta-badge.product {
            background: #e0f2fe;
            color: #0369a1;
        }

        .slide-actions {
            display: flex;
            gap: 8px;
        }

        /* ── Media Grid Styles ────────────────────────────────────────── */
        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
            gap: 22px;
            margin-top: 20px;
        }

        .media-card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #eef2f6;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .media-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(0, 40, 90, 0.12);
        }

        .media-preview {
            position: relative;
            width: 100%;
            height: 190px;
            background: #0f172a;
            overflow: hidden;
            cursor: pointer;
        }

        .media-preview img,
        .media-preview video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .media-card:hover .media-preview img,
        .media-card:hover .media-preview video {
            transform: scale(1.05);
        }

        .media-type-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.5px;
            background: rgba(15, 23, 42, 0.85);
            color: white;
            backdrop-filter: blur(4px);
            z-index: 2;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }

        .media-type-badge.video {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .media-type-badge.image {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .hero-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            z-index: 2;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }

        .play-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #00285a;
            font-size: 22px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
            transition: all 0.25s ease;
        }

        .media-preview:hover .play-icon {
            transform: translate(-50%, -50%) scale(1.15);
            background: #fff;
            color: #2563eb;
        }

        .media-info {
            padding: 16px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .media-title {
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 4px;
            color: #0f172a;
        }

        .media-meta {
            font-size: 11px;
            color: #64748b;
            margin-top: 3px;
        }

        .media-products-list {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-top: 8px;
            margin-bottom: 8px;
        }

        .media-product-chip {
            font-size: 10px;
            font-weight: 600;
            background: #eef2ff;
            color: #4338ca;
            padding: 3px 8px;
            border-radius: 12px;
            border: 1px solid #c7d2fe;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .media-actions {
            display: flex;
            gap: 8px;
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
        }

        /* Alert Styles */
        .alert-success {
            background: #f0fdf4;
            color: #166534;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 13.5px;
            font-weight: 600;
            border: 1px solid #bbf7d0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 13.5px;
            font-weight: 600;
            border: 1px solid #fecaca;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* ── Upload Area ─────────────────────────────────────────────── */
        .file-upload-area {
            position: relative;
            margin-top: 6px;
        }

        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 20px;
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.25s ease;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
        }

        .file-upload-label:hover {
            border-color: #00285a;
            background: #eff6ff;
            color: #00285a;
        }

        .file-upload-input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .image-preview {
            margin-top: 12px;
            display: none;
            position: relative;
        }

        .image-preview img, .image-preview video {
            max-height: 120px;
            border-radius: 10px;
            border: 1.5px solid #cbd5e1;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .image-preview .remove-btn {
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

        /* Button Styles */
        .btn-admin {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            border: none;
            text-decoration: none;
        }

        .btn-navy {
            background: linear-gradient(135deg, #00285a, #1e3f75);
            color: white;
            box-shadow: 0 4px 12px rgba(0, 40, 90, 0.25);
        }

        .btn-navy:hover {
            background: linear-gradient(135deg, #001e44, #15315e);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 40, 90, 0.35);
            color: white;
        }

        .btn-light {
            background: #fff;
            color: #475569;
            border: 1.5px solid #cbd5e1;
        }

        .btn-light:hover {
            background: #f8fafc;
            border-color: #00285a;
            color: #00285a;
        }

        .btn-sm {
            padding: 7px 14px;
            font-size: 11.5px;
            border-radius: 8px;
        }

        .btn-danger {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .btn-danger:hover {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-block {
            width: 100%;
            justify-content: center;
        }

        .form-grp {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-grp label {
            font-size: 11.5px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .form-control-admin {
            padding: 11px 16px;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            font-size: 13.5px;
            outline: none;
            transition: all 0.2s ease;
            width: 100%;
            background: #fff;
            color: #0f172a;
        }

        .form-control-admin:focus {
            border-color: #00285a;
            box-shadow: 0 0 0 3px rgba(0, 40, 90, 0.1);
        }

        .hint {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        .form-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            user-select: none;
        }

        .form-toggle input {
            width: 18px;
            height: 18px;
            accent-color: #00285a;
            cursor: pointer;
        }

        .form-toggle span {
            font-size: 13.5px;
            font-weight: 700;
            color: #1e293b;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid #eef2f6;
        }

        .filter-btns {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 7px 18px;
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 30px;
            font-size: 12.5px;
            font-weight: 700;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .filter-btn:hover {
            border-color: #00285a;
            color: #00285a;
        }

        .filter-btn.active {
            background: #00285a;
            color: white;
            border-color: #00285a;
            box-shadow: 0 2px 8px rgba(0, 40, 90, 0.25);
        }

        /* ── Modern Searchable Product Picker Component ───────────────── */
        .product-picker-container {
            position: relative;
            width: 100%;
        }

        .product-picker-search {
            position: relative;
        }

        .product-picker-search i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 15px;
        }

        .product-picker-input {
            padding-left: 40px !important;
        }

        .product-picker-dropdown {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            max-height: 240px;
            overflow-y: auto;
            background: #fff;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            z-index: 1050;
            display: none;
        }

        .product-picker-dropdown.show {
            display: block;
        }

        .product-picker-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            cursor: pointer;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s ease;
        }

        .product-picker-option:last-child {
            border-bottom: none;
        }

        .product-picker-option:hover {
            background: #eff6ff;
        }

        .product-picker-option.selected {
            background: #e0e7ff;
        }

        .product-picker-thumb {
            width: 38px;
            height: 38px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            flex-shrink: 0;
        }

        .product-picker-info {
            flex: 1;
            min-width: 0;
        }

        .product-picker-name {
            font-size: 12.5px;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-picker-meta {
            font-size: 11px;
            color: #64748b;
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .product-chips-wrap {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .product-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 5px 12px 5px 8px;
            background: #eff6ff;
            border: 1.5px solid #bfdbfe;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            color: #1e40af;
            animation: fadeIn 0.2s ease;
        }

        .product-chip img {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            object-fit: cover;
        }

        .product-chip-remove {
            cursor: pointer;
            color: #3b82f6;
            font-size: 14px;
            font-weight: 800;
            transition: color 0.15s;
            margin-left: 2px;
        }

        .product-chip-remove:hover {
            color: #ef4444;
        }

        /* ── Modern Dialog Modal Styles ───────────────────────────────── */
        .modal {
            display: none;
            position: fixed;
            z-index: 99999;
            inset: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(8px);
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal.show {
            display: flex;
            opacity: 1;
        }

        .modal-content {
            background: #ffffff;
            border-radius: 20px;
            width: 100%;
            max-width: 580px;
            max-height: 88vh;
            overflow-y: auto;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.8);
            transform: scale(0.92) translateY(20px);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .modal.show .modal-content {
            transform: scale(1) translateY(0);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 28px;
            border-bottom: 1px solid #eef2f6;
            background: #fafbff;
            border-radius: 20px 20px 0 0;
        }

        .modal-header h3 {
            margin: 0;
            font-family: 'Cinzel', serif, sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: #00285a;
            letter-spacing: 0.6px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-close {
            background: #f1f5f9;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            font-size: 18px;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .modal-close:hover {
            background: #fee2e2;
            color: #ef4444;
        }

        .modal-body {
            padding: 28px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
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
                <i class="bi bi-check-circle-fill" style="font-size: 18px;"></i> <div>{!! session('success') !!}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert-error">
                <i class="bi bi-exclamation-triangle-fill" style="font-size: 18px;"></i> <div>{{ session('error') }}</div>
            </div>
        @endif

        <div class="settings-tab">
            <button class="tab-btn active" onclick="showTab('general')"><i class="bi bi-sliders"></i> General Settings</button>
            <button class="tab-btn" onclick="showTab('hero')"><i class="bi bi-images"></i> Hero Slides</button>
            <button class="tab-btn" onclick="showTab('media')"><i class="bi bi-film"></i> Video & Image Gallery</button>
            <button class="tab-btn" onclick="showTab('seo')"><i class="bi bi-search"></i> SEO Settings</button>
            <button class="tab-btn" onclick="showTab('social')"><i class="bi bi-share"></i> Social Media</button>
            <button class="tab-btn" onclick="showTab('system')"><i class="bi bi-cpu"></i> System</button>
        </div>

        {{-- =============================================== --}}
        {{-- TAB 1: GENERAL SETTINGS --}}
        {{-- =============================================== --}}
        <div id="generalTab" class="tab-pane active">
            <div class="settings-grid">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <div class="admin-card-title"><i class="bi bi-building"></i> SITE INFORMATION</div>
                    </div>
                    <div style="padding:24px">
                        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                            @csrf
                            <div style="display:flex;flex-direction:column;gap:16px">
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
                                            <i class="bi bi-cloud-upload" style="font-size:18px"></i> Choose Logo
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
                                        <div style="margin-top:8px; display:flex; align-items:center; gap:10px;">
                                            <img src="{{ $settings['logo'] }}" alt="Current Logo" style="height:36px; border-radius:6px;" onerror="this.style.display='none'">
                                            <span style="font-size:12px; color:#64748b; font-weight:600;">Current Logo</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Favicon Upload --}}
                                <div class="form-grp">
                                    <label>Favicon</label>
                                    <div class="file-upload-area">
                                        <label class="file-upload-label">
                                            <i class="bi bi-cloud-upload" style="font-size:18px"></i> Choose Favicon
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
                        <div class="admin-card-title"><i class="bi bi-cash-stack"></i> BUSINESS SETTINGS</div>
                    </div>
                    <div style="padding:24px">
                        <form method="POST" action="{{ route('admin.settings.update') }}">
                            @csrf
                            <div style="display:flex;flex-direction:column;gap:16px">
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
                        <div class="admin-card-title"><i class="bi bi-images"></i> HERO SLIDES ({{ $heroSlides->count() }})</div>
                        <button type="button" class="btn-admin btn-navy btn-sm"
                            onclick="document.getElementById('addSlideForm').scrollIntoView({behavior:'smooth'})">
                            <i class="bi bi-plus-lg"></i> Add New
                        </button>
                    </div>
                    <div style="max-height:560px; overflow-y:auto">
                        @forelse($heroSlides as $slide)
                            <div class="slide-item">
                                @if (($slide->media_type ?? 'image') === 'video' && $slide->image)
                                    <video src="{{ $slide->image_url }}" class="slide-image" muted playsinline></video>
                                @elseif ($slide->image)
                                    <img src="{{ $slide->image_url }}" class="slide-image">
                                @endif
                                <div class="slide-info">
                                    <div class="slide-title">{{ $slide->title }}</div>
                                    <div class="slide-meta">
                                        <span class="slide-meta-badge">{{ strtoupper($slide->media_type ?? 'image') }}</span>
                                        <span class="slide-meta-badge {{ $slide->is_active ? 'active' : 'inactive' }}">
                                            {{ $slide->is_active ? '✓ Active' : '✗ Inactive' }}
                                        </span>
                                        <span>Order: <strong>{{ $slide->sort_order }}</strong></span>
                                        @if ($slide->button_text)
                                            <span>Button: <strong>{{ $slide->button_text }}</strong></span>
                                        @endif
                                        @if ($slide->product)
                                            <span class="slide-meta-badge product">
                                                <i class="bi bi-bag-check"></i> {{ $slide->product->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="slide-actions">
                                    <button type="button" class="btn-admin btn-light btn-sm"
                                        onclick='editSlide({{ $slide->id }}, {{ json_encode($slide->title) }}, {{ json_encode($slide->subtitle) }}, {{ json_encode($slide->media_type) }}, {{ json_encode($slide->image) }}, {{ json_encode($slide->button_text) }}, {{ json_encode($slide->button_link) }}, {{ json_encode($slide->product_id) }}, {{ $slide->sort_order }}, {{ $slide->is_active ? 'true' : 'false' }})'>
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
                            <div style="padding:50px;text-align:center;color:#64748b">
                                <i class="bi bi-images" style="font-size:48px;opacity:0.4;display:block;margin-bottom:12px"></i>
                                <p style="font-size:14px;font-weight:600">No hero slides added yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="admin-card" id="addSlideForm">
                    <div class="admin-card-header">
                        <div class="admin-card-title" id="formTitle"><i class="bi bi-plus-circle"></i> ADD NEW SLIDE</div>
                    </div>
                    <div style="padding:24px">
                        <form method="POST" action="{{ route('admin.settings.storeSlide') }}" id="slideForm"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="slide_id" id="slide_id">
                            <div style="display:flex;flex-direction:column;gap:14px">
                                <div class="form-grp">
                                    <label>Title *</label>
                                    <input type="text" name="title" id="slide_title" class="form-control-admin"
                                        required placeholder="e.g., NEW SUMMER COLLECTION">
                                </div>

                                <div class="form-grp">
                                    <label>Subtitle</label>
                                    <input type="text" name="subtitle" id="slide_subtitle" class="form-control-admin"
                                        placeholder="Discover premium styles crafted for comfort">
                                </div>

                                <div class="form-grp">
                                    <label>Hero Media Type *</label>
                                    <select name="media_type" id="slide_media_type" class="form-control-admin"
                                        onchange="toggleSlideMediaFields()" required>
                                        <option value="image">Image (JPG, PNG, WebP)</option>
                                        <option value="video">Video (MP4, WebM)</option>
                                    </select>
                                </div>

                                <div class="form-grp" id="slide_image_group">
                                    <label>Desktop Image *</label>
                                    <div class="file-upload-area">
                                        <label class="file-upload-label">
                                            <i class="bi bi-cloud-upload" style="font-size:20px"></i> Choose Desktop Image
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

                                <div class="form-grp" id="slide_video_group" style="display:none">
                                    <label>Desktop Video *</label>
                                    <div class="file-upload-area">
                                        <label class="file-upload-label">
                                            <i class="bi bi-cloud-upload" style="font-size:20px"></i> Choose Desktop Video
                                            <input type="file" name="video_file" id="slide_video_file"
                                                class="file-upload-input"
                                                accept="video/mp4,video/webm,video/quicktime">
                                        </label>
                                    </div>
                                    <div class="hint">Recommended: MP4/WebM | Max: 50MB</div>
                                </div>

                                <div class="form-grp" id="slide_mobile_image_group">
                                    <label>Mobile Image (Optional)</label>
                                    <div class="file-upload-area">
                                        <label class="file-upload-label">
                                            <i class="bi bi-cloud-upload" style="font-size:20px"></i> Choose Mobile Image
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

                                <div class="form-grp" id="slide_mobile_video_group" style="display:none">
                                    <label>Mobile Video (Optional)</label>
                                    <div class="file-upload-area">
                                        <label class="file-upload-label">
                                            <i class="bi bi-cloud-upload" style="font-size:20px"></i> Choose Mobile Video
                                            <input type="file" name="mobile_video_file" id="slide_mobile_video_file"
                                                class="file-upload-input"
                                                accept="video/mp4,video/webm,video/quicktime">
                                        </label>
                                    </div>
                                    <div class="hint">Optional mobile video | Max: 50MB</div>
                                </div>

                                {{-- Link Product Section --}}
                                <div class="form-grp">
                                    <label><i class="bi bi-bag-plus"></i> Link Product to Slide</label>
                                    <div class="product-picker-container" id="heroProductPicker">
                                        <div class="product-picker-search">
                                            <i class="bi bi-search"></i>
                                            <input type="text" class="form-control-admin product-picker-input"
                                                placeholder="Search product by name or SKU..."
                                                oninput="filterSingleProductPicker(this, 'heroProductPicker')">
                                        </div>
                                        <div class="product-picker-dropdown">
                                            <div class="product-picker-option" onclick="selectSingleProduct('heroProductPicker', '', '', '', '')">
                                                <div class="product-picker-info">
                                                    <div class="product-picker-name" style="color:#64748b">No Product (Use Custom Button Link)</div>
                                                </div>
                                            </div>
                                            @foreach ($products as $product)
                                                <div class="product-picker-option"
                                                    data-id="{{ $product->id }}"
                                                    data-name="{{ $product->name }}"
                                                    data-sku="{{ $product->sku }}"
                                                    data-price="{{ number_format(round($product->price)) }}"
                                                    data-url="{{ route('product.show', $product->slug, false) }}"
                                                    data-image="{{ ($product->image_url ?? asset('images/placeholder-product.jpg')) }}"
                                                    onclick="selectSingleProduct('heroProductPicker', '{{ $product->id }}', '{{ addslashes($product->name) }}', '{{ route('product.show', $product->slug, false) }}', '{{ ($product->image_url ?? asset('images/placeholder-product.jpg')) }}')">
                                                    <img src="{{ ($product->image_url ?? asset('images/placeholder-product.jpg')) }}" class="product-picker-thumb">
                                                    <div class="product-picker-info">
                                                        <div class="product-picker-name">{{ $product->name }}</div>
                                                        <div class="product-picker-meta">
                                                            <span>SKU: {{ $product->sku ?: 'N/A' }}</span>
                                                            <span>•</span>
                                                            <span>₹{{ number_format(round($product->price)) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <input type="hidden" name="product_id" id="slide_product_id" value="">
                                    <div class="product-chips-wrap" id="heroProductChipWrap"></div>
                                    <div class="hint">Select a product to direct customers straight to its detail page.</div>
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
                                        style="display:flex; align-items:center; gap:10px; padding-top:22px">
                                        <label class="form-toggle">
                                            <input type="checkbox" name="is_active" value="1" id="slide_active"
                                                checked>
                                            <span>Active Status</span>
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
                    <div class="admin-card-title"><i class="bi bi-film"></i> VIDEO & IMAGE GALLERY ({{ $media->count() }})</div>
                    <button type="button" class="btn-admin btn-navy btn-sm" onclick="showMediaUploadModal()">
                        <i class="bi bi-plus-lg"></i> Upload Media
                    </button>
                </div>
                <div style="padding:24px">
                    <div class="filter-btns">
                        <button class="filter-btn active" data-filter="all" onclick="filterMedia('all')">All Media</button>
                        <button class="filter-btn" data-filter="image" onclick="filterMedia('image')"><i class="bi bi-image"></i> Images</button>
                        <button class="filter-btn" data-filter="video" onclick="filterMedia('video')"><i class="bi bi-camera-video"></i> Videos</button>
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
                                        <div class="play-icon"><i class="bi bi-play-fill"></i></div>
                                    @endif
                                    <span class="media-type-badge {{ $fileType }}">{{ strtoupper($fileType) }}</span>
                                    @if ($item->is_primary)
                                        <span class="hero-badge"><i class="bi bi-star-fill"></i> HERO</span>
                                    @endif
                                </div>
                                <div class="media-info">
                                    <div class="media-title">{{ $item->alt_text ?? 'Untitled' }}</div>
                                    @if (!empty($item->subtitle))
                                        <div class="media-meta">{{ $item->subtitle }}</div>
                                    @endif
                                    @if (!empty($item->button_link))
                                        <div class="media-meta"><i class="bi bi-link-45deg"></i> {{ $item->button_link }}</div>
                                    @endif
                                    
                                    {{-- Attached Products Badges --}}
                                    @if ($item->products && $item->products->count() > 0)
                                        <div class="media-products-list">
                                            @foreach ($item->products as $p)
                                                <span class="media-product-chip">
                                                    <i class="bi bi-bag"></i> {{ Str::limit($p->name, 18) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="media-meta" style="margin-top:auto; padding-top:6px;">
                                        {{ $item->created_at ? $item->created_at->format('d M Y') : 'N/A' }} • Order: {{ $item->sort_order }}
                                    </div>

                                    <div class="media-actions">
                                        @if (!$item->is_primary)
                                            <button class="btn-admin btn-navy btn-sm" onclick="setHeroMedia({{ $item->id }})"><i class="bi bi-star"></i> Hero</button>
                                        @endif
                                        <button class="btn-admin btn-light btn-sm" onclick="editMedia({{ $item->id }})"><i class="bi bi-pencil"></i> Edit</button>
                                        <button class="btn-admin btn-danger btn-sm" onclick="deleteMedia({{ $item->id }})"><i class="bi bi-trash3"></i> Delete</button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; padding: 60px; grid-column: 1/-1; color: #64748b;">
                                <i class="bi bi-cloud-upload" style="font-size: 48px; opacity:0.4; display:block; margin-bottom:12px;"></i>
                                <p style="font-size: 14px; font-weight:600;">No media uploaded yet. Click "Upload Media" to add videos or images.</p>
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
                        <div class="admin-card-title"><i class="bi bi-search"></i> SEO SETTINGS</div>
                    </div>
                    <div style="padding:24px">
                        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                            @csrf
                            <div style="display:flex;flex-direction:column;gap:16px">
                                <div class="form-grp">
                                    <label>Default Meta Title</label>
                                    <input type="text" name="meta_title" class="form-control-admin" value="{{ $settings['meta_title'] ?? '' }}">
                                </div>
                                <div class="form-grp">
                                    <label>Default Meta Description</label>
                                    <textarea name="meta_description" class="form-control-admin" rows="3">{{ $settings['meta_description'] ?? '' }}</textarea>
                                </div>
                                <div class="form-grp">
                                    <label>OG Image</label>
                                    <div class="file-upload-area">
                                        <label class="file-upload-label">
                                            <i class="bi bi-cloud-upload" style="font-size:18px"></i> Choose OG Image
                                            <input type="file" name="og_image_file" class="file-upload-input" accept="image/*" onchange="previewImage(this, 'og_preview', 'og_preview_img')">
                                        </label>
                                    </div>
                                    <div id="og_preview" class="image-preview">
                                        <img id="og_preview_img" src="">
                                        <button type="button" class="remove-btn" onclick="clearImageUpload('og_image_file', 'og_preview')">×</button>
                                    </div>
                                </div>
                                <div class="form-grp">
                                    <label>Google Analytics ID</label>
                                    <input type="text" name="google_analytics_id" class="form-control-admin" value="{{ $settings['google_analytics_id'] ?? '' }}">
                                </div>
                                <div class="form-grp">
                                    <label>Meta Keywords</label>
                                    <input type="text" name="meta_keywords" class="form-control-admin" value="{{ $settings['meta_keywords'] ?? '' }}">
                                </div>
                                <button type="submit" class="btn-admin btn-navy"><i class="bi bi-check-lg"></i> Save SEO Settings</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-header">
                        <div class="admin-card-title"><i class="bi bi-shield-check"></i> VERIFICATION</div>
                    </div>
                    <div style="padding:24px">
                        <form method="POST" action="{{ route('admin.settings.update') }}">
                            @csrf
                            <div style="display:flex;flex-direction:column;gap:16px">
                                <div class="form-grp">
                                    <label>Google Site Verification</label>
                                    <input type="text" name="google_verification" class="form-control-admin" value="{{ $settings['google_verification'] ?? '' }}">
                                </div>
                                <div class="form-grp">
                                    <label>Bing Site Verification</label>
                                    <input type="text" name="bing_verification" class="form-control-admin" value="{{ $settings['bing_verification'] ?? '' }}">
                                </div>
                                <div class="form-grp">
                                    <label>Robots.txt Content</label>
                                    <textarea name="robots_txt" class="form-control-admin" rows="5">{{ $settings['robots_txt'] ?? "User-agent: *\nAllow: /\nDisallow: /admin" }}</textarea>
                                </div>
                                <button type="submit" class="btn-admin btn-navy"><i class="bi bi-check-lg"></i> Save Settings</button>
                            </div>
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
                    <div class="admin-card-title"><i class="bi bi-share"></i> SOCIAL MEDIA LINKS</div>
                </div>
                <div style="padding:24px">
                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        <div style="display:flex;flex-direction:column;gap:16px">
                            <div class="form-row">
                                <div class="form-grp"><label>Facebook URL</label><input type="url" name="facebook_url" class="form-control-admin" value="{{ $settings['facebook_url'] ?? '' }}"></div>
                                <div class="form-grp"><label>Instagram URL</label><input type="url" name="instagram_url" class="form-control-admin" value="{{ $settings['instagram_url'] ?? '' }}"></div>
                            </div>
                            <div class="form-row">
                                <div class="form-grp"><label>Twitter/X URL</label><input type="url" name="twitter_url" class="form-control-admin" value="{{ $settings['twitter_url'] ?? '' }}"></div>
                                <div class="form-grp"><label>YouTube URL</label><input type="url" name="youtube_url" class="form-control-admin" value="{{ $settings['youtube_url'] ?? '' }}"></div>
                            </div>
                            <div class="form-grp"><label>WhatsApp Number</label><input type="text" name="whatsapp_number" class="form-control-admin" value="{{ $settings['whatsapp_number'] ?? '' }}"></div>
                            <button type="submit" class="btn-admin btn-navy"><i class="bi bi-check-lg"></i> Save Social Links</button>
                        </div>
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
                        <div class="admin-card-title"><i class="bi bi-tools"></i> SYSTEM TOOLS</div>
                    </div>
                    <div style="padding:24px">
                        <form method="POST" action="{{ route('admin.cache.clear') }}">@csrf<button type="submit" class="btn-admin btn-light btn-block"><i class="bi bi-arrow-clockwise"></i> Clear All Cache</button></form>
                        <form method="POST" action="{{ route('admin.cache.clear', ['type' => 'view']) }}" style="margin-top:12px">@csrf<button type="submit" class="btn-admin btn-light btn-block"><i class="bi bi-eye"></i> Clear View Cache</button></form>
                        <form method="POST" action="{{ route('admin.backup.create') }}" style="margin-top:12px">@csrf<button type="submit" class="btn-admin btn-navy btn-block"><i class="bi bi-database"></i> Create Database Backup</button></form>
                    </div>
                </div>
                <div class="admin-card">
                    <div class="admin-card-header">
                        <div class="admin-card-title"><i class="bi bi-info-circle"></i> SYSTEM INFO</div>
                    </div>
                    <div style="padding:24px">
                        <div style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #eef2f6"><span>Laravel Version:</span><strong>{{ app()->version() }}</strong></div>
                        <div style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #eef2f6"><span>PHP Version:</span><strong>{{ phpversion() }}</strong></div>
                        <div style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #eef2f6"><span>Environment:</span><strong>{{ app()->environment() }}</strong></div>
                        <div style="display:flex; justify-content:space-between; padding:10px 0"><span>Last Backup:</span><strong>{{ $lastBackup ?? 'No backup yet' }}</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- UPLOAD MEDIA MODAL (VIDEO & IMAGE GALLERY)                        --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div id="mediaUploadModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="bi bi-cloud-arrow-up-fill" style="color:#00285a"></i> Upload Gallery Media</h3>
                <button class="modal-close" onclick="closeModal('mediaUploadModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="mediaUploadForm" enctype="multipart/form-data">
                    @csrf
                    <div style="display:flex; flex-direction:column; gap:16px;">
                        <div class="form-grp">
                            <label>Title *</label>
                            <input type="text" name="title" id="media_title" class="form-control-admin" required placeholder="e.g. Summer Signature Look">
                        </div>

                        <div class="form-grp">
                            <label>Subtitle / Description</label>
                            <input type="text" name="subtitle" id="media_subtitle" class="form-control-admin" placeholder="e.g. Featured streetwear collection">
                        </div>

                        <div class="form-grp">
                            <label>Collection Page URL</label>
                            <input type="text" name="button_link" id="media_button_link" class="form-control-admin" placeholder="/collection/summer-signature">
                        </div>

                        {{-- Multi-Product Picker for Upload --}}
                        <div class="form-grp">
                            <label><i class="bi bi-bag-check-fill"></i> Products in this Media / Collection</label>
                            <div class="product-picker-container" id="uploadProductPicker">
                                <div class="product-picker-search">
                                    <i class="bi bi-search"></i>
                                    <input type="text" class="form-control-admin product-picker-input" placeholder="Search & add products by name or SKU..." oninput="filterMultiProductPicker(this, 'uploadProductPicker')">
                                </div>
                                <div class="product-picker-dropdown">
                                    @foreach ($products as $product)
                                        <div class="product-picker-option"
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-sku="{{ $product->sku }}"
                                            data-price="{{ number_format(round($product->price)) }}"
                                            data-image="{{ ($product->image_url ?? asset('images/placeholder-product.jpg')) }}"
                                            onclick="toggleMultiProduct('uploadProductPicker', 'media_product_ids', '{{ $product->id }}', '{{ addslashes($product->name) }}', '{{ ($product->image_url ?? asset('images/placeholder-product.jpg')) }}')">
                                            <input type="checkbox" class="product-option-checkbox" value="{{ $product->id }}" onclick="event.stopPropagation(); toggleMultiProduct('uploadProductPicker', 'media_product_ids', '{{ $product->id }}', '{{ addslashes($product->name) }}', '{{ ($product->image_url ?? asset('images/placeholder-product.jpg')) }}')">
                                            <img src="{{ ($product->image_url ?? asset('images/placeholder-product.jpg')) }}" class="product-picker-thumb">
                                            <div class="product-picker-info">
                                                <div class="product-picker-name">{{ $product->name }}</div>
                                                <div class="product-picker-meta">
                                                    <span>SKU: {{ $product->sku ?: 'N/A' }}</span>
                                                    <span>•</span>
                                                    <span>₹{{ number_format(round($product->price)) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <select name="product_ids[]" id="media_product_ids" multiple style="display:none">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            <div class="product-chips-wrap" id="uploadProductChipsWrap"></div>
                            <div class="hint">Search and click products to tag them in this video/image gallery item.</div>
                        </div>

                        <div class="form-row">
                            <div class="form-grp">
                                <label>File Type *</label>
                                <select name="file_type" id="media_file_type" class="form-control-admin" required onchange="updateFileAccept()">
                                    <option value="image">Image (JPG, PNG, WebP)</option>
                                    <option value="video">Video (MP4, MOV)</option>
                                </select>
                            </div>
                            <div class="form-grp">
                                <label>Sort Order</label>
                                <input type="number" name="sort_order" id="media_sort_order" class="form-control-admin" value="0" min="0">
                            </div>
                        </div>

                        <div class="form-grp">
                            <label>Media File *</label>
                            <div class="file-upload-area">
                                <label class="file-upload-label" id="media_file_label" for="media_file">
                                    <span id="media_file_text" style="display:flex;align-items:center;justify-content:center;gap:10px;width:100%">
                                        <i class="bi bi-cloud-arrow-up" style="font-size:22px"></i> Click or Drag & Drop File
                                    </span>
                                    <input type="file" name="file" id="media_file" class="file-upload-input" accept="image/*,video/*" required>
                                </label>
                            </div>
                            <div class="hint">Max size: 50MB (Videos) | 5MB (Images)</div>
                            <div id="media_upload_preview" class="image-preview" style="display:none;margin-top:10px;text-align:center;">
                                <img id="media_upload_preview_img" src="" style="max-height:140px;border-radius:10px;display:none;margin:0 auto;">
                                <video id="media_upload_preview_video" src="" controls style="max-height:140px;border-radius:10px;display:none;margin:0 auto;"></video>
                            </div>
                        </div>

                        <input type="hidden" name="section" value="gallery">

                        <div class="action-buttons">
                            <button type="submit" class="btn-admin btn-navy"><i class="bi bi-cloud-upload"></i> Upload Media</button>
                            <button type="button" class="btn-admin btn-light" onclick="closeModal('mediaUploadModal')">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- EDIT MEDIA MODAL                                                 --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div id="editMediaModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="bi bi-pencil-square" style="color:#00285a"></i> Edit Gallery Media</h3>
                <button class="modal-close" onclick="closeModal('editMediaModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editMediaForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="media_id" id="edit_media_id">
                    <div style="display:flex; flex-direction:column; gap:16px;">
                        <div class="form-grp">
                            <label>Title *</label>
                            <input type="text" name="title" id="edit_media_title" class="form-control-admin" required>
                        </div>

                        <div class="form-grp">
                            <label>Subtitle / Description</label>
                            <input type="text" name="description" id="edit_media_description" class="form-control-admin">
                        </div>

                        <div class="form-grp">
                            <label>Collection Page URL</label>
                            <input type="text" name="button_link" id="edit_media_button_link" class="form-control-admin" placeholder="/collection/signature">
                        </div>

                        {{-- Multi-Product Picker for Edit --}}
                        <div class="form-grp">
                            <label><i class="bi bi-bag-check-fill"></i> Products in this Media / Collection</label>
                            <div class="product-picker-container" id="editProductPicker">
                                <div class="product-picker-search">
                                    <i class="bi bi-search"></i>
                                    <input type="text" class="form-control-admin product-picker-input" placeholder="Search & add products by name or SKU..." oninput="filterMultiProductPicker(this, 'editProductPicker')">
                                </div>
                                <div class="product-picker-dropdown">
                                    @foreach ($products as $product)
                                        <div class="product-picker-option"
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-sku="{{ $product->sku }}"
                                            data-price="{{ number_format(round($product->price)) }}"
                                            data-image="{{ ($product->image_url ?? asset('images/placeholder-product.jpg')) }}"
                                            onclick="toggleMultiProduct('editProductPicker', 'edit_media_product_ids', '{{ $product->id }}', '{{ addslashes($product->name) }}', '{{ ($product->image_url ?? asset('images/placeholder-product.jpg')) }}')">
                                            <input type="checkbox" class="product-option-checkbox" value="{{ $product->id }}" onclick="event.stopPropagation(); toggleMultiProduct('editProductPicker', 'edit_media_product_ids', '{{ $product->id }}', '{{ addslashes($product->name) }}', '{{ ($product->image_url ?? asset('images/placeholder-product.jpg')) }}')">
                                            <img src="{{ ($product->image_url ?? asset('images/placeholder-product.jpg')) }}" class="product-picker-thumb">
                                            <div class="product-picker-info">
                                                <div class="product-picker-name">{{ $product->name }}</div>
                                                <div class="product-picker-meta">
                                                    <span>SKU: {{ $product->sku ?: 'N/A' }}</span>
                                                    <span>•</span>
                                                    <span>₹{{ number_format(round($product->price)) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <select name="product_ids[]" id="edit_media_product_ids" multiple style="display:none">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            <div class="product-chips-wrap" id="editProductChipsWrap"></div>
                        </div>

                        <div class="form-grp">
                            <label>Sort Order</label>
                            <input type="number" name="sort_order" id="edit_media_sort_order" class="form-control-admin" min="0">
                        </div>

                        <div class="action-buttons">
                            <button type="submit" class="btn-admin btn-navy"><i class="bi bi-check-lg"></i> Update Media</button>
                            <button type="button" class="btn-admin btn-light" onclick="closeModal('editMediaModal')">Cancel</button>
                        </div>
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
                if (event && event.target) {
                    let btn = event.target.closest('.tab-btn');
                    if (btn) btn.classList.add('active');
                }
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

            /* ── Searchable Product Picker Functions ────────────────────── */

            // Show/hide dropdown on focus
            document.querySelectorAll('.product-picker-input').forEach(input => {
                input.addEventListener('focus', function() {
                    let dropdown = this.closest('.product-picker-container').querySelector('.product-picker-dropdown');
                    if (dropdown) dropdown.classList.add('show');
                });
            });

            // Close dropdowns on outside click
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.product-picker-container')) {
                    document.querySelectorAll('.product-picker-dropdown').forEach(d => d.classList.remove('show'));
                }
            });

            // Single Product Picker (Hero Slide)
            function filterSingleProductPicker(input, containerId) {
                let term = input.value.toLowerCase().trim();
                let options = document.querySelectorAll(`#${containerId} .product-picker-option`);
                options.forEach(opt => {
                    let text = opt.textContent.toLowerCase();
                    opt.style.display = text.includes(term) ? 'flex' : 'none';
                });
            }

            function selectSingleProduct(containerId, id, name, url, image) {
                document.getElementById('slide_product_id').value = id;
                let chipWrap = document.getElementById('heroProductChipWrap');
                let dropdown = document.querySelector(`#${containerId} .product-picker-dropdown`);
                if (dropdown) dropdown.classList.remove('show');

                if (id) {
                    chipWrap.innerHTML = `
                        <div class="product-chip">
                            <img src="${image}">
                            <span>${name}</span>
                            <span class="product-chip-remove" onclick="selectSingleProduct('${containerId}', '', '', '', '')">&times;</span>
                        </div>
                    `;
                    if (url) {
                        document.getElementById('slide_button_link').value = url;
                    }
                    if (!document.getElementById('slide_button_text').value.trim()) {
                        document.getElementById('slide_button_text').value = 'SHOP NOW';
                    }
                } else {
                    chipWrap.innerHTML = '';
                    document.getElementById('slide_button_link').value = '';
                }
            }

            // Multiple Product Picker (Gallery Media)
            function filterMultiProductPicker(input, containerId) {
                let term = input.value.toLowerCase().trim();
                let options = document.querySelectorAll(`#${containerId} .product-picker-option`);
                options.forEach(opt => {
                    let text = opt.textContent.toLowerCase();
                    opt.style.display = text.includes(term) ? 'flex' : 'none';
                });
            }

            function toggleMultiProduct(containerId, selectId, id, name, image) {
                let select = document.getElementById(selectId);
                if (!select) return;

                let option = Array.from(select.options).find(o => o.value == id);
                if (option) {
                    option.selected = !option.selected;
                }

                syncMultiProductUI(containerId, selectId);
            }

            function syncMultiProductUI(containerId, selectId) {
                let select = document.getElementById(selectId);
                if (!select) return;

                let selectedValues = Array.from(select.selectedOptions).map(o => o.value);
                let container = document.getElementById(containerId);
                let chipWrapId = containerId === 'uploadProductPicker' ? 'uploadProductChipsWrap' : 'editProductChipsWrap';
                let chipWrap = document.getElementById(chipWrapId);

                // Update checkboxes & selected state in options
                container.querySelectorAll('.product-picker-option').forEach(opt => {
                    let optId = opt.dataset.id;
                    let isSelected = selectedValues.includes(optId);
                    opt.classList.toggle('selected', isSelected);
                    let chk = opt.querySelector('.product-option-checkbox');
                    if (chk) chk.checked = isSelected;
                });

                // Render Chips
                if (chipWrap) {
                    chipWrap.innerHTML = selectedValues.map(val => {
                        let opt = container.querySelector(`.product-picker-option[data-id="${val}"]`);
                        if (!opt) return '';
                        let name = opt.dataset.name;
                        let img = opt.dataset.image;
                        return `
                            <div class="product-chip">
                                <img src="${img}">
                                <span>${name}</span>
                                <span class="product-chip-remove" onclick="toggleMultiProduct('${containerId}', '${selectId}', '${val}', '', '')">&times;</span>
                            </div>
                        `;
                    }).join('');
                }
            }

            // Hero Slide Functions
            function toggleSlideMediaFields() {
                var type = document.getElementById('slide_media_type').value || 'image';
                var imageGroup = document.getElementById('slide_image_group');
                var videoGroup = document.getElementById('slide_video_group');
                var mobileImageGroup = document.getElementById('slide_mobile_image_group');
                var mobileVideoGroup = document.getElementById('slide_mobile_video_group');
                var imageInput = document.getElementById('slide_image_file');
                var videoInput = document.getElementById('slide_video_file');
                var originalType = document.getElementById('slide_media_type').dataset.original || 'image';
                var needsNewMedia = !currentSlideId || type !== originalType;

                if (!imageGroup || !videoGroup || !imageInput || !videoInput) return;

                imageGroup.style.display = type === 'image' ? '' : 'none';
                videoGroup.style.display = type === 'video' ? '' : 'none';
                if (mobileImageGroup) mobileImageGroup.style.display = type === 'image' ? '' : 'none';
                if (mobileVideoGroup) mobileVideoGroup.style.display = type === 'video' ? '' : 'none';

                imageInput.required = type === 'image' && needsNewMedia;
                videoInput.required = type === 'video' && needsNewMedia;
            }

            function editSlide(id, title, subtitle, mediaType, image, buttonText, buttonLink, productId, sortOrder, isActive) {
                currentSlideId = id;
                document.getElementById('slide_id').value = id;
                document.getElementById('slide_title').value = title;
                document.getElementById('slide_subtitle').value = subtitle || '';
                document.getElementById('slide_media_type').value = mediaType || 'image';
                document.getElementById('slide_media_type').dataset.original = mediaType || 'image';
                document.getElementById('slide_button_text').value = buttonText || '';
                document.getElementById('slide_button_link').value = buttonLink || '';
                document.getElementById('slide_order').value = sortOrder;
                document.getElementById('slide_active').checked = isActive;
                
                // Populate single product picker for slide
                if (productId) {
                    let opt = document.querySelector(`#heroProductPicker .product-picker-option[data-id="${productId}"]`);
                    if (opt) {
                        selectSingleProduct('heroProductPicker', productId, opt.dataset.name, opt.dataset.url, opt.dataset.image);
                    } else {
                        selectSingleProduct('heroProductPicker', '', '', '', '');
                    }
                } else {
                    selectSingleProduct('heroProductPicker', '', '', '', '');
                }

                document.getElementById('image_preview').style.display = 'none';
                document.getElementById('mobile_image_preview').style.display = 'none';
                document.getElementById('slide_image_file').value = '';
                document.getElementById('slide_mobile_image_file').value = '';
                document.getElementById('slide_video_file').value = '';
                document.getElementById('slide_mobile_video_file').value = '';
                document.getElementById('slide_image_file').required = false;
                document.getElementById('slide_video_file').required = false;
                toggleSlideMediaFields();
                document.getElementById('formTitle').innerHTML = '<i class="bi bi-pencil-square"></i> EDIT SLIDE';
                document.getElementById('submitBtn').innerHTML = '<i class="bi bi-check-lg"></i> Update Slide';
                document.getElementById('cancelEditBtn').style.display = 'inline-flex';

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
                document.getElementById('addSlideForm').scrollIntoView({ behavior: 'smooth' });
            }

            function resetSlideForm() {
                currentSlideId = null;
                document.getElementById('slide_id').value = '';
                document.getElementById('slide_title').value = '';
                document.getElementById('slide_subtitle').value = '';
                document.getElementById('slide_media_type').value = 'image';
                document.getElementById('slide_media_type').dataset.original = 'image';
                document.getElementById('slide_image_file').value = '';
                document.getElementById('slide_mobile_image_file').value = '';
                document.getElementById('slide_video_file').value = '';
                document.getElementById('slide_mobile_video_file').value = '';
                document.getElementById('slide_button_text').value = '';
                document.getElementById('slide_button_link').value = '';
                selectSingleProduct('heroProductPicker', '', '', '', '');
                document.getElementById('slide_order').value = '0';
                document.getElementById('slide_active').checked = true;
                document.getElementById('image_preview').style.display = 'none';
                document.getElementById('mobile_image_preview').style.display = 'none';
                toggleSlideMediaFields();
                document.getElementById('formTitle').innerHTML = '<i class="bi bi-plus-circle"></i> ADD NEW SLIDE';
                document.getElementById('submitBtn').innerHTML = '<i class="bi bi-plus-lg"></i> Add Slide';
                document.getElementById('cancelEditBtn').style.display = 'none';
                var form = document.getElementById('slideForm');
                form.action = '{{ route('admin.settings.storeSlide') }}';
                if (document.getElementById('method_field')) document.getElementById('method_field').remove();
            }

            // Media Gallery Functions
            function showMediaUploadModal() {
                let modal = document.getElementById('mediaUploadModal');
                modal.classList.add('show');
                document.getElementById('mediaUploadForm').reset();
                const linkInput = document.getElementById('media_button_link');
                if (linkInput) linkInput.dataset.auto = 'true';

                // Clear multi product selection
                let select = document.getElementById('media_product_ids');
                if (select) {
                    Array.from(select.options).forEach(o => o.selected = false);
                }
                syncMultiProductUI('uploadProductPicker', 'media_product_ids');

                const uploadText = document.getElementById('media_file_text');
                if (uploadText) {
                    uploadText.innerHTML = `<i class="bi bi-cloud-arrow-up" style="font-size: 22px;"></i> Click or Drag & Drop File`;
                }
                const fileInput = document.getElementById('media_file');
                if (fileInput) fileInput.value = '';
                const previewDiv = document.getElementById('media_upload_preview');
                if (previewDiv) previewDiv.style.display = 'none';
                const prevImg = document.getElementById('media_upload_preview_img');
                if (prevImg) { prevImg.src = ''; prevImg.style.display = 'none'; }
                const prevVid = document.getElementById('media_upload_preview_video');
                if (prevVid) { prevVid.src = ''; prevVid.style.display = 'none'; }
            }

            function collectionUrlFromTitle(title) {
                let slug = (title || '')
                    .toString()
                    .trim()
                    .toLowerCase()
                    .replace(/&/g, ' and ')
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '')
                    .replace(/-(collection|collections)$/g, '');

                return slug ? `/collection/${slug}` : '';
            }

            function setupCollectionUrlAutofill(titleId, linkId) {
                const titleInput = document.getElementById(titleId);
                const linkInput = document.getElementById(linkId);

                if (!titleInput || !linkInput) return;

                linkInput.dataset.auto = linkInput.value ? 'false' : 'true';
                titleInput.addEventListener('input', function() {
                    if (linkInput.dataset.auto === 'true' || !linkInput.value.trim()) {
                        linkInput.value = collectionUrlFromTitle(this.value);
                        linkInput.dataset.auto = 'true';
                    }
                });
                linkInput.addEventListener('input', function() {
                    this.dataset.auto = this.value.trim() ? 'false' : 'true';
                });
            }

            function updateFileAccept() {
                const type = document.getElementById('media_file_type').value;
                const fileInput = document.getElementById('media_file');
                if (type === 'image') {
                    fileInput.accept = 'image/jpeg,image/jpg,image/png,image/webp';
                } else {
                    fileInput.accept = 'video/mp4,video/quicktime,video/webm';
                }
            }

            // File selection preview
            document.getElementById('media_file')?.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const file = e.target.files[0];
                    const uploadText = document.getElementById('media_file_text');
                    if (uploadText) {
                        uploadText.innerHTML = `<i class="bi bi-file-earmark-check-fill" style="font-size: 22px; color:#10b981"></i> <strong>${file.name}</strong> (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                    }

                    const previewDiv = document.getElementById('media_upload_preview');
                    const prevImg = document.getElementById('media_upload_preview_img');
                    const prevVid = document.getElementById('media_upload_preview_video');
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            if (prevImg) { prevImg.src = ev.target.result; prevImg.style.display = 'inline-block'; }
                            if (prevVid) { prevVid.style.display = 'none'; }
                            if (previewDiv) previewDiv.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    } else if (file.type.startsWith('video/')) {
                        const url = URL.createObjectURL(file);
                        if (prevVid) { prevVid.src = url; prevVid.style.display = 'inline-block'; }
                        if (prevImg) { prevImg.style.display = 'none'; }
                        if (previewDiv) previewDiv.style.display = 'block';
                    }
                }
            });

            // Media Upload Submission
            document.getElementById('mediaUploadForm')?.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const formData = new FormData();
                const title = document.getElementById('media_title').value;
                const subtitle = document.getElementById('media_subtitle').value;
                const buttonLink = document.getElementById('media_button_link').value;
                
                let select = document.getElementById('media_product_ids');
                let productIds = Array.from(select.selectedOptions).map(o => o.value);

                const fileType = document.getElementById('media_file_type').value;
                const file = document.getElementById('media_file').files[0];
                const sortOrder = document.getElementById('media_sort_order').value;
                const section = document.querySelector('input[name="section"]')?.value || 'gallery';
                
                if (!file) {
                    alert('Please select a file to upload');
                    return;
                }
                
                if (!title) {
                    alert('Please enter a title');
                    return;
                }
                
                formData.append('title', title);
                formData.append('subtitle', subtitle);
                formData.append('button_link', buttonLink);
                productIds.forEach(productId => formData.append('product_ids[]', productId));
                formData.append('file_type', fileType);
                formData.append('file', file);
                formData.append('sort_order', sortOrder || 0);
                formData.append('section', section);
                
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
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error uploading file: ' + error.message);
                } finally {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            });

            function setHeroMedia(id) {
                fetch('{{ route('admin.media.primary', '__MEDIA_ID__') }}'.replace('__MEDIA_ID__', id), {
                    method: 'POST',
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
                        alert(data.message || 'Unable to set hero media');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error setting hero media');
                });
            }

            function editMedia(id) {
                fetch(`/admin/media/gallery/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('edit_media_id').value = data.id;
                        document.getElementById('edit_media_title').value = data.title;
                        document.getElementById('edit_media_description').value = data.subtitle || '';
                        const linkInput = document.getElementById('edit_media_button_link');
                        linkInput.value = data.button_link || '';
                        linkInput.dataset.auto = linkInput.value ? 'false' : 'true';
                        
                        // Sync multi-product selection
                        let select = document.getElementById('edit_media_product_ids');
                        let selectedIds = (data.product_ids || []).map(i => i.toString());
                        if (select) {
                            Array.from(select.options).forEach(o => {
                                o.selected = selectedIds.includes(o.value);
                            });
                        }
                        syncMultiProductUI('editProductPicker', 'edit_media_product_ids');

                        document.getElementById('edit_media_sort_order').value = data.sort_order;
                        let modal = document.getElementById('editMediaModal');
                        modal.classList.add('show');
                    })
                    .catch(error => console.error('Error:', error));
            }

            document.getElementById('editMediaForm')?.addEventListener('submit', async (e) => {
                e.preventDefault();
                const id = document.getElementById('edit_media_id').value;
                let select = document.getElementById('edit_media_product_ids');
                let productIds = Array.from(select.selectedOptions).map(o => o.value);

                const formData = {
                    title: document.getElementById('edit_media_title').value,
                    subtitle: document.getElementById('edit_media_description').value,
                    button_link: document.getElementById('edit_media_button_link').value,
                    product_ids: productIds,
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

            function previewMedia(id) {
                fetch(`/admin/media/gallery/${id}`)
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

            function closeModal(modalId) {
                let modal = document.getElementById(modalId);
                if (modal) modal.classList.remove('show');
            }

            window.onclick = function(event) {
                if (event.target.classList.contains('modal')) {
                    event.target.classList.remove('show');
                }
            }

            setupCollectionUrlAutofill('media_title', 'media_button_link');
            setupCollectionUrlAutofill('edit_media_title', 'edit_media_button_link');

            const style = document.createElement('style');
            style.textContent = `.spin { animation: spin 1s linear infinite; } @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }`;
            document.head.appendChild(style);
        </script>
    @endpush
@endsection
