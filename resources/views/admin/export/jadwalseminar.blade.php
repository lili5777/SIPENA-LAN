@extends('admin.partials.layout')

@section('title', 'Export Jadwal Seminar - LAN Pusjar SKMP')
@section('page-title', 'Export Jadwal Seminar')

@section('styles')
    <style>
        /* Gunakan style yang sama dengan komposisipeserta.blade.php */
        /* Copy semua CSS dari file komposisipeserta */
        :root {
            --primary-color: #1a3a6c;
            --secondary-color: #2c5282;
            --dark-color: #1e293b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --teal-color: #14b8a6;
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
            grid-template-columns: repeat(5, 1fr) auto;
            gap: 1.5rem;
            align-items: end;
        }

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

        .form-select, .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            color: var(--dark-color);
            background: white;
            transition: all 0.3s ease;
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%231a3a6c' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px;
        }

        .form-select:focus, .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 58, 108, 0.1);
        }

        .btn-export {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
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
            box-shadow: 0 4px 12px rgba(26, 58, 108, 0.3);
        }

        .btn-export:disabled {
            background: linear-gradient(135deg, #94a3b8, #cbd5e1);
            cursor: not-allowed;
        }

        .export-info {
            margin-top: 1.5rem;
            padding: 1.25rem;
            background: #eff6ff;
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

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background: #fee2e2;
            border-left: 5px solid #ef4444;
            color: #7f1d1d;
        }

        .filter-active {
            border-color: var(--primary-color) !important;
            background: rgba(26, 58, 108, 0.05) !important;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive */
        @media (max-width: 1600px) {
            .export-form {
                grid-template-columns: repeat(4, 1fr);
            }
            .export-button-container {
                grid-column: span 4;
                display: flex;
                justify-content: center;
            }
        }

        @media (max-width: 992px) {
            .export-form {
                grid-template-columns: repeat(2, 1fr);
            }
            .export-button-container {
                grid-column: span 2;
            }
        }

        @media (max-width: 768px) {
            .export-form {
                grid-template-columns: 1fr;
            }
            .export-button-container {
                grid-column: span 1;
            }
        }
    </style>
@endsection

@section('content')
    <div class="export-section">
        {{-- ALERT ERROR --}}
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Error!</strong> {{ session('error') }}
            </div>
        @endif

        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-calendar-alt"></i>
                <span>Export Jadwal Seminar</span>
            </div>
        </div>

        <form action="{{ route('admin.export.jadwal-seminar') }}" method="GET" class="export-form" id="exportForm">
            <!-- Kategori -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-tag"></i>
                    Kategori
                    <span class="badge badge-optional">OPSIONAL</span>
                </label>
                <select name="kategori" class="form-select" id="kategori">
                    <option value="">Semua Kategori</option>
                    <option value="PNBP">PNBP</option>
                    <option value="FASILITASI">FASILITASI</option>
                </select>
            </div>

            <!-- Wilayah -->
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
                       placeholder="Ketik wilayah fasilitasi...">
                
                <datalist id="wilayahList">
                    <option value="DKI Jakarta">
                    <option value="Jawa Barat">
                    <option value="Jawa Tengah">
                    <option value="Jawa Timur">
                    <!-- tambahkan provinsi lainnya -->
                </datalist>
            </div>

            <!-- Jenis Pelatihan -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-graduation-cap"></i>
                    Jenis Pelatihan
                    <span class="badge badge-optional">OPSIONAL</span>
                </label>
                <select name="jenis_pelatihan" class="form-select">
                    <option value="">Semua Jenis Pelatihan</option>
                    <option value="LATSAR">LATSAR</option>
                    <option value="PKA">PKA</option>
                    <option value="PKN TK II">PKN TK II</option>
                    <option value="PKP">PKP</option>
                    <option value="PIM TK II">PIM TK II</option>
                    <option value="PIM TK III">PIM TK III</option>
                    <option value="PIM TK IV">PIM TK IV</option>
                </select>
            </div>

            <!-- Angkatan -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-users"></i>
                    Angkatan
                    <span class="badge badge-optional">OPSIONAL</span>
                </label>
                <select name="angkatan" class="form-select">
                    <option value="">Semua Angkatan</option>
                    @php
                        $romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X',
                                   'XI', 'XII', 'XIII', 'XIV', 'XV', 'XVI', 'XVII', 'XVIII', 'XIX', 'XX'];
                    @endphp
                    @foreach($romawi as $rom)
                        <option value="Angkatan {{ $rom }}">Angkatan {{ $rom }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tahun -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-calendar"></i>
                    Tahun
                    <span class="badge badge-optional">OPSIONAL</span>
                </label>
                <select name="tahun" class="form-select">
                    <option value="">Semua Tahun</option>
                    @for($year = date('Y'); $year >= 2020; $year--)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
            </div>

            <!-- Submit Button -->
            <div class="export-button-container">
                <button type="submit" class="btn-export" id="exportBtn">
                    <i class="fas fa-file-excel"></i>
                    Export Jadwal
                </button>
            </div>
        </form>

        <div class="export-info">
            <div class="export-info-header">
                <i class="fas fa-info-circle"></i>
                <h4>Informasi Export Jadwal Seminar</h4>
            </div>
            <p style="margin: 0; color: #64748b;">
                Export ini akan menghasilkan file Excel berisi jadwal seminar peserta dengan kolom:
                <strong>No, Waktu (kosong), NDH, Nama, NIP, Jabatan, Instansi, Nama Mentor, Jabatan Mentor, Coach (kosong)</strong>.
                Data diurutkan berdasarkan Angkatan (Romawi) dan NDH.
            </p>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const kategoriSelect = document.getElementById('kategori');
            const wilayahGroup = document.getElementById('wilayahGroup');
            const wilayahInput = document.getElementById('wilayah');
            const exportBtn = document.getElementById('exportBtn');
            const exportForm = document.getElementById('exportForm');

            function toggleWilayah() {
                if (kategoriSelect.value === 'FASILITASI') {
                    wilayahGroup.style.display = 'block';
                    wilayahInput.classList.add('filter-active');
                } else {
                    wilayahGroup.style.display = 'none';
                    wilayahInput.value = '';
                    wilayahInput.classList.remove('filter-active');
                }
            }

            kategoriSelect.addEventListener('change', toggleWilayah);
            toggleWilayah();

            exportForm.addEventListener('submit', function () {
                exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                exportBtn.disabled = true;

                setTimeout(() => {
                    exportBtn.innerHTML = '<i class="fas fa-file-excel"></i> Export Jadwal';
                    exportBtn.disabled = false;
                }, 10000);
            });
        });
    </script>
@endsection