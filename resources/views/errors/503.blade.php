<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sedang Pemeliharaan</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --navy: #1a3a6c;
            --blue: #2c5aa0;
            --light: #e8eef7;
            --white: #ffffff;
            --muted: rgba(255,255,255,0.45);
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--navy);
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow: hidden;
        }

        /* ── Background mesh ── */
        .bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse 80% 60% at 10% 20%, rgba(44,90,160,0.5) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 90% 80%, rgba(26,58,108,0.8) 0%, transparent 55%),
                var(--navy);
        }

        /* Floating orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.18;
            animation: drift 12s ease-in-out infinite alternate;
            z-index: 0;
        }
        .orb-1 { width: 400px; height: 400px; background: var(--blue); top: -100px; left: -100px; animation-duration: 14s; }
        .orb-2 { width: 300px; height: 300px; background: #4a7fd4; bottom: -80px; right: -60px; animation-duration: 10s; animation-delay: -4s; }
        .orb-3 { width: 200px; height: 200px; background: var(--blue); top: 50%; left: 60%; animation-duration: 18s; animation-delay: -7s; }

        @keyframes drift {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(30px, -30px) scale(1.08); }
        }

        /* Grid overlay */
        .grid-overlay {
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* ── Main card ── */
        .card {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 64px 56px;
            max-width: 520px;
            width: 90%;
            background: rgba(255,255,255,0.055);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 24px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 32px 80px rgba(0,0,0,0.35), inset 0 1px 0 rgba(255,255,255,0.12);
            animation: fadeUp 0.9s cubic-bezier(0.22,1,0.36,1) both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(36px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Icon */
        .icon-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            border-radius: 20px;
            background: rgba(44,90,160,0.35);
            border: 1px solid rgba(255,255,255,0.12);
            margin-bottom: 32px;
            animation: pulse-ring 3s ease-in-out infinite;
        }

        @keyframes pulse-ring {
            0%, 100% { box-shadow: 0 0 0 0 rgba(44,90,160,0.5); }
            50%       { box-shadow: 0 0 0 16px rgba(44,90,160,0); }
        }

        .icon-wrap svg {
            animation: spin-slow 8s linear infinite;
        }

        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        /* Text */
        h1 {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(2rem, 5vw, 2.8rem);
            font-weight: 400;
            line-height: 1.15;
            letter-spacing: -0.01em;
            margin-bottom: 16px;
        }

        h1 em {
            font-style: italic;
            color: var(--light);
        }

        p {
            font-size: 0.97rem;
            font-weight: 300;
            color: var(--muted);
            line-height: 1.7;
            max-width: 340px;
            margin: 0 auto 40px;
        }

        /* Divider */
        .divider {
            width: 40px;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            margin: 32px auto;
        }

        /* Progress dots */
        .dots {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
        }

        .dot.active {
            background: var(--white);
            animation: blink 1.4s ease-in-out infinite;
        }

        .dot:nth-child(2).active { animation-delay: 0.2s; }
        .dot:nth-child(3).active { animation-delay: 0.4s; }

        @keyframes blink {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.3; transform: scale(0.7); }
        }

        /* Footer tag */
        .tag {
            margin-top: 40px;
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.25);
        }
    </style>
</head>
<body>

    <div class="bg"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="grid-overlay"></div>

    <div class="card">

        <div class="icon-wrap">
            <!-- Gear / settings icon -->
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                 stroke="rgba(255,255,255,0.85)" stroke-width="1.6"
                 stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
            </svg>
        </div>

        <h1>Sedang dalam<br><em>Pemeliharaan</em></h1>

        <div class="divider"></div>

        <p>Kami sedang melakukan peningkatan sistem.<br>Mohon kembali sebentar lagi.</p>

        <div class="dots">
            <div class="dot active"></div>
            <div class="dot active"></div>
            <div class="dot active"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>

        <div class="tag">&mdash; Maintenance Mode</div>

    </div>

</body>
</html>