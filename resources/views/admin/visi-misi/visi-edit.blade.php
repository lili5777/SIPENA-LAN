@extends('admin.partials.layout')

@section('title', 'Edit Visi - SIMPEL')

@section('content')
    <!-- Page Header -->
    <div class="page-header bg-gradient-primary rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-edit fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">Edit Visi</h1>
                        <p class="text-white-50 mb-0">Perbarui visi perusahaan</p>
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
                        <i class="fas fa-edit me-2" style="color: #285496;"></i> Form Edit Visi
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('visi-misi.visi.update', $visi) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="visi" class="form-label fw-semibold">Isi Visi <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="visi" name="visi" rows="4"
                                required>{{ old('visi', $visi->visi) }}</textarea>
                            @error('visi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="ctt" class="form-label fw-semibold">Catatan</label>
                            <input type="text" class="form-control" id="ctt" name="ctt"
                                value="{{ old('ctt', $visi->ctt) }}">
                            @error('ctt')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('visi-misi.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-times me-2"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4 btn-lift">
                                <i class="fas fa-save me-2"></i> Perbarui
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection