@extends('admin.partials.layout')

@section('title', $isEdit ? 'Edit Angkatan' : 'Tambah Angkatan - Sistem Pelatihan')

@section('content')
    <!-- Page Header -->
    <div class="page-header bg-gradient-primary rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-calendar-alt fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">{{ $isEdit ? 'Edit Angkatan' : 'Tambah Angkatan Baru' }}</h1>
                        <p class="text-white-50 mb-0">{{ $isEdit ? 'Perbarui data angkatan' : 'Tambah data angkatan baru' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('angkatan.index') }}" class="btn btn-light btn-hover-lift shadow-sm d-flex align-items-center">
                    <i class="fas fa-arrow-left me-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Section -->
    <div class="alert-container mb-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
                <div class="alert-icon flex-shrink-0">
                    <i class="fas fa-check-circle fa-lg"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <strong>Sukses!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <div class="alert-icon flex-shrink-0">
                        <i class="fas fa-exclamation-circle fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <strong>Error!</strong> Terdapat kesalahan dalam input data:
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Form Section -->
    <div class="card border-0 shadow-lg">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="card-title mb-0 fw-semibold">
                <i class="fas fa-edit me-2" style="color: #285496;"></i> 
                Form {{ $isEdit ? 'Edit' : 'Tambah' }} Angkatan
            </h5>
        </div>
        
        <form method="POST" action="{{ $isEdit ? route('angkatan.update', $angkatan->id) : route('angkatan.store') }}" id="angkatanForm">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif
            
            <div class="card-body">
                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <!-- Jenis Pelatihan -->
                        <div class="mb-4">
                            <label for="id_jenis_pelatihan" class="form-label fw-semibold">
                                <i class="fas fa-graduation-cap me-1 text-primary"></i>
                                Jenis Pelatihan <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('id_jenis_pelatihan') is-invalid @enderror" 
                                    id="id_jenis_pelatihan" 
                                    name="id_jenis_pelatihan"
                                    required>
                                <option value="">Pilih Jenis Pelatihan</option>
                                @foreach($jenisPelatihan as $jenis)
                                    <option value="{{ $jenis->id }}" 
                                        {{ old('id_jenis_pelatihan', $isEdit ? $angkatan->id_jenis_pelatihan : '') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama_pelatihan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_jenis_pelatihan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Pilih jenis pelatihan untuk angkatan ini
                            </small>
                        </div>

                        <!-- Nama Angkatan -->
                        <div class="mb-4">
                            <label for="nama_angkatan" class="form-label fw-semibold">
                                <i class="fas fa-tag me-1 text-primary"></i>
                                Nama Angkatan <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nama_angkatan') is-invalid @enderror" 
                                   id="nama_angkatan" 
                                   name="nama_angkatan"
                                   value="{{ old('nama_angkatan', $isEdit ? $angkatan->nama_angkatan : '') }}"
                                   placeholder="Contoh: Angkatan 1, Gelombang 2, dll"
                                   required>
                            @error('nama_angkatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Masukkan nama angkatan yang mudah dikenali
                            </small>
                        </div>

                        <!-- Tahun -->
                        <div class="mb-4">
                            <label for="tahun" class="form-label fw-semibold">
                                <i class="fas fa-calendar me-1 text-primary"></i>
                                Tahun <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('tahun') is-invalid @enderror" 
                                   id="tahun" 
                                   name="tahun"
                                   value="{{ old('tahun', $isEdit ? $angkatan->tahun : date('Y')) }}"
                                   min="2000"
                                   max="{{ date('Y') + 5 }}"
                                   required>
                            @error('tahun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Tahun pelaksanaan angkatan
                            </small>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <!-- Kuota -->
                        <div class="mb-4">
                            <label for="kuota" class="form-label fw-semibold">
                                <i class="fas fa-users me-1 text-primary"></i>
                                Kuota Peserta
                            </label>
                            <input type="number" 
                                   class="form-control @error('kuota') is-invalid @enderror" 
                                   id="kuota" 
                                   name="kuota"
                                   value="{{ old('kuota', $isEdit ? $angkatan->kuota : '') }}"
                                   placeholder="Contoh: 50"
                                   min="1">
                            @error('kuota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Jumlah maksimal peserta yang dapat diterima (kosongkan untuk tidak terbatas)
                            </small>
                        </div>

                        <!-- Status Angkatan -->
                        <div class="mb-4">
                            <label for="status_angkatan" class="form-label fw-semibold">
                                <i class="fas fa-toggle-on me-1 text-primary"></i>
                                Status Angkatan <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('status_angkatan') is-invalid @enderror" 
                                    id="status_angkatan" 
                                    name="status_angkatan"
                                    required>
                                <option value="Dibuka" {{ old('status_angkatan', $isEdit ? $angkatan->status_angkatan : 'Dibuka') == 'Dibuka' ? 'selected' : '' }}>
                                    Dibuka
                                </option>
                                <option value="Berlangsung" {{ old('status_angkatan', $isEdit ? $angkatan->status_angkatan : '') == 'Berlangsung' ? 'selected' : '' }}>
                                    Berlangsung
                                </option>
                                <option value="Selesai" {{ old('status_angkatan', $isEdit ? $angkatan->status_angkatan : '') == 'Selesai' ? 'selected' : '' }}>
                                    Selesai
                                </option>
                                <option value="Ditutup" {{ old('status_angkatan', $isEdit ? $angkatan->status_angkatan : '') == 'Ditutup' ? 'selected' : '' }}>
                                    Ditutup
                                </option>
                            </select>
                            @error('status_angkatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Status menentukan apakah angkatan masih menerima pendaftaran
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Tanggal Mulai dan Selesai -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label for="tanggal_mulai" class="form-label fw-semibold">
                                <i class="fas fa-calendar-day me-1 text-primary"></i>
                                Tanggal Mulai
                            </label>
                            <input type="date" 
                                   class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                   id="tanggal_mulai" 
                                   name="tanggal_mulai"
                                   value="{{ old('tanggal_mulai', $isEdit && $angkatan->tanggal_mulai ? \Carbon\Carbon::parse($angkatan->tanggal_mulai)->format('Y-m-d') : '') }}">
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Tanggal mulai pelatihan (opsional)
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label for="tanggal_selesai" class="form-label fw-semibold">
                                <i class="fas fa-calendar-check me-1 text-primary"></i>
                                Tanggal Selesai
                            </label>
                            <input type="date" 
                                   class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                   id="tanggal_selesai" 
                                   name="tanggal_selesai"
                                   value="{{ old('tanggal_selesai', $isEdit && $angkatan->tanggal_selesai ? \Carbon\Carbon::parse($angkatan->tanggal_selesai)->format('Y-m-d') : '') }}">
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Tanggal selesai pelatihan (opsional)
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Validation Summary -->
                <div class="alert alert-warning d-none" id="validationSummary">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>
                            <strong class="d-block">Periksa kembali data berikut:</strong>
                            <ul class="mb-0 mt-1" id="validationErrors"></ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white py-3 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('angkatan.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-times me-2"></i> Batal
                        </a>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary px-4 btn-lift">
                            <i class="fas fa-save me-2"></i> {{ $isEdit ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Info Card -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">
                <i class="fas fa-info-circle me-2 text-primary"></i> Informasi Penting
            </h6>
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Dibuka:</strong> Angkatan dapat menerima pendaftaran baru
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-play-circle text-primary me-2"></i>
                            <strong>Berlangsung:</strong> Angkatan sedang berjalan
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-warning me-2"></i>
                            <strong>Selesai:</strong> Angkatan telah selesai dilaksanakan
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-times-circle text-danger me-2"></i>
                            <strong>Ditutup:</strong> Angkatan tidak menerima pendaftaran baru
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('angkatanForm');
            const validationSummary = document.getElementById('validationSummary');
            const validationErrors = document.getElementById('validationErrors');
            
            // Auto-hide alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.classList.contains('show') && !alert.classList.contains('alert-warning')) {
                        bootstrap.Alert.getOrCreateInstance(alert).close();
                    }
                }, 5000);
            });

            // Date validation
            const tanggalMulai = document.getElementById('tanggal_mulai');
            const tanggalSelesai = document.getElementById('tanggal_selesai');

            tanggalMulai.addEventListener('change', function() {
                if (tanggalSelesai.value && this.value > tanggalSelesai.value) {
                    showDateError('Tanggal mulai tidak boleh lebih besar dari tanggal selesai');
                    this.focus();
                } else {
                    hideDateError();
                }
            });

            tanggalSelesai.addEventListener('change', function() {
                if (tanggalMulai.value && this.value < tanggalMulai.value) {
                    showDateError('Tanggal selesai tidak boleh lebih kecil dari tanggal mulai');
                    this.focus();
                } else {
                    hideDateError();
                }
            });

            function showDateError(message) {
                if (!tanggalSelesai.classList.contains('is-invalid')) {
                    tanggalSelesai.classList.add('is-invalid');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = message;
                    tanggalSelesai.parentNode.appendChild(errorDiv);
                }
            }

            function hideDateError() {
                tanggalSelesai.classList.remove('is-invalid');
                const existingError = tanggalSelesai.parentNode.querySelector('.invalid-feedback');
                if (existingError && existingError.textContent.includes('tidak boleh lebih')) {
                    existingError.remove();
                }
            }

            // Form validation
            form.addEventListener('submit', function (e) {
                let errors = [];
                validationErrors.innerHTML = '';
                validationSummary.classList.add('d-none');

                // Validasi tahun
                const tahun = document.getElementById('tahun').value;
                const currentYear = new Date().getFullYear();
                if (tahun < 2000 || tahun > currentYear + 5) {
                    errors.push(`Tahun harus antara 2000 dan ${currentYear + 5}`);
                }

                // Validasi tanggal
                if (tanggalMulai.value && tanggalSelesai.value) {
                    if (new Date(tanggalMulai.value) > new Date(tanggalSelesai.value)) {
                        errors.push('Tanggal mulai tidak boleh lebih besar dari tanggal selesai');
                    }
                }

                // Validasi kuota
                const kuota = document.getElementById('kuota').value;
                if (kuota && kuota < 1) {
                    errors.push('Kuota harus lebih dari 0 jika diisi');
                }

                if (errors.length > 0) {
                    e.preventDefault();
                    validationSummary.classList.remove('d-none');
                    errors.forEach(error => {
                        const li = document.createElement('li');
                        li.textContent = error;
                        validationErrors.appendChild(li);
                    });
                    
                    // Scroll to validation summary
                    validationSummary.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });

            // Real-time validation feedback
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('blur', function () {
                    validateField(this);
                });
            });

            function validateField(field) {
                if (field.hasAttribute('required') && !field.value.trim()) {
                    field.classList.add('is-invalid');
                    if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('invalid-feedback')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Field ini wajib diisi';
                        field.parentNode.appendChild(errorDiv);
                    }
                } else {
                    field.classList.remove('is-invalid');
                    const errorDiv = field.parentNode.querySelector('.invalid-feedback');
                    if (errorDiv && errorDiv.textContent === 'Field ini wajib diisi') {
                        errorDiv.remove();
                    }
                }
            }
        });
    </script>

    <style>
        /* Form Styling */
        .form-label {
            font-weight: 600;
            color: #285496;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #285496;
            box-shadow: 0 0 0 0.25rem rgba(40, 84, 150, 0.25);
        }

        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #dc3545;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        }

        .invalid-feedback {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }

        /* Card Styling */
        .card {
            border-radius: 12px;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e9ecef 100%);
            border-bottom: 2px solid #285496;
        }

        /* Button Styling */
        .btn {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1e3d6f 0%, #2d5499 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 84, 150, 0.4);
        }

        .btn-outline-secondary {
            border: 2px solid #6c757d;
            color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .btn-lift {
            transition: transform 0.2s ease;
        }

        .btn-lift:hover {
            transform: translateY(-2px);
        }

        /* Info Card */
        .list-unstyled li {
            padding-left: 0;
            line-height: 1.6;
        }

        .list-unstyled i {
            width: 20px;
            text-align: center;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem;
            }
            
            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
            
            .form-control, .form-select {
                padding: 0.5rem 0.75rem;
            }
        }
    </style>
@endsection