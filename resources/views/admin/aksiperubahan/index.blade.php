@extends('admin.partials.layout')

@section('title', 'Aksi Perubahan - LAN Pusjar SKMP')
@section('page-title', 'Aksi Perubahan')

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

        .welcome-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 20px;
            padding: 2.5rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(26, 58, 108, 0.2);
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
        }

        .welcome-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .welcome-card::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .welcome-header {
            position: relative;
            z-index: 1;
        }

        .welcome-card h2 {
            font-size: 1.9rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .welcome-card p {
            opacity: 0.95;
            margin-bottom: 0.25rem;
            font-size: 1rem;
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

        .btn-edit {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 58, 108, 0.3);
            color: white;
            text-decoration: none;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            color: white;
            border: none;
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            border: none;
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            color: white;
        }

        .content-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .data-grid {
            display: grid;
            gap: 1.5rem;
        }

        .data-item {
            margin-bottom: 1.25rem;
            display: flex;
            flex-direction: column;
        }

        .data-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.35rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .data-value {
            font-size: 1rem;
            font-weight: 500;
            color: #1e293b;
            word-break: break-word;
            line-height: 1.5;
            white-space: pre-wrap;
        }

        .link-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .link-item i {
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .link-text {
            flex: 1;
            word-break: break-all;
        }

        .file-preview {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .file-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-size: 1.25rem;
        }

        .file-info {
            flex: 1;
        }

        .file-name {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
        }

        .file-size {
            font-size: 0.85rem;
            color: #64748b;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #dbeafe;
            color: var(--primary-color);
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-icon:hover {
            background: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }

        .no-data {
            text-align: center;
            padding: 4rem 2rem;
            color: #94a3b8;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 20px;
            border: 2px dashed #cbd5e1;
            margin: 2rem 0;
        }

        .no-data-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, rgba(26, 58, 108, 0.1) 0%, rgba(44, 82, 130, 0.05) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s ease-in-out infinite;
        }

        .no-data-icon i {
            font-size: 3.5rem;
            color: var(--primary-color);
            opacity: 0.6;
        }

        .no-data h4 {
            color: var(--dark-color);
            margin-bottom: 0.75rem;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .no-data p {
            color: #64748b;
            margin-bottom: 2rem;
            font-size: 1rem;
        }

        .btn-add-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 0.875rem 2.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.05rem;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(26, 58, 108, 0.2);
        }

        .btn-add-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(26, 58, 108, 0.3);
            color: white;
            text-decoration: none;
        }

        .btn-add-primary i {
            font-size: 1.2rem;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease;
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fefefe;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 800px;
            /* Diperbesar untuk 2 kolom */
            animation: slideUp 0.3s ease;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .close {
            color: #64748b;
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            background: none;
            border: none;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .close:hover {
            background: #fee2e2;
            color: var(--danger-color);
        }

        /* Grid untuk 2 kolom */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
            /* Ambil full width */
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 58, 108, 0.1);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 58, 108, 0.2);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }

        .btn-loading {
            position: relative;
            pointer-events: none;
        }

        .btn-loading .btn-text {
            opacity: 0;
        }

        .btn-loading .spinner-wrapper {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-hint {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }
        }

        @media (max-width: 768px) {
            .welcome-card {
                padding: 1.75rem 1.25rem;
            }

            .welcome-card h2 {
                font-size: 1.4rem;
            }

            .modal-content {
                width: 95%;
                padding: 1.5rem;
            }

            .section-header {
                flex-direction: column;
                align-items: stretch;
            }

            .form-row {
                grid-template-columns: 1fr;
                /* 1 kolom di mobile */
                gap: 1rem;
            }

            .no-data {
                padding: 3rem 1.5rem;
            }

            .btn-add-primary {
                padding: 0.75rem 2rem;
                font-size: 0.95rem;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Welcome Card -->
    <div class="welcome-card">
        <div class="welcome-header">
            <h2>Aksi Perubahan 🎯</h2>
            <p>Dokumentasi Aksi Perubahan Pelatihan Anda</p>
            @if($pendaftaran)
                <p><i class="fas fa-graduation-cap me-2"></i>{{ $pendaftaran->jenisPelatihan->nama_pelatihan ?? '' }} -
                    {{ $pendaftaran->angkatan->nama_angkatan ?? '' }}
                </p>
            @endif
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    @if ($kunci_judul == 0)
        <div class="no-data">
            <div class="no-data-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h4>Aksi Perubahan Belum Dibuka</h4>
            <p>
                Pengisian Aksi Perubahan belum dibuka oleh penyelenggara pelatihan.
                <br>
                Silakan menunggu informasi selanjutnya.
            </p>
        </div>
    @else
        @if(isset($message))
            <div class="no-data">
                <div class="no-data-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h4>Informasi</h4>
                <p>{{ $message }}</p>
            </div>
        @elseif($aksiPerubahan)
            <!-- Ada Data - Tampilkan -->
            <div class="content-card">
                <div class="section-header">
                    <div class="section-title">
                        <i class="fas fa-project-diagram"></i>
                        <span>Detail Aksi Perubahan</span>
                    </div>
                    <div style="display: flex; gap: 0.75rem;">
                        <button onclick="openEditModal()" class="btn-edit">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button onclick="openPengajuanModal()" class="btn-success">
                            <i class="fas fa-file-signature"></i>
                            Ajukan Pengesahan
                        </button>
                    </div>
                </div>

                <div class="data-grid">
                    <div class="data-item">
                        <span class="data-label">
                            <i class="fas fa-heading"></i>
                            Judul Aksi Perubahan
                        </span>
                        <span class="data-value">{{ $aksiPerubahan->judul ?? '-' }}</span>
                    </div>

                    @if($aksiPerubahan->abstrak)
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-align-left"></i>
                                Abstrak
                            </span>
                            <span class="data-value">{{ $aksiPerubahan->abstrak }}</span>
                        </div>
                    @endif

                    @if(!empty($aksiPerubahan?->kategori_aksatika))
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-tags"></i>
                                Kategori Aksatika
                            </span>
                            <span class="data-value">
                                {{ $aksiPerubahan->kategori_aksatika }}
                            </span>
                        </div>
                    @endif

                    @if($aksiPerubahan->file)
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-file-pdf"></i>
                                Dokumen Aksi Perubahan
                            </span>
                            <div class="file-preview">
                                <div class="file-icon">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="file-info">
                                    <div class="file-name">{{ basename($aksiPerubahan->file) }}</div>
                                    <div class="file-size">Dokumen Aksi Perubahan</div>
                                </div>
                                <a href="{{ Storage::disk('google')->url($aksiPerubahan->file) }}" target="_blank" class="btn-icon"
                                    title="Lihat Dokumen">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ Storage::disk('google')->url($aksiPerubahan->file) }}" download class="btn-icon"
                                    title="Download Dokumen">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($aksiPerubahan->link_video)
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-video"></i>
                                Link Video
                            </span>
                            <div class="link-item">
                                <i class="fas fa-link"></i>
                                <div class="link-text">{{ $aksiPerubahan->link_video }}</div>
                                <a href="{{ $aksiPerubahan->link_video }}" target="_blank" class="btn-icon" title="Buka Link">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($aksiPerubahan->link_laporan_majalah)
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-newspaper"></i>
                                Link Laporan Majalah
                            </span>
                            <div class="link-item">
                                <i class="fas fa-link"></i>
                                <div class="link-text">{{ $aksiPerubahan->link_laporan_majalah }}</div>
                                <a href="{{ $aksiPerubahan->link_laporan_majalah }}" target="_blank" class="btn-icon" title="Buka Link">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($aksiPerubahan->lembar_pengesahan)
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-file-signature"></i>
                                Lembar Pengesahan
                            </span>
                            <div class="file-preview">
                                <div class="file-icon">
                                    <i class="fas fa-file-signature"></i>
                                </div>
                                <div class="file-info">
                                    <div class="file-name">{{ basename($aksiPerubahan->lembar_pengesahan) }}</div>
                                    <div class="file-size">Dokumen Pengesahan</div>
                                </div>
                                <a href="{{ Storage::disk('google')->url($aksiPerubahan->lembar_pengesahan) }}" target="_blank"
                                    class="btn-icon" title="Lihat Dokumen">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ Storage::disk('google')->url($aksiPerubahan->lembar_pengesahan) }}" download
                                    class="btn-icon" title="Download Dokumen">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        @else
            <!-- Tidak Ada Data - Tampilkan Tombol Tambah -->
            <div class="no-data">
                <div class="no-data-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <h4>Belum Ada Aksi Perubahan</h4>
                <p>Silakan tambahkan Aksi Perubahan untuk pelatihan Anda</p>
                <button onclick="openAddModal()" class="btn-add-primary">
                    <i class="fas fa-plus-circle"></i>
                    Tambah Aksi Perubahan
                </button>
            </div>
        @endif

    @endif


    <!-- Modal Add -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-plus-circle"></i>
                    Tambah Aksi Perubahan
                </div>
                <button class="close" onclick="closeAddModal()">&times;</button>
            </div>
            <form action="{{ route('aksiperubahan.store') }}" method="POST" enctype="multipart/form-data" id="addForm">
                @csrf

                <!-- Judul - Full Width -->
                <div class="form-group full-width">
                    <label class="form-label">Judul Aksi Perubahan *</label>
                    <input type="text" name="judul" class="form-control" required
                        placeholder="Masukkan judul aksi perubahan">
                </div>

                <!-- Abstrak - Full Width -->
                <div class="form-group full-width">
                    <label class="form-label">Abstrak</label>
                    <textarea name="abstrak" class="form-control" placeholder="Masukkan abstrak aksi perubahan (opsional)"
                        rows="5"></textarea>
                </div>

                @php
                    $kategoriOptions = [
                        'Memperkokoh ideologi Pancasila, demokrasi, dan hak asasi manusia (HAM)',
                        'Memantapkan sistem pertahanan keamanan negara dan mendorong kemandirian bangsa melalui swasembada pangan, energi, air, ekonomi kreatif, ekonomi hijau, dan ekonomi biru',
                        'Meningkatkan lapangan kerja yang berkualitas, mendorong kewirausahaan, mengembangkan industri kreatif, dan melanjutkan pengembangan infrastruktur',
                        'Memperkuat pembangunan sumber daya manusia (SDM), sains, teknologi, pendidikan, kesehatan, prestasi olahraga, kesetaraan gender, serta penguatan peran perempuan, pemuda, dan penyandang disabilitas',
                        'Melanjutkan hilirisasi dan industrialisasi untuk meningkatkan nilai tambah di dalam negeri',
                        'Membangun dari desa dan dari bawah untuk pemerataan ekonomi dan pemberantasan kemiskinan.',
                        'Memperkuat reformasi politik, hukum, dan birokrasi, serta memperkuat pencegahan dan pemberantasan korupsi dan narkoba',
                        'Memperkuat penyelarasan kehidupan yang harmonis dengan lingkungan, alam, dan budaya, serta peningkatan toleransi antarumat beragama untuk mencapai masyarakat yang adil dan makmur',
                    ];

                    $selectedKategori = old('kategori_aksatika', $aksiPerubahan->kategori_aksatika ?? '');
                @endphp

                <!-- Row 1: Kategori dan File (2 kolom) -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kategori Aksatika</label>
                        <select name="kategori_aksatika" class="form-control @error('kategori_aksatika') is-invalid @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoriOptions as $opt)
                                <option value="{{ $opt }}" {{ $selectedKategori === $opt ? 'selected' : '' }}>
                                    {{ $opt }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_aksatika')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Dokumen Aksi Perubahan (PDF - Max 5MB)</label>
                        <input type="file" name="file" class="form-control" accept=".pdf">
                        <div class="form-hint">
                            <i class="fas fa-info-circle"></i>
                            Format: PDF, maksimal 5MB
                        </div>
                    </div>
                </div>

                <!-- Row 2: Link Video dan Link Laporan (2 kolom) -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Link Video</label>
                        <input type="url" name="link_video" class="form-control" placeholder="https://example.com/video">
                        <div class="form-hint">
                            <i class="fas fa-info-circle"></i>
                            Link video presentasi/demo
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Link Laporan Majalah</label>
                        <input type="url" name="link_laporan_majalah" class="form-control"
                            placeholder="https://example.com/laporan">
                        <div class="form-hint">
                            <i class="fas fa-info-circle"></i>
                            Link laporan di majalah
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="btnSubmitAdd">
                    <span class="btn-text">
                        <i class="fas fa-save"></i> Simpan Aksi Perubahan
                    </span>
                    <span class="spinner-wrapper" style="display: none;">
                        <span class="spinner"></span>
                        <span>Menyimpan...</span>
                    </span>
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    @if($aksiPerubahan)
        <div id="editModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <i class="fas fa-edit"></i>
                        Edit Aksi Perubahan
                    </div>
                    <button class="close" onclick="closeEditModal()">&times;</button>
                </div>
                <form action="{{ route('aksiperubahan.update', $aksiPerubahan->id) }}" method="POST"
                    enctype="multipart/form-data" id="editForm">
                    @csrf
                    @method('PUT')

                    <!-- Judul - Full Width -->
                    <div class="form-group full-width">
                        <label class="form-label">Judul Aksi Perubahan *</label>
                        <input type="text" name="judul" class="form-control" required value="{{ $aksiPerubahan->judul }}"
                            placeholder="Masukkan judul aksi perubahan">
                    </div>

                    <!-- Abstrak - Full Width -->
                    <div class="form-group full-width">
                        <label class="form-label">Abstrak</label>
                        <textarea name="abstrak" class="form-control" placeholder="Masukkan abstrak aksi perubahan (opsional)"
                            rows="5">{{ $aksiPerubahan->abstrak }}</textarea>
                    </div>

                    <!-- Row 1: Kategori dan File (2 kolom) -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Kategori Aksatika</label>
                            <select name="kategori_aksatika" class="form-control">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="pilihan1" {{ $aksiPerubahan->kategori_aksatika == 'pilihan1' ? 'selected' : '' }}>
                                    Pilihan 1</option>
                                <option value="pilihan2" {{ $aksiPerubahan->kategori_aksatika == 'pilihan2' ? 'selected' : '' }}>
                                    Pilihan 2</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Dokumen Aksi Perubahan</label>
                            @if($aksiPerubahan->file)
                                <div class="file-preview mb-2">
                                    <div class="file-icon">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="file-info">
                                        <div class="file-name">{{ basename($aksiPerubahan->file) }}</div>
                                        <div class="file-size">File saat ini</div>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="file" class="form-control" accept=".pdf">
                            <div class="form-hint">
                                <i class="fas fa-info-circle"></i>
                                Kosongkan jika tidak ingin mengubah file
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Link Video dan Link Laporan (2 kolom) -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Link Video</label>
                            <input type="url" name="link_video" class="form-control" value="{{ $aksiPerubahan->link_video }}"
                                placeholder="https://example.com/video">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Link Laporan Majalah</label>
                            <input type="url" name="link_laporan_majalah" class="form-control"
                                value="{{ $aksiPerubahan->link_laporan_majalah }}" placeholder="https://example.com/laporan">
                        </div>
                    </div>

                    <button type="submit" class="btn-submit" id="btnSubmitEdit">
                        <span class="btn-text">
                            <i class="fas fa-save"></i> Update Aksi Perubahan
                        </span>
                        <span class="spinner-wrapper" style="display: none;">
                            <span class="spinner"></span>
                            <span>Mengupdate...</span>
                        </span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Modal Pengajuan Pengesahan (TETAP FULL WIDTH) -->
        <div id="pengajuanModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <i class="fas fa-file-signature"></i>
                        Ajukan Lembar Pengesahan
                    </div>
                    <button class="close" onclick="closePengajuanModal()">&times;</button>
                </div>
                <form action="{{ route('aksiperubahan.upload-pengesahan', $aksiPerubahan->id) }}" method="POST"
                    enctype="multipart/form-data" id="pengajuanForm">
                    @csrf
                    @method('PUT')

                    <div class="alert alert-success">
                        <i class="fas fa-info-circle"></i>
                        Unggah lembar pengesahan yang sudah ditandatangani oleh pimpinan/penanggung jawab.
                    </div>

                    <!-- Judul Aksi Perubahan - Full Width -->
                    <div class="form-group">
                        <label class="form-label">Judul Aksi Perubahan</label>
                        <input type="text" class="form-control" value="{{ $aksiPerubahan->judul }}" readonly>
                    </div>

                    <!-- Lembar Pengesahan - Full Width -->
                    <div class="form-group">
                        <label class="form-label">Lembar Pengesahan * (PDF - Max 5MB)</label>
                        @if($aksiPerubahan->lembar_pengesahan)
                            <div class="file-preview mb-2">
                                <div class="file-icon">
                                    <i class="fas fa-file-signature"></i>
                                </div>
                                <div class="file-info">
                                    <div class="file-name">{{ basename($aksiPerubahan->lembar_pengesahan) }}</div>
                                    <div class="file-size">File saat ini</div>
                                </div>
                            </div>
                            <div class="form-hint">
                                <i class="fas fa-exclamation-triangle"></i>
                                Mengganti file akan menghapus file sebelumnya
                            </div>
                        @endif
                        <input type="file" name="lembar_pengesahan" class="form-control" accept=".pdf" required>
                        <div class="form-hint">
                            <i class="fas fa-info-circle"></i>
                            Format: PDF, maksimal 5MB, sudah ditandatangani
                        </div>
                    </div>



                    <button type="submit" class="btn-submit" id="btnSubmitPengajuan">
                        <span class="btn-text">
                            <i class="fas fa-paper-plane"></i> Ajukan Pengesahan
                        </span>
                        <span class="spinner-wrapper" style="display: none;">
                            <span class="spinner"></span>
                            <span>Mengunggah...</span>
                        </span>
                    </button>
                </form>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        // Loading spinner functions
        function showLoading(button) {
            button.classList.add('btn-loading');
            button.disabled = true;
            button.querySelector('.btn-text').style.opacity = '0';
            button.querySelector('.spinner-wrapper').style.display = 'flex';
        }

        function hideLoading(button) {
            button.classList.remove('btn-loading');
            button.disabled = false;
            button.querySelector('.btn-text').style.opacity = '1';
            button.querySelector('.spinner-wrapper').style.display = 'none';
        }

        // Handle Add Form Submit
        document.getElementById('addForm')?.addEventListener('submit', function (e) {
            const button = document.getElementById('btnSubmitAdd');
            showLoading(button);
        });

        // Handle Edit Form Submit
        document.getElementById('editForm')?.addEventListener('submit', function (e) {
            const button = document.getElementById('btnSubmitEdit');
            showLoading(button);
        });

        // Handle Pengajuan Form Submit
        document.getElementById('pengajuanForm')?.addEventListener('submit', function (e) {
            const button = document.getElementById('btnSubmitPengajuan');
            showLoading(button);
        });

        // Modal Functions
        function openAddModal() {
            document.getElementById('addModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.remove('active');
            document.body.style.overflow = 'auto';
            const button = document.getElementById('btnSubmitAdd');
            if (button) hideLoading(button);
        }

        function openEditModal() {
            document.getElementById('editModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('active');
            document.body.style.overflow = 'auto';
            const button = document.getElementById('btnSubmitEdit');
            if (button) hideLoading(button);
        }

        function openPengajuanModal() {
            document.getElementById('pengajuanModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closePengajuanModal() {
            document.getElementById('pengajuanModal').classList.remove('active');
            document.body.style.overflow = 'auto';
            const button = document.getElementById('btnSubmitPengajuan');
            if (button) hideLoading(button);
        }

        // File validation
        function validateFile(input, maxSizeMB = 5) {
            if (input.files.length > 0) {
                const fileSize = input.files[0].size / 1024 / 1024; // in MB
                if (fileSize > maxSizeMB) {
                    alert(`Ukuran file terlalu besar. Maksimal ${maxSizeMB}MB.`);
                    input.value = '';
                    return false;
                }
            }
            return true;
        }

        // Add file validation to all file inputs
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function () {
                validateFile(this, 5);
            });
        });

        // URL validation
        function validateURL(input) {
            if (input.value && !input.value.startsWith('http://') && !input.value.startsWith('https://')) {
                input.value = 'https://' + input.value;
            }
        }

        // Add URL validation to all URL inputs
        document.querySelectorAll('input[type="url"]').forEach(input => {
            input.addEventListener('blur', function () {
                validateURL(this);
            });
        });

        // Close modal when clicking outside
        window.onclick = function (event) {
            const addModal = document.getElementById('addModal');
            const editModal = document.getElementById('editModal');
            const pengajuanModal = document.getElementById('pengajuanModal');

            if (event.target == addModal) {
                closeAddModal();
            }
            if (event.target == editModal) {
                closeEditModal();
            }
            if (event.target == pengajuanModal) {
                closePengajuanModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeAddModal();
                closeEditModal();
                closePengajuanModal();
            }
        });

        // Auto hide alerts after 5 seconds
        setTimeout(function () {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (!alert.classList.contains('alert-success') && !alert.classList.contains('alert-error')) {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => alert.remove(), 500);
                }
            });
        }, 5000);

        // Hide success/error alerts after 8 seconds
        setTimeout(function () {
            const alerts = document.querySelectorAll('.alert-success, .alert-error');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s ease';
                setTimeout(() => alert.remove(), 500);
            });
        }, 8000);
    </script>
@endsection