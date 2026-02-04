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

        /* Grid layout untuk semua filter */
        .export-form {
            display: grid;
            grid-template-columns: repeat(5, 1fr) auto;
            gap: 1.5rem;
            align-items: end;
        }

        /* Container untuk tombol export */
        .export-button-container {
            grid-column: span 1;
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

        /* Badge untuk jenis filter */
        .form-label .badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-weight: 500;
            margin-left: 0.5rem;
        }

        .badge-optional {
            background: var(--warning-color);
            color: white;
        }

        .badge-conditional {
            background: var(--info-color);
            color: white;
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

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            color: var(--dark-color);
            background: white;
            transition: all 0.3s ease;
        }

        .form-select:focus,
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 58, 108, 0.1);
        }

        .form-select:hover,
        .form-control:hover {
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
            width: 100%;
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
            padding: 1.5rem;
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border-radius: 10px;
            border-left: 4px solid var(--info-color);
            margin-bottom: 1.5rem;
        }

        .stats-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
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
            padding: 1rem;
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

        /* Datalist styling */
        datalist {
            display: none;
        }

        .form-control[list] {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%231a3a6c' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px;
        }

        /* Form text helper */
        .form-text {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.75rem;
            color: #6b7280;
        }

        .export-info {
            margin-top: 1.5rem;
            padding: 1.25rem;
            background: #fef3c7;
            border-radius: 8px;
            border-left: 4px solid var(--warning-color);
        }

        .export-info-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .export-info-header i {
            color: var(--warning-color);
            font-size: 1.25rem;
        }

        .export-info-header h4 {
            margin: 0;
            color: var(--dark-color);
            font-size: 1.05rem;
            font-weight: 600;
        }

        .export-details {
            font-size: 0.9rem;
            color: #475569;
            line-height: 1.5;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
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
            margin-bottom: 0.5rem;
        }

        .loading-detail {
            font-size: 0.9rem;
            color: #64748b;
            text-align: center;
        }

        .progress {
            width: 300px;
            height: 8px;
            margin-top: 1rem;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(135deg, #059669, #10b981);
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        /* Style untuk filter aktif */
        .filter-active {
            border-color: var(--success-color) !important;
            background: rgba(16, 185, 129, 0.05) !important;
        }

        /* Alert styling */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            animation: fadeIn 0.5s ease-out;
        }

        .alert-danger {
            background: #fee2e2;
            border-left: 5px solid #ef4444;
            color: #7f1d1d;
        }

        .alert-success {
            background: #dcfce7;
            border-left: 5px solid #10b981;
            color: #14532d;
        }

        .alert .fas {
            margin-right: 0.75rem;
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
        @media (max-width: 1600px) {
            .export-form {
                grid-template-columns: repeat(4, 1fr);
                gap: 1.2rem;
            }
            
            .export-button-container {
                grid-column: span 4;
                display: flex;
                justify-content: center;
            }
            
            .btn-export {
                width: auto;
                min-width: 200px;
            }
        }

        @media (max-width: 1200px) {
            .export-form {
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
            }
            
            .export-button-container {
                grid-column: span 3;
            }
        }

        @media (max-width: 992px) {
            .export-form {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .export-button-container {
                grid-column: span 2;
            }

            .btn-export {
                width: 100%;
                max-width: 300px;
                margin: 0 auto;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .export-form {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }

            .export-button-container {
                grid-column: span 1;
            }

            .btn-export {
                max-width: 100%;
            }

            .stats-grid {
                grid-template-columns: 1fr;
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
            
            .export-stats {
                padding: 1rem;
            }
            
            .stat-item {
                padding: 0.75rem;
            }
            
            .progress {
                width: 250px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="export-section">
        {{-- ALERT ERROR --}}
        @if(session('error'))
            <div class="alert alert-danger">
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Error!</strong>
                </div>
                <div style="margin-top:0.5rem;">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        {{-- ALERT SUCCESS --}}
        @if(session('success'))
            <div class="alert alert-success">
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <i class="fas fa-check-circle"></i>
                    <strong>Sukses!</strong>
                </div>
                <div style="margin-top:0.5rem;">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-images"></i>
                <span>Export Foto Peserta</span>
            </div>
        </div>

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
            
            <!-- Kategori (Opsional) -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-tag"></i>
                    Kategori
                    <span class="badge badge-optional">OPSIONAL</span>
                </label>
                <select name="kategori" class="form-select" id="kategori">
                    <option value="">Semua Kategori</option>
                    <option value="PNBP" {{ old('kategori') == 'PNBP' ? 'selected' : '' }}>PNBP</option>
                    <option value="FASILITASI" {{ old('kategori') == 'FASILITASI' ? 'selected' : '' }}>FASILITASI</option>
                </select>
            </div>

            <!-- Wilayah (Kondisional - muncul jika kategori FASILITASI) -->
            <div class="form-group" id="wilayahGroup" style="display: none;">
                <label class="form-label">
                    <i class="fas fa-map-marker-alt"></i>
                    Wilayah
                    <span class="badge badge-conditional">KONDISIONAL</span>
                </label>
                <input type="text" 
                       name="wilayah" 
                       class="form-control" 
                       id="wilayah"
                       list="wilayahList"
                       placeholder="Ketik wilayah fasilitasi..."
                       value="{{ old('wilayah') }}">
                
                <!-- Datalist untuk autocomplete -->
                <datalist id="wilayahList">
                    <option value="DKI Jakarta">
                    <option value="Jawa Barat">
                    <option value="Jawa Tengah">
                    <option value="Jawa Timur">
                    <option value="Banten">
                    <option value="Bali">
                    <option value="Sumatera Utara">
                    <option value="Sumatera Barat">
                    <option value="Sumatera Selatan">
                    <option value="Kalimantan Timur">
                    <option value="Kalimantan Selatan">
                    <option value="Sulawesi Selatan">
                    <option value="Sulawesi Utara">
                    <option value="Papua">
                    <option value="Papua Barat">
                    <option value="Nusa Tenggara Barat">
                    <option value="Nusa Tenggara Timur">
                    <option value="Riau">
                    <option value="Kepulauan Riau">
                    <option value="Jambi">
                    <option value="Bengkulu">
                    <option value="Lampung">
                    <option value="Kalimantan Barat">
                    <option value="Kalimantan Tengah">
                    <option value="Kalimantan Utara">
                    <option value="Sulawesi Tengah">
                    <option value="Sulawesi Tenggara">
                    <option value="Sulawesi Barat">
                    <option value="Gorontalo">
                    <option value="Maluku">
                    <option value="Maluku Utara">
                </datalist>
                
            </div>

            <!-- Jenis Pelatihan (Opsional) -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-graduation-cap"></i>
                    Jenis Pelatihan
                    <span class="badge badge-optional">OPSIONAL</span>
                </label>
                <select name="jenis_pelatihan" class="form-select" id="jenis_pelatihan">
                    <option value="">Semua Jenis Pelatihan</option>
                    <option value="LATSAR" {{ old('jenis_pelatihan') == 'LATSAR' ? 'selected' : '' }}>LATSAR</option>
                    <option value="PKA" {{ old('jenis_pelatihan') == 'PKA' ? 'selected' : '' }}>PKA</option>
                    <option value="PKN TK II" {{ old('jenis_pelatihan') == 'PKN TK II' ? 'selected' : '' }}>PKN TK II</option>
                    <option value="PKP" {{ old('jenis_pelatihan') == 'PKP' ? 'selected' : '' }}>PKP</option>
                    <option value="PIM TK II" {{ old('jenis_pelatihan') == 'PIM TK II' ? 'selected' : '' }}>PIM TK II</option>
                    <option value="PIM TK III" {{ old('jenis_pelatihan') == 'PIM TK III' ? 'selected' : '' }}>PIM TK III</option>
                    <option value="PIM TK IV" {{ old('jenis_pelatihan') == 'PIM TK IV' ? 'selected' : '' }}>PIM TK IV</option>
                </select>
            </div>

            <!-- Angkatan (Opsional) -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-users"></i>
                    Angkatan
                    <span class="badge badge-optional">OPSIONAL</span>
                </label>
                <select name="angkatan" class="form-select" id="angkatan">
                    <option value="">Semua Angkatan</option>
                    @php
                        $romawi = [
                            'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X',
                            'XI', 'XII', 'XIII', 'XIV', 'XV', 'XVI', 'XVII', 'XVIII', 'XIX', 'XX',
                            'XXI', 'XXII', 'XXIII', 'XXIV', 'XXV', 'XXVI', 'XXVII', 'XXVIII', 'XXIX', 'XXX',
                            'XXXI', 'XXXII', 'XXXIII', 'XXXIV', 'XXXV', 'XXXVI', 'XXXVII', 'XXXVIII', 'XXXIX', 'XL',
                            'XLI', 'XLII', 'XLIII', 'XLIV', 'XLV', 'XLVI', 'XLVII', 'XLVIII', 'XLIX', 'L',
                            'LI', 'LII', 'LIII', 'LIV', 'LV', 'LVI', 'LVII', 'LVIII', 'LIX', 'LX',
                            'LXI', 'LXII', 'LXIII', 'LXIV', 'LXV', 'LXVI', 'LXVII', 'LXVIII', 'LXIX', 'LXX',
                            'LXXI', 'LXXII', 'LXXIII', 'LXXIV', 'LXXV', 'LXXVI', 'LXXVII', 'LXXVIII', 'LXXIX', 'LXXX'
                        ];

                    @endphp
                    @foreach($romawi as $rom)
                        <option value="Angkatan {{ $rom }}" {{ old('angkatan') == 'Angkatan ' . $rom ? 'selected' : '' }}>
                            Angkatan {{ $rom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tahun (Opsional) -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-calendar"></i>
                    Tahun
                    <span class="badge badge-optional">OPSIONAL</span>
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
            <div class="export-button-container">
                <button type="submit" class="btn-export" id="exportBtn">
                    <i class="fas fa-file-archive"></i>
                    Export Foto (ZIP)
                </button>
            </div>
        </form>

        <!-- Info Section -->
        <div class="export-info">
            <div class="export-info-header">
                <i class="fas fa-info-circle"></i>
                <h4>Informasi Export Foto Peserta</h4>
            </div>
            <div class="export-details">
                <p><strong>Catatan Export Foto:</strong></p>
                <ul style="margin-top: 0.5rem; padding-left: 1.25rem;">
                    <li>Sistem akan mengumpulkan semua foto peserta berdasarkan filter yang dipilih</li>
                    <li>Foto akan diunduh langsung dari Google Drive dan dikompres dalam format ZIP</li>
                    <li>Proses mungkin memakan waktu beberapa menit tergantung jumlah foto</li>
                    <li>Pastikan koneksi internet stabil selama proses export berlangsung</li>
                    <li>File ZIP akan berisi foto dengan format nama: NIP_Nama_Peserta.jpg</li>
                    <li>Filter kategori dan wilayah membantu mempersempit hasil export</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <div class="loading-text" id="loadingText">Memproses export foto...</div>
        <div class="loading-detail" id="loadingDetail">Harap tunggu, proses mungkin memakan waktu beberapa menit</div>
        <div class="progress">
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

            // Elements for kategori and wilayah
            const kategoriSelect = document.getElementById('kategori');
            const wilayahGroup = document.getElementById('wilayahGroup');
            const wilayahInput = document.getElementById('wilayah');

            // ====================
            // LOGIKA TAMPIL/HIDE WILAYAH
            // ====================
            function toggleWilayah() {
                const kategori = kategoriSelect.value;
                
                if (kategori === 'FASILITASI') {
                    // Tampilkan wilayah jika kategori FASILITASI
                    wilayahGroup.style.display = 'block';
                    wilayahInput.placeholder = "Ketik wilayah fasilitasi...";
                    
                    // Highlight wilayah input
                    wilayahInput.classList.add('filter-active');
                } else {
                    // Sembunyikan wilayah jika bukan FASILITASI
                    wilayahGroup.style.display = 'none';
                    wilayahInput.value = '';
                    wilayahInput.classList.remove('filter-active');
                }
                
                // Highlight kategori select jika ada nilai
                if (kategori) {
                    kategoriSelect.classList.add('filter-active');
                } else {
                    kategoriSelect.classList.remove('filter-active');
                }
                
                // Load statistics setelah perubahan
                loadStatistics();
            }

            // Inisialisasi pertama kali
            toggleWilayah();

            // Event listener untuk perubahan kategori
            kategoriSelect.addEventListener('change', toggleWilayah);

            // ====================
            // HIGHLIGHT FILTER AKTIF SAAT LOAD
            // ====================
            function highlightActiveFilters() {
                // Cek filter yang memiliki nilai dari URL atau old values
                const urlParams = new URLSearchParams(window.location.search);
                
                // Highlight kategori
                if (kategoriSelect.value) {
                    kategoriSelect.classList.add('filter-active');
                }
                
                // Highlight wilayah (jika ada)
                if (wilayahInput.value) {
                    wilayahInput.classList.add('filter-active');
                }
                
                // Highlight jenis pelatihan
                const jenisPelatihanSelect = document.getElementById('jenis_pelatihan');
                if (jenisPelatihanSelect.value) {
                    jenisPelatihanSelect.classList.add('filter-active');
                }
                
                // Highlight angkatan
                const angkatanSelect = document.getElementById('angkatan');
                if (angkatanSelect.value) {
                    angkatanSelect.classList.add('filter-active');
                }
                
                // Highlight tahun
                const tahunSelect = document.getElementById('tahun');
                if (tahunSelect.value) {
                    tahunSelect.classList.add('filter-active');
                }
            }

            // Panggil fungsi highlight saat halaman dimuat
            highlightActiveFilters();

            // ====================
            // FUNCTION TO LOAD STATISTICS
            // ====================
            function loadStatistics() {
                const kategori = kategoriSelect.value;
                const wilayah = wilayahInput.value;
                const jenisPelatihan = document.getElementById('jenis_pelatihan').value;
                const angkatan = document.getElementById('angkatan').value;
                const tahun = document.getElementById('tahun').value;

                fetch('{{ route("export.foto.stats") }}?' + new URLSearchParams({
                    kategori: kategori,
                    wilayah: wilayah,
                    jenis_pelatihan: jenisPelatihan,
                    angkatan: angkatan,
                    tahun: tahun
                }), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && typeof data === 'object') {
                            totalPesertaEl.textContent = data.total_peserta || 0;
                            totalFotoEl.textContent = data.total_dengan_foto || 0;
                            persentaseEl.textContent = data.persentase ? data.persentase + '%' : '0%';
                            
                            // Show stats section
                            exportStats.style.display = 'block';
                        } else {
                            throw new Error('Invalid response data');
                        }
                    })
                    .catch(error => {
                        console.error('Error loading statistics:', error);
                        // Hide stats section on error
                        exportStats.style.display = 'none';
                    });
            }

            // Load initial statistics
            loadStatistics();

            // Update statistics when filters change
            kategoriSelect.addEventListener('change', loadStatistics);
            document.getElementById('jenis_pelatihan').addEventListener('change', loadStatistics);
            document.getElementById('angkatan').addEventListener('change', loadStatistics);
            document.getElementById('tahun').addEventListener('change', loadStatistics);
            
            // Untuk input wilayah, gunakan debounce
            let debounceTimer;
            wilayahInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(loadStatistics, 500);
                
                // Highlight jika ada value
                if (this.value.trim()) {
                    this.classList.add('filter-active');
                } else {
                    this.classList.remove('filter-active');
                }
            });

            // ====================
            // FORM SUBMISSION HANDLING
            // ====================
            if (exportForm && exportBtn) {
                exportForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    // Validasi minimal satu filter harus dipilih
                    const kategori = kategoriSelect.value;
                    const wilayah = wilayahInput.value;
                    const jenisPelatihan = document.getElementById('jenis_pelatihan').value;
                    const angkatan = document.getElementById('angkatan').value;
                    const tahun = document.getElementById('tahun').value;

                    if (!kategori && !wilayah && !jenisPelatihan && !angkatan && !tahun) {
                        showAlert('error', 'Pilih minimal satu filter untuk export foto!');
                        return;
                    }

                    // Show loading overlay
                    loadingOverlay.style.display = 'flex';
                    loadingText.textContent = 'Mengumpulkan data peserta...';
                    loadingDetail.textContent = 'Harap tunggu, proses mungkin memakan waktu beberapa menit';
                    progressBar.style.width = '10%';
                    progressBar.classList.add('progress-bar-striped', 'progress-bar-animated');

                    // Disable button
                    exportBtn.disabled = true;
                    exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

                    // Create FormData
                    const formData = new FormData(this);

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
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(data => {
                                    throw new Error(data.error || 'Terjadi kesalahan pada server');
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

                            // Generate filename with timestamp and filter info
                            const timestamp = new Date().getTime();
                            let filename = 'foto_peserta_' + timestamp + '.zip';
                            
                            // Add filter info to filename if any
                            const filterParts = [];
                            if (kategori) filterParts.push(kategori);
                            if (wilayah && kategori === 'FASILITASI') filterParts.push(wilayah.replace(/\s+/g, '_'));
                            if (jenisPelatihan) filterParts.push(jenisPelatihan.replace(/\s+/g, '_'));
                            if (angkatan) filterParts.push(angkatan.replace(/\s+/g, '_'));
                            if (tahun) filterParts.push(tahun);
                            
                            if (filterParts.length > 0) {
                                filename = 'foto_peserta_' + filterParts.join('_') + '_' + timestamp + '.zip';
                            }

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
                                loadingDetail.textContent = 'Harap tunggu, proses mungkin memakan waktu beberapa menit';
                                progressBar.style.width = '0%';
                                progressBar.classList.remove('progress-bar-striped', 'progress-bar-animated');

                                // Re-enable button
                                exportBtn.disabled = false;
                                exportBtn.innerHTML = '<i class="fas fa-file-archive"></i> Export Foto (ZIP)';
                            }, 2000);

                            // Show success message
                            showAlert('success', 'Export foto berhasil! File ZIP sedang diunduh.');
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            clearInterval(progressInterval);

                            // Hide loading overlay
                            loadingOverlay.style.display = 'none';
                            progressBar.classList.remove('progress-bar-striped', 'progress-bar-animated');

                            // Re-enable button
                            exportBtn.disabled = false;
                            exportBtn.innerHTML = '<i class="fas fa-file-archive"></i> Export Foto (ZIP)';

                            // Show error message
                            showAlert('error', error.message || 'Terjadi kesalahan saat export foto.');
                        });
                });
            }

            // ====================
            // TOGGLE HIGHLIGHT SAAT FILTER BERUBAH
            // ====================
            document.querySelectorAll('.form-select').forEach(select => {
                select.addEventListener('change', function() {
                    if (this.value) {
                        this.classList.add('filter-active');
                    } else {
                        this.classList.remove('filter-active');
                    }
                });
            });

            // Untuk input wilayah
            if (wilayahInput) {
                wilayahInput.addEventListener('focus', function() {
                    this.setAttribute('list', 'wilayahList');
                });
            }

            // ====================
            // FUNCTION TO SHOW ALERTS
            // ====================
            function showAlert(type, message) {
                // Remove existing alerts
                const existingAlerts = document.querySelectorAll('.alert');
                existingAlerts.forEach(alert => alert.remove());

                // Create new alert
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'}`;
                
                const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
                alertDiv.innerHTML = `
                    <div style="display:flex; align-items:center; gap:0.75rem;">
                        <i class="fas ${icon}"></i>
                        <strong>${type === 'success' ? 'Sukses!' : 'Error!'}</strong>
                    </div>
                    <div style="margin-top:0.5rem;">
                        ${message}
                    </div>
                `;

                // Insert after section header
                const sectionHeader = document.querySelector('.section-header');
                if (sectionHeader && sectionHeader.parentNode) {
                    sectionHeader.parentNode.insertBefore(alertDiv, sectionHeader.nextSibling);
                }

                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            }

            // ====================
            // RESPONSIVE ADJUSTMENT
            // ====================
            function adjustFormLayout() {
                if (window.innerWidth <= 768) {
                    // Pada mobile, ubah placeholder wilayah
                    if (wilayahInput) {
                        wilayahInput.placeholder = "Ketik wilayah...";
                    }
                }
            }

            // Panggil saat resize dan load
            window.addEventListener('resize', adjustFormLayout);
            adjustFormLayout();
        });
    </script>
@endsection