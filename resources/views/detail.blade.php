@extends('layouts.master')

@section('title', 'Detail Berita - SIMPEL')

@section('content')
        <!-- Back Button -->
        <section class="back-section">
            <div class="container">
                <a href="{{ url('/') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </section>

        <!-- Berita Detail -->
        <section class="berita-detail-section">
            <div class="container">
                <div class="berita-detail">
                    <div class="berita-header animate">
                        <h1 class="berita-title">{{ $berita->judul }}</h1>
                        <div class="berita-meta">
                            <span class="berita-date">
                                <i class="far fa-calendar-alt"></i>
                                {{ $berita->created_at->format('d F Y') }}
                            </span>
                            <span class="berita-time">
                                <i class="far fa-clock"></i>
                                {{ $berita->created_at->format('H:i') }} WIB
                            </span>
                        </div>
                    </div>

                    @if($berita->foto)
                        <div class="berita-image animate">
                            <img src="{{ asset('gambar/' . $berita->foto) }}" alt="{{ $berita->judul }}" class="berita-main-img"
                                onerror="this.src='{{ asset('gambar/thum.png') }}'">
                        </div>
                    @endif

                    <div class="berita-content animate">
                        {!! $berita->isi !!}
                    </div>

                    <div class="berita-footer animate">
                        <a href="{{ url('/') }}" class="back-home-btn">
                            <i class="fas fa-home"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Related News (Optional) -->
        <section class="related-news-section">
            <div class="container">
                <div class="section-header animate">
                    <h2 class="section-title">Berita Lainnya</h2>
                    <div class="section-divider"></div>
                </div>

                @php
    $relatedBeritas = App\Models\Berita::where('id', '!=', $berita->id)
        ->latest()
        ->take(3)
        ->get();
                @endphp

                @if($relatedBeritas->count() > 0)
                    <div class="news-grid">
                        @foreach($relatedBeritas as $related)
                            <div class="news-card animate">
                                @if($related->foto)
                                    <img src="{{ asset('storage/' . $related->foto) }}" alt="{{ $related->judul }}" class="news-img"
                                        onerror="this.src='https://images.unsplash.com/photo-1559136555-9303baea8ebd?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80'">
                                @else
                                    <img src="https://images.unsplash.com/photo-1559136555-9303baea8ebd?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80"
                                        alt="{{ $related->judul }}" class="news-img">
                                @endif
                                <div class="news-content">
                                    <div class="news-date">
                                        <i class="far fa-calendar-alt"></i>
                                        {{ $related->created_at->format('d F Y') }}
                                    </div>
                                    <h3 class="news-title">{{ $related->judul }}</h3>
                                    <p class="news-text">
                                        {{ Str::limit(strip_tags($related->isi), 100) }}
                                    </p>
                                    <a href="{{ route('berita.detail', $related->id) }}" class="news-link">
                                        Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
@endsection

@push('styles')
    <style>
        /* Back Section */
        .back-section {
            padding: 20px 0;
            background-color: #f8f9fa;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .back-btn:hover {
            gap: 12px;
            color: var(--secondary-color);
        }

        /* Berita Detail Section */
        .berita-detail-section {
            padding: 40px 0;
        }

        .berita-detail {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: var(--shadow);
        }

        .berita-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .berita-title {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .berita-meta {
            display: flex;
            justify-content: center;
            gap: 20px;
            color: var(--gray-color);
            font-size: 0.9rem;
        }

        .berita-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .berita-image {
            margin-bottom: 30px;
        }

        .berita-main-img {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: 8px;
        }

        .berita-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--dark-color);
            margin-bottom: 40px;
        }

        .berita-content p {
            margin-bottom: 20px;
        }

        .berita-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 20px 0;
        }

        .berita-content h1,
        .berita-content h2,
        .berita-content h3 {
            color: var(--primary-color);
            margin: 30px 0 15px 0;
        }

        .berita-content ul,
        .berita-content ol {
            margin-left: 20px;
            margin-bottom: 20px;
        }

        .berita-footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .back-home-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background-color: var(--primary-color);
            color: white;
            padding: 12px 25px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .back-home-btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        /* Related News Section */
        .related-news-section {
            padding: 60px 0;
            background-color: #f8f9fa;
        }

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

        /* Responsive */
        @media (max-width: 768px) {
            .berita-detail {
                padding: 20px;
            }

            .berita-title {
                font-size: 1.6rem;
            }

            .berita-meta {
                flex-direction: column;
                gap: 10px;
                align-items: center;
            }

            .berita-content {
                font-size: 1rem;
            }

            .news-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .berita-title {
                font-size: 1.4rem;
            }

            .section-title {
                font-size: 1.6rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Animasi untuk konten berita
        document.addEventListener('DOMContentLoaded', function () {
            // Animasi fade in untuk konten
            const contentElements = document.querySelectorAll('.animate');
            contentElements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Scroll ke atas saat halaman dimuat
            window.scrollTo(0, 0);
        });

        // Tambahkan efek klik pada card berita terkait
        document.querySelectorAll('.news-card').forEach(card => {
            card.addEventListener('click', function () {
                const link = this.querySelector('.news-link');
                if (link) {
                    window.location.href = link.getAttribute('href');
                }
            });
        });
    </script>
@endpush