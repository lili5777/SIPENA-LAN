@extends('admin.partials.layout')

@section('title', ($berita ? 'Edit' : 'Tambah') . ' Berita - SIMPEL')

@section('content')
    

    <!-- Back Button -->
    <div class="mb-4 animate-fade-in">
        <a href="{{ route('berita.index') }}" class="btn btn-outline-secondary btn-modern">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card-modern border-0 shadow-lg animate-fade-in-delay">
                <div class="card-glow"></div>
                <div class="card-header-modern bg-gradient-light py-4 border-0">
                    <h5 class="card-title mb-0 fw-bold d-flex align-items-center">
                        <div class="icon-badge bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                            <i class="fas fa-newspaper fa-lg" style="color: #1a3a6c;"></i>
                        </div>
                        <span>Formulir Berita</span>
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ $berita ? route('berita.update', $berita->id) : route('berita.store') }}" method="POST"
                        id="beritaForm" enctype="multipart/form-data" novalidate>
                        @csrf
                        @if($berita)
                            @method('PUT')
                        @endif

                        <div class="row g-4">
                            <!-- Left Column: Main Content -->
                            <div class="col-lg-8">
                                <!-- Judul Berita -->
                                <div class="form-group-modern mb-4">
                                    <label for="judul" class="form-label-modern">
                                        <i class="fas fa-heading me-2"></i>
                                        Judul Berita <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" class="form-control-modern @error('judul') is-invalid @enderror"
                                            id="judul" name="judul" placeholder="Masukkan judul berita yang menarik"
                                            value="{{ old('judul', $berita->judul ?? '') }}" required maxlength="200">
                                        @error('judul')
                                            <div class="invalid-feedback-modern">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <div class="input-glow"></div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Maksimal 200 karakter
                                    </small>
                                </div>

                                <!-- Isi Berita dengan CKEditor -->
                                <div class="form-group-modern mb-4">
                                    <label for="isi" class="form-label-modern">
                                        <i class="fas fa-file-alt me-2"></i>
                                        Isi Berita <span class="text-danger">*</span>
                                    </label>
                                    <div class="editor-wrapper">
                                        <textarea class="form-control @error('isi') is-invalid @enderror" id="isi"
                                            name="isi" rows="10">{{ old('isi', $berita->isi ?? '') }}</textarea>
                                        @error('isi')
                                            <div class="invalid-feedback-modern d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Gunakan toolbar editor untuk memformat teks
                                    </small>
                                </div>
                            </div>

                            <!-- Right Column: Thumbnail Upload -->
                            <div class="col-lg-4">
                                <div class="thumbnail-upload-section">
                                    <label class="form-label-modern mb-3">
                                        <i class="fas fa-image me-2"></i>
                                        Foto Thumbnail
                                    </label>

                                    <div class="thumbnail-upload-container">
                                        <div class="thumbnail-preview" id="thumbnailPreview">
                                            @if($berita && $berita->foto)
                                                <img src="{{ asset('gambar/' . $berita->foto) }}" alt="Thumbnail"
                                                    id="previewImage">
                                                <div class="thumbnail-overlay-upload">
                                                    <i class="fas fa-camera fa-2x"></i>
                                                </div>
                                            @else
                                                <div class="thumbnail-placeholder-upload" id="thumbnailPlaceholder">
                                                    <i class="fas fa-image fa-4x"></i>
                                                    <p class="mt-3 mb-0">Upload Thumbnail</p>
                                                </div>
                                            @endif
                                        </div>

                                        <input type="file" class="d-none" id="foto" name="foto" accept="image/*">

                                        <button type="button" class="btn btn-primary btn-modern w-100 mt-3" id="uploadBtn">
                                            <i class="fas fa-upload me-2"></i>
                                            {{ $berita && $berita->foto ? 'Ganti Foto' : 'Upload Foto' }}
                                        </button>

                                        @if($berita && $berita->foto)
                                            <button type="button" class="btn btn-outline-danger btn-modern w-100 mt-2"
                                                id="removeThumbnailBtn">
                                                <i class="fas fa-trash-alt me-2"></i> Hapus Foto
                                            </button>
                                        @endif

                                        <div class="thumbnail-info mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Format: JPG, PNG, GIF (Max 2MB)
                                            </small>
                                        </div>

                                        @error('foto')
                                            <div class="invalid-feedback-modern d-block mt-2">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Tips Box -->
                                    <div class="tips-box mt-4">
                                        <h6 class="tips-title">
                                            <i class="fas fa-lightbulb me-2"></i>
                                            Tips Menulis Berita
                                        </h6>
                                        <ul class="tips-list">
                                            <li>Gunakan judul yang menarik dan jelas</li>
                                            <li>Sertakan foto berkualitas tinggi</li>
                                            <li>Format teks agar mudah dibaca</li>
                                            <li>Gunakan paragraf yang tidak terlalu panjang</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <div class="d-flex gap-3 justify-content-end">
                                <a href="{{ route('berita.index') }}" class="btn btn-outline-secondary btn-modern px-4">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary btn-modern px-5" id="submitBtn">
                                    <i class="fas fa-save me-2"></i>
                                    {{ $berita ? 'Perbarui' : 'Simpan' }} Berita
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Modern Color Variables */
        :root {
            --primary-color: #1a3a6c;
            --primary-dark: #142a52;
            --primary-light: #2c5aa0;
            --gold-color: #d4af37;
            --border-radius: 16px;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.16);
        }

        /* Animations */
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.6s ease-out;
        }

        .animate-slide-in-delay {
            animation: slideIn 0.6s ease-out 0.2s backwards;
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        .animate-fade-in-delay {
            animation: fadeIn 0.6s ease-out 0.3s backwards;
        }

        /* Modern Card */
        .card-modern {
            border-radius: var(--border-radius);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            background: white !important;
        }

        .card-glow {
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(26, 58, 108, 0.05) 0%, transparent 70%);
            opacity: 1;
        }

        .card-header-modern {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        /* Form Styles */
        .form-group-modern {
            margin-bottom: 1.5rem;
        }

        .form-label-modern {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
            display: block;
        }

        .input-wrapper {
            position: relative;
            transition: all 0.3s ease;
        }

        .input-glow {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
            transition: width 0.3s ease;
        }

        .input-wrapper.focused .input-glow {
            width: 100%;
        }

        .form-control-modern {
            width: 100%;
            padding: 0.875rem 1.125rem;
            font-size: 0.95rem;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: white !important;
            color: #2c3e50;
        }

        .form-control-modern:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 4px rgba(26, 58, 108, 0.1);
            background: white !important;
        }

        .form-control-modern.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback-modern {
            display: block;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        /* CKEditor */
        .editor-wrapper {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .editor-wrapper.focused {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(26, 58, 108, 0.1);
        }

        .ck-editor__editable {
            min-height: 400px;
            border: none !important;
            padding: 1rem !important;
            font-family: 'Inter', sans-serif;
        }

        .ck.ck-toolbar {
            background: #f8f9fa !important;
            border: 1px solid #e9ecef !important;
            border-bottom: none !important;
            border-radius: 10px 10px 0 0 !important;
        }

        .ck.ck-editor__main>.ck-editor__editable {
            background: white !important;
            border: 1px solid #e9ecef !important;
            border-radius: 0 0 10px 10px !important;
        }

        /* Thumbnail Upload */
        .thumbnail-upload-container {
            background: white;
            border-radius: 16px;
            border: 2px solid #e9ecef;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .thumbnail-preview {
            width: 100%;
            height: 250px;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            position: relative;
            cursor: pointer;
        }

        .thumbnail-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumbnail-placeholder-upload {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
            font-weight: 600;
        }

        .thumbnail-overlay-upload {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .thumbnail-preview:hover .thumbnail-overlay-upload {
            opacity: 1;
        }

        .thumbnail-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 0.75rem;
            text-align: center;
        }

        /* Tips Box */
        .tips-box {
            background: linear-gradient(135deg, #fff9e6 0%, #fffbf0 100%);
            border-left: 4px solid #ffc107;
            border-radius: 12px;
            padding: 1.25rem;
        }

        .tips-title {
            font-weight: 700;
            color: #856404;
            font-size: 0.95rem;
            margin-bottom: 0.75rem;
        }

        .tips-list {
            margin: 0;
            padding-left: 1.25rem;
            color: #856404;
            font-size: 0.85rem;
        }

        .tips-list li {
            margin-bottom: 0.5rem;
        }

        /* Form Actions */
        .form-actions {
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 2px solid #e9ecef;
        }

        /* Modern Buttons */
        .btn-modern {
            border-radius: 10px;
            padding: 0.625rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: none;
        }

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-modern:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-primary.btn-modern {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            border: none;
            color: white;
        }

        .btn-outline-secondary.btn-modern {
            border: 2px solid #6c757d;
            color: #6c757d;
        }

        .btn-outline-secondary.btn-modern:hover {
            background: #6c757d;
            color: white;
        }

        .btn-outline-danger.btn-modern {
            border: 2px solid #dc3545;
            color: #dc3545;
        }

        .btn-outline-danger.btn-modern:hover {
            background: #dc3545;
            color: white;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .thumbnail-upload-section {
                margin-top: 2rem;
            }
        }

        @media (max-width: 768px) {
            .form-actions .d-flex {
                flex-direction: column;
            }

            .form-actions .btn-modern {
                width: 100%;
            }

            .thumbnail-preview {
                height: 200px;
            }

            .ck-editor__editable {
                min-height: 300px;
            }
        }

        @media (max-width: 576px) {
            .btn-modern {
                font-size: 0.8rem;
                padding: 0.5rem 1.25rem;
            }
        }
    </style>
@endsection

@section('scripts')
    <!-- CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let editorInstance;
            const form = document.getElementById('beritaForm');
            const submitBtn = document.getElementById('submitBtn');
            const fotoInput = document.getElementById('foto');
            const uploadBtn = document.getElementById('uploadBtn');
            const thumbnailPreview = document.getElementById('thumbnailPreview');
            const removeThumbnailBtn = document.getElementById('removeThumbnailBtn');

            // CSRF Token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Initialize CKEditor
            ClassicEditor
                .create(document.querySelector('#isi'), {
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'underline', 'strikethrough', '|',
                            'link', 'bulletedList', 'numberedList', '|',
                            'alignment', '|',
                            'imageUpload', 'blockQuote', 'insertTable', '|',
                            'undo', 'redo'
                        ]
                    },
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                        ]
                    },
                    image: {
                        toolbar: [
                            'imageTextAlternative',
                            'imageStyle:inline',
                            'imageStyle:block',
                            'imageStyle:side'
                        ]
                    },
                    simpleUpload: {
                        uploadUrl: '{{ route("berita.uploadImage") }}',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    },
                    language: 'id'
                })
                .then(editor => {
                    editorInstance = editor;
                    console.log('CKEditor initialized successfully');

                    // Style editor
                    editor.ui.view.editable.element.style.minHeight = '400px';

                    // Focus effect
                    editor.ui.view.editable.element.addEventListener('focus', function() {
                        this.closest('.editor-wrapper').classList.add('focused');
                    });

                    editor.ui.view.editable.element.addEventListener('blur', function() {
                        this.closest('.editor-wrapper').classList.remove('focused');
                    });

                    // Custom upload adapter fallback
                    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                        return new MyUploadAdapter(loader);
                    };
                })
                .catch(error => {
                    console.error('CKEditor initialization error:', error);
                });

            // Custom Upload Adapter Class
            class MyUploadAdapter {
                constructor(loader) {
                    this.loader = loader;
                }

                upload() {
                    return this.loader.file
                        .then(file => new Promise((resolve, reject) => {
                            this._initRequest();
                            this._initListeners(resolve, reject, file);
                            this._sendRequest(file);
                        }));
                }

                abort() {
                    if (this.xhr) {
                        this.xhr.abort();
                    }
                }

                _initRequest() {
                    const xhr = this.xhr = new XMLHttpRequest();
                    xhr.open('POST', '{{ route("berita.uploadImage") }}', true);
                    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                    xhr.responseType = 'json';
                }

                _initListeners(resolve, reject, file) {
                    const xhr = this.xhr;
                    const loader = this.loader;
                    const genericErrorText = `Couldn't upload file: ${file.name}.`;

                    xhr.addEventListener('error', () => reject(genericErrorText));
                    xhr.addEventListener('abort', () => reject());
                    xhr.addEventListener('load', () => {
                        const response = xhr.response;

                        if (!response || response.error) {
                            return reject(response && response.error ? response.error.message : genericErrorText);
                        }

                        resolve({
                            default: response.url
                        });
                    });

                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', evt => {
                            if (evt.lengthComputable) {
                                loader.uploadTotal = evt.total;
                                loader.uploaded = evt.loaded;
                            }
                        });
                    }
                }

                _sendRequest(file) {
                    const data = new FormData();
                    data.append('upload', file);
                    this.xhr.send(data);
                }
            }

            // Thumbnail Upload Handler
            uploadBtn.addEventListener('click', function() {
                fotoInput.click();
            });

            fotoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];

                if (file) {
                    // Validate file size (2MB)
                    if (file.size > 2048 * 1024) {
                        alert('Ukuran file terlalu besar! Maksimal 2MB');
                        fotoInput.value = '';
                        return;
                    }

                    // Validate file type
                    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    if (!validTypes.includes(file.type)) {
                        alert('Format file tidak didukung! Gunakan JPG, PNG, GIF, atau WebP');
                        fotoInput.value = '';
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = function(e) {
                        thumbnailPreview.innerHTML = `
                            <img src="${e.target.result}" alt="Preview" id="previewImage">
                            <div class="thumbnail-overlay-upload">
                                <i class="fas fa-camera fa-2x"></i>
                            </div>
                        `;
                        uploadBtn.innerHTML = '<i class="fas fa-sync-alt me-2"></i> Ganti Foto';

                        // Add remove button if not exists
                        if (!removeThumbnailBtn) {
                            const removeBtn = document.createElement('button');
                            removeBtn.type = 'button';
                            removeBtn.className = 'btn btn-outline-danger btn-modern w-100 mt-2';
                            removeBtn.id = 'removeThumbnailBtn';
                            removeBtn.innerHTML = '<i class="fas fa-trash-alt me-2"></i> Hapus Foto';
                            uploadBtn.parentNode.insertBefore(removeBtn, uploadBtn.nextElementSibling);

                            removeBtn.addEventListener('click', handleRemoveThumbnail);
                        }
                    };

                    reader.readAsDataURL(file);
                }
            });

            // Remove thumbnail handler
            function handleRemoveThumbnail() {
                if (confirm('Apakah Anda yakin ingin menghapus foto?')) {
                    thumbnailPreview.innerHTML = `
                        <div class="thumbnail-placeholder-upload" id="thumbnailPlaceholder">
                            <i class="fas fa-image fa-4x"></i>
                            <p class="mt-3 mb-0">Upload Thumbnail</p>
                        </div>
                    `;
                    fotoInput.value = '';
                    uploadBtn.innerHTML = '<i class="fas fa-upload me-2"></i> Upload Foto';

                    const existingRemoveBtn = document.getElementById('removeThumbnailBtn');
                    if (existingRemoveBtn) {
                        existingRemoveBtn.remove();
                    }
                }
            }

            // Bind existing remove button
            if (removeThumbnailBtn) {
                removeThumbnailBtn.addEventListener('click', handleRemoveThumbnail);
            }

            // Form validation
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                let isValid = true;
                let errorMessage = '';

                // Validate title
                const judul = document.getElementById('judul').value.trim();
                if (!judul) {
                    isValid = false;
                    errorMessage = 'Judul berita harus diisi!';
                    document.getElementById('judul').focus();
                }

                // Validate editor content
                if (editorInstance) {
                    const editorData = editorInstance.getData().trim();
                    if (!editorData) {
                        isValid = false;
                        errorMessage = errorMessage || 'Isi berita harus diisi!';
                    }
                }

                if (!isValid) {
                    alert(errorMessage);
                    return false;
                }

                // If valid, submit form
                form.submit();
            });

            // Character counter for title
            const judulInput = document.getElementById('judul');
            const maxLength = 200;

            judulInput.addEventListener('input', function() {
                const currentLength = this.value.length;
                const remaining = maxLength - currentLength;

                if (remaining < 50) {
                    this.style.borderColor = remaining < 0 ? '#dc3545' : '#ffc107';
                } else {
                    this.style.borderColor = '';
                }
            });

            // Focus effects for inputs
            const inputs = document.querySelectorAll('.form-control-modern');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.closest('.input-wrapper').classList.add('focused');
                });

                input.addEventListener('blur', function() {
                    this.closest('.input-wrapper').classList.remove('focused');
                    if (this.value) {
                        this.closest('.input-wrapper').classList.add('filled');
                    } else {
                        this.closest('.input-wrapper').classList.remove('filled');
                    }
                });

                if (input.value) {
                    input.closest('.input-wrapper').classList.add('filled');
                }
            });

            // Make thumbnail preview clickable
            thumbnailPreview.addEventListener('click', function() {
                fotoInput.click();
            });
        });
    </script>
@endsection