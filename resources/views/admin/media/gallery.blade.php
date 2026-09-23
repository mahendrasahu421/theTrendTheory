{{-- resources/views/admin/media/gallery.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Lookbook & Media Gallery')

@section('content')
<style>
    .gallery-studio-root {
        display: flex;
        flex-direction: column;
        gap: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }


    /* Buttons */
    .btn-studio-gold {
        background: #ffd700;
        color: #00285a;
        font-weight: 800;
        font-size: 12.5px;
        padding: 9px 20px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 14px rgba(255, 215, 0, 0.35);
        transition: all 0.15s ease;
    }
    .btn-studio-gold:hover {
        background: #ffea79;
        transform: translateY(-1px);
    }
    .btn-studio-outline {
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff;
        font-weight: 700;
        font-size: 12px;
        padding: 9px 16px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }
    .btn-studio-outline:hover {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff;
    }

    /* KPI Grid */
    .kpi-row {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }
    @media (max-width: 900px) {
        .kpi-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (max-width: 500px) {
        .kpi-row {
            grid-template-columns: 1fr;
        }
    }
    .kpi-stat-card {
        background: #ffffff;
        border: 1.5px solid #eef2f6;
        border-radius: 14px;
        padding: 16px 18px;
        box-shadow: 0 2px 8px rgba(0, 40, 90, 0.02);
        transition: all 0.2s ease;
    }
    .kpi-stat-card:hover {
        border-color: #00285a;
        transform: translateY(-2px);
    }
    .kpi-top-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
    }
    .kpi-label-text {
        font-size: 10.5px;
        font-weight: 800;
        color: #64748b;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .kpi-icon-pill {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }
    .kpi-num {
        font-family: 'Cinzel', serif;
        font-size: 24px;
        font-weight: 700;
        line-height: 1.1;
    }
    .kpi-sub-text {
        font-size: 11px;
        color: #64748b;
        margin-top: 2px;
    }

    /* Media Cards Grid */
    .media-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }
    .gallery-media-card {
        background: #ffffff;
        border: 1.5px solid #eef2f6;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(0, 40, 90, 0.03);
        display: flex;
        flex-direction: column;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .gallery-media-card:hover {
        transform: translateY(-4px);
        border-color: #cbd5e1;
        box-shadow: 0 12px 28px rgba(0, 40, 90, 0.08);
    }
    .media-card-preview {
        height: 200px;
        background: #0f172a;
        position: relative;
        overflow: hidden;
    }
    .media-card-preview img, .media-card-preview video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .media-type-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        color: #ffffff;
        background: rgba(0, 0, 0, 0.65);
        backdrop-filter: blur(4px);
    }
    .primary-hero-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 800;
        color: #00285a;
        background: #ffd700;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    .media-card-body {
        padding: 16px 18px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .media-card-title {
        font-size: 14px;
        font-weight: 700;
        color: #00285a;
        margin: 0 0 3px;
        line-height: 1.35;
    }
    .media-card-sub {
        font-size: 11.5px;
        color: #64748b;
        margin: 0 0 12px;
    }
    .media-card-link {
        font-size: 11px;
        color: #0891b2;
        font-family: monospace;
        background: #ecfeff;
        border: 1px solid #cffafe;
        padding: 3px 8px;
        border-radius: 6px;
        width: fit-content;
        margin-bottom: 12px;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        white-space: nowrap;
    }

    .media-card-footer {
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
        margin-top: auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .tagged-products-pill {
        font-size: 11px;
        font-weight: 700;
        color: #334155;
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 6px;
    }

    /* Action Buttons inside cards */
    .btn-icon-action {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #00285a;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        transition: all 0.15s ease;
    }
    .btn-icon-action:hover {
        background: #00285a;
        color: #ffffff;
        border-color: #00285a;
    }
    .btn-icon-delete {
        border-color: #fecaca;
        background: #fef2f2;
        color: #dc2626;
    }
    .btn-icon-delete:hover {
        background: #dc2626;
        color: #ffffff;
        border-color: #dc2626;
    }

    /* ── High-End Studio Modal Styling ── */
    .modal-backdrop-custom {
        position: fixed;
        inset: 0;
        background: rgba(11, 25, 46, 0.68);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        transition: opacity 0.2s ease;
    }
    .modal-box-custom {
        background: #ffffff;
        border-radius: 24px;
        width: 100%;
        max-width: 620px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 60px -15px rgba(11, 25, 46, 0.35);
        border: 1px solid rgba(255, 255, 255, 0.8);
        animation: modalScaleUp 0.28s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
    }
    @keyframes modalScaleUp {
        from { transform: translateY(16px) scale(0.95); opacity: 0; }
        to { transform: translateY(0) scale(1); opacity: 1; }
    }
    .modal-header-custom {
        padding: 22px 26px 18px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fafcff;
        border-radius: 24px 24px 0 0;
    }
    .modal-header-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 10.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #4f46e5;
        background: #eef2ff;
        padding: 3px 10px;
        border-radius: 999px;
        margin-bottom: 4px;
    }
    .modal-title-custom {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modal-close-btn {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }
    .modal-close-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }
    .modal-body-custom {
        padding: 24px 26px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .modal-footer-custom {
        padding: 16px 26px;
        border-top: 1px solid #f1f5f9;
        background: #fafcff;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        border-radius: 0 0 24px 24px;
    }

    /* Form Fields inside Modal */
    .form-group-modal {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .label-modal {
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .input-modal {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 11px;
        font-size: 13px;
        font-family: inherit;
        color: #0f172a;
        outline: none;
        background: #ffffff;
        transition: all 0.15s ease;
    }
    .input-modal:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.08);
    }

    /* Modern Dropzone inside modal */
    .upload-zone-modal {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        padding: 26px 20px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }
    .upload-zone-modal:hover {
        border-color: #4f46e5;
        background: #eef2ff;
    }
    .upload-icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #eff6ff;
        color: #2563eb;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 8px;
    }

    /* Product Tagger Box */
    .product-tagger-box {
        max-height: 140px;
        overflow-y: auto;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 8px;
        background: #fafcff;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .product-tag-row {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 10px;
        border-radius: 8px;
        background: #ffffff;
        border: 1px solid #f1f5f9;
        font-size: 12px;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .product-tag-row:hover {
        background: #eff6ff;
        border-color: #bfdbfe;
    }

    .btn-modal-cancel {
        background: #ffffff;
        color: #64748b;
        font-weight: 700;
        font-size: 12.5px;
        padding: 9px 18px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-modal-cancel:hover {
        background: #f8fafc;
        color: #0f172a;
    }
    .btn-modal-submit {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: #ffffff;
        font-weight: 800;
        font-size: 13px;
        padding: 10px 22px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
        transition: all 0.15s ease;
    }
    .btn-modal-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(79, 70, 229, 0.45);
    }

    /* Toast Alert */
    .gallery-toast {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 99999;
        background: #0f172a;
        color: #ffffff;
        padding: 12px 20px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        display: none;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 600;
    }
</style>

<div class="gallery-studio-root">

  
    {{-- 2. KPI Metrics Bento Grid --}}
    <div class="kpi-row">
        <div class="kpi-stat-card">
            <div class="kpi-top-meta">
                <span class="kpi-label-text">Total Media Items</span>
                <div class="kpi-icon-pill" style="background:#eff6ff; color:#00285a;"><i class="bi bi-images"></i></div>
            </div>
            <div class="kpi-num" style="color:#00285a;">{{ number_format($stats['total']) }}</div>
            <div class="kpi-sub-text">Showcase assets on site</div>
        </div>

        <div class="kpi-stat-card">
            <div class="kpi-top-meta">
                <span class="kpi-label-text">Editorial Photos</span>
                <div class="kpi-icon-pill" style="background:#ecfdf5; color:#047857;"><i class="bi bi-image-fill"></i></div>
            </div>
            <div class="kpi-num" style="color:#047857;">{{ number_format($stats['images_count']) }}</div>
            <div class="kpi-sub-text">High-res lookbook photos</div>
        </div>

        <div class="kpi-stat-card">
            <div class="kpi-top-meta">
                <span class="kpi-label-text">Showcase Videos</span>
                <div class="kpi-icon-pill" style="background:#faf5ff; color:#7e22ce;"><i class="bi bi-play-circle-fill"></i></div>
            </div>
            <div class="kpi-num" style="color:#7e22ce;">{{ number_format($stats['videos_count']) }}</div>
            <div class="kpi-sub-text">Cinematic hero reels</div>
        </div>

        <div class="kpi-stat-card">
            <div class="kpi-top-meta">
                <span class="kpi-label-text">Hero Spotlight</span>
                <div class="kpi-icon-pill" style="background:#fffbeb; color:#b45309;"><i class="bi bi-star-fill"></i></div>
            </div>
            <div class="kpi-num" style="color:#b45309; font-size:16px; margin-top:4px;">
                {{ $stats['hero_video'] ? Str::limit($stats['hero_video']->alt_text ?: 'Primary Media', 18) : 'Not Set' }}
            </div>
            <div class="kpi-sub-text">Active top showcase asset</div>
        </div>
    </div>

    {{-- 3. Gallery Media Cards Grid --}}
    <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 style="font-family:'Cinzel', serif; font-size:17px; font-weight:700; color:#00285a; margin:0;">
                All Showcase Assets ({{ $galleryItems->count() }})
            </h3>
            <span class="text-muted font-xs">Sorted by priority order</span>
        </div>

        <div class="media-cards-grid" id="galleryGrid">
            @forelse ($galleryItems as $item)
                @php
                    $isVideo = str_starts_with($item->mime_type, 'video/');
                @endphp
                <div class="gallery-media-card" id="mediaCard{{ $item->id }}">
                    {{-- Media Preview Box --}}
                    <div class="media-card-preview">
                        @if ($isVideo)
                            <video src="{{ $item->url }}" muted loop preload="metadata" onmouseover="this.play()" onmouseout="this.pause()"></video>
                            <span class="media-type-badge"><i class="bi bi-camera-video-fill me-1"></i> Video</span>
                        @else
                            <img src="{{ $item->url }}" alt="{{ $item->alt_text }}">
                            <span class="media-type-badge"><i class="bi bi-image-fill me-1"></i> Photo</span>
                        @endif

                        @if ($item->is_primary)
                            <span class="primary-hero-badge">★ HERO SPOTLIGHT</span>
                        @endif
                    </div>

                    {{-- Body Info --}}
                    <div class="media-card-body">
                        <h4 class="media-card-title">{{ $item->alt_text ?: 'Untitled Showcase' }}</h4>
                        @if ($item->subtitle)
                            <p class="media-card-sub">{{ $item->subtitle }}</p>
                        @endif

                        @if ($item->button_link)
                            <div class="media-card-link" title="{{ $item->button_link }}">
                                <i class="bi bi-link-45deg me-1"></i>{{ $item->button_link }}
                            </div>
                        @endif

                        {{-- Footer Actions --}}
                        <div class="media-card-footer">
                            <span class="tagged-products-pill">
                                <i class="bi bi-tag-fill me-1 text-muted"></i>
                                {{ $item->products->count() }} Linked Products
                            </span>

                            <div style="display: flex; gap: 4px;">
                                <button type="button" class="btn-icon-action" onclick="openEditModal({{ $item->id }})" title="Edit Details">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button type="button" class="btn-icon-action btn-icon-delete" onclick="deleteMedia({{ $item->id }})" title="Delete Media">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: #ffffff; border: 1.5px solid #eef2f6; border-radius: 16px;">
                    <i class="bi bi-images" style="font-size: 36px; color: #cbd5e1; display: block; margin-bottom: 8px;"></i>
                    <h4 style="font-weight: 700; color: #00285a; margin-bottom: 4px;">No Showcase Media Uploaded</h4>
                    <p style="font-size: 13px; color: #64748b; margin-bottom: 16px;">Upload lookbook photos or showcase videos to power the homepage visual slider.</p>
                    <button type="button" class="btn-studio-gold" onclick="openUploadModal()">
                        <i class="bi bi-cloud-arrow-up-fill"></i> Upload First Media
                    </button>
                </div>
            @endforelse
        </div>
    </div>

</div>

{{-- ── 4. UPLOAD MEDIA MODAL ── --}}
<div class="modal-backdrop-custom" id="uploadModal">
    <div class="modal-box-custom">
        <div class="modal-header-custom">
            <div>
                <span class="modal-header-badge"><i class="bi bi-stars"></i> SHOWCASE STUDIO</span>
                <h3 class="modal-title-custom">
                    <i class="bi bi-cloud-arrow-up-fill text-primary"></i> Upload Showcase Media
                </h3>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeUploadModal()">&times;</button>
        </div>
        <form id="uploadForm" onsubmit="submitUpload(event)">
            @csrf
            <div class="modal-body-custom">
                {{-- Drop Zone --}}
                <div class="form-group-modal">
                    <label class="label-modal">
                        <span>Select Photo or Video File</span>
                        <span class="text-danger font-xs">* Required</span>
                    </label>
                    <div class="upload-zone-modal" onclick="document.getElementById('uploadFileInput').click()" id="uploadDropZone">
                        <div class="upload-icon-circle">
                            <i class="bi bi-cloud-arrow-up-fill" id="uploadDropIcon"></i>
                        </div>
                        <strong class="d-block font-xs text-navy mb-1" id="uploadFileName">Click or Drag &amp; Drop Photo (JPG/PNG/WebP) or Video (MP4)</strong>
                        <span class="text-muted font-xs">Max file size: 50MB &bull; Cloudinary CDN Optimized</span>
                        <input type="file" name="file" id="uploadFileInput" accept="image/*,video/*" class="d-none" required onchange="handleFileSelected(this)">
                    </div>
                </div>

                {{-- Title / Headline --}}
                <div class="form-group-modal">
                    <label class="label-modal">
                        <span>Headline / Title</span>
                        <span class="text-danger font-xs">* Required</span>
                    </label>
                    <input type="text" name="title" placeholder="e.g. Summer 2026 Fluid Tailoring Drop" class="input-modal" required>
                </div>

                {{-- Subtitle --}}
                <div class="form-group-modal">
                    <label class="label-modal">
                        <span>Subtitle / Tagline</span>
                        <span class="text-muted font-xs">Optional</span>
                    </label>
                    <input type="text" name="subtitle" placeholder="e.g. Heavyweight Cotton Essentials" class="input-modal">
                </div>

                {{-- Button CTA Link --}}
                <div class="form-group-modal">
                    <label class="label-modal">
                        <span>Action CTA Link</span>
                        <span class="text-muted font-xs">Optional storefront destination</span>
                    </label>
                    <input type="text" name="button_link" placeholder="e.g. /shop or /collections/summer-2026" class="input-modal">
                </div>

                {{-- Tagged Products --}}
                @if ($products->isNotEmpty())
                    <div class="form-group-modal">
                        <label class="label-modal">
                            <span>Tag Linked Products (Featured on Click)</span>
                            <span class="badge bg-light text-navy border font-xs">{{ $products->count() }} Available</span>
                        </label>
                        <div class="product-tagger-box">
                            @foreach ($products as $p)
                                <label class="product-tag-row">
                                    <input type="checkbox" name="product_ids[]" value="{{ $p->id }}" class="form-check-input mt-0">
                                    <span style="font-weight: 700; color: #0f172a;">{{ $p->name }}</span>
                                    <span class="badge bg-light text-muted border font-xs ms-auto">₹{{ number_format($p->price) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" onclick="closeUploadModal()">Cancel</button>
                <button type="submit" class="btn-modal-submit" id="uploadSubmitBtn">
                    <i class="bi bi-cloud-arrow-up-fill"></i> Start Upload
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── 5. EDIT MEDIA MODAL ── --}}
<div class="modal-backdrop-custom" id="editModal">
    <div class="modal-box-custom">
        <div class="modal-header-custom">
            <div>
                <span class="modal-header-badge"><i class="bi bi-pencil"></i> EDIT ASSET</span>
                <h3 class="modal-title-custom">
                    <i class="bi bi-pencil-square text-primary"></i> Edit Media Details
                </h3>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="editForm" onsubmit="submitEdit(event)">
            @csrf
            <input type="hidden" id="editMediaId">
            <div class="modal-body-custom">
                {{-- Current Media Preview --}}
                <div class="form-group-modal">
                    <label class="label-modal">
                        <span>Current Showcase Asset</span>
                    </label>
                    <div id="editCurrentMediaContainer" style="background:#0f172a; border-radius:12px; overflow:hidden; position:relative; min-height:160px; max-height:220px; display:flex; align-items:center; justify-content:center; border: 1.5px solid #cbd5e1;">
                        <img id="editCurrentImg" src="" style="width:100%; max-height:220px; object-fit:contain; display:none;">
                        <video id="editCurrentVideo" src="" controls style="width:100%; max-height:220px; object-fit:contain; display:none;"></video>
                        <span id="editCurrentBadge" class="media-type-badge" style="position:absolute; top:12px; right:12px; z-index:2;"></span>
                    </div>
                </div>

                {{-- Replace Media Drop Zone --}}
                <div class="form-group-modal">
                    <label class="label-modal">
                        <span>Replace Photo or Video File</span>
                        <span class="text-muted font-xs">Optional</span>
                    </label>
                    <div class="upload-zone-modal" onclick="document.getElementById('editFileInput').click()" id="editDropZone" style="padding:16px;">
                        <div class="upload-icon-circle" style="width:38px; height:38px; margin-bottom:6px;">
                            <i class="bi bi-cloud-arrow-up-fill" style="font-size:18px;"></i>
                        </div>
                        <strong class="d-block font-xs text-navy mb-1" id="editFileName">Click or Drag &amp; Drop New File to Replace</strong>
                        <span class="text-muted font-xs">Leave empty to keep existing media &bull; Max 50MB</span>
                        <input type="file" name="file" id="editFileInput" accept="image/*,video/*" class="d-none" onchange="handleEditFileSelectedGallery(this)">
                    </div>

                    <div id="editNewFilePreview" style="display:none; margin-top:10px; text-align:center; padding:12px; background:#f0fdf4; border:1.5px dashed #86efac; border-radius:12px;">
                        <div style="font-size:11.5px; font-weight:700; color:#15803d; margin-bottom:8px; display:flex; align-items:center; justify-content:center; gap:6px;">
                            <i class="bi bi-check-circle-fill"></i> New File Selected (Will Replace on Save)
                        </div>
                        <img id="editNewFileImg" src="" style="max-height:140px; border-radius:10px; display:none; margin:0 auto; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                        <video id="editNewFileVideo" src="" controls style="max-height:140px; border-radius:10px; display:none; margin:0 auto; box-shadow:0 4px 12px rgba(0,0,0,0.1);"></video>
                        <div style="margin-top:10px;">
                            <button type="button" class="btn btn-sm btn-outline-danger" style="font-size:11.5px; font-weight:700; border-radius:8px;" onclick="clearEditFileGallery()">
                                <i class="bi bi-x-circle"></i> Cancel Replacement (Keep Original)
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Title / Headline --}}
                <div class="form-group-modal">
                    <label class="label-modal">
                        <span>Headline / Title</span>
                        <span class="text-danger font-xs">* Required</span>
                    </label>
                    <input type="text" name="title" id="editTitle" class="input-modal" required>
                </div>

                {{-- Subtitle --}}
                <div class="form-group-modal">
                    <label class="label-modal">
                        <span>Subtitle / Tagline</span>
                        <span class="text-muted font-xs">Optional</span>
                    </label>
                    <input type="text" name="subtitle" id="editSubtitle" class="input-modal">
                </div>

                {{-- Button CTA Link --}}
                <div class="form-group-modal">
                    <label class="label-modal">
                        <span>Action CTA Link</span>
                        <span class="text-muted font-xs">Optional</span>
                    </label>
                    <input type="text" name="button_link" id="editButtonLink" class="input-modal">
                </div>

                {{-- Tagged Products --}}
                @if ($products->isNotEmpty())
                    <div class="form-group-modal">
                        <label class="label-modal">
                            <span>Tag Linked Products</span>
                            <span class="badge bg-light text-navy border font-xs">{{ $products->count() }} Available</span>
                        </label>
                        <div class="product-tagger-box" id="editProductsList">
                            @foreach ($products as $p)
                                <label class="product-tag-row">
                                    <input type="checkbox" name="product_ids[]" value="{{ $p->id }}" class="edit-prod-check form-check-input mt-0" id="editProd{{ $p->id }}">
                                    <span style="font-weight: 700; color: #0f172a;">{{ $p->name }}</span>
                                    <span class="badge bg-light text-muted border font-xs ms-auto">₹{{ number_format($p->price) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn-modal-submit" id="editSubmitBtn">
                    <i class="bi bi-check2-circle"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Toast Alert --}}
<div id="galleryToast" class="gallery-toast">
    <i class="bi bi-check-circle-fill text-success fs-5"></i>
    <span id="galleryToastMsg">Media updated successfully</span>
</div>

<script>
    function showToast(msg) {
        const toast = document.getElementById('galleryToast');
        const toastMsg = document.getElementById('galleryToastMsg');
        if (toast && toastMsg) {
            toastMsg.textContent = msg;
            toast.style.display = 'flex';
            setTimeout(() => { toast.style.display = 'none'; }, 3000);
        }
    }

    function openUploadModal() {
        document.getElementById('uploadModal').style.display = 'flex';
    }
    function closeUploadModal() {
        document.getElementById('uploadModal').style.display = 'none';
        document.getElementById('uploadForm').reset();
        document.getElementById('uploadFileName').textContent = 'Click to browse Photo (JPG/PNG/WebP) or Video (MP4)';
    }

    function handleFileSelected(input) {
        if (input.files && input.files[0]) {
            document.getElementById('uploadFileName').textContent = input.files[0].name + ` (${(input.files[0].size / (1024*1024)).toFixed(1)} MB)`;
        }
    }

    function submitUpload(e) {
        e.preventDefault();
        const form = document.getElementById('uploadForm');
        const btn = document.getElementById('uploadSubmitBtn');
        const formData = new FormData(form);

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Uploading to Cloud...';

        fetch("{{ route('admin.media.upload-gallery') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-upload"></i> Start Upload';
            if (data.success) {
                closeUploadModal();
                showToast('Media uploaded successfully!');
                setTimeout(() => window.location.reload(), 800);
            } else {
                alert(data.message || 'Upload failed');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-upload"></i> Start Upload';
            alert('Upload error. Please check file size.');
        });
    }

    function clearEditFileGallery() {
        const fileInput = document.getElementById('editFileInput');
        if (fileInput) fileInput.value = '';
        const previewDiv = document.getElementById('editNewFilePreview');
        if (previewDiv) previewDiv.style.display = 'none';
        const prevImg = document.getElementById('editNewFileImg');
        if (prevImg) { prevImg.src = ''; prevImg.style.display = 'none'; }
        const prevVid = document.getElementById('editNewFileVideo');
        if (prevVid) { prevVid.src = ''; prevVid.style.display = 'none'; }
        const labelText = document.getElementById('editFileName');
        if (labelText) {
            labelText.textContent = 'Click or Drag & Drop New File to Replace';
        }
    }

    function handleEditFileSelectedGallery(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const labelText = document.getElementById('editFileName');
            if (labelText) {
                labelText.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            }

            const previewDiv = document.getElementById('editNewFilePreview');
            const prevImg = document.getElementById('editNewFileImg');
            const prevVid = document.getElementById('editNewFileVideo');

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
    }

    function openEditModal(mediaId) {
        clearEditFileGallery();
        fetch(`/admin/media/gallery/${mediaId}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('editMediaId').value = data.id;
            document.getElementById('editTitle').value = data.title || '';
            document.getElementById('editSubtitle').value = data.subtitle || '';
            document.getElementById('editButtonLink').value = data.button_link || '';

            // Current media preview
            const currentImg = document.getElementById('editCurrentImg');
            const currentVid = document.getElementById('editCurrentVideo');
            const currentBadge = document.getElementById('editCurrentBadge');

            const isVideo = data.type === 'video' || (data.mime_type && data.mime_type.startsWith('video/'));
            if (isVideo) {
                if (currentVid) { currentVid.src = data.url; currentVid.style.display = 'block'; }
                if (currentImg) { currentImg.style.display = 'none'; }
                if (currentBadge) {
                    currentBadge.innerHTML = '<i class="bi bi-camera-video-fill me-1"></i> Current Video';
                }
            } else {
                if (currentImg) { currentImg.src = data.url; currentImg.style.display = 'block'; }
                if (currentVid) { currentVid.style.display = 'none'; }
                if (currentBadge) {
                    currentBadge.innerHTML = '<i class="bi bi-image-fill me-1"></i> Current Photo';
                }
            }

            // Reset product checkboxes
            document.querySelectorAll('.edit-prod-check').forEach(cb => {
                cb.checked = data.product_ids && data.product_ids.includes(parseInt(cb.value));
            });

            document.getElementById('editModal').style.display = 'flex';
        })
        .catch(err => alert('Failed to fetch media details'));
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    function submitEdit(e) {
        e.preventDefault();
        const mediaId = document.getElementById('editMediaId').value;
        const form = document.getElementById('editForm');
        const btn = document.getElementById('editSubmitBtn');

        const formData = new FormData(form);
        formData.append('_method', 'PUT');

        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-arrow-repeat spin me-1"></i> Saving Changes...';

        fetch(`/admin/media/gallery/${mediaId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            if (data.success) {
                closeEditModal();
                showToast('Media details and file saved successfully!');
                setTimeout(() => window.location.reload(), 600);
            } else {
                alert(data.message || 'Update failed');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            alert('Failed to update media: ' + err.message);
        });
    }

    function deleteMedia(mediaId) {
        if (!confirm('Are you sure you want to delete this media asset? It will be removed from the homepage slider.')) {
            return;
        }

        fetch(`/admin/media/gallery/${mediaId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const card = document.getElementById('mediaCard' + mediaId);
                if (card) card.remove();
                showToast('Media deleted successfully');
            } else {
                alert(data.message || 'Failed to delete');
            }
        })
        .catch(err => alert('Failed to delete media'));
    }
</script>
@endsection
