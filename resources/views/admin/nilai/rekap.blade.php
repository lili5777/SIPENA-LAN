@extends('admin.partials.layout')

@section('title', 'Rekap Nilai - ' . $jenisPelatihan->nama_pelatihan)

@section('content')

    <!-- Page Header -->
    <div class="page-header rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%); padding: 2rem;">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-chart-bar fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">Rekapan Nilai</h1>
                        <p class="text-white-50 mb-0">
                            <span class="badge bg-white text-primary fw-semibold me-2">{{ $jenisPelatihan->kode_pelatihan }}</span>
                            {{ $jenisPelatihan->nama_pelatihan }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('nilai.index', ['jenis' => $jenis]) }}" class="btn btn-light shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('nilai.rekap', ['jenis' => $jenis]) }}" method="GET">
                <div class="row g-2 align-items-end">

                    {{-- Filter Angkatan --}}
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

                    {{-- Filter Tahun --}}
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

                    {{-- Filter Kelompok --}}
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

                    {{-- Filter Kategori --}}
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

                    {{-- Filter Wilayah (muncul hanya jika FASILITASI) --}}
                    <div class="col-md-2 col-sm-6" id="filterWilayahWrapper"
                        style="{{ request('kategori') == 'FASILITASI' ? '' : 'display:none' }}">
                        <label class="form-label small text-muted mb-1">
                            <i class="fas fa-map-marker-alt me-1"></i> Wilayah
                        </label>
                        <input type="text"
                            name="wilayah"
                            id="filterWilayah"
                            class="form-control form-control-sm"
                            list="wilayahDatalistRekap"
                            placeholder="Ketik wilayah..."
                            value="{{ request('wilayah') }}">
                        <datalist id="wilayahDatalistRekap">
                            @foreach($wilayahList as $w)
                                <option value="{{ $w }}">
                            @endforeach
                        </datalist>
                    </div>

                    {{-- Cari --}}
                    <div class="col-md-2 col-sm-8">
                        <label class="form-label small text-muted mb-1">
                            <i class="fas fa-search me-1"></i> Cari
                        </label>
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Nama atau NIP..." value="{{ request('search') }}">
                    </div>

                    {{-- Tombol --}}
                    <div class="col-md-2 col-sm-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('nilai.rekap', ['jenis' => $jenis]) }}"
                                class="btn btn-outline-secondary btn-sm flex-fill">
                                <i class="fas fa-redo me-1"></i>
                            </a>
                        </div>
                    </div>

                </div>

                {{-- Badge filter aktif --}}
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

    <!-- Tabel Rekap -->
    <div class="card border-0 shadow-lg overflow-hidden">
        <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0 fw-semibold">
                <i class="fas fa-table me-2" style="color: #285496;"></i> Rekap Nilai Peserta
                <span class="badge bg-primary ms-2">{{ count($rekapData) }}</span>
            </h5>
            <small class="text-muted">
                <i class="fas fa-hand-pointer me-1"></i> Klik angka untuk melihat detail
            </small>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 rekap-table">
                    <thead>
                        <tr class="table-light">
                            <th class="ps-4" style="min-width:44px">No</th>
                            <th style="min-width:200px">Peserta</th>
                            <th style="min-width:55px">NDH</th>
                            <th style="min-width:140px">Kelompok</th>
                            @foreach($jenisNilaiList as $jn)
                                <th class="text-center" style="min-width:120px">
                                    <div class="fw-semibold">{{ $jn->name }}</div>
                                    <small class="text-muted fw-normal">Bobot {{ $jn->bobot }}%</small>
                                </th>
                            @endforeach
                            <th class="text-center" style="min-width:100px">
                                <div class="fw-semibold">Total</div>
                                <small class="text-muted fw-normal">Maks. 100</small>
                            </th>
                            <th class="text-center" style="min-width:100px">Kelengkapan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekapData as $index => $row)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold small">{{ $row['nama'] }}</div>
                                    <small class="text-muted">{{ $row['nip'] }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $row['ndh'] ?? '-' }}</span>
                                </td>
                                <td>
                                    <small class="fw-semibold">{{ $row['kelompok'] ?? '-' }}</small>
                                </td>

                                @foreach($jenisNilaiList as $jn)
                                    @php
                                        $nilaiJn     = $row['nilai_per_jenis'][$jn->id] ?? null;
                                        $sumKonversi = $nilaiJn['sum_konversi'] ?? 0;
                                        $catatan     = $row['catatan'][$jn->id] ?? null;
                                        $adaNilai    = $sumKonversi > 0;
                                        $detailDataArr  = $nilaiJn['detail_indikator'] ?? [];
                                        $detailDataJson = htmlspecialchars(json_encode($detailDataArr), ENT_QUOTES, 'UTF-8');
                                    @endphp
                                    <td class="text-center">
                                        @if($adaNilai)
                                            <button type="button"
                                                class="btn-nilai-detail"
                                                data-peserta="{{ $row['nama'] }}"
                                                data-jenis="{{ $jn->name }}"
                                                data-bobot="{{ $jn->bobot }}"
                                                data-sum="{{ number_format($sumKonversi, 2) }}"
                                                data-catatan="{{ $catatan ?? '' }}"
                                                data-detail="{{ $detailDataJson }}">
                                                {{ number_format($sumKonversi, 2) }}
                                            </button>
                                            @if($catatan)
                                                <i class="fas fa-sticky-note text-warning ms-1" style="font-size:.65rem;"
                                                    title="Ada catatan"></i>
                                            @endif
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                @endforeach

                                <td class="text-center">
                                    @php
                                        $total   = $row['total_nilai'];
                                        $bgClass = $total >= 80
                                            ? 'bg-success'
                                            : ($total >= 60 ? 'bg-warning text-dark' : ($total > 0 ? 'bg-danger' : 'bg-secondary'));

                                        $nilaiPerJenisJson = json_encode(
                                            collect($row['nilai_per_jenis'])->map(fn($v) => [
                                                'sum_konversi'     => $v['sum_konversi'],
                                                'detail_indikator' => $v['detail_indikator'] ?? [],
                                            ])
                                        );
                                        $jenisListJson = json_encode(
                                            $jenisNilaiList->map(fn($jn) => [
                                                'id'    => $jn->id,
                                                'name'  => $jn->name,
                                                'bobot' => $jn->bobot,
                                            ])->values()
                                        );
                                    @endphp
                                    <button type="button"
                                        class="btn-total-detail {{ $bgClass }}"
                                        data-peserta="{{ $row['nama'] }}"
                                        data-total="{{ number_format($total, 2) }}"
                                        data-nilai-per-jenis="{{ htmlspecialchars($nilaiPerJenisJson, ENT_QUOTES) }}"
                                        data-jenis-list="{{ htmlspecialchars($jenisListJson, ENT_QUOTES) }}">
                                        {{ number_format($total, 2) }}
                                    </button>
                                </td>

                                <td class="text-center">
                                    @php
                                        $kel    = $row['kelengkapan'];
                                        $pColor = $kel >= 100 ? 'bg-success' : ($kel > 0 ? 'bg-primary' : 'bg-secondary');
                                    @endphp
                                    <div class="d-flex align-items-center gap-1 justify-content-center">
                                        <div class="progress flex-grow-1" style="height:6px; border-radius:3px; min-width:44px;">
                                            <div class="progress-bar {{ $pColor }}" style="width:{{ $kel }}%"></div>
                                        </div>
                                        <small class="fw-semibold" style="min-width:32px;">{{ $kel }}%</small>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 5 + $jenisNilaiList->count() }}" class="text-center py-5">
                                    <i class="fas fa-chart-bar fa-4x mb-3" style="color: #e9ecef;"></i>
                                    <h5 class="text-muted mb-2">Belum ada data nilai</h5>
                                    <p class="text-muted">Belum ada peserta yang dinilai</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    @if(count($rekapData) > 0)
                    <tfoot>
                        <tr class="table-light fw-semibold">
                            <td colspan="4" class="ps-4 text-end text-muted small">Rata-rata:</td>
                            @foreach($jenisNilaiList as $jn)
                                @php
                                    $avgJn = collect($rekapData)->avg(fn($r) => $r['nilai_per_jenis'][$jn->id]['sum_konversi'] ?? 0);
                                @endphp
                                <td class="text-center">
                                    <span class="text-primary fw-bold">{{ number_format($avgJn, 2) }}</span>
                                </td>
                            @endforeach
                            @php $avgTotal = collect($rekapData)->avg('total_nilai'); @endphp
                            <td class="text-center">
                                <span class="badge bg-primary px-2 py-1">{{ number_format($avgTotal, 2) }}</span>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- ===== MODAL DETAIL JENIS NILAI ===== --}}
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg overflow-hidden">
                <div class="modal-header border-0 p-0">
                    <div class="w-100 px-4 py-3"
                        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-white fw-bold mb-0" id="modalDetailTitle">Detail Nilai</h6>
                                <small class="text-white-50" id="modalDetailSubtitle"></small>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                    </div>
                </div>
                <div class="modal-body p-4" id="modalDetailBody"></div>
                <div class="modal-footer border-0 bg-light py-2 px-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MODAL TOTAL NILAI ===== --}}
    <div class="modal fade" id="modalTotal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg overflow-hidden">
                <div class="modal-header border-0 p-0">
                    <div class="w-100 px-4 py-3"
                        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-white fw-bold mb-0" id="modalTotalTitle">Rincian Total Nilai</h6>
                                <small class="text-white-50" id="modalTotalSubtitle"></small>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                    </div>
                </div>
                <div class="modal-body p-4" id="modalTotalBody"></div>
                <div class="modal-footer border-0 bg-light py-2 px-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Toggle wilayah ────────────────────────────────────────
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

    // ── Modal detail & total ──────────────────────────────────
    const modalDetail = new bootstrap.Modal(document.getElementById('modalDetail'));
    const modalTotal  = new bootstrap.Modal(document.getElementById('modalTotal'));

    document.querySelectorAll('.btn-nilai-detail').forEach(btn => {
        btn.addEventListener('click', function () {
            const peserta  = this.dataset.peserta;
            const jenis    = this.dataset.jenis;
            const bobot    = this.dataset.bobot;
            const sum      = this.dataset.sum;
            const catatan  = this.dataset.catatan;
            const decodeHTML = str => { const txt = document.createElement('textarea'); txt.innerHTML = str; return txt.value; };
            const detail = JSON.parse(decodeHTML(this.dataset.detail || '[]'));

            document.getElementById('modalDetailTitle').textContent    = jenis;
            document.getElementById('modalDetailSubtitle').textContent = peserta;
            document.getElementById('modalDetailBody').innerHTML       = buildDetailHTML(detail, sum, bobot, catatan, jenis);
            modalDetail.show();
        });
    });

    document.querySelectorAll('.btn-total-detail').forEach(btn => {
        btn.addEventListener('click', function () {
            const peserta = this.dataset.peserta;
            const total   = this.dataset.total;
            const decodeHTML = str => { const txt = document.createElement('textarea'); txt.innerHTML = str; return txt.value; };
            const nilaiPerJenis = JSON.parse(decodeHTML(this.dataset.nilaiPerJenis || '{}'));
            const jenisList     = JSON.parse(decodeHTML(this.dataset.jenisList     || '[]'));

            document.getElementById('modalTotalTitle').textContent    = 'Rincian Total Nilai';
            document.getElementById('modalTotalSubtitle').textContent = peserta;
            document.getElementById('modalTotalBody').innerHTML       = buildTotalHTML(nilaiPerJenis, jenisList, total);
            modalTotal.show();
        });
    });

    function buildDetailHTML(detail, sum, bobot, catatan, jenisNama) {
        if (!detail || detail.length === 0) {
            return `<div class="text-center py-4 text-muted">
                        <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                        <p>Tidak ada detail indikator</p>
                    </div>`;
        }

        let html = `
            <div class="rekap-info-bar mb-4">
                <div class="row g-3 text-center">
                    <div class="col-4"><div class="rekap-stat-box">
                        <div class="rekap-stat-val text-primary">${sum}</div>
                        <div class="rekap-stat-label">Nilai Terbobot</div>
                    </div></div>
                    <div class="col-4"><div class="rekap-stat-box">
                        <div class="rekap-stat-val text-muted">${bobot}%</div>
                        <div class="rekap-stat-label">Bobot Jenis Nilai</div>
                    </div></div>
                    <div class="col-4"><div class="rekap-stat-box">
                        <div class="rekap-stat-val text-success">${detail.length}</div>
                        <div class="rekap-stat-label">Indikator</div>
                    </div></div>
                </div>
            </div>
            <div class="table-responsive mb-3">
                <table class="table table-sm detail-table mb-0">
                    <thead>
                        <tr class="table-light">
                            <th width="4%">No</th>
                            <th width="35%">Indikator</th>
                            <th class="text-center" width="15%">Bobot Ind.</th>
                            <th class="text-center" width="15%">Nilai Input</th>
                            <th class="text-center" width="31%">Cara Hitung → Kontribusi</th>
                        </tr>
                    </thead>
                    <tbody>`;

        let totalKontribusi = 0;
        detail.forEach((ind, i) => {
            const nilaiInput  = ind.nilai_input !== null && ind.nilai_input !== undefined
                ? parseFloat(ind.nilai_input) : null;
            const bobotInd    = parseFloat(ind.bobot_indikator);
            const kontribusi  = nilaiInput !== null
                ? ((nilaiInput / 100) * bobotInd).toFixed(2) : null;
            if (kontribusi !== null) totalKontribusi += parseFloat(kontribusi);

            html += `
                <tr>
                    <td class="text-muted">${i + 1}</td>
                    <td><div class="fw-semibold small">${ind.nama_indikator}</div></td>
                    <td class="text-center"><span class="badge bg-light text-dark border">${bobotInd}%</span></td>
                    <td class="text-center">
                        ${nilaiInput !== null
                            ? `<span class="fw-bold" style="color:#285496">${nilaiInput}</span>
                               <span class="text-muted small">/ 100</span>`
                            : `<span class="text-muted">—</span>`}
                    </td>
                    <td class="text-center">
                        ${kontribusi !== null
                            ? `<span class="formula-text">${nilaiInput}/100 × ${bobotInd}% = <strong class="text-success">${kontribusi}</strong></span>`
                            : `<span class="text-muted small">Belum dinilai</span>`}
                    </td>
                </tr>`;
        });

        html += `
                    </tbody>
                    <tfoot>
                        <tr class="table-light fw-bold">
                            <td colspan="4" class="text-end">Total kontribusi:</td>
                            <td class="text-center">
                                <span class="badge bg-primary px-2 py-1">${totalKontribusi.toFixed(2)}</span>
                                <small class="text-muted d-block" style="font-size:.68rem;">dari maks. ${bobot}%</small>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>`;

        if (catatan) {
            html += `
            <div class="catatan-box">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-sticky-note text-warning me-2"></i>
                    <span class="fw-semibold small">Catatan untuk ${jenisNama}</span>
                </div>
                <p class="mb-0 small" style="white-space:pre-wrap; line-height:1.6;">${catatan}</p>
            </div>`;
        }

        return html;
    }

    function buildTotalHTML(nilaiPerJenis, jenisList, total) {
        let html = `
            <div class="text-center mb-4">
                <div class="total-circle mx-auto">
                    <div class="total-val">${total}</div>
                    <div class="total-label">Total Nilai</div>
                </div>
                <small class="text-muted">dari maksimal 100</small>
            </div>
            <div class="mb-4">
                <div class="d-flex justify-content-between mb-1">
                    <small class="text-muted fw-semibold">Progress Total</small>
                    <small class="fw-bold" style="color:#285496">${total} / 100</small>
                </div>
                <div class="progress" style="height:10px; border-radius:6px;">
                    <div class="progress-bar ${parseFloat(total) >= 80 ? 'bg-success' : parseFloat(total) >= 60 ? 'bg-warning' : 'bg-danger'}"
                        style="width:${Math.min(parseFloat(total), 100)}%; border-radius:6px;"></div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm detail-table mb-0">
                    <thead>
                        <tr class="table-light">
                            <th>Jenis Nilai</th>
                            <th class="text-center">Bobot</th>
                            <th class="text-center">Nilai Terbobot</th>
                            <th class="text-center">% dari Bobot</th>
                            <th style="min-width:120px">Kontribusi</th>
                        </tr>
                    </thead>
                    <tbody>`;

        jenisList.forEach(jn => {
            const nilaiJn  = nilaiPerJenis[jn.id];
            const sumK     = nilaiJn ? parseFloat(nilaiJn.sum_konversi) : 0;
            const bobot    = parseFloat(jn.bobot);
            const persenJn = bobot > 0 ? Math.round((sumK / bobot) * 100) : 0;
            const barColor = persenJn >= 80 ? 'bg-success' : persenJn >= 50 ? 'bg-primary' : (sumK > 0 ? 'bg-warning' : 'bg-secondary');

            html += `
                <tr>
                    <td class="fw-semibold small">${jn.name}</td>
                    <td class="text-center"><span class="badge bg-light text-dark border">${bobot}%</span></td>
                    <td class="text-center">
                        <span class="fw-bold ${sumK > 0 ? '' : 'text-muted'}" style="${sumK > 0 ? 'color:#285496' : ''}">
                            ${sumK > 0 ? sumK.toFixed(2) : '—'}
                        </span>
                    </td>
                    <td class="text-center">
                        <small class="${persenJn > 0 ? 'fw-semibold' : 'text-muted'}">
                            ${persenJn > 0 ? persenJn + '%' : '—'}
                        </small>
                    </td>
                    <td>
                        <div class="progress" style="height:6px; border-radius:3px;">
                            <div class="progress-bar ${barColor}" style="width:${Math.min(persenJn,100)}%"></div>
                        </div>
                    </td>
                </tr>`;
        });

        html += `
                    </tbody>
                    <tfoot>
                        <tr class="table-light fw-bold">
                            <td colspan="2" class="text-end">Total:</td>
                            <td class="text-center">
                                <span class="badge ${parseFloat(total) >= 80 ? 'bg-success' : parseFloat(total) >= 60 ? 'bg-warning text-dark' : 'bg-danger'} px-2 py-1">
                                    ${total}
                                </span>
                            </td>
                            <td colspan="2" class="text-muted small">dari maks. 100</td>
                        </tr>
                    </tfoot>
                </table>
            </div>`;

        return html;
    }

});
</script>

<style>
    .page-header { box-shadow: 0 4px 20px rgba(40,84,150,.15); }

    .rekap-table th {
        border-bottom:2px solid rgba(40,84,150,.1); font-weight:600;
        color:#285496; background-color:#f8fafc;
        padding:.8rem 1rem; font-size:.95rem;
        vertical-align:middle; white-space:nowrap;
    }
    .rekap-table td { padding:.8rem 1rem; vertical-align:middle; border-bottom:1px solid #e9ecef; font-size:.95rem; }
    .rekap-table td .fw-bold { font-size:.95rem !important; }
    .rekap-table td small    { font-size:.85rem !important; }
    tfoot td { border-bottom:none !important; }
    tfoot .text-primary.fw-bold { font-size:1rem !important; }

    .form-label      { font-size:.92rem !important; }
    .form-select-sm,
    .form-control-sm { font-size:.92rem !important; height:36px; }
    .btn-sm          { font-size:.92rem !important; padding:.35rem .75rem; }
    .card-title      { font-size:1.1rem !important; }
    .card-header small { font-size:.88rem !important; }
    .badge { font-size:.82rem !important; }
    .rekap-table .progress { height:7px !important; }

    .btn-nilai-detail {
        background:none; border:none; padding:0;
        font-weight:700; font-size:1rem; color:#285496;
        cursor:pointer; border-bottom:1px dashed rgba(40,84,150,.4); transition:all .2s;
    }
    .btn-nilai-detail:hover { color:#1a3a6b; border-bottom-color:#285496; border-bottom-style:solid; }

    .btn-total-detail {
        border:none; border-radius:6px;
        padding:.4rem .85rem; font-weight:700;
        font-size:1rem; cursor:pointer; transition:all .2s; display:inline-block;
    }
    .btn-total-detail:hover { transform:translateY(-1px); box-shadow:0 4px 10px rgba(0,0,0,.15); }
    .btn-total-detail.bg-success  { background:#28a745 !important; color:white; }
    .btn-total-detail.bg-warning  { background:#ffc107 !important; color:#212529; }
    .btn-total-detail.bg-danger   { background:#dc3545 !important; color:white; }
    .btn-total-detail.bg-secondary{ background:#6c757d !important; color:white; }

    .rekap-stat-box { background:#f8fafc; border-radius:10px; padding:1rem; border:1px solid #e9ecef; }
    .rekap-stat-val   { font-size:1.5rem; font-weight:700; line-height:1.2; }
    .rekap-stat-label { font-size:.82rem; color:#6c757d; margin-top:.25rem; }

    .detail-table th {
        border-bottom:2px solid rgba(40,84,150,.1); font-weight:600;
        color:#285496; background-color:#f8fafc; padding:.65rem .8rem; font-size:.9rem;
    }
    .detail-table td  { padding:.65rem .8rem; font-size:.95rem; vertical-align:middle; }
    .detail-table tfoot td { border-bottom:none !important; background:#f8fafc; font-size:.92rem; }
    .formula-text { font-family:monospace; font-size:.88rem; color:#495057; }

    .catatan-box {
        background:rgba(245,158,11,.06); border:1px solid rgba(245,158,11,.25);
        border-radius:8px; padding:.9rem 1rem;
    }
    .catatan-box .small { font-size:.92rem !important; }

    .total-circle {
        width:120px; height:120px; border-radius:50%;
        background:linear-gradient(135deg,#285496,#3a6bc7);
        display:flex; flex-direction:column;
        align-items:center; justify-content:center;
        box-shadow:0 8px 24px rgba(40,84,150,.3); margin-bottom:.5rem;
    }
    .total-val   { color:white; font-size:1.75rem; font-weight:800; line-height:1.1; }
    .total-label { color:rgba(255,255,255,.75); font-size:.72rem; font-weight:600; letter-spacing:.5px; }

    /* Filter wilayah input */
    #filterWilayahWrapper .form-control-sm { border-color: rgba(40,84,150,.35); }
    #filterWilayahWrapper .form-control-sm:focus { border-color: #285496; box-shadow: 0 0 0 .15rem rgba(40,84,150,.15); }
</style>
@endsection