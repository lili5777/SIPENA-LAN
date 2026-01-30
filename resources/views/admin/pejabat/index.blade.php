@extends('admin.partials.layout')

@section('title', 'Pejabat - SIMPEL')

@section('styles')
    <style>
        /* Modern Color Variables */
        :root {
            --primary-color: #1a3a6c;
            --primary-dark: #142a52;
            --primary-light: #2c5aa0;
            --gold-color: #d4af37;
            --danger-color: #e63946;
            --border-radius: 16px;
        }

        /* Page Header Modern */
        .page-header-modern {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
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

        /* Drag Info Box */
        .drag-info-box {
            background: linear-gradient(135deg, #e3f2fd 0%, #f3f8ff 100%);
            border-left: 4px solid var(--primary-color);
            border-radius: 10px;
            padding: 0.75rem 1.25rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--primary-color);
        }

        /* Pejabat Card */
        .pejabat-card-wrapper {
            cursor: move;
            transition: all 0.3s ease;
        }

        .pejabat-card {
            border-radius: var(--border-radius);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            background: white !important;
            border: none !important;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .pejabat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(26, 58, 108, 0.2) !important;
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

        .pejabat-card:hover .card-glow {
            opacity: 1;
        }

        /* Drag Handle */
        .drag-handle {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 40px;
            height: 40px;
            background: rgba(26, 58, 108, 0.9);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            cursor: grab;
            z-index: 100;
            transition: all 0.3s ease;
            opacity: 0;
        }

        .pejabat-card:hover .drag-handle {
            opacity: 1;
        }

        .drag-handle:active {
            cursor: grabbing;
            transform: scale(0.95);
        }

        /* Position Badge */
        .position-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 100;
        }

        .position-number {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(26, 58, 108, 0.3);
        }

        /* Photo Section */
        .pejabat-photo-section {
            position: relative;
            height: 250px;
            overflow: hidden;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            flex-shrink: 0;
        }

        .pejabat-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .pejabat-card:hover .pejabat-photo {
            transform: scale(1.05);
        }

        .pejabat-photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
        }

        .photo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.3) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .pejabat-card:hover .photo-overlay {
            opacity: 1;
        }

        /* Card Content */
        .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 1.5rem !important;
        }

        .pejabat-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0 0 0.75rem 0;
            flex-shrink: 0;
        }

        .pejabat-jabatan {
            font-size: 0.95rem;
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.75rem;
            flex-shrink: 0;
        }

        .pejabat-nip {
            font-size: 0.85rem;
            color: #6c757d;
            background: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 1rem;
            flex-shrink: 0;
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
            padding: 0.5rem 1rem !important;
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
            position: relative;
            z-index: 10;
        }

        .btn-action:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
            color: white !important;
            text-decoration: none !important;
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

        /* Sortable States */
        .sortable-ghost {
            opacity: 0.4;
            background: #f8f9fa;
        }

        .sortable-chosen {
            transform: scale(1.05);
        }

        .sortable-drag {
            opacity: 0.8;
            transform: rotate(2deg);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3) !important;
        }

        .dragging {
            opacity: 0.5;
        }

        /* Save Position Button */
        .save-position-container {
            animation: slideDown 0.4s ease-out;
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
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            border: none;
            color: white;
        }

        .btn-primary.btn-modern:hover {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-color) 100%);
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
            background: rgba(230, 57, 70, 0.2);
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
        @media (max-width: 768px) {
            .pejabat-photo-section {
                height: 200px;
            }

            .pejabat-name {
                font-size: 1.1rem;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }

            .action-buttons .btn-action {
                width: 100%;
            }

            .drag-handle {
                opacity: 1;
            }
        }

        @media (max-width: 576px) {
            .btn-modern {
                font-size: 0.8rem;
                padding: 0.5rem 1rem;
            }

            .pejabat-photo-section {
                height: 180px;
            }
        }

        /* Fix for button z-index */
        .btn-action,
        .btn-modern {
            position: relative;
            z-index: 10;
        }

        /* Ensure links are clickable */
        a.btn-action {
            pointer-events: auto;
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
                        <i class="fas fa-user-tie fa-2x" style="color: #1a3a6c;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-2 fw-bold animate-slide-in">Data Pejabat</h1>
                        <p class="text-white-50 mb-0 animate-slide-in-delay">Kelola informasi pejabat perusahaan dengan
                            mudah</p>
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

    <!-- Action Buttons -->
    <div class="mb-4 d-flex justify-content-between align-items-center animate-fade-in">
        <div class="drag-info-box">
            <i class="fas fa-info-circle me-2"></i>
            <span>Drag & drop kartu untuk mengatur urutan posisi</span>
        </div>
        <a href="{{ route('pejabat.create') }}" class="btn btn-primary btn-modern shadow-sm">
            <i class="fas fa-plus me-2"></i> Tambah Pejabat
        </a>
    </div>

    <!-- Pejabat Cards -->
    @if($pejabats->count() > 0)
        <div class="row g-4" id="pejabatContainer">
            @foreach($pejabats as $index => $pejabat)
                <div class="col-lg-4 col-md-6 pejabat-card-wrapper animate-fade-in" data-id="{{ $pejabat->id }}"
                    data-position="{{ $pejabat->posisi }}" style="animation-delay: {{ $index * 0.1 }}s">
                    <div class="card-modern border-0 shadow-lg h-100 pejabat-card">
                        <div class="card-glow"></div>

                        <!-- Drag Handle -->
                        <div class="drag-handle">
                            <i class="fas fa-grip-vertical"></i>
                        </div>

                        <!-- Position Badge -->
                        <div class="position-badge">
                            <span class="position-number">{{ $pejabat->posisi }}</span>
                        </div>

                        <!-- Photo Section -->
                        <div class="pejabat-photo-section">
                            @if($pejabat->foto_pejabat)
                                <img src="{{ asset('gambar/' . $pejabat->foto_pejabat) }}" alt="{{ $pejabat->nama_pejabat }}"
                                    class="pejabat-photo" onerror="this.src='{{ asset('gambar/default-avatar.png') }}'">
                            @else
                                <div class="pejabat-photo-placeholder">
                                    <i class="fas fa-user fa-4x"></i>
                                </div>
                            @endif
                            <div class="photo-overlay"></div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-4 text-center">
                            <h5 class="pejabat-name mb-2">{{ $pejabat->nama_pejabat }}</h5>
                            <div class="pejabat-jabatan mb-3">
                                <i class="fas fa-briefcase me-2"></i>
                                {{ $pejabat->jabatan_pejabat }}
                            </div>

                            @if($pejabat->nip_pejabat)
                                <div class="pejabat-nip mb-3">
                                    <i class="fas fa-id-card me-2"></i>
                                    NIP: {{ $pejabat->nip_pejabat }}
                                </div>
                            @endif

                            <!-- Action Buttons - FIXED VERSION -->
                            <div class="action-buttons d-flex gap-2 justify-content-center">
                                <a href="{{ route('pejabat.edit', $pejabat->id) }}"
                                    class="btn btn-sm btn-warning btn-action flex-fill">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-danger btn-action delete-pejabat flex-fill"
                                    data-id="{{ $pejabat->id }}" data-nama="{{ $pejabat->nama_pejabat }}">
                                    <i class="fas fa-trash-alt me-1"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Save Position Button (appears when dragging) -->
        <div class="save-position-container text-center mt-4" style="display: none;">
            <button type="button" class="btn btn-success btn-modern btn-lg shadow-lg" id="savePositionBtn">
                <i class="fas fa-save me-2"></i> Simpan Urutan Posisi
            </button>
        </div>
    @else
        <!-- Empty State -->
        <div class="row">
            <div class="col-12">
                <div class="card-modern border-0 shadow-lg animate-fade-in">
                    <div class="card-body p-5">
                        <div class="empty-state-modern text-center py-5">
                            <div class="empty-icon-wrapper mb-4">
                                <i class="fas fa-user-tie fa-4x text-muted opacity-50"></i>
                                <div class="empty-icon-circle"></div>
                            </div>
                            <h5 class="text-dark fw-bold mb-2">Belum ada data pejabat</h5>
                            <p class="text-muted mb-4">Mulai dengan menambahkan data pejabat pertama Anda</p>
                            <a href="{{ route('pejabat.create') }}" class="btn btn-primary btn-modern btn-lg shadow-sm">
                                <i class="fas fa-plus me-2"></i> Tambah Pejabat Sekarang
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
                    <p class="text-muted mb-2">Anda akan menghapus pejabat:</p>
                    <div class="delete-preview-box mb-4">
                        <h5 class="text-danger fw-bold mb-0" id="deletePejabatNama"></h5>
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
    <!-- Sortable.js for drag and drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Pejabat index page loaded');

            // Delete Pejabat Confirmation - VANILLA JS VERSION
            document.addEventListener('click', function (e) {
                // Check if clicked element is a delete button
                if (e.target.classList.contains('delete-pejabat') ||
                    e.target.closest('.delete-pejabat')) {

                    e.preventDefault();
                    e.stopPropagation();

                    const button = e.target.classList.contains('delete-pejabat') ?
                        e.target : e.target.closest('.delete-pejabat');

                    const pejabatId = button.getAttribute('data-id');
                    const pejabatNama = button.getAttribute('data-nama');

                    console.log('Delete clicked:', pejabatId, pejabatNama);

                    // Update modal content
                    document.getElementById('deletePejabatNama').textContent = pejabatNama;

                    // Build delete URL
                    const deleteUrl = '{{ url("pejabat") }}/' + pejabatId;
                    document.getElementById('deleteForm').action = deleteUrl;

                    // Show modal
                    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                    deleteModal.show();
                }
            });

            // Test edit buttons
            document.querySelectorAll('a.btn-action[href*="edit"]').forEach(link => {
                link.addEventListener('click', function (e) {
                    console.log('Edit link clicked:', this.href);
                });
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function () {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Card hover effects
            document.querySelectorAll('.pejabat-card').forEach(card => {
                card.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateY(-5px)';
                    this.style.transition = 'all 0.3s ease';
                    this.style.boxShadow = '0 12px 40px rgba(26, 58, 108, 0.2)';
                });

                card.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '';
                });
            });

            // Drag and Drop Functionality
            const pejabatContainer = document.getElementById('pejabatContainer');
            const savePositionBtn = document.getElementById('savePositionBtn');
            const savePositionContainer = document.querySelector('.save-position-container');
            let hasChanges = false;

            if (pejabatContainer) {
                const sortable = Sortable.create(pejabatContainer, {
                    animation: 200,
                    handle: '.drag-handle',
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    onStart: function (evt) {
                        evt.item.classList.add('dragging');
                    },
                    onEnd: function (evt) {
                        evt.item.classList.remove('dragging');
                        hasChanges = true;
                        savePositionContainer.style.display = 'block';
                        updatePositionBadges();
                    }
                });

                // Save positions
                if (savePositionBtn) {
                    savePositionBtn.addEventListener('click', function () {
                        const cards = pejabatContainer.querySelectorAll('.pejabat-card-wrapper');
                        const positions = [];

                        cards.forEach((card, index) => {
                            positions.push({
                                id: card.getAttribute('data-id'),
                                position: index + 1
                            });
                        });

                        // Show loading state
                        savePositionBtn.disabled = true;
                        savePositionBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';

                        // Send AJAX request
                        fetch('{{ route("pejabat.updatePositions") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ positions: positions })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Show success message
                                    showAlert('success', data.message);
                                    savePositionContainer.style.display = 'none';
                                    hasChanges = false;

                                    // Update data-position attributes
                                    cards.forEach((card, index) => {
                                        card.setAttribute('data-position', index + 1);
                                    });
                                } else {
                                    showAlert('error', data.message || 'Terjadi kesalahan');
                                }
                            })
                            .catch(error => {
                                showAlert('error', 'Terjadi kesalahan saat menyimpan posisi');
                                console.error('Error:', error);
                            })
                            .finally(() => {
                                savePositionBtn.disabled = false;
                                savePositionBtn.innerHTML = '<i class="fas fa-save me-2"></i> Simpan Urutan Posisi';
                            });
                    });
                }
            }

            function updatePositionBadges() {
                const cards = document.querySelectorAll('.pejabat-card-wrapper');
                cards.forEach((card, index) => {
                    const badge = card.querySelector('.position-number');
                    if (badge) {
                        badge.textContent = index + 1;
                    }
                });
            }

            function showAlert(type, message) {
                const alertContainer = document.querySelector('.alert-container');
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
                const title = type === 'success' ? 'Sukses!' : 'Error!';

                const alertHTML = `
                        <div class="alert ${alertClass} alert-dismissible fade show shadow-lg animate-slide-down" role="alert">
                            <div class="d-flex align-items-center">
                                <div class="alert-icon-modern flex-shrink-0">
                                    <i class="fas ${iconClass} fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="alert-heading mb-1">${title}</h6>
                                    <p class="mb-0">${message}</p>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    `;

                alertContainer.innerHTML = alertHTML;

                setTimeout(() => {
                    const alert = alertContainer.querySelector('.alert');
                    if (alert) {
                        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                        bsAlert.close();
                    }
                }, 3000);
            }

            // Warn user before leaving if there are unsaved changes
            window.addEventListener('beforeunload', function (e) {
                if (hasChanges) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });

            // Debug: Log all buttons
            console.log('Edit buttons found:', document.querySelectorAll('a.btn-action[href*="edit"]').length);
            console.log('Delete buttons found:', document.querySelectorAll('.delete-pejabat').length);
        });
    </script>
@endsection