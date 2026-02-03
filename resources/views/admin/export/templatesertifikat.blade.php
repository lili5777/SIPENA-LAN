{{-- resources/views/admin/export/templatesertifikat.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Sertifikat Peserta</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 297mm;
            height: 210mm;
        }

        .certificate-page {
            position: relative;
            width: 297mm;
            height: 210mm;
            page-break-after: always;
            background-color: #ffffff;
            overflow: hidden;
        }

        .certificate-page:last-child {
            page-break-after: auto;
        }

        /* Border bingkai */
        .frame-outer {
            position: absolute;
            top: 8mm;
            left: 8mm;
            right: 8mm;
            bottom: 8mm;
            border: 2px solid #1a3a6c;
            border-radius: 4px;
        }

        .frame-inner {
            position: absolute;
            top: 10mm;
            left: 10mm;
            right: 10mm;
            bottom: 10mm;
            border: 1px solid #cbd5e1;
            border-radius: 2px;
        }

        /* Logo di kiri atas */
        .logo-section {
            position: absolute;
            top: 18mm;
            left: 22mm;
        }

        .logo-box {
            width: 260px;
            height: 60px;
            background: #f1f5f9;
            border-radius: 6px;
            padding: 6px;
            border: 1px solid #e2e8f0;
        }

        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Top Right Badges */
        .badges {
            position: absolute;
            top: 18mm;
            right: 22mm;
        }

        .badge {
            width: 52px;
            height: 52px;
            background: #f1f5f9;
            border-radius: 50%;
            padding: 4px;
            display: inline-block;
            margin-left: 8px;
            vertical-align: top;
            border: 1px solid #e2e8f0;
        }

        .badge img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }

        /* Angkatan label */
        .angkatan-section {
            position: absolute;
            top: 48mm;
            left: 22mm;
        }

        .angkatan-label {
            font-size: 22pt;
            font-weight: bold;
            color: #1a3a6c;
            letter-spacing: 2px;
        }

        /* Divider line di bawah angkatan */
        .divider {
            position: absolute;
            top: 62mm;
            left: 22mm;
            width: 180px;
            height: 3px;
            background: #1a3a6c;
            border-radius: 2px;
        }

        /* Konten Kiri (NDH, Nama, Kabupaten) */
        .left-content {
            position: absolute;
            top: 88mm;
            left: 22mm;
            width: 48%;
        }

        /* NDH besar */
        .ndh-large {
            font-size: 80pt;
            font-weight: bold;
            color: #1a3a6c;
            line-height: 1;
            margin-bottom: 12px;
        }

        /* Nama */
        .participant-name {
            font-size: 24pt;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 6px;
            line-height: 1.2;
        }

        /* Kabupaten */
        .participant-kabupaten {
            font-size: 15pt;
            color: #64748b;
            font-weight: 600;
            margin-top: 4px;
        }

        /* Foto besar di kanan */
        .photo-section {
            position: absolute;
            top: 50%;
            right: 40mm;
            transform: translateY(-50%);
        }

        .photo-circle {
            width: 240px;
            height: 240px;
            border-radius: 50%;
            border: 5px solid #1a3a6c;
            overflow: hidden;
            background: #f1f5f9;
            box-shadow: 0 4px 16px rgba(26, 58, 108, 0.15);
        }

        .photo-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #e2e8f0, #f1f5f9);
            display: table;
            text-align: center;
        }

        .photo-placeholder span {
            display: table-cell;
            vertical-align: middle;
            color: #1a3a6c;
            font-size: 100pt;
            font-weight: bold;
        }

        /* Bottom Number */
        .bottom-number {
            position: absolute;
            bottom: 14mm;
            left: 22mm;
            font-size: 13pt;
            font-weight: bold;
            color: #94a3b8;
        }

        /* Social Media */
        .social-media {
            position: absolute;
            bottom: 14mm;
            right: 22mm;
            font-size: 8pt;
            color: #94a3b8;
            text-align: right;
        }

        .social-media div {
            margin: 2px 0;
        }
    </style>
</head>

<body>
    @foreach($peserta as $index => $p)
        <div class="certificate-page">
            <!-- Bingkai -->
            <div class="frame-outer"></div>
            <div class="frame-inner"></div>

            <!-- Logo di kiri atas -->
            <div class="logo-section">
                <div class="logo-box">
                    @if(!empty($logo))
                        <img src="{{ $logo }}" alt="Logo LAN">
                    @endif
                </div>
            </div>

            <!-- Top Right Badges -->
            <div class="badges">
                @if(!empty($badge1))
                    <div class="badge">
                        <img src="{{ $badge1 }}" alt="Badge 1">
                    </div>
                @endif
                @if(!empty($badge2))
                    <div class="badge">
                        <img src="{{ $badge2 }}" alt="Badge 2">
                    </div>
                @endif
            </div>

            <!-- Angkatan -->
            <div class="angkatan-section">
                <div class="angkatan-label">Angkatan {{ $angkatan }}</div>
            </div>

            <!-- Divider -->
            <div class="divider"></div>

            <!-- Konten Kiri: NDH, Nama, Kabupaten -->
            <div class="left-content">
                <div class="ndh-large">{{ $p['ndh'] }}</div>
                <div class="participant-name">{{ strtoupper($p['nama']) }}</div>
                <div class="participant-kabupaten">{{ $p['kabupaten'] }}</div>
            </div>

            <!-- Foto Besar di Kanan -->
            <div class="photo-section">
                <div class="photo-circle">
                    @if(!empty($p['foto']))
                        <img src="{{ $p['foto'] }}" alt="Foto Peserta">
                    @else
                        <div class="photo-placeholder">
                            <span>{{ strtoupper(substr($p['nama'], 0, 1)) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bottom Number -->
            <div class="bottom-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
        </div>
    @endforeach
</body>

</html>