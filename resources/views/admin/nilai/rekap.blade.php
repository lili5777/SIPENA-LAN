@extends('admin.partials.layout')

@section('title', 'Rekap Nilai - ' . $jenisPelatihan->nama_pelatihan)

@section('content')

    <!-- Page Header -->
    <div class="page-header rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%); padding: 1rem 1.5rem;">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-2 me-2 shadow"
                        style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-chart-bar" style="color: #285496; font-size: 1rem;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1" style="font-size: 1.25rem;">Rekapan Nilai</h1>
                        <p class="text-white-50 mb-0" style="font-size: 0.8rem;">
                            <span class="badge bg-white text-primary fw-semibold me-2">{{ $jenisPelatihan->kode_pelatihan }}</span>
                            {{ $jenisPelatihan->nama_pelatihan }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('nilai.index', ['jenis' => $jenis]) }}" class="btn btn-light btn-sm shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-2 p-md-3">
            <form action="{{ route('nilai.rekap', ['jenis' => $jenis]) }}" method="GET">
                <div class="row g-2">
                    <div class="col-6 col-md-2">
                        <select name="angkatan" class="form-select form-select-sm">
                            <option value="">Angkatan</option>
                            @foreach($angkatanRomawi as $romawi)
                                <option value="{{ $romawi }}" {{ request('angkatan') == $romawi ? 'selected' : '' }}>
                                    Angkatan {{ $romawi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-2">
                        <select name="tahun" class="form-select form-select-sm">
                            <option value="">Tahun</option>
                            @foreach(array_reverse($tahunList) as $tahun)
                                <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-2">
                        <select name="kelompok" class="form-select form-select-sm">
                            <option value="">Kelompok</option>
                            @foreach($kelompokList as $k)
                                <option value="{{ $k }}" {{ request('kelompok') == $k ? 'selected' : '' }}>
                                    Kelompok {{ $k }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-2">
                        <select name="penguji" id="filterPenguji" class="form-select form-select-sm">
                            <option value="">Penguji</option>
                            @foreach($pengujiList as $pg)
                                <option value="{{ $pg->id }}" {{ request('penguji') == $pg->id ? 'selected' : '' }}>
                                    {{ $pg->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-2">
                        <select name="kategori" class="form-select form-select-sm" id="filterKategori">
                            <option value="">Kategori</option>
                            <option value="PNBP" {{ request('kategori') == 'PNBP' ? 'selected' : '' }}>PNBP</option>
                            <option value="FASILITASI" {{ request('kategori') == 'FASILITASI' ? 'selected' : '' }}>FASILITASI</option>
                        </select>
                    </div>

                    <div class="col-6 col-md-2" id="filterWilayahWrapper"
                        style="{{ request('kategori') == 'FASILITASI' ? '' : 'display:none' }}">
                        <input type="text" name="wilayah" id="filterWilayah" class="form-control form-control-sm"
                            list="wilayahDatalistRekap" placeholder="Wilayah" value="{{ request('wilayah') }}">
                        <datalist id="wilayahDatalistRekap">
                            @foreach($wilayahList as $w)
                                <option value="{{ $w }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div class="col-8 col-md-3">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Cari nama/NIP..." value="{{ request('search') }}">
                    </div>

                    <div class="col-4 col-md-2">
                        <div class="d-flex gap-1">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                <i class="fas fa-search me-1"></i>
                            </button>
                            <a href="{{ route('nilai.rekap', ['jenis' => $jenis]) }}"
                                class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Mobile Info -->
    <div class="d-block d-md-none mb-3">
        <div class="alert alert-info py-2 mb-0 small">
            <i class="fas fa-arrows-alt-h me-1"></i> Geser tabel ke kanan untuk melihat semua kolom
        </div>
    </div>

    <!-- Spreadsheet Container -->
    <div class="card border-0 shadow-sm overflow-hidden mb-3">
        <div class="card-header bg-white py-2 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h5 class="card-title mb-0 fw-semibold" style="font-size: 0.95rem;">
                <i class="fas fa-table me-2" style="color:#285496;"></i>
                Rekap Nilai
                <span class="badge bg-primary ms-1">{{ $pesertaPaginated->total() }}</span>
            </h5>
            <small class="text-muted" style="font-size: 0.7rem;">
                {{ $pesertaPaginated->firstItem() }}-{{ $pesertaPaginated->lastItem() }} dari {{ $pesertaPaginated->total() }}
            </small>
        </div>

        <div class="spreadsheet-wrapper">
            <div class="spreadsheet-scroll">
                <table class="spreadsheet-table" id="spreadsheetTable">
                    <thead>
                        <tr class="thead-group">
                            <th class="sticky-col sticky-col-1" rowspan="2">No</th>
                            <th class="sticky-col sticky-col-2" rowspan="2">Peserta</th>
                            <th class="sticky-col sticky-col-3" rowspan="2">NDH</th>
                            <th class="sticky-col sticky-col-4" rowspan="2">Kelompok</th>
                            @foreach($jenisNilaiList as $jn)
                                @php $colspan = $jn->indikatorNilai->count() + 1; @endphp
                                <th colspan="{{ $colspan }}" class="jenis-nilai-header text-center jenis-group-border">
                                    <div class="jn-label">
                                        <span class="jn-name">{{ $jn->name }}</span>
                                        <span class="jn-bobot">{{ $jn->bobot }}%</span>
                                    </div>
                                </th>
                            @endforeach
                            <th class="sticky-col-right" rowspan="2">Total</th>
                        </tr>
                        <tr class="thead-indikator">
                            @foreach($jenisNilaiList as $jn)
                                @foreach($jn->indikatorNilai as $ind)
                                    <th class="indikator-header ind-clickable"
                                        data-indikator-id="{{ $ind->id }}"
                                        data-bobot="{{ $ind->bobot }}"
                                        data-ind-name="{{ $ind->name }}"
                                        data-jenis-name="{{ $jn->name }}">
                                        <div class="ind-inner">
                                            <div class="ind-name">{{ Str::limit($ind->name, 18) }}</div>
                                            <div class="ind-bobot">{{ $ind->bobot }}%</div>
                                        </div>
                                    </th>
                                @endforeach
                                <th class="catatan-col-header jenis-group-border">
                                    <div class="ind-inner">
                                        <i class="fas fa-comment-dots" style="font-size:.7rem; color:#64748b;"></i>
                                        <span style="font-size: 0.6rem; color:#64748b;">Catatan</span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekapData as $index => $row)
                            <tr class="peserta-row {{ $row['is_prioritas_user'] ? 'row-prioritas' : '' }}"
                                data-peserta-id="{{ $row['peserta_id'] }}">

                                <td class="sticky-col sticky-col-1 td-no text-center">
                                    {{ $pesertaPaginated->firstItem() + $index }}
                                </td>

                                <td class="sticky-col sticky-col-2 td-nama">
                                    <div class="d-flex align-items-center gap-1">
                                        @if($row['is_prioritas_user'])
                                            <i class="fas fa-star text-primary" style="font-size: 0.7rem;"></i>
                                        @endif
                                        <div class="peserta-avatar-sm">
                                            {{ strtoupper(substr($row['nama'], 0, 1)) }}
                                        </div>
                                        <div class="min-width-0">
                                            <div class="fw-semibold small peserta-nama-text">{{ $row['nama'] }}</div>
                                            <small class="text-muted" style="font-size: 0.6rem;">{{ $row['nip'] ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>

                                <td class="sticky-col sticky-col-3 td-ndh text-center">
                                    @if($row['ndh'])
                                        <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">{{ $row['ndh'] }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td class="sticky-col sticky-col-4 td-kelompok">
                                    @if($row['kelompok'])
                                        <div class="small fw-semibold">{{ $row['kelompok'] }}</div>
                                        @if(!empty($row['angkatan']))
                                            <small class="text-muted" style="font-size:.65rem;">{{ $row['angkatan'] }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                @foreach($jenisNilaiList as $jn)
                                    @php
                                        $nilaiJn         = $row['nilai_per_jenis'][$jn->id] ?? null;
                                        $detailIndikator = collect($nilaiJn['detail_indikator'] ?? []);
                                        $catatan         = $row['catatan'][$jn->id] ?? null;
                                        $hasCatatan      = !empty($catatan);
                                    @endphp

                                    @foreach($jn->indikatorNilai as $ind)
                                        @php
                                            $detailInd  = $detailIndikator->firstWhere('nama_indikator', $ind->name);
                                            $nilaiInput = $detailInd['nilai_input'] ?? null;
                                        @endphp
                                        <td class="nilai-cell text-center {{ $nilaiInput !== null ? 'status-saved' : '' }}">
                                            <div class="cell-display">
                                                @if($nilaiInput !== null)
                                                    <span class="cell-value">{{ $nilaiInput }}</span>
                                                @else
                                                    <span class="cell-empty">—</span>
                                                @endif
                                            </div>
                                        </td>
                                    @endforeach

                                    {{-- Kolom catatan: hanya bisa diklik jika ada catatan --}}
                                    <td class="catatan-cell jenis-group-border text-center {{ $hasCatatan ? 'catatan-has-value catatan-clickable' : 'catatan-no-value' }}"
                                        @if($hasCatatan)
                                            data-catatan="{{ e($catatan) }}"
                                            data-jenis-nama="{{ $jn->name }}"
                                            title="Klik untuk lihat catatan"
                                        @endif>
                                        @if($hasCatatan)
                                            <button type="button" class="catatan-view-btn">
                                                <i class="fas fa-comment-dots"></i>
                                            </button>
                                        @else
                                            <i class="fas fa-comment text-muted" style="font-size: 0.85rem; opacity:.35;"></i>
                                        @endif
                                    </td>
                                @endforeach

                                <td class="sticky-col-right td-total text-center">
                                    <span class="fw-bold text-primary">{{ number_format($row['total_nilai'], 1) }}</span>
                                    <div class="total-bar">
                                        <div class="total-bar-fill" style="width:{{ min($row['total_nilai'], 100) }}%"></div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                @php
                                    $colspan = 4 + $jenisNilaiList->sum(fn($jn) => $jn->indikatorNilai->count() + 1) + 1;
                                @endphp
                                <td colspan="{{ $colspan }}" class="text-center py-5">
                                    <i class="fas fa-chart-bar fa-3x mb-2 d-block text-muted"></i>
                                    <p class="text-muted mb-0">Belum ada data nilai</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($pesertaPaginated->hasPages())
            <div class="card-footer bg-white py-2 border-top">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
                    <small class="text-muted text-center text-md-start">
                        Menampilkan {{ $pesertaPaginated->firstItem() }}–{{ $pesertaPaginated->lastItem() }}
                        dari {{ $pesertaPaginated->total() }} peserta
                    </small>
                    <nav>
                        <ul class="pagination pagination-sm mb-0 justify-content-center">
                            @if($pesertaPaginated->onFirstPage())
                                <li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $pesertaPaginated->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a></li>
                            @endif

                            @php
                                $start = max($pesertaPaginated->currentPage() - 1, 1);
                                $end   = min($start + 2, $pesertaPaginated->lastPage());
                                $start = max($end - 2, 1);
                            @endphp

                            @if($start > 1)
                                <li class="page-item"><a class="page-link" href="{{ $pesertaPaginated->url(1) }}">1</a></li>
                                @if($start > 2)<li class="page-item disabled"><span class="page-link">...</span></li>@endif
                            @endif

                            @for($i = $start; $i <= $end; $i++)
                                <li class="page-item {{ $i == $pesertaPaginated->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $pesertaPaginated->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if($end < $pesertaPaginated->lastPage())
                                @if($end < $pesertaPaginated->lastPage() - 1)<li class="page-item disabled"><span class="page-link">...</span></li>@endif
                                <li class="page-item"><a class="page-link" href="{{ $pesertaPaginated->url($pesertaPaginated->lastPage()) }}">{{ $pesertaPaginated->lastPage() }}</a></li>
                            @endif

                            @if($pesertaPaginated->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $pesertaPaginated->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        @endif
    </div>

    {{-- ── CATATAN POPOVER (view-only) ── --}}
    <div id="catatanViewPopover" class="catatan-view-popover" style="display:none;" role="dialog">
        <div class="cvp-arrow"></div>
        <div class="cvp-header">
            <div class="cvp-title">
                <i class="fas fa-comment-dots me-2" style="font-size:.8rem;"></i>
                <span id="cvpTitle">Catatan</span>
            </div>
            <button type="button" class="cvp-close" id="btnCvpClose" aria-label="Tutup">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="cvp-body">
            <p id="cvpText" class="cvp-text mb-0"></p>
        </div>
    </div>

    {{-- ── MODAL DETAIL INDIKATOR ── --}}
    <div class="modal fade" id="modalDetailIndikator" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header-custom">
                    <div class="d-flex align-items-center gap-2 w-100">
                        <div class="modal-header-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="modal-title text-white fw-bold mb-0" id="modalDetailLabel">—</h5>
                            <div class="d-flex flex-wrap gap-1 mt-1" id="modalBadgeArea"></div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                </div>
                <div class="modal-body p-3" id="modalRubrikBody"></div>
                <div class="modal-footer border-0 pt-0 px-3 pb-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Tom Select Penguji ────────────────────────────────────────────
    const elPenguji = document.getElementById('filterPenguji');
    if (elPenguji) {
        new TomSelect(elPenguji, {
            placeholder: 'Cari penguji...',
            allowEmptyOption: true,
            maxOptions: 10,
            searchField: ['text'],
        });
    }

    // ── Toggle wilayah ────────────────────────────────────────────────
    const filterKategori       = document.getElementById('filterKategori');
    const filterWilayahWrapper = document.getElementById('filterWilayahWrapper');
    const filterWilayah        = document.getElementById('filterWilayah');
    if (filterKategori) {
        filterKategori.addEventListener('change', function () {
            if (this.value === 'FASILITASI') {
                filterWilayahWrapper.style.display = '';
            } else {
                filterWilayahWrapper.style.display = 'none';
                if (filterWilayah) filterWilayah.value = '';
            }
        });
    }

    // ── MODAL DETAIL INDIKATOR ────────────────────────────────────────
    const bsModal = new bootstrap.Modal(document.getElementById('modalDetailIndikator'));

    const LEVEL_COLORS = [
        { bg:'#fff3e0', border:'#ff9800', text:'#7a3b00', badge:'#ff9800' },
        { bg:'#e3f2fd', border:'#2196f3', text:'#0d47a1', badge:'#2196f3' },
        { bg:'#e8f5e9', border:'#4caf50', text:'#1b5e20', badge:'#4caf50' },
        { bg:'#f3e5f5', border:'#9c27b0', text:'#4a148c', badge:'#9c27b0' },
    ];

    @php
        $indikatorDataArray = [];
        foreach ($jenisNilaiList as $jn) {
            foreach ($jn->indikatorNilai as $ind) {
                $detailArray = [];
                foreach ($ind->detailIndikator as $d) {
                    $detailArray[] = [
                        'level' => $d->level,
                        'uraian' => $d->uraian,
                        'range' => $d->range,
                    ];
                }
                $indikatorDataArray[$ind->id] = [
                    'name'      => $ind->name,
                    'bobot'     => $ind->bobot,
                    'jenis_name'=> $jn->name,
                    'roles'     => $ind->roles->pluck('name')->map(fn($r) => ucfirst($r))->implode(', '),
                    'detail'    => $detailArray,
                ];
            }
        }
    @endphp
    const indikatorData = @json($indikatorDataArray);

    document.querySelectorAll('.ind-clickable').forEach(th => {
        th.addEventListener('click', function () {
            const indId = this.dataset.indikatorId;
            const data  = indikatorData[indId];
            if (!data) return;

            document.getElementById('modalDetailLabel').textContent = data.name;
            document.getElementById('modalBadgeArea').innerHTML = `
                <span class="badge bg-white bg-opacity-25 text-white">Bobot ${data.bobot}%</span>
                <span class="badge bg-white bg-opacity-25 text-white">${data.roles || 'Admin'}</span>`;

            const modalBody = document.getElementById('modalRubrikBody');
            modalBody.innerHTML = `
                <div class="row g-2 mb-3">
                    <div class="col-4"><div class="minfo-card"><div class="minfo-label">Bobot</div><div class="minfo-value">${data.bobot}%</div></div></div>
                    <div class="col-4"><div class="minfo-card"><div class="minfo-label">Jenis</div><div class="minfo-value">${data.jenis_name}</div></div></div>
                    <div class="col-4"><div class="minfo-card"><div class="minfo-label">Penilai</div><div class="minfo-value small">${data.roles || 'Admin'}</div></div></div>
                </div>
                <div class="rubrik-section">
                    <div class="rubrik-title"><i class="fas fa-list-ul me-1"></i> Rubrik Penilaian</div>
                    <div id="rubrikContent"></div>
                </div>`;

            const rubrikContent = document.getElementById('rubrikContent');
            if (!data.detail || data.detail.length === 0) {
                rubrikContent.innerHTML = '<div class="text-center py-4 text-muted">Belum ada rubrik penilaian</div>';
            } else {
                rubrikContent.innerHTML = data.detail.map((d, i) => {
                    const c = LEVEL_COLORS[i % LEVEL_COLORS.length];
                    return `
                        <div class="rubrik-item" style="border-left-color: ${c.badge};">
                            <div class="rubrik-level">
                                <span class="level-badge" style="background: ${c.badge};">Level ${d.level}</span>
                            </div>
                            <div class="rubrik-uraian">${d.uraian || '-'}</div>
                            <div class="rubrik-range" style="background:${c.bg};color:${c.text};">${d.range || '-'}</div>
                        </div>`;
                }).join('');
            }

            bsModal.show();
        });
    });

    // ════════════════════════════════════════════════════════
    // CATATAN VIEW POPOVER (read-only)
    // ════════════════════════════════════════════════════════
    const cvpPopover  = document.getElementById('catatanViewPopover');
    const cvpTitle    = document.getElementById('cvpTitle');
    const cvpText     = document.getElementById('cvpText');
    const btnCvpClose = document.getElementById('btnCvpClose');

    let activeCvpCell = null;

    function openCvpPopover(td) {
        activeCvpCell = td;

        cvpTitle.textContent = td.dataset.jenisNama || 'Catatan';
        cvpText.textContent  = td.dataset.catatan   || '';

        // Tandai cell aktif
        document.querySelectorAll('.catatan-cell.cvp-active').forEach(c => c.classList.remove('cvp-active'));
        td.classList.add('cvp-active');

        cvpPopover.style.display = 'block';
        positionCvpPopover(td);
    }

    function positionCvpPopover(td) {
        const rect       = td.getBoundingClientRect();
        const scrollTop  = window.pageYOffset  || document.documentElement.scrollTop;
        const scrollLeft = window.pageXOffset  || document.documentElement.scrollLeft;
        const popW       = cvpPopover.offsetWidth  || 300;
        const popH       = cvpPopover.offsetHeight || 160;
        const vw         = window.innerWidth;
        const vh         = window.innerHeight;

        let top  = rect.bottom + scrollTop  + 8;
        let left = rect.left   + scrollLeft + (rect.width / 2) - (popW / 2);

        if (left + popW + 16 > scrollLeft + vw) left = scrollLeft + vw - popW - 16;
        if (left < scrollLeft + 8)              left = scrollLeft + 8;

        const spaceBelow = vh - rect.bottom;
        if (spaceBelow < popH + 20 && rect.top > popH + 20) {
            top = rect.top + scrollTop - popH - 8;
            cvpPopover.classList.add('cvp-above');
            cvpPopover.classList.remove('cvp-below');
        } else {
            cvpPopover.classList.add('cvp-below');
            cvpPopover.classList.remove('cvp-above');
        }

        cvpPopover.style.top  = top  + 'px';
        cvpPopover.style.left = left + 'px';

        const arrow    = cvpPopover.querySelector('.cvp-arrow');
        const arrowLeft = rect.left + scrollLeft + (rect.width / 2) - left;
        if (arrow) arrow.style.left = Math.max(16, Math.min(arrowLeft, popW - 16)) + 'px';
    }

    function closeCvpPopover() {
        cvpPopover.style.display = 'none';
        if (activeCvpCell) {
            activeCvpCell.classList.remove('cvp-active');
            activeCvpCell = null;
        }
    }

    // Klik cell catatan yang ada isinya
    document.querySelectorAll('.catatan-clickable').forEach(td => {
        td.addEventListener('click', function (e) {
            e.stopPropagation();
            if (activeCvpCell === this) {
                closeCvpPopover();
                return;
            }
            openCvpPopover(this);
        });
    });

    // Tutup tombol
    btnCvpClose.addEventListener('click', closeCvpPopover);

    // Klik luar → tutup
    document.addEventListener('click', function (e) {
        if (!cvpPopover || cvpPopover.style.display === 'none') return;
        if (cvpPopover.contains(e.target))           return;
        if (e.target.closest('.catatan-clickable'))  return;
        closeCvpPopover();
    });

    // Escape → tutup
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeCvpPopover();
    });

    // Re-posisi saat scroll
    let reposTimeout;
    document.querySelector('.spreadsheet-scroll')?.addEventListener('scroll', function () {
        if (cvpPopover.style.display === 'none' || !activeCvpCell) return;
        clearTimeout(reposTimeout);
        reposTimeout = setTimeout(() => positionCvpPopover(activeCvpCell), 50);
    });
    window.addEventListener('resize', function () {
        if (cvpPopover.style.display === 'none' || !activeCvpCell) return;
        clearTimeout(reposTimeout);
        reposTimeout = setTimeout(() => positionCvpPopover(activeCvpCell), 50);
    });

});
</script>

<style>
/* ════════════════════════════════════════════════════
   SPREADSHEET LAYOUT
════════════════════════════════════════════════════ */
.spreadsheet-wrapper { overflow: hidden; position: relative; }
.spreadsheet-scroll  {
    overflow-x: auto;
    overflow-y: auto;
    max-height: calc(100vh - 320px);
    min-height: 300px;
    -webkit-overflow-scrolling: touch;
}
.spreadsheet-table {
    border-collapse: separate;
    border-spacing: 0;
    width: max-content;
    min-width: 100%;
    font-size: 0.75rem;
}

/* Sticky Left Columns */
.sticky-col        { position: sticky; background: white; z-index: 3; }
.sticky-col-1      { left: 0;    min-width: 45px;  width: 45px;  border-right: 1px solid #dee2e6; }
.sticky-col-2      { left: 45px; min-width: 180px; width: 180px; border-right: 1px solid #dee2e6; }
.sticky-col-3      { left: 225px;min-width: 55px;  width: 55px;  border-right: 1px solid #dee2e6; }
.sticky-col-4      { left: 280px;min-width: 120px; width: 120px; border-right: 2px solid #285496; }

/* Sticky Right Columns */
.sticky-col-right  {
    position: sticky; right: 0;
    background: white; z-index: 3;
    border-left: 2px solid #285496;
    min-width: 80px; width: 80px;
    text-align: center;
}

/* Header */
.spreadsheet-table thead tr th {
    position: sticky; top: 0; z-index: 4;
    background: #f8fafc;
    padding: 0.4rem 0.3rem;
    font-weight: 600; color: #285496;
    border-bottom: 2px solid rgba(40,84,150,.15);
    white-space: nowrap;
}
.spreadsheet-table thead tr.thead-indikator th {
    top: 42px; z-index: 4;
    background: #f0f4ff;
    border-bottom: 1px solid #dee2e6;
    font-weight: 500;
    padding: 0.3rem 0.2rem;
}

/* Pastikan sticky corner z-index lebih tinggi */
.spreadsheet-table thead tr th.sticky-col   { z-index: 6; }
.spreadsheet-table thead tr th.sticky-col-right { z-index: 6; }

/* Jenis Header */
.jenis-nilai-header {
    border-bottom: 2px solid rgba(40,84,150,.15) !important;
    padding: 0.3rem 0.2rem !important;
    background: #f8fafc;
}
.jenis-group-border { border-right: 2px solid #285496 !important; }
.jn-label  { display: flex; flex-direction: column; align-items: center; gap: 2px; }
.jn-name   { font-weight: 700; font-size: 0.7rem; color: #285496; }
.jn-bobot  { font-size: 0.6rem; background: rgba(40,84,150,.1); color: #285496; border-radius: 3px; padding: .05rem .25rem; }

/* Indikator Header */
.indikator-header  { min-width: 70px; max-width: 85px; cursor: pointer; }
.ind-clickable:hover { background: #e4ecff !important; }
.ind-inner  { display: flex; flex-direction: column; align-items: center; gap: 2px; }
.ind-name   { font-size: 0.65rem; color: #333; text-align: center; line-height: 1.2; max-width: 75px; word-break: break-word; }
.ind-bobot  { font-size: 0.6rem; color: #888; }

/* Catatan Header */
.catatan-col-header {
    min-width: 50px; width: 50px;
    text-align: center;
    background: #f8faff !important;
    cursor: default !important;
}

/* ── Row ── */
.peserta-row td {
    padding: 0.3rem 0.35rem;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
    background: white;
}
.peserta-row:hover td                          { background: #f8faff; }
.peserta-row:hover .sticky-col,
.peserta-row:hover .sticky-col-right,
.peserta-row:hover .sticky-col-right-2        { background: #f8faff !important; }
.peserta-row.row-prioritas                    { border-left: 3px solid #285496; }
.peserta-row td.jenis-group-border,
.peserta-row:hover td.jenis-group-border      { border-right: 2px solid #285496 !important; }

/* ── Cell Nilai ── */
.nilai-cell {
    min-width: 60px; max-width: 80px;
    text-align: center;
    padding: .3rem .25rem !important;
}
.nilai-cell.status-saved { background: #edf7ee !important; }

.cell-display {
    display: flex; align-items: center; justify-content: center;
    min-height: 26px;
}
.cell-value  { font-weight: 700; font-size: .85rem; color: #1a1a2e; line-height: 1; }
.nilai-cell.status-saved .cell-value { color: #1e5c22; }
.cell-empty  { color: #ccc; font-size: .8rem; }

/* ── Peserta Info ── */
.peserta-avatar-sm {
    width: 28px; height: 28px; border-radius: 6px;
    background: linear-gradient(135deg, #285496, #3a6bc7);
    color: white; font-size: 0.7rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.peserta-nama-text { max-width: 120px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.min-width-0 { min-width: 0; }

/* ── Total ── */
.td-total        { background: #f8fafc; }
.total-bar       { height: 3px; border-radius: 2px; background: #e9ecef; margin-top: 3px; overflow: hidden; }
.total-bar-fill  { height: 100%; background: linear-gradient(90deg, #285496, #3a6bc7); }

/* ── Catatan Cell ── */
.catatan-cell {
    min-width: 50px; width: 50px;
    text-align: center;
    padding: .3rem .2rem !important;
    position: relative;
}
.catatan-no-value   { cursor: default; }
.catatan-has-value  { background: #eff8ff !important; }
.catatan-clickable  { cursor: pointer; }
.catatan-clickable:hover { background: #dbeafe !important; }
.catatan-cell.cvp-active {
    background: #dbeafe !important;
    box-shadow: inset 0 0 0 2px #285496;
}

.catatan-view-btn {
    background: none; border: none;
    color: #285496; cursor: pointer;
    padding: 3px 5px; border-radius: 5px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: .9rem;
    transition: transform .12s, color .12s;
}
.catatan-view-btn:hover { transform: scale(1.2); color: #1d3d70; }

/* ── Pagination ── */
.pagination-sm .page-link { padding: .25rem .5rem; font-size: .75rem; }
.pagination-sm .page-item.active .page-link { background-color: #285496; border-color: #285496; }

/* ── Scrollbar ── */
.spreadsheet-scroll::-webkit-scrollbar       { height: 6px; width: 6px; }
.spreadsheet-scroll::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 3px; }
.spreadsheet-scroll::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 3px; }
.spreadsheet-scroll::-webkit-scrollbar-thumb:hover { background: #285496; }

/* ════════════════════════════════════════════════════
   CATATAN VIEW POPOVER (read-only)
════════════════════════════════════════════════════ */
.catatan-view-popover {
    position: absolute;
    z-index: 1080;
    width: 300px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(40,84,150,.18), 0 2px 8px rgba(0,0,0,.08);
    overflow: hidden;
    animation: cvpFadeIn .15s ease;
}
@keyframes cvpFadeIn {
    from { opacity:0; transform:translateY(-6px); }
    to   { opacity:1; transform:translateY(0); }
}
.catatan-view-popover.cvp-above { animation: cvpFadeInUp .15s ease; }
@keyframes cvpFadeInUp {
    from { opacity:0; transform:translateY(6px); }
    to   { opacity:1; transform:translateY(0); }
}

/* Panah */
.cvp-arrow {
    position: absolute;
    top: -7px; left: 50%;
    transform: translateX(-50%);
    width: 14px; height: 7px;
    overflow: hidden;
}
.cvp-arrow::after {
    content: '';
    position: absolute;
    top: 1px; left: 50%;
    transform: translateX(-50%) rotate(45deg);
    width: 10px; height: 10px;
    background: #285496;
    border: 1px solid #285496;
}
.catatan-view-popover.cvp-above .cvp-arrow {
    top: auto; bottom: -7px;
    transform: translateX(-50%) rotate(180deg);
}

/* Header */
.cvp-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: .55rem .85rem .5rem;
    background: linear-gradient(135deg, #285496, #3a6bc7);
    gap: 8px;
}
.cvp-title {
    font-size: .8rem; font-weight: 700; color: #fff;
    display: flex; align-items: center;
    flex: 1; min-width: 0;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.cvp-close {
    background: rgba(255,255,255,.2); border: none; border-radius: 6px;
    color: #fff; width: 22px; height: 22px;
    display: flex; align-items: center; justify-content: center;
    font-size: .65rem; cursor: pointer; flex-shrink: 0;
    transition: background .12s;
}
.cvp-close:hover { background: rgba(255,255,255,.35); }

/* Body */
.cvp-body { padding: .75rem .85rem; }
.cvp-text {
    font-size: .82rem; color: #1e293b;
    line-height: 1.6;
    white-space: pre-wrap;
    word-break: break-word;
    max-height: 200px;
    overflow-y: auto;
}

/* ════════════════════════════════════════════════════
   MODAL DETAIL INDIKATOR
════════════════════════════════════════════════════ */
.modal-header-custom {
    background: linear-gradient(135deg, #285496, #3a6bc7);
    padding: .75rem 1rem;
}
.modal-header-icon {
    width: 32px; height: 32px; border-radius: 50%;
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
}
.modal-header-icon i { color: #fff; font-size: .8rem; }

.minfo-card {
    background: #f8fafc; border: 1px solid #e4ecf7;
    border-radius: 8px; padding: .4rem .5rem; text-align: center;
}
.minfo-label { font-size: .6rem; font-weight: 700; color: #999; margin-bottom: 2px; }
.minfo-value { font-size: .8rem; font-weight: 700; color: #285496; }

.rubrik-section { margin-top: .5rem; }
.rubrik-title   {
    font-size: .75rem; font-weight: 700; color: #285496;
    margin-bottom: .5rem; padding-bottom: .25rem;
    border-bottom: 2px solid #e4ecf7;
}
.rubrik-item {
    display: flex; align-items: center; gap: .5rem;
    padding: .5rem; margin-bottom: .5rem;
    background: #fafcff; border-left: 3px solid;
    border-radius: 6px;
}
.rubrik-level  { flex-shrink: 0; width: 70px; }
.level-badge   {
    display: inline-block; padding: .2rem .5rem;
    border-radius: 15px; color: white;
    font-size: .65rem; font-weight: 700;
}
.rubrik-uraian { flex: 1; font-size: .7rem; color: #333; }
.rubrik-range  {
    flex-shrink: 0; padding: .2rem .5rem;
    border-radius: 4px; font-size: .65rem; font-weight: 600;
}

/* ── Responsive ── */
@media (max-width: 768px) {
    .spreadsheet-scroll { max-height: calc(100vh - 280px); }
    .spreadsheet-table  { font-size: .7rem; }
    .ind-name           { font-size: .6rem; }
    .cell-value         { font-size: .75rem !important; }
}
@media (max-width: 576px) {
    .sticky-col-2       { min-width: 150px; width: 150px; left: 45px; }
    .sticky-col-3       { left: 195px; }
    .sticky-col-4       { left: 250px; min-width: 100px; width: 100px; }
    .peserta-nama-text  { max-width: 90px; }
}
</style>
@endsection