@extends('admin.partials.layout')

@section('title', ($kontak ? 'Edit' : 'Tambah') . ' Kontak - SIMPEL')

@section('content')
    <!-- Page Header with Animation -->
    <div class="page-header-modern bg-gradient-primary rounded-4 mb-4 overflow-hidden position-relative">
        <div class="header-pattern"></div>
        <div class="row align-items-center position-relative" style="z-index: 2;">
            <div class="col">
                <div class="d-flex align-items-center p-4">
                    <div class="icon-wrapper-modern bg-white rounded-circle p-3 me-4 shadow-lg animate-float">
                        <i class="fas fa-{{ $kontak ? 'edit' : 'plus-circle' }} fa-2x" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-2 fw-bold animate-slide-in">
                            {{ $kontak ? 'Edit' : 'Tambah' }} Kontak
                        </h1>
                        <p class="text-white-50 mb-0 animate-slide-in-delay">
                            {{ $kontak ? 'Perbarui' : 'Tambahkan' }} informasi kontak perusahaan
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mb-4 animate-fade-in">
        <a href="{{ route('kontak.index') }}" class="btn btn-outline-secondary btn-modern">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-12">
            <div class="card-modern border-0 shadow-lg animate-fade-in-delay">
                <div class="card-glow"></div>
                <div class="card-header-modern bg-gradient-light py-4 border-0">
                    <h5 class="card-title mb-0 fw-bold d-flex align-items-center">
                        <div class="icon-badge bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                            <i class="fas fa-address-card fa-lg" style="color: #285496;"></i>
                        </div>
                        <span>Formulir Kontak</span>
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('kontak.store') }}" method="POST" id="kontakForm">
                        @csrf

                        <!-- Primary Contact Information -->
                        <div class="form-section mb-5">
                            <div class="section-header mb-4">
                                <h6 class="section-title">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Informasi Kontak Utama
                                </h6>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row g-4">
                                <!-- Alamat -->
                                <div class="col-12">
                                    <div class="form-group-modern">
                                        <label for="alamat" class="form-label-modern">
                                            <i class="fas fa-map-marker-alt me-2"></i>
                                            Alamat <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-wrapper">
                                            <textarea class="form-control-modern @error('alamat') is-invalid @enderror"
                                                id="alamat" name="alamat" rows="3"
                                                placeholder="Masukkan alamat lengkap perusahaan"
                                                required>{{ old('alamat', $kontak->alamat ?? '') }}</textarea>
                                            @error('alamat')
                                                <div class="invalid-feedback-modern">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <div class="input-glow"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nomor HP -->
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="nomor_hp" class="form-label-modern">
                                            <i class="fas fa-phone-alt me-2"></i>
                                            Nomor Telepon <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-wrapper">
                                            <input type="text"
                                                class="form-control-modern @error('nomor_hp') is-invalid @enderror"
                                                id="nomor_hp" name="nomor_hp" placeholder="Contoh: +62 812-3456-7890"
                                                value="{{ old('nomor_hp', $kontak->nomor_hp ?? '') }}" required>
                                            @error('nomor_hp')
                                                <div class="invalid-feedback-modern">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <div class="input-glow"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="email" class="form-label-modern">
                                            <i class="fas fa-envelope me-2"></i>
                                            Email <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-wrapper">
                                            <input type="email"
                                                class="form-control-modern @error('email') is-invalid @enderror" id="email"
                                                name="email" placeholder="contoh@perusahaan.com"
                                                value="{{ old('email', $kontak->email ?? '') }}" required>
                                            @error('email')
                                                <div class="invalid-feedback-modern">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <div class="input-glow"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media Information -->
                        <div class="form-section mb-5">
                            <div class="section-header mb-4">
                                <h6 class="section-title">
                                    <i class="fas fa-share-alt me-2"></i>
                                    Media Sosial (Opsional)
                                </h6>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row g-4">
                                <!-- Facebook -->
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="fb" class="form-label-modern">
                                            <i class="fab fa-facebook-f me-2"></i>
                                            Facebook
                                        </label>
                                        <div class="input-wrapper social-input facebook-input">
                                            <div class="social-icon-input">
                                                <i class="fab fa-facebook-f"></i>
                                            </div>
                                            <input type="url"
                                                class="form-control-modern with-icon @error('fb') is-invalid @enderror"
                                                id="fb" name="fb" placeholder="https://facebook.com/username"
                                                value="{{ old('fb', $kontak->fb ?? '') }}">
                                            @error('fb')
                                                <div class="invalid-feedback-modern">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <div class="input-glow"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Instagram -->
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="ig" class="form-label-modern">
                                            <i class="fab fa-instagram me-2"></i>
                                            Instagram
                                        </label>
                                        <div class="input-wrapper social-input instagram-input">
                                            <div class="social-icon-input">
                                                <i class="fab fa-instagram"></i>
                                            </div>
                                            <input type="url"
                                                class="form-control-modern with-icon @error('ig') is-invalid @enderror"
                                                id="ig" name="ig" placeholder="https://instagram.com/username"
                                                value="{{ old('ig', $kontak->ig ?? '') }}">
                                            @error('ig')
                                                <div class="invalid-feedback-modern">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <div class="input-glow"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Twitter -->
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="twitter" class="form-label-modern">
                                            <i class="fab fa-twitter me-2"></i>
                                            Twitter
                                        </label>
                                        <div class="input-wrapper social-input twitter-input">
                                            <div class="social-icon-input">
                                                <i class="fab fa-twitter"></i>
                                            </div>
                                            <input type="url"
                                                class="form-control-modern with-icon @error('twitter') is-invalid @enderror"
                                                id="twitter" name="twitter" placeholder="https://twitter.com/username"
                                                value="{{ old('twitter', $kontak->twitter ?? '') }}">
                                            @error('twitter')
                                                <div class="invalid-feedback-modern">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <div class="input-glow"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- LinkedIn -->
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="linkedin" class="form-label-modern">
                                            <i class="fab fa-linkedin-in me-2"></i>
                                            LinkedIn
                                        </label>
                                        <div class="input-wrapper social-input linkedin-input">
                                            <div class="social-icon-input">
                                                <i class="fab fa-linkedin-in"></i>
                                            </div>
                                            <input type="url"
                                                class="form-control-modern with-icon @error('linkedin') is-invalid @enderror"
                                                id="linkedin" name="linkedin"
                                                placeholder="https://linkedin.com/company/username"
                                                value="{{ old('linkedin', $kontak->linkedin ?? '') }}">
                                            @error('linkedin')
                                                <div class="invalid-feedback-modern">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <div class="input-glow"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <div class="d-flex gap-3 justify-content-end">
                                <a href="{{ route('kontak.index') }}" class="btn btn-outline-secondary btn-modern px-4">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary btn-modern px-5">
                                    <i class="fas fa-save me-2"></i>
                                    {{ $kontak ? 'Perbarui' : 'Simpan' }} Kontak
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('kontakForm');
            const inputs = form.querySelectorAll('.form-control-modern');

            // Add focus/blur effects
            inputs.forEach(input => {
                input.addEventListener('focus', function () {
                    this.closest('.input-wrapper').classList.add('focused');
                });

                input.addEventListener('blur', function () {
                    this.closest('.input-wrapper').classList.remove('focused');
                    if (this.value) {
                        this.closest('.input-wrapper').classList.add('filled');
                    } else {
                        this.closest('.input-wrapper').classList.remove('filled');
                    }
                });

                // Check if already filled on load
                if (input.value) {
                    input.closest('.input-wrapper').classList.add('filled');
                }
            });

            // Form validation with custom styling
            form.addEventListener('submit', function (e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Add shake animation to invalid inputs
                    const invalidInputs = form.querySelectorAll(':invalid');
                    invalidInputs.forEach(input => {
                        input.closest('.input-wrapper').classList.add('shake');
                        setTimeout(() => {
                            input.closest('.input-wrapper').classList.remove('shake');
                        }, 500);
                    });
                }
                form.classList.add('was-validated');
            });

            // Auto-format phone number
            const phoneInput = document.getElementById('nomor_hp');
            phoneInput.addEventListener('input', function (e) {
                // Allow only numbers, +, -, and spaces
                let value = e.target.value.replace(/[^\d+\-\s]/g, '');
                e.target.value = value;
            });

            // Preview social media URLs
            const socialInputs = ['fb', 'ig', 'twitter', 'linkedin'];
            socialInputs.forEach(id => {
                const input = document.getElementById(id);
                input.addEventListener('input', function () {
                    if (this.value && !this.value.startsWith('http')) {
                        this.setCustomValidity('URL harus dimulai dengan http:// atau https://');
                    } else {
                        this.setCustomValidity('');
                    }
                });
            });
        });
    </script>

    <style>
        /* Modern Color Variables */
        :root {
            --primary-color: #285496;
            --primary-dark: #1e3f70;
            --primary-light: #3a6bc7;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-bg: #f8f9fa;
            --border-radius: 16px;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.16);
            --facebook: #1877f2;
            --instagram: #e4405f;
            --twitter: #1da1f2;
            --linkedin: #0077b5;
        }

        /* Page Header Modern */
        .page-header-modern {
            background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);
            position: relative;
            overflow: hidden;
        }

        .header-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            opacity: 0.5;
        }

        .icon-wrapper-modern {
            animation: float 3s ease-in-out infinite;
        }

        /* Animations */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

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

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-10px);
            }

            75% {
                transform: translateX(10px);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.6s ease-out;
        }

        .animate-slide-in-delay {
            animation: slideIn 0.6s ease-out 0.2s backwards;
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        .animate-fade-in-delay {
            animation: fadeIn 0.6s ease-out 0.3s backwards;
        }

        .shake {
            animation: shake 0.5s ease-in-out;
        }

        /* Modern Card Styles */
        .card-modern {
            border-radius: var(--border-radius);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: white !important;
        }

        .card-glow {
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(40, 84, 150, 0.05) 0%, transparent 70%);
            opacity: 1;
        }

        .card-header-modern {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .icon-badge {
            transition: all 0.3s ease;
        }

        /* Form Section */
        .form-section {
            position: relative;
        }

        .section-header {
            position: relative;
        }

        .section-title {
            font-weight: 700;
            color: #2c3e50;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .section-divider {
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), transparent);
            border-radius: 2px;
        }

        /* Form Group Modern */
        .form-group-modern {
            margin-bottom: 1.5rem;
        }

        .form-label-modern {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
            display: block;
        }

        .input-wrapper {
            position: relative;
            transition: all 0.3s ease;
        }

        .input-glow {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
            transition: width 0.3s ease;
        }

        .input-wrapper.focused .input-glow {
            width: 100%;
        }

        .form-control-modern {
            width: 100%;
            padding: 0.875rem 1.125rem;
            font-size: 0.95rem;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: white !important;
            color: #2c3e50;
        }

        .form-control-modern:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 4px rgba(40, 84, 150, 0.1);
            background: white !important;
        }

        .form-control-modern::placeholder {
            color: #adb5bd;
        }

        .form-control-modern.is-invalid {
            border-color: var(--danger-color);
        }

        .form-control-modern.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1);
        }

        .invalid-feedback-modern {
            display: block;
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        /* Social Input Styling */
        .social-input {
            position: relative;
        }

        .social-icon-input {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            z-index: 1;
        }

        .form-control-modern.with-icon {
            padding-left: 4rem;
        }

        /* Facebook Input */
        .facebook-input .social-icon-input {
            background: rgba(24, 119, 242, 0.1);
            color: var(--facebook);
        }

        .facebook-input.focused .social-icon-input {
            background: var(--facebook);
            color: white;
        }

        /* Instagram Input */
        .instagram-input .social-icon-input {
            background: rgba(228, 64, 95, 0.1);
            color: var(--instagram);
        }

        .instagram-input.focused .social-icon-input {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            color: white;
        }

        /* Twitter Input */
        .twitter-input .social-icon-input {
            background: rgba(29, 161, 242, 0.1);
            color: var(--twitter);
        }

        .twitter-input.focused .social-icon-input {
            background: var(--twitter);
            color: white;
        }

        /* LinkedIn Input */
        .linkedin-input .social-icon-input {
            background: rgba(0, 119, 181, 0.1);
            color: var(--linkedin);
        }

        .linkedin-input.focused .social-icon-input {
            background: var(--linkedin);
            color: white;
        }

        /* Form Actions */
        .form-actions {
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 2px solid #e9ecef;
        }

        /* Modern Buttons */
        .btn-modern {
            border-radius: 10px;
            padding: 0.625rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-modern:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-primary.btn-modern {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            border: none;
        }

        .btn-outline-secondary.btn-modern {
            border: 2px solid #6c757d;
            color: #6c757d;
        }

        .btn-outline-secondary.btn-modern:hover {
            background: #6c757d;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-actions .d-flex {
                flex-direction: column;
            }

            .form-actions .btn-modern {
                width: 100%;
            }

            .section-title {
                font-size: 1rem;
            }

            .form-control-modern {
                font-size: 0.9rem;
                padding: 0.75rem 1rem;
            }

            .form-control-modern.with-icon {
                padding-left: 3.5rem;
            }

            .social-icon-input {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .page-header-modern {
                border-radius: 12px;
            }

            .card-modern {
                border-radius: 12px;
            }

            .btn-modern {
                font-size: 0.8rem;
                padding: 0.5rem 1.25rem;
            }
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Focus styles for accessibility */
        .btn-modern:focus {
            outline: 3px solid rgba(40, 84, 150, 0.4);
            outline-offset: 2px;
        }

        .form-control-modern:focus {
            outline: none;
        }
    </style>
@endsection