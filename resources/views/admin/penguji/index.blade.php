@extends('admin.partials.layout')

@section('title', 'Master Penguji - Sistem Pelatihan')

@section('content')
    <!-- Page Header -->
    <div class="page-header bg-gradient-primary rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-user-check fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">Penguji</h1>
                        <p class="text-white-50 mb-0">Kelola data penguji pelatihan</p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('penguji.export') }}?{{ http_build_query(request()->all()) }}"
                       class="btn btn-success btn-hover-lift shadow-sm d-flex align-items-center">
                        <i class="fas fa-file-excel me-2"></i> Export Excel
                    </a>
                    <a href="{{ route('penguji.create') }}" class="btn btn-light btn-hover-lift shadow-sm d-flex align-items-center">
                        <i class="fas fa-plus me-2"></i> Tambah Penguji
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="alert-container mb-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
                <div class="alert-icon flex-shrink-0"><i class="fas fa-check-circle fa-lg"></i></div>
                <div class="flex-grow-1 ms-3"><strong>Sukses!</strong> {{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
                <div class="alert-icon flex-shrink-0"><i class="fas fa-exclamation-circle fa-lg"></i></div>
                <div class="flex-grow-1 ms-3"><strong>Error!</strong> {{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3"><i class="fas fa-users"></i></div>
                        <div><h6 class="text-muted mb-1">Total Penguji</h6><h3 class="mb-0 fw-bold text-primary">{{ $totalPenguji }}</h3></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 text-success me-3"><i class="fas fa-user-check"></i></div>
                        <div><h6 class="text-muted mb-1">Aktif</h6><h3 class="mb-0 fw-bold text-success">{{ $aktifPenguji }}</h3></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-secondary bg-opacity-10 text-secondary me-3"><i class="fas fa-user-times"></i></div>
                        <div><h6 class="text-muted mb-1">Non-Aktif</h6><h3 class="mb-0 fw-bold text-secondary">{{ $nonaktifPenguji }}</h3></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form id="filterForm" method="GET" action="{{ route('penguji.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted mb-1"><i class="fas fa-filter me-1"></i> Filter Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="Aktif"    {{ request('status') == 'Aktif'    ? 'selected' : '' }}>Aktif</option>
                            <option value="Nonaktif" {{ request('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted mb-1"><i class="fas fa-sort me-1"></i> Urutkan</label>
                        <select name="sort" class="form-select">
                            <option value="nama"     {{ request('sort','nama') == 'nama'     ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="kelompok" {{ request('sort') == 'kelompok' ? 'selected' : '' }}>Jumlah Kelompok</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted mb-1"><i class="fas fa-search me-1"></i> Cari Penguji</label>
                        <input type="text" name="search" class="form-control" placeholder="Nama, NIP, Email..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted mb-1"><i class="fas fa-list-ol me-1"></i> Per Halaman</label>
                        <select name="per_page" class="form-select">
                            <option value="10"  {{ request('per_page','10') == '10'  ? 'selected' : '' }}>10</option>
                            <option value="25"  {{ request('per_page') == '25'  ? 'selected' : '' }}>25</option>
                            <option value="50"  {{ request('per_page') == '50'  ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                            <option value="-1"  {{ request('per_page') == '-1'  ? 'selected' : '' }}>Semua</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-filter-primary w-100"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                @if(request()->hasAny(['status','sort','search','per_page']))
                    <div class="row mt-2">
                        <div class="col-12">
                            <a href="{{ route('penguji.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Reset Filter
                            </a>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-lg overflow-hidden">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="card-title mb-0 fw-semibold">
                <i class="fas fa-list me-2" style="color: #285496;"></i> Daftar Penguji
            </h5>
            <small class="text-muted">
                Menampilkan {{ $penguji->firstItem() ?? 0 }} - {{ $penguji->lastItem() ?? 0 }} dari penguji
            </small>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr class="table-light">
                            <th width="4%"  class="ps-4">No</th>
                            <th width="18%">Nama Penguji</th>
                            <th width="12%">NIP</th>
                            <th width="13%">Jabatan</th>
                            <th width="11%">Golongan & Pangkat</th>
                            <th width="14%">Kontak</th>
                            <th width="11%">Informasi Keuangan</th>
                            {{-- <th width="8%"  class="text-center">Status</th> --}}
                            {{-- <th width="9%"  class="text-center">Akun</th> --}}
                            <th width="10%" class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penguji as $item)
                            <tr class="penguji-row">
                                <td class="ps-4 fw-semibold">
                                    {{ ($penguji->currentPage() - 1) * $penguji->perPage() + $loop->iteration }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mentor-icon me-3" style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
                                            <i class="fas fa-user-check"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $item->nama }}</div>
                                            <div class="text-muted small">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $item->dibuat_pada ? \Carbon\Carbon::parse($item->dibuat_pada)->format('d/m/Y H:i') : '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0 fw-semibold">
                                        <i class="fas fa-id-badge me-1"></i>{{ $item->nip ?? '-' }}
                                    </p>
                                </td>
                                <td>
                                    <p class="mb-0 fw-semibold">
                                        <i class="fas fa-briefcase me-1"></i>{{ $item->jabatan ?? '-' }}
                                    </p>
                                </td>
                                <td>
                                    @if($item->golongan || $item->pangkat)
                                        <div class="mb-1">
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1">
                                                <i class="fas fa-layer-group me-1"></i>{{ $item->golongan ?? '-' }}
                                            </span>
                                        </div>
                                        <small class="text-muted"><i class="fas fa-medal me-1"></i>{{ $item->pangkat ?? '-' }}</small>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->email)
                                        <div><i class="fas fa-envelope me-1 text-muted"></i><small>{{ $item->email }}</small></div>
                                    @endif
                                    @if($item->nomor_hp)
                                        <div><i class="fas fa-phone me-1 text-muted"></i><small>{{ $item->nomor_hp }}</small></div>
                                    @endif
                                    @if(!$item->email && !$item->nomor_hp)
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->nomor_rekening)
                                        <div><i class="fas fa-credit-card me-1 text-muted"></i><small>{{ $item->nomor_rekening }}</small></div>
                                    @endif
                                    @if($item->npwp)
                                        <div><i class="fas fa-id-card me-1 text-muted"></i><small>{{ $item->npwp }}</small></div>
                                    @endif
                                    @if(!$item->nomor_rekening && !$item->npwp)
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                {{-- <td class="text-center">
                                    <span class="badge {{ $item->status_aktif ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $item->status_aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td> --}}
                                {{-- <td class="text-center">
                                    @if($item->user)
                                        <span class="badge bg-info text-white" data-bs-toggle="tooltip" title="Akun: {{ $item->user->email }}">
                                            <i class="fas fa-user-circle me-1"></i>Ada
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted">
                                            <i class="fas fa-user-slash me-1"></i>Tidak ada
                                        </span>
                                    @endif
                                </td> --}}
                                <td class="text-center pe-4">
                                    <div class="btn-group" role="group">
                                        {{-- Tombol Kirim WA --}}
                                        @if($item->user && $item->nomor_hp)
                                            <button type="button"
                                                class="btn btn-sm btn-outline-success btn-action send-wa"
                                                data-id="{{ $item->id }}"
                                                data-name="{{ $item->nama }}"
                                                data-bs-toggle="tooltip"
                                                title="Kirim Akun ke WhatsApp">
                                                <i class="fab fa-whatsapp"></i>
                                            </button>
                                        @endif

                                        <a href="{{ route('penguji.edit', $item->id) }}"
                                            class="btn btn-sm btn-outline-warning btn-action"
                                            data-bs-toggle="tooltip" title="Edit Penguji">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger btn-action delete-penguji"
                                            data-id="{{ $item->id }}"
                                            data-name="{{ $item->nama }}"
                                            data-kelompok="{{ $item->kelompok_count }}"
                                            data-bs-toggle="tooltip" title="Hapus Penguji">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <i class="fas fa-user-check fa-4x mb-3" style="color: #e9ecef;"></i>
                                    <h5 class="text-muted mb-2">
                                        @if(request()->hasAny(['status','search'])) Tidak ada hasil ditemukan
                                        @else Belum ada penguji @endif
                                    </h5>
                                    @if(!request()->hasAny(['status','search']))
                                        <a href="{{ route('penguji.create') }}" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus me-1"></i> Tambah Penguji
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($penguji->count() > 0)
            <div class="card-footer bg-white py-3 border-0">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <small class="text-muted">
                            Menampilkan {{ $penguji->firstItem() }} - {{ $penguji->lastItem() }} dari penguji
                        </small>
                    </div>
                    <div class="col-md-6">
                        <nav class="d-flex justify-content-md-end">
                            @if($penguji->hasPages())
                                {{ $penguji->links('pagination::bootstrap-5') }}
                            @else
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>
                                    <li class="page-item active"><span class="page-link">1</span></li>
                                    <li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></li>
                                </ul>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center px-4 pb-4">
                    <i class="fas fa-exclamation-triangle fa-4x mb-3" style="color: #ff4757;"></i>
                    <h4 class="mb-3 fw-bold">Konfirmasi Hapus</h4>
                    <p class="text-muted mb-1">Anda akan menghapus penguji:</p>
                    <h5 class="text-danger mb-4 fw-bold" id="deletePengujiName"></h5>
                    <p class="text-muted small mb-4" id="confirmMessage"></p>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Batal
                    </button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4 btn-lift" id="deleteButton">
                            <i class="fas fa-trash-alt me-2"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- WA Modal -->
    <div class="modal fade" id="waModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="fab fa-whatsapp text-success me-2"></i> Kirim Info Akun via WhatsApp
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 pb-2">
                    <p class="text-muted mb-1">Kirim informasi akun login kepada:</p>
                    <h5 class="fw-bold mb-3" id="waPengujiName"></h5>

                    {{-- State: loading --}}
                    <div id="waStateLoading" class="text-center py-3">
                        <span class="spinner-border text-primary" style="width:2rem;height:2rem;"></span>
                        <p class="text-muted mt-2 mb-0 small">Memuat data...</p>
                    </div>

                    {{-- State: siap kirim --}}
                    <div id="waStateReady" class="d-none">
                        <div class="alert alert-success d-flex align-items-start py-2 mb-0" role="alert">
                            <i class="fas fa-check-circle me-2 mt-1 flex-shrink-0 text-success"></i>
                            <div class="small">Pesan berisi <strong>email</strong> dan <strong>password</strong> akun login akan dikirim ke nomor WhatsApp penguji. Klik tombol di bawah untuk membuka WhatsApp.</div>
                        </div>
                    </div>

                    {{-- State: belum ada password_plain --}}
                    <div id="waStateNoPass" class="d-none">
                        <div class="alert alert-warning d-flex align-items-start py-2 mb-3" role="alert">
                            <i class="fas fa-exclamation-triangle me-2 mt-1 flex-shrink-0"></i>
                            <div class="small">Password akun penguji ini <strong>belum tersimpan</strong> (akun lama sebelum fitur ini diterapkan).<br>Generate otomatis atau ketik password sendiri, lalu klik <strong>"Set & Kirim WA"</strong>.</div>
                        </div>
                        <div class="card border border-primary-subtle rounded-3 p-3" style="background:#f8fbff;">
                            <label class="form-label fw-semibold small mb-2">
                                <i class="fas fa-key me-1 text-primary"></i> Password Baru:
                            </label>
                            <div class="input-group">
                                <input type="text" id="waGeneratedPass"
                                       class="form-control fw-bold"
                                       placeholder="Generate atau ketik password (min. 5 karakter)"
                                       maxlength="50">
                                <button class="btn btn-outline-primary" type="button" id="waRefreshPass"
                                        data-bs-toggle="tooltip" title="Generate otomatis (5 karakter)">
                                    <i class="fas fa-magic"></i>
                                </button>
                                <button class="btn btn-outline-secondary" type="button" id="waCopyPass"
                                        data-bs-toggle="tooltip" title="Salin password">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <div id="waPassFeedback" class="mt-1" style="font-size:.82rem;display:none;"></div>
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle me-1"></i>Minimal 5 karakter. Password ini akan disimpan ke database dan dikirim via WhatsApp.
                            </small>
                        </div>
                    </div>

                    {{-- State: error --}}
                    <div id="waStateError" class="d-none">
                        <div class="alert alert-danger d-flex align-items-start py-2 mb-0" role="alert">
                            <i class="fas fa-times-circle me-2 mt-1 flex-shrink-0"></i>
                            <div class="small" id="waErrorMsg">Terjadi kesalahan.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-3 justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Batal
                    </button>
                    {{-- Tombol kirim WA (muncul saat ready) --}}
                    <a href="#" id="waLink" target="_blank"
                       class="btn btn-success px-4 btn-lift d-none">
                        <i class="fab fa-whatsapp me-2"></i> Buka WhatsApp
                    </a>
                    {{-- Tombol generate + set password (muncul saat no pass) --}}
                    <button type="button" id="waGenerateBtn"
                            class="btn btn-primary px-4 btn-lift d-none">
                        <i class="fas fa-magic me-2"></i> Generate & Set Password
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    document.querySelectorAll('#filterForm select').forEach(s => s.addEventListener('change', () => document.getElementById('filterForm').submit()));

    // ── Delete Modal ──────────────────────────────────────────────
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const deleteBtn   = document.getElementById('deleteButton');
    const confirmMsg  = document.getElementById('confirmMessage');

    document.querySelectorAll('.delete-penguji').forEach(btn => {
        btn.addEventListener('click', function () {
            const kelompok = parseInt(this.dataset.kelompok);
            document.getElementById('deletePengujiName').textContent = this.dataset.name;
            document.getElementById('deleteForm').action = `/penguji/${this.dataset.id}`;

            if (kelompok > 0) {
                confirmMsg.innerHTML = `<i class="fas fa-exclamation-triangle me-1 text-warning"></i>
                    Penguji ini masih terhubung ke <strong>${kelompok} kelompok</strong>.
                    <strong class="text-danger">Tidak dapat dihapus.</strong><br><br>
                    <i class="fas fa-info-circle me-1"></i> Lepaskan penguji dari semua kelompok terlebih dahulu.`;
                deleteBtn.disabled = true;
                deleteBtn.classList.replace('btn-danger', 'btn-secondary');
                deleteBtn.innerHTML = '<i class="fas fa-ban me-2"></i> Tidak Dapat Dihapus';
            } else {
                confirmMsg.innerHTML = `<i class="fas fa-info-circle me-1"></i> Tindakan ini tidak dapat dibatalkan. Semua data penguji akan dihapus.`;
                deleteBtn.disabled = false;
                deleteBtn.classList.replace('btn-secondary', 'btn-danger');
                deleteBtn.innerHTML = '<i class="fas fa-trash-alt me-2"></i> Hapus';
            }
            deleteModal.show();
        });
    });

    document.getElementById('deleteModal').addEventListener('hidden.bs.modal', function () {
        deleteBtn.disabled = false;
        deleteBtn.classList.replace('btn-secondary', 'btn-danger');
        deleteBtn.innerHTML = '<i class="fas fa-trash-alt me-2"></i> Hapus';
    });

    // ── WhatsApp Modal ────────────────────────────────────────────
    const waModal      = new bootstrap.Modal(document.getElementById('waModal'));
    const waLink       = document.getElementById('waLink');
    const waGenerateBtn= document.getElementById('waGenerateBtn');
    const waGeneratedPass = document.getElementById('waGeneratedPass');
    let   currentWaId  = null;

    function waSetState(state) {
        ['waStateLoading','waStateReady','waStateNoPass','waStateError'].forEach(id => {
            document.getElementById(id).classList.add('d-none');
        });
        waLink.classList.add('d-none');
        waGenerateBtn.classList.add('d-none');

        document.getElementById(state).classList.remove('d-none');
        if (state === 'waStateReady')  waLink.classList.remove('d-none');
        if (state === 'waStateNoPass') {
            waGenerateBtn.classList.remove('d-none');
            // Auto-generate password saat panel muncul
            waGeneratedPass.value = generateRandomPassword();
        }
    }

    function generateRandomPassword() {
        const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let pass = '';
        pass += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'[Math.floor(Math.random() * 26)];
        pass += '0123456789'[Math.floor(Math.random() * 10)];
        pass += '0123456789'[Math.floor(Math.random() * 10)];
        pass += 'abcdefghijklmnopqrstuvwxyz'[Math.floor(Math.random() * 26)];
        pass += chars[Math.floor(Math.random() * chars.length)];
        return pass.split('').sort(() => Math.random() - 0.5).join('');
    }

    document.querySelectorAll('.send-wa').forEach(btn => {
        btn.addEventListener('click', function () {
            currentWaId = this.dataset.id;
            document.getElementById('waPengujiName').textContent = this.dataset.name;
            waSetState('waStateLoading');
            waModal.show();

            fetch(`/penguji/${currentWaId}/whatsapp`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    waLink.href = data.link;
                    waSetState('waStateReady');
                } else if (data.need_generate) {
                    waSetState('waStateNoPass');
                } else {
                    document.getElementById('waErrorMsg').textContent = data.message;
                    waSetState('waStateError');
                }
            })
            .catch(() => {
                document.getElementById('waErrorMsg').textContent = 'Gagal menghubungi server.';
                waSetState('waStateError');
            });
        });
    });

    // Refresh/generate password otomatis (5 karakter)
    document.getElementById('waRefreshPass').addEventListener('click', function () {
        waGeneratedPass.value = generateRandomPassword();
        waGeneratedPass.dispatchEvent(new Event('input'));
    });

    // Validasi real-time saat mengetik manual
    waGeneratedPass.addEventListener('input', function () {
        const fb  = document.getElementById('waPassFeedback');
        const val = this.value;
        if (!val) { fb.style.display = 'none'; return; }
        fb.style.display = 'block';
        if (val.length < 5) {
            fb.innerHTML = '<i class="fas fa-times-circle text-danger me-1"></i><span class="text-danger">Password minimal 5 karakter (' + val.length + '/5)</span>';
            waGenerateBtn.disabled = true;
        } else {
            fb.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i><span class="text-success">Password valid (' + val.length + ' karakter)</span>';
            waGenerateBtn.disabled = false;
        }
    });

    // Copy password
    document.getElementById('waCopyPass').addEventListener('click', function () {
        navigator.clipboard.writeText(waGeneratedPass.value).then(() => {
            this.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(() => { this.innerHTML = '<i class="fas fa-copy"></i>'; }, 1500);
        });
    });

    // Generate & Set Password → simpan ke DB → buka WA
    waGenerateBtn.addEventListener('click', function () {
        const password = waGeneratedPass.value;
        if (!password || !currentWaId) return;

        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...';

        fetch(`/penguji/${currentWaId}/generate-password`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ password: password })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                waLink.href = data.link;
                waSetState('waStateReady');
            } else {
                document.getElementById('waErrorMsg').textContent = data.message;
                waSetState('waStateError');
            }
        })
        .catch(() => {
            document.getElementById('waErrorMsg').textContent = 'Gagal menyimpan password.';
            waSetState('waStateError');
        })
        .finally(() => {
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-magic me-2"></i> Generate & Set Password';
        });
    });

    // Reset modal saat ditutup
    document.getElementById('waModal').addEventListener('hidden.bs.modal', function () {
        currentWaId = null;
        waLink.href = '#';
        waSetState('waStateLoading');
    });

    // ── Auto-hide alerts ──────────────────────────────────────────
    document.querySelectorAll('.alert').forEach(a => {
        setTimeout(() => { if (a.classList.contains('show')) bootstrap.Alert.getOrCreateInstance(a).close(); }, 5000);
    });
});
</script>

<style>
    .page-header { padding:2rem;box-shadow:0 4px 20px rgba(40,84,150,.15); }
    .icon-wrapper { width:60px;height:60px;display:flex;align-items:center;justify-content:center; }
    .stat-card { border-radius:12px;transition:transform .3s,box-shadow .3s;border:1px solid #e9ecef; }
    .stat-card:hover { transform:translateY(-5px);box-shadow:0 8px 25px rgba(0,0,0,.1) !important; }
    .stat-icon { width:48px;height:48px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.25rem; }
    .mentor-icon { width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-size:1.1rem;box-shadow:0 4px 8px rgba(40,84,150,.2); }
    .btn-filter-primary { background:linear-gradient(135deg,#285496,#3a6bc7);border:none;color:white;transition:all .3s; }
    .btn-filter-primary:hover { background:linear-gradient(135deg,#1e3d6f,#2d5499);transform:translateY(-2px);color:white;box-shadow:0 8px 25px rgba(40,84,150,.4); }
    .btn-action { border-radius:8px;padding:.375rem .75rem;margin:0 2px;transition:all .2s;border-width:2px; }
    .btn-action:hover { transform:translateY(-2px);box-shadow:0 4px 8px rgba(0,0,0,.1); }
    .btn-lift:hover { transform:translateY(-2px); }
    .btn-hover-lift:hover { transform:translateY(-2px);box-shadow:0 6px 16px rgba(0,0,0,.15) !important; }
    .table th { border-bottom:2px solid #dee2e6;font-weight:600;color:#285496;background-color:#f8fafc;padding:1rem; }
    .table td { padding:.85rem 1rem;vertical-align:middle;border-bottom:1px solid #e9ecef; }
    .penguji-row:hover { background-color:rgba(40,84,150,.03) !important; }
    .pagination .page-link { color:#4A5568;border:1px solid #E2E8F0;border-radius:6px; }
    .pagination .page-item.active .page-link { background-color:#285496;border-color:#285496;color:white; }
</style>
@endsection