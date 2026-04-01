@extends('admin.partials.layout')

@section('title', 'Akses Penilaian - ' . $jenisPelatihan->nama_pelatihan)

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
                        <h1 class="text-white mb-1">Akses Penilaian</h1>
                        <p class="text-white-50 mb-0">
                            <span class="badge bg-white text-primary fw-semibold me-2">{{ $jenisPelatihan->kode_pelatihan }}</span>
                            {{ $jenisPelatihan->nama_pelatihan }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('akses-penilaian.index') }}" class="btn btn-light shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
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
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-exclamation-circle fa-lg me-3"></i>
            <div class="flex-grow-1"><strong>Error!</strong> {{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Legend Role -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3 px-4">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <span class="text-muted small fw-semibold">
                    <i class="fas fa-tag me-1"></i> Role Tersedia:
                </span>
                @foreach($roleList as $role)
                    <span class="role-legend-badge role-{{ $role->name }}">
                        <i class="fas fa-circle me-1" style="font-size:.5rem; vertical-align:middle;"></i>
                        {{ ucfirst($role->name) }}
                    </span>
                @endforeach
                <span class="role-legend-badge role-admin ms-2">
                    <i class="fas fa-crown me-1" style="font-size:.65rem;"></i>
                    Admin (akses penuh, otomatis)
                </span>
            </div>
        </div>
    </div>

    <!-- Form Bulk Save -->
    <form action="{{ route('akses-penilaian.simpan-bulk', $jenisPelatihan->id) }}" method="POST" id="formAkses">
        @csrf

        @forelse($jenisNilaiList as $jn)
            <div class="card border-0 shadow-sm mb-4 jenis-nilai-section">

                <!-- Header Jenis Nilai -->
                <div class="card-header border-0 py-3 px-4"
                    style="background: linear-gradient(135deg, rgba(40,84,150,.06), rgba(58,107,199,.04)); border-left: 4px solid #285496 !important;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="jn-icon me-3">
                                <i class="fas fa-star"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">{{ $jn->name }}</h6>
                                <small class="text-muted">
                                    Bobot: <strong>{{ $jn->bobot }}%</strong>
                                    &nbsp;·&nbsp;
                                    {{ $jn->indikatorNilai->count() }} Indikator
                                </small>
                            </div>
                        </div>
                        <!-- Tombol select all untuk jenis nilai ini -->
                        <div class="d-flex gap-2">
                            @foreach($roleList as $role)
                                <button type="button"
                                    class="btn btn-xs btn-outline-secondary btn-select-all-jn"
                                    data-jn-id="{{ $jn->id }}"
                                    data-role-id="{{ $role->id }}"
                                    data-bs-toggle="tooltip"
                                    title="Pilih semua indikator untuk {{ $role->display_name ?? $role->name }}">
                                    <i class="fas fa-check-double me-1"></i>
                                    {{ $role->display_name ?? ucfirst($role->name) }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Tabel Indikator -->
                <div class="card-body p-0">
                    @if($jn->indikatorNilai->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 akses-table">
                                <thead>
                                    <tr class="table-light">
                                        <th width="4%" class="ps-4">No</th>
                                        <th width="32%">Indikator</th>
                                        <th width="10%">Bobot</th>
                                        @foreach($roleList as $role)
                                            <th width="{{ floor(54 / $roleList->count()) }}%" class="text-center">
                                                <span class="role-header-badge role-{{ $role->name }}">
                                                    {{ $role->display_name ?? ucfirst($role->name) }}
                                                </span>
                                            </th>
                                        @endforeach
                                        <th width="8%" class="text-center pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jn->indikatorNilai as $idx => $ind)
                                        @php
                                            $roleIdsTerSet = $ind->roles->pluck('id')->toArray();
                                            $adaAkses      = count($roleIdsTerSet) > 0;
                                        @endphp
                                        <tr class="indikator-row {{ $adaAkses ? '' : 'row-belum-diatur' }}"
                                            data-indikator-id="{{ $ind->id }}"
                                            data-jn-id="{{ $jn->id }}">

                                            <td class="ps-4 fw-semibold">{{ $idx + 1 }}</td>

                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="ind-dot {{ $adaAkses ? 'configured' : '' }} me-2"></div>
                                                    <div>
                                                        <div class="fw-semibold small">{{ $ind->name }}</div>
                                                        @if($ind->deskripsi)
                                                            <small class="text-muted">{{ Str::limit($ind->deskripsi, 50) }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <span class="badge bg-light text-dark border fw-semibold">
                                                    {{ $ind->bobot }}%
                                                </span>
                                            </td>

                                            @foreach($roleList as $role)
                                                <td class="text-center">
                                                    <div class="form-check d-flex justify-content-center mb-0">
                                                        <input type="checkbox"
                                                            class="form-check-input akses-checkbox"
                                                            name="akses[{{ $ind->id }}][]"
                                                            value="{{ $role->id }}"
                                                            data-indikator-id="{{ $ind->id }}"
                                                            data-role-id="{{ $role->id }}"
                                                            data-jn-id="{{ $jn->id }}"
                                                            {{ in_array($role->id, $roleIdsTerSet) ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                            @endforeach

                                            <td class="text-center pe-4">
                                                <button type="button"
                                                    class="btn btn-xs btn-outline-danger btn-reset-indikator"
                                                    data-indikator-id="{{ $ind->id }}"
                                                    data-nama="{{ $ind->name }}"
                                                    data-bs-toggle="tooltip" title="Reset akses indikator ini">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-tasks fa-2x mb-2 d-block" style="color:#e9ecef;"></i>
                            <p class="mb-0 small">Belum ada indikator untuk jenis nilai ini</p>
                        </div>
                    @endif
                </div>

            </div>
        @empty
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-star fa-4x mb-3" style="color: #e9ecef;"></i>
                    <h5 class="text-muted mb-2">Belum ada jenis nilai</h5>
                    <p class="text-muted">Tambahkan jenis nilai dan indikator terlebih dahulu</p>
                </div>
            </div>
        @endforelse

        @if($jenisNilaiList->count() > 0)
            <!-- Tombol Simpan Semua -->
            <div class="card border-0 shadow-sm sticky-bottom-bar">
                <div class="card-body py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Perubahan akan diterapkan ke semua indikator sekaligus
                            </small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('akses-penilaian.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-times me-2"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="fas fa-save me-2"></i> Simpan Semua Pengaturan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </form>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    // ── Saat checkbox berubah: update tampilan baris ──────────
    document.querySelectorAll('.akses-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            updateRowStatus(this.dataset.indikatorId);
        });
    });

    function updateRowStatus(indikatorId) {
        const row      = document.querySelector(`.indikator-row[data-indikator-id="${indikatorId}"]`);
        const checks   = row.querySelectorAll('.akses-checkbox:checked');
        const dot      = row.querySelector('.ind-dot');
        const adaAkses = checks.length > 0;

        row.classList.toggle('row-belum-diatur', !adaAkses);
        dot.classList.toggle('configured', adaAkses);
    }

    // ── Tombol "pilih semua role X untuk jenis nilai Y" ───────
    document.querySelectorAll('.btn-select-all-jn').forEach(btn => {
        btn.addEventListener('click', function () {
            const jnId   = this.dataset.jnId;
            const roleId = this.dataset.roleId;

            // Cek apakah semua sudah tercentang
            const allCb   = document.querySelectorAll(
                `.akses-checkbox[data-jn-id="${jnId}"][data-role-id="${roleId}"]`
            );
            const allChecked = [...allCb].every(cb => cb.checked);

            // Toggle: jika semua sudah checked → uncheck semua, sebaliknya check semua
            allCb.forEach(cb => {
                cb.checked = !allChecked;
                updateRowStatus(cb.dataset.indikatorId);
            });

            // Update tampilan tombol
            this.classList.toggle('btn-secondary', !allChecked);
            this.classList.toggle('btn-outline-secondary', allChecked);
        });
    });

    // ── Tombol reset per indikator (AJAX) ─────────────────────
    document.querySelectorAll('.btn-reset-indikator').forEach(btn => {
        btn.addEventListener('click', async function () {
            const indId = this.dataset.indikatorId;
            const nama  = this.dataset.nama;

            if (!confirm(`Reset akses untuk indikator "${nama}"?`)) return;

            try {
                const res  = await fetch('/akses-penilaian/reset', {
                    method: 'POST',
                    headers: {
                        'Content-Type':     'application/json',
                        'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ indikator_nilai_id: indId }),
                });
                const data = await res.json();

                if (data.success) {
                    // Uncheck semua checkbox di baris ini
                    document.querySelectorAll(`.akses-checkbox[data-indikator-id="${indId}"]`)
                        .forEach(cb => { cb.checked = false; });
                    updateRowStatus(indId);
                    showToast('success', 'Akses berhasil direset');
                } else {
                    showToast('error', data.message || 'Gagal reset');
                }
            } catch (e) {
                showToast('error', 'Error jaringan');
            }
        });
    });

    // ── Toast notifikasi kecil ─────────────────────────────────
    function showToast(type, msg) {
        const existing = document.getElementById('akses-toast');
        if (existing) existing.remove();

        const colors = { success: '#28a745', error: '#dc3545' };
        const icons  = { success: 'fa-check-circle', error: 'fa-times-circle' };

        const toast = document.createElement('div');
        toast.id        = 'akses-toast';
        toast.className = 'position-fixed bottom-0 end-0 m-4 shadow-lg';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <div class="d-flex align-items-center gap-2 px-4 py-3 rounded-3 text-white"
                style="background:${colors[type]}; min-width:220px;">
                <i class="fas ${icons[type]}"></i>
                <span class="small fw-semibold">${msg}</span>
            </div>`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    // Auto-hide alerts
    document.querySelectorAll('.alert').forEach(a => {
        setTimeout(() => bootstrap.Alert.getOrCreateInstance(a)?.close(), 5000);
    });

});
</script>

<style>
    .page-header { padding: 2rem; box-shadow: 0 4px 20px rgba(40,84,150,.15); }

    /* ── JENIS NILAI SECTION ─── */
    .jenis-nilai-section { border-radius: 12px !important; overflow: hidden; }
    .jn-icon {
        width: 36px; height: 36px; border-radius: 8px;
        background: linear-gradient(135deg, #f59e0b, #f97316);
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: .9rem; flex-shrink: 0;
    }

    /* ── TABLE ─── */
    .akses-table th {
        border-bottom: 2px solid rgba(40,84,150,.1); font-weight: 600;
        color: #285496; background-color: #f8fafc;
        padding: .65rem 1rem; font-size: .82rem;
    }
    .akses-table td { padding: .7rem 1rem; vertical-align: middle; border-bottom: 1px solid #e9ecef; }
    .akses-table tr:hover { background-color: rgba(40,84,150,.02); }
    .row-belum-diatur { background-color: rgba(255,193,7,.04) !important; }
    .row-belum-diatur:hover { background-color: rgba(255,193,7,.08) !important; }

    /* ── IND DOT ─── */
    .ind-dot {
        width: 9px; height: 9px; border-radius: 50%;
        background: #dee2e6; flex-shrink: 0; transition: all .2s;
    }
    .ind-dot.configured { background: #28a745; box-shadow: 0 0 0 3px rgba(40,167,69,.2); }

    /* ── CHECKBOX ─── */
    .akses-checkbox {
        width: 1.15em; height: 1.15em; cursor: pointer;
        border: 2px solid #ced4da; border-radius: 4px !important;
        transition: all .15s;
    }
    .akses-checkbox:checked { background-color: #285496; border-color: #285496; }
    .akses-checkbox:focus { box-shadow: 0 0 0 .2rem rgba(40,84,150,.25); border-color: #285496; }

    /* ── ROLE BADGES ─── */
    .role-legend-badge, .role-header-badge {
        display: inline-block; border-radius: 6px; font-size: .72rem;
        font-weight: 600; padding: .25rem .6rem;
    }
    .role-pic        { background: rgba(23,162,184,.12);  color: #117a8b; border: 1px solid rgba(23,162,184,.3);  }
    .role-penguji    { background: rgba(255,193,7,.15);   color: #856404; border: 1px solid rgba(255,193,7,.4);   }
    .role-coach      { background: rgba(40,167,69,.1);    color: #155724; border: 1px solid rgba(40,167,69,.3);   }
    .role-evaluator  { background: rgba(111,66,193,.1);   color: #4a0e8f; border: 1px solid rgba(111,66,193,.3);  }
    .role-admin      { background: rgba(220,53,69,.1);    color: #721c24; border: 1px solid rgba(220,53,69,.3);   }

    /* ── TOMBOL KECIL ─── */
    .btn-xs { padding: .2rem .5rem; font-size: .75rem; border-radius: 6px; }

    /* ── STICKY BOTTOM ─── */
    .sticky-bottom-bar {
        position: sticky; bottom: 0; z-index: 100;
        border-top: 1px solid #e9ecef !important;
        background: white; border-radius: 0 !important;
        box-shadow: 0 -4px 16px rgba(0,0,0,.08) !important;
    }
</style>
@endsection