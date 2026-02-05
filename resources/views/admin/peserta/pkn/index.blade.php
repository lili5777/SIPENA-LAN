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
                @if(auth()->user()->hasPermission('peserta.create'))
                <a href="{{ route('peserta.create', ['jenis' => request()->route('jenis')]) }}" class="btn btn-light btn-hover-lift shadow-sm d-flex align-items-center">
                    <i class="fas fa-user-plus me-2"></i>
                    Tambah Peserta
                </a>
                @endif
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
                                {{ $pendaftaran->where('status_pendaftaran', 'Menunggu Verifikasi')->count() }}
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
                                    {{ $pendaftaran->where('status_pendaftaran', 'Diterima')->count() }}
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
                                    {{ $pendaftaran->where('status_pendaftaran', 'Ditolak')->count() }}
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
                                    {{ $pendaftaran->where('status_pendaftaran', 'Lulus')->count() }}
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
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold text-muted mb-1">
                                    <i class="fas fa-filter me-1"></i> Angkatan
                                </label>
                                <select name="angkatan" class="form-select">
                                    <option value="">Semua Angkatan</option>
                                    @foreach($angkatanList as $angkatan)
                                        <option value="{{ $angkatan->id }}"
                                            {{ request('angkatan') == $angkatan->id ? 'selected' : '' }}>
                                            {{ $angkatan->nama_angkatan }} - {{ $angkatan->tahun }} 
                                            @if(!empty($angkatan->wilayah))
                                                ({{ $angkatan->wilayah }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold text-muted mb-1">
                                    <i class="fas fa-tags me-1"></i> Kategori
                                </label>
                                <select name="kategori" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    <option value="PNBP" {{ request('kategori') == 'PNBP' ? 'selected' : '' }}>PNBP</option>
                                    <option value="FASILITASI" {{ request('kategori') == 'FASILITASI' ? 'selected' : '' }}>Fasilitasi</option>
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

    // Mapping status untuk ikon dan warna
    $statusConfig = [
        'Menunggu Verifikasi' => [
            'color' => 'status-warning',
            'icon' => 'fa-clock',
            'text' => 'Menunggu Verifikasi'
        ],
        'Diterima' => [
            'color' => 'status-info',
            'icon' => 'fa-check-circle',
            'text' => 'Diterima'
        ],
        'Ditolak' => [
            'color' => 'status-danger',
            'icon' => 'fa-times-circle',
            'text' => 'Ditolak'
        ],
        'Lulus' => [
            'color' => 'status-success',
            'icon' => 'fa-graduation-cap',
            'text' => 'Lulus'
        ]
    ];

    $currentStatus = $daftar->status_pendaftaran;
    $statusData = $statusConfig[$currentStatus] ?? [
        'color' => 'status-secondary',
        'icon' => 'fa-question-circle',
        'text' => $currentStatus
    ];
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
                                                                                                                            <div class="d-flex align-items-start">
                                                                                                                                <i class="fas fa-building me-2 mt-1"></i>
                                                                                                                                <div class="d-flex flex-column">
                                                                                                                                    <span class="mb-1">
                                                                                                                                        {{ $kepegawaian->asal_instansi ?? '-' }}
                                                                                                                                    </span>
                                                                                                                                    <small class="text-muted">
                                                                                                                                        {{ $kepegawaian->unit_kerja ?? '-' }}
                                                                                                                                    </small>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </td>
                                                                                                                        <td class="d-none d-md-table-cell">
                                                                                                                            <p class="mb-0 text-muted peserta-angkatan">
                                                                                                                                {{ $daftar->angkatan->nama_angkatan ?? '-' }}

                                                                                                                                @if($daftar->angkatan && $daftar->angkatan->tahun)
                                                                                                                                    <br>
                                                                                                                                    <small class="text-muted">
                                                                                                                                        ({{ $daftar->angkatan->tahun }})
                                                                                                                                    </small>
                                                                                                                                @endif

                                                                                                                                @if(
                                                                                                                                    $daftar->angkatan &&
                                                                                                                                    $daftar->angkatan->kategori === 'FASILITASI' &&
                                                                                                                                    !empty($daftar->angkatan->wilayah)
                                                                                                                                )
                                                                                                                                    <br>
                                                                                                                                    <small class="text-muted">
                                                                                                                                        Wilayah: {{ $daftar->angkatan->wilayah }}
                                                                                                                                    </small>
                                                                                                                                @endif
                                                                                                                            </p>
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                            <div class="d-flex flex-column gap-1">
                                                                                                                                <span class="badge custom-badge {{ $statusData['color'] }} peserta-status">
                                                                                                                                    <i class="fas {{ $statusData['icon'] }} me-1"></i>
                                                                                                                                    {{ $statusData['text'] }}
                                                                                                                                </span>
                                                                                                                                @if ($daftar->status_pendaftaran != 'Diterima')
                                                                                                                                    <button type="button" class="btn btn-sm btn-outline-warning update-status"
                                                                                                                                        data-id="{{ $daftar->id }}" data-status="{{ $daftar->status_pendaftaran }}"
                                                                                                                                        data-bs-toggle="tooltip" title="Ubah Status">
                                                                                                                                        <i class="fas fa-edit me-1"></i> Verifikasi
                                                                                                                                    </button>
                                                                                                                                @endif
                                                                                                                            </div>
                                                                                                                        </td>
                                                                                                                        <td class="text-center pe-4">
                                                                                                                            <div class="btn-group" role="group">
                                                                                                                                <button type="button" class="btn btn-sm btn-outline-info btn-action view-detail"
                                                                                                                                    data-id="{{ $daftar->id }}" data-bs-toggle="tooltip" title="Lihat Detail">
                                                                                                                                    <i class="fas fa-eye"></i>
                                                                                                                                </button>
                                                                                                                                @if(auth()->user()->role->name === 'admin')
                                                                                                                                                            <button type="button" class="btn btn-sm btn-outline-secondary btn-action swap-angkatan"
                                                                                                                                                                data-id="{{ $daftar->id }}" 
                                                                                                                                                                data-jenis="{{ request()->route('jenis') }}"
                                                                                                                                                                data-bs-toggle="tooltip" title="Swap/Tukar dengan Peserta Lain (NDH ikut angkatan)">
                                                                                                                                                                <i class="fas fa-exchange-alt"></i>
                                                                                                                                                            </button>
                                                                                                                                                            @endif
                                                                                                                                <a href="{{ route('peserta.edit', ['jenis' => request()->route('jenis'), 'id' => $daftar->id]) }}" class="btn btn-sm btn-outline-warning btn-action"
                                                                                                                                    data-bs-toggle="tooltip" title="Edit Peserta">
                                                                                                                                    <i class="fas fa-edit"></i>
                                                                                                                                </a>
                                                                                                                                <button type="button" class="btn btn-sm btn-outline-danger btn-action delete-peserta"
                                                                                                                                    data-id="{{ $daftar->id }}" data-name="{{ $peserta->nama_lengkap }}"
                                                                                                                                    data-jenis="{{ request()->route('jenis') }}"
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
                                                <a href="{{ route('peserta.index', ['jenis' => request()->route('jenis')]) }}" class="btn btn-outline-primary">
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
                <h4 class="modal-title mb-3 fw-bold" >Tambah Peserta</h4>
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
                            <span id="detailStatusBadge" class="badge custom-badge"></span>
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
                        <button class="nav-link px-4 py-3 rounded-top-3 fw-semibold" id="nav-aksi-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-aksi" type="button" role="tab">
                            <i class="fas fa-lightbulb me-2"></i>Aksi Perubahan
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

                    <!-- Tab 2: Dokumen - SEMUA DISATUKAN -->
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

                    <!-- TAB BARU: Aksi Perubahan -->
                    <div class="tab-pane fade" id="nav-aksi" role="tabpanel" tabindex="0">
                        <div id="aksiContent" class="p-4" style="max-height: 60vh; overflow-y: auto;">
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-3 text-muted">Memuat data aksi perubahan...</p>
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

<!-- Status Update Modal dengan Loading -->
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
                        <div id="currentStatus" class="fw-bold mb-2"></div>
                    </div>

                    <div class="mb-4 text-start">
                        <label class="form-label fw-semibold">Status Baru</label>
                        <select class="form-select" name="status_pendaftaran" id="statusSelect" required>
                            <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                            <option value="Diterima">Diterima</option>
                            <option value="Ditolak">Ditolak</option>
                            <option value="Lulus">Lulus</option>
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
                    
                    <!-- Loading Indicator (hidden by default) -->
                    <div id="statusLoading" class="d-none">
                        
                        <p class="text-muted mt-3">Menyimpan perubahan...</p>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal" id="statusCancelBtn">
                        <i class="fas fa-times me-2"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary px-4 btn-lift" id="statusSubmitBtn">
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
                    <button type="submit" id="deleteSubmitBtn" class="btn btn-danger px-4 btn-lift">
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

                    // Status Form Elements
                    const statusForm = document.getElementById('statusForm');
                    const statusSubmitBtn = document.getElementById('statusSubmitBtn');
                    const statusCancelBtn = document.getElementById('statusCancelBtn');
                    const statusLoading = document.getElementById('statusLoading');

                    // Initialize table
                    initializeTable();

                    // Show entries dropdown
                    const showEntries = document.getElementById('showEntries');
                    showEntries.addEventListener('change', function () {
                        rowsPerPage = this.value === '-1' ? -1 : parseInt(this.value);
                        currentPage = 1;
                        updateTable();
                    });

                    // Pagination controls
                    document.getElementById('prevPage').addEventListener('click', function () {
                        if (currentPage > 1) {
                            currentPage--;
                            updateTable();
                        }
                    });

                    document.getElementById('nextPage').addEventListener('click', function () {
                        const totalPages = Math.ceil(filteredRows.length / (rowsPerPage === -1 ?
                            filteredRows.length : rowsPerPage));
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

                    // Swap angkatan button
                document.querySelectorAll('.swap-angkatan').forEach(button => {
                    button.addEventListener('click', function() {
                        const pendaftaranId = this.getAttribute('data-id');
                        const jenis = this.getAttribute('data-jenis');
                        
                        window.location.href = `/peserta/${jenis}/${pendaftaranId}/swap`;
                    });
                });

                    // Update Status - PERBAIKAN: Gunakan data-status yang benar
                    document.querySelectorAll('.update-status').forEach(button => {
                        button.addEventListener('click', function () {
                            const pendaftaranId = this.getAttribute('data-id');
                            const currentStatus = this.getAttribute('data-status'); 
                            const jenis = "{{ request()->route('jenis') }}";

                            // Set form action - Sesuaikan dengan route Anda
                            const form = document.getElementById('statusForm');
                            form.action = `/peserta/update-status/${pendaftaranId}`;

                            // Set current status
                            const currentStatusElement = document.getElementById('currentStatus');
                            const statusText = formatStatusText(currentStatus);
                            const statusColor = getStatusColor(currentStatus);
                            const statusIcon = getStatusIcon(currentStatus);

                            currentStatusElement.innerHTML = `
                                <span class="badge custom-badge ${statusColor}">
                                    <i class="fas ${statusIcon} me-1"></i>
                                    ${statusText}
                                </span>
                            `;

                            // Set current status in select
                            const statusSelect = document.getElementById('statusSelect');
                            statusSelect.value = currentStatus;

                            // Reset form state
                            statusLoading.classList.add('d-none');
                            statusSubmitBtn.disabled = false;
                            statusCancelBtn.disabled = false;
                            statusSubmitBtn.innerHTML = '<i class="fas fa-save me-2"></i> Simpan';
                            document.getElementById('catatanInput').value = '';

                            statusModal.show();
                        });
                    });

                    // Delete Peserta
                    // Delete Peserta
                    const deleteForm = document.getElementById('deleteForm');
                    const deletePesertaName = document.getElementById('deletePesertaName');
                    const deleteSubmitBtn = document.getElementById('deleteSubmitBtn');

                    document.querySelectorAll('.delete-peserta').forEach(button => {
                    button.addEventListener('click', function () {
                        const pesertaId = this.getAttribute('data-id');
                        const pesertaName = this.getAttribute('data-name');
                        const jenis = this.getAttribute('data-jenis');

                        deletePesertaName.textContent = pesertaName;
                        deleteForm.action = `/peserta/${jenis}/${pesertaId}`;
                        deleteModal.show();
                    });
                    });

                    // ✅ disable tombol saat submit supaya tidak double submit
                    if (deleteForm && deleteSubmitBtn) {
                    deleteForm.addEventListener('submit', function () {
                        deleteSubmitBtn.disabled = true;
                        deleteSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menghapus...';
                    });
                    }


                    // Status Form Submission dengan loading indicator
                    statusForm.addEventListener('submit', async function (e) {
                        e.preventDefault();

                        // Tampilkan loading, sembunyikan tombol
                        // statusLoading.classList.remove('d-none');
                        statusSubmitBtn.disabled = true;
                        statusCancelBtn.disabled = true;
                        statusSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';

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
                                // Update tombol dengan sukses
                                statusSubmitBtn.innerHTML = '<i class="fas fa-check me-2"></i> Berhasil!';
                                statusSubmitBtn.classList.remove('btn-primary');
                                statusSubmitBtn.classList.add('btn-success');

                                showAlert('success', result.message);

                                // Tutup modal setelah sukses
                                setTimeout(() => {
                                    statusModal.hide();

                                    // Reset tombol ke keadaan semula
                                    setTimeout(() => {
                                        // statusLoading.classList.add('d-none');
                                        statusSubmitBtn.disabled = false;
                                        statusCancelBtn.disabled = false;
                                        statusSubmitBtn.innerHTML = '<i class="fas fa-save me-2"></i> Simpan';
                                        statusSubmitBtn.classList.remove('btn-success');
                                        statusSubmitBtn.classList.add('btn-primary');

                                        // Refresh halaman
                                        window.location.reload();
                                    }, 500);
                                }, 1500);
                            } else {
                                // Sembunyikan loading, aktifkan tombol kembali
                                // statusLoading.classList.add('d-none');
                                statusSubmitBtn.disabled = false;
                                statusCancelBtn.disabled = false;
                                statusSubmitBtn.innerHTML = '<i class="fas fa-save me-2"></i> Simpan';

                                showAlert('error', result.message || 'Terjadi kesalahan');
                            }
                        } catch (error) {
                            // Sembunyikan loading, aktifkan tombol kembali
                            // statusLoading.classList.add('d-none');
                            statusSubmitBtn.disabled = false;
                            statusCancelBtn.disabled = false;
                            statusSubmitBtn.innerHTML = '<i class="fas fa-save me-2"></i> Simpan';

                            showAlert('error', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
                            console.error('Error:', error);
                        }
                    });

                    // Load All Detail Data
                    async function loadAllDetailData(pendaftaranId) {
                        try {
                            const response = await fetch(`/peserta/detail/${pendaftaranId}`);
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
                                loadAksiContent(data);
                            } else {
                                showDetailError();
                            }
                        } catch (error) {
                            showDetailError();
                            console.error('Error loading detail:', error);
                        }
                    }

                    // Load Data Peserta Content
                    function loadDataPesertaContent(data) {
                        const content = document.getElementById('dataPesertaContent');
                        content.innerHTML = generateDataPesertaHTML(data);
                    }

                    // Load Dokumen Content - SEMUA DISATUKAN
                    function loadDokumenContent(data) {
                        const content = document.getElementById('dokumenContent');
                        content.innerHTML = generateDokumenHTML(data);

                        // Add event listeners for document viewer
                        content.querySelectorAll('.view-document').forEach(button => {
                            button.addEventListener('click', function (e) {
                                e.preventDefault();
                                const path = this.getAttribute('data-path');
                                const title = this.getAttribute('data-title');
                                // openDocumentViewer(url, title);
                                if (path) {
                                    window.open(`/preview-drive?path=${encodeURIComponent(path)}`, '_blank');
                                }
                            });
                        });
                    }

                    // Load Aksi Perubahan Content (1 data saja)
function loadAksiContent(data) {
  const content = document.getElementById('aksiContent');
  content.innerHTML = generateAksiHTML(data);

  // Viewer untuk file (dokumen + lembar pengesahan)
  content.querySelectorAll('.view-aksi-document').forEach(button => {
    button.addEventListener('click', function (e) {
      e.preventDefault();
      const path = this.getAttribute('data-path');
      if (path) {
        window.open(`/preview-drive?path=${encodeURIComponent(path)}`, '_blank');
      }
    });
  });
}

// helper: ambil nama file dari path drive
function fileNameFromPath(path) {
  if (!path) return '';
  const clean = path.split('?')[0];
  return clean.split('/').pop() || 'file.pdf';
}

// helper: pastikan link punya http/https
function normalizeUrl(url) {
  if (!url) return null;
  if (/^https?:\/\//i.test(url)) return url;
  return `https://${url}`;
}

// Generate Aksi Perubahan HTML (tanpa perulangan)
function generateAksiHTML(data) {
  // support kalau backend ngirim array atau object
  const aksi = Array.isArray(data.aksi_perubahan)
    ? (data.aksi_perubahan[0] || null)
    : (data.aksi_perubahan || null);

  if (!aksi) {
    return `
      <div class="aksi-content text-center py-5">
        <div class="empty-state">
          <div class="empty-state-icon mb-4">
            <i class="fas fa-lightbulb fa-4x" style="color: #e9ecef;"></i>
          </div>
          <h4 class="text-muted mb-3">Belum Ada Aksi Perubahan</h4>
          <p class="text-muted mb-4">Peserta ini belum mengirimkan aksi perubahan</p>
        </div>
      </div>
    `;
  }

  return `
    <div class="aksi-container">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0 d-flex align-items-center">
          <div class="icon-wrapper bg-warning bg-opacity-10 p-2 rounded-3 me-3">
            <i class="fas fa-lightbulb text-warning"></i>
          </div>
          <span>Aksi Perubahan</span>
        </h5>
      </div>

      <div class="row g-4">
        ${aksiCard(aksi)}
      </div>
    </div>
  `;
}

// Card tunggal (tanpa index/perulangan)
function aksiCard(aksi) {
  // sesuaikan dengan dropdown kamu: pilihan1/pilihan2
  const kategoriMap = {
    'pilihan1': { color: 'success', icon: 'fa-bolt', text: 'Pilihan 1' },
    'pilihan2': { color: 'info', icon: 'fa-tag', text: 'Pilihan 2' }
  };

  const kategori = kategoriMap[aksi.kategori_aksatika] || {
    color: 'secondary',
    icon: 'fa-question',
    text: aksi.kategori_aksatika || '-'
  };

  const videoUrl = normalizeUrl(aksi.link_video);
  const majalahUrl = normalizeUrl(aksi.link_laporan_majalah);

  return `
    <div class="col-xl-6 col-lg-12">
      <div class="aksi-card card border-0 shadow-sm h-100">
        <div class="card-body p-4">

          <div class="d-flex align-items-start mb-3">
            <div class="badge-number me-3">
              <span class="badge bg-primary rounded-circle p-2"
                style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                1
              </span>
            </div>

            <div class="flex-grow-1">
              <h6 class="fw-bold mb-2 text-dark">${aksi.judul || 'Aksi Perubahan'}</h6>

              <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                <span class="badge bg-${kategori.color} bg-opacity-10 text-${kategori.color}">
                  <i class="fas ${kategori.icon} me-1"></i>${kategori.text}
                </span>
              </div>

              ${aksi.abstrak ? `
                <div class="abstrak-section mb-3">
                  <label class="text-muted small">Abstrak</label>
                  <p class="mb-0 text-dark" style="line-height: 1.5;">
                    ${aksi.abstrak}
                  </p>
                </div>
              ` : ''}
            </div>
          </div>

          <!-- Dokumen dan Link -->
          <div class="row g-2">
            ${aksi.file ? `
              <div class="col-md-6">
                <div class="dokumen-item d-flex align-items-center p-2 border rounded">
                  <div class="dokumen-icon bg-primary bg-opacity-10 rounded p-2 me-2">
                    <i class="fas fa-file-pdf text-primary"></i>
                  </div>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">Laporan Lengkap</small>
                    <small class="fw-semibold">${fileNameFromPath(aksi.file)}</small>
                  </div>
                  <button type="button" class="btn btn-sm btn-outline-primary view-aksi-document"
                          data-path="${aksi.file}" data-title="Laporan Lengkap">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
              </div>
            ` : ''}

            ${aksi.lembar_pengesahan ? `
              <div class="col-md-6">
                <div class="dokumen-item d-flex align-items-center p-2 border rounded">
                  <div class="dokumen-icon bg-success bg-opacity-10 rounded p-2 me-2">
                    <i class="fas fa-file-signature text-success"></i>
                  </div>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">Lembar Pengesahan</small>
                    <small class="fw-semibold">${fileNameFromPath(aksi.lembar_pengesahan)}</small>
                  </div>
                  <button type="button" class="btn btn-sm btn-outline-success view-aksi-document"
                          data-path="${aksi.lembar_pengesahan}" data-title="Lembar Pengesahan">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
              </div>
            ` : ''}

            ${videoUrl ? `
              <div class="col-md-6">
                <div class="link-item d-flex align-items-center p-2 border rounded">
                  <div class="link-icon bg-danger bg-opacity-10 rounded p-2 me-2">
                    <i class="fab fa-youtube text-danger"></i>
                  </div>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">Video Presentasi</small>
                    <small class="fw-semibold">Buka Link</small>
                  </div>
                  <a href="${videoUrl}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-external-link-alt"></i>
                  </a>
                </div>
              </div>
            ` : ''}

            ${majalahUrl ? `
              <div class="col-md-6">
                <div class="link-item d-flex align-items-center p-2 border rounded">
                  <div class="link-icon bg-info bg-opacity-10 rounded p-2 me-2">
                    <i class="fas fa-newspaper text-info"></i>
                  </div>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">Laporan Majalah</small>
                    <small class="fw-semibold">Buka Link</small>
                  </div>
                  <a href="${majalahUrl}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-info">
                    <i class="fas fa-external-link-alt"></i>
                  </a>
                </div>
              </div>
            ` : ''}
          </div>

        </div>
      </div>
    </div>
  `;
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
                                            <h6 class="fw-bold mb-3 pb-2 border-bottom">
                                                <i class="fas fa-id-card me-2 text-primary"></i>Informasi Pribadi
                                            </h6>
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <div class="info-item">
                                                        <label class="text-muted small">Nama Panggilan</label>
                                                        <p class="fw-semibold mb-0">${peserta.nama_panggilan || '-'}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="info-item">
                                                        <label class="text-muted small">NIP/NRP</label>
                                                        <p class="fw-semibold mb-0">${peserta.nip_nrp || '-'}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="info-item">
                                                        <label class="text-muted small">Jenis Kelamin</label>
                                                        <p class="fw-semibold mb-0">${peserta.jenis_kelamin || '-'}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="info-item">
                                                        <label class="text-muted small">Tempat/Tanggal Lahir</label>
                                                        <p class="fw-semibold mb-0">${peserta.tempat_lahir || '-'}, ${formatDate(peserta.tanggal_lahir)}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="info-item">
                                                        <label class="text-muted small">Agama</label>
                                                        <p class="fw-semibold mb-0">${peserta.agama || '-'}</p>
                                                    </div>
                                                </div>
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
                                                        <label class="text-muted small">Alamat Rumah</label>
                                                        <p class="fw-semibold mb-0">${peserta.alamat_rumah || '-'}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="info-item">
                                                        <label class="text-muted small">Email Pribadi</label>
                                                        <p class="fw-semibold mb-0">${peserta.email_pribadi || '-'}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="info-item">
                                                        <label class="text-muted small">Nomor HP/WhatsApp</label>
                                                        <p class="fw-semibold mb-0">${peserta.nomor_hp || '-'}</p>
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
                                                        <p class="fw-semibold mb-0">${peserta.perokok || '-'}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="info-item">
                                                        <label class="text-muted small">Pendidikan Terakhir</label>
                                                        <p class="fw-semibold mb-0">${peserta.pendidikan_terakhir || '-'}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="info-item">
                                                        <label class="text-muted small">Bidang Studi</label>
                                                        <p class="fw-semibold mb-0">${peserta.bidang_studi || '-'}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="info-item">
                                                        <label class="text-muted small">Bidang Keahlian</label>
                                                        <p class="fw-semibold mb-0">${peserta.bidang_keahlian || '-'}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="info-item">
                                                        <label class="text-muted small">Ukuran Kaos Taktikal</label>
                                                        <p class="fw-semibold mb-0">${peserta.ukuran_kaos || '-'}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="info-item">
                                                        <label class="text-muted small">Ukuran Kaos Training</label>
                                                        <p class="fw-semibold mb-0">${peserta.ukuran_training || '-'}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="info-item">
                                                        <label class="text-muted small">Ukuran Celana</label>
                                                        <p class="fw-semibold mb-0">${peserta.ukuran_celana || '-'}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="avatar-placeholder rounded-3 bg-primary bg-opacity-10 p-4 d-inline-flex flex-column align-items-center">
                                                <i class="fas fa-user fa-4x text-primary mb-2"></i>

                                                <label class="text-muted small mt-2">NDH</label>
                                                <p class="fw-semibold mb-0 text-center">
                                                    ${peserta.ndh ?? '-'}
                                                </p>
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
                                                <label class="text-muted small">Unit Kerja/Detail Instansi</label>
                                                <p class="fw-semibold mb-0">${kepegawaian?.unit_kerja || '-'}</p>
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

                                    </div>
                                </div>
                            </div>
                        `;
                    }

                    // Generate Dokumen HTML - SEMUA DISATUKAN
                    function generateDokumenHTML(data) {
                        const peserta = data.peserta;
                        const kepegawaian = data.kepegawaian;
                        const pendaftaran = data.pendaftaran;

                        // Dokumen dengan ikon yang lebih jelas
                        const dokumenList = [
                            {
                                title: 'KTP',
                                icon: 'fa-id-card',
                                color: 'success',
                                file: peserta.file_ktp,
                                description: 'Kartu Tanda Penduduk yang masih berlaku'
                            },
                            {
                                title: 'Pasfoto',
                                icon: 'fa-camera',
                                color: 'primary',
                                file: peserta.file_pas_foto,
                                description: 'Foto terbaru ukuran 4x6 dengan background merah'
                            },
                            {
                                title: 'SK Jabatan Terakhir',
                                icon: 'fa-file-alt',
                                color: 'success',
                                file: kepegawaian?.file_sk_jabatan,
                                description: 'Surat Keputusan jabatan terakhir yang masih berlaku'
                            },
                            {
                                title: 'SK Pangkat/Golongan',
                                icon: 'fa-award',
                                color: 'warning',
                                file: kepegawaian?.file_sk_pangkat,
                                description: 'Surat Keputusan kenaikan pangkat/golongan'
                            },
                            {
                                title: 'Surat Komitmen',
                                icon: 'fa-handshake',
                                color: 'info',
                                file: pendaftaran.file_surat_komitmen,
                                description: 'Surat komitmen menyelesaikan pelatihan'
                            },
                            {
                                title: 'Pakta Integritas',
                                icon: 'fa-file-signature',
                                color: 'danger',
                                file: pendaftaran.file_pakta_integritas,
                                description: 'Pakta integritas mengikuti peraturan'
                            },
                            {
                                title: 'Surat Tugas',
                                icon: 'fa-tasks',
                                color: 'primary',
                                file: pendaftaran.file_surat_tugas,
                                description: 'Surat tugas dari instansi asal'
                            },
                            {
                                title: 'Surat Kelulusan Seleksi',
                                icon: 'fa-graduation-cap',
                                color: 'success',
                                file: pendaftaran.file_surat_kelulusan_seleksi,
                                description: 'Surat kelulusan seleksi administrasi'
                            },
                            {
                                title: 'Surat Sehat',
                                icon: 'fa-heartbeat',
                                color: 'warning',
                                file: pendaftaran.file_surat_sehat,
                                description: 'Surat keterangan sehat dari dokter'
                            },
                            {
                                title: 'Surat Bebas Narkoba',
                                icon: 'fa-ban',
                                color: 'info',
                                file: pendaftaran.file_surat_bebas_narkoba,
                                description: 'Surat keterangan bebas narkoba'
                            }
                        ];

                        // Hitung statistik dokumen
                        const totalDokumen = dokumenList.length;
                        const dokumenTersedia = dokumenList.filter(d => d.file).length;
                        const dokumenBelum = totalDokumen - dokumenTersedia;
                        const persentaseKelengkapan = dokumenTersedia > 0 ? Math.round((dokumenTersedia / totalDokumen) * 100) : 0;

                        return `
                            <div class="dokumen-container">
                                <!-- Statistik Dokumen -->
                                <div class="row mb-4">
                                    <div class="col-md-6 col-lg-3">
                                        <div class="stat-card card border-0 shadow-sm">
                                            <div class="card-body text-center p-3">
                                                <div class="stat-icon-wrapper bg-primary bg-opacity-10 rounded-circle p-3 mx-auto mb-2">
                                                    <i class="fas fa-folder-open fa-xl text-primary"></i>
                                                </div>
                                                <h3 class="fw-bold text-primary mb-1">${totalDokumen}</h3>
                                                <p class="text-muted small mb-0">Total Dokumen</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="stat-card card border-0 shadow-sm">
                                            <div class="card-body text-center p-3">
                                                <div class="stat-icon-wrapper bg-success bg-opacity-10 rounded-circle p-3 mx-auto mb-2">
                                                    <i class="fas fa-check-circle fa-xl text-success"></i>
                                                </div>
                                                <h3 class="fw-bold text-success mb-1">${dokumenTersedia}</h3>
                                                <p class="text-muted small mb-0">Dokumen Tersedia</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="stat-card card border-0 shadow-sm">
                                            <div class="card-body text-center p-3">
                                                <div class="stat-icon-wrapper bg-warning bg-opacity-10 rounded-circle p-3 mx-auto mb-2">
                                                    <i class="fas fa-clock fa-xl text-warning"></i>
                                                </div>
                                                <h3 class="fw-bold text-warning mb-1">${dokumenBelum}</h3>
                                                <p class="text-muted small mb-0">Belum Diunggah</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="stat-card card border-0 shadow-sm">
                                            <div class="card-body text-center p-3">
                                                <div class="stat-icon-wrapper bg-info bg-opacity-10 rounded-circle p-3 mx-auto mb-2">
                                                    <i class="fas fa-chart-line fa-xl text-info"></i>
                                                </div>
                                                <h3 class="fw-bold text-info mb-1">${persentaseKelengkapan}%</h3>
                                                <p class="text-muted small mb-0">Kelengkapan</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Progress Bar Kelengkapan -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="fw-bold mb-0">Kelengkapan Dokumen</h6>
                                            <span class="badge bg-primary">${persentaseKelengkapan}%</span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-primary" role="progressbar" 
                                                 style="width: ${persentaseKelengkapan}%" 
                                                 aria-valuenow="${persentaseKelengkapan}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Header -->
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="fw-bold mb-0 d-flex align-items-center">
                                        <div class="icon-wrapper bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                            <i class="fas fa-folder-open text-primary"></i>
                                        </div>
                                        <span>Semua Dokumen Peserta</span>
                                    </h5>
                                    <div class="text-muted small">
                                        <i class="fas fa-check-circle text-success me-1"></i>
                                        ${dokumenTersedia} dari ${totalDokumen} dokumen tersedia
                                    </div>
                                </div>

                                <!-- Grid Dokumen -->
                                <div class="row g-4">
                                    ${dokumenList.map(dokumen => dokumenCard(dokumen)).join('')}
                                </div>
                            </div>
                        `;
                    }

                    // Dokumen Card Component
                    function dokumenCard(dokumen) {
                        if (dokumen.file) {
                            const fileSize = getFileSize(dokumen.file);
                            return `
                                <div class="col-xl-4 col-lg-6 col-md-6">
                                    <div class="dokumen-card card border-0 shadow-sm h-100 dokumen-available">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="dokumen-icon-wrapper bg-${dokumen.color} bg-opacity-10 p-3 rounded-3 me-3">
                                                    <i class="fas ${dokumen.icon} fa-2x text-${dokumen.color}"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1 text-dark">${dokumen.title}</h6>
                                                    <p class="text-muted small mb-2">${dokumen.description}</p>
                                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                                        <span class="badge bg-success bg-opacity-10 text-success">
                                                            <i class="fas fa-check-circle me-1"></i>Tersedia
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-${dokumen.color} view-document" 
                                                        data-path="${dokumen.file}" data-title="${dokumen.title}">
                                                    <i class="fas fa-eye me-1"></i> Lihat
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        } else {
                            return `
                                <div class="col-xl-4 col-lg-6 col-md-6">
                                    <div class="dokumen-card card border-0 shadow-sm h-100 dokumen-missing">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="dokumen-icon-wrapper bg-secondary bg-opacity-10 p-3 rounded-3 me-3">
                                                    <i class="fas ${dokumen.icon} fa-2x text-secondary"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1 text-dark">${dokumen.title}</h6>
                                                    <p class="text-muted small mb-2">${dokumen.description}</p>
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-danger bg-opacity-10 text-danger">
                                                            <i class="fas fa-times-circle me-1"></i>Belum diunggah
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Dokumen belum diunggah oleh peserta
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }
                    }

                    // Helper function untuk ukuran file
                    function getFileSize(fileUrl) {
                        // Simulasi ukuran file
                        const sizes = ['2.1 MB', '1.8 MB', '3.2 MB', '1.5 MB', '2.4 MB', '1.9 MB', '2.7 MB', '2.3 MB', '1.6 MB'];
                        const randomIndex = Math.floor(Math.random() * sizes.length);
                        return sizes[randomIndex];
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
                                                            <label class="text-muted small">NIP</label>
                                                            <p class="fw-semibold mb-0">${mentor.nip_mentor || '-'}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info-item">
                                                            <label class="text-muted small">NPWP</label>
                                                            <p class="fw-semibold mb-0">${mentor.npwp_mentor || '-'}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info-item">
                                                            <label class="text-muted small">Jabatan</label>
                                                            <p class="fw-semibold mb-0">${mentor.jabatan_mentor || '-'}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info-item">
                                                            <label class="text-muted small">Nomor Rekening</label>
                                                            <p class="fw-semibold mb-0">${mentor.nomor_rekening || '-'}</p>
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
                                                            <p class="fw-semibold mb-0">${mentor.nomor_hp_mentor || '-'}</p>
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


                            </div>
                        `;
                    }

                    // Helper Functions untuk Status
                    function getStatusIcon(status) {
                        const statusLower = status.toLowerCase();
                        const icons = {
                            'menunggu verifikasi': 'fa-clock',
                            'diterima': 'fa-check-circle',
                            'ditolak': 'fa-times-circle',
                            'lulus': 'fa-graduation-cap'
                        };
                        return icons[statusLower] || 'fa-question-circle';
                    }

                    function getStatusColor(status) {
                        const statusLower = status.toLowerCase();
                        const colors = {
                            'menunggu verifikasi': 'status-warning',
                            'diterima': 'status-info',
                            'ditolak': 'status-danger',
                            'lulus': 'status-success'
                        };
                        return colors[statusLower] || 'status-secondary';
                    }

                    function formatStatusText(status) {
                        const statusLower = status.toLowerCase();
                        const texts = {
                            'menunggu verifikasi': 'Menunggu Verifikasi',
                            'diterima': 'Diterima',
                            'ditolak': 'Ditolak',
                            'lulus': 'Lulus'
                        };
                        return texts[statusLower] || status;
                    }

                    function updateStatusBadge(status) {
                        const badge = document.getElementById('detailStatusBadge');
                        const statusText = formatStatusText(status);
                        const statusColor = getStatusColor(status);
                        const statusIcon = getStatusIcon(status);

                        badge.className = `badge custom-badge ${statusColor}`;
                        badge.innerHTML = `<i class="fas ${statusIcon} me-1"></i>${statusText}`;
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

                    function formatDate(dateString) {
                        if (!dateString) return '-';
                        try {
                            const date = new Date(dateString);
                            return date.toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: 'long',
                                year: 'numeric'
                            });
                        } catch (e) {
                            return '-';
                        }
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
                        // Hapus alert lama
                        const oldAlerts = document.querySelectorAll('.alert-container .alert');
                        oldAlerts.forEach(alert => {
                            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                            bsAlert.close();
                        });

                        // Buat alert baru
                        const alertDiv = document.createElement('div');
                        alertDiv.className = `alert alert-${type} alert-dismissible fade show shadow-sm d-flex align-items-center`;

                        const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
                        const title = type === 'success' ? 'Sukses!' : 'Error!';

                        alertDiv.innerHTML = `
                            <div class="alert-icon flex-shrink-0">
                                <i class="fas ${iconClass} fa-lg"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <strong>${title}</strong> ${message}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;

                        document.querySelector('.alert-container').prepend(alertDiv);

                        // Auto close setelah 5 detik
                        setTimeout(() => {
                            if (alertDiv.parentNode) {
                                const bsAlert = bootstrap.Alert.getOrCreateInstance(alertDiv);
                                bsAlert.close();
                            }
                        }, 5000);
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
                    function openDocumentViewer(url, title) {
                        const cleanUrl = url.startsWith('uploads/') ? '/' + url : url;
                        window.open(cleanUrl, '_blank');

                        // Show notification
                        showToast('info', `Membuka dokumen: ${title}`);
                    }

                    function showToast(type, message) {
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


                /* CUSTOM STATUS BADGES (CSS Custom - Tidak bergantung pada Bootstrap) */
                /* SIMPLE FIX FOR TOOLTIP POSITION */
        .tooltip {
            position: fixed !important;
            z-index: 9999 !important;
        }

        /* Pastikan tombol memiliki posisi relative */
        .btn-action {
            position: relative !important;
        }

        /* Hilangkan efek transform pada hover jika mengganggu */
        .btn-action:hover {
            transform: none !important;
        }

        /* Perbaikan untuk tabel */
        .table-responsive {
            overflow-x: auto;
            overflow-y: visible !important;
        }

        .table {
            margin-bottom: 0;
        }

        /* Force tooltip to be visible */
        .bs-tooltip-top {
            margin-top: -10px !important;
        }

        .bs-tooltip-bottom {
            margin-top: 10px !important;
        }
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

                .status-warning {
                    background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%) !important;
                    color: #212529 !important;
                }

                .status-info {
                    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
                    color: white !important;
                }

                .status-danger {
                    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
                    color: white !important;
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

                /* Loading Indicator in Status Modal */
                #statusLoading {
                    padding: 2rem;
                    border-radius: 10px;
                    background-color: rgba(255, 255, 255, 0.95);
                    margin: 1.5rem 0;
                    text-align: center;
                    animation: fadeIn 0.3s ease;
                }

                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(10px); }
                    to { opacity: 1; transform: translateY(0); }
                }

                #statusLoading .spinner-border {
                    width: 3rem;
                    height: 3rem;
                    border-width: 0.25rem;
                }

                /* Button Colors */
                .btn-primary {
                    background-color: #285496 !important;
                    border-color: #285496 !important;
                }

                .btn-success {
                    background-color: #28a745 !important;
                    border-color: #28a745 !important;
                }

                .btn-warning {
                    background-color: #ffc107 !important;
                    border-color: #ffc107 !important;
                    color: #212529 !important;
                }

                .btn-info {
                    background-color: #17a2b8 !important;
                    border-color: #17a2b8 !important;
                }

                .btn-danger {
                    background-color: #dc3545 !important;
                    border-color: #dc3545 !important;
                }

                /* DOKUMEN STYLING */
                .dokumen-container .stat-card {
                    border-radius: 10px;
                    transition: all 0.3s ease;
                    height: 100%;
                }

                .dokumen-container .stat-card:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1) !important;
                }

                .stat-icon-wrapper {
                    width: 70px;
                    height: 70px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .dokumen-container h3 {
                    font-size: 1.75rem;
                    font-weight: 700;
                }

                /* Progress Bar */
                .progress {
                    background-color: #e9ecef;
                    border-radius: 4px;
                    overflow: hidden;
                }

                .progress-bar {
                    background: linear-gradient(90deg, #285496, #3a6bc7);
                    transition: width 0.6s ease;
                }

                /* Dokumen Cards */
                .dokumen-card {
                    transition: all 0.3s ease;
                    border-radius: 12px;
                    overflow: hidden;
                    border: 1px solid #e9ecef;
                    height: 100%;
                }

                .dokumen-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
                    border-color: #285496;
                }

                .dokumen-available {
                    border-left: 4px solid #28a745;
                }

                .dokumen-missing {
                    border-left: 4px solid #6c757d;
                    opacity: 0.8;
                }

                .dokumen-card:hover.dokumen-missing {
                    opacity: 1;
                    border-color: #6c757d;
                }

                .dokumen-icon-wrapper {
                    width: 60px;
                    height: 60px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.3s ease;
                    flex-shrink: 0;
                }

                .dokumen-card:hover .dokumen-icon-wrapper {
                    transform: scale(1.1);
                }

                /* Badge Styling */
                .badge.bg-success.bg-opacity-10 {
                    background-color: rgba(40, 167, 69, 0.1) !important;
                    color: #28a745 !important;
                    border: 1px solid rgba(40, 167, 69, 0.2);
                }

                .badge.bg-danger.bg-opacity-10 {
                    background-color: rgba(220, 53, 69, 0.1) !important;
                    color: #dc3545 !important;
                    border: 1px solid rgba(220, 53, 69, 0.2);
                }

                .badge.bg-primary {
                    background-color: #285496 !important;
                    color: white !important;
                }

                /* Responsive Design */
                @media (max-width: 768px) {
                    .custom-badge {
                        padding: 0.35rem 0.5rem;
                        font-size: 0.75rem;
                    }

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

                    .dokumen-container .row>.col-xl-4 {
                        flex: 0 0 100%;
                        max-width: 100%;
                    }

                    .dokumen-icon-wrapper {
                        width: 50px;
                        height: 50px;
                    }

                    .dokumen-icon-wrapper i {
                        font-size: 1.5rem !important;
                    }

                    .modal-footer .btn-group {
                        flex-direction: column;
                        width: 100%;
                    }

                    .modal-footer .btn {
                        width: 100%;
                        margin-bottom: 0.5rem;
                    }

                    .stat-icon-wrapper {
                        width: 50px;
                        height: 50px;
                    }

                    .stat-icon-wrapper i {
                        font-size: 1.25rem !important;
                    }

                    .dokumen-container h3 {
                        font-size: 1.5rem;
                    }
                }

                @media (min-width: 769px) and (max-width: 992px) {
                    .dokumen-container .row>.col-xl-4 {
                        flex: 0 0 50%;
                        max-width: 50%;
                    }
                }

                @media (max-width: 576px) {
                    .custom-badge {
                        padding: 0.25rem 0.4rem;
                        font-size: 0.7rem;
                    }

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

                    .dokumen-card .card-body {
                        padding: 1rem !important;
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

                    .dokumen-container .row>.col-xl-4 {
                        margin-bottom: 1rem;
                    }
                }

                /* Aksi Perubahan Styling */
    .aksi-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        border-left: 4px solid #ffc107;
    }

    .aksi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        border-color: #ffc107;
    }

    .badge-number {
        flex-shrink: 0;
    }

    .badge-number .badge {
        font-size: 0.9rem;
        font-weight: 600;
    }

    .abstrak-section {
        background-color: #f8f9fa;
        padding: 12px;
        border-radius: 8px;
        margin-top: 8px;
    }

    .dokumen-item, .link-item {
        transition: all 0.2s ease;
        background-color: #f8f9fa;
    }

    .dokumen-item:hover, .link-item:hover {
        background-color: #e9ecef;
        transform: translateY(-2px);
    }

    .dokumen-icon, .link-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .aksi-card {
            margin-bottom: 1rem;
        }

        .dokumen-item, .link-item {
            margin-bottom: 0.5rem;
        }
    }
            </style>
@endsection