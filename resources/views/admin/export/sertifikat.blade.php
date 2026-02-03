{{-- resources/views/admin/export/sertifikat.blade.php --}}
@extends('admin.partials.layout')

@section('title', 'Export Sertifikat Peserta - LAN Pusjar SKMP')
@section('page-title', 'Export Sertifikat Peserta')

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

        /* Badge styling */
        .form-label .badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-weight: 500;
            margin-left: 0.5rem;
        }

        .badge-required {
            background: var(--danger-color);
            color: white;
        }

        .badge-optional {
            background: var(--warning-color);
            color: white;
        }

        .badge-conditional {
            background: var(--info-color);
            color: white;
        }

        .btn-export {
            background: linear-gradient(135deg, #10b981, #059669);
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
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            background: linear-gradient(135deg, #059669, #047857);
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

        .export-info {
            margin-top: 1.5rem;
            padding: 1.25rem;
            background: #f0f9ff;
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
        }

        .export-info-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .export-info-header i {
            color: var(--primary-color);
            font-size: 1.25rem;
        }

        .export-info-header h4 {
            margin: 0;
            color: var(--dark-color);
            font-size: 1.05rem;
            font-weight: 600;
        }

        .template-comparison {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 1rem;
        }

        .template-item {
            background: white;
            padding: 1rem;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .template-item h5 {
            margin: 0 0 0.5rem 0;
            color: var(--primary-color);
            font-size: 0.95rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .template-item h5 i {
            font-size: 0.9rem;
        }

        .template-item ul {
            margin: 0;
            padding-left: 1.25rem;
            font-size: 0.85rem;
            color: #475569;
        }

        .template-item ul li {
            margin-bottom: 0.25rem;
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

            .template-comparison {
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
                <i class="fas fa-certificate"></i>
                <span>Export Sertifikat Peserta (PDF)</span>
            </div>
        </div>

        <form action="{{ route('admin.export.sertifikat') }}" method="GET" class="export-form" target="_blank" id="exportForm">
            <!-- Kategori (Opsional) -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-tag"></i>
                    Kategori
                    <span class="badge badge-optional">OPSIONAL</span>
                </label>
                <select name="kategori" class="form-select" id="kategori">
                    <option value="">Semua Kategori</option>
                    <option value="PNBP" {{ request('kategori') == 'PNBP' ? 'selected' : '' }}>PNBP</option>
                    <option value="FASILITASI" {{ request('kategori') == 'FASILITASI' ? 'selected' : '' }}>FASILITASI</option>
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
                       value="{{ request('wilayah') }}">

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
                    <option value="LATSAR" {{ request('jenis_pelatihan') == 'LATSAR' ? 'selected' : '' }}>LATSAR</option>
                    <option value="PKA" {{ request('jenis_pelatihan') == 'PKA' ? 'selected' : '' }}>PKA</option>
                    <option value="PKN TK II" {{ request('jenis_pelatihan') == 'PKN TK II' ? 'selected' : '' }}>PKN TK II</option>
                    <option value="PKP" {{ request('jenis_pelatihan') == 'PKP' ? 'selected' : '' }}>PKP</option>
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
                            'XLI', 'XLII', 'XLIII', 'XLIV', 'XLV', 'XLVI', 'XLVII', 'XLVIII', 'XLIX', 'L'
                        ];
                    @endphp
                    @foreach($romawi as $rom)
                        <option value="Angkatan {{ $rom }}" {{ request('angkatan') == 'Angkatan ' . $rom ? 'selected' : '' }}>
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
                        <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Submit Button -->
            <div class="export-button-container">
                <button type="submit" class="btn-export" id="exportBtn">
                    <i class="fas fa-certificate"></i>
                    Export Sertifikat
                </button>
            </div>
        </form>

        <div class="export-info">
            <div class="export-info-header">
                <i class="fas fa-info-circle"></i>
                <h4>Informasi Filter Export Sertifikat</h4>
            </div>

            <div class="template-comparison">
                <div class="template-item">
                    <h5>
                        <i class="fas fa-tags"></i>
                        Filter Kategori & Wilayah
                    </h5>
                    <ul>
                        <li><strong>PNBP</strong> - Sertifikat peserta dengan kategori PNBP</li>
                        <li><strong>FASILITASI</strong> - Sertifikat peserta dengan kategori Fasilitasi</li>
                        <li><strong>Wilayah</strong> - Muncul otomatis jika kategori=FASILITASI</li>
                        <li><strong>Semua Kategori</strong> - Kosongkan untuk semua kategori</li>
                    </ul>
                </div>

                <div class="template-item">
                    <h5>
                        <i class="fas fa-filter"></i>
                        Filter Lainnya
                    </h5>
                    <ul>
                        <li><strong>Jenis Pelatihan</strong> - Filter berdasarkan jenis pelatihan</li>
                        <li><strong>Angkatan</strong> - Filter berdasarkan nama angkatan (I - L)</li>
                        <li><strong>Tahun</strong> - Filter berdasarkan tahun angkatan</li>
                        <li><strong>Kombinasi</strong> - Bisa gunakan beberapa filter sekaligus</li>
                    </ul>
                </div>
            </div>

            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #e2e8f0;">
                <h5 style="color: var(--primary-color); font-size: 0.95rem; font-weight: 600; margin-bottom: 0.75rem;">
                    <i class="fas fa-exclamation-circle"></i> Catatan Penting
                </h5>
                <p style="font-size: 0.9rem; color: #64748b; margin: 0;">
                    <strong>Perhatian:</strong><br>
                    1. Export akan menghasilkan file PDF sertifikat landscape, satu halaman per peserta.<br>
                    2. Semua filter bersifat opsional (kecuali wilayah muncul jika kategori=FASILITASI).<br>
                    3. Data akan difilter berdasarkan kombinasi semua filter yang dipilih.<br>
                    4. Jika tidak memilih filter tertentu, semua data akan diekspor.<br>
                    5. Setiap sertifikat berisi foto, nama, NDH, angkatan, dan kabupaten asal.
                </p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const exportBtn = document.getElementById('exportBtn');
            const exportForm = document.getElementById('exportForm');
            const kategoriSelect = document.getElementById('kategori');
            const wilayahGroup = document.getElementById('wilayahGroup');
            const wilayahInput = document.getElementById('wilayah');

            // ====================
            // LOGIKA TAMPIL/HIDE WILAYAH
            // ====================
            function toggleWilayah() {
                const kategori = kategoriSelect.value;

                if (kategori === 'FASILITASI') {
                    wilayahGroup.style.display = 'block';
                    wilayahInput.placeholder = "Ketik wilayah fasilitasi...";
                    wilayahInput.classList.add('filter-active');
                } else {
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
            }

            // Inisialisasi pertama kali berdasarkan value yang ada di URL
            toggleWilayah();

            // Event listener untuk perubahan kategori
            kategoriSelect.addEventListener('change', toggleWilayah);

            // ====================
            // HIGHLIGHT FILTER AKTIF SAAT LOAD
            // ====================
            function highlightActiveFilters() {
                const urlParams = new URLSearchParams(window.location.search);

                if (urlParams.get('kategori')) {
                    kategoriSelect.classList.add('filter-active');
                }

                if (urlParams.get('wilayah')) {
                    wilayahInput.classList.add('filter-active');
                }

                const jenisPelatihanSelect = document.getElementById('jenis_pelatihan');
                if (urlParams.get('jenis_pelatihan')) {
                    jenisPelatihanSelect.classList.add('filter-active');
                }

                const angkatanSelect = document.getElementById('angkatan');
                if (urlParams.get('angkatan')) {
                    angkatanSelect.classList.add('filter-active');
                }

                const tahunSelect = document.getElementById('tahun');
                if (urlParams.get('tahun')) {
                    tahunSelect.classList.add('filter-active');
                }
            }

            highlightActiveFilters();

            // ====================
            // VALIDASI & SUBMIT
            // ====================
            if (exportForm && exportBtn) {
                exportForm.addEventListener('submit', function (e) {
                    // Semua filter opsional, tidak ada validasi wajib

                    // Tampilkan loading
                    const originalText = exportBtn.innerHTML;
                    exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                    exportBtn.disabled = true;

                    // Hilangkan highlight filter aktif
                    document.querySelectorAll('.filter-active').forEach(el => {
                        el.classList.remove('filter-active');
                    });

                    // Re-enable setelah 10 detik jika gagal
                    setTimeout(() => {
                        exportBtn.innerHTML = originalText;
                        exportBtn.disabled = false;
                    }, 10000);
                });
            }

            // ====================
            // TOGGLE HIGHLIGHT SAAT FILTER BERUBAH
            // ====================
            document.querySelectorAll('.form-select').forEach(select => {
                select.addEventListener('change', function () {
                    if (this.value) {
                        this.classList.add('filter-active');
                    } else {
                        this.classList.remove('filter-active');
                    }
                });
            });

            // Untuk input wilayah
            if (wilayahInput) {
                wilayahInput.addEventListener('input', function () {
                    if (this.value.trim()) {
                        this.classList.add('filter-active');
                    } else {
                        this.classList.remove('filter-active');
                    }
                });

                wilayahInput.addEventListener('focus', function () {
                    this.setAttribute('list', 'wilayahList');
                });
            }

            // ====================
            // AUTO-SUBMIT DENGAN ENTER
            // ====================
            document.querySelectorAll('.form-select, .form-control').forEach(input => {
                input.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        exportForm.submit();
                    }
                });
            });

            // ====================
            // RESPONSIVE ADJUSTMENT
            // ====================
            function adjustFormLayout() {
                if (window.innerWidth <= 768) {
                    if (wilayahInput) {
                        wilayahInput.placeholder = "Ketik wilayah...";
                    }
                }
            }

            window.addEventListener('resize', adjustFormLayout);
            adjustFormLayout();
        });
    </script>
@endsection