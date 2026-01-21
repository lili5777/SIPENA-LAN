@extends('admin.partials.layout')

@section('title', 'Export Foto Peserta - LAN Pusjar SKMP')
@section('page-title', 'Export Foto Peserta')

@section('styles')
    <style>
        :root {
            --primary-color: #1a3a6c;
            --secondary-color: #2c5282;
            --dark-color: #1e293b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
        }

        .export-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            margin-bottom: 2rem;
            animation: fadeIn 0.8s ease-out;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e2e8f0;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        .section-title i {
            color: var(--primary-color);
            background: rgba(26, 58, 108, 0.1);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .export-form {
            display: grid;
            grid-template-columns: repeat(3, 1fr) auto;
            gap: 1.5rem;
            align-items: end;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark-color);
            font-size: 0.95rem;
        }

        .form-label i {
            color: var(--primary-color);
            margin-right: 0.5rem;
        }

        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            color: var(--dark-color);
            background: white;
            transition: all 0.3s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%231a3a6c' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 58, 108, 0.1);
        }

        .form-select:hover {
            border-color: #cbd5e1;
        }

        .btn-export {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            height: 48px;
            min-width: 150px;
        }

        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
            background: linear-gradient(135deg, #047857, #059669);
        }

        .btn-export:active {
            transform: translateY(0);
        }

        .btn-export:disabled {
            background: linear-gradient(135deg, #94a3b8, #cbd5e1);
            cursor: not-allowed;
            transform: none;
        }

        .btn-export i {
            font-size: 1.1rem;
        }

        .export-stats {
            margin-top: 1.5rem;
            padding: 1rem;
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border-radius: 10px;
            border-left: 4px solid var(--info-color);
        }

        .stats-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .stats-title i {
            color: var(--info-color);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .stat-item {
            text-align: center;
            padding: 0.75rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #64748b;
        }

        .export-options {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-top: 1.5rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 10px;
            border: 2px dashed #cbd5e1;
        }

        .option-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .option-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: var(--dark-color);
            font-size: 0.95rem;
        }

        .option-label i {
            color: var(--primary-color);
        }

        .option-hint {
            font-size: 0.85rem;
            color: #64748b;
            margin-left: 1.75rem;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #cbd5e1;
            border-radius: 4px;
            cursor: pointer;
            accent-color: var(--primary-color);
        }

        .form-check-label {
            font-size: 0.95rem;
            color: var(--dark-color);
            cursor: pointer;
        }

        .export-info {
            margin-top: 1.5rem;
            padding: 1rem;
            background: #fef3c7;
            border-radius: 8px;
            border-left: 4px solid var(--warning-color);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .export-info i {
            color: var(--warning-color);
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .export-info p {
            margin: 0;
            color: #475569;
            font-size: 0.95rem;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            display: none;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #e2e8f0;
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        }

        .loading-text {
            font-size: 1.1rem;
            color: var(--dark-color);
            font-weight: 600;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .export-form {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .export-options {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .btn-export {
                grid-column: span 2;
                width: 100%;
                max-width: 300px;
                margin: 0 auto;
            }
        }

        @media (max-width: 768px) {
            .export-form {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .btn-export {
                grid-column: span 1;
                max-width: 100%;
            }
        }

        @media (max-width: 576px) {
            .export-section {
                padding: 1.25rem 1rem;
            }

            .section-title {
                font-size: 1.25rem;
            }

            .section-title i {
                width: 35px;
                height: 35px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="export-section">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-images"></i>
                <span>Export Foto Peserta</span>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics Section -->
        <div class="export-stats" id="exportStats">
            <div class="stats-title">
                <i class="fas fa-chart-bar"></i>
                <span>Statistik Data</span>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value" id="totalPeserta">0</div>
                    <div class="stat-label">Total Peserta</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="totalFoto">0</div>
                    <div class="stat-label">Peserta dengan Foto</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="persentase">0%</div>
                    <div class="stat-label">Persentase Foto</div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <form action="{{ route('admin.export.foto') }}" method="POST" class="export-form" id="exportFotoForm">
            @csrf
            <!-- Jenis Pelatihan -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-graduation-cap"></i>
                    Jenis Pelatihan
                </label>
                <select name="jenis_pelatihan" class="form-select" id="jenis_pelatihan">
                    <option value="">Semua Jenis Pelatihan</option>
                    <option value="LATSAR" {{ old('jenis_pelatihan') == 'LATSAR' ? 'selected' : '' }}>LATSAR</option>
                    <option value="PKA" {{ old('jenis_pelatihan') == 'PKA' ? 'selected' : '' }}>PKA</option>
                    <option value="PKN TK II" {{ old('jenis_pelatihan') == 'PKN TK II' ? 'selected' : '' }}>PKN TK II
                    </option>
                    <option value="PKP" {{ old('jenis_pelatihan') == 'PKP' ? 'selected' : '' }}>PKP</option>
                </select>
            </div>

            <!-- Angkatan -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-users"></i>
                    Angkatan
                </label>
                <select name="angkatan" class="form-select" id="angkatan">
                    <option value="">Semua Angkatan</option>
                    @php
$romawi = [
    'I',
    'II',
    'III',
    'IV',
    'V',
    'VI',
    'VII',
    'VIII',
    'IX',
    'X',
    'XI',
    'XII',
    'XIII',
    'XIV',
    'XV',
    'XVI',
    'XVII',
    'XVIII',
    'XIX',
    'XX',
    'XXI',
    'XXII',
    'XXIII',
    'XXIV',
    'XXV',
    'XXVI',
    'XXVII',
    'XXVIII',
    'XXIX',
    'XXX',
    'XXXI',
    'XXXII',
    'XXXIII',
    'XXXIV',
    'XXXV',
    'XXXVI',
    'XXXVII',
    'XXXVIII',
    'XXXIX',
    'XL',
    'XLI',
    'XLII',
    'XLIII',
    'XLIV',
    'XLV',
    'XLVI',
    'XLVII',
    'XLVIII',
    'XLIX',
    'L',
    'LI',
    'LII',
    'LIII',
    'LIV',
    'LV',
    'LVI',
    'LVII',
    'LVIII',
    'LIX',
    'LX',
    'LXI',
    'LXII',
    'LXIII',
    'LXIV',
    'LXV',
    'LXVI',
    'LXVII',
    'LXVIII',
    'LXIX',
    'LXX'
];
                    @endphp
                    @foreach($romawi as $rom)
                        <option value="Angkatan {{ $rom }}" {{ old('angkatan') == 'Angkatan ' . $rom ? 'selected' : '' }}>
                            Angkatan {{ $rom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tahun -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-calendar"></i>
                    Tahun
                </label>
                <select name="tahun" class="form-select" id="tahun">
                    <option value="">Semua Tahun</option>
                    @php
$currentYear = date('Y');
$startYear = 2020;
                    @endphp
                    @for($year = $currentYear; $year >= $startYear; $year--)
                        <option value="{{ $year }}" {{ old('tahun') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="btn-export" id="exportBtn">
                    <i class="fas fa-file-archive"></i>
                    Export Foto (ZIP)
                </button>
            </div>
        </form>

       

        <!-- Info Section -->
        <div class="export-info">
            <i class="fas fa-info-circle"></i>
            <p>
                <strong>Info Export Foto:</strong> Sistem akan mengumpulkan semua foto peserta berdasarkan filter yang
                dipilih.
                Foto akan diunduh langsung dari Google Drive dan dikompres dalam format ZIP. Proses mungkin memakan waktu
                beberapa menit tergantung jumlah foto. Pastikan koneksi internet stabil.
            </p>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <div class="loading-text" id="loadingText">Memproses export foto...</div>
        <div class="mt-2 text-muted" id="loadingDetail">Harap tunggu, proses mungkin memakan waktu beberapa menit</div>
        <div class="progress mt-3" style="width: 300px; height: 8px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressBar" style="width: 0%"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const exportBtn = document.getElementById('exportBtn');
            const exportForm = document.getElementById('exportFotoForm');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const loadingText = document.getElementById('loadingText');
            const progressBar = document.getElementById('progressBar');
            const loadingDetail = document.getElementById('loadingDetail');

            // Elements for statistics
            const totalPesertaEl = document.getElementById('totalPeserta');
            const totalFotoEl = document.getElementById('totalFoto');
            const persentaseEl = document.getElementById('persentase');
            const exportStats = document.getElementById('exportStats');

            // Function to load statistics
            function loadStatistics() {
                const jenisPelatihan = document.getElementById('jenis_pelatihan').value;
                const angkatan = document.getElementById('angkatan').value;
                const tahun = document.getElementById('tahun').value;

                fetch('{{ route("export.foto.stats") }}?' + new URLSearchParams({
                    jenis_pelatihan: jenisPelatihan,
                    angkatan: angkatan,
                    tahun: tahun
                }), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        totalPesertaEl.textContent = data.total_peserta || 0;
                        totalFotoEl.textContent = data.total_dengan_foto || 0;
                        persentaseEl.textContent = data.persentase ? data.persentase + '%' : '0%';

                        // Show/hide stats section
                        exportStats.style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Error loading statistics:', error);
                        exportStats.style.display = 'none';
                    });
            }

            // Load initial statistics
            loadStatistics();

            // Update statistics when filters change
            document.getElementById('jenis_pelatihan').addEventListener('change', loadStatistics);
            document.getElementById('angkatan').addEventListener('change', loadStatistics);
            document.getElementById('tahun').addEventListener('change', loadStatistics);

            if (exportForm && exportBtn) {
                exportForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    // Show loading overlay
                    loadingOverlay.style.display = 'flex';
                    loadingText.textContent = 'Mengumpulkan data peserta...';
                    progressBar.style.width = '10%';

                    // Disable button
                    exportBtn.disabled = true;
                    exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

                    // Create FormData
                    const formData = new FormData(this);

                    // Add additional options
                    // formData.append('use_nip', document.getElementById('use_nip').checked ? 1 : 0);
                    // formData.append('include_angkatan', document.getElementById('include_angkatan').checked ? 1 : 0);
                    // formData.append('skip_missing', document.getElementById('skip_missing').checked ? 1 : 0);
                    // formData.append('add_info', document.getElementById('add_info').checked ? 1 : 0);

                    // Simulate progress
                    let progress = 10;
                    const progressInterval = setInterval(() => {
                        progress += 2;
                        progressBar.style.width = progress + '%';

                        if (progress >= 40) {
                            loadingText.textContent = 'Mengunduh foto dari Google Drive...';
                        } else if (progress >= 70) {
                            loadingText.textContent = 'Mengkompres file ke ZIP...';
                        } else if (progress >= 90) {
                            loadingText.textContent = 'Menyiapkan file untuk download...';
                        }

                        if (progress >= 100) {
                            clearInterval(progressInterval);
                        }
                    }, 500);

                    // Send request
                    // Send request
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                // Coba parse sebagai JSON untuk error message
                                return response.json().then(data => {
                                    throw new Error(data.error || 'Network response was not ok');
                                }).catch(() => {
                                    throw new Error('Terjadi kesalahan pada server');
                                });
                            }
                            return response.blob();
                        })
                        .then(blob => {
                            clearInterval(progressInterval);
                            progressBar.style.width = '100%';
                            loadingText.textContent = 'Download dimulai...';

                            // Create download link
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.style.display = 'none';
                            a.href = url;

                            // Get filename from Content-Disposition header or use default
                            let filename = 'foto_peserta_' + new Date().getTime() + '.zip';

                            a.download = filename;
                            document.body.appendChild(a);
                            a.click();

                            // Cleanup
                            window.URL.revokeObjectURL(url);
                            document.body.removeChild(a);

                            // Hide loading overlay after delay
                            setTimeout(() => {
                                loadingOverlay.style.display = 'none';
                                loadingText.textContent = 'Memproses export foto...';
                                progressBar.style.width = '0%';

                                // Re-enable button
                                exportBtn.disabled = false;
                                exportBtn.innerHTML = '<i class="fas fa-file-archive"></i> Export Foto (ZIP)';
                            }, 2000);

                            // Show success message
                            showAlert('success', 'Export foto berhasil! File sedang diunduh.');
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            clearInterval(progressInterval);

                            // Hide loading overlay
                            loadingOverlay.style.display = 'none';

                            // Re-enable button
                            exportBtn.disabled = false;
                            exportBtn.innerHTML = '<i class="fas fa-file-archive"></i> Export Foto (ZIP)';

                            // Show error message
                            showAlert('error', error.message);
                        });
                });
            }

            // Function to show alerts
            function showAlert(type, message) {
                // Remove existing alerts
                const existingAlerts = document.querySelectorAll('.alert');
                existingAlerts.forEach(alert => alert.remove());

                // Create new alert
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
                alertDiv.setAttribute('role', 'alert');

                const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
                alertDiv.innerHTML = `
                        <i class="fas ${icon}"></i> ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;

                // Insert after section header
                const sectionHeader = document.querySelector('.section-header');
                sectionHeader.parentNode.insertBefore(alertDiv, sectionHeader.nextSibling);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            }
        });
    </script>
@endsection