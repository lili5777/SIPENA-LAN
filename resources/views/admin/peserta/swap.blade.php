@extends('admin.partials.layout')

@section('title', 'Swap/Tukar Peserta Antar Angkatan - Sistem Pelatihan')

@section('styles')
    <style>
        .swap-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .card-header-gradient {
            background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);
        }

        .info-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #285496;
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(40, 84, 150, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-weight: 600;
            color: #343a40;
            margin-bottom: 0;
        }

        .peserta-card {
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .peserta-card.asal {
            border-color: #285496;
            background-color: rgba(40, 84, 150, 0.05);
        }

        .peserta-card.tujuan {
            border-color: #28a745;
            background-color: rgba(40, 167, 69, 0.05);
        }

        .peserta-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #dee2e6;
        }

        .peserta-title {
            font-weight: 600;
            margin-bottom: 0;
        }

        .peserta-asal .peserta-title {
            color: #285496;
        }

        .peserta-tujuan .peserta-title {
            color: #28a745;
        }

        .ndh-badge {
            font-size: 0.9rem;
            font-weight: 700;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
            color: #212529;
        }

        .ndh-badge.sebelum {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
        }

        .ndh-badge.sesudah {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
            color: white;
        }

        .peserta-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.75rem;
            font-size: 0.85rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
        }

        .detail-icon {
            color: #6c757d;
            margin-right: 0.5rem;
            width: 16px;
        }

        .arrow-container {
            text-align: center;
            margin: 2rem 0;
        }

        .arrow-icon {
            font-size: 2rem;
            color: #6c757d;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }

        .angkatan-select-section {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .peserta-select-section {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .peserta-select-loading {
            text-align: center;
            padding: 2rem;
        }

        .peserta-select-option {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .peserta-select-option:hover {
            border-color: #285496;
            background-color: rgba(40, 84, 150, 0.05);
        }

        .peserta-select-option.active {
            border-color: #28a745;
            background-color: rgba(40, 167, 69, 0.1);
        }

        .swap-result-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border: 2px dashed #285496;
        }

        .swap-result-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .swap-result-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .swap-result-icon.asal {
            background: rgba(40, 84, 150, 0.1);
            color: #285496;
        }

        .swap-result-icon.tujuan {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .alert-warning-custom {
            background-color: rgba(255, 193, 7, 0.1);
            border-left: 4px solid #ffc107;
            border-radius: 8px;
            padding: 1rem;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #285496;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection

@section('content')
    <div class="swap-container">
        <!-- Alert Container -->
        <div class="alert-container mb-4"></div>

        <!-- Page Header -->
        <div class="page-header bg-gradient-primary rounded-3 mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                            <i class="fas fa-exchange-alt fa-lg" style="color: #285496;"></i>
                        </div>
                        <div>
                            <h1 class="text-white mb-1">Swap/Tukar Peserta Antar Angkatan</h1>
                            <p class="text-white-50 mb-0">Tukar tempat peserta dengan NDH mengikuti angkatan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Peserta Asal -->
        <div class="peserta-card asal mb-3">
            <div class="peserta-header">
                <div>
                    <h5 class="peserta-title mb-1">
                        <i class="fas fa-user me-2"></i>Peserta yang Akan Ditukar
                    </h5>
                    <small class="text-muted">Peserta ini akan berpindah ke angkatan lain</small>
                </div>
                <div class="ndh-badge">NDH: {{ $pendaftaranAsal->peserta->ndh ?? '-' }}</div>
            </div>

            <div class="peserta-details">
                <div class="detail-item">
                    <i class="fas fa-user-circle detail-icon"></i>
                    <span><strong>Nama:</strong> {{ $pendaftaranAsal->peserta->nama_lengkap }}</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-id-card detail-icon"></i>
                    <span><strong>NIP/NRP:</strong> {{ $pendaftaranAsal->peserta->nip_nrp }}</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-graduation-cap detail-icon"></i>
                    <span><strong>Angkatan Saat Ini:</strong> {{ $pendaftaranAsal->angkatan->nama_angkatan }}
                        ({{ $pendaftaranAsal->angkatan->tahun }})</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-building detail-icon"></i>
                    <span><strong>Instansi:</strong>
                        {{ $pendaftaranAsal->peserta->kepegawaianPeserta->asal_instansi ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Warning Alert -->
        <div class="alert alert-warning-custom mb-4">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-exchange-alt fa-2x text-warning"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-2">Fitur Swap/Tukar Peserta (NDH Ikut Angkatan)</h6>
                    <p class="mb-2"><strong>Contoh Kasus:</strong></p>
                    <ul class="mb-0">
                        <li><strong>Sebelum:</strong> Angkatan 1: Ali (NDH 2), Ida (NDH 1) | Angkatan 2: Rizal (NDH 1)</li>
                        <li><strong>Proses:</strong> Ali (NDH 2) ditukar dengan Rizal (NDH 1)</li>
                        <li><strong>Setelah:</strong> Angkatan 1: Rizal (NDH 2), Ida (NDH 1) | Angkatan 2: Ali (NDH 1)</li>
                    </ul>
                    <p class="mt-2 mb-0"><strong>Keterangan:</strong> NDH mengikuti angkatan, bukan peserta!</p>
                </div>
            </div>
        </div>

        <!-- Form Swap -->
        <div class="card border-0 shadow-lg">
            <div class="card-header card-header-gradient text-white">
                <h5 class="mb-0">
                    <i class="fas fa-cogs me-2"></i>Pilih Peserta untuk Ditukar
                </h5>
            </div>

            <form id="swapForm" method="POST"
                action="{{ route('peserta.swap.process', ['jenis' => $jenis, 'id' => $pendaftaranAsal->id]) }}">
                @csrf

                <div class="card-body">
                    <!-- Step 1: Pilih Angkatan Tujuan -->
                    <div class="angkatan-select-section">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-list me-1"></i>Pilih Angkatan Tujuan
                        </label>
                        <p class="text-muted small mb-3">Pilih angkatan yang memiliki peserta yang akan ditukar</p>

                        <select class="form-select" id="angkatanTujuanSelect" required>
                            <option value="">-- Pilih Angkatan --</option>
                            @foreach($angkatanTujuanList as $angkatan)
                                @php
                                    $jumlahPeserta = $angkatan->pendaftaran->count() ?: 0;

                                @endphp
                                <option value="{{ $angkatan->id }}" data-nama="{{ $angkatan->nama_angkatan }}"
                                    data-tahun="{{ $angkatan->tahun }}">
                                    {{ $angkatan->nama_angkatan }} ({{ $angkatan->tahun }}) - {{ $jumlahPeserta }} peserta
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Step 2: Pilih Peserta Tujuan -->
                    <div class="peserta-select-section" id="pesertaSelectSection" style="display: none;">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-user-friends me-1"></i>Pilih Peserta untuk Ditukar
                        </label>
                        <p class="text-muted small mb-3">
                            Pilih peserta dari angkatan <span id="selectedAngkatanNama" class="fw-semibold"></span>
                            yang akan ditukar dengan {{ $pendaftaranAsal->peserta->nama_lengkap }}
                        </p>

                        <div id="pesertaListContainer">
                            <!-- Peserta list will be loaded here via AJAX -->
                        </div>

                        <input type="hidden" name="angkatan_tujuan_id" id="selectedAngkatanId">
                        <input type="hidden" name="peserta_tujuan_id" id="selectedPesertaId">
                        <div class="invalid-feedback" id="pesertaError">Silakan pilih peserta untuk ditukar</div>
                    </div>

                    <!-- Step 3: Preview Hasil Tukar -->
                    <div id="swapPreview" style="display: none;">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-eye me-2"></i>Preview Hasil Tukar
                        </h6>

                        <div class="swap-result-container">
                            <!-- Peserta Asal (Ali) -->
                            <div class="swap-result-item">
                                <div class="swap-result-icon asal">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $pendaftaranAsal->peserta->nama_lengkap }}</h6>
                                            <p class="mb-1 small text-muted">
                                                <i class="fas fa-id-card me-1"></i>{{ $pendaftaranAsal->peserta->nip_nrp }}
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <div class="mb-1">
                                                <small class="text-muted">Sebelum:</small>
                                                <span class="ndh-badge sebelum ms-2">NDH
                                                    {{ $pendaftaranAsal->peserta->ndh ?? '-' }}</span>
                                            </div>
                                            <div>
                                                <small class="text-muted">Sesudah:</small>
                                                <span class="ndh-badge sesudah ms-2" id="previewNdhAsalSesudah">NDH ?</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0 small">
                                        <i class="fas fa-arrow-right me-1 text-primary"></i>
                                        Pindah ke: <span id="previewAngkatanAsal" class="fw-semibold"></span>
                                    </p>
                                </div>
                            </div>

                            <!-- Arrow -->
                            <div class="text-center my-2">
                                <i class="fas fa-exchange-alt fa-lg text-muted"></i>
                            </div>

                            <!-- Peserta Tujuan (Rizal) -->
                            <div class="swap-result-item">
                                <div class="swap-result-icon tujuan">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1" id="previewNamaTujuan">-</h6>
                                            <p class="mb-1 small text-muted">
                                                <i class="fas fa-id-card me-1"></i><span id="previewNipTujuan">-</span>
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <div class="mb-1">
                                                <small class="text-muted">Sebelum:</small>
                                                <span class="ndh-badge sebelum ms-2" id="previewNdhTujuanSebelum">NDH
                                                    ?</span>
                                            </div>
                                            <div>
                                                <small class="text-muted">Sesudah:</small>
                                                <span class="ndh-badge sesudah ms-2" id="previewNdhTujuanSesudah">NDH
                                                    ?</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0 small">
                                        <i class="fas fa-arrow-right me-1 text-success"></i>
                                        Pindah ke: <span
                                            class="fw-semibold">{{ $pendaftaranAsal->angkatan->nama_angkatan }}</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Summary -->
                            <div class="mt-3 p-3 bg-white rounded border">
                                <h6 class="fw-bold mb-2 text-center">Ringkasan Pertukaran NDH</h6>
                                <p class="mb-0 text-center">
                                    <strong>{{ $pendaftaranAsal->peserta->nama_lengkap }}</strong> (NDH
                                    {{ $pendaftaranAsal->peserta->ndh ?? '-' }})
                                    <i class="fas fa-exchange-alt mx-2"></i>
                                    <strong><span id="previewNamaTujuan2">-</span></strong> (NDH <span
                                        id="previewNdhTujuan2">?</span>)
                                </p>
                                <p class="mb-0 text-center small text-muted">
                                    NDH akan mengikuti angkatan tujuan masing-masing
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-sticky-note me-1"></i>Catatan (Opsional)
                        </label>
                        <textarea class="form-control" name="catatan_swap" id="catatanSwap" rows="3"
                            placeholder="Tambahkan catatan alasan penukaran..."></textarea>
                        <small class="text-muted">Catatan akan tercatat dalam riwayat aktivitas kedua peserta</small>
                    </div>
                </div>

                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('peserta.index', ['jenis' => $jenis]) }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-times me-2"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4" id="submitBtn" disabled>
                            <i class="fas fa-exchange-alt me-2"></i> Proses Tukar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const angkatanSelect = document.getElementById('angkatanTujuanSelect');
            const pesertaSelectSection = document.getElementById('pesertaSelectSection');
            const pesertaListContainer = document.getElementById('pesertaListContainer');
            const swapPreview = document.getElementById('swapPreview');
            const submitBtn = document.getElementById('submitBtn');
            const swapForm = document.getElementById('swapForm');
            const loadingOverlay = document.getElementById('loadingOverlay');

            const selectedAngkatanId = document.getElementById('selectedAngkatanId');
            const selectedPesertaId = document.getElementById('selectedPesertaId');
            const selectedAngkatanNama = document.getElementById('selectedAngkatanNama');

            // Preview elements
            const previewAngkatanAsal = document.getElementById('previewAngkatanAsal');
            const previewNamaTujuan = document.getElementById('previewNamaTujuan');
            const previewNipTujuan = document.getElementById('previewNipTujuan');
            const previewNamaTujuan2 = document.getElementById('previewNamaTujuan2');

            // NDH preview elements
            const previewNdhAsalSesudah = document.getElementById('previewNdhAsalSesudah');
            const previewNdhTujuanSebelum = document.getElementById('previewNdhTujuanSebelum');
            const previewNdhTujuanSesudah = document.getElementById('previewNdhTujuanSesudah');
            const previewNdhTujuan2 = document.getElementById('previewNdhTujuan2');

            // Current NDH of peserta asal
            const currentNdhAsal = {{ $pendaftaranAsal->peserta->ndh ?? 0 }};

            // When angkatan is selected
            angkatanSelect.addEventListener('change', function () {
                const angkatanId = this.value;
                const selectedOption = this.options[this.selectedIndex];
                const angkatanNama = selectedOption.getAttribute('data-nama');
                const angkatanTahun = selectedOption.getAttribute('data-tahun');

                if (!angkatanId) {
                    pesertaSelectSection.style.display = 'none';
                    swapPreview.style.display = 'none';
                    submitBtn.disabled = true;
                    return;
                }

                // Show peserta selection section
                pesertaSelectSection.style.display = 'block';
                selectedAngkatanId.value = angkatanId;
                selectedAngkatanNama.textContent = `${angkatanNama} (${angkatanTahun})`;
                previewAngkatanAsal.textContent = `${angkatanNama} (${angkatanTahun})`;

                // Show loading
                pesertaListContainer.innerHTML = `
                <div class="peserta-select-loading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat daftar peserta...</p>
                </div>
            `;

                // Load peserta list via AJAX
                loadPesertaList(angkatanId);
            });

            // Function to load peserta list
            // Function to load peserta list
            async function loadPesertaList(angkatanId) {
                try {
                    const response = await fetch(`/peserta/get-peserta-angkatan?jenis={{ $jenis }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            angkatan_id: angkatanId,
                            exclude_peserta_id: {{ $pendaftaranAsal->peserta->id }}
            })
                    });

                    // Perhatikan: Saya menambahkan log untuk debugging
                    console.log('Response status:', response.status);
                    console.log('Response URL:', response.url);

                    const result = await response.json();

                    console.log('Result:', result); // Debug log

                    if (result.success) {
                        if (result.data.length === 0) {
                            pesertaListContainer.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Tidak ada peserta lain di angkatan ini
                    </div>
                `;
                            swapPreview.style.display = 'none';
                            submitBtn.disabled = true;
                            return;
                        }

                        // Render peserta list
                        let html = '';
                        result.data.forEach(peserta => {
                            html += `
                    <div class="peserta-select-option" 
                         data-pendaftaran-id="${peserta.id}"
                         data-peserta-id="${peserta.peserta_id}"
                         data-nama="${peserta.nama}"
                         data-nip="${peserta.nip_nrp}"
                         data-ndh="${peserta.ndh}"
                         data-instansi="${peserta.asal_instansi}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">${peserta.nama}</h6>
                                <p class="mb-1 small text-muted">
                                    <i class="fas fa-id-card me-1"></i>${peserta.nip_nrp}
                                </p>
                                <p class="mb-1 small text-muted">
                                    <i class="fas fa-building me-1"></i>${peserta.asal_instansi}
                                </p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-warning">NDH: ${peserta.ndh || '-'}</span>
                            </div>
                        </div>
                    </div>
                `;
                        });

                        pesertaListContainer.innerHTML = html;

                        // Add click event to each peserta option
                        document.querySelectorAll('.peserta-select-option').forEach(option => {
                            option.addEventListener('click', function () {
                                // Remove active class from all options
                                document.querySelectorAll('.peserta-select-option').forEach(opt => {
                                    opt.classList.remove('active');
                                });

                                // Add active class to selected option
                                this.classList.add('active');

                                // Set selected peserta
                                selectedPesertaId.value = this.getAttribute('data-pendaftaran-id');

                                // Update preview with NDH swap logic
                                updatePreview(
                                    this.getAttribute('data-nama'),
                                    this.getAttribute('data-nip'),
                                    this.getAttribute('data-ndh'),
                                    this.getAttribute('data-instansi')
                                );

                                // Show preview and enable submit button
                                swapPreview.style.display = 'block';
                                submitBtn.disabled = false;
                            });
                        });

                    } else {
                        console.error('API Error:', result); // Debug log
                        showAlert('error', result.message || 'Gagal memuat daftar peserta');
                        pesertaListContainer.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Gagal memuat daftar peserta
                </div>
            `;
                    }

                } catch (error) {
                    console.error('Error loading peserta list:', error);
                    showAlert('error', 'Terjadi kesalahan saat memuat daftar peserta');
                    pesertaListContainer.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                Error: ${error.message}
            </div>
        `;
                }
            }

            // Function to update preview with NDH swap logic
            function updatePreview(nama, nip, ndhTujuan, instansi) {
                // Update basic info
                previewNamaTujuan.textContent = nama;
                previewNipTujuan.textContent = nip;
                previewNamaTujuan2.textContent = nama;

                // Update NDH preview with swap logic
                const ndhAsal = currentNdhAsal; // NDH Ali (2)

                // Peserta tujuan sebelum swap (Rizal: NDH 1)
                previewNdhTujuanSebelum.textContent = `NDH ${ndhTujuan}`;
                previewNdhTujuan2.textContent = ndhTujuan;

                // Setelah swap:
                // Ali pindah ke Angkatan 2, dapat NDH Rizal (1)
                previewNdhAsalSesudah.textContent = `NDH ${ndhTujuan}`;

                // Rizal pindah ke Angkatan 1, dapat NDH Ali (2)
                previewNdhTujuanSesudah.textContent = `NDH ${ndhAsal}`;

                // Update preview for peserta asal
                const selectedOption = angkatanSelect.options[angkatanSelect.selectedIndex];
                const angkatanNama = selectedOption.getAttribute('data-nama');
                const angkatanTahun = selectedOption.getAttribute('data-tahun');
                previewAngkatanAsal.textContent = `${angkatanNama} (${angkatanTahun})`;
            }

            // Form submission
            swapForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                // Validation
                if (!selectedAngkatanId.value || !selectedPesertaId.value) {
                    showAlert('error', 'Silakan pilih angkatan dan peserta untuk ditukar');
                    return;
                }

                // Get selected peserta info for confirmation message
                const selectedOption = document.querySelector('.peserta-select-option.active');
                const namaTujuan = selectedOption ? selectedOption.getAttribute('data-nama') : '';
                const ndhTujuan = selectedOption ? selectedOption.getAttribute('data-ndh') : '';

                // Confirm action with NDH swap details
                const confirmMessage =
                    `Apakah Anda yakin ingin menukar tempat:\n\n` +
                    `• ${namaTujuan} (NDH ${ndhTujuan || '?'}) dari angkatan ${selectedAngkatanNama.textContent}\n` +
                    `• Dengan {{ $pendaftaranAsal->peserta->nama_lengkap }} (NDH {{ $pendaftaranAsal->peserta->ndh ?? '?' }}) dari {{ $pendaftaranAsal->angkatan->nama_angkatan }}\n\n` +
                    `Setelah swap:\n` +
                    `• {{ $pendaftaranAsal->peserta->nama_lengkap }} akan pindah ke ${selectedAngkatanNama.textContent} dengan NDH ${ndhTujuan || '?'}\n` +
                    `• ${namaTujuan} akan pindah ke {{ $pendaftaranAsal->angkatan->nama_angkatan }} dengan NDH {{ $pendaftaranAsal->peserta->ndh ?? '?' }}\n\n` +
                    `Tindakan ini tidak dapat dibatalkan.`;

                if (!confirm(confirmMessage)) {
                    return;
                }

                // Show loading
                loadingOverlay.classList.add('active');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memproses...';

                try {
                    const formData = new FormData(this);

                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        showAlert('success', result.message);

                        // Redirect after delay
                        setTimeout(() => {
                            window.location.href = result.redirect_url || '{{ route("peserta.index", ["jenis" => $jenis]) }}';
                        }, 1500);
                    } else {
                        showAlert('error', result.message || 'Terjadi kesalahan');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-exchange-alt me-2"></i> Proses Tukar';
                    }

                } catch (error) {
                    console.error('Error:', error);
                    showAlert('error', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-exchange-alt me-2"></i> Proses Tukar';
                } finally {
                    loadingOverlay.classList.remove('active');
                }
            });

            // Alert function
            function showAlert(type, message) {
                // Remove existing alerts
                const oldAlerts = document.querySelectorAll('.alert-container .alert');
                oldAlerts.forEach(alert => {
                    if (alert.parentNode) {
                        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                        bsAlert.close();
                    }
                });

                // Create new alert
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show shadow-sm d-flex align-items-center`;

                const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
                const title = type === 'success' ? 'Sukses!' : 'Error!';

                alertDiv.innerHTML = `
                <div class="alert-icon flex-shrink-0">
                    <i class="fas ${iconClass} fa-lg"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <strong>${title}</strong> ${message}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;

                // Add to page
                document.querySelector('.alert-container').prepend(alertDiv);

                // Auto close after 5 seconds
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        const bsAlert = bootstrap.Alert.getOrCreateInstance(alertDiv);
                        bsAlert.close();
                    }
                }, 5000);
            }
        });
    </script>
@endsection