@extends('admin.partials.layout')

@section('title', 'Detail Indikator - ' . $indikatorNilai->name)

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
                        <h1 class="text-white mb-1">Detail Indikator</h1>
                        <p class="text-white-50 mb-0">
                            <span class="badge bg-white text-primary fw-semibold me-2">{{ $jenisPelatihan->kode_pelatihan }}</span>
                            {{ $jenisPelatihan->nama_pelatihan }}
                            <i class="fas fa-chevron-right mx-1 small"></i>
                            {{ $jenisNilai->name }}
                            <i class="fas fa-chevron-right mx-1 small"></i>
                            {{ $indikatorNilai->name }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <button class="btn btn-light shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="fas fa-plus me-2"></i> Tambah Detail
                </button>
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

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('indikator-penilaian.index') }}" class="text-decoration-none" style="color:#285496">
                    <i class="fas fa-clipboard-list me-1"></i> Indikator Penilaian
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('indikator-penilaian.jenis-nilai.index', $jenisPelatihan->id) }}" class="text-decoration-none" style="color:#285496">
                    {{ $jenisPelatihan->nama_pelatihan }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('indikator-penilaian.indikator.index', [$jenisPelatihan->id, $jenisNilai->id]) }}" class="text-decoration-none" style="color:#285496">
                    {{ $jenisNilai->name }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ $indikatorNilai->name }}</li>
        </ol>
    </nav>

    <!-- Step Indicator -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="d-flex align-items-center step-indicator">
                <div class="step-item done">
                    <div class="step-circle done"><i class="fas fa-check"></i></div>
                    <span class="step-label">Jenis Pelatihan</span>
                </div>
                <div class="step-line done"></div>
                <div class="step-item done">
                    <div class="step-circle done"><i class="fas fa-check"></i></div>
                    <span class="step-label">Jenis Nilai</span>
                </div>
                <div class="step-line done"></div>
                <div class="step-item done">
                    <div class="step-circle done"><i class="fas fa-check"></i></div>
                    <span class="step-label">Indikator</span>
                </div>
                <div class="step-line done"></div>
                <div class="step-item active">
                    <div class="step-circle active"><i class="fas fa-layer-group"></i></div>
                    <span class="step-label">Detail Indikator</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Card Indikator -->
    <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #285496 !important; border-radius: 10px !important;">
        <div class="card-body py-3 px-4">
            <div class="d-flex align-items-center">
                <div class="indikator-icon me-3">
                    <i class="fas fa-tasks"></i>
                </div>
                <div>
                    <div class="fw-bold">{{ $indikatorNilai->name }}</div>
                    <small class="text-muted">{{ $indikatorNilai->deskripsi ?? 'Tidak ada deskripsi' }}</small>
                </div>
                <div class="ms-auto">
                    <span class="badge bg-info text-dark">{{ $detailIndikator->count() }} Detail</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-lg overflow-hidden">
        <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0 fw-semibold">
                <i class="fas fa-list me-2" style="color: #285496;"></i> Daftar Detail Indikator
                <span class="badge bg-primary ms-2">{{ $detailIndikator->count() }}</span>
            </h5>
            <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Detail berupa uraian level/poin penilaian</small>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr class="table-light">
                            <th width="5%" class="ps-4">No</th>
                            <th width="8%">Level</th>
                            <th width="45%">Uraian</th>
                            <th width="20%">Range Nilai</th>
                            <th width="12%" class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detailIndikator as $index => $item)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <span class="level-badge level-{{ $item->level }}">
                                        <i class="fas fa-circle me-1" style="font-size:.5rem; vertical-align:middle;"></i>
                                        Level {{ $item->level ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <p class="mb-0 small">{{ $item->uraian ?? '-' }}</p>
                                </td>
                                <td>
                                    @if($item->range)
                                        <span class="badge bg-light text-dark border fw-semibold">
                                            <i class="fas fa-ruler-horizontal me-1"></i> {{ $item->range }}
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group" role="group">
                                        <button type="button"
                                            class="btn btn-sm btn-outline-warning btn-action btn-edit"
                                            data-id="{{ $item->id }}"
                                            data-level="{{ $item->level }}"
                                            data-uraian="{{ $item->uraian }}"
                                            data-range="{{ $item->range }}"
                                            data-bs-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger btn-action btn-delete"
                                            data-id="{{ $item->id }}"
                                            data-uraian="{{ Str::limit($item->uraian, 40) }}"
                                            data-bs-toggle="tooltip" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-layer-group fa-4x mb-3" style="color: #e9ecef;"></i>
                                        <h5 class="text-muted mb-2">Belum ada detail indikator</h5>
                                        <p class="text-muted mb-4">Tambahkan uraian level penilaian untuk indikator ini</p>
                                        <button class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                            <i class="fas fa-plus me-2"></i> Tambah Detail
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-plus-circle me-2" style="color:#285496"></i> Tambah Detail Indikator
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('indikator-penilaian.detail-indikator.store', [$jenisPelatihan->id, $jenisNilai->id, $indikatorNilai->id]) }}" method="POST">
                    @csrf
                    <div class="modal-body px-4 py-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Level</label>
                                <input type="number" name="level" class="form-control" placeholder="Contoh: 1, 2, 3..." min="1">
                                <small class="text-muted">Tingkatan level penilaian</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Range Nilai</label>
                                <input type="text" name="range" class="form-control" placeholder="Contoh: 0-25, 76-100...">
                                <small class="text-muted">Rentang nilai untuk level ini</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Uraian</label>
                                <textarea name="uraian" class="form-control" rows="4" placeholder="Tuliskan uraian/deskripsi detail indikator untuk level ini..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-edit me-2" style="color:#285496"></i> Edit Detail Indikator
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body px-4 py-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Level</label>
                                <input type="number" name="level" id="editLevel" class="form-control" min="1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Range Nilai</label>
                                <input type="text" name="range" id="editRange" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Uraian</label>
                                <textarea name="uraian" id="editUraian" class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="fas fa-save me-2"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div class="modal fade" id="modalDelete" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center px-4 pb-4">
                    <i class="fas fa-exclamation-triangle fa-4x mb-3" style="color: #ff4757;"></i>
                    <h4 class="fw-bold mb-3">Konfirmasi Hapus</h4>
                    <p class="text-muted mb-1">Anda akan menghapus detail indikator:</p>
                    <h6 class="text-danger fw-bold mb-2" id="deleteUraian"></h6>
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

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('editLevel').value = this.dataset.level || '';
            document.getElementById('editUraian').value = this.dataset.uraian || '';
            document.getElementById('editRange').value = this.dataset.range || '';
            document.getElementById('editForm').action = `{{ url('indikator-penilaian/'.$jenisPelatihan->id.'/jenis-nilai/'.$jenisNilai->id.'/indikator/'.$indikatorNilai->id.'/detail') }}/${this.dataset.id}`;
            new bootstrap.Modal(document.getElementById('modalEdit')).show();
        });
    });

    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('deleteUraian').textContent = this.dataset.uraian || 'Detail indikator ini';
            document.getElementById('deleteForm').action = `{{ url('indikator-penilaian/'.$jenisPelatihan->id.'/jenis-nilai/'.$jenisNilai->id.'/indikator/'.$indikatorNilai->id.'/detail') }}/${this.dataset.id}`;
            new bootstrap.Modal(document.getElementById('modalDelete')).show();
        });
    });

    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => bootstrap.Alert.getOrCreateInstance(alert)?.close(), 5000);
    });
});
</script>

<style>
    .step-indicator { gap: 0; }
    .step-item { display: flex; flex-direction: column; align-items: center; flex: 0 0 auto; }
    .step-circle {
        width: 40px; height: 40px; border-radius: 50%;
        background: #e9ecef; color: #adb5bd;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .9rem; border: 2px solid #dee2e6;
        transition: all .3s;
    }
    .step-circle.active { background: #285496; color: white; border-color: #285496; box-shadow: 0 4px 12px rgba(40,84,150,.3); }
    .step-circle.done { background: #28a745; color: white; border-color: #28a745; }
    .step-label { font-size: .75rem; color: #6c757d; margin-top: .4rem; font-weight: 500; white-space: nowrap; }
    .step-line { flex: 1; height: 2px; background: #dee2e6; margin: 0 .5rem; margin-bottom: 1.2rem; }
    .step-line.done { background: #28a745; }

    .indikator-icon {
        width: 40px; height: 40px; border-radius: 8px;
        background: linear-gradient(135deg, #285496, #3a6bc7);
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: .9rem;
    }

    .level-badge {
        display: inline-flex; align-items: center;
        padding: .3rem .6rem; border-radius: 6px;
        font-size: .75rem; font-weight: 600;
    }
    .level-1 { background: rgba(40,167,69,.1); color: #28a745; }
    .level-2 { background: rgba(23,162,184,.1); color: #17a2b8; }
    .level-3 { background: rgba(255,193,7,.15); color: #d68910; }
    .level-4 { background: rgba(255,87,51,.1); color: #ff5733; }
    .level-5 { background: rgba(40,84,150,.1); color: #285496; }

    .btn-action { border-radius: 8px; padding: .375rem .75rem; margin: 0 2px; transition: all .2s; border-width: 2px; }
    .btn-action:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,.1); }
    .table th { border-bottom: 2px solid rgba(40,84,150,.1); font-weight: 600; color: #285496; background-color: #f8fafc; padding: .75rem 1rem; }
    .table td { padding: .75rem 1rem; vertical-align: middle; border-bottom: 1px solid #e9ecef; }
</style>
@endsection