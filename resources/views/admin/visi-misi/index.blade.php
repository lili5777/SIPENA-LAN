@extends('admin.partials.layout')

@section('title', 'Visi & Misi - Sistem Inventori Obat')

@section('content')
    <!-- Page Header with Animation -->
    <div class="page-header-modern bg-gradient-primary rounded-4 mb-4 overflow-hidden position-relative">
        <div class="header-pattern"></div>
        <div class="row align-items-center position-relative" style="z-index: 2;">
            <div class="col">
                <div class="d-flex align-items-center p-4">
                    <div class="icon-wrapper-modern bg-white rounded-circle p-3 me-4 shadow-lg animate-float">
                        <i class="fas fa-bullseye fa-2x" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-2 fw-bold animate-slide-in">Visi & Misi</h1>
                        <p class="text-white-50 mb-0 animate-slide-in-delay">Kelola visi dan misi perusahaan dengan mudah
                        </p>
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

    <div class="row g-4">
        <!-- Visi Card with Modern Design -->
        <div class="col-lg-6">
            <div class="card-modern border-0 shadow-lg h-100 animate-fade-in">
                <div class="card-glow"></div>
                <div class="card-header-modern bg-gradient-light py-4 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold d-flex align-items-center">
                            <div class="icon-badge bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                <i class="fas fa-eye fa-lg" style="color: #285496;"></i>
                            </div>
                            <span>Visi Perusahaan</span>
                        </h5>
                        @if($visi)
                            <a href="{{ route('visi-misi.visi.edit') }}" class="btn btn-warning btn-modern shadow-sm">
                                <i class="fas fa-edit me-2"></i> Edit
                            </a>
                        @else
                            <a href="{{ route('visi-misi.visi.create') }}" class="btn btn-primary btn-modern shadow-sm">
                                <i class="fas fa-plus me-2"></i> Tambah
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($visi)
                        <div class="visi-content">
                            <div class="mb-4">
                                <div class="label-modern mb-3">
                                    <i class="fas fa-quote-left me-2"></i>Isi Visi
                                </div>
                                <div class="content-box-modern position-relative">
                                    <div class="quote-decoration top"></div>
                                    <p class="mb-0 fs-5 fw-medium text-dark lh-lg">{{ $visi->visi }}</p>
                                    <div class="quote-decoration bottom"></div>
                                </div>
                            </div>
                            @if($visi->ctt)
                                <div class="note-modern">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-info-circle text-primary me-2 mt-1"></i>
                                        <div>
                                            <h6 class="text-muted mb-2 small fw-semibold">CATATAN</h6>
                                            <p class="text-muted mb-0">{{ $visi->ctt }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="empty-state-modern text-center py-5">
                            <div class="empty-icon-wrapper mb-4">
                                <i class="fas fa-eye-slash fa-4x text-muted opacity-50"></i>
                                <div class="empty-icon-circle"></div>
                            </div>
                            <h5 class="text-dark fw-bold mb-2">Belum ada visi</h5>
                            <p class="text-muted mb-4">Mulai dengan menambahkan visi perusahaan Anda</p>
                            <a href="{{ route('visi-misi.visi.create') }}" class="btn btn-primary btn-modern btn-lg shadow-sm">
                                <i class="fas fa-plus me-2"></i> Tambah Visi Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Misi Card with Modern Design -->
        <div class="col-lg-6">
            <div class="card-modern border-0 shadow-lg h-100 animate-fade-in-delay">
                <div class="card-glow"></div>
                <div class="card-header-modern bg-gradient-light py-4 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold d-flex align-items-center">
                            <div class="icon-badge bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                <i class="fas fa-bullseye fa-lg" style="color: #285496;"></i>
                            </div>
                            <span>Misi Perusahaan</span>
                        </h5>
                        <a href="{{ route('visi-misi.misi.create') }}" class="btn btn-primary btn-modern shadow-sm">
                            <i class="fas fa-plus me-2"></i> Tambah
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($misi->count() > 0)
                        <div class="misi-list">
                            @foreach($misi as $index => $item)
                                <div class="misi-item-modern mb-3" style="animation-delay: {{ $index * 0.1 }}s">
                                    <div class="misi-card-inner">
                                        <div class="misi-number">{{ $index + 1 }}</div>
                                        <div class="d-flex align-items-start flex-grow-1">
                                            <div class="flex-shrink-0">
                                                @php
                                                    $iconClass = [
                                                        'users' => 'fas fa-users',
                                                        'edit' => 'fas fa-edit',
                                                        'zap' => 'fas fa-bolt',
                                                        'book' => 'fas fa-book'
                                                    ][$item->icon] ?? 'fas fa-circle';
                                                @endphp
                                                <div class="icon-wrapper-misi">
                                                    <i class="{{ $iconClass }}"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="fw-bold mb-2 text-dark">{{ $item->ctt }}</h6>
                                                <p class="mb-3 text-secondary lh-base">{{ $item->isi }}</p>
                                                <div class="action-buttons">
                                                    <a href="{{ route('visi-misi.misi.edit', $item) }}"
                                                        class="btn btn-sm btn-warning btn-action">
                                                        <i class="fas fa-edit me-1"></i> Edit
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger btn-action delete-misi"
                                                        data-id="{{ $item->id }}" data-ctt="{{ $item->ctt }}">
                                                        <i class="fas fa-trash-alt me-1"></i> Hapus
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state-modern text-center py-5">
                            <div class="empty-icon-wrapper mb-4">
                                <i class="fas fa-bullseye fa-4x text-muted opacity-50"></i>
                                <div class="empty-icon-circle"></div>
                            </div>
                            <h5 class="text-dark fw-bold mb-2">Belum ada misi</h5>
                            <p class="text-muted mb-4">Tambahkan misi untuk mencapai visi perusahaan</p>
                            <a href="{{ route('visi-misi.misi.create') }}" class="btn btn-primary btn-modern btn-lg shadow-sm">
                                <i class="fas fa-plus me-2"></i> Tambah Misi Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

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
                    <p class="text-muted mb-2">Anda akan menghapus misi:</p>
                    <div class="delete-preview-box mb-4">
                        <h5 class="text-danger fw-bold mb-0" id="deleteMisiCtt"></h5>
                    </div>
                    <div class="warning-box mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <span>Tindakan ini tidak dapat dibatalkan</span>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Delete Misi Confirmation with Animation
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const deleteForm = document.getElementById('deleteForm');
            const deleteMisiCtt = document.getElementById('deleteMisiCtt');

            document.querySelectorAll('.delete-misi').forEach(button => {
                button.addEventListener('click', function () {
                    const misiId = this.getAttribute('data-id');
                    const misiCtt = this.getAttribute('data-ctt');

                    deleteMisiCtt.textContent = misiCtt;
                    deleteForm.action = `{{ url('visi-misi/misi') }}/${misiId}`;
                    deleteModal.show();
                });
            });

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

            // Add hover effect to misi items
            const misiItems = document.querySelectorAll('.misi-item-modern');
            misiItems.forEach(item => {
                item.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateX(5px)';
                });
                item.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateX(0)';
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

            document.querySelectorAll('.misi-item-modern').forEach(item => {
                observer.observe(item);
            });
        });
    </script>

    <style>
        /* Modern Color Variables */
        :root {
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
            animation: fadeIn 0.6s ease-out;
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

        .card-body {
            background: white !important;
        }

        /* Content Box Modern */
        .content-box-modern {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-left: 4px solid var(--primary-color);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .content-box-modern:hover {
            box-shadow: var(--shadow-md);
            border-left-width: 6px;
        }

        .quote-decoration {
            position: absolute;
            font-size: 3rem;
            color: var(--primary-color);
            opacity: 0.1;
            font-family: Georgia, serif;
        }

        .quote-decoration.top {
            top: 10px;
            left: 10px;
        }

        .quote-decoration.bottom {
            bottom: 10px;
            right: 10px;
            transform: rotate(180deg);
        }

        .label-modern {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Note Modern */
        .note-modern {
            background: linear-gradient(135deg, #e3f2fd 0%, #f3f8ff 100%);
            border-radius: 12px;
            padding: 1.25rem;
            border: 1px solid rgba(40, 84, 150, 0.2);
        }

        /* Empty State Modern */
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

        /* Misi Item Modern */
        .misi-item-modern {
            opacity: 0;
            transform: translateX(-20px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: fadeIn 0.6s ease-out forwards;
        }

        .misi-item-modern.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .misi-card-inner {
            background: white !important;
            border: 2px solid #e9ecef;
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            gap: 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .misi-card-inner::before {
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

        .misi-item-modern:hover .misi-card-inner {
            border-color: var(--primary-color);
            box-shadow: 0 8px 24px rgba(40, 84, 150, 0.15);
        }

        .misi-item-modern:hover .misi-card-inner::before {
            transform: scaleY(1);
        }

        .misi-number {
            position: absolute;
            top: -10px;
            left: -10px;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(40, 84, 150, 0.3);
            z-index: 1;
        }

        .icon-wrapper-misi {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, rgba(40, 84, 150, 0.1) 0%, rgba(58, 107, 199, 0.1) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.25rem;
            transition: all 0.3s ease;
        }

        .misi-item-modern:hover .icon-wrapper-misi {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: white;
            transform: scale(1.1);
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

        .btn-action {
            border-radius: 8px;
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
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

            .misi-number {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }

            .icon-wrapper-misi {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }

            .content-box-modern {
                padding: 1.5rem;
            }

            .btn-modern {
                font-size: 0.8rem;
                padding: 0.4rem 1rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn-action {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .page-header-modern {
                border-radius: 12px;
            }

            .card-modern {
                border-radius: 12px;
            }

            .misi-card-inner {
                padding: 1rem;
            }

            .fs-5 {
                font-size: 1rem !important;
            }

            h5 {
                font-size: 1.1rem;
            }

            .modal-modern {
                border-radius: 16px;
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