<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biodata Peserta</title>
    <style>
        @page {
            size: A4;
            margin: 2cm 1.5cm;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 20px;
        }
        
        .page-container {
            position: relative;
            width: 100%;
            max-width: 21cm;
            margin: 0 auto;
        }
        
        .nomor-halaman {
            position: absolute;
            top: 0;
            right: 0;
            border: 2px solid #000;
            padding: 12px 25px;
            font-size: 28pt;
            font-weight: bold;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            margin-top: 10px;
        }
        
        .header h2 {
            font-size: 13pt;
            font-weight: bold;
            margin: 3px 0;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .form-table td {
            border: 1px solid #000;
            padding: 6px 10px;
            vertical-align: top;
        }
        
        .form-table td:first-child {
            width: 4%;
            text-align: center;
            font-weight: normal;
        }
        
        .form-table td:nth-child(2) {
            width: 30%;
            padding-left: 8px;
        }
        
        .form-table td:nth-child(3) {
            width: 2%;
            text-align: center;
        }
        
        .form-table td:last-child {
            width: 64%;
            padding-left: 10px;
        }
        
        .row-title-aksi td {
            height: 180px;
            vertical-align: top;
        }
        
        /* Force fixed height for dompdf */
        .row-title-aksi td:first-child,
        .row-title-aksi td:nth-child(2),
        .row-title-aksi td:nth-child(3),
        .row-title-aksi td:last-child {
            min-height: 180px;
            max-height: 180px;
        }
        
        .footer-section {
            margin-top: 25px;
            page-break-inside: avoid;
        }
        
        .catatan {
            margin-bottom: 15px;
            float: left;
            width: 60%;
        }
        
        .catatan-title {
            font-weight: normal;
            margin-bottom: 8px;
        }
        
        .catatan ol {
            margin: 0;
            padding-left: 20px;
        }
        
        .catatan li {
            margin-bottom: 5px;
            font-size: 10.5pt;
            line-height: 1.4;
        }
        
        .signature-section {
            float: right;
            text-align: center;
            width: 35%;
            padding-top: 5px;
        }
        
        .signature-city {
            text-align: right;
            margin-bottom: 10px;
            font-size: 11pt;
        }
        
        .signature-label {
            text-align: center;
            margin-bottom: 60px;
            font-size: 11pt;
        }
        
        .signature-line {
            margin: 0 auto 5px;
            border-bottom: 1px solid #000;
            width: 180px;
        }
        
        .signature-name {
            margin-top: 5px;
            font-weight: normal;
            font-size: 11pt;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="nomor-halaman">
            1
        </div>
        
        <div class="header">
            <h2>BIODATA PESERTA</h2>
            <h2>{{$jenisPelatihan->deskripsi}}</h2>
            <h2>{{ $angkatan->nama_angkatan ?? 'XIX' }} TAHUN {{ date('Y', strtotime($angkatan->tanggal_mulai ?? now())) }}</h2>
        </div>
        
        <table class="form-table">
            <tr>
                <td>1.</td>
                <td>Nama (Lengkap dengan Gelar)</td>
                <td>:</td>
                <td>{{ $peserta->nama_lengkap ?? '' }}</td>
            </tr>
            <tr>
                <td>2.</td>
                <td>NIP</td>
                <td>:</td>
                <td>{{ $peserta->nip_nrp ?? '' }}</td>
            </tr>
            <tr>
                <td>3.</td>
                <td>Tempat & Tanggal Lahir</td>
                <td>:</td>
                <td>
                    {{ $peserta->tempat_lahir ?? '' }}, 
                    {{ $peserta->tanggal_lahir ? date('d F Y', strtotime($peserta->tanggal_lahir)) : '' }}
                </td>
            </tr>
            <tr>
                <td>4.</td>
                <td>Pangkat/Golongan</td>
                <td>:</td>
                <td>{{ $kepegawaian->pangkat ?? '' }}, {{ $kepegawaian->golongan_ruang ?? '' }}</td>
            </tr>
            <tr>
                <td>5.</td>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $kepegawaian->jabatan ?? '' }}</td>
            </tr>
            <tr>
                <td>6.</td>
                <td>Instansi</td>
                <td>:</td>
                <td>{{ $kepegawaian->asal_instansi ?? '' }}</td>
            </tr>
            <tr class="row-title-aksi">
                <td>7.</td>
                <td>
                    @if($jenisPelatihan->id == 1)
                        Judul Proyek Perubahan
                    @elseif($jenisPelatihan->id == 2)
                        Judul Aktualisasi
                    @elseif(in_array($jenisPelatihan->id, [3, 4]))
                        Judul Aksi Perubahan
                    @else
                        Judul Aksi Perubahan
                    @endif
                </td>
                <td>:</td>
                <td>{{ $pendaftaran->judul_aksi_perubahan ?? '' }}</td>
            </tr>
        </table>
        
        <div class="footer-section clearfix">
            <div class="catatan">
                <div class="catatan-title">Catatan :</div>
                <ol>
                    <li>Mohon memberikan data yang valid dan benar dengan mengoreksi data yang dianggap salah</li>
                    <li>Penulisan Judul Menggunakan huruf kapital diawal kata, huruf kapital semua jika menggunakan Akronim</li>
                    <li>Data diatas akan digunakan untuk pembuatan E-STTP</li>
                    <li>Koreksi kami terima paling lambat selasa tanggal 02 Oktober jam 16.00 Wita dan diserahkan ke PIC / Penyelenggara</li>
                </ol>
            </div>
            
            <div class="signature-section">
                <div class="signature-city">
                    {{ $kepegawaian->kabupaten->nama ?? 'Makassar' }}, {{ date('d F Y', strtotime($pendaftaran->tanggal_daftar ?? now())) }}
                </div>
                <div class="signature-label">Tanda Tangan</div>
                <div class="signature-line"></div>
                <div class="signature-name">( {{ $peserta->nama_lengkap ?? '' }} )</div>
            </div>
        </div>
    </div>
</body>
</html>