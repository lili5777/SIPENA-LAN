{{-- resources/views/admin/dashboard/edit.blade.php --}}
@extends('admin.partials.layout')

@section('title', 'Edit Data Peserta - LAN Pusjar SKMP')
@section('page-title', 'Edit Data Peserta')

@section('styles')
    <style>
        /* Reset dan Base Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        .edit-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .edit-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .edit-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .edit-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .edit-header p {
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            text-decoration: none;
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(-5px);
        }

        /* Form Styles */
        .edit-form {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .form-tabs {
            display: flex;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .form-tabs::-webkit-scrollbar {
            display: none;
        }

        .form-tab {
            padding: 1rem 2rem;
            background: none;
            border: none;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 160px;
            justify-content: center;
        }

        .form-tab:hover {
            color: var(--primary-color);
            background: rgba(26, 58, 108, 0.05);
        }

        .form-tab.active {
            color: var(--primary-color);
            background: white;
        }

        .form-tab.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--primary-color);
        }

        .form-tab-content {
            display: none;
            padding: 2rem;
            animation: fadeInUp 0.5s ease-out;
        }

        .form-tab-content.active {
            display: block;
        }

        .form-section-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .form-section-header i {
            color: var(--primary-color);
            background: rgba(26, 58, 108, 0.1);
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .form-section-header h3 {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #334155;
            font-size: 0.95rem;
        }

        .form-label.required::after {
            content: ' *';
            color: #ef4444;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 58, 108, 0.1);
        }

        .form-input.error,
        .form-select.error,
        .form-textarea.error {
            border-color: #ef4444;
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-hint {
            display: block;
            margin-top: 0.375rem;
            font-size: 0.85rem;
            color: #64748b;
        }

        .text-danger {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }

        /* File Upload Styles */
        .form-file {
            position: relative;
            margin-top: 0.5rem;
        }

        .form-file-input {
            position: absolute;
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            z-index: -1;
        }

        .form-file-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            background: #f8fafc;
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .form-file-label:hover {
            border-color: var(--primary-color);
            background: #f1f5f9;
        }

        .form-file-label i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .form-file-name {
            margin-top: 0.75rem;
            padding: 0.75rem;
            background: #f1f5f9;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .file-info i.fa-check-circle {
            color: #10b981;
        }

        .file-info span {
            flex: 1;
            word-break: break-all;
        }

        .btn-change-file {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .btn-change-file:hover {
            background: var(--secondary-color);
            transform: translateY(-1px);
        }

        .no-file {
            color: #94a3b8;
            font-style: italic;
        }

        /* Alert Styles */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .alert-info {
            background: #dbeafe;
            color: var(--primary-color);
            border: 1px solid #bfdbfe;
        }

        .alert-info i {
            margin-top: 0.125rem;
        }

        /* Mentor Styles */
        .mentor-options {
            margin-bottom: 1.5rem;
        }

        #mentor-container {
            margin-top: 1.5rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-cancel {
            background: #f1f5f9;
            color: #64748b;
            border: 2px solid #e2e8f0;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #e2e8f0;
            color: #475569;
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
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 58, 108, 0.2);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Loading Spinner */
        .spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .edit-container {
                padding: 1rem;
            }

            .edit-header {
                padding: 1.5rem;
            }

            .form-tab {
                min-width: 140px;
                padding: 1rem 1.5rem;
            }

            .form-tab-content {
                padding: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        @media (max-width: 768px) {
            .edit-header h1 {
                font-size: 1.5rem;
            }

            .form-tab {
                min-width: 120px;
                padding: 0.875rem 1rem;
                font-size: 0.9rem;
            }

            .form-tab-content {
                padding: 1rem;
            }

            .form-section-header {
                flex-direction: column;
                text-align: center;
                gap: 0.75rem;
            }

            .form-section-header i {
                width: 45px;
                height: 45px;
            }

            .form-section-header h3 {
                font-size: 1.2rem;
            }

            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-cancel,
            .btn-submit {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .edit-header {
                padding: 1.25rem;
            }

            .edit-header h1 {
                font-size: 1.3rem;
            }

            .form-tabs {
                flex-wrap: wrap;
            }

            .form-tab {
                flex: 1;
                min-width: auto;
                padding: 0.75rem;
                font-size: 0.85rem;
            }

            .file-info {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-change-file {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="edit-container">
        <div class="edit-header">
            <h1>Edit Data Peserta</h1>
            <p>Perbarui informasi pribadi, kepegawaian, dan dokumen Anda</p>
            <a href="{{ route('dashboard') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Dashboard
            </a>
        </div>

        <form id="editForm" class="edit-form" method="POST" action="{{ route('admin.dashboard.update') }}"
            enctype="multipart/form-data">
            @csrf

            <!-- Tab Navigation -->
            <div class="form-tabs">
                <button type="button" class="form-tab active" data-tab="tab-personal">
                    <i class="fas fa-user"></i>
                    Data Pribadi
                </button>
                <button type="button" class="form-tab" data-tab="tab-employment">
                    <i class="fas fa-briefcase"></i>
                    Data Kepegawaian
                </button>
                <button type="button" class="form-tab" data-tab="tab-mentor">
                    <i class="fas fa-user-tie"></i>
                    Data Mentor
                </button>
                <button type="button" class="form-tab" data-tab="tab-documents">
                    <i class="fas fa-file-alt"></i>
                    Dokumen
                </button>
            </div>

            <!-- Tab 1: Data Pribadi -->
            <div id="tab-personal" class="form-tab-content active">
                <div class="form-section-header">
                    <i class="fas fa-user-circle"></i>
                    <h3>Informasi Pribadi</h3>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">NIP/NRP</label>
                        <input type="text" name="nip_nrp" class="form-input @error('nip_nrp') error @enderror"
                            value="{{ old('nip_nrp', $peserta->nip_nrp) }}" required readonly>
                        <small class="form-hint">NIP/NRP tidak dapat diubah</small>
                        @error('nip_nrp')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-input @error('nama_lengkap') error @enderror"
                            value="{{ old('nama_lengkap', $peserta->nama_lengkap) }}" required>
                        @error('nama_lengkap')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Panggilan</label>
                        <input type="text" name="nama_panggilan" class="form-input @error('nama_panggilan') error @enderror"
                            value="{{ old('nama_panggilan', $peserta->nama_panggilan) }}">
                        @error('nama_panggilan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') error @enderror" required>
                            <option value="">Pilih</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Agama</label>
                        <select name="agama" class="form-select @error('agama') error @enderror" required>
                            <option value="">Pilih</option>
                            <option value="Islam" {{ old('agama', $peserta->agama) == 'Islam' ? 'selected' : '' }}>Islam
                            </option>
                            <option value="Kristen" {{ old('agama', $peserta->agama) == 'Kristen' ? 'selected' : '' }}>Kristen
                            </option>
                            <option value="Katolik" {{ old('agama', $peserta->agama) == 'Katolik' ? 'selected' : '' }}>Katolik
                            </option>
                            <option value="Hindu" {{ old('agama', $peserta->agama) == 'Hindu' ? 'selected' : '' }}>Hindu
                            </option>
                            <option value="Buddha" {{ old('agama', $peserta->agama) == 'Buddha' ? 'selected' : '' }}>Buddha
                            </option>
                            <option value="Konghucu" {{ old('agama', $peserta->agama) == 'Konghucu' ? 'selected' : '' }}>
                                Konghucu</option>
                        </select>
                        @error('agama')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Status Perkawinan</label>
                        <select name="status_perkawinan" id="status_perkawinan"
                            class="form-select @error('status_perkawinan') error @enderror" required>
                            <option value="">Pilih</option>
                            <option value="Belum Menikah" {{ old('status_perkawinan', $peserta->status_perkawinan) == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                            <option value="Menikah" {{ old('status_perkawinan', $peserta->status_perkawinan) == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                            <option value="Duda" {{ old('status_perkawinan', $peserta->status_perkawinan) == 'Duda' ? 'selected' : '' }}>Duda</option>
                            <option value="Janda" {{ old('status_perkawinan', $peserta->status_perkawinan) == 'Janda' ? 'selected' : '' }}>Janda</option>
                        </select>
                        @error('status_perkawinan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-input @error('tempat_lahir') error @enderror"
                            value="{{ old('tempat_lahir', $peserta->tempat_lahir) }}" required>
                        @error('tempat_lahir')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-input @error('tanggal_lahir') error @enderror"
                            value="{{ old('tanggal_lahir', $peserta->tanggal_lahir) }}" required>
                        @error('tanggal_lahir')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Email Pribadi</label>
                        <input type="email" name="email_pribadi" class="form-input @error('email_pribadi') error @enderror"
                            value="{{ old('email_pribadi', $peserta->email_pribadi) }}" required>
                        @error('email_pribadi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Nomor HP</label>
                        <input type="tel" name="nomor_hp" class="form-input @error('nomor_hp') error @enderror"
                            value="{{ old('nomor_hp', $peserta->nomor_hp) }}" required>
                        @error('nomor_hp')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label required">Alamat Rumah</label>
                    <textarea name="alamat_rumah" class="form-textarea @error('alamat_rumah') error @enderror"
                        required>{{ old('alamat_rumah', $peserta->alamat_rumah) }}</textarea>
                    @error('alamat_rumah')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Olahraga & Hobi</label>
                        <input type="text" name="olahraga_hobi" class="form-input @error('olahraga_hobi') error @enderror"
                            value="{{ old('olahraga_hobi', $peserta->olahraga_hobi) }}">
                        @error('olahraga_hobi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Status Perokok</label>
                        <select name="perokok" class="form-select @error('perokok') error @enderror" required>
                            <option value="">Pilih</option>
                            <option value="Ya" {{ old('perokok', $peserta->perokok) == 'Ya' ? 'selected' : '' }}>Ya</option>
                            <option value="Tidak" {{ old('perokok', $peserta->perokok) == 'Tidak' ? 'selected' : '' }}>Tidak
                            </option>
                        </select>
                        @error('perokok')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Ukuran Kaos</label>
                        <select name="ukuran_kaos" class="form-select @error('ukuran_kaos') error @enderror">
                            <option value="">Pilih</option>
                            <option value="S" {{ old('ukuran_kaos', $peserta->ukuran_kaos) == 'S' ? 'selected' : '' }}>S
                            </option>
                            <option value="M" {{ old('ukuran_kaos', $peserta->ukuran_kaos) == 'M' ? 'selected' : '' }}>M
                            </option>
                            <option value="L" {{ old('ukuran_kaos', $peserta->ukuran_kaos) == 'L' ? 'selected' : '' }}>L
                            </option>
                            <option value="XL" {{ old('ukuran_kaos', $peserta->ukuran_kaos) == 'XL' ? 'selected' : '' }}>XL
                            </option>
                            <option value="XXL" {{ old('ukuran_kaos', $peserta->ukuran_kaos) == 'XXL' ? 'selected' : '' }}>XXL
                            </option>
                            <option value="XXXL" {{ old('ukuran_kaos', $peserta->ukuran_kaos) == 'XXXL' ? 'selected' : '' }}>
                                XXXL</option>
                        </select>
                        @error('ukuran_kaos')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ukuran Celana</label>
                        <select name="ukuran_celana" class="form-select @error('ukuran_celana') error @enderror">
                            <option value="">Pilih</option>
                            <option value="S" {{ old('ukuran_celana', $peserta->ukuran_celana) == 'S' ? 'selected' : '' }}>S
                            </option>
                            <option value="M" {{ old('ukuran_celana', $peserta->ukuran_celana) == 'M' ? 'selected' : '' }}>M
                            </option>
                            <option value="L" {{ old('ukuran_celana', $peserta->ukuran_celana) == 'L' ? 'selected' : '' }}>L
                            </option>
                            <option value="XL" {{ old('ukuran_celana', $peserta->ukuran_celana) == 'XL' ? 'selected' : '' }}>
                                XL</option>
                            <option value="XXL" {{ old('ukuran_celana', $peserta->ukuran_celana) == 'XXL' ? 'selected' : '' }}>XXL</option>
                            <option value="XXXL" {{ old('ukuran_celana', $peserta->ukuran_celana) == 'XXXL' ? 'selected' : '' }}>XXXL</option>
                        </select>
                        @error('ukuran_celana')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ukuran Training</label>
                        <select name="ukuran_training" class="form-select @error('ukuran_training') error @enderror">
                            <option value="">Pilih</option>
                            <option value="S" {{ old('ukuran_training', $peserta->ukuran_training) == 'S' ? 'selected' : '' }}>S</option>
                            <option value="M" {{ old('ukuran_training', $peserta->ukuran_training) == 'M' ? 'selected' : '' }}>M</option>
                            <option value="L" {{ old('ukuran_training', $peserta->ukuran_training) == 'L' ? 'selected' : '' }}>L</option>
                            <option value="XL" {{ old('ukuran_training', $peserta->ukuran_training) == 'XL' ? 'selected' : '' }}>XL</option>
                            <option value="XXL" {{ old('ukuran_training', $peserta->ukuran_training) == 'XXL' ? 'selected' : '' }}>XXL</option>
                            <option value="XXXL" {{ old('ukuran_training', $peserta->ukuran_training) == 'XXXL' ? 'selected' : '' }}>XXXL</option>
                        </select>
                        @error('ukuran_training')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-section-header">
                    <i class="fas fa-graduation-cap"></i>
                    <h3>Pendidikan</h3>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Pendidikan Terakhir</label>
                        <select name="pendidikan_terakhir" class="form-select @error('pendidikan_terakhir') error @enderror"
                            required>
                            <option value="">Pilih</option>
                            <option value="SD" {{ old('pendidikan_terakhir', $peserta->pendidikan_terakhir) == 'SD' ? 'selected' : '' }}>SD</option>
                            <option value="SMP" {{ old('pendidikan_terakhir', $peserta->pendidikan_terakhir) == 'SMP' ? 'selected' : '' }}>SMP</option>
                            <option value="SMU" {{ old('pendidikan_terakhir', $peserta->pendidikan_terakhir) == 'SMU' ? 'selected' : '' }}>SMU</option>
                            <option value="D3" {{ old('pendidikan_terakhir', $peserta->pendidikan_terakhir) == 'D3' ? 'selected' : '' }}>D3</option>
                            <option value="D4" {{ old('pendidikan_terakhir', $peserta->pendidikan_terakhir) == 'D4' ? 'selected' : '' }}>D4</option>
                            <option value="S1" {{ old('pendidikan_terakhir', $peserta->pendidikan_terakhir) == 'S1' ? 'selected' : '' }}>S1</option>
                            <option value="S2" {{ old('pendidikan_terakhir', $peserta->pendidikan_terakhir) == 'S2' ? 'selected' : '' }}>S2</option>
                            <option value="S3" {{ old('pendidikan_terakhir', $peserta->pendidikan_terakhir) == 'S3' ? 'selected' : '' }}>S3</option>
                        </select>
                        @error('pendidikan_terakhir')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bidang Studi</label>
                        <input type="text" name="bidang_studi" class="form-input @error('bidang_studi') error @enderror"
                            value="{{ old('bidang_studi', $peserta->bidang_studi) }}">
                        @error('bidang_studi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Bidang Keahlian</label>
                        <input type="text" name="bidang_keahlian"
                            class="form-input @error('bidang_keahlian') error @enderror"
                            value="{{ old('bidang_keahlian', $peserta->bidang_keahlian) }}">
                        @error('bidang_keahlian')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kondisi Peserta</label>
                        <textarea name="kondisi_peserta"
                            class="form-textarea @error('kondisi_peserta') error @enderror">{{ old('kondisi_peserta', $peserta->kondisi_peserta) }}</textarea>
                        @error('kondisi_peserta')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Tab 2: Data Kepegawaian -->
            <div id="tab-employment" class="form-tab-content">
                <div class="form-section-header">
                    <i class="fas fa-building"></i>
                    <h3>Data Kepegawaian</h3>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Asal Instansi</label>
                        <input type="text" name="asal_instansi" class="form-input @error('asal_instansi') error @enderror"
                            value="{{ old('asal_instansi', $kepegawaian->asal_instansi ?? '') }}" required>
                        @error('asal_instansi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Unit Kerja</label>
                        <input type="text" name="unit_kerja" class="form-input @error('unit_kerja') error @enderror"
                            value="{{ old('unit_kerja', $kepegawaian->unit_kerja ?? '') }}">
                        @error('unit_kerja')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Provinsi</label>
                        <select name="id_provinsi" id="id_provinsi"
                            class="form-select @error('id_provinsi') error @enderror" required>
                            <option value="">Pilih Provinsi</option>
                            @foreach($provinsiList as $provinsi)
                                <option value="{{ $provinsi->id }}" {{ old('id_provinsi', $kepegawaian->id_provinsi ?? '') == $provinsi->id ? 'selected' : '' }}>
                                    {{ $provinsi->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_provinsi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kabupaten/Kota</label>
                        <select name="id_kabupaten_kota" id="id_kabupaten_kota"
                            class="form-select @error('id_kabupaten_kota') error @enderror" {{ !$kepegawaian?->id_provinsi ? 'disabled' : '' }}>
                            <option value="">Pilih Kabupaten/Kota</option>
                            @foreach($kabupatenList as $kabupaten)
                                <option value="{{ $kabupaten->id }}" {{ old('id_kabupaten_kota', $kepegawaian->id_kabupaten_kota ?? '') == $kabupaten->id ? 'selected' : '' }}>
                                    {{ $kabupaten->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_kabupaten_kota')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label required">Alamat Kantor</label>
                    <textarea name="alamat_kantor" class="form-textarea @error('alamat_kantor') error @enderror"
                        required>{{ old('alamat_kantor', $kepegawaian->alamat_kantor ?? '') }}</textarea>
                    @error('alamat_kantor')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nomor Telepon Kantor</label>
                        <input type="tel" name="nomor_telepon_kantor"
                            class="form-input @error('nomor_telepon_kantor') error @enderror"
                            value="{{ old('nomor_telepon_kantor', $kepegawaian->nomor_telepon_kantor ?? '') }}">
                        @error('nomor_telepon_kantor')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Kantor</label>
                        <input type="email" name="email_kantor" class="form-input @error('email_kantor') error @enderror"
                            value="{{ old('email_kantor', $kepegawaian->email_kantor ?? '') }}">
                        @error('email_kantor')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Jabatan</label>
                        <input type="text" name="jabatan" class="form-input @error('jabatan') error @enderror"
                            value="{{ old('jabatan', $kepegawaian->jabatan ?? '') }}" required>
                        @error('jabatan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pangkat</label>
                        <input type="text" name="pangkat" class="form-input @error('pangkat') error @enderror"
                            value="{{ old('pangkat', $kepegawaian->pangkat ?? '') }}">
                        @error('pangkat')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Golongan Ruang</label>
                        <input type="text" name="golongan_ruang" class="form-input @error('golongan_ruang') error @enderror"
                            value="{{ old('golongan_ruang', $kepegawaian->golongan_ruang ?? '') }}" required>
                        @error('golongan_ruang')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Eselon</label>
                        <input type="text" name="eselon" class="form-input @error('eselon') error @enderror"
                            value="{{ old('eselon', $kepegawaian->eselon ?? '') }}">
                        @error('eselon')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-section-header">
                    <i class="fas fa-file-contract"></i>
                    <h3>Data SK</h3>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nomor SK CPNS</label>
                        <input type="text" name="nomor_sk_cpns" class="form-input @error('nomor_sk_cpns') error @enderror"
                            value="{{ old('nomor_sk_cpns', $kepegawaian->nomor_sk_cpns ?? '') }}">
                        @error('nomor_sk_cpns')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal SK CPNS</label>
                        <input type="date" name="tanggal_sk_cpns"
                            class="form-input @error('tanggal_sk_cpns') error @enderror"
                            value="{{ old('tanggal_sk_cpns', $kepegawaian->tanggal_sk_cpns ?? '') }}">
                        @error('tanggal_sk_cpns')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nomor SK Terakhir</label>
                        <input type="text" name="nomor_sk_terakhir"
                            class="form-input @error('nomor_sk_terakhir') error @enderror"
                            value="{{ old('nomor_sk_terakhir', $kepegawaian->nomor_sk_terakhir ?? '') }}">
                        @error('nomor_sk_terakhir')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal SK Jabatan</label>
                        <input type="date" name="tanggal_sk_jabatan"
                            class="form-input @error('tanggal_sk_jabatan') error @enderror"
                            value="{{ old('tanggal_sk_jabatan', $kepegawaian->tanggal_sk_jabatan ?? '') }}">
                        @error('tanggal_sk_jabatan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tahun Lulus PKP/PIM IV</label>
                        <input type="number" name="tahun_lulus_pkp_pim_iv"
                            class="form-input @error('tahun_lulus_pkp_pim_iv') error @enderror"
                            value="{{ old('tahun_lulus_pkp_pim_iv', $kepegawaian->tahun_lulus_pkp_pim_iv ?? '') }}"
                            min="1900" max="{{ date('Y') }}">
                        @error('tahun_lulus_pkp_pim_iv')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Tab 3: Data Mentor -->
            <div id="tab-mentor" class="form-tab-content">
                <div class="form-section-header">
                    <i class="fas fa-user-tie"></i>
                    <h3>Data Mentor</h3>
                </div>

                @if($pendaftaranTerbaru)
                    <div class="form-group">
                        <label class="form-label required">Sudah Ada Penunjukan Mentor?</label>
                        <select name="sudah_ada_mentor" id="sudah_ada_mentor"
                            class="form-select @error('sudah_ada_mentor') error @enderror" required>
                            <option value="">Pilih</option>
                            <option value="Ya" {{ old('sudah_ada_mentor') == 'Ya' ? 'selected' : '' }}>Ya</option>
                            <option value="Tidak" {{ old('sudah_ada_mentor') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                        </select>
                        @error('sudah_ada_mentor')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div id="mentor-container" style="display: none;">
                        <div class="form-group">
                            <label class="form-label required">Pilih Menu Mentor</label>
                            <select name="mentor_mode" id="mentor_mode"
                                class="form-select @error('mentor_mode') error @enderror">
                                <option value="">Pilih Menu</option>
                                <option value="pilih" {{ old('mentor_mode') == 'pilih' ? 'selected' : '' }}>Pilih dari Daftar
                                    Mentor</option>
                                <option value="tambah" {{ old('mentor_mode') == 'tambah' ? 'selected' : '' }}>Tambah Mentor Baru
                                </option>
                            </select>
                            @error('mentor_mode')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Pilih dari daftar mentor -->
                        <div id="select-mentor-form" style="display: none;">
                            <div class="form-group">
                                <label class="form-label required">Pilih Mentor</label>
                                <select name="id_mentor" id="id_mentor" class="form-select @error('id_mentor') error @enderror">
                                    <option value="">Pilih Mentor...</option>
                                    @foreach($mentorList as $mentor)
                                        <option value="{{ $mentor->id }}" data-nama="{{ $mentor->nama_mentor }}"
                                            data-jabatan="{{ $mentor->jabatan_mentor }}"
                                            data-rekening="{{ $mentor->nomor_rekening }}" data-npwp="{{ $mentor->npwp_mentor }}" {{ old('id_mentor') == $mentor->id ? 'selected' : '' }}>
                                            {{ $mentor->nama_mentor }} - {{ $mentor->jabatan_mentor }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_mentor')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Nama Mentor</label>
                                    <input type="text" name="nama_mentor" id="nama_mentor_select" class="form-input" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Jabatan Mentor</label>
                                    <input type="text" name="jabatan_mentor" id="jabatan_mentor_select" class="form-input"
                                        readonly>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Nomor Rekening Mentor</label>
                                    <input type="text" name="nomor_rekening_mentor" id="nomor_rekening_mentor_select"
                                        class="form-input" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">NPWP Mentor</label>
                                    <input type="text" name="npwp_mentor" id="npwp_mentor_select" class="form-input" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Tambah mentor baru -->
                        <div id="add-mentor-form" style="display: none;">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Silakan lengkapi data mentor baru
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Nama Mentor</label>
                                    <input type="text" name="nama_mentor_baru" id="nama_mentor_baru"
                                        class="form-input @error('nama_mentor_baru') error @enderror"
                                        value="{{ old('nama_mentor_baru') }}">
                                    @error('nama_mentor_baru')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Jabatan Mentor</label>
                                    <input type="text" name="jabatan_mentor_baru" id="jabatan_mentor_baru"
                                        class="form-input @error('jabatan_mentor_baru') error @enderror"
                                        value="{{ old('jabatan_mentor_baru') }}">
                                    @error('jabatan_mentor_baru')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Nomor Rekening Mentor</label>
                                    <input type="text" name="nomor_rekening_mentor_baru" id="nomor_rekening_mentor_baru"
                                        class="form-input @error('nomor_rekening_mentor_baru') error @enderror"
                                        value="{{ old('nomor_rekening_mentor_baru') }}">
                                    @error('nomor_rekening_mentor_baru')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">NPWP Mentor</label>
                                    <input type="text" name="npwp_mentor_baru" id="npwp_mentor_baru"
                                        class="form-input @error('npwp_mentor_baru') error @enderror"
                                        value="{{ old('npwp_mentor_baru') }}">
                                    @error('npwp_mentor_baru')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Anda belum memiliki pendaftaran aktif untuk mengatur mentor.
                    </div>
                @endif
            </div>

            <!-- Tab 4: Dokumen -->
            <div id="tab-documents" class="form-tab-content">
                <div class="form-section-header">
                    <i class="fas fa-file-upload"></i>
                    <h3>Dokumen Pendukung</h3>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Catatan:</strong> Upload file hanya jika ingin mengganti file yang sudah ada.
                    Format file yang diterima: PDF, JPG, JPEG, PNG (maks. 1MB).
                </div>

                <div class="form-section-header">
                    <i class="fas fa-id-card"></i>
                    <h3>Dokumen Pribadi</h3>
                </div>

                <div class="form-group">
                    <label class="form-label">KTP</label>
                    <div class="form-file">
                        <input type="file" name="file_ktp" class="form-file-input" accept=".pdf,.jpg,.jpeg,.png">
                        <label class="form-file-label">
                            <i class="fas fa-cloud-upload-alt"></i><br>
                            Klik untuk mengunggah file
                        </label>
                        <div class="form-file-name">
                            @if($peserta->file_ktp)
                                <div class="file-info">
                                    <i class="fas fa-check-circle text-success"></i>
                                    <span>{{ basename($peserta->file_ktp) }}</span>
                                    <button type="button" class="btn-change-file" data-target="file_ktp">
                                        <i class="fas fa-exchange-alt"></i> Ganti File
                                    </button>
                                </div>
                            @else
                                <span class="no-file">Belum ada file diupload</span>
                            @endif
                        </div>
                    </div>
                    @error('file_ktp')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Pas Foto</label>
                    <div class="form-file">
                        <input type="file" name="file_pas_foto" class="form-file-input" accept=".jpg,.jpeg,.png">
                        <label class="form-file-label">
                            <i class="fas fa-cloud-upload-alt"></i><br>
                            Klik untuk mengunggah file
                        </label>
                        <div class="form-file-name">
                            @if($peserta->file_pas_foto)
                                <div class="file-info">
                                    <i class="fas fa-check-circle text-success"></i>
                                    <span>{{ basename($peserta->file_pas_foto) }}</span>
                                    <button type="button" class="btn-change-file" data-target="file_pas_foto">
                                        <i class="fas fa-exchange-alt"></i> Ganti File
                                    </button>
                                </div>
                            @else
                                <span class="no-file">Belum ada file diupload</span>
                            @endif
                        </div>
                    </div>
                    @error('file_pas_foto')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                @if($kepegawaian)
                    <div class="form-section-header">
                        <i class="fas fa-file-contract"></i>
                        <h3>Dokumen Kepegawaian</h3>
                    </div>

                    <div class="form-group">
                        <label class="form-label">SK Jabatan</label>
                        <div class="form-file">
                            <input type="file" name="file_sk_jabatan" class="form-file-input" accept=".pdf">
                            <label class="form-file-label">
                                <i class="fas fa-cloud-upload-alt"></i><br>
                                Klik untuk mengunggah file
                            </label>
                            <div class="form-file-name">
                                @if($kepegawaian->file_sk_jabatan)
                                    <div class="file-info">
                                        <i class="fas fa-check-circle text-success"></i>
                                        <span>{{ basename($kepegawaian->file_sk_jabatan) }}</span>
                                        <button type="button" class="btn-change-file" data-target="file_sk_jabatan">
                                            <i class="fas fa-exchange-alt"></i> Ganti File
                                        </button>
                                    </div>
                                @else
                                    <span class="no-file">Belum ada file diupload</span>
                                @endif
                            </div>
                        </div>
                        @error('file_sk_jabatan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">SK Pangkat</label>
                        <div class="form-file">
                            <input type="file" name="file_sk_pangkat" class="form-file-input" accept=".pdf">
                            <label class="form-file-label">
                                <i class="fas fa-cloud-upload-alt"></i><br>
                                Klik untuk mengunggah file
                            </label>
                            <div class="form-file-name">
                                @if($kepegawaian->file_sk_pangkat)
                                    <div class="file-info">
                                        <i class="fas fa-check-circle text-success"></i>
                                        <span>{{ basename($kepegawaian->file_sk_pangkat) }}</span>
                                        <button type="button" class="btn-change-file" data-target="file_sk_pangkat">
                                            <i class="fas fa-exchange-alt"></i> Ganti File
                                        </button>
                                    </div>
                                @else
                                    <span class="no-file">Belum ada file diupload</span>
                                @endif
                            </div>
                        </div>
                        @error('file_sk_pangkat')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">SK CPNS</label>
                        <div class="form-file">
                            <input type="file" name="file_sk_cpns" class="form-file-input" accept=".pdf">
                            <label class="form-file-label">
                                <i class="fas fa-cloud-upload-alt"></i><br>
                                Klik untuk mengunggah file
                            </label>
                            <div class="form-file-name">
                                @if($kepegawaian->file_sk_cpns)
                                    <div class="file-info">
                                        <i class="fas fa-check-circle text-success"></i>
                                        <span>{{ basename($kepegawaian->file_sk_cpns) }}</span>
                                        <button type="button" class="btn-change-file" data-target="file_sk_cpns">
                                            <i class="fas fa-exchange-alt"></i> Ganti File
                                        </button>
                                    </div>
                                @else
                                    <span class="no-file">Belum ada file diupload</span>
                                @endif
                            </div>
                        </div>
                        @error('file_sk_cpns')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">SPMT</label>
                        <div class="form-file">
                            <input type="file" name="file_spmt" class="form-file-input" accept=".pdf">
                            <label class="form-file-label">
                                <i class="fas fa-cloud-upload-alt"></i><br>
                                Klik untuk mengunggah file
                            </label>
                            <div class="form-file-name">
                                @if($kepegawaian->file_spmt)
                                    <div class="file-info">
                                        <i class="fas fa-check-circle text-success"></i>
                                        <span>{{ basename($kepegawaian->file_spmt) }}</span>
                                        <button type="button" class="btn-change-file" data-target="file_spmt">
                                            <i class="fas fa-exchange-alt"></i> Ganti File
                                        </button>
                                    </div>
                                @else
                                    <span class="no-file">Belum ada file diupload</span>
                                @endif
                            </div>
                        </div>
                        @error('file_spmt')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">SKP</label>
                        <div class="form-file">
                            <input type="file" name="file_skp" class="form-file-input" accept=".pdf">
                            <label class="form-file-label">
                                <i class="fas fa-cloud-upload-alt"></i><br>
                                Klik untuk mengunggah file
                            </label>
                            <div class="form-file-name">
                                @if($kepegawaian->file_skp)
                                    <div class="file-info">
                                        <i class="fas fa-check-circle text-success"></i>
                                        <span>{{ basename($kepegawaian->file_skp) }}</span>
                                        <button type="button" class="btn-change-file" data-target="file_skp">
                                            <i class="fas fa-exchange-alt"></i> Ganti File
                                        </button>
                                    </div>
                                @else
                                    <span class="no-file">Belum ada file diupload</span>
                                @endif
                            </div>
                        </div>
                        @error('file_skp')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                @endif

                @if($pendaftaranTerbaru)
                    <div class="form-section-header">
                        <i class="fas fa-file-alt"></i>
                        <h3>Dokumen Pendaftaran</h3>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Surat Tugas</label>
                        <div class="form-file">
                            <input type="file" name="file_surat_tugas" class="form-file-input" accept=".pdf">
                            <label class="form-file-label">
                                <i class="fas fa-cloud-upload-alt"></i><br>
                                Klik untuk mengunggah file
                            </label>
                            <div class="form-file-name">
                                @if($pendaftaranTerbaru->file_surat_tugas)
                                    <div class="file-info">
                                        <i class="fas fa-check-circle text-success"></i>
                                        <span>{{ basename($pendaftaranTerbaru->file_surat_tugas) }}</span>
                                        <button type="button" class="btn-change-file" data-target="file_surat_tugas">
                                            <i class="fas fa-exchange-alt"></i> Ganti File
                                        </button>
                                    </div>
                                @else
                                    <span class="no-file">Belum ada file diupload</span>
                                @endif
                            </div>
                        </div>
                        @error('file_surat_tugas')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Surat Kesediaan</label>
                        <div class="form-file">
                            <input type="file" name="file_surat_kesediaan" class="form-file-input" accept=".pdf">
                            <label class="form-file-label">
                                <i class="fas fa-cloud-upload-alt"></i><br>
                                Klik untuk mengunggah file
                            </label>
                            <div class="form-file-name">
                                @if($pendaftaranTerbaru->file_surat_kesediaan)
                                    <div class="file-info">
                                        <i class="fas fa-check-circle text-success"></i>
                                        <span>{{ basename($pendaftaranTerbaru->file_surat_kesediaan) }}</span>
                                        <button type="button" class="btn-change-file" data-target="file_surat_kesediaan">
                                            <i class="fas fa-exchange-alt"></i> Ganti File
                                        </button>
                                    </div>
                                @else
                                    <span class="no-file">Belum ada file diupload</span>
                                @endif
                            </div>
                        </div>
                        @error('file_surat_kesediaan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pakta Integritas</label>
                        <div class="form-file">
                            <input type="file" name="file_pakta_integritas" class="form-file-input" accept=".pdf">
                            <label class="form-file-label">
                                <i class="fas fa-cloud-upload-alt"></i><br>
                                Klik untuk mengunggah file
                            </label>
                            <div class="form-file-name">
                                @if($pendaftaranTerbaru->file_pakta_integritas)
                                    <div class="file-info">
                                        <i class="fas fa-check-circle text-success"></i>
                                        <span>{{ basename($pendaftaranTerbaru->file_pakta_integritas) }}</span>
                                        <button type="button" class="btn-change-file" data-target="file_pakta_integritas">
                                            <i class="fas fa-exchange-alt"></i> Ganti File
                                        </button>
                                    </div>
                                @else
                                    <span class="no-file">Belum ada file diupload</span>
                                @endif
                            </div>
                        </div>
                        @error('file_pakta_integritas')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Surat Sehat</label>
                        <div class="form-file">
                            <input type="file" name="file_surat_sehat" class="form-file-input" accept=".pdf">
                            <label class="form-file-label">
                                <i class="fas fa-cloud-upload-alt"></i><br>
                                Klik untuk mengunggah file
                            </label>
                            <div class="form-file-name">
                                @if($pendaftaranTerbaru->file_surat_sehat)
                                    <div class="file-info">
                                        <i class="fas fa-check-circle text-success"></i>
                                        <span>{{ basename($pendaftaranTerbaru->file_surat_sehat) }}</span>
                                        <button type="button" class="btn-change-file" data-target="file_surat_sehat">
                                            <i class="fas fa-exchange-alt"></i> Ganti File
                                        </button>
                                    </div>
                                @else
                                    <span class="no-file">Belum ada file diupload</span>
                                @endif
                            </div>
                        </div>
                        @error('file_surat_sehat')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                @endif
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('dashboard') }}" class="btn-cancel">
                    <i class="fas fa-times"></i>
                    Batal
                </a>
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Tab Navigation
            const formTabs = document.querySelectorAll('.form-tab');
            const tabContents = document.querySelectorAll('.form-tab-content');

            formTabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const tabId = tab.getAttribute('data-tab');

                    // Remove active class from all tabs and contents
                    formTabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(content => {
                        content.classList.remove('active');
                        content.style.opacity = '0';
                        content.style.transform = 'translateY(20px)';
                    });

                    // Add active class to clicked tab and corresponding content
                    tab.classList.add('active');
                    const activeContent = document.getElementById(tabId);
                    if (activeContent) {
                        activeContent.classList.add('active');
                        setTimeout(() => {
                            activeContent.style.opacity = '1';
                            activeContent.style.transform = 'translateY(0)';
                            activeContent.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        }, 10);
                    }
                });
            });

            // Initialize first tab
            const firstTabContent = document.querySelector('.form-tab-content.active');
            if (firstTabContent) {
                setTimeout(() => {
                    firstTabContent.style.opacity = '1';
                    firstTabContent.style.transform = 'translateY(0)';
                    firstTabContent.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                }, 100);
            }

            // Set data-tab attributes
            document.querySelectorAll('.form-tab').forEach((tab, index) => {
                const tabIds = ['tab-personal', 'tab-employment', 'tab-mentor', 'tab-documents'];
                if (index < tabIds.length) {
                    tab.setAttribute('data-tab', tabIds[index]);
                }
            });

            // File Upload Handling
            const fileInputs = document.querySelectorAll('.form-file-input');

            fileInputs.forEach(input => {
                input.addEventListener('change', function (e) {
                    const fileName = this.files[0] ? this.files[0].name : 'Belum ada file dipilih';
                    const fileInfo = this.closest('.form-file').querySelector('.form-file-name');

                    if (this.files[0]) {
                        fileInfo.innerHTML = `
                        <div class="file-info">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>${fileName}</span>
                            <button type="button" class="btn-change-file" data-target="${this.name}">
                                <i class="fas fa-exchange-alt"></i> Ganti File
                            </button>
                        </div>
                    `;

                        // Attach event listener to new change button
                        const changeBtn = fileInfo.querySelector('.btn-change-file');
                        if (changeBtn) {
                            changeBtn.addEventListener('click', () => {
                                this.click();
                            });
                        }
                    }
                });
            });

            // Change file button functionality
            document.querySelectorAll('.btn-change-file').forEach(btn => {
                btn.addEventListener('click', function () {
                    const target = this.getAttribute('data-target');
                    const fileInput = document.querySelector(`input[name="${target}"]`);
                    if (fileInput) {
                        fileInput.click();
                    }
                });
            });

            // Province and City Selection
            const provinceSelect = document.getElementById('id_provinsi');
            const citySelect = document.getElementById('id_kabupaten_kota');

            if (provinceSelect && citySelect) {
                provinceSelect.addEventListener('change', function () {
                    const provinceId = this.value;

                    if (provinceId) {
                        citySelect.disabled = false;
                        citySelect.innerHTML = '<option value="">Memuat kabupaten/kota...</option>';

                        // Fetch cities for selected province
                        fetch(`/api/kabupaten/${provinceId}`)
                            .then(response => response.json())
                            .then(data => {
                                citySelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                                data.forEach(city => {
                                    const option = document.createElement('option');
                                    option.value = city.id;
                                    option.textContent = city.name;
                                    citySelect.appendChild(option);
                                });

                                // Set selected value if exists in old input
                                const oldValue = "{{ old('id_kabupaten_kota', $kepegawaian->id_kabupaten_kota ?? '') }}";
                                if (oldValue) {
                                    citySelect.value = oldValue;
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching cities:', error);
                                citySelect.innerHTML = '<option value="">Gagal memuat data</option>';
                            });
                    } else {
                        citySelect.disabled = true;
                        citySelect.innerHTML = '<option value="">Pilih Provinsi Dahulu</option>';
                    }
                });
            }

            // Status Perkawinan Handling
            const statusPerkawinan = document.getElementById('status_perkawinan');
            const namaPasanganField = document.querySelector('input[name="nama_pasangan"]');

            if (statusPerkawinan && namaPasanganField) {
                statusPerkawinan.addEventListener('change', function () {
                    if (this.value === 'Menikah') {
                        namaPasanganField.disabled = false;
                        namaPasanganField.required = true;
                    } else {
                        namaPasanganField.disabled = true;
                        namaPasanganField.required = false;
                        namaPasanganField.value = '';
                    }
                });

                // Initialize on page load
                if (statusPerkawinan.value !== 'Menikah') {
                    namaPasanganField.disabled = true;
                    namaPasanganField.required = false;
                }
            }

            // Mentor Handling
            const sudahAdaMentor = document.getElementById('sudah_ada_mentor');
            const mentorContainer = document.getElementById('mentor-container');
            const mentorMode = document.getElementById('mentor_mode');
            const selectMentorForm = document.getElementById('select-mentor-form');
            const addMentorForm = document.getElementById('add-mentor-form');
            const mentorSelect = document.getElementById('id_mentor');

            if (sudahAdaMentor && mentorContainer) {
                sudahAdaMentor.addEventListener('change', function () {
                    if (this.value === 'Ya') {
                        mentorContainer.style.display = 'block';
                    } else {
                        mentorContainer.style.display = 'none';
                        if (mentorMode) mentorMode.value = '';
                        if (selectMentorForm) selectMentorForm.style.display = 'none';
                        if (addMentorForm) addMentorForm.style.display = 'none';
                        if (mentorSelect) mentorSelect.value = '';
                    }
                });

                // Initialize on page load
                if (sudahAdaMentor.value === 'Ya') {
                    mentorContainer.style.display = 'block';
                }
            }

            if (mentorMode && selectMentorForm && addMentorForm) {
                mentorMode.addEventListener('change', function () {
                    if (this.value === 'pilih') {
                        selectMentorForm.style.display = 'block';
                        addMentorForm.style.display = 'none';
                    } else if (this.value === 'tambah') {
                        selectMentorForm.style.display = 'none';
                        addMentorForm.style.display = 'block';
                    } else {
                        selectMentorForm.style.display = 'none';
                        addMentorForm.style.display = 'none';
                    }
                });

                // Initialize on page load
                if (mentorMode.value === 'pilih') {
                    selectMentorForm.style.display = 'block';
                } else if (mentorMode.value === 'tambah') {
                    addMentorForm.style.display = 'block';
                }
            }

            // Update mentor details when mentor is selected
            if (mentorSelect) {
                mentorSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];

                    if (selectedOption.dataset.nama) {
                        document.getElementById('nama_mentor_select').value = selectedOption.dataset.nama;
                        document.getElementById('jabatan_mentor_select').value = selectedOption.dataset.jabatan;
                        document.getElementById('nomor_rekening_mentor_select').value = selectedOption.dataset.rekening || '';
                        document.getElementById('npwp_mentor_select').value = selectedOption.dataset.npwp || '';
                    } else {
                        document.getElementById('nama_mentor_select').value = '';
                        document.getElementById('jabatan_mentor_select').value = '';
                        document.getElementById('nomor_rekening_mentor_select').value = '';
                        document.getElementById('npwp_mentor_select').value = '';
                    }
                });

                // Trigger change on page load if mentor is selected
                if (mentorSelect.value) {
                    mentorSelect.dispatchEvent(new Event('change'));
                }
            }

            // Form Submission
            const editForm = document.getElementById('editForm');
            const submitBtn = document.getElementById('submitBtn');

            if (editForm) {
                editForm.addEventListener('submit', function (e) {
                    // Validate required fields
                    const requiredFields = editForm.querySelectorAll('[required]');
                    let isValid = true;

                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            field.classList.add('error');
                            isValid = false;

                            // Find tab and activate it
                            const tabContent = field.closest('.form-tab-content');
                            if (tabContent) {
                                const tabId = tabContent.id;
                                const tabButton = document.querySelector(`.form-tab[data-tab="${tabId}"]`);
                                if (tabButton) {
                                    tabButton.click();
                                }
                            }
                        } else {
                            field.classList.remove('error');
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        alert('Harap lengkapi semua field yang wajib diisi.');
                        return;
                    }

                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner"></span> Menyimpan...';

                    // File size validation (client-side)
                    const fileInputs = editForm.querySelectorAll('input[type="file"]');
                    let fileSizeValid = true;

                    fileInputs.forEach(input => {
                        if (input.files.length > 0) {
                            const file = input.files[0];
                            const maxSize = 1024 * 1024; // 1MB

                            if (file.size > maxSize) {
                                fileSizeValid = false;
                                input.classList.add('error');
                                alert(`File ${input.name} terlalu besar. Maksimal 1MB.`);
                            }
                        }
                    });

                    if (!fileSizeValid) {
                        e.preventDefault();
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
                        return;
                    }

                    // Form is valid, allow submission
                });
            }

            // Real-time validation
            const formInputs = editForm.querySelectorAll('input, select, textarea');

            formInputs.forEach(input => {
                input.addEventListener('blur', function () {
                    if (this.hasAttribute('required') && !this.value.trim()) {
                        this.classList.add('error');
                    } else {
                        this.classList.remove('error');
                    }

                    // Email validation
                    if (this.type === 'email' && this.value) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(this.value)) {
                            this.classList.add('error');
                        } else {
                            this.classList.remove('error');
                        }
                    }

                    // Phone validation (simple)
                    if (this.name === 'nomor_hp' && this.value) {
                        const phoneRegex = /^[0-9+\-\s()]{10,20}$/;
                        if (!phoneRegex.test(this.value)) {
                            this.classList.add('error');
                        } else {
                            this.classList.remove('error');
                        }
                    }
                });
            });

            // Auto-save draft (optional)
            let autoSaveTimer;
            const formData = {};

            editForm.querySelectorAll('input, select, textarea').forEach(input => {
                input.addEventListener('input', function () {
                    clearTimeout(autoSaveTimer);
                    autoSaveTimer = setTimeout(() => {
                        saveDraft();
                    }, 2000);
                });
            });

            function saveDraft() {
                const formData = new FormData(editForm);
                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });

                // Save to localStorage (or send to server)
                localStorage.setItem('dashboard_edit_draft', JSON.stringify(data));
                console.log('Draft saved');
            }

            // Load draft on page load
            const savedDraft = localStorage.getItem('dashboard_edit_draft');
            if (savedDraft) {
                const draftData = JSON.parse(savedDraft);
                Object.keys(draftData).forEach(key => {
                    const input = editForm.querySelector(`[name="${key}"]`);
                    if (input && input.type !== 'file') {
                        input.value = draftData[key];
                    }
                });
            }

            // Clear draft on successful submission
            editForm.addEventListener('submit', function () {
                localStorage.removeItem('dashboard_edit_draft');
            });
        });
    </script>
@endsection