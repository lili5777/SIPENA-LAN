@extends('admin.partials.layout')

@section('title', 'Pengaturan Akses Penilaian')

@section('content')

    <!-- Page Header -->
    <div class="page-header rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%); padding: 2rem;">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-shield-alt fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">Pengaturan Akses Penilaian</h1>
                        <p class="text-white-50 mb-0">Atur siapa saja yang dapat mengisi nilai per indikator</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-check-circle fa-lg me-3"></i>
            <div class="flex-grow-1"><strong>Sukses!</strong> {{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Info Box -->
    <div class="alert alert-info d-flex align-items-start shadow-sm mb-4" role="alert">
        <i class="fas fa-info-circle fa-lg me-3 mt-1 flex-shrink-0"></i>
        <div>
            <strong>Cara kerja akses penilaian:</strong>
            <ul class="mb-0 mt-1 ps-3">
                <li>Setiap indikator dapat diassign ke satu atau lebih role</li>
                <li><strong>Admin</strong> selalu bisa mengisi semua indikator tanpa perlu diatur</li>
                <li>Role yang tidak punya akses ke indikator tertentu akan melihat input terkunci</li>
                <li>Hanya <strong>Admin</strong> dan <strong>Evaluator</strong> yang bisa mengubah pengaturan ini</li>
            </ul>
        </div>
    </div>

    <!-- Pilih Jenis Pelatihan -->
    <div class="d-flex align-items-center mb-3">
        <h5 class="fw-semibold mb-0">
            <i class="fas fa-graduation-cap me-2" style="color: #285496;"></i>
            Pilih Jenis Pelatihan
        </h5>
    </div>

    <div class="row g-3">
        @foreach($jenisPelatihan as $jp)
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('akses-penilaian.kelola', $jp->id) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm akses-card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start mb-3">
                                <div class="jp-icon me-3">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="badge bg-primary-soft text-primary fw-semibold mb-1 d-block" style="width:fit-content">
                                        {{ $jp->kode_pelatihan }}
                                    </span>
                                    <h6 class="fw-bold mb-0" style="color:#2c3e50; line-height:1.3;">
                                        {{ $jp->nama_pelatihan }}
                                    </h6>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">Akses terkonfigurasi</small>
                                <small class="fw-semibold text-primary">
                                    {{ $jp->sudah_ada_akses }}/{{ $jp->total_indikator }} indikator
                                </small>
                            </div>

                            @php
                                $persen = $jp->total_indikator > 0
                                    ? round(($jp->sudah_ada_akses / $jp->total_indikator) * 100)
                                    : 0;
                            @endphp
                            <div class="progress mb-3" style="height:6px; border-radius:4px;">
                                <div class="progress-bar {{ $persen >= 100 ? 'bg-success' : ($persen > 0 ? 'bg-primary' : 'bg-secondary') }}"
                                    style="width:{{ $persen }}%"></div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge {{ $persen >= 100 ? 'bg-success' : ($persen > 0 ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                    {{ $persen >= 100 ? 'Lengkap' : ($persen > 0 ? 'Sebagian' : 'Belum diatur') }}
                                </span>
                                <div class="arrow-icon">
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.alert').forEach(a => {
        setTimeout(() => bootstrap.Alert.getOrCreateInstance(a)?.close(), 5000);
    });
});
</script>

<style>
    .page-header { padding: 2rem; box-shadow: 0 4px 20px rgba(40,84,150,.15); }

    .akses-card {
        border-radius: 12px !important;
        border: 2px solid transparent !important;
        transition: all .25s ease;
        cursor: pointer;
    }
    .akses-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(40,84,150,.15) !important;
        border-color: #285496 !important;
    }
    .akses-card:hover .arrow-icon i { transform: translateX(4px); color: #285496 !important; }
    .akses-card:hover .jp-icon { background: linear-gradient(135deg, #285496, #3a6bc7); color: white; }

    .jp-icon {
        width: 44px; height: 44px; border-radius: 10px; flex-shrink: 0;
        background: rgba(40,84,150,.1); color: #285496;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; transition: all .25s;
    }
    .arrow-icon i { transition: all .25s; }
    .bg-primary-soft { background: rgba(40,84,150,.1) !important; }
    .text-primary { color: #285496 !important; }
</style>
@endsection