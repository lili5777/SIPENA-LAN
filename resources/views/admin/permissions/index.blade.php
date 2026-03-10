@extends('admin.partials.layout')

@section('title', 'Manajemen Permission - Sistem Inventori Obat')

@section('content')
    <!-- Page Header -->
    <div class="page-header rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%); padding: 2rem;">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="bg-white rounded-circle p-3 me-3 shadow"
                        style="width:60px;height:60px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-key fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">Manajemen Permission</h1>
                        <p class="text-white-50 mb-0">Kelola hak akses yang tersedia dalam sistem</p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('permissions.create') }}" class="btn btn-light shadow-sm">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Permission
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Section -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-check-circle fa-lg me-3"></i>
            <div class="flex-grow-1">{!! session('success') !!}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-exclamation-circle fa-lg me-3"></i>
            <div class="flex-grow-1">{!! session('error') !!}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold" style="color:#285496;">{{ $permissions->count() }}</div>
                <div class="text-muted small">Total Permission</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold" style="color:#285496;">{{ $groupedPermissions->count() }}</div>
                <div class="text-muted small">Total Module</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold" style="color:#285496;">
                    {{ $permissions->where('roles_count', 0)->count() }}
                </div>
                <div class="text-muted small">Tidak Digunakan</div>
            </div>
        </div>
    </div>

    <!-- Permission Table Card -->
    <div class="card border-0 shadow-lg overflow-hidden">
        <div class="card-header bg-white py-3 border-0">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-list me-2" style="color: #285496;"></i> Daftar Permission
                    </h5>
                </div>
                <div class="col-auto d-flex gap-2">
                    <!-- Filter Module -->
                    <select class="form-select form-select-sm" id="moduleFilter" style="min-width:150px;">
                        <option value="">Semua Module</option>
                        @foreach($groupedPermissions->keys() as $module)
                            <option value="{{ $module }}">{{ ucfirst($module) }}</option>
                        @endforeach
                    </select>
                    <!-- Search -->
                    <div class="input-group input-group-sm" style="max-width: 250px;">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" id="searchInput"
                            placeholder="Cari permission...">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch" style="display:none;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="permissionTable">
                    <thead>
                        <tr class="table-light">
                            <th width="5%" class="ps-4">No</th>
                            <th width="30%">Permission</th>
                            <th width="15%">Module</th>
                            <th width="30%" class="d-none d-md-table-cell">Deskripsi</th>
                            <th width="10%" class="text-center">Roles</th>
                            <th width="10%" class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $index => $permission)
                            @php
                                $parts = explode('.', $permission->name);
                                $module = $parts[0] ?? '-';
                                $action = $parts[1] ?? $permission->name;
                            @endphp
                            <tr class="permission-row" data-module="{{ $module }}">
                                <td class="ps-4 fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="perm-avatar me-3"
                                            style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
                                            <i class="fas fa-key"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-monospace" style="font-size:.9rem;">
                                                {{ $permission->name }}
                                            </div>
                                            <div class="text-muted small">
                                                {{ ucfirst($action) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill"
                                        style="background:rgba(40,84,150,.12);color:#285496;font-weight:600;">
                                        {{ ucfirst($module) }}
                                    </span>
                                </td>
                                <td class="d-none d-md-table-cell text-muted">
                                    {{ $permission->description ?? 'Tidak ada deskripsi' }}
                                </td>
                                <td class="text-center">
                                    @if($permission->roles_count > 0)
                                        <span class="badge bg-success rounded-pill">{{ $permission->roles_count }}</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill">0</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('permissions.edit', $permission) }}"
                                            class="btn btn-sm btn-outline-warning btn-action"
                                            title="Edit Permission">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger btn-action delete-permission"
                                            data-id="{{ $permission->id }}"
                                            data-name="{{ $permission->name }}"
                                            data-roles="{{ $permission->roles_count }}"
                                            title="Hapus Permission">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-state-row">
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-key fa-4x mb-3" style="color: #e9ecef;"></i>
                                        <h5 class="text-muted mb-2">Belum ada permission</h5>
                                        <p class="text-muted mb-4">Mulai dengan membuat permission pertama</p>
                                        <a href="{{ route('permissions.create') }}" class="btn btn-primary px-4">
                                            <i class="fas fa-plus-circle me-2"></i> Buat Permission
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($permissions->count() > 0)
            <div class="card-footer bg-white py-3 border-0">
                <small class="text-muted">Menampilkan {{ $permissions->count() }} permission</small>
            </div>
        @endif
    </div>
@endsection

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:15px;overflow:hidden;">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center px-4 pb-4">
                <i class="fas fa-exclamation-triangle fa-4x mb-3" style="color: #ff4757;"></i>
                <h4 class="fw-bold mb-3">Konfirmasi Hapus</h4>
                <p class="text-muted mb-1">Anda akan menghapus permission:</p>
                <h5 class="text-danger fw-bold mb-4 text-monospace" id="deletePermName"></h5>

                <div class="alert alert-warning mb-4" id="deleteWarning" style="display:none;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span id="warningMessage"></span>
                </div>

                <p class="text-muted small mb-4">
                    <i class="fas fa-info-circle me-1"></i>
                    Tindakan ini tidak dapat dibatalkan. Permission akan dicabut dari semua role terkait.
                </p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Batal
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">
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
    // ── Delete Modal ──────────────────────────────────────────────
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const deleteForm  = document.getElementById('deleteForm');

    document.querySelectorAll('.delete-permission').forEach(btn => {
        btn.addEventListener('click', function () {
            const id    = this.dataset.id;
            const name  = this.dataset.name;
            const roles = parseInt(this.dataset.roles);

            document.getElementById('deletePermName').textContent = name;
            deleteForm.action = `{{ url('permissions') }}/${id}`;

            const warn = document.getElementById('deleteWarning');
            if (roles > 0) {
                document.getElementById('warningMessage').textContent =
                    `Permission ini digunakan oleh ${roles} role. Menghapus akan mencabut akses tersebut.`;
                warn.style.display = 'block';
            } else {
                warn.style.display = 'none';
            }
            deleteModal.show();
        });
    });

    // ── Search ────────────────────────────────────────────────────
    const searchInput  = document.getElementById('searchInput');
    const clearBtn     = document.getElementById('clearSearch');
    const moduleFilter = document.getElementById('moduleFilter');
    let debounce;

    function applyFilters() {
        const term   = searchInput.value.toLowerCase().trim();
        const module = moduleFilter.value.toLowerCase();
        let visible  = 0;

        document.querySelectorAll('.permission-row').forEach(row => {
            const name     = row.querySelector('.fw-bold').textContent.toLowerCase();
            const rowModule = row.dataset.module.toLowerCase();

            const matchTerm   = term === '' || name.includes(term);
            const matchModule = module === '' || rowModule === module;

            if (matchTerm && matchModule) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        // No results row
        const tbody    = document.querySelector('#permissionTable tbody');
        const existing = tbody.querySelector('.no-results-row');
        if (visible === 0 && (term !== '' || module !== '')) {
            if (!existing) {
                const tr = document.createElement('tr');
                tr.className = 'no-results-row';
                tr.innerHTML = `<td colspan="6" class="text-center py-5">
                    <i class="fas fa-search fa-3x mb-3" style="color:#e9ecef;"></i>
                    <h5 class="text-muted">Tidak ditemukan</h5>
                    <p class="text-muted">Coba ubah kata kunci atau filter module</p>
                </td>`;
                tbody.appendChild(tr);
            }
        } else if (existing) {
            existing.remove();
        }
    }

    searchInput.addEventListener('input', function () {
        clearBtn.style.display = this.value ? 'block' : 'none';
        clearTimeout(debounce);
        debounce = setTimeout(applyFilters, 250);
    });

    clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        this.style.display = 'none';
        applyFilters();
    });

    moduleFilter.addEventListener('change', applyFilters);

    // ── Auto-hide alerts ──────────────────────────────────────────
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            if (alert.classList.contains('show')) {
                bootstrap.Alert.getOrCreateInstance(alert)?.close();
            }
        }, 5000);
    });
});
</script>

<style>
    .perm-avatar {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 1rem;
        box-shadow: 0 4px 8px rgba(40,84,150,.2);
        flex-shrink: 0;
    }
    .btn-action {
        border-radius: 8px; padding: .375rem .65rem;
        margin: 0 2px; transition: all .2s ease; border-width: 2px;
    }
    .btn-action:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,.1); }
    .table th { border-bottom: 2px solid #e3ecf7; font-weight: 600; color: #285496; background: #f8fafc; padding: 1rem; }
    .table td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #e9ecef; }
    .text-monospace { font-family: 'Courier New', monospace; }
    .empty-state { padding: 2rem 1rem; }
    @keyframes slideInDown {
        from { transform: translateY(-100%); opacity: 0; }
        to   { transform: translateY(0);     opacity: 1; }
    }
    .alert { animation: slideInDown .3s ease; border-radius: 10px; border: none; }
</style>
@endsection