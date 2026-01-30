@extends('admin.partials.layout')

@section('title', ($pejabat ? 'Edit' : 'Tambah') . ' Pejabat - SIMPEL')

@section('content')
    <!-- Page Header with Animation -->
    <div class="page-header-modern bg-gradient-primary rounded-4 mb-4 overflow-hidden position-relative">
        <div class="header-pattern"></div>
        <div class="row align-items-center position-relative" style="z-index: 2;">
            <div class="col">
                <div class="d-flex align-items-center p-4">
                    <div class="icon-wrapper-modern bg-white rounded-circle p-3 me-4 shadow-lg animate-float">
                        <i class="fas fa-{{ $pejabat ? 'edit' : 'plus-circle' }} fa-2x" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-2 fw-bold animate-slide-in">
                            {{ $pejabat ? 'Edit' : 'Tambah' }} Pejabat
                        </h1>
                        <p class="text-white-50 mb-0 animate-slide-in-delay">
                            {{ $pejabat ? 'Perbarui' : 'Tambahkan' }} data pejabat perusahaan
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mb-4 animate-fade-in">
        <a href="{{ route('pejabat.index') }}" class="btn btn-outline-secondary btn-modern">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card-modern border-0 shadow-lg animate-fade-in-delay">
                <div class="card-glow"></div>
                <div class="card-header-modern bg-gradient-light py-4 border-0">
                    <h5 class="card-title mb-0 fw-bold d-flex align-items-center">
                        <div class="icon-badge bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                            <i class="fas fa-user-tie fa-lg" style="color: #285496;"></i>
                        </div>
                        <span>Formulir Data Pejabat</span>
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ $pejabat ? route('pejabat.update', $pejabat) : route('pejabat.store') }}" method="POST"
                        id="pejabatForm" enctype="multipart/form-data">
                        @csrf
                        @if($pejabat)
                            @method('PUT')
                        @endif

                        <div class="row g-4">
                            <!-- Left Column: Photo Upload -->
                            <div class="col-lg-4">
                                <div class="photo-upload-section">
                                    <label class="form-label-modern mb-3">
                                        <i class="fas fa-camera me-2"></i>
                                        Foto Pejabat
                                    </label>

                                    <div class="photo-upload-container">
                                        <div class="photo-preview" id="photoPreview">
                                            @if($pejabat && $pejabat->foto_pejabat)
                                                <img src="{{ asset('gambar/' . $pejabat->foto_pejabat) }}" alt="Foto Pejabat"
                                                    id="previewImage">
                                                <div class="photo-overlay">
                                                    <i class="fas fa-camera fa-2x"></i>
                                                </div>
                                            @else
                                                <div class="photo-placeholder" id="photoPlaceholder">
                                                    <i class="fas fa-user fa-4x"></i>
                                                    <p class="mt-3 mb-0">Upload Foto</p>
                                                </div>
                                            @endif
                                        </div>

                                        <input type="file" class="d-none" id="foto_pejabat" name="foto_pejabat"
                                            accept="image/*">

                                        <button type="button" class="btn btn-primary btn-modern w-100 mt-3" id="uploadBtn">
                                            <i class="fas fa-upload me-2"></i>
                                            {{ $pejabat && $pejabat->foto_pejabat ? 'Ganti Foto' : 'Upload Foto' }}
                                        </button>

                                        @if($pejabat && $pejabat->foto_pejabat)
                                            <button type="button" class="btn btn-outline-danger btn-modern w-100 mt-2"
                                                id="removePhotoBtn">
                                                <i class="fas fa-trash-alt me-2"></i> Hapus Foto
                                            </button>
                                        @endif

                                        <div class="photo-info mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Format: JPG, PNG, GIF (Max 2MB)
                                            </small>
                                        </div>

                                        @error('foto_pejabat')
                                            <div class="invalid-feedback-modern d-block mt-2">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Form Fields -->
                            <div class="col-lg-8">
                                <!-- Personal Information Section -->
                                <div class="form-section mb-4">
                                    <div class="section-header mb-4">
                                        <h6 class="section-title">
                                            <i class="fas fa-id-card me-2"></i>
                                            Informasi Pejabat
                                        </h6>
                                        <div class="section-divider"></div>
                                    </div>

                                    <div class="row g-4">
                                        <!-- Nama Pejabat -->
                                        <div class="col-12">
                                            <div class="form-group-modern">
                                                <label for="nama_pejabat" class="form-label-modern">
                                                    <i class="fas fa-user me-2"></i>
                                                    Nama Lengkap <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-wrapper">
                                                    <input type="text"
                                                        class="form-control-modern @error('nama_pejabat') is-invalid @enderror"
                                                        id="nama_pejabat" name="nama_pejabat"
                                                        placeholder="Masukkan nama lengkap pejabat"
                                                        value="{{ old('nama_pejabat', $pejabat->nama_pejabat ?? '') }}"
                                                        required>
                                                    @error('nama_pejabat')
                                                        <div class="invalid-feedback-modern">
                                                            <i class="fas fa-exclamation-circle me-1"></i>
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                    <div class="input-glow"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Jabatan -->
                                        <div class="col-md-6">
                                            <div class="form-group-modern">
                                                <label for="jabatan_pejabat" class="form-label-modern">
                                                    <i class="fas fa-briefcase me-2"></i>
                                                    Jabatan <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-wrapper">
                                                    <input type="text"
                                                        class="form-control-modern @error('jabatan_pejabat') is-invalid @enderror"
                                                        id="jabatan_pejabat" name="jabatan_pejabat"
                                                        placeholder="Contoh: Direktur Utama"
                                                        value="{{ old('jabatan_pejabat', $pejabat->jabatan_pejabat ?? '') }}"
                                                        required>
                                                    @error('jabatan_pejabat')
                                                        <div class="invalid-feedback-modern">
                                                            <i class="fas fa-exclamation-circle me-1"></i>
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                    <div class="input-glow"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- NIP -->
                                        <div class="col-md-6">
                                            <div class="form-group-modern">
                                                <label for="nip_pejabat" class="form-label-modern">
                                                    <i class="fas fa-id-card-alt me-2"></i>
                                                    NIP (Opsional)
                                                </label>
                                                <div class="input-wrapper">
                                                    <input type="text"
                                                        class="form-control-modern @error('nip_pejabat') is-invalid @enderror"
                                                        id="nip_pejabat" name="nip_pejabat"
                                                        placeholder="Contoh: 198501012010011001"
                                                        value="{{ old('nip_pejabat', $pejabat->nip_pejabat ?? '') }}">
                                                    @error('nip_pejabat')
                                                        <div class="invalid-feedback-modern">
                                                            <i class="fas fa-exclamation-circle me-1"></i>
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                    <div class="input-glow"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Posisi -->
                                        <div class="col-12">
                                            <div class="form-group-modern">
                                                <label for="posisi" class="form-label-modern">
                                                    <i class="fas fa-sort-numeric-up me-2"></i>
                                                    Urutan Posisi <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-wrapper">
                                                    <input type="number"
                                                        class="form-control-modern @error('posisi') is-invalid @enderror"
                                                        id="posisi" name="posisi" placeholder="Masukkan nomor urutan"
                                                        value="{{ old('posisi', $pejabat->posisi ?? $nextPosisi) }}" min="1"
                                                        required>
                                                    @error('posisi')
                                                        <div class="invalid-feedback-modern">
                                                            <i class="fas fa-exclamation-circle me-1"></i>
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                    <div class="input-glow"></div>
                                                </div>
                                                <small class="text-muted mt-2 d-block">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Nomor urutan menentukan posisi tampilan (semakin kecil, semakin atas)
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <div class="d-flex gap-3 justify-content-end">
                                <a href="{{ route('pejabat.index') }}" class="btn btn-outline-secondary btn-modern px-4">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary btn-modern px-5">
                                    <i class="fas fa-save me-2"></i>
                                    {{ $pejabat ? 'Perbarui' : 'Simpan' }} Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('pejabatForm');
            const inputs = form.querySelectorAll('.form-control-modern');
            const photoInput = document.getElementById('foto_pejabat');
            const uploadBtn = document.getElementById('uploadBtn');
            const removePhotoBtn = document.getElementById('removePhotoBtn');
            const photoPreview = document.getElementById('photoPreview');
            let removedPhoto = false;

            // Add focus/blur effects
            inputs.forEach(input => {
                input.addEventListener('focus', function () {
                    this.closest('.input-wrapper').classList.add('focused');
                });

                input.addEventListener('blur', function () {
                    this.closest('.input-wrapper').classList.remove('focused');
                    if (this.value) {
                        this.closest('.input-wrapper').classList.add('filled');
                    } else {
                        this.closest('.input-wrapper').classList.remove('filled');
                    }
                });

                // Check if already filled on load
                if (input.value) {
                    input.closest('.input-wrapper').classList.add('filled');
                }
            });

            // Upload button click
            uploadBtn.addEventListener('click', function () {
                photoInput.click();
            });

            // Photo preview
            photoInput.addEventListener('change', function (e) {
                const file = e.target.files[0];

                if (file) {
                    // Validate file size (2MB)
                    if (file.size > 2048000) {
                        alert('Ukuran file terlalu besar! Maksimal 2MB');
                        photoInput.value = '';
                        return;
                    }

                    // Validate file type
                    if (!file.type.match('image.*')) {
                        alert('File harus berupa gambar!');
                        photoInput.value = '';
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = function (e) {
                        photoPreview.innerHTML = `
                                <img src="${e.target.result}" alt="Preview" id="previewImage">
                                <div class="photo-overlay">
                                    <i class="fas fa-camera fa-2x"></i>
                                </div>
                            `;
                        uploadBtn.innerHTML = '<i class="fas fa-sync-alt me-2"></i> Ganti Foto';

                        if (!removePhotoBtn) {
                            const removeBtn = document.createElement('button');
                            removeBtn.type = 'button';
                            removeBtn.className = 'btn btn-outline-danger btn-modern w-100 mt-2';
                            removeBtn.id = 'removePhotoBtn';
                            removeBtn.innerHTML = '<i class="fas fa-trash-alt me-2"></i> Hapus Foto';
                            uploadBtn.parentNode.insertBefore(removeBtn, uploadBtn.nextSibling);

                            removeBtn.addEventListener('click', handleRemovePhoto);
                        }

                        removedPhoto = false;
                    };

                    reader.readAsDataURL(file);
                }
            });

            // Remove photo handler
            function handleRemovePhoto() {
                if (confirm('Apakah Anda yakin ingin menghapus foto?')) {
                    photoPreview.innerHTML = `
                            <div class="photo-placeholder" id="photoPlaceholder">
                                <i class="fas fa-user fa-4x"></i>
                                <p class="mt-3 mb-0">Upload Foto</p>
                            </div>
                        `;
                    photoInput.value = '';
                    uploadBtn.innerHTML = '<i class="fas fa-upload me-2"></i> Upload Foto';

                    if (removePhotoBtn) {
                        removePhotoBtn.remove();
                    }

                    removedPhoto = true;
                }
            }

            if (removePhotoBtn) {
                removePhotoBtn.addEventListener('click', handleRemovePhoto);
            }

            // Form validation
            form.addEventListener('submit', function (e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Add shake animation to invalid inputs
                    const invalidInputs = form.querySelectorAll(':invalid');
                    invalidInputs.forEach(input => {
                        const wrapper = input.closest('.input-wrapper');
                        if (wrapper) {
                            wrapper.classList.add('shake');
                            setTimeout(() => {
                                wrapper.classList.remove('shake');
                            }, 500);
                        }
                    });

                    // Scroll to first invalid input
                    const firstInvalid = form.querySelector(':invalid');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstInvalid.focus();
                    }
                }
                form.classList.add('was-validated');
            });

            // Auto-format NIP (only numbers)
            const nipInput = document.getElementById('nip_pejabat');
            nipInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/[^\d]/g, '');
                e.target.value = value;
            });

            // Prevent negative numbers in posisi
            const posisiInput = document.getElementById('posisi');
            posisiInput.addEventListener('input', function (e) {
                if (parseInt(e.target.value) < 1) {
                    e.target.value = 1;
                }
            });
        });
    </script>

    <style>
        /* Modern Color Variables */
        :root {
            --primary-color: #285496;
            --primary-dark: #1e3f70;
            --primary-light: #3a6bc7;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-bg: #f8f9fa;
            --border-radius: 16px;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.16);
        }

        /* Page Header Modern */
        .page-header-modern {
            background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);
            position: relative;
            overflow: hidden;
        }

        .header-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            opacity: 0.5;
        }

        .icon-wrapper-modern {
            animation: float 3s ease-in-out infinite;
        }

        /* Animations */
        @keyframes float {

            0%,
            100% {
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

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-10px);
            }

            75% {
                transform: translateX(10px);
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

        .shake {
            animation: shake 0.5s ease-in-out;
        }

        /* Modern Card Styles */
        .card-modern {
            border-radius: var(--border-radius);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: white !important;
        }

        .card-glow {
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(40, 84, 150, 0.05) 0%, transparent 70%);
            opacity: 1;
        }

        .card-header-modern {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .icon-badge {
            transition: all 0.3s ease;
        }

        /* Photo Upload Section */
        .photo-upload-section {
            position: sticky;
            top: 20px;
        }

        .photo-upload-container {
            background: white;
            border-radius: 16px;
            border: 2px solid #e9ecef;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .photo-upload-container:hover {
            border-color: var(--primary-color);
            box-shadow: 0 8px 24px rgba(40, 84, 150, 0.1);
        }

        .photo-preview {
            width: 100%;
            height: 300px;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .photo-preview:hover {
            transform: scale(1.02);
        }

        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
            font-weight: 600;
        }

        .photo-overlay {
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

        .photo-preview:hover .photo-overlay {
            opacity: 1;
        }

        .photo-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 0.75rem;
            text-align: center;
        }

        /* Form Section */
        .form-section {
            position: relative;
        }

        .section-header {
            position: relative;
        }

        .section-title {
            font-weight: 700;
            color: #2c3e50;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .section-divider {
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), transparent);
            border-radius: 2px;
        }

        /* Form Group Modern */
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
            box-shadow: 0 0 0 4px rgba(40, 84, 150, 0.1);
            background: white !important;
        }

        .form-control-modern::placeholder {
            color: #adb5bd;
        }

        .form-control-modern.is-invalid {
            border-color: var(--danger-color);
        }

        .form-control-modern.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1);
        }

        .invalid-feedback-modern {
            display: block;
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 500;
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
            border: 2px solid var(--danger-color);
            color: var(--danger-color);
        }

        .btn-outline-danger.btn-modern:hover {
            background: var(--danger-color);
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .photo-upload-section {
                position: relative;
                top: 0;
                margin-bottom: 2rem;
            }
        }

        @media (max-width: 768px) {
            .form-actions .d-flex {
                flex-direction: column;
            }

            .form-actions .btn-modern {
                width: 100%;
            }

            .section-title {
                font-size: 1rem;
            }

            .form-control-modern {
                font-size: 0.9rem;
                padding: 0.75rem 1rem;
            }

            .photo-preview {
                height: 250px;
            }
        }

        @media (max-width: 576px) {
            .page-header-modern {
                border-radius: 12px;
            }

            .card-modern {
                border-radius: 12px;
            }

            .btn-modern {
                font-size: 0.8rem;
                padding: 0.5rem 1.25rem;
            }

            .photo-preview {
                height: 200px;
            }
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Focus styles for accessibility */
        .btn-modern:focus {
            outline: 3px solid rgba(40, 84, 150, 0.4);
            outline-offset: 2px;
        }

        .form-control-modern:focus {
            outline: none;
        }
    </style>
@endsection