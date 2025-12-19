@extends('admin.partials.layout')

@section('title', 'Manajemen User - Sistem Inventori Obat')

@section('content')
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h1>Manajemen User</h1>
            </div>
            <div class="col-auto">
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Tambah User
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Section -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- User Table -->
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="card-title mb-0">Daftar User</h5>
                </div>
                <div class="col-auto">
                    <div class="input-group" style="max-width: 300px;">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" id="searchInput"
                            placeholder="Cari nama user, email, atau role...">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Search Results Info -->
            <div id="searchInfo" class="mb-3" style="display: none;">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Menampilkan <span id="searchCount">0</span> hasil dari pencarian
                </small>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="userTable">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Nama User</th>
                            <th width="30%">Email</th>
                            <th width="20%">Role</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr data-user-id="{{ $user->id }}">
                                <td data-label="No">{{ $index + 1 }}</td>
                                <td data-label="Nama User">
                                    <div class="d-flex align-items-center">
                                        <div class="user-icon me-2">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <span>{{ $user->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Email">
                                    <span class="text-muted">{{ $user->email }}</span>
                                </td>
                                <td data-label="Role">
                                    <span class="badge bg-primary">{{ $user->role->name }}</span>
                                </td>
                                <td data-label="Aksi">
                                    <div class="btn-group action-btn-group" role="group">
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning"
                                            title="Edit User">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger delete-user"
                                            data-id="{{ $user->id }}" data-name="{{ $user->name }}" title="Hapus User">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-state-row">
                                <td colspan="5" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                                        <h5 class="text-muted mb-2">Tidak ada data user</h5>
                                        <p class="text-muted mb-3">Belum ada user yang terdaftar dalam sistem</p>
                                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i> Tambah User Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                        <p class="mb-2">Apakah Anda yakin ingin menghapus user:</p>
                        <strong class="text-danger" id="deleteUserName"></strong>
                        <p class="text-warning mt-3">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <small>Tindakan ini tidak dapat dibatalkan!</small>
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Delete User Confirmation
            const deleteButtons = document.querySelectorAll('.delete-user');
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const deleteForm = document.getElementById('deleteForm');
            const deleteUserName = document.getElementById('deleteUserName');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const userId = this.getAttribute('data-id');
                    const userName = this.getAttribute('data-name');

                    deleteUserName.textContent = userName;
                    deleteForm.action = `{{ url('users') }}/${userId}`;
                    deleteModal.show();
                });
            });

            // Search functionality
            initializeSearch();

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.classList.contains('show')) {
                        bootstrap.Alert.getOrCreateInstance(alert).close();
                    }
                }, 5000);
            });
        });

        // Search functionality
        function initializeSearch() {
            const searchInput = document.getElementById('searchInput');
            const userTable = document.getElementById('userTable');
            const searchInfo = document.getElementById('searchInfo');
            const searchCount = document.getElementById('searchCount');

            let debounceTimer;

            searchInput.addEventListener('input', function (e) {
                clearTimeout(debounceTimer);

                debounceTimer = setTimeout(() => {
                    performSearch(e.target.value);
                }, 200);
            });

            function performSearch(searchTerm) {
                const term = searchTerm.toLowerCase().trim();
                const tbody = userTable.querySelector('tbody');
                const rows = tbody.querySelectorAll('tr');
                let visibleCount = 0;

                // Remove previous no-results message
                const existingNoResults = tbody.querySelector('.no-results-row');
                if (existingNoResults) {
                    existingNoResults.remove();
                }

                rows.forEach(row => {
                    // Skip empty state row
                    if (row.classList.contains('empty-state-row')) {
                        row.style.display = term === '' ? '' : 'none';
                        return;
                    }

                    const cells = row.querySelectorAll('td');

                    // Make sure we have the right number of cells
                    if (cells.length < 5) {
                        return;
                    }

                    // Clear previous highlights
                    clearHighlights(row);

                    // Get text content from relevant columns
                    const userName = cells[1].textContent.toLowerCase();
                    const userEmail = cells[2].textContent.toLowerCase();
                    const userRole = cells[3].textContent.toLowerCase();

                    // Check if search term matches
                    const nameMatch = userName.includes(term);
                    const emailMatch = userEmail.includes(term);
                    const roleMatch = userRole.includes(term);
                    const isMatch = term === '' || nameMatch || emailMatch || roleMatch;

                    if (isMatch) {
                        row.style.display = '';
                        visibleCount++;

                        // Highlight matching terms
                        if (term !== '') {
                            if (nameMatch) highlightText(cells[1], term);
                            if (emailMatch) highlightText(cells[2], term);
                            if (roleMatch) highlightText(cells[3], term);
                        }
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show/hide search info
                if (term === '') {
                    searchInfo.style.display = 'none';
                } else {
                    searchCount.textContent = visibleCount;
                    searchInfo.style.display = 'block';

                    // Show no results message if no matches found
                    if (visibleCount === 0) {
                        showNoResultsMessage(tbody, term);
                    }
                }
            }

            function clearHighlights(row) {
                const highlightedElements = row.querySelectorAll('mark');
                highlightedElements.forEach(mark => {
                    mark.replaceWith(mark.textContent);
                });
            }

            function highlightText(element, searchTerm) {
                // Skip if element contains HTML (like the user icon)
                const strongElement = element.querySelector('strong');
                if (strongElement) {
                    const text = strongElement.textContent;
                    const highlightedText = text.replace(
                        new RegExp(`(${escapeRegex(searchTerm)})`, 'gi'),
                        '<mark class="bg-warning text-dark">$1</mark>'
                    );
                    strongElement.innerHTML = highlightedText;
                } else {
                    const text = element.textContent;
                    const highlightedText = text.replace(
                        new RegExp(`(${escapeRegex(searchTerm)})`, 'gi'),
                        '<mark class="bg-warning text-dark">$1</mark>'
                    );
                    element.innerHTML = highlightedText;
                }
            }

            function showNoResultsMessage(tbody, searchTerm) {
                const noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-results-row';
                noResultsRow.innerHTML = `
                                <td colspan="5" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-search fa-4x text-muted mb-3"></i>
                                        <h5 class="text-muted mb-2">Tidak ada hasil ditemukan</h5>
                                        <p class="text-muted mb-0">
                                            Tidak ada user yang cocok dengan pencarian: 
                                            <strong>"${searchTerm}"</strong>
                                        </p>
                                        <button class="btn btn-outline-primary btn-sm mt-2" onclick="document.getElementById('searchInput').value = ''; document.getElementById('searchInput').dispatchEvent(new Event('input'));">
                                            <i class="fas fa-times me-1"></i> Hapus Pencarian
                                        </button>
                                    </div>
                                </td>
                            `;
                tbody.appendChild(noResultsRow);
            }

            function escapeRegex(string) {
                return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            }
        }
    </script>

    <style>
        /* User Icon Styling */
        .user-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(45deg, var(--primary-color), #5dade2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
        }

        /* Search Highlighting */
        mark {
            padding: 2px 4px;
            border-radius: 3px;
            font-weight: 500;
        }

        /* Search Info */
        #searchInfo {
            padding: 0.5rem;
            background-color: #f8f9fa;
            border-radius: 0.375rem;
            border-left: 4px solid var(--primary-color);
        }

        /* Button Group Styling */
        .btn-group .btn {
            border-radius: 6px;
            margin: 0 1px;
        }

        .btn-group .btn:first-child {
            margin-left: 0;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }

        /* Table Improvements */
        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--dark-color);
            background-color: #f8f9fa;
            border-bottom: 2px solid #e3e6f0;
        }

        .table td {
            vertical-align: middle;
        }

        /* Empty State */
        .empty-state {
            padding: 2rem;
        }

        .empty-state i {
            opacity: 0.6;
        }

        /* Alert Styling */
        .alert {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        /* Search Input */
        .input-group-text {
            background-color: #f8f9fa;
            border-color: #ced4da;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        /* Modal Enhancements */
        .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            border-bottom: 1px solid #e3e6f0;
            background-color: #f8f9fa;
            border-radius: 15px 15px 0 0;
        }

        .modal-footer {
            border-top: 1px solid #e3e6f0;
            background-color: #f8f9fa;
            border-radius: 0 0 15px 15px;
        }

        /* Hover Effects */
        .table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            transition: transform 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        /* Badge Styling */
        .badge {
            font-size: 0.75rem;
            padding: 0.4em 0.6em;
        }

        /* Mobile/tablet responsive table -> card layout */
        @media (max-width: 768px) {

            /* Hide thead, we'll show labels via data-label */
            #userTable thead {
                display: none;
            }

            #userTable tbody tr {
                display: block;
                border: 1px solid #e9ecef;
                border-radius: 10px;
                padding: 0.75rem;
                margin-bottom: 0.75rem;
                background: #fff;
                box-shadow: 0 6px 18px rgba(0, 0, 0, 0.03);
            }

            #userTable tbody tr.empty-state-row {
                border: none;
                box-shadow: none;
                padding: 0;
                background: transparent;
            }

            #userTable tbody tr td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.35rem 0.5rem;
                border: none;
            }

            #userTable tbody tr td[data-label] {
                /* ensure multiline doesn't break layout */
                gap: 0.5rem;
            }

            #userTable tbody tr td:before {
                content: attr(data-label);
                flex: 0 0 40%;
                font-weight: 600;
                color: #495057;
                margin-right: 0.75rem;
            }

            /* make the right-hand content wrap and align to the left */
            #userTable tbody tr td>*:last-child {
                text-align: right;
                flex: 1 1 auto;
            }

            /* User name cell special: keep icon and name stacked nicely */
            #userTable tbody tr td[data-label="Nama User"] .d-flex {
                gap: 0.5rem;
                align-items: center;
            }

            /* Role badges styling for mobile */
            #userTable tbody tr td[data-label="Role"] .badge {
                display: inline-block;
                margin: 2px;
            }

            /* Action buttons full width and stacked */
            .action-btn-group {
                display: flex;
                flex-direction: column;
                gap: 6px;
                width: 100%;
            }

            .action-btn-group .btn {
                width: 100%;
                justify-content: center;
            }

            /* Modal adjustments for mobile */
            .modal-xl {
                max-width: 95vw;
            }

            /* reduce margins for small screens */
            .card {
                margin-bottom: 1rem;
            }

            /* ensure search input uses full width */
            .input-group {
                width: 100%;
            }
        }
    </style>
@endsection