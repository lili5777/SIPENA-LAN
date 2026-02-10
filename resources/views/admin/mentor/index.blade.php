@extends('admin.partials.layout')

@section('title', 'Master Mentor - Sistem Pelatihan')

@section('content')
    <!-- Page Header -->
    <div class="page-header bg-gradient-primary rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-chalkboard-teacher fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">Mentor</h1>
                        <p class="text-white-50 mb-0">Kelola data mentor pelatihan</p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('mentor.create') }}" class="btn btn-light btn-hover-lift shadow-sm d-flex align-items-center">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Mentor
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Section -->
    <div class="alert-container mb-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
                <div class="alert-icon flex-shrink-0">
                    <i class="fas fa-check-circle fa-lg"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <strong>Sukses!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
                <div class="alert-icon flex-shrink-0">
                    <i class="fas fa-exclamation-circle fa-lg"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <strong>Error!</strong> {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Mentor</h6>
                            <h3 class="mb-0 fw-bold text-primary">
                                {{ $totalMentor }}
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
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Aktif</h6>
                            <h3 class="mb-0 fw-bold text-success">
                                {{ $aktifMentor }}
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
                        <div class="stat-icon bg-secondary bg-opacity-10 text-secondary me-3">
                            <i class="fas fa-user-times"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Non-Aktif</h6>
                            <h3 class="mb-0 fw-bold text-secondary">
                                {{ $nonaktifMentor }}
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
            <form id="filterForm" method="GET" action="{{ route('mentor.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted mb-1">
                            <i class="fas fa-filter me-1"></i> Filter Status
                        </label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Nonaktif" {{ request('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted mb-1">
                            <i class="fas fa-sort me-1"></i> Urutkan
                        </label>
                        <select name="sort" class="form-select">
                            <option value="nama" {{ request('sort') == 'nama' ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="peserta" {{ request('sort') == 'peserta' ? 'selected' : '' }}>Jumlah Peserta</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted mb-1">
                            <i class="fas fa-search me-1"></i> Cari Mentor
                        </label>
                        <input type="text" name="search" class="form-control" 
                            placeholder="Nama, NIP, Email..." 
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted mb-1">
                            <i class="fas fa-list-ol me-1"></i> Per Halaman
                        </label>
                        <select name="per_page" class="form-select">
                            <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                            <option value="-1" {{ request('per_page') == '-1' ? 'selected' : '' }}>Semua</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-filter-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                @if(request()->hasAny(['status', 'sort', 'search', 'per_page']))
                    <div class="row mt-2">
                        <div class="col-12">
                            <a href="{{ route('mentor.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Reset Filter
                            </a>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>


    <!-- Mentor Table Card -->
    <div class="card border-0 shadow-lg overflow-hidden">
        <div class="card-header bg-white py-3 border-0">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-list me-2" style="color: #285496;"></i> Daftar Mentor
                    </h5>
                    <small class="text-muted">
                        Menampilkan {{ $mentor->firstItem() ?? 0 }} - {{ $mentor->lastItem() ?? 0 }} dari {{ $mentor->total() }} mentor
                    </small>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="mentorTable">
                    <thead>
                        <tr class="table-light">
                            <th width="5%" class="ps-4">No</th>
                            <th width="20%">Nama Mentor</th>
                            <th width="15%">NIP</th>
                            <th width="15%">Jabatan</th>
                            <th width="15%">Kontak</th>
                            <th width="15%">Informasi Keuangan</th>
                            <th width="10%">Jumlah Peserta</th>
                            {{-- <th width="10%">Status</th> --}}
                            <th width="10%" class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                   <tbody>
                        @forelse($mentor as $index => $item)
                            @php
                                // Mapping status untuk ikon dan warna
                                $statusConfig = [
                                    'Aktif' => [
                                        'color' => 'status-success',
                                        'icon' => 'fa-user-check',
                                        'text' => 'Aktif'
                                    ],
                                    'Nonaktif' => [
                                        'color' => 'status-secondary',
                                        'icon' => 'fa-user-times',
                                        'text' => 'Nonaktif'
                                    ]
                                ];

                                $currentStatus = $item->status_aktif ? 'Aktif' : 'Nonaktif';
                                $statusData = $statusConfig[$currentStatus] ?? [
                                    'color' => 'status-secondary',
                                    'icon' => 'fa-question-circle',
                                    'text' => $currentStatus
                                ];

                                // Hitung jumlah peserta
                                $jumlahPeserta = $item->peserta_mentor_count;
                            @endphp
                            <tr class="mentor-row" data-mentor-id="{{ $item->id }}">
                                <td class="ps-4 fw-semibold">
                                    {{ ($mentor->currentPage() - 1) * $mentor->perPage() + $loop->iteration }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mentor-icon me-3"
                                            style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
                                            <i class="fas fa-chalkboard-teacher"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold mentor-name">{{ $item->nama_mentor }}</div>
                                            <div class="text-muted small">
                                                <i class="fas fa-clock me-1"></i>
                                                Dibuat: {{ $item->dibuat_pada ? \Carbon\Carbon::parse($item->dibuat_pada)->format('d/m/Y H:i') : '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0 fw-semibold">
                                        <i class="fas fa-id-badge me-1"></i>
                                        {{ $item->nip_mentor ?? '-' }}
                                    </p>
                                </td>
                                <td>
                                    <p class="mb-0 fw-semibold mentor-jabatan">
                                        <i class="fas fa-briefcase me-1"></i>
                                        {{ $item->jabatan_mentor ?? '-' }}
                                    </p>
                                </td>
                                <td>
                                    <div class="mb-1">
                                        @if($item->email_mentor)
                                            <i class="fas fa-envelope me-1 text-muted"></i>
                                            <small>{{ $item->email_mentor }}</small>
                                        @endif
                                    </div>
                                    <div>
                                        @if($item->nomor_hp_mentor)
                                            <i class="fas fa-phone me-1 text-muted"></i>
                                            <small>{{ $item->nomor_hp_mentor }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="mb-1">
                                        @if($item->nomor_rekening)
                                            <i class="fas fa-credit-card me-1 text-muted"></i>
                                            <small>{{ $item->nomor_rekening }}</small>
                                        @endif
                                    </div>
                                    <div>
                                        @if($item->npwp_mentor)
                                            <i class="fas fa-id-card me-1 text-muted"></i>
                                            <small>{{ $item->npwp_mentor }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($jumlahPeserta > 0)
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2" 
                                            data-bs-toggle="tooltip" 
                                            title="{{ $jumlahPeserta }} peserta terdaftar">
                                            <i class="fas fa-users me-1"></i>
                                            {{ $jumlahPeserta }} Peserta
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                            <i class="fas fa-users me-1"></i>
                                            Belum ada
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('mentor.edit', $item->id) }}" 
                                        class="btn btn-sm btn-outline-warning btn-action"
                                        data-bs-toggle="tooltip" title="Edit Mentor">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-action delete-mentor"
                                            data-id="{{ $item->id }}" 
                                            data-name="{{ $item->nama_mentor }}"
                                            data-peserta="{{ $jumlahPeserta }}"
                                            data-bs-toggle="tooltip" title="Hapus Mentor">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-state-row">
                                <td colspan="8" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mb-3">
                                            <i class="fas fa-chalkboard-teacher fa-4x" style="color: #e9ecef;"></i>
                                        </div>
                                        <h5 class="text-muted mb-2">
                                            @if(request()->hasAny(['status', 'search']))
                                                Tidak ada hasil yang ditemukan
                                            @else
                                                Belum ada mentor
                                            @endif
                                        </h5>
                                        <p class="text-muted mb-4">
                                            @if(request()->hasAny(['status', 'search']))
                                                Coba ubah filter atau kata kunci pencarian
                                            @else
                                                Tidak ada mentor yang terdaftar
                                            @endif
                                        </p>
                                        @if(!request()->hasAny(['status', 'search']))
                                            <a href="{{ route('mentor.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i> Tambah Mentor
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer Pagination - Selalu tampilkan -->
        @if($mentor->count() > 0)
            <div class="card-footer bg-white py-3 border-0">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="table-pagination-info">
                            <small class="text-muted">
                                Menampilkan {{ $mentor->firstItem() }} - {{ $mentor->lastItem() }} dari {{ $mentor->total() }} mentor
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <nav class="d-flex justify-content-md-end">
                            @if($mentor->hasPages())
                                {{ $mentor->links('pagination::bootstrap-5') }}
                            @else
                                <!-- Pagination untuk 1 halaman -->
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                                    </li>
                                    <li class="page-item active">
                                        <span class="page-link">1</span>
                                    </li>
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                                    </li>
                                </ul>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center px-4 pb-4">
                    <div class="delete-icon mb-3">
                        <i class="fas fa-exclamation-triangle fa-4x" style="color: #ff4757;"></i>
                    </div>
                    <h4 class="modal-title mb-3 fw-bold" id="deleteModalLabel">Konfirmasi Hapus</h4>
                    <p class="text-muted mb-1">Anda akan menghapus mentor:</p>
                    <h5 class="text-danger mb-4 fw-bold" id="deleteMentorName"></h5>

                    <p class="text-muted small mb-4" id="confirmMessage">
                        <i class="fas fa-info-circle me-1"></i>
                        Tindakan ini tidak dapat dibatalkan. Semua data mentor akan dihapus.
                    </p>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Batal
                    </button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4 btn-lift" id="deleteButton">
                            <i class="fas fa-trash-alt me-2"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-submit form when changing filters
    document.querySelectorAll('#filterForm select').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });

    // Delete Modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const deleteForm = document.getElementById('deleteForm');
    const deleteMentorName = document.getElementById('deleteMentorName');
    const confirmMessage = document.getElementById('confirmMessage');
    const deleteButton = document.getElementById('deleteButton');

    document.querySelectorAll('.delete-mentor').forEach(button => {
        button.addEventListener('click', function () {
            const mentorId = this.getAttribute('data-id');
            const mentorName = this.getAttribute('data-name');
            const jumlahPeserta = parseInt(this.getAttribute('data-peserta'));

            deleteMentorName.textContent = mentorName;
            deleteForm.action = `/mentor/${mentorId}`;
            
            if (jumlahPeserta > 0) {
                confirmMessage.innerHTML = `
                    <i class="fas fa-exclamation-triangle me-1 text-warning"></i>
                    Mentor ini memiliki ${jumlahPeserta} peserta. 
                    <strong class="text-danger">Tidak dapat dihapus</strong> karena masih terhubung dengan peserta.
                    <br><br>
                    <i class="fas fa-info-circle me-1"></i>
                    Hapus semua relasi peserta terlebih dahulu sebelum menghapus mentor.
                `;
                deleteButton.disabled = true;
                deleteButton.classList.remove('btn-danger');
                deleteButton.classList.add('btn-secondary');
                deleteButton.innerHTML = '<i class="fas fa-ban me-2"></i> Tidak Dapat Dihapus';
            } else {
                confirmMessage.innerHTML = `
                    <i class="fas fa-info-circle me-1"></i>
                    Tindakan ini tidak dapat dibatalkan. Semua data mentor akan dihapus.
                `;
                deleteButton.disabled = false;
                deleteButton.classList.remove('btn-secondary');
                deleteButton.classList.add('btn-danger');
                deleteButton.innerHTML = '<i class="fas fa-trash-alt me-2"></i> Hapus';
            }
            
            deleteModal.show();
        });
    });

    // Reset modal saat ditutup
    document.getElementById('deleteModal').addEventListener('hidden.bs.modal', function () {
        confirmMessage.innerHTML = `
            <i class="fas fa-info-circle me-1"></i>
            Tindakan ini tidak dapat dibatalkan. Semua data mentor akan dihapus.
        `;
        deleteButton.disabled = false;
        deleteButton.classList.remove('btn-secondary');
        deleteButton.classList.add('btn-danger');
        deleteButton.innerHTML = '<i class="fas fa-trash-alt me-2"></i> Hapus';
    });

    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.classList.contains('show')) {
                bootstrap.Alert.getOrCreateInstance(alert).close();
            }
        }, 5000);
    });
});
</script>

<style>
    /* CUSTOM STATUS BADGES */
    .custom-badge {
        border-radius: 8px;
        font-weight: 500;
        letter-spacing: 0.3px;
        padding: 0.5rem 0.75rem;
        border: none;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .custom-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .status-success {
        background: linear-gradient(135deg, #28a745 0%, #218838 100%) !important;
        color: white !important;
    }

    .status-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%) !important;
        color: white !important;
    }

    /* Page Header */
    .page-header {
        padding: 2rem;
        margin-bottom: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(40, 84, 150, 0.15);
    }

    .icon-wrapper {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Stats Cards */
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

    /* Mentor Icon */
    .mentor-icon {
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

    /* Action Buttons */
    .btn-action {
        border-radius: 8px;
        padding: 0.375rem 0.75rem;
        margin: 0 2px;
        transition: all 0.2s ease;
        border-width: 2px;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-lift {
        transition: transform 0.2s ease;
    }

    .btn-lift:hover {
        transform: translateY(-2px);
    }

    /* Table Styling */
    .table th {
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #285496;
        background-color: #f8fafc;
        padding: 1rem;
    }

    .table td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
    }

    .mentor-row:hover {
        background-color: rgba(40, 84, 150, 0.03) !important;
    }

    /* Pagination Styling */
    .pagination {
        gap: 4px;
    }

    .pagination .page-link {
        color: #4A5568;
        border: 1px solid #E2E8F0;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-weight: 500;
        min-width: 40px;
        text-align: center;
        transition: all 0.2s ease;
    }

    .pagination .page-link:hover {
        background-color: #EDF2F7;
        border-color: #CBD5E0;
        color: #2D3748;
    }

    .pagination .page-item.active .page-link {
        background-color: #4C51BF;
        border-color: #4C51BF;
        color: white;
        font-weight: 600;
    }

    .pagination .page-item.disabled .page-link {
        color: #A0AEC0;
        background-color: #F7FAFC;
        border-color: #E2E8F0;
    }
</style>
@endsection