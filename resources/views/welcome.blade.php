@extends('layouts.master')

@section('title', 'SIMPEL - Sistem Informasi Profesional')

@section('content')
    <!-- Banner -->
    <section class="banner" id="home">
        <img src="https://www.sipena.info/images/gedung.png" alt="Gedung SIMPEL" class="banner-img">
        <div class="banner-overlay">
            <div class="container">
                <div class="banner-content animate">
                    <h1 class="banner-title">Selamat Datang di PUSJAR SKMP</h1>
                    <p class="banner-text">Sistem Informasi Profesional yang menyediakan layanan terbaik untuk kebutuhan
                        informasi dan publikasi Anda. Kami berkomitmen untuk memberikan solusi inovatif dan berkualitas
                        tinggi.</p>
                    <a href="{{ route('pendaftaran.create') }}" class="banner-btn">Daftar Pelatihan</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Berita & Kegiatan -->
    <section class="news-section" id="publication">
        <div class="container">
            <div class="section-header animate">
                <h2 class="section-title">Berita & Kegiatan Terbaru</h2>
                <div class="section-divider"></div>
            </div>

            @if($beritas->count() > 0)
                <div class="news-grid">
                    @foreach($beritas as $berita)
                        <div class="news-card animate">
                            @if($berita->foto)
                                <img src="{{ asset('gambar/' . $berita->foto) }}" alt="{{ $berita->judul }}" class="news-img"
                                    onerror="this.src='https://images.unsplash.com/photo-1559136555-9303baea8ebd?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80'">
                            @else
                                <img src="{{ asset('gambar/thum.png') }}"
                                    alt="{{ $berita->judul }}" class="news-img">
                            @endif
                            <div class="news-content">
                                <div class="news-date">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ $berita->created_at->format('d F Y') }}
                                </div>
                                <h3 class="news-title">{{ $berita->judul }}</h3>
                                <p class="news-text">
                                    {{ Str::limit(strip_tags($berita->isi), 150) }}
                                </p>
                                <a href="{{ route('berita.detail', $berita->id) }}" class="news-link">
                                    Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($beritas->hasPages())
                    <div class="pagination-container animate" style="margin-top: 40px;">
                        <div class="pagination">
                            {{ $beritas->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            @else
                <div class="no-news animate">
                    <i class="far fa-newspaper" style="font-size: 3rem; color: var(--gray-color); margin-bottom: 20px;"></i>
                    <h3 style="color: var(--gray-color);">Belum ada berita tersedia</h3>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
    <style>
        /* Banner - Style Awal */
        .banner {
            position: relative;
            height: 500px;
            overflow: hidden;
            margin-bottom: 50px;
        }

        .banner-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(26, 58, 108, 0.8), rgba(26, 58, 108, 0.4));
            display: flex;
            align-items: center;
        }

        .banner-content {
            color: white;
            max-width: 600px;
        }

        .banner-title {
            font-size: 2.2rem;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .banner-text {
            font-size: 1.1rem;
            margin-bottom: 25px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .banner-btn {
            display: inline-block;
            background-color: var(--accent-color);
            color: white;
            padding: 12px 30px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .banner-btn:hover {
            background-color: white;
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        /* Section Header */
        .section-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
            font-weight: 700;
        }

        .section-divider {
            width: 80px;
            height: 3px;
            background-color: var(--accent-color);
            margin: 0 auto;
        }

        /* News Section */
        .news-section {
            padding: 60px 0;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }

        .news-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            cursor: pointer;
        }

        .news-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .news-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .news-card:hover .news-img {
            transform: scale(1.05);
        }

        .news-content {
            padding: 20px;
        }

        .news-date {
            font-size: 0.85rem;
            color: var(--gray-color);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .news-title {
            font-size: 1.2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
            font-weight: 600;
            line-height: 1.4;
        }

        .news-text {
            color: var(--dark-color);
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .news-link {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: var(--transition);
        }

        .news-link:hover {
            gap: 10px;
            color: var(--secondary-color);
        }

        /* Pagination Styles */
        .pagination-container {
            text-align: center;
        }

        .pagination {
            display: inline-flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 5px;
        }

        .pagination .page-item {
            margin: 0;
        }

        .pagination .page-link {
            color: var(--primary-color);
            background-color: white;
            border: 1px solid #ddd;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            transition: var(--transition);
        }

        .pagination .page-link:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .pagination .page-item.disabled .page-link {
            color: #ccc;
            pointer-events: none;
        }

        .no-news {
            text-align: center;
            padding: 40px 0;
        }

        /* Responsive pagination */
        @media (max-width: 576px) {
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }

            .pagination .page-link {
                padding: 6px 12px;
                font-size: 0.9rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Add loading animation to banner button
        const bannerBtn = document.querySelector('.banner-btn');
        if (bannerBtn) {
            bannerBtn.addEventListener('click', function (e) {
                // Add loading animation
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                this.style.pointerEvents = 'none';

                if (this.getAttribute('href') === '{{ route('pendaftaran.create') }}') {
                    return; // Biarkan smooth scroll bekerja
                }

                e.preventDefault();

                // Reset after 1.5 seconds
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.style.pointerEvents = 'auto';
                }, 1500);
            });
        }

        // Add click event to news cards
        document.querySelectorAll('.news-card').forEach(card => {
            card.addEventListener('click', function () {
                const link = this.querySelector('.news-link');
                if (link) {
                    window.location.href = link.getAttribute('href');
                }
            });

            // Add interactive effect on hover
            card.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-10px)';
            });

            card.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
@endpush