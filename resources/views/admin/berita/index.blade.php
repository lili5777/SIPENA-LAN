@extends('admin.partials.layout')

@section('title', 'Berita - SIMPEL')

@section('styles')
    <style>
        /* Modern Color Variables */
        :root {
            --primary-color: #1a3a6c;
            --secondary-color: #2c5aa0;
            --accent-color: #e63946;
            --gold-color: #d4af37;
            --success-color: #2a9d8f;
            --border-radius: 12px;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.16);
        }

        /* Page Header Modern */
        .page-header-modern {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            position: relative;
            overflow: hidden;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
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

        /* Info Box */
        .info-box {
            background: linear-gradient(135deg, #e3f2fd 0%, #f3f8ff 100%);
            border-left: 4px solid var(--primary-color);
            border-radius: 10px;
            padding: 0.75rem 1.25rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--primary-color);
        }

        /* Berita Card */
        .card-modern {
            border-radius: var(--border-radius);
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white !important;
            border: none !important;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .berita-card:hover {
            box-shadow: 0 12px 40px rgba(26, 58, 108, 0.2) !important;
            transform: translateY(-5px);
        }

        .card-glow {
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(26, 58, 108, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .berita-card:hover .card-glow {
            opacity: 1;
        }

        /* Thumbnail Section */
        .berita-thumbnail {
            position: relative;
            height: 220px;
            overflow: hidden;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            flex-shrink: 0;
        }

        .thumbnail-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .berita-card:hover .thumbnail-image {
            transform: scale(1.05);
        }

        .thumbnail-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
        }

        .thumbnail-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.4) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .berita-card:hover .thumbnail-overlay {
            opacity: 1;
        }

        /* Date Badge */
        .date-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: white;
            border-radius: 10px;
            padding: 0.5rem 0.75rem;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 2;
        }

        .date-day {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            line-height: 1;
        }

        .date-month {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
        }

        /* Card Content */
        .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 1.5rem !important;
        }

        .berita-title {
            font-size: 1.1rem;
            font-weight: 600;
            line-height: 1.4;
            margin: 0 0 1rem 0;
            flex-shrink: 0;
        }

        .title-link {
            color: #2c3e50;
            text-decoration: none;
            transition: color 0.3s ease;
            display: block;
        }

        .title-link:hover {
            color: var(--primary-color);
            text-decoration: none;
        }

        .berita-excerpt {
            font-size: 0.9rem;
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 1rem;
            flex-grow: 1;
            overflow: hidden;
        }

        .berita-meta {
            border-top: 1px solid #e9ecef;
            padding-top: 0.75rem;
            margin-bottom: 1rem;
            flex-shrink: 0;
        }

        .meta-item {
            font-size: 0.85rem;
            color: #6c757d;
            display: flex;
            align-items: center;
        }

        /* Action Buttons - FIXED VERSION */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: auto;
            flex-shrink: 0;
        }

        .btn-action {
            border-radius: 8px !important;
            font-size: 0.85rem !important;
            padding: 0.5rem 0.75rem !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            border: none !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-decoration: none !important;
            min-height: 36px;
            flex: 1;
        }

        .btn-action:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
            color: white !important;
            text-decoration: none !important;
        }

        .btn-info.btn-action {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
            color: white !important;
        }

        .btn-info.btn-action:hover {
            background: linear-gradient(135deg, #138496 0%, #117a8b 100%) !important;
            color: white !important;
        }

        .btn-warning.btn-action {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important;
            color: white !important;
        }

        .btn-warning.btn-action:hover {
            background: linear-gradient(135deg, #e0a800 0%, #d39e00 100%) !important;
            color: white !important;
        }

        .btn-danger.btn-action {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
            color: white !important;
        }

        .btn-danger.btn-action:hover {
            background: linear-gradient(135deg, #c82333 0%, #bd2130 100%) !important;
            color: white !important;
        }

        /* Modern Buttons */
        .btn-modern {
            border-radius: 10px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            color: white;
            text-decoration: none;
        }

        .btn-primary.btn-modern {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            color: white;
        }

        .btn-primary.btn-modern:hover {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            color: white;
        }

        .btn-outline-secondary.btn-modern {
            border: 2px solid #6c757d;
            color: #6c757d;
            background: transparent;
        }

        .btn-outline-secondary.btn-modern:hover {
            background: #6c757d;
            color: white;
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

        .alert .btn-close {
            filter: brightness(0) invert(1);
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
            background: radial-gradient(circle, rgba(26, 58, 108, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
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
            color: var(--accent-color);
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
            background: rgba(230, 57, 70, 0.2);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        .delete-preview-box {
            background: linear-gradient(135deg, #fff5f5 0%, #ffe0e0 100%);
            border-left: 4px solid var(--accent-color);
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

        /* Pagination Modern */
        .pagination-modern .pagination {
            gap: 0.5rem;
            justify-content: center;
        }

        .pagination-modern .page-link {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            color: var(--primary-color);
            font-weight: 600;
            padding: 0.5rem 1rem;
            margin: 0;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .pagination-modern .page-link:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-2px);
            text-decoration: none;
        }

        .pagination-modern .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-color: var(--primary-color);
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .berita-thumbnail {
                height: 180px;
            }

            .berita-title {
                font-size: 1rem;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }

            .action-buttons .btn-action {
                width: 100%;
                margin: 0;
            }

            .card-body {
                padding: 1rem !important;
            }
        }

        /* Fix for z-index issues */
        .btn-action,
        .delete-berita,
        .btn-modern {
            position: relative;
            z-index: 10;
        }

        /* Ensure card content doesn't overflow */
        .berita-excerpt {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Fix for button spacing */
        .flex-fill {
            flex: 1 1 auto !important;
        }

        /* Make sure modal is on top */
        #deleteModal {
            z-index: 9999;
        }
    </style>
@endsection

@section('content')
    <!-- Page Header with Animation -->
    <div class="page-header-modern bg-gradient-primary rounded-4 mb-4 overflow-hidden position-relative">
        <div class="header-pattern"></div>
        <div class="row align-items-center position-relative" style="z-index: 2;">
            <div class="col">
                <div class="d-flex align-items-center p-4">
                    <div class="icon-wrapper-modern bg-white rounded-circle p-3 me-4 shadow-lg animate-float">
                        <i class="fas fa-newspaper fa-2x" style="color: #1a3a6c;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-2 fw-bold animate-slide-in">Kelola Berita</h1>
                        <p class="text-white-50 mb-0 animate-slide-in-delay">Manajemen berita dan artikel perusahaan</p>
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

    <!-- Action Button -->
    <div class="mb-4 d-flex justify-content-between align-items-center animate-fade-in">
        <div class="info-box">
            <i class="fas fa-info-circle me-2"></i>
            <span>Total: <strong>{{ $beritas->total() }}</strong> berita</span>
        </div>
        <a href="{{ route('berita.create') }}" class="btn btn-primary btn-modern shadow-sm">
            <i class="fas fa-plus me-2"></i> Tambah Berita
        </a>
    </div>

    <!-- Berita Cards -->
    @if($beritas->count() > 0)
        <div class="row g-4">
            @foreach($beritas as $index => $berita)
                <div class="col-lg-4 col-md-6 animate-fade-in" style="animation-delay: {{ $index * 0.1 }}s">
                    <div class="card-modern berita-card border-0 shadow-lg h-100">
                        <div class="card-glow"></div>

                        <!-- Thumbnail Section -->
                        <div class="berita-thumbnail">
                            @if($berita->foto)
                                <img src="{{ asset('gambar/' . $berita->foto) }}" alt="{{ $berita->judul }}" class="thumbnail-image"
                                    onerror="this.src='{{ asset('gambar/default-news.png') }}'">
                            @else
                                <div class="thumbnail-placeholder">
                                    <i class="fas fa-newspaper fa-4x"></i>
                                </div>
                            @endif
                            <div class="thumbnail-overlay"></div>

                            <!-- Date Badge -->
                            <div class="date-badge">
                                <div class="date-day">{{ $berita->created_at->format('d') }}</div>
                                <div class="date-month">{{ $berita->created_at->format('M') }}</div>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="berita-title mb-3 flex-shrink-0">
                                <a href="{{ route('berita.show', $berita->id) }}" class="title-link">
                                    {{ Str::limit($berita->judul, 60) }}
                                </a>
                            </h5>

                            <div class="berita-excerpt mb-3 flex-grow-1">
                                {{ Str::limit(strip_tags($berita->isi), 120) }}
                            </div>

                            <div class="berita-meta mb-3 flex-shrink-0">
                                <span class="meta-item">
                                    <i class="far fa-clock me-1"></i>
                                    {{ $berita->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <!-- Action Buttons - FIXED POSITIONING -->
                            <div class="action-buttons d-flex gap-2 flex-shrink-0 mt-2">
                                <a href="{{ route('berita.show', $berita->id) }}" class="btn btn-sm btn-info btn-action flex-fill">
                                    <i class="fas fa-eye me-1"></i> Lihat
                                </a>
                                <a href="{{ route('berita.edit', $berita->id) }}"
                                    class="btn btn-sm btn-warning btn-action flex-fill">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-danger btn-action delete-berita flex-fill"
                                    data-id="{{ $berita->id }}" data-judul="{{ $berita->judul }}">
                                    <i class="fas fa-trash-alt me-1"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($beritas->hasPages())
            <div class="d-flex justify-content-center mt-5">
                <div class="pagination-modern">
                    {{ $beritas->links() }}
                </div>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="row">
            <div class="col-12">
                <div class="card-modern border-0 shadow-lg animate-fade-in">
                    <div class="card-body p-5">
                        <div class="empty-state-modern text-center py-5">
                            <div class="empty-icon-wrapper mb-4">
                                <i class="fas fa-newspaper fa-4x text-muted opacity-50"></i>
                                <div class="empty-icon-circle"></div>
                            </div>
                            <h5 class="text-dark fw-bold mb-2">Belum ada berita</h5>
                            <p class="text-muted mb-4">Mulai dengan menambahkan berita atau artikel pertama Anda</p>
                            <a href="{{ route('berita.create') }}" class="btn btn-primary btn-modern btn-lg shadow-sm">
                                <i class="fas fa-plus me-2"></i> Tambah Berita Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Enhanced Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-xl modal-modern">
                <div class="modal-body text-center p-5">
                    <div class="delete-icon-modern mb-4">
                        <div class="warning-pulse"></div>
                        <i class="fas fa-exclamation-triangle fa-4x"></i>
                    </div>
                    <h4 class="modal-title mb-3 fw-bold" id="deleteModalLabel">Konfirmasi Hapus</h4>
                    <p class="text-muted mb-2">Anda akan menghapus berita:</p>
                    <div class="delete-preview-box mb-4">
                        <h5 class="text-danger fw-bold mb-0" id="deleteBeritaJudul"></h5>
                    </div>
                    <div class="warning-box mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <span>Tindakan ini tidak dapat dibatalkan dan foto akan terhapus</span>
                    </div>
                    <div class="modal-actions d-flex gap-3 justify-content-center">
                        <button type="button" class="btn btn-outline-secondary btn-modern px-4" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i> Batal
                        </button>
                        <form id="deleteForm" method="POST" class="d-inline">
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
    <!-- Load jQuery terlebih dahulu -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script>
        // Versi dengan jQuery (setelah jQuery dimuat)
        $(document).ready(function () {
            console.log('Berita index page loaded - jQuery version');

            // Delete Berita Confirmation - SIMPLE VERSION
            $(document).on('click', '.delete-berita', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const beritaId = $(this).data('id');
                const beritaJudul = $(this).data('judul');

                console.log('Delete button clicked:', beritaId, beritaJudul);

                // Update modal content
                $('#deleteBeritaJudul').text(beritaJudul);

                // Build delete URL - adjust based on your route
                const deleteUrl = '{{ url("berita") }}/' + beritaId;
                $('#deleteForm').attr('action', deleteUrl);

                // Show modal using Bootstrap
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                deleteModal.show();
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function () {
                $('.alert').alert('close');
            }, 5000);

            // Card hover effects
            $('.berita-card').hover(
                function () {
                    $(this).css({
                        'transform': 'translateY(-5px)',
                        'transition': 'all 0.3s ease'
                    });
                },
                function () {
                    $(this).css('transform', 'translateY(0)');
                }
            );

            // Fix for pagination links
            $(document).on('click', '.pagination a', function (e) {
                e.preventDefault();
                const url = $(this).attr('href');
                window.location.href = url;
            });
        });

        // Versi tanpa jQuery (fallback)
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Berita index page loaded - vanilla JS version');

            // Add click event listeners to delete buttons
            document.querySelectorAll('.delete-berita').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const beritaId = this.getAttribute('data-id');
                    const beritaJudul = this.getAttribute('data-judul');

                    console.log('Delete clicked (vanilla):', beritaId, beritaJudul);

                    // Update modal content
                    document.getElementById('deleteBeritaJudul').textContent = beritaJudul;

                    // Build delete URL
                    const deleteUrl = '{{ url("berita") }}/' + beritaId;
                    document.getElementById('deleteForm').action = deleteUrl;

                    // Show modal
                    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                    deleteModal.show();
                });
            });

            // Card hover effects - vanilla JS
            document.querySelectorAll('.berita-card').forEach(card => {
                card.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateY(-5px)';
                    this.style.transition = 'all 0.3s ease';
                });

                card.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Auto-hide alerts
            setTimeout(function () {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
@endsection