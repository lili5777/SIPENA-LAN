@extends('layouts.master')

@section('title', 'SIMPEL - Pendaftaran Berhasil')

@section('content')
        <section class="success-hero">
            <div class="container">
                <div class="success-content">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h1 class="success-title">Pendaftaran Berhasil!</h1>
                    <p class="success-message">
                        Terima kasih telah mendaftar. Data pendaftaran Anda telah berhasil dikirim dan sedang menunggu
                        verifikasi.
                    </p>

                    <div class="success-info">
                        <div class="info-card">
                            <h3><i class="fas fa-info-circle"></i> Informasi Pendaftaran</h3>
                            <div class="info-details">
                                <div class="info-item">
                                    <span class="info-label">Nama Peserta:</span>
                                    <span class="info-value">{{ $pendaftaran->peserta->nama_lengkap }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Email Peserta:</span>
                                    <span class="info-value">{{ $pendaftaran->peserta->email_pribadi }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Jenis Pelatihan:</span>
                                    <span class="info-value">{{ $pendaftaran->jenisPelatihan->nama_pelatihan }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Angkatan:</span>
                                    <span class="info-value">{{ $pendaftaran->angkatan->nama_angkatan }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Status:</span>
                                    <span class="info-value badge badge-warning">Menunggu Verifikasi</span>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    @if($pic && $pic->user)
<div class="success-info">
    <div class="info-card">
        <h3><i class="fas fa-user-tie"></i> PIC Pelatihan</h3>
        <div class="info-details">
            <div class="info-item">
                <span class="info-label">Nama PIC:</span>
                <span class="info-value">{{ $pic->user->name }}</span>
            </div>
            <div class="info-item">
    <span class="info-label">No. HP:</span>
    <span class="info-value">
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pic->user->no_telp) }}"
           target="_blank"
           style="color:#000000; text-decoration:none; font-weight:500;">
            {{ $pic->user->no_telp }}
        </a>
    </span>
</div>
        </div>
    </div>
</div>
@else
<div class="success-note">
    <p><strong>Informasi PIC</strong></p>
    <p>PIC untuk pelatihan ini belum ditentukan.</p>
</div>
@endif


                    <div class="action-buttons">
                        <a href="{{ route('pendaftaran.create') }}" class="action-btn action-primary">
                            <i class="fas fa-plus btn-icon"></i> Daftar Lagi
                        </a>
                        <a href="{{ url('/') }}" class="action-btn action-secondary">
                            <i class="fas fa-home btn-icon"></i> Kembali ke Beranda
                        </a>
                    </div>


                    <div class="success-note">
                        <p><strong>Catatan Penting:</strong></p>
                        <ul>
                            <li>Proses verifikasi membutuhkan waktu 3-14 hari kerja</li>
                            <li>Pastikan email dan nomor HP yang didaftarkan aktif</li>
                            <li>Mohon cek email Anda secara berkala, termasuk folder <b>Spam/Junk</b>, untuk memastikan tidak ada pesan yang
                                terlewat</li>
                            <li>Simpan Nomor PIC yang tercantum di atas (Hubungi Jika ada kendala atau pertanyaan)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <style>
            .action-buttons {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 0.95rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .action-primary {
        background: #1a3a6c;
        color: white;
    }

    .action-primary:hover {
        background: #0e2549;
        transform: translateY(-1px);
    }

    .action-secondary {
        background: #f8f9fa;
        color: #495057;
        border-color: #dee2e6;
    }

    .action-secondary:hover {
        background: #e9ecef;
        transform: translateY(-1px);
    }


    .btn-icon {
        font-size: 1.1rem;
    }

    @media (max-width: 640px) {
        .action-buttons {
            flex-direction: column;
            width: 100%;
        }

        .action-btn {
            justify-content: center;
            padding: 12px;
        }
    }
            .success-hero {
                background: linear-gradient(135deg, #f7fafc, #edf2f7);
                min-height: 100vh;
                display: flex;
                align-items: center;
                padding: 60px 0;
            }

            .success-content {
                max-width: 800px;
                margin: 0 auto;
                text-align: center;
                background: white;
                border-radius: 12px;
                padding: 40px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .success-icon {
                font-size: 5rem;
                color: var(--success-color);
                margin-bottom: 20px;
            }

            .success-title {
                font-size: 2.5rem;
                color: var(--primary-color);
                margin-bottom: 15px;
            }

            .success-message {
                font-size: 1.1rem;
                color: var(--gray-color);
                margin-bottom: 30px;
                line-height: 1.6;
            }

            .success-info {
                margin: 30px 0;
            }

            .info-card {
                background: #f8f9fa;
                border-radius: 8px;
                padding: 20px;
                text-align: left;
            }

            .info-card h3 {
                display: flex;
                align-items: center;
                gap: 10px;
                color: var(--primary-color);
                margin-bottom: 20px;
                font-size: 1.3rem;
            }

            .info-details {
                display: grid;
                gap: 15px;
            }

            .info-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding-bottom: 10px;
                border-bottom: 1px solid #e2e8f0;
            }

            .info-item:last-child {
                border-bottom: none;
            }

            .info-label {
                font-weight: 600;
                color: var(--dark-color);
            }

            .info-value {
                color: var(--gray-color);
            }

            .badge {
                padding: 4px 12px;
                border-radius: 20px;
                font-size: 0.9rem;
                font-weight: 500;
            }

            .badge-warning {
                background: #fed7d7;
                color: #c53030;
            }

            .success-actions {
                display: flex;
                gap: 15px;
                justify-content: center;
                margin: 30px 0;
                flex-wrap: wrap;
            }

            .success-note {
                margin-top: 30px;
                padding: 20px;
                background: #fffaf0;
                border-radius: 8px;
                border-left: 4px solid #ed8936;
                text-align: left;
            }

            .success-note p {
                color: var(--dark-color);
                font-weight: 500;
                margin-bottom: 10px;
            }

            .success-note ul {
                margin: 0;
                padding-left: 20px;
                color: var(--gray-color);
            }

            .success-note li {
                margin-bottom: 5px;
                line-height: 1.5;
            }

            @media (max-width: 768px) {
                .success-content {
                    padding: 20px;
                }

                .success-title {
                    font-size: 2rem;
                }

                .success-actions {
                    flex-direction: column;
                }

                .success-actions .btn {
                    width: 100%;
                }

                .info-item {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 5px;
                }
            }
        </style>
@endsection