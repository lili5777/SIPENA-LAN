@extends('admin.partials.layout')

@section('title', 'Peserta PKN TK II - Sistem Pelatihan')

@section('content')
    <!-- Page Header -->
    <div class="page-header bg-gradient-primary rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-users fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">Peserta {{ $jenisPelatihan->nama_pelatihan ?? 'PKN TK II' }}</h1>
                        <p class="text-white-50 mb-0">Kelola peserta pelatihan PKN TK II</p>
                        <div class="d-flex align-items-center text-white-75 mt-2">
                            <span class="me-3">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $angkatanSelected->nama_angkatan ?? 'Semua Angkatan' }}
                            </span>
                            <span>
                                <i class="fas fa-user-friends me-1"></i>
                                {{ $pendaftaran->count() }} Peserta
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a href="#" class="btn btn-light btn-hover-lift shadow-sm d-flex align-items-center" data-bs-toggle="modal"
                    data-bs-target="#addPesertaModal">
                    <i class="fas fa-user-plus me-2"></i>
                    Tambah Peserta
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Section -->
    <div class="alert-container mb-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
                <div class="alert-icon flex-shrink-0">
                    <i class="fas fa-check-circle fa-lg"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <strong>Sukses!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
                <div class="alert-icon flex-shrink-0">
                    <i class="fas fa-exclamation-circle fa-lg"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <strong>Error!</strong> {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Filter Angkatan -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-filter me-2" style="color: #285496;"></i> Filter Angkatan
                    </h6>
                </div>
                <div class="col-md-8">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('peserta.pkn-tk2') }}"
                            class="btn btn-sm {{ !request('angkatan') ? 'btn-primary' : 'btn-outline-primary' }}">
                            Semua Angkatan
                        </a>
                        @foreach($angkatanList as $angkatan)
                            <a href="{{ route('peserta.pkn-tk2', ['angkatan' => $angkatan->id]) }}"
                                class="btn btn-sm {{ request('angkatan') == $angkatan->id ? 'btn-primary' : 'btn-outline-primary' }}">
                                {{ $angkatan->nama_angkatan }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Pending</h6>
                            <h3 class="mb-0 fw-bold text-warning">
                                {{ $pendaftaran->where('status_pendaftaran', 'pending')->count() }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Diterima</h6>
                            <h3 class="mb-0 fw-bold text-info">
                                {{ $pendaftaran->where('status_pendaftaran', 'diterima')->count() }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-danger bg-opacity-10 text-danger me-3">
                            <i class="fas fa-user-times"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Ditolak</h6>
                            <h3 class="mb-0 fw-bold text-danger">
                                {{ $pendaftaran->where('status_pendaftaran', 'ditolak')->count() }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Lulus</h6>
                            <h3 class="mb-0 fw-bold text-success">
                                {{ $pendaftaran->where('status_pendaftaran', 'lulus')->count() }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Peserta Table Card -->
    <div class="card border-0 shadow-lg overflow-hidden">
        <div class="card-header bg-white py-3 border-0">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-list me-2" style="color: #285496;"></i> Daftar Peserta
                    </h5>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" id="searchInput"
                            placeholder="Cari nama, NIP, atau instansi...">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch" style="display: none;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="pesertaTable">
                    <thead>
                        <tr class="table-light">
                            <th width="5%" class="ps-4">No</th>
                            <th width="25%">Identitas</th>
                            <th width="15%" class="d-none d-md-table-cell">Instansi</th>
                            <th width="15%" class="d-none d-md-table-cell">Angkatan</th>
                            <th width="15%">Status</th>
                            <th width="25%" class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendaftaran as $index => $daftar)
                            @php
                                $peserta = $daftar->peserta;
                                $kepegawaian = $peserta->kepegawaianPeserta;
                                $mentor = $daftar->pesertaMentor->first()?->mentor ?? null;
                            @endphp
                            <tr class="peserta-row" data-peserta-id="{{ $daftar->id }}">
                                <td class="ps-4 fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-3"
                                            style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold peserta-name">{{ $peserta->nama_lengkap }}</div>
                                            <div class="text-muted small">
                                                <i class="fas fa-id-card me-1"></i>
                                                {{ $peserta->nip_nrp ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <p class="mb-0 text-muted peserta-instansi">
                                        <i class="fas fa-building me-1"></i>
                                        {{ $kepegawaian->unit_kerja ?? '-' }}
                                    </p>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <p class="mb-0 text-muted peserta-angkatan">
                                        {{ $daftar->angkatan->nama_angkatan ?? '-' }}
                                    </p>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-warning text-dark',
                                                'diterima' => 'bg-info',
                                                'ditolak' => 'bg-danger',
                                                'lulus' => 'bg-success'
                                            ];
                                            $statusColor = $statusColors[$daftar->status_pendaftaran] ?? 'bg-secondary';
                                            $statusIcons = [
                                                'pending' => 'fa-clock',
                                                'diterima' => 'fa-check',
                                                'ditolak' => 'fa-times',
                                                'lulus' => 'fa-graduation-cap'
                                            ];
                                            $statusIcon = $statusIcons[$daftar->status_pendaftaran] ?? 'fa-question';
                                        @endphp
                                        <span class="badge {{ $statusColor }} peserta-status">
                                            <i class="fas {{ $statusIcon }} me-1"></i>
                                            {{ ucfirst($daftar->status_pendaftaran) }}
                                        </span>
                                        <button type="button" class="btn btn-sm btn-outline-warning update-status"
                                            data-id="{{ $daftar->id }}" data-status="{{ $daftar->status_pendaftaran }}"
                                            data-bs-toggle="tooltip" title="Ubah Status">
                                            <i class="fas fa-edit me-1"></i> Verifikasi
                                        </button>
                                    </div>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-info btn-action view-detail"
                                            data-id="{{ $daftar->id }}" data-bs-toggle="tooltip" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="#" class="btn btn-sm btn-outline-warning btn-action" data-bs-toggle="tooltip"
                                            title="Edit Peserta">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-action delete-peserta"
                                            data-id="{{ $daftar->id }}" data-name="{{ $peserta->nama_lengkap }}"
                                            data-bs-toggle="tooltip" title="Hapus Peserta">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-state-row">
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mb-3">
                                            <i class="fas fa-users fa-4x" style="color: #e9ecef;"></i>
                                        </div>
                                        <h5 class="text-muted mb-2">Belum ada peserta</h5>
                                        <p class="text-muted mb-4">Tidak ada peserta yang terdaftar pada angkatan ini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($pendaftaran->count() > 0)
            <div class="card-footer bg-white py-3 border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <small class="text-muted">
                            Menampilkan {{ $pendaftaran->count() }} peserta
                        </small>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

<!-- Add Peserta Modal -->
<div class="modal fade" id="addPesertaModal" tabindex="-1" aria-labelledby="addPesertaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center px-4 pb-4">
                <div class="add-icon mb-3">
                    <i class="fas fa-user-plus fa-3x" style="color: #285496;"></i>
                </div>
                <h4 class="modal-title mb-3 fw-bold" id="addPesertaModalLabel">Tambah Peserta</h4>
                <p class="text-muted mb-4">
                    Fitur tambah peserta sedang dalam pengembangan
                </p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white bg-opacity-20 rounded-3 p-2 me-3">
                        <i class="fas fa-user-circle fa-lg text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white mb-0 fw-bold" id="detailModalLabel">
                            Detail Peserta
                        </h5>
                        <small class="text-white-75" id="detailModalSubtitle"></small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="detail-container p-4" style="max-height: 70vh; overflow-y: auto;">
                    <div id="detailContent">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3 text-muted">Memuat data peserta...</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                <div class="modal-body text-center px-4 pb-4">
                    <div class="status-icon mb-3">
                        <i class="fas fa-edit fa-3x" style="color: #285496;"></i>
                    </div>
                    <h4 class="modal-title mb-3 fw-bold" id="statusModalLabel">Verifikasi Status</h4>

                    <div class="mb-4 text-start">
                        <label class="form-label fw-semibold">Status Saat Ini</label>
                        <div id="currentStatus" class="fw-bold"></div>
                    </div>

                    <div class="mb-4 text-start">
                        <label class="form-label fw-semibold">Status Baru</label>
                        <select class="form-select" name="status_pendaftaran" id="statusSelect" required>
                            <option value="pending">Pending</option>
                            <option value="diterima">Diterima</option>
                            <option value="ditolak">Ditolak</option>
                            <option value="lulus">Lulus</option>
                        </select>
                    </div>

                    <div class="mb-4 text-start">
                        <label class="form-label fw-semibold">Catatan (Opsional)</label>
                        <textarea class="form-control" name="catatan_verifikasi" id="catatanInput" rows="3"
                            placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>

                    <p class="text-muted small mb-4">
                        <i class="fas fa-info-circle me-1"></i>
                        Perubahan status akan tercatat di riwayat peserta
                    </p>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary px-4 btn-lift">
                        <i class="fas fa-save me-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center px-4 pb-4">
                <div class="delete-icon mb-3">
                    <i class="fas fa-exclamation-triangle fa-4x" style="color: #ff4757;"></i>
                </div>
                <h4 class="modal-title mb-3 fw-bold" id="deleteModalLabel">Konfirmasi Hapus</h4>
                <p class="text-muted mb-1">Anda akan menghapus peserta:</p>
                <h5 class="text-danger mb-4 fw-bold" id="deletePesertaName"></h5>

                <p class="text-muted small mb-4">
                    <i class="fas fa-info-circle me-1"></i>
                    Tindakan ini tidak dapat dibatalkan. Semua data peserta akan dihapus.
                </p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Batal
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4 btn-lift">
                        <i class="fas fa-trash-alt me-2"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Modals
            const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
            const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

            // View Detail
            document.querySelectorAll('.view-detail').forEach(button => {
                button.addEventListener('click', function () {
                    const pendaftaranId = this.getAttribute('data-id');
                    loadDetailData(pendaftaranId);
                    detailModal.show();
                });
            });

            // Update Status
            document.querySelectorAll('.update-status').forEach(button => {
                button.addEventListener('click', function () {
                    const pendaftaranId = this.getAttribute('data-id');
                    const currentStatus = this.getAttribute('data-status');

                    // Set form action
                    const form = document.getElementById('statusForm');
                    form.action = `update-status/${pendaftaranId}`;

                    // Set current status
                    const currentStatusElement = document.getElementById('currentStatus');
                    const statusColors = {
                        'pending': 'warning',
                        'diterima': 'info',
                        'ditolak': 'danger',
                        'lulus': 'success'
                    };
                    const statusText = currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1);
                    const statusColor = statusColors[currentStatus] || 'secondary';

                    currentStatusElement.innerHTML = `
                            <span class="badge bg-${statusColor}">
                                <i class="fas ${getStatusIcon(currentStatus)} me-1"></i>
                                ${statusText}
                            </span>
                        `;

                    // Set current status in select
                    const statusSelect = document.getElementById('statusSelect');
                    statusSelect.value = currentStatus;

                    statusModal.show();
                });
            });

            // Delete Peserta
            const deleteForm = document.getElementById('deleteForm');
            const deletePesertaName = document.getElementById('deletePesertaName');

            document.querySelectorAll('.delete-peserta').forEach(button => {
                button.addEventListener('click', function () {
                    const pesertaId = this.getAttribute('data-id');
                    const pesertaName = this.getAttribute('data-name');

                    deletePesertaName.textContent = pesertaName;
                    deleteForm.action = `{{ url('peserta') }}/${pesertaId}`;
                    deleteModal.show();
                });
            });

            // Status Form Submission
            const statusForm = document.getElementById('statusForm');
            statusForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const action = this.action;

                try {
                    const response = await fetch(action, {
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
                        statusModal.hide();
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showAlert('error', result.message || 'Terjadi kesalahan');
                    }
                } catch (error) {
                    showAlert('error', 'Terjadi kesalahan jaringan');
                }
            });

            // Load Detail Data
            async function loadDetailData(pendaftaranId) {
                const detailContent = document.getElementById('detailContent');
                detailContent.innerHTML = `
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3 text-muted">Memuat data peserta...</p>
                        </div>
                    `;

                try {
                    const response = await fetch(`detail/${pendaftaranId}`);
                    const result = await response.json();

                    if (result.success) {
                        const data = result.data;
                        detailContent.innerHTML = generateDetailHTML(data);
                        // Update modal subtitle
                        document.getElementById('detailModalSubtitle').textContent =
                            `${data.peserta.nip_nrp || 'NIP/NRP tidak tersedia'} • ${data.angkatan?.nama_angkatan || ''}`;
                    } else {
                        detailContent.innerHTML = `
                                <div class="text-center py-5">
                                    <div class="empty-state-icon mb-3">
                                        <i class="fas fa-exclamation-circle fa-4x" style="color: #e9ecef;"></i>
                                    </div>
                                    <h5 class="text-muted mb-2">Gagal memuat data</h5>
                                    <p class="text-muted">Silakan coba lagi</p>
                                </div>
                            `;
                    }
                } catch (error) {
                    detailContent.innerHTML = `
                            <div class="text-center py-5">
                                <div class="empty-state-icon mb-3">
                                    <i class="fas fa-exclamation-circle fa-4x" style="color: #e9ecef;"></i>
                                </div>
                                <h5 class="text-muted mb-2">Terjadi kesalahan</h5>
                                <p class="text-muted">Silakan coba lagi</p>
                            </div>
                        `;
                }
            }

            // Generate Detail HTML
            function generateDetailHTML(data) {
                const peserta = data.peserta;
                const kepegawaian = data.kepegawaian;
                const angkatan = data.angkatan;
                const mentor = data.mentor;
                const pendaftaran = data.pendaftaran;

                return `
                        <div class="detail-content">
                            <!-- Header -->
                            <div class="detail-header mb-4 pb-3 border-bottom">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="detail-avatar me-3 flex-shrink-0"
                                        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%); 
                                               width: 70px; height: 70px; border-radius: 15px; 
                                               display: flex; align-items: center; justify-content: center; 
                                               color: white; font-size: 2rem; box-shadow: 0 6px 15px rgba(40, 84, 150, 0.3);">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h4 class="mb-1 fw-bold">${peserta.nama_lengkap}</h4>
                                        <p class="text-muted mb-2">
                                            <i class="fas fa-id-card me-1"></i>
                                            ${peserta.nip_nrp || 'NIP/NRP tidak tersedia'}
                                        </p>
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3">
                                                <i class="fas fa-calendar me-1"></i>
                                                ${angkatan?.nama_angkatan || '-'}
                                            </span>
                                            <span class="badge ${getStatusClass(pendaftaran.status_pendaftaran)} 
                                                  bg-opacity-10 text-${getStatusColor(pendaftaran.status_pendaftaran)} 
                                                  border border-${getStatusColor(pendaftaran.status_pendaftaran)} border-opacity-25 rounded-pill px-3">
                                                <i class="fas ${getStatusIcon(pendaftaran.status_pendaftaran)} me-1"></i>
                                                ${pendaftaran.status_pendaftaran.toUpperCase()}
                                            </span>
                                            ${mentor?.nama_mentor ?
                        `<span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-3">
                                                    <i class="fas fa-chalkboard-teacher me-1"></i>
                                                    ${mentor.nama_mentor}
                                                </span>` : ''
                    }
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <!-- Data Pribadi -->
                                <div class="col-lg-6">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-light bg-opacity-25 border-0">
                                            <h6 class="mb-0 fw-bold">
                                                <i class="fas fa-user me-2 text-primary"></i> Data Pribadi
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <label class="small text-muted">Nama Lengkap</label>
                                                    <p class="fw-semibold mb-2">${peserta.nama_lengkap || '-'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted">NIP/NRP</label>
                                                    <p class="fw-semibold mb-2">${peserta.nip_nrp || '-'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted">Jenis Kelamin</label>
                                                    <p class="fw-semibold mb-2">${peserta.jenis_kelamin || '-'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted">Tempat Lahir</label>
                                                    <p class="fw-semibold mb-2">${peserta.tempat_lahir || '-'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted">Tanggal Lahir</label>
                                                    <p class="fw-semibold mb-2">${peserta.tanggal_lahir ? formatDate(peserta.tanggal_lahir) : '-'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted">Agama</label>
                                                    <p class="fw-semibold mb-2">${peserta.agama || '-'}</p>
                                                </div>
                                                <div class="col-12">
                                                    <label class="small text-muted">Alamat Rumah</label>
                                                    <p class="fw-semibold mb-2">${peserta.alamat_rumah || '-'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted">Email Pribadi</label>
                                                    <p class="fw-semibold mb-2">${peserta.email_pribadi || '-'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted">Nomor HP/WhatsApp</label>
                                                    <p class="fw-semibold mb-2">${peserta.nomor_hp || '-'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted">Pendidikan Terakhir</label>
                                                    <p class="fw-semibold mb-2">${peserta.pendidikan_terakhir || '-'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted">Bidang Studi</label>
                                                    <p class="fw-semibold mb-2">${peserta.bidang_studi || '-'}</p>
                                                </div>
                                                <div class="col-12">
                                                    <label class="small text-muted">Bidang Keahlian</label>
                                                    <p class="fw-semibold mb-2">${peserta.bidang_keahlian || '-'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted">Nama Istri/Suami</label>
                                                    <p class="fw-semibold mb-2">${peserta.nama_pasangan || '-'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted">Olahraga Kegemaran</label>
                                                    <p class="fw-semibold mb-2">${peserta.olahraga_hobi || '-'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted">Apakah Anda merokok?</label>
                                                    <p class="fw-semibold mb-2">${peserta.perokok ? 'Ya' : 'Tidak'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted">Ukuran Kaos/Celana Training</label>
                                                    <p class="fw-semibold mb-2">${peserta.ukuran_kaos || '-'}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Data Kepegawaian -->
                                <div class="col-lg-6">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-light bg-opacity-25 border-0">
                                            <h6 class="mb-0 fw-bold">
                                                <i class="fas fa-building me-2 text-primary"></i> Data Kepegawaian
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <label class="small text-muted">Asal Instansi</label>
                                                    <p class="fw-semibold mb-2">${kepegawaian?.asal_instansi || '-'}</p>
                                                </div>
                                                <div class="col-12">
                                                    <label class="small text-muted">Unit Kerja</label>
                                                    <p class="fw-semibold mb-2">${kepegawaian?.unit_kerja || '-'}</p>
                                                </div>
                                                <div class="col-12">
                                                    <label class="small text-muted">Jabatan</label>
                                                    <p class="fw-semibold mb-2">${kepegawaian?.jabatan || '-'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted">Eselon</label>
                                                    <p class="fw-semibold mb-2">${kepegawaian?.eselon || '-'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted">Pangkat/Golongan Ruang</label>
                                                    <p class="fw-semibold mb-2">${kepegawaian?.pangkat || '-'} ${kepegawaian?.golongan_ruang ? '/ ' + kepegawaian.golongan_ruang : ''}</p>
                                                </div>
                                                <div class="col-12">
                                                    <label class="small text-muted">Alamat Kantor</label>
                                                    <p class="fw-semibold mb-2">${kepegawaian?.alamat_kantor || '-'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted">Provinsi</label>
                                                    <p class="fw-semibold mb-2">${kepegawaian?.id_provinsi || '-'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted">Kabupaten/Kota</label>
                                                    <p class="fw-semibold mb-2">${kepegawaian?.id_kabupaten_kota || '-'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted">Nomor Telepon Kantor</label>
                                                    <p class="fw-semibold mb-2">${kepegawaian?.nomor_telepon_kantor || '-'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted">Email Kantor</label>
                                                    <p class="fw-semibold mb-2">${kepegawaian?.email_kantor || '-'}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dokumen Peserta -->
                                <div class="col-lg-6">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-light bg-opacity-25 border-0">
                                            <h6 class="mb-0 fw-bold">
                                                <i class="fas fa-file me-2 text-primary"></i> Dokumen Peserta
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <!-- Dokumen dari Peserta -->
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="document-icon me-2">
                                                            <i class="fas fa-image ${peserta.file_pas_foto ? 'text-primary' : 'text-muted'}"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <label class="small text-muted d-block">Pasfoto</label>
                                                            ${peserta.file_pas_foto ?
                        `<a href="${peserta.file_pas_foto}" target="_blank" class="btn btn-sm btn-link p-0 text-decoration-none">
                                                                    <i class="fas fa-download me-1"></i> Unduh
                                                                </a>` :
                        `<span class="badge bg-secondary">Belum diunggah</span>`
                    }
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Dokumen dari Kepegawaian -->
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="document-icon me-2">
                                                            <i class="fas fa-file-contract ${kepegawaian?.file_sk_jabatan ? 'text-primary' : 'text-muted'}"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <label class="small text-muted d-block">SK Jabatan Terakhir</label>
                                                            ${kepegawaian?.file_sk_jabatan ?
                        `<a href="${kepegawaian.file_sk_jabatan}" target="_blank" class="btn btn-sm btn-link p-0 text-decoration-none">
                                                                    <i class="fas fa-download me-1"></i> Unduh
                                                                </a>` :
                        `<span class="badge bg-secondary">Belum diunggah</span>`
                    }
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="document-icon me-2">
                                                            <i class="fas fa-file-certificate ${kepegawaian?.file_sk_pangkat ? 'text-primary' : 'text-muted'}"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <label class="small text-muted d-block">SK Pangkat/Golongan</label>
                                                            ${kepegawaian?.file_sk_pangkat ?
                        `<a href="${kepegawaian.file_sk_pangkat}" target="_blank" class="btn btn-sm btn-link p-0 text-decoration-none">
                                                                    <i class="fas fa-download me-1"></i> Unduh
                                                                </a>` :
                        `<span class="badge bg-secondary">Belum diunggah</span>`
                    }
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dokumen Pendaftaran -->
                                <div class="col-lg-6">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-light bg-opacity-25 border-0">
                                            <h6 class="mb-0 fw-bold">
                                                <i class="fas fa-file-alt me-2 text-primary"></i> Dokumen Pendaftaran
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="document-icon me-2">
                                                            <i class="fas fa-file-signature ${pendaftaran.file_surat_komitmen ? 'text-primary' : 'text-muted'}"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <label class="small text-muted d-block">Surat Komitmen</label>
                                                            ${pendaftaran.file_surat_komitmen ?
                        `<a href="${pendaftaran.file_surat_komitmen}" target="_blank" class="btn btn-sm btn-link p-0 text-decoration-none">
                                                                    <i class="fas fa-download me-1"></i> Unduh
                                                                </a>` :
                        `<span class="badge bg-secondary">Belum diunggah</span>`
                    }
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="document-icon me-2">
                                                            <i class="fas fa-file-certificate ${pendaftaran.file_pakta_integritas ? 'text-primary' : 'text-muted'}"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <label class="small text-muted d-block">Pakta Integritas</label>
                                                            ${pendaftaran.file_pakta_integritas ?
                        `<a href="${pendaftaran.file_pakta_integritas}" target="_blank" class="btn btn-sm btn-link p-0 text-decoration-none">
                                                                    <i class="fas fa-download me-1"></i> Unduh
                                                                </a>` :
                        `<span class="badge bg-secondary">Belum diunggah</span>`
                    }
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="document-icon me-2">
                                                            <i class="fas fa-file-contract ${pendaftaran.file_surat_tugas ? 'text-primary' : 'text-muted'}"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <label class="small text-muted d-block">Surat Tugas</label>
                                                            ${pendaftaran.file_surat_tugas ?
                        `<a href="${pendaftaran.file_surat_tugas}" target="_blank" class="btn btn-sm btn-link p-0 text-decoration-none">
                                                                    <i class="fas fa-download me-1"></i> Unduh
                                                                </a>` :
                        `<span class="badge bg-secondary">Belum diunggah</span>`
                    }
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="document-icon me-2">
                                                            <i class="fas fa-file-check ${pendaftaran.file_surat_kelulusan_seleksi ? 'text-primary' : 'text-muted'}"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <label class="small text-muted d-block">Surat Kelulusan Seleksi</label>
                                                            ${pendaftaran.file_surat_kelulusan_seleksi ?
                        `<a href="${pendaftaran.file_surat_kelulusan_seleksi}" target="_blank" class="btn btn-sm btn-link p-0 text-decoration-none">
                                                                    <i class="fas fa-download me-1"></i> Unduh
                                                                </a>` :
                        `<span class="badge bg-secondary">Belum diunggah</span>`
                    }
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="document-icon me-2">
                                                            <i class="fas fa-file-medical ${pendaftaran.file_surat_sehat ? 'text-primary' : 'text-muted'}"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <label class="small text-muted d-block">Surat Sehat</label>
                                                            ${pendaftaran.file_surat_sehat ?
                        `<a href="${pendaftaran.file_surat_sehat}" target="_blank" class="btn btn-sm btn-link p-0 text-decoration-none">
                                                                    <i class="fas fa-download me-1"></i> Unduh
                                                                </a>` :
                        `<span class="badge bg-secondary">Belum diunggah</span>`
                    }
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="document-icon me-2">
                                                            <i class="fas fa-file-medical-alt ${pendaftaran.file_surat_bebas_narkoba ? 'text-primary' : 'text-muted'}"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <label class="small text-muted d-block">Surat Bebas Narkoba</label>
                                                            ${pendaftaran.file_surat_bebas_narkoba ?
                        `<a href="${pendaftaran.file_surat_bebas_narkoba}" target="_blank" class="btn btn-sm btn-link p-0 text-decoration-none">
                                                                    <i class="fas fa-download me-1"></i> Unduh
                                                                </a>` :
                        `<span class="badge bg-secondary">Belum diunggah</span>`
                    }
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informasi Mentor -->
                                <div class="col-lg-6">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-light bg-opacity-25 border-0">
                                            <h6 class="mb-0 fw-bold">
                                                <i class="fas fa-chalkboard-teacher me-2 text-primary"></i> Informasi Mentor
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <label class="small text-muted">Nama Mentor</label>
                                                    <p class="fw-semibold mb-2">${mentor?.nama_mentor || 'Belum ditetapkan'}</p>
                                                </div>
                                                <div class="col-12">
                                                    <label class="small text-muted">Jabatan Mentor</label>
                                                    <p class="fw-semibold mb-2">${mentor?.jabatan_mentor || '-'}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
            }

            // Helper Functions
            function getStatusIcon(status) {
                const icons = {
                    'pending': 'fa-clock',
                    'diterima': 'fa-check',
                    'ditolak': 'fa-times',
                    'lulus': 'fa-graduation-cap'
                };
                return icons[status] || 'fa-question';
            }

            function getStatusColor(status) {
                const colors = {
                    'pending': 'warning',
                    'diterima': 'info',
                    'ditolak': 'danger',
                    'lulus': 'success'
                };
                return colors[status] || 'secondary';
            }

            function getStatusClass(status) {
                const classes = {
                    'pending': 'bg-warning text-dark',
                    'diterima': 'bg-info',
                    'ditolak': 'bg-danger',
                    'lulus': 'bg-success'
                };
                return classes[status] || 'bg-secondary';
            }

            function formatDate(dateString) {
                if (!dateString) return '-';
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
            }

            function showAlert(type, message) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show shadow-sm d-flex align-items-center`;
                alertDiv.innerHTML = `
                        <div class="alert-icon flex-shrink-0">
                            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle fa-lg"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <strong>${type === 'success' ? 'Sukses!' : 'Error!'}</strong> ${message}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;

                document.querySelector('.alert-container').appendChild(alertDiv);

                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        const bsAlert = new bootstrap.Alert(alertDiv);
                        bsAlert.close();
                    }
                }, 3000);
            }

            // Search Functionality
            initializeSearch();

            // Auto-hide alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.classList.contains('show')) {
                        bootstrap.Alert.getOrCreateInstance(alert).close();
                    }
                }, 5000);
            });

            // Add hover effects to table rows
            const tableRows = document.querySelectorAll('.peserta-row');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateY(-2px)';
                    this.style.transition = 'transform 0.2s ease';
                });

                row.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateY(0)';
                });
            });
        });

        // Simple Search Functionality
        function initializeSearch() {
            const searchInput = document.getElementById('searchInput');
            const clearSearchBtn = document.getElementById('clearSearch');
            const pesertaTable = document.getElementById('pesertaTable');

            searchInput.addEventListener('input', function (e) {
                // Show/hide clear button
                if (this.value.trim() !== '') {
                    clearSearchBtn.style.display = 'block';
                } else {
                    clearSearchBtn.style.display = 'none';
                }

                performSearch(this.value);
            });

            clearSearchBtn.addEventListener('click', function () {
                searchInput.value = '';
                this.style.display = 'none';
                performSearch('');
            });

            function performSearch(searchTerm) {
                const term = searchTerm.toLowerCase().trim();
                const tbody = pesertaTable.querySelector('tbody');
                const rows = tbody.querySelectorAll('tr:not(.empty-state-row)');
                let visibleCount = 0;

                rows.forEach(row => {
                    if (row.classList.contains('empty-state-row')) {
                        row.style.display = term === '' ? '' : 'none';
                        return;
                    }

                    const cells = row.querySelectorAll('td');
                    if (cells.length < 6) {
                        row.style.display = 'none';
                        return;
                    }

                    // Get searchable text from each column
                    const nameCell = cells[1];
                    const instansiCell = cells[2];
                    const nameText = nameCell.querySelector('.peserta-name')?.textContent.toLowerCase() || '';
                    const nipText = nameCell.querySelector('.text-muted')?.textContent.toLowerCase() || '';
                    const instansiText = instansiCell?.querySelector('.peserta-instansi')?.textContent.toLowerCase() || '';

                    // Search in all fields
                    const isMatch = term === '' ||
                        nameText.includes(term) ||
                        nipText.includes(term) ||
                        instansiText.includes(term);

                    if (isMatch) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show/hide empty state
                if (visibleCount === 0 && term !== '') {
                    showNoResultsMessage(tbody, term);
                } else {
                    removeNoResultsMessage();
                }
            }

            function showNoResultsMessage(tbody, term) {
                removeNoResultsMessage();

                const noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-results-row';
                noResultsRow.innerHTML = `
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-search me-2"></i>
                                Tidak ditemukan peserta dengan kata kunci "${term}"
                            </div>
                        </td>
                    `;
                tbody.appendChild(noResultsRow);
            }

            function removeNoResultsMessage() {
                const existing = document.querySelector('.no-results-row');
                if (existing) {
                    existing.remove();
                }
            }
        }

        function clearSearch() {
            const searchInput = document.getElementById('searchInput');
            const clearSearchBtn = document.getElementById('clearSearch');

            searchInput.value = '';
            clearSearchBtn.style.display = 'none';

            const event = new Event('input');
            searchInput.dispatchEvent(event);
        }
    </script>

    <style>
        /* Color Variables */
        :root {
            --primary-color: #285496;
            --primary-light: rgba(40, 84, 150, 0.1);
            --primary-gradient: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);
            --danger-color: #ff4757;
            --warning-color: #ffa502;
            --info-color: #17a2b8;
        }

        /* Page Header */
        .page-header {
            padding: 2rem;
            margin-bottom: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(40, 84, 150, 0.15);
        }

        .icon-wrapper {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Stats Cards */
        .stat-card {
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        /* Filter Buttons */
        .btn-outline-primary {
            border-color: #285496;
            color: #285496;
        }

        .btn-outline-primary:hover {
            background-color: #285496;
            border-color: #285496;
            color: white;
        }

        /* User Avatar */
        .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
            box-shadow: 0 4px 8px rgba(40, 84, 150, 0.2);
        }

        /* Action Buttons */
        .btn-action {
            border-radius: 8px;
            padding: 0.375rem 0.75rem;
            margin: 0 2px;
            transition: all 0.2s ease;
            border-width: 2px;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-outline-info:hover {
            background-color: var(--info-color);
            border-color: var(--info-color);
            color: white;
        }

        .btn-outline-warning:hover {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
            color: white;
        }

        .btn-outline-danger:hover {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
            color: white;
        }

        .btn-lift {
            transition: transform 0.2s ease;
        }

        .btn-lift:hover {
            transform: translateY(-2px);
        }

        /* Search */
        .search-group {
            border-radius: 10px;
            overflow: hidden;
        }

        .search-group .form-control:focus {
            box-shadow: none;
            border-color: #dee2e6;
        }

        /* Badge Styling */
        .badge {
            font-size: 0.75rem;
            padding: 0.4em 0.8em;
            border-radius: 20px;
        }

        .bg-warning {
            background-color: #ffc107 !important;
        }

        .bg-info {
            background-color: #17a2b8 !important;
        }

        .bg-danger {
            background-color: #dc3545 !important;
        }

        .bg-success {
            background-color: #28a745 !important;
        }

        .bg-primary {
            background-color: #285496 !important;
        }

        /* Table Styling */
        .table {
            margin-bottom: 0;
        }

        .table th {
            border-bottom: 2px solid var(--primary-light);
            font-weight: 600;
            color: var(--primary-color);
            background-color: #f8fafc;
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }

        .peserta-row:hover {
            background-color: rgba(40, 84, 150, 0.03) !important;
        }

        /* Alert Styling */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .alert-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Empty State */
        .empty-state {
            padding: 3rem 1rem;
        }

        .empty-state-icon {
            opacity: 0.5;
        }

        /* Modal Styling */
        .modal-content {
            border-radius: 15px;
            overflow: hidden;
        }

        .modal-header {
            padding: 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
        }

        /* Detail Modal */
        .detail-avatar {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            box-shadow: 0 6px 15px rgba(40, 84, 150, 0.3);
        }

        .document-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Better label styling */
        .small.text-muted {
            font-size: 0.75rem;
            font-weight: 500;
            color: #6c757d !important;
            margin-bottom: 0.25rem;
        }

        .fw-semibold.mb-2 {
            font-size: 0.9rem;
            color: #343a40;
            margin-bottom: 0.75rem !important;
        }

        /* Card spacing improvement */
        .card-body .row.g-2>div {
            margin-bottom: 0.5rem;
        }

        /* Mobile Optimizations */
        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
            }

            .stat-card {
                margin-bottom: 1rem;
            }

            .btn-group {
                display: flex;
                gap: 0.25rem;
                flex-wrap: nowrap;
            }

            .btn-action {
                margin: 0;
                padding: 0.25rem 0.5rem;
                font-size: 0.8rem;
            }

            .update-status {
                padding: 0.2rem 0.4rem;
                font-size: 0.75rem;
            }

            .table-responsive {
                border-radius: 10px;
                border: 1px solid #e9ecef;
            }

            .table th {
                font-size: 0.8rem;
                padding: 0.5rem;
            }

            .table td {
                font-size: 0.8rem;
                padding: 0.5rem;
            }

            .user-avatar {
                width: 36px;
                height: 36px;
                font-size: 0.9rem;
                margin-right: 0.5rem !important;
            }

            /* Mobile: hide columns */
            .table th:nth-child(3),
            .table td:nth-child(3),
            .table th:nth-child(4),
            .table td:nth-child(4) {
                display: none;
            }

            /* Mobile: adjust column widths */
            .table th:first-child,
            .table td:first-child {
                width: 10% !important;
            }

            .table th:nth-child(2),
            .table td:nth-child(2) {
                width: 40% !important;
            }

            .table th:nth-child(5),
            .table td:nth-child(5) {
                width: 50% !important;
            }

            /* Mobile user info - minimal */
            .fw-bold {
                font-size: 0.85rem;
                margin-bottom: 0.1rem;
            }

            .text-muted.small {
                font-size: 0.7rem;
            }

            /* Badge size on mobile */
            .badge {
                font-size: 0.7rem;
                padding: 0.3em 0.6em;
            }

            /* Filter buttons */
            .d-flex.flex-wrap.gap-2 {
                gap: 0.5rem !important;
            }

            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }

            /* Better label styling on mobile */
            .small.text-muted {
                font-size: 0.7rem;
            }

            .fw-semibold.mb-2 {
                font-size: 0.85rem;
                margin-bottom: 0.5rem !important;
            }
        }

        @media (max-width: 576px) {
            .page-header {
                text-align: center;
                padding: 1.5rem 1rem;
            }

            .icon-wrapper {
                margin: 0 auto 1rem;
            }

            .search-group {
                width: 100%;
                max-width: none;
                margin-top: 1rem;
            }

            .modal-dialog {
                margin: 0.5rem;
            }

            /* Even more compact for small phones */
            .user-avatar {
                width: 32px;
                height: 32px;
                font-size: 0.8rem;
            }

            .fw-bold {
                font-size: 0.8rem;
            }

            .btn-action {
                padding: 0.2rem 0.4rem;
                font-size: 0.75rem;
                min-width: 30px;
            }

            .table td {
                padding: 0.4rem;
            }

            .table th {
                padding: 0.4rem;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .page-header p {
                font-size: 0.9rem;
            }

            /* Card header responsive */
            .card-header .row {
                flex-direction: column;
                gap: 1rem;
            }

            .card-header .col-md-6 {
                width: 100%;
            }

            /* Adjust card layout for mobile */
            .detail-content .row>.col-lg-6 {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 375px) {

            /* Extra small phones */
            .user-avatar {
                width: 28px;
                height: 28px;
                font-size: 0.7rem;
            }

            .fw-bold {
                font-size: 0.75rem;
            }

            .btn-action {
                padding: 0.15rem 0.3rem;
                font-size: 0.7rem;
                min-width: 28px;
            }

            .table td:first-child {
                width: 8% !important;
                font-size: 0.75rem;
            }

            .table td:nth-child(2) {
                width: 42% !important;
            }

            .table td:nth-child(5) {
                width: 50% !important;
            }

            /* Single column filter on very small screens */
            .d-flex.flex-wrap.gap-2 {
                flex-direction: column;
            }

            /* Stack cards on very small screens */
            .detail-content .row>.col-lg-6 {
                width: 100% !important;
            }
        }

        /* Animation for alerts */
        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .alert {
            animation: slideInDown 0.3s ease;
        }

        /* Custom scrollbar */
        .detail-container::-webkit-scrollbar {
            width: 6px;
        }

        .detail-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .detail-container::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }

        .detail-container::-webkit-scrollbar-thumb:hover {
            background: #1e4274;
        }

        /* Responsive search input */
        @media (max-width: 768px) {
            #searchInput {
                font-size: 0.9rem;
            }
        }
    </style>
@endsection