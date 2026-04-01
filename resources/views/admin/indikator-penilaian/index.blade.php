@extends('admin.partials.layout')

@section('title', 'Master Indikator Penilaian')

@section('content')
    <!-- Page Header -->
    <div class="page-header rounded-3 mb-4"
        style="background: linear-gradient(135deg, #285496 0%, #3a6bc7 100%); padding: 2rem;">
        <div class="row align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white rounded-circle p-3 me-3 shadow">
                        <i class="fas fa-clipboard-list fa-lg" style="color: #285496;"></i>
                    </div>
                    <div>
                        <h1 class="text-white mb-1">Master Indikator Penilaian</h1>
                        <p class="text-white-50 mb-0">Kelola jenis nilai dan indikator penilaian per jenis pelatihan</p>
                    </div>
                </div>
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

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">
                <i class="fas fa-clipboard-list me-1"></i> Pilih Jenis Pelatihan
            </li>
        </ol>
    </nav>

    <!-- Step Indicator -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="d-flex align-items-center step-indicator">
                <div class="step-item active">
                    <div class="step-circle active"><i class="fas fa-graduation-cap"></i></div>
                    <span class="step-label">Jenis Pelatihan</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item">
                    <div class="step-circle"><span>2</span></div>
                    <span class="step-label">Jenis Nilai</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item">
                    <div class="step-circle"><span>3</span></div>
                    <span class="step-label">Indikator</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item">
                    <div class="step-circle"><span>4</span></div>
                    <span class="step-label">Detail Indikator</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Title -->
    <div class="d-flex align-items-center mb-3">
        <h5 class="fw-semibold mb-0"><i class="fas fa-graduation-cap me-2" style="color: #285496;"></i> Pilih Jenis Pelatihan</h5>
        <span class="badge bg-primary ms-2">{{ $jenisPelatihan->count() }} Pelatihan</span>
    </div>

    <!-- Jenis Pelatihan Cards -->
    @if($jenisPelatihan->count() > 0)
        <div class="row g-3">
            @foreach($jenisPelatihan as $jp)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <a href="{{ route('indikator-penilaian.jenis-nilai.index', $jp->id) }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm pelatihan-card h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="pelatihan-icon me-3">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="badge bg-primary-subtle text-primary fw-semibold mb-1">{{ $jp->kode_pelatihan }}</span>
                                        <h6 class="fw-bold mb-0 card-title-text">{{ $jp->nama_pelatihan }}</h6>
                                    </div>
                                </div>
                                @if($jp->deskripsi)
                                    <p class="text-muted small mb-3">{{ Str::limit($jp->deskripsi, 80) }}</p>
                                @endif
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-light text-dark border small">
                                            <i class="fas fa-list me-1"></i>
                                            {{ $jp->jenisNilai()->count() ?? 0 }} Jenis Nilai
                                        </span>
                                    </div>
                                    <div class="arrow-icon">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-graduation-cap fa-4x mb-3" style="color: #e9ecef;"></i>
                <h5 class="text-muted mb-2">Belum ada jenis pelatihan</h5>
                <p class="text-muted">Tambahkan jenis pelatihan terlebih dahulu</p>
            </div>
        </div>
    @endif

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => bootstrap.Alert.getOrCreateInstance(alert)?.close(), 5000);
    });
});
</script>

<style>
    .step-indicator { gap: 0; }
    .step-item { display: flex; flex-direction: column; align-items: center; flex: 0 0 auto; }
    .step-circle {
        width: 40px; height: 40px; border-radius: 50%;
        background: #e9ecef; color: #adb5bd;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .9rem; border: 2px solid #dee2e6;
        transition: all .3s;
    }
    .step-circle.active { background: #285496; color: white; border-color: #285496; box-shadow: 0 4px 12px rgba(40,84,150,.3); }
    .step-label { font-size: .75rem; color: #6c757d; margin-top: .4rem; font-weight: 500; white-space: nowrap; }
    .step-line { flex: 1; height: 2px; background: #dee2e6; margin: 0 .5rem; margin-bottom: 1.2rem; }

    .pelatihan-card {
        border-radius: 12px !important;
        transition: all .25s ease;
        cursor: pointer;
        border: 2px solid transparent !important;
    }
    .pelatihan-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(40,84,150,.15) !important;
        border-color: #285496 !important;
    }
    .pelatihan-card:hover .arrow-icon { transform: translateX(4px); color: #285496; }
    .pelatihan-card:hover .pelatihan-icon { background: linear-gradient(135deg, #285496, #3a6bc7); color: white; }

    .pelatihan-icon {
        width: 44px; height: 44px; border-radius: 10px;
        background: linear-gradient(135deg, rgba(40,84,150,.1), rgba(58,107,199,.1));
        color: #285496;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
        transition: all .25s;
    }
    .arrow-icon { color: #adb5bd; transition: all .25s; font-size: .85rem; }
    .card-title-text { color: #2c3e50; line-height: 1.3; }
    .bg-primary-subtle { background-color: rgba(40,84,150,.1) !important; }
    .text-primary { color: #285496 !important; }
</style>
@endsection