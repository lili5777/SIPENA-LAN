@extends('admin.partials.layout')

@section('title', 'Penilaian Mandiri - ' . $jenisPelatihan->nama_pelatihan)

@section('content')

    <!-- Page Header -->
    <div class="page-header rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%); padding: 2rem;">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-user-edit fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">Penilaian Mandiri</h1>
                        <p class="text-white-50 mb-0">
                            <span class="badge bg-white text-primary fw-semibold me-2">
                                {{ $jenisPelatihan->kode_pelatihan }}
                            </span>
                            {{ $jenisPelatihan->nama_pelatihan }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert -->
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
            <div class="flex-grow-1"><strong>Gagal!</strong> {{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Info Box -->
    <div class="alert alert-info d-flex align-items-start shadow-sm mb-4" role="alert">
        <i class="fas fa-info-circle fa-lg me-3 mt-1 flex-shrink-0"></i>
        <div>
            <strong>Cara pengisian:</strong>
            <ul class="mb-0 mt-1 ps-3 small">
                <li>Isi nilai <strong>0–100</strong> untuk setiap indikator</li>
                <li>Lampirkan <strong>screenshot/foto bukti</strong> (JPG/PNG, maks. 5MB) — <strong>wajib</strong></li>
                <li>Nilai akan diverifikasi oleh PIC/Evaluator sebelum masuk ke rekap</li>
                <li>Jika <span class="badge bg-danger">Ditolak</span>, Anda dapat submit ulang dengan perbaikan</li>
            </ul>
        </div>
    </div>

    @if($indikatorList->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-lock fa-4x mb-3" style="color:#e9ecef;"></i>
                <h5 class="text-muted mb-2">Belum ada indikator yang tersedia</h5>
                <p class="text-muted small">Admin/Evaluator belum mengaktifkan penilaian mandiri untuk Anda</p>
            </div>
        </div>
    @else

        @php
            $grouped = $indikatorList->groupBy(fn($ind) => $ind->jenisNilai->name ?? 'Lainnya');
        @endphp

        @foreach($grouped as $jenisNama => $indikators)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header border-0 py-3 px-4"
                    style="background:linear-gradient(135deg,rgba(40,84,150,.06),rgba(58,107,199,.04)); border-left:4px solid #285496 !important;">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-star me-2" style="color:#285496;"></i>
                        {{ $jenisNama }}
                    </h6>
                </div>

                <div class="card-body p-0">
                    @foreach($indikators as $ind)
                        @php
                            $upload    = $uploadList[$ind->id] ?? null;
                            $disetujui = $nilaiDisetujui[$ind->id] ?? null;
                            $status    = $upload?->status ?? null;
                        @endphp

                        <div class="indikator-row {{ !$loop->last ? 'border-bottom' : '' }} p-4">
                            <div class="row align-items-start g-3">

                                <!-- Info indikator -->
                                <div class="col-md-4">
                                    <div class="d-flex align-items-start">
                                        <div class="ind-num me-3">{{ $loop->iteration }}</div>
                                        <div>
                                            <div class="fw-bold">{{ $ind->name }}</div>
                                            <small class="text-muted">
                                                Bobot: <strong>{{ $ind->bobot }}%</strong>
                                            </small>
                                            @if($ind->deskripsi)
                                                <p class="text-muted small mb-0 mt-1">{{ $ind->deskripsi }}</p>
                                            @endif

                                            <!-- Status badge -->
                                            <div class="mt-2">
                                                @if($disetujui)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i> Disetujui
                                                    </span>
                                                    <span class="badge bg-light text-dark border ms-1">
                                                        Nilai: {{ $disetujui->nilai }}
                                                    </span>
                                                @elseif($status === 'pending')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-clock me-1"></i> Menunggu Verifikasi
                                                    </span>
                                                @elseif($status === 'ditolak')
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times-circle me-1"></i> Ditolak
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-minus-circle me-1"></i> Belum Disubmit
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Catatan tolak -->
                                            @if($status === 'ditolak' && $upload?->catatan_verifikator)
                                                <div class="alert alert-danger py-2 px-3 mt-2 mb-0 small">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    <strong>Alasan ditolak:</strong>
                                                    {{ $upload->catatan_verifikator }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Preview file dari Google Drive -->
                                <div class="col-md-3">
                                    @if($upload?->file)
                                        <div class="file-preview-box text-center py-2">
                                            <i class="fas fa-file-image fa-2x mb-2 d-block" style="color:#285496;"></i>
                                            <div class="mb-2">
                                                <small class="text-muted d-block">Screenshot tersedia</small>
                                            </div>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-primary btn-preview-img"
                                                data-src="{{ route('upload-nilai.file', $upload->id) }}?v={{ $upload->updated_at->timestamp }}">
                                                <i class="fas fa-eye me-2"></i> Lihat Screenshot
                                            </button>
                                            @if($upload->nilai)
                                                <div class="mt-2">
                                                    <small class="text-muted">Nilai diajukan: </small>
                                                    <strong class="text-primary">{{ $upload->nilai }}</strong>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-muted small text-center py-3">
                                            <i class="fas fa-image fa-2x mb-1 d-block" style="color:#dee2e6;"></i>
                                            Belum ada file
                                        </div>
                                    @endif
                                </div>

                                <!-- Form submit -->
                                <div class="col-md-5">
                                    @if($disetujui)
                                        <div class="text-center py-3 text-muted small">
                                            <i class="fas fa-lock fa-2x mb-2 d-block text-success"></i>
                                            Nilai sudah disetujui dan terkunci
                                        </div>
                                    @elseif($status === 'pending')
                                        <div class="text-center py-3 text-muted small">
                                            <i class="fas fa-hourglass-half fa-2x mb-2 d-block text-warning"></i>
                                            Menunggu verifikasi dari PIC/Evaluator
                                        </div>
                                    @else
                                        <form action="{{ route('penilaian-mandiri.store') }}"
                                            method="POST"
                                            enctype="multipart/form-data"
                                            class="submit-form">
                                            @csrf
                                            <input type="hidden" name="id_indikator_nilai" value="{{ $ind->id }}">

                                            @if($status === 'ditolak')
                                                <div class="alert alert-warning py-2 px-3 mb-3 small">
                                                    <i class="fas fa-redo me-1"></i>
                                                    <strong>Submit ulang</strong> — perbaiki nilai dan/atau screenshot Anda
                                                </div>
                                            @endif

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold mb-1">
                                                    <i class="fas fa-pencil-alt me-1" style="color:#285496;"></i>
                                                    Nilai <span class="text-muted fw-normal">(0 – 100)</span>
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="number"
                                                    name="nilai"
                                                    class="form-control form-control-sm"
                                                    min="0" max="100" step="1"
                                                    value="{{ old('nilai', $upload?->nilai) }}"
                                                    placeholder="0 – 100"
                                                    required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold mb-1">
                                                    <i class="fas fa-image me-1" style="color:#285496;"></i>
                                                    Screenshot Bukti <span class="text-danger">*</span>
                                                </label>
                                                <input type="file"
                                                    name="file"
                                                    class="form-control form-control-sm file-input"
                                                    accept="image/jpeg,image/png,image/webp"
                                                    required>
                                                <small class="text-muted">JPG/PNG/WebP, maks. 5MB</small>
                                                <!-- Preview lokal sebelum upload -->
                                                <div class="file-preview mt-2 d-none">
                                                    <img src="" alt="preview" class="img-thumbnail"
                                                        style="max-height:100px; width:100%; object-fit:cover;">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold mb-1">
                                                    <i class="fas fa-comment me-1" style="color:#285496;"></i>
                                                    Catatan <span class="text-muted fw-normal">(opsional)</span>
                                                </label>
                                                <textarea name="catatan_peserta"
                                                    class="form-control form-control-sm"
                                                    rows="2"
                                                    placeholder="Keterangan tambahan..."
                                                    maxlength="500">{{ old('catatan_peserta', $upload?->catatan_peserta) }}</textarea>
                                            </div>

                                            {{-- ✅ Tombol dengan spinner saat submit --}}
                                            <button type="submit" class="btn btn-primary btn-sm w-100 btn-submit-nilai">
                                                <span class="btn-normal-state">
                                                    <i class="fas fa-paper-plane me-2"></i>
                                                    {{ $status === 'ditolak' ? 'Submit Ulang' : 'Submit Nilai' }}
                                                </span>
                                                <span class="btn-loading-state d-none">
                                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                                    Mengupload ke server...
                                                </span>
                                            </button>
                                        </form>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

    @endif

    <!-- Modal Preview Gambar -->
    <div class="modal fade" id="modalPreviewGambar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 py-2 px-4"
                    style="background:linear-gradient(135deg,#285496,#3a6bc7);">
                    <h6 class="text-white mb-0 fw-bold">
                        <i class="fas fa-image me-2"></i> Preview Screenshot
                    </h6>
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

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Auto-hide alerts
    document.querySelectorAll('.alert:not(.alert-info)').forEach(a => {
        setTimeout(() => bootstrap.Alert.getOrCreateInstance(a)?.close(), 6000);
    });

    // ✅ Preview lokal sebelum upload
    document.querySelectorAll('.file-input').forEach(input => {
        input.addEventListener('change', function () {
            const previewBox = this.closest('.mb-3').querySelector('.file-preview');
            const previewImg = previewBox.querySelector('img');
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    previewImg.src = e.target.result;
                    previewBox.classList.remove('d-none');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    // ✅ Disable tombol + tampilkan spinner saat form submit
    document.querySelectorAll('.submit-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            const btn         = this.querySelector('.btn-submit-nilai');
            const normalState = btn.querySelector('.btn-normal-state');
            const loadingState= btn.querySelector('.btn-loading-state');

            // Validasi manual sebelum disable
            const fileInput = this.querySelector('input[name="file"]');
            const nilaiInput= this.querySelector('input[name="nilai"]');

            if (!fileInput.files || fileInput.files.length === 0) return; // biarkan HTML validation
            if (!nilaiInput.value) return;

            // Disable tombol dan tampilkan spinner
            btn.disabled = true;
            normalState.classList.add('d-none');
            loadingState.classList.remove('d-none');

            // Safety: re-enable setelah 30 detik jika tidak ada response
            setTimeout(() => {
                btn.disabled = false;
                normalState.classList.remove('d-none');
                loadingState.classList.add('d-none');
            }, 30000);
        });
    });

    // Tombol lihat gambar
    document.querySelectorAll('.btn-preview-img').forEach(btn => {
        btn.addEventListener('click', function () {
            previewGambar(this.dataset.src);
        });
    });
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

    .indikator-row { transition: background .2s; }
    .indikator-row:hover { background: rgba(40,84,150,.02); }

    .ind-num {
        width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
        background: linear-gradient(135deg, #285496, #3a6bc7);
        color: white; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .85rem;
    }

    .file-preview-box {
        background: #f8fafc; border-radius: 8px; padding: .75rem;
        border: 1px solid #e9ecef;
    }
    .img-preview-thumb { border-radius: 6px; transition: opacity .2s; }
    .img-preview-thumb:hover { opacity: .85; }

    .btn-xs { padding: .2rem .5rem; font-size: .75rem; border-radius: 5px; }

    .submit-form .form-control-sm { font-size: .88rem; }
    .submit-form .form-label { font-size: .88rem; }

    /* ✅ Tombol saat loading */
    .btn-submit-nilai:disabled {
        opacity: .85;
        cursor: not-allowed;
    }
</style>
@endsection