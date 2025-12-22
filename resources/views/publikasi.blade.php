@extends('layouts.master')

@section('title', 'Publikasi & Kinerja - SIPENA')

@section('content')
    <!-- Hero Section -->
    <section class="pub-hero-section">
        <div class="container">
            <div class="pub-hero-content">
                <h1 class="pub-hero-title">Publikasi & Kinerja Organisasi</h1>
                <p class="pub-hero-subtitle">Data dan Statistik Pencapaian PUSJAR SKMP dalam Pengembangan SDM Aparatur
                    Negara</p>
                <div class="hero-badge">
                    <span class="badge-text">Update Terakhir: {{ date('d F Y') }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistik Utama -->
    <section class="stats-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">Statistik Utama</div>
                <h2 class="section-title">Total Alumni <span class="highlight">Pelatihan</span></h2>
                <p class="section-subtitle">Akumulasi Data Alumni dari Berbagai Jenis Pelatihan</p>
            </div>

            <div class="total-stats-card">
                <div class="total-number">
                    <span class="number">25,847</span>
                    <span class="label">Total Alumni</span>
                </div>
                <div class="stats-trend">
                    <div class="trend-indicator positive">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                            <polyline points="17 6 23 6 23 12"></polyline>
                        </svg>
                        <span>+12.5% dari tahun lalu</span>
                    </div>
                </div>
            </div>

            <div class="stats-grid">
                <!-- PKN2 -->
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                            <path d="M2 17l10 5 10-5"></path>
                            <path d="M2 12l10 5 10-5"></path>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">8,942</div>
                        <div class="stat-label">Alumni PKN</div>
                        <div class="stat-change positive">+845 dari tahun lalu</div>
                    </div>
                    <div class="stat-progress">
                        <div class="progress-bar" style="width: 85%"></div>
                    </div>
                </div>

                <!-- PKA -->
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">5,213</div>
                        <div class="stat-label">Alumni PKA</div>
                        <div class="stat-change positive">+312 dari tahun lalu</div>
                    </div>
                    <div class="stat-progress">
                        <div class="progress-bar" style="width: 65%"></div>
                    </div>
                </div>

                <!-- PKP -->
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">4,185</div>
                        <div class="stat-label">Alumni PKP</div>
                        <div class="stat-change positive">+198 dari tahun lalu</div>
                    </div>
                    <div class="stat-progress">
                        <div class="progress-bar" style="width: 52%"></div>
                    </div>
                </div>

                <!-- Latsar -->
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">3,947</div>
                        <div class="stat-label">Alumni Latsar</div>
                        <div class="stat-change positive">+287 dari tahun lalu</div>
                    </div>
                    <div class="stat-progress">
                        <div class="progress-bar" style="width: 48%"></div>
                    </div>
                </div>

                <!-- Teknis -->
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">3,560</div>
                        <div class="stat-label">Alumni Teknis</div>
                        <div class="stat-change positive">+156 dari tahun lalu</div>
                    </div>
                    <div class="stat-progress">
                        <div class="progress-bar" style="width: 42%"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Grafik Section -->
    <section class="charts-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">Analisis Data</div>
                <h2 class="section-title">Trend <span class="highlight">Kinerja</span> 5 Tahun Terakhir</h2>
            </div>

            <div class="charts-grid">
                <!-- Grafik Total Alumni per Tahun -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">Jumlah Alumni per Tahun</h3>
                        <div class="chart-controls">
                            <button class="chart-btn active" data-chart="line">Garis</button>
                            <button class="chart-btn" data-chart="bar">Batang</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="alumniPerTahunChart"></canvas>
                    </div>
                    <div class="chart-footer">
                        <div class="chart-stats">
                            <div class="chart-stat">
                                <span class="stat-label">Rata-rata Pertumbuhan</span>
                                <span class="stat-value positive">+11.2%</span>
                            </div>
                            <div class="chart-stat">
                                <span class="stat-label">Pertumbuhan Tertinggi</span>
                                <span class="stat-value">2023 (+15.3%)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grafik Alumni per Jenis -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">Distribusi Jenis Pelatihan</h3>
                        <div class="chart-controls">
                            <button class="chart-btn active" data-chart="pie">Pie</button>
                            <button class="chart-btn" data-chart="doughnut">Donat</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="alumniPerJenisChart"></canvas>
                    </div>
                    <div class="chart-footer">
                        <div class="chart-stats">
                            <div class="chart-stat">
                                <span class="stat-label">Pelatihan Terpopuler</span>
                                <span class="stat-value">PKN (34.6%)</span>
                            </div>
                            <div class="chart-stat">
                                <span class="stat-label">Jumlah Pelatihan</span>
                                <span class="stat-value">5 Jenis</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Perbandingan Detail -->
            <div class="detail-chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Perbandingan per Jenis Pelatihan (5 Tahun)</h3>
                    <div class="year-selector">
                        <button class="year-btn active" data-year="all">5 Tahun</button>
                        <button class="year-btn" data-year="2023">2023</button>
                        <button class="year-btn" data-year="2022">2022</button>
                        <button class="year-btn" data-year="2021">2021</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="detailComparisonChart"></canvas>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('styles')
    <style>
        /* Hero Section */
        .pub-hero-section {
            position: relative;
            height: 400px;
            background: linear-gradient(135deg,
                    rgba(26, 58, 108, 0.95) 0%,
                    rgba(44, 90, 160, 0.95) 100%),
                url('https://www.sipena.info/images/gedung.png');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            color: white;
            overflow: hidden;
        }

        .pub-hero-content {
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
            animation: fadeInUp 1s ease-out;
        }

        .pub-hero-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.1;
        }

        .pub-hero-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .badge-text {
            font-size: 0.9rem;
            font-weight: 500;
            opacity: 0.9;
        }

        /* Stats Section */
            .stats-section {
                padding: 5rem 0;
                background: linear-gradient(to bottom, #fff 0%, #f8fafd 100%);
            }

            .section-subtitle {
                color: var(--gray-color);
                font-size: 1rem;
                margin-top: 0.5rem;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
            }

            .total-stats-card {
                background: white;
                border-radius: 20px;
                padding: 3rem;
                box-shadow: 0 20px 60px rgba(26, 58, 108, 0.1);
                margin: 2rem auto 4rem;
                max-width: 600px;
                text-align: center;
                position: relative;
                overflow: hidden;
            }

            .total-stats-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 5px;
                background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
            }

            .total-number {
                margin-bottom: 1.5rem;
            }

            .total-number .number {
                font-size: 4rem;
                font-weight: 800;
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                display: block;
                line-height: 1;
            }

            .total-number .label {
                font-size: 1.1rem;
                color: var(--gray-color);
                font-weight: 500;
                margin-top: 0.5rem;
                display: block;
            }

            .stats-trend {
                display: flex;
                justify-content: center;
            }

            .trend-indicator {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                border-radius: 50px;
                font-size: 0.9rem;
                font-weight: 600;
            }

            .trend-indicator.positive {
                background: rgba(76, 175, 80, 0.1);
                color: #4CAF50;
            }

            .trend-indicator svg {
                width: 16px;
                height: 16px;
            }

            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1.5rem;
                margin-top: 2rem;
            }

            .stat-card {
                background: white;
                border-radius: 16px;
                padding: 1.5rem;
                box-shadow: 0 10px 30px rgba(26, 58, 108, 0.08);
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .stat-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 20px 40px rgba(26, 58, 108, 0.15);
            }

            .stat-icon {
                width: 48px;
                height: 48px;
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1rem;
                color: white;
            }

            .stat-icon svg {
                width: 24px;
                height: 24px;
            }

            .stat-content {
                margin-bottom: 1rem;
            }

            .stat-number {
                font-size: 2rem;
                font-weight: 700;
                color: var(--primary-color);
                line-height: 1;
                margin-bottom: 0.25rem;
            }

            .stat-label {
                font-size: 0.9rem;
                color: var(--gray-color);
                font-weight: 500;
            }

            .stat-change {
                font-size: 0.8rem;
                font-weight: 600;
                margin-top: 0.25rem;
            }

            .stat-change.positive {
                color: #4CAF50;
            }

            .stat-progress {
                height: 4px;
                background: rgba(26, 58, 108, 0.1);
                border-radius: 2px;
                overflow: hidden;
            }

            .progress-bar {
                height: 100%;
                background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
                border-radius: 2px;
                transition: width 1s ease;
            }

            /* Charts Section - Responsif Utama */
            .charts-section {
                padding: 5rem 0;
                background: white;
            }

            .charts-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
                gap: 2rem;
                margin-bottom: 3rem;
            }

            .chart-card {
                background: white;
                border-radius: 20px;
                padding: 2rem;
                box-shadow: 0 10px 30px rgba(26, 58, 108, 0.08);
                border: 1px solid rgba(26, 58, 108, 0.1);
            }

            .chart-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1.5rem;
            }

            .chart-title {
                font-size: 1.2rem;
                font-weight: 600;
                color: var(--primary-color);
            }

            .chart-controls {
                display: flex;
                gap: 0.5rem;
            }

            .chart-btn {
                padding: 0.5rem 1rem;
                background: rgba(26, 58, 108, 0.05);
                border: 1px solid rgba(26, 58, 108, 0.1);
                border-radius: 8px;
                font-size: 0.8rem;
                font-weight: 500;
                color: var(--gray-color);
                cursor: pointer;
                transition: all 0.3s ease;
                white-space: nowrap;
            }

            .chart-btn:hover {
                background: rgba(26, 58, 108, 0.1);
            }

            .chart-btn.active {
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                color: white;
                border-color: transparent;
            }

            .chart-container {
                height: 300px;
                position: relative;
                margin: 1rem 0;
                width: 100%;
            }

            /* Grafik Container Responsif */
            .chart-container canvas {
                width: 100% !important;
                height: 100% !important;
                max-height: 300px;
            }

            .chart-footer {
                margin-top: 1.5rem;
                padding-top: 1.5rem;
                border-top: 1px solid rgba(26, 58, 108, 0.1);
            }

            .chart-stats {
                display: flex;
                justify-content: space-between;
                gap: 1rem;
                flex-wrap: wrap;
            }

            .chart-stat {
                display: flex;
                flex-direction: column;
                min-width: 120px;
            }

            .stat-label {
                font-size: 0.8rem;
                color: var(--gray-color);
                margin-bottom: 0.25rem;
            }

            .stat-value {
                font-size: 0.9rem;
                font-weight: 600;
                color: var(--dark-color);
            }

            .stat-value.positive {
                color: #4CAF50;
            }

            /* Detail Chart */
            .detail-chart-card {
                background: white;
                border-radius: 20px;
                padding: 2rem;
                box-shadow: 0 10px 30px rgba(26, 58, 108, 0.08);
                border: 1px solid rgba(26, 58, 108, 0.1);
            }

            .year-selector {
                display: flex;
                gap: 0.5rem;
                flex-wrap: wrap;
            }

            .year-btn {
                padding: 0.5rem 1rem;
                background: rgba(26, 58, 108, 0.05);
                border: 1px solid rgba(26, 58, 108, 0.1);
                border-radius: 8px;
                font-size: 0.8rem;
                font-weight: 500;
                color: var(--gray-color);
                cursor: pointer;
                transition: all 0.3s ease;
                white-space: nowrap;
            }

            .year-btn:hover {
                background: rgba(26, 58, 108, 0.1);
            }

            .year-btn.active {
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                color: white;
                border-color: transparent;
            }

            /* Animations */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Responsive Design untuk Grafik */
            @media (max-width: 1200px) {
                .charts-grid {
                    grid-template-columns: 1fr;
                }

                .chart-card, .detail-chart-card {
                    width: 100%;
                    margin: 0 auto;
                }
            }

            @media (max-width: 992px) {
                .pub-hero-title {
                    font-size: 2.5rem;
                }

                .total-number .number {
                    font-size: 3.5rem;
                }

                .stats-grid {
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                }

                .chart-container {
                    height: 250px;
                }

                .chart-container canvas {
                    max-height: 250px;
                }
            }

            @media (max-width: 768px) {
                .pub-hero-section {
                    height: 350px;
                }

                .pub-hero-title {
                    font-size: 2rem;
                }

                .pub-hero-subtitle {
                    font-size: 1rem;
                }

                .total-stats-card {
                    padding: 2rem;
                    margin: 2rem 0;
                }

                .total-number .number {
                    font-size: 3rem;
                }

                .chart-card, .detail-chart-card {
                    padding: 1.5rem;
                    border-radius: 16px;
                }

                .chart-header {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 1rem;
                    margin-bottom: 1rem;
                }

                .chart-controls, .year-selector {
                    width: 100%;
                    justify-content: center;
                    flex-wrap: wrap;
                }

                .chart-container {
                    height: 200px;
                    margin: 1rem 0;
                }

                .chart-container canvas {
                    max-height: 200px;
                }

                .chart-title {
                    font-size: 1.1rem;
                    text-align: center;
                    width: 100%;
                }

                .chart-stats {
                    flex-direction: column;
                    align-items: center;
                    gap: 0.75rem;
                }

                .chart-stat {
                    align-items: center;
                    text-align: center;
                    min-width: auto;
                }
            }

            @media (max-width: 576px) {
                .pub-hero-section {
                    height: 300px;
                }

                .pub-hero-title {
                    font-size: 1.8rem;
                }

                .total-number .number {
                    font-size: 2.5rem;
                }

                .stats-grid {
                    grid-template-columns: 1fr;
                    gap: 1rem;
                }

                .chart-container {
                    height: 180px;
                }

                .chart-container canvas {
                    max-height: 180px;
                }

                .chart-btn, .year-btn {
                    padding: 0.4rem 0.8rem;
                    font-size: 0.75rem;
                }

                .chart-footer {
                    margin-top: 1rem;
                    padding-top: 1rem;
                }

                .detail-chart-card .chart-container {
                    height: 200px;
                }

                .detail-chart-card .chart-container canvas {
                    max-height: 200px;
                }
            }

            @media (max-width: 380px) {
                .pub-hero-title {
                    font-size: 1.6rem;
                }

                .total-number .number {
                    font-size: 2rem;
                }

                .stat-number {
                    font-size: 1.5rem;
                }

                .chart-container {
                    height: 160px;
                }

                .chart-container canvas {
                    max-height: 160px;
                }

                .chart-card, .detail-chart-card {
                    padding: 1rem;
                    border-radius: 12px;
                }

                .chart-title {
                    font-size: 1rem;
                }

                .detail-chart-card .chart-container {
                    height: 180px;
                }

                .detail-chart-card .chart-container canvas {
                    max-height: 180px;
                }
            }

            /* Responsif untuk chart legend */
            @media (max-width: 768px) {
                .chartjs-legend {
                    display: flex !important;
                    flex-wrap: wrap !important;
                    justify-content: center !important;
                    margin-top: 10px !important;
                }

                .chartjs-legend li {
                    display: inline-flex !important;
                    margin: 5px 10px !important;
                    font-size: 0.8rem !important;
                }
            }

            @media (max-width: 480px) {
                .chartjs-legend {
                    flex-direction: column !important;
                    align-items: flex-start !important;
                }

                .chartjs-legend li {
                    margin: 3px 0 !important;
                    font-size: 0.75rem !important;
                }
            }
        </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initialize Charts dengan Responsive Options
        document.addEventListener('DOMContentLoaded', function () {
            // Responsive breakpoint untuk mobile
            const isMobile = window.innerWidth < 768;

            // Chart 1: Alumni per Tahun (Line/Bar)
            const alumniPerTahunCtx = document.getElementById('alumniPerTahunChart').getContext('2d');
            let alumniPerTahunChart = new Chart(alumniPerTahunCtx, {
                type: 'line',
                data: {
                    labels: ['2019', '2020', '2021', '2022', '2023'],
                    datasets: [{
                        label: 'Jumlah Alumni',
                        data: [3755, 4025, 4355, 4750, 5419],
                        borderColor: '#1a3a6c',
                        backgroundColor: 'rgba(26, 58, 108, 0.1)',
                        borderWidth: isMobile ? 2 : 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#1a3a6c',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: isMobile ? 4 : 6,
                        pointHoverRadius: isMobile ? 6 : 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(26, 58, 108, 0.9)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#fff',
                            borderWidth: 1,
                            callbacks: {
                                label: function (context) {
                                    return `Alumni: ${context.raw.toLocaleString()} orang`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(26, 58, 108, 0.1)'
                            },
                            ticks: {
                                callback: function (value) {
                                    return value.toLocaleString();
                                },
                                font: {
                                    size: isMobile ? 10 : 12
                                }
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(26, 58, 108, 0.1)'
                            },
                            ticks: {
                                font: {
                                    size: isMobile ? 10 : 12
                                }
                            }
                        }
                    }
                }
            });

            // Chart 2: Alumni per Jenis (Pie/Donut)
            const alumniPerJenisCtx = document.getElementById('alumniPerJenisChart').getContext('2d');
            let alumniPerJenisChart = new Chart(alumniPerJenisCtx, {
                type: 'pie',
                data: {
                    labels: ['PKN', 'PKA', 'PKP', 'Latsar', 'Teknis'],
                    datasets: [{
                        data: [8942, 5213, 4185, 3947, 3560],
                        backgroundColor: [
                            '#1a3a6c',
                            '#2c5aa0',
                            '#4a7bc8',
                            '#6c9ae4',
                            '#8db9ff'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: isMobile ? 'bottom' : 'right',
                            labels: {
                                padding: isMobile ? 10 : 20,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                boxWidth: isMobile ? 8 : 12,
                                font: {
                                    size: isMobile ? 10 : 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value.toLocaleString()} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Chart 3: Detail Comparison (Stacked Bar)
            const detailComparisonCtx = document.getElementById('detailComparisonChart').getContext('2d');
            let detailComparisonChart = new Chart(detailComparisonCtx, {
                type: 'bar',
                data: {
                    labels: ['2019', '2020', '2021', '2022', '2023'],
                    datasets: [
                        {
                            label: 'PKN',
                            data: [1325, 1425, 1520, 1650, 1845],
                            backgroundColor: '#1a3a6c',
                            stack: 'Stack 0'
                        },
                        {
                            label: 'PKA',
                            data: [815, 875, 950, 1020, 1125],
                            backgroundColor: '#2c5aa0',
                            stack: 'Stack 0'
                        },
                        {
                            label: 'PKP',
                            data: [575, 620, 685, 750, 892],
                            backgroundColor: '#4a7bc8',
                            stack: 'Stack 0'
                        },
                        {
                            label: 'Latsar',
                            data: [545, 580, 620, 685, 825],
                            backgroundColor: '#6c9ae4',
                            stack: 'Stack 0'
                        },
                        {
                            label: 'Teknis',
                            data: [495, 525, 580, 645, 732],
                            backgroundColor: '#8db9ff',
                            stack: 'Stack 0'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: isMobile ? 'bottom' : 'top',
                            labels: {
                                padding: isMobile ? 10 : 20,
                                boxWidth: isMobile ? 10 : 15,
                                font: {
                                    size: isMobile ? 10 : 12
                                }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function (context) {
                                    return `${context.dataset.label}: ${context.raw.toLocaleString()} alumni`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: true,
                            grid: {
                                color: 'rgba(26, 58, 108, 0.1)'
                            },
                            ticks: {
                                font: {
                                    size: isMobile ? 10 : 12
                                }
                            }
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(26, 58, 108, 0.1)'
                            },
                            ticks: {
                                callback: function (value) {
                                    return value.toLocaleString();
                                },
                                font: {
                                    size: isMobile ? 10 : 12
                                }
                            }
                        }
                    }
                }
            });

            // Chart Type Toggle
            document.querySelectorAll('.chart-btn[data-chart]').forEach(btn => {
                btn.addEventListener('click', function () {
                    const chartType = this.getAttribute('data-chart');
                    const chartContainer = this.closest('.chart-card');
                    const chartId = chartContainer.querySelector('canvas').id;

                    // Update active button
                    this.closest('.chart-controls').querySelectorAll('.chart-btn').forEach(b => {
                        b.classList.remove('active');
                    });
                    this.classList.add('active');

                    // Update chart type
                    if (chartId === 'alumniPerTahunChart') {
                        alumniPerTahunChart.config.type = chartType;
                        alumniPerTahunChart.update();
                    } else if (chartId === 'alumniPerJenisChart') {
                        alumniPerJenisChart.config.type = chartType === 'pie' ? 'pie' : 'doughnut';
                        alumniPerJenisChart.update();
                    }
                });
            });

            // Year Filter for Detail Chart
            document.querySelectorAll('.year-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const year = this.getAttribute('data-year');

                    // Update active button
                    this.closest('.year-selector').querySelectorAll('.year-btn').forEach(b => {
                        b.classList.remove('active');
                    });
                    this.classList.add('active');

                    // Filter data based on year
                    let labels = ['2019', '2020', '2021', '2022', '2023'];
                    let datasets = [
                        [1325, 1425, 1520, 1650, 1845],
                        [815, 875, 950, 1020, 1125],
                        [575, 620, 685, 750, 892],
                        [545, 580, 620, 685, 825],
                        [495, 525, 580, 645, 732]
                    ];

                    if (year !== 'all') {
                        const yearIndex = labels.indexOf(year);
                        if (yearIndex !== -1) {
                            labels = [year];
                            datasets = datasets.map(data => [data[yearIndex]]);
                        }
                    }

                    // Update chart
                    detailComparisonChart.data.labels = labels;
                    detailComparisonChart.data.datasets.forEach((dataset, index) => {
                        dataset.data = datasets[index];
                    });
                    detailComparisonChart.update();
                });
            });

            // Animate numbers
            function animateValue(element, start, end, duration) {
                let startTimestamp = null;
                const step = (timestamp) => {
                    if (!startTimestamp) startTimestamp = timestamp;
                    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                    const value = Math.floor(progress * (end - start) + start);
                    element.textContent = value.toLocaleString();
                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    }
                };
                window.requestAnimationFrame(step);
            }

            // Animate stats on scroll
            const observerOptions = {
                threshold: 0.2
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const statNumbers = entry.target.querySelectorAll('.stat-number');
                        statNumbers.forEach(stat => {
                            const value = parseInt(stat.textContent.replace(/,/g, ''));
                            stat.textContent = '0';
                            setTimeout(() => {
                                animateValue(stat, 0, value, 1500);
                            }, 300);
                        });
                    }
                });
            }, observerOptions);

            observer.observe(document.querySelector('.stats-grid'));

            // Progress bar animation
            document.querySelectorAll('.progress-bar').forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 500);
            });

            // Handle window resize untuk update chart responsif
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    alumniPerTahunChart.resize();
                    alumniPerJenisChart.resize();
                    detailComparisonChart.resize();
                }, 250);
            });
        });
    </script>
@endpush