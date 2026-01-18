@extends('admin.partials.layout')

@section('title', 'Manajemen User - Sistem Inventori Obat')

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
                        <h1 class="text-white mb-1">Manajemen User</h1>
                        <p class="text-white-50 mb-0">Kelola pengguna dan hak akses dalam sistem</p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('users.create') }}" class="btn btn-light btn-hover-lift shadow-sm"
                    data-bs-toggle="tooltip" data-bs-placement="left" title="Buat user baru">
                    <i class="fas fa-user-plus me-2"></i> Tambah User
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

    <!-- User Table Card -->
    <div class="card border-0 shadow-lg overflow-hidden">
        <div class="card-header bg-white py-3 border-0">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-list me-2" style="color: #285496;"></i> Daftar User
                    </h5>
                </div>
                <div class="col-auto">
                    <div class="input-group search-group" style="max-width: 300px;">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" id="searchInput"
                            placeholder="Cari nama, email, atau role...">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch" style="display: none;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Results Info -->
        <div id="searchInfo" class="alert alert-info alert-dismissible fade show m-3 mb-0" style="display: none;">
            <div class="d-flex align-items-center">
                <i class="fas fa-filter me-2"></i>
                <div class="flex-grow-1">
                    Menampilkan <span id="searchCount" class="fw-bold">0</span> hasil dari pencarian
                </div>
                <button type="button" class="btn-close" id="clearSearchBtn"></button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="userTable">
                    <thead>
                        <tr class="table-light">
                            <th width="5%" class="ps-4">No</th>
                            <th width="25%">User</th>
                            <th width="25%" class="d-none d-md-table-cell">Email</th>
                            <th width="15%">Role</th>
                            <th width="30%" class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr class="user-row" data-user-id="{{ $user->id }}">
                                <td class="ps-4 fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-3"
                                            style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold user-name">{{ $user->name }}</div>
                                            <div class="text-muted small d-none d-md-block">
                                                <i class="fas fa-envelope me-1"></i>
                                                {{ $user->email }}
                                            </div>
                                            <div class="text-muted small d-md-none">
                                                <i class="fas fa-envelope me-1"></i>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <p class="mb-0 text-muted user-email">{{ $user->email }}</p>
                                </td>
                                <td>
                                    @php
                                        $roleColors = [
                                            'Admin' => 'bg-danger',
                                            'Super Admin' => 'bg-danger',
                                            'Manager' => 'bg-warning text-dark',
                                            'Supervisor' => 'bg-info',
                                            'Staff' => 'bg-primary',
                                            'PIC' => 'bg-success',
                                            'User' => 'bg-secondary'
                                        ];
                                        $roleColor = $roleColors[$user->role->name] ?? 'bg-primary';
                                    @endphp
                                    <span class="badge {{ $roleColor }} user-role">{{ $user->role->name }}</span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group" role="group">
                                        @if($user->role->name === 'pic')
                                            <!-- Tombol Akses khusus untuk PIC -->
                                            <button type="button" class="btn btn-sm btn-outline-success btn-action manage-access"
                                                data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-bs-toggle="tooltip"
                                                title="Kelola Akses PIC">
                                                <i class="fas fa-key"></i>
                                            </button>
                                        @endif
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="btn btn-sm btn-outline-warning btn-action" data-bs-toggle="tooltip"
                                            title="Edit User">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-action delete-user"
                                            data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-bs-toggle="tooltip"
                                            title="Hapus User">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-state-row">
                                <td colspan="5" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mb-3">
                                            <i class="fas fa-users fa-4x" style="color: #e9ecef;"></i>
                                        </div>
                                        <h5 class="text-muted mb-2">Belum ada user</h5>
                                        <p class="text-muted mb-4">Mulai dengan membuat user pertama Anda</p>
                                        <a href="{{ route('users.create') }}" class="btn btn-primary btn-lg px-4">
                                            <i class="fas fa-user-plus me-2"></i> Tambah User Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($users->count() > 0)
            <div class="card-footer bg-white py-3 border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <small class="text-muted">
                            Menampilkan {{ $users->count() }} user
                        </small>
                    </div>
                </div>
            </div>
        @endif
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
                    <p class="text-muted mb-1">Anda akan menghapus user:</p>
                    <h5 class="text-danger mb-4 fw-bold" id="deleteUserName"></h5>

                    <p class="text-muted small mb-4">
                        <i class="fas fa-info-circle me-1"></i>
                        Tindakan ini tidak dapat dibatalkan. User tidak akan dapat mengakses sistem.
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

    <!-- Modal Kelola Akses PIC -->
    <div class="modal fade" id="accessModal" tabindex="-1" aria-labelledby="accessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-gradient-primary text-white border-0">
                    <div class="d-flex align-items-center w-100">
                        <div class="modal-icon-wrapper me-3">
                            <i class="fas fa-key fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="modal-title mb-0 fw-bold" id="accessModalLabel">
                                Kelola Akses PIC
                            </h5>
                            <p class="mb-0 small opacity-90">
                                User: <span id="accessUserName" class="fw-semibold"></span>
                            </p>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                </div>

                <div class="modal-body p-0">
                    <form id="accessForm">
                        <input type="hidden" id="accessUserId">

                        <!-- Loading State -->
                        <div id="loadingAccess" class="text-center py-5">
                            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-3">Memuat data akses...</p>
                        </div>

                        <!-- Access Content -->
                        <div id="accessContent" class="p-4" style="display: none;">
                            <!-- Tab Navigation -->
                            <ul class="nav nav-pills mb-3" id="accessTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pelatihan-tab" data-bs-toggle="tab"
                                        data-bs-target="#pelatihan-tab-pane" type="button" role="tab">
                                        <i class="fas fa-graduation-cap me-2"></i>
                                        Jenis Pelatihan
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="angkatan-tab" data-bs-toggle="tab"
                                        data-bs-target="#angkatan-tab-pane" type="button" role="tab">
                                        <i class="fas fa-users me-2"></i>
                                        Angkatan
                                    </button>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content" id="accessTabContent">
                                <!-- Jenis Pelatihan Tab -->
                                <div class="tab-pane fade show active" id="pelatihan-tab-pane" role="tabpanel" tabindex="0">
                                    <div class="access-section">
                                        <div class="mb-3">
                                            <h6 class="mb-2 fw-semibold text-dark">
                                                Pilih Jenis Pelatihan
                                            </h6>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-search"></i>
                                                </span>
                                                <input type="text" class="form-control border-start-0 pelatihan-search"
                                                    placeholder="Cari jenis pelatihan...">
                                            </div>
                                        </div>

                                        <div class="access-scroll-container">
                                            <div class="card border">
                                                <div class="card-body p-0">
                                                    <div id="jenisPelatihanList" class="access-checklist">
                                                        @forelse($allJenisPelatihan as $jp)
                                                            <div class="access-item"
                                                                data-search="{{ strtolower($jp->kode_pelatihan . ' ' . $jp->nama_pelatihan) }}">
                                                                <div class="form-check mb-0">
                                                                    <input class="form-check-input pelatihan-checkbox"
                                                                        type="checkbox" value="{{ $jp->id }}"
                                                                        id="jp_{{ $jp->id }}" disabled>
                                                                    <label class="form-check-label" for="jp_{{ $jp->id }}">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="ms-2">
                                                                                <div class="fw-medium">{{ $jp->nama_pelatihan }}
                                                                                </div>
                                                                                <small
                                                                                    class="text-muted">{{ $jp->kode_pelatihan }}</small>
                                                                            </div>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="text-center py-4">
                                                                <i class="fas fa-graduation-cap fa-2x text-muted mb-2"></i>
                                                                <p class="text-muted mb-0">Tidak ada data jenis pelatihan</p>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <small class="text-muted">
                                                Terpilih: <span id="pelatihanCount"
                                                    class="fw-semibold text-primary">0</span> dari
                                                {{ $allJenisPelatihan->count() }}
                                            </small>
                                        </div>
                                        <div id="jenisWarning" class="alert alert-warning mt-2 p-2" style="display: none;">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Pilih jenis pelatihan terlebih dahulu untuk mengakses angkatan
                                        </div>
                                    </div>
                                </div>

                                <!-- Angkatan Tab -->
                                <div class="tab-pane fade" id="angkatan-tab-pane" role="tabpanel" tabindex="0">
                                    <div class="access-section">
                                        <div class="mb-3">
                                            <h6 class="mb-2 fw-semibold text-dark">
                                                Pilih Angkatan (Hanya dari jenis pelatihan yang dipilih)
                                            </h6>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-search"></i>
                                                </span>
                                                <input type="text" class="form-control border-start-0 angkatan-search"
                                                    placeholder="Cari angkatan atau jenis pelatihan...">
                                            </div>
                                        </div>

                                        <div class="access-scroll-container">
                                            <div class="card border">
                                                <div class="card-body p-0">
                                                    <div id="angkatanList" class="access-checklist">
                                                        @forelse($allAngkatan as $ang)
                                                            @php
                                                                // Warna badge berdasarkan jenis pelatihan
                                                                $jenisColors = [
                                                                    1 => 'bg-info text-white',  // PKN
                                                                    2 => 'bg-primary text-white', // LATSAR
                                                                    3 => 'bg-success text-white', // PKA
                                                                    4 => 'bg-warning text-dark'   // PKP
                                                                ];
                                                                $jenisColor = $jenisColors[$ang->id_jenis_pelatihan] ?? 'bg-secondary';

                                                                // Nama singkat jenis pelatihan
                                                                $jenisNamaSingkat = [
                                                                    1 => 'PKN',
                                                                    2 => 'LATSAR',
                                                                    3 => 'PKA',
                                                                    4 => 'PKP'
                                                                ];
                                                                $jenisSingkat = $jenisNamaSingkat[$ang->id_jenis_pelatihan] ?? '???';
                                                            @endphp
                                                            <div class="access-item"
                                                                data-search="{{ strtolower($ang->nama_angkatan . ' ' . $ang->tahun . ' ' . ($ang->jenisPelatihan->nama_pelatihan ?? '')) }}"
                                                                data-jenis="{{ $ang->id_jenis_pelatihan }}">
                                                                <div class="form-check mb-0">
                                                                    <input class="form-check-input angkatan-checkbox"
                                                                        type="checkbox" value="{{ $ang->id }}"
                                                                        id="ang_{{ $ang->id }}"
                                                                        data-jenis="{{ $ang->id_jenis_pelatihan }}" disabled>
                                                                    <label class="form-check-label w-100"
                                                                        for="ang_{{ $ang->id }}">
                                                                        <div
                                                                            class="d-flex align-items-center justify-content-between">
                                                                            <div class="flex-grow-1">
                                                                                <div class="fw-medium">{{ $ang->nama_angkatan }}
                                                                                </div>
                                                                                <div class="d-flex align-items-center mt-1">
                                                                                    <small class="text-muted me-2">Tahun
                                                                                        {{ $ang->tahun }}</small>
                                                                                    <span
                                                                                        class="badge {{ $jenisColor }} badge-sm">
                                                                                        <i
                                                                                            class="fas fa-graduation-cap me-1"></i>
                                                                                        {{ $jenisSingkat }}
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="text-end ms-2">
                                                                                <small class="text-muted d-block">
                                                                                    {{ $ang->jenisPelatihan->nama_pelatihan ?? 'Tidak diketahui' }}
                                                                                </small>
                                                                            </div>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="text-center py-4">
                                                                <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                                                <p class="text-muted mb-0">Tidak ada data angkatan</p>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <small class="text-muted">
                                                Terpilih: <span id="angkatanCount" class="fw-semibold text-primary">0</span>
                                                dari <span id="angkatanAvailable">0</span> angkatan tersedia
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Tutup
                    </button>
                    <button type="button" class="btn btn-primary" id="editAccessBtn">
                        <i class="fas fa-edit me-2"></i> Edit
                    </button>
                    <button type="button" class="btn btn-success" id="saveAccessBtn" style="display: none;">
                        <i class="fas fa-save me-2"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Delete User Confirmation
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const deleteForm = document.getElementById('deleteForm');
            const deleteUserName = document.getElementById('deleteUserName');

            document.querySelectorAll('.delete-user').forEach(button => {
                button.addEventListener('click', function () {
                    const userId = this.getAttribute('data-id');
                    const userName = this.getAttribute('data-name');

                    deleteUserName.textContent = userName;
                    deleteForm.action = `{{ url('users') }}/${userId}`;
                    deleteModal.show();
                });
            });

            // Manage Access Modal
            const accessModal = new bootstrap.Modal(document.getElementById('accessModal'));
            const accessUserName = document.getElementById('accessUserName');
            const accessUserId = document.getElementById('accessUserId');
            const loadingAccess = document.getElementById('loadingAccess');
            const accessContent = document.getElementById('accessContent');
            const editAccessBtn = document.getElementById('editAccessBtn');
            const saveAccessBtn = document.getElementById('saveAccessBtn');

            let isEditMode = false;
            let selectedJenisIds = [];

            document.querySelectorAll('.manage-access').forEach(button => {
                button.addEventListener('click', function () {
                    const userId = this.getAttribute('data-id');
                    const userName = this.getAttribute('data-name');

                    accessUserName.textContent = userName;
                    accessUserId.value = userId;

                    // Reset state
                    isEditMode = false;
                    editAccessBtn.style.display = 'block';
                    saveAccessBtn.style.display = 'none';
                    loadingAccess.style.display = 'block';
                    accessContent.style.display = 'none';
                    selectedJenisIds = [];

                    // Reset search
                    document.querySelectorAll('.pelatihan-search, .angkatan-search').forEach(input => {
                        input.value = '';
                    });

                    // Show all items
                    document.querySelectorAll('.access-item').forEach(item => {
                        item.style.display = 'flex';
                    });

                    // Load access data
                    loadPicAccess(userId);

                    accessModal.show();
                });
            });

            // Edit button handler
            editAccessBtn.addEventListener('click', function () {
                isEditMode = true;
                enableEditMode();
            });

            // Save button handler
            saveAccessBtn.addEventListener('click', function () {
                savePicAccess();
            });

            // Search functionality for pelatihan
            document.querySelector('.pelatihan-search').addEventListener('input', function (e) {
                const searchTerm = this.value.toLowerCase();
                searchItems(searchTerm, '#jenisPelatihanList');
            });

            // Search functionality for angkatan
            document.querySelector('.angkatan-search').addEventListener('input', function (e) {
                const searchTerm = this.value.toLowerCase();
                filterAngkatanBySearch(searchTerm);
            });

            // Checkbox change handlers
            document.addEventListener('change', function (e) {
                if (e.target.classList.contains('pelatihan-checkbox')) {
                    handleJenisPelatihanChange(e.target);
                    updateCount('pelatihan');
                }
                if (e.target.classList.contains('angkatan-checkbox')) {
                    updateCount('angkatan');
                }
            });

            // Tab change handler
            document.querySelectorAll('#accessTab button').forEach(tab => {
                tab.addEventListener('shown.bs.tab', function (event) {
                    if (event.target.id === 'angkatan-tab') {
                        // Saat pindah ke tab angkatan, update filter
                        updateAngkatanAvailability();
                    }
                });
            });

            function loadPicAccess(userId) {
                fetch(`{{ url('users') }}/${userId}/pic-access`)
                    .then(response => response.json())
                    .then(data => {
                        // Uncheck all first
                        document.querySelectorAll('.pelatihan-checkbox').forEach(cb => cb.checked = false);
                        document.querySelectorAll('.angkatan-checkbox').forEach(cb => cb.checked = false);

                        // Check the ones with access
                        data.jenis_pelatihan.forEach(id => {
                            const checkbox = document.getElementById(`jp_${id}`);
                            if (checkbox) {
                                checkbox.checked = true;
                                handleJenisPelatihanChange(checkbox, true);
                            }
                        });

                        data.angkatan.forEach(id => {
                            const checkbox = document.getElementById(`ang_${id}`);
                            if (checkbox) {
                                checkbox.checked = true;
                            }
                        });

                        // Update counts
                        updateCount('pelatihan');
                        updateCount('angkatan');

                        loadingAccess.style.display = 'none';
                        accessContent.style.display = 'block';

                        // Update availability angkatan setelah load
                        updateAngkatanAvailability();
                    })
                    .catch(error => {
                        console.error('Error loading access:', error);
                        alert('Gagal memuat data akses');
                    });
            }

            function handleJenisPelatihanChange(checkbox, isInitialLoad = false) {
                const jenisId = checkbox.value;

                if (checkbox.checked) {
                    // Tambahkan ke selectedJenisIds jika belum ada
                    if (!selectedJenisIds.includes(jenisId)) {
                        selectedJenisIds.push(jenisId);
                    }
                } else {
                    // Hapus dari selectedJenisIds
                    selectedJenisIds = selectedJenisIds.filter(id => id !== jenisId);

                    // Uncheck semua angkatan dari jenis ini
                    document.querySelectorAll(`.angkatan-checkbox[data-jenis="${jenisId}"]`).forEach(cb => {
                        cb.checked = false;
                    });
                }

                // Update filter angkatan
                if (!isInitialLoad) {
                    updateAngkatanAvailability();
                }
            }

            function updateAngkatanAvailability() {
                const angkatanCheckboxes = document.querySelectorAll('.angkatan-checkbox');
                let availableCount = 0;

                angkatanCheckboxes.forEach(checkbox => {
                    const jenisId = checkbox.getAttribute('data-jenis');

                    if (selectedJenisIds.length === 0) {
                        // Jika belum pilih jenis pelatihan, nonaktifkan semua angkatan
                        checkbox.disabled = true;
                        checkbox.parentElement.parentElement.style.opacity = '0.5';
                    } else if (selectedJenisIds.includes(jenisId)) {
                        // Jika jenis pelatihan dipilih, aktifkan angkatan dari jenis tersebut
                        checkbox.disabled = !isEditMode;
                        checkbox.parentElement.parentElement.style.opacity = '1';
                        availableCount++;
                    } else {
                        // Jika jenis pelatihan tidak dipilih, nonaktifkan dan sembunyikan angkatan
                        checkbox.disabled = true;
                        checkbox.parentElement.parentElement.style.opacity = '0.5';
                        checkbox.checked = false; // Pastikan tidak tercentang
                    }
                });

                // Update count angkatan tersedia
                document.getElementById('angkatanAvailable').textContent = availableCount;

                // Tampilkan/sembunyikan warning
                const jenisWarning = document.getElementById('jenisWarning');
                if (selectedJenisIds.length === 0) {
                    jenisWarning.style.display = 'block';
                } else {
                    jenisWarning.style.display = 'none';
                }

                // Update count yang terpilih
                updateCount('angkatan');

                // Reset search jika ada
                const searchTerm = document.querySelector('.angkatan-search').value.toLowerCase();
                if (searchTerm) {
                    filterAngkatanBySearch(searchTerm);
                }
            }

            function filterAngkatanBySearch(term) {
                const items = document.querySelectorAll('#angkatanList .access-item');

                items.forEach(item => {
                    const searchText = item.getAttribute('data-search') || '';
                    const jenisId = item.getAttribute('data-jenis');

                    // Cek apakah item sesuai dengan jenis yang dipilih
                    const jenisMatch = selectedJenisIds.length === 0 || selectedJenisIds.includes(jenisId);

                    // Cek apakah item sesuai dengan search term
                    const textMatch = term === '' || searchText.includes(term);

                    if (jenisMatch && textMatch) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }

            function enableEditMode() {
                // Aktifkan checkbox jenis pelatihan
                document.querySelectorAll('.pelatihan-checkbox').forEach(cb => {
                    cb.disabled = false;
                });

                // Aktifkan checkbox angkatan berdasarkan jenis yang sudah dipilih
                updateAngkatanAvailability();

                editAccessBtn.style.display = 'none';
                saveAccessBtn.style.display = 'block';
            }

            function savePicAccess() {
                const userId = accessUserId.value;
                const jenisPelatihan = selectedJenisIds;
                const angkatan = [];

                document.querySelectorAll('.angkatan-checkbox:checked').forEach(cb => {
                    angkatan.push(cb.value);
                });

                // Validation
                if (jenisPelatihan.length === 0) {
                    alert('Pilih minimal satu jenis pelatihan');
                    return;
                }

                if (angkatan.length === 0) {
                    alert('Pilih minimal satu angkatan');
                    return;
                }

                // Validasi tambahan: pastikan semua angkatan terpilih sesuai dengan jenis yang dipilih
                const invalidAngkatan = [];
                document.querySelectorAll('.angkatan-checkbox:checked').forEach(cb => {
                    const jenisId = cb.getAttribute('data-jenis');
                    if (!jenisPelatihan.includes(jenisId)) {
                        invalidAngkatan.push(cb.nextElementSibling.querySelector('.fw-medium').textContent);
                    }
                });

                if (invalidAngkatan.length > 0) {
                    alert('Beberapa angkatan tidak sesuai dengan jenis pelatihan yang dipilih:\n' +
                        invalidAngkatan.join(', ') + '\n\nHarap periksa kembali pilihan Anda.');
                    return;
                }

                // Show loading
                saveAccessBtn.disabled = true;
                saveAccessBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';

                fetch(`{{ url('users') }}/${userId}/pic-access`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        jenis_pelatihan: jenisPelatihan,
                        angkatan: angkatan
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Disable checkboxes (readonly mode)
                            document.querySelectorAll('.pelatihan-checkbox, .angkatan-checkbox').forEach(cb => {
                                cb.disabled = true;
                            });

                            // Show success message
                            showSuccess('Akses berhasil disimpan');

                            // Reset buttons
                            isEditMode = false;
                            editAccessBtn.style.display = 'block';
                            saveAccessBtn.style.display = 'none';
                            saveAccessBtn.disabled = false;
                            saveAccessBtn.innerHTML = '<i class="fas fa-save me-2"></i> Simpan';
                        } else {
                            alert('Gagal menyimpan: ' + data.message);
                            saveAccessBtn.disabled = false;
                            saveAccessBtn.innerHTML = '<i class="fas fa-save me-2"></i> Simpan';
                        }
                    })
                    .catch(error => {
                        console.error('Error saving access:', error);
                        alert('Terjadi kesalahan saat menyimpan');
                        saveAccessBtn.disabled = false;
                        saveAccessBtn.innerHTML = '<i class="fas fa-save me-2"></i> Simpan';
                    });
            }

            function searchItems(term, containerSelector) {
                const items = document.querySelectorAll(`${containerSelector} .access-item`);
                items.forEach(item => {
                    const searchText = item.getAttribute('data-search') || '';
                    if (searchText.includes(term)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }

            function updateCount(type) {
                const countElement = document.getElementById(`${type}Count`);
                const checkboxes = document.querySelectorAll(`.${type}-checkbox:checked`);
                countElement.textContent = checkboxes.length;
            }

            function showSuccess(message) {
                // Create success alert
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 z-3';
                alertDiv.style.minWidth = '300px';
                alertDiv.innerHTML = `
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <div class="flex-grow-1">${message}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;

                document.body.appendChild(alertDiv);

                // Auto remove after 3 seconds
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 3000);
            }

            // Enhanced Search Functionality for main table
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
        });

        // Search Functionality for main table
        function initializeSearch() {
            const searchInput = document.getElementById('searchInput');
            const clearSearchBtn = document.getElementById('clearSearch');
            const clearSearchBtn2 = document.getElementById('clearSearchBtn');
            const searchInfo = document.getElementById('searchInfo');
            const searchCount = document.getElementById('searchCount');
            const userTable = document.getElementById('userTable');

            let debounceTimer;

            searchInput.addEventListener('input', function (e) {
                clearTimeout(debounceTimer);

                if (this.value.trim() !== '') {
                    clearSearchBtn.style.display = 'block';
                } else {
                    clearSearchBtn.style.display = 'none';
                }

                debounceTimer = setTimeout(() => {
                    performSearch(this.value);
                }, 300);
            });

            clearSearchBtn.addEventListener('click', function () {
                searchInput.value = '';
                this.style.display = 'none';
                performSearch('');
            });

            clearSearchBtn2.addEventListener('click', function () {
                searchInput.value = '';
                clearSearchBtn.style.display = 'none';
                performSearch('');
            });

            function performSearch(searchTerm) {
                const term = searchTerm.toLowerCase().trim();
                const tbody = userTable.querySelector('tbody');
                const rows = tbody.querySelectorAll('tr:not(.empty-state-row)');
                let visibleCount = 0;

                clearHighlights();

                rows.forEach(row => {
                    if (row.classList.contains('empty-state-row')) {
                        row.style.display = term === '' ? '' : 'none';
                        return;
                    }

                    if (row.classList.contains('no-results-row')) {
                        row.remove();
                        return;
                    }

                    const cells = row.querySelectorAll('td');

                    if (cells.length < 5) {
                        row.style.display = 'none';
                        return;
                    }

                    const userName = getTextFromCell(cells[1]).toLowerCase();
                    const userEmail = cells[2] ? getTextFromCell(cells[2]).toLowerCase() : '';
                    const userRole = getTextFromCell(cells[3]).toLowerCase();

                    const isMatch = term === '' ||
                        userName.includes(term) ||
                        (userEmail && userEmail.includes(term)) ||
                        userRole.includes(term);

                    if (isMatch) {
                        row.style.display = '';
                        visibleCount++;

                        if (term !== '') {
                            if (userName.includes(term)) highlightText(cells[1], term);
                            if (userEmail && userEmail.includes(term) && cells[2]) highlightText(cells[2], term);
                            if (userRole.includes(term)) highlightText(cells[3], term);
                        } else {
                            restoreCell(cells[1]);
                            if (cells[2]) restoreCell(cells[2]);
                            restoreCell(cells[3]);
                        }
                    } else {
                        row.style.display = 'none';
                        restoreCell(cells[1]);
                        if (cells[2]) restoreCell(cells[2]);
                        restoreCell(cells[3]);
                    }
                });

                if (term === '') {
                    searchInfo.style.display = 'none';
                    removeNoResultsMessage();
                } else {
                    searchCount.textContent = visibleCount;
                    searchInfo.style.display = 'block';

                    if (visibleCount === 0) {
                        showNoResultsMessage(tbody, term);
                    } else {
                        removeNoResultsMessage();
                    }
                }
            }

            function getTextFromCell(cell) {
                const nameElement = cell.querySelector('.user-name');
                if (nameElement) return nameElement.textContent;

                const emailElement = cell.querySelector('.user-email');
                if (emailElement) return emailElement.textContent;

                const roleElement = cell.querySelector('.user-role');
                if (roleElement) return roleElement.textContent;

                return cell.textContent;
            }

            function highlightText(cell, term) {
                const text = getTextFromCell(cell);
                const regex = new RegExp(`(${escapeRegex(term)})`, 'gi');

                if (!cell.dataset.originalHtml) {
                    cell.dataset.originalHtml = cell.innerHTML;
                }

                const nameElement = cell.querySelector('.user-name');
                const emailElement = cell.querySelector('.user-email');
                const roleElement = cell.querySelector('.user-role');

                if (nameElement) {
                    if (!nameElement.dataset.originalHtml) {
                        nameElement.dataset.originalHtml = nameElement.innerHTML;
                    }
                    const highlighted = text.replace(regex, '<mark class="search-highlight">$1</mark>');
                    nameElement.innerHTML = highlighted;
                } else if (emailElement) {
                    if (!emailElement.dataset.originalHtml) {
                        emailElement.dataset.originalHtml = emailElement.innerHTML;
                    }
                    const highlighted = text.replace(regex, '<mark class="search-highlight">$1</mark>');
                    emailElement.innerHTML = highlighted;
                } else if (roleElement) {
                    if (!roleElement.dataset.originalHtml) {
                        roleElement.dataset.originalHtml = roleElement.innerHTML;
                    }
                    const highlighted = text.replace(regex, '<mark class="search-highlight">$1</mark>');
                    roleElement.innerHTML = highlighted;
                }
            }

            function restoreCell(cell) {
                if (cell.dataset.originalHtml) {
                    cell.innerHTML = cell.dataset.originalHtml;
                    delete cell.dataset.originalHtml;
                }

                const nameElement = cell.querySelector('.user-name');
                const emailElement = cell.querySelector('.user-email');
                const roleElement = cell.querySelector('.user-role');

                if (nameElement && nameElement.dataset.originalHtml) {
                    nameElement.innerHTML = nameElement.dataset.originalHtml;
                    delete nameElement.dataset.originalHtml;
                }

                if (emailElement && emailElement.dataset.originalHtml) {
                    emailElement.innerHTML = emailElement.dataset.originalHtml;
                    delete emailElement.dataset.originalHtml;
                }

                if (roleElement && roleElement.dataset.originalHtml) {
                    roleElement.innerHTML = roleElement.dataset.originalHtml;
                    delete roleElement.dataset.originalHtml;
                }
            }

            function clearHighlights() {
                const cells = document.querySelectorAll('td[data-original-html]');
                cells.forEach(cell => {
                    if (cell.dataset.originalHtml) {
                        cell.innerHTML = cell.dataset.originalHtml;
                        delete cell.dataset.originalHtml;
                    }
                });

                const elements = document.querySelectorAll('.user-name[data-original-html], .user-email[data-original-html], .user-role[data-original-html]');
                elements.forEach(el => {
                    if (el.dataset.originalHtml) {
                        el.innerHTML = el.dataset.originalHtml;
                        delete el.dataset.originalHtml;
                    }
                });

                const highlights = document.querySelectorAll('mark.search-highlight');
                highlights.forEach(mark => {
                    const parent = mark.parentNode;
                    const text = document.createTextNode(mark.textContent);
                    parent.replaceChild(text, mark);
                });
            }

            function showNoResultsMessage(tbody, term) {
                removeNoResultsMessage();

                const noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-results-row';
                noResultsRow.innerHTML = `
                        <td colspan="5" class="text-center py-5">
                            <div class="empty-state">
                                <div class="empty-state-icon mb-3">
                                    <i class="fas fa-search fa-4x" style="color: #e9ecef;"></i>
                                </div>
                                <h5 class="text-muted mb-2">Tidak ditemukan</h5>
                                <p class="text-muted mb-4">Tidak ada user yang cocok dengan "${term}"</p>
                                <button class="btn btn-outline-primary btn-sm" onclick="clearSearch()">
                                    <i class="fas fa-times me-2"></i> Hapus Pencarian
                                </button>
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

            function escapeRegex(string) {
                return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
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
            --primary-light: rgba(40, 84, 150, 0.1);
            --primary-gradient: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);
            --primary-color: #285496;
            --danger-color: #ff4757;
            --warning-color: #ffa502;
            --info-color: #17a2b8;
            --success-color: #28a745;
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

        .btn-outline-success:hover {
            background-color: var(--success-color);
            border-color: var(--success-color);
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .search-group .form-control:focus {
            box-shadow: none;
            border-color: #dee2e6;
        }

        .search-highlight {
            background: linear-gradient(120deg, #FFD700 0%, #FFEC8B 100%) !important;
            padding: 2px 4px;
            border-radius: 4px;
            color: #333 !important;
            font-weight: 600;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* Badge Styling */
        .badge {
            font-size: 0.75rem;
            padding: 0.4em 0.8em;
            border-radius: 20px;
        }

        .badge-sm {
            font-size: 0.65rem;
            padding: 0.25em 0.5em;
        }

        .bg-danger {
            background-color: #dc3545 !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
        }

        .bg-info {
            background-color: #17a2b8 !important;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .bg-success {
            background-color: #28a745 !important;
        }

        .bg-secondary {
            background-color: #6c757d !important;
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

        .user-row:hover {
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
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(40, 84, 150, 0.1);
        }

        .modal-header {
            padding: 1.25rem;
            background: var(--primary-gradient);
        }

        .modal-body {
            padding: 1.25rem;
        }

        .modal-footer {
            padding: 1rem 1.25rem;
        }

        /* Access Modal Specific Styles */
        .modal-icon-wrapper {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Tab Navigation */
        .nav-pills {
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 0.5rem;
        }

        .nav-pills .nav-link {
            border-radius: 6px;
            padding: 0.5rem 1rem;
            color: #6c757d;
            transition: all 0.2s ease;
            border: none;
            background: none;
            font-weight: 500;
        }

        .nav-pills .nav-link:hover {
            color: var(--primary-color);
            background-color: rgba(40, 84, 150, 0.05);
        }

        .nav-pills .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }

        /* Scroll Container */
        .access-scroll-container {
            max-height: 300px;
            overflow-y: auto;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }

        /* Checklist Items */
        .access-checklist {
            padding: 0.5rem;
        }

        .access-item {
            padding: 0.75rem;
            border-bottom: 1px solid #f1f3f4;
            transition: all 0.2s ease;
            border-radius: 6px;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
        }

        .access-item:hover {
            background-color: #f8f9fa;
        }

        .access-item:last-child {
            border-bottom: none;
        }

        /* Checkbox Styling - Fix for single checkbox */
        .form-check {
            margin-bottom: 0;
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
            cursor: pointer;
            border: 2px solid #ced4da;
            margin-top: 0;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
        }

        .form-check-input:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }

        .form-check-label {
            cursor: pointer;
            width: 100%;
            padding: 0;
            margin: 0;
        }

        .form-check-input:disabled+.form-check-label {
            cursor: default;
        }

        /* Custom Scrollbar */
        .access-scroll-container::-webkit-scrollbar {
            width: 6px;
        }

        .access-scroll-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .access-scroll-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .access-scroll-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Mobile Optimizations */
        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
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

            .table th:nth-child(3) {
                display: none;
            }

            .table td:nth-child(3) {
                display: none;
            }

            .table th:first-child,
            .table td:first-child {
                width: 10% !important;
            }

            .table th:nth-child(2),
            .table td:nth-child(2) {
                width: 35% !important;
            }

            .table th:nth-child(4),
            .table td:nth-child(4) {
                width: 20% !important;
            }

            .table th:last-child,
            .table td:last-child {
                width: 35% !important;
            }

            .fw-bold {
                font-size: 0.85rem;
                margin-bottom: 0.1rem;
            }

            .text-muted.small {
                font-size: 0.7rem;
            }

            .badge {
                font-size: 0.7rem;
                padding: 0.3em 0.6em;
            }

            /* Access Modal Mobile */
            .modal-dialog {
                margin: 0.5rem;
            }

            .nav-pills .nav-link {
                padding: 0.4rem 0.75rem;
                font-size: 0.85rem;
            }

            .access-item {
                padding: 0.6rem;
            }

            .access-scroll-container {
                max-height: 250px;
            }

            .modal-footer .btn {
                font-size: 0.875rem;
                padding: 0.375rem 0.75rem;
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

            /* Access Modal Small Screens */
            .modal-header {
                padding: 1rem;
            }

            .modal-body {
                padding: 1rem;
            }

            .access-scroll-container {
                max-height: 220px;
            }

            .access-item {
                padding: 0.5rem;
            }

            .form-check-label {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 375px) {
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
                width: 10% !important;
                font-size: 0.75rem;
            }

            .table td:nth-child(2) {
                width: 30% !important;
            }

            .table td:nth-child(4) {
                width: 20% !important;
            }

            .table td:last-child {
                width: 40% !important;
            }

            /* Access Modal Extra Small */
            .nav-pills {
                flex-direction: column;
                gap: 0.25rem;
            }

            .nav-pills .nav-link {
                width: 100%;
                text-align: center;
            }

            .access-item {
                padding: 0.4rem;
            }

            .modal-footer {
                flex-direction: column;
                gap: 0.5rem;
            }

            .modal-footer .btn {
                width: 100%;
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

        /* Custom scrollbar for body */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #1e4274;
        }

        /* Success Alert Position */
        .position-fixed.z-3 {
            z-index: 1060;
        }

        /* Tab Content Animation */
        .tab-pane {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
@endsection