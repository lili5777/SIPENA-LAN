@extends('admin.partials.layout')

@section('title', 'Penilaian Peserta - ' . $jenisPelatihan->nama_pelatihan)

@section('content')

<div class="page-header rounded-3 mb-4"
    style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%); padding: 2rem;">
    <div class="row align-items-center">
        <div class="col">
            <div class="d-flex align-items-center">
                <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                    <i class="fas fa-table fa-lg" style="color: #285496;"></i>
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
        <div class="col-auto d-flex gap-2">
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
        <small>Menampilkan peserta dari kelompok Anda. Gunakan filter <strong>Kelompok</strong> untuk melihat kelompok lain (view only).</small>
    </div>
@elseif($roleName === 'pic')
    <div class="alert alert-info d-flex align-items-center shadow-sm mb-4 py-2" role="alert">
        <i class="fas fa-info-circle me-2 flex-shrink-0"></i>
        <small>Menampilkan peserta dari angkatan yang Anda tangani.</small>
    </div>
@endif

<!-- Legend & Info Bar -->
<div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
    <div class="d-flex align-items-center gap-2">
        <div class="legend-swatch swatch-saved">85</div>
        <small class="text-muted">Tersimpan</small>
    </div>
    <div class="d-flex align-items-center gap-2">
        <div class="legend-swatch swatch-pending">72</div>
        <small class="text-muted">Belum disimpan</small>
    </div>
    <div class="d-flex align-items-center gap-2">
        <div class="legend-swatch swatch-empty">—</div>
        <small class="text-muted">Kosong</small>
    </div>
    {{-- [BARU] legend catatan --}}
    <div class="d-flex align-items-center gap-2">
        <span class="catatan-icon-legend catatan-icon-filled">
            <i class="fas fa-comment-dots"></i>
        </span>
        <small class="text-muted">Ada catatan</small>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="catatan-icon-legend catatan-icon-empty">
            <i class="fas fa-comment"></i>
        </span>
        <small class="text-muted">Belum ada catatan</small>
    </div>
    <div class="d-flex align-items-center gap-2">
        <small class="text-muted">
            <i class="fas fa-info-circle me-1 text-primary"></i>
            Klik nama indikator di header untuk melihat rubrik penilaian
        </small>
    </div>
    <div class="ms-auto d-flex align-items-center gap-2">
        <span class="text-muted small"><i class="fas fa-keyboard me-1"></i>Enter / Tab untuk pindah cell</span>
        <span class="badge bg-primary" id="badge-unsaved" style="display:none !important;">
            <i class="fas fa-circle me-1" style="font-size:.5rem;"></i>
            <span id="count-unsaved">0</span> perubahan belum disimpan
        </span>
    </div>
</div>

<!-- Filter & Search -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3">
        <form action="{{ route('nilai.index', ['jenis' => $jenis]) }}" method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-2 col-sm-6">
                    <label class="form-label small text-muted mb-1"><i class="fas fa-layer-group me-1"></i> Angkatan</label>
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
                    <label class="form-label small text-muted mb-1"><i class="fas fa-calendar-alt me-1"></i> Tahun</label>
                    <select name="tahun" class="form-select form-select-sm">
                        <option value="">Semua Tahun</option>
                        @foreach(array_reverse($tahunList) as $tahun)
                            <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label small text-muted mb-1"><i class="fas fa-users me-1"></i> Kelompok</label>
                    <select name="kelompok" class="form-select form-select-sm">
                        <option value="">Semua Kelompok</option>
                        @foreach($kelompokList as $k)
                            <option value="{{ $k }}" {{ request('kelompok') == $k ? 'selected' : '' }}>Kelompok {{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label small text-muted mb-1"><i class="fas fa-tag me-1"></i> Kategori</label>
                    <select name="kategori" class="form-select form-select-sm" id="filterKategori">
                        <option value="">Semua Kategori</option>
                        <option value="PNBP"       {{ request('kategori') == 'PNBP'       ? 'selected' : '' }}>PNBP</option>
                        <option value="FASILITASI" {{ request('kategori') == 'FASILITASI' ? 'selected' : '' }}>FASILITASI</option>
                    </select>
                </div>
                <div class="col-md-2 col-sm-6" id="filterWilayahWrapper"
                    style="{{ request('kategori') == 'FASILITASI' ? '' : 'display:none' }}">
                    <label class="form-label small text-muted mb-1"><i class="fas fa-map-marker-alt me-1"></i> Wilayah</label>
                    <input type="text" name="wilayah" id="filterWilayah" class="form-control form-control-sm"
                        list="wilayahDatalist" placeholder="Ketik wilayah..." value="{{ request('wilayah') }}">
                    <datalist id="wilayahDatalist">
                        @foreach($wilayahList as $w)
                            <option value="{{ $w }}">
                        @endforeach
                    </datalist>
                </div>
                <div class="col-md-2 col-sm-8">
                    <label class="form-label small text-muted mb-1"><i class="fas fa-search me-1"></i> Cari</label>
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
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </div>

            @php
                $activeFilters = array_filter([
                    'Angkatan' => request('angkatan') ? 'Angkatan ' . request('angkatan') : null,
                    'Tahun'    => request('tahun'),
                    'Kelompok' => request('kelompok') ? 'Kelompok ' . request('kelompok') : null,
                    'Kategori' => request('kategori'),
                    'Wilayah'  => request('wilayah'),
                    'Cari'     => request('search'),
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

{{-- ── SECTION LAPORAN KELOMPOK ──────────────────────────────────────── --}}
@if(isset($kelompokLaporan) && $kelompokLaporan->isNotEmpty())
<div class="card border-0 shadow-sm mb-3" id="sectionLaporan">
    <div class="card-body p-3">
        <div class="d-flex align-items-center gap-2 mb-3">
            <div style="width:4px;height:18px;background:#285496;border-radius:2px;flex-shrink:0;"></div>
            <span class="fw-semibold small" style="color:#285496;">
                <i class="fas fa-folder-open me-1"></i> Laporan Kelompok
            </span>
            <span class="text-muted small">
                —
                @if(request('kelompok') && request('angkatan') && request('tahun'))
                    Kelompok {{ request('kelompok') }}, Angkatan {{ request('angkatan') }} / {{ request('tahun') }}
                @elseif(request('kelompok') && request('angkatan'))
                    Kelompok {{ request('kelompok') }}, Angkatan {{ request('angkatan') }}
                @elseif(request('kelompok') && request('tahun'))
                    Kelompok {{ request('kelompok') }}, Tahun {{ request('tahun') }}
                @elseif(request('kelompok'))
                    Kelompok {{ request('kelompok') }}
                @elseif(request('angkatan') && request('tahun'))
                    Angkatan {{ request('angkatan') }} / {{ request('tahun') }}
                @elseif(request('angkatan'))
                    Angkatan {{ request('angkatan') }}
                @elseif(request('tahun'))
                    Tahun {{ request('tahun') }}
                @endif
            </span>
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 fw-normal ms-1" style="font-size:.72rem;">
                {{ $kelompokLaporan->count() }} kelompok
            </span>
        </div>

        <div class="laporan-grid" id="laporanGrid">
            @foreach($kelompokLaporan as $klp)
                @php
                    $hasLink       = !empty($klp->link_laporan);
                    $angkatanLabel = $klp->angkatan->nama_angkatan ?? '-';
                    $tahunLabel    = $klp->angkatan->tahun ?? '';
                @endphp
                <div class="laporan-card {{ $hasLink ? 'laporan-card-has-link' : 'laporan-card-no-link' }}">
                    <div class="laporan-card-icon {{ $hasLink ? '' : 'laporan-card-icon-empty' }}">
                        <i class="fas fa-file-alt" style="font-size:.8rem;"></i>
                    </div>
                    <div class="laporan-card-info">
                        <div class="laporan-card-name" title="{{ $klp->nama_kelompok }}">{{ $klp->nama_kelompok }}</div>
                        <div class="laporan-card-meta">{{ $angkatanLabel }}{{ $tahunLabel ? ' · ' . $tahunLabel : '' }}</div>
                    </div>
                    @if($hasLink)
                        <a href="{{ $klp->link_laporan }}" target="_blank" rel="noopener noreferrer"
                           class="laporan-btn" title="Buka laporan {{ $klp->nama_kelompok }}">
                            Buka <i class="fas fa-external-link-alt" style="font-size:.6rem;"></i>
                        </a>
                    @else
                        <span class="laporan-btn-empty">Belum tersedia</span>
                    @endif
                </div>
            @endforeach
        </div>

        @if($kelompokLaporan->count() > 6)
            <button class="laporan-toggle-btn mt-2" id="laporanToggle"
                onclick="toggleLaporan({{ $kelompokLaporan->count() }})"
                data-count="{{ $kelompokLaporan->count() }}" data-open="0">
                <i class="fas fa-chevron-down me-1 laporan-toggle-icon"></i>
                Lihat semua ({{ $kelompokLaporan->count() }})
            </button>
        @endif
    </div>
</div>
@endif
{{-- ── END SECTION LAPORAN KELOMPOK ──────────────────────────────────── --}}

<!-- Spreadsheet Container -->
<div class="card border-0 shadow-lg overflow-hidden mb-3">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0 fw-semibold">
            <i class="fas fa-table me-2" style="color:#285496;"></i>
            Spreadsheet Penilaian
            <span class="badge bg-primary ms-2">{{ $peserta->total() }}</span>
        </h5>
        <div class="d-flex align-items-center gap-2">
            <small class="text-muted">
                Menampilkan {{ $peserta->count() }} dari {{ $peserta->total() }} peserta
            </small>
            <button type="button" class="btn btn-success btn-sm px-3" id="btnSimpanSemua" style="display:none;">
                <i class="fas fa-save me-1"></i> Simpan Semua (<span id="countSimpan">0</span>)
            </button>
        </div>
    </div>

    <div class="spreadsheet-wrapper">
        <div class="spreadsheet-scroll">
            <table class="spreadsheet-table" id="spreadsheetTable">
                <thead>
                    <!-- Baris 1: Group header jenis nilai -->
                    <tr class="thead-group">
                        <th class="sticky-col sticky-col-1 sticky-header" rowspan="2">No</th>
                        <th class="sticky-col sticky-col-2 sticky-header" rowspan="2">Nama Peserta</th>
                        <th class="sticky-col sticky-col-3 sticky-header" rowspan="2">NDH</th>
                        <th class="sticky-col sticky-col-4 sticky-header" rowspan="2">Kelompok</th>
                        @foreach($jenisNilaiList as $jnLoop => $jn)
                            {{-- +1 untuk kolom catatan --}}
                            <th colspan="{{ $jn->indikatorNilai->count() + 1 }}"
                                class="jenis-nilai-header text-center jenis-group-border"
                                style="--jn-color: {{ $jnColors[$jnLoop % count($jnColors)] }}">
                                <div class="jn-label">
                                    <span class="jn-name">{{ $jn->name }}</span>
                                    <span class="jn-bobot">{{ $jn->bobot }}%</span>
                                </div>
                            </th>
                        @endforeach
                        <th class="sticky-col-right sticky-header total-header" rowspan="2">
                            <div>Total</div>
                            <small style="font-weight:400; font-size:.65rem; opacity:.7;">/ 100</small>
                        </th>
                    </tr>
                    <!-- Baris 2: Nama indikator + kolom catatan -->
                    <tr class="thead-indikator">
                        @foreach($jenisNilaiList as $jn)
                            @foreach($jn->indikatorNilai as $indLoop => $ind)
                                @php
                                    $detailJson = json_encode(
                                        $ind->detailIndikator->map(fn($d) => [
                                            'level'  => $d->level,
                                            'uraian' => $d->uraian,
                                            'range'  => $d->range,
                                        ])->values()->toArray()
                                    );
                                    $rolesStr = $ind->roles->isEmpty()
                                        ? 'Admin'
                                        : $ind->roles->pluck('name')->map(fn($r) => ucfirst($r))->implode(', ');
                                @endphp
                                <th class="indikator-header ind-clickable"
                                    data-indikator-id="{{ $ind->id }}"
                                    data-bobot="{{ $ind->bobot }}"
                                    data-jenis-id="{{ $jn->id }}"
                                    data-ind-name="{{ $ind->name }}"
                                    data-jenis-name="{{ $jn->name }}"
                                    data-roles="{{ $rolesStr }}"
                                    data-detail='{{ $detailJson }}'>
                                    <div class="ind-inner">
                                        <div class="ind-name">{{ Str::limit($ind->name, 22) }}</div>
                                        <div class="ind-bobot">
                                            {{ $ind->bobot }}%
                                            <i class="fas fa-info-circle ind-icon-info"></i>
                                        </div>
                                    </div>
                                </th>
                            @endforeach
                            {{-- [BARU] Header kolom catatan per jenis nilai --}}
                            <th class="indikator-header catatan-col-header jenis-group-border"
                                data-jenis-id="{{ $jn->id }}">
                                <div class="ind-inner">
                                    <div class="ind-name" style="color:#64748b;">
                                        <i class="fas fa-comment-dots" style="font-size:.7rem;"></i>
                                        Catatan
                                    </div>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($peserta as $index => $item)
                        @php
                            $bisaDinilai = $item->bisaDinilaiUser ?? true;
                        @endphp
                        <tr class="peserta-row {{ !$bisaDinilai ? 'row-readonly' : '' }}"
                            data-peserta-id="{{ $item->id }}"
                            data-bisa-nilai="{{ $bisaDinilai ? '1' : '0' }}">

                            <td class="sticky-col sticky-col-1 td-no">
                                {{ $peserta->firstItem() + $index }}
                            </td>

                            <td class="sticky-col sticky-col-2 td-nama">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="peserta-avatar-sm">
                                        {{ strtoupper(substr($item->nama_lengkap, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold small peserta-nama-text">{{ $item->nama_lengkap }}</div>
                                        <small class="text-muted" style="font-size:.68rem;">{{ $item->nip_nrp ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>

                            <td class="sticky-col sticky-col-3 td-ndh text-center">
                                @if($item->ndh)
                                    <span class="badge bg-light text-dark border fw-bold">{{ $item->ndh }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td class="sticky-col sticky-col-4 td-kelompok">
                                @if($item->kelompokInfo)
                                    <div class="small fw-semibold">{{ $item->kelompokInfo->nama_kelompok }}</div>
                                    <small class="text-muted" style="font-size:.68rem;">{{ $item->kelompokInfo->angkatan->nama_angkatan ?? '-' }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <!-- Nilai Cells + Catatan Cell per jenis nilai -->
                            @foreach($jenisNilaiList as $jn)
                                @foreach($jn->indikatorNilai as $indLoop => $ind)
                                    @php
                                        $existingNilai = $item->nilaiMap[$ind->id] ?? null;
                                        $canEdit = $bisaDinilai && ($ind->userDapatNilai ?? false);
                                    @endphp
                                    <td class="nilai-cell {{ $canEdit ? 'editable' : 'readonly' }} {{ $existingNilai !== null ? 'status-saved' : '' }}"
                                        data-peserta-id="{{ $item->id }}"
                                        data-indikator-id="{{ $ind->id }}"
                                        data-bobot="{{ $ind->bobot }}"
                                        data-jenis-id="{{ $jn->id }}"
                                        data-saved="{{ $existingNilai ?? '' }}"
                                        data-current="{{ $existingNilai ?? '' }}">
                                        @if($canEdit)
                                            <div class="cell-display">
                                                @if($existingNilai !== null)
                                                    <span class="cell-value">{{ $existingNilai }}</span>
                                                @else
                                                    <span class="cell-empty">—</span>
                                                @endif
                                            </div>
                                            <input type="number"
                                                class="cell-input"
                                                min="0" max="100" step="1"
                                                value="{{ $existingNilai ?? '' }}"
                                                placeholder="0–100"
                                                autocomplete="off"
                                                style="display:none;">
                                        @else
                                            <div class="cell-display readonly-display">
                                                @if($existingNilai !== null)
                                                    <span class="cell-value">{{ $existingNilai }}</span>
                                                @else
                                                    <span class="cell-empty text-muted">—</span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                @endforeach

                                {{-- [BARU] Cell catatan per jenis nilai --}}
                                @php
                                    $existingCatatan = $item->catatanMap[$jn->id] ?? null;
                                    $hasCatatan      = !empty($existingCatatan);
                                @endphp
                                <td class="catatan-cell jenis-group-border {{ $hasCatatan ? 'catatan-has-value' : '' }}"
                                    data-peserta-id="{{ $item->id }}"
                                    data-jenis-id="{{ $jn->id }}"
                                    data-jenis-nama="{{ $jn->name }}"
                                    data-catatan="{{ $existingCatatan ?? '' }}"
                                    data-bisa-edit="{{ $bisaDinilai ? '1' : '0' }}"
                                    title="{{ $hasCatatan ? 'Ada catatan — klik untuk edit' : 'Klik untuk tambah catatan' }}">
                                    <button type="button"
                                        class="catatan-trigger-btn {{ $hasCatatan ? 'catatan-trigger-filled' : 'catatan-trigger-empty' }}"
                                        {{ !$bisaDinilai ? 'disabled' : '' }}>
                                        <i class="fas {{ $hasCatatan ? 'fa-comment-dots' : 'fa-comment' }}"></i>
                                    </button>
                                </td>
                            @endforeach

                            <!-- Total -->
                            <td class="sticky-col-right td-total" data-peserta-id="{{ $item->id }}">
                                <span class="total-value" id="total-{{ $item->id }}">
                                    {{ number_format($item->totalNilai ?? 0, 1) }}
                                </span>
                                <div class="total-bar">
                                    <div class="total-bar-fill" style="width:{{ min($item->totalNilai ?? 0, 100) }}%"></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 4 + $jenisNilaiList->sum(fn($jn) => $jn->indikatorNilai->count() + 1) + 1 }}"
                                class="text-center py-5">
                                <i class="fas fa-users fa-4x mb-3 d-block" style="color:#e9ecef;"></i>
                                <h5 class="text-muted">Belum ada peserta</h5>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($peserta->count() > 0)
        <div class="card-footer bg-white py-3 border-top">
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

{{-- ═══════════════════════════════════════════════════════════════
     [BARU] CATATAN POPOVER — floating div
════════════════════════════════════════════════════════════════ --}}
<div id="catatanPopover" class="catatan-popover" style="display:none;" role="dialog" aria-label="Catatan Penilaian">
    <div class="catatan-popover-arrow"></div>
    <div class="catatan-popover-header">
        <div class="catatan-popover-title">
            <i class="fas fa-comment-dots me-2" style="font-size:.85rem;"></i>
            <span id="catatanPopoverTitle">Catatan</span>
        </div>
        <button type="button" class="catatan-popover-close" id="btnCatatanClose" aria-label="Tutup">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="catatan-popover-body">
        <textarea id="catatanTextarea"
            class="catatan-textarea"
            placeholder="Tulis catatan untuk peserta ini..."
            maxlength="2000"
            rows="4"></textarea>
        <div class="catatan-char-count">
            <span id="catatanCharCount">0</span> / 2000
        </div>
    </div>
    <div class="catatan-popover-footer">
        <button type="button" class="btn-catatan-batal" id="btnCatatanBatal">
            <i class="fas fa-times me-1"></i>Batal
        </button>
        <button type="button" class="btn-catatan-hapus" id="btnCatatanHapus">
            <i class="fas fa-trash me-1"></i>Hapus
        </button>
        <button type="button" class="btn-catatan-simpan" id="btnCatatanSimpan">
            <i class="fas fa-check me-1"></i>Simpan
        </button>
    </div>
</div>
{{-- ── END CATATAN POPOVER ──────────────────────────────────────── --}}

{{-- MODAL DETAIL INDIKATOR --}}
<div class="modal fade" id="modalDetailIndikator" tabindex="-1"
    aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius:.75rem; overflow:hidden;">
            <div class="modal-header-custom">
                <div class="d-flex align-items-start gap-3 w-100">
                    <div class="modal-header-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <h5 class="modal-title text-white fw-bold mb-1 lh-sm" id="modalDetailLabel"
                            style="word-break:break-word;">—</h5>
                        <div class="d-flex flex-wrap gap-2" id="modalBadgeArea"></div>
                    </div>
                    <button type="button" class="btn-close btn-close-white flex-shrink-0 mt-1"
                        data-bs-dismiss="modal"></button>
                </div>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-4">
                        <div class="minfo-card">
                            <div class="minfo-label">Bobot</div>
                            <div class="minfo-value" id="mBobot">—</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="minfo-card">
                            <div class="minfo-label">Jenis Penilaian</div>
                            <div class="minfo-value text-truncate" id="mJenis" title="">—</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="minfo-card">
                            <div class="minfo-label">Penilai</div>
                            <div class="minfo-value" id="mPenilai">—</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:4px;height:18px;background:#285496;border-radius:2px;flex-shrink:0;"></div>
                    <h6 class="mb-0 fw-semibold" style="color:#285496;">Rubrik / Level Penilaian</h6>
                </div>

                <div id="modalRubrikBody"></div>
            </div>

            <div class="modal-footer border-0 pt-0 px-4 pb-4">
                <button type="button" class="btn btn-outline-secondary btn-sm px-4"
                    data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notifikasi -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:9999;">
    <div id="toastNilai" class="toast align-items-center border-0 shadow" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastBody"></div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── TOGGLE LAPORAN ────────────────────────────────────────
    window.toggleLaporan = function (totalCount) {
        const grid   = document.getElementById('laporanGrid');
        const btn    = document.getElementById('laporanToggle');
        const icon   = btn ? btn.querySelector('.laporan-toggle-icon') : null;
        const isOpen = btn && btn.dataset.open === '1';

        if (isOpen) {
            grid.classList.remove('laporan-grid-expanded');
            if (btn)  { btn.dataset.open = '0'; }
            if (icon) { icon.classList.replace('fa-chevron-up', 'fa-chevron-down'); }
            if (btn)  { btn.childNodes[btn.childNodes.length - 1].textContent = ' Lihat semua (' + totalCount + ')'; }
        } else {
            grid.classList.add('laporan-grid-expanded');
            if (btn)  { btn.dataset.open = '1'; }
            if (icon) { icon.classList.replace('fa-chevron-down', 'fa-chevron-up'); }
            if (btn)  { btn.childNodes[btn.childNodes.length - 1].textContent = ' Sembunyikan'; }
        }
    };

    // ── MODAL DETAIL INDIKATOR ────────────────────────────────
    const bsModal = new bootstrap.Modal(document.getElementById('modalDetailIndikator'));

    const LEVEL_COLORS = [
        { bg:'#fff3e0', border:'#ff9800', text:'#7a3b00', badge:'#ff9800' },
        { bg:'#e3f2fd', border:'#2196f3', text:'#0d47a1', badge:'#2196f3' },
        { bg:'#e8f5e9', border:'#4caf50', text:'#1b5e20', badge:'#4caf50' },
        { bg:'#f3e5f5', border:'#9c27b0', text:'#4a148c', badge:'#9c27b0' },
        { bg:'#fce4ec', border:'#e91e63', text:'#880e4f', badge:'#e91e63' },
        { bg:'#e0f7fa', border:'#00bcd4', text:'#006064', badge:'#00bcd4' },
    ];

    document.querySelectorAll('.ind-clickable').forEach(th => {
        th.addEventListener('click', function () {
            const name   = this.dataset.indName   ?? '—';
            const jenis  = this.dataset.jenisName ?? '—';
            const bobot  = this.dataset.bobot     ?? '—';
            const roles  = this.dataset.roles     ?? '—';
            let   detail = [];
            try { detail = JSON.parse(this.dataset.detail || '[]'); } catch(e) {}

            document.getElementById('modalDetailLabel').textContent = name;
            document.getElementById('mBobot').textContent           = bobot + '%';
            document.getElementById('mJenis').textContent           = jenis;
            document.getElementById('mJenis').title                 = jenis;
            document.getElementById('mPenilai').textContent         = roles;

            document.getElementById('modalBadgeArea').innerHTML =
                `<span class="badge bg-white bg-opacity-25 text-white fw-normal" style="font-size:.72rem;">
                    <i class="fas fa-percentage me-1"></i>Bobot ${bobot}%
                 </span>
                 <span class="badge bg-white bg-opacity-25 text-white fw-normal" style="font-size:.72rem;">
                    <i class="fas fa-user-check me-1"></i>${roles}
                 </span>`;

            const rubrikEl = document.getElementById('modalRubrikBody');
            if (!detail.length) {
                rubrikEl.innerHTML = `
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-folder-open fa-3x mb-3 d-block" style="opacity:.2;"></i>
                        <p class="mb-0">Belum ada rubrik penilaian untuk indikator ini.</p>
                    </div>`;
            } else {
                const rows = detail.map((d, i) => {
                    const c = LEVEL_COLORS[i % LEVEL_COLORS.length];
                    return `
                        <tr>
                            <td class="text-center py-3" style="width:80px;">
                                <span style="display:inline-block;background:${c.badge};color:#fff;
                                    padding:.25rem .65rem;border-radius:20px;font-size:.72rem;
                                    font-weight:700;white-space:nowrap;">Level ${d.level}</span>
                            </td>
                            <td class="py-3" style="font-size:.83rem;color:#333;line-height:1.6;">
                                ${d.uraian ? d.uraian : '<em class="text-muted">—</em>'}
                            </td>
                            <td class="text-center py-3" style="width:120px;">
                                <span style="display:inline-block;background:${c.bg};color:${c.text};
                                    border:1px solid ${c.border};padding:.2rem .6rem;border-radius:6px;
                                    font-size:.8rem;font-weight:600;white-space:nowrap;">
                                    ${d.range ? d.range : '—'}
                                </span>
                            </td>
                        </tr>`;
                }).join('');

                rubrikEl.innerHTML = `
                    <div class="table-responsive rounded-3 border">
                        <table class="table mb-0" style="font-size:.83rem;">
                            <thead style="background:#f0f4ff;">
                                <tr>
                                    <th class="text-center py-2 px-3"
                                        style="color:#285496;font-size:.72rem;font-weight:700;
                                               text-transform:uppercase;letter-spacing:.05em;
                                               border-bottom:2px solid #d0dff5;width:80px;">Level</th>
                                    <th class="py-2 px-3"
                                        style="color:#285496;font-size:.72rem;font-weight:700;
                                               text-transform:uppercase;letter-spacing:.05em;
                                               border-bottom:2px solid #d0dff5;">Uraian / Deskripsi Kinerja</th>
                                    <th class="text-center py-2 px-3"
                                        style="color:#285496;font-size:.72rem;font-weight:700;
                                               text-transform:uppercase;letter-spacing:.05em;
                                               border-bottom:2px solid #d0dff5;width:120px;">Range Nilai</th>
                                </tr>
                            </thead>
                            <tbody>${rows}</tbody>
                        </table>
                    </div>`;
            }

            bsModal.show();
        });
    });

    // ── FILTER WILAYAH ────────────────────────────────────────
    const filterKategori       = document.getElementById('filterKategori');
    const filterWilayahWrapper = document.getElementById('filterWilayahWrapper');
    const filterWilayah        = document.getElementById('filterWilayah');
    if (filterKategori) {
        filterKategori.addEventListener('change', function () {
            filterWilayahWrapper.style.display = this.value === 'FASILITASI' ? '' : 'none';
            if (this.value !== 'FASILITASI') filterWilayah.value = '';
        });
    }

    // ── SPREADSHEET NILAI ─────────────────────────────────────
    const pendingChanges = new Map();
    const csrfToken      = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const toastEl        = document.getElementById('toastNilai');
    const bsToast        = new bootstrap.Toast(toastEl, { delay: 3000 });

    function showToast(msg, type = 'success') {
        toastEl.className = `toast align-items-center border-0 shadow text-white bg-${type}`;
        document.getElementById('toastBody').innerHTML =
            `<i class="fas fa-${type === 'success' ? 'check-circle' : 'times-circle'} me-2"></i>${msg}`;
        bsToast.show();
    }

    function updatePendingUI() {
        const n       = pendingChanges.size;
        const badge   = document.getElementById('badge-unsaved');
        const btn     = document.getElementById('btnSimpanSemua');
        const cBtn    = document.getElementById('countSimpan');
        const cBadge  = document.getElementById('count-unsaved');
        if (n > 0) {
            badge.style.removeProperty('display');
            btn.style.removeProperty('display');
        } else {
            badge.style.setProperty('display','none','important');
            btn.style.display = 'none';
        }
        if (cBtn)   cBtn.textContent   = n;
        if (cBadge) cBadge.textContent = n;
    }

    function recalcTotal(pesertaId) {
        let total = 0;
        document.querySelectorAll(`.nilai-cell[data-peserta-id="${pesertaId}"]`).forEach(td => {
            const cur   = td.dataset.current;
            const bobot = parseFloat(td.dataset.bobot) || 0;
            if (cur !== '' && cur !== undefined) {
                const v = parseFloat(cur);
                if (!isNaN(v)) total += (v / 100) * bobot;
            }
        });
        const el = document.getElementById(`total-${pesertaId}`);
        if (el) {
            el.textContent = total.toFixed(1);
            const fill = el.closest('td')?.querySelector('.total-bar-fill');
            if (fill) fill.style.width = Math.min(total, 100) + '%';
        }
    }

    function renderDisplay(td, val) {
        const display = td.querySelector('.cell-display');
        const input   = td.querySelector('.cell-input');

        if (display) display.style.display = '';
        if (input)   input.style.display   = 'none';

        td.classList.remove('status-saved', 'status-pending');

        const hasVal = val !== null && val !== undefined && val !== '';
        if (hasVal) {
            display.innerHTML = `<span class="cell-value">${val}</span>`;
            td.classList.add(td.classList.contains('pending') ? 'status-pending' : 'status-saved');
        } else {
            display.innerHTML = `<span class="cell-empty">—</span>`;
        }
    }

    function activateCell(td) {
        if (!td.classList.contains('editable') || td.classList.contains('editing')) return;

        document.querySelectorAll('.nilai-cell.editing').forEach(other => {
            if (other !== td) deactivateCell(other, true);
        });

        const display = td.querySelector('.cell-display');
        const input   = td.querySelector('.cell-input');

        if (display) display.style.display = 'none';
        if (input) {
            input.style.display = 'block';
            input.value = td.dataset.current !== undefined ? td.dataset.current : '';
            requestAnimationFrame(() => { input.focus(); input.select(); });
        }

        td.classList.add('editing');
    }

    function deactivateCell(td, save = true) {
        if (!td.classList.contains('editing')) return;

        const display  = td.querySelector('.cell-display');
        const input    = td.querySelector('.cell-input');
        if (!input || !display) { td.classList.remove('editing'); return; }

        td.classList.remove('editing');

        input.style.display   = 'none';
        display.style.display = '';

        const rawVal   = input.value.trim();
        const savedVal = td.dataset.saved ?? '';

        let newVal = null;
        if (rawVal !== '') {
            const n = parseFloat(rawVal);
            if (!isNaN(n) && n >= 0 && n <= 100) {
                newVal = n;
            } else {
                input.value        = savedVal;
                td.dataset.current = savedVal;
                renderDisplay(td, savedVal !== '' ? parseFloat(savedVal) : null);
                recalcTotal(td.dataset.pesertaId);
                return;
            }
        }

        const newValStr = newVal !== null ? String(newVal) : '';
        const key       = `${td.dataset.pesertaId}-${td.dataset.indikatorId}`;
        const changed   = newValStr !== String(savedVal);

        if (changed) {
            td.dataset.current = newValStr;
            pendingChanges.set(key, { pesertaId: td.dataset.pesertaId, indikatorId: td.dataset.indikatorId, nilai: newVal, td });
            td.classList.add('pending');
            renderDisplay(td, newVal);
            updatePendingUI();
        } else {
            td.dataset.current = savedVal;
            if (pendingChanges.has(key)) {
                pendingChanges.delete(key);
                td.classList.remove('pending');
                updatePendingUI();
            }
            renderDisplay(td, savedVal !== '' ? parseFloat(savedVal) : null);
        }

        recalcTotal(td.dataset.pesertaId);

        if (save && changed) simpanSatuNilai(td, key, newVal);
    }

    async function simpanSatuNilai(td, key, val) {
        td.classList.add('saving');
        try {
            const body = {
                peserta_id:         td.dataset.pesertaId,
                indikator_nilai_id: td.dataset.indikatorId,
            };
            if (val !== null) body.nilai_input = val;

            const res  = await fetch('/nilai/simpan', {
                method: 'POST',
                headers: {
                    'Content-Type'    : 'application/json',
                    'X-CSRF-TOKEN'    : csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(body),
            });
            const data = await res.json();
            td.classList.remove('saving');

            if (data.success) {
                const finalStr     = val !== null ? String(val) : '';
                td.dataset.saved   = finalStr;
                td.dataset.current = finalStr;
                td.classList.remove('pending');
                pendingChanges.delete(key);
                renderDisplay(td, val);
                updatePendingUI();
                showToast(data.deleted ? 'Nilai dihapus.' : 'Nilai tersimpan.', 'success');
            } else {
                td.classList.add('error');
                showToast('Gagal: ' + (data.message ?? ''), 'danger');
                setTimeout(() => td.classList.remove('error'), 3000);
            }
        } catch {
            td.classList.remove('saving');
            td.classList.add('error');
            showToast('Gagal (error jaringan)', 'danger');
            setTimeout(() => td.classList.remove('error'), 3000);
        }
    }

    // ── Simpan semua pending ──────────────────────────────────
    document.getElementById('btnSimpanSemua')?.addEventListener('click', async function () {
        if (!pendingChanges.size) return;
        this.disabled  = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
        await Promise.all([...pendingChanges.entries()].map(([k, item]) =>
            simpanSatuNilai(item.td, k, item.nilai)
        ));
        this.disabled  = false;
        this.innerHTML = '<i class="fas fa-save me-1"></i> Simpan Semua (<span id="countSimpan">0</span>)';
        if (!pendingChanges.size) showToast('Semua nilai berhasil disimpan.', 'success');
    });

    // ── Klik cell → edit ─────────────────────────────────────
    document.querySelectorAll('.nilai-cell.editable').forEach(td => {
        td.addEventListener('click', function (e) {
            if (e.target.tagName === 'INPUT') return;
            activateCell(this);
        });
    });

    // ── Keyboard ─────────────────────────────────────────────
    document.querySelectorAll('.cell-input').forEach(input => {
        input.addEventListener('keydown', function (e) {
            const td       = this.closest('.nilai-cell');
            const allCells = [...document.querySelectorAll('.nilai-cell.editable')];

            switch(e.key) {
                case 'Enter':
                    e.preventDefault();
                    deactivateCell(td, true);
                    { const ni = nextInCol(allCells, td, 1); if (ni !== -1) activateCell(allCells[ni]); }
                    break;
                case 'Tab':
                    e.preventDefault();
                    deactivateCell(td, true);
                    { const idx = allCells.indexOf(td); const next = allCells[idx + (e.shiftKey ? -1 : 1)]; if (next) activateCell(next); }
                    break;
                case 'Escape':
                    this.value = td.dataset.saved ?? '';
                    deactivateCell(td, false);
                    break;
                case 'ArrowDown':
                    e.preventDefault();
                    deactivateCell(td, true);
                    { const ni = nextInCol(allCells, td, 1); if (ni !== -1) activateCell(allCells[ni]); }
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    deactivateCell(td, true);
                    { const ni = nextInCol(allCells, td, -1); if (ni !== -1) activateCell(allCells[ni]); }
                    break;
            }
        });

        input.addEventListener('blur', function () {
            const td = this.closest('.nilai-cell');
            setTimeout(() => { if (td.classList.contains('editing')) deactivateCell(td, true); }, 150);
        });
    });

    function nextInCol(allCells, currentTd, dir) {
        const id       = currentTd.dataset.indikatorId;
        const colCells = allCells.filter(c => c.dataset.indikatorId === id);
        const idx      = colCells.indexOf(currentTd) + dir;
        if (idx < 0 || idx >= colCells.length) return -1;
        return allCells.indexOf(colCells[idx]);
    }

    // ── Klik luar → tutup editing ─────────────────────────────
    document.addEventListener('click', function (e) {
        if (e.target.closest('#modalDetailIndikator')) return;
        if (e.target.closest('#catatanPopover'))       return;
        if (e.target.closest('.catatan-cell'))         return;
        if (!e.target.closest('.nilai-cell')) {
            document.querySelectorAll('.nilai-cell.editing').forEach(td => deactivateCell(td, true));
        }
    });

    // ── Alert auto-close ──────────────────────────────────────
    document.querySelectorAll('.alert').forEach(a => {
        setTimeout(() => bootstrap.Alert.getOrCreateInstance(a)?.close(), 5000);
    });

    // ── Init recalc semua row ─────────────────────────────────
    document.querySelectorAll('.peserta-row').forEach(row => recalcTotal(row.dataset.pesertaId));


    // ════════════════════════════════════════════════════════
    // [BARU] CATATAN POPOVER LOGIC
    // ════════════════════════════════════════════════════════
    const popover        = document.getElementById('catatanPopover');
    const popoverTitle   = document.getElementById('catatanPopoverTitle');
    const textarea       = document.getElementById('catatanTextarea');
    const charCount      = document.getElementById('catatanCharCount');
    const btnClose       = document.getElementById('btnCatatanClose');
    const btnBatal       = document.getElementById('btnCatatanBatal');
    const btnHapus       = document.getElementById('btnCatatanHapus');
    const btnSimpan      = document.getElementById('btnCatatanSimpan');

    let activeCatatanCell = null; // TD yang sedang aktif

    // Hitung karakter
    textarea.addEventListener('input', function () {
        charCount.textContent = this.value.length;
    });

    // Buka popover
    function openCatatanPopover(td) {
        // Tutup cell nilai yang sedang editing
        document.querySelectorAll('.nilai-cell.editing').forEach(c => deactivateCell(c, true));

        activeCatatanCell = td;

        const jenisNama       = td.dataset.jenisNama  ?? 'Catatan';
        const existingCatatan = td.dataset.catatan     ?? '';

        popoverTitle.textContent  = jenisNama;
        textarea.value            = existingCatatan;
        charCount.textContent     = existingCatatan.length;

        // Hapus highlight sebelumnya
        document.querySelectorAll('.catatan-cell.catatan-active').forEach(c => c.classList.remove('catatan-active'));
        td.classList.add('catatan-active');

        // Tampilkan popover dulu agar bisa diukur posisinya
        popover.style.display = 'block';
        positionPopover(td);

        textarea.focus();
    }

    // Posisikan popover relatif terhadap td
    function positionPopover(td) {
        const rect        = td.getBoundingClientRect();
        const scrollTop   = window.pageYOffset || document.documentElement.scrollTop;
        const scrollLeft  = window.pageXOffset || document.documentElement.scrollLeft;
        const popW        = popover.offsetWidth  || 320;
        const popH        = popover.offsetHeight || 220;
        const vw          = window.innerWidth;
        const vh          = window.innerHeight;

        // Coba muncul di bawah cell
        let top  = rect.bottom + scrollTop  + 8;
        let left = rect.left   + scrollLeft + (rect.width / 2) - (popW / 2);

        // Kalau ke kanan layar → geser kiri
        if (left + popW + 16 > scrollLeft + vw) left = scrollLeft + vw - popW - 16;
        // Kalau ke kiri layar → geser kanan
        if (left < scrollLeft + 8) left = scrollLeft + 8;

        // Kalau bawah terpotong → muncul di atas cell
        const spaceBelow = vh - rect.bottom;
        if (spaceBelow < popH + 20 && rect.top > popH + 20) {
            top = rect.top + scrollTop - popH - 8;
            popover.classList.add('popover-above');
            popover.classList.remove('popover-below');
        } else {
            popover.classList.add('popover-below');
            popover.classList.remove('popover-above');
        }

        popover.style.top  = top  + 'px';
        popover.style.left = left + 'px';

        // Posisi panah relatif ke popover
        const arrowLeft = rect.left + scrollLeft + (rect.width / 2) - left;
        const arrow     = popover.querySelector('.catatan-popover-arrow');
        if (arrow) arrow.style.left = Math.max(16, Math.min(arrowLeft, popW - 16)) + 'px';
    }

    // Tutup popover
    function closeCatatanPopover() {
        popover.style.display = 'none';
        if (activeCatatanCell) {
            activeCatatanCell.classList.remove('catatan-active');
            activeCatatanCell = null;
        }
    }

    // Simpan catatan ke server
    async function simpanCatatan(pesertaId, jenisNilaiId, catatan) {
        btnSimpan.disabled = true;
        btnSimpan.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';

        try {
            const res = await fetch('/nilai/catatan/simpan', {
                method: 'POST',
                headers: {
                    'Content-Type'    : 'application/json',
                    'X-CSRF-TOKEN'    : csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    peserta_id    : pesertaId,
                    jenis_nilai_id: jenisNilaiId,
                    catatan       : catatan,
                }),
            });
            const data = await res.json();

            btnSimpan.disabled = false;
            btnSimpan.innerHTML = '<i class="fas fa-check me-1"></i>Simpan';

            if (data.success) {
                // Update data-catatan di cell
                if (activeCatatanCell) {
                    activeCatatanCell.dataset.catatan = catatan;

                    const btn = activeCatatanCell.querySelector('.catatan-trigger-btn');
                    if (catatan.trim()) {
                        activeCatatanCell.classList.add('catatan-has-value');
                        activeCatatanCell.title = 'Ada catatan — klik untuk edit';
                        if (btn) {
                            btn.classList.remove('catatan-trigger-empty');
                            btn.classList.add('catatan-trigger-filled');
                            btn.innerHTML = '<i class="fas fa-comment-dots"></i>';
                        }
                    } else {
                        activeCatatanCell.classList.remove('catatan-has-value');
                        activeCatatanCell.title = 'Klik untuk tambah catatan';
                        if (btn) {
                            btn.classList.remove('catatan-trigger-filled');
                            btn.classList.add('catatan-trigger-empty');
                            btn.innerHTML = '<i class="fas fa-comment"></i>';
                        }
                    }
                }

                showToast('Catatan berhasil disimpan.', 'success');
                closeCatatanPopover();
            } else {
                showToast('Gagal menyimpan catatan: ' + (data.message ?? ''), 'danger');
            }
        } catch (err) {
            btnSimpan.disabled = false;
            btnSimpan.innerHTML = '<i class="fas fa-check me-1"></i>Simpan';
            showToast('Gagal (error jaringan)', 'danger');
        }
    }

    // Event: klik tombol pada cell catatan
    document.querySelectorAll('.catatan-cell').forEach(td => {
        td.addEventListener('click', function (e) {
            e.stopPropagation();
            if (this.dataset.bisaEdit === '0') return;
            // Kalau popover sudah terbuka untuk cell ini → tutup
            if (activeCatatanCell === this) {
                closeCatatanPopover();
                return;
            }
            openCatatanPopover(this);
        });
    });

    // Event: tombol simpan
    btnSimpan.addEventListener('click', function () {
        if (!activeCatatanCell) return;
        const pesertaId    = activeCatatanCell.dataset.pesertaId;
        const jenisNilaiId = activeCatatanCell.dataset.jenisId;
        const catatan      = textarea.value.trim();
        simpanCatatan(pesertaId, jenisNilaiId, catatan);
    });

    // Event: tombol hapus (kirim catatan kosong)
    btnHapus.addEventListener('click', function () {
        if (!activeCatatanCell) return;
        if (!confirm('Hapus catatan ini?')) return;
        const pesertaId    = activeCatatanCell.dataset.pesertaId;
        const jenisNilaiId = activeCatatanCell.dataset.jenisId;
        textarea.value = '';
        simpanCatatan(pesertaId, jenisNilaiId, '');
    });

    // Event: tombol batal & close
    btnBatal.addEventListener('click', closeCatatanPopover);
    btnClose.addEventListener('click', closeCatatanPopover);

    // Event: klik luar popover → tutup
    document.addEventListener('click', function (e) {
        if (!popover || popover.style.display === 'none') return;
        if (popover.contains(e.target)) return;
        if (e.target.closest('.catatan-cell'))   return;
        closeCatatanPopover();
    });

    // Event: Escape → tutup popover
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && popover.style.display !== 'none') {
            closeCatatanPopover();
        }
    });

    // Re-posisi saat scroll / resize
    let reposTimeout;
    document.querySelector('.spreadsheet-scroll')?.addEventListener('scroll', function () {
        if (popover.style.display === 'none' || !activeCatatanCell) return;
        clearTimeout(reposTimeout);
        reposTimeout = setTimeout(() => positionPopover(activeCatatanCell), 50);
    });
    window.addEventListener('resize', function () {
        if (popover.style.display === 'none' || !activeCatatanCell) return;
        clearTimeout(reposTimeout);
        reposTimeout = setTimeout(() => positionPopover(activeCatatanCell), 50);
    });

}); // end DOMContentLoaded
</script>

<style>
/* ════════════════════════════════════════════
   LAPORAN KELOMPOK SECTION
════════════════════════════════════════════ */
.laporan-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.laporan-grid:not(.laporan-grid-expanded) .laporan-card:nth-child(n+7) { display: none; }

.laporan-card {
    display: flex; align-items: center; gap: 10px;
    background: #f8fafc; border: 1px solid #e4ecf7;
    border-radius: 10px; padding: 10px 12px;
    flex: 1 1 190px; max-width: 270px; min-width: 190px;
    transition: border-color .15s, box-shadow .15s;
}
.laporan-card-has-link:hover { border-color: #285496; box-shadow: 0 2px 8px rgba(40,84,150,.09); }
.laporan-card-no-link { opacity: .8; }

.laporan-card-icon {
    width: 32px; height: 32px; border-radius: 7px;
    background: #dbeafe; color: #285496;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.laporan-card-icon.laporan-card-icon-empty { background: #f1f5f9; color: #94a3b8; }
.laporan-card-info { flex: 1; min-width: 0; }
.laporan-card-name { font-size: .8rem; font-weight: 600; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.laporan-card-meta { font-size: .7rem; color: #64748b; margin-top: 1px; }

.laporan-btn {
    font-size: .72rem; font-weight: 600; padding: 4px 10px;
    border-radius: 6px; background: #285496; color: #fff !important;
    text-decoration: none; white-space: nowrap; flex-shrink: 0;
    transition: background .12s; display: inline-flex; align-items: center; gap: 4px;
}
.laporan-btn:hover { background: #1d3d70; color: #fff !important; }
.laporan-btn-empty {
    font-size: .7rem; padding: 4px 8px; border-radius: 6px;
    background: #f1f5f9; color: #94a3b8; border: 1px dashed #cbd5e1; white-space: nowrap; flex-shrink: 0;
}
.laporan-toggle-btn {
    font-size: .75rem; color: #285496; background: none; border: none;
    cursor: pointer; padding: 0; display: inline-flex; align-items: center; gap: 3px;
}
.laporan-toggle-btn:hover { text-decoration: underline; }

/* ════════════════════════════════════════════
   SPREADSHEET LAYOUT
════════════════════════════════════════════ */
.spreadsheet-wrapper { overflow: hidden; position: relative; }
.spreadsheet-scroll  { overflow-x: auto; overflow-y: auto; max-height: calc(100vh - 380px); min-height: 300px; }
.spreadsheet-table   { border-collapse: separate; border-spacing: 0; width: max-content; min-width: 100%; font-size: .82rem; }

.sticky-col          { position: sticky; background: white; z-index: 3; }
.sticky-header       { z-index: 5 !important; }
.sticky-col-1        { left: 0;     min-width: 48px;  max-width: 48px;  border-right: 1px solid #dee2e6; }
.sticky-col-2        { left: 48px;  min-width: 220px; max-width: 220px; border-right: 1px solid #dee2e6; }
.sticky-col-3        { left: 268px; min-width: 60px;  max-width: 60px;  border-right: 1px solid #dee2e6; }
.sticky-col-4        { left: 328px; min-width: 140px; max-width: 140px; border-right: 2px solid #285496; }

.sticky-col-right {
    position: sticky; right: 0; background: white; z-index: 3;
    border-left: 2px solid #285496; min-width: 90px; max-width: 90px; text-align: center;
}

.spreadsheet-table thead tr th {
    position: sticky; top: 0; z-index: 4;
    background: #f8fafc; padding: .5rem .6rem;
    font-size: .75rem; font-weight: 600; color: #285496;
    white-space: nowrap; border-bottom: 2px solid rgba(40,84,150,.15);
}
.spreadsheet-table thead tr.thead-indikator th {
    top: 48px; z-index: 4;
    background: #f0f4ff; color: #444; font-weight: 500;
    border-bottom: 2px solid #dee2e6;
}

.jenis-group-border  { border-right: 2px solid #285496 !important; }
.jenis-border-right  { border-right: 2px solid #285496 !important; }
.spreadsheet-table thead tr th.jenis-group-border { border-right: 2px solid #285496 !important; }

.jenis-nilai-header  { border-bottom: 2px solid rgba(40,84,150,.15) !important; padding: .4rem .6rem !important; }
.jn-label { display: flex; flex-direction: column; align-items: center; gap: 2px; }
.jn-name  { font-weight: 700; font-size: .78rem; color: #285496; }
.jn-bobot { font-size: .65rem; font-weight: 500; background: rgba(40,84,150,.12); color: #285496; border-radius: 4px; padding: .05rem .35rem; }

.indikator-header { max-width: 100px; min-width: 80px; }
.ind-clickable    { cursor: pointer; transition: background .12s; }
.ind-clickable:hover { background: #e4ecff !important; }
.ind-clickable:hover .ind-icon-info { opacity: 1; color: #285496; }
.ind-inner  { display: flex; flex-direction: column; align-items: center; gap: 2px; }
.ind-name   { font-size: .72rem; color: #333; text-align: center; white-space: normal; line-height: 1.3; max-width: 90px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
.ind-bobot  { font-size: .65rem; color: #888; display: flex; align-items: center; gap: 3px; }
.ind-icon-info { font-size: .6rem; opacity: .35; transition: opacity .15s, color .15s; }

.peserta-row td { padding: .4rem .6rem; border-bottom: 1px solid #e9ecef; vertical-align: middle; background: white; }
.peserta-row:hover td { background: #f8faff; }
.peserta-row:hover .sticky-col,
.peserta-row:hover .sticky-col-right { background: #f8faff !important; }
.peserta-row.row-readonly td { opacity: .65; }
.peserta-row .sticky-col { background: white; }

.peserta-row td.jenis-group-border,
.peserta-row:hover td.jenis-group-border { border-right: 2px solid #285496 !important; }

/* ════════════════════════════════════════
   CELL NILAI
════════════════════════════════════════ */
.nilai-cell {
    min-width: 80px; max-width: 100px;
    text-align: center; cursor: default;
    transition: background .12s;
    padding: .3rem .4rem !important;
    position: relative;
}
.nilai-cell.editable               { cursor: pointer; }
.nilai-cell.editable:hover:not(.editing) { background: #eef2ff !important; }
.nilai-cell.status-saved           { background: #edf7ee !important; }
.nilai-cell.status-pending         { background: #e8f0fe !important; outline: 1px solid #a8c4f8; outline-offset: -1px; }
.nilai-cell.editing                { background: #fffbeb !important; box-shadow: inset 0 0 0 2px #285496; }
.nilai-cell.saving                 { opacity: .55; pointer-events: none; }
.nilai-cell.error                  { background: rgba(220,53,69,.08) !important; box-shadow: inset 0 0 0 2px #dc3545 !important; }

.cell-display {
    display: flex; align-items: center; justify-content: center;
    min-height: 28px; width: 100%;
}
.cell-value { font-weight: 700; font-size: .88rem; color: #1a1a2e; line-height: 1; }
.nilai-cell.status-saved  .cell-value { color: #1e5c22; }
.nilai-cell.status-pending .cell-value { color: #1a3c8e; }
.cell-empty      { color: #ccc; font-size: .8rem; }
.readonly-display { opacity: .7; cursor: default; }

.cell-input {
    width: 100%; border: none; outline: none; background: transparent;
    text-align: center; font-weight: 700; font-size: .9rem;
    color: #285496; padding: 0; min-height: 28px;
    -moz-appearance: textfield;
}
.cell-input::-webkit-outer-spin-button,
.cell-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

/* Total */
.total-header   { background: #f0f4ff !important; color: #285496 !important; }
.td-total       { background: #f8fafc; }
.total-value    { font-weight: 700; font-size: .9rem; color: #285496; display: block; }
.total-bar      { height: 4px; border-radius: 2px; background: #e9ecef; margin-top: 3px; overflow: hidden; }
.total-bar-fill { height: 100%; background: linear-gradient(90deg, #285496, #3a6bc7); border-radius: 2px; transition: width .4s ease; }

/* Legend */
.legend-swatch  { display:inline-flex; align-items:center; justify-content:center; min-width:36px; height:22px; border-radius:5px; font-size:.72rem; font-weight:700; padding:0 6px; }
.swatch-saved   { background:#edf7ee; color:#1e5c22; border:1px solid #b7debb; }
.swatch-pending { background:#e8f0fe; color:#1a3c8e; border:1px solid #a8c4f8; }
.swatch-empty   { background:#f8f9fa; color:#aaa;    border:1px solid #dee2e6; }

/* Peserta */
.peserta-avatar-sm {
    width:30px; height:30px; border-radius:8px;
    background:linear-gradient(135deg,#285496,#3a6bc7);
    color:white; font-size:.75rem; font-weight:700;
    display:flex; align-items:center; justify-content:center; flex-shrink:0;
}
.peserta-nama-text { max-width:170px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

/* Pagination */
.pagination-sm .page-link { padding:.375rem .625rem; border-radius:6px; color:#285496; }
.pagination-sm .page-item.active .page-link { background-color:#285496; border-color:#285496; }

/* Scrollbar */
.spreadsheet-scroll::-webkit-scrollbar       { height:8px; width:8px; }
.spreadsheet-scroll::-webkit-scrollbar-track { background:#f1f1f1; }
.spreadsheet-scroll::-webkit-scrollbar-thumb { background:#c1c1c1; border-radius:4px; }
.spreadsheet-scroll::-webkit-scrollbar-thumb:hover { background:#285496; }

/* ════════════════════════════════════════
   MODAL DETAIL INDIKATOR
════════════════════════════════════════ */
.modal-header-custom {
    background: linear-gradient(135deg, #285496, #3a6bc7);
    padding: 1.25rem 1.5rem 1rem;
}
.modal-header-icon {
    width: 44px; height: 44px; border-radius: 50%;
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.modal-header-icon i { color: #fff; font-size: 1.1rem; }
.minfo-card {
    background: #f8fafc; border: 1px solid #e4ecf7;
    border-radius: 10px; padding: .7rem .9rem; text-align: center;
}
.minfo-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #999; margin-bottom: 4px; }
.minfo-value { font-size: .95rem; font-weight: 700; color: #285496; line-height: 1.2; }

/* ════════════════════════════════════════
   [BARU] CATATAN CELL & BUTTON
════════════════════════════════════════ */
.catatan-col-header {
    min-width: 54px;
    max-width: 54px;
    text-align: center;
    background: #f8faff !important;
    cursor: default !important;
}
.catatan-col-header .ind-name { color: #64748b !important; }

.catatan-cell {
    min-width: 54px;
    max-width: 54px;
    text-align: center;
    padding: .3rem .25rem !important;
    cursor: pointer;
    transition: background .12s;
    position: relative;
}
.catatan-cell:hover { background: #f0f4ff !important; }
.catatan-cell.catatan-has-value { background: #eff8ff !important; }
.catatan-cell.catatan-active {
    background: #dbeafe !important;
    box-shadow: inset 0 0 0 2px #285496;
}

.catatan-trigger-btn {
    background: none;
    border: none;
    padding: 4px 6px;
    border-radius: 6px;
    cursor: pointer;
    line-height: 1;
    transition: transform .12s, color .12s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.catatan-trigger-btn:disabled { cursor: not-allowed; opacity: .4; }
.catatan-trigger-btn:not(:disabled):hover { transform: scale(1.15); }

.catatan-trigger-empty  { color: #cbd5e1; }
.catatan-trigger-filled { color: #285496; }
.catatan-cell.catatan-has-value .catatan-trigger-filled { color: #285496; }

/* Legend icon */
.catatan-icon-legend {
    display: inline-flex; align-items: center; justify-content: center;
    width: 22px; height: 22px; border-radius: 5px; font-size: .72rem;
}
.catatan-icon-filled { background: #dbeafe; color: #285496; }
.catatan-icon-empty  { background: #f1f5f9; color: #cbd5e1; }

/* ════════════════════════════════════════
   [BARU] CATATAN POPOVER
════════════════════════════════════════ */
.catatan-popover {
    position: absolute;
    z-index: 1080;
    width: 320px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(40,84,150,.18), 0 2px 8px rgba(0,0,0,.08);
    overflow: hidden;
    animation: popoverFadeIn .15s ease;
}
@keyframes popoverFadeIn {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}
.catatan-popover.popover-above { animation: popoverFadeInUp .15s ease; }
@keyframes popoverFadeInUp {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Panah */
.catatan-popover-arrow {
    position: absolute;
    top: -7px;
    left: 50%;
    transform: translateX(-50%);
    width: 14px;
    height: 7px;
    overflow: hidden;
}
.catatan-popover-arrow::after {
    content: '';
    position: absolute;
    top: 1px;
    left: 50%;
    transform: translateX(-50%) rotate(45deg);
    width: 10px;
    height: 10px;
    background: #fff;
    border: 1px solid #e2e8f0;
    box-shadow: 0 0 0 0 transparent;
}
.catatan-popover.popover-above .catatan-popover-arrow {
    top: auto;
    bottom: -7px;
    transform: translateX(-50%) rotate(180deg);
}

/* Header */
.catatan-popover-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .6rem .85rem .55rem;
    background: linear-gradient(135deg, #285496, #3a6bc7);
    gap: 8px;
}
.catatan-popover-title {
    font-size: .8rem;
    font-weight: 700;
    color: #fff;
    display: flex;
    align-items: center;
    flex: 1;
    min-width: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.catatan-popover-close {
    background: rgba(255,255,255,.2);
    border: none;
    border-radius: 6px;
    color: #fff;
    width: 24px; height: 24px;
    display: flex; align-items: center; justify-content: center;
    font-size: .7rem;
    cursor: pointer;
    flex-shrink: 0;
    transition: background .12s;
}
.catatan-popover-close:hover { background: rgba(255,255,255,.35); }

/* Body */
.catatan-popover-body { padding: .75rem .85rem .5rem; }

.catatan-textarea {
    width: 100%;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    padding: .55rem .7rem;
    font-size: .82rem;
    color: #1e293b;
    resize: vertical;
    min-height: 80px;
    max-height: 180px;
    outline: none;
    transition: border-color .15s, box-shadow .15s;
    font-family: inherit;
    line-height: 1.55;
}
.catatan-textarea:focus {
    border-color: #285496;
    box-shadow: 0 0 0 3px rgba(40,84,150,.1);
}
.catatan-textarea::placeholder { color: #b0bec5; }

.catatan-char-count {
    font-size: .68rem;
    color: #94a3b8;
    text-align: right;
    margin-top: 4px;
}

/* Footer */
.catatan-popover-footer {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: .5rem .85rem .7rem;
    border-top: 1px solid #f1f5f9;
}

.btn-catatan-batal,
.btn-catatan-hapus,
.btn-catatan-simpan {
    border: none;
    border-radius: 7px;
    font-size: .76rem;
    font-weight: 600;
    padding: .35rem .75rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    transition: background .12s, transform .08s;
}
.btn-catatan-batal:active,
.btn-catatan-hapus:active,
.btn-catatan-simpan:active { transform: scale(.97); }

.btn-catatan-batal  { background: #f1f5f9; color: #64748b; margin-right: auto; }
.btn-catatan-batal:hover  { background: #e2e8f0; }

.btn-catatan-hapus  { background: #fee2e2; color: #dc2626; }
.btn-catatan-hapus:hover  { background: #fecaca; }

.btn-catatan-simpan { background: #285496; color: #fff; }
.btn-catatan-simpan:hover { background: #1d3d70; }
.btn-catatan-simpan:disabled { opacity: .65; cursor: not-allowed; }
</style>
@endsection