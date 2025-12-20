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
                            <th width="30%" class="d-none d-md-table-cell">Email</th>
                            <th width="20%">Role</th>
                            <th width="20%" class="text-center pe-4">Aksi</th>
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
                                            <!-- Mobile: Minimal info -->
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
                                            'User' => 'bg-secondary'
                                        ];
                                        $roleColor = $roleColors[$user->role->name] ?? 'bg-primary';
                                    @endphp
                                    <span class="badge {{ $roleColor }} user-role">{{ $user->role->name }}</span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group" role="group">
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
@endsection

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
            const tableRows = document.querySelectorAll('.user-row');
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

        // Search Functionality
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

                // Remove existing highlights and restore original HTML
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

                    // Make sure we have enough cells
                    if (cells.length < 5) {
                        row.style.display = 'none';
                        return;
                    }

                    // Get searchable text from each column
                    const userName = getTextFromCell(cells[1]).toLowerCase();
                    const userEmail = cells[2] ? getTextFromCell(cells[2]).toLowerCase() : '';
                    const userRole = getTextFromCell(cells[3]).toLowerCase();

                    // Search in all fields
                    const isMatch = term === '' ||
                        userName.includes(term) ||
                        (userEmail && userEmail.includes(term)) ||
                        userRole.includes(term);

                    if (isMatch) {
                        row.style.display = '';
                        visibleCount++;

                        // Highlight matching text
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

            // Helper function to get text content from a cell
            function getTextFromCell(cell) {
                // Try to get text from specific elements first
                const nameElement = cell.querySelector('.user-name');
                if (nameElement) return nameElement.textContent;

                const emailElement = cell.querySelector('.user-email');
                if (emailElement) return emailElement.textContent;

                const roleElement = cell.querySelector('.user-role');
                if (roleElement) return roleElement.textContent;

                // Fallback to cell text content
                return cell.textContent;
            }

            // Helper function to highlight text in a cell
            function highlightText(cell, term) {
                const text = getTextFromCell(cell);
                const regex = new RegExp(`(${escapeRegex(term)})`, 'gi');

                // Save original HTML if not already saved
                if (!cell.dataset.originalHtml) {
                    cell.dataset.originalHtml = cell.innerHTML;
                }

                // Find the right element to highlight
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

            // Helper function to restore cell content
            function restoreCell(cell) {
                if (cell.dataset.originalHtml) {
                    cell.innerHTML = cell.dataset.originalHtml;
                    delete cell.dataset.originalHtml;
                }

                // Also restore child elements
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
                // Restore all cells with saved original HTML
                const cells = document.querySelectorAll('td[data-original-html]');
                cells.forEach(cell => {
                    if (cell.dataset.originalHtml) {
                        cell.innerHTML = cell.dataset.originalHtml;
                        delete cell.dataset.originalHtml;
                    }
                });

                // Also restore individual elements
                const elements = document.querySelectorAll('.user-name[data-original-html], .user-email[data-original-html], .user-role[data-original-html]');
                elements.forEach(el => {
                    if (el.dataset.originalHtml) {
                        el.innerHTML = el.dataset.originalHtml;
                        delete el.dataset.originalHtml;
                    }
                });

                // Remove any remaining highlight marks
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

            // Dispatch input event to trigger search
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

            /* Mobile: hide email column header and cells */
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
                width: 45% !important;
            }

            .table th:nth-child(3),
            .table td:nth-child(3) {
                width: 20% !important;
            }

            .table th:last-child,
            .table td:last-child {
                width: 20% !important;
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
                width: 12% !important;
                font-size: 0.75rem;
            }

            .table td:nth-child(2) {
                width: 43% !important;
            }

            .table td:nth-child(3) {
                width: 20% !important;
            }

            .table td:last-child {
                width: 25% !important;
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