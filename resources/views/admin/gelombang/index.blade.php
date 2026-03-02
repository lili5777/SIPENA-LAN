@extends('admin.partials.layout')

@section('title', 'Manajemen Gelombang')

@section('content')
    <!-- Page Header -->
    <div class="page-header rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%); padding: 2rem;">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-layer-group fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">Manajemen Gelombang</h1>
                        <p class="text-white-50 mb-0">Kelola gelombang pelatihan dan angkatan yang terhubung</p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('gelombang.create') }}" class="btn btn-light shadow-sm">
                    <i class="fas fa-plus me-2"></i> Tambah Gelombang
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Section -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-check-circle fa-lg me-3"></i>
            <div class="flex-grow-1"><strong>Sukses!</strong> {{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-exclamation-circle fa-lg me-3"></i>
            <div class="flex-grow-1"><strong>Error!</strong> {{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-3">
            <form action="{{ route('gelombang.index') }}" method="GET" id="filterForm">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label small text-muted mb-1">
                            <i class="fas fa-graduation-cap me-1"></i> Filter Jenis Pelatihan
                        </label>
                        <select name="jenis_pelatihan" class="form-select form-select-sm" id="jenisPelatihanFilter">
                            <option value="">Semua Jenis Pelatihan</option>
                            @foreach($jenisPelatihan as $jp)
                                <option value="{{ $jp->id }}" {{ request('jenis_pelatihan') == $jp->id ? 'selected' : '' }}>
                                    {{ $jp->nama_pelatihan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label small text-muted mb-1">
                            <i class="fas fa-calendar me-1"></i> Filter Tahun
                        </label>
                        <select name="tahun" class="form-select form-select-sm">
                            <option value="">Semua Tahun</option>
                            @foreach($tahunList as $thn)
                                <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>
                                    {{ $thn }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label small text-muted mb-1">
                            <i class="fas fa-tag me-1"></i> Filter Kategori
                        </label>
                        <select name="kategori" class="form-select form-select-sm" id="kategoriFilter">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                    {{ $kat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-8">
                        <label class="form-label small text-muted mb-1">
                            <i class="fas fa-search me-1"></i> Cari Gelombang
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control"
                                placeholder="Nama gelombang..."
                                value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('gelombang.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                <i class="fas fa-redo me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>

                @if(request('jenis_pelatihan') || request('tahun') || request('kategori') || request('search'))
                <div class="mt-2 pt-2 border-top">
                    <small class="text-muted"><i class="fas fa-filter me-1"></i> Filter Aktif:</small>
                    @if(request('jenis_pelatihan'))
                        <span class="badge bg-primary ms-1">
                            Jenis: {{ $jenisPelatihan->find(request('jenis_pelatihan'))->nama_pelatihan ?? '-' }}
                            <a href="{{ route('gelombang.index', array_diff_key(request()->all(), ['jenis_pelatihan' => ''])) }}" class="text-white ms-1" style="text-decoration:none">×</a>
                        </span>
                    @endif
                    @if(request('tahun'))
                        <span class="badge bg-info ms-1">
                            Tahun: {{ request('tahun') }}
                            <a href="{{ route('gelombang.index', array_diff_key(request()->all(), ['tahun' => ''])) }}" class="text-white ms-1" style="text-decoration:none">×</a>
                        </span>
                    @endif
                    @if(request('kategori'))
                        <span class="badge bg-warning text-dark ms-1">
                            Kategori: {{ request('kategori') }}
                            <a href="{{ route('gelombang.index', array_diff_key(request()->all(), ['kategori' => ''])) }}" class="text-dark ms-1" style="text-decoration:none">×</a>
                        </span>
                    @endif
                    @if(request('search'))
                        <span class="badge bg-secondary ms-1">
                            Cari: "{{ request('search') }}"
                            <a href="{{ route('gelombang.index', array_diff_key(request()->all(), ['search' => ''])) }}" class="text-white ms-1" style="text-decoration:none">×</a>
                        </span>
                    @endif
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-lg overflow-hidden">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="card-title mb-0 fw-semibold">
                <i class="fas fa-list me-2" style="color: #285496;"></i> Daftar Gelombang
            </h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr class="table-light">
                            <th width="5%" class="ps-4">No</th>
                            <th width="25%">Nama Gelombang</th>
                            <th width="25%">Jenis Pelatihan</th>
                            <th width="8%">Tahun</th>
                            <th width="10%">Kategori</th>
                            <th width="10%" class="text-center">Angkatan</th>
                            <th width="17%" class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gelombang as $index => $item)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $gelombang->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="gelombang-avatar me-3">
                                            <i class="fas fa-layer-group"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $item->nama_gelombang }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $item->jenisPelatihan->nama_pelatihan ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $item->tahun }}</span>
                                </td>
                                <td>
                                    @if($item->kategori)
                                        <span class="badge {{ $item->kategori === 'PNBP' ? 'bg-warning text-dark' : 'bg-success' }}">
                                            {{ $item->kategori }}
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php $jumlahAngkatan = $item->angkatan()->count(); @endphp
                                    <span class="badge {{ $jumlahAngkatan > 0 ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $jumlahAngkatan }} Angkatan
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('gelombang.kelola-angkatan', $item) }}"
                                            class="btn btn-sm btn-outline-success btn-action"
                                            data-bs-toggle="tooltip" title="Kelola Angkatan">
                                            <i class="fas fa-users-cog"></i>
                                        </a>
                                        <a href="{{ route('gelombang.edit', $item) }}"
                                            class="btn btn-sm btn-outline-warning btn-action"
                                            data-bs-toggle="tooltip" title="Edit Gelombang">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger btn-action delete-gelombang"
                                            data-id="{{ $item->id }}"
                                            data-nama="{{ $item->nama_gelombang }}"
                                            data-bs-toggle="tooltip" title="Hapus Gelombang">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-layer-group fa-4x mb-3" style="color: #e9ecef;"></i>
                                        <h5 class="text-muted mb-2">Belum ada gelombang</h5>
                                        <p class="text-muted mb-4">Mulai dengan membuat gelombang pertama</p>
                                        <a href="{{ route('gelombang.create') }}" class="btn btn-primary px-4">
                                            <i class="fas fa-plus me-2"></i> Tambah Gelombang
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($gelombang->count() > 0)
            <div class="card-footer bg-white py-3 border-0">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Menampilkan <strong>{{ $gelombang->firstItem() }}</strong>
                            sampai <strong>{{ $gelombang->lastItem() }}</strong>
                            dari <strong>{{ $gelombang->total() }}</strong> gelombang
                        </small>
                    </div>
                    <div class="col-md-6">
                        @if($gelombang->hasPages())
                            <nav aria-label="Gelombang pagination">
                                <ul class="pagination pagination-sm justify-content-md-end justify-content-center mb-0">
                                    @if($gelombang->onFirstPage())
                                        <li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $gelombang->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a></li>
                                    @endif

                                    @php
                                        $start = max($gelombang->currentPage() - 2, 1);
                                        $end   = min($start + 4, $gelombang->lastPage());
                                        $start = max($end - 4, 1);
                                    @endphp

                                    @if($start > 1)
                                        <li class="page-item"><a class="page-link" href="{{ $gelombang->url(1) }}">1</a></li>
                                        @if($start > 2)<li class="page-item disabled"><span class="page-link">...</span></li>@endif
                                    @endif

                                    @for($i = $start; $i <= $end; $i++)
                                        <li class="page-item {{ $i == $gelombang->currentPage() ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $gelombang->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                    @if($end < $gelombang->lastPage())
                                        @if($end < $gelombang->lastPage() - 1)<li class="page-item disabled"><span class="page-link">...</span></li>@endif
                                        <li class="page-item"><a class="page-link" href="{{ $gelombang->url($gelombang->lastPage()) }}">{{ $gelombang->lastPage() }}</a></li>
                                    @endif

                                    @if($gelombang->hasMorePages())
                                        <li class="page-item"><a class="page-link" href="{{ $gelombang->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a></li>
                                    @else
                                        <li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></li>
                                    @endif
                                </ul>
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center px-4 pb-4">
                    <i class="fas fa-exclamation-triangle fa-4x mb-3" style="color: #ff4757;"></i>
                    <h4 class="fw-bold mb-3">Konfirmasi Hapus</h4>
                    <p class="text-muted mb-1">Anda akan menghapus gelombang:</p>
                    <h5 class="text-danger fw-bold mb-4" id="deleteNamaGelombang"></h5>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Gelombang hanya bisa dihapus jika tidak ada angkatan yang terhubung.
                    </p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Batal
                    </button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">
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
    // Tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    // Auto submit filter
    document.getElementById('jenisPelatihanFilter').addEventListener('change', function () {
        document.getElementById('filterForm').submit();
    });
    document.getElementById('kategoriFilter').addEventListener('change', function () {
        document.getElementById('filterForm').submit();
    });

    // Delete modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    document.querySelectorAll('.delete-gelombang').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('deleteNamaGelombang').textContent = this.dataset.nama;
            document.getElementById('deleteForm').action = `{{ url('gelombang') }}/${this.dataset.id}`;
            deleteModal.show();
        });
    });

    // Auto-hide alerts
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => bootstrap.Alert.getOrCreateInstance(alert)?.close(), 5000);
    });
});
</script>

<style>
    .gelombang-avatar {
        width: 44px; height: 44px;
        border-radius: 10px;
        background: linear-gradient(135deg, #285496, #3a6bc7);
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 1.1rem;
        box-shadow: 0 4px 8px rgba(40,84,150,0.2);
        flex-shrink: 0;
    }
    .btn-action { border-radius: 8px; padding: .375rem .75rem; margin: 0 2px; transition: all .2s; border-width: 2px; }
    .btn-action:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,.1); }
    .table th { border-bottom: 2px solid rgba(40,84,150,.1); font-weight: 600; color: #285496; background-color: #f8fafc; padding: 1rem; }
    .table td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #e9ecef; }
    .pagination-sm .page-link { padding: .375rem .625rem; border-radius: 6px; color: #285496; transition: all .2s; }
    .pagination-sm .page-item.active .page-link { background-color: #285496; border-color: #285496; color: white; }
</style>
@endsection