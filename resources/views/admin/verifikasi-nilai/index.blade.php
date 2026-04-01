@extends('admin.partials.layout')

@section('title', 'Verifikasi Nilai Mandiri')

@section('content')

    <!-- Page Header -->
    <div class="page-header rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%); padding: 2rem;">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-clipboard-check fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">Verifikasi Nilai Mandiri</h1>
                        <p class="text-white-50 mb-0">Verifikasi submission nilai dari peserta</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('verifikasi-nilai.index') }}" method="GET">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
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
                    <div class="col-md-2">
                        <label class="form-label small text-muted mb-1">
                            <i class="fas fa-filter me-1"></i> Status
                        </label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Menunggu</option>
                            <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="ditolak"   {{ request('status') === 'ditolak'   ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted mb-1">
                            <i class="fas fa-search me-1"></i> Cari Peserta
                        </label>
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Nama atau NIP..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('verifikasi-nilai.index') }}"
                                class="btn btn-outline-secondary btn-sm flex-fill">
                                <i class="fas fa-redo me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Submission -->
    <div class="card border-0 shadow-lg overflow-hidden">
        <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0 fw-semibold">
                <i class="fas fa-list me-2" style="color: #285496;"></i> Daftar Submission
                <span class="badge bg-primary ms-2">{{ $submissions->total() }}</span>
            </h5>
            <small class="text-muted">
                {{ $submissions->count() }} dari {{ $submissions->total() }} submission
            </small>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 verif-table">
                    <thead>
                        <tr class="table-light">
                            <th class="ps-4" width="4%">No</th>
                            <th width="22%">Peserta</th>
                            <th width="22%">Indikator</th>
                            <th class="text-center" width="8%">Nilai</th>
                            <th class="text-center" width="10%">Screenshot</th>
                            <th width="15%">Catatan Peserta</th>
                            <th class="text-center" width="10%">Status</th>
                            <th class="text-center" width="9%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $index => $sub)
                            <tr class="{{ $sub->status === 'pending' ? 'row-pending' : '' }}">
                                <td class="ps-4 fw-semibold">{{ $submissions->firstItem() + $index }}</td>

                                <td>
                                    <div class="fw-bold small">{{ $sub->peserta->nama_lengkap ?? '-' }}</div>
                                    <small class="text-muted">{{ $sub->peserta->nip_nrp ?? '-' }}</small>
                                    <div>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $sub->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                </td>

                                <td>
                                    <div class="fw-semibold small">{{ $sub->indikatorNilai->name ?? '-' }}</div>
                                    <small class="text-muted">
                                        {{ $sub->indikatorNilai->jenisNilai->name ?? '-' }}
                                        · Bobot {{ $sub->indikatorNilai->bobot ?? 0 }}%
                                    </small>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-light text-dark border fw-bold" style="font-size:.9rem;">
                                        {{ $sub->nilai ?? '-' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    @if($sub->file)
                                        <button type="button"
                                            class="btn btn-xs btn-outline-primary btn-lihat-screenshot"
                                            data-src="{{ route('upload-nilai.file', $sub->id) }}?v={{ $sub->updated_at->timestamp }}"
                                            data-peserta="{{ $sub->peserta->nama_lengkap ?? '' }}"
                                            data-indikator="{{ $sub->indikatorNilai->name ?? '' }}">
                                            <i class="fas fa-eye me-1"></i> Lihat
                                        </button>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>

                                <td>
                                    @if($sub->catatan_peserta)
                                        <small class="text-muted">{{ Str::limit($sub->catatan_peserta, 60) }}</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <span class="badge {{ $sub->statusBadgeClass() }} px-2 py-1">
                                        @if($sub->status === 'pending')
                                            <i class="fas fa-clock me-1"></i>
                                        @elseif($sub->status === 'disetujui')
                                            <i class="fas fa-check me-1"></i>
                                        @else
                                            <i class="fas fa-times me-1"></i>
                                        @endif
                                        {{ $sub->statusLabel() }}
                                    </span>
                                    @if($sub->status !== 'pending' && $sub->verifikator)
                                        <div>
                                            <small class="text-muted" style="font-size:.68rem;">
                                                oleh {{ $sub->verifikator->name }}
                                            </small>
                                        </div>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if($sub->status === 'pending')
                                        <button type="button"
                                            class="btn btn-xs btn-success me-1 btn-approve"
                                            data-id="{{ $sub->id }}"
                                            data-peserta="{{ $sub->peserta->nama_lengkap }}"
                                            data-indikator="{{ $sub->indikatorNilai->name }}"
                                            data-nilai="{{ $sub->nilai }}"
                                            data-bs-toggle="tooltip" title="Setujui">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button"
                                            class="btn btn-xs btn-danger btn-reject"
                                            data-id="{{ $sub->id }}"
                                            data-peserta="{{ $sub->peserta->nama_lengkap }}"
                                            data-indikator="{{ $sub->indikatorNilai->name }}"
                                            data-bs-toggle="tooltip" title="Tolak">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @elseif($sub->status === 'ditolak' && $sub->catatan_verifikator)
                                        <button type="button"
                                            class="btn btn-xs btn-outline-secondary btn-lihat-catatan"
                                            data-catatan="{{ $sub->catatan_verifikator }}"
                                            data-bs-toggle="tooltip" title="Lihat alasan penolakan">
                                            <i class="fas fa-comment-alt"></i>
                                        </button>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-clipboard-check fa-4x mb-3" style="color:#e9ecef;"></i>
                                    <h5 class="text-muted mb-2">Belum ada submission</h5>
                                    <p class="text-muted">Belum ada peserta yang mengajukan nilai mandiri</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($submissions->hasPages())
            <div class="card-footer bg-white py-3 border-0">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <small class="text-muted">
                            Menampilkan <strong>{{ $submissions->firstItem() }}</strong>
                            sampai <strong>{{ $submissions->lastItem() }}</strong>
                            dari <strong>{{ $submissions->total() }}</strong>
                        </small>
                    </div>
                    <div class="col-md-6">
                        <nav class="d-flex justify-content-md-end">
                            {{ $submissions->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Preview Gambar -->
    <div class="modal fade" id="modalPreviewGambar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 py-2 px-4"
                    style="background:linear-gradient(135deg,#285496,#3a6bc7);">
                    <div>
                        <h6 class="text-white mb-0 fw-bold" id="previewTitle">Preview Screenshot</h6>
                        <small class="text-white-50" id="previewSubtitle"></small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                                <div class="modal-body p-0 text-center" style="background:#1a1a2e; min-height:200px; display:flex; align-items:center; justify-content:center; flex-direction:column;">
                    <div class="preview-loading w-100">
                        <div class="d-flex flex-column align-items-center py-5">
                            <div class="spinner-border text-light mb-3" style="width:2.5rem;height:2.5rem;" role="status"></div>
                            <span class="text-white-50 small">Memuat gambar dari server...</span>
                        </div>
                    </div>
                    <img id="previewGambarImg" src="" alt="Preview"
                        class="img-fluid rounded d-none" style="max-height:70vh; max-width:100%; padding:.5rem;">
                </div>
                </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Setujui -->
    <div class="modal fade" id="modalApprove" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 py-3 px-4"
                    style="background:linear-gradient(135deg,#28a745,#20c997);">
                    <h6 class="text-white fw-bold mb-0">
                        <i class="fas fa-check-circle me-2"></i> Konfirmasi Persetujuan
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="mb-1">Anda akan menyetujui nilai dari:</p>
                    <div class="alert alert-success py-2 px-3">
                        <strong id="approveNamaPeserta"></strong><br>
                        <small id="approveNamaIndikator" class="text-muted"></small><br>
                        Nilai: <strong id="approveNilai"></strong>
                    </div>
                    <p class="text-muted small mb-0">
                        Nilai akan otomatis masuk ke rekap penilaian peserta.
                    </p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success px-4" id="btnConfirmApprove">
                        <i class="fas fa-check me-2"></i> Ya, Setujui
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tolak -->
    <div class="modal fade" id="modalReject" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 py-3 px-4"
                    style="background:linear-gradient(135deg,#dc3545,#c82333);">
                    <h6 class="text-white fw-bold mb-0">
                        <i class="fas fa-times-circle me-2"></i> Tolak Submission
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="mb-1">Tolak submission dari:</p>
                    <div class="alert alert-danger py-2 px-3 mb-3">
                        <strong id="rejectNamaPeserta"></strong><br>
                        <small id="rejectNamaIndikator" class="text-muted"></small>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold small">
                            Alasan Penolakan <span class="text-danger">*</span>
                        </label>
                        <textarea id="catatanVerifikator" class="form-control" rows="3"
                            placeholder="Tuliskan alasan penolakan agar peserta dapat memperbaiki..."></textarea>
                        <small class="text-muted">Wajib diisi — akan ditampilkan ke peserta</small>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger px-4" id="btnConfirmReject">
                        <i class="fas fa-times me-2"></i> Tolak Submission
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Catatan Tolak -->
    <div class="modal fade" id="modalCatatanTolak" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 py-3 px-4"
                    style="background:linear-gradient(135deg,#6c757d,#495057);">
                    <h6 class="text-white fw-bold mb-0">
                        <i class="fas fa-comment-alt me-2"></i> Alasan Penolakan
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p id="isiCatatanTolak" style="white-space:pre-wrap; line-height:1.6;"></p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    const modalApprove      = new bootstrap.Modal(document.getElementById('modalApprove'));
    const modalReject       = new bootstrap.Modal(document.getElementById('modalReject'));
    const modalCatatanTolak = new bootstrap.Modal(document.getElementById('modalCatatanTolak'));

    let currentId = null;

    // ── SETUJUI ────────────────────────────────────────────────
    document.querySelectorAll('.btn-approve').forEach(btn => {
        btn.addEventListener('click', function () {
            currentId = this.dataset.id;
            document.getElementById('approveNamaPeserta').textContent   = this.dataset.peserta;
            document.getElementById('approveNamaIndikator').textContent = this.dataset.indikator;
            document.getElementById('approveNilai').textContent         = this.dataset.nilai;
            modalApprove.show();
        });
    });

    document.getElementById('btnConfirmApprove').addEventListener('click', async function () {
        if (!currentId) return;
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses...';

        try {
            const res  = await fetch(`/verifikasi-nilai/${currentId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type':     'application/json',
                    'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            const data = await res.json();

            if (data.success) {
                modalApprove.hide();
                showToast('success', data.message);
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast('error', data.message || 'Gagal menyetujui.');
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-check me-2"></i> Ya, Setujui';
            }
        } catch (e) {
            showToast('error', 'Error jaringan.');
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-check me-2"></i> Ya, Setujui';
        }
    });

    // ── TOLAK ──────────────────────────────────────────────────
    document.querySelectorAll('.btn-reject').forEach(btn => {
        btn.addEventListener('click', function () {
            currentId = this.dataset.id;
            document.getElementById('rejectNamaPeserta').textContent   = this.dataset.peserta;
            document.getElementById('rejectNamaIndikator').textContent = this.dataset.indikator;
            document.getElementById('catatanVerifikator').value        = '';
            document.getElementById('catatanVerifikator').classList.remove('is-invalid');
            modalReject.show();
        });
    });

    document.getElementById('btnConfirmReject').addEventListener('click', async function () {
        if (!currentId) return;

        const catatan = document.getElementById('catatanVerifikator').value.trim();
        if (!catatan) {
            document.getElementById('catatanVerifikator').classList.add('is-invalid');
            return;
        }
        document.getElementById('catatanVerifikator').classList.remove('is-invalid');

        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses...';

        try {
            const res  = await fetch(`/verifikasi-nilai/${currentId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type':     'application/json',
                    'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ catatan_verifikator: catatan }),
            });
            const data = await res.json();

            if (data.success) {
                modalReject.hide();
                showToast('success', data.message);
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast('error', data.message || 'Gagal menolak.');
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-times me-2"></i> Tolak Submission';
            }
        } catch (e) {
            showToast('error', 'Error jaringan.');
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-times me-2"></i> Tolak Submission';
        }
    });

    // ── LIHAT SCREENSHOT ──────────────────────────────────────
    document.querySelectorAll('.btn-lihat-screenshot').forEach(btn => {
        btn.addEventListener('click', function () {
            previewGambar(
                this.dataset.src,
                this.dataset.peserta,
                this.dataset.indikator
            );
        });
    });

    // ── LIHAT CATATAN TOLAK ────────────────────────────────────
    document.querySelectorAll('.btn-lihat-catatan').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('isiCatatanTolak').textContent = this.dataset.catatan;
            modalCatatanTolak.show();
        });
    });

    // ── TOAST ──────────────────────────────────────────────────
    function showToast(type, msg) {
        const existing = document.getElementById('verif-toast');
        if (existing) existing.remove();

        const colors = { success: '#28a745', error: '#dc3545' };
        const icons  = { success: 'fa-check-circle', error: 'fa-times-circle' };

        const toast = document.createElement('div');
        toast.id        = 'verif-toast';
        toast.className = 'position-fixed bottom-0 end-0 m-4 shadow-lg';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <div class="d-flex align-items-center gap-2 px-4 py-3 rounded-3 text-white"
                style="background:${colors[type]}; min-width:240px;">
                <i class="fas ${icons[type]}"></i>
                <span class="small fw-semibold">${msg}</span>
            </div>`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3500);
    }
});

// ✅ Preview gambar dari GDrive dengan spinner + error handling
window.previewGambar = function(src, peserta, indikator) {
    peserta   = peserta   || '';
    indikator = indikator || '';

    const modalEl = document.getElementById('modalPreviewGambar');
    const img     = document.getElementById('previewGambarImg');
    const loading = document.querySelector('.preview-loading');

    // Reset state setiap kali dibuka
    img.classList.add('d-none');
    img.src = '';
    loading.innerHTML = '<div class="d-flex flex-column align-items-center py-4">'
        + '<div class="spinner-border text-light mb-3" style="width:2.5rem;height:2.5rem;" role="status"></div>'
        + '<span class="text-white-50 small">Memuat gambar dari server...</span>'
        + '</div>';
    loading.classList.remove('d-none');

    if (document.getElementById('previewTitle')) {
        document.getElementById('previewTitle').textContent    = 'Screenshot Bukti';
        document.getElementById('previewSubtitle').textContent = peserta + (indikator ? ' — ' + indikator : '');
    }

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();

    // Timeout 20 detik
    const loadTimeout = setTimeout(function() {
        loading.innerHTML = '<div class="d-flex flex-column align-items-center py-4">'
            + '<i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>'
            + '<span class="text-white fw-semibold mb-1">Waktu muat habis</span>'
            + '<span class="text-white-50 small mb-3">Koneksi lambat atau file tidak tersedia</span>'
            + '<button class="btn btn-sm btn-outline-light" onclick="previewGambar(this.dataset.src, this.dataset.p, this.dataset.i)"'
            + ' data-src="' + src + '" data-p="' + peserta + '" data-i="' + indikator + '">'
            + '<i class="fas fa-redo me-1"></i> Coba Lagi</button>'
            + '</div>';
    }, 20000);

    const tempImg = new Image();

    tempImg.onload = function() {
        clearTimeout(loadTimeout);
        img.src = src;
        img.classList.remove('d-none');
        loading.classList.add('d-none');
    };

    tempImg.onerror = function() {
        clearTimeout(loadTimeout);
        loading.innerHTML = '<div class="d-flex flex-column align-items-center py-4">'
            + '<i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>'
            + '<span class="text-white fw-semibold mb-1">Gagal memuat gambar</span>'
            + '<span class="text-white-50 small mb-3">File mungkin sedang diproses atau tidak tersedia</span>'
            + '<button class="btn btn-sm btn-outline-light" onclick="previewGambar(this.dataset.src, this.dataset.p, this.dataset.i)"'
            + ' data-src="' + src + '" data-p="' + peserta + '" data-i="' + indikator + '">'
            + '<i class="fas fa-redo me-1"></i> Coba Lagi</button>'
            + '</div>';
    };

    tempImg.src = src;
};
</script>

<style>
    .page-header { padding: 2rem; box-shadow: 0 4px 20px rgba(40,84,150,.15); }

    .verif-table th {
        border-bottom: 2px solid rgba(40,84,150,.1); font-weight:600;
        color:#285496; background-color:#f8fafc;
        padding:.7rem .9rem; font-size:.88rem; white-space:nowrap;
    }
    .verif-table td { padding:.7rem .9rem; vertical-align:middle; border-bottom:1px solid #e9ecef; font-size:.9rem; }

    .row-pending { background-color: rgba(255,193,7,.04); }
    .row-pending:hover { background-color: rgba(255,193,7,.08) !important; }

    .img-thumb-verif { border-radius:6px; transition:opacity .2s; }
    .img-thumb-verif:hover { opacity:.8; }

    .btn-xs { padding:.25rem .55rem; font-size:.78rem; border-radius:5px; }
</style>
@endsection