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
            background-color: #f7941d;
            background-image: linear-gradient(135deg, #f7941d 0%, #f5a04d 100%);
            overflow: hidden;
        }

        .certificate-page:last-child {
            page-break-after: auto;
        }

        /* Decorative corners */
        .corner-tl {
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 0;
            border-top: 150px solid rgba(255, 255, 255, 0.15);
            border-right: 150px solid transparent;
        }

        .corner-br {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 0;
            height: 0;
            border-bottom: 150px solid rgba(255, 255, 255, 0.15);
            border-left: 150px solid transparent;
        }

        /* Logo di kiri atas */
        .logo-section {
            position: absolute;
            top: 20mm;
            left: 30mm;
        }

        .logo-box {
            width: 300px;
            height: 70px;
            background: white;
            border-radius: 8px;
            padding: 5px;
        }

        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Top Right Badges */
        .badges {
            position: absolute;
            top: 20mm;
            right: 30mm;
        }

        .badge {
            width: 55px;
            height: 55px;
            background: white;
            border-radius: 50%;
            padding: 4px;
            display: inline-block;
            margin-left: 8px;
            vertical-align: top;
        }

        .badge img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }

        /* Angkatan label - kiri tengah atas */
        .angkatan-section {
            position: absolute;
            top: 50mm;
            left: 30mm;
        }

        .angkatan-label {
            font-size: 32pt;
            font-weight: bold;
            color: white;
            letter-spacing: 2px;
        }

        /* Konten Kiri (NDH, Nama, Kabupaten) */
        .left-content {
            position: absolute;
            top: 95mm;
            left: 30mm;
            width: 45%;
        }

        /* NDH besar */
        .ndh-large {
            font-size: 96pt;
            font-weight: bold;
            color: white;
            line-height: 1;
            margin-bottom: 15px;
        }

        /* Nama */
        .participant-name {
            font-size: 28pt;
            font-weight: bold;
            color: white;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        /* Kabupaten */
        .participant-kabupaten {
            font-size: 18pt;
            color: white;
            font-weight: 600;
            margin-top: 5px;
        }

        /* Foto besar di kanan */
        .photo-section {
            position: absolute;
            top: 50%;
            right: 50mm;
            transform: translateY(-50%);
        }

        .photo-circle {
            width: 280px;
            height: 280px;
            border-radius: 50%;
            border: 10px solid white;
            overflow: hidden;
            background: white;
        }

        .photo-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #e0e0e0, #f5f5f5);
            display: table;
            text-align: center;
        }

        .photo-placeholder span {
            display: table-cell;
            vertical-align: middle;
            color: #999;
            font-size: 120pt;
            font-weight: bold;
        }

        /* Bottom Number */
        .bottom-number {
            position: absolute;
            bottom: 15mm;
            left: 30mm;
            font-size: 16pt;
            font-weight: bold;
            color: white;
        }

        /* Social Media */
        .social-media {
            position: absolute;
            bottom: 15mm;
            right: 30mm;
            font-size: 8pt;
            color: white;
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
            <!-- Decorative Corners -->
            <div class="corner-tl"></div>
            <div class="corner-br"></div>

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

            <!-- Angkatan - kiri tengah atas -->
            <div class="angkatan-section">
                <div class="angkatan-label">Angkatan {{ $angkatan }}</div>
            </div>

            <!-- Konten Kiri: NDH, Nama, Kabupaten -->
            <div class="left-content">
                <!-- NDH Besar -->
                <div class="ndh-large">{{ $p['ndh'] }}</div>

                <!-- Nama -->
                <div class="participant-name">{{ strtoupper($p['nama']) }}</div>

                <!-- Kabupaten -->
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