@extends('admin.partials.layout')

@section('title', 'Manajemen Kelompok')

@section('content')
    <!-- Page Header -->
    <div class="page-header rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%); padding: 2rem;">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-users fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">Manajemen Kelompok</h1>
                        <p class="text-white-50 mb-0">Kelola kelompok peserta pelatihan beserta pembimbing dan penguji</p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('kelompok.create') }}" class="btn btn-light shadow-sm">
                    <i class="fas fa-plus me-2"></i> Tambah Kelompok
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
            <form action="{{ route('kelompok.index') }}" method="GET" id="filterForm">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label small text-muted mb-1">
                            <i class="fas fa-graduation-cap me-1"></i> Jenis Pelatihan
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
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label small text-muted mb-1">
                            <i class="fas fa-layer-group me-1"></i> Angkatan
                        </label>
                        <select name="angkatan" class="form-select form-select-sm">
                            <option value="">Semua Angkatan</option>
                            @foreach($angkatanList as $ang)
                                <option value="{{ $ang->id }}" {{ request('angkatan') == $ang->id ? 'selected' : '' }}>
                                    {{ $ang->nama_angkatan }} ({{ $ang->tahun }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-4">
                        <label class="form-label small text-muted mb-1">
                            <i class="fas fa-calendar me-1"></i> Tahun
                        </label>
                        <select name="tahun" class="form-select form-select-sm">
                            <option value="">Semua Tahun</option>
                            @foreach($tahunList as $thn)
                                <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-8">
                        <label class="form-label small text-muted mb-1">
                            <i class="fas fa-search me-1"></i> Cari
                        </label>
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Nama kelompok..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 col-sm-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('kelompok.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                <i class="fas fa-redo me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-lg overflow-hidden">
        <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0 fw-semibold">
                <i class="fas fa-list me-2" style="color: #285496;"></i> Daftar Kelompok
            </h5>
            <div class="d-flex gap-2">
                <a href="{{ route('coach.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-chalkboard-teacher me-1"></i> Master Coach
                </a>
                <a href="{{ route('penguji.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-user-check me-1"></i> Master Penguji
                </a>
                <a href="{{ route('evaluator.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-clipboard-check me-1"></i> Master Evaluator
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr class="table-light">
                            <th width="4%" class="ps-4">No</th>
                            <th width="15%">Nama Kelompok</th>
                            <th width="12%">Jenis Pelatihan</th>
                            <th width="14%">Angkatan</th>
                            <th width="5%">Tahun</th>
                            <th width="10%">Mentor</th>
                            <th width="10%">Coach</th>
                            <th width="10%">Penguji</th>
                            <th width="10%">Evaluator</th>
                            <th width="7%" class="text-center">Peserta</th>
                            <th width="8%" class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelompok as $index => $item)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $kelompok->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="kelompok-avatar me-2">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold small">{{ $item->nama_kelompok }}</div>
                                            @if($item->keterangan)
                                                <small class="text-muted">{{ Str::limit($item->keterangan, 30) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $item->jenisPelatihan->nama_pelatihan ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold small">{{ $item->angkatan->nama_angkatan ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $item->tahun }}</span>
                                </td>
                                <td>
                                    @if($item->mentor)
                                        <div class="small fw-semibold">{{ $item->mentor->nama_mentor }}</div>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->coach)
                                        <div class="small fw-semibold">{{ $item->coach->nama }}</div>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->penguji)
                                        <div class="small fw-semibold">{{ $item->penguji->nama }}</div>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->evaluator)
                                        <div class="small fw-semibold">{{ $item->evaluator->nama }}</div>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php $jmlPeserta = $item->peserta()->count(); @endphp
                                    <span class="badge {{ $jmlPeserta > 0 ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $jmlPeserta }} Peserta
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('kelompok.kelola-peserta', $item) }}"
                                            class="btn btn-sm btn-outline-success btn-action"
                                            data-bs-toggle="tooltip" title="Kelola Peserta">
                                            <i class="fas fa-users-cog"></i>
                                        </a>
                                        <a href="{{ route('kelompok.edit', $item) }}"
                                            class="btn btn-sm btn-outline-warning btn-action"
                                            data-bs-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger btn-action delete-kelompok"
                                            data-id="{{ $item->id }}"
                                            data-nama="{{ $item->nama_kelompok }}"
                                            data-bs-toggle="tooltip" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-users fa-4x mb-3" style="color: #e9ecef;"></i>
                                        <h5 class="text-muted mb-2">Belum ada kelompok</h5>
                                        <p class="text-muted mb-4">Mulai dengan membuat kelompok pertama</p>
                                        <a href="{{ route('kelompok.create') }}" class="btn btn-primary px-4">
                                            <i class="fas fa-plus me-2"></i> Tambah Kelompok
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($kelompok->count() > 0)
            <div class="card-footer bg-white py-3 border-0">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <small class="text-muted">
                            Menampilkan <strong>{{ $kelompok->firstItem() }}</strong>
                            sampai <strong>{{ $kelompok->lastItem() }}</strong>
                            dari <strong>{{ $kelompok->total() }}</strong> kelompok
                        </small>
                    </div>
                    <div class="col-md-6">
                        @if($kelompok->hasPages())
                            <nav>
                                <ul class="pagination pagination-sm justify-content-md-end justify-content-center mb-0">
                                    @if($kelompok->onFirstPage())
                                        <li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $kelompok->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a></li>
                                    @endif
                                    @php
                                        $start = max($kelompok->currentPage() - 2, 1);
                                        $end   = min($start + 4, $kelompok->lastPage());
                                        $start = max($end - 4, 1);
                                    @endphp
                                    @if($start > 1)
                                        <li class="page-item"><a class="page-link" href="{{ $kelompok->url(1) }}">1</a></li>
                                        @if($start > 2)<li class="page-item disabled"><span class="page-link">...</span></li>@endif
                                    @endif
                                    @for($i = $start; $i <= $end; $i++)
                                        <li class="page-item {{ $i == $kelompok->currentPage() ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $kelompok->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                                    @if($end < $kelompok->lastPage())
                                        @if($end < $kelompok->lastPage() - 1)<li class="page-item disabled"><span class="page-link">...</span></li>@endif
                                        <li class="page-item"><a class="page-link" href="{{ $kelompok->url($kelompok->lastPage()) }}">{{ $kelompok->lastPage() }}</a></li>
                                    @endif
                                    @if($kelompok->hasMorePages())
                                        <li class="page-item"><a class="page-link" href="{{ $kelompok->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a></li>
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
                    <p class="text-muted mb-1">Anda akan menghapus kelompok:</p>
                    <h5 class="text-danger fw-bold mb-4" id="deleteNamaKelompok"></h5>
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
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    document.querySelectorAll('.delete-kelompok').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('deleteNamaKelompok').textContent = this.dataset.nama;
            document.getElementById('deleteForm').action = `{{ url('kelompok') }}/${this.dataset.id}`;
            deleteModal.show();
        });
    });

    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => bootstrap.Alert.getOrCreateInstance(alert)?.close(), 5000);
    });
});
</script>

<style>
    .kelompok-avatar {
        width: 36px; height: 36px; border-radius: 8px;
        background: linear-gradient(135deg, #285496, #3a6bc7);
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 1rem; box-shadow: 0 4px 8px rgba(40,84,150,.2);
        flex-shrink: 0;
    }
    .btn-action { border-radius: 8px; padding: .375rem .75rem; margin: 0 2px; transition: all .2s; border-width: 2px; }
    .btn-action:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,.1); }
    .table th { border-bottom: 2px solid rgba(40,84,150,.1); font-weight: 600; color: #285496; background-color: #f8fafc; padding: .75rem 1rem; }
    .table td { padding: .75rem 1rem; vertical-align: middle; border-bottom: 1px solid #e9ecef; }
    .pagination-sm .page-link { padding: .375rem .625rem; border-radius: 6px; color: #285496; }
    .pagination-sm .page-item.active .page-link { background-color: #285496; border-color: #285496; color: white; }
</style>
@endsection