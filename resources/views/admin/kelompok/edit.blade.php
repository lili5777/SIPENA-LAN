@extends('admin.partials.layout')

@section('title', 'Edit Kelompok - ' . $kelompok->nama_kelompok)

@section('content')
    <div class="page-header rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%); padding: 2rem;">
        <div class="d-flex align-items-center">
            <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                <i class="fas fa-edit fa-lg" style="color: #285496;"></i>
            </div>
            <div>
                <h1 class="text-white mb-1">Edit Kelompok</h1>
                <p class="text-white-50 mb-0">
                    <a href="{{ route('kelompok.index') }}" class="text-white-50 text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke daftar kelompok
                    </a>
                </p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-users me-2" style="color: #285496;"></i> Edit: {{ $kelompok->nama_kelompok }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger d-flex align-items-start mb-4">
                            <i class="fas fa-exclamation-circle me-2 mt-1"></i>
                            <ul class="mb-0 ps-2">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('kelompok.update', $kelompok) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="id_jenis_pelatihan" class="form-label fw-semibold">
                                    Jenis Pelatihan <span class="text-danger">*</span>
                                </label>
                                <select name="id_jenis_pelatihan" id="id_jenis_pelatihan"
                                    class="form-select @error('id_jenis_pelatihan') is-invalid @enderror">
                                    <option value="">-- Pilih Jenis Pelatihan --</option>
                                    @foreach($jenisPelatihan as $jp)
                                        <option value="{{ $jp->id }}"
                                            {{ old('id_jenis_pelatihan', $kelompok->id_jenis_pelatihan) == $jp->id ? 'selected' : '' }}>
                                            {{ $jp->nama_pelatihan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_jenis_pelatihan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="id_angkatan" class="form-label fw-semibold">
                                    Angkatan <span class="text-danger">*</span>
                                </label>
                                <select name="id_angkatan" id="id_angkatan"
                                    class="form-select @error('id_angkatan') is-invalid @enderror">
                                    <option value="">-- Pilih Angkatan --</option>
                                    @foreach($angkatanList as $ang)
                                        <option value="{{ $ang->id }}"
                                            {{ old('id_angkatan', $kelompok->id_angkatan) == $ang->id ? 'selected' : '' }}>
                                            {{ $ang->nama_angkatan }} ({{ $ang->tahun }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_angkatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-8">
                                <label for="nama_kelompok" class="form-label fw-semibold">
                                    Nama Kelompok <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nama_kelompok" id="nama_kelompok"
                                    class="form-control @error('nama_kelompok') is-invalid @enderror"
                                    value="{{ old('nama_kelompok', $kelompok->nama_kelompok) }}">
                                @error('nama_kelompok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label for="tahun" class="form-label fw-semibold">
                                    Tahun <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="tahun" id="tahun"
                                    class="form-control @error('tahun') is-invalid @enderror"
                                    min="2000" max="2099"
                                    value="{{ old('tahun', $kelompok->tahun) }}">
                                @error('tahun')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12"><hr class="my-1"><p class="text-muted small fw-semibold mb-0">Pembimbing & Penguji</p></div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mentor</label>
                                <select name="id_mentor" class="form-select">
                                    <option value="">-- Pilih Mentor --</option>
                                    @foreach($mentorList as $m)
                                        <option value="{{ $m->id }}" {{ old('id_mentor', $kelompok->id_mentor) == $m->id ? 'selected' : '' }}>
                                            {{ $m->nama_mentor }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Coach</label>
                                <select name="id_coach" class="form-select">
                                    <option value="">-- Pilih Coach --</option>
                                    @foreach($coachList as $c)
                                        <option value="{{ $c->id }}" {{ old('id_coach', $kelompok->id_coach) == $c->id ? 'selected' : '' }}>
                                            {{ $c->nama }}@if($c->jabatan) — {{ $c->jabatan }}@endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Penguji</label>
                                <select name="id_penguji" class="form-select">
                                    <option value="">-- Pilih Penguji --</option>
                                    @foreach($pengujiList as $p)
                                        <option value="{{ $p->id }}" {{ old('id_penguji', $kelompok->id_penguji) == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama }}@if($p->jabatan) — {{ $p->jabatan }}@endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Evaluator</label>
                                <select name="id_evaluator" class="form-select">
                                    <option value="">-- Pilih Evaluator --</option>
                                    @foreach($evaluatorList as $e)
                                        <option value="{{ $e->id }}" {{ old('id_evaluator', $kelompok->id_evaluator) == $e->id ? 'selected' : '' }}>
                                            {{ $e->nama }}@if($e->jabatan) — {{ $e->jabatan }}@endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Keterangan</label>
                                <textarea name="keterangan" rows="2" class="form-control"
                                    placeholder="Catatan tambahan (opsional)">{{ old('keterangan', $kelompok->keterangan) }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex gap-2 pt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('kelompok.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-times me-2"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection