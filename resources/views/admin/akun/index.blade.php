@extends('admin.partials.layout')

@section('title', 'Akun Saya - LAN Pusjar SKMP')
@section('page-title', 'Akun Saya')

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

        .account-container {
            max-width: 800px;
            margin: 0 auto;
            animation: fadeInUp 0.6s ease-out;
        }

        .account-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .account-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .account-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .account-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .account-avatar {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2.5rem;
            border: 4px solid rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 1;
        }

        .account-name {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .account-role {
            opacity: 0.9;
            font-size: 1rem;
            position: relative;
            z-index: 1;
        }

        .account-body {
            padding: 2.5rem 2rem;
        }

        .info-section {
            margin-bottom: 2rem;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .section-title i {
            color: var(--primary-color);
            background: rgba(26, 58, 108, 0.1);
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            padding: 1.25rem 1.5rem;
            background: #f8fafc;
            border-radius: 12px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary-color);
        }

        .info-item:hover {
            background: #f1f5f9;
            transform: translateX(5px);
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            margin-right: 1.25rem;
            flex-shrink: 0;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.35rem;
        }

        .info-value {
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .info-value.password {
            letter-spacing: 3px;
            font-size: 1.2rem;
        }

        .info-action {
            flex-shrink: 0;
        }

        .btn-edit-password {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-edit-password:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 58, 108, 0.3);
        }

        .btn-edit-password i {
            font-size: 0.875rem;
        }

        .modal {
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex !important;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.3s ease;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .modal-title i {
            color: var(--primary-color);
            background: rgba(26, 58, 108, 0.1);
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .modal-close {
            background: none;
            border: none;
            color: #64748b;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 36px;
            height: 36px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            color: var(--danger-color);
            background: #fee2e2;
        }

        .modal-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-label i {
            color: var(--primary-color);
            margin-right: 0.35rem;
        }

        .form-label .required {
            color: var(--danger-color);
            margin-left: 0.25rem;
        }

        .input-group {
            position: relative;
            display: flex;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 3rem 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            background: #f8fafc;
        }

        .form-control.error {
            border-color: var(--danger-color);
            background: #fef2f2;
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            transition: color 0.3s ease;
            padding: 0.5rem;
            z-index: 1;
        }

        .toggle-password:hover {
            color: var(--primary-color);
        }

        .error-message {
            color: var(--danger-color);
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .error-message i {
            font-size: 0.75rem;
        }

        .password-requirements {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem 1.25rem;
            margin-top: 1rem;
        }

        .password-requirements h6 {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .password-requirements h6 i {
            color: var(--info-color);
        }

        .password-requirements ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .password-requirements li {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .password-requirements li:last-child {
            margin-bottom: 0;
        }

        .password-requirements li i {
            color: #94a3b8;
            font-size: 0.7rem;
        }

        .modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            background: #f8fafc;
        }

        .btn {
            padding: 0.75rem 1.75rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary {
            background: white;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 58, 108, 0.3);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideDown 0.3s ease;
        }

        .alert i {
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        /* Tambahkan untuk modal-open */
        body.modal-open {
            overflow: hidden;
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

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .account-container {
                padding: 0;
            }

            .account-header {
                padding: 2rem 1.5rem;
            }

            .account-name {
                font-size: 1.5rem;
            }

            .account-body {
                padding: 2rem 1.5rem;
            }

            .info-item {
                flex-direction: column;
                align-items: flex-start;
                padding: 1.25rem;
            }

            .info-icon {
                margin-right: 0;
                margin-bottom: 1rem;
            }

            .info-action {
                width: 100%;
                margin-top: 1rem;
            }

            .btn-edit-password {
                width: 100%;
                justify-content: center;
            }

            .modal-content {
                width: 95%;
            }

            .modal-header {
                padding: 1.25rem 1.5rem;
            }

            .modal-body {
                padding: 1.5rem;
            }

            .modal-footer {
                padding: 1.25rem 1.5rem;
                flex-direction: column-reverse;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .account-avatar {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }

            .account-name {
                font-size: 1.3rem;
            }

            .section-title {
                font-size: 1.1rem;
            }

            .info-item {
                padding: 1rem;
            }

            .modal-title {
                font-size: 1.1rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="account-container">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <div class="account-card">
            <div class="account-header">
                <div class="account-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h2 class="account-name">{{ $user->name ?? 'Administrator' }}</h2>
                <p class="account-role">{{ $user->role->name ?? 'Administrator' }}</p>
            </div>

            <div class="account-body">
                <div class="info-section">
                    <div class="section-title">
                        <i class="fas fa-id-card"></i>
                        Informasi Akun
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $user->email ?? 'admin@example.com' }}</div>
                        </div>
                    </div>

                    <div class="info-item">
    <div class="info-icon">
        <i class="fas fa-phone"></i>
    </div>
    <div class="info-content">
        <div class="info-label">Nomor HP</div>
        <div class="info-value">{{ $user->no_telp ?? '-' }}</div>
    </div>
    <div class="info-action">
        <button type="button" class="btn-edit-password" onclick="openPhoneModal()">
            <i class="fas fa-edit"></i>
            Ubah Nomor HP
        </button>
    </div>
</div>


                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Password</div>
                            <div class="info-value password">••••••••••</div>
                        </div>
                        <div class="info-action">
                            <button type="button" class="btn-edit-password" onclick="openPasswordModal()">
                                <i class="fas fa-edit"></i>
                                Ubah Password
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Phone Modal -->
<div id="phoneModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-phone"></i>
                Ubah Nomor HP
            </div>
            <button type="button" class="modal-close" onclick="closePhoneModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="phoneForm" action="{{ route('admin.akun.update-phone') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-phone"></i>
                        Nomor HP
                        <span class="required">*</span>
                    </label>

                    <div class="input-group">
                        <input type="text"
                               class="form-control"
                               id="no_telp"
                               name="no_telp"
                               value="{{ old('no_telp', $user->no_telp) }}"
                               placeholder="Contoh: 08xxxxxxxxxx"
                               required>
                    </div>

                    <div class="error-message" id="no_telp_error" style="display:none;">
                        <i class="fas fa-exclamation-circle"></i>
                        <span></span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closePhoneModal()">
                    <i class="fas fa-times"></i>
                    Batal
                </button>
                <button type="submit" class="btn btn-primary" id="submitPhoneBtn">
                    <i class="fas fa-save"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>


    <!-- Password Modal -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-key"></i>
                    Ubah Password
                </div>
                <button type="button" class="modal-close" onclick="closePasswordModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="passwordForm" action="{{ route('admin.akun.update-password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock"></i>
                            Password Saat Ini
                            <span class="required">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="current_password" name="current_password"
                                placeholder="Masukkan password saat ini" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="error-message" id="current_password_error" style="display: none;">
                            <i class="fas fa-exclamation-circle"></i>
                            <span></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-key"></i>
                            Password Baru
                            <span class="required">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password" name="new_password"
                                placeholder="Masukkan password baru" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('new_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="error-message" id="new_password_error" style="display: none;">
                            <i class="fas fa-exclamation-circle"></i>
                            <span></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-check-circle"></i>
                            Konfirmasi Password Baru
                            <span class="required">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password_confirmation"
                                name="new_password_confirmation" placeholder="Masukkan ulang password baru" required>
                            <button type="button" class="toggle-password"
                                onclick="togglePassword('new_password_confirmation')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="error-message" id="new_password_confirmation_error" style="display: none;">
                            <i class="fas fa-exclamation-circle"></i>
                            <span></span>
                        </div>
                    </div>

                    <div class="password-requirements">
                        <h6>
                            <i class="fas fa-info-circle"></i>
                            Ketentuan Password:
                        </h6>
                        <ul>
                            <li><i class="fas fa-circle"></i> Minimal 8 karakter</li>
                            <li><i class="fas fa-circle"></i> Mengandung huruf besar dan kecil</li>
                            <li><i class="fas fa-circle"></i> Mengandung minimal satu angka</li>
                        </ul>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closePasswordModal()">
                        <i class="fas fa-times"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
   <script>
    // =========================
    // Modal: Phone
    // =========================
    function openPhoneModal() {
        const modal = document.getElementById('phoneModal');
        if (!modal) return;

        modal.classList.add('active');
        document.body.classList.add('modal-open');
    }

    function closePhoneModal() {
        const modal = document.getElementById('phoneModal');
        if (modal) modal.classList.remove('active');

        document.body.classList.remove('modal-open');

        const form = document.getElementById('phoneForm');
        if (form) form.reset();

        clearErrors();

        // aktifkan kembali tombol submit kalau sebelumnya disabled
        const btn = document.getElementById('submitPhoneBtn');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
        }
    }

    // =========================
    // Modal: Password
    // =========================
    function openPasswordModal() {
        const modal = document.getElementById('passwordModal');
        if (!modal) return;

        modal.classList.add('active');
        document.body.classList.add('modal-open');
    }

    function closePasswordModal() {
        const modal = document.getElementById('passwordModal');
        if (modal) modal.classList.remove('active');

        document.body.classList.remove('modal-open');

        const form = document.getElementById('passwordForm');
        if (form) form.reset();

        clearErrors();

        // aktifkan kembali tombol submit kalau sebelumnya disabled
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
        }
    }

    // =========================
    // Toggle password visibility
    // =========================
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        if (!input) return;

        const button = input.nextElementSibling;
        if (!button) return;

        const icon = button.querySelector('i');
        if (!icon) return;

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // =========================
    // Error helpers
    // =========================
    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(error => {
            error.style.display = 'none';
            const span = error.querySelector('span');
            if (span) span.textContent = '';
        });

        document.querySelectorAll('.form-control').forEach(input => {
            input.classList.remove('error');
        });
    }

    function showError(fieldId, message) {
        const input = document.getElementById(fieldId);
        const errorDiv = document.getElementById(fieldId + '_error');

        if (input) input.classList.add('error');

        if (errorDiv) {
            errorDiv.style.display = 'flex';
            const span = errorDiv.querySelector('span');
            if (span) span.textContent = message;
        }
    }

    // =========================
    // DOM Ready
    // =========================
    document.addEventListener('DOMContentLoaded', function () {

        // -------------------------------------------------
        // Klik luar modal untuk menutup (Phone & Password)
        // -------------------------------------------------
        const phoneModal = document.getElementById('phoneModal');
        if (phoneModal) {
            phoneModal.addEventListener('click', function (e) {
                if (e.target === this) closePhoneModal();
            });
        }

        const passwordModal = document.getElementById('passwordModal');
        if (passwordModal) {
            passwordModal.addEventListener('click', function (e) {
                if (e.target === this) closePasswordModal();
            });
        }

        // -------------------------------------------------
        // Tombol ESC: tutup modal yang sedang aktif
        // -------------------------------------------------
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;

            if (phoneModal && phoneModal.classList.contains('active')) {
                closePhoneModal();
            }

            if (passwordModal && passwordModal.classList.contains('active')) {
                closePasswordModal();
            }
        });

        // -------------------------------------------------
        // Auto-hide alerts setelah 5 detik
        // -------------------------------------------------
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.style.animation = 'fadeIn 0.3s ease reverse';
                setTimeout(() => {
                    if (alert.parentNode) alert.remove();
                }, 300);
            }, 5000);
        });

        // -------------------------------------------------
        // Submit Phone Form
        // -------------------------------------------------
        const phoneForm = document.getElementById('phoneForm');
        if (phoneForm) {
            phoneForm.addEventListener('submit', function (e) {
                e.preventDefault();
                clearErrors();

                const noTelpInput = document.getElementById('no_telp');
                const noTelp = noTelpInput ? noTelpInput.value.trim() : '';

                let isValid = true;

                if (!noTelp) {
                    showError('no_telp', 'Nomor HP harus diisi');
                    isValid = false;
                } else if (!/^[0-9+\s()-]{9,20}$/.test(noTelp)) {
                    showError('no_telp', 'Format nomor HP tidak valid');
                    isValid = false;
                }

                if (isValid) {
                    const btn = document.getElementById('submitPhoneBtn');
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                    }
                    this.submit();
                }
            });
        }

        // -------------------------------------------------
        // Submit Password Form
        // -------------------------------------------------
        const passwordForm = document.getElementById('passwordForm');
        if (passwordForm) {
            passwordForm.addEventListener('submit', function (e) {
                e.preventDefault();
                clearErrors();

                const currentPassword = document.getElementById('current_password')?.value || '';
                const newPassword = document.getElementById('new_password')?.value || '';
                const confirmPassword = document.getElementById('new_password_confirmation')?.value || '';

                let isValid = true;

                if (!currentPassword) {
                    showError('current_password', 'Password saat ini harus diisi');
                    isValid = false;
                }

                if (!newPassword) {
                    showError('new_password', 'Password baru harus diisi');
                    isValid = false;
                } else if (newPassword.length < 8) {
                    showError('new_password', 'Password minimal 8 karakter');
                    isValid = false;
                } else if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(newPassword)) {
                    showError('new_password', 'Password harus mengandung huruf besar, kecil, dan angka');
                    isValid = false;
                }

                if (!confirmPassword) {
                    showError('new_password_confirmation', 'Konfirmasi password harus diisi');
                    isValid = false;
                } else if (newPassword !== confirmPassword) {
                    showError('new_password_confirmation', 'Password tidak cocok');
                    isValid = false;
                }

                if (isValid && currentPassword === newPassword) {
                    showError('new_password', 'Password baru tidak boleh sama dengan password lama');
                    isValid = false;
                }

                if (isValid) {
                    const submitBtn = document.getElementById('submitBtn');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                    }
                    this.submit();
                }
            });
        }
    });
</script>
@endsection