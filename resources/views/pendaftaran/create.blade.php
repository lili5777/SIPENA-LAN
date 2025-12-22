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
                        </div>

                        <input type="hidden" name="id_jenis_pelatihan" id="id_jenis_pelatihan">
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
                                <select name="id_angkatan" id="id_angkatan" class="form-select" required disabled>
                                    <option value="">Memuat pilihan angkatan...</option>
                                </select>
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
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
            // Elements
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

            // Step 1: Pilih Pelatihan
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

            // Step 2: Pilih Angkatan
            function loadAngkatan(jenisId) {
                angkatanSelect.innerHTML = '<option value="">Memuat pilihan angkatan...</option>';
                angkatanSelect.disabled = true;
                nextToStep3Btn.disabled = true;
                angkatanInfo.style.display = 'none';

                // Simulasi data angkatan
                setTimeout(() => {
                    const angkatanData = {
                        '1': [ // PKN_TK_II
                            { id: '1', nama: 'Angkatan I', tahun: '2024', kuota: '50 peserta', status: 'Aktif' },
                            { id: '2', nama: 'Angkatan II', tahun: '2024', kuota: '50 peserta', status: 'Aktif' }
                        ],
                        '2': [ // PD_CPNS
                            { id: '3', nama: 'Batch 1', tahun: '2024', kuota: '100 peserta', status: 'Aktif' },
                            { id: '4', nama: 'Batch 2', tahun: '2024', kuota: '100 peserta', status: 'Aktif' }
                        ],
                        '3': [ // PKA
                            { id: '5', nama: 'Gelombang 1', tahun: '2024', kuota: '30 peserta', status: 'Aktif' },
                            { id: '6', nama: 'Gelombang 2', tahun: '2024', kuota: '30 peserta', status: 'Aktif' }
                        ]
                    };

                    const data = angkatanData[jenisId] || [];

                    if (data.length === 0) {
                        angkatanSelect.innerHTML = '<option value="">Tidak ada angkatan tersedia</option>';
                        return;
                    }

                    angkatanSelect.innerHTML = '<option value="">Pilih Angkatan</option>';
                    data.forEach(angkatan => {
                        const option = document.createElement('option');
                        option.value = angkatan.id;
                        option.textContent = `${angkatan.nama} (${angkatan.tahun})`;
                        option.dataset.nama = angkatan.nama;
                        option.dataset.tahun = angkatan.tahun;
                        option.dataset.kuota = angkatan.kuota || 'Tidak tersedia';
                        option.dataset.status = angkatan.status || 'Aktif';
                        angkatanSelect.appendChild(option);
                    });

                    angkatanSelect.disabled = false;
                }, 500);
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

            // Step 3: Load Dynamic Form
            function loadDynamicForm() {
                dynamicFormContainer.innerHTML = `
                        <div class="form-loading">
                            <i class="fas fa-spinner fa-spin"></i>
                            <p>Menyiapkan formulir pendaftaran...</p>
                        </div>
                    `;

                setTimeout(() => {
                    if (selectedTraining.kode === 'PKN_TK_II') {
                        loadFormPKN_TK_II();
                    } else if (selectedTraining.kode === 'PD_CPNS') {
                        loadFormPD_CPNS();
                    } else if (selectedTraining.kode === 'PKA') {
                        loadFormPKA();
                    }
                }, 300);
            }

            function loadFormPKN_TK_II() {
                dynamicFormContainer.innerHTML = `
                        <div class="form-section-header">
                            <i class="fas fa-user-tie"></i> Data Pribadi
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Nama Lengkap (Berikut Gelar Pendidikan)</label>
                                <input type="text" name="nama_lengkap" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">NIP/NRP</label>
                                <input type="text" name="nip_nrp" class="form-input" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-input" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Alamat Rumah</label>
                            <textarea name="alamat_rumah" class="form-textarea" required></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Email Pribadi</label>
                                <input type="email" name="email_pribadi" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Nomor HP/WhatsApp</label>
                                <input type="tel" name="nomor_hp" class="form-input" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Pendidikan Terakhir</label>
                                <select name="pendidikan_terakhir" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="D3">D3</option>
                                    <option value="D4">D4</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bidang Studi Pendidikan Terakhir</label>
                                <input type="text" name="bidang_studi" class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bidang Keahlian</label>
                                <input type="text" name="bidang_keahlian" class="form-input">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Agama</label>
                                <select name="agama" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nama Istri/Suami</label>
                                <input type="text" name="nama_pasangan" class="form-input">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Olahraga Kegemaran</label>
                                <input type="text" name="olahraga_hobi" class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Apakah Anda merokok?</label>
                                <select name="perokok" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ukuran Kaos Olahraga/Celana Training</label>
                                <select name="ukuran_kaos" class="form-select">
                                    <option value="">Pilih</option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                    <option value="XXL">XXL</option>
                                    <option value="XXXL">XXXL</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-section-header">
                            <i class="fas fa-building"></i> Data Kepegawaian
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Asal Instansi</label>
                                <input type="text" name="asal_instansi" class="form-input" placeholder="Contoh: Lembaga Administrasi Negara" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Unit Kerja Peserta</label>
                                <input type="text" name="unit_kerja" class="form-input" placeholder="Contoh: Sekretariat Daerah Kota Makassar" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Jabatan</label>
                                <input type="text" name="jabatan" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Pangkat / Golongan Ruang</label>
                                <select name="golongan_ruang" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="Pembina Utama, IV/E">Pembina Utama, IV/E</option>
                                    <option value="Pembina Utama Madya, IV/D">Pembina Utama Madya, IV/D</option>
                                    <option value="Pembina Utama Muda, IV/C">Pembina Utama Muda, IV/C</option>
                                    <option value="Pembina Tingkat I, IV/B">Pembina Tingkat I, IV/B</option>
                                    <option value="Pembina, IV/A">Pembina, IV/A</option>
                                    <option value="Penata Tingkat I, III/D">Penata Tingkat I, III/D</option>
                                    <option value="Penata, III/C">Penata, III/C</option>
                                    <option value="Penata Muda Tingkat I, III/B">Penata Muda Tingkat I, III/B</option>
                                    <option value="Penata Muda, III/A">Penata Muda, III/A</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Eselon</label>
                                <select name="eselon" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="II">II</option>
                                    <option value="III/Pejabat Fungsional">III/Pejabat Fungsional</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Provinsi  (Kantor/Tempat Tugas)</label>
                                <select name="provinsi" class="form-select" required>
                                    <option value="">Pilih Provinsi</option>
                                    <option value="11">Aceh</option>
                                    <option value="12">Sumatera Utara</option>
                                    <option value="13">Sumatera Barat</option>
                                    <option value="14">Riau</option>
                                    <option value="15">Jambi</option>
                                    <option value="16">Sumatera Selatan</option>
                                    <option value="17">Bengkulu</option>
                                    <option value="18">Lampung</option>
                                    <option value="19">Kepulauan Bangka Belitung</option>
                                    <option value="21">Kepulauan Riau</option>
                                    <option value="31">DKI Jakarta</option>
                                    <option value="32">Jawa Barat</option>
                                    <option value="33">Jawa Tengah</option>
                                    <option value="34">DI Yogyakarta</option>
                                    <option value="35">Jawa Timur</option>
                                    <option value="36">Banten</option>
                                    <option value="51">Bali</option>
                                    <option value="52">Nusa Tenggara Barat</option>
                                    <option value="53">Nusa Tenggara Timur</option>
                                    <option value="61">Kalimantan Barat</option>
                                    <option value="62">Kalimantan Tengah</option>
                                    <option value="63">Kalimantan Selatan</option>
                                    <option value="64">Kalimantan Timur</option>
                                    <option value="65">Kalimantan Utara</option>
                                    <option value="71">Sulawesi Utara</option>
                                    <option value="72">Sulawesi Tengah</option>
                                    <option value="73">Sulawesi Selatan</option>
                                    <option value="74">Sulawesi Tenggara</option>
                                    <option value="75">Gorontalo</option>
                                    <option value="76">Sulawesi Barat</option>
                                    <option value="81">Maluku</option>
                                    <option value="82">Maluku Utara</option>
                                    <option value="91">Papua Barat</option>
                                    <option value="94">Papua</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Kabupaten (Lokasi Kantor/Tempat Tugas)</label>
                                <select name="kabupaten" class="form-select" required disabled>
                                    <option value="">Pilih Kabupaten (Pilih Provinsi Dahulu)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Alamat Kantor</label>
                            <textarea name="alamat_kantor" class="form-textarea" required></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Nomor Telepon Kantor</label>
                                <input type="tel" name="nomor_telepon_kantor" class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email Kantor</label>
                                <input type="email" name="email_kantor" class="form-input">
                            </div>
                        </div>

                        <div class="form-section-header">
                            <i class="fas fa-file-upload"></i> Dokumen Pendukung
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Unggah Bukti SK Jabatan Terakhir (Definitif)</label>
                                <div class="form-file">
                                    <input type="file" name="file_sk_jabatan" class="form-file-input" accept=".pdf" required>
                                    <label class="form-file-label">
                                        <i class="fas fa-cloud-upload-alt"></i><br>
                                        Klik untuk mengunggah file PDF (maks. 5MB)
                                    </label>
                                    <div class="form-file-name"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Unggah Bukti SK Pangkat/Golongan Ruang Terakhir</label>
                                <div class="form-file">
                                    <input type="file" name="file_sk_pangkat" class="form-file-input" accept=".pdf" required>
                                    <label class="form-file-label">
                                        <i class="fas fa-cloud-upload-alt"></i><br>
                                        Klik untuk mengunggah file PDF (maks. 5MB)
                                    </label>
                                    <div class="form-file-name"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label ">Unggah Surat Pernyataan Komitmen</label>
                            <div class="form-file">
                                <input type="file" name="file_surat_komitmen" class="form-file-input" accept=".pdf" >
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file PDF (maks. 5MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Unggah Scan Pakta Integritas</label>
                            <div class="form-file">
                                <input type="file" name="file_pakta_integritas" class="form-file-input" accept=".pdf" required>
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file PDF (maks. 5MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Unggah Scan Surat Tugas mengikuti pelatihan yang ditandatangani oleh pejabat yang berwenang</label>
                            <div class="form-file">
                                <input type="file" name="file_surat_tugas" class="form-file-input" accept=".pdf" required>
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file PDF (maks. 5MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label ">Unggah Scan Surat Keterangan Kelulusan/Hasil Seleksi calon peserta PKN TK.II</label>
                            <div class="form-file">
                                <input type="file" name="file_surat_kelulusan_seleksi" class="form-file-input" accept=".pdf" >
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file PDF (maks. 5MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Unggah Surat Keterangan Berbadan Sehat</label>
                                <div class="form-file">
                                    <input type="file" name="file_surat_sehat" class="form-file-input" accept=".pdf" required>
                                    <label class="form-file-label">
                                        <i class="fas fa-cloud-upload-alt"></i><br>
                                        Klik untuk mengunggah file PDF (maks. 5MB)
                                    </label>
                                    <div class="form-file-name"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Unggah Surat Keterangan Bebas Narkoba</label>
                                <div class="form-file">
                                    <input type="file" name="file_surat_bebas_narkoba" class="form-file-input" accept=".pdf" required>
                                    <label class="form-file-label">
                                        <i class="fas fa-cloud-upload-alt"></i><br>
                                        Klik untuk mengunggah file PDF (maks. 5MB)
                                    </label>
                                    <div class="form-file-name"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Upload Pasfoto peserta berwarna</label>
                            <div class="form-file">
                                <input type="file" name="file_pas_foto" class="form-file-input" accept=".jpg,.jpeg,.png" required>
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file JPG/PNG (maks. 2MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>

                        <div class="form-section-header">
                            <i class="fas fa-user-graduate"></i> Data Mentor
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Nama Mentor (jika sudah ditentukan)</label>
                                <input type="text" name="nama_mentor" class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Jabatan Mentor</label>
                                <input type="text" name="jabatan_mentor" class="form-input">
                            </div>
                        </div>
                    `;

                setupFormInteractions();
            }

            function loadFormPD_CPNS() {
                dynamicFormContainer.innerHTML = `
                        <div class="form-section-header">
                            <i class="fas fa-user-graduate"></i> Form Kesediaan PD CPNS
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Nama Lengkap dan Gelar</label>
                                <input type="text" name="nama_lengkap" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">NIP</label>
                                <input type="text" name="nip" class="form-input" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Nama Panggilan</label>
                                <input type="text" name="nama_panggilan" class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Agama</label>
                                <select name="agama" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Email</label>
                                <input type="email" name="email" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">No HP (yang terhubung Whatsapp)</label>
                                <input type="tel" name="no_hp" class="form-input" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Tempat Lahir (sesuai KTP)</label>
                                <input type="text" name="tempat_lahir" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-input" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Alamat Rumah</label>
                            <textarea name="alamat_rumah" class="form-textarea" required></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Alamat Kantor</label>
                            <textarea name="alamat_kantor" class="form-textarea" required></textarea>
                        </div>

                        <div class="form-section-header">
                            <i class="fas fa-building"></i> Data Instansi
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Asal Instansi</label>
                                <input type="text" name="asal_instansi" class="form-input" placeholder="Contoh: Dinas Kesehatan" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Jabatan</label>
                                <input type="text" name="jabatan" class="form-input" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Provinsi</label>
                                <select name="provinsi" class="form-select" required>
                                    <option value="">Pilih Provinsi</option>
                                    <option value="11">Aceh</option>
                                    <option value="12">Sumatera Utara</option>
                                    <!-- Tambahkan provinsi lainnya -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Kabupaten/Kota</label>
                                <select name="kabupaten" class="form-select" required disabled>
                                    <option value="">Pilih Kabupaten/Kota (Pilih Provinsi Dahulu)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Alamat Instansi</label>
                            <textarea name="alamat_instansi" class="form-textarea"></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Golongan Ruang</label>
                                <select name="golongan_ruang" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="I/a">I/a</option>
                                    <option value="I/b">I/b</option>
                                    <option value="I/c">I/c</option>
                                    <option value="I/d">I/d</option>
                                    <option value="II/a">II/a</option>
                                    <option value="II/b">II/b</option>
                                    <option value="II/c">II/c</option>
                                    <option value="II/d">II/d</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Pangkat</label>
                                <select name="pangkat" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="Pengatur Muda">Pengatur Muda</option>
                                    <option value="Pengatur Muda Tingkat I">Pengatur Muda Tingkat I</option>
                                    <option value="Pengatur">Pengatur</option>
                                    <option value="Pengatur Tingkat I">Pengatur Tingkat I</option>
                                    <option value="Penata Muda">Penata Muda</option>
                                    <option value="Penata Muda Tingkat I">Penata Muda Tingkat I</option>
                                    <option value="Penata">Penata</option>
                                    <option value="Penata Tingkat I">Penata Tingkat I</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Nomor SK CPNS</label>
                                <input type="text" name="nomor_sk_cpns" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Tanggal SK CPNS</label>
                                <input type="date" name="tanggal_sk_cpns" class="form-input" required>
                            </div>
                        </div>

                        <!-- Data lainnya untuk PD CPNS -->
                        <div class="form-section-header">
                            <i class="fas fa-graduation-cap"></i> Data Pendidikan
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Pendidikan Terakhir</label>
                                <select name="pendidikan_terakhir" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="SMA">SMA</option>
                                    <option value="D3">D3</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bidang Studi Pendidikan Terakhir</label>
                                <input type="text" name="bidang_studi" class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bidang Keahlian/Kepakaran</label>
                                <input type="text" name="bidang_keahlian" class="form-input">
                            </div>
                        </div>

                        <div class="form-section-header">
                            <i class="fas fa-heart"></i> Data Lainnya
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Olahraga Kegemaran/Hobi</label>
                                <input type="text" name="olahraga_hobi" class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Apakah Saudara/i adalah perokok?</label>
                                <select name="perokok" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kondisi Peserta</label>
                                <textarea name="kondisi_peserta" class="form-textarea"></textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Status Perkawinan</label>
                                <select name="status_perkawinan" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="Belum Menikah">Belum Menikah</option>
                                    <option value="Menikah">Menikah</option>
                                    <option value="Cerai Hidup">Cerai Hidup</option>
                                    <option value="Cerai Mati">Cerai Mati</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nama Istri/Suami</label>
                                <input type="text" name="nama_pasangan" class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ukuran Kaos olahraga</label>
                                <select name="ukuran_kaos" class="form-select">
                                    <option value="">Pilih</option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                    <option value="XXL">XXL</option>
                                    <option value="XXXL">XXXL</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-section-header">
                            <i class="fas fa-user-graduate"></i> Data Mentor
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Apakah sudah ada penunjukan Mentor?</label>
                            <select name="sudah_ada_mentor" id="sudah_ada_mentor" class="form-select" required>
                                <option value="">Pilih</option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>

                        <div id="mentor-detail" style="display: none;">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Nama Mentor</label>
                                    <input type="text" name="nama_mentor" class="form-input">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Jabatan Mentor</label>
                                    <input type="text" name="jabatan_mentor" class="form-input">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Nomor Rekening Mentor</label>
                                    <input type="text" name="nomor_rekening_mentor" class="form-input" placeholder="Bank Mandiri, 174xxxxxxxxx a.n Nanang Wijaya">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">NPWP Mentor</label>
                                    <input type="text" name="npwp_mentor" class="form-input">
                                </div>
                            </div>
                        </div>

                        <div class="form-section-header">
                            <i class="fas fa-file-upload"></i> Dokumen Pendukung
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Unggah Scan atau Foto KTP yang berlaku</label>
                            <div class="form-file">
                                <input type="file" name="file_ktp" class="form-file-input" accept=".pdf" required>
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file PDF (maks. 5MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Unggah scan SK CPNS</label>
                                <div class="form-file">
                                    <input type="file" name="file_sk_cpns" class="form-file-input" accept=".pdf" required>
                                    <label class="form-file-label">
                                        <i class="fas fa-cloud-upload-alt"></i><br>
                                        Klik untuk mengunggah file PDF (maks. 5MB)
                                    </label>
                                    <div class="form-file-name"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Unggah scan SPMT</label>
                                <div class="form-file">
                                    <input type="file" name="file_spmt" class="form-file-input" accept=".pdf" required>
                                    <label class="form-file-label">
                                        <i class="fas fa-cloud-upload-alt"></i><br>
                                        Klik untuk mengunggah file PDF (maks. 5MB)
                                    </label>
                                    <div class="form-file-name"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Unggah scan Surat Penyataan Kesediaan</label>
                            <div class="form-file">
                                <input type="file" name="file_surat_kesediaan" class="form-file-input" accept=".pdf" required>
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file PDF (maks. 5MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Unggah Scan Surat Tugas</label>
                            <div class="form-file">
                                <input type="file" name="file_surat_tugas" class="form-file-input" accept=".pdf" required>
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file PDF (maks. 5MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Sasaran Kinerja Pegawai (SKP)</label>
                            <div class="form-file">
                                <input type="file" name="file_skp" class="form-file-input" accept=".pdf">
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file PDF (maks. 5MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Unggah Pas Foto peserta</label>
                            <div class="form-file">
                                <input type="file" name="file_pas_foto" class="form-file-input" accept=".jpg,.jpeg,.png" required>
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file JPG/PNG (maks. 2MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Unggah Surat Keterangan Berbadan Sehat</label>
                            <div class="form-file">
                                <input type="file" name="file_surat_sehat" class="form-file-input" accept=".pdf" required>
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file PDF (maks. 5MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>
                    `;

                // Show/hide mentor details based on selection
                document.getElementById('sudah_ada_mentor').addEventListener('change', function () {
                    document.getElementById('mentor-detail').style.display = this.value === 'Ya' ? 'block' : 'none';
                });

                setupFormInteractions();
            }

            function loadFormPKA() {
                dynamicFormContainer.innerHTML = `
                        <div class="form-section-header">
                            <i class="fas fa-chalkboard-teacher"></i> Form kesediaan PKA
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">NIP/NRP</label>
                                <input type="text" name="nip_nrp" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Nama lengkap gelar</label>
                                <input type="text" name="nama_lengkap" class="form-input" required>
                            </div>
                        </div>

                        <!-- Data Pribadi PKA - lengkap sesuai dokumen -->
                        <div class="form-section-header">
                            <i class="fas fa-user"></i> Data Pribadi
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Agama</label>
                                <select name="agama" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Tempat lahir</label>
                                <input type="text" name="tempat_lahir" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Tanggal lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-input" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Alamat rumah</label>
                            <textarea name="alamat_rumah" class="form-textarea" required></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Alamat kantor</label>
                            <textarea name="alamat_kantor" class="form-textarea" required></textarea>
                        </div>

                        <!-- Data Kepegawaian PKA -->
                        <div class="form-section-header">
                            <i class="fas fa-building"></i> Data Kepegawaian
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Asal instansi</label>
                                <input type="text" name="asal_instansi" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Instansi Detail/Unit Kerja</label>
                                <input type="text" name="unit_kerja" class="form-input" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Provinsi</label>
                                <select name="provinsi" class="form-select" required>
                                    <option value="">Pilih Provinsi</option>
                                    <option value="11">Aceh</option>
                                    <option value="12">Sumatera Utara</option>
                                    <!-- Tambahkan provinsi lainnya -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Kabupaten/Kota</label>
                                <select name="kabupaten" class="form-select" required disabled>
                                    <option value="">Pilih Kabupaten/Kota (Pilih Provinsi Dahulu)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Jabatan</label>
                                <input type="text" name="jabatan" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Eselon</label>
                                <select name="eselon" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="I.a">I.a</option>
                                    <option value="I.b">I.b</option>
                                    <option value="II.a">II.a</option>
                                    <option value="II.b">II.b</option>
                                    <option value="III.a">III.a</option>
                                    <option value="III.b">III.b</option>
                                    <option value="IV.a">IV.a</option>
                                    <option value="IV.b">IV.b</option>
                                    <option value="Non Eselon">Non Eselon</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Unggah Scan fotokopi kelulusan/hasil seleksi calon peserta PKA / Sertifikat/Piagam Penghargaan Terbaik</label>
                            <div class="form-file">
                                <input type="file" name="file_surat_kelulusan_seleksi" class="form-file-input" accept=".pdf" required>
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file PDF (maks. 5MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Tahun Lulus PKP/PIM IV</label>
                                <input type="number" name="tahun_lulus_pkp_pim_iv" class="form-input" min="1900" max="2099" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Pangkat / Golongan Ruang</label>
                                <select name="golongan_ruang" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="I/a">I/a</option>
                                    <option value="I/b">I/b</option>
                                    <option value="I/c">I/c</option>
                                    <option value="I/d">I/d</option>
                                    <option value="II/a">II/a</option>
                                    <option value="II/b">II/b</option>
                                    <option value="II/c">II/c</option>
                                    <option value="II/d">II/d</option>
                                    <option value="III/a">III/a</option>
                                    <option value="III/b">III/b</option>
                                    <option value="III/c">III/c</option>
                                    <option value="III/d">III/d</option>
                                    <option value="IV/a">IV/a</option>
                                    <option value="IV/b">IV/b</option>
                                    <option value="IV/c">IV/c</option>
                                    <option value="IV/d">IV/d</option>
                                    <option value="IV/e">IV/e</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">No WA</label>
                                <input type="tel" name="no_wa" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Email pribadi</label>
                                <input type="email" name="email_pribadi" class="form-input" required>
                            </div>
                        </div>

                        <!-- Data Pendidikan PKA -->
                        <div class="form-section-header">
                            <i class="fas fa-graduation-cap"></i> Data Pendidikan
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Pendidikan terakhir</label>
                                <select name="pendidikan_terakhir" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="SMA">SMA</option>
                                    <option value="D3">D3</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bidang Studi Pendidikan Terakhir</label>
                                <input type="text" name="bidang_studi" class="form-input">
                            </div>
                        </div>

                        <!-- Data Lainnya PKA -->
                        <div class="form-section-header">
                            <i class="fas fa-heart"></i> Data Lainnya
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Status Perkawinan</label>
                                <select name="status_perkawinan" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="Belum Menikah">Belum Menikah</option>
                                    <option value="Menikah">Menikah</option>
                                    <option value="Cerai Hidup">Cerai Hidup</option>
                                    <option value="Cerai Mati">Cerai Mati</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nama Istri/Suami</label>
                                <input type="text" name="nama_pasangan" class="form-input">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Apakah Saudara Merokok ?</label>
                                <select name="perokok" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Olahraga Kegemaran/Hobi</label>
                                <input type="text" name="olahraga_hobi" class="form-input">
                            </div>
                        </div>

                        <!-- Dokumen PKA -->
                        <div class="form-section-header">
                            <i class="fas fa-file-upload"></i> Dokumen Pendukung
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Foto KTP</label>
                            <div class="form-file">
                                <input type="file" name="file_ktp" class="form-file-input" accept=".jpg,.jpeg,.png" required>
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file JPG/PNG (maks. 2MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Tanggal SK Jabatan Terakhir</label>
                                <input type="date" name="tanggal_sk_jabatan" class="form-input" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Unggah Bukti SK Jabatan Terakhir (Definitif)</label>
                                <div class="form-file">
                                    <input type="file" name="file_sk_jabatan" class="form-file-input" accept=".jpg,.jpeg,.png" required>
                                    <label class="form-file-label">
                                        <i class="fas fa-cloud-upload-alt"></i><br>
                                        Klik untuk mengunggah file JPG/PNG (maks. 2MB)
                                    </label>
                                    <div class="form-file-name"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Unggah Bukti SK Pangkat / Golongan Ruang Terakhir</label>
                                <div class="form-file">
                                    <input type="file" name="file_sk_pangkat" class="form-file-input" accept=".jpg,.jpeg,.png" required>
                                    <label class="form-file-label">
                                        <i class="fas fa-cloud-upload-alt"></i><br>
                                        Klik untuk mengunggah file JPG/PNG (maks. 2MB)
                                    </label>
                                    <div class="form-file-name"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Unggah Scan Formulir Kesediaan</label>
                            <div class="form-file">
                                <input type="file" name="file_surat_kesediaan" class="form-file-input" accept=".jpg,.jpeg,.png" required>
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file JPG/PNG (maks. 2MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Unggah Scan Pakta Integritas</label>
                            <div class="form-file">
                                <input type="file" name="file_pakta_integritas" class="form-file-input" accept=".pdf" required>
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file PDF (maks. 5MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Unggah Scan Surat Tugas</label>
                            <div class="form-file">
                                <input type="file" name="file_surat_tugas" class="form-file-input" accept=".pdf" required>
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file PDF (maks. 5MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Nomor Telepon Kantor</label>
                                <input type="tel" name="nomor_telepon_kantor" class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">E-mail Kantor</label>
                                <input type="email" name="email_kantor" class="form-input">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Unggah Pas Foto peserta</label>
                            <div class="form-file">
                                <input type="file" name="file_pas_foto" class="form-file-input" accept=".jpg,.jpeg,.png" required>
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file JPG/PNG (maks. 2MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>

                        <!-- Data Mentor PKA -->
                        <div class="form-section-header">
                            <i class="fas fa-user-graduate"></i> Data Mentor
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Nama Mentor</label>
                                <input type="text" name="nama_mentor" class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Jabatan Mentor</label>
                                <input type="text" name="jabatan_mentor" class="form-input">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Nomor Rekening Mentor</label>
                                <input type="text" name="nomor_rekening_mentor" class="form-input" placeholder="Bank Mandiri, 174xxxxxxxxx a.n Nanang Wijaya" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">NPWP Mentor</label>
                                <input type="text" name="npwp_mentor" class="form-input">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Unggah Form Persetujuan Mentor</label>
                            <div class="form-file">
                                <input type="file" name="file_persetujuan_mentor" class="form-file-input" accept=".pdf" required>
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file PDF (maks. 5MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>

                        <!-- Dokumen Tambahan PKA -->
                        <div class="form-group">
                            <label class="form-label required">Unggah Surat Berbadan Sehat</label>
                            <div class="form-file">
                                <input type="file" name="file_surat_sehat" class="form-file-input" accept=".pdf" required>
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file PDF (maks. 5MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Unggah Surat Pernyataan Tidak Sedang mempertanggungjawabkan Penyelesaian Administrasi</label>
                            <div class="form-file">
                                <input type="file" name="file_surat_pernyataan_administrasi" class="form-file-input" accept=".pdf" required>
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file PDF (maks. 5MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Unggah Surat Keterangan bebas narkoba</label>
                            <div class="form-file">
                                <input type="file" name="file_surat_bebas_narkoba" class="form-file-input" accept=".pdf" required>
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file PDF (maks. 5MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>
                    `;

                setupFormInteractions();
            }

            function setupFormInteractions() {
                // File input handlers
                document.querySelectorAll('.form-file-input').forEach(input => {
                    input.addEventListener('change', function () {
                        const fileName = this.files[0]?.name || 'Belum ada file dipilih';
                        this.parentElement.querySelector('.form-file-name').textContent = fileName;
                    });
                });

                // Provinsi-Kabupaten dependency
                document.addEventListener('change', function (e) {
                    if (e.target && e.target.name === 'provinsi') {
                        const kabSelect = e.target.closest('.form-row').querySelector('select[name="kabupaten"]');
                        if (kabSelect) {
                            // Simulasi load kabupaten
                            kabSelect.innerHTML = '<option value="">Memuat...</option>';
                            kabSelect.disabled = true;

                            setTimeout(() => {
                                kabSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                                const kabupatenList = [
                                    'Kota Jakarta Pusat', 'Kota Jakarta Selatan', 'Kota Jakarta Timur',
                                    'Kota Jakarta Barat', 'Kota Jakarta Utara', 'Kabupaten Bogor',
                                    'Kota Bogor', 'Kabupaten Bekasi', 'Kota Bekasi', 'Kabupaten Tangerang',
                                    'Kota Tangerang', 'Kota Tangerang Selatan', 'Kabupaten Bandung',
                                    'Kota Bandung', 'Kota Surabaya', 'Kota Semarang', 'Kota Yogyakarta'
                                ];

                                kabupatenList.forEach(kab => {
                                    const option = document.createElement('option');
                                    option.value = kab;
                                    option.textContent = kab;
                                    kabSelect.appendChild(option);
                                });

                                kabSelect.disabled = false;
                            }, 500);
                        }
                    }
                });
            }

            // Navigation
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

            // Form submission
            document.getElementById('pendaftaranForm').addEventListener('submit', function (e) {
                e.preventDefault();

                submitFormBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
                submitFormBtn.disabled = true;

                // Simulate form submission
                setTimeout(() => {
                    alert('Pendaftaran berhasil dikirim! Data Anda telah direkam.');
                    submitFormBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Pendaftaran';
                    submitFormBtn.disabled = false;

                    // Reset form after 2 seconds
                    setTimeout(() => {
                        moveToStep(1);
                        document.querySelectorAll('.training-card').forEach(card => {
                            card.classList.remove('selected');
                        });
                        angkatanSelect.innerHTML = '<option value="">Pilih Angkatan</option>';
                        jenisPelatihanInput.value = '';
                        selectedTraining = null;
                        selectedAngkatan = null;
                        dynamicFormContainer.innerHTML = '';
                    }, 2000);
                }, 2000);
            });
        });
    </script>
@endpush