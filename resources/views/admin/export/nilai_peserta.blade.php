@extends('admin.partials.layout')

@section('title', 'Export Rekap Nilai Peserta')
@section('page-title', 'Export Rekap Nilai Peserta')

@section('styles')
<style>
    :root {
        --primary:     #285496;
        --primary-dk:  #1B3A6B;
        --primary-lt:  #3A6BC7;
        --success:     #10B981;
        --warning:     #F59E0B;
        --danger:      #EF4444;
        --info:        #3B82F6;
        --surface:     #F8FAFC;
        --border:      #E2E8F0;
        --text:        #1E293B;
        --muted:       #64748B;
    }

    /* ── Layout ─────────────────────────────────────── */
    .export-wrap {
        animation: fadeUp .5s ease both;
    }
    @keyframes fadeUp {
        from { opacity:0; transform:translateY(14px); }
        to   { opacity:1; transform:translateY(0); }
    }

    /* ── Card ────────────────────────────────────────── */
    .ex-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(40,84,150,.09);
        margin-bottom: 1.75rem;
        overflow: hidden;
    }
    .ex-card-header {
        display: flex;
        align-items: center;
        gap: .85rem;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, var(--primary-dk) 0%, var(--primary) 100%);
    }
    .ex-card-header .icon-wrap {
        width: 40px; height: 40px;
        background: rgba(255,255,255,.15);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.1rem;
        flex-shrink: 0;
    }
    .ex-card-header h5 {
        margin: 0; color: #fff;
        font-size: 1.05rem; font-weight: 700; letter-spacing: .02em;
    }
    .ex-card-header small {
        color: rgba(255,255,255,.65); font-size: .8rem;
    }
    .ex-card-body { padding: 1.5rem; }

    /* ── Filter grid ─────────────────────────────────── */
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.1rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.25rem;
    }
    .filter-grid .full-row { grid-column: 1 / -1; }

    .form-label {
        display: block;
        font-size: .85rem;
        font-weight: 600;
        color: var(--text);
        margin-bottom: .4rem;
    }
    .form-label i { color: var(--primary); margin-right: .35rem; }
    .badge-req  { background: var(--danger);  color:#fff; font-size:.65rem; padding:.15rem .4rem; border-radius:4px; margin-left:.3rem; vertical-align:middle; }
    .badge-opt  { background: var(--warning); color:#fff; font-size:.65rem; padding:.15rem .4rem; border-radius:4px; margin-left:.3rem; vertical-align:middle; }
    .badge-cond { background: var(--info);    color:#fff; font-size:.65rem; padding:.15rem .4rem; border-radius:4px; margin-left:.3rem; vertical-align:middle; }

    .form-select,
    .form-control {
        width: 100%;
        padding: .575rem 1rem;
        border: 2px solid var(--border);
        border-radius: 8px;
        font-size: .9rem;
        color: var(--text);
        background: #fff;
        transition: border-color .2s, box-shadow .2s;
        appearance: none;
    }
    .form-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%23285496' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right .75rem center;
    }
    .form-control[list] {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%23285496' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right .75rem center;
    }
    .form-select:focus,
    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(40,84,150,.12);
    }
    .form-select.is-active,
    .form-control.is-active {
        border-color: var(--success);
        background-color: rgba(16,185,129,.04);
    }
    .form-select.is-required { border-color: var(--danger) !important; }

    /* ── Wilayah field animasi masuk ─────────────────── */
    #wilayahGroup {
        display: none;
        animation: fadeUp .3s ease both;
    }
    #wilayahGroup.show { display: block; }

    /* ── Preview info ────────────────────────────────── */
    .preview-box {
        background: #EFF6FF;
        border: 1px solid #BFDBFE;
        border-left: 4px solid var(--primary);
        border-radius: 8px;
        padding: 1rem 1.1rem;
        font-size: .88rem;
        color: #1E40AF;
        display: none;
    }
    .preview-box.show { display: block; animation: fadeUp .3s ease; }
    .preview-box strong { color: var(--primary-dk); }
    .preview-col-list {
        display: flex; flex-wrap: wrap; gap: .4rem; margin-top: .6rem;
    }
    .preview-col-pill {
        background: #DBEAFE; color: #1D4ED8;
        border-radius: 20px; padding: .2rem .65rem; font-size: .78rem;
        font-weight: 600; white-space: nowrap;
    }
    .preview-col-pill.identity { background: #EDE9FE; color: #5B21B6; }
    .preview-col-pill.total    { background: #D1FAE5; color: #065F46; }

    /* ── Tombol export ───────────────────────────────── */
    .btn-export-main {
        display: inline-flex; align-items: center; gap: .6rem;
        background: linear-gradient(135deg, var(--success), #34D399);
        color: #fff; border: none; border-radius: 8px;
        padding: .7rem 2rem; font-size: .95rem; font-weight: 700;
        cursor: pointer; transition: all .25s; min-width: 180px;
        justify-content: center;
    }
    .btn-export-main:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(16,185,129,.3);
        background: linear-gradient(135deg, #0DA271, var(--success));
    }
    .btn-export-main:disabled {
        background: linear-gradient(135deg, #94A3B8, #CBD5E1);
        cursor: not-allowed; transform: none; box-shadow: none;
    }

    /* ── Info card ───────────────────────────────────── */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    .info-item {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: .9rem 1rem;
    }
    .info-item h6 {
        font-size: .88rem; font-weight: 700;
        color: var(--primary); margin-bottom: .5rem;
        display: flex; align-items: center; gap: .4rem;
    }
    .info-item ul { margin:0; padding-left:1.1rem; font-size:.83rem; color: var(--muted); }
    .info-item li { margin-bottom: .25rem; line-height: 1.5; }

    /* ── Struktur kolom excel preview ───────────────── */
    .excel-preview {
        overflow-x: auto;
        margin-top: .75rem;
    }
    .excel-preview table {
        border-collapse: collapse;
        font-size: .75rem;
        white-space: nowrap;
    }
    .excel-preview th, .excel-preview td {
        border: 1px solid #CBD5E1;
        padding: .3rem .6rem;
        text-align: center;
    }
    .excel-preview .th-identity {
        background: var(--primary-dk); color: #fff;
    }
    .excel-preview .th-jenis {
        background: var(--primary); color: #fff;
    }
    .excel-preview .th-ind {
        background: var(--primary-lt); color: #fff;
    }
    .excel-preview .th-total {
        background: #10803B; color: #fff;
    }
    .excel-preview .td-data { background: var(--surface); color: var(--muted); font-style: italic; }

    /* ── Responsive ──────────────────────────────────── */
    @media (max-width: 768px) {
        .filter-grid { grid-template-columns: 1fr; }
        .ex-card-body { padding: 1rem; }
        .btn-export-main { width: 100%; }
    }
</style>
@endsection

@section('content')
<div class="export-wrap">

    {{-- Flash messages --}}
    @if(session('error'))
        <div class="alert" style="background:#FEE2E2;border-left:5px solid #EF4444;color:#7F1D1D;padding:1rem 1.25rem;border-radius:8px;margin-bottom:1.25rem;">
            <i class="fas fa-exclamation-triangle me-2"></i> <strong>Error!</strong> {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert" style="background:#DCFCE7;border-left:5px solid #10B981;color:#14532D;padding:1rem 1.25rem;border-radius:8px;margin-bottom:1.25rem;">
            <i class="fas fa-check-circle me-2"></i> <strong>Sukses!</strong> {{ session('success') }}
        </div>
    @endif

    {{-- ===== FORM EXPORT ===== --}}
    <div class="ex-card">
        <div class="ex-card-header">
            <div class="icon-wrap"><i class="fas fa-file-export"></i></div>
            <div>
                <h5>Export Rekap Nilai Peserta</h5>
                <small>Download rekap nilai ke format Excel (.xlsx)</small>
            </div>
        </div>
        <div class="ex-card-body">
            <form action="{{ route('admin.export.nilai.download') }}" method="GET" id="exportForm">

                <div class="filter-grid">

                    {{-- Jenis Pelatihan (WAJIB) --}}
                    <div>
                        <label class="form-label">
                            <i class="fas fa-graduation-cap"></i> Jenis Pelatihan
                            <span class="badge-req">WAJIB</span>
                        </label>
                        <select name="jenis_pelatihan" id="jenisPelatihan" class="form-select" required>
                            <option value="">-- Pilih Jenis Pelatihan --</option>
                            @foreach($jenisPelatihan as $jp)
                                <option value="{{ $jp->id }}"
                                    data-nama="{{ $jp->nama_pelatihan }}"
                                    {{ request('jenis_pelatihan') == $jp->id ? 'selected' : '' }}>
                                    {{ $jp->nama_pelatihan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kategori (OPSIONAL) --}}
                    <div>
                        <label class="form-label">
                            <i class="fas fa-tag"></i> Kategori
                            <span class="badge-opt">OPSIONAL</span>
                        </label>
                        <select name="kategori" id="kategori" class="form-select">
                            <option value="">Semua Kategori</option>
                            <option value="PNBP"       {{ request('kategori') == 'PNBP'       ? 'selected' : '' }}>PNBP</option>
                            <option value="FASILITASI" {{ request('kategori') == 'FASILITASI' ? 'selected' : '' }}>FASILITASI</option>
                        </select>
                    </div>

                    {{-- Wilayah (muncul hanya jika FASILITASI) --}}
                    <div id="wilayahGroup" class="{{ request('kategori') == 'FASILITASI' ? 'show' : '' }}">
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt"></i> Wilayah
                            <span class="badge-cond">KONDISIONAL</span>
                        </label>
                        <input type="text"
                               name="wilayah"
                               id="wilayah"
                               class="form-control {{ request('wilayah') ? 'is-active' : '' }}"
                               list="wilayahList"
                               placeholder="Ketik nama wilayah..."
                               value="{{ request('wilayah') }}">
                        <datalist id="wilayahList">
                            <option value="DKI Jakarta">
                            <option value="Jawa Barat">
                            <option value="Jawa Tengah">
                            <option value="Jawa Timur">
                            <option value="Banten">
                            <option value="Bali">
                            <option value="Sumatera Utara">
                            <option value="Sumatera Barat">
                            <option value="Sumatera Selatan">
                            <option value="Kalimantan Timur">
                            <option value="Kalimantan Selatan">
                            <option value="Sulawesi Selatan">
                            <option value="Sulawesi Utara">
                            <option value="Papua">
                            <option value="Papua Barat">
                            <option value="Nusa Tenggara Barat">
                            <option value="Nusa Tenggara Timur">
                        </datalist>
                    </div>

                    {{-- Angkatan --}}
                    <div>
                        <label class="form-label">
                            <i class="fas fa-layer-group"></i> Angkatan
                            <span class="badge-opt">OPSIONAL</span>
                        </label>
                        <select name="angkatan" id="angkatan" class="form-select">
                            <option value="">Semua Angkatan</option>
                            @foreach($angkatanRomawi as $romawi)
                                <option value="{{ $romawi }}"
                                    {{ request('angkatan') == $romawi ? 'selected' : '' }}>
                                    Angkatan {{ $romawi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tahun --}}
                    <div>
                        <label class="form-label">
                            <i class="fas fa-calendar-alt"></i> Tahun
                            <span class="badge-opt">OPSIONAL</span>
                        </label>
                        <select name="tahun" id="tahun" class="form-select">
                            <option value="">Semua Tahun</option>
                            @foreach(array_reverse($tahunList) as $tahun)
                                <option value="{{ $tahun }}"
                                    {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kelompok --}}
                    <div>
                        <label class="form-label">
                            <i class="fas fa-users"></i> Kelompok
                            <span class="badge-opt">OPSIONAL</span>
                        </label>
                        <select name="kelompok" id="kelompok" class="form-select">
                            <option value="">Semua Kelompok</option>
                            @foreach($kelompokList as $k)
                                <option value="{{ $k }}"
                                    {{ request('kelompok') == $k ? 'selected' : '' }}>
                                    Kelompok {{ $k }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Search --}}
                    <div>
                        <label class="form-label">
                            <i class="fas fa-search"></i> Cari Peserta
                            <span class="badge-opt">OPSIONAL</span>
                        </label>
                        <input type="text" name="search" id="search" class="form-control"
                            placeholder="Nama atau NIP..." value="{{ request('search') }}">
                    </div>

                    {{-- Tombol --}}
                    <div class="full-row" style="display:flex; align-items:flex-end; justify-content:flex-end; gap:1rem; flex-wrap:wrap;">
                        <button type="submit" class="btn-export-main" id="exportBtn" disabled>
                            <i class="fas fa-file-excel"></i> Download Excel
                        </button>
                    </div>
                </div>

            </form>

            {{-- Preview struktur kolom --}}
            <div class="preview-box mt-3" id="previewBox">
                <strong><i class="fas fa-table me-1"></i> Struktur Kolom Excel</strong>
                <div id="previewContent"></div>
            </div>
        </div>
    </div>

    {{-- ===== INFO CARD ===== --}}
    <div class="ex-card">
        <div class="ex-card-header">
            <div class="icon-wrap"><i class="fas fa-info-circle"></i></div>
            <div>
                <h5>Informasi Export</h5>
                <small>Panduan penggunaan fitur export rekap nilai</small>
            </div>
        </div>
        <div class="ex-card-body">
            <div class="info-grid">
                <div class="info-item">
                    <h6><i class="fas fa-columns"></i> Kolom Identitas (Tetap)</h6>
                    <ul>
                        <li><strong>No, NDH</strong> — Nomor urut & NDH peserta</li>
                        <li><strong>Nama Peserta</strong> — Nama lengkap</li>
                        <li><strong>NIP / NRP</strong> — Diformat sebagai teks</li>
                        <li><strong>Jabatan</strong> — Dari data kepegawaian</li>
                        <li><strong>Instansi</strong> — Asal instansi peserta</li>
                        <li><strong>Pangkat, Golongan</strong> — Dari kepegawaian</li>
                    </ul>
                </div>
                <div class="info-item">
                    <h6><i class="fas fa-star"></i> Kolom Nilai (Dinamis)</h6>
                    <ul>
                        <li>Header <strong>baris 1</strong>: Nama jenis nilai + bobot %</li>
                        <li>Header <strong>baris 2</strong>: Nama indikator + bobot %</li>
                        <li>Jenis nilai di-<strong>merge</strong> sesuai jumlah indikatornya</li>
                        <li>Isi cell: <strong>nilai input</strong> peserta (0–100)</li>
                        <li>Cell belum dinilai → <span style="background:#FFF3CD;padding:.1rem .4rem;border-radius:3px;">warna kuning</span></li>
                    </ul>
                </div>
                <div class="info-item">
                    <h6><i class="fas fa-calculator"></i> Kolom Total & Rata-rata</h6>
                    <ul>
                        <li><strong>Total Nilai</strong> — Nilai terbobot keseluruhan (maks. 100)</li>
                        <li><span style="background:#28a745;color:#fff;padding:.1rem .4rem;border-radius:3px;">Hijau</span> ≥ 80 |
                            <span style="background:#ffc107;color:#212529;padding:.1rem .4rem;border-radius:3px;">Kuning</span> 60–79 |
                            <span style="background:#dc3545;color:#fff;padding:.1rem .4rem;border-radius:3px;">Merah</span> &lt; 60</li>
                        <li><strong>Baris terakhir</strong>: rata-rata per indikator & total</li>
                        <li><strong>Freeze pane</strong> aktif (kolom A-B + baris header)</li>
                    </ul>
                </div>
                <div class="info-item">
                    <h6><i class="fas fa-filter"></i> Filter</h6>
                    <ul>
                        <li><strong>Jenis Pelatihan</strong> wajib dipilih sebelum export</li>
                        <li><strong>Kategori FASILITASI</strong> memunculkan filter Wilayah</li>
                        <li>Filter lain bersifat opsional</li>
                        <li>Nama file: <code>rekap-nilai-[jenis]-[tanggal].xlsx</code></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Preview jenis nilai (server-side, jika sudah ada pilihan) --}}
    @if($jenisNilaiList->count() > 0)
    <div class="ex-card">
        <div class="ex-card-header">
            <div class="icon-wrap"><i class="fas fa-table"></i></div>
            <div>
                <h5>Preview Struktur Kolom Excel</h5>
                <small>Berdasarkan jenis pelatihan yang dipilih</small>
            </div>
        </div>
        <div class="ex-card-body">
            <div class="excel-preview">
                <table>
                    {{-- Baris 1 --}}
                    <tr>
                        <th class="th-identity" rowspan="2">No</th>
                        <th class="th-identity" rowspan="2">NDH</th>
                        <th class="th-identity" rowspan="2">Nama Peserta</th>
                        <th class="th-identity" rowspan="2">NIP/NRP</th>
                        <th class="th-identity" rowspan="2">Jabatan</th>
                        <th class="th-identity" rowspan="2">Instansi</th>
                        <th class="th-identity" rowspan="2">Pangkat</th>
                        <th class="th-identity" rowspan="2">Golongan</th>
                        @foreach($jenisNilaiList as $jn)
                            <th class="th-jenis" colspan="{{ $jn->indikator_nilai_count }}">
                                {{ $jn->name }} ({{ $jn->bobot }}%)
                            </th>
                        @endforeach
                        <th class="th-total" rowspan="2">TOTAL</th>
                    </tr>
                    {{-- Baris 2 (indikator) --}}
                    <tr>
                        @foreach($jenisNilaiList as $jn)
                            @foreach($jn->indikatorNilai as $ind)
                                <th class="th-ind">{{ $ind->name }} ({{ $ind->bobot }}%)</th>
                            @endforeach
                        @endforeach
                    </tr>
                    {{-- Contoh baris data --}}
                    <tr>
                        <td class="td-data">1</td>
                        <td class="td-data">001</td>
                        <td class="td-data">Nama Peserta…</td>
                        <td class="td-data">199001012020…</td>
                        <td class="td-data">Jabatan…</td>
                        <td class="td-data">Instansi…</td>
                        <td class="td-data">Penata Tk.I</td>
                        <td class="td-data">III/d</td>
                        @foreach($jenisNilaiList as $jn)
                            @foreach($jn->indikatorNilai as $ind)
                                <td class="td-data">0–100</td>
                            @endforeach
                        @endforeach
                        <td class="td-data" style="background:#D1FAE5;color:#065F46;font-weight:700;">0–100</td>
                    </tr>
                </table>
            </div>
            <small class="text-muted mt-2 d-block">
                <i class="fas fa-info-circle me-1"></i>
                Total {{ $jenisNilaiList->sum('indikator_nilai_count') }} kolom indikator +
                8 kolom identitas + 1 kolom total =
                <strong>{{ $jenisNilaiList->sum('indikator_nilai_count') + 9 }} kolom</strong>
            </small>
        </div>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const jenisSel    = document.getElementById('jenisPelatihan');
    const kategoriSel = document.getElementById('kategori');
    const wilayahGrp  = document.getElementById('wilayahGroup');
    const wilayahInp  = document.getElementById('wilayah');
    const exportBtn   = document.getElementById('exportBtn');
    const previewBox  = document.getElementById('previewBox');
    const form        = document.getElementById('exportForm');

    const serverJenisNilai = @json($jenisNilaiList);

    // ── Toggle wilayah field ──────────────────────────
    function toggleWilayah() {
        if (kategoriSel.value === 'FASILITASI') {
            wilayahGrp.classList.add('show');
        } else {
            wilayahGrp.classList.remove('show');
            wilayahInp.value = '';
            wilayahInp.classList.remove('is-active');
        }
    }
    toggleWilayah(); // jalankan saat load
    kategoriSel.addEventListener('change', toggleWilayah);

    // ── Enable/disable export button ──────────────────
    function checkExportReady() {
        if (jenisSel.value) {
            exportBtn.disabled = false;
            jenisSel.classList.add('is-active');
            jenisSel.classList.remove('is-required');
        } else {
            exportBtn.disabled = true;
            jenisSel.classList.remove('is-active');
        }
    }
    checkExportReady();
    jenisSel.addEventListener('change', function () {
        checkExportReady();
        previewBox.classList.remove('show');
    });

    // ── Highlight filter aktif ────────────────────────
    ['angkatan', 'tahun', 'kelompok', 'search', 'kategori'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        const check = () => {
            if (el.value && el.value !== '') el.classList.add('is-active');
            else el.classList.remove('is-active');
        };
        check();
        el.addEventListener('change', check);
        el.addEventListener('input', check);
    });

    // Highlight wilayah input secara terpisah
    wilayahInp.addEventListener('input', function () {
        if (this.value.trim()) this.classList.add('is-active');
        else this.classList.remove('is-active');
    });

    // ── Form submit ───────────────────────────────────
    form.addEventListener('submit', function (e) {
        if (!jenisSel.value) {
            e.preventDefault();
            jenisSel.classList.add('is-required');
            jenisSel.focus();
            return false;
        }

        // Jika kategori bukan FASILITASI, pastikan wilayah dikosongkan
        if (kategoriSel.value !== 'FASILITASI') {
            wilayahInp.value = '';
        }

        exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        exportBtn.disabled  = true;

        setTimeout(() => {
            exportBtn.innerHTML = '<i class="fas fa-file-excel"></i> Download Excel';
            exportBtn.disabled  = false;
        }, 12000);
    });
});
</script>
@endsection