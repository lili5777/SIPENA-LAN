<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Daftar Hadir Peserta</title>
    <style>
        @page {
            margin: 15mm 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 0;
            padding-top: 45mm;
        }

        .header {
            position: fixed;
            top: -15mm;
            left: 0;
            right: 0;
            height: 40mm;
        }

        .header-content {
            display: flex;
            align-items: flex-start;
            padding: 5mm 0;
        }

        .logo {
            width: 100%;
            height: 100%;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .header-text {
            flex: 1;
            text-align: center;
        }

        .header h3 {
            margin: 0 0 3px 0;
            font-size: 12pt;
            font-weight: bold;
        }

        .header h4 {
            margin: 0 0 2px 0;
            font-size: 11pt;
            font-weight: bold;
        }

        .header p {
            margin: 0 0 2px 0;
            font-size: 10pt;
        }

        .content {
            margin-top: 0;
        }

        .info-section {
            margin-bottom: 10px;
            font-size: 9pt;
        }

        .info-row {
            margin: 2px 0;
            display: flex;
        }

        .info-label {
            display: inline-block;
            width: 180px;
            font-weight: bold;
        }

        .info-separator {
            margin: 0 5px;
        }

        .info-value {
            flex: 1;
        }

        table.peserta {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 9pt;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
        }

        table.peserta th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 5px 3px;
            font-weight: bold;
            text-align: center;
        }

        table.peserta td {
            border: 1px solid #000;
            padding: 8px 4px;
            vertical-align: middle;
        }

        table.peserta td.no {
            text-align: center;
            width: 30px;
        }

        table.peserta td.ndh {
            text-align: center;
            width: 35px;
        }

        table.peserta td.nama {
            width: 35%;
            text-align: left;
        }

        table.peserta td.instansi {
            width: 30%;
            text-align: center;
        }

        table.peserta td.ttd {
            width: 15%;
            height: 40px;
            text-align: left;
            padding-left: 8px;
            vertical-align: middle;
        }

        table.peserta td.ket {
            width: 8%;
            text-align: center;
        }

        .footer-section {
            margin-top: 15px;
            page-break-inside: avoid;
        }

        .footer-table {
            width: 100%;
            font-size: 9pt;
        }

        .footer-table td {
            vertical-align: top;
            padding: 5px;
        }

        .signature-box {
            text-align: center;
        }

        .keterangan-box {
            border: 1px solid #000;
            padding: 8px;
            margin-top: 5px;
            font-size: 9pt;
        }

        .keterangan-box p {
            margin: 3px 0;
        }

        .keterangan-row {
            display: flex;
            margin: 2px 0;
        }

        .keterangan-label {
            width: 140px;
        }

        .keterangan-separator {
            width: 10px;
            text-align: center;
        }

        .keterangan-value {
            flex: 1;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <!-- Header akan muncul di setiap halaman -->
    <div class="header">
        <div class="header-content">
            <div class="logo">
                <img src="{{ public_path('gambar/lan_header.JPG') }}" alt="Logo LAN" style="width:80%; height:80%;">
            </div>
            <div class="header-text">
                <h3>DAFTAR HADIR PESERTA</h3>
                <h4>{{ strtoupper($jenis_pelatihan) }} ANGKATAN {{ $angkatan }}</h4>
                <p>PUSAT PEMBELAJARAN DAN STRATEGI KEBIJAKAN MANAJEMEN PEMERINTAHAN</p>
                <p><strong>LEMBAGA ADMINISTRASI NEGARA</strong></p>
                <p>MAKASSAR</p>
            </div>
        </div>
    </div>

    <div class="content">
        <!-- Informasi Sesi -->
         <div class="info-section">
            <div class="info-row">
                <span class="info-label">HARI/TANGGAL</span>
                <span class="info-separator">:</span>
                <span class="info-value">{{ $hari_tanggal ?: '.........................................................' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">WAKTU</span>
                <span class="info-separator">:</span>
                <span class="info-value">{{ $waktu ?: '.........................................................' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">MATERI</span>
                <span class="info-separator">:</span>
                <span class="info-value">{{ $materi ?: '.........................................................' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">NARASUMBER/FASILITATOR</span>
                <span class="info-separator">:</span>
                <span class="info-value">{{ $narasumber ?: '.........................................................' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">SESI</span>
                <span class="info-separator">:</span>
                <span class="info-value">.........................................................</span>
            </div>
        </div>

        <!-- Tabel Peserta -->
        <table class="peserta">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama/NIP</th>
                    <th>NDH</th>
                    <th>Instansi</th>
                    <th>Tanda Tangan</th>
                    <th>Ket.</th>
                </tr>
            </thead>
            <tbody>
                @php
$perPage = 20;
$totalPeserta = count($peserta);
$totalPages = ceil($totalPeserta / $perPage);
                @endphp

                @for($page = 0; $page < $totalPages; $page++)
                    @php
    $start = $page * $perPage;
    $end = min($start + $perPage, $totalPeserta);
                    @endphp

                    @for($i = $start; $i < $end; $i++)
                        <tr>
                            <td class="no">{{ $i + 1 }}</td>
                            <td class="nama">
                                {{ $peserta[$i]['nama'] ?? '' }}<br>
                                <small>{{ $peserta[$i]['nip'] ?? '' }}</small>
                            </td>
                            <td class="ndh"><strong>{{ $peserta[$i]['ndh'] ?? str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</strong>
                            </td>
                            <td class="instansi">{{ $peserta[$i]['instansi'] ?? '' }}</td>
                            <td class="ttd">{{ $i + 1 }}. ............................</td>
                            <td class="ket"></td>
                        </tr>
                    @endfor

                    @if($page < $totalPages - 1)
                            </tbody>
                        </table>
                        <div class="page-break"></div>

                        <!-- Tabel dilanjutkan di halaman berikutnya -->
                        <table class="peserta">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama/NIP</th>
                                    <th>NDH</th>
                                    <th>Instansi</th>
                                    <th>Tanda Tangan</th>
                                    <th>Ket.</th>
                                </tr>
                            </thead>
                            <tbody>
                    @endif
                @endfor
            </tbody>
        </table>

        <!-- Footer Section -->
        <div class="footer-section">
            <table class="footer-table">
                <tr>
                    <td style="width: 25%;">
                        <strong>Penanggung Jawab</strong><br><br>
                        ..............................<br><br>
                        <strong>Evaluasi Penyelenggara</strong><br><br>
                        ..............................
                    </td>
                    <td style="width: 25%;">
                        <strong>Evaluator Pembelajaran</strong><br>
                        1. ..............................<br>
                        2. ..............................<br>
                        3. ..............................<br>
                        4. ..............................
                    </td>
                    <td style="width: 25%;">
                        <strong>Fasilitator</strong><br><br>
                        ..............................<br><br>
                        ..............................<br><br>
                        ..............................<br><br>
                        ..............................
                    </td>
                    <td style="width: 25%;">
                        <div class="signature-box">
                            Makassar, {{ $tanggal_ttd }}<br><br>
                            <strong>Ketua Squad {{ $jenis_pelatihan }} ANG {{ $angkatan }}</strong>
                            <br><br><br><br>
                            ..............................
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Keterangan -->
            <div class="keterangan-box">
                <p><strong>Keterangan:</strong></p>
                <div class="keterangan-row">
                    <span class="keterangan-label">Jumlah Peserta</span>
                    <span class="keterangan-separator">:</span>
                    <span class="keterangan-value">{{ count($peserta) }} orang</span>
                </div>
                <div class="keterangan-row">
                    <span class="keterangan-label">Tanpa Keterangan</span>
                    <span class="keterangan-separator">:</span>
                    <span class="keterangan-value">.............. orang</span>
                </div>
                <div class="keterangan-row">
                    <span class="keterangan-label">Sakit</span>
                    <span class="keterangan-separator">:</span>
                    <span class="keterangan-value">.............. orang</span>
                </div>
                <div class="keterangan-row">
                    <span class="keterangan-label">Izin</span>
                    <span class="keterangan-separator">:</span>
                    <span class="keterangan-value">.............. orang</span>
                </div>
                <div class="keterangan-row">
                    <span class="keterangan-label">Terlambat</span>
                    <span class="keterangan-separator">:</span>
                    <span class="keterangan-value">.............. orang</span>
                </div>
                <div class="keterangan-row">
                    <span class="keterangan-label"><u>Jumlah Hadir</u></span>
                    <span class="keterangan-separator">:</span>
                    <span class="keterangan-value">.............. orang</span>
                </div>
                <br>
                <p><strong>Komposisi Peserta {{ $jenis_pelatihan }} Angkatan {{ $angkatan }} Tahun
                        {{ $tahun }}:</strong></p>
                <div class="keterangan-row">
                    <span class="keterangan-label">- Laki-Laki</span>
                    <span class="keterangan-separator">:</span>
                    <span class="keterangan-value">{{ $jumlah_laki }} orang</span>
                </div>
                <div class="keterangan-row">
                    <span class="keterangan-label">- Perempuan</span>
                    <span class="keterangan-separator">:</span>
                    <span class="keterangan-value">{{ $jumlah_perempuan }} orang</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>