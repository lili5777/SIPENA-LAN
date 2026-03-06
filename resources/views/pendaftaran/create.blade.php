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
                                        <div class="info-item" id="verification-pic" style="display: none;">
                                            <span class="info-label">Nama PIC:</span>
                                            <span class="info-value" id="detail-pic"></span>
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
                            <button type="submit" class="btn btn-success" id="submit-form"
                                data-submitting-text="<i class='fas fa-spinner fa-spin'></i> Menyimpan...">
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

            /* Extended tokens — dipakai searchable instansi select */
            --gray-50:  #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
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

        .progress-step.active { opacity: 1; }

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

        .step-label { font-size: 0.9rem; font-weight: 500; }

        /* Form Section */
        .form-section { padding: 40px 0 80px 0; }

        .form-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .form-step { padding: 40px; display: none; }
        .form-step.active { display: block; animation: fadeIn 0.5s ease; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .step-header { text-align: center; margin-bottom: 40px; }

        .step-title {
            font-size: 1.8rem;
            color: var(--primary-color);
            margin-bottom: 10px;
            font-weight: 600;
        }

        .step-description { color: var(--gray-color); margin-bottom: 20px; }

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

        .training-icon { font-size: 2.5rem; color: var(--primary-color); margin-bottom: 15px; }
        .training-name { font-size: 1.2rem; color: var(--dark-color); margin-bottom: 5px; font-weight: 600; }
        .training-code { font-size: 0.9rem; color: var(--gray-color); margin-bottom: 20px; }

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

        .training-select-btn:hover { background: var(--secondary-color); }

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
        .form-group { margin-bottom: 20px; }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
        }

        .form-label.required::after { content: " *"; color: var(--danger-color); }

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

        .form-textarea { min-height: 100px; resize: vertical; }

        .form-hint {
            font-size: 0.85rem;
            color: var(--gray-color);
            margin-top: 5px;
            display: block;
        }

        .form-file { position: relative; overflow: hidden; }

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

        .error-message {
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-weight: 500;
        }

        /* Angkatan Info */
        .angkatan-info { margin-top: 30px; }

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

        .info-details { display: grid; gap: 10px; }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-label { font-weight: 500; color: var(--dark-color); }
        .info-value { color: var(--gray-color); }
        .text-success { color: var(--success-color) !important; }

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
        .dynamic-form-container { margin-top: 30px; }

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

        .form-section-header:first-child { margin-top: 0; }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
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

        .btn-primary { background: var(--accent-color); color: white; }
        .btn-primary:hover { background: var(--secondary-color); transform: translateY(-2px); }
        .btn-primary:disabled { background: #cbd5e0; cursor: not-allowed; transform: none; }
        .btn-secondary { background: #e2e8f0; color: var(--dark-color); }
        .btn-secondary:hover { background: #cbd5e0; }
        .btn-success { background: var(--success-color); color: white; }
        .btn-success:hover { background: #38a169; transform: translateY(-2px); }

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

        .alert i { font-size: 1.2rem; flex-shrink: 0; }

        .verification-result { margin-top: 20px; }

        /* ===================================================
           INSTANSI SEARCHABLE SELECT STYLES
           (Dibutuhkan oleh partial yang di-inject via AJAX)
        =================================================== */
        .instansi-select-wrapper-partial { position: relative; }

        .instansi-select-wrapper-partial #instansi_trigger_partial:hover {
            border-color: var(--primary-color);
        }

        #instansi_list_partial::-webkit-scrollbar       { width: 6px; }
        #instansi_list_partial::-webkit-scrollbar-track { background: var(--gray-100); }
        #instansi_list_partial::-webkit-scrollbar-thumb {
            background: var(--gray-400);
            border-radius: 3px;
        }
        #instansi_list_partial::-webkit-scrollbar-thumb:hover { background: var(--gray-500); }

        #asal_instansi_search_partial:focus {
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 3px rgba(26, 58, 108, 0.1);
        }
        /* ===================================================
           END INSTANSI SEARCHABLE SELECT STYLES
        =================================================== */

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

        .file-info i { font-size: 1rem; }

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

        .btn-change-file:hover { background: var(--accent-color); color: white; }
        .no-file { color: var(--gray-color); font-style: italic; }

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

        .crop-controls .btn { padding: 6px 12px; font-size: 0.85rem; }

        #crop-preview-container {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        #cropped-preview { border-radius: 4px; }

        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
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
            0%   { box-shadow: 0 0 0 0 rgba(66, 153, 225, 0.4); }
            70%  { box-shadow: 0 0 0 20px rgba(66, 153, 225, 0); }
            100% { box-shadow: 0 0 0 0 rgba(66, 153, 225, 0); }
        }

        .loading-spinner { font-size: 3rem; color: var(--primary-color); margin-bottom: 20px; }

        .loading-spinner i { animation: spin 1s linear infinite; }

        @keyframes spin {
            0%   { transform: rotate(0deg); }
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

        .loading-message p { color: var(--gray-color); line-height: 1.6; margin-bottom: 10px; }

        .loading-detail {
            background: rgba(245, 158, 11, 0.1);
            border-left: 3px solid var(--warning-color);
            padding: 10px 15px;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 0.9rem;
            text-align: left;
        }

        .loading-detail i { color: var(--warning-color); margin-right: 8px; }

        .submitting * { pointer-events: none !important; }

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

        #ndh-hint { display: flex; align-items: center; gap: 5px; margin-top: 8px; }
        #ndh-info  { font-weight: 500; }

        #search-mentor:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }

        #search-info {
            padding: 8px 12px;
            background: rgba(66, 153, 225, 0.1);
            border-left: 3px solid var(--accent-color);
            border-radius: 4px;
            animation: fadeIn 0.3s ease;
        }

        #mentor-loading {
            padding: 10px;
            text-align: center;
            background: rgba(245, 158, 11, 0.1);
            border-radius: 4px;
        }

        #mentor-not-found {
            padding: 10px;
            text-align: center;
            background: rgba(245, 101, 101, 0.1);
            border-radius: 4px;
            border-left: 3px solid var(--danger-color);
        }

        /* Form input disabled */
        .form-input:disabled {
            background-color: #f7fafc;
            cursor: not-allowed;
            opacity: 0.7;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-hero      { padding: 40px 0; }
            .form-hero-title { font-size: 2rem; }
            .progress-indicator { gap: 20px; }
            .progress-step  { gap: 5px; }
            .step-label     { font-size: 0.8rem; }
            .form-step      { padding: 20px; }
            .step-title     { font-size: 1.5rem; }
            .training-options { grid-template-columns: 1fr; }
            .form-row       { grid-template-columns: 1fr; }
            .step-navigation { flex-direction: column; gap: 10px; }
            .step-navigation .btn { width: 100%; }
            .selected-info  { flex-direction: column; align-items: center; }
            .info-item      { flex-direction: column; align-items: flex-start; gap: 5px; }
            .info-label, .info-value { width: 100%; }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ============================================
            // VARIABLES & ELEMENTS
            // ============================================
            const step1Content       = document.getElementById('step1-content');
            const step2Content       = document.getElementById('step2-content');
            const step3Content       = document.getElementById('step3-content');
            const step1Indicator     = document.getElementById('step1');
            const step2Indicator     = document.getElementById('step2');
            const step3Indicator     = document.getElementById('step3');
            const jenisPelatihanInput = document.getElementById('id_jenis_pelatihan');
            const selectedTrainingName = document.getElementById('selected-training-name');
            const currentTrainingName  = document.getElementById('current-training-name');
            const currentNipNrp        = document.getElementById('current-nip-nrp');
            const currentAngkatanName  = document.getElementById('current-angkatan-name');
            const dynamicFormContainer = document.getElementById('dynamic-form-container');
            const backToStep1Btn  = document.getElementById('back-to-step1');
            const backToStep2Btn  = document.getElementById('back-to-step2');
            const nextToStep3Btn  = document.getElementById('next-to-step3');
            const verifyNipBtn    = document.getElementById('verify-nip-btn');
            const nipNrpInput     = document.getElementById('nip_nrp');
            const verificationResult  = document.getElementById('verification-result');
            const verificationSuccess = document.getElementById('verification-success');
            const verificationError   = document.getElementById('verification-error');
            const verificationDetails = document.getElementById('verification-details');
            const verificationAnggaran= document.getElementById('verification-anggaran');
            const successMessage = document.getElementById('success-message');
            const errorMessage   = document.getElementById('error-message');
            const detailNama     = document.getElementById('detail-nama');
            const detailAngkatan = document.getElementById('detail-angkatan');

            let selectedTraining  = null;
            let verifiedPeserta   = null;
            let pendaftaranData   = null;

            function fieldLabel(field) {
                const labels = {
                    'nama_lengkap'               : 'Nama Lengkap',
                    'nip_nrp'                    : 'NIP/NRP',
                    'nama_panggilan'             : 'Nama Panggilan',
                    'jenis_kelamin'              : 'Jenis Kelamin',
                    'agama'                      : 'Agama',
                    'tempat_lahir'               : 'Tempat Lahir',
                    'tanggal_lahir'              : 'Tanggal Lahir',
                    'alamat_rumah'               : 'Alamat Rumah',
                    'email_pribadi'              : 'Email Pribadi',
                    'nomor_hp'                   : 'Nomor HP',
                    'pendidikan_terakhir'        : 'Pendidikan Terakhir',
                    'bidang_studi'               : 'Bidang Studi',
                    'bidang_keahlian'            : 'Bidang Keahlian',
                    'status_perkawinan'          : 'Status Perkawinan',
                    'nama_pasangan'              : 'Nama Pasangan',
                    'olahraga_hobi'              : 'Olahraga/Hobi',
                    'perokok'                    : 'Status Perokok',
                    'ukuran_kaos'                : 'Ukuran Kaos',
                    'ukuran_celana'              : 'Ukuran Celana',
                    'ukuran_training'            : 'Ukuran Baju Taktikal',
                    'kondisi_peserta'            : 'Kondisi Peserta',
                    'asal_instansi'              : 'Asal Instansi',
                    'unit_kerja'                 : 'Unit Kerja',
                    'id_provinsi'                : 'Provinsi',
                    'id_kabupaten_kota'          : 'Kabupaten/Kota',
                    'alamat_kantor'              : 'Alamat Kantor',
                    'jabatan'                    : 'Jabatan',
                    'pangkat'                    : 'Pangkat',
                    'golongan_ruang'             : 'Golongan Ruang',
                    'eselon'                     : 'Eselon',
                    'nomor_sk_cpns'              : 'Nomor SK CPNS',
                    'tanggal_sk_cpns'            : 'Tanggal SK CPNS',
                    'nomor_sk_terakhir'          : 'Nomor SK Jabatan Terakhir',
                    'tanggal_sk_jabatan'         : 'Tanggal SK Jabatan',
                    'ndh'                        : 'NDH',
                    'sudah_ada_mentor'           : 'Status Mentor',
                    'mentor_mode'                : 'Mode Mentor',
                    'id_mentor'                  : 'Pilih Mentor',
                    'nama_mentor_baru'           : 'Nama Mentor Baru',
                    'nip_mentor_baru'            : 'NIP Mentor Baru',
                    'jabatan_mentor_baru'        : 'Jabatan Mentor Baru',
                    'golongan_mentor_baru'       : 'Golongan Mentor Baru',
                    'pangkat_mentor_baru'        : 'Pangkat Mentor Baru',
                    'nomor_rekening_mentor_baru' : 'Nomor Rekening Mentor',
                    'npwp_mentor_baru'           : 'NPWP Mentor',
                    'file_ktp'                   : 'File KTP',
                    'file_pas_foto_cropped'      : 'Foto Peserta',
                    'file_sk_cpns'               : 'File SK CPNS',
                    'file_spmt'                  : 'File SPMT',
                    'file_skp'                   : 'File SKP',
                    'file_sk_jabatan'            : 'File SK Jabatan',
                    'file_sk_pangkat'            : 'File SK Pangkat',
                    'file_surat_kesediaan'       : 'File Surat Kesediaan',
                    'file_surat_tugas'           : 'File Surat Tugas',
                    'file_pakta_integritas'      : 'File Pakta Integritas',
                    'file_surat_sehat'           : 'File Surat Sehat',
                    'file_surat_bebas_narkoba'   : 'File Surat Bebas Narkoba',
                    'file_persetujuan_mentor'    : 'File Persetujuan Mentor',
                };
                return labels[field] || field.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
            }

            // ============================================
            // STEP 1: PILIH PELATIHAN
            // ============================================
            document.querySelectorAll('.training-card').forEach(card => {
                card.addEventListener('click', function () {
                    document.querySelectorAll('.training-card').forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');

                    selectedTraining = {
                        id:   this.getAttribute('data-id'),
                        kode: this.getAttribute('data-kode'),
                        name: this.querySelector('.training-name').textContent
                    };

                    jenisPelatihanInput.value = selectedTraining.id;
                    selectedTrainingName.textContent = selectedTraining.name;
                    currentTrainingName.textContent  = selectedTraining.name;

                    moveToStep(2);
                    resetVerification();
                });
            });

            // ============================================
            // STEP 2: VERIFIKASI NIP/NRP
            // ============================================
            function resetVerification() {
                verificationResult.style.display   = 'none';
                verificationSuccess.style.display  = 'none';
                verificationError.style.display    = 'none';
                verificationDetails.style.display  = 'none';
                verificationAnggaran.style.display = 'none';
                nextToStep3Btn.disabled = true;
                verifiedPeserta  = null;
                pendaftaranData  = null;
                nipNrpInput.value = '';
                dynamicFormContainer.innerHTML = '';
            }

            verifyNipBtn.addEventListener('click', async function () {
                if (!selectedTraining || !nipNrpInput.value.trim()) {
                    showVerificationError('Silakan pilih pelatihan dan masukkan NIP/NRP');
                    return;
                }

                const originalText = verifyNipBtn.innerHTML;
                verifyNipBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memverifikasi...';
                verifyNipBtn.disabled  = true;

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
                        verifiedPeserta = data.peserta;
                        pendaftaranData = data.pendaftaran;

                        currentNipNrp.textContent = verifiedPeserta.nip_nrp;
                        currentAngkatanName.textContent = pendaftaranData.angkatan
                            ? `${pendaftaranData.angkatan.nama_angkatan} (${pendaftaranData.angkatan.tahun}) (${pendaftaranData.angkatan.kategori})`
                            : 'Angkatan tidak tersedia';

                        successMessage.textContent   = data.message;
                        detailNama.textContent       = verifiedPeserta.nama_lengkap;
                        detailAngkatan.textContent   = pendaftaranData.angkatan
                            ? `${pendaftaranData.angkatan.nama_angkatan} ${pendaftaranData.angkatan.tahun} (${pendaftaranData.angkatan.kategori})`
                            : 'Tidak tersedia';

                        const detailPic       = document.getElementById('detail-pic');
                        const verificationPic = document.getElementById('verification-pic');
                        if (data.pic) {
                            detailPic.innerHTML = `<strong>${data.pic.nama}</strong><br><small>${data.pic.no_telp}</small>`;
                            verificationPic.style.display = 'flex';
                        } else {
                            detailPic.textContent = 'PIC belum ditentukan';
                            verificationPic.style.display = 'flex';
                        }

                        verificationSuccess.style.display  = 'flex';
                        verificationError.style.display    = 'none';
                        verificationDetails.style.display  = 'flex';
                        verificationAnggaran.style.display = 'flex';
                        verificationResult.style.display   = 'block';
                        nextToStep3Btn.disabled = false;

                    } else {
                        errorMessage.textContent = data.message;
                        verificationError.style.display    = 'flex';
                        verificationSuccess.style.display  = 'none';
                        verificationDetails.style.display  = 'none';
                        verificationAnggaran.style.display = 'none';
                        verificationResult.style.display   = 'block';
                        nextToStep3Btn.disabled = true;
                    }
                } catch (error) {
                    console.error('Verification error:', error);
                    showVerificationError('Terjadi kesalahan jaringan. Silakan coba lagi.');
                } finally {
                    verifyNipBtn.innerHTML = originalText;
                    verifyNipBtn.disabled  = false;
                }
            });

            // ============================================
            // NAVIGATION
            // ============================================
            function moveToStep(step) {
                [step1Indicator, step2Indicator, step3Indicator].forEach(i => i.classList.remove('active'));
                document.getElementById(`step${step}`).classList.add('active');

                [step1Content, step2Content, step3Content].forEach(c => c.classList.remove('active'));
                document.getElementById(`step${step}-content`).classList.add('active');

                if (step === 3 && verifiedPeserta) loadFormPartial();

                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            backToStep1Btn.addEventListener('click', () => { moveToStep(1); resetVerification(); });
            backToStep2Btn.addEventListener('click', () => moveToStep(2));
            nextToStep3Btn.addEventListener('click', () => { if (verifiedPeserta && pendaftaranData) moveToStep(3); });

            // ============================================
            // STEP 3: LOAD DYNAMIC FORM
            // ============================================
            async function loadFormPartial() {
                if (!selectedTraining || !verifiedPeserta || !pendaftaranData) return;

                dynamicFormContainer.innerHTML = `
                    <div style="text-align:center;padding:40px;">
                        <i class="fas fa-spinner fa-spin" style="font-size:2rem;color:var(--primary-color);"></i>
                        <p style="margin-top:15px;color:var(--gray-color);">Menyiapkan formulir pembaruan data...</p>
                    </div>`;

                try {
                    const response = await fetch(`/form-partial/${selectedTraining.kode}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            peserta_data:     JSON.stringify(verifiedPeserta),
                            pendaftaran_data: JSON.stringify(pendaftaranData)
                        })
                    });

                    if (!response.ok) throw new Error('Network response was not ok');

                    const html = await response.text();
                    dynamicFormContainer.innerHTML = html;

                    // Jalankan semua <script> yang ada di dalam HTML partial
                    dynamicFormContainer.querySelectorAll('script').forEach(oldScript => {
                        const newScript = document.createElement('script');
                        newScript.textContent = oldScript.textContent;
                        document.body.appendChild(newScript);
                        oldScript.remove();
                    });

                    // Setup interaksi form
                    setupFormInteractions();
                    setupPangkatAutoFill();

                } catch (error) {
                    console.error('Error loading form partial:', error);
                    dynamicFormContainer.innerHTML = `
                        <div class="alert alert-info" style="border-color:var(--danger-color);color:#c53030;background:rgba(245,101,101,0.1);">
                            <i class="fas fa-exclamation-circle"></i>
                            Gagal memuat formulir. Silakan coba lagi.
                        </div>`;
                }
            }

            // ============================================
            // CROP FOTO 3×4
            // ============================================
            let cropper = null;
            let originalImageFile = null;

            function setupPhotoCropping() {
                const fileInput       = document.getElementById('file_pas_foto');
                const uploadContainer = document.getElementById('upload-container');
                const cropContainer   = document.getElementById('crop-container');
                const previewContainer= document.getElementById('crop-preview-container');
                const cropImage       = document.getElementById('crop-image');
                const croppedPreview  = document.getElementById('cropped-preview');
                const cropDataInput   = document.getElementById('crop_data');
                const croppedInput    = document.getElementById('file_pas_foto_cropped');
                const changePhotoBtn  = document.getElementById('change-photo');
                const fileNameDisplay = document.getElementById('file-name-display');

                const existingPhotoContainer = document.getElementById('existing-photo-container');
                if (existingPhotoContainer) {
                    const changeBtn = document.getElementById('btn-change-photo-existing');
                    if (changeBtn) {
                        changeBtn.addEventListener('click', function () {
                            existingPhotoContainer.style.display = 'none';
                            if (uploadContainer) uploadContainer.style.display = 'block';
                            if (fileInput)    fileInput.value    = '';
                            if (cropDataInput) cropDataInput.value = '';
                            if (croppedInput)  croppedInput.value  = '';
                            if (fileNameDisplay) fileNameDisplay.innerHTML = '<span class="no-file">Belum ada file dipilih</span>';
                        });
                    }
                }

                if (fileInput) {
                    fileInput.addEventListener('change', function (e) {
                        if (this.files && this.files[0]) handleFileSelect(this.files[0]);
                    });
                }

                function handleFileSelect(file) {
                    if (!file.type.match('image.*')) { showErrorMessage('File harus berupa gambar (JPG/PNG)'); return; }
                    if (file.size > 1 * 1024 * 1024) { showErrorMessage('Ukuran file maksimal 1MB'); return; }
                    originalImageFile = file;
                    if (fileNameDisplay) fileNameDisplay.innerHTML = `<span style="color:var(--warning-color);"><i class="fas fa-crop-alt"></i> ${file.name} (${formatFileSize(file.size)})</span>`;
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        if (uploadContainer) uploadContainer.style.display = 'none';
                        if (cropContainer)   cropContainer.style.display   = 'block';
                        if (cropImage) {
                            cropImage.src = e.target.result;
                            cropImage.onload = function () { initCropper(); };
                        }
                    };
                    reader.readAsDataURL(file);
                }

                function initCropper() {
                    if (cropper) cropper.destroy();
                    if (cropImage) {
                        cropper = new Cropper(cropImage, {
                            aspectRatio: 3 / 4, viewMode: 1, autoCropArea: 0.8,
                            movable: true, rotatable: true, scalable: true, zoomable: true,
                            zoomOnTouch: true, zoomOnWheel: true,
                            cropBoxMovable: true, cropBoxResizable: true,
                            toggleDragModeOnDblclick: false,
                            minCropBoxWidth: 100, minCropBoxHeight: 133,
                            ready: function () { console.log('Cropper siap'); }
                        });
                        setupCropControls();
                    }
                }

                function setupCropControls() {
                    document.getElementById('crop-zoom-in')?.addEventListener('click',    () => cropper.zoom(0.1));
                    document.getElementById('crop-zoom-out')?.addEventListener('click',   () => cropper.zoom(-0.1));
                    document.getElementById('crop-rotate-left')?.addEventListener('click',() => cropper.rotate(-45));
                    document.getElementById('crop-rotate-right')?.addEventListener('click',()=> cropper.rotate(45));
                    document.getElementById('crop-reset')?.addEventListener('click',      () => cropper.reset());
                    document.getElementById('crop-confirm')?.addEventListener('click',    () => cropAndPreview());
                    document.getElementById('crop-cancel')?.addEventListener('click',     () => cancelCrop());
                }

                function cropAndPreview() {
                    if (!cropper) { showErrorMessage('Cropper belum diinisialisasi'); return; }
                    const cropData = cropper.getData();
                    if (cropDataInput) cropDataInput.value = JSON.stringify(cropData);
                    const canvas = cropper.getCroppedCanvas({ width: 450, height: 600, imageSmoothingEnabled: true, imageSmoothingQuality: 'high' });
                    const base64Image = canvas.toDataURL('image/jpeg', 0.95);
                    if (croppedPreview) croppedPreview.src = base64Image;
                    if (cropContainer)    cropContainer.style.display    = 'none';
                    if (previewContainer) previewContainer.style.display = 'block';
                    if (croppedInput)     croppedInput.value             = base64Image;
                    if (fileInput)        fileInput.value                = '';
                }

                function cancelCrop() {
                    if (cropper) { cropper.destroy(); cropper = null; }
                    if (cropContainer)    cropContainer.style.display    = 'none';
                    if (uploadContainer)  uploadContainer.style.display  = 'block';
                    if (previewContainer) previewContainer.style.display = 'none';
                    if (fileInput)        fileInput.value = '';
                    if (cropDataInput)    cropDataInput.value = '';
                    if (croppedInput)     croppedInput.value  = '';
                    if (fileNameDisplay)  fileNameDisplay.innerHTML = '<span class="no-file">Belum ada file dipilih</span>';
                }

                if (changePhotoBtn) {
                    changePhotoBtn.addEventListener('click', function () {
                        if (previewContainer) previewContainer.style.display = 'none';
                        if (uploadContainer)  uploadContainer.style.display  = 'block';
                        if (fileInput)        fileInput.value = '';
                        if (cropDataInput)    cropDataInput.value = '';
                        if (croppedInput)     croppedInput.value  = '';
                        if (fileNameDisplay)  fileNameDisplay.innerHTML = '<span class="no-file">Belum ada file dipilih</span>';
                    });
                }
            }

            // ============================================
            // PROVINSI & KABUPATEN
            // ============================================
            async function loadProvinsi() {
                const provinsiSelect = document.querySelector('[name="id_provinsi"]');
                if (!provinsiSelect) return;
                provinsiSelect.innerHTML = '<option value="">Memuat provinsi...</option>';
                try {
                    const response = await fetch('/api/get-provinces');
                    const result   = await response.json();
                    if (result.success) {
                        provinsiSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                        result.data.forEach(prov => {
                            const opt = document.createElement('option');
                            opt.value = prov.id;
                            opt.textContent  = prov.name;
                            opt.dataset.code = prov.code;
                            provinsiSelect.appendChild(opt);
                        });
                        if (verifiedPeserta?.kepegawaian?.id_provinsi) {
                            setTimeout(() => {
                                provinsiSelect.value = verifiedPeserta.kepegawaian.id_provinsi;
                                provinsiSelect.dispatchEvent(new Event('change'));
                            }, 100);
                        }
                    } else throw new Error(result.message);
                } catch (error) {
                    provinsiSelect.innerHTML = '<option value="">Error loading</option>';
                    showErrorMessage('Gagal memuat data provinsi');
                }
            }

            async function loadKabupaten(provId) {
                const kabSelect = document.querySelector('[name="id_kabupaten_kota"]');
                if (!kabSelect) return;
                kabSelect.innerHTML = '<option value="">Memuat kabupaten...</option>';
                kabSelect.disabled  = true;
                try {
                    const response = await fetch(`/api/get-regencies/${provId}`);
                    const result   = await response.json();
                    if (result.success) {
                        kabSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                        kabSelect.disabled  = false;
                        result.data.forEach(kab => {
                            const opt = document.createElement('option');
                            opt.value = kab.id;
                            opt.textContent = kab.name;
                            kabSelect.appendChild(opt);
                        });
                        if (verifiedPeserta?.kepegawaian?.id_kabupaten_kota)
                            kabSelect.value = verifiedPeserta.kepegawaian.id_kabupaten_kota;
                    } else throw new Error(result.message);
                } catch (error) {
                    kabSelect.innerHTML = '<option value="">Error loading</option>';
                    kabSelect.disabled  = false;
                    showErrorMessage('Gagal memuat data kabupaten/kota');
                }
            }

            // ============================================
            // AUTO CAPITALIZATION
            // ============================================
            function capitalizeWords(str) { return str.replace(/\b\w/g, c => c.toUpperCase()); }

            function setupAutoCapitalization() {
                document.querySelectorAll('.capitalize').forEach(input => {
                    input.addEventListener('input',  function () { if (this.value) this.value = capitalizeWords(this.value); });
                    input.addEventListener('change', function () { if (this.value) this.value = capitalizeWords(this.value); });
                    if (input.value) input.value = capitalizeWords(input.value);
                });
                document.querySelectorAll('.lowercase').forEach(input => {
                    input.addEventListener('input',  function () { if (this.value) this.value = this.value.toLowerCase(); });
                    input.addEventListener('change', function () { if (this.value) this.value = this.value.toLowerCase(); });
                    if (input.value) input.value = input.value.toLowerCase();
                });
                document.querySelectorAll('.uppercase').forEach(input => {
                    input.addEventListener('input',  function () { if (this.value) this.value = this.value.toUpperCase(); });
                    input.addEventListener('change', function () { if (this.value) this.value = this.value.toUpperCase(); });
                    if (input.value) input.value = input.value.toUpperCase();
                });
            }

            // ============================================
            // STATUS PERKAWINAN
            // ============================================
            function setupMaritalStatusLogic() {
                const maritalStatusSelect = document.getElementById('status_perkawinan');
                const spouseNameInput     = document.getElementById('nama_pasangan');
                if (!maritalStatusSelect || !spouseNameInput) return;

                function toggleSpouseNameInput() {
                    const isMarried = maritalStatusSelect.value === 'Menikah';
                    spouseNameInput.disabled    = !isMarried;
                    spouseNameInput.required    = isMarried;
                    spouseNameInput.placeholder = isMarried ? 'Masukkan nama istri/suami' : 'Hanya untuk yang berstatus Menikah';
                    if (!isMarried) spouseNameInput.value = '';
                    spouseNameInput.classList.remove('error');
                    spouseNameInput.parentElement.querySelector('.text-danger')?.remove();
                }

                toggleSpouseNameInput();
                maritalStatusSelect.addEventListener('change', toggleSpouseNameInput);
            }

            // ============================================
            // SETUP FORM INTERACTIONS
            // ============================================
            function setupFormInteractions() {
                loadProvinsi();

                document.addEventListener('change', function (e) {
                    if (e.target.name === 'id_provinsi' && e.target.value) loadKabupaten(e.target.value);
                });

                setupAutoCapitalization();
                setupPangkatAutoFill();
                setupPhotoCropping();
                loadAvailableNdh();
                setupNipNormalization();
                setupMentorForm();
                setupMaritalStatusLogic();
                setupPangkatMentorAutoFill();
            }

            // ============================================
            // NDH
            // ============================================
            async function loadAvailableNdh() {
                const ndhSelect = document.getElementById('ndh');
                const ndhInfo   = document.getElementById('ndh-info');
                if (!ndhSelect || !pendaftaranData || !selectedTraining) return;

                ndhSelect.innerHTML = '<option value="">Memuat NDH...</option>';
                ndhSelect.disabled  = true;

                try {
                    const nipNrp   = verifiedPeserta?.nip_nrp || '';
                    const response = await fetch(
                        `/api/get-available-ndh?id_jenis_pelatihan=${selectedTraining.id}&id_angkatan=${pendaftaranData.id_angkatan}&nip_nrp=${nipNrp}`
                    );
                    const result = await response.json();

                    if (result.success) {
                        ndhSelect.innerHTML = '<option value="">-- Pilih NDH --</option>';
                        ndhSelect.disabled  = false;

                        if (result.data.length === 0) {
                            ndhSelect.innerHTML = '<option value="">Tidak ada NDH tersedia</option>';
                            ndhSelect.disabled  = true;
                            ndhInfo.innerHTML   = '<i class="fas fa-exclamation-triangle"></i> Semua NDH sudah terisi penuh';
                            ndhInfo.style.color = 'var(--warning-color)';
                            return;
                        }

                        result.data.forEach(ndh => {
                            const opt = document.createElement('option');
                            opt.value = ndh;
                            opt.textContent = `NDH ${ndh}`;
                            if (verifiedPeserta?.ndh && verifiedPeserta.ndh == ndh) opt.selected = true;
                            ndhSelect.appendChild(opt);
                        });

                        ndhInfo.innerHTML   = `<i class="fas fa-check-circle"></i> Tersedia: ${result.tersedia} dari ${result.kuota} NDH`;
                        ndhInfo.style.color = 'var(--success-color)';

                        if (verifiedPeserta?.ndh) {
                            ndhSelect.value = verifiedPeserta.ndh;
                            if (!result.data.includes(parseInt(verifiedPeserta.ndh))) {
                                const opt = document.createElement('option');
                                opt.value       = verifiedPeserta.ndh;
                                opt.textContent = `NDH ${verifiedPeserta.ndh} (NDH Anda saat ini)`;
                                opt.selected    = true;
                                ndhSelect.insertBefore(opt, ndhSelect.firstChild.nextSibling);
                                ndhInfo.innerHTML   = `<i class="fas fa-info-circle"></i> NDH Anda saat ini: ${verifiedPeserta.ndh}. Anda dapat mengubahnya jika diperlukan.`;
                                ndhInfo.style.color = 'var(--accent-color)';
                            }
                        }
                    } else throw new Error(result.message);
                } catch (error) {
                    ndhSelect.innerHTML = '<option value="">Error loading NDH</option>';
                    ndhSelect.disabled  = false;
                    if (ndhInfo) {
                        ndhInfo.innerHTML   = '<i class="fas fa-exclamation-circle"></i> Gagal memuat data NDH';
                        ndhInfo.style.color = 'var(--danger-color)';
                    }
                    showErrorMessage('Gagal memuat data NDH yang tersedia');
                }
            }

            // ============================================
            // NIP NORMALIZATION
            // ============================================
            function setupNipNormalization() {
                document.querySelectorAll('.nip-normalize').forEach(input => {
                    input.addEventListener('input', function ()  { this.value = this.value.replace(/[\s\.]/g, ''); });
                    input.addEventListener('paste', function ()  { setTimeout(() => { this.value = this.value.replace(/[\s\.]/g, ''); }, 10); });
                });
            }

            // ============================================
            // MENTOR FORM
            // ============================================
            function setupMentorForm() {
                const mentorSelect      = document.getElementById('sudah_ada_mentor');
                const mentorContainer   = document.getElementById('mentor-container');
                const mentorModeSelect  = document.getElementById('mentor_mode');
                const mentorDropdown    = document.getElementById('id_mentor');
                const searchInput       = document.getElementById('search-mentor');

                if (!mentorSelect || !mentorContainer) return;

                mentorSelect.addEventListener('change', function () {
                    if (this.value === 'Ya') {
                        mentorContainer.style.display = 'block';
                        if (mentorDropdown && mentorDropdown.options.length <= 1) loadMentors();
                    } else {
                        mentorContainer.style.display = 'none';
                    }
                });

                if (mentorModeSelect) {
                    mentorModeSelect.addEventListener('change', function () {
                        const selectForm = document.getElementById('select-mentor-form');
                        const addForm    = document.getElementById('add-mentor-form');
                        if (this.value === 'pilih') {
                            if (selectForm) selectForm.style.display = 'block';
                            if (addForm)    addForm.style.display    = 'none';
                            if (mentorDropdown && mentorDropdown.options.length <= 1) loadMentors();
                        } else {
                            if (selectForm) selectForm.style.display = 'none';
                            if (addForm)    addForm.style.display    = 'block';
                        }
                    });
                }

                if (searchInput) {
                    let searchTimeout;
                    searchInput.addEventListener('input', function () {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => loadMentors(this.value.trim()), 500);
                    });
                }

                if (mentorDropdown) {
                    mentorDropdown.addEventListener('change', function () {
                        const payload = this.options[this.selectedIndex]?.dataset.mentor;
                        const fields  = ['nama_mentor_select','nip_mentor_select','jabatan_mentor_select',
                                         'nomor_rekening_mentor_select','npwp_mentor_select','nomor_hp_mentor_select',
                                         'golongan_mentor_select','pangkat_mentor_select'];
                        if (!payload) { fields.forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; }); return; }
                        const mentor = JSON.parse(payload);
                        document.getElementById('nama_mentor_select').value = mentor.nama_mentor || '';
                        document.getElementById('nip_mentor_select').value  = mentor.nip_mentor  || '';
                        document.getElementById('jabatan_mentor_select').value = mentor.jabatan_mentor || '';
                        document.getElementById('nomor_rekening_mentor_select').value = mentor.nomor_rekening_mentor || '';
                        document.getElementById('npwp_mentor_select').value    = mentor.npwp_mentor    || '';
                        document.getElementById('nomor_hp_mentor_select').value= mentor.nomor_hp_mentor|| '';
                        const golEl = document.getElementById('golongan_mentor_select');
                        const pakEl = document.getElementById('pangkat_mentor_select');
                        if (golEl) golEl.value = mentor.golongan || '';
                        if (pakEl) pakEl.value = mentor.pangkat  || '';
                    });
                }
            }

            async function loadMentors(searchTerm = '') {
                const mentorDropdown    = document.getElementById('id_mentor');
                const loadingIndicator  = document.getElementById('mentor-loading');
                const notFoundIndicator = document.getElementById('mentor-not-found');
                const searchInfo        = document.getElementById('search-info');
                const searchResultCount = document.getElementById('search-result-count');
                if (!mentorDropdown) return;

                if (loadingIndicator)  loadingIndicator.style.display  = 'block';
                if (notFoundIndicator) notFoundIndicator.style.display = 'none';
                if (searchInfo)        searchInfo.style.display        = 'none';

                mentorDropdown.innerHTML = '<option value="">Memuat daftar mentor...</option>';
                mentorDropdown.disabled  = true;

                try {
                    const url      = searchTerm ? `/api/mentors?search=${encodeURIComponent(searchTerm)}` : '/api/mentors';
                    const response = await fetch(url);
                    const result   = await response.json();

                    if (loadingIndicator) loadingIndicator.style.display = 'none';

                    if (result.success && result.data?.length > 0) {
                        mentorDropdown.innerHTML = '<option value="">Pilih Mentor</option>';
                        mentorDropdown.disabled  = false;
                        result.data.forEach(mentor => {
                            const opt = document.createElement('option');
                            opt.value = mentor.id_mentor || mentor.id;
                            opt.textContent = `${mentor.nama_mentor} - ${mentor.nip_mentor} - ${mentor.jabatan_mentor}`;
                            opt.dataset.mentor = JSON.stringify({
                                nama_mentor: mentor.nama_mentor, nip_mentor: mentor.nip_mentor,
                                jabatan_mentor: mentor.jabatan_mentor, golongan: mentor.golongan,
                                pangkat: mentor.pangkat, nomor_rekening_mentor: mentor.nomor_rekening,
                                npwp_mentor: mentor.npwp_mentor, nomor_hp_mentor: mentor.nomor_hp_mentor
                            });
                            mentorDropdown.appendChild(opt);
                        });
                        if (searchTerm && searchInfo && searchResultCount) {
                            searchInfo.style.display = 'block';
                            searchResultCount.textContent = `Ditemukan ${result.total} mentor yang sesuai dengan "${searchTerm}"`;
                        }
                        if (verifiedPeserta?.id_mentor) {
                            mentorDropdown.value = verifiedPeserta.id_mentor;
                            if (mentorDropdown.value) mentorDropdown.dispatchEvent(new Event('change'));
                        }
                    } else {
                        mentorDropdown.innerHTML = '<option value="">Tidak ada mentor ditemukan</option>';
                        mentorDropdown.disabled  = false;
                        if (notFoundIndicator) {
                            notFoundIndicator.style.display = 'block';
                            if (searchTerm) notFoundIndicator.innerHTML = `<i class="fas fa-exclamation-circle"></i> Tidak ada mentor yang sesuai dengan "${searchTerm}"`;
                        }
                    }
                } catch (error) {
                    if (loadingIndicator) loadingIndicator.style.display = 'none';
                    mentorDropdown.innerHTML = '<option value="">Error loading mentors</option>';
                    mentorDropdown.disabled  = false;
                    showErrorMessage('Gagal memuat daftar mentor. Silakan coba lagi.');
                }
            }

            // ============================================
            // LOADING OVERLAY
            // ============================================
            function showLoadingOverlay(message = 'Menyimpan data perubahan. Mohon tunggu...') {
                hideLoadingOverlay();
                const overlay = document.createElement('div');
                overlay.id = 'loading-overlay';
                overlay.className = 'loading-overlay';
                overlay.innerHTML = `
                    <div class="loading-content">
                        <div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i></div>
                        <div class="loading-message">
                            <h4><i class="fas fa-clock"></i> Proses Sedang Berjalan</h4>
                            <p>${message}</p>
                            <p class="loading-detail">
                                <i class="fas fa-info-circle"></i>
                                Proses ini mungkin memakan waktu beberapa menit.
                                <strong>Jangan tutup atau refresh halaman ini.</strong>
                            </p>
                        </div>
                    </div>`;
                document.body.appendChild(overlay);
                document.body.style.overflow = 'hidden';
            }

            function updateLoadingMessage(msg) {
                const el = document.querySelector('#loading-overlay .loading-message p');
                if (el) el.textContent = msg;
            }

            function hideLoadingOverlay() {
                document.getElementById('loading-overlay')?.remove();
                document.body.style.overflow = '';
            }

            // ============================================
            // PANGKAT AUTO-FILL
            // ============================================
            function setupPangkatAutoFill() {
                const golonganRuangSelect = document.getElementById('golongan_ruang');
                const pangkatInput        = document.getElementById('pangkat');
                const pangkatDescription  = document.getElementById('pangkat_description');
                const pangkatDescText     = document.getElementById('pangkat_desc_text');
                if (!golonganRuangSelect || !pangkatInput) return;

                const pangkatMapping = {
                    'II/a': { pangkat: 'Pengatur Muda',           description: 'Golongan IIa - Pengatur Muda' },
                    'II/b': { pangkat: 'Pengatur Muda Tingkat I',  description: 'Golongan IIb - Pengatur Muda Tingkat I' },
                    'II/c': { pangkat: 'Pengatur',                 description: 'Golongan IIc - Pengatur' },
                    'II/d': { pangkat: 'Pengatur Tingkat I',       description: 'Golongan IId - Pengatur Tingkat I' },
                    'III/a':{ pangkat: 'Penata Muda',              description: 'Golongan IIIa - Penata Muda' },
                    'III/b':{ pangkat: 'Penata Muda Tingkat I',    description: 'Golongan IIIb - Penata Muda Tingkat I' },
                    'III/c':{ pangkat: 'Penata',                   description: 'Golongan IIIc - Penata' },
                    'III/d':{ pangkat: 'Penata Tingkat I',         description: 'Golongan IIId - Penata Tingkat I' },
                    'IV/a': { pangkat: 'Pembina',                  description: 'Golongan IVa - Pembina' },
                    'IV/b': { pangkat: 'Pembina Tingkat I',        description: 'Golongan IVb - Pembina Tingkat I' },
                    'IV/c': { pangkat: 'Pembina Muda',             description: 'Golongan IVc - Pembina Muda' },
                    'IV/d': { pangkat: 'Pembina Madya',            description: 'Golongan IVd - Pembina Madya' },
                };

                function updatePangkat() {
                    const data = pangkatMapping[golonganRuangSelect.value];
                    if (data) {
                        pangkatInput.value = data.pangkat;
                        if (pangkatDescText)    pangkatDescText.textContent   = data.description;
                        if (pangkatDescription) pangkatDescription.style.display = 'block';
                    } else {
                        pangkatInput.value = '';
                        if (pangkatDescription) pangkatDescription.style.display = 'none';
                    }
                }

                golonganRuangSelect.addEventListener('change', updatePangkat);
                updatePangkat();
            }

            // ============================================
            // PANGKAT MENTOR AUTO-FILL
            // ============================================
            function setupPangkatMentorAutoFill() {
                const golonganMentorBaru = document.getElementById('golongan_mentor_baru');
                const pangkatMentorBaru  = document.getElementById('pangkat_mentor_baru');
                if (!golonganMentorBaru || !pangkatMentorBaru) return;

                const map = {
                    'II/a':'Pengatur Muda','II/b':'Pengatur Muda Tingkat I','II/c':'Pengatur','II/d':'Pengatur Tingkat I',
                    'III/a':'Penata Muda','III/b':'Penata Muda Tingkat I','III/c':'Penata','III/d':'Penata Tingkat I',
                    'IV/a':'Pembina','IV/b':'Pembina Tingkat I','IV/c':'Pembina Utama Muda','IV/d':'Pembina Utama Madya',
                };

                if (golonganMentorBaru.value) pangkatMentorBaru.value = map[golonganMentorBaru.value] || '';
                golonganMentorBaru.addEventListener('change', function () { pangkatMentorBaru.value = map[this.value] || ''; });
            }

            // ============================================
            // HELPERS
            // ============================================
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024, sizes = ['Bytes','KB','MB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
            }

            function showVerificationError(message) {
                errorMessage.textContent = message;
                verificationError.style.display    = 'flex';
                verificationSuccess.style.display  = 'none';
                verificationDetails.style.display  = 'none';
                verificationAnggaran.style.display = 'none';
                verificationResult.style.display   = 'block';
            }

            function showNotification(type, message) {
                const notification = document.createElement('div');
                notification.className = `notification ${type}`;
                notification.innerHTML = `<div class="notification-content"><i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i><span>${message}</span></div>`;
                document.body.appendChild(notification);
                setTimeout(() => notification.classList.add('show'), 10);
                setTimeout(() => { notification.classList.remove('show'); setTimeout(() => notification.remove(), 300); }, type === 'success' ? 3000 : 5000);
            }

            function showSuccessMessage(msg) { showNotification('success', msg); }
            function showErrorMessage(msg)   { showNotification('error',   msg); }

            // ============================================
            // FORM SUBMISSION
            // ============================================
            document.getElementById('pendaftaranForm').addEventListener('submit', async function (e) {
                e.preventDefault();
                if (this.classList.contains('submitting')) return false;
                this.classList.add('submitting');

                const submitBtn  = document.getElementById('submit-form');
                const origText   = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan Perubahan...';
                submitBtn.disabled  = true;

                showLoadingOverlay('Menyimpan data perubahan. Proses ini mungkin memakan waktu beberapa menit. Mohon tidak menutup halaman ini.');

                // Client-side required validation
                const requiredFields = this.querySelectorAll('[required]');
                let hasEmpty = false;
                document.querySelectorAll('.client-error').forEach(el => el.remove());

                requiredFields.forEach(field => {
                    if (!field.value && field.type !== 'file' && !field.disabled) {
                        hasEmpty = true;
                        field.classList.add('error');
                        const fg = field.closest('.form-group');
                        if (fg) {
                            const err = document.createElement('small');
                            err.className   = 'text-danger client-error';
                            err.textContent = 'Field ini wajib diisi';
                            fg.appendChild(err);
                        }
                    }
                });

                if (hasEmpty) {
                    this.classList.remove('submitting');
                    submitBtn.innerHTML = origText;
                    submitBtn.disabled  = false;
                    hideLoadingOverlay();
                    document.querySelector('.error')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    showErrorMessage('Mohon lengkapi semua field yang wajib diisi.');
                    return false;
                }

                // File size validation
                let hasOversized = false;
                this.querySelectorAll('input[type="file"]').forEach(input => {
                    if (input.files.length > 0 && input.files[0].size > 1 * 1024 * 1024) {
                        hasOversized = true;
                        input.classList.add('error');
                        const fg = input.closest('.form-group');
                        if (fg && !fg.querySelector('.file-size-error')) {
                            const err = document.createElement('small');
                            err.className   = 'text-danger file-size-error';
                            err.innerHTML   = `<i class="fas fa-exclamation-circle"></i> Ukuran file (${formatFileSize(input.files[0].size)}) melebihi batas maksimal 1 MB`;
                            fg.appendChild(err);
                        }
                    }
                });

                if (hasOversized) {
                    this.classList.remove('submitting');
                    submitBtn.innerHTML = origText;
                    submitBtn.disabled  = false;
                    hideLoadingOverlay();
                    document.querySelector('.file-size-error')?.closest('.form-group')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    showErrorMessage('Ada file yang melebihi ukuran maksimal 1 MB. Silakan periksa kembali.');
                    return false;
                }

                const formData   = new FormData(this);
                const csrfToken  = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                if (verifiedPeserta?.id)   formData.append('peserta_id',    verifiedPeserta.id);
                if (pendaftaranData?.id)   formData.append('pendaftaran_id', pendaftaranData.id);
                formData.append('is_update', 'true');

                try {
                    const controller = new AbortController();
                    const timeoutId  = setTimeout(() => controller.abort(), 600000);

                    const response = await fetch(this.action, {
                        method: 'POST', body: formData, signal: controller.signal,
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    });

                    clearTimeout(timeoutId);
                    this.classList.remove('submitting');
                    hideLoadingOverlay();
                    submitBtn.innerHTML = origText;
                    submitBtn.disabled  = false;

                    // ✅ BACA JSON DULU APAPUN STATUS RESPONSENYA
                    let data;
                    try {
                        data = await response.json();
                    } catch(parseErr) {
                        // Response bukan JSON (misal 500 HTML)
                        const errMap = { 413: 'Ukuran file terlalu besar.', 500: 'Kesalahan server. Hubungi administrator.' };
                        showErrorMessage(errMap[response.status] || 'Terjadi kesalahan. Silakan coba lagi.');
                        return;
                    }

                    if (data.success) {
                        // SUKSES
                        showLoadingOverlay('Data berhasil disimpan! Mengarahkan ke halaman detail...');
                        setTimeout(() => {
                            hideLoadingOverlay();
                            showSuccessMessage('Data berhasil diperbarui!');
                            setTimeout(() => { window.location.href = data.redirect_url + '?id=' + data.pendaftaran_id; }, 1000);
                        }, 1500);
                        return;
                    }

                    // GAGAL — tampilkan error
                    document.querySelectorAll('.server-error').forEach(el => el.remove());
                    document.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
                    document.getElementById('error-summary-box')?.remove();

                    if (data.errors) {
                        const errorList = [];
                        let firstErrorEl = null;

                        Object.keys(data.errors).forEach(field => {
                            const errorMsg = data.errors[field][0];

                            // Cari input — coba berbagai selector
                            let input = document.querySelector(`[name="${field}"]`)
                                    || document.querySelector(`#${field}`);

                            if (input) {
                                // Jika input di dalam container yang hidden, buka dulu
                                let parent = input.closest('[style*="display: none"], [style*="display:none"]');
                                if (parent) parent.style.display = 'block';

                                input.classList.add('error');

                                const fg = input.closest('.form-group');
                                if (fg) {
                                    fg.querySelector('.server-error')?.remove();
                                    const err = document.createElement('small');
                                    err.className = 'text-danger server-error';
                                    err.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${errorMsg}`;
                                    fg.appendChild(err);
                                    if (!firstErrorEl) firstErrorEl = fg;
                                }
                            }

                            errorList.push({ field, message: errorMsg });
                        });

                        // Summary box merah di atas form
                        const summary = document.createElement('div');
                        summary.id = 'error-summary-box';
                        summary.style.cssText = `
                            background:#fff5f5; border:2px solid #fc8181; border-radius:8px;
                            padding:16px 20px; margin-bottom:24px;
                        `;
                        summary.innerHTML = `
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                                <i class="fas fa-exclamation-triangle" style="color:#e53e3e;font-size:1.2rem;"></i>
                                <strong style="color:#c53030;font-size:1rem;">
                                    Terdapat ${errorList.length} kesalahan yang perlu diperbaiki:
                                </strong>
                            </div>
                            <ul style="margin:0;padding-left:20px;color:#c53030;font-size:0.9rem;line-height:1.8;">
                                ${errorList.map(e => `<li><strong>${fieldLabel(e.field)}:</strong> ${e.message}</li>`).join('')}
                            </ul>
                        `;

                        // Sisipkan di atas step-navigation
                        const navBar = document.querySelector('#step3-content .step-navigation');
                        if (navBar) navBar.parentNode.insertBefore(summary, navBar);

                        // Scroll ke summary
                        setTimeout(() => {
                            const target = document.getElementById('error-summary-box') || firstErrorEl;
                            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }, 200);

                    } else {
                        showErrorMessage(data.message || 'Terjadi kesalahan. Silakan coba lagi.');
                    }

                } catch (error) {
                    this.classList.remove('submitting');
                    hideLoadingOverlay();
                    submitBtn.innerHTML = origText;
                    submitBtn.disabled  = false;
                    showErrorMessage(error.name === 'AbortError'
                        ? 'Proses upload terlalu lama. Coba lagi dengan file lebih kecil.'
                        : 'Terjadi kesalahan jaringan. Silakan coba lagi.');
                }
            });

            // ============================================
            // PREVENT CLOSE DURING SUBMISSION
            // ============================================
            window.addEventListener('beforeunload', function (e) {
                if (document.getElementById('pendaftaranForm')?.classList.contains('submitting')) {
                    e.preventDefault();
                    e.returnValue = 'Data sedang disimpan. Apakah Anda yakin ingin meninggalkan halaman ini?';
                }
            });

            // ============================================
            // CLEAR ERRORS ON INPUT & FILE VALIDATION
            // ============================================
            document.addEventListener('input', function (e) {
                if (!e.target.matches('input, select, textarea')) return;
                e.target.classList.remove('error');
                e.target.closest('.form-group')?.querySelector('.server-error, .client-error')?.remove();
            });

            document.addEventListener('change', function (e) {
                if (!e.target.matches('input[type="file"]')) return;
                const fileInput = e.target;
                const file = fileInput.files[0];
                if (!file) return;

                const maxSize = 1 * 1024 * 1024;
                const formGroup = fileInput.closest('.form-group');
                formGroup?.querySelector('.file-size-error')?.remove();
                fileInput.classList.remove('error');

                const fileLabel     = fileInput.closest('.form-file')?.querySelector('.form-file-label');
                const fileNameDisplay = fileInput.closest('.form-file')?.querySelector('.form-file-name');

                if (fileLabel) { fileLabel.style.borderColor = ''; fileLabel.style.background = ''; }

                if (file.size > maxSize) {
                    fileInput.value = '';
                    fileInput.classList.add('error');
                    if (fileLabel) { fileLabel.style.borderColor = 'var(--danger-color)'; fileLabel.style.background = 'rgba(245,101,101,0.05)'; }
                    if (fileNameDisplay) fileNameDisplay.innerHTML = `<span class="no-file text-danger"><i class="fas fa-exclamation-triangle"></i> File terlalu besar (${formatFileSize(file.size)})</span>`;
                    if (formGroup) {
                        const err = document.createElement('small');
                        err.className = 'text-danger file-size-error';
                        err.innerHTML = `<i class="fas fa-exclamation-circle"></i> Ukuran file "${file.name}" (${formatFileSize(file.size)}) melebihi batas maksimal 1 MB`;
                        formGroup.appendChild(err);
                    }
                    showErrorMessage(`File "${file.name}" terlalu besar! Maksimal 1 MB.`);
                } else {
                    if (fileNameDisplay) fileNameDisplay.innerHTML = `<span style="color:var(--success-color);"><i class="fas fa-check-circle"></i> ${file.name} (${formatFileSize(file.size)})</span>`;
                    if (fileLabel) { fileLabel.style.borderColor = 'var(--success-color)'; fileLabel.style.background = 'rgba(72,187,120,0.05)'; }
                }
            });

            // Handler tombol "Ganti File"
            document.addEventListener('click', function (e) {
                const btn = e.target.closest('.btn-change-file');
                if (!btn) return;
                const targetName = btn.getAttribute('data-target');
                const fileInput  = document.querySelector(`input[name="${targetName}"]`);
                if (!fileInput) return;
                fileInput.value = '';
                const fileNameDisplay = fileInput.closest('.form-file')?.querySelector('.form-file-name');
                if (fileNameDisplay) fileNameDisplay.innerHTML = '<span class="no-file">Belum ada file dipilih</span>';
                fileInput.classList.remove('error');
                const fileLabel = fileInput.closest('.form-file')?.querySelector('.form-file-label');
                if (fileLabel) { fileLabel.style.borderColor = ''; fileLabel.style.background = ''; }
                fileInput.closest('.form-group')?.querySelector('.file-size-error')?.remove();
                fileInput.click();
            });

            // ============================================
            // NOTIFICATION STYLES
            // ============================================
            const style = document.createElement('style');
            style.textContent = `
                .notification {
                    position: fixed; top: 20px; right: 20px; z-index: 9999;
                    min-width: 300px; max-width: 400px; background: white;
                    border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    padding: 15px 20px; transform: translateX(400px); transition: transform 0.3s ease;
                }
                .notification.show { transform: translateX(0); }
                .notification.success { border-left: 4px solid var(--success-color); }
                .notification.error   { border-left: 4px solid var(--danger-color); }
                .notification-content { display: flex; align-items: center; gap: 12px; }
                .notification-content i { font-size: 1.2rem; }
                .notification.success .notification-content i { color: var(--success-color); }
                .notification.error   .notification-content i { color: var(--danger-color); }
                .notification-content span { flex: 1; font-size: 0.95rem; }
                .submitting * { pointer-events: none !important; }
            `;
            document.head.appendChild(style);
        });
    </script>
@endpush