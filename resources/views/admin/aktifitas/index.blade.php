@extends('admin.partials.layout')

@section('title', 'Aktivitas User - Sistem Pelatihan')

@section('content')
    
    <!-- Alert Section -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center mb-4" role="alert">
            <div class="alert-icon flex-shrink-0">
                <i class="fas fa-check-circle fa-lg"></i>
            </div>
            <div class="flex-grow-1 ms-3">
                <strong>Sukses!</strong> {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                            <i class="fas fa-list-alt"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Aktivitas</h6>
                            <h3 class="mb-0 fw-bold text-primary">
                                {{ number_format($totalAktivitas) }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Hari Ini</h6>
                            <h3 class="mb-0 fw-bold text-success">
                                {{ number_format($aktivitasHariIni) }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                            <i class="fas fa-calendar-week"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Minggu Ini</h6>
                            <h3 class="mb-0 fw-bold text-info">
                                {{ number_format($aktivitasMingguIni) }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form id="filterForm" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">
                        <i class="fas fa-search me-1"></i> Pencarian
                    </label>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari user atau deskripsi..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted mb-1">
                        <i class="fas fa-calendar-alt me-1"></i> Dari Tanggal
                    </label>
                    <input type="date" 
                           name="date_from" 
                           class="form-control"
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted mb-1">
                        <i class="fas fa-calendar-alt me-1"></i> Sampai Tanggal
                    </label>
                    <input type="date" 
                           name="date_to" 
                           class="form-control"
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted mb-1">
                        <i class="fas fa-list-ol me-1"></i> Tampilkan
                    </label>
                    <select name="per_page" class="form-select">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-filter-primary">
                            <i class="fas fa-filter me-1"></i> Terapkan Filter
                        </button>
                        <a href="{{ route('aktifitas.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Aktivitas Table Card -->
    <div class="card border-0 shadow-lg overflow-hidden">
        <div class="card-header bg-white py-3 border-0">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-history me-2" style="color: #285496;"></i> Daftar Aktivitas
                    </h5>
                    <small class="text-muted" id="tableInfo">
                        Menampilkan {{ $logs->count() }} dari {{ $logs->total() }} aktivitas
                    </small>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="aktifitasTable">
                    <thead>
                        <tr class="table-light">
                            <th width="5%" class="ps-4">No</th>
                            <th width="25%">User</th>
                            <th width="50%">Aktivitas</th>
                            <th width="20%">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                            <tr class="activity-row">
                                <td class="ps-4 fw-semibold">{{ $logs->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-icon me-3"
                                            style="background: linear-gradient(135deg, #28a745 0%, #218838 100%);">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold user-name">{{ $log->user->name ?? 'User Tidak Diketahui' }}</div>
                                            @if($log->user->email ?? false)
                                            <div class="text-muted small">
                                                <i class="fas fa-envelope me-1"></i>
                                                {{ $log->user->email }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="activity-content">
                                        <div class="activity-description">
                                            <i class="fas fa-circle me-2" style="font-size: 6px; color: #285496;"></i>
                                            {!! $log->deskripsi !!}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="time-icon me-2">
                                            <i class="fas fa-clock text-muted"></i>
                                        </div>
                                        <div>
                                            <span class="fw-semibold time-ago" data-bs-toggle="tooltip" 
                                                  title="{{ $log->created_at->translatedFormat('d F Y H:i:s') }}">
                                                {{ $log->humanTime() }}
                                            </span>
                                            <div class="text-muted small">
                                                {{ $log->created_at->translatedFormat('d/m/Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-state-row">
                                <td colspan="4" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mb-3">
                                            <i class="fas fa-history fa-4x" style="color: #e9ecef;"></i>
                                        </div>
                                        <h5 class="text-muted mb-2">Belum ada aktivitas</h5>
                                        <p class="text-muted mb-4">Tidak ada log aktivitas yang ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($logs->count() > 0)
            <div class="card-footer bg-white py-3 border-0">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="table-pagination-info">
                            <small class="text-muted" id="paginationInfo">
                                Menampilkan {{ $logs->firstItem() }}-{{ $logs->lastItem() }} dari {{ $logs->total() }} aktivitas
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Table pagination" class="d-flex justify-content-md-end">
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item {{ $logs->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $logs->previousPageUrl() }}" tabindex="-1">
                                        Previous
                                    </a>
                                </li>

                                @for ($i = 1; $i <= $logs->lastPage(); $i++)
                                    <li class="page-item {{ $logs->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $logs->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                <li class="page-item {{ !$logs->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $logs->nextPageUrl() }}">
                                        Next
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Real-time time update
            function updateTimes() {
                document.querySelectorAll('.time-ago').forEach(element => {
                    const timestamp = element.getAttribute('title');
                    if (timestamp) {
                        const timeDiff = moment(timestamp).fromNow();
                        element.textContent = timeDiff;
                    }
                });
            }

            // Update times every minute
            setInterval(updateTimes, 60000);
            updateTimes(); // Initial update

            // Add hover effects to table rows
            const tableRows = document.querySelectorAll('.activity-row');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateY(-2px)';
                    this.style.transition = 'transform 0.2s ease';
                    this.style.backgroundColor = 'rgba(40, 84, 150, 0.03)';
                });

                row.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateY(0)';
                    this.style.backgroundColor = '';
                });
            });

            // Filter form submission with enter key
            document.querySelectorAll('#filterForm input').forEach(input => {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        document.getElementById('filterForm').submit();
                    }
                });
            });

            // Auto-refresh data every 5 minutes (optional)
            let autoRefreshInterval = setInterval(() => {
                if (document.visibilityState === 'visible') {
                    window.location.reload();
                }
            }, 300000); // 5 minutes

            // Clear interval when page is hidden
            document.addEventListener('visibilitychange', function() {
                if (document.visibilityState === 'hidden') {
                    clearInterval(autoRefreshInterval);
                }
            });

            // Highlight search terms in table
            function highlightSearchTerms() {
                const searchTerm = "{{ request('search') }}";
                if (searchTerm) {
                    const regex = new RegExp(`(${searchTerm})`, 'gi');
                    document.querySelectorAll('.activity-description, .user-name').forEach(element => {
                        const originalText = element.innerHTML;
                        const highlighted = originalText.replace(regex, '<mark>$1</mark>');
                        element.innerHTML = highlighted;
                    });
                }
            }

            highlightSearchTerms();
        });
    </script>

    <style>
        /* User Icon */
        .user-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
            box-shadow: 0 4px 8px rgba(40, 84, 150, 0.2);
        }

        /* Activity Content */
        .activity-content {
            position: relative;
            padding-left: 10px;
        }

        .activity-description {
            line-height: 1.6;
            color: #495057;
        }

        /* Timeline effect */
        .activity-row:not(:last-child) .activity-content:before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            bottom: -50%;
            width: 2px;
            background: linear-gradient(to bottom, #dee2e6, transparent);
            transform: translateX(-50%);
        }

        /* Table styling specific for activity */
        .table th {
            background-color: #f8fafc;
            border-bottom: 2px solid var(--primary-light);
        }

        .table td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
        }

        /* Time styling */
        .time-ago {
            color: #285496;
            font-weight: 500;
            cursor: help;
        }

        /* Highlight search terms */
        mark {
            background-color: #fff3cd;
            padding: 0 2px;
            border-radius: 3px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .activity-row {
                border-bottom: 2px solid #f8f9fa;
            }
            
            .activity-row .activity-content:before {
                display: none;
            }
        }

        /* Reuse existing styles from mentor page */
        .stat-card {
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .btn-filter-primary {
            background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-filter-primary:hover {
            background: linear-gradient(135deg, #1e3d6f 0%, #2d5499 100%);
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 8px 25px rgba(40, 84, 150, 0.4);
        }

        .empty-state {
            padding: 3rem 1rem;
        }

        .empty-state-icon {
            color: #e9ecef;
        }
    </style>
@endsection