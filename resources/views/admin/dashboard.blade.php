@extends('admin.partials.layout')

@section('title', 'Dashboard - LAN Pusjar SKMP')
@section('page-title', 'Dashboard')

@section('styles')
    <style>
        .welcome-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 20px;
            padding: 2.5rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(26, 58, 108, 0.2);
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
        }

        .welcome-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .welcome-card::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .welcome-header {
            position: relative;
            z-index: 1;
        }

        .welcome-card h2 {
            font-size: 1.9rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .welcome-card p {
            opacity: 0.95;
            margin-bottom: 0.25rem;
            font-size: 1rem;
        }

        .welcome-stats {
            display: flex;
            gap: 2rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        .welcome-stat {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .welcome-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .welcome-stat-content h5 {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
        }

        .welcome-stat-content small {
            opacity: 0.8;
            font-size: 0.85rem;
        }

       

        .stat-icon-container {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .stat-icon-container::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: inherit;
            opacity: 0.15;
        }

        .stat-icon-container.blue {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .stat-icon-container.green {
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
        }

        .stat-icon-container.orange {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            color: white;
        }

        .stat-icon-container.purple {
            background: linear-gradient(135deg, #8b5cf6, #a78bfa);
            color: white;
        }

        .stat-content {
            position: relative;
            z-index: 1;
        }

        .stat-value {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
            line-height: 1;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.95rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .stat-change {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.9rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .stat-change.positive {
            color: #065f46;
            background: #d1fae5;
        }

        .stat-change.negative {
            color: #991b1b;
            background: #fee2e2;
        }

        
        /* Responsive */
        @media (max-width: 1200px) {
            .welcome-stats {
                gap: 1.5rem;
            }
        }

        @media (max-width: 992px) {
            .welcome-card {
                padding: 2rem 1.5rem;
            }

            .welcome-card h2 {
                font-size: 1.6rem;
            }

            .welcome-stats {
                gap: 1rem;
            }

            .welcome-stat {
                flex: 1 1 calc(50% - 1rem);
            }
        }

        @media (max-width: 768px) {
            .welcome-card {
                padding: 1.75rem 1.25rem;
            }

            .welcome-card h2 {
                font-size: 1.4rem;
            }

            .welcome-stats {
                flex-direction: column;
                gap: 1rem;
            }

            .welcome-stat {
                flex: none;
                width: 100%;
            }

            .stat-card {
                margin-bottom: 1.5rem;
            }

            .stat-value {
                font-size: 2rem;
            }

            .stat-icon-container {
                width: 60px;
                height: 60px;
                font-size: 1.75rem;
            }
        }

        @media (max-width: 576px) {
            .welcome-card h2 {
                font-size: 1.3rem;
            }

            .welcome-card p {
                font-size: 0.95rem;
            }

            .stat-value {
                font-size: 1.75rem;
            }

            .stat-icon-container {
                width: 55px;
                height: 55px;
                font-size: 1.5rem;
            }
        }

        @media (max-width: 380px) {
            .welcome-card {
                padding: 1.5rem 1rem;
            }

            .welcome-card h2 {
                font-size: 1.2rem;
            }

            .stat-card {
                padding: 1.5rem;
            }

            .stat-value {
                font-size: 1.6rem;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

     

        

        #dashboardTime {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            font-family: 'Inter', monospace;
        }

        #dashboardDate {
            font-size: 1.1rem;
            color: #64748b;
            font-weight: 500;
        }
    </style>
@endsection

@section('content')
    <!-- Welcome Card -->
    <div class="welcome-card">
        <div class="welcome-header">
            <h2>Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
            <p>Sistem Manajemen Pembelajaran LAN Pusjar SKMP</p>
            <p><i class="fas fa-calendar-alt me-2"></i>Menyediakan platform terpadu untuk pengelolaan pembelajaran</p>
        </div>
        <div class="welcome-stats">
            <div class="welcome-stat">
                <div class="welcome-stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="welcome-stat-content">
                    <h5>1,248</h5>
                    <small>Total Peserta</small>
                </div>
            </div>
            <div class="welcome-stat">
                <div class="welcome-stat-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="welcome-stat-content">
                    <h5>48</h5>
                    <small>Kelas Aktif</small>
                </div>
            </div>
            <div class="welcome-stat">
                <div class="welcome-stat-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="welcome-stat-content">
                    <h5>96%</h5>
                    <small>Tingkat Kelulusan</small>
                </div>
            </div>
        </div>
    </div>


    
@endsection

@section('scripts')
    <script>
       

      

        // Animate progress bars on load
        window.addEventListener('load', function () {
            document.querySelectorAll('.progress-bar').forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.transition = 'width 1.5s ease-in-out';
                    bar.style.width = width;
                }, 300);
            });
        });
    </script>
@endsection