<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Report Data Peserta - {{ $peserta->nama_lengkap ?? 'Peserta' }}</title>
    <style>
        @page {
            margin: 15px;
            font-family: Arial, sans-serif;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* HEADER SECTION */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #1a3a6c;
        }

        .header h1 {
            color: #1a3a6c;
            font-size: 18px;
            margin: 0 0 3px 0;
            padding: 0;
        }

        .header h2 {
            color: #2c5282;
            font-size: 14px;
            margin: 0;
            padding: 0;
            font-weight: normal;
        }

        /* INFO BOX STYLING */
        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 12px;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .info-box h3 {
            color: #1a3a6c;
            font-size: 13px;
            margin: 0 0 8px 0;
            padding: 0 0 5px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        /* GRID LAYOUT */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -8px;
        }

        .col {
            flex: 1;
            padding: 0 8px;
            min-width: 45%;
        }

        /* DATA ITEM STYLING */
        .data-item {
            margin-bottom: 6px;
            display: flex;
            min-height: 18px;
            align-items: flex-start;
        }

        .data-label {
            font-weight: bold;
            width: 140px;
            min-width: 140px;
            color: #64748b;
            flex-shrink: 0;
        }

        .data-value {
            flex: 1;
            color: #1e293b;
            word-break: break-word;
        }

        /* STATUS BADGES */
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            margin: 0;
        }

        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-inactive {
            background-color: #fee2e2;
            color: #991b1b;
        }

        /* DOCUMENT LIST */
        .document-list {
            list-style: none;
            padding: 0;
            margin: 5px 0 0 0;
        }

        .document-item {
            margin-bottom: 3px;
            padding: 3px 0;
            border-bottom: 1px dashed #e2e8f0;
            display: flex;
            align-items: center;
        }

        .document-item:last-child {
            border-bottom: none;
        }

        /* SUB HEADERS */
        .sub-header {
            color: #1a3a6c;
            font-size: 11px;
            font-weight: bold;
            margin: 10px 0 6px 0;
            padding: 0;
        }

        /* FOOTER */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 9px;
            color: #64748b;
            line-height: 1.2;
        }

        /* PAGE BREAK */
        .page-break {
            page-break-before: always;
            margin-top: 20px;
        }

        /* LOGO */
        .logo-container {
            text-align: center;
            margin-bottom: 15px;
        }

        .logo {
            max-width: 120px;
            height: auto;
        }

        /* UTILITY CLASSES */
        .mt-1 {
            margin-top: 5px;
        }

        .mt-2 {
            margin-top: 10px;
        }

        .mb-1 {
            margin-bottom: 5px;
        }

        .mb-2 {
            margin-bottom: 10px;
        }

        .text-success {
            color: #065f46;
        }

        .text-danger {
            color: #991b1b;
        }

        .text-muted {
            color: #64748b;
        }

        /* TABLE FOR DOCUMENTS (ALTERNATIVE) */
        .doc-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 10px;
        }

        .doc-table th {
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 4px 6px;
            text-align: left;
            font-weight: bold;
            color: #1a3a6c;
        }

        .doc-table td {
            border: 1px solid #e2e8f0;
            padding: 4px 6px;
            vertical-align: top;
        }
    </style>
</head>

<body>
    <!-- LOGO -->
    <div class="logo-container">
        @if(file_exists(public_path('images/logo.png')))
            <img src="{{ public_path('images/logo.png') }}" class="logo">
        @endif
    </div>

    <!-- HEADER -->
    <div class="header">
        <h1>LAPORAN DATA PESERTA PELATIHAN</h1>
        <h2>LAN Pusjar SKMP - Sistem Manajemen Pembelajaran</h2>
    </div>

    <!-- INFORMASI PELATIHAN -->
    <div class="info-box">
        <h3>INFORMASI PELATIHAN</h3>
        <div class="row">
            <div class="col">
                <div class="data-item">
                    <span class="data-label">Jenis Pelatihan:</span>
                    <span class="data-value">{{ $jenisPelatihan->nama_pelatihan ?? '-' }}</span>
                </div>
            </div>
            <div class="col">
                <div class="data-item">
                    <span class="data-label">Angkatan:</span>
                    <span class="data-value">{{ $angkatan->nama_angkatan ?? '-' }}</span>
                </div>
                <div class="data-item">
                    <span class="data-label">Tahun:</span>
                    <span class="data-value">{{ $angkatan->tahun ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- DATA PRIBADI PESERTA -->
    <div class="info-box">
        <h3>DATA PRIBADI PESERTA</h3>
        <div class="row">
            <div class="col">
                <div class="data-item">
                    <span class="data-label">Nama Lengkap:</span>
                    <span class="data-value">{{ $peserta->nama_lengkap ?? '-' }}</span>
                </div>
                <div class="data-item">
                    <span class="data-label">NIP/NRP:</span>
                    <span class="data-value">{{ $peserta->nip_nrp ?? '-' }}</span>
                </div>
                <div class="data-item">
                    <span class="data-label">Nama Panggilan:</span>
                    <span class="data-value">{{ $peserta->nama_panggilan ?? '-' }}</span>
                </div>
                <div class="data-item">
                    <span class="data-label">Jenis Kelamin:</span>
                    <span class="data-value">{{ $peserta->jenis_kelamin ?? '-' }}</span>
                </div>
                <div class="data-item">
                    <span class="data-label">Tempat Lahir:</span>
                    <span class="data-value">{{ $peserta->tempat_lahir ?? '-' }}</span>
                </div>
                <div class="data-item">
                    <span class="data-label">Tanggal Lahir:</span>
                    <span class="data-value">
                        @if($peserta->tanggal_lahir)
                            {{ \Carbon\Carbon::parse($peserta->tanggal_lahir)->format('d F Y') }}
                        @else
                            -
                        @endif
                    </span>
                </div>
                <div class="data-item">
                    <span class="data-label">Agama:</span>
                    <span class="data-value">{{ $peserta->agama ?? '-' }}</span>
                </div>
            </div>
            <div class="col">
                <div class="data-item">
                    <span class="data-label">Status Perkawinan:</span>
                    <span class="data-value">{{ $peserta->status_perkawinan ?? '-' }}</span>
                </div>
                @if($peserta->status_perkawinan && strtolower($peserta->status_perkawinan) != 'belum menikah')
                    <div class="data-item">
                        <span class="data-label">Nama Pasangan:</span>
                        <span class="data-value">{{ $peserta->nama_pasangan ?? '-' }}</span>
                    </div>
                @endif
                <div class="data-item">
                    <span class="data-label">Email Pribadi:</span>
                    <span class="data-value">{{ $peserta->email_pribadi ?? '-' }}</span>
                </div>
                <div class="data-item">
                    <span class="data-label">Nomor HP:</span>
                    <span class="data-value">{{ $peserta->nomor_hp ?? '-' }}</span>
                </div>
                <div class="data-item">
                    <span class="data-label">Alamat Rumah:</span>
                    <span class="data-value">{{ $peserta->alamat_rumah ?? '-' }}</span>
                </div>
                <div class="data-item">
                    <span class="data-label">Pendidikan Terakhir:</span>
                    <span class="data-value">{{ $peserta->pendidikan_terakhir ?? '-' }}</span>
                </div>
                <div class="data-item">
                    <span class="data-label">Bidang Studi:</span>
                    <span class="data-value">{{ $peserta->bidang_studi ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- PENDIDIKAN & KEAHLIAN -->
    <div class="info-box">
        <h3>PENDIDIKAN & KEAHLIAN</h3>
        <div class="row">
            <div class="col">
                <div class="data-item">
                    <span class="data-label">Bidang Keahlian:</span>
                    <span class="data-value">{{ $peserta->bidang_keahlian ?? '-' }}</span>
                </div>
                <div class="data-item">
                    <span class="data-label">Olahraga/Hobi:</span>
                    <span class="data-value">{{ $peserta->olahraga_hobi ?? '-' }}</span>
                </div>
            </div>
            <div class="col">
                <div class="data-item">
                    <span class="data-label">Perokok:</span>
                    <span class="data-value">
                        @if(isset($peserta->perokok))
                            {{ $peserta->perokok == 1 ? 'Ya' : 'Tidak' }}
                        @else
                            -
                        @endif
                    </span>
                </div>
                <div class="data-item">
                    <span class="data-label">Kondisi Peserta:</span>
                    <span class="data-value">{{ $peserta->kondisi_peserta ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- UKURAN PAKAIAN -->
    <div class="info-box">
        <h3>UKURAN PAKAIAN</h3>
        <div class="row">
            <div class="col">
                <div class="data-item">
                    <span class="data-label">Ukuran Kaos:</span>
                    <span class="data-value">{{ $peserta->ukuran_kaos ?? '-' }}</span>
                </div>
                <div class="data-item">
                    <span class="data-label">Ukuran Training:</span>
                    <span class="data-value">{{ $peserta->ukuran_training ?? '-' }}</span>
                </div>
            </div>
            <div class="col">
                <div class="data-item">
                    <span class="data-label">Ukuran Celana:</span>
                    <span class="data-value">{{ $peserta->ukuran_celana ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- DATA KEPEGAWAIAN -->
    @if($kepegawaian)
        <div class="info-box">
            <h3>DATA KEPEGAWAIAN</h3>
            <div class="row">
                <div class="col">
                    <div class="data-item">
                        <span class="data-label">Asal Instansi:</span>
                        <span class="data-value">{{ $kepegawaian->asal_instansi ?? '-' }}</span>
                    </div>
                    <div class="data-item">
                        <span class="data-label">Unit Kerja:</span>
                        <span class="data-value">{{ $kepegawaian->unit_kerja ?? '-' }}</span>
                    </div>
                    <div class="data-item">
                        <span class="data-label">Provinsi:</span>
                        <span class="data-value">{{ $kepegawaian->provinsi->name ?? '-' }}</span>
                    </div>
                    <div class="data-item">
                        <span class="data-label">Kabupaten/Kota:</span>
                        <span class="data-value">{{ $kepegawaian->kabupaten->name ?? '-' }}</span>
                    </div>
                </div>
                <div class="col">
                    <div class="data-item">
                        <span class="data-label">Jabatan:</span>
                        <span class="data-value">{{ $kepegawaian->jabatan ?? '-' }}</span>
                    </div>
                    @if($jenisPelatihan->kode_pelatihan != "LATSAR")
                        <div class="data-item">
                            <span class="data-label">Eselon:</span>
                            <span class="data-value">{{ $kepegawaian->eselon ?? '-' }}</span>
                        </div>
                    @endif
                    <div class="data-item">
                        <span class="data-label">Pangkat:</span>
                        <span class="data-value">{{ $kepegawaian->pangkat ?? '-' }}</span>
                    </div>
                    <div class="data-item">
                        <span class="data-label">Golongan Ruang:</span>
                        <span class="data-value">{{ $kepegawaian->golongan_ruang ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="data-item">
                        <span class="data-label">Alamat Kantor:</span>
                        <span class="data-value">{{ $kepegawaian->alamat_kantor ?? '-' }}</span>
                    </div>
                    <div class="data-item">
                        <span class="data-label">Email Kantor:</span>
                        <span class="data-value">{{ $kepegawaian->email_kantor ?? '-' }}</span>
                    </div>
                </div>
                <div class="col">
                    <div class="data-item">
                        <span class="data-label">Telp Kantor:</span>
                        <span class="data-value">{{ $kepegawaian->nomor_telepon_kantor ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- DATA SK -->
            <div class="sub-header">Data Surat Keputusan</div>
            <div class="row">
                <div class="col">
                    @if($jenisPelatihan->kode_pelatihan == "LATSAR")
                        <div class="data-item">
                            <span class="data-label">Nomor SK CPNS:</span>
                            <span class="data-value">{{ $kepegawaian->nomor_sk_cpns ?? '-' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">Tanggal SK CPNS:</span>
                            <span class="data-value">
                                @if($kepegawaian->tanggal_sk_cpns)
                                    {{ \Carbon\Carbon::parse($kepegawaian->tanggal_sk_cpns)->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    @elseif($jenisPelatihan->kode_pelatihan != "PKN_TK_II")
                        <div class="data-item">
                            <span class="data-label">Nomor SK Terakhir:</span>
                            <span class="data-value">{{ $kepegawaian->nomor_sk_terakhir ?? '-' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">Tanggal SK Jabatan:</span>
                            <span class="data-value">
                                @if($kepegawaian->tanggal_sk_jabatan)
                                    {{ \Carbon\Carbon::parse($kepegawaian->tanggal_sk_jabatan)->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    @endif
                </div>
                <div class="col">
                    @if(in_array($jenisPelatihan->kode_pelatihan, ['PKA', 'PKP']))
                        <div class="data-item">
                            <span class="data-label">Tahun Lulus PKP/PIM IV:</span>
                            <span class="data-value">{{ $kepegawaian->tahun_lulus_pkp_pim_iv ?? '-' }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- DATA MENTOR -->
    @if($mentor)
        <div class="info-box">
            <h3>DATA MENTOR</h3>
            <div class="row">
                <div class="col">
                    <div class="data-item">
                        <span class="data-label">Nama Mentor:</span>
                        <span class="data-value">{{ $mentor->nama_mentor ?? '-' }}</span>
                    </div>
                    <div class="data-item">
                        <span class="data-label">NIP Mentor:</span>
                        <span class="data-value">{{ $mentor->nip_mentor ?? '-' }}</span>
                    </div>
                    <div class="data-item">
                        <span class="data-label">Jabatan Mentor:</span>
                        <span class="data-value">{{ $mentor->jabatan_mentor ?? '-' }}</span>
                    </div>
                    <div class="data-item">
                        <span class="data-label">Email Mentor:</span>
                        <span class="data-value">{{ $mentor->email_mentor ?? '-' }}</span>
                    </div>
                </div>
                <div class="col">
                    <div class="data-item">
                        <span class="data-label">Nomor HP Mentor:</span>
                        <span class="data-value">{{ $mentor->nomor_hp_mentor ?? '-' }}</span>
                    </div>
                    <div class="data-item">
                        <span class="data-label">NPWP Mentor:</span>
                        <span class="data-value">{{ $mentor->npwp_mentor ?? '-' }}</span>
                    </div>
                    <div class="data-item">
                        <span class="data-label">Nomor Rekening:</span>
                        <span class="data-value">{{ $mentor->nomor_rekening ?? '-' }}</span>
                    </div>
                </div>
            </div>
            <div class="data-item">
                <span class="data-label">Status Mentor:</span>
                <span class="data-value">
                    @if($mentor->status_aktif)
                        <span class="status-badge status-active">Aktif</span>
                    @else
                        <span class="status-badge status-inactive">Tidak Aktif</span>
                    @endif
                </span>
            </div>
        </div>
    @endif

    <!-- DOKUMEN -->
    <div class="page-break"></div>

    <!-- DOKUMEN PRIBADI -->
    <div class="info-box">
        <h3>DOKUMEN PRIBADI</h3>
        <table class="doc-table">
            <thead>
                <tr>
                    <th width="60%">Jenis Dokumen</th>
                    <th width="40%">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Kartu Tanda Penduduk (KTP)</td>
                    <td class="{{ $peserta->file_ktp ? 'text-success' : 'text-danger' }}">
                        {{ $peserta->file_ktp ? 'Tersedia' : 'Belum diunggah' }}
                    </td>
                </tr>
                <tr>
                    <td>Pas Foto (4x6)</td>
                    <td class="{{ $peserta->file_pas_foto ? 'text-success' : 'text-danger' }}">
                        {{ $peserta->file_pas_foto ? 'Tersedia' : 'Belum diunggah' }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- DOKUMEN KEPEGAWAIAN -->
    @if($kepegawaian)
        <div class="info-box">
            <h3>DOKUMEN KEPEGAWAIAN</h3>
            <table class="doc-table">
                <thead>
                    <tr>
                        <th width="60%">Jenis Dokumen</th>
                        <th width="40%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if($jenisPelatihan->kode_pelatihan != "LATSAR")
                        <tr>
                            <td>Surat Keputusan Jabatan</td>
                            <td class="{{ $kepegawaian->file_sk_jabatan ? 'text-success' : 'text-danger' }}">
                                {{ $kepegawaian->file_sk_jabatan ? 'Tersedia' : 'Belum diunggah' }}
                            </td>
                        </tr>
                        <tr>
                            <td>Surat Keputusan Pangkat</td>
                            <td class="{{ $kepegawaian->file_sk_pangkat ? 'text-success' : 'text-danger' }}">
                                {{ $kepegawaian->file_sk_pangkat ? 'Tersedia' : 'Belum diunggah' }}
                            </td>
                        </tr>
                    @endif

                    @if($jenisPelatihan->kode_pelatihan == "LATSAR")
                        <tr>
                            <td>SK CPNS</td>
                            <td class="{{ $kepegawaian->file_sk_cpns ? 'text-success' : 'text-danger' }}">
                                {{ $kepegawaian->file_sk_cpns ? 'Tersedia' : 'Belum diunggah' }}
                            </td>
                        </tr>
                        <tr>
                            <td>SPMT</td>
                            <td class="{{ $kepegawaian->file_spmt ? 'text-success' : 'text-danger' }}">
                                {{ $kepegawaian->file_spmt ? 'Tersedia' : 'Belum diunggah' }}
                            </td>
                        </tr>
                    @endif

                    @if(!in_array($jenisPelatihan->kode_pelatihan, ['LATSAR', 'PKN_TK_II']))
                        <tr>
                            <td>SKP</td>
                            <td class="{{ $kepegawaian->file_skp ? 'text-success' : 'text-danger' }}">
                                {{ $kepegawaian->file_skp ? 'Tersedia' : 'Belum diunggah' }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    @endif

    <!-- DOKUMEN PENDAFTARAN -->
    <div class="info-box">
        <h3>DOKUMEN PENDAFTARAN</h3>
        <table class="doc-table">
            <thead>
                <tr>
                    <th width="60%">Jenis Dokumen</th>
                    <th width="40%">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Surat Tugas</td>
                    <td class="{{ $pendaftaran->file_surat_tugas ? 'text-success' : 'text-danger' }}">
                        {{ $pendaftaran->file_surat_tugas ? 'Tersedia' : 'Belum diunggah' }}
                    </td>
                </tr>

                @if($jenisPelatihan->kode_pelatihan != "PKN_TK_II")
                    <tr>
                        <td>Surat Kesediaan</td>
                        <td class="{{ $pendaftaran->file_surat_kesediaan ? 'text-success' : 'text-danger' }}">
                            {{ $pendaftaran->file_surat_kesediaan ? 'Tersedia' : 'Belum diunggah' }}
                        </td>
                    </tr>
                @endif

                @if($jenisPelatihan->kode_pelatihan != "LATSAR")
                    <tr>
                        <td>Pakta Integritas</td>
                        <td class="{{ $pendaftaran->file_pakta_integritas ? 'text-success' : 'text-danger' }}">
                            {{ $pendaftaran->file_pakta_integritas ? 'Tersedia' : 'Belum diunggah' }}
                        </td>
                    </tr>
                @endif

                @if($jenisPelatihan->kode_pelatihan == "PKN_TK_II")
                    <tr>
                        <td>Surat Komitmen</td>
                        <td class="{{ $pendaftaran->file_surat_komitmen ? 'text-success' : 'text-danger' }}">
                            {{ $pendaftaran->file_surat_komitmen ? 'Tersedia' : 'Belum diunggah' }}
                        </td>
                    </tr>
                @endif

                @if($jenisPelatihan->kode_pelatihan != "LATSAR")
                    <tr>
                        <td>Surat Sehat</td>
                        <td class="{{ $pendaftaran->file_surat_sehat ? 'text-success' : 'text-danger' }}">
                            {{ $pendaftaran->file_surat_sehat ? 'Tersedia' : 'Belum diunggah' }}
                        </td>
                    </tr>
                @endif

                @if($jenisPelatihan->kode_pelatihan != "LATSAR")
                    <tr>
                        <td>Surat Bebas Narkoba</td>
                        <td class="{{ $pendaftaran->file_surat_bebas_narkoba ? 'text-success' : 'text-danger' }}">
                            {{ $pendaftaran->file_surat_bebas_narkoba ? 'Tersedia' : 'Belum diunggah' }}
                        </td>
                    </tr>
                @endif

                @if($jenisPelatihan->kode_pelatihan == "PKN_TK_II")
                    <tr>
                        <td>Surat Kelulusan Seleksi</td>
                        <td class="{{ $pendaftaran->file_surat_kelulusan_seleksi ? 'text-success' : 'text-danger' }}">
                            {{ $pendaftaran->file_surat_kelulusan_seleksi ? 'Tersedia' : 'Belum diunggah' }}
                        </td>
                    </tr>
                @endif

                @if(in_array($jenisPelatihan->kode_pelatihan, ['PKA', 'PKP', 'LATSAR']))
                    <tr>
                        <td>Persetujuan Mentor</td>
                        <td class="{{ $pendaftaran->file_persetujuan_mentor ? 'text-success' : 'text-danger' }}">
                            {{ $pendaftaran->file_persetujuan_mentor ? 'Tersedia' : 'Belum diunggah' }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p><strong>Dokumen ini dicetak pada:</strong> {{ $tanggal_report }}</p>
        <p><strong>Oleh:</strong> {{ $user->name }} ({{ $user->role->name }})</p>
        <p>LAN Pusjar SKMP - Sistem Manajemen Pembelajaran</p>
        <p>Halaman ini adalah dokumen resmi sistem</p>
    </div>
</body>

</html>