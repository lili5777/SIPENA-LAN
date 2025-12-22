<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('gambar/lanri.png') }}" type="image/png">
    <title>@yield('title', 'SIPENA - Sistem Informasi Profesional')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-color: #1a3a6c;
            --secondary-color: #2c5aa0;
            --accent-color: #e63946;
            --gold-color: #d4af37;
            --dark-color: #0d1b2a;
            --light-color: #f8f9fa;
            --gray-color: #6c757d;
            --shadow: 0 10px 30px rgba(13, 27, 42, 0.15);
            --transition: all 0.3s ease;
            --gradient-primary: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            --gradient-light: linear-gradient(135deg, #e2e4e8 0%, #305eb9 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--light-color);
            color: var(--dark-color);
            line-height: 1.6;
            overflow-x: hidden;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
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

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .animate {
            animation: fadeInUp 0.6s ease forwards;
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .banner-title {
                font-size: 2rem;
            }

            .banner-text {
                font-size: 1rem;
            }
        }

        @media (max-width: 768px) {
            .banner {
                height: 400px;
            }

            .banner-title {
                font-size: 1.8rem;
            }

            .news-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        @media (max-width: 576px) {
            .banner {
                height: 350px;
            }

            .banner-title {
                font-size: 1.5rem;
            }

            .section-title {
                font-size: 1.7rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }

        @media (max-width: 380px) {
            .container {
                padding: 0 15px;
            }

            .banner {
                height: 300px;
            }

            .banner-title {
                font-size: 1.0rem;
            }

            .banner-text {
                font-size: 0.9rem;
            }

            .section-title {
                font-size: 1.4rem;
            }

            .news-card {
                padding: 15px;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    @include('partials.navbar')

    @yield('content')

    @include('partials.footer')

    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navLinks = document.getElementById('navLinks');
        let isMenuOpen = false;

        // Toggle menu mobile
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                isMenuOpen = !isMenuOpen;

                if (isMenuOpen) {
                    navLinks.classList.add('active');
                    mobileMenuBtn.innerHTML = '<i class="fas fa-times"></i>';
                } else {
                    navLinks.classList.remove('active');
                    mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
                }
            });
        }

        // Close mobile menu when clicking a link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                if (navLinks) {
                    navLinks.classList.remove('active');
                }
                if (mobileMenuBtn) {
                    mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
                }
                isMenuOpen = false;
            });
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (isMenuOpen && navLinks && !navLinks.contains(e.target) && mobileMenuBtn && !mobileMenuBtn.contains(e.target)) {
                navLinks.classList.remove('active');
                mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
                isMenuOpen = false;
            }
        });

        // Close mobile menu on window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768 && isMenuOpen && navLinks) {
                navLinks.classList.remove('active');
                if (mobileMenuBtn) {
                    mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
                }
                isMenuOpen = false;
            }
        });

        // Scroll animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.news-card, .section-header, .banner-content').forEach(el => {
            observer.observe(el);
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();

                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Add hover effect to cards
        document.querySelectorAll('.news-card').forEach(card => {
            card.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-10px)';
            });

            card.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0)';
            });
        });

        // Add active state to nav links based on scroll position
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-links a[href^="#"]');

            let currentSection = '';

            sections.forEach(section => {
                const sectionTop = section.offsetTop - 100;
                const sectionHeight = section.clientHeight;

                if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
                    currentSection = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${currentSection}`) {
                    link.classList.add('active');
                }
            });
        });

        // Parallax effect for banner
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const banner = document.querySelector('.banner');
            const bannerImg = document.querySelector('.banner-img');

            if (banner && bannerImg) {
                const rate = scrolled * 0.5;
                bannerImg.style.transform = `translate3d(0px, ${rate}px, 0px)`;
            }
        });

        // Console log untuk debugging
        console.log('SIPENA website loaded successfully');
    </script>
    @stack('scripts')
</body>

</html>