@extends('admin.partials.layout')

@section('title', 'Tambah Pengguna - Sistem Inventori Obat')

@section('content')
    <!-- Page Header -->
    <div class="page-header bg-gradient-primary rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-user-plus fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">Tambah Pengguna Baru</h1>
                        <p class="text-white-50 mb-0">Buat akun pengguna baru dengan hak akses yang sesuai</p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('users.index') }}" class="btn btn-light btn-hover-lift shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Form Section -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-user-plus me-2" style="color: #285496;"></i> Form Tambah Pengguna
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST" id="userForm">
                        @csrf

                        <!-- Nama -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium">Nama <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Nama lengkap pengguna yang akan ditampilkan di sistem</div>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email') }}" placeholder="contoh@email.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Alamat email yang valid dan belum terdaftar di sistem</div>
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-medium">Password <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Minimal 8 karakter" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Password minimal 8 karakter dengan kombinasi huruf dan angka</div>
                        </div>

                        <!-- Roles (Dropdown) -->
                        <div class="mb-4">
                            <label for="role_id" class="form-label fw-medium">Pilih Role <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user-tag"></i>
                                </span>
                                <select class="form-select @error('role_id') is-invalid @enderror" id="role_id"
                                    name="role_id" required>
                                    <option value="" disabled selected>-- Pilih Role --</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Role menentukan hak akses pengguna dalam sistem</div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-lift">
                                <i class="fas fa-times me-2"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary btn-lift">
                                <i class="fas fa-save me-2"></i> Simpan Pengguna
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Section -->
        <div class="col-lg-4">
            <!-- Info Card -->
            <div class="card border-0 shadow-lg mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-info-circle me-2" style="color: #285496;"></i> Panduan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0 shadow-sm">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-lightbulb fa-lg mt-1 me-3" style="color: #285496;"></i>
                            <div>
                                <h6 class="alert-heading mb-2">Tips Penting</h6>
                                <ul class="mb-0 ps-3">
                                    <li class="mb-1">Email harus <strong>unik</strong> dan valid</li>
                                    <li class="mb-1">Gunakan <strong>password kuat</strong></li>
                                    <li class="mb-1">Pilih <strong>role sesuai kebutuhan</strong></li>
                                    <li class="mb-1">Verifikasi data sebelum disimpan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });

            // Update role description when role is selected
            const roleSelect = document.getElementById('role_id');
            const roleDescription = document.getElementById('roleDescription');

            // Role descriptions
            const roleDescriptions = {
                @foreach($roles as $role)
                    '{{ $role->id }}': {
                    name: '{{ $role->name }}',
                    color: getRoleColor('{{ $role->name }}'),
                    description: '{{ $role->description ?? "Role untuk " . $role->name }}',
                    permissions: {{ $role->permissions_count ?? 0 }}
                    },
                @endforeach
            };

        // Function to get role color
        function getRoleColor(roleName) {
            const colors = {
                'Admin': 'bg-danger',
                'Super Admin': 'bg-danger',
                'Manager': 'bg-warning',
                'Supervisor': 'bg-info',
                'Staff': 'bg-primary',
                'User': 'bg-secondary'
            };
            return colors[roleName] || 'bg-primary';
        }

        // Update role description
        roleSelect.addEventListener('change', function () {
            const selectedRoleId = this.value;
            const roleInfo = roleDescriptions[selectedRoleId];

            if (roleInfo) {
                roleDescription.innerHTML = `
                        <div class="role-info-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge ${roleInfo.color} fs-6">${roleInfo.name}</span>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-key me-1"></i>${roleInfo.permissions}
                                </span>
                            </div>
                            <p class="mb-3">${roleInfo.description}</p>
                            <div class="role-features">
                                <h6 class="mb-2">Fitur Utama:</h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-1"><i class="fas fa-check-circle text-success me-2"></i>Akses sesuai role</li>
                                    <li class="mb-1"><i class="fas fa-check-circle text-success me-2"></i>Hak akses terbatas</li>
                                    <li><i class="fas fa-check-circle text-success me-2"></i>Operasional sistem</li>
                                </ul>
                            </div>
                        </div>
                    `;
            } else {
                roleDescription.innerHTML = `
                        <div class="empty-state mb-3">
                            <i class="fas fa-user-tag fa-3x" style="color: #e9ecef;"></i>
                        </div>
                        <p class="text-muted mb-0">Pilih role untuk melihat detail</p>
                    `;
            }
        });

        // Form validation
        const userForm = document.getElementById('userForm');

        userForm.addEventListener('submit', function (e) {
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const roleSelect = document.getElementById('role_id');

            // Clear previous errors
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });

            let hasError = false;

            // Validate name
            if (!nameInput.value.trim()) {
                nameInput.classList.add('is-invalid');
                showError(nameInput, 'Nama wajib diisi');
                hasError = true;
            }

            // Validate email
            if (!emailInput.value.trim()) {
                emailInput.classList.add('is-invalid');
                showError(emailInput, 'Email wajib diisi');
                hasError = true;
            } else if (!isValidEmail(emailInput.value)) {
                emailInput.classList.add('is-invalid');
                showError(emailInput, 'Format email tidak valid');
                hasError = true;
            }

            // Validate password
            if (!passwordInput.value) {
                passwordInput.classList.add('is-invalid');
                showError(passwordInput, 'Password wajib diisi');
                hasError = true;
            } else if (passwordInput.value.length < 8) {
                passwordInput.classList.add('is-invalid');
                showError(passwordInput, 'Password minimal 8 karakter');
                hasError = true;
            }

            // Validate role
            if (!roleSelect.value) {
                roleSelect.classList.add('is-invalid');
                showError(roleSelect, 'Role wajib dipilih');
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                showToast('Harap perbaiki data yang salah', 'error');
            }
        });

        // Helper function to show error
        function showError(element, message) {
            let errorDiv = element.parentNode.querySelector('.invalid-feedback');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                element.parentNode.appendChild(errorDiv);
            }
            errorDiv.textContent = message;
        }

        // Email validation function
        function isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // Real-time validation
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');

        nameInput.addEventListener('input', function () {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
                removeError(this);
            }
        });

        emailInput.addEventListener('input', function () {
            if (this.value.trim() && isValidEmail(this.value)) {
                this.classList.remove('is-invalid');
                removeError(this);
            }
        });

        passwordInput.addEventListener('input', function () {
            if (this.value && this.value.length >= 8) {
                this.classList.remove('is-invalid');
                removeError(this);
            }
        });

        roleSelect.addEventListener('change', function () {
            if (this.value) {
                this.classList.remove('is-invalid');
                removeError(this);
            }
        });

        // Helper function to remove error
        function removeError(element) {
            const errorDiv = element.parentNode.querySelector('.invalid-feedback');
            if (errorDiv) {
                errorDiv.remove();
            }
        }

        // Toast notification function
        function showToast(message, type = 'info') {
            // Remove existing toast
            const existingToast = document.querySelector('.custom-toast');
            if (existingToast) {
                existingToast.remove();
            }

            // Create toast
            const toast = document.createElement('div');
            toast.className = `custom-toast alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show`;
            toast.innerHTML = `
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;

            // Style toast
            Object.assign(toast.style, {
                position: 'fixed',
                top: '20px',
                right: '20px',
                zIndex: '9999',
                minWidth: '300px',
                boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
                borderRadius: '10px'
            });

            document.body.appendChild(toast);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 5000);
        }

        // Trigger role description on page load if role is already selected
        if (roleSelect.value) {
            roleSelect.dispatchEvent(new Event('change'));
        }
        });
    </script>

    <style>
        /* Color Variables */
        :root {
            --primary-color: #285496;
            --primary-light: rgba(40, 84, 150, 0.1);
            --primary-gradient: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
        }

        /* Page Header */
        .page-header {
            padding: 2rem;
            margin-bottom: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(40, 84, 150, 0.15);
        }

        .icon-wrapper {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Input Groups */
        .input-group-text {
            background-color: #f8f9fa;
            border-color: #e9ecef;
            color: var(--primary-color);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(40, 84, 150, 0.25);
        }

        /* Password Toggle Button */
        #togglePassword {
            border-color: #e9ecef;
            color: var(--primary-color);
        }

        #togglePassword:hover {
            background-color: var(--primary-light);
            border-color: var(--primary-color);
        }

        /* Role Summary */
        .role-info-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 1.25rem;
        }

        .role-features ul li {
            padding: 0.25rem 0;
        }

        /* Badge Colors */
        .bg-danger {
            background-color: var(--danger-color) !important;
        }

        .bg-warning {
            background-color: var(--warning-color) !important;
        }

        .bg-info {
            background-color: var(--info-color) !important;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        /* Buttons */
        .btn-lift {
            transition: transform 0.2s ease;
        }

        .btn-lift:hover {
            transform: translateY(-2px);
        }

        .btn-outline-secondary {
            border-color: #6c757d;
            color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        /* Empty State */
        .empty-state {
            opacity: 0.6;
        }

        /* Form Validation */
        .is-invalid {
            border-color: var(--danger-color) !important;
        }

        .invalid-feedback {
            font-size: 0.85rem;
            margin-top: 0.25rem;
            color: var(--danger-color);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
            }

            .btn-group {
                width: 100%;
            }

            .btn-group .btn {
                flex: 1;
            }

            .role-info-card {
                padding: 1rem;
            }
        }

        @media (max-width: 576px) {
            .page-header {
                text-align: center;
                padding: 1.5rem 1rem;
            }

            .icon-wrapper {
                margin: 0 auto 1rem;
            }

            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .page-header p {
                font-size: 0.9rem;
            }
        }

        /* Animation for alerts */
        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .alert {
            animation: slideInDown 0.3s ease;
        }
    </style>
@endsection