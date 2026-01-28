@extends('layouts.master')

@section('title', 'SIMPEL - Form Pembaruan Data Peserta')

@section('content')
    <!-- Hero Section -->
    <section class="form-hero" id="home">
        <div class="container">
            <div class="form-hero-content animate">
                <h1 class="form-hero-title">Form Pembaruan Data Peserta Pelatihan</h1>
                <p class="form-hero-text">Perbarui data diri Anda untuk mengikuti program pelatihan profesional. Isi
                    formulir dengan data yang lengkap dan valid.</p>
                <div class="progress-indicator">
                    <div class="progress-step active" id="step1">
                        <div class="step-number">1</div>
                        <div class="step-label">Pilih Pelatihan</div>
                    </div>
                    <div class="progress-step" id="step2">
                        <div class="step-number">2</div>
                        <div class="step-label">Verifikasi NIP/NRP</div>
                    </div>
                    <div class="progress-step" id="step3">
                        <div class="step-number">3</div>
                        <div class="step-label">Perbarui Data</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Form Section -->
    <section class="form-section" id="form-section">
        <div class="container">
            <div class="form-wrapper animate">
                <form action="{{ route('pendaftaran.updateData') }}" method="POST" enctype="multipart/form-data"
                    id="pendaftaranForm">
                    @csrf

                    <!-- Step 1: Pilih Pelatihan -->
                    <div class="form-step active" id="step1-content">
                        <div class="step-header">
                            <h2 class="step-title">Pilih Jenis Pelatihan</h2>
                            <p class="step-description">Silakan pilih jenis pelatihan yang sudah Anda daftarkan</p>
                        </div>

                        <div class="training-options">
                            @foreach($jenisPelatihan as $pelatihan)
                                <div class="training-card" data-id="{{ $pelatihan->id }}"
                                    data-kode="{{ $pelatihan->kode_pelatihan }}">
                                    <div class="training-icon">
                                        @if($pelatihan->kode_pelatihan == 'PKN_TK_II')
                                            <i class="fas fa-user-tie"></i>
                                        @elseif($pelatihan->kode_pelatihan == 'LATSAR')
                                            <i class="fas fa-user-graduate"></i>
                                        @elseif($pelatihan->kode_pelatihan == 'PKA')
                                            <i class="fas fa-chalkboard-teacher"></i>
                                        @elseif($pelatihan->kode_pelatihan == 'PKP')
                                            <i class="fas fa-user-shield"></i>
                                        @endif
                                    </div>
                                    <h3 class="training-name">{{ $pelatihan->nama_pelatihan }}</h3>
                                    <p class="training-code">{{ $pelatihan->kode_pelatihan }}</p>
                                    <button type="button" class="training-select-btn">Pilih Pelatihan Ini</button>
                                </div>
                            @endforeach
                        </div>

                        <input type="hidden" name="id_jenis_pelatihan" id="id_jenis_pelatihan"
                            value="{{ old('id_jenis_pelatihan', '') }}">
                    </div>

                    <!-- Step 2: Verifikasi NIP/NRP -->
                    <div class="form-step" id="step2-content">
                        <div class="step-header">
                            <h2 class="step-title">Verifikasi NIP/NRP</h2>
                            <p class="step-description">Masukkan NIP/NRP Anda untuk verifikasi dan akses form pembaruan data
                            </p>
                            <div class="selected-training">
                                <i class="fas fa-check-circle"></i>
                                <span id="selected-training-name"></span>
                            </div>
                        </div>

                        <div class="angkatan-container">
                            <div class="form-group">
                                <label for="nip_nrp" class="form-label required">NIP/NRP </label>
                                <input type="text" name="nip_nrp" id="nip_nrp"
                                    class="form-input @error('nip_nrp') error @enderror" placeholder="Masukkan NIP/NRP Anda"
                                    required>
                                @error('nip_nrp')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="button" class="btn btn-primary" id="verify-nip-btn" style="width: 100%;">
                                    <i class="fas fa-check"></i> Verifikasi NIP/NRP
                                </button>
                            </div>

                            <div class="verification-result" id="verification-result" style="display: none;">
                                <div class="info-card">
                                    <h4><i class="fas fa-info-circle"></i> Hasil Verifikasi</h4>
                                    <div class="info-details">
                                        <div class="info-item" id="verification-success" style="display: none;">
                                            <span class="info-label">Status:</span>
                                            <span class="info-value text-success">
                                                <i class="fas fa-check-circle"></i>
                                                <span id="success-message"></span>
                                            </span>
                                        </div>
                                        <div class="info-item" id="verification-error" style="display: none;">
                                            {{-- <span class="info-label">Status:</span> --}}
                                            <span class="info-value text-danger">
                                                <i class="fas fa-exclamation-circle"></i>
                                                <span id="error-message"></span>
                                            </span>
                                        </div>
                                        <div class="info-item" id="verification-details" style="display: none;">
                                            <span class="info-label">Nama Peserta:</span>
                                            <span class="info-value" id="detail-nama"></span>
                                        </div>
                                        <div class="info-item" id="verification-anggaran" style="display: none;">
                                            <span class="info-label">Angkatan:</span>
                                            <span class="info-value" id="detail-angkatan"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="step-navigation">
                            <button type="button" class="btn btn-secondary" id="back-to-step1">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </button>
                            <button type="button" class="btn btn-primary" id="next-to-step3" disabled>
                                Lanjutkan <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Perbarui Data -->
                    <div class="form-step" id="step3-content">
                        <div class="step-header">
                            <h2 class="step-title">Perbarui Data Peserta</h2>
                            <p class="step-description">Lengkapi atau perbarui data berikut dengan informasi yang valid</p>
                            <div class="selected-info">
                                <div class="info-badge">
                                    <i class="fas fa-book"></i> <span id="current-training-name"></span>
                                </div>
                                <div class="info-badge" style="display: none">
                                    <i class="fas fa-id-card"></i> NIP/NRP: <span id="current-nip-nrp"></span>
                                </div>
                                <div class="info-badge">
                                    <i class="fas fa-calendar-alt"></i> Angkatan: <span id="current-angkatan-name"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Dynamic Form Container -->
                        <div class="dynamic-form-container" id="dynamic-form-container">
                            <!-- Form akan dimuat dinamis di sini -->
                        </div>

                        <div class="step-navigation">
                            <button type="button" class="btn btn-secondary" id="back-to-step2">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </button>
                            <button type="submit" class="btn btn-success" id="submit-form"data-submitting-text="<i class='fas fa-spinner fa-spin'></i> Menyimpan...">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </section>
@endsection

@push('styles')
        <style>
            /* CSS UTAMA YANG SUDAH ADA */
            :root {
                --primary-color: #1a3a6c;
                --secondary-color: #2c5282;
                --accent-color: #4299e1;
                --success-color: #48bb78;
                --warning-color: #ed8936;
                --danger-color: #f56565;
                --light-color: #f7fafc;
                --dark-color: #2d3748;
                --gray-color: #718096;
                --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                --transition: all 0.3s ease;
            }

            /* Hero Section */
            .form-hero {
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                color: white;
                padding: 60px 0;
                margin-bottom: 40px;
            }

            .form-hero-content {
                text-align: center;
                max-width: 800px;
                margin: 0 auto;
            }

            .form-hero-title {
                font-size: 2.5rem;
                margin-bottom: 15px;
                font-weight: 700;
            }

            .form-hero-text {
                font-size: 1.1rem;
                opacity: 0.9;
                margin-bottom: 40px;
                line-height: 1.6;
            }

            .progress-indicator {
                display: flex;
                justify-content: center;
                gap: 40px;
                margin-top: 40px;
            }

            .progress-step {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 10px;
                opacity: 0.5;
                transition: var(--transition);
            }

            .progress-step.active {
                opacity: 1;
            }

            .step-number {
                width: 40px;
                height: 40px;
                background-color: rgba(255, 255, 255, 0.2);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                border: 2px solid transparent;
            }

            .progress-step.active .step-number {
                background-color: var(--accent-color);
                border-color: white;
            }

            .step-label {
                font-size: 0.9rem;
                font-weight: 500;
            }

            /* Form Section */
            .form-section {
                padding: 40px 0 80px 0;
            }

            .form-wrapper {
                max-width: 1000px;
                margin: 0 auto;
                background: white;
                border-radius: 12px;
                box-shadow: var(--shadow);
                overflow: hidden;
            }

            .form-step {
                padding: 40px;
                display: none;
            }

            .form-step.active {
                display: block;
                animation: fadeIn 0.5s ease;
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

            .step-header {
                text-align: center;
                margin-bottom: 40px;
            }

            .step-title {
                font-size: 1.8rem;
                color: var(--primary-color);
                margin-bottom: 10px;
                font-weight: 600;
            }

            .step-description {
                color: var(--gray-color);
                margin-bottom: 20px;
            }

            /* Training Cards */
            .training-options {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 20px;
                margin-top: 30px;
            }

            .training-card {
                background: var(--light-color);
                border-radius: 10px;
                padding: 25px;
                text-align: center;
                transition: var(--transition);
                border: 2px solid transparent;
                cursor: pointer;
            }

            .training-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
                border-color: var(--accent-color);
            }

            .training-card.selected {
                border-color: var(--success-color);
                background: linear-gradient(135deg, rgba(72, 187, 120, 0.1), rgba(66, 153, 225, 0.1));
            }

            .training-icon {
                font-size: 2.5rem;
                color: var(--primary-color);
                margin-bottom: 15px;
            }

            .training-name {
                font-size: 1.2rem;
                color: var(--dark-color);
                margin-bottom: 5px;
                font-weight: 600;
            }

            .training-code {
                font-size: 0.9rem;
                color: var(--gray-color);
                margin-bottom: 20px;
            }

            .training-select-btn {
                background: var(--accent-color);
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
                font-weight: 500;
                transition: var(--transition);
                width: 100%;
            }

            .training-select-btn:hover {
                background: var(--secondary-color);
            }

            .selected-training {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                background: rgba(72, 187, 120, 0.1);
                color: var(--success-color);
                padding: 10px 20px;
                border-radius: 5px;
                margin-top: 15px;
                font-weight: 500;
            }

            /* Form Elements */
            .form-group {
                margin-bottom: 20px;
            }

            .form-label {
                display: block;
                margin-bottom: 8px;
                font-weight: 500;
                color: var(--dark-color);
            }

            .form-label.required::after {
                content: " *";
                color: var(--danger-color);
            }

            .form-select,
            .form-input,
            .form-textarea {
                width: 100%;
                padding: 10px 12px;
                border: 2px solid #e2e8f0;
                border-radius: 6px;
                font-size: 1rem;
                transition: var(--transition);
                background: white;
            }

            .form-select.error,
            .form-input.error,
            .form-textarea.error {
                border-color: var(--danger-color) !important;
                box-shadow: 0 0 0 3px rgba(245, 101, 101, 0.1) !important;
            }

            .form-select:focus,
            .form-input:focus,
            .form-textarea:focus {
                outline: none;
                border-color: var(--accent-color);
                box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
            }

            .form-textarea {
                min-height: 100px;
                resize: vertical;
            }

            .form-hint {
                font-size: 0.85rem;
                color: var(--gray-color);
                margin-top: 5px;
            }

            .form-file {
                position: relative;
                overflow: hidden;
            }

            .form-file-input {
                position: absolute;
                width: 100%;
                height: 100%;
                opacity: 0;
                cursor: pointer;
            }

            .form-file-label {
                display: block;
                padding: 15px;
                background: var(--light-color);
                border: 2px dashed #cbd5e0;
                border-radius: 6px;
                text-align: center;
                cursor: pointer;
                transition: var(--transition);
            }

            .form-file-label:hover {
                border-color: var(--accent-color);
                background: rgba(66, 153, 225, 0.05);
            }

            .form-file-name {
                margin-top: 10px;
                font-size: 0.9rem;
                color: var(--gray-color);
            }

            /* Error Styling */
            .text-danger {
                color: var(--danger-color) !important;
                font-size: 0.85rem;
                margin-top: 5px;
                display: block;
            }

            /* Angkatan Info */
            .angkatan-info {
                margin-top: 30px;
            }

            .info-card {
                background: linear-gradient(135deg, #f7fafc, #edf2f7);
                border-radius: 10px;
                padding: 20px;
                border-left: 4px solid var(--accent-color);
            }

            .info-card h4 {
                display: flex;
                align-items: center;
                gap: 10px;
                color: var(--primary-color);
                margin-bottom: 15px;
                font-size: 1.1rem;
            }

            .info-details {
                display: grid;
                gap: 10px;
            }

            .info-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .info-label {
                font-weight: 500;
                color: var(--dark-color);
            }

            .info-value {
                color: var(--gray-color);
            }

            .text-success {
                color: var(--success-color) !important;
            }

            .text-danger {
                color: var(--danger-color) !important;
            }

            /* Selected Info */
            .selected-info {
                display: flex;
                gap: 15px;
                justify-content: center;
                margin-top: 20px;
                flex-wrap: wrap;
            }

            .selected-info .info-badge {
                background: var(--primary-color);
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 16px;
                border-radius: 20px;
                font-size: 0.9rem;
                font-weight: 500;
                color: white;
            }

            /* Dynamic Form Container */
            .dynamic-form-container {
                margin-top: 30px;
            }

            .form-section-header {
                font-size: 1.4rem;
                color: var(--primary-color);
                margin: 40px 0 20px 0;
                padding-bottom: 10px;
                border-bottom: 2px solid #e2e8f0;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .form-section-header:first-child {
                margin-top: 0;
            }

            .form-row {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
                margin-bottom: 20px;
            }

            .checkbox-group {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 15px;
            }

            .checkbox-group input[type="checkbox"] {
                width: 18px;
                height: 18px;
            }

            .checkbox-group label {
                margin: 0;
                font-weight: normal;
            }

            /* Buttons */
            .btn {
                padding: 12px 25px;
                border: none;
                border-radius: 6px;
                font-weight: 500;
                cursor: pointer;
                transition: var(--transition);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }

            .btn-primary {
                background: var(--accent-color);
                color: white;
            }

            .btn-primary:hover {
                background: var(--secondary-color);
                transform: translateY(-2px);
            }

            .btn-primary:disabled {
                background: #cbd5e0;
                cursor: not-allowed;
                transform: none;
            }

            .btn-secondary {
                background: #e2e8f0;
                color: var(--dark-color);
            }

            .btn-secondary:hover {
                background: #cbd5e0;
            }

            .btn-success {
                background: var(--success-color);
                color: white;
            }

            .btn-success:hover {
                background: #38a169;
                transform: translateY(-2px);
            }

            .step-navigation {
                display: flex;
                justify-content: space-between;
                margin-top: 40px;
                padding-top: 20px;
                border-top: 2px solid #e2e8f0;
            }

            /* Alert Styles */
            .alert {
                padding: 15px;
                border-radius: 6px;
                margin-bottom: 20px;
                border-left: 4px solid;
                display: flex;
                align-items: flex-start;
                gap: 10px;
            }

            .alert-info {
                background-color: rgba(66, 153, 225, 0.1);
                border-color: var(--accent-color);
                color: var(--secondary-color);
            }

            .alert i {
                font-size: 1.2rem;
                flex-shrink: 0;
            }

            /* Verification Result */
            .verification-result {
                margin-top: 20px;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .form-hero {
                    padding: 40px 0;
                }

                .form-hero-title {
                    font-size: 2rem;
                }

                .progress-indicator {
                    gap: 20px;
                }

                .progress-step {
                    gap: 5px;
                }

                .step-label {
                    font-size: 0.8rem;
                }

                .form-step {
                    padding: 20px;
                }

                .step-title {
                    font-size: 1.5rem;
                }

                .training-options {
                    grid-template-columns: 1fr;
                }

                .form-row {
                    grid-template-columns: 1fr;
                }

                .step-navigation {
                    flex-direction: column;
                    gap: 10px;
                }

                .step-navigation .btn {
                    width: 100%;
                }

                .selected-info {
                    flex-direction: column;
                    align-items: center;
                }

                .info-item {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 5px;
                }

                .info-label, .info-value {
                    width: 100%;
                }
            }

            /* CSS untuk input yang disabled */
            .form-input:disabled {
                background-color: #f7fafc;
                cursor: not-allowed;
                opacity: 0.7;
            }

            /* Styling untuk form-hint tambahan */
            .form-hint {
                font-size: 0.85rem;
                color: var(--gray-color);
                margin-top: 5px;
                display: block;
            }
            /* File Upload Styling */
    .file-info {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px;
        background: rgba(72, 187, 120, 0.1);
        border-radius: 4px;
        margin-top: 5px;
    }

    .file-info i {
        font-size: 1rem;
    }

    .btn-change-file {
        background: none;
        border: 1px solid var(--accent-color);
        color: var(--accent-color);
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-change-file:hover {
        background: var(--accent-color);
        color: white;
    }

    .no-file {
        color: var(--gray-color);
        font-style: italic;
    }

    /* Crop Container Styling */
    .crop-wrapper {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
    }

    .crop-controls {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .crop-controls .btn {
        padding: 6px 12px;
        font-size: 0.85rem;
    }

    /* Preview styling */
    #crop-preview-container {
        text-align: center;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    #cropped-preview {
        border-radius: 4px;
    }

    /* Hide file input when cropping */
    .hidden-input {
        display: none;
    }

    /* Loading overlay */
    .crop-loading {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .crop-loading i {
        font-size: 2rem;
        color: var(--primary-color);
    }

    /* ============================================
       LOADING OVERLAY STYLES
       ============================================ */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.95);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
    }

    .loading-content {
        background: white;
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        max-width: 500px;
        width: 90%;
        text-align: center;
        border: 2px solid var(--accent-color);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(66, 153, 225, 0.4);
        }
        70% {
            box-shadow: 0 0 0 20px rgba(66, 153, 225, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(66, 153, 225, 0);
        }
    }

    .loading-spinner {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 20px;
    }

    .loading-spinner i {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loading-message h4 {
        color: var(--primary-color);
        margin-bottom: 15px;
        font-size: 1.4rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .loading-message p {
        color: var(--gray-color);
        line-height: 1.6;
        margin-bottom: 10px;
    }

    .loading-detail {
        background: rgba(245, 158, 11, 0.1);
        border-left: 3px solid var(--warning-color);
        padding: 10px 15px;
        border-radius: 4px;
        margin-top: 20px;
        font-size: 0.9rem;
        text-align: left;
    }

    .loading-detail i {
        color: var(--warning-color);
        margin-right: 8px;
    }

    /* Disable all form inputs during submission */
    .submitting * {
        pointer-events: none !important;
    }

    .submitting .form-input:disabled,
    .submitting .form-select:disabled {
        background-color: #f8f9fa;
        opacity: 0.7;
    }

    /* Progress indicator untuk upload */
    .upload-progress {
        margin-top: 20px;
        width: 100%;
    }

    .progress-bar {
        height: 8px;
        background: #e2e8f0;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--accent-color), var(--success-color));
        width: 0%;
        transition: width 0.3s ease;
    }

    .progress-text {
        font-size: 0.85rem;
        color: var(--gray-color);
        display: flex;
        justify-content: space-between;
    }

    .time-estimate {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.3);
        border-radius: 6px;
        padding: 8px 15px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #7c2d12;
        font-size: 0.9rem;
    }

    .time-estimate i {
        color: #d97706;
    }
        </style>
@endpush


@push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ============================================
            // VARIABLES & ELEMENTS
            // ============================================
            const step1Content = document.getElementById('step1-content');
            const step2Content = document.getElementById('step2-content');
            const step3Content = document.getElementById('step3-content');
            const step1Indicator = document.getElementById('step1');
            const step2Indicator = document.getElementById('step2');
            const step3Indicator = document.getElementById('step3');
            const jenisPelatihanInput = document.getElementById('id_jenis_pelatihan');
            const selectedTrainingName = document.getElementById('selected-training-name');
            const currentTrainingName = document.getElementById('current-training-name');
            const currentNipNrp = document.getElementById('current-nip-nrp');
            const currentAngkatanName = document.getElementById('current-angkatan-name');
            const dynamicFormContainer = document.getElementById('dynamic-form-container');
            const backToStep1Btn = document.getElementById('back-to-step1');
            const backToStep2Btn = document.getElementById('back-to-step2');
            const nextToStep3Btn = document.getElementById('next-to-step3');
            const verifyNipBtn = document.getElementById('verify-nip-btn');
            const nipNrpInput = document.getElementById('nip_nrp');
            const verificationResult = document.getElementById('verification-result');
            const verificationSuccess = document.getElementById('verification-success');
            const verificationError = document.getElementById('verification-error');
            const verificationDetails = document.getElementById('verification-details');
            const verificationAnggaran = document.getElementById('verification-anggaran');
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');
            const detailNama = document.getElementById('detail-nama');
            const detailAngkatan = document.getElementById('detail-angkatan');
            const submitFormBtn = document.getElementById('submit-form');

            let selectedTraining = null;
            let verifiedPeserta = null;
            let pendaftaranData = null;

            // ============================================
            // STEP 1: PILIH PELATIHAN
            // ============================================
            document.querySelectorAll('.training-card').forEach(card => {
                card.addEventListener('click', function () {
                    // Remove selected class from all cards
                    document.querySelectorAll('.training-card').forEach(c => {
                        c.classList.remove('selected');
                    });

                    // Add selected class to clicked card
                    this.classList.add('selected');

                    // Get training data
                    selectedTraining = {
                        id: this.getAttribute('data-id'),
                        kode: this.getAttribute('data-kode'),
                        name: this.querySelector('.training-name').textContent
                    };

                    // Update hidden input
                    jenisPelatihanInput.value = selectedTraining.id;

                    // Update UI
                    selectedTrainingName.textContent = selectedTraining.name;
                    currentTrainingName.textContent = selectedTraining.name;

                    // Move to step 2
                    moveToStep(2);

                    // Reset verification
                    resetVerification();
                });
            });

            // ============================================
            // STEP 2: VERIFIKASI NIP/NRP
            // ============================================
            function resetVerification() {
                verificationResult.style.display = 'none';
                verificationSuccess.style.display = 'none';
                verificationError.style.display = 'none';
                verificationDetails.style.display = 'none';
                verificationAnggaran.style.display = 'none';
                nextToStep3Btn.disabled = true;
                verifiedPeserta = null;
                pendaftaranData = null;
                nipNrpInput.value = '';
                dynamicFormContainer.innerHTML = '';
            }

            verifyNipBtn.addEventListener('click', async function () {
                if (!selectedTraining || !nipNrpInput.value.trim()) {
                    showVerificationError('Silakan pilih pelatihan dan masukkan NIP/NRP');
                    return;
                }

                // Show loading state
                const originalText = verifyNipBtn.innerHTML;
                verifyNipBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memverifikasi...';
                verifyNipBtn.disabled = true;

                try {
                    const response = await fetch('/api/verify-nip', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            nip_nrp: nipNrpInput.value.trim(),
                            id_jenis_pelatihan: selectedTraining.id
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Verification successful
                        verifiedPeserta = data.peserta;
                        pendaftaranData = data.pendaftaran;

                        // Update UI
                        currentNipNrp.textContent = verifiedPeserta.nip_nrp;
                        currentAngkatanName.textContent = pendaftaranData.angkatan ? 
                            `${pendaftaranData.angkatan.nama_angkatan} (${pendaftaranData.angkatan.tahun})` : 
                            'Angkatan tidak tersedia';

                        // Show verification details
                        successMessage.textContent = data.message;
                        detailNama.textContent = verifiedPeserta.nama_lengkap;
                        detailAngkatan.textContent = pendaftaranData.angkatan ? 
                            `${pendaftaranData.angkatan.nama_angkatan} (${pendaftaranData.angkatan.tahun})` : 
                            'Tidak tersedia';

                        verificationSuccess.style.display = 'flex';
                        verificationError.style.display = 'none';
                        verificationDetails.style.display = 'flex';
                        verificationAnggaran.style.display = 'flex';
                        verificationResult.style.display = 'block';

                        nextToStep3Btn.disabled = false;

                    } else {
                        // Verification failed
                        errorMessage.textContent = data.message;
                        verificationError.style.display = 'flex';
                        verificationSuccess.style.display = 'none';
                        verificationDetails.style.display = 'none';
                        verificationAnggaran.style.display = 'none';
                        verificationResult.style.display = 'block';

                        nextToStep3Btn.disabled = true;
                    }
                } catch (error) {
                    console.error('Verification error:', error);
                    showVerificationError('Terjadi kesalahan jaringan. Silakan coba lagi.');
                } finally {
                    verifyNipBtn.innerHTML = originalText;
                    verifyNipBtn.disabled = false;
                }
            });

            // ============================================
            // NAVIGATION
            // ============================================
            function moveToStep(step) {
                // Update indicators
                [step1Indicator, step2Indicator, step3Indicator].forEach(indicator => {
                    indicator.classList.remove('active');
                });
                document.getElementById(`step${step}`).classList.add('active');

                // Update content
                [step1Content, step2Content, step3Content].forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(`step${step}-content`).classList.add('active');

                // Jika pindah ke step 3, load form
                if (step === 3 && verifiedPeserta) {
                    loadFormPartial();
                }

                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            backToStep1Btn.addEventListener('click', () => {
                moveToStep(1);
                resetVerification();
            });

            backToStep2Btn.addEventListener('click', () => moveToStep(2));

            nextToStep3Btn.addEventListener('click', () => {
                if (verifiedPeserta && pendaftaranData) {
                    moveToStep(3);
                }
            });

            // ============================================
            // STEP 3: LOAD DYNAMIC FORM
            // ============================================
            async function loadFormPartial() {
                if (!selectedTraining || !verifiedPeserta || !pendaftaranData) {
                    return;
                }

                dynamicFormContainer.innerHTML = `
                    <div class="form-loading">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>Menyiapkan formulir pembaruan data...</p>
                    </div>
                `;

                try {
                    const response = await fetch(`/form-partial/${selectedTraining.kode}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            peserta_data: JSON.stringify(verifiedPeserta),
                            pendaftaran_data: JSON.stringify(pendaftaranData)
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const html = await response.text();
                    dynamicFormContainer.innerHTML = html;

                    // Setup form interactions
                    setupFormInteractions();
                    setupPangkatAutoFill();

                } catch (error) {
                    console.error('Error loading form partial:', error);
                    dynamicFormContainer.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            Gagal memuat formulir. Silakan coba lagi.
                        </div>
                    `;
                }
            }

            // ============================================
            // FORM INTERACTIONS
            // ============================================

            // ============================================
            // CROP FOTO 3×4 IMPLEMENTATION
            // ============================================

            let cropper = null;
            let originalImageFile = null;

            // Setup crop functionality
            function setupPhotoCropping() {
                const fileInput = document.getElementById('file_pas_foto');
                const uploadContainer = document.getElementById('upload-container');
                const cropContainer = document.getElementById('crop-container');
                const previewContainer = document.getElementById('crop-preview-container');
                const cropImage = document.getElementById('crop-image');
                const croppedPreview = document.getElementById('cropped-preview');
                const cropDataInput = document.getElementById('crop_data');
                const croppedInput = document.getElementById('file_pas_foto_cropped');
                const changePhotoBtn = document.getElementById('change-photo');
                const fileNameDisplay = document.getElementById('file-name-display');

                // ============================================
                // HANDLE FOTO YANG SUDAH ADA
                // ============================================
                const existingPhotoContainer = document.getElementById('existing-photo-container');
                if (existingPhotoContainer) {
                    const changeBtn = document.getElementById('btn-change-photo-existing');
                    if (changeBtn) {
                        changeBtn.addEventListener('click', function () {
                            // Sembunyikan container foto lama
                            existingPhotoContainer.style.display = 'none';

                            // Tampilkan upload container
                            if (uploadContainer) uploadContainer.style.display = 'block';

                            // Reset semua input
                            if (fileInput) fileInput.value = '';
                            if (cropDataInput) cropDataInput.value = '';
                            if (croppedInput) croppedInput.value = '';

                            // Reset nama file
                            if (fileNameDisplay) {
                                fileNameDisplay.innerHTML = '<span class="no-file">Belum ada file dipilih</span>';
                            }
                        });
                    }
                }

                // Event untuk file input utama
                if (fileInput) {
                    fileInput.addEventListener('change', function (e) {
                        if (this.files && this.files[0]) {
                            handleFileSelect(this.files[0]);
                        }
                    });
                }

                function handleFileSelect(file) {
                    // Validasi file
                    if (!file.type.match('image.*')) {
                        showErrorMessage('File harus berupa gambar (JPG/PNG)');
                        return;
                    }

                    if (file.size > 1 * 1024 * 1024) {
                        showErrorMessage('Ukuran file maksimal 1MB');
                        return;
                    }

                    // Simpan file asli (hanya untuk proses crop)
                    originalImageFile = file;

                    // Tampilkan nama file
                    if (fileNameDisplay) {
                        fileNameDisplay.innerHTML = `
                            <span style="color: var(--warning-color);">
                                <i class="fas fa-crop-alt"></i> 
                                ${file.name} (${formatFileSize(file.size)})
                            </span>
                        `;
                    }

                    // Baca file sebagai URL
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        // Sembunyikan upload container
                        if (uploadContainer) uploadContainer.style.display = 'none';

                        // Tampilkan crop container
                        if (cropContainer) cropContainer.style.display = 'block';

                        // Set image source
                        if (cropImage) {
                            cropImage.src = e.target.result;

                            // Inisialisasi cropper setelah image loaded
                            cropImage.onload = function () {
                                initCropper();
                            };
                        }
                    };

                    reader.readAsDataURL(file);
                }

                function initCropper() {
                    // Hancurkan cropper lama jika ada
                    if (cropper) {
                        cropper.destroy();
                    }

                    // Inisialisasi cropper baru dengan aspect ratio 3:4
                    if (cropImage) {
                        cropper = new Cropper(cropImage, {
                            aspectRatio: 3 / 4,
                            viewMode: 1,
                            autoCropArea: 0.8,
                            movable: true,
                            rotatable: true,
                            scalable: true,
                            zoomable: true,
                            zoomOnTouch: true,
                            zoomOnWheel: true,
                            cropBoxMovable: true,
                            cropBoxResizable: true,
                            toggleDragModeOnDblclick: false,
                            minCropBoxWidth: 100,
                            minCropBoxHeight: 133,
                            ready: function () {
                                console.log('Cropper siap digunakan');
                            }
                        });

                        // Setup control buttons
                        setupCropControls();
                    }
                }

                function setupCropControls() {
                    // Zoom in
                    document.getElementById('crop-zoom-in').addEventListener('click', function () {
                        cropper.zoom(0.1);
                    });

                    // Zoom out
                    document.getElementById('crop-zoom-out').addEventListener('click', function () {
                        cropper.zoom(-0.1);
                    });

                    // Rotate left
                    document.getElementById('crop-rotate-left').addEventListener('click', function () {
                        cropper.rotate(-45);
                    });

                    // Rotate right
                    document.getElementById('crop-rotate-right').addEventListener('click', function () {
                        cropper.rotate(45);
                    });

                    // Reset
                    document.getElementById('crop-reset').addEventListener('click', function () {
                        cropper.reset();
                    });

                    // Confirm crop
                    document.getElementById('crop-confirm').addEventListener('click', function () {
                        cropAndPreview();
                    });

                    // Cancel crop
                    document.getElementById('crop-cancel').addEventListener('click', function () {
                        cancelCrop();
                    });
                }

                function cropAndPreview() {
                    if (!cropper) {
                        showErrorMessage('Cropper belum diinisialisasi');
                        return;
                    }

                    // Dapatkan data crop
                    const cropData = cropper.getData();
                    if (cropDataInput) cropDataInput.value = JSON.stringify(cropData);

                    // Buat canvas untuk hasil crop
                    const canvas = cropper.getCroppedCanvas({
                        width: 450,  // 3 × 150
                        height: 600, // 4 × 150
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: 'high'
                    });

                    // Konversi canvas ke base64 (LANGSUNG)
                    const base64Image = canvas.toDataURL('image/jpeg', 0.95); // kualitas 95%

                    // Set preview
                    if (croppedPreview) croppedPreview.src = base64Image;

                    // Sembunyikan crop container
                    if (cropContainer) cropContainer.style.display = 'none';

                    // Tampilkan preview container
                    if (previewContainer) previewContainer.style.display = 'block';

                    // Simpan base64 ke hidden input
                    if (croppedInput) croppedInput.value = base64Image;

                    // Clear file input (tidak perlu file asli)
                    if (fileInput) fileInput.value = '';

                    console.log('Foto berhasil di-crop, ukuran base64:', base64Image.length, 'bytes');
                }

                function cancelCrop() {
                    // Hancurkan cropper
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }

                    // Reset tampilan
                    cropContainer.style.display = 'none';
                    uploadContainer.style.display = 'block';
                    previewContainer.style.display = 'none';

                    // Reset input file
                    fileInput.value = '';
                    originalFileInput.value = '';
                    cropDataInput.value = '';
                    croppedInput.value = '';

                    // Reset nama file
                    fileNameDisplay.innerHTML = '<span class="no-file">Belum ada file dipilih</span>';
                }

                // Tombol ganti foto di preview
                if (changePhotoBtn) {
                    changePhotoBtn.addEventListener('click', function () {
                        // Reset preview
                        previewContainer.style.display = 'none';

                        // Tampilkan upload container
                        uploadContainer.style.display = 'block';

                        // Reset input
                        fileInput.value = '';
                        cropDataInput.value = '';
                        croppedInput.value = '';

                        // Reset nama file
                        fileNameDisplay.innerHTML = '<span class="no-file">Belum ada file dipilih</span>';
                    });
                }
            }

            // Ganti fungsi loadProvinsi
            async function loadProvinsi() {
                const provinsiSelect = document.querySelector('[name="id_provinsi"]');
                if (!provinsiSelect) return;

                provinsiSelect.innerHTML = '<option value="">Memuat provinsi...</option>';

                try {
                    // Ganti endpoint
                    const response = await fetch('/api/get-provinces');
                    const result = await response.json();

                    if (result.success) {
                        provinsiSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                        result.data.forEach(prov => {
                            const option = document.createElement('option');
                            option.value = prov.id; // Menggunakan id sebagai value
                            option.textContent = prov.name;
                            option.dataset.code = prov.code; // Simpan code sebagai data attribute
                            provinsiSelect.appendChild(option);
                        });

                        // Set value dari data peserta jika ada
                        if (verifiedPeserta && verifiedPeserta.kepegawaian && verifiedPeserta.kepegawaian.id_provinsi) {
                            setTimeout(() => {
                                provinsiSelect.value = verifiedPeserta.kepegawaian.id_provinsi;
                                provinsiSelect.dispatchEvent(new Event('change'));
                            }, 100);
                        }
                    } else {
                        throw new Error(result.message);
                    }

                } catch (error) {
                    console.error('Provinsi error:', error);
                    provinsiSelect.innerHTML = '<option value="">Error loading</option>';
                    showErrorMessage('Gagal memuat data provinsi');
                }
            }

            // Ganti fungsi loadKabupaten
            async function loadKabupaten(provId) {
                const kabSelect = document.querySelector('[name="id_kabupaten_kota"]');
                if (!kabSelect) return;

                kabSelect.innerHTML = '<option value="">Memuat kabupaten...</option>';
                kabSelect.disabled = true;

                try {
                    // Ganti endpoint
                    const response = await fetch(`/api/get-regencies/${provId}`);
                    const result = await response.json();

                    if (result.success) {
                        kabSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                        kabSelect.disabled = false;

                        result.data.forEach(kab => {
                            const option = document.createElement('option');
                            option.value = kab.id; // Menggunakan id sebagai value
                            option.textContent = kab.name;
                            kabSelect.appendChild(option);
                        });

                        // Set value dari data peserta jika ada
                        if (verifiedPeserta && verifiedPeserta.kepegawaian && verifiedPeserta.kepegawaian.id_kabupaten_kota) {
                            kabSelect.value = verifiedPeserta.kepegawaian.id_kabupaten_kota;
                        }
                    } else {
                        throw new Error(result.message);
                    }

                } catch (error) {
                    console.error('Kabupaten error:', error);
                    kabSelect.innerHTML = '<option value="">Error loading</option>';
                    kabSelect.disabled = false;
                    showErrorMessage('Gagal memuat data kabupaten/kota');
                }
            }

            // ============================================
            // KAPITALISASI OTOMATIS - FIXED VERSION
            // ============================================

            // Fungsi untuk kapitalisasi setiap kata
            function capitalizeWords(str) {
                return str.replace(/\b\w/g, function (char) {
                    return char.toUpperCase();
                });
            }

            // Fungsi untuk lowercase
            function toLowerCase(str) {
                return str.toLowerCase();
            }

            // Fungsi untuk uppercase
            function toUpperCase(str) {
                return str.toUpperCase();
            }

            // Fungsi untuk setup kapitalisasi
            function setupAutoCapitalization() {
                console.log('Setting up auto-capitalization...');

                // Event listener untuk input dengan class 'capitalize'
                document.querySelectorAll('.capitalize').forEach(function (input) {
                    // Setup input event
                    input.addEventListener('input', function (e) {
                        if (this.value) {
                            this.value = capitalizeWords(this.value);
                        }
                    });

                    // Setup change event
                    input.addEventListener('change', function (e) {
                        if (this.value) {
                            this.value = capitalizeWords(this.value);
                        }
                    });

                    // Apply to existing value
                    if (input.value) {
                        input.value = capitalizeWords(input.value);
                    }

                    console.log('Added capitalize to:', input.name);
                });

                // Event listener untuk input dengan class 'lowercase'
                document.querySelectorAll('.lowercase').forEach(function (input) {
                    input.addEventListener('input', function (e) {
                        if (this.value) {
                            this.value = toLowerCase(this.value);
                        }
                    });

                    input.addEventListener('change', function (e) {
                        if (this.value) {
                            this.value = toLowerCase(this.value);
                        }
                    });

                    if (input.value) {
                        input.value = toLowerCase(input.value);
                    }
                });

                // Event listener untuk input dengan class 'uppercase'
                document.querySelectorAll('.uppercase').forEach(function (input) {
                    input.addEventListener('input', function (e) {
                        if (this.value) {
                            this.value = toUpperCase(this.value);
                        }
                    });

                    input.addEventListener('change', function (e) {
                        if (this.value) {
                            this.value = toUpperCase(this.value);
                        }
                    });

                    if (input.value) {
                        input.value = toUpperCase(input.value);
                    }
                });
            }

            // ============================================
            // FUNGSI UNTUK MENGATUR STATUS PERKAWINAN
            // ============================================
            function setupMaritalStatusLogic() {
                const maritalStatusSelect = document.getElementById('status_perkawinan');
                const spouseNameInput = document.getElementById('nama_pasangan');

                if (!maritalStatusSelect || !spouseNameInput) return;

                function toggleSpouseNameInput() {
                    const isMarried = maritalStatusSelect.value === 'Menikah';

                    if (isMarried) {
                        spouseNameInput.disabled = false;
                        spouseNameInput.required = true;
                        spouseNameInput.placeholder = "Masukkan nama istri/suami";
                    } else {
                        spouseNameInput.disabled = true;
                        spouseNameInput.required = false;
                        spouseNameInput.value = ''; // Kosongkan nilai jika tidak menikah
                        spouseNameInput.placeholder = "Hanya untuk yang berstatus Menikah";
                    }

                    // Update label dan validasi
                    const label = spouseNameInput.parentElement.querySelector('.form-label');
                    if (label) {
                        if (isMarried) {
                            label.classList.add('required');
                            label.textContent = 'Nama Istri/Suami';
                        } else {
                            label.classList.remove('required');
                            label.textContent = 'Nama Istri/Suami';
                        }
                    }

                    // Clear error jika ada
                    spouseNameInput.classList.remove('error');
                    const errorMsg = spouseNameInput.parentElement.querySelector('.text-danger');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                }

                // Inisialisasi saat pertama kali load
                toggleSpouseNameInput();

                // Event listener untuk perubahan
                maritalStatusSelect.addEventListener('change', toggleSpouseNameInput);
            }

            function setupFormInteractions() {
                // Load provinsi data
                loadProvinsi();

                // Event listener untuk provinsi change
                document.addEventListener('change', function (e) {
                    if (e.target.name === 'id_provinsi' && e.target.value) {
                        loadKabupaten(e.target.value);
                    }
                });

                // File input handlers
                document.querySelectorAll('.form-file-input').forEach(input => {
                    input.addEventListener('change', function () {
                        const fileName = this.files[0]?.name || 'Belum ada file dipilih';
                        this.parentElement.querySelector('.form-file-name').textContent = fileName;
                    });
                });
                setupAutoCapitalization();

                setupPangkatAutoFill();
                setupPhotoCropping();

                // Setup mentor form jika ada
                setupMentorForm();
                setupMaritalStatusLogic();
            }

            function setupMentorForm() {
                const mentorSelect = document.getElementById('sudah_ada_mentor');
                const mentorContainer = document.getElementById('mentor-container');
                const mentorModeSelect = document.getElementById('mentor_mode');
                const mentorDropdown = document.getElementById('id_mentor');

                if (!mentorSelect || !mentorContainer) return;

                // Toggle mentor container
                mentorSelect.addEventListener('change', function () {
                    if (this.value === 'Ya') {
                        mentorContainer.style.display = 'block';
                        if (mentorDropdown && mentorDropdown.options.length <= 1) {
                            loadMentors();
                        }
                    } else {
                        mentorContainer.style.display = 'none';
                    }
                });

                // Toggle between select and add forms
                if (mentorModeSelect) {
                    mentorModeSelect.addEventListener('change', function () {
                        const selectForm = document.getElementById('select-mentor-form');
                        const addForm = document.getElementById('add-mentor-form');

                        if (this.value === 'pilih') {
                            selectForm.style.display = 'block';
                            addForm.style.display = 'none';
                            if (mentorDropdown && mentorDropdown.options.length <= 1) {
                                loadMentors();
                            }
                        } else {
                            selectForm.style.display = 'none';
                            addForm.style.display = 'block';
                        }
                    });
                }
            }

            async function loadMentors() {
                const mentorDropdown = document.getElementById('id_mentor');
                if (!mentorDropdown) return;

                mentorDropdown.innerHTML = '<option value="">Memuat daftar mentor...</option>';
                mentorDropdown.disabled = true;

                try {
                    const response = await fetch('/api/mentors');
                    const data = await response.json();

                    mentorDropdown.innerHTML = '<option value="">Pilih Mentor</option>';
                    mentorDropdown.disabled = false;

                    data.forEach(mentor => {
                        const option = document.createElement('option');
                        option.value = mentor.id_mentor || mentor.id;
                        option.textContent = `${mentor.nama_mentor} - ${mentor.jabatan_mentor}`;
                        option.dataset.mentor = JSON.stringify({
                            nama_mentor: mentor.nama_mentor,
                            jabatan_mentor: mentor.jabatan_mentor,
                            nomor_rekening_mentor: mentor.nomor_rekening,
                            npwp_mentor: mentor.npwp_mentor
                        });
                        mentorDropdown.appendChild(option);
                    });

                    // Set value dari data peserta jika ada
                    if (verifiedPeserta && verifiedPeserta.id_mentor) {
                        mentorDropdown.value = verifiedPeserta.id_mentor;
                        if (mentorDropdown.value) {
                            mentorDropdown.dispatchEvent(new Event('change'));
                        }
                    }

                } catch (error) {
                    console.error('Error loading mentors:', error);
                    mentorDropdown.innerHTML = '<option value="">Error loading mentors</option>';
                    mentorDropdown.disabled = false;
                }
            }

            // ============================================
            // LOADING OVERLAY FUNCTIONS
            // ============================================
            function showLoadingOverlay(message = 'Menyimpan data perubahan. Mohon tunggu...') {
                // Hapus overlay sebelumnya jika ada
                hideLoadingOverlay();

                // Buat overlay
                const overlay = document.createElement('div');
                overlay.id = 'loading-overlay';
                overlay.className = 'loading-overlay';
                overlay.innerHTML = `
                    <div class="loading-content">
                        <div class="loading-spinner">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                        <div class="loading-message">
                            <h4><i class="fas fa-clock"></i> Proses Sedang Berjalan</h4>
                            <p>${message}</p>
                            <p class="loading-detail">
                                <i class="fas fa-info-circle"></i>
                                Proses ini mungkin memakan waktu beberapa menit. 
                                <strong>Jangan tutup atau refresh halaman ini.</strong>
                            </p>
                        </div>
                    </div>
                `;

                document.body.appendChild(overlay);
                document.body.style.overflow = 'hidden'; // Mencegah scroll
            }

            function updateLoadingMessage(newMessage) {
                const overlay = document.getElementById('loading-overlay');
                if (overlay) {
                    const messageElement = overlay.querySelector('.loading-message p');
                    if (messageElement) {
                        messageElement.textContent = newMessage;
                    }
                }
            }

            function hideLoadingOverlay() {
                const overlay = document.getElementById('loading-overlay');
                if (overlay) {
                    overlay.remove();
                }
                document.body.style.overflow = ''; // Kembalikan scroll
            }

            // ============================================
            // FUNGSI UNTUK AUTO-FILL PANGKAT
            // ============================================
            function setupPangkatAutoFill() {
                const golonganRuangSelect = document.getElementById('golongan_ruang');
                const pangkatInput = document.getElementById('pangkat');
                const pangkatDescription = document.getElementById('pangkat_description');
                const pangkatDescText = document.getElementById('pangkat_desc_text');

                if (!golonganRuangSelect || !pangkatInput) {
                    console.log('Elemen golongan_ruang atau pangkat tidak ditemukan');
                    return;
                }

                const pangkatMapping = {
                    'II/a': { pangkat: 'Pengatur Muda', description: 'Golongan IIa - Pengatur Muda' },
                    'II/b': { pangkat: 'Pengatur Muda Tingkat I', description: 'Golongan IIb - Pengatur Muda Tingkat I' },
                    'II/c': { pangkat: 'Pengatur', description: 'Golongan IIc - Pengatur' },
                    'II/d': { pangkat: 'Pengatur Tingkat I', description: 'Golongan IId - Pengatur Tingkat I' },
                    'III/a': { pangkat: 'Penata Muda', description: 'Golongan IIIa - Penata Muda' },
                    'III/b': { pangkat: 'Penata Muda Tingkat I', description: 'Golongan IIIb - Penata Muda Tingkat I' },
                    'III/c': { pangkat: 'Penata', description: 'Golongan IIIc - Penata' },
                    'III/d': { pangkat: 'Penata Tingkat I', description: 'Golongan IIId - Penata Tingkat I' },
                    'IV/a': { pangkat: 'Pembina', description: 'Golongan IVa - Pembina' },
                    'IV/b': { pangkat: 'Pembina Tingkat I', description: 'Golongan IVb - Pembina Tingkat I' },
                    'IV/c': { pangkat: 'Pembina Muda', description: 'Golongan IVc - Pembina Muda' },
                    'IV/d': { pangkat: 'Pembina Madya', description: 'Golongan IVd - Pembina Madya' }
                };

                function updatePangkatFromGolongan() {
                    const selectedGolongan = golonganRuangSelect.value;
                    console.log('Golongan dipilih:', selectedGolongan); // Untuk debugging

                    if (selectedGolongan && pangkatMapping[selectedGolongan]) {
                        pangkatInput.value = pangkatMapping[selectedGolongan].pangkat;

                        if (pangkatDescText) {
                            pangkatDescText.textContent = pangkatMapping[selectedGolongan].description;
                        }

                        if (pangkatDescription) {
                            pangkatDescription.style.display = 'block';
                        }
                    } else {
                        pangkatInput.value = '';

                        if (pangkatDescription) {
                            pangkatDescription.style.display = 'none';
                        }
                    }
                }

                // Event listener
                golonganRuangSelect.addEventListener('change', updatePangkatFromGolongan);

                // Inisialisasi pertama kali
                updatePangkatFromGolongan();

                console.log('Auto-fill pangkat sudah di-setup');
            }

            // ============================================
            // FUNGSI HELPER
            // ============================================
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
            }

            function showVerificationError(message) {
                errorMessage.textContent = message;
                verificationError.style.display = 'flex';
                verificationSuccess.style.display = 'none';
                verificationDetails.style.display = 'none';
                verificationAnggaran.style.display = 'none';
                verificationResult.style.display = 'block';
            }

            // ============================================
            // NOTIFICATION FUNCTIONS
            // ============================================
            function showSuccessMessage(message) {
                const notification = document.createElement('div');
                notification.className = 'notification success';
                notification.innerHTML = `
                    <div class="notification-content">
                        <i class="fas fa-check-circle"></i>
                        <span>${message}</span>
                    </div>
                `;

                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.classList.add('show');
                }, 10);

                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 3000);
            }

            function showErrorMessage(message) {
                const notification = document.createElement('div');
                notification.className = 'notification error';
                notification.innerHTML = `
                    <div class="notification-content">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>${message}</span>
                    </div>
                `;

                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.classList.add('show');
                }, 10);

                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 5000);
            }

            // ============================================
            // FORM SUBMISSION - FIXED VERSION
            // ============================================
            document.getElementById('pendaftaranForm').addEventListener('submit', async function (e) {
                e.preventDefault();

                // Validasi apakah form sudah dalam proses submit
                if (this.classList.contains('submitting')) {
                    console.log('Form sedang dalam proses submit, mohon tunggu...');
                    return false;
                }

                // Tandai form sedang dalam proses submit
                this.classList.add('submitting');

                const submitBtn = document.getElementById('submit-form');
                const originalText = submitBtn.innerHTML;

                // Show loading state dengan pesan lebih jelas
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan Perubahan...';
                submitBtn.disabled = true;

                // Tampilkan overlay loading dengan pesan
                showLoadingOverlay('Menyimpan data perubahan. Proses ini mungkin memakan waktu beberapa menit. Mohon tidak menutup halaman ini.');

                // Validasi client-side
                const requiredFields = this.querySelectorAll('[required]');
                let hasEmptyRequired = false;

                document.querySelectorAll('.client-error').forEach(el => el.remove());

                requiredFields.forEach(field => {
                    if (!field.value && field.type !== 'file' && !field.disabled) {
                        hasEmptyRequired = true;
                        field.classList.add('error');

                        const formGroup = field.closest('.form-group');
                        if (formGroup) {
                            const errorMsg = document.createElement('small');
                            errorMsg.className = 'text-danger client-error';
                            errorMsg.textContent = 'Field ini wajib diisi';
                            formGroup.appendChild(errorMsg);
                        }
                    }
                });

                if (hasEmptyRequired) {
                    // Reset submit state
                    this.classList.remove('submitting');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    hideLoadingOverlay();

                    const firstError = document.querySelector('.error');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    showErrorMessage('Mohon lengkapi semua field yang wajib diisi.');
                    return false;
                }

                // Validasi khusus untuk file upload
                const fileInputs = this.querySelectorAll('input[type="file"]');
                let hasOversizedFile = false;
                const maxSize = 1 * 1024 * 1024; // 1MB

                fileInputs.forEach(input => {
                    if (input.files.length > 0) {
                        const file = input.files[0];
                        if (file.size > maxSize) {
                            hasOversizedFile = true;
                            input.classList.add('error');

                            const formGroup = input.closest('.form-group');
                            if (formGroup) {
                                const existingError = formGroup.querySelector('.file-size-error');
                                if (!existingError) {
                                    const errorMsg = document.createElement('small');
                                    errorMsg.className = 'text-danger file-size-error';
                                    errorMsg.innerHTML = `
                                        <i class="fas fa-exclamation-circle"></i> 
                                        Ukuran file (${formatFileSize(file.size)}) melebihi batas maksimal 1 MB
                                    `;
                                    formGroup.appendChild(errorMsg);
                                }
                            }
                        }
                    }
                });

                if (hasOversizedFile) {
                    // Reset submit state
                    this.classList.remove('submitting');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    hideLoadingOverlay();

                    const firstError = this.querySelector('.file-size-error');
                    if (firstError) {
                        firstError.closest('.form-group').scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    showErrorMessage('Ada file yang melebihi ukuran maksimal 1 MB. Silakan periksa kembali file yang Anda upload.');
                    return false;
                }

                // Collect form data
                const formData = new FormData(this);

                // Add hidden data
                if (verifiedPeserta && verifiedPeserta.id) {
                    formData.append('peserta_id', verifiedPeserta.id);
                }
                if (pendaftaranData && pendaftaranData.id) {
                    formData.append('pendaftaran_id', pendaftaranData.id);
                }

                // Add additional flag untuk tracking
                formData.append('is_update', 'true');

                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                try {
                    // Set timeout yang lebih lama untuk proses upload
                    const controller = new AbortController();
                    const timeoutId = setTimeout(() => controller.abort(), 300000); // 5 menit timeout

                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        signal: controller.signal,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    clearTimeout(timeoutId);

                    const data = await response.json();

                    // Reset submit state
                    this.classList.remove('submitting');

                    if (data.success) {
                        // Update pesan loading
                        updateLoadingMessage('Data berhasil disimpan! Mengarahkan ke halaman detail...');

                        setTimeout(() => {
                            hideLoadingOverlay();
                            showSuccessMessage('Data berhasil diperbarui!');

                            const urlWithId = data.redirect_url + '?id=' + data.pendaftaran_id;

                            // Delay sedikit sebelum redirect
                            setTimeout(() => {
                                window.location.href = urlWithId;
                            }, 1000);

                        }, 1500);

                    } else {
                        // Reset UI
                        hideLoadingOverlay();
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;

                        // Clear previous errors
                        document.querySelectorAll('.server-error').forEach(el => el.remove());
                        document.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
                        document.querySelectorAll('.form-file-label').forEach(label => {
                            label.style.borderColor = '';
                            label.style.background = '';
                        });

                        // Display new errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                let input = document.querySelector(`[name="${field}"]`);

                                if (!input) input = document.querySelector(`[name="${field}[]"]`);
                                if (!input) input = document.querySelector(`#${field}`);

                                if (input) {
                                    input.classList.add('error');

                                    if (input.type === 'file') {
                                        const fileLabel = input.closest('.form-file')?.querySelector('.form-file-label');
                                        if (fileLabel) {
                                            fileLabel.style.borderColor = 'var(--danger-color)';
                                            fileLabel.style.background = 'rgba(245, 101, 101, 0.05)';
                                        }
                                    }

                                    let formGroup = input.closest('.form-group');
                                    if (!formGroup) {
                                        formGroup = input.closest('.checkbox-group') ||
                                            input.closest('.form-check') ||
                                            input.parentElement;
                                    }

                                    if (formGroup) {
                                        const existingError = formGroup.querySelector('.server-error');
                                        if (existingError) existingError.remove();

                                        const errorMsg = document.createElement('small');
                                        errorMsg.className = 'text-danger server-error';
                                        errorMsg.textContent = data.errors[field][0];
                                        formGroup.appendChild(errorMsg);
                                    }
                                }
                            });

                            const firstError = document.querySelector('.error');
                            if (firstError) {
                                setTimeout(() => {
                                    firstError.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'center'
                                    });
                                }, 300);
                            }
                        } else if (data.message) {
                            showErrorMessage(data.message);
                        }
                    }

                } catch (error) {
                    // Reset submit state
                    this.classList.remove('submitting');
                    hideLoadingOverlay();
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;

                    if (error.name === 'AbortError') {
                        showErrorMessage('Proses upload terlalu lama. Silakan coba lagi dengan file yang lebih kecil atau koneksi yang lebih stabil.');
                    } else {
                        console.error('AJAX Error:', error);
                        showErrorMessage('Terjadi kesalahan jaringan. Silakan coba lagi.');
                    }
                }
            });

            // ============================================
            // PREVENT PAGE REFRESH/CLOSE DURING SUBMISSION
            // ============================================
            window.addEventListener('beforeunload', function (e) {
                const form = document.getElementById('pendaftaranForm');
                if (form && form.classList.contains('submitting')) {
                    e.preventDefault();
                    e.returnValue = 'Data sedang disimpan. Apakah Anda yakin ingin meninggalkan halaman ini?';
                    return 'Data sedang disimpan. Apakah Anda yakin ingin meninggalkan halaman ini?';
                }
            });

            // ============================================
            // CLEAR ERRORS ON INPUT
            // ============================================
            document.addEventListener('input', function (e) {
                if (e.target.matches('input, select, textarea')) {
                    e.target.classList.remove('error');
                    const formGroup = e.target.closest('.form-group');
                    if (formGroup) {
                        const errorMsg = formGroup.querySelector('.server-error, .client-error');
                        if (errorMsg) errorMsg.remove();
                    }

                    if (e.target.type === 'file') {
                        const fileLabel = e.target.closest('.form-file')?.querySelector('.form-file-label');
                        if (fileLabel) {
                            fileLabel.style.borderColor = '';
                            fileLabel.style.background = '';
                        }
                    }
                }
            });

            document.addEventListener('change', function (e) {
                if (e.target.matches('input[type="file"]')) {
                    e.target.classList.remove('error');
                    const formGroup = e.target.closest('.form-group');
                    if (formGroup) {
                        const errorMsg = formGroup.querySelector('.server-error, .client-error');
                        if (errorMsg) errorMsg.remove();
                    }

                    const fileLabel = e.target.closest('.form-file')?.querySelector('.form-file-label');
                    if (fileLabel) {
                        fileLabel.style.borderColor = '';
                        fileLabel.style.background = '';
                    }
                }
            });

            // ============================================
            // FILE VALIDATION
            // ============================================
            document.addEventListener('change', function (e) {
                if (e.target.matches('input[type="file"]')) {
                    const fileInput = e.target;
                    const file = fileInput.files[0];

                    if (!file) return;

                    const maxSize = 1 * 1024 * 1024; // 1MB dalam bytes
                    const fileSize = file.size;
                    const fileName = file.name;
                    const fileNameDisplay = fileInput.closest('.form-file')?.querySelector('.form-file-name');
                    const formGroup = fileInput.closest('.form-group');

                    // Remove previous error messages
                    const existingError = formGroup?.querySelector('.file-size-error');
                    if (existingError) {
                        existingError.remove();
                    }

                    // Remove error styling
                    fileInput.classList.remove('error');
                    const fileLabel = fileInput.closest('.form-file')?.querySelector('.form-file-label');
                    if (fileLabel) {
                        fileLabel.style.borderColor = '';
                        fileLabel.style.background = '';
                    }

                    // Check file size
                    if (fileSize > maxSize) {
                        // File terlalu besar
                        fileInput.value = ''; // Clear input
                        fileInput.classList.add('error');

                        if (fileLabel) {
                            fileLabel.style.borderColor = 'var(--danger-color)';
                            fileLabel.style.background = 'rgba(245, 101, 101, 0.05)';
                        }

                        // Update file name display
                        if (fileNameDisplay) {
                            fileNameDisplay.innerHTML = `
                                <span class="no-file text-danger">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    File terlalu besar (${formatFileSize(fileSize)})
                                </span>
                            `;
                        }

                        // Add error message
                        if (formGroup) {
                            const errorMsg = document.createElement('small');
                            errorMsg.className = 'text-danger file-size-error';
                            errorMsg.innerHTML = `
                                <i class="fas fa-exclamation-circle"></i> 
                                Ukuran file "${fileName}" (${formatFileSize(fileSize)}) melebihi batas maksimal 1 MB
                            `;
                            formGroup.appendChild(errorMsg);
                        }

                        // Show notification
                        showErrorMessage(`File "${fileName}" terlalu besar! Ukuran maksimal 1 MB. Ukuran file Anda: ${formatFileSize(fileSize)}`);

                    } else {
                        // File valid
                        if (fileNameDisplay) {
                            fileNameDisplay.innerHTML = `
                                <span style="color: var(--success-color);">
                                    <i class="fas fa-check-circle"></i> 
                                    ${fileName} (${formatFileSize(fileSize)})
                                </span>
                            `;
                        }

                        if (fileLabel) {
                            fileLabel.style.borderColor = 'var(--success-color)';
                            fileLabel.style.background = 'rgba(72, 187, 120, 0.05)';
                        }
                    }
                }
            });

            // Handler untuk tombol "Ganti File"
            document.addEventListener('click', function (e) {
                if (e.target.matches('.btn-change-file') || e.target.closest('.btn-change-file')) {
                    const btn = e.target.closest('.btn-change-file');
                    const targetName = btn.getAttribute('data-target');
                    const fileInput = document.querySelector(`input[name="${targetName}"]`);

                    if (fileInput) {
                        // Reset file input
                        fileInput.value = '';

                        // Update UI
                        const fileNameDisplay = fileInput.closest('.form-file')?.querySelector('.form-file-name');
                        if (fileNameDisplay) {
                            fileNameDisplay.innerHTML = '<span class="no-file">Belum ada file dipilih</span>';
                        }

                        // Remove error styling
                        fileInput.classList.remove('error');
                        const fileLabel = fileInput.closest('.form-file')?.querySelector('.form-file-label');
                        if (fileLabel) {
                            fileLabel.style.borderColor = '';
                            fileLabel.style.background = '';
                        }

                        // Remove error message
                        const formGroup = fileInput.closest('.form-group');
                        const errorMsg = formGroup?.querySelector('.file-size-error');
                        if (errorMsg) {
                            errorMsg.remove();
                        }

                        // Trigger file input click
                        fileInput.click();
                    }
                }
            });

            // ============================================
            // ADD NOTIFICATION STYLES
            // ============================================
            const notificationStyles = `
                .notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 9999;
                    min-width: 300px;
                    max-width: 400px;
                    background: white;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    padding: 15px 20px;
                    transform: translateX(400px);
                    transition: transform 0.3s ease;
                }

                .notification.show {
                    transform: translateX(0);
                }

                .notification.success {
                    border-left: 4px solid var(--success-color);
                }

                .notification.error {
                    border-left: 4px solid var(--danger-color);
                }

                .notification-content {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }

                .notification-content i {
                    font-size: 1.2rem;
                }

                .notification.success .notification-content i {
                    color: var(--success-color);
                }

                .notification.error .notification-content i {
                    color: var(--danger-color);
                }

                .notification-content span {
                    flex: 1;
                    font-size: 0.95rem;
                }

                /* Disable all form inputs during submission */
                .submitting * {
                    pointer-events: none !important;
                }

                .submitting .form-input:disabled,
                .submitting .form-select:disabled {
                    background-color: #f8f9fa;
                    opacity: 0.7;
                }
            `;

            const styleSheet = document.createElement("style");
            styleSheet.textContent = notificationStyles;
            document.head.appendChild(styleSheet);
        });
    </script>
@endpush