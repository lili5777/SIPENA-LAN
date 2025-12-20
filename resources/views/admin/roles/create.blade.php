@extends('admin.partials.layout')

@section('title', 'Tambah Role - Sistem Inventori Obat')

@section('content')
    <!-- Page Header -->
    <div class="page-header bg-gradient-primary rounded-3 mb-4" style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-user-plus fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">Tambah Role Baru</h1>
                        <p class="text-white-50 mb-0">Buat role baru dengan hak akses yang sesuai</p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('roles.index') }}" class="btn btn-light btn-hover-lift shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Form Section -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-plus-circle me-2" style="color: #285496;"></i> Form Tambah Role
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('roles.store') }}" method="POST" id="roleForm">
                        @csrf
                        
                        <!-- Nama Role -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium">Nama Role <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user-tag"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="Contoh: Admin, Manager, Staff" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Nama role harus unik dan mudah dipahami</div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-medium">Deskripsi</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-align-left"></i>
                                </span>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="3" 
                                          placeholder="Deskripsi singkat tentang role ini">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Permissions Section -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-medium">Permissions <span class="text-danger">*</span></label>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="selectAll">
                                        <i class="fas fa-check-double me-1"></i> Pilih Semua
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">
                                        <i class="fas fa-ban me-1"></i> Hapus Semua
                                    </button>
                                </div>
                            </div>

                            <!-- Permission Cards -->
                            <div class="permissions-container">
                                @php
                                    $groupedPermissions = [];
                                    foreach ($permissions as $permission) {
                                        $parts = explode('.', $permission->name);
                                        $module = $parts[0];
                                        $action = $parts[1] ?? '';
                                        $groupedPermissions[$module][$action] = $permission;
                                    }
                                @endphp

                                @foreach($groupedPermissions as $module => $actions)
                                    <div class="permission-module-card mb-3">
                                        <div class="module-header">
                                            <div class="form-check">
                                                <input class="form-check-input module-checkbox" 
                                                       type="checkbox" 
                                                       data-module="{{ $module }}"
                                                       id="module_{{ $module }}">
                                                <label class="form-check-label fw-bold" for="module_{{ $module }}">
                                                    <i class="fas fa-folder me-2" style="color: #285496;"></i>
                                                    {{ ucfirst($module) }}
                                                </label>
                                            </div>
                                            <span class="badge bg-primary rounded-pill">{{ count($actions) }}</span>
                                        </div>
                                        
                                        <div class="permissions-grid">
                                            @php
                                                $actionLabels = [
                                                    'create' => 'Tambah',
                                                    'read' => 'Lihat', 
                                                    'update' => 'Edit',
                                                    'delete' => 'Hapus',
                                                    'export' => 'Export',
                                                    'import' => 'Import',
                                                    'manage' => 'Kelola',
                                                    'view' => 'Lihat',
                                                    'edit' => 'Edit',
                                                    'store' => 'Simpan',
                                                    'destroy' => 'Hapus',
                                                    'index' => 'Daftar',
                                                    'show' => 'Detail'
                                                ];
                                            @endphp

                                            @foreach($actions as $action => $permission)
                                                <div class="permission-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox" 
                                                               type="checkbox" 
                                                               name="permissions[]" 
                                                               value="{{ $permission->id }}" 
                                                               id="permission_{{ $permission->id }}"
                                                               data-module="{{ $module }}"
                                                               {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                            <i class="fas {{ getPermissionIcon($action) }} me-2" style="color: #285496;"></i>
                                                            {{ $actionLabels[$action] ?? ucfirst($action) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @error('permissions')
                                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    {{ $message }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-lift">
                                <i class="fas fa-times me-2"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary btn-lift">
                                <i class="fas fa-save me-2"></i> Simpan Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Section -->
        <div class="col-lg-4">
            <!-- Info Card -->
            <div class="card border-0 shadow-lg mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-info-circle me-2" style="color: #285496;"></i> Panduan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0 shadow-sm">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-lightbulb fa-lg mt-1 me-3" style="color: #285496;"></i>
                            <div>
                                <h6 class="alert-heading mb-2">Tips Penting</h6>
                                <ul class="mb-0 ps-3">
                                    <li class="mb-1">Nama role harus <strong>unik</strong></li>
                                    <li class="mb-1">Gunakan nama yang <strong>deskriptif</strong></li>
                                    <li class="mb-1">Pilih permissions sesuai <strong>kebutuhan</strong></li>
                                    <li class="mb-1">Hindari memberikan <strong>akses berlebihan</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selected Permissions Summary -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-check-circle me-2" style="color: #285496;"></i> Permissions Terpilih
                    </h5>
                </div>
                <div class="card-body">
                    <div class="selected-permissions-summary">
                        <div id="selectedPermissions" class="text-center py-4">
                            <div class="empty-state mb-3">
                                <i class="fas fa-key fa-3x" style="color: #e9ecef;"></i>
                            </div>
                            <p class="text-muted mb-0">Belum ada permissions terpilih</p>
                        </div>
                        <div class="stats mt-3 border-top pt-3" style="display: none;">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="stat-number" id="totalPermissions">0</div>
                                    <div class="stat-label text-muted small">Total</div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-number" id="totalModules">0</div>
                                    <div class="stat-label text-muted small">Module</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@php
    function getPermissionIcon($action) {
        $icons = [
            'create' => 'fa-plus',
            'read' => 'fa-eye',
            'update' => 'fa-edit',
            'delete' => 'fa-trash',
            'export' => 'fa-download',
            'import' => 'fa-upload',
            'manage' => 'fa-cogs',
            'view' => 'fa-eye',
            'edit' => 'fa-edit',
            'store' => 'fa-save',
            'destroy' => 'fa-trash',
            'index' => 'fa-list',
            'show' => 'fa-eye'
        ];
        return $icons[$action] ?? 'fa-check';
    }
@endphp

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const moduleCheckboxes = document.querySelectorAll('.module-checkbox');
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
        const selectAllBtn = document.getElementById('selectAll');
        const deselectAllBtn = document.getElementById('deselectAll');
        const roleForm = document.getElementById('roleForm');
        
        // Module checkbox functionality
        moduleCheckboxes.forEach(moduleCheckbox => {
            moduleCheckbox.addEventListener('change', function() {
                const module = this.getAttribute('data-module');
                const modulePermissions = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
                
                modulePermissions.forEach(permission => {
                    permission.checked = this.checked;
                });
                
                updateSelectedPermissions();
            });
        });
        
        // Individual permission checkbox functionality
        permissionCheckboxes.forEach(permissionCheckbox => {
            permissionCheckbox.addEventListener('change', function() {
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
        selectAllBtn.addEventListener('click', function() {
            permissionCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            moduleCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
                checkbox.indeterminate = false;
            });
            updateSelectedPermissions();
            
            // Show success feedback
            showToast('Semua permissions berhasil dipilih', 'success');
        });
        
        // Deselect All functionality
        deselectAllBtn.addEventListener('click', function() {
            permissionCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            moduleCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
                checkbox.indeterminate = false;
            });
            updateSelectedPermissions();
            
            // Show info feedback
            showToast('Semua permissions berhasil dihapus', 'info');
        });
        
        // Update selected permissions summary
        function updateSelectedPermissions() {
            const selectedPermissions = document.querySelectorAll('.permission-checkbox:checked');
            const summaryElement = document.getElementById('selectedPermissions');
            const statsElement = document.querySelector('.stats');
            
            if (selectedPermissions.length === 0) {
                summaryElement.innerHTML = `
                    <div class="empty-state mb-3">
                        <i class="fas fa-key fa-3x" style="color: #e9ecef;"></i>
                    </div>
                    <p class="text-muted mb-0">Belum ada permissions terpilih</p>
                `;
                statsElement.style.display = 'none';
                return;
            }
            
            // Group by module and count actions
            const grouped = {};
            selectedPermissions.forEach(permission => {
                const module = permission.getAttribute('data-module');
                const label = permission.nextElementSibling.textContent.trim();
                const icon = permission.nextElementSibling.querySelector('i').className;
                
                if (!grouped[module]) {
                    grouped[module] = { actions: [], count: 0 };
                }
                grouped[module].actions.push({ label, icon });
                grouped[module].count++;
            });
            
            // Build HTML
            let html = '<div class="selected-permissions-list">';
            Object.keys(grouped).forEach(module => {
                html += `
                    <div class="permission-module-summary mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">
                                <i class="fas fa-folder me-2" style="color: #285496;"></i>
                                ${module.charAt(0).toUpperCase() + module.slice(1)}
                            </h6>
                            <span class="badge bg-primary rounded-pill">${grouped[module].count}</span>
                        </div>
                        <div class="permissions-chips">
                `;
                
                grouped[module].actions.forEach(action => {
                    html += `
                        <span class="permission-chip">
                            <i class="${action.icon} me-1"></i>${action.label}
                        </span>
                    `;
                });
                
                html += `
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            
            summaryElement.innerHTML = html;
            statsElement.style.display = 'block';
            
            // Update stats
            document.getElementById('totalPermissions').textContent = selectedPermissions.length;
            document.getElementById('totalModules').textContent = Object.keys(grouped).length;
        }
        
        // Form validation
        roleForm.addEventListener('submit', function(e) {
            const selectedPermissions = document.querySelectorAll('.permission-checkbox:checked');
            const nameInput = document.getElementById('name');
            
            // Clear previous errors
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            
            let hasError = false;
            
            // Validate name
            if (!nameInput.value.trim()) {
                nameInput.classList.add('is-invalid');
                nameInput.nextElementSibling.textContent = 'Nama role wajib diisi';
                hasError = true;
            }
            
            // Validate permissions
            if (selectedPermissions.length === 0) {
                showToast('Pilih setidaknya satu permission!', 'warning');
                e.preventDefault();
                return false;
            }
            
            if (hasError) {
                e.preventDefault();
                showToast('Harap perbaiki data yang salah', 'error');
            }
        });
        
        // Initialize on page load
        updateSelectedPermissions();
        moduleCheckboxes.forEach(moduleCheckbox => {
            const module = moduleCheckbox.getAttribute('data-module');
            const modulePermissions = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
            
            const allChecked = Array.from(modulePermissions).every(permission => permission.checked);
            const someChecked = Array.from(modulePermissions).some(permission => permission.checked);
            
            moduleCheckbox.checked = allChecked;
            moduleCheckbox.indeterminate = someChecked && !allChecked;
        });
        
        // Toast notification function
        function showToast(message, type = 'info') {
            // Remove existing toast
            const existingToast = document.querySelector('.custom-toast');
            if (existingToast) {
                existingToast.remove();
            }
            
            // Create toast
            const toast = document.createElement('div');
            toast.className = `custom-toast alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show`;
            toast.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            // Style toast
            Object.assign(toast.style, {
                position: 'fixed',
                top: '20px',
                right: '20px',
                zIndex: '9999',
                minWidth: '300px',
                boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
                borderRadius: '10px'
            });
            
            document.body.appendChild(toast);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 5000);
        }
        
        // Real-time validation for name input
        const nameInput = document.getElementById('name');
        nameInput.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    });
</script>

<style>
   
    
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
    
    /* Input Groups */
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #e9ecef;
        color: var(--primary-color);
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(40, 84, 150, 0.25);
    }
    
    /* Permission Module Cards */
    .permission-module-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 1.25rem;
        transition: all 0.3s ease;
    }
    
    .permission-module-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(40, 84, 150, 0.1);
    }
    
    .module-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--primary-light);
    }
    
    .module-header .form-check {
        display: flex;
        align-items: center;
    }
    
    .module-checkbox {
        width: 18px;
        height: 18px;
        margin-right: 10px;
        cursor: pointer;
    }
    
    .module-checkbox:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    /* Permissions Grid */
    .permissions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.75rem;
    }
    
    .permission-item {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 0.75rem;
        border-left: 3px solid var(--primary-color);
        transition: all 0.2s ease;
    }
    
    .permission-item:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }
    
    .permission-item .form-check {
        display: flex;
        align-items: center;
        margin: 0;
    }
    
    .permission-checkbox {
        width: 16px;
        height: 16px;
        margin-right: 8px;
        cursor: pointer;
    }
    
    .permission-checkbox:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .permission-item label {
        cursor: pointer;
        user-select: none;
        margin: 0;
        flex: 1;
    }
    
    /* Selected Permissions Summary */
    .selected-permissions-summary {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 5px;
    }
    
    .selected-permissions-summary::-webkit-scrollbar {
        width: 6px;
    }
    
    .selected-permissions-summary::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .selected-permissions-summary::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 10px;
    }
    
    .permission-module-summary {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .permissions-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .permission-chip {
        background: var(--primary-light);
        color: var(--primary-color);
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        border: 1px solid rgba(40, 84, 150, 0.2);
    }
    
    /* Stats */
    .stat-number {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--primary-color);
    }
    
    .stat-label {
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }
    
    /* Buttons */
    .btn-lift {
        transition: transform 0.2s ease;
    }
    
    .btn-lift:hover {
        transform: translateY(-2px);
    }
    
    .btn-outline-primary {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }
    
    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    /* Empty State */
    .empty-state {
        opacity: 0.6;
    }
    
    /* Form Validation */
    .is-invalid {
        border-color: #dc3545 !important;
    }
    
    .invalid-feedback {
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }
        
        .permissions-grid {
            grid-template-columns: 1fr;
        }
        
        .permission-module-card {
            padding: 1rem;
        }
        
        .permission-item {
            padding: 0.5rem;
        }
        
        .btn-group {
            width: 100%;
        }
        
        .btn-group .btn {
            flex: 1;
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
        
        .permissions-chips {
            justify-content: center;
        }
        
        .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style>
@endsection