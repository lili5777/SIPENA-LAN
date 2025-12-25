@extends('layouts.master')

@section('title', 'SIPENA - Form Pendaftaran Pelatihan')

@section('content')
    <!-- Hero Section -->
    <section class="form-hero" id="home">
        <div class="container">
            <div class="form-hero-content animate">
                <h1 class="form-hero-title">Form Pendaftaran Peserta Pelatihan</h1>
                <p class="form-hero-text">Daftarkan diri Anda untuk mengikuti program pelatihan profesional. Isi formulir
                    dengan data yang lengkap dan valid.</p>
                <div class="progress-indicator">
                    <div class="progress-step active" id="step1">
                        <div class="step-number">1</div>
                        <div class="step-label">Pilih Pelatihan</div>
                    </div>
                    <div class="progress-step" id="step2">
                        <div class="step-number">2</div>
                        <div class="step-label">Pilih Angkatan</div>
                    </div>
                    <div class="progress-step" id="step3">
                        <div class="step-number">3</div>
                        <div class="step-label">Isi Formulir</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Form Section -->
    <section class="form-section" id="form-section">
        <div class="container">
            <div class="form-wrapper animate">
                <form action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data"
                    id="pendaftaranForm">
                    @csrf

                    <!-- Step 1: Pilih Pelatihan -->
                    <div class="form-step active" id="step1-content">
                        <div class="step-header">
                            <h2 class="step-title">Pilih Jenis Pelatihan</h2>
                            <p class="step-description">Silakan pilih jenis pelatihan yang akan Anda ikuti</p>
                        </div>

                        <div class="training-options">
                            <div class="training-card" data-id="1" data-kode="PKN_TK_II">
                                <div class="training-icon">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <h3 class="training-name">PKN TK II</h3>
                                <p class="training-code">PKN Tingkat II</p>
                                <button type="button" class="training-select-btn">Pilih Pelatihan Ini</button>
                            </div>
                            <div class="training-card" data-id="2" data-kode="PD_CPNS">
                                <div class="training-icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <h3 class="training-name">PD CPNS</h3>
                                <p class="training-code">Pendidikan CPNS</p>
                                <button type="button" class="training-select-btn">Pilih Pelatihan Ini</button>
                            </div>
                            <div class="training-card" data-id="3" data-kode="PKA">
                                <div class="training-icon">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <h3 class="training-name">PKA</h3>
                                <p class="training-code">Pelatihan Khusus</p>
                                <button type="button" class="training-select-btn">Pilih Pelatihan Ini</button>
                            </div>
                            <div class="training-card" data-id="4" data-kode="PKP">
                                <div class="training-icon">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <h3 class="training-name">PKP</h3>
                                <p class="training-code">Pelatihan Khusus</p>
                                <button type="button" class="training-select-btn">Pilih Pelatihan Ini</button>
                            </div>
                        </div>

                        <input type="hidden" name="id_jenis_pelatihan" id="id_jenis_pelatihan"
                            value="{{ old('id_jenis_pelatihan', '') }}">
                    </div>

                    <!-- Step 2: Pilih Angkatan -->
                    <div class="form-step" id="step2-content">
                        <div class="step-header">
                            <h2 class="step-title">Pilih Angkatan Pelatihan</h2>
                            <p class="step-description">Silakan pilih angkatan untuk pelatihan yang dipilih</p>
                            <div class="selected-training">
                                <i class="fas fa-check-circle"></i>
                                <span id="selected-training-name"></span>
                            </div>
                        </div>

                        <div class="angkatan-container">
                            <div class="form-group">
                                <label for="id_angkatan" class="form-label">Angkatan *</label>
                                <select name="id_angkatan" id="id_angkatan"
                                    class="form-select @error('id_angkatan') error @enderror" required disabled>
                                    <option value="">Memuat pilihan angkatan...</option>
                                </select>
                                @error('id_angkatan')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <div class="form-hint">Harap tunggu hingga daftar angkatan dimuat</div>
                            </div>

                            <div class="angkatan-info" id="angkatan-info" style="display: none;">
                                <div class="info-card">
                                    <h4><i class="fas fa-info-circle"></i> Informasi Angkatan</h4>
                                    <div class="info-details">
                                        <div class="info-item">
                                            <span class="info-label">Nama Angkatan:</span>
                                            <span class="info-value" id="info-nama-angkatan"></span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Tahun:</span>
                                            <span class="info-value" id="info-tahun-angkatan"></span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Kuota:</span>
                                            <span class="info-value" id="info-kuota-angkatan"></span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Status:</span>
                                            <span class="info-badge" id="info-status-angkatan"></span>
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

                    <!-- Step 3: Isi Formulir -->
                    <div class="form-step" id="step3-content">
                        <div class="step-header">
                            <h2 class="step-title">Isi Formulir Pendaftaran</h2>
                            <p class="step-description">Lengkapi data berikut dengan informasi yang valid</p>
                            <div class="selected-info">
                                <div class="info-badge">
                                    <i class="fas fa-book"></i> <span id="current-training-name"></span>
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
                            <button type="submit" class="btn btn-success" id="submit-form">
                                <i class="fas fa-paper-plane"></i> Kirim Pendaftaran
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

        .info-badge {
            display: inline-block;
            padding: 4px 12px;
            background: var(--success-color);
            color: white;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
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
            const angkatanSelect = document.getElementById('id_angkatan');
            const selectedTrainingName = document.getElementById('selected-training-name');
            const currentTrainingName = document.getElementById('current-training-name');
            const currentAngkatanName = document.getElementById('current-angkatan-name');
            const dynamicFormContainer = document.getElementById('dynamic-form-container');
            const backToStep1Btn = document.getElementById('back-to-step1');
            const backToStep2Btn = document.getElementById('back-to-step2');
            const nextToStep3Btn = document.getElementById('next-to-step3');
            const submitFormBtn = document.getElementById('submit-form');
            const angkatanInfo = document.getElementById('angkatan-info');

            let selectedTraining = null;
            let selectedAngkatan = null;

            // ============================================
            // HANDLE OLD VALUES (IF VALIDATION FAILED)
            // ============================================
            window.oldValues = @json(old(), JSON_PRETTY_PRINT);
            const validationFailed = @json($errors->any() ? true : false);

            // Jika ada validation errors, auto-select training yang dipilih sebelumnya
            if (validationFailed && window.oldValues.id_jenis_pelatihan) {
                // Tunggu DOM siap
                setTimeout(() => {
                    const oldTrainingId = window.oldValues.id_jenis_pelatihan;
                    const trainingCard = document.querySelector(`[data-id="${oldTrainingId}"]`);

                    if (trainingCard) {
                        // Auto-select training card
                        trainingCard.click();

                        // Tunggu angkatan load
                        setTimeout(() => {
                            if (window.oldValues.id_angkatan) {
                                const angkatanSelect = document.getElementById('id_angkatan');

                                // Cek berulang sampai options tersedia
                                const checkAngkatan = setInterval(() => {
                                    if (angkatanSelect.options.length > 1) {
                                        clearInterval(checkAngkatan);
                                        angkatanSelect.value = window.oldValues.id_angkatan;
                                        angkatanSelect.dispatchEvent(new Event('change'));

                                        // Load form setelah semua ready
                                        setTimeout(() => {
                                            loadDynamicForm();
                                            moveToStep(3);
                                        }, 500);
                                    }
                                }, 100);
                            }
                        }, 500);
                    }
                }, 100);
            }

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

                    // Load angkatan
                    loadAngkatan(selectedTraining.id);
                });
            });

            // ============================================
            // STEP 2: PILIH ANGKATAN
            // ============================================
            async function loadAngkatan(jenisId) {
                angkatanSelect.innerHTML = '<option value="">Memuat pilihan angkatan...</option>';
                angkatanSelect.disabled = true;
                nextToStep3Btn.disabled = true;
                angkatanInfo.style.display = 'none';

                try {
                    const response = await fetch(`/api/angkatan/${jenisId}`);
                    const data = await response.json();

                    if (data.length === 0) {
                        angkatanSelect.innerHTML = '<option value="">Tidak ada angkatan tersedia</option>';
                        return;
                    }

                    angkatanSelect.innerHTML = '<option value="">Pilih Angkatan</option>';
                    data.forEach(angkatan => {
                        const option = document.createElement('option');
                        option.value = angkatan.id;
                        option.textContent = `${angkatan.nama_angkatan} (${angkatan.tahun})`;
                        option.dataset.nama = angkatan.nama_angkatan;
                        option.dataset.tahun = angkatan.tahun;
                        option.dataset.kuota = angkatan.kuota;
                        option.dataset.status = angkatan.status_angkatan;
                        angkatanSelect.appendChild(option);
                    });

                    angkatanSelect.disabled = false;

                    // Jika ada old value, set value
                    if (window.oldValues && window.oldValues.id_angkatan) {
                        setTimeout(() => {
                            angkatanSelect.value = window.oldValues.id_angkatan;
                            if (angkatanSelect.value) {
                                angkatanSelect.dispatchEvent(new Event('change'));
                            }
                        }, 100);
                    }
                } catch (error) {
                    console.error('Error load angkatan:', error);
                    angkatanSelect.innerHTML = '<option value="">Error loading data</option>';
                }
            }

            angkatanSelect.addEventListener('change', function () {
                if (!this.value) {
                    nextToStep3Btn.disabled = true;
                    angkatanInfo.style.display = 'none';
                    return;
                }

                const selectedOption = this.options[this.selectedIndex];
                selectedAngkatan = {
                    id: this.value,
                    nama: selectedOption.dataset.nama,
                    tahun: selectedOption.dataset.tahun,
                    kuota: selectedOption.dataset.kuota,
                    status: selectedOption.dataset.status
                };

                // Update UI
                currentAngkatanName.textContent = `${selectedAngkatan.nama} (${selectedAngkatan.tahun})`;

                // Show angkatan info
                document.getElementById('info-nama-angkatan').textContent = selectedAngkatan.nama;
                document.getElementById('info-tahun-angkatan').textContent = selectedAngkatan.tahun;
                document.getElementById('info-kuota-angkatan').textContent = selectedAngkatan.kuota;

                const statusBadge = document.getElementById('info-status-angkatan');
                statusBadge.textContent = selectedAngkatan.status;
                statusBadge.className = 'info-badge';
                if (selectedAngkatan.status === 'Aktif') {
                    statusBadge.style.background = 'var(--success-color)';
                } else if (selectedAngkatan.status === 'Penuh') {
                    statusBadge.style.background = 'var(--danger-color)';
                } else {
                    statusBadge.style.background = 'var(--warning-color)';
                }

                angkatanInfo.style.display = 'block';
                nextToStep3Btn.disabled = false;
            });

            // ============================================
            // STEP 3: LOAD DYNAMIC FORM
            // ============================================
            function loadDynamicForm() {
                dynamicFormContainer.innerHTML = `
                        <div class="form-loading">
                            <i class="fas fa-spinner fa-spin"></i>
                            <p>Menyiapkan formulir pendaftaran...</p>
                        </div>
                    `;

                setTimeout(() => {
                    if (selectedTraining.kode === 'PKN_TK_II') {
                        loadFormPartial('PKN_TK_II');
                    } else if (selectedTraining.kode === 'PD_CPNS') {
                        loadFormPartial('PD_CPNS');
                    } else if (selectedTraining.kode === 'PKA' || selectedTraining.kode === 'PKP') {
                        loadFormPartial('PKA');
                    }
                }, 300);
            }

            async function loadFormPartial(formType) {
                try {
                    const response = await fetch(`/form-partial/${formType}`);
                    const html = await response.text();
                    dynamicFormContainer.innerHTML = html;

                    // Load provinsi setelah form dimuat
                    loadProvinsi();
                    setupFormInteractions();

                    // Set old values jika ada
                    setOldValuesToForm();

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

            async function loadProvinsi() {
                const provinsiSelect = document.querySelector('[name="id_provinsi"]');
                if (!provinsiSelect) return;

                provinsiSelect.innerHTML = '<option value="">Memuat provinsi...</option>';

                try {
                    const response = await fetch('/proxy/provinces');
                    const result = await response.json();

                    provinsiSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                    result.data.forEach(prov => {
                        const option = document.createElement('option');
                        option.value = prov.id || prov.code;
                        option.textContent = prov.name;
                        provinsiSelect.appendChild(option);
                    });

                    // Set old value jika ada
                    if (window.oldValues && window.oldValues.id_provinsi) {
                        provinsiSelect.value = window.oldValues.id_provinsi;
                        // Trigger change event untuk load kabupaten
                        setTimeout(() => {
                            provinsiSelect.dispatchEvent(new Event('change'));
                        }, 100);
                    }
                } catch (error) {
                    console.error('Provinsi error:', error);
                    provinsiSelect.innerHTML = '<option value="">Error loading</option>';
                }
            }

            async function loadKabupaten(provId) {
                const kabSelect = document.querySelector('[name="id_kabupaten_kota"]');
                if (!kabSelect) return;

                kabSelect.innerHTML = '<option value="">Memuat kabupaten...</option>';
                kabSelect.disabled = true;

                try {
                    const response = await fetch(`/proxy/regencies/${provId}`);
                    const result = await response.json();

                    kabSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                    kabSelect.disabled = false;

                    result.data.forEach(kab => {
                        const option = document.createElement('option');
                        option.value = kab.id || kab.code;
                        option.textContent = kab.name;
                        kabSelect.appendChild(option);
                    });

                    // Set old value jika ada
                    if (window.oldValues && window.oldValues.id_kabupaten_kota) {
                        kabSelect.value = window.oldValues.id_kabupaten_kota;
                    }
                } catch (error) {
                    console.error('Kabupaten error:', error);
                    kabSelect.innerHTML = '<option value="">Error loading</option>';
                    kabSelect.disabled = false;
                }
            }

            function setupFormInteractions() {
                // File input handlers
                document.querySelectorAll('.form-file-input').forEach(input => {
                    input.addEventListener('change', function () {
                        const fileName = this.files[0]?.name || 'Belum ada file dipilih';
                        this.parentElement.querySelector('.form-file-name').textContent = fileName;
                    });
                });

                // Event listener untuk provinsi change
                document.addEventListener('change', function (e) {
                    if (e.target.name === 'id_provinsi' && e.target.value) {
                        loadKabupaten(e.target.value);
                    }

                    // Handle mentor show/hide untuk PD CPNS
                    if (e.target.name === 'sudah_ada_mentor') {
                        const mentorDetail = document.getElementById('mentor-detail');
                        if (mentorDetail) {
                            if (e.target.value === 'Ya') {
                                mentorDetail.style.display = 'block';
                            } else {
                                mentorDetail.style.display = 'none';
                            }
                        }
                    }
                });
            }

            function setOldValuesToForm() {
                if (!window.oldValues) return;

                setTimeout(() => {
                    Object.keys(window.oldValues).forEach(fieldName => {
                        const field = document.querySelector(`[name="${fieldName}"]`);
                        if (field && field.type !== 'file') {
                            if (field.type === 'checkbox' || field.type === 'radio') {
                                field.checked = window.oldValues[fieldName] == field.value;
                            } else if (field.tagName === 'SELECT') {
                                field.value = window.oldValues[fieldName];
                            } else {
                                field.value = window.oldValues[fieldName];
                            }
                        }
                    });
                }, 200);
            }

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

                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            backToStep1Btn.addEventListener('click', () => moveToStep(1));
            backToStep2Btn.addEventListener('click', () => moveToStep(2));

            nextToStep3Btn.addEventListener('click', () => {
                if (selectedAngkatan) {
                    loadDynamicForm();
                    moveToStep(3);
                }
            });

            // ============================================
            // AJAX FORM SUBMISSION
            // ============================================
            document.getElementById('pendaftaranForm').addEventListener('submit', async function (e) {
                e.preventDefault();

                console.log('Form submission via AJAX started...');

                const submitBtn = document.getElementById('submit-form');
                const originalText = submitBtn.innerHTML;

                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
                submitBtn.disabled = true;

                // Validasi client-side sederhana
                const requiredFields = this.querySelectorAll('[required]');
                let hasEmptyRequired = false;

                // Clear previous client-side errors
                document.querySelectorAll('.client-error').forEach(el => el.remove());

                requiredFields.forEach(field => {
                    if (!field.value && field.type !== 'file') {
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
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;

                    // Scroll ke error pertama
                    const firstError = document.querySelector('.error');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    return false;
                }

                // Collect form data
                const formData = new FormData(this);

                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                try {
                    // Kirim request AJAX
                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Success - redirect ke halaman sukses
                        showSuccessMessage('Pendaftaran berhasil dikirim!');

                        setTimeout(() => {
                            window.location.href = data.redirect_url || '/pendaftaran/success';
                        }, 1500);

                    } else {
                        // Validation errors
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;

                        // Clear previous errors
                        document.querySelectorAll('.server-error').forEach(el => el.remove());
                        document.querySelectorAll('.error').forEach(el => el.classList.remove('error'));

                        // Clear file label error styles
                        document.querySelectorAll('.form-file-label').forEach(label => {
                            label.style.borderColor = '';
                            label.style.background = '';
                        });

                        // Display new errors
                        if (data.errors) {
                            console.log('Validation errors:', data.errors);

                            Object.keys(data.errors).forEach(field => {
                                let input = document.querySelector(`[name="${field}"]`);

                                // Jika tidak ketemu, coba dengan nama field yang berbeda
                                if (!input) {
                                    input = document.querySelector(`[name="${field}[]"]`);
                                }

                                if (!input) {
                                    input = document.querySelector(`#${field}`);
                                }

                                if (input) {
                                    input.classList.add('error');

                                    // Untuk file inputs, juga highlight label
                                    if (input.type === 'file') {
                                        const fileLabel = input.closest('.form-file')?.querySelector('.form-file-label');
                                        if (fileLabel) {
                                            fileLabel.style.borderColor = 'var(--danger-color)';
                                            fileLabel.style.background = 'rgba(245, 101, 101, 0.05)';
                                        }
                                    }

                                    // Cari form group
                                    let formGroup = input.closest('.form-group');
                                    if (!formGroup) {
                                        formGroup = input.closest('.checkbox-group') ||
                                            input.closest('.form-check') ||
                                            input.parentElement;
                                    }

                                    if (formGroup) {
                                        // Hapus error message sebelumnya
                                        const existingError = formGroup.querySelector('.server-error');
                                        if (existingError) existingError.remove();

                                        // Tambahkan error message baru
                                        const errorMsg = document.createElement('small');
                                        errorMsg.className = 'text-danger server-error';
                                        errorMsg.textContent = data.errors[field][0];
                                        formGroup.appendChild(errorMsg);
                                    }
                                } else {
                                    console.warn(`Field "${field}" not found in DOM`);
                                    showErrorMessage(data.errors[field][0]);
                                }
                            });

                            // Scroll ke error pertama
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
                            // General error message
                            showErrorMessage(data.message);
                        }
                    }

                } catch (error) {
                    console.error('AJAX Error:', error);
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    showErrorMessage('Terjadi kesalahan jaringan. Silakan coba lagi.');
                }
            });

            // ============================================
            // HELPER FUNCTIONS
            // ============================================
            function showSuccessMessage(message) {
                // Hapus notifikasi sebelumnya
                document.querySelectorAll('.notification').forEach(el => el.remove());

                const notification = document.createElement('div');
                notification.className = 'notification success';
                notification.innerHTML = `
                        <div class="notification-content">
                            <i class="fas fa-check-circle"></i>
                            <span>${message}</span>
                        </div>
                    `;

                document.body.appendChild(notification);

                // Animasi masuk
                setTimeout(() => {
                    notification.classList.add('show');
                }, 10);

                // Hapus setelah 3 detik
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 3000);
            }

            function showErrorMessage(message) {
                // Hapus notifikasi sebelumnya
                document.querySelectorAll('.notification').forEach(el => el.remove());

                const notification = document.createElement('div');
                notification.className = 'notification error';
                notification.innerHTML = `
                        <div class="notification-content">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>${message}</span>
                        </div>
                    `;

                document.body.appendChild(notification);

                // Animasi masuk
                setTimeout(() => {
                    notification.classList.add('show');
                }, 10);

                // Hapus setelah 5 detik
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 5000);
            }

            // Tambahkan CSS untuk notifikasi
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
                `;

            // Inject styles
            const styleSheet = document.createElement("style");
            styleSheet.textContent = notificationStyles;
            document.head.appendChild(styleSheet);

            // Tambahkan event listener untuk clear error saat input
            document.addEventListener('input', function (e) {
                if (e.target.matches('input, select, textarea')) {
                    e.target.classList.remove('error');

                    // Hapus error messages terkait
                    const formGroup = e.target.closest('.form-group');
                    if (formGroup) {
                        const errorMsg = formGroup.querySelector('.server-error, .client-error');
                        if (errorMsg) errorMsg.remove();
                    }

                    // Reset file label styling
                    if (e.target.type === 'file') {
                        const fileLabel = e.target.closest('.form-file')?.querySelector('.form-file-label');
                        if (fileLabel) {
                            fileLabel.style.borderColor = '';
                            fileLabel.style.background = '';
                        }
                    }
                }
            });

            // Tambahkan event listener untuk clear error saat file change
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
        });
    </script>
@endpush