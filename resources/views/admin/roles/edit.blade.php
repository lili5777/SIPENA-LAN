@extends('admin.partials.layout')

@section('title', 'Edit Role - Sistem Inventori Obat')

@section('content')
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h1>Edit Role: {{ $role->name }}</h1>
            </div>
            <div class="col-auto">
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Form Edit Role</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('roles.update', $role) }}" method="POST" id="roleForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Role <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $role->name) }}" 
                                   placeholder="Masukkan nama role" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Masukkan deskripsi role">{{ old('description', $role->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Permissions Section -->
                        <div class="mb-4">
                            <label class="form-label">Permissions <span class="text-danger">*</span></label>
                            
                            <!-- Quick Actions -->
                            <div class="d-flex gap-2 mb-3">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="selectAll">
                                    <i class="fas fa-check-square me-1"></i> Pilih Semua
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">
                                    <i class="fas fa-times-circle me-1"></i> Batal Pilih Semua
                                </button>
                            </div>

                            <!-- Permissions Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="25%">Module</th>
                                            <th width="75%">Permissions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // Group permissions by module
                                            $groupedPermissions = [];
                                            foreach ($permissions as $permission) {
                                                $parts = explode('.', $permission->name);
                                                $module = $parts[0];
                                                $action = $parts[1] ?? '';
                                                $groupedPermissions[$module][$action] = $permission;
                                            }
                                        @endphp

                                        @foreach($groupedPermissions as $module => $actions)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input module-checkbox" 
                                                               type="checkbox" 
                                                               data-module="{{ $module }}"
                                                               id="module_{{ $module }}">
                                                        <label class="form-check-label fw-bold" for="module_{{ $module }}">
                                                            {{ ucfirst($module) }}
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="row">
                                                        @php
                                                            $actionLabels = [
                                                                'create' => 'Tambah',
                                                                'read' => 'Lihat',
                                                                'update' => 'Edit',
                                                                'delete' => 'Hapus',
                                                                'export' => 'Export',
                                                                'import' => 'Import'
                                                            ];
                                                        @endphp

                                                        @foreach($actions as $action => $permission)
                                                            <div class="col-md-3 mb-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input permission-checkbox" 
                                                                           type="checkbox" 
                                                                           name="permissions[]" 
                                                                           value="{{ $permission->id }}" 
                                                                           id="permission_{{ $permission->id }}"
                                                                           data-module="{{ $module }}"
                                                                           {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                                        {{ $actionLabels[$action] ?? ucfirst($action) }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            @error('permissions')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('roles.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Update Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Role</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Dibuat Pada:</strong><br>
                        {{ $role->created_at->format('d M Y H:i') }}
                    </div>
                    <div class="mb-3">
                        <strong>Diupdate Pada:</strong><br>
                        {{ $role->updated_at->format('d M Y H:i') }}
                    </div>
                </div>
            </div>

            <!-- Selected Permissions Summary -->
            <div class="card bg-light mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">Permissions Terpilih</h6>
                </div>
                <div class="card-body">
                    <div id="selectedPermissions" class="small">
                        <span class="text-muted">Belum ada permissions terpilih</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Module checkbox functionality
            const moduleCheckboxes = document.querySelectorAll('.module-checkbox');
            const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');

            // When module checkbox is clicked, check/uncheck all permissions in that module
            moduleCheckboxes.forEach(moduleCheckbox => {
                moduleCheckbox.addEventListener('change', function () {
                    const module = this.getAttribute('data-module');
                    const modulePermissions = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);

                    modulePermissions.forEach(permission => {
                        permission.checked = this.checked;
                    });

                    updateSelectedPermissions();
                });
            });

            // When individual permission is clicked, update module checkbox state
            permissionCheckboxes.forEach(permissionCheckbox => {
                permissionCheckbox.addEventListener('change', function () {
                    const module = this.getAttribute('data-module');
                    const moduleCheckbox = document.querySelector(`.module-checkbox[data-module="${module}"]`);
                    const modulePermissions = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);

                    // Check if all permissions in module are selected
                    const allChecked = Array.from(modulePermissions).every(permission => permission.checked);
                    // Check if at least one permission is selected
                    const someChecked = Array.from(modulePermissions).some(permission => permission.checked);

                    moduleCheckbox.checked = allChecked;
                    moduleCheckbox.indeterminate = someChecked && !allChecked;

                    updateSelectedPermissions();
                });
            });

            // Select All functionality
            document.getElementById('selectAll').addEventListener('click', function () {
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                moduleCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                    checkbox.indeterminate = false;
                });
                updateSelectedPermissions();
            });

            // Deselect All functionality
            document.getElementById('deselectAll').addEventListener('click', function () {
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                moduleCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    checkbox.indeterminate = false;
                });
                updateSelectedPermissions();
            });

            // Update selected permissions summary
            function updateSelectedPermissions() {
                const selectedPermissions = document.querySelectorAll('.permission-checkbox:checked');
                const summaryElement = document.getElementById('selectedPermissions');

                if (selectedPermissions.length === 0) {
                    summaryElement.innerHTML = '<span class="text-muted">Belum ada permissions terpilih</span>';
                    return;
                }

                // Group by module
                const grouped = {};
                selectedPermissions.forEach(permission => {
                    const module = permission.getAttribute('data-module');
                    const label = permission.nextElementSibling.textContent.trim();

                    if (!grouped[module]) {
                        grouped[module] = [];
                    }
                    grouped[module].push(label);
                });

                let html = '';
                Object.keys(grouped).forEach(module => {
                    html += `<div class="mb-2">
                        <strong>${module.charAt(0).toUpperCase() + module.slice(1)}:</strong><br>
                        <small>${grouped[module].join(', ')}</small>
                    </div>`;
                });

                html += `<div class="mt-2 text-primary">
                    <strong>Total: ${selectedPermissions.length} permissions</strong>
                </div>`;

                summaryElement.innerHTML = html;
            }

            // Form validation
            document.getElementById('roleForm').addEventListener('submit', function (e) {
                const selectedPermissions = document.querySelectorAll('.permission-checkbox:checked');

                if (selectedPermissions.length === 0) {
                    e.preventDefault();
                    alert('Pilih setidaknya satu permission!');
                    return false;
                }
            });

            // Initialize selected permissions on page load
            updateSelectedPermissions();

            // Initialize module checkboxes state on page load
            moduleCheckboxes.forEach(moduleCheckbox => {
                const module = moduleCheckbox.getAttribute('data-module');
                const modulePermissions = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);

                const allChecked = Array.from(modulePermissions).every(permission => permission.checked);
                const someChecked = Array.from(modulePermissions).some(permission => permission.checked);

                moduleCheckbox.checked = allChecked;
                moduleCheckbox.indeterminate = someChecked && !allChecked;
            });
        });
    </script>

    <style>
        .module-checkbox {
            transform: scale(1.2);
        }

        .permission-checkbox {
            transform: scale(1.1);
        }

        .form-check-label {
            margin-left: 5px;
            cursor: pointer;
        }

        .table th {
            background-color: #f8f9fa !important;
            font-weight: 600;
        }

        #selectedPermissions {
            max-height: 200px;
            overflow-y: auto;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
@endsection