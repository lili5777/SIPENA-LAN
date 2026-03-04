@extends('admin.partials.layout')

@section('title', $isEdit ? 'Edit Evaluator' : 'Tambah Evaluator - Sistem Pelatihan')

@section('content')
    <!-- Page Header -->
    <div class="page-header bg-gradient-primary rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%);">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-user-graduate fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">{{ $isEdit ? 'Edit Evaluator' : 'Tambah Evaluator Baru' }}</h1>
                        <p class="text-white-50 mb-0">{{ $isEdit ? 'Perbarui data evaluator' : 'Tambah data evaluator baru' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('evaluator.index') }}" class="btn btn-light btn-hover-lift shadow-sm d-flex align-items-center">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="alert-container mb-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
                <div class="alert-icon flex-shrink-0"><i class="fas fa-check-circle fa-lg"></i></div>
                <div class="flex-grow-1 ms-3"><strong>Sukses!</strong> {{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <div class="alert-icon flex-shrink-0"><i class="fas fa-exclamation-circle fa-lg"></i></div>
                    <div class="flex-grow-1 ms-3">
                        <strong>Error!</strong> Terdapat kesalahan dalam input data:
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Form -->
    <div class="card border-0 shadow-lg">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="card-title mb-0 fw-semibold">
                <i class="fas fa-edit me-2" style="color: #285496;"></i>
                Form {{ $isEdit ? 'Edit' : 'Tambah' }} Coach
            </h5>
        </div>

        <form method="POST"
            action="{{ $isEdit ? route('evaluator.update', $evaluator->id) : route('evaluator.store') }}"
            id="evaluatorForm">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="card-body">

                {{-- DATA PRIBADI --}}
                <div class="section-divider mb-4">
                    <span><i class="fas fa-user me-2"></i>Data Pribadi</span>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Nama -->
                        <div class="mb-4">
                            <label for="nama" class="form-label fw-semibold">
                                <i class="fas fa-user me-1 text-primary"></i> Nama Evaluator <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                id="nama" name="nama"
                                value="{{ old('nama', $isEdit ? $evaluator->nama : '') }}"
                                placeholder="Masukkan nama lengkap evaluator" required>
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Nama lengkap sesuai dokumen resmi</small>
                        </div>

                        <!-- NIP -->
                        <div class="mb-4">
                            <label for="nip" class="form-label fw-semibold">
                                <i class="fas fa-id-card me-1 text-primary"></i> NIP Evaluator
                            </label>
                            <input type="text" class="form-control @error('nip') is-invalid @enderror"
                                id="nip" name="nip"
                                value="{{ old('nip', $isEdit ? $evaluator->nip : '') }}"
                                placeholder="Masukkan NIP coach (jika ada)">
                            @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Jabatan -->
                        <div class="mb-4">
                            <label for="jabatan" class="form-label fw-semibold">
                                <i class="fas fa-briefcase me-1 text-primary"></i> Jabatan Evaluator
                            </label>
                            <input type="text" class="form-control @error('jabatan') is-invalid @enderror"
                                id="jabatan" name="jabatan"
                                value="{{ old('jabatan', $isEdit ? $evaluator->jabatan : '') }}"
                                placeholder="Contoh: Senior Coach, Konsultan, dll">
                            @error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Jabatan atau posisi dalam organisasi</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Golongan -->
                        <div class="mb-4">
                            <label for="golongan" class="form-label fw-semibold">
                                <i class="fas fa-layer-group me-1 text-primary"></i> Golongan Ruang
                            </label>
                            <select class="form-select @error('golongan') is-invalid @enderror" id="golongan" name="golongan">
                                <option value="">-- Pilih Golongan Ruang --</option>
                                @foreach(['II/a','II/b','II/c','II/d','III/a','III/b','III/c','III/d','IV/a','IV/b','IV/c','IV/d'] as $gol)
                                    <option value="{{ $gol }}"
                                        {{ old('golongan', $isEdit ? $evaluator->golongan : '') == $gol ? 'selected' : '' }}>
                                        {{ $gol }}
                                    </option>
                                @endforeach
                            </select>
                            @error('golongan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Pangkat -->
                        <div class="mb-4">
                            <label for="pangkat" class="form-label fw-semibold">
                                <i class="fas fa-medal me-1 text-primary"></i> Pangkat
                            </label>
                            <input type="text" class="form-control @error('pangkat') is-invalid @enderror"
                                id="pangkat" name="pangkat"
                                value="{{ old('pangkat', $isEdit ? $evaluator->pangkat : '') }}"
                                placeholder="Terisi otomatis berdasarkan golongan" readonly>
                            @error('pangkat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Terisi otomatis saat golongan dipilih</small>
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status_aktif" class="form-label fw-semibold">
                                <i class="fas fa-toggle-on me-1 text-primary"></i> Status Evaluator <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('status_aktif') is-invalid @enderror"
                                id="status_aktif" name="status_aktif" required>
                                <option value="1" {{ old('status_aktif', $isEdit ? $evaluator->status_aktif : '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('status_aktif', $isEdit ? $evaluator->status_aktif : '') == '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status_aktif')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Status menentukan apakah coach masih aktif</small>
                        </div>
                    </div>
                </div>

                {{-- KONTAK --}}
                <div class="section-divider mb-4 mt-2">
                    <span><i class="fas fa-address-book me-2"></i>Informasi Kontak</span>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                <i class="fas fa-envelope me-1 text-primary"></i> Email Evaluator
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email"
                                value="{{ old('email', $isEdit ? $evaluator->email : '') }}"
                                placeholder="contoh@email.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Email aktif untuk komunikasi</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label for="nomor_hp" class="form-label fw-semibold">
                                <i class="fas fa-phone me-1 text-primary"></i> Nomor HP Evaluator
                            </label>
                            <input type="text" class="form-control @error('nomor_hp') is-invalid @enderror"
                                id="nomor_hp" name="nomor_hp"
                                value="{{ old('nomor_hp', $isEdit ? $evaluator->nomor_hp : '') }}"
                                placeholder="081234567890">
                            @error('nomor_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Nomor WhatsApp atau telepon</small>
                        </div>
                    </div>
                </div>

                {{-- KEUANGAN --}}
                <div class="section-divider mb-4 mt-2">
                    <span><i class="fas fa-money-bill-wave me-2"></i>Informasi Keuangan</span>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label for="nomor_rekening" class="form-label fw-semibold">
                                <i class="fas fa-credit-card me-1 text-primary"></i> Nomor Rekening
                            </label>
                            <input type="text" class="form-control @error('nomor_rekening') is-invalid @enderror"
                                id="nomor_rekening" name="nomor_rekening"
                                value="{{ old('nomor_rekening', $isEdit ? $evaluator->nomor_rekening : '') }}"
                                placeholder="Contoh: Bank Mandiri - 1234567890">
                            @error('nomor_rekening')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Nomor rekening untuk pembayaran honorarium</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label for="npwp" class="form-label fw-semibold">
                                <i class="fas fa-file-invoice me-1 text-primary"></i> NPWP Evaluator
                            </label>
                            <input type="text" class="form-control @error('npwp') is-invalid @enderror"
                                id="npwp" name="npwp"
                                value="{{ old('npwp', $isEdit ? $evaluator->npwp : '') }}"
                                placeholder="Contoh: 12.345.678.9-012.345">
                            @error('npwp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>NPWP untuk keperluan perpajakan</small>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning d-none" id="validationSummary">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div><strong class="d-block">Periksa kembali data berikut:</strong>
                            <ul class="mb-0 mt-1" id="validationErrors"></ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white py-3 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('evaluator.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-times me-2"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-4 btn-lift">
                        <i class="fas fa-save me-2"></i> {{ $isEdit ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Info Card -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h6 class="fw-semibold mb-3"><i class="fas fa-info-circle me-2 text-primary"></i> Informasi Penting</h6>
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i><strong>Aktif:</strong> Evaluator dapat dipilih untuk kelompok baru</li>
                        <li class="mb-2"><i class="fas fa-exclamation-circle text-warning me-2"></i>Data email harus unik dan tidak boleh duplikat</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-times-circle text-secondary me-2"></i><strong>Nonaktif:</strong> Evaluator tidak muncul dalam pilihan kelompok baru</li>
                        <li class="mb-2"><i class="fas fa-info-circle text-primary me-2"></i>Informasi keuangan untuk keperluan pembayaran honorarium</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('evaluatorForm');

    // Auto-hide alerts
    document.querySelectorAll('.alert').forEach(a => {
        setTimeout(() => { if (a.classList.contains('show') && !a.classList.contains('alert-warning')) bootstrap.Alert.getOrCreateInstance(a).close(); }, 5000);
    });

    // Format NPWP
    document.getElementById('npwp').addEventListener('input', function (e) {
        let v = e.target.value.replace(/\D/g, '');
        let f = '';
        if (v.length > 2)  { f += v.substr(0,2) + '.'; v = v.substr(2); }
        if (v.length > 3)  { f += v.substr(0,3) + '.'; v = v.substr(3); }
        if (v.length > 3)  { f += v.substr(0,3) + '.'; v = v.substr(3); }
        if (v.length > 1)  { f += v.substr(0,1) + '-'; v = v.substr(1); }
        if (v.length > 3)  { f += v.substr(0,3) + '.'; v = v.substr(3); }
        f += v;
        e.target.value = f;
    });

    // Format HP
    document.getElementById('nomor_hp').addEventListener('input', function (e) {
        let v = e.target.value.replace(/\D/g, '');
        if (v.startsWith('0')) v = '62' + v.substr(1);
        e.target.value = v.substr(0, 15);
    });

    // Client-side validation
    form.addEventListener('submit', function (e) {
        let errors = [];
        document.getElementById('validationErrors').innerHTML = '';
        document.getElementById('validationSummary').classList.add('d-none');

        const email = document.getElementById('email').value;
        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) errors.push('Format email tidak valid');

        const phone = document.getElementById('nomor_hp').value;
        if (phone && phone.replace(/\D/g,'').length < 10) errors.push('Nomor HP minimal 10 digit');

        if (errors.length > 0) {
            e.preventDefault();
            const summary = document.getElementById('validationSummary');
            const errList = document.getElementById('validationErrors');
            summary.classList.remove('d-none');
            errors.forEach(err => { const li = document.createElement('li'); li.textContent = err; errList.appendChild(li); });
            summary.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    // Auto-fill Pangkat dari Golongan
    const golonganPangkat = {
        'II/a' :'Pengatur Muda',       'II/b' :'Pengatur Muda Tingkat I',
        'II/c' :'Pengatur',             'II/d' :'Pengatur Tingkat I',
        'III/a':'Penata Muda',          'III/b':'Penata Muda Tingkat I',
        'III/c':'Penata',               'III/d':'Penata Tingkat I',
        'IV/a' :'Pembina',              'IV/b' :'Pembina Tingkat I',
        'IV/c' :'Pembina Utama Muda',   'IV/d' :'Pembina Utama Madya',
    };
    const golSel = document.getElementById('golongan');
    const pangkatInput = document.getElementById('pangkat');
    if (golSel.value) pangkatInput.value = golonganPangkat[golSel.value] || '';
    golSel.addEventListener('change', function () {
        pangkatInput.value = golonganPangkat[this.value] || '';
    });
});
</script>

<style>
    .section-divider { display:flex;align-items:center;gap:12px;color:#285496;font-weight:600;font-size:.95rem; }
    .section-divider::before,.section-divider::after { content:'';flex:1;height:1px;background:linear-gradient(to right,#285496,#dee2e6); }
    .section-divider::after { background:linear-gradient(to left,#285496,#dee2e6); }
    .form-label { font-weight:600;color:#285496;margin-bottom:.5rem; }
    .form-control,.form-select { border-radius:8px;padding:.75rem 1rem;border:1px solid #dee2e6;transition:all .3s; }
    .form-control:focus,.form-select:focus { border-color:#285496;box-shadow:0 0 0 .25rem rgba(40,84,150,.25); }
    .form-control[readonly] { background-color:#f8f9fa;cursor:not-allowed; }
    .card { border-radius:12px;overflow:hidden; }
    .btn-primary { background:linear-gradient(135deg,#285496,#3a6bc7);border:none; }
    .btn-primary:hover { background:linear-gradient(135deg,#1e3d6f,#2d5499);transform:translateY(-2px);box-shadow:0 8px 25px rgba(40,84,150,.4); }
    .btn-outline-secondary { border:2px solid #6c757d;color:#6c757d; }
    .btn-outline-secondary:hover { background-color:#6c757d;border-color:#6c757d;color:white; }
    .btn-lift:hover { transform:translateY(-2px); }
</style>
@endsection