@extends('admin.partials.layout')

@section('title', 'Penilaian Peserta - ' . $jenisPelatihan->nama_pelatihan)

@section('content')

    <div class="page-header rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%); padding: 2rem;">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-star fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">Penilaian Peserta</h1>
                        <p class="text-white-50 mb-0">
                            <span class="badge bg-white text-primary fw-semibold me-2">{{ $jenisPelatihan->kode_pelatihan }}</span>
                            {{ $jenisPelatihan->nama_pelatihan }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('nilai.rekap', ['jenis' => $jenis]) }}" class="btn btn-light shadow-sm">
                    <i class="fas fa-chart-bar me-2"></i> Rekapan Nilai
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-check-circle fa-lg me-3"></i>
            <div class="flex-grow-1"><strong>Sukses!</strong> {{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php $roleName = auth()->user()->role->name ?? ''; @endphp
    @if(in_array($roleName, ['coach', 'penguji']))
        <div class="alert alert-info d-flex align-items-center shadow-sm mb-4 py-2" role="alert">
            <i class="fas fa-info-circle me-2 flex-shrink-0"></i>
            <small>
                Menampilkan peserta dari kelompok Anda.
                Gunakan filter <strong>Kelompok</strong> untuk melihat nilai peserta kelompok lain (view only).
            </small>
        </div>
    @elseif($roleName === 'pic')
        <div class="alert alert-info d-flex align-items-center shadow-sm mb-4 py-2" role="alert">
            <i class="fas fa-info-circle me-2 flex-shrink-0"></i>
            <small>Menampilkan peserta dari angkatan yang Anda tangani.</small>
        </div>
    @endif

    @if(isset($kelompokFilter) && $kelompokFilter && $kelompokFilter->link_laporan)
        <div class="card border-0 shadow-sm mb-4"
            style="border-left: 4px solid #285496 !important; border-radius: 10px !important;">
            <div class="card-body py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-3 flex-shrink-0"
                            style="background:rgba(40,84,150,.1);">
                            <i class="fas fa-folder-open" style="color:#285496; font-size:1.1rem;"></i>
                        </div>
                        <div>
                            <div class="fw-semibold" style="color:#285496;">
                                Dokumen Laporan — {{ $kelompokFilter->nama_kelompok }}
                            </div>
                            <small class="text-muted">
                                {{ $kelompokFilter->angkatan->nama_angkatan ?? '' }}
                                · {{ $kelompokFilter->jenisPelatihan->nama_pelatihan ?? '' }}
                            </small>
                        </div>
                    </div>
                    <a href="{{ $kelompokFilter->link_laporan }}"
                        target="_blank" rel="noopener"
                        class="btn btn-primary btn-sm px-4 shadow-sm flex-shrink-0">
                        <i class="fas fa-external-link-alt me-2"></i>
                        Buka Dokumen Laporan
                    </a>
                </div>
            </div>
        </div>
    @elseif(isset($kelompokFilter) && $kelompokFilter && !$kelompokFilter->link_laporan)
        <div class="alert alert-warning d-flex align-items-center shadow-sm mb-4 py-2" role="alert">
            <i class="fas fa-folder-open me-2 flex-shrink-0"></i>
            <small>
                <strong>{{ $kelompokFilter->nama_kelompok }}</strong>
                — belum ada link laporan yang ditambahkan untuk kelompok ini.
            </small>
        </div>
    @endif

    <!-- Filter & Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('nilai.index', ['jenis' => $jenis]) }}" method="GET">
                <div class="row g-2 align-items-end">
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label small text-muted mb-1">
                            <i class="fas fa-layer-group me-1"></i> Angkatan
                        </label>
                        <select name="angkatan" class="form-select form-select-sm">
                            <option value="">Semua Angkatan</option>
                            @foreach($angkatanRomawi as $romawi)
                                <option value="{{ $romawi }}" {{ request('angkatan') == $romawi ? 'selected' : '' }}>
                                    Angkatan {{ $romawi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label small text-muted mb-1">
                            <i class="fas fa-calendar-alt me-1"></i> Tahun
                        </label>
                        <select name="tahun" class="form-select form-select-sm">
                            <option value="">Semua Tahun</option>
                            @foreach(array_reverse($tahunList) as $tahun)
                                <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label small text-muted mb-1">
                            <i class="fas fa-users me-1"></i> Kelompok
                        </label>
                        <select name="kelompok" class="form-select form-select-sm">
                            <option value="">Semua Kelompok</option>
                            @foreach($kelompokList as $k)
                                <option value="{{ $k }}" {{ request('kelompok') == $k ? 'selected' : '' }}>
                                    Kelompok {{ $k }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label small text-muted mb-1">
                            <i class="fas fa-tag me-1"></i> Kategori
                        </label>
                        <select name="kategori" class="form-select form-select-sm" id="filterKategori">
                            <option value="">Semua Kategori</option>
                            <option value="PNBP"       {{ request('kategori') == 'PNBP'       ? 'selected' : '' }}>PNBP</option>
                            <option value="FASILITASI" {{ request('kategori') == 'FASILITASI' ? 'selected' : '' }}>FASILITASI</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6" id="filterWilayahWrapper"
                        style="{{ request('kategori') == 'FASILITASI' ? '' : 'display:none' }}">
                        <label class="form-label small text-muted mb-1">
                            <i class="fas fa-map-marker-alt me-1"></i> Wilayah
                        </label>
                        <input type="text"
                            name="wilayah"
                            id="filterWilayah"
                            class="form-control form-control-sm"
                            list="wilayahDatalistIndex"
                            placeholder="Ketik wilayah..."
                            value="{{ request('wilayah') }}">
                        <datalist id="wilayahDatalistIndex">
                            @foreach($wilayahList as $w)
                                <option value="{{ $w }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-md-2 col-sm-8">
                        <label class="form-label small text-muted mb-1">
                            <i class="fas fa-search me-1"></i> Cari Peserta
                        </label>
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Nama atau NIP..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 col-sm-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('nilai.index', ['jenis' => $jenis]) }}"
                                class="btn btn-outline-secondary btn-sm flex-fill">
                                <i class="fas fa-redo me-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                @php
                    $activeFilters = array_filter([
                        'Angkatan'  => request('angkatan')  ? 'Angkatan ' . request('angkatan')  : null,
                        'Tahun'     => request('tahun'),
                        'Kelompok'  => request('kelompok')  ? 'Kelompok ' . request('kelompok')  : null,
                        'Kategori'  => request('kategori'),
                        'Wilayah'   => request('wilayah'),
                        'Cari'      => request('search'),
                    ]);
                @endphp
                @if(count($activeFilters))
                    <div class="d-flex flex-wrap gap-1 mt-2">
                        @foreach($activeFilters as $label => $val)
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 fw-normal" style="font-size:.78rem;">
                                <i class="fas fa-check-circle me-1" style="font-size:.65rem;"></i>
                                {{ $label }}: {{ $val }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-lg overflow-hidden">
        <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0 fw-semibold">
                <i class="fas fa-list me-2" style="color: #285496;"></i> Daftar Peserta
                <span class="badge bg-primary ms-2">{{ $peserta->total() }}</span>
            </h5>
            <small class="text-muted">
                Menampilkan {{ $peserta->count() }} dari {{ $peserta->total() }} peserta
            </small>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr class="table-light">
                            <th width="4%" class="ps-4">No</th>
                            <th width="30%">Nama Peserta</th>
                            <th width="7%">NDH</th>
                            <th width="20%">Kelompok</th>
                            <th width="22%">Progress Nilai</th>
                            <th width="17%" class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peserta as $index => $item)
                            @php
                                $kelompok        = $item->kelompokInfo ?? null;
                                $totalInd        = $item->totalIndikator ?? 0;
                                $sudahDinilai    = $item->sudahDinilai ?? 0;
                                $persen          = $totalInd > 0 ? round(($sudahDinilai / $totalInd) * 100) : 0;
                                $bisaDinilaiUser = $item->bisaDinilaiUser ?? true;
                            @endphp
                            <tr data-peserta-row="{{ $item->id }}">
                                <td class="ps-4 fw-semibold">{{ $peserta->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="peserta-avatar me-2">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold small">{{ $item->nama_lengkap }}</div>
                                            <small class="text-muted">
                                                <i class="fas fa-id-card me-1"></i>{{ $item->nip_nrp ?? '-' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($item->ndh)
                                        <span class="badge bg-light text-dark border fw-bold">{{ $item->ndh }}</span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($kelompok)
                                        <div class="d-flex align-items-center">
                                            <div class="kelompok-dot me-2"></div>
                                            <div>
                                                <div class="small fw-semibold d-flex align-items-center gap-1">
                                                    {{ $kelompok->nama_kelompok }}
                                                    @if(!request()->filled('kelompok') && $kelompok->link_laporan)
                                                        <a href="{{ $kelompok->link_laporan }}"
                                                            target="_blank" rel="noopener"
                                                            class="link-laporan-icon"
                                                            data-bs-toggle="tooltip"
                                                            title="Buka Dokumen Laporan {{ $kelompok->nama_kelompok }}"
                                                            onclick="event.stopPropagation()">
                                                            <i class="fas fa-file-alt"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                                <small class="text-muted">{{ $kelompok->angkatan->nama_angkatan ?? '-' }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($totalInd > 0)
                                        {{-- FIX #1: tambahkan data attributes dan id untuk update realtime --}}
                                        <div class="d-flex align-items-center gap-2"
                                            data-progress-peserta="{{ $item->id }}">
                                            <div class="flex-grow-1">
                                                <div class="progress" style="height:6px; border-radius:4px;">
                                                    <div class="progress-bar
                                                        {{ $persen >= 100 ? 'bg-success' : ($persen > 0 ? 'bg-primary' : 'bg-secondary') }}"
                                                        id="progressbar-{{ $item->id }}"
                                                        style="width:{{ $persen }}%"></div>
                                                </div>
                                            </div>
                                            <small class="text-muted fw-semibold"
                                                id="progresspct-{{ $item->id }}"
                                                style="min-width:36px">{{ $persen }}%</small>
                                        </div>
                                        <small class="text-muted"
                                            id="progresslabel-{{ $item->id }}">{{ $sudahDinilai }}/{{ $totalInd }} indikator</small>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <button type="button"
                                        class="btn btn-sm btn-action btn-primary btn-nilai"
                                        data-peserta-id="{{ $item->id }}"
                                        data-peserta-nama="{{ $item->nama_lengkap }}"
                                        data-total-indikator="{{ $totalInd }}"
                                        data-bisa-nilai="{{ $bisaDinilaiUser ? '1' : '0' }}"
                                        data-bs-toggle="tooltip"
                                        title="Input Nilai">
                                        <i class="fas fa-star me-1"></i> Nilai
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-users fa-4x mb-3" style="color: #e9ecef;"></i>
                                    <h5 class="text-muted mb-2">Belum ada peserta</h5>
                                    <p class="text-muted">Tidak ada peserta yang ditemukan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($peserta->count() > 0)
            <div class="card-footer bg-white py-3 border-0">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <small class="text-muted">
                            Menampilkan <strong>{{ $peserta->firstItem() }}</strong>
                            sampai <strong>{{ $peserta->lastItem() }}</strong>
                            dari <strong>{{ $peserta->total() }}</strong> peserta
                        </small>
                    </div>
                    <div class="col-md-6">
                        @if($peserta->hasPages())
                            <nav>
                                <ul class="pagination pagination-sm justify-content-md-end justify-content-center mb-0">
                                    @if($peserta->onFirstPage())
                                        <li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $peserta->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a></li>
                                    @endif
                                    @php
                                        $start = max($peserta->currentPage() - 2, 1);
                                        $end   = min($start + 4, $peserta->lastPage());
                                        $start = max($end - 4, 1);
                                    @endphp
                                    @if($start > 1)
                                        <li class="page-item"><a class="page-link" href="{{ $peserta->url(1) }}">1</a></li>
                                        @if($start > 2)<li class="page-item disabled"><span class="page-link">...</span></li>@endif
                                    @endif
                                    @for($i = $start; $i <= $end; $i++)
                                        <li class="page-item {{ $i == $peserta->currentPage() ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $peserta->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                                    @if($end < $peserta->lastPage())
                                        @if($end < $peserta->lastPage() - 1)<li class="page-item disabled"><span class="page-link">...</span></li>@endif
                                        <li class="page-item"><a class="page-link" href="{{ $peserta->url($peserta->lastPage()) }}">{{ $peserta->lastPage() }}</a></li>
                                    @endif
                                    @if($peserta->hasMorePages())
                                        <li class="page-item"><a class="page-link" href="{{ $peserta->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a></li>
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

    {{-- ===== MODAL PENILAIAN ===== --}}
    <div class="modal fade" id="modalNilai" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg overflow-hidden" style="max-height:90vh; display:flex; flex-direction:column;">

                <div class="modal-header p-0 border-0 flex-shrink-0">
                    <div class="w-100" id="modalHeader"
                        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%); padding: 1.25rem 1.5rem;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-white rounded-circle p-2 me-3 shadow-sm">
                                    <i class="fas fa-star" id="modalHeaderIcon" style="color:#285496; font-size:1rem;"></i>
                                </div>
                                <div>
                                    <h5 class="text-white mb-0 fw-bold" id="modalNilaiTitle">Input Nilai Peserta</h5>
                                    <small class="text-white-50" id="modalNilaiSubtitle">—</small>
                                </div>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                    </div>
                </div>

                <div class="modal-body p-0 flex-grow-1 overflow-hidden d-flex flex-column">
                    <div class="text-center py-5" id="nilaiLoading">
                        <div class="spinner-border text-primary" style="width:3rem;height:3rem;" role="status"></div>
                        <p class="mt-3 text-muted">Memuat data penilaian...</p>
                    </div>

                    <div id="nilaiNoAccess" class="d-none flex-grow-1 d-flex align-items-center justify-content-center">
                        <div class="text-center py-5 px-4">
                            <div class="mb-3" style="font-size:3rem;">🔒</div>
                            <h5 class="fw-bold text-muted mb-2">Tidak Ada Akses</h5>
                            <p class="text-muted mb-0">Anda tidak memiliki akses untuk menilai peserta ini.</p>
                        </div>
                    </div>

                    <div id="nilaiContent" class="d-none flex-grow-1 overflow-hidden" style="display:flex !important; flex-direction:column;">
                        <div class="d-flex flex-grow-1 overflow-hidden" style="min-height:0;">

                            <!-- Panel Kiri -->
                            <div class="border-end bg-light d-flex flex-column flex-shrink-0" style="width:260px; overflow:hidden;">
                                <div class="p-3 border-bottom flex-shrink-0">
                                    <small class="text-muted fw-semibold text-uppercase" style="font-size:.7rem; letter-spacing:.5px;">
                                        <i class="fas fa-layer-group me-1"></i> Jenis Nilai
                                    </small>
                                </div>
                                <div id="jenisNilaiTabs" class="p-2 flex-grow-1" style="overflow-y:auto;"></div>
                                <div class="p-3 border-top flex-shrink-0">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="text-muted fw-semibold">Total Nilai</small>
                                        <small class="fw-bold text-primary" id="totalNilaiLabel">0 / 100</small>
                                    </div>
                                    <div class="progress" style="height:8px; border-radius:4px;">
                                        <div class="progress-bar bg-success" id="totalProgressBar"
                                            style="width:0%; transition:width .4s ease;"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Panel Kanan -->
                            <div class="flex-grow-1" style="overflow-y:auto; overflow-x:hidden;">
                                <div id="indikatorContent" class="p-4">
                                    <div class="text-center py-5 text-muted">
                                        <i class="fas fa-arrow-left fa-2x mb-2 d-block"></i>
                                        <p>Pilih jenis nilai di sebelah kiri</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top py-2 px-4 flex-shrink-0">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <small class="text-muted" id="modalFooterInfo">
                            <i class="fas fa-info-circle me-1"></i>
                            Isi semua nilai lalu klik <strong>Simpan Semua</strong> di bagian bawah.
                        </small>
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i> Tutup
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    const filterKategori       = document.getElementById('filterKategori');
    const filterWilayahWrapper = document.getElementById('filterWilayahWrapper');
    const filterWilayah        = document.getElementById('filterWilayah');

    if (filterKategori) {
        filterKategori.addEventListener('change', function () {
            if (this.value === 'FASILITASI') {
                filterWilayahWrapper.style.display = '';
            } else {
                filterWilayahWrapper.style.display = 'none';
                filterWilayah.value = '';
            }
        });
    }

    const modalNilai       = new bootstrap.Modal(document.getElementById('modalNilai'));
    let currentPesertaId   = null;
    let currentTotalInd    = 0;
    let nilaiData          = {};
    let catatanData        = {};
    let activeJenisNilaiId = null;
    let jenisNilaiCache    = [];
    let pesertaMilikUser   = true;

    document.querySelectorAll('.btn-nilai').forEach(btn => {
        btn.addEventListener('click', function () {
            currentPesertaId = this.dataset.pesertaId;
            currentTotalInd  = parseInt(this.dataset.totalIndikator) || 0;
            const nama       = this.dataset.pesertaNama;

            document.getElementById('modalNilaiTitle').textContent    = 'Nilai: ' + nama;
            document.getElementById('modalNilaiSubtitle').textContent = 'Memuat...';
            document.getElementById('nilaiLoading').classList.remove('d-none');
            document.getElementById('nilaiLoading').style.display    = '';
            document.getElementById('nilaiContent').classList.add('d-none');
            document.getElementById('nilaiContent').style.display    = 'none';
            document.getElementById('nilaiNoAccess').classList.add('d-none');
            document.getElementById('nilaiNoAccess').style.display   = 'none';
            document.getElementById('jenisNilaiTabs').innerHTML       = '';
            document.getElementById('indikatorContent').innerHTML     = '';
            document.getElementById('totalProgressBar').style.width  = '0%';
            document.getElementById('totalNilaiLabel').textContent    = '0 / 100';

            nilaiData = {}; catatanData = {}; activeJenisNilaiId = null;
            jenisNilaiCache = []; pesertaMilikUser = true;

            modalNilai.show();
            loadNilaiData(currentPesertaId);
        });
    });

    async function loadNilaiData(pesertaId) {
        try {
            const res  = await fetch(`/nilai/get-data/${pesertaId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();

            if (!data.success) throw new Error(data.message);

            nilaiData        = data.existing_nilai   || {};
            catatanData      = data.existing_catatan || {};
            pesertaMilikUser = data.peserta_milik_user !== false;

            const rawJenisNilai = data.jenis_nilai || [];

            jenisNilaiCache = rawJenisNilai
                .map(jn => {
                    const indikatorBisaDinilai = (jn.indikator_nilai || []).filter(
                        ind => ind.user_dapat_nilai !== false
                    );
                    return { ...jn, indikator_nilai: indikatorBisaDinilai };
                })
                .filter(jn => jn.indikator_nilai.length > 0);

            const loadingEl   = document.getElementById('nilaiLoading');
            const contentEl   = document.getElementById('nilaiContent');
            const noAccessEl  = document.getElementById('nilaiNoAccess');

            loadingEl.classList.add('d-none');
            loadingEl.style.display = 'none';

            if (jenisNilaiCache.length === 0) {
                noAccessEl.classList.remove('d-none');
                noAccessEl.style.display = 'flex';
                document.getElementById('modalNilaiSubtitle').textContent = 'Tidak ada akses';
                return;
            }

            contentEl.classList.remove('d-none');
            contentEl.style.display = 'flex';

            const aksi = data.aksi_perubahan;
            let subtitle = jenisNilaiCache.length + ' Jenis Nilai';
            if (aksi && aksi.judul) {
                subtitle += ' · 📄 ' + aksi.judul;
                if (aksi.kategori_aksatika) {
                    subtitle += ' (' + aksi.kategori_aksatika + ')';
                }
            }
            document.getElementById('modalNilaiSubtitle').textContent = subtitle;

            renderJenisNilaiTabs(jenisNilaiCache);
            updateTotalNilai(jenisNilaiCache);
            selectJenisNilai(jenisNilaiCache[0]);

        } catch (err) {
            document.getElementById('nilaiLoading').innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-circle fa-3x text-danger mb-3 d-block"></i>
                    <p class="text-muted">Gagal memuat: ${err.message}</p>
                </div>`;
        }
    }

    function renderJenisNilaiTabs(list) {
        const container = document.getElementById('jenisNilaiTabs');
        container.innerHTML = '';

        list.forEach(jn => {
            const indList = jn.indikator_nilai || [];
            const terisi  = indList.filter(ind =>
                nilaiData[ind.id] !== undefined &&
                nilaiData[ind.id] !== null &&
                nilaiData[ind.id] !== ''
            ).length;
            const total   = indList.length;
            const selesai = total > 0 && terisi === total;
            const persen  = total > 0 ? Math.round((terisi / total) * 100) : 0;

            const btn = document.createElement('button');
            btn.type       = 'button';
            btn.className  = 'jenis-nilai-tab w-100 text-start mb-1' +
                (activeJenisNilaiId == jn.id ? ' active' : '');
            btn.dataset.id = jn.id;
            btn.innerHTML  = `
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div class="d-flex align-items-center gap-2">
                        <div class="jn-dot ${selesai ? 'done' : ''}"></div>
                        <span class="fw-semibold small">${jn.name}</span>
                    </div>
                    <span class="badge ${selesai ? 'bg-success' : 'bg-secondary bg-opacity-25 text-dark'} small">
                        ${jn.bobot}%
                    </span>
                </div>
                <div class="progress" style="height:3px; border-radius:2px; background:rgba(0,0,0,.1);">
                    <div class="progress-bar bg-primary" style="width:${persen}%; transition:width .3s;"></div>
                </div>
                <small style="font-size:.68rem; color:rgba(0,0,0,.45);">
                    ${terisi}/${total} terisi
                </small>
            `;
            btn.addEventListener('click', () => selectJenisNilai(jn));
            container.appendChild(btn);
        });
    }

    function selectJenisNilai(jn) {
        activeJenisNilaiId = jn.id;
        document.querySelectorAll('.jenis-nilai-tab').forEach(t => t.classList.remove('active'));
        const tab = document.querySelector(`.jenis-nilai-tab[data-id="${jn.id}"]`);
        if (tab) tab.classList.add('active');
        renderIndikatorPanel(jn);
    }

    // =========================================================
    // FIX #3 — Layout indikator: panduan & input sejajar
    // =========================================================
    function renderIndikatorPanel(jn) {
        const container = document.getElementById('indikatorContent');
        const indList   = jn.indikator_nilai || [];

        let html = `
            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                <div>
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-star me-2" style="color:#285496"></i>${jn.name}
                    </h6>
                    <small class="text-muted">
                        Bobot: <strong>${jn.bobot}%</strong>
                        &nbsp;·&nbsp;
                        <span class="text-success">${indList.length} indikator</span>
                    </small>
                </div>
                <span class="badge bg-primary">${indList.length} Indikator</span>
            </div>
        `;

        if (indList.length === 0) {
            html += `<div class="text-center py-4 text-muted">
                        <i class="fas fa-tasks fa-2x mb-2 d-block"></i>
                        <p>Belum ada indikator</p>
                     </div>`;
        } else {
            indList.forEach((ind, idx) => {
                const nilaiSaatIni = nilaiData[ind.id] ?? '';
                const detailList   = ind.detail_indikator || [];
                const hasDetail    = detailList.length > 0;

                // Preview konversi awal jika nilai sudah ada
                const konversiHtml = (nilaiSaatIni !== '' && nilaiSaatIni !== null)
                    ? `<div class="preview-formula saved mt-1" id="preview-${ind.id}">
                            ${nilaiSaatIni} / 100 &times; ${ind.bobot}%
                            = <strong>${(parseFloat(nilaiSaatIni) / 100 * ind.bobot).toFixed(2)}</strong>
                            <i class="fas fa-check-circle text-success ms-1"></i>
                       </div>`
                    : `<div class="mt-1" id="preview-${ind.id}"></div>`;

                if (hasDetail) {
                    // ── Layout 2 kolom: kiri panduan, kanan input nilai ──
                    html += `
                    <div class="indikator-card mb-3">
                        <div class="d-flex align-items-start mb-2">
                            <div class="indikator-number me-2">${idx + 1}</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small">${ind.name}</div>
                                <small class="text-muted">Bobot: <strong>${ind.bobot}%</strong></small>
                                ${ind.deskripsi ? `<p class="text-muted small mb-0 mt-1">${ind.deskripsi}</p>` : ''}
                            </div>
                        </div>

                        <div class="row g-3 align-items-start">
                            {{-- Kolom kiri: panduan level --}}
                            <div class="col-lg-7 col-12">
                                <div class="panduan-header mb-2">
                                    <small class="text-muted fw-semibold" style="font-size:.72rem;">
                                        <i class="fas fa-list-ul me-1"></i> Panduan Level
                                        <span class="text-primary">— klik level untuk isi nilai otomatis</span>
                                    </small>
                                </div>
                                <div class="panduan-grid">
                                    ${detailList.map(det => `
                                        <div class="detail-level-card level-${det.level}"
                                            onclick="pilihDariDetail(${ind.id}, ${ind.bobot}, '${(det.range || '').replace(/'/g, '')}', this)">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="level-badge">Level ${det.level ?? '-'}</span>
                                                ${det.range ? `<span class="range-badge">${det.range}</span>` : ''}
                                            </div>
                                            <p class="mb-0" style="font-size:.8rem; line-height:1.4;">${det.uraian ?? '-'}</p>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>

                            {{-- Kolom kanan: sticky input nilai --}}
                            <div class="col-lg-5 col-12">
                                <div class="nilai-sticky-box">
                                    <div class="nilai-sticky-label mb-2">
                                        <i class="fas fa-pencil-alt me-1" style="color:#285496"></i>
                                        <span class="fw-semibold small">Input Nilai</span>
                                        <span class="text-muted small ms-1">(0 – 100)</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                                        <input type="number"
                                            class="form-control nilai-input"
                                            id="input-nilai-${ind.id}"
                                            data-indikator-id="${ind.id}"
                                            data-bobot="${ind.bobot}"
                                            value="${nilaiSaatIni}"
                                            min="0" max="100" step="1"
                                            placeholder="0 – 100"
                                            oninput="hitungPreview(${ind.id}, ${ind.bobot}, this.value)">
                                        <span class="text-muted small fw-semibold">/ 100</span>
                                    </div>
                                    ${konversiHtml}
                                    <div class="mt-2" id="status-${ind.id}"></div>
                                    <div class="nilai-hint mt-2">
                                        <i class="fas fa-lightbulb text-warning me-1"></i>
                                        <small class="text-muted">Klik panduan level di kiri untuk isi otomatis</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                } else {
                    // ── Layout tanpa panduan: sederhana 1 baris ──
                    html += `
                    <div class="indikator-card mb-3">
                        <div class="d-flex align-items-start">
                            <div class="indikator-number me-2">${idx + 1}</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small mb-1">${ind.name}</div>
                                <small class="text-muted">Bobot: <strong>${ind.bobot}%</strong></small>
                                ${ind.deskripsi ? `<p class="text-muted small mb-2 mt-1">${ind.deskripsi}</p>` : ''}
                                <div class="d-flex align-items-center gap-2 flex-wrap mt-2">
                                    <input type="number"
                                        class="form-control nilai-input"
                                        id="input-nilai-${ind.id}"
                                        data-indikator-id="${ind.id}"
                                        data-bobot="${ind.bobot}"
                                        value="${nilaiSaatIni}"
                                        min="0" max="100" step="1"
                                        placeholder="0 – 100"
                                        oninput="hitungPreview(${ind.id}, ${ind.bobot}, this.value)">
                                    <span class="text-muted small fw-semibold">/ 100</span>
                                    <div id="status-${ind.id}" class="simpan-status"></div>
                                </div>
                                ${konversiHtml}
                            </div>
                        </div>
                    </div>`;
                }
            });
        }

        // =========================================================
        // FIX #2 — Tombol simpan 1x per jenis nilai (batch)
        // =========================================================
        if (pesertaMilikUser && indList.length > 0) {
            const catatanSaatIni = catatanData[jn.id] ?? '';

            html += `
            <div class="catatan-wrapper mt-3 pt-3 border-top">
                <label class="form-label fw-semibold small">
                    <i class="fas fa-sticky-note me-1 text-warning"></i>
                    Catatan <span class="text-muted fw-normal">— ${jn.name}</span>
                </label>
                <textarea class="form-control form-control-sm"
                    id="catatan-${jn.id}" rows="3"
                    placeholder="Catatan penilaian..."
                >${catatanSaatIni}</textarea>
            </div>

            <div class="simpan-batch-bar mt-3 p-3 rounded-3 border">
                <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                    <div>
                        <div class="fw-semibold small mb-0">
                            <i class="fas fa-save me-1" style="color:#285496"></i>
                            Simpan semua nilai — <em>${jn.name}</em>
                        </div>
                        <small class="text-muted">
                            Semua nilai dan catatan di atas akan disimpan sekaligus.
                        </small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span id="batch-status-${jn.id}" class="simpan-status"></span>
                        <button type="button"
                            class="btn btn-primary px-4 btn-simpan-batch"
                            onclick="simpanBatch(${jn.id}, ${currentPesertaId})">
                            <i class="fas fa-save me-2"></i>
                            Simpan Semua
                        </button>
                    </div>
                </div>
            </div>`;
        }

        container.innerHTML = html;
    }

    function updateTotalNilai(jnList) {
        const list = jnList || jenisNilaiCache;
        let total  = 0;
        list.forEach(jn => {
            (jn.indikator_nilai || []).forEach(ind => {
                const val   = parseFloat(nilaiData[ind.id]);
                const bobot = parseFloat(ind.bobot);
                if (!isNaN(val) && !isNaN(bobot)) total += (val / 100) * bobot;
            });
        });
        const persen = Math.min(Math.round(total), 100);
        document.getElementById('totalProgressBar').style.width = persen + '%';
        document.getElementById('totalNilaiLabel').textContent  = total.toFixed(2) + ' / 100';
    }

    function refreshTabs() {
        renderJenisNilaiTabs(jenisNilaiCache);
        if (activeJenisNilaiId) {
            const t = document.querySelector(`.jenis-nilai-tab[data-id="${activeJenisNilaiId}"]`);
            if (t) t.classList.add('active');
        }
    }

    // =========================================================
    // FIX #1 — Update progress bar di tabel index secara realtime
    // =========================================================
    function updateProgressBarIndex(pesertaId) {
        if (!pesertaId) return;

        // Hitung berapa indikator yang sudah terisi dari nilaiData saat ini
        let terisiSekarang = 0;
        jenisNilaiCache.forEach(jn => {
            (jn.indikator_nilai || []).forEach(ind => {
                if (nilaiData[ind.id] !== undefined &&
                    nilaiData[ind.id] !== null &&
                    nilaiData[ind.id] !== '') {
                    terisiSekarang++;
                }
            });
        });

        const totalInd = currentTotalInd;
        if (totalInd <= 0) return;

        const persen = Math.round((terisiSekarang / totalInd) * 100);

        const barEl   = document.getElementById(`progressbar-${pesertaId}`);
        const pctEl   = document.getElementById(`progresspct-${pesertaId}`);
        const lblEl   = document.getElementById(`progresslabel-${pesertaId}`);

        if (barEl) {
            barEl.style.width = persen + '%';
            barEl.className = 'progress-bar ' +
                (persen >= 100 ? 'bg-success' : persen > 0 ? 'bg-primary' : 'bg-secondary');
        }
        if (pctEl) pctEl.textContent = persen + '%';
        if (lblEl) lblEl.textContent = terisiSekarang + '/' + totalInd + ' indikator';
    }

    window.hitungPreview = function(indId, bobot, val) {
        const preview = document.getElementById(`preview-${indId}`);
        if (!preview) return;
        const v = parseFloat(val);
        if (isNaN(v) || String(val).trim() === '') { preview.innerHTML = ''; return; }
        const k = (v / 100 * bobot).toFixed(2);
        preview.innerHTML = `<span class="preview-formula">${v} / 100 &times; ${bobot}% = <strong>${k}</strong></span>`;
    };

    window.pilihDariDetail = function(indId, bobot, range, el) {
        const input = document.getElementById(`input-nilai-${indId}`);
        if (!input || input.readOnly) return;
        let nilai = null;
        if (range && range.trim() !== '') {
            const clean = range.replace(/\s+/g, '');
            const parts = clean.split('-');
            if (parts.length === 2) {
                const a = parseFloat(parts[0]);
                const b = parseFloat(parts[1]);
                if (!isNaN(a) && !isNaN(b)) nilai = Math.round((a + b) / 2);
            } else if (parts.length === 1) {
                const a = parseFloat(parts[0]);
                if (!isNaN(a)) nilai = a;
            }
        }
        if (nilai === null) {
            const levelEl  = el.querySelector('.level-badge');
            const levelTxt = levelEl ? levelEl.textContent.replace(/[^0-9]/g, '').trim() : '';
            const levelNum = parseInt(levelTxt);
            nilai = !isNaN(levelNum) ? Math.min(levelNum * 20, 100) : 50;
        }
        nilai = Math.max(0, Math.min(100, nilai));
        input.value = nilai;
        hitungPreview(indId, bobot, nilai);
        el.closest('.panduan-grid').querySelectorAll('.detail-level-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
    };

    // =========================================================
    // FIX #2 — Simpan batch: 1 tombol untuk semua indikator di jenis nilai ini
    // =========================================================
    window.simpanBatch = async function(jenisNilaiId, pesertaId) {
        const statusEl = document.getElementById(`batch-status-${jenisNilaiId}`);
        const btn      = document.querySelector('.btn-simpan-batch');
        const jn       = jenisNilaiCache.find(j => j.id == jenisNilaiId);
        if (!jn) return;

        const indList  = jn.indikator_nilai || [];
        const requests = [];

        // Kumpulkan semua indikator yang ada nilai-nya di form
        indList.forEach(ind => {
            const input = document.getElementById(`input-nilai-${ind.id}`);
            if (!input) return;
            const val = input.value.trim();
            if (val === '') return; // skip yang kosong
            const nilaiInput = parseFloat(val);
            if (isNaN(nilaiInput) || nilaiInput < 0 || nilaiInput > 100) return;
            requests.push({ indId: ind.id, bobot: ind.bobot, nilaiInput });
        });

        if (requests.length === 0) {
            tampilStatus(statusEl, 'error', 'Belum ada nilai yang diisi');
            return;
        }

        // Simpan catatan sekaligus
        const catatanEl  = document.getElementById(`catatan-${jenisNilaiId}`);
        const catatanVal = catatanEl ? catatanEl.value : null;

        if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...'; }
        tampilStatus(statusEl, 'loading', '');

        let berhasil = 0;
        let gagal    = 0;

        // Kirim semua request nilai paralel
        const nilaiPromises = requests.map(async ({ indId, bobot, nilaiInput }) => {
            try {
                const res  = await fetch('/nilai/simpan', {
                    method: 'POST',
                    headers: {
                        'Content-Type':     'application/json',
                        'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        peserta_id:         pesertaId,
                        indikator_nilai_id: indId,
                        nilai_input:        nilaiInput,
                    }),
                });
                const data = await res.json();
                if (data.success) {
                    nilaiData[indId] = nilaiInput;
                    // Update preview tiap indikator
                    const preview = document.getElementById(`preview-${indId}`);
                    if (preview) {
                        const konversi = (nilaiInput / 100 * bobot).toFixed(2);
                        preview.innerHTML = `
                            <span class="preview-formula saved">
                                ${nilaiInput} / 100 &times; ${bobot}%
                                = <strong>${konversi}</strong>
                                <i class="fas fa-check-circle text-success ms-1"></i>
                            </span>`;
                    }
                    berhasil++;
                } else {
                    gagal++;
                }
            } catch {
                gagal++;
            }
        });

        // Kirim catatan jika ada
        let catatanPromise = Promise.resolve();
        if (catatanVal !== null) {
            catatanPromise = fetch('/nilai/simpan-catatan', {
                method: 'POST',
                headers: {
                    'Content-Type':     'application/json',
                    'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    peserta_id:     pesertaId,
                    jenis_nilai_id: jenisNilaiId,
                    catatan:        catatanVal,
                }),
            }).then(r => r.json()).then(d => {
                if (d.success) catatanData[jenisNilaiId] = catatanVal;
            }).catch(() => {});
        }

        await Promise.all([...nilaiPromises, catatanPromise]);

        if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-save me-2"></i> Simpan Semua'; }

        if (gagal === 0) {
            tampilStatus(statusEl, 'success', `${berhasil} nilai & catatan tersimpan`);
        } else {
            tampilStatus(statusEl, 'error', `${berhasil} berhasil, ${gagal} gagal`);
        }

        updateTotalNilai();
        refreshTabs();

        // FIX #1 — Update progress bar di tabel index
        updateProgressBarIndex(currentPesertaId);
    };

    function tampilStatus(el, type, msg) {
        const map = {
            loading: `<i class="fas fa-spinner fa-spin text-muted"></i>`,
            success: `<i class="fas fa-check-circle text-success"></i> <small class="text-success">${msg}</small>`,
            error:   `<i class="fas fa-times-circle text-danger"></i> <small class="text-danger">${msg}</small>`,
        };
        el.innerHTML = map[type] || '';
        if (type !== 'loading') setTimeout(() => { el.innerHTML = ''; }, 4000);
    }

    document.querySelectorAll('.alert').forEach(a => {
        setTimeout(() => bootstrap.Alert.getOrCreateInstance(a)?.close(), 5000);
    });
});
</script>

<style>
    .table th { border-bottom:2px solid rgba(40,84,150,.1); font-weight:600; color:#285496; background-color:#f8fafc; padding:.75rem 1rem; }
    .table td { padding:.75rem 1rem; vertical-align:middle; border-bottom:1px solid #e9ecef; }
    .peserta-avatar {
        width:36px; height:36px; border-radius:8px; flex-shrink:0;
        background:linear-gradient(135deg,#285496,#3a6bc7);
        display:flex; align-items:center; justify-content:center;
        color:white; font-size:.9rem; box-shadow:0 4px 8px rgba(40,84,150,.2);
    }
    .kelompok-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; background:linear-gradient(135deg,#285496,#3a6bc7); }
    .link-laporan-icon {
        display:inline-flex; align-items:center; justify-content:center;
        width:18px; height:18px; border-radius:4px;
        background:rgba(40,84,150,.1); color:#285496;
        font-size:.65rem; transition:all .2s; text-decoration:none; flex-shrink:0;
    }
    .link-laporan-icon:hover { background:#285496; color:white; transform:translateY(-1px); box-shadow:0 2px 6px rgba(40,84,150,.3); }
    .btn-action { border-radius:8px; padding:.375rem .75rem; transition:all .2s; border-width:2px; }
    .btn-action:hover { transform:translateY(-2px); box-shadow:0 4px 8px rgba(0,0,0,.1); }
    .pagination-sm .page-link { padding:.375rem .625rem; border-radius:6px; color:#285496; }
    .pagination-sm .page-item.active .page-link { background-color:#285496; border-color:#285496; }
    #filterWilayahWrapper .form-control-sm { border-color: rgba(40,84,150,.35); }
    #filterWilayahWrapper .form-control-sm:focus { border-color: #285496; box-shadow: 0 0 0 .15rem rgba(40,84,150,.15); }

    /* ===== MODAL LAYOUT ===== */
    #modalNilai .modal-content { max-height:90vh; display:flex; flex-direction:column; }
    #modalNilai .modal-body { flex:1 1 auto; overflow:hidden; display:flex; flex-direction:column; padding:0; }
    #nilaiContent { flex:1 1 auto; display:flex; flex-direction:column; overflow:hidden; min-height:0; }
    #nilaiContent > .d-flex { flex:1 1 auto; min-height:0; overflow:hidden; }
    #nilaiNoAccess { flex:1 1 auto; }

    .jenis-nilai-tab { background:white; border:1.5px solid #e9ecef; border-radius:10px; padding:.6rem .75rem; cursor:pointer; transition:all .2s; }
    .jenis-nilai-tab:hover { background:#f0f4ff; border-color:#285496; }
    .jenis-nilai-tab.active { background:linear-gradient(135deg,#285496,#3a6bc7); border-color:#285496; color:white; }
    .jenis-nilai-tab.active small, .jenis-nilai-tab.active .text-muted { color:rgba(255,255,255,.65) !important; }
    .jenis-nilai-tab.active .badge { background:rgba(255,255,255,.25) !important; color:white !important; }
    .jenis-nilai-tab.active .progress { background:rgba(255,255,255,.25); }
    .jenis-nilai-tab.active .progress-bar { background:white !important; }
    .jn-dot { width:8px; height:8px; border-radius:50%; background:#dee2e6; flex-shrink:0; }
    .jn-dot.done { background:#28a745; }

    /* ===== FIX #3 — Indikator card redesign ===== */
    .indikator-card { background:#fff; border:1px solid #e9ecef; border-radius:12px; padding:1rem 1.25rem; transition:box-shadow .2s; }
    .indikator-card:hover { box-shadow:0 4px 16px rgba(40,84,150,.1); }
    .indikator-number {
        width:28px; height:28px; border-radius:6px; flex-shrink:0;
        background:linear-gradient(135deg,#285496,#3a6bc7);
        color:white; display:flex; align-items:center; justify-content:center;
        font-weight:700; font-size:.8rem;
    }

    /* Panduan level grid — compact, tidak bertumpuk */
    .panduan-grid { display:flex; flex-direction:column; gap:.5rem; }
    .detail-level-card {
        border:1.5px solid #e9ecef; border-radius:8px; padding:.6rem .75rem;
        cursor:pointer; transition:all .2s; background:#f8fafc; user-select:none;
    }
    .detail-level-card:hover { border-color:#285496; background:#f0f4ff; }
    .detail-level-card.selected { border-color:#285496; background:rgba(40,84,150,.07); box-shadow:0 2px 8px rgba(40,84,150,.15); }
    .level-badge { display:inline-block; background:#285496; color:white; font-size:.65rem; font-weight:700; padding:.15rem .45rem; border-radius:4px; }
    .range-badge { display:inline-block; background:#f0f4ff; color:#285496; border:1px solid #285496; font-size:.65rem; font-weight:700; padding:.15rem .45rem; border-radius:4px; }
    .level-1 .level-badge { background:#28a745; }
    .level-2 .level-badge { background:#17a2b8; }
    .level-3 .level-badge { background:#ffc107; color:#212529; }
    .level-4 .level-badge { background:#fd7e14; }
    .level-5 .level-badge { background:#dc3545; }

    /* Sticky input nilai di kanan */
    .nilai-sticky-box {
        background:#f8fafc; border:1.5px solid rgba(40,84,150,.2);
        border-radius:10px; padding:1rem; position:sticky; top:1rem;
    }
    .nilai-sticky-label { color:#285496; }
    .nilai-hint { background:rgba(255,193,7,.08); border-radius:6px; padding:.4rem .6rem; }
    .nilai-input { max-width:100px; font-weight:700; font-size:1rem; text-align:center; border-radius:8px; }

    .preview-formula { display:inline-block; background:rgba(40,84,150,.08); border:1px solid rgba(40,84,150,.15); border-radius:6px; padding:.2rem .6rem; font-size:.78rem; color:#285496; font-family:monospace; }
    .preview-formula.saved { background:rgba(40,167,69,.08); border-color:rgba(40,167,69,.2); color:#28a745; }

    /* Catatan & batch bar */
    .catatan-wrapper { background:rgba(245,158,11,.05); border-radius:10px; padding:1rem; border:1px solid rgba(245,158,11,.2); }
    .simpan-batch-bar { background:rgba(40,84,150,.04); border-color:rgba(40,84,150,.2) !important; }
    .simpan-status { font-size:.8rem; }
</style>
@endsection