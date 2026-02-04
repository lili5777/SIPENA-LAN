@extends('admin.partials.layout')

@section('title', 'Export Data Peserta - LAN Pusjar SKMP')
@section('page-title', 'Export Data Peserta')

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

        /* Form dalam satu baris */
        .export-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            align-items: end;
            margin-bottom: 2rem;
        }

        /* Container untuk semua filter dalam satu baris */
        .filters-container {
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 1.5rem;
            align-items: end;
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        }

        /* Untuk tombol export di baris terpisah */
        .export-button-container {
            grid-column: 1 / -1;
            display: flex;
            justify-content: flex-end;
            margin-top: 1rem;
            padding-top: 1.5rem;
            border-top: 2px solid #f1f5f9;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark-color);
            font-size: 0.9rem;
        }

        .form-label i {
            color: var(--primary-color);
            margin-right: 0.5rem;
            font-size: 0.9rem;
        }

        .form-label .badge {
            font-size: 0.65rem;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            font-weight: 500;
            margin-left: 0.3rem;
            vertical-align: middle;
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

        .form-select,
        .form-control {
            width: 100%;
            padding: 0.625rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.9rem;
            color: var(--dark-color);
            background: white;
            transition: all 0.3s ease;
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%231a3a6c' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 14px;
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

        .form-select.required,
        .form-control.required {
            border-color: var(--danger-color);
        }

        .form-select.conditional,
        .form-control.conditional {
            border-color: var(--info-color);
        }

        .btn-export {
            background: linear-gradient(135deg, var(--success-color), #34d399);
            color: white;
            border: none;
            padding: 0.75rem 2.5rem;
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
            min-width: 180px;
        }

        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
            background: linear-gradient(135deg, #0da271, #10b981);
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

        /* Form text helper */
        .form-text {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.75rem;
            color: #6b7280;
        }

        /* Datalist styling */
        datalist {
            display: none;
        }

        .form-control[list] {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%231a3a6c' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 14px;
        }

        /* Tag container untuk informasi filter aktif */
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
            padding: 1rem;
            background: #f1f5f9;
            border-radius: 8px;
        }

        .filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--primary-color);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .filter-tag .remove-filter {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.7rem;
            transition: all 0.2s ease;
        }

        .filter-tag .remove-filter:hover {
            background: rgba(255, 255, 255, 0.3);
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
        @media (max-width: 1800px) {
            .filters-container {
                grid-template-columns: repeat(5, 1fr);
            }
        }

        @media (max-width: 1600px) {
            .filters-container {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 1400px) {
            .filters-container {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 1200px) {
            .filters-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.2rem;
            }
        }

        @media (max-width: 768px) {
            .export-form {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }

            .filters-container {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .export-button-container {
                justify-content: center;
            }

            .btn-export {
                width: 100%;
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

            .filters-container {
                padding: 1rem;
            }

            .btn-export {
                padding: 0.75rem 1.5rem;
                min-width: auto;
            }
        }

        /* Style untuk kondisi filter aktif */
        .filter-active {
            border-color: var(--success-color) !important;
            background: rgba(16, 185, 129, 0.05) !important;
        }

        /* Animasi untuk filter aktif */
        .filter-active-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }
    </style>
@endsection

@section('content')
    <div class="export-section">
        {{-- ALERT ERROR --}}
        @if (session('error'))
            <div class="alert alert-danger" style="
                background: #fee2e2;
                border-left: 5px solid #ef4444;
                color: #7f1d1d;
                padding: 1rem 1.25rem;
                border-radius: 8px;
                margin-bottom: 1.5rem;
                animation: fadeIn 0.5s ease-out;
            ">
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
        @if (session('success'))
            <div class="alert alert-success" style="
                background: #dcfce7;
                border-left: 5px solid #10b981;
                color: #14532d;
                padding: 1rem 1.25rem;
                border-radius: 8px;
                margin-bottom: 1.5rem;
                animation: fadeIn 0.5s ease-out;
            ">
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
                <i class="fas fa-file-export"></i>
                <span>Export Data Peserta</span>
            </div>
        </div>

        <form action="{{ route('admin.export.peserta') }}" method="GET" class="export-form" id="exportForm">
            <!-- Container untuk semua filter dalam satu baris -->
            <div class="filters-container" id="filtersContainer">
                <!-- Template (Wajib) -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-file-alt"></i>
                        Template
                        <span class="badge badge-required">WAJIB</span>
                    </label>
                    <select name="template" class="form-select" id="template" required>
                        <option value="">-- Pilih Template --</option>
                        <option value="form_registrasi" {{ request('template') == 'form_registrasi' ? 'selected' : '' }}>
                            Form Registrasi
                        </option>
                        <option value="smart_bangkom" {{ request('template') == 'smart_bangkom' ? 'selected' : '' }}>
                            Smart Bangkom
                        </option>
                    </select>
                </div>

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

                <!-- Wilayah (Opsional) -->
                <div class="form-group" id="wilayahGroup">
                    <label class="form-label">
                        <i class="fas fa-map-marker-alt"></i>
                        Wilayah
                        <span class="badge badge-optional">OPSIONAL</span>
                    </label>
                    <!-- Input text dengan datalist untuk autocomplete -->
                    <input type="text" 
                           name="wilayah" 
                           class="form-control" 
                           id="wilayah"
                           list="wilayahList"
                           placeholder="Ketik wilayah..."
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
                        <option value="">Semua Jenis</option>
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
                                'I','II','III','IV','V','VI','VII','VIII','IX','X',
                                'XI','XII','XIII','XIV','XV','XVI','XVII','XVIII','XIX','XX',
                                'XXI','XXII','XXIII','XXIV','XXV','XXVI','XXVII','XXVIII','XXIX','XXX',
                                'XXXI','XXXII','XXXIII','XXXIV','XXXV','XXXVI','XXXVII','XXXVIII','XXXIX','XL',
                                'XLI','XLII','XLIII','XLIV','XLV','XLVI','XLVII','XLVIII','XLIX','L',
                                'LI','LII','LIII','LIV','LV','LVI','LVII','LVIII','LIX','LX',
                                'LXI','LXII','LXIII','LXIV','LXV','LXVI','LXVII','LXVIII','LXIX','LXX',
                                'LXXI','LXXII','LXXIII','LXXIV','LXXV','LXXVI','LXXVII','LXXVIII','LXXIX','LXXX'
                            ];
                        @endphp

                        @for ($i = 0; $i < 80; $i++)
                            <option value="Angkatan {{ $romawi[$i] }}"
                                {{ request('angkatan') == 'Angkatan '.$romawi[$i] ? 'selected' : '' }}>
                                Angkatan {{ $romawi[$i] }}
                            </option>
                        @endfor

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
            </div>

            <!-- Submit Button di baris terpisah -->
            <div class="export-button-container">
                <button type="submit" class="btn-export" id="exportBtn">
                    <i class="fas fa-file-excel"></i>
                    Export Data
                </button>
            </div>
        </form>

        {{-- Tampilkan filter aktif jika ada --}}
        @php
            $activeFilters = [];
            if(request('template')) $activeFilters['template'] = request('template');
            if(request('kategori') && request('kategori') != '') $activeFilters['kategori'] = request('kategori');
            if(request('wilayah') && request('wilayah') != '') $activeFilters['wilayah'] = request('wilayah');
            if(request('jenis_pelatihan') && request('jenis_pelatihan') != '') $activeFilters['jenis_pelatihan'] = request('jenis_pelatihan');
            if(request('angkatan') && request('angkatan') != '') $activeFilters['angkatan'] = request('angkatan');
            if(request('tahun') && request('tahun') != '') $activeFilters['tahun'] = request('tahun');
        @endphp
        
        @if(count($activeFilters) > 1) {{-- Lebih dari 1 karena template selalu ada --}}
            <div class="active-filters">
                <strong style="color: var(--primary-color); font-size: 0.9rem;">Filter Aktif:</strong>
                @foreach($activeFilters as $key => $value)
                    @if($key != 'template') {{-- Jangan tampilkan template --}}
                        @php
                            $label = '';
                            $icon = '';
                            switch($key) {
                                case 'kategori':
                                    $label = 'Kategori';
                                    $icon = 'tag';
                                    break;
                                case 'wilayah':
                                    $label = 'Wilayah';
                                    $icon = 'map-marker-alt';
                                    break;
                                case 'jenis_pelatihan':
                                    $label = 'Jenis Pelatihan';
                                    $icon = 'graduation-cap';
                                    break;
                                case 'angkatan':
                                    $label = 'Angkatan';
                                    $icon = 'users';
                                    break;
                                case 'tahun':
                                    $label = 'Tahun';
                                    $icon = 'calendar';
                                    break;
                            }
                        @endphp
                        <div class="filter-tag">
                            <i class="fas fa-{{ $icon }}"></i>
                            {{ $label }}: {{ $value }}
                            <button type="button" class="remove-filter" data-filter="{{ $key }}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                @endforeach
                @if(count($activeFilters) > 1)
                    <div class="filter-tag" style="background: var(--warning-color);">
                        <i class="fas fa-filter"></i>
                        {{ count($activeFilters) - 1 }} Filter Aktif
                        <button type="button" class="remove-filter" id="clearAllFilters">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
            </div>
        @endif

        <div class="export-info">
            <div class="export-info-header">
                <i class="fas fa-info-circle"></i>
                <h4>Informasi Filter Export</h4>
            </div>

            <div class="template-comparison">
                <div class="template-item">
                    <h5>
                        <i class="fas fa-tags"></i>
                        Filter Kategori & Wilayah
                    </h5>
                    <ul>
                        <li><strong>PNBP</strong> - Peserta dengan kategori PNBP (Non Fasilitasi)</li>
                        <li><strong>FASILITASI</strong> - Peserta dengan kategori Fasilitasi</li>
                        <li><strong>Wilayah</strong> - Input text (bisa ketik bebas atau pilih dari daftar)</li>
                        <li><strong>Kombinasi</strong> - Bisa gunakan kategori dan wilayah bersamaan</li>
                    </ul>
                </div>

                <div class="template-item">
                    <h5>
                        <i class="fas fa-filter"></i>
                        Filter Lainnya
                    </h5>
                    <ul>
                        <li><strong>Jenis Pelatihan</strong> - Filter berdasarkan jenis pelatihan</li>
                        <li><strong>Angkatan</strong> - Filter berdasarkan nama angkatan (I - XX)</li>
                        <li><strong>Tahun</strong> - Filter berdasarkan tahun angkatan (2020 - {{ date('Y') }})</li>
                        <li><strong>Kombinasi</strong> - Bisa gunakan semua filter sekaligus</li>
                    </ul>
                </div>
            </div>

            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #e2e8f0;">
                <h5 style="color: var(--primary-color); font-size: 0.95rem; font-weight: 600; margin-bottom: 0.75rem;">
                    <i class="fas fa-exclamation-circle"></i> Penting!
                </h5>
                <p style="font-size: 0.9rem; color: #64748b; margin: 0;">
                    <strong>Catatan:</strong><br>
                    1. <strong>Template Export wajib dipilih</strong> untuk menentukan format file output.<br>
                    2. Semua filter lainnya bersifat opsional (bisa dikosongkan).<br>
                    3. Data akan difilter berdasarkan kombinasi semua filter yang dipilih.<br>
                    4. Wilayah menggunakan pencarian partial match (LIKE).<br>
                    5. Filter aktif akan ditampilkan sebagai tag di atas form.
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
            const templateSelect = document.getElementById('template');
            const kategoriSelect = document.getElementById('kategori');
            const wilayahGroup = document.getElementById('wilayahGroup');
            const wilayahInput = document.getElementById('wilayah');
            const filtersContainer = document.getElementById('filtersContainer');
            
            // ====================
            // LOGIKA TAMPIL/HIDE WILAYAH
            // ====================
            function toggleWilayah() {
                const kategori = kategoriSelect.value;
                
                if (kategori === 'FASILITASI') {
                    // Tampilkan wilayah jika FASILITASI
                    wilayahGroup.style.display = 'block';
                    wilayahInput.placeholder = "Masukkan wilayah fasilitasi...";
                    wilayahInput.classList.add('filter-active');
                } else {
                    // Sembunyikan jika bukan FASILITASI
                    wilayahGroup.style.display = 'none';
                    wilayahInput.value = '';
                    wilayahInput.classList.remove('filter-active');
                }
            }

            // Inisialisasi pertama kali
            toggleWilayah();

            // Event listener untuk perubahan kategori
            kategoriSelect.addEventListener('change', toggleWilayah);

            // ====================
            // HIGHLIGHT FILTER AKTIF
            // ====================
            function highlightActiveFilters() {
                // Reset semua filter
                document.querySelectorAll('.form-select, .form-control').forEach(el => {
                    el.classList.remove('filter-active', 'filter-active-animation');
                });
                
                // Highlight filter yang memiliki nilai
                const urlParams = new URLSearchParams(window.location.search);
                
                // Template
                if (urlParams.get('template')) {
                    templateSelect.classList.add('filter-active');
                }
                
                // Kategori
                if (urlParams.get('kategori')) {
                    kategoriSelect.classList.add('filter-active', 'filter-active-animation');
                }
                
                // Wilayah
                if (urlParams.get('wilayah')) {
                    wilayahInput.classList.add('filter-active', 'filter-active-animation');
                }
                
                // Jenis Pelatihan
                const jenisPelatihanSelect = document.getElementById('jenis_pelatihan');
                if (urlParams.get('jenis_pelatihan')) {
                    jenisPelatihanSelect.classList.add('filter-active', 'filter-active-animation');
                }
                
                // Angkatan
                const angkatanSelect = document.getElementById('angkatan');
                if (urlParams.get('angkatan')) {
                    angkatanSelect.classList.add('filter-active', 'filter-active-animation');
                }
                
                // Tahun
                const tahunSelect = document.getElementById('tahun');
                if (urlParams.get('tahun')) {
                    tahunSelect.classList.add('filter-active', 'filter-active-animation');
                }
            }

            // Panggil fungsi highlight saat halaman dimuat
            highlightActiveFilters();

            // ====================
            // VALIDASI FORM
            // ====================
            if (exportForm && exportBtn) {
                exportForm.addEventListener('submit', function (e) {
                    // Validasi template
                    if (!templateSelect.value) {
                        e.preventDefault();
                        alert('Silakan pilih template export terlebih dahulu!');
                        templateSelect.classList.add('required');
                        templateSelect.focus();
                        return false;
                    }
                    
                    // Tampilkan loading
                    const originalText = exportBtn.innerHTML;
                    exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                    exportBtn.disabled = true;
                    
                    // Hilangkan animasi pada semua filter
                    document.querySelectorAll('.filter-active-animation').forEach(el => {
                        el.classList.remove('filter-active-animation');
                    });

                    // Re-enable setelah 10 detik jika gagal
                    setTimeout(() => {
                        exportBtn.innerHTML = originalText;
                        exportBtn.disabled = false;
                    }, 10000);
                });
            }

            // Remove required class when template is selected
            if (templateSelect) {
                templateSelect.addEventListener('change', function () {
                    if (this.value) {
                        this.classList.remove('required');
                        this.classList.add('filter-active');
                    } else {
                        this.classList.remove('filter-active');
                    }
                });
            }

            // ====================
            // TOGGLE HIGHLIGHT SAAT FILTER BERUBAH
            // ====================
            document.querySelectorAll('.form-select, .form-control').forEach(el => {
                el.addEventListener('change', function() {
                    if (this.value) {
                        this.classList.add('filter-active');
                    } else {
                        this.classList.remove('filter-active', 'filter-active-animation');
                    }
                });
                
                el.addEventListener('input', function() {
                    if (this.value) {
                        this.classList.add('filter-active');
                    } else {
                        this.classList.remove('filter-active', 'filter-active-animation');
                    }
                });
            });

            // ====================
            // HAPUS FILTER DARI TAG
            // ====================
            document.querySelectorAll('.remove-filter').forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    
                    if (filter === 'clearAllFilters') {
                        // Hapus semua filter (kecuali template)
                        document.getElementById('kategori').value = '';
                        document.getElementById('wilayah').value = '';
                        document.getElementById('jenis_pelatihan').value = '';
                        document.getElementById('angkatan').value = '';
                        document.getElementById('tahun').value = '';
                        
                        // Hapus highlight
                        document.querySelectorAll('.form-select, .form-control').forEach(el => {
                            el.classList.remove('filter-active', 'filter-active-animation');
                        });
                        
                        // Template tetap aktif
                        if (templateSelect.value) {
                            templateSelect.classList.add('filter-active');
                        }
                    } else {
                        // Hapus filter spesifik
                        const selectElement = document.getElementById(filter);
                        if (selectElement) {
                            selectElement.value = '';
                            selectElement.classList.remove('filter-active', 'filter-active-animation');
                        }
                        
                        // Untuk input wilayah
                        if (filter === 'wilayah') {
                            document.getElementById('wilayah').value = '';
                            document.getElementById('wilayah').classList.remove('filter-active', 'filter-active-animation');
                        }
                    }
                    
                    // Submit form untuk refresh data
                    exportForm.submit();
                });
            });

            // ====================
            // AUTOCOMPLETE WILAYAH
            // ====================
            if (wilayahInput) {
                // Tambahkan event listener untuk menampilkan autocomplete
                wilayahInput.addEventListener('focus', function() {
                    this.setAttribute('list', 'wilayahList');
                });
                
                // Highlight saat diketik
                wilayahInput.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.classList.add('filter-active');
                    } else {
                        this.classList.remove('filter-active', 'filter-active-animation');
                    }
                });
            }

            // ====================
            // ENTER UNTUK SUBMIT
            // ====================
            document.querySelectorAll('.form-select, .form-control').forEach(el => {
                el.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        exportForm.submit();
                    }
                });
            });

            // ====================
            // RESPONSIVE ADJUSTMENT
            // ====================
            function adjustFiltersLayout() {
                if (window.innerWidth <= 768) {
                    // Pada mobile, ubah placeholder menjadi lebih pendek
                    if (wilayahInput) {
                        wilayahInput.placeholder = "Ketik wilayah...";
                    }
                }
            }

            // Panggil saat resize
            window.addEventListener('resize', adjustFiltersLayout);
            // Panggil saat load
            adjustFiltersLayout();
        });
    </script>
@endsection