{{-- resources/views/admin/gelombang/kelola-angkatan.blade.php --}}
@extends('admin.partials.layout')

@section('title', 'Kelola Angkatan - ' . $gelombang->nama_gelombang)

@section('content')
    <div class="page-header rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%); padding: 2rem;">
        <div class="d-flex align-items-center">
            <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                <i class="fas fa-users-cog fa-lg" style="color: #285496;"></i>
            </div>
            <div>
                <h1 class="text-white mb-1">Kelola Angkatan</h1>
                <p class="text-white-50 mb-0">
                    <span class="me-2">{{ $gelombang->nama_gelombang }}</span>
                    <span class="badge bg-white" style="color: #285496;">{{ $gelombang->jenisPelatihan->nama_pelatihan ?? '-' }}</span>
                    <span class="badge bg-white ms-1" style="color: #285496;">{{ $gelombang->tahun }}</span>
                </p>
            </div>
            <div class="ms-auto">
                <a href="{{ route('gelombang.index') }}" class="btn btn-light shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Section -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-check-circle fa-lg me-3"></i>
            <div class="flex-grow-1"><strong>Sukses!</strong> {{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-exclamation-circle fa-lg me-3"></i>
            <div class="flex-grow-1"><strong>Error!</strong> {{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Kolom Kiri: Angkatan Tersedia (belum punya gelombang) -->
        <div class="col-md-5">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-inbox me-2 text-secondary"></i>
                        Angkatan Tersedia
                        <span class="badge bg-secondary ms-1">{{ $angkatanTersedia->count() }}</span>
                    </h5>
                    <small class="text-muted">Angkatan dari jenis pelatihan yang sama, belum terhubung ke gelombang manapun</small>
                </div>
                <div class="card-body p-0">
                    @if($angkatanTersedia->isEmpty())
                        <div class="text-center py-5 px-3">
                            <i class="fas fa-check-circle fa-3x mb-3" style="color: #e9ecef;"></i>
                            <p class="text-muted mb-0">Semua angkatan sudah terhubung ke gelombang</p>
                        </div>
                    @else
                        <form action="{{ route('gelombang.tambah-angkatan', $gelombang) }}" method="POST">
                            @csrf
                            <div class="p-3 border-bottom">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" id="searchTersedia"
                                        placeholder="Cari angkatan...">
                                </div>
                            </div>
                            <div class="angkatan-list" style="max-height: 400px; overflow-y: auto;">
                                @foreach($angkatanTersedia as $ang)
                                    <div class="angkatan-item d-flex align-items-center p-3 border-bottom hover-bg"
                                        data-search="{{ strtolower($ang->nama_angkatan . ' ' . $ang->tahun) }}">
                                        <div class="form-check mb-0 me-2">
                                            <input class="form-check-input" type="checkbox"
                                                name="angkatan_ids[]" value="{{ $ang->id }}"
                                                id="tersedia_{{ $ang->id }}">
                                        </div>
                                        <label class="form-check-label w-100" for="tersedia_{{ $ang->id }}" style="cursor:pointer">
                                            <div class="fw-semibold">{{ $ang->nama_angkatan }}</div>
                                            <small class="text-muted">
                                                Tahun {{ $ang->tahun }}
                                                @if($ang->kategori)
                                                    · {{ $ang->kategori }}
                                                @endif
                                                @if($ang->wilayah)
                                                    · {{ $ang->wilayah }}
                                                @endif
                                            </small>
                                        </label>
                                        <span class="badge ms-auto {{ $ang->status_angkatan === 'Berlangsung' ? 'bg-success' : ($ang->status_angkatan === 'Dibuka' ? 'bg-primary' : 'bg-secondary') }}">
                                            {{ $ang->status_angkatan }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="p-3 border-top">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <small class="text-muted">
                                        Terpilih: <span id="countTerpilih" class="fw-bold text-primary">0</span>
                                    </small>
                                    <button type="button" id="selectAll" class="btn btn-link btn-sm p-0">Pilih Semua</button>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-arrow-right me-2"></i> Tambahkan ke Gelombang
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ikon Tengah -->
        <div class="col-md-2 d-none d-md-flex align-items-center justify-content-center">
            <div class="text-center text-muted">
                <i class="fas fa-exchange-alt fa-2x mb-2" style="color: #285496;"></i>
                <p class="small mb-0">Kelola<br>Relasi</p>
            </div>
        </div>

        <!-- Kolom Kanan: Angkatan Terhubung -->
        <div class="col-md-5">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-link me-2 text-success"></i>
                        Terhubung ke Gelombang Ini
                        <span class="badge bg-success ms-1">{{ $angkatanTerhubung->count() }}</span>
                    </h5>
                    <small class="text-muted">Angkatan yang sudah menjadi bagian dari {{ $gelombang->nama_gelombang }}</small>
                </div>
                <div class="card-body p-0">
                    @if($angkatanTerhubung->isEmpty())
                        <div class="text-center py-5 px-3">
                            <i class="fas fa-layer-group fa-3x mb-3" style="color: #e9ecef;"></i>
                            <p class="text-muted mb-0">Belum ada angkatan yang terhubung</p>
                            <small class="text-muted">Tambahkan dari daftar tersedia di sebelah kiri</small>
                        </div>
                    @else
                        <div class="p-3 border-bottom">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="searchTerhubung"
                                    placeholder="Cari angkatan...">
                            </div>
                        </div>
                        <div class="angkatan-list" style="max-height: 450px; overflow-y: auto;">
                            @foreach($angkatanTerhubung as $ang)
                                <div class="terhubung-item d-flex align-items-center p-3 border-bottom hover-bg"
                                    data-search="{{ strtolower($ang->nama_angkatan . ' ' . $ang->tahun) }}">
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">{{ $ang->nama_angkatan }}</div>
                                        <small class="text-muted">
                                            Tahun {{ $ang->tahun }}
                                            @if($ang->kategori) · {{ $ang->kategori }} @endif
                                            @if($ang->wilayah) · {{ $ang->wilayah }} @endif
                                        </small>
                                    </div>
                                    <span class="badge me-2 {{ $ang->status_angkatan === 'Berlangsung' ? 'bg-success' : ($ang->status_angkatan === 'Dibuka' ? 'bg-primary' : 'bg-secondary') }}">
                                        {{ $ang->status_angkatan }}
                                    </span>
                                    <form action="{{ route('gelombang.lepas-angkatan', $gelombang) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Lepas angkatan ini dari gelombang?')">
                                        @csrf
                                        <input type="hidden" name="angkatan_id" value="{{ $ang->id }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="tooltip" title="Lepas dari gelombang">
                                            <i class="fas fa-unlink"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    // Auto-hide alerts
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => bootstrap.Alert.getOrCreateInstance(alert)?.close(), 5000);
    });

    // Search tersedia
    document.getElementById('searchTersedia')?.addEventListener('input', function () {
        const term = this.value.toLowerCase();
        document.querySelectorAll('.angkatan-item').forEach(item => {
            item.style.display = item.dataset.search.includes(term) ? '' : 'none';
        });
    });

    // Search terhubung
    document.getElementById('searchTerhubung')?.addEventListener('input', function () {
        const term = this.value.toLowerCase();
        document.querySelectorAll('.terhubung-item').forEach(item => {
            item.style.display = item.dataset.search.includes(term) ? '' : 'none';
        });
    });

    // Hitung checkbox terpilih
    document.querySelectorAll('input[name="angkatan_ids[]"]').forEach(cb => {
        cb.addEventListener('change', updateCount);
    });

    function updateCount() {
        const count = document.querySelectorAll('input[name="angkatan_ids[]"]:checked').length;
        const el = document.getElementById('countTerpilih');
        if (el) el.textContent = count;
    }

    // Select All
    document.getElementById('selectAll')?.addEventListener('click', function () {
        const checkboxes = document.querySelectorAll('input[name="angkatan_ids[]"]');
        const allChecked = [...checkboxes].every(cb => cb.checked);
        checkboxes.forEach(cb => cb.checked = !allChecked);
        this.textContent = allChecked ? 'Pilih Semua' : 'Batal Pilih';
        updateCount();
    });
});
</script>

<style>
    .hover-bg:hover { background-color: rgba(40, 84, 150, 0.03); }
    .angkatan-list::-webkit-scrollbar { width: 6px; }
    .angkatan-list::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
    .form-check-input { width: 1.1rem; height: 1.1rem; cursor: pointer; }
    .form-check-input:checked { background-color: #285496; border-color: #285496; }
    .icon-wrapper { width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; }
</style>
@endsection