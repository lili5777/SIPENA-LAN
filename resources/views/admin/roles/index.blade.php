@extends('admin.partials.layout')

@section('title', 'Manajemen Role - Sistem Inventori Obat')

@section('content')
    <!-- Page Header -->
    <div class="page-header bg-gradient-primary rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-user-tag fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">Manajemen Role</h1>
                        <p class="text-white-50 mb-0">Kelola role dan hak akses pengguna dalam sistem</p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('roles.create') }}" class="btn btn-light btn-hover-lift shadow-sm"
                    data-bs-toggle="tooltip" data-bs-placement="left" title="Buat role baru">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Role
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

    <!-- Role Table Card -->
    <div class="card border-0 shadow-lg overflow-hidden">
        <div class="card-header bg-white py-3 border-0">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-list me-2" style="color: #285496;"></i> Daftar Role
                    </h5>
                </div>
                <div class="col-auto">
                    <div class="input-group search-group" style="max-width: 300px;">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" id="searchInput"
                            placeholder="Cari role atau deskripsi...">
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
                <table class="table table-hover mb-0" id="roleTable">
                    <thead>
                        <tr class="table-light">
                            <th width="5%" class="ps-4">No</th>
                            <th width="30%">Role</th>
                            <th width="40%" class="d-none d-md-table-cell">Deskripsi</th>
                            <th width="25%" class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $index => $role)
                            <tr class="role-row" data-role-id="{{ $role->id }}">
                                <td class="ps-4 fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="role-avatar me-3"
                                            style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
                                            <i class="fas fa-user-tag"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $role->name }}</div>
                                            <!-- Desktop: Full info -->
                                            <div class="text-muted small d-none d-md-block">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $role->users_count ?? 0 }} user
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-key me-1"></i>
                                                {{ $role->permissions_count ?? 0 }} permissions
                                            </div>
                                            <!-- Mobile: Minimal info - hanya icon tanpa count -->
                                            <div class="text-muted small d-md-none">
                                                <i class="fas fa-user me-1"></i>
                                                <i class="fas fa-key me-1"></i>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <p class="mb-0 text-muted">{{ $role->description ?? 'Tidak ada deskripsi' }}</p>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-info btn-action view-permissions"
                                            data-bs-toggle="modal" data-bs-target="#permissionsModal"
                                            data-role-name="{{ $role->name }}"
                                            data-permissions="{{ $role->permissions->pluck('name')->toJson() }}"
                                            data-bs-toggle="tooltip" title="Lihat Hak Akses">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('roles.edit', $role) }}"
                                            class="btn btn-sm btn-outline-warning btn-action" data-bs-toggle="tooltip"
                                            title="Edit Role">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-action delete-role"
                                            data-id="{{ $role->id }}" data-name="{{ $role->name }}"
                                            data-users="{{ $role->users_count ?? 0 }}" data-bs-toggle="tooltip"
                                            title="Hapus Role">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-state-row">
                                <td colspan="4" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mb-3">
                                            <i class="fas fa-user-tag fa-4x" style="color: #e9ecef;"></i>
                                        </div>
                                        <h5 class="text-muted mb-2">Belum ada role</h5>
                                        <p class="text-muted mb-4">Mulai dengan membuat role pertama Anda</p>
                                        <a href="{{ route('roles.create') }}" class="btn btn-primary btn-lg px-4">
                                            <i class="fas fa-plus-circle me-2"></i> Buat Role Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($roles->count() > 0)
            <div class="card-footer bg-white py-3 border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <small class="text-muted">
                            Menampilkan {{ $roles->count() }} role
                        </small>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

<!-- Permissions Modal -->
<div class="modal fade" id="permissionsModal" tabindex="-1" aria-labelledby="permissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
                <h5 class="modal-title text-white" id="permissionsModalLabel">
                    <i class="fas fa-key me-2"></i>
                    Hak Akses: <span id="modalRoleName"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="permissions-container p-4">
                    <div id="permissionsList">
                        <!-- Permissions will be loaded here -->
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Tutup
                </button>
            </div>
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
                <p class="text-muted mb-1">Anda akan menghapus role:</p>
                <h5 class="text-danger mb-4 fw-bold" id="deleteRoleName"></h5>

                <div class="alert alert-warning mb-4" id="deleteWarning" style="display: none;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span id="warningMessage"></span>
                </div>

                <p class="text-muted small mb-4">
                    <i class="fas fa-info-circle me-1"></i>
                    Tindakan ini tidak dapat dibatalkan. Semua hak akses yang terkait akan dihapus.
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

                    // Delete Role Confirmation
                    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                    const deleteForm = document.getElementById('deleteForm');
                    const deleteRoleName = document.getElementById('deleteRoleName');
                    const deleteWarning = document.getElementById('deleteWarning');
                    const warningMessage = document.getElementById('warningMessage');

                    document.querySelectorAll('.delete-role').forEach(button => {
                        button.addEventListener('click', function () {
                            const roleId = this.getAttribute('data-id');
                            const roleName = this.getAttribute('data-name');
                            const userCount = parseInt(this.getAttribute('data-users'));

                            deleteRoleName.textContent = roleName;
                            deleteForm.action = `{{ url('roles') }}/${roleId}`;

                            // Show warning if role has users
                            if (userCount > 0) {
                                warningMessage.textContent = `Role ini digunakan oleh ${userCount} user. Menghapus role akan mencabut akses mereka.`;
                                deleteWarning.style.display = 'block';
                            } else {
                                deleteWarning.style.display = 'none';
                            }

                            deleteModal.show();
                        });
                    });

                    // View Permissions Modal
                    const viewPermissionsButtons = document.querySelectorAll('.view-permissions');
                    const modalRoleName = document.getElementById('modalRoleName');
                    const permissionsList = document.getElementById('permissionsList');

                    viewPermissionsButtons.forEach(button => {
                        button.addEventListener('click', function () {
                            const roleName = this.getAttribute('data-role-name');
                            const permissions = JSON.parse(this.getAttribute('data-permissions'));

                            modalRoleName.textContent = roleName;

                            if (permissions.length > 0) {
                                const groupedPermissions = groupPermissionsByModule(permissions);
                                permissionsList.innerHTML = generateGroupedPermissionsHTML(groupedPermissions);
                            } else {
                                permissionsList.innerHTML = `
                                    <div class="text-center py-5">
                                        <div class="empty-permissions mb-4">
                                            <i class="fas fa-ban fa-4x" style="color: #e9ecef;"></i>
                                        </div>
                                        <h5 class="text-muted mb-2">Belum ada hak akses</h5>
                                        <p class="text-muted">Role ini belum memiliki permissions yang diberikan</p>
                                    </div>
                                `;
                            }
                        });
                    });

                    // Group permissions by module
                    function groupPermissionsByModule(permissions) {
                        const grouped = {};

                        permissions.forEach(permission => {
                            const parts = permission.split('.');
                            const module = parts[0] ? parts[0].replace(/_/g, ' ') : 'general';
                            const action = parts[1] || permission;

                            if (!grouped[module]) {
                                grouped[module] = [];
                            }

                            grouped[module].push({
                                action: action,
                                fullName: permission
                            });
                        });

                        return grouped;
                    }

                    // Generate HTML for grouped permissions
                    function generateGroupedPermissionsHTML(groupedPermissions) {
                        const actionLabels = {
                            'create': 'Tambah',
                            'read': 'Lihat',
                            'update': 'Edit',
                            'delete': 'Hapus',
                            'export': 'Export',
                            'import': 'Import',
                            'manage': 'Kelola',
                            'view': 'Lihat',
                            'edit': 'Edit',
                            'store': 'Simpan',
                            'destroy': 'Hapus',
                            'index': 'Daftar',
                            'show': 'Detail'
                        };

                        const actionIcons = {
                            'create': 'fa-plus',
                            'read': 'fa-eye',
                            'update': 'fa-edit',
                            'delete': 'fa-trash',
                            'export': 'fa-download',
                            'import': 'fa-upload',
                            'manage': 'fa-cogs',
                            'view': 'fa-eye',
                            'edit': 'fa-edit',
                            'store': 'fa-save',
                            'destroy': 'fa-trash',
                            'index': 'fa-list',
                            'show': 'fa-eye'
                        };

                        let html = '<div class="permissions-grid">';

                        Object.keys(groupedPermissions).forEach(module => {
                            const actions = groupedPermissions[module];
                            const moduleName = module.charAt(0).toUpperCase() + module.slice(1);

                            html += `
                                <div class="permission-module-card">
                                    <div class="module-header">
                                        <h6 class="mb-0">${moduleName}</h6>
                                        <span class="badge bg-primary rounded-pill">${actions.length}</span>
                                    </div>
                                    <div class="permissions-list">
                            `;

                            actions.forEach(item => {
                                const action = item.action;
                                const label = actionLabels[action] || action.charAt(0).toUpperCase() + action.slice(1);
                                const icon = actionIcons[action] || 'fa-check';

                                html += `
                                    <div class="permission-item">
                                        <i class="fas ${icon} me-2" style="color: #285496;"></i>
                                        <span>${label}</span>
                                        <small class="text-muted ms-2 d-none d-md-inline">${item.fullName}</small>
                                    </div>
                                `;
                            });

                            html += `
                                    </div>
                                </div>
                            `;
                        });

                        html += '</div>';
                        return html;
                    }

                    // Enhanced Search Functionality
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
                    const tableRows = document.querySelectorAll('.role-row');
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

                // Search Functionality - Fixed Version
                    function initializeSearch() {
                        const searchInput = document.getElementById('searchInput');
                        const clearSearchBtn = document.getElementById('clearSearch');
                        const clearSearchBtn2 = document.getElementById('clearSearchBtn');
                        const searchInfo = document.getElementById('searchInfo');
                        const searchCount = document.getElementById('searchCount');
                        const roleTable = document.getElementById('roleTable');

                        let debounceTimer;

                        // Clear search button event
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

                        // Search input event
                        searchInput.addEventListener('input', function (e) {
                            clearTimeout(debounceTimer);

                            // Show/hide clear button
                            if (this.value.trim() !== '') {
                                clearSearchBtn.style.display = 'block';
                            } else {
                                clearSearchBtn.style.display = 'none';
                            }

                            debounceTimer = setTimeout(() => {
                                performSearch(this.value);
                            }, 300);
                        });

                        function performSearch(searchTerm) {
                            const term = searchTerm.toLowerCase().trim();
                            const tbody = roleTable.querySelector('tbody');
                            const rows = tbody.querySelectorAll('tr');
                            let visibleCount = 0;

                            // Remove existing highlights and restore original HTML
                            clearHighlights();

                            rows.forEach(row => {
                                // Skip empty state row
                                if (row.classList.contains('empty-state-row')) {
                                    row.style.display = term === '' ? '' : 'none';
                                    return;
                                }

                                // Skip no-results row if exists
                                if (row.classList.contains('no-results-row')) {
                                    row.remove();
                                    return;
                                }

                                const cells = row.querySelectorAll('td');

                                // Make sure we have enough cells
                                if (cells.length < 3) {
                                    row.style.display = 'none';
                                    return;
                                }

                                // Get only the role name text (excluding icon and counts)
                                const roleNameCell = cells[1];
                                const roleNameText = getRoleNameText(roleNameCell).toLowerCase();

                                // Search only in role name, not in description or counts
                                const isMatch = term === '' || roleNameText.includes(term);

                                if (isMatch) {
                                    row.style.display = '';
                                    visibleCount++;

                                    // Highlight matching text in role name only
                                    if (term !== '') {
                                        highlightRoleName(roleNameCell, term);
                                    } else {
                                        restoreRoleName(roleNameCell);
                                    }
                                } else {
                                    row.style.display = 'none';
                                    restoreRoleName(roleNameCell);
                                }
                            });

                            // Update search info
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

                        // Helper function to get only the role name text
                        function getRoleNameText(cell) {
                            const roleNameElement = cell.querySelector('.fw-bold');
                            return roleNameElement ? roleNameElement.textContent : cell.textContent;
                        }

                        // Helper function to highlight only the role name
                        function highlightRoleName(cell, term) {
                            const roleNameElement = cell.querySelector('.fw-bold');
                            if (!roleNameElement) return;

                            // Save original HTML if not already saved
                            if (!roleNameElement.dataset.originalHtml) {
                                roleNameElement.dataset.originalHtml = roleNameElement.innerHTML;
                            }

                            const text = roleNameElement.textContent;
                            const regex = new RegExp(`(${escapeRegex(term)})`, 'gi');
                            const highlightedText = text.replace(regex, '<mark class="search-highlight">$1</mark>');

                            // Update only if there's a match
                            if (text !== highlightedText) {
                                roleNameElement.innerHTML = highlightedText;
                            }
                        }

                        // Helper function to restore original role name HTML
                        function restoreRoleName(cell) {
                            const roleNameElement = cell.querySelector('.fw-bold');
                            if (roleNameElement && roleNameElement.dataset.originalHtml) {
                                roleNameElement.innerHTML = roleNameElement.dataset.originalHtml;
                                delete roleNameElement.dataset.originalHtml;
                            }
                        }

                        function clearHighlights() {
                            // Remove highlight marks
                            const highlighted = document.querySelectorAll('mark.search-highlight');
                            highlighted.forEach(mark => {
                                const parent = mark.parentNode;
                                if (parent && parent.classList.contains('fw-bold')) {
                                    // Restore original HTML if available
                                    if (parent.dataset.originalHtml) {
                                        parent.innerHTML = parent.dataset.originalHtml;
                                        delete parent.dataset.originalHtml;
                                    } else {
                                        // Fallback: replace mark with its text content
                                        const text = document.createTextNode(mark.textContent);
                                        mark.parentNode.replaceChild(text, mark);
                                    }
                                }
                            });
                        }

                        function showNoResultsMessage(tbody, term) {
                            // Check if message already exists
                            const existing = tbody.querySelector('.no-results-row');
                            if (existing) return;

                            const noResultsRow = document.createElement('tr');
                            noResultsRow.className = 'no-results-row';
                            noResultsRow.innerHTML = `
                    <td colspan="4" class="text-center py-5">
                        <div class="empty-state">
                            <div class="empty-state-icon mb-3">
                                <i class="fas fa-search fa-4x" style="color: #e9ecef;"></i>
                            </div>
                            <h5 class="text-muted mb-2">Tidak ditemukan</h5>
                            <p class="text-muted mb-4">Tidak ada role yang cocok dengan "${term}"</p>
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

                    // Clear search function for button onclick
                    function clearSearch() {
                        const searchInput = document.getElementById('searchInput');
                        const clearSearchBtn = document.getElementById('clearSearch');

                        searchInput.value = '';
                        clearSearchBtn.style.display = 'none';

                        // Dispatch input event to trigger search
                        const event = new Event('input');
                        searchInput.dispatchEvent(event);
                    }
            </script>

            <style>
               /* Add to your existing styles */
    .search-highlight {
        background: linear-gradient(120deg, #FFD700 0%, #FFEC8B 100%) !important;
        padding: 2px 4px;
        border-radius: 4px;
        color: #333 !important;
        font-weight: 600;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
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

                /* Role Avatar */
                .role-avatar {
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
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                }

                .search-group .form-control:focus {
                    box-shadow: none;
                    border-color: #dee2e6;
                }

                .search-highlight {
                    background-color: #fff3cd;
                    padding: 2px 4px;
                    border-radius: 4px;
                    color: #856404;
                    font-weight: 600;
                }

                /* Permissions Modal */
                .permissions-container {
                    max-height: 60vh;
                    overflow-y: auto;
                }

                .permissions-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                    gap: 1rem;
                }

                .permission-module-card {
                    background: var(--light-color);
                    border-radius: 10px;
                    padding: 1rem;
                    border: 1px solid #e9ecef;
                    transition: transform 0.2s ease;
                }

                .permission-module-card:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                }

                .module-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 0.75rem;
                    padding-bottom: 0.5rem;
                    border-bottom: 2px solid var(--primary-light);
                }

                .permission-item {
                    display: flex;
                    align-items: center;
                    padding: 0.5rem;
                    background: white;
                    border-radius: 6px;
                    margin-bottom: 0.5rem;
                    border-left: 3px solid var(--primary-color);
                }

                .permission-item:last-child {
                    margin-bottom: 0;
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

                .role-row:hover {
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

                /* Mobile Optimizations */
                @media (max-width: 768px) {
                    .page-header {
                        padding: 1.5rem;
                    }

                    .permissions-grid {
                        grid-template-columns: 1fr;
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

                    .role-avatar {
                        width: 36px;
                        height: 36px;
                        font-size: 0.9rem;
                        margin-right: 0.5rem !important;
                    }

                    /* Mobile: hide description column header and cells */
                    .table th:nth-child(3) {
                        display: none;
                    }

                    .table td:nth-child(3) {
                        display: none;
                    }

                    /* Mobile: adjust column widths */
                    .table th:first-child,
                    .table td:first-child {
                        width: 15% !important;
                    }

                    .table th:nth-child(2),
                    .table td:nth-child(2) {
                        width: 50% !important;
                    }

                    .table th:last-child,
                    .table td:last-child {
                        width: 35% !important;
                    }

                    /* Mobile role info - minimal */
                    .fw-bold {
                        font-size: 0.85rem;
                        margin-bottom: 0.1rem;
                    }

                    .text-muted.small {
                        font-size: 0.7rem;
                    }

                    /* Hide count numbers on mobile */
                    .text-muted.small .fa-user:after,
                    .text-muted.small .fa-key:after {
                        content: none;
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
                    .role-avatar {
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
                }

                @media (max-width: 375px) {

                    /* Extra small phones */
                    .role-avatar {
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
                        width: 12% !important;
                        font-size: 0.75rem;
                    }

                    .table td:nth-child(2) {
                        width: 48% !important;
                    }

                    .table td:last-child {
                        width: 40% !important;
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
                ::-webkit-scrollbar {
                    width: 6px;
                    height: 6px;
                }

                ::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 10px;
                }

                ::-webkit-scrollbar-thumb {
                    background: var(--primary-color);
                    border-radius: 10px;
                }

                ::-webkit-scrollbar-thumb:hover {
                    background: #1e4274;
                }
            </style>
@endsection