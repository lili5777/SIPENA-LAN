{{-- resources/views/admin/gelombang/edit.blade.php --}}
@extends('admin.partials.layout')

@section('title', 'Edit Gelombang')

@section('content')
    <div class="page-header rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%); padding: 2rem;">
        <div class="d-flex align-items-center">
            <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                <i class="fas fa-edit fa-lg" style="color: #285496;"></i>
            </div>
            <div>
                <h1 class="text-white mb-1">Edit Gelombang</h1>
                <p class="text-white-50 mb-0">
                    <a href="{{ route('gelombang.index') }}" class="text-white-50 text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke daftar gelombang
                    </a>
                </p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-layer-group me-2" style="color: #285496;"></i>
                        Edit: {{ $gelombang->nama_gelombang }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger d-flex align-items-start mb-4">
                            <i class="fas fa-exclamation-circle me-2 mt-1"></i>
                            <ul class="mb-0 ps-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-center mb-4">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center mb-4">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('gelombang.update', $gelombang) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="id_jenis_pelatihan" class="form-label fw-semibold">
                                Jenis Pelatihan <span class="text-danger">*</span>
                            </label>
                            <select name="id_jenis_pelatihan" id="id_jenis_pelatihan"
                                class="form-select @error('id_jenis_pelatihan') is-invalid @enderror">
                                <option value="">-- Pilih Jenis Pelatihan --</option>
                                @foreach($jenisPelatihan as $jp)
                                    <option value="{{ $jp->id }}"
                                        {{ old('id_jenis_pelatihan', $gelombang->id_jenis_pelatihan) == $jp->id ? 'selected' : '' }}>
                                        {{ $jp->nama_pelatihan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_jenis_pelatihan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($gelombang->angkatan()->count() > 0)
                                <small class="text-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Perhatian: mengubah jenis pelatihan dapat mempengaruhi angkatan yang sudah terhubung.
                                </small>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label for="nama_gelombang" class="form-label fw-semibold">
                                Nama Gelombang <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama_gelombang" id="nama_gelombang"
                                class="form-control @error('nama_gelombang') is-invalid @enderror"
                                placeholder="Contoh: Gelombang 1"
                                value="{{ old('nama_gelombang', $gelombang->nama_gelombang) }}">
                            @error('nama_gelombang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tahun" class="form-label fw-semibold">
                                Tahun <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="tahun" id="tahun"
                                class="form-control @error('tahun') is-invalid @enderror"
                                placeholder="Contoh: 2026"
                                min="2000" max="2099"
                                value="{{ old('tahun', $gelombang->tahun) }}">
                            @error('tahun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="kategori" class="form-label fw-semibold">
                                Kategori <span class="text-danger">*</span>
                            </label>
                            <select name="kategori" id="kategori"
                                class="form-select @error('kategori') is-invalid @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoriList as $kat)
                                    <option value="{{ $kat }}"
                                        {{ old('kategori', $gelombang->kategori) == $kat ? 'selected' : '' }}>
                                        {{ $kat }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Info angkatan terhubung -->
                        <div class="alert alert-info d-flex align-items-center mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <span>
                                Gelombang ini memiliki
                                <strong>{{ $gelombang->angkatan()->count() }} angkatan</strong> terhubung.
                                @if($gelombang->angkatan()->count() > 0)
                                    <a href="{{ route('gelombang.kelola-angkatan', $gelombang) }}" class="ms-1">
                                        Kelola angkatan →
                                    </a>
                                @endif
                            </span>
                        </div>

                        <div class="d-flex gap-2 pt-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('gelombang.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-times me-2"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection