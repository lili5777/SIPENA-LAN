@extends('admin.partials.layout')

@section('title', 'Export Komposisi Peserta - LAN Pusjar SKMP')
@section('page-title', 'Export Komposisi Peserta')

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
            background: linear-gradient(135deg, var(--info-color), #60a5fa);
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
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            background: linear-gradient(135deg, #2563eb, #3b82f6);
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
            padding: 1rem;
            background: #eff6ff;
            border-radius: 8px;
            border-left: 4px solid var(--info-color);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .export-info i {
            color: var(--info-color);
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .export-info p {
            margin: 0;
            color: #475569;
            font-size: 0.95rem;
        }

        .komposisi-preview {
            margin-top: 1.5rem;
            padding: 1.25rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 2px dashed #cbd5e1;
        }

        .komposisi-preview h4 {
            color: var(--primary-color);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .komposisi-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .komposisi-item {
            background: white;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            border-left: 3px solid var(--primary-color);
            font-size: 0.9rem;
            color: #64748b;
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
        @media (max-width: 992px) {
            .export-form {
                grid-template-columns: repeat(2, 1fr);
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

            .btn-export {
                grid-column: span 1;
                max-width: 100%;
            }

            .komposisi-list {
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
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-chart-pie"></i>
                <span>Export Komposisi Peserta</span>
            </div>
        </div>

        <form action="{{ route('admin.export.komposisi') }}" method="GET" class="export-form">
            <!-- Jenis Pelatihan -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-graduation-cap"></i>
                    Jenis Pelatihan
                </label>
                <select name="jenis_pelatihan" class="form-select" id="jenis_pelatihan">
                    <option value="">Semua Jenis Pelatihan</option>
                    <option value="LATSAR" {{ request('jenis_pelatihan') == 'LATSAR' ? 'selected' : '' }}>LATSAR</option>
                    <option value="PKA" {{ request('jenis_pelatihan') == 'PKA' ? 'selected' : '' }}>PKA</option>
                    <option value="PKN TK II" {{ request('jenis_pelatihan') == 'PKN TK II' ? 'selected' : '' }}>PKN TK II
                    </option>
                    <option value="PKP" {{ request('jenis_pelatihan') == 'PKP' ? 'selected' : '' }}>PKP</option>
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
                        <option value="Angkatan {{ $rom }}" {{ request('angkatan') == 'Angkatan ' . $rom ? 'selected' : '' }}>
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
                        <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="btn-export">
                    <i class="fas fa-file-excel"></i>
                    Export Komposisi
                </button>
            </div>
        </form>

        <div class="export-info">
            <i class="fas fa-info-circle"></i>
            <p>
                <strong>Info:</strong> Export ini akan mengelompokkan peserta berdasarkan Jenis Kelamin, Pendidikan,
                Pangkat/Golongan, dan Asal Instansi dengan persentase masing-masing kategori.
            </p>
        </div>

        <div class="komposisi-preview">
            <h4>
                <i class="fas fa-list-check"></i>
                Data yang akan diekspor:
            </h4>
            <div class="komposisi-list">
                <div class="komposisi-item">
                    <i class="fas fa-venus-mars"></i> A. Jenis Kelamin
                </div>
                <div class="komposisi-item">
                    <i class="fas fa-user-graduate"></i> B. Pendidikan Terakhir
                </div>
                <div class="komposisi-item">
                    <i class="fas fa-ranking-star"></i> C. Pangkat/Golongan
                </div>
                <div class="komposisi-item">
                    <i class="fas fa-building"></i> D. Asal Instansi
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const exportBtn = document.querySelector('.btn-export');
            const exportForm = document.querySelector('.export-form');

            if (exportForm && exportBtn) {
                exportForm.addEventListener('submit', function () {
                    exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                    exportBtn.disabled = true;

                    // Re-enable after 5 seconds if form submission fails
                    setTimeout(() => {
                        exportBtn.innerHTML = '<i class="fas fa-file-excel"></i> Export Komposisi';
                        exportBtn.disabled = false;
                    }, 5000);
                });
            }
        });
    </script>
@endsection