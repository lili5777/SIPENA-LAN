{{--
    View ini digunakan untuk CREATE dan EDIT permission.
    Variabel:
      - $permission  → ada saat edit (instance Permission), null/tidak ada saat create
      - $isEdit      → boolean, di-set oleh controller (true = edit, false = create)
--}}
@extends('admin.partials.layout')

@php
    $isEdit  = isset($permission);
    $title   = $isEdit ? 'Edit Permission' : 'Tambah Permission';
    $action  = $isEdit
                ? route('permissions.update', $permission)
                : route('permissions.store');
    $method  = $isEdit ? 'PUT' : 'POST';
@endphp

@section('title', $title . ' - Sistem Inventori Obat')

@section('content')
    <!-- Page Header -->
    <div class="page-header rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%); padding: 2rem;">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="bg-white rounded-circle p-3 me-3 shadow"
                        style="width:60px;height:60px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas {{ $isEdit ? 'fa-edit' : 'fa-plus-circle' }} fa-lg" style="color:#285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">{{ $title }}</h1>
                        <p class="text-white-50 mb-0">
                            {{ $isEdit ? 'Ubah nama atau deskripsi permission' : 'Tambahkan permission baru ke sistem' }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('permissions.index') }}" class="btn btn-light shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-key me-2" style="color:#285496;"></i>
                        Form {{ $title }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ $action }}" method="POST" id="permForm">
                        @csrf
                        @if($isEdit)
                            @method('PUT')
                        @endif

                        {{-- ── Nama Permission ── --}}
                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium">
                                Nama Permission <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="text"
                                       class="form-control text-monospace @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $permission->name ?? '') }}"
                                       placeholder="Contoh: obat.create atau laporan.export"
                                       required
                                       autocomplete="off">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">
                                Format: <code>module.action</code> — hanya huruf, angka, underscore, dan titik.
                                <br>Contoh: <code>obat.create</code>, <code>laporan.export</code>, <code>user.delete</code>
                            </div>
                        </div>

                        {{-- ── Preview nama ── --}}
                        <div class="mb-4" id="namePreviewWrapper" style="display:none;">
                            <label class="form-label fw-medium text-muted">Preview</label>
                            <div class="d-flex gap-2 flex-wrap" id="namePreview"></div>
                        </div>

                        {{-- ── Deskripsi ── --}}
                        <div class="mb-4">
                            <label for="description" class="form-label fw-medium">Deskripsi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          name="description"
                                          rows="3"
                                          maxlength="255"
                                          placeholder="Penjelasan singkat tentang permission ini">{{ old('description', $permission->description ?? '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-end">
                                <small class="text-muted" id="charCount">0 / 255</small>
                            </div>
                        </div>

                        {{-- ── Quick fill (hanya untuk create) ── --}}
                        @if(!$isEdit)
                        <div class="mb-4">
                            <label class="form-label fw-medium">Isi Cepat (Quick Fill)</label>
                            <p class="text-muted small mb-2">
                                Ketik nama module lalu klik action yang ingin dibuat:
                            </p>
                            <div class="input-group mb-2">
                                <span class="input-group-text"><i class="fas fa-folder"></i></span>
                                <input type="text" class="form-control" id="quickModule"
                                       placeholder="Nama module, contoh: obat">
                            </div>
                            <div class="d-flex flex-wrap gap-2" id="quickActions">
                                @foreach(['create','read','update','delete','export','import','index','show'] as $act)
                                    <button type="button" class="btn btn-sm btn-outline-primary quick-action-btn"
                                            data-action="{{ $act }}">
                                        <i class="fas {{ [
                                            'create'=>'fa-plus','read'=>'fa-eye','update'=>'fa-edit',
                                            'delete'=>'fa-trash','export'=>'fa-download','import'=>'fa-upload',
                                            'index'=>'fa-list','show'=>'fa-eye'
                                        ][$act] ?? 'fa-check' }} me-1"></i>
                                        {{ ucfirst($act) }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- ── Tombol ── --}}
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>
                                {{ $isEdit ? 'Simpan Perubahan' : 'Buat Permission' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Info card (edit: tampilkan role yang menggunakan) --}}
            @if($isEdit && $permission->roles->count() > 0)
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-user-tag me-2" style="color:#285496;"></i>
                        Digunakan oleh {{ $permission->roles->count() }} Role
                    </h6>
                </div>
                <div class="card-body d-flex flex-wrap gap-2">
                    @foreach($permission->roles as $role)
                        <span class="badge rounded-pill py-2 px-3"
                              style="background:rgba(40,84,150,.1);color:#285496;font-size:.85rem;">
                            <i class="fas fa-user-tag me-1"></i>{{ $role->name }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nameInput    = document.getElementById('name');
    const descInput    = document.getElementById('description');
    const charCount    = document.getElementById('charCount');
    const preview      = document.getElementById('namePreview');
    const previewWrap  = document.getElementById('namePreviewWrapper');

    // ── Character count ────────────────────────────────────────
    function updateCharCount() {
        const len = descInput.value.length;
        charCount.textContent = `${len} / 255`;
        charCount.classList.toggle('text-danger', len > 230);
    }
    descInput.addEventListener('input', updateCharCount);
    updateCharCount();

    // ── Name preview ───────────────────────────────────────────
    const actionLabels = {
        create:'Tambah', read:'Lihat', update:'Edit', delete:'Hapus',
        export:'Export', import:'Import', manage:'Kelola',
        view:'Lihat', edit:'Edit', store:'Simpan',
        destroy:'Hapus', index:'Daftar', show:'Detail'
    };

    function updatePreview() {
        const val  = nameInput.value.trim();
        if (!val) { previewWrap.style.display = 'none'; return; }

        const parts  = val.split('.');
        const module = parts[0] ? parts[0].charAt(0).toUpperCase() + parts[0].slice(1) : '';
        const action = parts[1] || '';
        const label  = actionLabels[action] || (action ? action.charAt(0).toUpperCase() + action.slice(1) : '');

        let html = '';
        if (module) {
            html += `<span class="badge rounded-pill py-2 px-3"
                          style="background:rgba(40,84,150,.1);color:#285496;">
                       <i class="fas fa-folder me-1"></i>${module}
                     </span>`;
        }
        if (label) {
            html += `<span class="badge rounded-pill py-2 px-3 bg-success text-white">
                       <i class="fas fa-check me-1"></i>${label}
                     </span>`;
        }
        html += `<span class="badge rounded-pill py-2 px-3 bg-light text-dark border">
                   <code>${val}</code>
                 </span>`;

        preview.innerHTML = html;
        previewWrap.style.display = 'block';
    }

    nameInput.addEventListener('input', function () {
        // Sanitize: hanya izinkan karakter valid
        this.value = this.value.replace(/[^a-zA-Z0-9_.]/g, '');
        updatePreview();
    });
    updatePreview(); // init saat edit

    // ── Quick Fill (create only) ───────────────────────────────
    const quickModule = document.getElementById('quickModule');
    if (quickModule) {
        document.querySelectorAll('.quick-action-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const module = quickModule.value.trim().replace(/[^a-zA-Z0-9_]/g, '');
                if (!module) {
                    quickModule.classList.add('is-invalid');
                    setTimeout(() => quickModule.classList.remove('is-invalid'), 1500);
                    return;
                }
                nameInput.value = `${module}.${this.dataset.action}`;
                nameInput.dispatchEvent(new Event('input'));
                nameInput.focus();
            });
        });
    }

    // ── Form validation ────────────────────────────────────────
    document.getElementById('permForm').addEventListener('submit', function (e) {
        if (!nameInput.value.trim()) {
            nameInput.classList.add('is-invalid');
            e.preventDefault();
        }
    });

    nameInput.addEventListener('input', function () {
        this.classList.remove('is-invalid');
    });
});
</script>

<style>
    .text-monospace { font-family: 'Courier New', monospace !important; }
    .input-group-text { background:#f8f9fa; border-color:#e9ecef; color:#285496; }
    .form-control:focus { border-color:#285496; box-shadow: 0 0 0 .2rem rgba(40,84,150,.2); }
    .page-header { box-shadow: 0 4px 20px rgba(40,84,150,.15); }
    .quick-action-btn { border-color:#285496; color:#285496; transition:all .2s; }
    .quick-action-btn:hover { background:#285496; color:#fff; transform:translateY(-2px); }
    .btn-primary { background:#285496; border-color:#285496; }
    .btn-primary:hover { background:#1e4274; border-color:#1e4274; }
</style>
@endsection