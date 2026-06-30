{{-- resources/views/admin/components/image-uploader.blade.php --}}
@php
    $uploaderId = 'uploader_' . ($modelType ?? 'model') . '_' . ($modelId ?? 'new') . '_' . ($collection ?? 'default');
    $existing = $existing ?? collect();
@endphp

<div id="{{ $uploaderId }}" class="img-uploader-wrap">
    <style>
        .img-uploader-wrap {
            margin-bottom: 24px;
        }

        .iuw-label {
            font-size: 11px;
            font-weight: 700;
            color: #7a8fa6;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 10px;
            display: block;
        }

        .iuw-drop {
            border: 2px dashed #d9dee6;
            border-radius: 14px;
            padding: 32px 20px;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
            background: #fafbff;
            position: relative;
        }

        .iuw-drop:hover,
        .iuw-drop.drag-over {
            border-color: #00285a;
            background: #f0f4ff;
        }

        .iuw-drop input[type=file] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        .iuw-drop-icon {
            font-size: 32px;
            color: #b0bec5;
            margin-bottom: 8px;
        }

        .iuw-drop-text {
            font-size: 13px;
            color: #7a8fa6;
        }

        .iuw-drop-text strong {
            color: #00285a;
        }

        .iuw-progress {
            height: 3px;
            background: #e8edf5;
            border-radius: 3px;
            margin-top: 8px;
            overflow: hidden;
            display: none;
        }

        .iuw-progress-bar {
            height: 100%;
            background: #00285a;
            border-radius: 3px;
            width: 0;
            transition: width .3s;
        }

        .iuw-msg {
            font-size: 12px;
            margin-top: 6px;
            padding: 6px 10px;
            border-radius: 6px;
            display: none;
        }

        .iuw-msg.success {
            background: #e8f5e9;
            color: #2e7d32;
            display: block;
        }

        .iuw-msg.error {
            background: #fce4ec;
            color: #c62828;
            display: block;
        }

        .iuw-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            margin-top: 12px;
        }

        .iuw-img-card {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #eef2f6;
            background: #f8fafc;
            aspect-ratio: 4/5;
        }

        .iuw-img-card.is-primary {
            border-color: #ffd700;
        }

        .iuw-img-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .iuw-img-badge {
            position: absolute;
            top: 6px;
            left: 6px;
            background: #ffd700;
            color: #00285a;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
        }

        .iuw-img-actions {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, .6);
            display: flex;
            gap: 4px;
            padding: 5px;
            opacity: 0;
            transition: opacity .15s;
        }

        .iuw-img-card:hover .iuw-img-actions {
            opacity: 1;
        }

        .iuw-action-btn {
            flex: 1;
            padding: 4px;
            border: none;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: .15s;
        }

        .iuw-btn-primary {
            background: #ffd700;
            color: #00285a;
        }

        .iuw-btn-delete {
            background: #ff3f6c;
            color: white;
        }
    </style>

    <span class="iuw-label">{{ $label ?? 'Images' }}</span>

    <div class="iuw-drop" id="{{ $uploaderId }}_drop">
        <input type="file" id="{{ $uploaderId }}_input" accept="image/jpeg,image/jpg,image/png,image/webp"
            {{ isset($multiple) && $multiple ? 'multiple' : '' }}>
        <div class="iuw-drop-icon"><i class="bi bi-cloud-upload"></i></div>
        <div class="iuw-drop-text"><strong>Click to upload</strong> or drag & drop here</div>
        <div class="iuw-drop-hint">JPG, PNG, WEBP — max 5MB each</div>
    </div>

    <div class="iuw-progress" id="{{ $uploaderId }}_progress">
        <div class="iuw-progress-bar" id="{{ $uploaderId }}_bar"></div>
    </div>
    <div class="iuw-msg" id="{{ $uploaderId }}_msg"></div>

    <div class="iuw-grid" id="{{ $uploaderId }}_grid">
        @foreach ($existing->sortByDesc('is_primary')->sortBy('sort_order') as $media)
            <div class="iuw-img-card {{ $media->is_primary ? 'is-primary' : '' }}" id="media_{{ $media->id }}">
                <img src="{{ $media->getImageUrl(200, 250) }}" alt="{{ $media->alt_text }}">
                @if ($media->is_primary)
                    <div class="iuw-img-badge">MAIN</div>
                @endif
                <div class="iuw-img-actions">
                    @if (!$media->is_primary)
                        <button class="iuw-action-btn iuw-btn-primary"
                            onclick="setMainImage({{ $media->id }}, '{{ $uploaderId }}')">Main</button>
                    @endif
                    <button class="iuw-action-btn iuw-btn-delete"
                        onclick="deleteImage({{ $media->id }}, '{{ $uploaderId }}')"><i
                            class="bi bi-trash3"></i></button>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        (function() {
            var uploaderId = '{{ $uploaderId }}';
            var modelType = '{{ $modelType }}';
            var modelId = {{ $modelId ?? 'null' }};
            var collection = '{{ $collection ?? 'default' }}';
            var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            var dropZone = document.getElementById(uploaderId + '_drop');
            var fileInput = document.getElementById(uploaderId + '_input');
            var grid = document.getElementById(uploaderId + '_grid');
            var progressWrap = document.getElementById(uploaderId + '_progress');
            var progressBar = document.getElementById(uploaderId + '_bar');
            var msgEl = document.getElementById(uploaderId + '_msg');

            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropZone.classList.add('drag-over');
            });
            dropZone.addEventListener('dragleave', function() {
                dropZone.classList.remove('drag-over');
            });
            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                dropZone.classList.remove('drag-over');
                var files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
                if (files.length) uploadFiles(files);
            });
            fileInput.addEventListener('change', function() {
                if (this.files.length) uploadFiles(Array.from(this.files));
                this.value = '';
            });

            function uploadFiles(files) {
                var total = files.length,
                    done = 0;
                showMsg('');
                progressWrap.style.display = 'block';
                progressBar.style.width = '0%';
                files.forEach((file, idx) => {
                    if (file.size > 5 * 1024 * 1024) {
                        showMsg(file.name + ' too large (max 5MB)', 'error');
                        done++;
                        return;
                    }
                    var formData = new FormData();
                    formData.append('file', file);
                    formData.append('model_type', modelType);
                    formData.append('model_id', modelId);
                    formData.append('collection', collection);
                    formData.append('alt_text', file.name.replace(/\.[^/.]+$/, ''));
                    formData.append('is_primary', grid.children.length === 0 && idx === 0 ? '1' : '0');
                    formData.append('_token', csrfToken);
                    var placeholderId = 'placeholder_' + Date.now() + '_' + idx;
                    var placeholder = document.createElement('div');
                    placeholder.className = 'iuw-img-card';
                    placeholder.id = placeholderId;
                    placeholder.innerHTML =
                        '<div class="iuw-uploading-overlay" style="position:absolute;inset:0;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;"><i class="bi bi-arrow-repeat" style="animation:spin 1s linear infinite"></i></div>';
                    grid.appendChild(placeholder);
                    fetch('{{ route('admin.media.upload') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(r => r.json())
                        .then(data => {
                            done++;
                            progressBar.style.width = Math.round((done / total) * 100) + '%';
                            var ph = document.getElementById(placeholderId);
                            if (ph) ph.remove();
                            if (data.success) {
                                addImageCard(data.media);
                                showMsg('Image uploaded!', 'success');
                            } else {
                                showMsg('Upload failed: ' + (data.message || 'Unknown error'), 'error');
                            }
                            if (done === total) setTimeout(() => {
                                progressWrap.style.display = 'none';
                            }, 1500);
                        })
                        .catch(err => {
                            done++;
                            var ph = document.getElementById(placeholderId);
                            if (ph) ph.remove();
                            showMsg('Upload error', 'error');
                        });
                });
            }

            function addImageCard(media) {
                var card = document.createElement('div');
                card.className = 'iuw-img-card' + (media.is_primary ? ' is-primary' : '');
                card.id = 'media_' + media.id;
                card.innerHTML = '<img src="' + media.thumb_url + '">' + (media.is_primary ?
                        '<div class="iuw-img-badge">MAIN</div>' : '') + '<div class="iuw-img-actions">' + (!media
                        .is_primary ? '<button class="iuw-action-btn iuw-btn-primary" onclick="setMainImage(' + media
                        .id + ', \'' + uploaderId + '\')">Main</button>' : '') +
                    '<button class="iuw-action-btn iuw-btn-delete" onclick="deleteImage(' + media.id + ', \'' +
                    uploaderId + '\')"><i class="bi bi-trash3"></i></button></div>';
                grid.appendChild(card);
            }

            function showMsg(text, type) {
                if (!text) {
                    msgEl.className = 'iuw-msg';
                    return;
                }
                msgEl.textContent = text;
                msgEl.className = 'iuw-msg ' + type;
                setTimeout(() => msgEl.className = 'iuw-msg', 3000);
            }
        })();

        function deleteImage(mediaId, uploaderId) {
            if (!confirm('Delete this image?')) return;
            var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            fetch('/admin/media/' + mediaId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json()).then(data => {
                    if (data.success) document.getElementById('media_' + mediaId)?.remove();
                    else alert('Delete failed');
                });
        }

        function setMainImage(mediaId, uploaderId) {
            var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            fetch('/admin/media/' + mediaId + '/primary', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json()).then(data => {
                    if (data.success) {
                        document.querySelectorAll('.iuw-img-card').forEach(c => {
                            c.classList.remove('is-primary');
                            c.querySelector('.iuw-img-badge')?.remove();
                        });
                        var card = document.getElementById('media_' + mediaId);
                        if (card) {
                            card.classList.add('is-primary');
                            card.insertAdjacentHTML('afterbegin', '<div class="iuw-img-badge">MAIN</div>');
                        }
                    }
                });
        }
    </script>
</div>
