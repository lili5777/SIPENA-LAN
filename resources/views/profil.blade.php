@extends('layouts.master')

@section('title', 'Profil - SIMPEL')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-overlay">
            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title">Profil Organisasi</h1>
                    <p class="hero-subtitle">PUSJAR SKMP - Menggerakkan Transformasi Digital Pemerintahan Menuju Indonesia
                        Maju</p>

                </div>
            </div>
        </div>
    </section>

    <!-- Visi Section -->
    <section class="visi-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">Visi Organisasi</div>
                <h2 class="section-title">Masa Depan yang <span class="highlight">Kami Bangun</span></h2>
            </div>
            <div class="visi-content">
                <div class="visi-card">
                    <div class="visi-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M12 2a10 10 0 1 0 10 10H12V2z" />
                            <path d="M19 13a7 7 0 0 0-14 0" />
                            <path d="M12 2v20" />
                        </svg>
                    </div>
                    <div class="visi-text">
                        <blockquote>
                            "Sebagai Institusi Pembelajar Berkelas Dunia yang Mampu menjadi Penggerak Utama dalam mewujudkan
                            World Class Government Untuk Mendukung Visi Indonesia Maju yang berdaulat, Mandiri, dan
                            berkepribadian berlandaskan gotong royong."
                        </blockquote>
                        <div class="visi-tagline">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M12 19l7-7 3 3-7 7-3-3z" />
                                <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z" />
                                <path d="M2 2l7.586 7.586" />
                            </svg>
                            Membangun Indonesia yang Berdaulat dan Mandiri
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Misi Section -->
    <section class="misi-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">Misi Organisasi</div>
                <h2 class="section-title">Komitmen <span class="highlight">Kami</span></h2>
            </div>

            <div class="misi-grid">
                <!-- Misi 1 -->
                <div class="misi-card" data-aos="fade-up">
                    <div class="misi-number">01</div>
                    <div class="misi-content">
                        <h3 class="misi-title">SDM Aparatur Unggul</h3>
                        <p class="misi-description">
                            Mewujudkan SDM Aparatur unggul melalui kebijakan, pembinaan, dan penyelenggaraan pengembangan
                            kompetensi yang berstandar internasional.
                        </p>
                    </div>
                    <div class="misi-hover">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                </div>

                <!-- Misi 2 -->
                <div class="misi-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="misi-number">02</div>
                    <div class="misi-content">
                        <h3 class="misi-title">Kebijakan Berkualitas</h3>
                        <p class="misi-description">
                            Mewujudkan Kebijakan Administrasi Negara yang berkualitas melalui kajian kebijakan berbasis
                            evidence dan penyediaan analis kebijakan yang kompeten.
                        </p>
                    </div>
                    <div class="misi-hover">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                        </svg>
                    </div>
                </div>

                <!-- Misi 3 -->
                <div class="misi-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="misi-number">03</div>
                    <div class="misi-content">
                        <h3 class="misi-title">Inovasi Administrasi</h3>
                        <p class="misi-description">
                            Mewujudkan Inovasi Administrasi Negara yang berkualitas melalui pengembangan model inovasi serta
                            penguatan kapasitas dan budaya inovasi.
                        </p>
                    </div>
                    <div class="misi-hover">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                        </svg>
                    </div>
                </div>

                <!-- Misi 4 -->
                <div class="misi-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="misi-number">04</div>
                    <div class="misi-content">
                        <h3 class="misi-title">Organisasi Pembelajar</h3>
                        <p class="misi-description">
                            Mewujudkan organisasi pembelajar berkinerja tinggi melalui dukungan pelayanan yang berkualitas
                            dan berbasis elektronik.
                        </p>
                    </div>
                    <div class="misi-hover">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership Section -->
    <section class="leadership-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">Kepemimpinan</div>
                <h2 class="section-title">Pejabat <span class="highlight">Struktural</span></h2>
            </div>

            <div class="leadership-grid">
                <!-- Pejabat 1 -->
                <div class="leadership-card">
                    <div class="card-floating-bg"></div>
                    <div class="leadership-photo">
                        <img src="{{ asset('gambar/kapus.png') }}"
                            alt="Dr. Ahmad Wijaya">
                        <div class="photo-border"></div>
                    </div>
                    <div class="leadership-info">
                        <h3 class="leader-name">Dr. Muhammad Aswad, M.Si</h3>
                        <p class="leader-position">Kepala Pusat PUSJAR SKMP</p>
                        <div class="leader-nip">
                            <span>NIP:</span>
                            <strong>19670206 199303 1 001</strong>
                        </div>
                    </div>
                </div>

                <!-- Pejabat 2 -->
                <div class="leadership-card">
                    <div class="card-floating-bg"></div>
                    <div class="leadership-photo">
                        <img src=""
                            alt="Drs. Budi Santoso">
                        <div class="photo-border"></div>
                    </div>
                    <div class="leadership-info">
                        <h3 class="leader-name">Pejabat 2</h3>
                        <p class="leader-position">Wakil Kepala Bidang SDM</p>
                        <div class="leader-nip">
                            <span>NIP:</span>
                            <strong>1xxxxxxxxxx</strong>
                        </div>
                    </div>
                </div>

                <!-- Pejabat 3 -->
                <div class="leadership-card">
                    <div class="card-floating-bg"></div>
                    <div class="leadership-photo">
                        <img src=""
                            alt="Ir. Siti Nurhaliza">
                        <div class="photo-border"></div>
                    </div>
                    <div class="leadership-info">
                        <h3 class="leader-name">Pejabat 3</h3>
                        <p class="leader-position">Kepala Bidang Inovasi</p>
                        <div class="leader-nip">
                            <span>NIP:</span>
                            <strong>1xxxxxxxxxxxxx</strong>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        /* Hero Section */
        .hero-section {
            position: relative;
            height: 65vh;
            min-height: 300px;
            background: linear-gradient(135deg,
                    rgba(26, 58, 108, 0.9) 0%,
                    rgba(44, 90, 160, 0.9) 50%,
                    rgba(26, 58, 108, 0.9) 100%),
                url('https://www.sipena.info/images/gedung.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            color: white;
            overflow: hidden;
        }

        .hero-overlay {
            position: relative;
            z-index: 2;
            width: 100%;
        }

        .hero-content {
            max-width: 800px;
            animation: fadeInUp 1s ease-out;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.1;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 3rem;
            line-height: 1.6;
            max-width: 600px;
        }

        .hero-stats {
            display: flex;
            gap: 3rem;
            margin-top: 4rem;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #fff, #e6f0ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .hero-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 150px;
            color: var(--light-color);
            z-index: 1;
        }

        /* Section Header */
        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark-color);
            margin-bottom: 1rem;
        }

        .highlight {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Visi Section */
        .visi-section {
            padding: 8rem 0;
            background: linear-gradient(to bottom, #fff 0%, #f8fafd 100%);
        }

        .visi-content {
            max-width: 900px;
            margin: 0 auto;
        }

        .visi-card {
            background: white;
            border-radius: 24px;
            padding: 4rem;
            box-shadow:
                0 20px 60px rgba(26, 58, 108, 0.1),
                0 1px 3px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
            transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .visi-card:hover {
            transform: translateY(-10px);
        }

        .visi-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            color: white;
        }

        .visi-icon svg {
            width: 40px;
            height: 40px;
        }

        .visi-text blockquote {
            font-size: 1.4rem;
            line-height: 1.8;
            color: var(--dark-color);
            font-weight: 500;
            margin-bottom: 2rem;
            position: relative;
            padding-left: 2rem;
        }

        .visi-text blockquote::before {
            content: '"';
            position: absolute;
            left: 0;
            top: -20px;
            font-size: 4rem;
            color: var(--primary-color);
            opacity: 0.2;
            font-family: Georgia, serif;
        }

        .visi-tagline {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1.1rem;
            padding-top: 2rem;
            border-top: 2px solid rgba(26, 58, 108, 0.1);
        }

        .visi-tagline svg {
            width: 24px;
            height: 24px;
            stroke-width: 2.5;
        }

        /* Misi Section */
        .misi-section {
            padding: 8rem 0;
            background: white;
        }

        .misi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .misi-card {
            background: linear-gradient(135deg,
                    rgba(248, 249, 250, 1) 0%,
                    rgba(255, 255, 255, 1) 100%);
            border-radius: 20px;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(26, 58, 108, 0.1);
            transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
            cursor: pointer;
        }

        .misi-card:hover {
            transform: translateY(-15px);
            box-shadow:
                0 30px 60px rgba(26, 58, 108, 0.15),
                0 10px 20px rgba(26, 58, 108, 0.1);
            border-color: transparent;
            background: linear-gradient(135deg,
                    rgba(26, 58, 108, 0.02) 0%,
                    rgba(44, 90, 160, 0.02) 100%);
        }

        .misi-number {
            font-size: 4rem;
            font-weight: 800;
            background: linear-gradient(135deg,
                    rgba(26, 58, 108, 0.1) 0%,
                    rgba(44, 90, 160, 0.1) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 1.5rem;
        }

        .misi-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .misi-description {
            color: var(--dark-color);
            line-height: 1.6;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .misi-hover {
            position: absolute;
            right: 2rem;
            bottom: 2rem;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .misi-card:hover .misi-hover {
            transform: translateY(0);
            opacity: 1;
        }

        .misi-hover svg {
            width: 20px;
            height: 20px;
        }

        /* Leadership Section */
        .leadership-section {
            padding: 8rem 0;
            background: linear-gradient(to bottom, #f8fafd 0%, #fff 100%);
        }

        .leadership-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2.5rem;
        }

        .leadership-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
            box-shadow:
                0 10px 30px rgba(26, 58, 108, 0.08),
                0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .leadership-card:hover {
            transform: translateY(-15px);
            box-shadow:
                0 40px 80px rgba(26, 58, 108, 0.15),
                0 15px 30px rgba(26, 58, 108, 0.1);
        }

        .card-floating-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg,
                    rgba(26, 58, 108, 0.03) 0%,
                    rgba(44, 90, 160, 0.03) 100%);
            opacity: 0;
            transition: opacity 0.6s ease;
        }

        .leadership-card:hover .card-floating-bg {
            opacity: 1;
        }

        .leadership-photo {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            position: relative;
        }

        .leadership-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            position: relative;
            z-index: 2;
        }

        .photo-border {
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            z-index: 1;
            opacity: 0.3;
            transition: all 0.6s ease;
        }

        .leadership-card:hover .photo-border {
            opacity: 1;
            transform: scale(1.05);
        }

        .leadership-info {
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .leader-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        .leader-position {
            color: var(--accent-color);
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }

        .leader-nip {
            background: rgba(26, 58, 108, 0.05);
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            display: inline-flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
        }

        .leader-nip span {
            color: var(--gray-color);
            font-weight: 500;
        }

        .leader-nip strong {
            color: var(--dark-color);
            font-weight: 600;
            letter-spacing: 0.5px;
        }


        

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .leadership-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
        }

        @media (max-width: 992px) {
            .hero-title {
                font-size: 3rem;
            }

            .hero-stats {
                gap: 2rem;
            }

            .section-title {
                font-size: 2.2rem;
            }

            .misi-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                height: 60vh;
                min-height: 500px;
                background-attachment: scroll;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .hero-stats {
                flex-direction: column;
                gap: 1.5rem;
                margin-top: 3rem;
            }

            .stat-item {
                align-items: center;
            }

            .visi-card {
                padding: 3rem 2rem;
            }

            .visi-text blockquote {
                font-size: 1.2rem;
                padding-left: 1.5rem;
            }

            .leadership-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 2rem;
            }

            .cta-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .visi-card {
                padding: 2rem 1.5rem;
                border-radius: 16px;
            }

            .visi-icon {
                width: 60px;
                height: 60px;
            }

            .visi-icon svg {
                width: 30px;
                height: 30px;
            }

            .visi-text blockquote {
                font-size: 1.1rem;
                padding-left: 1rem;
            }

            .visi-text blockquote::before {
                font-size: 3rem;
            }

            .misi-card {
                padding: 2rem;
            }

            .leadership-card {
                padding: 2rem;
            }

            .leadership-photo {
                width: 100px;
                height: 100px;
            }

            .cta-title {
                font-size: 1.6rem;
            }

            .cta-button {
                padding: 0.8rem 2rem;
                font-size: 1rem;
            }
        }

        @media (max-width: 380px) {
            .hero-title {
                font-size: 1.8rem;
            }

            .section-title {
                font-size: 1.6rem;
            }

            .misi-grid {
                grid-template-columns: 1fr;
            }

            .leadership-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Animate cards on scroll
        document.querySelectorAll('.misi-card, .leadership-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });

        // Parallax effect for hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const heroSection = document.querySelector('.hero-section');

            if (heroSection) {
                const rate = scrolled * 0.3;
                heroSection.style.backgroundPosition = `center ${rate}px`;
            }
        });

        // Smooth hover effects for cards
        document.querySelectorAll('.leadership-card').forEach(card => {
            card.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-15px)';
            });

            card.addEventListener('mouseleave', function () {
                if (!this.matches(':hover')) {
                    this.style.transform = 'translateY(0)';
                }
            });
        });

        // Add click effect to misi cards
        document.querySelectorAll('.misi-card').forEach(card => {
            card.addEventListener('click', function () {
                this.style.transform = 'translateY(-15px) scale(1.02)';
                setTimeout(() => {
                    if (!this.matches(':hover')) {
                        this.style.transform = 'translateY(-15px)';
                    }
                }, 300);
            });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();

                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Initialize animations on page load
        document.addEventListener('DOMContentLoaded', function () {
            const heroContent = document.querySelector('.hero-content');
            if (heroContent) {
                heroContent.style.animation = 'fadeInUp 1s ease-out';
            }
        });

        // Add floating effect to leadership cards on hover
        document.querySelectorAll('.leadership-card').forEach(card => {
            card.addEventListener('mousemove', function (e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                const centerX = rect.width / 2;
                const centerY = rect.height / 2;

                const rotateY = (x - centerX) / 25;
                const rotateX = (centerY - y) / 25;

                this.style.transform = `translateY(-15px) perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            });

            card.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0) perspective(1000px) rotateX(0) rotateY(0)';
            });
        });
    </script>
@endpush