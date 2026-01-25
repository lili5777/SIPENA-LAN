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
            max-width: 600px;
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

        .form-group {
            margin-bottom: 1.5rem;
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

    @if ($kunci_judul == false)
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
                        {{-- <button onclick="confirmDelete()" class="btn-danger">
                            <i class="fas fa-trash"></i>
                            Hapus
                        </button> --}}
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

                    {{-- @if($aksiPerubahan->biodata)
                    <div class="data-item">
                        <span class="data-label">
                            <i class="fas fa-align-left"></i>
                            Deskripsi / Biodata
                        </span>
                        <span class="data-value">{{ $aksiPerubahan->biodata }}</span>
                    </div>
                    @endif --}}

                    @if($aksiPerubahan->file)
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-file"></i>
                                Dokumen Hasil
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
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Hidden Delete Form -->
            <form id="deleteForm" action="{{ route('aksiperubahan.destroy', $aksiPerubahan->id) }}" method="POST"
                style="display: none;">
                @csrf
                @method('DELETE')
            </form>

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
            <form action="{{ route('aksiperubahan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Judul Aksi Perubahan *</label>
                    <input type="text" name="judul" class="form-control" required
                        placeholder="Masukkan judul aksi perubahan">
                </div>

                {{-- <div class="form-group">
                    <label class="form-label">Deskripsi / Biodata</label>
                    <textarea name="biodata" class="form-control"
                        placeholder="Masukkan deskripsi atau biodata aksi perubahan (opsional)"></textarea>
                </div> --}}

                <div class="form-group">
                    <label class="form-label">Dokumen Hasil (PDF - Max 5MB)</label>
                    <input type="file" name="file" class="form-control" accept=".pdf">
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
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Judul Aksi Perubahan *</label>
                        <input type="text" name="judul" class="form-control" required value="{{ $aksiPerubahan->judul }}"
                            placeholder="Masukkan judul aksi perubahan">
                    </div>

                    {{-- <div class="form-group">
                        <label class="form-label">Deskripsi / Biodata</label>
                        <textarea name="biodata" class="form-control"
                            placeholder="Masukkan deskripsi atau biodata aksi perubahan (opsional)">{{ $aksiPerubahan->biodata }}</textarea>
                    </div> --}}

                    <div class="form-group">
                        <label class="form-label">Dokumen Hasil (PDF Max 5MB)</label>
                        @if($aksiPerubahan->file)
                            <div class="file-preview mb-2">
                                <div class="file-icon">
                                    <i class="fas fa-file"></i>
                                </div>
                                <div class="file-info">
                                    <div class="file-name">{{ basename($aksiPerubahan->file) }}</div>
                                    <div class="file-size">File saat ini</div>
                                </div>
                            </div>
                        @endif
                        <input type="file" name="file" class="form-control" accept=".pdf">
                        <small style="color: #64748b; font-size: 0.85rem;">Kosongkan jika tidak ingin mengubah file</small>
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
        document.querySelector('#addModal form').addEventListener('submit', function (e) {
            const button = document.getElementById('btnSubmitAdd');
            showLoading(button);
        });

        // Handle Edit Form Submit
        @if($aksiPerubahan)
            document.querySelector('#editModal form').addEventListener('submit', function (e) {
                const button = document.getElementById('btnSubmitEdit');
                showLoading(button);
            });
        @endif

            function openAddModal() {
                document.getElementById('addModal').classList.add('active');
                document.body.style.overflow = 'hidden';
            }

        function closeAddModal() {
            document.getElementById('addModal').classList.remove('active');
            document.body.style.overflow = 'auto';
            // Reset form and button state
            const form = document.querySelector('#addModal form');
            const button = document.getElementById('btnSubmitAdd');
            form.reset();
            hideLoading(button);
        }

        function openEditModal() {
            document.getElementById('editModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('active');
            document.body.style.overflow = 'auto';
            // Reset button state
            @if($aksiPerubahan)
                const button = document.getElementById('btnSubmitEdit');
                hideLoading(button);
            @endif
            }

        function confirmDelete() {
            if (confirm('Apakah Anda yakin ingin menghapus Aksi Perubahan ini? Data yang sudah dihapus tidak dapat dikembalikan.')) {
                document.getElementById('deleteForm').submit();
            }
        }

        // Close modal when clicking outside
        window.onclick = function (event) {
            const addModal = document.getElementById('addModal');
            const editModal = document.getElementById('editModal');

            if (event.target == addModal) {
                closeAddModal();
            }
            if (event.target == editModal) {
                closeEditModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeAddModal();
                closeEditModal();
            }
        });

        // Auto hide alerts after 5 seconds
        setTimeout(function () {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s ease';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
@endsection