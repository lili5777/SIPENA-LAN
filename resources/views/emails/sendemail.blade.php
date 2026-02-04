<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Anda Telah Dibuat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f7f9fc;
            padding: 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .email-header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .email-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .email-header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .email-body {
            padding: 35px 30px;
        }

        .greeting {
            font-size: 18px;
            margin-bottom: 25px;
            color: #2d3748;
        }

        .greeting span {
            color: #4f46e5;
            font-weight: 600;
        }

        .intro-text {
            margin-bottom: 30px;
            color: #4a5568;
            font-size: 16px;
        }

        .info-card {
            background-color: #f8fafc;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            border-left: 4px solid #4f46e5;
        }

        .info-card h2 {
            font-size: 18px;
            color: #2d3748;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .info-card h2 i {
            margin-right: 10px;
            color: #4f46e5;
        }

        .info-item {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #4a5568;
            min-width: 100px;
        }

        .info-value {
            color: #2d3748;
            font-weight: 500;
        }

        .warning-box {
            background-color: #fffbeb;
            border: 1px solid #fbbf24;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            align-items: flex-start;
        }

        .warning-icon {
            color: #d97706;
            font-size: 20px;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .warning-text {
            color: #92400e;
            font-weight: 500;
        }

        .cta-button {
            display: inline-block;
            background-color: #4f46e5; /* fallback */
            background-image: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff !important;
            text-decoration: none !important;
            padding: 14px 32px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            border: 2px solid #ffffff; /* biar makin kontras */
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.25);
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(79, 70, 229, 0.3);
        }

        .footer {
            text-align: center;
            padding: 25px 20px;
            color: #718096;
            font-size: 14px;
            border-top: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }

        .footer p {
            margin-bottom: 10px;
        }

        .footer strong {
            color: #4f46e5;
        }

        .company-logo {
            font-size: 20px;
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        @media (max-width: 600px) {
            .email-body {
                padding: 25px 20px;
            }

            .email-header {
                padding: 25px 15px;
            }

            .email-header h1 {
                font-size: 24px;
            }

            .info-card {
                padding: 20px 15px;
            }

            .info-item {
                flex-direction: column;
            }

            .info-label {
                margin-bottom: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Akun Anda Telah Dibuat!</h1>
            <p>Selamat datang di platform kami</p>
        </div>

        <div class="email-body">
            <p class="greeting">Halo, <span>{{ $data['name'] }}</span></p>

            <p class="intro-text">Akun Anda telah berhasil dibuat. Berikut informasi login yang dapat Anda gunakan untuk
                mengakses akun Anda:</p>

            <div class="info-card">
                <h2>
                    <span style="font-size: 1.2em; margin-right: 10px;">🔐</span>
                    Informasi Login Anda
                </h2>
            
                <div class="info-item">
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $data['email'] }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Password :</div>
                    <div class="info-value">{{ $data['password'] }}</div>
                </div>
        
            
                <div style="text-align: center;">
                    <!-- Tombol untuk login -->
                    <a href="https://simpel.pw/login" class="cta-button"
                        style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                        Login
                    </a>
                    <p style="color: #718096; font-size: 14px;">Klik tombol di atas untuk login ke Akun anda.</p>
                </div>
            
            </div>


            <div class="info-card">
                <h2>
                    <span style="font-size: 1.2em; margin-right: 10px;">💬</span>
                    Grup WhatsApp Angkatan
                </h2>
            
                <p style="color:#4a5568; margin-bottom:15px;">
                    Silakan bergabung ke Grup WhatsApp resmi angkatan melalui tautan di bawah ini:
                </p>
            
                <div style="text-align:center;">
                    <a href="{{ $data['link_gb_wa'] }}" target="_blank" class="cta-button"
                        style="background-image: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);">
                        Gabung Grup WhatsApp
                    </a>
                </div>
            
                <p style="margin-top:15px; font-size:14px; color:#718096; text-align:center;">
                    Jika tombol tidak berfungsi, salin dan buka link berikut:<br>
                    <span style="word-break: break-all; color:#4f46e5;">
                        {{ $data['link_gb_wa'] }}
                    </span>
                </p>
            </div>

            

            
        </div>

        <div class="footer">
            <div class="company-logo">Platform Kami</div>
            <p>Jika Anda mengalami kesulitan atau memiliki pertanyaan, jangan ragu untuk menghubungi tim dukungan kami.
            </p>
            <p>Terima kasih,<br><strong>Tim Admin</strong></p>
            <p style="margin-top: 15px; font-size: 13px; color: #a0aec0;">
                Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
            </p>
        </div>
    </div>
</body>

</html>