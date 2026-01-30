@extends('admin.partials.layout')

@section('title', 'Detail Berita - SIMPEL')

@section('content')
    <!-- Page Header with Animation -->
    <div class="page-header-modern bg-gradient-primary rounded-4 mb-4 overflow-hidden position-relative">
        <div class="header-pattern"></div>
        <div class="row align-items-center position-relative" style="z-index: 2;">
            <div class="col">
                <div class="d-flex align-items-center p-4">
                    <div class="icon-wrapper-modern bg-white rounded-circle p-3 me-4 shadow-lg animate-float">
                        <i class="fas fa-newspaper fa-2x" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-2 fw-bold animate-slide-in">Detail Berita</h1>
                        <p class="text-white-50 mb-0 animate-slide-in-delay">Tampilan lengkap artikel berita</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mb-4 d-flex gap-3 animate-fade-in">
        <a href="{{ route('berita.index') }}" class="btn btn-outline-secondary btn-modern">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
        <a href="{{ route('berita.edit', $berita) }}" class="btn btn-warning btn-modern">
            <i class="fas fa-edit me-2"></i> Edit Berita
        </a>
        <button type="button" class="btn btn-danger btn-modern" data-bs-toggle="modal" data-bs-target="#deleteModal">
            <i class="fas fa-trash-alt me-2"></i> Hapus Berita
        </button>
    </div>

    <!-- Article Content -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <article class="article-modern border-0 shadow-lg animate-fade-in-delay">
                <div class="article-glow"></div>

                <!-- Article Header -->
                <div class="article-header">
                    @if($berita->foto)
                        <div class="article-featured-image">
                            <img src="{{ asset('gambar/' . $berita->foto) }}" alt="{{ $berita->judul }}" class="featured-image"
                                onerror="this.src='{{ asset('gambar/default-news.png') }}'">
                            <div class="image-overlay"></div>
                        </div>
                    @endif

                    <div class="article-header-content p-5">
                        <h1 class="article-title mb-4">{{ $berita->judul }}</h1>

                        <div class="article-meta">
                            <span class="meta-item">
                                <i class="far fa-calendar-alt me-2"></i>
                                {{ $berita->created_at->format('d F Y') }}
                            </span>
                            <span class="meta-divider">•</span>
                            <span class="meta-item">
                                <i class="far fa-clock me-2"></i>
                                {{ $berita->created_at->format('H:i') }} WIB
                            </span>
                            @if($berita->created_at != $berita->updated_at)
                                <span class="meta-divider">•</span>
                                <span class="meta-item">
                                    <i class="fas fa-edit me-2"></i>
                                    Diperbarui {{ $berita->updated_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Article Body -->
                <div class="article-body p-5">
                    <div class="article-content">
                        {!! $berita->isi !!}
                    </div>
                </div>

                <!-- Article Footer -->
                <div class="article-footer p-5 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="article-info">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Dibuat {{ $berita->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <div class="article-actions">
                            <a href="{{ route('berita.edit', $berita) }}" class="btn btn-sm btn-warning btn-action">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-xl modal-modern">
                <div class="modal-body text-center p-5">
                    <div class="delete-icon-modern mb-4">
                        <div class="warning-pulse"></div>
                        <i class="fas fa-exclamation-triangle fa-4x"></i>
                    </div>
                    <h4 class="modal-title mb-3 fw-bold">Konfirmasi Hapus</h4>
                    <p class="text-muted mb-2">Anda akan menghapus berita:</p>
                    <div class="delete-preview-box mb-4">
                        <h5 class="text-danger fw-bold mb-0">{{ $berita->judul }}</h5>
                    </div>
                    <div class="warning-box mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <span>Tindakan ini tidak dapat dibatalkan dan foto akan terhapus</span>
                    </div>
                    <div class="modal-actions d-flex gap-3 justify-content-center">
                        <button type="button" class="btn btn-outline-secondary btn-modern px-4" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i> Batal
                        </button>
                        <form action="{{ route('berita.destroy', $berita) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-modern px-4">
                                <i class="fas fa-trash-alt me-2"></i> Hapus Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Smooth scroll for internal links
            document.querySelectorAll('.article-content a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
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

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        .animate-fade-in-delay {
            animation: fadeIn 0.6s ease-out 0.3s backwards;
        }

        /* Article Modern */
        .article-modern {
            background: white !important;
            border-radius: var(--border-radius);
            overflow: hidden;
            position: relative;
        }

        .article-glow {
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(40, 84, 150, 0.05) 0%, transparent 70%);
            opacity: 1;
        }

        /* Article Header */
        .article-header {
            position: relative;
        }

        .article-featured-image {
            position: relative;
            height: 500px;
            overflow: hidden;
        }

        .featured-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 50%;
            background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.7) 100%);
        }

        .article-header-content {
            position: relative;
            background: white;
        }

        .article-title {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.2;
            color: #2c3e50;
            margin: 0;
        }

        .article-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
            padding-top: 1rem;
            border-top: 2px solid #e9ecef;
        }

        .meta-item {
            font-size: 0.9rem;
            color: #6c757d;
            font-weight: 500;
        }

        .meta-divider {
            color: #dee2e6;
        }

        /* Article Body */
        .article-body {
            position: relative;
            z-index: 1;
        }

        .article-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #2c3e50;
        }

        .article-content h1,
        .article-content h2,
        .article-content h3,
        .article-content h4,
        .article-content h5,
        .article-content h6 {
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .article-content h1 {
            font-size: 2rem;
        }

        .article-content h2 {
            font-size: 1.75rem;
        }

        .article-content h3 {
            font-size: 1.5rem;
        }

        .article-content p {
            margin-bottom: 1.5rem;
        }

        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 1.5rem 0;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .article-content a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .article-content a:hover {
            color: var(--primary-light);
            text-decoration: underline;
        }

        .article-content ul,
        .article-content ol {
            margin-bottom: 1.5rem;
            padding-left: 2rem;
        }

        .article-content li {
            margin-bottom: 0.5rem;
        }

        .article-content blockquote {
            border-left: 4px solid var(--primary-color);
            padding-left: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            color: #6c757d;
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
        }

        .article-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            border-radius: 8px;
            overflow: hidden;
        }

        .article-content table th,
        .article-content table td {
            padding: 0.75rem;
            border: 1px solid #dee2e6;
        }

        .article-content table th {
            background: var(--primary-color);
            color: white;
            font-weight: 600;
        }

        .article-content table tr:nth-child(even) {
            background: #f8f9fa;
        }

        /* Article Footer */
        .article-footer {
            position: relative;
            z-index: 1;
            background: #f8f9fa;
        }

        /* Action Buttons */
        .btn-action {
            border-radius: 8px;
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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

        .btn-outline-secondary.btn-modern {
            border: 2px solid #6c757d;
            color: #6c757d;
        }

        .btn-outline-secondary.btn-modern:hover {
            background: #6c757d;
            color: white;
        }

        /* Modal Modern */
        .modal-modern {
            border-radius: 20px;
            overflow: hidden;
        }

        .delete-icon-modern {
            position: relative;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .delete-icon-modern i {
            color: var(--danger-color);
            position: relative;
            z-index: 2;
        }

        .warning-pulse {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100px;
            height: 100px;
            background: rgba(220, 53, 69, 0.2);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        .delete-preview-box {
            background: linear-gradient(135deg, #fff5f5 0%, #ffe0e0 100%);
            border-left: 4px solid var(--danger-color);
            border-radius: 12px;
            padding: 1rem;
        }

        .warning-box {
            background: linear-gradient(135deg, #fff3cd 0%, #ffe4a0 100%);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            color: #856404;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .article-featured-image {
                height: 400px;
            }

            .article-title {
                font-size: 2rem;
            }

            .article-content {
                font-size: 1rem;
            }
        }

        @media (max-width: 768px) {
            .article-featured-image {
                height: 300px;
            }

            .article-title {
                font-size: 1.75rem;
            }

            .article-header-content,
            .article-body,
            .article-footer {
                padding: 2rem !important;
            }

            .article-meta {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 576px) {
            .article-featured-image {
                height: 250px;
            }

            .article-title {
                font-size: 1.5rem;
            }

            .article-header-content,
            .article-body,
            .article-footer {
                padding: 1.5rem !important;
            }
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Focus styles for accessibility */
        .btn-modern:focus,
        .btn-action:focus {
            outline: 3px solid rgba(40, 84, 150, 0.4);
            outline-offset: 2px;
        }
    </style>
@endsection