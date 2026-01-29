@extends('admin.partials.layout')

@section('title', 'Tambah Misi - Sistem Inventori Obat')

@section('content')
    <!-- Page Header -->
    <div class="page-header bg-gradient-primary rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-plus fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">Tambah Misi</h1>
                        <p class="text-white-50 mb-0">Tambahkan misi perusahaan</p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('visi-misi.index') }}" class="btn btn-light btn-hover-lift shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-bullseye me-2" style="color: #285496;"></i> Form Tambah Misi
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('visi-misi.misi.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="isi" class="form-label fw-semibold">Isi Misi <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="isi" name="isi" rows="3" 
                                      placeholder="Masukkan isi misi..." required>{{ old('isi') }}</textarea>
                            @error('isi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ctt" class="form-label fw-semibold">Judul Singkat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ctt" name="ctt" 
                                       value="{{ old('ctt') }}" placeholder="Contoh: Integritas" maxlength="50" required>
                                <small class="text-muted">Maksimal 50 karakter</small>
                                @error('ctt')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="icon" class="form-label fw-semibold">Icon <span class="text-danger">*</span></label>
                                <select class="form-select" id="icon" name="icon" required>
                                    <option value="" disabled selected>Pilih icon...</option>
                                    @foreach($icons as $key => $label)
                                        <option value="{{ $key }}" {{ old('icon') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="mt-2">
                                    <small class="text-muted">Preview:</small>
                                    <div id="iconPreview" class="mt-1">
                                        <i class="fas fa-question-circle fa-lg text-muted"></i>
                                    </div>
                                </div>
                                @error('icon')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('visi-misi.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-times me-2"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4 btn-lift">
                                <i class="fas fa-save me-2"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const iconSelect = document.getElementById('icon');
            const iconPreview = document.getElementById('iconPreview');
            
            const iconMap = {
                'users': 'fas fa-users',
                'edit': 'fas fa-edit',
                'zap': 'fas fa-bolt',
                'book': 'fas fa-book'
            };
            
            function updateIconPreview() {
                const selectedIcon = iconSelect.value;
                if (selectedIcon && iconMap[selectedIcon]) {
                    iconPreview.innerHTML = `<i class="${iconMap[selectedIcon]} fa-lg text-primary"></i>`;
                } else {
                    iconPreview.innerHTML = '<i class="fas fa-question-circle fa-lg text-muted"></i>';
                }
            }
            
            iconSelect.addEventListener('change', updateIconPreview);
            updateIconPreview(); // Initial preview
        });
    </script>
@endsection