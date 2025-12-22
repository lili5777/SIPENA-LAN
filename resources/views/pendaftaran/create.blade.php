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
                            @foreach ($jenisPelatihan as $jp)
                                <div class="training-card" data-id="{{ $jp->id }}" data-kode="{{ $jp->kode_pelatihan }}">
                                    <div class="training-icon">
                                        @if($jp->kode_pelatihan == 'PKN_TK_II')
                                            <i class="fas fa-user-tie"></i>
                                        @elseif($jp->kode_pelatihan == 'PD_CPNS')
                                            <i class="fas fa-user-graduate"></i>
                                        @elseif($jp->kode_pelatihan == 'PKA')
                                            <i class="fas fa-chalkboard-teacher"></i>
                                        @else
                                            <i class="fas fa-book"></i>
                                        @endif
                                    </div>
                                    <h3 class="training-name">{{ $jp->nama_pelatihan }}</h3>
                                    <p class="training-code">{{ $jp->kode_pelatihan }}</p>
                                    <button type="button" class="training-select-btn">Pilih Pelatihan Ini</button>
                                </div>
                            @endforeach
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
                            <div class="form-placeholder">
                                <i class="fas fa-spinner fa-spin"></i>
                                <p>Memuat formulir...</p>
                            </div>
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
            max-width: 900px;
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
            margin-bottom: 25px;
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
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-select:focus,
        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
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

        .form-placeholder {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray-color);
        }

        .form-placeholder i {
            font-size: 3rem;
            margin-bottom: 20px;
            color: var(--accent-color);
        }

        .form-section-header {
            font-size: 1.4rem;
            color: var(--primary-color);
            margin: 30px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
            font-weight: 600;
        }

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

        /* Loading Animation */
        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        .loading {
            animation: pulse 1.5s infinite;
        }

        /* Success Animation */
        .success-animation {
            display: none;
            text-align: center;
            padding: 40px;
        }

        .success-animation i {
            font-size: 4rem;
            color: var(--success-color);
            margin-bottom: 20px;
            animation: bounce 1s ease infinite alternate;
        }

        @keyframes bounce {
            from {
                transform: translateY(0);
            }

            to {
                transform: translateY(-10px);
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

                fetch(`/api/angkatan/${jenisId}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
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
                            option.dataset.kuota = angkatan.kuota || 'Tidak tersedia';
                            option.dataset.status = angkatan.status || 'Aktif';
                            angkatanSelect.appendChild(option);
                        });

                        angkatanSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error loading angkatan:', error);
                        angkatanSelect.innerHTML = '<option value="">Gagal memuat angkatan</option>';
                        showNotification('error', 'Gagal memuat data angkatan. Silakan coba lagi.');
                    });
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
                        <div class="form-placeholder">
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
                }, 500);
            }

            function loadFormPKN_TK_II() {
                dynamicFormContainer.innerHTML = `
                        <div class="form-section-header">
                            <i class="fas fa-user-tie"></i> Formulir PKN TK II
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Nama Lengkap (Beserta Gelar)</label>
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
                        </div>

                        <div class="form-section-header">
                            <i class="fas fa-building"></i> Data Kepegawaian
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Asal Instansi</label>
                                <input type="text" name="asal_instansi" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Jabatan</label>
                                <input type="text" name="jabatan" class="form-input" required>
                            </div>
                        </div>

                        <div class="form-section-header">
                            <i class="fas fa-file-upload"></i> Dokumen Pendukung
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Unggah SK Jabatan Terakhir</label>
                            <div class="form-file">
                                <input type="file" name="file_sk_jabatan" class="form-file-input" required>
                                <label class="form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i><br>
                                    Klik untuk mengunggah file (PDF, maks. 5MB)
                                </label>
                                <div class="form-file-name"></div>
                            </div>
                        </div>

                        <!-- Add more fields as needed -->
                    `;

                // Add file input handlers
                document.querySelectorAll('.form-file-input').forEach(input => {
                    input.addEventListener('change', function () {
                        const fileName = this.files[0]?.name || 'Belum ada file dipilih';
                        this.parentElement.querySelector('.form-file-name').textContent = fileName;
                    });
                });
            }

            function loadFormPD_CPNS() {
                dynamicFormContainer.innerHTML = `
                        <div class="form-section-header">
                            <i class="fas fa-user-graduate"></i> Formulir PD CPNS
                        </div>

                        <!-- Add PD CPNS specific fields here -->
                        <p>Formulir PD CPNS sedang dimuat...</p>
                    `;
            }

            function loadFormPKA() {
                dynamicFormContainer.innerHTML = `
                        <div class="form-section-header">
                            <i class="fas fa-chalkboard-teacher"></i> Formulir PKA
                        </div>

                        <!-- Add PKA specific fields here -->
                        <p>Formulir PKA sedang dimuat...</p>
                    `;
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
                    showNotification('success', 'Pendaftaran berhasil dikirim!');
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
                    }, 2000);
                }, 2000);
            });

            // Notification function
            function showNotification(type, message) {
                const notification = document.createElement('div');
                notification.className = `notification notification-${type}`;
                notification.innerHTML = `
                        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                        ${message}
                    `;

                notification.style.cssText = `
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        background: ${type === 'success' ? 'var(--success-color)' : 'var(--danger-color)'};
                        color: white;
                        padding: 15px 25px;
                        border-radius: 6px;
                        box-shadow: var(--shadow);
                        z-index: 1000;
                        animation: slideIn 0.3s ease;
                    `;

                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }

            // Add CSS for notifications
            const style = document.createElement('style');
            style.textContent = `
                    @keyframes slideIn {
                        from { transform: translateX(100%); opacity: 0; }
                        to { transform: translateX(0); opacity: 1; }
                    }
                    @keyframes slideOut {
                        from { transform: translateX(0); opacity: 1; }
                        to { transform: translateX(100%); opacity: 0; }
                    }
                `;
            document.head.appendChild(style);
        });
    </script>
@endpush