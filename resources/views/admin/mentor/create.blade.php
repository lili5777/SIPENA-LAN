@extends('admin.partials.layout')

@section('title', $isEdit ? 'Edit Mentor' : 'Tambah Mentor - Sistem Pelatihan')

@section('content')
    <!-- Page Header -->
    <div class="page-header bg-gradient-primary rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-chalkboard-teacher fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">{{ $isEdit ? 'Edit Mentor' : 'Tambah Mentor Baru' }}</h1>
                        <p class="text-white-50 mb-0">{{ $isEdit ? 'Perbarui data mentor' : 'Tambah data mentor baru' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('mentor.index') }}"
                    class="btn btn-light btn-hover-lift shadow-sm d-flex align-items-center">
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
                Form {{ $isEdit ? 'Edit' : 'Tambah' }} Mentor
            </h5>
        </div>

        <form method="POST" action="{{ $isEdit ? route('mentor.update', $mentor->id) : route('mentor.store') }}"
            id="mentorForm">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="card-body">
                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <!-- Nama Mentor -->
                        <div class="mb-4">
                            <label for="nama_mentor" class="form-label fw-semibold">
                                <i class="fas fa-user me-1 text-primary"></i>
                                Nama Mentor <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nama_mentor') is-invalid @enderror"
                                id="nama_mentor" name="nama_mentor"
                                value="{{ old('nama_mentor', $isEdit ? $mentor->nama_mentor : '') }}"
                                placeholder="Masukkan nama lengkap mentor" required>
                            @error('nama_mentor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Nama lengkap mentor sesuai dengan dokumen resmi
                            </small>
                        </div>

                        <!-- NIP Mentor -->
                        <div class="mb-4">
                            <label for="nip_mentor" class="form-label fw-semibold">
                                <i class="fas fa-id-card me-1 text-primary"></i>
                                NIP Mentor
                            </label>
                            <input type="text" class="form-control @error('nip_mentor') is-invalid @enderror"
                                id="nip_mentor" name="nip_mentor"
                                value="{{ old('nip_mentor', $isEdit ? $mentor->nip_mentor : '') }}"
                                placeholder="Masukkan NIP mentor (jika ada)">
                            @error('nip_mentor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jabatan Mentor -->
                        <div class="mb-4">
                            <label for="jabatan_mentor" class="form-label fw-semibold">
                                <i class="fas fa-briefcase me-1 text-primary"></i>
                                Jabatan Mentor
                            </label>
                            <input type="text" class="form-control @error('jabatan_mentor') is-invalid @enderror"
                                id="jabatan_mentor" name="jabatan_mentor"
                                value="{{ old('jabatan_mentor', $isEdit ? $mentor->jabatan_mentor : '') }}"
                                placeholder="Contoh: Senior Trainer, Konsultan, dll">
                            @error('jabatan_mentor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Jabatan atau posisi mentor dalam organisasi
                            </small>
                        </div>

                        <!-- Email Mentor -->
                        <div class="mb-4">
                            <label for="email_mentor" class="form-label fw-semibold">
                                <i class="fas fa-envelope me-1 text-primary"></i>
                                Email Mentor
                            </label>
                            <input type="email" class="form-control @error('email_mentor') is-invalid @enderror"
                                id="email_mentor" name="email_mentor"
                                value="{{ old('email_mentor', $isEdit ? $mentor->email_mentor : '') }}"
                                placeholder="contoh@email.com">
                            @error('email_mentor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Email aktif untuk komunikasi dengan mentor
                            </small>
                        </div>

                        <!-- Nomor HP Mentor -->
                        <div class="mb-4">
                            <label for="nomor_hp_mentor" class="form-label fw-semibold">
                                <i class="fas fa-phone me-1 text-primary"></i>
                                Nomor HP Mentor
                            </label>
                            <input type="text" class="form-control @error('nomor_hp_mentor') is-invalid @enderror"
                                id="nomor_hp_mentor" name="nomor_hp_mentor"
                                value="{{ old('nomor_hp_mentor', $isEdit ? $mentor->nomor_hp_mentor : '') }}"
                                placeholder="081234567890">
                            @error('nomor_hp_mentor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Nomor WhatsApp atau telepon yang dapat dihubungi
                            </small>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <!-- Nomor Rekening -->
                        <div class="mb-4">
                            <label for="nomor_rekening" class="form-label fw-semibold">
                                <i class="fas fa-credit-card me-1 text-primary"></i>
                                Nomor Rekening
                            </label>
                            <input type="text" class="form-control @error('nomor_rekening') is-invalid @enderror"
                                id="nomor_rekening" name="nomor_rekening"
                                value="{{ old('nomor_rekening', $isEdit ? $mentor->nomor_rekening : '') }}"
                                placeholder="Contoh: 1234567890">
                            @error('nomor_rekening')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Nomor rekening untuk pembayaran honorarium
                            </small>
                        </div>

                        <!-- NPWP Mentor -->
                        <div class="mb-4">
                            <label for="npwp_mentor" class="form-label fw-semibold">
                                <i class="fas fa-id-card me-1 text-primary"></i>
                                NPWP Mentor
                            </label>
                            <input type="text" class="form-control @error('npwp_mentor') is-invalid @enderror"
                                id="npwp_mentor" name="npwp_mentor"
                                value="{{ old('npwp_mentor', $isEdit ? $mentor->npwp_mentor : '') }}"
                                placeholder="Contoh: 12.345.678.9-012.345">
                            @error('npwp_mentor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                NPWP untuk keperluan perpajakan
                            </small>
                        </div>

                        <!-- Status Aktif -->
                        <div class="mb-4">
                            <label for="status_aktif" class="form-label fw-semibold">
                                <i class="fas fa-toggle-on me-1 text-primary"></i>
                                Status Mentor <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('status_aktif') is-invalid @enderror" id="status_aktif"
                                name="status_aktif" required>
                                <option value="1" {{ old('status_aktif', $isEdit ? $mentor->status_aktif : '1') == '1' ? 'selected' : '' }}>
                                    Aktif
                                </option>
                                <option value="0" {{ old('status_aktif', $isEdit ? $mentor->status_aktif : '') == '0' ? 'selected' : '' }}>
                                    Nonaktif
                                </option>
                            </select>
                            @error('status_aktif')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Status menentukan apakah mentor masih aktif mengajar
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
                        <a href="{{ route('mentor.index') }}" class="btn btn-outline-secondary px-4">
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
                            <strong>Aktif:</strong> Mentor dapat ditugaskan untuk pelatihan baru
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-exclamation-circle text-warning me-2"></i>
                            Data email harus unik dan tidak boleh duplikat
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-times-circle text-secondary me-2"></i>
                            <strong>Nonaktif:</strong> Mentor tidak dapat ditugaskan pelatihan baru
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Informasi keuangan untuk keperluan pembayaran honorarium
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
            const form = document.getElementById('mentorForm');
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

            // Format NPWP
            const npwpInput = document.getElementById('npwp_mentor');
            npwpInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');
                let formattedValue = '';

                if (value.length > 2) {
                    formattedValue += value.substr(0, 2) + '.';
                    value = value.substr(2);
                }
                if (value.length > 3) {
                    formattedValue += value.substr(0, 3) + '.';
                    value = value.substr(3);
                }
                if (value.length > 3) {
                    formattedValue += value.substr(0, 3) + '.';
                    value = value.substr(3);
                }
                if (value.length > 1) {
                    formattedValue += value.substr(0, 1) + '-';
                    value = value.substr(1);
                }
                if (value.length > 3) {
                    formattedValue += value.substr(0, 3) + '.';
                    value = value.substr(3);
                }
                if (value.length > 0) {
                    formattedValue += value;
                }

                e.target.value = formattedValue;
            });

            // Format nomor HP
            const phoneInput = document.getElementById('nomor_hp_mentor');
            phoneInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');

                // Tambahkan +62 jika dimulai dengan 0
                if (value.startsWith('0')) {
                    value = '62' + value.substr(1);
                }

                // Batasi maksimal 15 digit
                value = value.substr(0, 15);

                e.target.value = value;
            });

            // Form validation
            form.addEventListener('submit', function (e) {
                let errors = [];
                validationErrors.innerHTML = '';
                validationSummary.classList.add('d-none');

                // Validasi email format
                const email = document.getElementById('email_mentor').value;
                if (email && !validateEmail(email)) {
                    errors.push('Format email tidak valid');
                }

                // Validasi nomor HP (minimal 10 digit jika diisi)
                const phone = document.getElementById('nomor_hp_mentor').value;
                if (phone && phone.replace(/\D/g, '').length < 10) {
                    errors.push('Nomor HP minimal 10 digit');
                }

                // Validasi NPWP format (15 digit jika diisi)
                const npwp = document.getElementById('npwp_mentor').value;
                const npwpDigits = npwp.replace(/\D/g, '');
                // if (npwp && npwpDigits.length !== 15) {
                //     errors.push('NPWP harus 15 digit');
                // }

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

            // Email validation function
            function validateEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }

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

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #285496;
            box-shadow: 0 0 0 0.25rem rgba(40, 84, 150, 0.25);
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
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

            .form-control,
            .form-select {
                padding: 0.5rem 0.75rem;
            }
        }
    </style>
@endsection