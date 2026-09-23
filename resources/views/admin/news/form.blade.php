{{-- resources/views/admin/news/form.blade.php --}}
@extends('admin.layouts.app')
@section('title', $title)

@section('content')
<style>
    .editor-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
        max-width: 1300px;
        margin: 0 auto;
    }

    /* Top Sticky Header */
    .editor-top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        background: #ffffff;
        padding: 16px 22px;
        border-radius: 16px;
        border: 1.5px solid #eef2f6;
        box-shadow: 0 4px 16px rgba(67, 56, 202, 0.04);
    }
    .editor-badge-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #e0e7ff;
        color: #4338ca;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        padding: 3px 10px;
        border-radius: 999px;
        border: 1px solid #c7d2fe;
    }
    .editor-title {
        font-family: 'Cinzel', serif;
        font-size: 20px;
        font-weight: 700;
        color: #1e1b4b;
        margin: 2px 0 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Grid Layout */
    .editor-grid-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 20px;
        align-items: start;
    }
    @media (max-width: 1024px) {
        .editor-grid-layout {
            grid-template-columns: 1fr;
        }
    }

    /* Form Cards */
    .editor-card {
        background: #ffffff;
        border: 1.5px solid #eef2f6;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.02);
        margin-bottom: 20px;
        transition: border-color 0.2s ease;
    }
    .editor-card:hover {
        border-color: #c7d2fe;
    }
    .editor-card-head {
        padding: 14px 20px;
        background: #fafbff;
        border-bottom: 1.5px solid #eef2f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .editor-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13.5px;
        font-weight: 800;
        color: #1e1b4b;
        margin: 0;
    }
    .editor-card-title i {
        font-size: 16px;
        color: #4338ca;
    }
    .editor-card-body {
        padding: 20px;
    }

    /* Input Groups */
    .form-group-wrap {
        margin-bottom: 18px;
    }
    .form-group-wrap:last-child {
        margin-bottom: 0;
    }
    .field-label {
        font-size: 12px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .field-label i {
        color: #6366f1;
        margin-right: 4px;
    }
    .field-char-count {
        font-size: 10.5px;
        color: #94a3b8;
        font-weight: 600;
    }

    .form-input-modern {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px;
        font-family: inherit;
        outline: none;
        background: #ffffff;
        color: #0f172a;
        transition: all 0.15s ease;
    }
    .form-input-modern:focus {
        border-color: #4338ca;
        box-shadow: 0 0 0 3px rgba(67, 56, 202, 0.08);
    }
    .form-input-title {
        font-size: 16px;
        font-weight: 700;
        padding: 12px 16px;
        color: #1e1b4b;
    }

    /* Slug Box */
    .slug-preview-container {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }
    .slug-prefix {
        padding: 10px 12px;
        background: #f1f5f9;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        border-right: 1.5px solid #e2e8f0;
        white-space: nowrap;
        font-family: monospace;
    }
    .slug-input {
        border: none;
        background: transparent;
        padding: 10px 12px;
        font-size: 12.5px;
        color: #0f172a;
        outline: none;
        flex: 1;
        font-family: monospace;
    }

    /* Formatting Toolbar */
    .toolbar-strip {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 4px;
        padding: 8px 12px;
        background: #fafbff;
        border: 1.5px solid #e2e8f0;
        border-bottom: none;
        border-radius: 10px 10px 0 0;
    }
    .tool-btn {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 700;
        color: #334155;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.15s ease;
    }
    .tool-btn:hover {
        background: #4338ca;
        color: #ffffff;
        border-color: #4338ca;
    }
    .tool-btn i {
        font-size: 13px;
    }
    .content-textarea {
        width: 100%;
        padding: 14px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 0 0 10px 10px;
        font-size: 13.5px;
        line-height: 1.7;
        font-family: inherit;
        outline: none;
        background: #ffffff;
        color: #1e293b;
        resize: vertical;
        min-height: 320px;
    }
    .content-textarea:focus {
        border-color: #4338ca;
        box-shadow: 0 0 0 3px rgba(67, 56, 202, 0.08);
    }
    .editor-stats-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 6px;
        font-size: 11.5px;
        color: #64748b;
    }

    /* Cover Upload Zone */
    .cover-drop-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 24px 16px;
        text-align: center;
        background: #fafbff;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }
    .cover-drop-zone:hover {
        border-color: #4338ca;
        background: #f5f3ff;
    }
    .cover-drop-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #e0e7ff;
        color: #4338ca;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin: 0 auto 10px;
    }
    .cover-preview-holder {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 12px;
        border: 1.5px solid #e2e8f0;
        background: #000;
    }
    .cover-preview-img {
        width: 100%;
        max-height: 200px;
        object-fit: cover;
        display: block;
    }

    /* Switches */
    .switch-card-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        background: #f8fafc;
        border: 1.5px solid #eef2f6;
        border-radius: 10px;
        margin-bottom: 10px;
    }
    .switch-title {
        font-size: 12.5px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }
    .switch-sub {
        font-size: 11px;
        color: #64748b;
        margin: 1px 0 0;
    }

    /* Action Buttons */
    .btn-publish-submit {
        background: #4338ca;
        color: #ffffff;
        border: none;
        padding: 11px 22px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(67, 56, 202, 0.25);
        transition: all 0.2s ease;
        width: 100%;
    }
    .btn-publish-submit:hover {
        background: #3730a3;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(67, 56, 202, 0.35);
    }
    .btn-cancel-link {
        padding: 8px 16px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-cancel-link:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* Quick category pills */
    .quick-cat-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-top: 8px;
    }
    .quick-cat-tag {
        font-size: 10.5px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 6px;
        background: #f1f5f9;
        color: #475569;
        cursor: pointer;
        border: 1px solid #e2e8f0;
        transition: all 0.15s ease;
    }
    .quick-cat-tag:hover {
        background: #4338ca;
        color: #ffffff;
        border-color: #4338ca;
    }
</style>

<div class="editor-container">

    {{-- Form Body --}}
    <form method="POST" action="{{ $isEdit ? route('admin.news.update', $news) : route('admin.news.store') }}" enctype="multipart/form-data" id="newsForm">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        {{-- 1. Top Bar Header --}}
        <div class="editor-top-bar">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.news.index') }}" class="btn-cancel-link" title="Back to All News">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <div class="editor-badge-tag">
                        <i class="bi bi-newspaper"></i> BRAND PRESS DESK
                    </div>
                    <h1 class="editor-title">
                        {{ $isEdit ? 'Edit News Announcement' : 'Publish Press Release' }}
                    </h1>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @if ($isEdit && $news->is_published)
                    <a href="{{ route('news.show', $news->slug) }}" target="_blank" class="btn-cancel-link" style="background:#e0e7ff; color:#4338ca; border-color:#c7d2fe;">
                        <i class="bi bi-box-arrow-up-right"></i> Live View
                    </a>
                @endif
                <a href="{{ route('admin.news.index') }}" class="btn-cancel-link">
                    Cancel
                </a>
                <button type="submit" class="btn-publish-submit" style="width: auto; padding: 8px 20px;">
                    <i class="bi bi-check2-circle"></i> {{ $isEdit ? 'Save Changes' : 'Publish News' }}
                </button>
            </div>
        </div>

        {{-- 2. Two-Column Editor Grid --}}
        <div class="editor-grid-layout mt-3">

            {{-- ── Left Column: Main Canvas ── --}}
            <div class="editor-left-pane">
                
                {{-- Card 1: Title, Slug & Summary --}}
                <div class="editor-card">
                    <div class="editor-card-head">
                        <h2 class="editor-card-title">
                            <i class="bi bi-megaphone-fill"></i> Headline &amp; Executive Summary
                        </h2>
                        <span class="badge bg-light text-dark font-xs border px-2 py-1">Required</span>
                    </div>

                    <div class="editor-card-body">
                        {{-- Title Input --}}
                        <div class="form-group-wrap">
                            <label class="field-label" for="articleTitle">
                                <span><i class="bi bi-fonts"></i> News Headline <span class="text-danger">*</span></span>
                                <span class="field-char-count" id="titleCharCount">0 / 255</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="articleTitle"
                                   value="{{ old('title', $news->title) }}" 
                                   placeholder="e.g. THE TREND THEORY Expands Next-Day Delivery to 500+ Cities in India" 
                                   class="form-input-modern form-input-title @error('title') is-invalid @enderror"
                                   required 
                                   maxlength="255"
                                   oninput="handleTitleInput(this.value)">
                            @error('title')
                                <small class="text-danger mt-1 d-block font-xs"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- URL Permaslug --}}
                        <div class="form-group-wrap">
                            <label class="field-label" for="articleSlug">
                                <span><i class="bi bi-link-45deg"></i> News Permaslug</span>
                                <span class="field-char-count">Auto-generated for PR SEO</span>
                            </label>
                            <div class="slug-preview-container">
                                <span class="slug-prefix">/news/</span>
                                <input type="text" 
                                       name="slug" 
                                       id="articleSlug"
                                       value="{{ old('slug', $news->slug) }}" 
                                       placeholder="the-trend-theory-expands-next-day-delivery" 
                                       class="slug-input">
                            </div>
                        </div>

                        {{-- Excerpt Summary --}}
                        <div class="form-group-wrap">
                            <label class="field-label" for="articleSummary">
                                <span><i class="bi bi-card-text"></i> Media Executive Summary (Press Snippet)</span>
                                <span class="field-char-count" id="summaryCharCount">0 / 300</span>
                            </label>
                            <textarea name="summary" 
                                      id="articleSummary"
                                      rows="3" 
                                      placeholder="A 1-2 sentence high-impact summary for media publications, partners, and press distributors..." 
                                      class="form-input-modern @error('summary') is-invalid @enderror"
                                      maxlength="300"
                                      oninput="updateSummaryCount(this.value)">{{ old('summary', $news->summary) }}</textarea>
                            @error('summary')
                                <small class="text-danger mt-1 d-block font-xs">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Card 2: Press Release Full Statement --}}
                <div class="editor-card">
                    <div class="editor-card-head">
                        <h2 class="editor-card-title">
                            <i class="bi bi-textarea-t"></i> Official Press Statement &amp; Body
                        </h2>
                        <span class="badge bg-light text-dark font-xs border px-2 py-1">Official Release</span>
                    </div>

                    <div class="editor-card-body p-0">
                        {{-- Formatting Toolstrip --}}
                        <div class="toolbar-strip">
                            <button type="button" class="tool-btn" onclick="insertTag('h2')" title="Add Section Title">
                                <i class="bi bi-type-h2"></i> Heading
                            </button>
                            <button type="button" class="tool-btn" onclick="insertTag('h3')" title="Add Subheading">
                                <i class="bi bi-type-h3"></i> Subhead
                            </button>
                            <button type="button" class="tool-btn" onclick="insertTag('bold')" title="Bold text">
                                <i class="bi bi-type-bold"></i> Bold
                            </button>
                            <button type="button" class="tool-btn" onclick="insertTag('quote')" title="Official Quote from Leadership">
                                <i class="bi bi-quote"></i> Executive Quote
                            </button>
                            <button type="button" class="tool-btn" onclick="insertTag('ul')" title="Key Release Highlights">
                                <i class="bi bi-list-ul"></i> Key Highlights
                            </button>
                            <button type="button" class="tool-btn" onclick="insertTag('media_note')" title="Media Contact Note Box">
                                <i class="bi bi-info-circle"></i> Media Note Box
                            </button>
                        </div>

                        {{-- Textarea Body --}}
                        <textarea name="content" 
                                  id="articleContent" 
                                  class="content-textarea @error('content') is-invalid @enderror" 
                                  placeholder="Write the full press release statement here...
Include dates, expansion details, leadership quotes, and customer impact."
                                  required
                                  oninput="updateWordCount(this.value)">{{ old('content', $news->content) }}</textarea>

                        {{-- Live Stats Bar --}}
                        <div class="editor-stats-bar px-3 py-2 bg-light border-top">
                            <span id="wordStatsDisplay"><i class="bi bi-pencil-square me-1"></i> 0 words</span>
                            <span id="readStatsDisplay"><i class="bi bi-clock me-1"></i> ~1 min read</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── Right Column: Sidebar Controls ── --}}
            <div class="editor-right-pane">

                {{-- Card 3: Publishing Controls --}}
                <div class="editor-card">
                    <div class="editor-card-head">
                        <h2 class="editor-card-title">
                            <i class="bi bi-send-check"></i> Release Status &amp; Timing
                        </h2>
                    </div>

                    <div class="editor-card-body">
                        {{-- Live Status --}}
                        <div class="switch-card-item">
                            <div>
                                <h4 class="switch-title"><i class="bi bi-broadcast text-success me-1"></i> Live Published</h4>
                                <p class="switch-sub">Broadcast on /news feed</p>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       role="switch" 
                                       name="is_published" 
                                       value="1" 
                                       id="isPublishedSwitch"
                                       @checked(old('is_published', $news->is_published ?? true))
                                       style="width: 38px; height: 20px; cursor: pointer;">
                            </div>
                        </div>

                        {{-- Featured Spotlight --}}
                        <div class="switch-card-item">
                            <div>
                                <h4 class="switch-title"><i class="bi bi-star-fill text-warning me-1"></i> Top Press Headline</h4>
                                <p class="switch-sub">Pin in top hero banner</p>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       role="switch" 
                                       name="is_featured" 
                                       value="1" 
                                       id="isFeaturedSwitch"
                                       @checked(old('is_featured', $news->is_featured ?? false))
                                       style="width: 38px; height: 20px; cursor: pointer;">
                            </div>
                        </div>

                        {{-- Release Date --}}
                        <div class="form-group-wrap mt-3">
                            <label class="field-label" for="publishedAtInput">
                                <span><i class="bi bi-calendar-event"></i> Official Release Date</span>
                            </label>
                            <input type="datetime-local" 
                                   name="published_at" 
                                   id="publishedAtInput"
                                   value="{{ old('published_at', $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" 
                                   class="form-input-modern font-xs">
                        </div>

                        {{-- Primary Submit Button --}}
                        <div class="pt-3 border-top mt-3">
                            <button type="submit" class="btn-publish-submit">
                                <i class="bi bi-check2-circle fs-5"></i>
                                <span>{{ $isEdit ? 'Save News Changes' : 'Publish Press Release' }}</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Card 4: Press Media Photo --}}
                <div class="editor-card">
                    <div class="editor-card-head">
                        <h2 class="editor-card-title">
                            <i class="bi bi-image"></i> Press Release Photo
                        </h2>
                        <span class="badge bg-light text-dark font-xs border px-2 py-1">16:9 Banner</span>
                    </div>

                    <div class="editor-card-body">
                        {{-- Live Preview Box --}}
                        @if ($news->image_url)
                            <div class="cover-preview-holder" id="previewHolder">
                                <img src="{{ $news->image_url }}" alt="Cover Visual" id="coverImgPreview" class="cover-preview-img">
                            </div>
                        @else
                            <div class="cover-preview-holder d-none" id="previewHolder">
                                <img src="" alt="Cover Visual" id="coverImgPreview" class="cover-preview-img">
                            </div>
                        @endif

                        {{-- Drop Area --}}
                        <div class="cover-drop-zone" onclick="document.getElementById('coverFileInput').click()">
                            <div class="cover-drop-icon">
                                <i class="bi bi-cloud-arrow-up-fill"></i>
                            </div>
                            <strong class="d-block text-indigo font-sm mb-1" style="color:#4338ca;">Click to Upload Press Banner</strong>
                            <p class="text-muted font-xs m-0">Recommended: 1200 x 675px (JPG, PNG, WebP up to 5MB)</p>
                            <input type="file" 
                                   name="image" 
                                   id="coverFileInput" 
                                   accept="image/*" 
                                   class="d-none"
                                   onchange="handleCoverPreview(this)">
                        </div>
                    </div>
                </div>

                {{-- Card 5: PR Desk & Classification --}}
                <div class="editor-card">
                    <div class="editor-card-head">
                        <h2 class="editor-card-title">
                            <i class="bi bi-tags"></i> PR Desk &amp; Category
                        </h2>
                    </div>

                    <div class="editor-card-body">
                        {{-- Category Selector --}}
                        <div class="form-group-wrap">
                            <label class="field-label" for="categoryInput">
                                <span><i class="bi bi-tag-fill text-indigo" style="color:#4338ca;"></i> News Category <span class="text-danger">*</span></span>
                            </label>
                            <input list="categoryDatalist" 
                                   name="category" 
                                   id="categoryInput"
                                   value="{{ old('category', $news->category ?? 'Company News') }}" 
                                   placeholder="Select or enter category..." 
                                   class="form-input-modern font-sm" 
                                   required>
                            <datalist id="categoryDatalist">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}"></option>
                                @endforeach
                            </datalist>

                            {{-- Quick Click Pills --}}
                            <div class="quick-cat-pills">
                                <span class="quick-cat-tag" onclick="selectCat('Company News')">#CompanyNews</span>
                                <span class="quick-cat-tag" onclick="selectCat('Press Release')">#PressRelease</span>
                                <span class="quick-cat-tag" onclick="selectCat('Product Drops & Launches')">#Drops</span>
                                <span class="quick-cat-tag" onclick="selectCat('Logistics & Expansion')">#Logistics</span>
                            </div>
                        </div>

                        {{-- Author Name --}}
                        <div class="form-group-wrap">
                            <label class="field-label" for="authorNameInput">
                                <span><i class="bi bi-person-badge"></i> PR / Communications Desk</span>
                            </label>
                            <input type="text" 
                                   name="author_name" 
                                   id="authorNameInput"
                                   value="{{ old('author_name', $news->author_name ?? 'THE TREND THEORY PR Team') }}" 
                                   placeholder="e.g. THE TREND THEORY PR Team" 
                                   class="form-input-modern font-sm">
                        </div>

                        {{-- Estimated Read Time --}}
                        <div class="form-group-wrap">
                            <label class="field-label" for="readTimeInput">
                                <span><i class="bi bi-clock"></i> Reading Time</span>
                            </label>
                            <input type="text" 
                                   name="read_time" 
                                   id="readTimeInput"
                                   value="{{ old('read_time', $news->read_time ?? '2 min read') }}" 
                                   placeholder="e.g. 2 min read" 
                                   class="form-input-modern font-sm">
                        </div>

                        {{-- Search Keywords / Tags --}}
                        <div class="form-group-wrap">
                            <label class="field-label" for="tagsInput">
                                <span><i class="bi bi-hash"></i> Press Distribution Keywords</span>
                            </label>
                            <input type="text" 
                                   name="tags" 
                                   id="tagsInput"
                                   value="{{ old('tags', $news->tags) }}" 
                                   placeholder="Announcement, Expansion, Logistics (comma separated)" 
                                   class="form-input-modern font-sm">
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>

<script>
    const isEditMode = {{ $isEdit ? 'true' : 'false' }};
    const slugInput = document.getElementById('articleSlug');
    const titleCharCount = document.getElementById('titleCharCount');
    const summaryCharCount = document.getElementById('summaryCharCount');

    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
    }

    function handleTitleInput(val) {
        if (titleCharCount) titleCharCount.textContent = `${val.length} / 255`;
        if (!isEditMode && slugInput && (slugInput.value === '' || slugInput.dataset.autoGenerated === 'true')) {
            slugInput.value = slugify(val);
            slugInput.dataset.autoGenerated = 'true';
        }
    }

    function updateSummaryCount(val) {
        if (summaryCharCount) summaryCharCount.textContent = `${val.length} / 300`;
    }

    function selectCat(name) {
        const input = document.getElementById('categoryInput');
        if (input) input.value = name;
    }

    function handleCoverPreview(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const holder = document.getElementById('previewHolder');
                const img = document.getElementById('coverImgPreview');
                if (img && holder) {
                    img.src = e.target.result;
                    holder.classList.remove('d-none');
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function updateWordCount(text) {
        const words = text.trim() ? text.trim().split(/\s+/).length : 0;
        const readTime = Math.max(1, Math.ceil(words / 200));
        const wordDisp = document.getElementById('wordStatsDisplay');
        const readDisp = document.getElementById('readStatsDisplay');
        const readInput = document.getElementById('readTimeInput');

        if (wordDisp) wordDisp.innerHTML = `<i class="bi bi-pencil-square me-1"></i> ${words} words`;
        if (readDisp) readDisp.innerHTML = `<i class="bi bi-clock me-1"></i> ~${readTime} min read`;
        if (readInput && !readInput.dataset.manual) {
            readInput.value = `${readTime} min read`;
        }
    }

    // Rich Text Tag Inserter
    function insertTag(type) {
        const textarea = document.getElementById('articleContent');
        if (!textarea) return;

        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const selected = textarea.value.substring(start, end);
        let replacement = '';

        if (type === 'h2') {
            replacement = `\n<h2>${selected || 'Section Title'}</h2>\n`;
        } else if (type === 'h3') {
            replacement = `\n<h3>${selected || 'Press Subheading'}</h3>\n`;
        } else if (type === 'bold') {
            replacement = `<strong>${selected || 'Bold Statement'}</strong>`;
        } else if (type === 'quote') {
            replacement = `\n<blockquote>"${selected || 'We are excited to deliver this next step in our consumer journey...'}"</blockquote>\n`;
        } else if (type === 'ul') {
            replacement = `\n<ul>\n  <li>${selected || 'Key Milestone 1'}</li>\n  <li>Key Milestone 2</li>\n  <li>Key Milestone 3</li>\n</ul>\n`;
        } else if (type === 'media_note') {
            replacement = `\n<div class="media-note-box p-3 bg-light rounded-3 my-3 border-start border-4 border-indigo">\n  <strong>📢 Media Inquiries:</strong>\n  <p>${selected || 'For interviews and press kit requests, contact pr@thetrendtheory.com'}</p>\n</div>\n`;
        }

        textarea.setRangeText(replacement, start, end, 'select');
        textarea.focus();
        updateWordCount(textarea.value);
    }

    // Initialize counters on load
    document.addEventListener('DOMContentLoaded', () => {
        const titleEl = document.getElementById('articleTitle');
        const summaryEl = document.getElementById('articleSummary');
        const contentEl = document.getElementById('articleContent');
        if (titleEl) handleTitleInput(titleEl.value);
        if (summaryEl) updateSummaryCount(summaryEl.value);
        if (contentEl) updateWordCount(contentEl.value);
    });
</script>
@endsection
