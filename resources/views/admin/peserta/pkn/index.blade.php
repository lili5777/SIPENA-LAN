@extends('admin.partials.layout')

@section('title', 'Peserta PKN TK II - Sistem Pelatihan')

@section('content')
    <!-- Page Header -->
    <div class="page-header bg-gradient-primary rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-users fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">Peserta {{ $jenisPelatihan->nama_pelatihan ?? 'PKN TK II' }}</h1>
                        <p class="text-white-50 mb-0">Kelola peserta pelatihan PKN TK II</p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a href="#" class="btn btn-light btn-hover-lift shadow-sm d-flex align-items-center" data-bs-toggle="modal"
                    data-bs-target="#addPesertaModal">
                    <i class="fas fa-user-plus me-2"></i>
                    Tambah Peserta
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
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Pending</h6>
                            <h3 class="mb-0 fw-bold text-warning">
                                {{ $pendaftaran->where('status_pendaftaran', 'pending')->count() }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Diterima</h6>
                            <h3 class="mb-0 fw-bold text-info">
                                {{ $pendaftaran->where('status_pendaftaran', 'diterima')->count() }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-danger bg-opacity-10 text-danger me-3">
                            <i class="fas fa-user-times"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Ditolak</h6>
                            <h3 class="mb-0 fw-bold text-danger">
                                {{ $pendaftaran->where('status_pendaftaran', 'ditolak')->count() }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Lulus</h6>
                            <h3 class="mb-0 fw-bold text-success">
                                {{ $pendaftaran->where('status_pendaftaran', 'lulus')->count() }}
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
            <div class="row align-items-center">
                <div class="col-md-6">
                    <form id="filterForm" method="GET" class="row g-2">
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold text-muted mb-1">
                                <i class="fas fa-filter me-1"></i> Filter Angkatan
                            </label>
                            <select name="angkatan" class="form-select">
                                <option value="">Semua Angkatan</option>
                                @foreach($angkatanList as $angkatan)
                                    <option value="{{ $angkatan->id }}" {{ request('angkatan') == $angkatan->id ? 'selected' : '' }}>
                                        {{ $angkatan->nama_angkatan }} - {{ $angkatan->tahun }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-md-4 align-self-end">
                            <button type="submit" class="btn btn-filter-primary w-100">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-md-end align-items-center mt-3 mt-md-0">
                        <div class="d-flex align-items-center">
                            <label class="form-label small fw-semibold text-muted mb-0 me-2">
                                <i class="fas fa-list-ol me-1"></i> Tampilkan:
                            </label>
                            <select class="form-select form-select-sm w-auto" id="showEntries">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="-1">Semua</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Peserta Table Card -->
    <div class="card border-0 shadow-lg overflow-hidden">
        <div class="card-header bg-white py-3 border-0">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-list me-2" style="color: #285496;"></i> Daftar Peserta
                    </h5>
                    <small class="text-muted" id="tableInfo">
                        Menampilkan {{ $pendaftaran->count() }} dari {{ $pendaftaran->count() }} peserta
                    </small>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" id="searchInput"
                            placeholder="Cari nama, NIP, atau instansi...">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch" style="display: none;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="pesertaTable">
                    <thead>
                        <tr class="table-light">
                            <th width="5%" class="ps-4">No</th>
                            <th width="25%">Identitas</th>
                            <th width="15%" class="d-none d-md-table-cell">Instansi</th>
                            <th width="15%" class="d-none d-md-table-cell">Angkatan</th>
                            <th width="15%">Status</th>
                            <th width="25%" class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendaftaran as $index => $daftar)
                            @php
    $peserta = $daftar->peserta;
    $kepegawaian = $peserta->kepegawaianPeserta;
    $mentor = $daftar->pesertaMentor->first()?->mentor ?? null;
                            @endphp
                            <tr class="peserta-row" data-peserta-id="{{ $daftar->id }}">
                                <td class="ps-4 fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-3"
                                            style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold peserta-name">{{ $peserta->nama_lengkap }}</div>
                                            <div class="text-muted small">
                                                <i class="fas fa-id-card me-1"></i>
                                                {{ $peserta->nip_nrp ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <p class="mb-0 text-muted peserta-instansi">
                                        <i class="fas fa-building me-1"></i>
                                        {{ $kepegawaian->unit_kerja ?? '-' }}
                                    </p>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <p class="mb-0 text-muted peserta-angkatan">
                                        {{ $daftar->angkatan->nama_angkatan ?? '-' }}
                                        @if($daftar->angkatan && $daftar->angkatan->tahun)
                                            <br><small class="text-muted">({{ $daftar->angkatan->tahun }})</small>
                                        @endif
                                    </p>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @php
    $statusColors = [
        'pending' => 'bg-warning text-dark',
        'diterima' => 'bg-info',
        'ditolak' => 'bg-danger',
        'lulus' => 'bg-success'
    ];
    $statusColor = $statusColors[$daftar->status_pendaftaran] ?? 'bg-secondary';
    $statusIcons = [
        'pending' => 'fa-clock',
        'diterima' => 'fa-check',
        'ditolak' => 'fa-times',
        'lulus' => 'fa-graduation-cap'
    ];
    $statusIcon = $statusIcons[$daftar->status_pendaftaran] ?? 'fa-question';
                                        @endphp
                                        <span class="badge {{ $statusColor }} peserta-status">
                                            <i class="fas {{ $statusIcon }} me-1"></i>
                                            {{ ucfirst($daftar->status_pendaftaran) }}
                                        </span>
                                        <button type="button" class="btn btn-sm btn-outline-warning update-status"
                                            data-id="{{ $daftar->id }}" data-status="{{ $daftar->status_pendaftaran }}"
                                            data-bs-toggle="tooltip" title="Ubah Status">
                                            <i class="fas fa-edit me-1"></i> Verifikasi
                                        </button>
                                    </div>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-info btn-action view-detail"
                                            data-id="{{ $daftar->id }}" data-bs-toggle="tooltip" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="#" class="btn btn-sm btn-outline-warning btn-action" data-bs-toggle="tooltip"
                                            title="Edit Peserta">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-action delete-peserta"
                                            data-id="{{ $daftar->id }}" data-name="{{ $peserta->nama_lengkap }}"
                                            data-bs-toggle="tooltip" title="Hapus Peserta">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-state-row">
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mb-3">
                                            <i class="fas fa-users fa-4x" style="color: #e9ecef;"></i>
                                        </div>
                                        <h5 class="text-muted mb-2">Belum ada peserta</h5>
                                        <p class="text-muted mb-4">Tidak ada peserta yang terdaftar pada angkatan ini</p>
                                        @if(request('angkatan'))
                                            <a href="{{ route('peserta.pkn-tk2') }}" class="btn btn-outline-primary">
                                                <i class="fas fa-arrow-left me-1"></i> Kembali ke semua peserta
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

        @if($pendaftaran->count() > 0)
            <div class="card-footer bg-white py-3 border-0">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="table-pagination-info">
                            <small class="text-muted" id="paginationInfo">
                                Menampilkan 1-{{ $pendaftaran->count() }} dari {{ $pendaftaran->count() }} peserta
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Table pagination" class="d-flex justify-content-md-end">
                            <ul class="pagination pagination-sm mb-0" id="paginationControls">
                                <li class="page-item disabled">
                                    <button class="page-link" id="prevPage">Previous</button>
                                </li>
                                <li class="page-item">
                                    <span class="page-link">
                                        <span id="currentPage">1</span> / <span id="totalPages">1</span>
                                    </span>
                                </li>
                                <li class="page-item">
                                    <button class="page-link" id="nextPage">Next</button>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

<!-- Add Peserta Modal -->
<div class="modal fade" id="addPesertaModal" tabindex="-1" aria-labelledby="addPesertaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center px-4 pb-4">
                <div class="add-icon mb-3">
                    <i class="fas fa-user-plus fa-3x" style="color: #285496;"></i>
                </div>
                <h4 class="modal-title mb-3 fw-bold" id="addPesertaModalLabel">Tambah Peserta</h4>
                <p class="text-muted mb-4">
                    Fitur tambah peserta sedang dalam pengembangan
                </p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg overflow-hidden">
            <!-- Modal Header dengan Background Gradient -->
            <div class="modal-header"
                style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%); padding: 1.5rem;">
                <div class="d-flex align-items-center w-100">
                    <div class="icon-wrapper bg-white bg-opacity-20 rounded-3 p-3 me-3 shadow-sm">
                        <i class="fas fa-user-circle fa-xl text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="modal-title text-white mb-1 fw-bold" id="detailModalLabel">
                            Detail Peserta PKN TK II
                        </h5>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <small class="text-white-75" id="detailModalSubtitle"></small>
                            <span id="detailStatusBadge" class="badge bg-white bg-opacity-25 text-white"></span>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
            </div>

            <!-- Modal Body dengan Navigation Tabs -->
            <div class="modal-body p-0">
                <!-- Navigation Tabs -->
                <nav class="bg-light border-bottom">
                    <div class="nav nav-tabs px-3 pt-3" id="nav-tab" role="tablist">
                        <button class="nav-link active px-4 py-3 rounded-top-3 fw-semibold" id="nav-data-tab"
                            data-bs-toggle="tab" data-bs-target="#nav-data" type="button" role="tab">
                            <i class="fas fa-user-circle me-2"></i>Data Peserta
                        </button>
                        <button class="nav-link px-4 py-3 rounded-top-3 fw-semibold" id="nav-dokumen-tab"
                            data-bs-toggle="tab" data-bs-target="#nav-dokumen" type="button" role="tab">
                            <i class="fas fa-folder-open me-2"></i>Dokumen
                        </button>
                        <button class="nav-link px-4 py-3 rounded-top-3 fw-semibold" id="nav-mentor-tab"
                            data-bs-toggle="tab" data-bs-target="#nav-mentor" type="button" role="tab">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Mentor
                        </button>
                    </div>
                </nav>

                <!-- Tab Content -->
                <div class="tab-content p-0">
                    <!-- Tab 1: Data Peserta -->
                    <div class="tab-pane fade show active" id="nav-data" role="tabpanel" tabindex="0">
                        <div id="dataPesertaContent" class="p-4" style="max-height: 60vh; overflow-y: auto;">
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"
                                    role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-3 text-muted">Memuat data peserta...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Dokumen -->
                    <div class="tab-pane fade" id="nav-dokumen" role="tabpanel" tabindex="0">
                        <div id="dokumenContent" class="p-4" style="max-height: 60vh; overflow-y: auto;">
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"
                                    role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-3 text-muted">Memuat dokumen...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 3: Mentor -->
                    <div class="tab-pane fade" id="nav-mentor" role="tabpanel" tabindex="0">
                        <div id="mentorContent" class="p-4" style="max-height: 60vh; overflow-y: auto;">
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"
                                    role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-3 text-muted">Memuat data mentor...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-light border-top py-3 px-4">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            <span id="detailTimestamp"></span>
                        </small>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary rounded-3 px-4" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i> Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                <div class="modal-body text-center px-4 pb-4">
                    <div class="status-icon mb-3">
                        <i class="fas fa-edit fa-3x" style="color: #285496;"></i>
                    </div>
                    <h4 class="modal-title mb-3 fw-bold" id="statusModalLabel">Verifikasi Status</h4>

                    <div class="mb-4 text-start">
                        <label class="form-label fw-semibold">Status Saat Ini</label>
                        <div id="currentStatus" class="fw-bold"></div>
                    </div>

                    <div class="mb-4 text-start">
                        <label class="form-label fw-semibold">Status Baru</label>
                        <select class="form-select" name="status_pendaftaran" id="statusSelect" required>
                            <option value="pending">Pending</option>
                            <option value="diterima">Diterima</option>
                            <option value="ditolak">Ditolak</option>
                            <option value="lulus">Lulus</option>
                        </select>
                    </div>

                    <div class="mb-4 text-start">
                        <label class="form-label fw-semibold">Catatan (Opsional)</label>
                        <textarea class="form-control" name="catatan_verifikasi" id="catatanInput" rows="3"
                            placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>

                    <p class="text-muted small mb-4">
                        <i class="fas fa-info-circle me-1"></i>
                        Perubahan status akan tercatat di riwayat peserta
                    </p>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary px-4 btn-lift">
                        <i class="fas fa-save me-2"></i> Simpan
                    </button>
                </div>
            </form>
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
                <p class="text-muted mb-1">Anda akan menghapus peserta:</p>
                <h5 class="text-danger mb-4 fw-bold" id="deletePesertaName"></h5>

                <p class="text-muted small mb-4">
                    <i class="fas fa-info-circle me-1"></i>
                    Tindakan ini tidak dapat dibatalkan. Semua data peserta akan dihapus.
                </p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Batal
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4 btn-lift">
                        <i class="fas fa-trash-alt me-2"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize variables
            let currentPage = 1;
            let rowsPerPage = 10;
            let filteredRows = [];

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Modals
            const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
            const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

            // Initialize table
            initializeTable();

            // Show entries dropdown
            const showEntries = document.getElementById('showEntries');
            showEntries.addEventListener('change', function() {
                rowsPerPage = this.value === '-1' ? -1 : parseInt(this.value);
                currentPage = 1;
                updateTable();
            });

            // Pagination controls
            document.getElementById('prevPage').addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    updateTable();
                }
            });

            document.getElementById('nextPage').addEventListener('click', function() {
                const totalPages = Math.ceil(filteredRows.length / (rowsPerPage === -1 ? filteredRows.length : rowsPerPage));
                if (currentPage < totalPages) {
                    currentPage++;
                    updateTable();
                }
            });

            // Initialize table function
            function initializeTable() {
                const table = document.getElementById('pesertaTable');
                filteredRows = Array.from(table.querySelectorAll('tbody tr:not(.empty-state-row)'));
                updateTable();
            }

            // Update table display
            function updateTable() {
                const table = document.getElementById('pesertaTable');
                const rows = filteredRows;
                const totalRows = rows.length;

                // Calculate pagination
                const totalPages = rowsPerPage === -1 ? 1 : Math.ceil(totalRows / rowsPerPage);
                const startIndex = rowsPerPage === -1 ? 0 : (currentPage - 1) * rowsPerPage;
                const endIndex = rowsPerPage === -1 ? totalRows : startIndex + rowsPerPage;

                // Hide all rows
                rows.forEach(row => {
                    row.style.display = 'none';
                });

                // Show rows for current page
                const visibleRows = rows.slice(startIndex, endIndex);
                visibleRows.forEach(row => {
                    row.style.display = '';
                });

                // Update pagination info
                updatePaginationInfo(startIndex, endIndex, totalRows);

                // Update pagination controls
                updatePaginationControls(currentPage, totalPages);
            }

            // Update pagination info
            function updatePaginationInfo(start, end, total) {
                const startDisplay = total === 0 ? 0 : start + 1;
                const endDisplay = Math.min(end, total);

                document.getElementById('paginationInfo').textContent = 
                    `Menampilkan ${startDisplay}-${endDisplay} dari ${total} peserta`;

                document.getElementById('tableInfo').textContent = 
                    `Menampilkan ${endDisplay} dari ${total} peserta`;
            }

            // Update pagination controls
            function updatePaginationControls(current, total) {
                document.getElementById('currentPage').textContent = current;
                document.getElementById('totalPages').textContent = total;

                const prevBtn = document.getElementById('prevPage');
                const nextBtn = document.getElementById('nextPage');

                prevBtn.parentElement.classList.toggle('disabled', current <= 1);
                nextBtn.parentElement.classList.toggle('disabled', current >= total);
            }

            // View Detail
            document.querySelectorAll('.view-detail').forEach(button => {
                button.addEventListener('click', function () {
                    const pendaftaranId = this.getAttribute('data-id');
                    loadAllDetailData(pendaftaranId);
                    detailModal.show();
                });
            });

            // Update Status
            document.querySelectorAll('.update-status').forEach(button => {
                button.addEventListener('click', function () {
                    const pendaftaranId = this.getAttribute('data-id');
                    const currentStatus = this.getAttribute('data-status');

                    // Set form action
                    const form = document.getElementById('statusForm');
                    form.action = `update-status/${pendaftaranId}`;

                    // Set current status
                    const currentStatusElement = document.getElementById('currentStatus');
                    const statusColors = {
                        'pending': 'warning',
                        'diterima': 'info',
                        'ditolak': 'danger',
                        'lulus': 'success'
                    };
                    const statusText = currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1);
                    const statusColor = statusColors[currentStatus] || 'secondary';

                    currentStatusElement.innerHTML = `
                        <span class="badge bg-${statusColor}">
                            <i class="fas ${getStatusIcon(currentStatus)} me-1"></i>
                            ${statusText}
                        </span>
                    `;

                    // Set current status in select
                    const statusSelect = document.getElementById('statusSelect');
                    statusSelect.value = currentStatus;

                    statusModal.show();
                });
            });

            // Delete Peserta
            const deleteForm = document.getElementById('deleteForm');
            const deletePesertaName = document.getElementById('deletePesertaName');

            document.querySelectorAll('.delete-peserta').forEach(button => {
                button.addEventListener('click', function () {
                    const pesertaId = this.getAttribute('data-id');
                    const pesertaName = this.getAttribute('data-name');

                    deletePesertaName.textContent = pesertaName;
                    deleteForm.action = `{{ url('peserta') }}/${pesertaId}`;
                    deleteModal.show();
                });
            });

            // Status Form Submission
            const statusForm = document.getElementById('statusForm');
            statusForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const action = this.action;

                try {
                    const response = await fetch(action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        showAlert('success', result.message);
                        statusModal.hide();
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showAlert('error', result.message || 'Terjadi kesalahan');
                    }
                } catch (error) {
                    showAlert('error', 'Terjadi kesalahan jaringan');
                }
            });

            // Load All Detail Data
            async function loadAllDetailData(pendaftaranId) {
                try {
                    const response = await fetch(`detail/${pendaftaranId}`);
                    const result = await response.json();

                    if (result.success) {
                        const data = result.data;

                        // Update modal header
                        document.getElementById('detailModalSubtitle').textContent =
                            `${data.peserta.nip_nrp || 'NIP/NRP tidak tersedia'} • ${data.angkatan?.nama_angkatan || ''}`;

                        // Update status badge
                        updateStatusBadge(data.pendaftaran.status_pendaftaran);

                        // Update timestamp
                        document.getElementById('detailTimestamp').textContent =
                            `Terakhir diperbarui: ${formatDateTime(new Date())}`;

                        // Load tab contents
                        loadDataPesertaContent(data);
                        loadDokumenContent(data);
                        loadMentorContent(data);
                    } else {
                        showDetailError();
                    }
                } catch (error) {
                    showDetailError();
                }
            }

            // Load Data Peserta Content
            function loadDataPesertaContent(data) {
                const content = document.getElementById('dataPesertaContent');
                content.innerHTML = generateDataPesertaHTML(data);
            }

            // Load Dokumen Content
            function loadDokumenContent(data) {
                const content = document.getElementById('dokumenContent');
                content.innerHTML = generateDokumenHTML(data);
            }

            // Load Mentor Content
            function loadMentorContent(data) {
                const content = document.getElementById('mentorContent');
                content.innerHTML = generateMentorHTML(data);
            }

            // Generate Data Peserta HTML
            function generateDataPesertaHTML(data) {
                const peserta = data.peserta;
                const kepegawaian = data.kepegawaian;

                return `
                    <div class="detail-content">
                        <!-- Profile Header -->
                        <div class="profile-header mb-4 pb-4 border-bottom">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h4 class="fw-bold text-primary mb-2">${peserta.nama_lengkap}</h4>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <label class="text-muted small">NIP/NRP</label>
                                                <p class="fw-semibold mb-0">${peserta.nip_nrp || '-'}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <label class="text-muted small">Jenis Kelamin</label>
                                                <p class="fw-semibold mb-0">${peserta.jenis_kelamin || '-'}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <label class="text-muted small">Tempat/Tanggal Lahir</label>
                                                <p class="fw-semibold mb-0">${peserta.tempat_lahir || '-'}, ${formatDate(peserta.tanggal_lahir)}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <label class="text-muted small">Agama</label>
                                                <p class="fw-semibold mb-0">${peserta.agama || '-'}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <div class="avatar-placeholder rounded-3 bg-primary bg-opacity-10 p-4 d-inline-block">
                                        <i class="fas fa-user fa-4x text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Pribadi -->
                        <div class="info-section mb-4">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom">
                                <i class="fas fa-id-card me-2 text-primary"></i>Informasi Pribadi
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Alamat Rumah</label>
                                        <p class="fw-semibold mb-0">${peserta.alamat_rumah || '-'}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Email Pribadi</label>
                                        <p class="fw-semibold mb-0">${peserta.email_pribadi || '-'}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Nomor HP/WhatsApp</label>
                                        <p class="fw-semibold mb-0">${peserta.nomor_hp || '-'}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Pendidikan Terakhir</label>
                                        <p class="fw-semibold mb-0">${peserta.pendidikan_terakhir || '-'}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Bidang Studi</label>
                                        <p class="fw-semibold mb-0">${peserta.bidang_studi || '-'}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Bidang Keahlian</label>
                                        <p class="fw-semibold mb-0">${peserta.bidang_keahlian || '-'}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Kepegawaian -->
                        <div class="info-section mb-4">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom">
                                <i class="fas fa-building me-2 text-primary"></i>Informasi Kepegawaian
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Asal Instansi</label>
                                        <p class="fw-semibold mb-0">${kepegawaian?.asal_instansi || '-'}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Unit Kerja</label>
                                        <p class="fw-semibold mb-0">${kepegawaian?.unit_kerja || '-'}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Jabatan</label>
                                        <p class="fw-semibold mb-0">${kepegawaian?.jabatan || '-'}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Eselon</label>
                                        <p class="fw-semibold mb-0">${kepegawaian?.eselon || '-'}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Pangkat/Golongan</label>
                                        <p class="fw-semibold mb-0">${kepegawaian?.pangkat || '-'} ${kepegawaian?.golongan_ruang ? '/ ' + kepegawaian.golongan_ruang : ''}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Email Kantor</label>
                                        <p class="fw-semibold mb-0">${kepegawaian?.email_kantor || '-'}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Alamat Kantor</label>
                                        <p class="fw-semibold mb-0">${kepegawaian?.alamat_kantor || '-'}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Nomor Telepon Kantor</label>
                                        <p class="fw-semibold mb-0">${kepegawaian?.nomor_telepon_kantor || '-'}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Provinsi</label>
                                        <p class="fw-semibold mb-0">${data.provinsi?.name || '-'}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Kabupaten/Kota</label>
                                        <p class="fw-semibold mb-0">${data.kabupaten?.name || '-'}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="info-section">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom">
                                <i class="fas fa-info-circle me-2 text-primary"></i>Informasi Tambahan
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small">Status</label>
                                        <p class="fw-semibold mb-0">${peserta.status_perkawinan || '-'}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small">Nama Pasangan</label>
                                        <p class="fw-semibold mb-0">${peserta.nama_pasangan || '-'}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small">Olahraga/Hobi</label>
                                        <p class="fw-semibold mb-0">${peserta.olahraga_hobi || '-'}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small">Merokok</label>
                                        <p class="fw-semibold mb-0">${peserta.perokok ? 'Ya' : 'Tidak'}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <label class="text-muted small">Ukuran Kaos</label>
                                        <p class="fw-semibold mb-0">${peserta.ukuran_kaos || '-'}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Generate Dokumen HTML
            function generateDokumenHTML(data) {
                const peserta = data.peserta;
                const kepegawaian = data.kepegawaian;
                const pendaftaran = data.pendaftaran;

                const dokumenList = [
                    {
                        title: 'Pasfoto',
                        icon: 'fa-image',
                        file: peserta.file_pas_foto,
                        type: 'pribadi'
                    },
                    {
                        title: 'SK Jabatan Terakhir',
                        icon: 'fa-file-contract',
                        file: kepegawaian?.file_sk_jabatan,
                        type: 'kepegawaian'
                    },
                    {
                        title: 'SK Pangkat/Golongan',
                        icon: 'fa-file-certificate',
                        file: kepegawaian?.file_sk_pangkat,
                        type: 'kepegawaian'
                    },
                    {
                        title: 'Surat Komitmen',
                        icon: 'fa-file-signature',
                        file: pendaftaran.file_surat_komitmen,
                        type: 'pendaftaran'
                    },
                    {
                        title: 'Pakta Integritas',
                        icon: 'fa-file-certificate',
                        file: pendaftaran.file_pakta_integritas,
                        type: 'pendaftaran'
                    },
                    {
                        title: 'Surat Tugas',
                        icon: 'fa-file-contract',
                        file: pendaftaran.file_surat_tugas,
                        type: 'pendaftaran'
                    },
                    {
                        title: 'Surat Kelulusan Seleksi',
                        icon: 'fa-file-check',
                        file: pendaftaran.file_surat_kelulusan_seleksi,
                        type: 'pendaftaran'
                    },
                    {
                        title: 'Surat Sehat',
                        icon: 'fa-file-medical',
                        file: pendaftaran.file_surat_sehat,
                        type: 'pendaftaran'
                    },
                    {
                        title: 'Surat Bebas Narkoba',
                        icon: 'fa-file-medical-alt',
                        file: pendaftaran.file_surat_bebas_narkoba,
                        type: 'pendaftaran'
                    }
                ];

                // Group dokumen by type
                const groupedDokumen = {
                    pribadi: dokumenList.filter(d => d.type === 'pribadi'),
                    kepegawaian: dokumenList.filter(d => d.type === 'kepegawaian'),
                    pendaftaran: dokumenList.filter(d => d.type === 'pendaftaran')
                };

                return `
                    <div class="dokumen-content">
                        <div class="row">
                            <!-- Dokumen Pribadi -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-primary bg-opacity-10 border-0">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-user me-2 text-primary"></i>Dokumen Pribadi
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        ${groupedDokumen.pribadi.map(dokumen => dokumenCard(dokumen)).join('')}
                                    </div>
                                </div>
                            </div>

                            <!-- Dokumen Kepegawaian -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-info bg-opacity-10 border-0">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-building me-2 text-info"></i>Dokumen Kepegawaian
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        ${groupedDokumen.kepegawaian.map(dokumen => dokumenCard(dokumen)).join('')}
                                    </div>
                                </div>
                            </div>

                            <!-- Dokumen Pendaftaran -->
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-success bg-opacity-10 border-0">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-file-alt me-2 text-success"></i>Dokumen Pendaftaran
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        ${groupedDokumen.pendaftaran.map(dokumen => dokumenCard(dokumen)).join('')}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Dokumen Card Component
            function dokumenCard(dokumen) {
                if (dokumen.file) {
                    return `
                        <div class="dokumen-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="dokumen-icon bg-primary bg-opacity-10 text-primary rounded-2 p-2 me-3">
                                    <i class="fas ${dokumen.icon}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-semibold">${dokumen.title}</h6>
                                    <small class="text-muted">Dokumen tersedia</small>
                                </div>
                                <div class="ms-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary view-document" 
                                            data-url="${dokumen.file}" data-title="${dokumen.title}">
                                        <i class="fas fa-eye me-1"></i> Lihat
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    return `
                        <div class="dokumen-item mb-3 opacity-75">
                            <div class="d-flex align-items-center">
                                <div class="dokumen-icon bg-secondary bg-opacity-10 text-secondary rounded-2 p-2 me-3">
                                    <i class="fas ${dokumen.icon}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-semibold">${dokumen.title}</h6>
                                    <small class="text-muted">Belum diunggah</small>
                                </div>
                                <div class="ms-2">
                                    <span class="badge bg-secondary">Tidak tersedia</span>
                                </div>
                            </div>
                        </div>
                    `;
                }
            }

            // Generate Mentor HTML
            function generateMentorHTML(data) {
                const mentor = data.mentor;
                const angkatan = data.angkatan;

                if (!mentor) {
                    return `
                        <div class="mentor-content text-center py-5">
                            <div class="empty-state">
                                <div class="empty-state-icon mb-4">
                                    <i class="fas fa-chalkboard-teacher fa-4x" style="color: #e9ecef;"></i>
                                </div>
                                <h4 class="text-muted mb-3">Belum Ditugaskan Mentor</h4>
                                <p class="text-muted mb-4">Peserta ini belum memiliki mentor yang ditugaskan</p>
                                <button type="button" class="btn btn-primary rounded-3 px-4">
                                    <i class="fas fa-plus me-2"></i> Tugaskan Mentor
                                </button>
                            </div>
                        </div>
                    `;
                }

                return `
                    <div class="mentor-content">
                        <!-- Mentor Profile -->
                        <div class="mentor-profile card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h4 class="fw-bold text-primary mb-2">${mentor.nama_mentor}</h4>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <label class="text-muted small">Jabatan</label>
                                                    <p class="fw-semibold mb-0">${mentor.jabatan_mentor || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <label class="text-muted small">Instansi</label>
                                                    <p class="fw-semibold mb-0">${mentor.instansi_mentor || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <label class="text-muted small">Email</label>
                                                    <p class="fw-semibold mb-0">${mentor.email_mentor || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <label class="text-muted small">Telepon</label>
                                                    <p class="fw-semibold mb-0">${mentor.telepon_mentor || '-'}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <div class="mentor-badge bg-primary bg-opacity-10 rounded-3 p-3 d-inline-block">
                                            <i class="fas fa-chalkboard-teacher fa-3x text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Angkatan -->
                        <div class="angkatan-info card border-0 shadow-sm">
                            <div class="card-header bg-light border-0">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-calendar-alt me-2 text-primary"></i>Informasi Angkatan
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="text-muted small">Nama Angkatan</label>
                                            <p class="fw-semibold mb-0">${angkatan?.nama_angkatan || '-'}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="text-muted small">Periode</label>
                                            <p class="fw-semibold mb-0">
                                                ${formatDate(angkatan?.tanggal_mulai)} - ${formatDate(angkatan?.tanggal_selesai)}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="text-muted small">Lokasi Pelatihan</label>
                                            <p class="fw-semibold mb-0">${angkatan?.lokasi_pelatihan || '-'}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="text-muted small">Status Angkatan</label>
                                            <p class="fw-semibold mb-0">
                                                <span class="badge ${getAngkatanStatusBadge(angkatan?.status)}">
                                                    ${angkatan?.status || '-'}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Helper Functions
            function getStatusIcon(status) {
                const icons = {
                    'pending': 'fa-clock',
                    'diterima': 'fa-check',
                    'ditolak': 'fa-times',
                    'lulus': 'fa-graduation-cap'
                };
                return icons[status] || 'fa-question';
            }

            function getStatusColor(status) {
                const colors = {
                    'pending': 'warning',
                    'diterima': 'info',
                    'ditolak': 'danger',
                    'lulus': 'success'
                };
                return colors[status] || 'secondary';
            }

            function getAngkatanStatusBadge(status) {
                const badges = {
                    'aktif': 'bg-success',
                    'selesai': 'bg-primary',
                    'rencana': 'bg-warning',
                    'dibatalkan': 'bg-danger'
                };
                return badges[status?.toLowerCase()] || 'bg-secondary';
            }

            function updateStatusBadge(status) {
                const badge = document.getElementById('detailStatusBadge');
                const statusText = status.charAt(0).toUpperCase() + status.slice(1);
                const statusColor = getStatusColor(status);

                badge.className = `badge bg-${statusColor}`;
                badge.innerHTML = `<i class="fas ${getStatusIcon(status)} me-1"></i>${statusText}`;
            }

            function formatDate(dateString) {
                if (!dateString) return '-';
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
            }

            function formatDateTime(date) {
                return date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            function showAlert(type, message) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show shadow-sm d-flex align-items-center`;
                alertDiv.innerHTML = `
                    <div class="alert-icon flex-shrink-0">
                        <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <strong>${type === 'success' ? 'Sukses!' : 'Error!'}</strong> ${message}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;

                document.querySelector('.alert-container').appendChild(alertDiv);

                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        const bsAlert = new bootstrap.Alert(alertDiv);
                        bsAlert.close();
                    }
                }, 3000);
            }

            function showDetailError() {
                const content = document.getElementById('dataPesertaContent');
                content.innerHTML = `
                    <div class="text-center py-5">
                        <div class="empty-state-icon mb-3">
                            <i class="fas fa-exclamation-circle fa-4x" style="color: #e9ecef;"></i>
                        </div>
                        <h5 class="text-muted mb-2">Gagal memuat data</h5>
                        <p class="text-muted">Silakan coba lagi</p>
                    </div>
                `;
            }

            // Document Viewer
            document.addEventListener('click', function (e) {
                if (e.target.closest('.view-document')) {
                    e.preventDefault();
                    const button = e.target.closest('.view-document');
                    const url = button.getAttribute('data-url');
                    const title = button.getAttribute('data-title');
                    openDocumentViewer(url, title);
                }
            });

            function openDocumentViewer(url, title) {
                // For now, open in new tab
                // In production, you might want to use a modal with PDF viewer
                window.open(url, '_blank');

                // Optional: Show a toast notification
                showToast('info', `Membuka dokumen: ${title}`);
            }

            function showToast(type, message) {
                // Simple toast implementation
                const toast = document.createElement('div');
                toast.className = `toast align-items-center text-bg-${type} border-0 position-fixed bottom-0 end-0 m-3`;
                toast.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-info-circle me-2"></i>${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                `;

                document.body.appendChild(toast);
                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();

                toast.addEventListener('hidden.bs.toast', function () {
                    toast.remove();
                });
            }

            // Initialize search
            initializeSearch();

            // Auto-hide alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.classList.contains('show')) {
                        bootstrap.Alert.getOrCreateInstance(alert).close();
                    }
                }, 5000);
            });

            // Add hover effects to table rows
            const tableRows = document.querySelectorAll('.peserta-row');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateY(-2px)';
                    this.style.transition = 'transform 0.2s ease';
                });

                row.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateY(0)';
                });
            });
        });

        // Search Functionality
       function initializeSearch() {
            const searchInput = document.getElementById('searchInput');
            const clearSearchBtn = document.getElementById('clearSearch');
            const pesertaTable = document.getElementById('pesertaTable');
            const tbody = pesertaTable.querySelector('tbody');

            searchInput.addEventListener('input', function () {
                const term = this.value.trim();

                if (term !== '') {
                    clearSearchBtn.style.display = 'block';
                } else {
                    clearSearchBtn.style.display = 'none';
                }

                performSearch(term);
            });

            clearSearchBtn.addEventListener('click', function () {
                searchInput.value = '';
                this.style.display = 'none';
                performSearch('');
            });

            function performSearch(searchTerm) {
                const term = searchTerm.toLowerCase().trim();
                const allRows = Array.from(tbody.querySelectorAll('tr.peserta-row'));

                // SELALU hapus pesan no-result di awal
                removeNoResultsMessage();

                allRows.forEach(row => {
                    const nameText = row.querySelector('.peserta-name')?.textContent.toLowerCase() || '';
                    const nipText = row.querySelector('.text-muted')?.textContent.toLowerCase() || '';
                    const instansiText = row.querySelector('.peserta-instansi')?.textContent.toLowerCase() || '';

                    if (term === '') {
                        // Input kosong → tampilkan semua data
                        row.style.display = '';
                    } else if (
                        nameText.includes(term) ||
                        nipText.includes(term) ||
                        instansiText.includes(term)
                    ) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Kalau term kosong → jangan tampilkan pesan apa pun
                if (term === '') {
                    return;
                }

                // Cek apakah masih ada row yang terlihat
                const anyVisible = allRows.some(row => row.style.display !== 'none');

                if (!anyVisible) {
                    showNoResultsMessage(tbody, term);
                }
            }

            function showNoResultsMessage(tbody, term) {
                removeNoResultsMessage();

                const noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-results-row';
                noResultsRow.innerHTML = `
            <td colspan="6" class="text-center py-4">
                <div class="text-muted">
                    <i class="fas fa-search me-2"></i>
                    Tidak ditemukan peserta dengan kata kunci "${term}"
                </div>
            </td>
        `;
                tbody.appendChild(noResultsRow);
            }

            function removeNoResultsMessage() {
                const existing = tbody.querySelector('.no-results-row');
                if (existing) {
                    existing.remove();
                }
            }
        }


        function clearSearch() {
            const searchInput = document.getElementById('searchInput');
            const clearSearchBtn = document.getElementById('clearSearch');

            searchInput.value = '';
            clearSearchBtn.style.display = 'none';

            const event = new Event('input');
            searchInput.dispatchEvent(event);
        }
    </script>

    <style>
        /* Color Variables */
        :root {
            --primary-light: rgba(40, 84, 150, 0.1);
            --primary-gradient: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);
            --danger-color: #ff4757;
            --warning-color: #ffa502;
            --info-color: #17a2b8;
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


        /* Filter Section */
        .filter-section {
            background: linear-gradient(135deg, rgba(40, 84, 150, 0.05) 0%, rgba(58, 107, 199, 0.05) 100%);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .filter-section label {
            font-weight: 500;
            color: #285496;
        }

        /* User Avatar */
        .user-avatar {
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
            border-bottom: 2px solid var(--primary-light);
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

        .peserta-row:hover {
            background-color: rgba(40, 84, 150, 0.03) !important;
        }

        /* Pagination */
        .pagination .page-link {
            color: #285496;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            margin: 0 2px;
            font-weight: 500;
        }

        .pagination .page-link:hover {
            background-color: rgba(40, 84, 150, 0.1);
            border-color: #285496;
        }

        .pagination .page-item.active .page-link {
            background-color: #285496;
            border-color: #285496;
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #f8f9fa;
        }

        /* Detail Modal Styling */
        .modal-header .icon-wrapper {
            width: 50px;
            height: 50px;
        }

        /* Tab Navigation */
        .nav-tabs {
            border-bottom: 1px solid #dee2e6;
        }

        .nav-tabs .nav-link {
            color: #6c757d;
            border: none;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            margin-bottom: -1px;
        }

        .nav-tabs .nav-link:hover {
            color: #285496;
            border-color: rgba(40, 84, 150, 0.3);
        }

        .nav-tabs .nav-link.active {
            color: #285496;
            background-color: transparent;
            border-color: #285496;
            font-weight: 600;
        }

        /* Dokumen Styling */
        .dokumen-item {
            padding: 0.75rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .dokumen-item:hover {
            background-color: rgba(40, 84, 150, 0.05);
            border-color: rgba(40, 84, 150, 0.1);
            transform: translateX(5px);
        }

        .dokumen-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        /* Info Items */
        .info-item {
            margin-bottom: 1rem;
        }

        .info-item label {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
            display: block;
        }

        .info-item p {
            font-size: 0.95rem;
            color: #343a40;
            margin-bottom: 0;
        }

        /* Avatar Placeholder */
        .avatar-placeholder {
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Profile Header */
        .profile-header {
            background: linear-gradient(135deg, rgba(40, 84, 150, 0.05) 0%, rgba(58, 107, 199, 0.05) 100%);
            border-radius: 10px;
            padding: 1.5rem;
        }

        /* Info Sections */
        .info-section {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        }

        /* Toast Styling */
        .toast {
            z-index: 9999;
        }

        /* Card Styling in Modal */
        .mentor-profile .card-body {
            padding: 1.5rem;
        }

        .mentor-badge {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Empty State */
        .empty-state {
            padding: 3rem 1rem;
        }

        .empty-state-icon {
            opacity: 0.5;
        }

        /* Badge Styling */
        .badge {
            font-size: 0.75rem;
            padding: 0.4em 0.8em;
            border-radius: 20px;
            font-weight: 500;
        }

        .bg-warning {
            background-color: #ffc107 !important;
        }

        .bg-info {
            background-color: #17a2b8 !important;
        }

        .bg-danger {
            background-color: #dc3545 !important;
        }

        .bg-success {
            background-color: #28a745 !important;
        }

        .bg-primary {
            background-color: #285496 !important;
        }

        /* Custom Scrollbar */
        .modal-body .tab-content>.tab-pane {
            scrollbar-width: thin;
            scrollbar-color: #285496 #f1f1f1;
        }

        .modal-body .tab-content>.tab-pane::-webkit-scrollbar {
            width: 6px;
        }

        .modal-body .tab-content>.tab-pane::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .modal-body .tab-content>.tab-pane::-webkit-scrollbar-thumb {
            background: #285496;
            border-radius: 10px;
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tab-pane.active {
            animation: fadeIn 0.3s ease;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .modal-dialog.modal-xl {
                margin: 0.5rem;
            }

            .nav-tabs {
                flex-wrap: nowrap;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .nav-tabs .nav-link {
                white-space: nowrap;
                font-size: 0.85rem;
                padding: 0.75rem 1rem;
            }

            .profile-header {
                padding: 1rem;
            }

            .info-section {
                padding: 1rem;
            }

            .dokumen-content .row>.col-md-4 {
                margin-bottom: 1rem;
            }

            .mentor-profile .card-body {
                padding: 1rem;
            }

            .modal-footer .btn-group {
                flex-direction: column;
                width: 100%;
            }

            .modal-footer .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            /* Filter responsive */
            .filter-section .row {
                flex-direction: column;
            }

            .filter-section .col-md-8,
            .filter-section .col-md-4 {
                width: 100%;
                margin-bottom: 1rem;
            }

            .filter-section .col-md-4 button {
                width: 100%;
            }

            /* Show entries responsive */
            .show-entries-container {
                margin-top: 1rem;
                width: 100%;
                justify-content: flex-start !important;
            }
        }

        @media (max-width: 576px) {
            .modal-header {
                padding: 1rem;
            }

            .modal-body {
                padding: 0;
            }

            .nav-tabs .nav-link {
                font-size: 0.8rem;
                padding: 0.5rem 0.75rem;
            }

            .dokumen-item {
                padding: 0.5rem;
            }

            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.8rem;
            }

            .table th,
            .table td {
                padding: 0.75rem 0.5rem;
                font-size: 0.85rem;
            }

            .btn-action {
                padding: 0.25rem 0.5rem;
                margin: 1px;
            }

            .pagination .page-link {
                padding: 0.25rem 0.5rem;
                font-size: 0.8rem;
                margin: 1px;
            }
        }

        /* Print Styles */
        @media print {

            .modal-header,
            .modal-footer,
            .nav-tabs,
            .btn,
            .filter-section,
            .show-entries-container {
                display: none !important;
            }

            .modal-dialog {
                max-width: 100% !important;
                margin: 0 !important;
            }

            .modal-content {
                border: none !important;
                box-shadow: none !important;
            }

            .modal-body {
                overflow: visible !important;
                max-height: none !important;
            }

            .tab-content>.tab-pane {
                display: block !important;
                opacity: 1 !important;
            }
        }
    </style>
@endsection