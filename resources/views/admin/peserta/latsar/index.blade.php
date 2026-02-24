@extends('admin.partials.layout')

@section('title', 'Peserta LATSAR - Sistem Pelatihan')

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
                        <h1 class="text-white mb-1">Peserta {{ $jenisPelatihan->nama_pelatihan ?? 'LATSAR' }}</h1>
                        <p class="text-white-50 mb-0">Kelola peserta LATSAR</p>
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
                            {{ $statsData->where('status_pendaftaran', 'Menunggu Verifikasi')->count() }}
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
                            {{ $statsData->where('status_pendaftaran', 'Diterima')->count() }}
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
                            {{ $statsData->where('status_pendaftaran', 'Ditolak')->count() }}
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
                            {{ $statsData->where('status_pendaftaran', 'Lulus')->count() }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Toolbar (muncul saat ada checkbox dipilih) -->
<div id="bulkActionToolbar" class="bulk-toolbar d-none mb-3">
    <div class="card border-0 shadow-sm" style="border-left: 4px solid #dc3545 !important; border-radius: 10px;">
        <div class="card-body py-2 px-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="bulk-count-badge">
                        <span class="badge bg-danger rounded-pill px-3 py-2 fs-6 fw-bold" id="selectedCount">0</span>
                        <span class="ms-2 fw-semibold text-dark">peserta dipilih</span>
                    </div>
                    <div class="vr d-none d-md-block"></div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="cancelSelection">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-danger btn-lift shadow-sm" id="bulkDeleteBtn">
                        <i class="fas fa-trash-alt me-2"></i>
                        Hapus <span id="bulkDeleteCount">0</span> Peserta
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Filter Section -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form id="filterForm" method="GET" class="row g-3 align-items-end">
            <!-- Filter Angkatan -->
            <div class="col-md-3">
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
            
            <!-- Filter Kategori -->
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted mb-1">
                    <i class="fas fa-tags me-1"></i> Kategori
                </label>
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    <option value="PNBP" {{ request('kategori') == 'PNBP' ? 'selected' : '' }}>PNBP</option>
                    <option value="FASILITASI" {{ request('kategori') == 'FASILITASI' ? 'selected' : '' }}>Fasilitasi</option>
                </select>
            </div>

            <!-- Filter Status -->
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted mb-1">
                    <i class="fas fa-info-circle me-1"></i> Status
                </label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="Menunggu Verifikasi" {{ request('status') == 'Menunggu Verifikasi' ? 'selected' : '' }}>
                        Menunggu Verifikasi
                    </option>
                    <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>
                        Diterima
                    </option>
                    <option value="Lulus" {{ request('status') == 'Lulus' ? 'selected' : '' }}>
                        Lulus
                    </option>
                </select>
            </div>

            <!-- ✅ SEARCH INPUT (Server-Side) -->
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-muted mb-1">
                    <i class="fas fa-search me-1"></i> Cari Peserta
                </label>
                <div class="input-group">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Nama, NIP, atau Instansi..." 
                           value="{{ request('search') }}">
                    @if(request('search'))
                        <a href="{{ route('peserta.index', ['jenis' => request()->route('jenis')]) }}" 
                           class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Tombol Filter -->
            <div class="col-md-1">
                <button type="submit" class="btn btn-filter-primary w-100">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
            </div>
        </form>
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
                            @if(request('search'))
                                Ditemukan {{ $pendaftaran->total() }} peserta dengan kata kunci "{{ request('search') }}"
                            @else
                                Menampilkan {{ $pendaftaran->count() }} dari {{ $pendaftaran->total() }} peserta
                            @endif
                        </small>
                    </div>
                    {{-- <div class="col-md-6">
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
                    </div> --}}
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="pesertaTable">
                        <thead>
                            <tr class="table-light">
                                <th width="5%" class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input select-all-checkbox" type="checkbox" id="selectAll" 
                                                title="Pilih semua peserta di halaman ini">
                                        </div>
                                        <span>No</span>
                                    </div>
                                </th>
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
                                                                                                                                                    <td class="ps-4">
                                                                                                                                                        <div class="d-flex align-items-center gap-2">
                                                                                                                                                            <div class="form-check mb-0">
                                                                                                                                                                <input class="form-check-input peserta-checkbox" type="checkbox" 
                                                                                                                                                                    value="{{ $daftar->id }}"
                                                                                                                                                                    data-name="{{ $peserta->nama_lengkap }}"
                                                                                                                                                                    data-jenis="{{ request()->route('jenis') }}">
                                                                                                                                                            </div>
                                                                                                                                                            <span class="fw-semibold">{{ $pendaftaran->firstItem() + $index }}</span>
                                                                                                                                                        </div>
                                                                                                                                                    </td>
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
        
        @if ($daftar->status_pendaftaran == 'Diterima')
            {{-- Tombol Kirim Ulang Info Akun --}}
            <button type="button" class="btn btn-sm btn-outline-success resend-account-info"
                data-id="{{ $daftar->id }}" 
                data-name="{{ $peserta->nama_lengkap }}"
                data-bs-toggle="tooltip" title="Kirim Ulang Info Akun ke WhatsApp">
                <i class="fab fa-whatsapp me-1"></i> Kirim Info Akun
            </button>
        @else
            {{-- Tombol Verifikasi Status --}}
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
            <div class="col-md-6 mb-2 mb-md-0">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Menampilkan <strong>{{ $pendaftaran->firstItem() }}</strong> 
                    sampai <strong>{{ $pendaftaran->lastItem() }}</strong> 
                    dari <strong>{{ $pendaftaran->total() }}</strong> peserta
                    @if(request('angkatan') || request('kategori') || request('status'))
                        <span class="text-primary">(terfilter)</span>
                    @endif
                </small>
            </div>
            <div class="col-md-6">
                <!-- Custom Compact Pagination -->
                @if ($pendaftaran->hasPages())
                    <nav aria-label="Peserta pagination">
                        <ul class="pagination pagination-sm justify-content-md-end justify-content-center mb-0">
                            {{-- Previous Page Link --}}
                            @if ($pendaftaran->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $pendaftaran->previousPageUrl() }}" rel="prev">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @php
                                $start = max($pendaftaran->currentPage() - 2, 1);
                                $end = min($start + 4, $pendaftaran->lastPage());
                                $start = max($end - 4, 1);
                            @endphp

                            {{-- First Page --}}
                            @if($start > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $pendaftaran->url(1) }}">1</a>
                                </li>
                                @if($start > 2)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                            @endif

                            {{-- Page Numbers --}}
                            @for ($i = $start; $i <= $end; $i++)
                                @if ($i == $pendaftaran->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $i }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $pendaftaran->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endif
                            @endfor

                            {{-- Last Page --}}
                            @if($end < $pendaftaran->lastPage())
                                @if($end < $pendaftaran->lastPage() - 1)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ $pendaftaran->url($pendaftaran->lastPage()) }}">
                                        {{ $pendaftaran->lastPage() }}
                                    </a>
                                </li>
                            @endif

                            {{-- Next Page Link --}}
                            @if ($pendaftaran->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $pendaftaran->nextPageUrl() }}" rel="next">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                @endif
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
                            Detail Peserta Latsar
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
                            <i class="fas fa-lightbulb me-2"></i>Aktualisasi
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
                            {{-- <option value="Ditolak">Ditolak</option> --}}
                            <option value="Lulus">Lulus</option>
                        </select>
                    </div>

                    {{-- <div class="mb-4 text-start">
                        <label class="form-label fw-semibold">Catatan (Opsional)</label>
                        <textarea class="form-control" name="catatan_verifikasi" id="catatanInput" rows="3"
                            placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div> --}}
{{-- 
                    <p class="text-muted small mb-4">
                        <i class="fas fa-info-circle me-1"></i>
                        Perubahan status akan tercatat di riwayat peserta
                    </p> --}}
                    
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

<!-- Bulk Delete Confirmation Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center px-4 pb-4">
                <div class="delete-icon mb-3">
                    <i class="fas fa-exclamation-triangle fa-4x" style="color: #ff4757;"></i>
                </div>
                <h4 class="modal-title mb-2 fw-bold" id="bulkDeleteModalLabel">Konfirmasi Hapus Massal</h4>
                <p class="text-muted mb-1">Anda akan menghapus <strong id="bulkDeleteCountModal" class="text-danger">0</strong> peserta:</p>
                
                <!-- Preview list peserta yang dipilih -->
                <div id="bulkDeletePreview" class="text-start bg-light rounded-3 p-3 mb-4 mt-3" 
                     style="max-height: 200px; overflow-y: auto; font-size: 0.875rem;">
                </div>
                
                <div class="alert alert-danger d-flex align-items-start text-start py-2" role="alert">
                    <i class="fas fa-exclamation-circle me-2 mt-1 flex-shrink-0"></i>
                    <div>
                        <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan. 
                        Semua data, dokumen, akun user, dan relasi peserta akan dihapus permanen.
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Batal
                </button>
                <button type="button" class="btn btn-danger px-4 btn-lift" id="confirmBulkDelete">
                    <i class="fas fa-trash-alt me-2"></i> Ya, Hapus Semua
                </button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ================================================================
    // INITIALIZE TOOLTIPS
    // ================================================================
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // ================================================================
    // MODALS
    // ================================================================
    const detailModal     = new bootstrap.Modal(document.getElementById('detailModal'));
    const statusModal     = new bootstrap.Modal(document.getElementById('statusModal'));
    const deleteModal     = new bootstrap.Modal(document.getElementById('deleteModal'));
    const bulkDeleteModal = new bootstrap.Modal(document.getElementById('bulkDeleteModal'));

    // ================================================================
    // STATUS FORM ELEMENTS
    // ================================================================
    const statusForm      = document.getElementById('statusForm');
    const statusSubmitBtn = document.getElementById('statusSubmitBtn');
    const statusCancelBtn = document.getElementById('statusCancelBtn');
    const statusLoading   = document.getElementById('statusLoading');

    // ================================================================
    // VIEW DETAIL
    // ================================================================
    document.querySelectorAll('.view-detail').forEach(button => {
        button.addEventListener('click', function () {
            const pendaftaranId = this.getAttribute('data-id');
            loadAllDetailData(pendaftaranId);
            detailModal.show();
        });
    });

    // ================================================================
    // SWAP ANGKATAN
    // ================================================================
    document.querySelectorAll('.swap-angkatan').forEach(button => {
        button.addEventListener('click', function () {
            const pendaftaranId = this.getAttribute('data-id');
            const jenis         = this.getAttribute('data-jenis');
            window.location.href = `/peserta/${jenis}/${pendaftaranId}/swap`;
        });
    });

    // ================================================================
    // UPDATE STATUS
    // ================================================================
    document.querySelectorAll('.update-status').forEach(button => {
        button.addEventListener('click', function () {
            const pendaftaranId = this.getAttribute('data-id');
            const currentStatus = this.getAttribute('data-status');

            const form = document.getElementById('statusForm');
            form.action = `/peserta/update-status/${pendaftaranId}`;

            const currentStatusElement = document.getElementById('currentStatus');
            currentStatusElement.innerHTML = `
                <span class="badge custom-badge ${getStatusColor(currentStatus)}">
                    <i class="fas ${getStatusIcon(currentStatus)} me-1"></i>
                    ${formatStatusText(currentStatus)}
                </span>
            `;

            document.getElementById('statusSelect').value = currentStatus;

            statusLoading.classList.add('d-none');
            statusSubmitBtn.disabled  = false;
            statusCancelBtn.disabled  = false;
            statusSubmitBtn.innerHTML = '<i class="fas fa-save me-2"></i> Simpan';

            statusModal.show();
        });
    });

    // ================================================================
    // DELETE PESERTA (SINGLE)
    // ================================================================
    const deleteForm        = document.getElementById('deleteForm');
    const deletePesertaName = document.getElementById('deletePesertaName');
    const deleteSubmitBtn   = document.getElementById('deleteSubmitBtn');

    document.querySelectorAll('.delete-peserta').forEach(button => {
        button.addEventListener('click', function () {
            const pesertaId   = this.getAttribute('data-id');
            const pesertaName = this.getAttribute('data-name');
            const jenis       = this.getAttribute('data-jenis');

            deletePesertaName.textContent = pesertaName;
            deleteForm.action = `/peserta/${jenis}/${pesertaId}`;
            deleteModal.show();
        });
    });

    if (deleteForm && deleteSubmitBtn) {
        deleteForm.addEventListener('submit', function () {
            deleteSubmitBtn.disabled  = true;
            deleteSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menghapus...';
        });
    }

    // ================================================================
    // STATUS FORM SUBMIT (AJAX)
    // ================================================================
    statusForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (statusSubmitBtn.getAttribute('data-submitting') === 'true') return;

        statusSubmitBtn.setAttribute('data-submitting', 'true');
        statusSubmitBtn.disabled  = true;
        statusCancelBtn.disabled  = true;
        statusSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';

        try {
            const formData = new FormData(this);
            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                statusSubmitBtn.innerHTML = '<i class="fas fa-check me-2"></i> Berhasil!';
                statusSubmitBtn.classList.remove('btn-primary');
                statusSubmitBtn.classList.add('btn-success');

                showAlert('success', result.message);

                if (result.wa_link) {
                    setTimeout(() => {
                        statusModal.hide();
                        showWhatsAppModal(result.wa_link, result.peserta_name);
                    }, 1500);
                } else {
                    setTimeout(() => {
                        statusModal.hide();
                        setTimeout(() => window.location.reload(), 500);
                    }, 1500);
                }
            } else {
                statusSubmitBtn.removeAttribute('data-submitting');
                statusSubmitBtn.disabled  = false;
                statusCancelBtn.disabled  = false;
                statusSubmitBtn.innerHTML = '<i class="fas fa-save me-2"></i> Simpan';
                showAlert('error', result.message || 'Terjadi kesalahan');
            }
        } catch (error) {
            statusSubmitBtn.removeAttribute('data-submitting');
            statusSubmitBtn.disabled  = false;
            statusCancelBtn.disabled  = false;
            statusSubmitBtn.innerHTML = '<i class="fas fa-save me-2"></i> Simpan';
            showAlert('error', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
            console.error('Error:', error);
        }
    });

    // Reset tombol ketika status modal ditutup
    document.getElementById('statusModal').addEventListener('hidden.bs.modal', function () {
        statusSubmitBtn.removeAttribute('data-submitting');
        statusSubmitBtn.disabled = false;
        statusSubmitBtn.classList.remove('btn-success');
        statusSubmitBtn.classList.add('btn-primary');
        statusSubmitBtn.innerHTML = '<i class="fas fa-save me-2"></i> Simpan';
    });

    // ================================================================
    // WHATSAPP MODAL
    // ================================================================
    function showWhatsAppModal(waLink, pesertaName) {
        const oldModal = document.getElementById('whatsappModal');
        if (oldModal) oldModal.remove();

        document.body.insertAdjacentHTML('beforeend', `
            <div class="modal fade" id="whatsappModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header border-0 bg-success text-white">
                            <h5 class="modal-title fw-bold">
                                <i class="fab fa-whatsapp me-2"></i>Kirim Informasi via WhatsApp
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center p-4">
                            <div class="wa-icon mb-3">
                                <i class="fab fa-whatsapp fa-5x text-success"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Status Berhasil Diperbarui!</h5>
                            <p class="text-muted mb-4">
                                Klik tombol di bawah untuk mengirim informasi akun ke <strong>${pesertaName}</strong> via WhatsApp.
                            </p>
                            <div class="d-grid gap-2">
                                <a href="${waLink}" target="_blank" class="btn btn-success btn-lg" id="openWhatsAppBtn">
                                    <i class="fab fa-whatsapp me-2"></i>Buka WhatsApp
                                </a>
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    Nanti Saja
                                </button>
                            </div>
                            <small class="text-muted mt-3 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Pesan akan otomatis terisi, Anda tinggal klik kirim
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        `);

        const whatsappModal = new bootstrap.Modal(document.getElementById('whatsappModal'));
        whatsappModal.show();

        document.getElementById('openWhatsAppBtn').addEventListener('click', function () {
            setTimeout(() => {
                whatsappModal.hide();
                window.location.reload();
            }, 1000);
        });

        document.getElementById('whatsappModal').addEventListener('hidden.bs.modal', function () {
            setTimeout(() => window.location.reload(), 300);
        });
    }

    // ================================================================
    // RESEND ACCOUNT INFO
    // ================================================================
    document.querySelectorAll('.resend-account-info').forEach(button => {
        button.addEventListener('click', function () {
            const pendaftaranId = this.getAttribute('data-id');
            const pesertaName   = this.getAttribute('data-name');

            if (confirm(`Kirim ulang informasi akun ke ${pesertaName}?\n\nPassword baru akan di-generate dan dikirim via WhatsApp.`)) {
                resendAccountInfo(pendaftaranId, pesertaName);
            }
        });
    });

    async function resendAccountInfo(pendaftaranId, pesertaName) {
        const button       = document.querySelector(`.resend-account-info[data-id="${pendaftaranId}"]`);
        const originalHTML = button.innerHTML;
        button.disabled    = true;
        button.innerHTML   = '<i class="fas fa-spinner fa-spin me-1"></i> Mengirim...';

        try {
            const response = await fetch(`/peserta/resend-account-info/${pendaftaranId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (result.success) {
                button.innerHTML = '<i class="fas fa-check me-1"></i> Terkirim!';
                button.classList.remove('btn-outline-success');
                button.classList.add('btn-success');

                showAlert('success', result.message);

                if (result.wa_link) {
                    setTimeout(() => showWhatsAppModal(result.wa_link, pesertaName), 1000);
                }

                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.disabled  = false;
                    button.classList.remove('btn-success');
                    button.classList.add('btn-outline-success');
                }, 3000);

            } else {
                button.innerHTML = originalHTML;
                button.disabled  = false;
                showAlert('error', result.message || 'Gagal mengirim informasi akun');
            }
        } catch (error) {
            showAlert('error', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
            console.error('Error:', error);
            button.innerHTML = originalHTML;
            button.disabled  = false;
        }
    }

    // ================================================================
    // LOAD ALL DETAIL DATA
    // ================================================================
    async function loadAllDetailData(pendaftaranId) {
        try {
            const response = await fetch(`/peserta/detail/${pendaftaranId}`);
            const result   = await response.json();

            if (result.success) {
                const data = result.data;

                document.getElementById('detailModalSubtitle').textContent =
                    `${data.peserta.nip_nrp || 'NIP/NRP tidak tersedia'} • ${data.angkatan?.nama_angkatan || ''}`;

                updateStatusBadge(data.pendaftaran.status_pendaftaran);

                document.getElementById('detailTimestamp').textContent =
                    `Terakhir diperbarui: ${formatDateTime(new Date())}`;

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

    function loadDataPesertaContent(data) {
        document.getElementById('dataPesertaContent').innerHTML = generateDataPesertaHTML(data);
    }

    function loadDokumenContent(data) {
        const content = document.getElementById('dokumenContent');
        content.innerHTML = generateDokumenHTML(data);

        content.querySelectorAll('.view-document').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const path = this.getAttribute('data-path');
                if (path) window.open(`/preview-drive?path=${encodeURIComponent(path)}`, '_blank');
            });
        });
    }

    function loadAksiContent(data) {
        const content = document.getElementById('aksiContent');
        content.innerHTML = generateAksiHTML(data);

        content.querySelectorAll('.view-aksi-document').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const path = this.getAttribute('data-path');
                if (path) window.open(`/preview-drive?path=${encodeURIComponent(path)}`, '_blank');
            });
        });
    }

    function loadMentorContent(data) {
        document.getElementById('mentorContent').innerHTML = generateMentorHTML(data);
    }

    // ================================================================
    // GENERATE HTML — DATA PESERTA
    // ================================================================
    function generateDataPesertaHTML(data) {
        const peserta     = data.peserta;
        const kepegawaian = data.kepegawaian;

        return `
            <div class="detail-content">
                <div class="profile-header mb-4 pb-4 border-bottom">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="fw-bold text-primary mb-2">${peserta.nama_lengkap}</h4>
                            <h6 class="fw-bold mb-3 pb-2 border-bottom">
                                <i class="fas fa-id-card me-2 text-primary"></i>Informasi Pribadi
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4"><div class="info-item"><label class="text-muted small">Nama Panggilan</label><p class="fw-semibold mb-0">${peserta.nama_panggilan || '-'}</p></div></div>
                                <div class="col-md-4"><div class="info-item"><label class="text-muted small">NIP/NRP</label><p class="fw-semibold mb-0">${peserta.nip_nrp || '-'}</p></div></div>
                                <div class="col-md-4"><div class="info-item"><label class="text-muted small">Jenis Kelamin</label><p class="fw-semibold mb-0">${peserta.jenis_kelamin || '-'}</p></div></div>
                                <div class="col-md-4"><div class="info-item"><label class="text-muted small">Tempat/Tanggal Lahir</label><p class="fw-semibold mb-0">${peserta.tempat_lahir || '-'}, ${formatDate(peserta.tanggal_lahir)}</p></div></div>
                                <div class="col-md-4"><div class="info-item"><label class="text-muted small">Agama</label><p class="fw-semibold mb-0">${peserta.agama || '-'}</p></div></div>
                                <div class="col-md-4"><div class="info-item"><label class="text-muted small">Status</label><p class="fw-semibold mb-0">${peserta.status_perkawinan || '-'}</p></div></div>
                                <div class="col-md-4"><div class="info-item"><label class="text-muted small">Nama Pasangan</label><p class="fw-semibold mb-0">${peserta.nama_pasangan || '-'}</p></div></div>
                                <div class="col-md-4"><div class="info-item"><label class="text-muted small">Alamat Rumah</label><p class="fw-semibold mb-0">${peserta.alamat_rumah || '-'}</p></div></div>
                                <div class="col-md-4"><div class="info-item"><label class="text-muted small">Email Pribadi</label><p class="fw-semibold mb-0">${peserta.email_pribadi || '-'}</p></div></div>
                                <div class="col-md-4"><div class="info-item"><label class="text-muted small">Nomor HP/WhatsApp</label><p class="fw-semibold mb-0">${peserta.nomor_hp || '-'}</p></div></div>
                                <div class="col-md-4"><div class="info-item"><label class="text-muted small">Olahraga/Hobi</label><p class="fw-semibold mb-0">${peserta.olahraga_hobi || '-'}</p></div></div>
                                <div class="col-md-4"><div class="info-item"><label class="text-muted small">Merokok</label><p class="fw-semibold mb-0">${peserta.perokok || '-'}</p></div></div>
                                <div class="col-md-4"><div class="info-item"><label class="text-muted small">Pendidikan Terakhir</label><p class="fw-semibold mb-0">${peserta.pendidikan_terakhir || '-'}</p></div></div>
                                <div class="col-md-4"><div class="info-item"><label class="text-muted small">Bidang Studi</label><p class="fw-semibold mb-0">${peserta.bidang_studi || '-'}</p></div></div>
                                <div class="col-md-4"><div class="info-item"><label class="text-muted small">Bidang Keahlian</label><p class="fw-semibold mb-0">${peserta.bidang_keahlian || '-'}</p></div></div>
                                <div class="col-md-4"><div class="info-item"><label class="text-muted small">Ukuran Baju Kaos</label><p class="fw-semibold mb-0">${peserta.ukuran_kaos || '-'}</p></div></div>
                                <div class="col-md-4"><div class="info-item"><label class="text-muted small">Ukuran Baju Taktikal</label><p class="fw-semibold mb-0">${peserta.ukuran_training || '-'}</p></div></div>
                                <div class="col-md-4"><div class="info-item"><label class="text-muted small">Ukuran Celana</label><p class="fw-semibold mb-0">${peserta.ukuran_celana || '-'}</p></div></div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="avatar-placeholder rounded-3 bg-primary bg-opacity-10 p-4 d-inline-flex flex-column align-items-center">
                                <i class="fas fa-user fa-4x text-primary mb-2"></i>
                                <label class="text-muted small mt-2">NDH</label>
                                <p class="fw-semibold mb-0 text-center">${peserta.ndh ?? '-'}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="info-section mb-4">
                    <h6 class="fw-bold mb-3 pb-2 border-bottom">
                        <i class="fas fa-building me-2 text-primary"></i>Informasi Kepegawaian
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6"><div class="info-item"><label class="text-muted small">Asal Instansi</label><p class="fw-semibold mb-0">${kepegawaian?.asal_instansi || '-'}</p></div></div>
                        <div class="col-md-6"><div class="info-item"><label class="text-muted small">Unit Kerja/Detail Instansi</label><p class="fw-semibold mb-0">${kepegawaian?.unit_kerja || '-'}</p></div></div>
                        <div class="col-md-6"><div class="info-item"><label class="text-muted small">Provinsi</label><p class="fw-semibold mb-0">${data.provinsi?.name || '-'}</p></div></div>
                        <div class="col-md-6"><div class="info-item"><label class="text-muted small">Kabupaten/Kota</label><p class="fw-semibold mb-0">${data.kabupaten?.name || '-'}</p></div></div>
                        <div class="col-md-6"><div class="info-item"><label class="text-muted small">Jabatan</label><p class="fw-semibold mb-0">${kepegawaian?.jabatan || '-'}</p></div></div>
                        <div class="col-md-6"><div class="info-item"><label class="text-muted small">Pangkat/Golongan</label><p class="fw-semibold mb-0">${kepegawaian?.pangkat || '-'} ${kepegawaian?.golongan_ruang ? '/ ' + kepegawaian.golongan_ruang : ''}</p></div></div>
                        <div class="col-md-6"><div class="info-item"><label class="text-muted small">Nomor SK CPNS</label><p class="fw-semibold mb-0">${kepegawaian?.nomor_sk_cpns || '-'}</p></div></div>
                        <div class="col-md-6"><div class="info-item"><label class="text-muted small">Tanggal SK CPNS</label><p class="fw-semibold mb-0">${kepegawaian?.tanggal_sk_cpns || '-'}</p></div></div>
                        <div class="col-md-6"><div class="info-item"><label class="text-muted small">Email Kantor</label><p class="fw-semibold mb-0">${kepegawaian?.email_kantor || '-'}</p></div></div>
                        <div class="col-md-6"><div class="info-item"><label class="text-muted small">Alamat Kantor</label><p class="fw-semibold mb-0">${kepegawaian?.alamat_kantor || '-'}</p></div></div>
                        <div class="col-md-6"><div class="info-item"><label class="text-muted small">Nomor Telepon Kantor</label><p class="fw-semibold mb-0">${kepegawaian?.nomor_telepon_kantor || '-'}</p></div></div>
                    </div>
                </div>
            </div>
        `;
    }

    // ================================================================
    // GENERATE HTML — DOKUMEN
    // ================================================================
    function generateDokumenHTML(data) {
        const peserta     = data.peserta;
        const kepegawaian = data.kepegawaian;
        const pendaftaran = data.pendaftaran;

        const dokumenList = [
            { title: 'KTP',             icon: 'fa-id-card',       color: 'success', file: peserta.file_ktp,                   description: 'Kartu Tanda Penduduk yang masih berlaku' },
            { title: 'Pasfoto',         icon: 'fa-camera',        color: 'primary', file: peserta.file_pas_foto,               description: 'Foto terbaru ukuran 4x6 dengan background merah' },
            { title: 'SK CPNS',         icon: 'fa-file-alt',      color: 'success', file: kepegawaian?.file_sk_cpns,           description: 'Surat Keputusan CPNS yang masih berlaku' },
            { title: 'SPMT',            icon: 'fa-award',         color: 'warning', file: kepegawaian?.file_spmt,              description: 'Surat Pernyataan Melaksanakan Tugas' },
            { title: 'SKP',             icon: 'fa-handshake',     color: 'info',    file: kepegawaian?.file_skp,               description: 'Sasaran Kinerja Pegawai' },
            { title: 'Surat Tugas',     icon: 'fa-tasks',         color: 'primary', file: pendaftaran.file_surat_tugas,        description: 'Surat tugas dari instansi asal' },
            { title: 'Surat Kesediaan', icon: 'fa-graduation-cap',color: 'success', file: pendaftaran.file_surat_kesediaan,    description: 'Surat kesediaan mengikuti pelatihan' },
            { title: 'Surat Sehat',     icon: 'fa-heartbeat',     color: 'warning', file: pendaftaran.file_surat_sehat,        description: 'Surat keterangan sehat dari dokter' },
        ];

        const totalDokumen    = dokumenList.length;
        const dokumenTersedia = dokumenList.filter(d => d.file).length;
        const dokumenBelum    = totalDokumen - dokumenTersedia;
        const persentase      = dokumenTersedia > 0 ? Math.round((dokumenTersedia / totalDokumen) * 100) : 0;

        return `
            <div class="dokumen-container">
                <div class="row mb-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="stat-card card border-0 shadow-sm"><div class="card-body text-center p-3">
                            <div class="stat-icon-wrapper bg-primary bg-opacity-10 rounded-circle p-3 mx-auto mb-2"><i class="fas fa-folder-open fa-xl text-primary"></i></div>
                            <h3 class="fw-bold text-primary mb-1">${totalDokumen}</h3><p class="text-muted small mb-0">Total Dokumen</p>
                        </div></div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="stat-card card border-0 shadow-sm"><div class="card-body text-center p-3">
                            <div class="stat-icon-wrapper bg-success bg-opacity-10 rounded-circle p-3 mx-auto mb-2"><i class="fas fa-check-circle fa-xl text-success"></i></div>
                            <h3 class="fw-bold text-success mb-1">${dokumenTersedia}</h3><p class="text-muted small mb-0">Dokumen Tersedia</p>
                        </div></div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="stat-card card border-0 shadow-sm"><div class="card-body text-center p-3">
                            <div class="stat-icon-wrapper bg-warning bg-opacity-10 rounded-circle p-3 mx-auto mb-2"><i class="fas fa-clock fa-xl text-warning"></i></div>
                            <h3 class="fw-bold text-warning mb-1">${dokumenBelum}</h3><p class="text-muted small mb-0">Belum Diunggah</p>
                        </div></div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="stat-card card border-0 shadow-sm"><div class="card-body text-center p-3">
                            <div class="stat-icon-wrapper bg-info bg-opacity-10 rounded-circle p-3 mx-auto mb-2"><i class="fas fa-chart-line fa-xl text-info"></i></div>
                            <h3 class="fw-bold text-info mb-1">${persentase}%</h3><p class="text-muted small mb-0">Kelengkapan</p>
                        </div></div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0">Kelengkapan Dokumen</h6>
                            <span class="badge bg-primary">${persentase}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: ${persentase}%"></div>
                        </div>
                    </div>
                </div>

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

                <div class="row g-4">
                    ${dokumenList.map(dokumen => dokumenCard(dokumen)).join('')}
                </div>
            </div>
        `;
    }

    function dokumenCard(dokumen) {
        if (dokumen.file) {
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
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        <i class="fas fa-check-circle me-1"></i>Tersedia
                                    </span>
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
                                    <span class="badge bg-danger bg-opacity-10 text-danger">
                                        <i class="fas fa-times-circle me-1"></i>Belum diunggah
                                    </span>
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

    // ================================================================
    // GENERATE HTML — AKSI PERUBAHAN
    // ================================================================
    function fileNameFromPath(path) {
        if (!path) return '';
        return path.split('?')[0].split('/').pop() || 'file.pdf';
    }

    function normalizeUrl(url) {
        if (!url) return null;
        if (/^https?:\/\//i.test(url)) return url;
        return `https://${url}`;
    }

    function generateAksiHTML(data) {
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

        const kategoriMap = {
            'pilihan1': { color: 'success', icon: 'fa-bolt', text: 'Pilihan 1' },
            'pilihan2': { color: 'info',    icon: 'fa-tag',  text: 'Pilihan 2' }
        };
        const kategori = kategoriMap[aksi.kategori_aksatika] || {
            color: 'secondary', icon: 'fa-question', text: aksi.kategori_aksatika || '-'
        };

        const videoUrl   = normalizeUrl(aksi.link_video);
        const majalahUrl = normalizeUrl(aksi.link_laporan_majalah);

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
                    <div class="col-xl-6 col-lg-12">
                        <div class="aksi-card card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="badge-number me-3">
                                        <span class="badge bg-primary rounded-circle p-2"
                                            style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
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
                                                <p class="mb-0 text-dark" style="line-height:1.5;">${aksi.abstrak}</p>
                                            </div>
                                        ` : ''}
                                    </div>
                                </div>
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
                </div>
            </div>
        `;
    }

    // ================================================================
    // GENERATE HTML — MENTOR
    // ================================================================
    function generateMentorHTML(data) {
        const mentor = data.mentor;

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
                <div class="mentor-profile card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="fw-bold text-primary mb-2">${mentor.nama_mentor}</h4>
                                <div class="row g-3">
                                    <div class="col-md-6"><div class="info-item"><label class="text-muted small">NIP/NRP</label><p class="fw-semibold mb-0">${mentor.nip_mentor || '-'}</p></div></div>
                                    <div class="col-md-6"><div class="info-item"><label class="text-muted small">NPWP</label><p class="fw-semibold mb-0">${mentor.npwp_mentor || '-'}</p></div></div>
                                    <div class="col-md-6"><div class="info-item"><label class="text-muted small">Jabatan</label><p class="fw-semibold mb-0">${mentor.jabatan_mentor || '-'}</p></div></div>
                                    <div class="col-md-6"><div class="info-item"><label class="text-muted small">Nomor Rekening</label><p class="fw-semibold mb-0">${mentor.nomor_rekening || '-'}</p></div></div>
                                    <div class="col-md-6"><div class="info-item"><label class="text-muted small">Email</label><p class="fw-semibold mb-0">${mentor.email_mentor || '-'}</p></div></div>
                                    <div class="col-md-6"><div class="info-item"><label class="text-muted small">Telepon</label><p class="fw-semibold mb-0">${mentor.nomor_hp_mentor || '-'}</p></div></div>
                                    <div class="col-md-6"><div class="info-item"><label class="text-muted small">Golongan Ruang</label><p class="fw-semibold mb-0">${mentor.golongan || '-'}</p></div></div>
                                    <div class="col-md-6"><div class="info-item"><label class="text-muted small">Pangkat</label><p class="fw-semibold mb-0">${mentor.pangkat || '-'}</p></div></div>
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

    // ================================================================
    // STATUS HELPER FUNCTIONS
    // ================================================================
    function getStatusIcon(status) {
        const icons = {
            'menunggu verifikasi': 'fa-clock',
            'diterima':            'fa-check-circle',
            'ditolak':             'fa-times-circle',
            'lulus':               'fa-graduation-cap'
        };
        return icons[status.toLowerCase()] || 'fa-question-circle';
    }

    function getStatusColor(status) {
        const colors = {
            'menunggu verifikasi': 'status-warning',
            'diterima':            'status-info',
            'ditolak':             'status-danger',
            'lulus':               'status-success'
        };
        return colors[status.toLowerCase()] || 'status-secondary';
    }

    function formatStatusText(status) {
        const texts = {
            'menunggu verifikasi': 'Menunggu Verifikasi',
            'diterima':            'Diterima',
            'ditolak':             'Ditolak',
            'lulus':               'Lulus'
        };
        return texts[status.toLowerCase()] || status;
    }

    function updateStatusBadge(status) {
        const badge     = document.getElementById('detailStatusBadge');
        badge.className = `badge custom-badge ${getStatusColor(status)}`;
        badge.innerHTML = `<i class="fas ${getStatusIcon(status)} me-1"></i>${formatStatusText(status)}`;
    }

    function formatDate(dateString) {
        if (!dateString) return '-';
        try {
            return new Date(dateString).toLocaleDateString('id-ID', {
                day: '2-digit', month: 'long', year: 'numeric'
            });
        } catch (e) { return '-'; }
    }

    function formatDateTime(date) {
        return date.toLocaleDateString('id-ID', {
            day: '2-digit', month: 'short', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
    }

    // ================================================================
    // ✅ SHOW ALERT — expose ke window supaya bisa diakses dari mana saja
    // ================================================================
    function showAlert(type, message) {
        const oldAlerts = document.querySelectorAll('.alert-container .alert');
        oldAlerts.forEach(alert => {
            try { bootstrap.Alert.getOrCreateInstance(alert).close(); } catch (e) {}
        });

        const alertDiv  = document.createElement('div');
        const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        const bsType    = type === 'success' ? 'success' : 'danger';
        const title     = type === 'success' ? 'Sukses!' : 'Error!';

        alertDiv.className = `alert alert-${bsType} alert-dismissible fade show shadow-sm d-flex align-items-center`;
        alertDiv.setAttribute('role', 'alert');
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

        setTimeout(() => {
            if (alertDiv.parentNode) {
                try { bootstrap.Alert.getOrCreateInstance(alertDiv).close(); } catch (e) {}
            }
        }, 6000);
    }

    // ✅ Wajib: expose ke window agar bisa dipanggil dari luar DOMContentLoaded
    window.showAlert = showAlert;

    function showDetailError() {
        document.getElementById('dataPesertaContent').innerHTML = `
            <div class="text-center py-5">
                <div class="empty-state-icon mb-3">
                    <i class="fas fa-exclamation-circle fa-4x" style="color: #e9ecef;"></i>
                </div>
                <h5 class="text-muted mb-2">Gagal memuat data</h5>
                <p class="text-muted">Silakan coba lagi</p>
            </div>
        `;
    }

    // ================================================================
    // AUTO-HIDE SESSION ALERTS
    // ================================================================
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            if (alert.classList.contains('show')) {
                try { bootstrap.Alert.getOrCreateInstance(alert).close(); } catch (e) {}
            }
        }, 5000);
    });

    // ================================================================
    // HOVER EFFECTS TABLE ROWS
    // ================================================================
    document.querySelectorAll('.peserta-row').forEach(row => {
        row.addEventListener('mouseenter', function () {
            if (!this.classList.contains('row-selected')) {
                this.style.transform  = 'translateY(-2px)';
                this.style.transition = 'transform 0.2s ease';
            }
        });
        row.addEventListener('mouseleave', function () {
            this.style.transform = 'translateY(0)';
        });
    });

    // ================================================================
    // ✅ MULTI DELETE — semua di dalam DOMContentLoaded agar showAlert
    //    bisa langsung dipanggil tanpa window.showAlert
    // ================================================================
    const selectAllCheckbox    = document.getElementById('selectAll');
    const bulkActionToolbar    = document.getElementById('bulkActionToolbar');
    const selectedCountBadge   = document.getElementById('selectedCount');
    const bulkDeleteCountSpan  = document.getElementById('bulkDeleteCount');
    const bulkDeleteBtn        = document.getElementById('bulkDeleteBtn');
    const cancelSelection      = document.getElementById('cancelSelection');
    const confirmBulkDeleteBtn = document.getElementById('confirmBulkDelete');

    function getAllCheckboxes() {
        return document.querySelectorAll('.peserta-checkbox');
    }

    function getSelectedCheckboxes() {
        return document.querySelectorAll('.peserta-checkbox:checked');
    }

    function updateBulkToolbar() {
        const selected = getSelectedCheckboxes();
        const count    = selected.length;
        const all      = getAllCheckboxes();

        if (count > 0) {
            bulkActionToolbar.classList.remove('d-none');
            selectedCountBadge.textContent  = count;
            bulkDeleteCountSpan.textContent = count;
            const countModal = document.getElementById('bulkDeleteCountModal');
            if (countModal) countModal.textContent = count;
        } else {
            bulkActionToolbar.classList.add('d-none');
        }

        if (selectAllCheckbox && all.length > 0) {
            selectAllCheckbox.checked       = count === all.length;
            selectAllCheckbox.indeterminate = count > 0 && count < all.length;
        }
    }

    // Select All
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            getAllCheckboxes().forEach(cb => {
                cb.checked = this.checked;
                const row  = cb.closest('.peserta-row');
                if (row) row.classList.toggle('row-selected', this.checked);
            });
            updateBulkToolbar();
        });
    }

    // Individual checkbox (event delegation)
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('peserta-checkbox')) {
            const row = e.target.closest('.peserta-row');
            if (row) row.classList.toggle('row-selected', e.target.checked);
            updateBulkToolbar();
        }
    });

    // Batal pilihan
    if (cancelSelection) {
        cancelSelection.addEventListener('click', function () {
            getAllCheckboxes().forEach(cb => {
                cb.checked = false;
                const row  = cb.closest('.peserta-row');
                if (row) row.classList.remove('row-selected');
            });
            if (selectAllCheckbox) {
                selectAllCheckbox.checked       = false;
                selectAllCheckbox.indeterminate = false;
            }
            updateBulkToolbar();
        });
    }

    // Buka modal konfirmasi
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function () {
            const selected = getSelectedCheckboxes();
            if (selected.length === 0) return;

            const preview = document.getElementById('bulkDeletePreview');
            if (preview) {
                preview.innerHTML = '';
                selected.forEach((cb, i) => {
                    const name = cb.getAttribute('data-name') || 'Peserta';
                    const item = document.createElement('div');
                    item.className = 'preview-item';
                    item.innerHTML = `
                        <i class="fas fa-user-times text-danger"></i>
                        <span><strong>${i + 1}.</strong> ${name}</span>
                    `;
                    preview.appendChild(item);
                });
            }

            bulkDeleteModal.show();
        });
    }

    // ✅ Eksekusi hapus massal
    if (confirmBulkDeleteBtn) {
        confirmBulkDeleteBtn.addEventListener('click', async function () {
            const selected = getSelectedCheckboxes();
            if (selected.length === 0) return;

            const ids    = Array.from(selected).map(cb => cb.value);
            const jenis  = selected[0].getAttribute('data-jenis');
            const jumlah = ids.length;

            // Loading state
            confirmBulkDeleteBtn.disabled  = true;
            confirmBulkDeleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menghapus...';

            try {
                const response = await fetch(`/peserta/${jenis}/bulk-delete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type':     'application/json',
                        'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ ids })
                });

                const result = await response.json();

                // Tutup modal dulu
                bulkDeleteModal.hide();

                if (result.success) {
                    // Animasi hapus baris dari tabel
                    selected.forEach(cb => {
                        const row = cb.closest('tr');
                        if (row) {
                            row.style.transition = 'all 0.35s ease';
                            row.style.opacity    = '0';
                            row.style.transform  = 'translateX(-30px)';
                            row.style.background = '#fff5f5';
                            setTimeout(() => row.remove(), 350);
                        }
                    });

                    // Reset checkbox & toolbar
                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked       = false;
                        selectAllCheckbox.indeterminate = false;
                    }
                    updateBulkToolbar();

                    // ✅ Tampilkan notifikasi sukses
                    setTimeout(() => {
                        showAlert('success', result.message || `${jumlah} peserta berhasil dihapus.`);

                        if (result.failed > 0) {
                            setTimeout(() => {
                                showAlert('error', `${result.failed} peserta gagal dihapus.`);
                            }, 600);
                        }
                    }, 400);

                    // Reload setelah 2.5 detik
                    setTimeout(() => window.location.reload(), 2500);

                } else {
                    // ✅ Tampilkan notifikasi error
                    showAlert('error', result.message || 'Gagal menghapus peserta. Silakan coba lagi.');
                }

            } catch (error) {
                bulkDeleteModal.hide();
                showAlert('error', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
                console.error('Bulk delete error:', error);
            } finally {
                confirmBulkDeleteBtn.disabled  = false;
                confirmBulkDeleteBtn.innerHTML = '<i class="fas fa-trash-alt me-2"></i> Ya, Hapus Semua';
            }
        });
    }

    // Reset tombol saat modal bulk delete ditutup
    document.getElementById('bulkDeleteModal').addEventListener('hidden.bs.modal', function () {
        if (confirmBulkDeleteBtn) {
            confirmBulkDeleteBtn.disabled  = false;
            confirmBulkDeleteBtn.innerHTML = '<i class="fas fa-trash-alt me-2"></i> Ya, Hapus Semua';
        }
    });

}); // akhir DOMContentLoaded
</script>

<style>
    /* ================================================================
       TOOLTIP FIX
    ================================================================ */
    .tooltip { position: fixed !important; z-index: 9999 !important; }
    .btn-action { position: relative !important; }
    .btn-action:hover { transform: none !important; }
    .table-responsive { overflow-x: auto; overflow-y: visible !important; }
    .table { margin-bottom: 0; }

    /* ================================================================
       CUSTOM STATUS BADGES
    ================================================================ */
    .custom-badge {
        border-radius: 8px; font-weight: 500; letter-spacing: 0.3px;
        padding: 0.5rem 0.75rem; border: none; font-size: 0.85rem;
        display: inline-flex; align-items: center; justify-content: center;
        transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .custom-badge:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
    .status-warning  { background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%) !important; color: #212529 !important; }
    .status-info     { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important; color: white !important; }
    .status-danger   { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important; color: white !important; }
    .status-success  { background: linear-gradient(135deg, #28a745 0%, #218838 100%) !important; color: white !important; }
    .status-secondary{ background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%) !important; color: white !important; }

    /* ================================================================
       PAGE HEADER
    ================================================================ */
    .page-header { padding: 2rem; margin-bottom: 1.5rem; border-radius: 12px; box-shadow: 0 4px 20px rgba(40,84,150,0.15); }
    .icon-wrapper { width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; }

    /* ================================================================
       STAT CARDS
    ================================================================ */
    .stat-card { border-radius: 12px; transition: transform 0.3s ease, box-shadow 0.3s ease; border: 1px solid #e9ecef; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important; }
    .stat-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }

    /* ================================================================
       FILTER BUTTON
    ================================================================ */
    .btn-filter-primary { background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%); border: none; color: white; transition: all 0.3s ease; }
    .btn-filter-primary:hover { background: linear-gradient(135deg, #1e3d6f 0%, #2d5499 100%); transform: translateY(-2px); color: white; box-shadow: 0 8px 25px rgba(40,84,150,0.4); }

    /* ================================================================
       USER AVATAR & ACTION BUTTONS
    ================================================================ */
    .user-avatar { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.1rem; box-shadow: 0 4px 8px rgba(40,84,150,0.2); }
    .btn-action { border-radius: 8px; padding: 0.375rem 0.75rem; margin: 0 2px; transition: all 0.2s ease; border-width: 2px; }
    .btn-action:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .btn-lift { transition: transform 0.2s ease; }
    .btn-lift:hover { transform: translateY(-2px); }

    /* ================================================================
       TABLE
    ================================================================ */
    .table th { border-bottom: 2px solid var(--primary-light); font-weight: 600; color: #285496; background-color: #f8fafc; padding: 1rem; }
    .table td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #e9ecef; }
    .peserta-row:hover { background-color: rgba(40,84,150,0.03) !important; }

    /* ================================================================
       PAGINATION
    ================================================================ */
    .pagination .page-link { color: #285496; border: 1px solid #dee2e6; border-radius: 6px; margin: 0 2px; font-weight: 500; }
    .pagination .page-link:hover { background-color: rgba(40,84,150,0.1); border-color: #285496; }
    .pagination .page-item.active .page-link { background-color: #285496; border-color: #285496; color: white; }
    .pagination .page-item.disabled .page-link { color: #6c757d; pointer-events: none; background-color: #f8f9fa; }
    .pagination-sm { gap: 0.25rem; }
    .pagination-sm .page-link { padding: 0.375rem 0.625rem; font-size: 0.875rem; border-radius: 6px; }

    /* ================================================================
       BUTTON COLORS
    ================================================================ */
    .btn-primary { background-color: #285496 !important; border-color: #285496 !important; }
    .btn-success { background-color: #28a745 !important; border-color: #28a745 !important; }
    .btn-warning { background-color: #ffc107 !important; border-color: #ffc107 !important; color: #212529 !important; }
    .btn-info    { background-color: #17a2b8 !important; border-color: #17a2b8 !important; }
    .btn-danger  { background-color: #dc3545 !important; border-color: #dc3545 !important; }

    /* ================================================================
       STATUS LOADING ANIMATION
    ================================================================ */
    #statusLoading { padding: 2rem; border-radius: 10px; background-color: rgba(255,255,255,0.95); margin: 1.5rem 0; text-align: center; animation: fadeIn 0.3s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* ================================================================
       DOKUMEN CARDS
    ================================================================ */
    .stat-icon-wrapper { width: 70px; height: 70px; display: flex; align-items: center; justify-content: center; }
    .progress { background-color: #e9ecef; border-radius: 4px; overflow: hidden; }
    .progress-bar { background: linear-gradient(90deg, #285496, #3a6bc7); transition: width 0.6s ease; }
    .dokumen-card { transition: all 0.3s ease; border-radius: 12px; overflow: hidden; border: 1px solid #e9ecef; height: 100%; }
    .dokumen-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important; border-color: #285496; }
    .dokumen-available { border-left: 4px solid #28a745; }
    .dokumen-missing { border-left: 4px solid #6c757d; opacity: 0.8; }
    .dokumen-card:hover.dokumen-missing { opacity: 1; border-color: #6c757d; }
    .dokumen-icon-wrapper { width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; flex-shrink: 0; }
    .dokumen-card:hover .dokumen-icon-wrapper { transform: scale(1.1); }
    .badge.bg-success.bg-opacity-10 { background-color: rgba(40,167,69,0.1) !important; color: #28a745 !important; border: 1px solid rgba(40,167,69,0.2); }
    .badge.bg-danger.bg-opacity-10  { background-color: rgba(220,53,69,0.1) !important;  color: #dc3545 !important; border: 1px solid rgba(220,53,69,0.2); }
    .badge.bg-primary { background-color: #285496 !important; color: white !important; }

    /* ================================================================
       AKSI PERUBAHAN
    ================================================================ */
    .aksi-card { transition: all 0.3s ease; border-radius: 12px; border-left: 4px solid #ffc107; }
    .aksi-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important; }
    .badge-number .badge { font-size: 0.9rem; font-weight: 600; }
    .abstrak-section { background-color: #f8f9fa; padding: 12px; border-radius: 8px; margin-top: 8px; }
    .dokumen-item, .link-item { transition: all 0.2s ease; background-color: #f8f9fa; }
    .dokumen-item:hover, .link-item:hover { background-color: #e9ecef; transform: translateY(-2px); }
    .dokumen-icon, .link-icon { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; }

    /* ================================================================
       WHATSAPP MODAL
    ================================================================ */
    #whatsappModal .wa-icon i { animation: bounce 2s infinite; }
    @keyframes bounce {
        0%,20%,50%,80%,100% { transform: translateY(0); }
        40% { transform: translateY(-20px); }
        60% { transform: translateY(-10px); }
    }

    /* ================================================================
       RESEND ACCOUNT INFO BUTTON
    ================================================================ */
    .btn-outline-success.resend-account-info { border-color: #25D366; color: #25D366; font-size: 0.75rem; padding: 0.25rem 0.5rem; }
    .btn-outline-success.resend-account-info:hover { background-color: #25D366; border-color: #25D366; color: white; transform: translateY(-1px); }
    .btn-outline-success.resend-account-info:disabled { opacity: 0.6; cursor: not-allowed; }
    .btn-success.resend-account-info { background-color: #25D366; border-color: #25D366; color: white; }

    /* ================================================================
       ✅ BULK ACTION / MULTI DELETE
    ================================================================ */
    .bulk-toolbar { animation: slideDown 0.3s ease; }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    .bulk-toolbar .card { background: linear-gradient(135deg, #fff5f5 0%, #fff 100%); }

    .form-check-input { width: 1.1em; height: 1.1em; cursor: pointer; border: 2px solid #ced4da; transition: all 0.2s ease; }
    .form-check-input:checked { background-color: #285496; border-color: #285496; }
    .form-check-input:focus { border-color: #285496; box-shadow: 0 0 0 0.2rem rgba(40,84,150,0.25); }
    .select-all-checkbox { width: 1.2em; height: 1.2em; }

    .peserta-row.row-selected { background-color: rgba(40,84,150,0.06) !important; border-left: 3px solid #285496; transition: all 0.2s ease; }

    #bulkDeletePreview .preview-item { display: flex; align-items: center; gap: 8px; padding: 4px 0; border-bottom: 1px solid #e9ecef; }
    #bulkDeletePreview .preview-item:last-child { border-bottom: none; }
    #bulkDeletePreview .preview-item i { color: #dc3545; flex-shrink: 0; }

    /* ================================================================
       RESPONSIVE
    ================================================================ */
    @media (max-width: 768px) {
        .custom-badge { padding: 0.35rem 0.5rem; font-size: 0.75rem; }
        .modal-dialog.modal-xl { margin: 0.5rem; }
        .nav-tabs { flex-wrap: nowrap; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .nav-tabs .nav-link { white-space: nowrap; font-size: 0.85rem; padding: 0.75rem 1rem; }
        .dokumen-container .row>.col-xl-4 { flex: 0 0 100%; max-width: 100%; }
        .dokumen-icon-wrapper { width: 50px; height: 50px; }
        .stat-icon-wrapper { width: 50px; height: 50px; }
        .aksi-card { margin-bottom: 1rem; }
        .modal-footer .btn-group { flex-direction: column; width: 100%; }
        .modal-footer .btn { width: 100%; margin-bottom: 0.5rem; }
        .pagination-sm .page-link { padding: 0.3rem 0.5rem; font-size: 0.8rem; }
        .pagination-sm { gap: 0.15rem; }
    }
    @media (min-width: 769px) and (max-width: 992px) {
        .dokumen-container .row>.col-xl-4 { flex: 0 0 50%; max-width: 50%; }
    }
    @media (max-width: 576px) {
        .custom-badge { padding: 0.25rem 0.4rem; font-size: 0.7rem; }
        .modal-header { padding: 1rem; }
        .dokumen-card .card-body { padding: 1rem !important; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.8rem; }
        .table th, .table td { padding: 0.75rem 0.5rem; font-size: 0.85rem; }
        .btn-action { padding: 0.25rem 0.5rem; margin: 1px; }
        .pagination .page-link { padding: 0.25rem 0.5rem; font-size: 0.8rem; margin: 1px; }
    }
</style>
@endsection