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
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('mentor.create') }}" class="btn btn-light btn-hover-lift shadow-sm d-flex align-items-center">
                        <i class="fas fa-plus me-2"></i>
                        Tambah Mentor
                    </a>
                    <button type="button" id="btnPreviewDuplikat" 
                        class="btn btn-warning btn-hover-lift shadow-sm d-flex align-items-center"
                        data-bs-toggle="tooltip" title="Temukan dan hapus mentor yang terdaftar ganda">
                        <i class="fas fa-broom me-2"></i>
                        Rapikan Duplikat
                    </button>
                </div>
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
                            <th width="13%">Golongan & Pangkat</th>
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
                                    @if($item->golongan || $item->pangkat)
                                        <div class="mb-1">
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1">
                                                <i class="fas fa-layer-group me-1"></i>
                                                {{ $item->golongan ?? '-' }}
                                            </span>
                                        </div>
                                        <div>
                                            <small class="text-muted">
                                                <i class="fas fa-medal me-1"></i>
                                                {{ $item->pangkat ?? '-' }}
                                            </small>
                                        </div>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
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
                                        <button type="button" 
                                            class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 border-0 view-peserta" 
                                            data-mentor-id="{{ $item->id }}"
                                            data-mentor-name="{{ $item->nama_mentor }}"
                                            data-bs-toggle="tooltip" 
                                            title="Klik untuk melihat detail peserta"
                                            style="cursor: pointer; transition: all 0.3s ease;">
                                            <i class="fas fa-users me-1"></i>
                                            {{ $jumlahPeserta }} Peserta
                                        </button>
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
                                <td colspan="9" class="text-center py-5">
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

    <!-- Detail Peserta Modal -->
    <div class="modal fade" id="pesertaModal" tabindex="-1" aria-labelledby="pesertaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-gradient-primary text-white border-0" 
                    style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
                    <div>
                        <h5 class="modal-title fw-bold mb-1" id="pesertaModalLabel">
                            <i class="fas fa-users me-2"></i>
                            Detail Peserta Mentor
                        </h5>
                        <p class="mb-0 small opacity-75" id="mentorInfo"></p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <!-- Loading State -->
                    <div id="loadingPeserta" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-3">Memuat data peserta...</p>
                    </div>

                    <!-- Error State -->
                    <div id="errorPeserta" class="alert alert-danger m-4" style="display: none;">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <span id="errorMessage"></span>
                    </div>

                    <!-- Table Container -->
                    <div id="pesertaTableContainer" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="pesertaTable">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th width="5%" class="ps-4">No</th>
                                        <th width="20%">Nama Peserta</th>
                                        <th width="15%">NIP/NRP</th>
                                        <th width="20%">Instansi</th>
                                        <th width="15%">Kontak</th>
                                        <th width="15%">Angkatan</th>
                                    </tr>
                                </thead>
                                <tbody id="pesertaTableBody">
                                    <!-- Data will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div id="emptyPeserta" class="text-center py-5" style="display: none;">
                        <div class="empty-state-icon mb-3">
                            <i class="fas fa-user-slash fa-4x" style="color: #e9ecef;"></i>
                        </div>
                        <h5 class="text-muted mb-2">Belum ada peserta</h5>
                        <p class="text-muted">Mentor ini belum memiliki peserta yang terdaftar</p>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <div class="w-100 d-flex justify-content-between align-items-center">
                        <span class="text-muted small">
                            <i class="fas fa-info-circle me-1"></i>
                            Total: <strong id="totalPeserta">0</strong> peserta
                        </span>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
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

    <!-- Cleanup Duplikat Modal -->
<div class="modal fade" id="cleanupModal" tabindex="-1" aria-labelledby="cleanupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 text-white"
                style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
                <div>
                    <h5 class="modal-title fw-bold mb-1" id="cleanupModalLabel">
                        <i class="fas fa-broom me-2"></i>
                        Rapikan Mentor Duplikat
                    </h5>
                    <p class="mb-0 small opacity-75">Preview data sebelum pembersihan dilakukan</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0">

                <!-- Loading -->
                <div id="cleanupLoading" class="text-center py-5">
                    <div class="spinner-border text-warning" role="status"></div>
                    <p class="text-muted mt-3">Memindai data mentor duplikat...</p>
                </div>

                <!-- Error -->
                <div id="cleanupError" class="alert alert-danger m-4" style="display:none;"></div>

                <!-- Tidak ada duplikat -->
                <div id="cleanupEmpty" class="text-center py-5" style="display:none;">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h5 class="text-muted">Data mentor sudah bersih!</h5>
                    <p class="text-muted">Tidak ditemukan mentor dengan data duplikat.</p>
                </div>

                <!-- Ada duplikat -->
                <div id="cleanupContent" style="display:none;">
                    <!-- Summary Banner -->
                    <div class="alert alert-warning m-4 mb-0 d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fa-lg me-3 flex-shrink-0"></i>
                        <div>
                            Ditemukan <strong id="totalDuplikat">0</strong> kelompok mentor duplikat.
                            Sistem akan <strong>mempertahankan mentor dengan peserta terbanyak</strong> dan 
                            <strong>memindahkan semua peserta</strong> dari mentor duplikat ke mentor yang dipertahankan, 
                            kemudian mentor duplikat akan <strong class="text-danger">dihapus permanen</strong>. 
                            Jika jumlah peserta sama, mentor dengan <strong>ID terkecil</strong> yang dipertahankan.
                        </div>
                    </div>

                    <!-- List Duplikat -->
                    <div id="duplikatList" class="p-4"></div>
                </div>
            </div>

            <div class="modal-footer border-0 bg-light">
                <div class="w-100 d-flex justify-content-between align-items-center">
                    <span class="text-muted small" id="cleanupFooterInfo"></span>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Batal
                        </button>
                        <button type="button" id="btnExecCleanup" class="btn btn-danger" style="display:none;">
                            <i class="fas fa-broom me-2"></i>
                            Bersihkan Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cleanup Result Modal -->
<div class="modal fade" id="cleanupResultModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 bg-success text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-check-circle me-2"></i> Pembersihan Selesai
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h5 id="resultMessage" class="fw-bold mb-3"></h5>
                <div id="resultDetail" class="text-muted small"></div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-primary" onclick="location.reload()">
                    <i class="fas fa-sync me-2"></i>Muat Ulang Halaman
                </button>
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

    // ============================================
    // 🧹 CLEANUP DUPLIKAT HANDLER
    // ============================================
    const cleanupModal = new bootstrap.Modal(document.getElementById('cleanupModal'));
    const cleanupResultModal = new bootstrap.Modal(document.getElementById('cleanupResultModal'));

    document.getElementById('btnPreviewDuplikat').addEventListener('click', function () {
        // Reset state
        document.getElementById('cleanupLoading').style.display = 'block';
        document.getElementById('cleanupError').style.display = 'none';
        document.getElementById('cleanupEmpty').style.display = 'none';
        document.getElementById('cleanupContent').style.display = 'none';
        document.getElementById('btnExecCleanup').style.display = 'none';
        document.getElementById('cleanupFooterInfo').textContent = '';

        cleanupModal.show();

        fetch('{{ route("mentor.previewDuplicates") }}')
            .then(r => r.json())
            .then(data => {
                document.getElementById('cleanupLoading').style.display = 'none';

                if (!data.success) {
                    document.getElementById('cleanupError').style.display = 'block';
                    document.getElementById('cleanupError').textContent = data.message;
                    return;
                }

                if (data.total_duplikat === 0) {
                    document.getElementById('cleanupEmpty').style.display = 'block';
                    return;
                }

                // Ada duplikat — render
                document.getElementById('totalDuplikat').textContent = data.total_duplikat;
                document.getElementById('cleanupContent').style.display = 'block';
                document.getElementById('btnExecCleanup').style.display = 'inline-flex';
                document.getElementById('cleanupFooterInfo').innerHTML =
                    `<i class="fas fa-info-circle me-1 text-warning"></i> 
                    ${data.total_duplikat} kelompok duplikat ditemukan`;

                const list = document.getElementById('duplikatList');
                list.innerHTML = '';

                data.duplicates.forEach((dup, idx) => {
                    // ── Baris peserta yang akan dipindahkan ──
                    const pesertaRows = dup.peserta_dipindah.length > 0
                        ? dup.peserta_dipindah.map((p, pi) => `
                            <tr>
                                <td class="text-center fw-semibold text-muted">${pi + 1}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width:30px;height:30px;font-size:0.75rem;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span class="fw-semibold">${p.nama_lengkap}</span>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border"><i class="fas fa-id-badge me-1 text-muted"></i>${p.nip_nrp ?? '-'}</span></td>
                                <td><span class="badge bg-info bg-opacity-10 text-info"><i class="fas fa-graduation-cap me-1"></i>${p.nama_angkatan}</span></td>
                                <td class="text-center">
                                    <span class="text-danger me-1"><i class="fas fa-arrow-right"></i></span>
                                    <span class="fw-semibold text-success">${dup.keep.nama}</span>
                                </td>
                            </tr>`
                        ).join('')
                        : `<tr><td colspan="5" class="text-center text-muted py-3">
                                <i class="fas fa-info-circle me-1"></i>Tidak ada peserta yang perlu dipindahkan
                        </td></tr>`;

                    // ── Card mentor yang DIHAPUS ──
                    const removeCards = dup.remove.map(r => `
                        <div class="d-flex align-items-start gap-3 p-3 rounded mb-2"
                            style="background:rgba(220,53,69,0.06); border:1px solid rgba(220,53,69,0.2);">
                            <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:40px;height:40px;">
                                <i class="fas fa-trash-alt"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-danger">${r.nama}</div>
                                <div class="small text-muted mt-1">
                                    <span class="me-3"><i class="fas fa-id-badge me-1"></i>${r.nip && r.nip !== '-' ? r.nip : 'NIP tidak ada'}</span>
                                    <span><i class="fas fa-briefcase me-1"></i>${r.jabatan ?? '-'}</span>
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">
                                        <i class="fas fa-users me-1"></i>${r.total_peserta} peserta dimiliki
                                    </span>
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 ms-1">
                                        <i class="fas fa-arrow-right me-1"></i>Semua peserta dipindah ke: ${dup.keep.nama}
                                    </span>
                                </div>
                            </div>
                        </div>`
                    ).join('');

                    // ── Render card utama duplikat ──
                    list.innerHTML += `
                    <div class="card mb-4 shadow-sm" style="border:2px solid #f39c12; border-radius:12px; overflow:hidden;">

                        {{-- === HEADER === --}}
                        <div class="card-header d-flex justify-content-between align-items-center py-3"
                            style="background:linear-gradient(135deg,#fff8e1,#fff3cd); border-bottom:2px solid #f39c12;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-warning fw-bold"
                                    style="width:32px;height:32px;background:rgba(243,156,18,0.15);font-size:0.9rem;">${idx + 1}</div>
                                <div>
                                    <span class="fw-bold text-dark">${dup.label}</span>
                                    <span class="badge ms-2 ${dup.type === 'nip' ? 'bg-primary' : 'bg-secondary'} bg-opacity-75">
                                        <i class="fas fa-${dup.type === 'nip' ? 'fingerprint' : 'font'} me-1"></i>
                                        ${dup.type === 'nip' ? 'Duplikat NIP' : 'Duplikat Nama'}
                                    </span>
                                </div>
                            </div>
                            <div class="text-muted small">
                                <i class="fas fa-copy me-1"></i>
                                ${1 + dup.remove.length} entri ditemukan
                            </div>
                        </div>

                        <div class="card-body p-0">

                            {{-- === BAGIAN 1: MENTOR DIPERTAHANKAN === --}}
                            <div class="p-3" style="background:#f0fdf4; border-bottom:1px solid #d1fae5;">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fas fa-shield-alt text-success"></i>
                                    <span class="fw-bold text-success text-uppercase" style="font-size:0.78rem;letter-spacing:0.05em;">
                                        Mentor Dipertahankan
                                    </span>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 ms-auto">
                                        Peserta terbanyak → diprioritaskan
                                    </span>
                                </div>
                                <div class="d-flex align-items-start gap-3">
                                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width:48px;height:48px;font-size:1.2rem;">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold fs-6">${dup.keep.nama}</div>
                                        <div class="text-muted small mt-1 d-flex flex-wrap gap-3">
                                            <span><i class="fas fa-id-badge me-1 text-primary"></i><strong>ID:</strong> ${dup.keep.id}</span>
                                            <span><i class="fas fa-hashtag me-1 text-primary"></i><strong>NIP:</strong> ${dup.keep.nip && dup.keep.nip !== '-' ? dup.keep.nip : '<em>tidak ada</em>'}</span>
                                            <span><i class="fas fa-briefcase me-1 text-primary"></i><strong>Jabatan:</strong> ${dup.keep.jabatan ?? '-'}</span>
                                        </div>
                                        <div class="mt-2 d-flex gap-2 flex-wrap">
                                            <span class="badge bg-success px-3 py-2">
                                                <i class="fas fa-users me-1"></i>
                                                ${dup.keep.total_peserta} peserta saat ini
                                            </span>
                                            ${dup.peserta_dipindah.length > 0 ? `
                                            <span class="badge bg-primary px-3 py-2">
                                                <i class="fas fa-plus me-1"></i>
                                                +${dup.peserta_dipindah.length} peserta akan ditambahkan
                                            </span>
                                            <span class="badge bg-dark px-3 py-2">
                                                <i class="fas fa-layer-group me-1"></i>
                                                Total akhir: ${parseInt(dup.keep.total_peserta) + dup.peserta_dipindah.length} peserta
                                            </span>` : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- === BAGIAN 2: MENTOR DIHAPUS === --}}
                            <div class="p-3" style="background:#fff5f5; border-bottom:1px solid #fed7d7;">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fas fa-trash-alt text-danger"></i>
                                    <span class="fw-bold text-danger text-uppercase" style="font-size:0.78rem;letter-spacing:0.05em;">
                                        Mentor Akan Dihapus (${dup.remove.length} entri)
                                    </span>
                                </div>
                                ${removeCards}
                            </div>

                            {{-- === BAGIAN 3: TABEL PESERTA DIPINDAHKAN === --}}
                            <div class="p-3">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="fas fa-exchange-alt text-primary"></i>
                                    <span class="fw-bold text-primary text-uppercase" style="font-size:0.78rem;letter-spacing:0.05em;">
                                        Daftar Peserta yang Dipindahkan
                                    </span>
                                    <span class="badge bg-primary ms-1">${dup.peserta_dipindah.length} peserta</span>
                                    ${dup.peserta_dipindah.length > 0 ? `
                                    <span class="ms-auto small text-muted">
                                        <i class="fas fa-arrow-right me-1 text-danger"></i>semua dipindah ke
                                        <strong class="text-success">${dup.keep.nama}</strong>
                                    </span>` : ''}
                                </div>

                                ${dup.peserta_dipindah.length > 0 ? `
                                <div class="table-responsive rounded" style="max-height:220px;overflow-y:auto;border:1px solid #e2e8f0;">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead style="position:sticky;top:0;background:#f8fafc;z-index:5;">
                                            <tr>
                                                <th class="text-center ps-3" width="5%">No</th>
                                                <th width="28%">Nama Peserta</th>
                                                <th width="18%">NIP/NRP</th>
                                                <th width="18%">Angkatan</th>
                                                <th width="31%">Dipindah Ke</th>
                                            </tr>
                                        </thead>
                                        <tbody>${pesertaRows}</tbody>
                                    </table>
                                </div>` : `
                                <div class="text-center py-4 text-muted rounded" style="background:#f8fafc;border:1px dashed #cbd5e0;">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2 d-block"></i>
                                    Tidak ada peserta yang perlu dipindahkan dari mentor duplikat ini
                                </div>`}
                            </div>

                            {{-- === BAGIAN 4: RINGKASAN AKSI === --}}
                            <div class="px-3 pb-3">
                                <div class="rounded p-3 d-flex flex-wrap gap-3 align-items-center"
                                    style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border:1px solid #bfdbfe;">
                                    <i class="fas fa-info-circle text-primary fa-lg"></i>
                                    <div class="small">
                                        <strong>Yang akan terjadi:</strong>
                                        <ol class="mb-0 mt-1 ps-3">
                                            <li>Mentor <strong class="text-danger">${dup.remove.map(r => r.nama).join(', ')}</strong> akan <strong class="text-danger">dihapus</strong></li>
                                            ${dup.peserta_dipindah.length > 0
                                                ? `<li><strong>${dup.peserta_dipindah.length} peserta</strong> dipindahkan ke <strong class="text-success">${dup.keep.nama}</strong></li>`
                                                : '<li>Tidak ada peserta yang perlu dipindahkan</li>'}
                                            <li>Data yang kosong pada <strong class="text-success">${dup.keep.nama}</strong> akan dilengkapi dari data duplikat</li>
                                            <li>Mentor <strong class="text-success">${dup.keep.nama}</strong> akan memiliki <strong>${parseInt(dup.keep.total_peserta) + dup.peserta_dipindah.length} peserta</strong> setelah cleanup</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>`;
                });
            })
            .catch(err => {
                document.getElementById('cleanupLoading').style.display = 'none';
                document.getElementById('cleanupError').style.display = 'block';
                document.getElementById('cleanupError').textContent = 'Gagal memuat data. Silakan coba lagi.';
            });
    });

    // Eksekusi pembersihan
    document.getElementById('btnExecCleanup').addEventListener('click', function () {
        if (!confirm('Anda yakin ingin membersihkan data mentor duplikat? Tindakan ini tidak dapat dibatalkan.')) return;

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Membersihkan...';

        fetch('{{ route("mentor.cleanupDuplicates") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(r => r.json())
        .then(data => {
            cleanupModal.hide();

            if (data.success) {
                document.getElementById('resultMessage').textContent = data.message;
                document.getElementById('resultDetail').innerHTML =
                    `<div class="row g-3 justify-content-center mt-2">
                        <div class="col-auto">
                            <div class="text-center p-3 bg-danger bg-opacity-10 rounded">
                                <div class="fs-3 fw-bold text-danger">${data.total_hapus}</div>
                                <div class="small">Mentor Dihapus</div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                                <div class="fs-3 fw-bold text-primary">${data.total_pindah}</div>
                                <div class="small">Peserta Dipindah</div>
                            </div>
                        </div>
                    </div>`;
                cleanupResultModal.show();
            } else {
                alert('Gagal: ' + data.message);
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-broom me-2"></i> Bersihkan Sekarang';
            }
        })
        .catch(() => {
            alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-broom me-2"></i> Bersihkan Sekarang';
        });
    });

    // ============================================
    // 🆕 PESERTA MODAL HANDLER
    // ============================================
    const pesertaModal = new bootstrap.Modal(document.getElementById('pesertaModal'));
    
    document.querySelectorAll('.view-peserta').forEach(button => {
        button.addEventListener('click', function() {
            const mentorId = this.getAttribute('data-mentor-id');
            const mentorName = this.getAttribute('data-mentor-name');
            
            // Reset modal state
            document.getElementById('loadingPeserta').style.display = 'block';
            document.getElementById('errorPeserta').style.display = 'none';
            document.getElementById('pesertaTableContainer').style.display = 'none';
            document.getElementById('emptyPeserta').style.display = 'none';
            
            // Show modal
            pesertaModal.show();
            
            // Fetch data
            fetch(`/mentor/${mentorId}/peserta`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loadingPeserta').style.display = 'none';
                    
                    if (data.success) {
                        // Update modal header info
                        document.getElementById('mentorInfo').innerHTML = `
                            <i class="fas fa-user-tie me-1"></i> ${data.mentor.nama}
                            ${data.mentor.nip ? ' | NIP: ' + data.mentor.nip : ''}
                            ${data.mentor.jabatan ? ' | ' + data.mentor.jabatan : ''}
                        `;
                        
                        document.getElementById('totalPeserta').textContent = data.total;
                        
                        if (data.peserta && data.peserta.length > 0) {
                            // Populate table
                            const tbody = document.getElementById('pesertaTableBody');
                            tbody.innerHTML = '';
                            
                            data.peserta.forEach((peserta, index) => {
                                const row = `
                                    <tr>
                                        <td class="ps-4 fw-semibold">${index + 1}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-2"
                                                    style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">${peserta.nama}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                <i class="fas fa-id-badge me-1"></i>
                                                ${peserta.nip}
                                            </span>
                                        </td>
                                        <td>
                                            <i class="fas fa-building me-1 text-muted"></i>
                                            <small>${peserta.instansi}</small>
                                        </td>
                                        <td>
                                            <div class="small">
                                                ${peserta.email ? `<div class="mb-1"><i class="fas fa-envelope me-1 text-muted"></i>${peserta.email}</div>` : ''}
                                                ${peserta.nomor_hp ? `<div><i class="fas fa-phone me-1 text-muted"></i>${peserta.nomor_hp}</div>` : ''}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                <i class="fas fa-graduation-cap me-1"></i>
                                                ${peserta.angkatan} - ${peserta.tahun}
                                            </span>
                                        </td>
                                    </tr>
                                `;
                                tbody.innerHTML += row;
                            });
                            
                            document.getElementById('pesertaTableContainer').style.display = 'block';
                        } else {
                            document.getElementById('emptyPeserta').style.display = 'block';
                        }
                    } else {
                        document.getElementById('errorPeserta').style.display = 'block';
                        document.getElementById('errorMessage').textContent = data.message || 'Terjadi kesalahan saat memuat data';
                    }
                })
                .catch(error => {
                    document.getElementById('loadingPeserta').style.display = 'none';
                    document.getElementById('errorPeserta').style.display = 'block';
                    document.getElementById('errorMessage').textContent = 'Gagal memuat data peserta. Silakan coba lagi.';
                    console.error('Error:', error);
                });
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

    .view-peserta:hover {
        background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%) !important;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 84, 150, 0.3);
    }

    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #f8f9fa;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);
    }
</style>
@endsection