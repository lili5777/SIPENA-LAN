@extends('admin.partials.layout')

@section('title', 'Jenis Nilai - ' . $jenisPelatihan->nama_pelatihan)

@section('content')
    <!-- Page Header -->
    <div class="page-header rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%); padding: 2rem;">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-list-alt fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">Jenis Nilai</h1>
                        <p class="text-white-50 mb-0">
                            <span class="badge bg-white text-primary fw-semibold me-2">{{ $jenisPelatihan->kode_pelatihan }}</span>
                            {{ $jenisPelatihan->nama_pelatihan }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <button class="btn btn-light shadow-sm {{ $sisaBobot <= 0 ? 'disabled' : '' }}"
                    {{ $sisaBobot <= 0 ? '' : 'data-bs-toggle=modal data-bs-target=#modalTambah' }}>
                    <i class="fas fa-plus me-2"></i> Tambah Jenis Nilai
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
            <li class="breadcrumb-item active">{{ $jenisPelatihan->nama_pelatihan }}</li>
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
                <div class="step-item active">
                    <div class="step-circle active"><i class="fas fa-list-alt"></i></div>
                    <span class="step-label">Jenis Nilai</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item">
                    <div class="step-circle"><span>3</span></div>
                    <span class="step-label">Indikator</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item">
                    <div class="step-circle"><span>4</span></div>
                    <span class="step-label">Detail Indikator</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bobot Summary Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center g-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-2">
                        <span class="fw-semibold me-2">Total Bobot Terpakai</span>
                        <span class="badge {{ $totalBobot >= 100 ? 'bg-success' : ($totalBobot > 0 ? 'bg-warning text-dark' : 'bg-secondary') }}">
                            {{ $totalBobot }}% / 100%
                        </span>
                        @if($sisaBobot > 0)
                            <span class="ms-2 text-muted small">
                                <i class="fas fa-info-circle me-1"></i>Sisa: <strong class="text-primary">{{ $sisaBobot }}%</strong>
                            </span>
                        @endif
                    </div>
                    <div class="progress" style="height: 12px; border-radius: 6px;">
                        <div class="progress-bar {{ $totalBobot >= 100 ? 'bg-success' : 'bg-primary' }}"
                            role="progressbar"
                            style="width: {{ min($totalBobot, 100) }}%; border-radius: 6px;"
                            aria-valuenow="{{ $totalBobot }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row g-2 text-center">
                        <div class="col-4">
                            <div class="bobot-stat-box">
                                <div class="bobot-stat-value text-primary">100%</div>
                                <div class="bobot-stat-label">Total Alokasi</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bobot-stat-box">
                                <div class="bobot-stat-value text-warning">{{ $totalBobot }}%</div>
                                <div class="bobot-stat-label">Sudah Diisi</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bobot-stat-box">
                                <div class="bobot-stat-value {{ $sisaBobot <= 0 ? 'text-success' : 'text-danger' }}">{{ $sisaBobot }}%</div>
                                <div class="bobot-stat-label">Sisa</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-lg overflow-hidden">
        <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0 fw-semibold">
                <i class="fas fa-list me-2" style="color: #285496;"></i> Daftar Jenis Nilai
                <span class="badge bg-primary ms-2">{{ $jenisNilai->count() }}</span>
            </h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr class="table-light">
                            <th width="4%" class="ps-4">No</th>
                            <th width="25%">Nama Jenis Nilai</th>
                            <th width="25%">Deskripsi</th>
                            <th width="20%">Bobot</th>
                            <th width="12%" class="text-center">Indikator</th>
                            <th width="14%" class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jenisNilai as $index => $item)
                            @php
                                $totalBobotIndikator = $item->totalBobotIndikator();
                                $persenIndikator     = $item->bobot > 0 ? round(($totalBobotIndikator / $item->bobot) * 100) : 0;
                            @endphp
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="nilai-avatar me-2">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="fw-bold small">{{ $item->name }}</div>
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $item->deskripsi ?? '-' }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-primary fw-semibold" style="min-width:52px">{{ $item->bobot }}%</span>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between mb-1">
                                                <small class="text-muted" style="font-size:.7rem">
                                                    Indikator: {{ $totalBobotIndikator }}% / {{ $item->bobot }}%
                                                </small>
                                            </div>
                                            <div class="progress" style="height:6px; border-radius:4px;">
                                                <div class="progress-bar {{ $persenIndikator >= 100 ? 'bg-success' : 'bg-info' }}"
                                                    style="width:{{ min($persenIndikator,100) }}%; border-radius:4px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @php $jmlIndikator = $item->indikatorNilai()->count(); @endphp
                                    <span class="badge {{ $jmlIndikator > 0 ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $jmlIndikator }} Indikator
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('indikator-penilaian.indikator.index', [$jenisPelatihan->id, $item->id]) }}"
                                            class="btn btn-sm btn-outline-success btn-action"
                                            data-bs-toggle="tooltip" title="Kelola Indikator">
                                            <i class="fas fa-list-ul"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-warning btn-action btn-edit"
                                            data-id="{{ $item->id }}"
                                            data-name="{{ $item->name }}"
                                            data-deskripsi="{{ $item->deskripsi }}"
                                            data-bobot="{{ $item->bobot }}"
                                            data-bs-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger btn-action btn-delete"
                                            data-id="{{ $item->id }}"
                                            data-name="{{ $item->name }}"
                                            data-bs-toggle="tooltip" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-star fa-4x mb-3" style="color: #e9ecef;"></i>
                                        <h5 class="text-muted mb-2">Belum ada jenis nilai</h5>
                                        <p class="text-muted mb-4">Mulai dengan menambahkan jenis nilai pertama</p>
                                        <button class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                            <i class="fas fa-plus me-2"></i> Tambah Jenis Nilai
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($jenisNilai->count() > 0)
                    <tfoot>
                        <tr class="table-light fw-bold">
                            <td colspan="3" class="ps-4 text-end text-muted small">Total Bobot:</td>
                            <td>
                                <span class="badge {{ $totalBobot >= 100 ? 'bg-success' : 'bg-warning text-dark' }} fw-bold">
                                    {{ $totalBobot }}% / 100%
                                </span>
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-plus-circle me-2" style="color:#285496"></i> Tambah Jenis Nilai
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('indikator-penilaian.jenis-nilai.store', $jenisPelatihan->id) }}" method="POST">
                    @csrf
                    <div class="modal-body px-4 py-3">
                        <!-- Info sisa bobot -->
                        <div class="alert alert-info py-2 mb-3 d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            <span class="small">
                                Sisa bobot yang tersedia: <strong>{{ $sisaBobot }}%</strong>
                                (dari total 100%)
                            </span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Jenis Nilai <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Nilai Akademik, Nilai Sikap..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Bobot (%) <span class="text-danger">*</span>
                                <small class="text-muted fw-normal">— maks. {{ $sisaBobot }}%</small>
                            </label>
                            <div class="input-group">
                                <input type="number" name="bobot" class="form-control"
                                    placeholder="Contoh: 30" step="0.01" min="0.01"
                                    max="{{ $sisaBobot }}" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="mt-1">
                                <small class="text-muted">Sudah terpakai: <strong>{{ $totalBobot }}%</strong> &nbsp;|&nbsp; Sisa: <strong class="text-primary">{{ $sisaBobot }}%</strong></small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi singkat jenis nilai..."></textarea>
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-edit me-2" style="color:#285496"></i> Edit Jenis Nilai
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body px-4 py-3">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Jenis Nilai <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Bobot (%) <span class="text-danger">*</span>
                                <small class="text-muted fw-normal" id="editBobotHint"></small>
                            </label>
                            <div class="input-group">
                                <input type="number" name="bobot" id="editBobot" class="form-control"
                                    step="0.01" min="0.01" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="deskripsi" id="editDeskripsi" class="form-control" rows="3"></textarea>
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
                    <p class="text-muted mb-1">Anda akan menghapus jenis nilai:</p>
                    <h5 class="text-danger fw-bold mb-2" id="deleteNama"></h5>
                    <p class="text-muted small">Semua indikator dan detail terkait akan ikut terhapus.</p>
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

    const totalBobot = {{ $totalBobot }};
    const sisaBobot  = {{ $sisaBobot }};

    // Edit
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function () {
            const bobotSaatIni  = parseFloat(this.dataset.bobot);
            const sisaUntukEdit = Math.round((sisaBobot + bobotSaatIni) * 100) / 100;

            document.getElementById('editName').value         = this.dataset.name;
            document.getElementById('editDeskripsi').value    = this.dataset.deskripsi || '';
            document.getElementById('editBobot').value        = bobotSaatIni;
            document.getElementById('editBobot').max          = sisaUntukEdit;
            document.getElementById('editBobotHint').textContent = `— maks. ${sisaUntukEdit}%`;
            document.getElementById('editForm').action = `{{ url('indikator-penilaian/'.$jenisPelatihan->id.'/jenis-nilai') }}/${this.dataset.id}`;
            new bootstrap.Modal(document.getElementById('modalEdit')).show();
        });
    });

    // Delete
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('deleteNama').textContent = this.dataset.name;
            document.getElementById('deleteForm').action = `{{ url('indikator-penilaian/'.$jenisPelatihan->id.'/jenis-nilai') }}/${this.dataset.id}`;
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
        font-weight: 700; font-size: .9rem; border: 2px solid #dee2e6; transition: all .3s;
    }
    .step-circle.active { background: #285496; color: white; border-color: #285496; box-shadow: 0 4px 12px rgba(40,84,150,.3); }
    .step-circle.done   { background: #28a745; color: white; border-color: #28a745; }
    .step-label { font-size: .75rem; color: #6c757d; margin-top: .4rem; font-weight: 500; white-space: nowrap; }
    .step-line  { flex: 1; height: 2px; background: #dee2e6; margin: 0 .5rem; margin-bottom: 1.2rem; }
    .step-line.done { background: #28a745; }

    .nilai-avatar {
        width: 36px; height: 36px; border-radius: 8px;
        background: linear-gradient(135deg, #f59e0b, #f97316);
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: .9rem; box-shadow: 0 4px 8px rgba(245,158,11,.2); flex-shrink: 0;
    }
    .bobot-stat-box { background: #f8fafc; border-radius: 10px; padding: .6rem; border: 1px solid #e9ecef; }
    .bobot-stat-value { font-size: 1.2rem; font-weight: 700; line-height: 1.2; }
    .bobot-stat-label { font-size: .7rem; color: #6c757d; }

    .btn-action { border-radius: 8px; padding: .375rem .75rem; margin: 0 2px; transition: all .2s; border-width: 2px; }
    .btn-action:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,.1); }
    .table th { border-bottom: 2px solid rgba(40,84,150,.1); font-weight: 600; color: #285496; background-color: #f8fafc; padding: .75rem 1rem; }
    .table td { padding: .75rem 1rem; vertical-align: middle; border-bottom: 1px solid #e9ecef; }
    tfoot td { border-bottom: none !important; }
</style>
@endsection