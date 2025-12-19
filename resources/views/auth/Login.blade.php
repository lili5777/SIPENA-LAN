<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem LAN Pusjar SKMP</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1a3a6c;
            --secondary-color: #2c5aa0;
            --accent-color: #e63946;
            --gold-color: #d4af37;
            --dark-color: #0d1b2a;
            --light-color: #f8f9fa;
            --success-color: #2a9d8f;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #e2e4e8 0%, #305eb9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            padding: 20px;
        }

        .login-container {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(13, 27, 42, 0.15);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
            min-height: 580px;
            display: flex;
            animation: fadeIn 0.8s ease-out;
        }

        .login-form {
            padding: 45px 50px;
            flex: 1.2;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            z-index: 1;
            background-color: white;
        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .lan-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            box-shadow: 0 4px 12px rgba(26, 58, 108, 0.2);
        }

        .login-header h2 {
            color: var(--dark-color);
            font-weight: 700;
            margin-bottom: 8px;
            font-size: 1.9rem;
            letter-spacing: -0.5px;
        }

        .login-header h3 {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .login-header p {
            color: #5a6c7d;
            font-size: 14px;
            line-height: 1.5;
            max-width: 400px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            color: var(--dark-color);
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 6px;
            display: block;
        }

        .form-control {
            height: 48px;
            border-radius: 10px;
            padding-left: 50px;
            border: 1.5px solid #d1d9e6;
            transition: all 0.3s;
            font-size: 14px;
            background-color: #f8fafc;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.15);
            background-color: white;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 65%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 16px;
            transition: all 0.3s;
        }

        .form-control:focus+.input-icon {
            color: var(--secondary-color);
        }

        .btn-login {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
            height: 48px;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s;
            margin-top: 15px;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(26, 58, 108, 0.3);
        }

        .login-footer {
            text-align: center;
            margin-top: 25px;
            font-size: 13px;
            color: #6c757d;
        }

        .login-footer a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .login-image {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 35px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .login-image::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(255,255,255,0.08)"/></svg>');
            background-size: cover;
            animation: float 20s infinite linear;
        }

        .institution-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: pulse 3s infinite;
            color: var(--gold-color);
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
        }

        .image-content {
            text-align: center;
            z-index: 1;
            position: relative;
            max-width: 400px;
        }

        .image-content h3 {
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 1.6rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .image-content p {
            font-size: 15px;
            line-height: 1.6;
            opacity: 0.9;
            margin-bottom: 25px;
        }

        .floating-icons {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }

        .floating-icon {
            position: absolute;
            font-size: 18px;
            opacity: 0.2;
            color: white;
            animation: floatAround 25s infinite linear;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes float {
            0% {
                transform: translate(0, 0) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        @keyframes floatAround {
            0% {
                transform: translate(0, 0) rotate(0deg);
            }

            25% {
                transform: translate(100px, 80px) rotate(90deg);
            }

            50% {
                transform: translate(50px, 150px) rotate(180deg);
            }

            75% {
                transform: translate(-30px, 80px) rotate(270deg);
            }

            100% {
                transform: translate(0, 0) rotate(360deg);
            }
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .login-container {
                max-width: 95%;
                min-height: auto;
            }

            .login-form {
                padding: 35px 30px;
            }

            .login-image {
                padding: 25px;
            }

            .institution-icon {
                font-size: 70px;
            }

            .image-content h3 {
                font-size: 1.4rem;
            }
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 100%;
                border-radius: 12px;
            }

            .login-image {
                order: -1;
                padding: 25px 20px;
                min-height: 280px;
            }

            .login-form {
                padding: 30px 25px;
            }

            /* Sembunyikan logo, judul, dan subjudul di mobile */
            .lan-logo,
            .login-header h2,
            .login-header h3 {
                display: none;
            }

            /* Teks lebih kecil untuk mobile */
            .form-label {
                font-size: 12px !important;
            }

            .form-control {
                font-size: 13px !important;
                height: 44px;
                padding-left: 40px;
            }

            .input-icon {
                font-size: 14px;
                left: 12px;
            }

            .btn-login {
                font-size: 13px !important;
                height: 44px;
            }

            .alert {
                font-size: 12px !important;
                padding: 10px 12px;
            }

            .login-footer {
                font-size: 11px !important;
            }

            .login-footer p {
                margin-bottom: 5px;
            }

            .login-header p {
                font-size: 11px !important;
            }

            .institution-icon {
                font-size: 60px;
                margin-bottom: 15px;
            }

            .image-content h3 {
                font-size: 1.2rem;
            }

            .image-content p {
                font-size: 13px;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 15px;
            }

            .login-container {
                border-radius: 10px;
            }

            .login-form {
                padding: 25px 20px;
            }

            .login-header {
                margin-bottom: 20px;
            }

            /* Pastikan elemen tetap tersembunyi di mobile kecil */
            .lan-logo,
            .login-header h2,
            .login-header h3 {
                display: none;
            }

            /* Teks lebih kecil untuk mobile kecil */
            .form-label {
                font-size: 11px !important;
            }

            .form-control {
                font-size: 12px !important;
                height: 42px;
                padding-left: 38px;
            }

            .input-icon {
                font-size: 13px;
            }

            .btn-login {
                font-size: 12px !important;
                height: 42px;
            }

            .alert {
                font-size: 11px !important;
                padding: 8px 10px;
            }

            .login-footer {
                font-size: 10px !important;
                margin-top: 15px;
            }

            .login-header p {
                font-size: 10px !important;
            }

            .login-image {
                min-height: 200px;
                padding: 20px 15px;
            }

            .institution-icon {
                font-size: 50px;
                margin-bottom: 10px;
            }

            .image-content h3 {
                font-size: 1.1rem;
                margin-bottom: 8px;
            }

            .image-content p {
                font-size: 12px;
                margin-bottom: 15px;
            }
        }

        @media (max-width: 380px) {
            .login-form {
                padding: 20px 15px;
            }

            /* Pastikan elemen tetap tersembunyi di mobile sangat kecil */
            .lan-logo,
            .login-header h2,
            .login-header h3 {
                display: none;
            }

            /* Teks lebih kecil untuk mobile sangat kecil */
            .form-label {
                font-size: 10px !important;
            }

            .form-control {
                font-size: 11px !important;
                height: 40px;
                padding-left: 36px;
            }

            .input-icon {
                font-size: 12px;
            }

            .btn-login {
                font-size: 11px !important;
                height: 40px;
            }

            .alert {
                font-size: 10px !important;
                padding: 6px 8px;
            }

            .login-footer {
                font-size: 9px !important;
            }

            .login-header p {
                font-size: 9px !important;
            }

            .form-group {
                margin-bottom: 14px;
            }

            .login-image {
                min-height: 180px;
                padding: 15px 10px;
            }

            .institution-icon {
                font-size: 45px;
            }

            .image-content h3 {
                font-size: 1rem;
            }

            .image-content p {
                font-size: 11px;
            }
        }

        /* Custom checkbox */
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Error animation */
        .error-shake {
            animation: shake 0.5s;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-5px);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translateX(5px);
            }
        }

        /* Loading animation */
        .btn-loading {
            position: relative;
            color: transparent;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-top: -10px;
            margin-left: -10px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Alert styles */
        .alert {
            border-radius: 10px;
            border: none;
            padding: 12px 15px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid var(--success-color);
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid var(--accent-color);
        }

        /* Backend error message */
        .backend-error {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
            display: none;
        }

        /* Style khusus untuk alert backend agar tidak disembunyikan */
        .alert-backend {
            display: block !important;
        }

        /* Additional styling for better mobile touch targets */
        @media (max-width: 768px) {

            .form-control,
            .btn-login {
                /* Prevents zoom on iOS */
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-form">
            <div class="login-header">
                <div class="lan-logo">
                    <i class="fas fa-landmark"></i>
                </div>
                <h2>LAN - Pusjar SKMP</h2>
                <h3>Sistem Manajemen Pembelajaran</h3>
            </div>

            <!-- Alert untuk pesan sukses/error dari frontend -->
            <div class="alert alert-success" id="successAlert" style="display: none;">
                <i class="fas fa-check-circle me-2"></i> Login berhasil! Mengarahkan ke dashboard...
            </div>

            <div class="alert alert-danger" id="errorAlert" style="display: none;">
                <i class="fas fa-exclamation-circle me-2"></i> <span id="errorMessage"></span>
            </div>

            <!-- Tampilkan error dari backend jika ada -->
            @if(session('error'))
                <div class="alert alert-danger alert-backend">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                </div>
            @endif

            <form id="loginForm" action="{{ route('login.submit') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" class="form-control" id="email" name="email" placeholder="nama@lan.go.id"
                        value="{{ old('email') }}" required>
                    <div class="backend-error" id="emailError"></div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Kata Sandi</label>
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Masukkan kata sandi Anda" required>
                    <div class="backend-error" id="passwordError"></div>
                </div>

                <button type="submit" class="btn btn-login w-100" id="loginBtn">
                    <span id="btnText">Masuk</span>
                </button>

                <div class="login-footer">
                    <p>Butuh bantuan? <a href="#">Hubungi Administrator Sistem</a></p>
                    <p class="mt-2">© 2023 LAN (Pusjar SKMP) - Hak Cipta Dilindungi</p>
                </div>
            </form>
        </div>

        <div class="login-image">
            <div class="floating-icons">
                <i class="fas fa-book floating-icon" style="top: 15%; left: 15%; animation-delay: 0s;"></i>
                <i class="fas fa-graduation-cap floating-icon" style="top: 25%; left: 85%; animation-delay: 3s;"></i>
                <i class="fas fa-chart-line floating-icon" style="top: 75%; left: 15%; animation-delay: 6s;"></i>
                <i class="fas fa-users floating-icon" style="top: 65%; left: 80%; animation-delay: 9s;"></i>
                <i class="fas fa-balance-scale floating-icon" style="top: 85%; left: 45%; animation-delay: 12s;"></i>
            </div>

            <div class="image-content">
                <i class="fas fa-university institution-icon"></i>
                <h3>Sistem Manajemen Pembelajaran LAN</h3>
                <p>Platform terintegrasi untuk pengelolaan pembelajaran, kebijakan, dan strategi manajemen pemerintahan.
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const btnText = document.getElementById('btnText');
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            const errorMessage = document.getElementById('errorMessage');

            // Reset loading state jika halaman di-refresh dengan error
            loginBtn.disabled = false;
            btnText.textContent = 'Masuk';
            loginBtn.classList.remove('btn-loading');

            loginForm.addEventListener('submit', function (e) {
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;

                // Sembunyikan alert frontend sebelumnya
                successAlert.style.display = 'none';
                errorAlert.style.display = 'none';

                // Validasi email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    errorMessage.textContent = 'Format email institusi tidak valid!';
                    errorAlert.style.display = 'block';
                    document.getElementById('email').classList.add('error-shake');
                    setTimeout(() => {
                        document.getElementById('email').classList.remove('error-shake');
                    }, 500);
                    return;
                }

                // Validasi password
                if (password.length < 3) {
                    e.preventDefault();
                    errorMessage.textContent = 'Password harus minimal 3 karakter!';
                    errorAlert.style.display = 'block';
                    document.getElementById('password').classList.add('error-shake');
                    setTimeout(() => {
                        document.getElementById('password').classList.remove('error-shake');
                    }, 500);
                    return;
                }

                // Jika validasi frontend lolos, biarkan form submit ke backend
                // Tampilkan loading state
                loginBtn.disabled = true;
                btnText.textContent = 'Memproses...';
                loginBtn.classList.add('btn-loading');
            });

            // Validasi real-time untuk email
            document.getElementById('email').addEventListener('blur', function () {
                const email = this.value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (email && !emailRegex.test(email)) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });

            // Validasi real-time untuk password
            document.getElementById('password').addEventListener('input', function () {
                if (this.value.length > 0 && this.value.length < 3) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });

            // Auto-hide alerts setelah beberapa detik - HANYA untuk alert frontend
            const frontendAlerts = document.querySelectorAll('#successAlert, #errorAlert');
            frontendAlerts.forEach(alert => {
                if (alert.style.display !== 'none') {
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 5000);
                }
            });

            // Touch optimization for mobile
            if ('ontouchstart' in window) {
                document.querySelectorAll('.form-control, .btn-login').forEach(element => {
                    element.style.minHeight = '44px';
                });
            }
        });
    </script>
</body>

</html>