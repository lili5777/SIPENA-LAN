@extends('admin.partials.layout')

@section('title', 'Kontak - SIMPEL')

@section('content')
    <!-- Page Header with Animation -->
    <div class="page-header-modern bg-gradient-primary rounded-4 mb-4 overflow-hidden position-relative">
        <div class="header-pattern"></div>
        <div class="row align-items-center position-relative" style="z-index: 2;">
            <div class="col">
                <div class="d-flex align-items-center p-4">
                    <div class="icon-wrapper-modern bg-white rounded-circle p-3 me-4 shadow-lg animate-float">
                        <i class="fas fa-address-book fa-2x" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-2 fw-bold animate-slide-in">Informasi Kontak</h1>
                        <p class="text-white-50 mb-0 animate-slide-in-delay">Kelola informasi kontak perusahaan Anda</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Section with Animation -->
    <div class="alert-container mb-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-lg animate-slide-down" role="alert">
                <div class="d-flex align-items-center">
                    <div class="alert-icon-modern flex-shrink-0">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="alert-heading mb-1">Sukses!</h6>
                        <p class="mb-0">{{ session('success') }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-lg animate-slide-down" role="alert">
                <div class="d-flex align-items-center">
                    <div class="alert-icon-modern flex-shrink-0">
                        <i class="fas fa-exclamation-circle fa-2x"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="alert-heading mb-1">Error!</h6>
                        <p class="mb-0">{{ session('error') }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    @if($kontak)
        <!-- Contact Information Card -->
        <div class="row g-4">
            <div class="col-12">
                <div class="card-modern border-0 shadow-lg animate-fade-in">
                    <div class="card-glow"></div>
                    <div class="card-header-modern bg-gradient-light py-4 border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fw-bold d-flex align-items-center">
                                <div class="icon-badge bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                    <i class="fas fa-info-circle fa-lg" style="color: #285496;"></i>
                                </div>
                                <span>Informasi Kontak Perusahaan</span>
                            </h5>
                            <a href="{{ route('kontak.form') }}" class="btn btn-warning btn-modern shadow-sm">
                                <i class="fas fa-edit me-2"></i> Edit Kontak
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Primary Contact Info -->
                            <div class="col-lg-6">
                                <div class="contact-group animate-fade-in" style="animation-delay: 0.1s">
                                    <div class="contact-item-modern">
                                        <div class="contact-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="contact-content">
                                            <label class="contact-label">Alamat</label>
                                            <p class="contact-value">{{ $kontak->alamat }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="contact-group animate-fade-in" style="animation-delay: 0.2s">
                                    <div class="contact-item-modern">
                                        <div class="contact-icon">
                                            <i class="fas fa-phone-alt"></i>
                                        </div>
                                        <div class="contact-content">
                                            <label class="contact-label">Nomor Telepon</label>
                                            <p class="contact-value">
                                                <a href="tel:{{ $kontak->nomor_hp }}" class="contact-link">
                                                    {{ $kontak->nomor_hp }}
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="contact-group animate-fade-in" style="animation-delay: 0.3s">
                                    <div class="contact-item-modern">
                                        <div class="contact-icon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="contact-content">
                                            <label class="contact-label">Email</label>
                                            <p class="contact-value">
                                                <a href="mailto:{{ $kontak->email }}" class="contact-link">
                                                    {{ $kontak->email }}
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Media -->
                            <div class="col-12">
                                <div class="divider-modern my-4"></div>
                                <h6 class="social-title mb-4">
                                    <i class="fas fa-share-alt me-2"></i> Media Sosial
                                </h6>
                            </div>

                            @if($kontak->fb || $kontak->ig || $kontak->twitter || $kontak->linkedin)
                                <div class="col-12">
                                    <div class="social-links-container">
                                        @if($kontak->fb)
                                            <a href="{{ $kontak->fb }}" target="_blank"
                                                class="social-link-modern facebook animate-fade-in" style="animation-delay: 0.4s">
                                                <div class="social-icon">
                                                    <i class="fab fa-facebook-f"></i>
                                                </div>
                                                <div class="social-info">
                                                    <span class="social-name">Facebook</span>
                                                    <span class="social-url">{{ Str::limit($kontak->fb, 40) }}</span>
                                                </div>
                                                <i class="fas fa-external-link-alt social-arrow"></i>
                                            </a>
                                        @endif

                                        @if($kontak->ig)
                                            <a href="{{ $kontak->ig }}" target="_blank"
                                                class="social-link-modern instagram animate-fade-in" style="animation-delay: 0.5s">
                                                <div class="social-icon">
                                                    <i class="fab fa-instagram"></i>
                                                </div>
                                                <div class="social-info">
                                                    <span class="social-name">Instagram</span>
                                                    <span class="social-url">{{ Str::limit($kontak->ig, 40) }}</span>
                                                </div>
                                                <i class="fas fa-external-link-alt social-arrow"></i>
                                            </a>
                                        @endif

                                        @if($kontak->twitter)
                                            <a href="{{ $kontak->twitter }}" target="_blank"
                                                class="social-link-modern twitter animate-fade-in" style="animation-delay: 0.6s">
                                                <div class="social-icon">
                                                    <i class="fab fa-twitter"></i>
                                                </div>
                                                <div class="social-info">
                                                    <span class="social-name">Twitter</span>
                                                    <span class="social-url">{{ Str::limit($kontak->twitter, 40) }}</span>
                                                </div>
                                                <i class="fas fa-external-link-alt social-arrow"></i>
                                            </a>
                                        @endif

                                        @if($kontak->linkedin)
                                            <a href="{{ $kontak->linkedin }}" target="_blank"
                                                class="social-link-modern linkedin animate-fade-in" style="animation-delay: 0.7s">
                                                <div class="social-icon">
                                                    <i class="fab fa-linkedin-in"></i>
                                                </div>
                                                <div class="social-info">
                                                    <span class="social-name">LinkedIn</span>
                                                    <span class="social-url">{{ Str::limit($kontak->linkedin, 40) }}</span>
                                                </div>
                                                <i class="fas fa-external-link-alt social-arrow"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="col-12">
                                    <div class="empty-social-state text-center py-4">
                                        <i class="fas fa-share-alt fa-3x text-muted opacity-50 mb-3"></i>
                                        <p class="text-muted mb-0">Belum ada informasi media sosial</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="row">
            <div class="col-12">
                <div class="card-modern border-0 shadow-lg animate-fade-in">
                    <div class="card-body p-5">
                        <div class="empty-state-modern text-center py-5">
                            <div class="empty-icon-wrapper mb-4">
                                <i class="fas fa-address-book fa-4x text-muted opacity-50"></i>
                                <div class="empty-icon-circle"></div>
                            </div>
                            <h5 class="text-dark fw-bold mb-2">Belum ada informasi kontak</h5>
                            <p class="text-muted mb-4">Tambahkan informasi kontak perusahaan Anda sekarang</p>
                            <a href="{{ route('kontak.form') }}" class="btn btn-primary btn-modern btn-lg shadow-sm">
                                <i class="fas fa-plus me-2"></i> Tambah Kontak Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-hide alerts with fade effect
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.classList.contains('show')) {
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-20px)';
                        setTimeout(() => {
                            bootstrap.Alert.getOrCreateInstance(alert).close();
                        }, 300);
                    }
                }, 5000);
            });

            // Add hover effect to contact items
            const contactItems = document.querySelectorAll('.contact-item-modern');
            contactItems.forEach(item => {
                item.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateX(5px)';
                });
                item.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateX(0)';
                });
            });

            // Add hover effect to social links
            const socialLinks = document.querySelectorAll('.social-link-modern');
            socialLinks.forEach(link => {
                link.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateY(-3px)';
                });
                link.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Scroll animation observer
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.contact-group').forEach(item => {
                observer.observe(item);
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

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
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

        @keyframes pulse {

            0%,
            100% {
                opacity: 0.4;
                transform: scale(1);
            }

            50% {
                opacity: 0.8;
                transform: scale(1.1);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.6s ease-out;
        }

        .animate-slide-in-delay {
            animation: slideIn 0.6s ease-out 0.2s backwards;
        }

        .animate-slide-down {
            animation: slideDown 0.4s ease-out;
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
        }

        .animate-fade-in-delay {
            animation: fadeIn 0.6s ease-out 0.3s backwards;
        }

        /* Modern Card Styles */
        .card-modern {
            border-radius: var(--border-radius);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: white !important;
        }

        .card-modern:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(40, 84, 150, 0.2) !important;
        }

        .card-glow {
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(40, 84, 150, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .card-modern:hover .card-glow {
            opacity: 1;
        }

        .card-header-modern {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .icon-badge {
            transition: all 0.3s ease;
        }

        .card-modern:hover .icon-badge {
            transform: scale(1.1);
            background: var(--primary-color) !important;
        }

        .card-modern:hover .icon-badge i {
            color: white !important;
        }

        /* Contact Item Modern */
        .contact-item-modern {
            background: white !important;
            border: 2px solid #e9ecef;
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            gap: 1.25rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .contact-item-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-light) 100%);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .contact-item-modern:hover {
            border-color: var(--primary-color);
            box-shadow: 0 8px 24px rgba(40, 84, 150, 0.15);
        }

        .contact-item-modern:hover::before {
            transform: scaleY(1);
        }

        .contact-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(40, 84, 150, 0.1) 0%, rgba(58, 107, 199, 0.1) 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.5rem;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .contact-item-modern:hover .contact-icon {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: white;
            transform: scale(1.1);
        }

        .contact-content {
            flex-grow: 1;
        }

        .contact-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
            display: block;
        }

        .contact-value {
            font-size: 1.1rem;
            font-weight: 500;
            color: #2c3e50;
            margin: 0;
            word-break: break-word;
        }

        .contact-link {
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .contact-link:hover {
            color: var(--primary-light);
            transform: translateX(3px);
        }

        /* Divider Modern */
        .divider-modern {
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
            opacity: 0.2;
        }

        .social-title {
            font-weight: 700;
            color: #2c3e50;
            font-size: 1.1rem;
        }

        /* Social Links Modern */
        .social-links-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
        }

        .social-link-modern {
            background: white !important;
            border: 2px solid #e9ecef;
            border-radius: 14px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .social-link-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            transition: transform 0.3s ease;
            transform: scaleY(0);
        }

        .social-link-modern.facebook::before {
            background: var(--facebook);
        }

        .social-link-modern.instagram::before {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        }

        .social-link-modern.twitter::before {
            background: var(--twitter);
        }

        .social-link-modern.linkedin::before {
            background: var(--linkedin);
        }

        .social-link-modern:hover::before {
            transform: scaleY(1);
        }

        .social-link-modern:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .social-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .social-link-modern.facebook .social-icon {
            background: rgba(24, 119, 242, 0.1);
            color: var(--facebook);
        }

        .social-link-modern.facebook:hover .social-icon {
            background: var(--facebook);
            color: white;
        }

        .social-link-modern.instagram .social-icon {
            background: rgba(228, 64, 95, 0.1);
            color: var(--instagram);
        }

        .social-link-modern.instagram:hover .social-icon {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            color: white;
        }

        .social-link-modern.twitter .social-icon {
            background: rgba(29, 161, 242, 0.1);
            color: var(--twitter);
        }

        .social-link-modern.twitter:hover .social-icon {
            background: var(--twitter);
            color: white;
        }

        .social-link-modern.linkedin .social-icon {
            background: rgba(0, 119, 181, 0.1);
            color: var(--linkedin);
        }

        .social-link-modern.linkedin:hover .social-icon {
            background: var(--linkedin);
            color: white;
        }

        .social-info {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .social-name {
            font-weight: 700;
            font-size: 1rem;
            color: #2c3e50;
            margin-bottom: 0.25rem;
        }

        .social-url {
            font-size: 0.85rem;
            color: #6c757d;
            word-break: break-all;
        }

        .social-arrow {
            color: #6c757d;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .social-link-modern:hover .social-arrow {
            transform: translateX(3px);
            color: var(--primary-color);
        }

        /* Empty State */
        .empty-state-modern {
            position: relative;
        }

        .empty-icon-wrapper {
            position: relative;
            display: inline-block;
        }

        .empty-icon-circle {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, rgba(40, 84, 150, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        .empty-social-state {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 12px;
            border: 2px dashed #dee2e6;
        }

        /* Modern Buttons */
        .btn-modern {
            border-radius: 10px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
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

        /* Alert Modern */
        .alert {
            border-radius: 12px;
            border: none;
            transition: all 0.3s ease;
        }

        .alert-icon-modern {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.3);
        }

        .alert-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .alert-danger {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .page-header-modern .d-flex {
                flex-direction: column;
                text-align: center;
            }

            .icon-wrapper-modern {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 768px) {
            .card-modern:hover {
                transform: translateY(-4px);
            }

            .social-links-container {
                grid-template-columns: 1fr;
            }

            .contact-item-modern {
                flex-direction: column;
                text-align: center;
            }

            .contact-icon {
                margin: 0 auto;
            }

            .btn-modern {
                font-size: 0.8rem;
                padding: 0.4rem 1rem;
            }
        }

        @media (max-width: 576px) {
            .page-header-modern {
                border-radius: 12px;
            }

            .card-modern {
                border-radius: 12px;
            }

            .contact-value {
                font-size: 1rem;
            }

            h5 {
                font-size: 1.1rem;
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
    </style>
@endsection