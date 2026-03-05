{{-- ============================================================
     MODAL PEMBERITAHUAN INSTANSI PESERTA
     Tampil sekali per sesi untuk role: pic, admin
     Cara integrasi:
       1. Paste blok @if ... @endif ini tepat sebelum </body> atau
          di bagian bawah @section('content') pada dashboard.blade.php
       2. Tidak perlu library tambahan — sudah pakai vanilla JS + CSS.
     ============================================================ --}}

@if(in_array(auth()->user()->role->name, ['pic', 'admin']))
{{-- ── STYLES ─────────────────────────────────────────────── --}}
<style>
/* ── overlay ── */
#instansi-notice-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.72);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    opacity: 0;
    transition: opacity 0.35s ease;
}
#instansi-notice-overlay.visible { opacity: 1; }

/* ── modal card ── */
.in-modal {
    background: #ffffff;
    border-radius: 20px;
    max-width: 600px;
    width: 100%;
    /* kunci: tidak boleh melebihi tinggi viewport */
    max-height: calc(100vh - 2rem);
    display: flex;
    flex-direction: column;
    box-shadow:
        0 32px 80px rgba(15, 23, 42, 0.28),
        0 8px 24px rgba(15, 23, 42, 0.12),
        inset 0 1px 0 rgba(255,255,255,0.9);
    overflow: hidden;
    transform: translateY(28px) scale(0.97);
    transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.35s ease;
    opacity: 0;
}
#instansi-notice-overlay.visible .in-modal {
    transform: translateY(0) scale(1);
    opacity: 1;
}

/* ── header banner ── */
.in-header {
    background: linear-gradient(135deg, #1a3a6c 0%, #2c5282 60%, #1e4b9c 100%);
    padding: 1.25rem 1.75rem 1rem;
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
}
.in-header::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,0.07);
}
.in-header::after {
    content: '';
    position: absolute;
    bottom: -60px; left: -20px;
    width: 140px; height: 140px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
}

.in-header-row {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    position: relative;
    z-index: 1;
}

.in-icon-wrap {
    width: 52px; height: 52px;
    border-radius: 14px;
    background: rgba(255,255,255,0.18);
    border: 1.5px solid rgba(255,255,255,0.3);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: 1.45rem;
    color: #fff;
}

.in-header-text h3 {
    color: #fff;
    font-size: 1.2rem;
    font-weight: 700;
    margin: 0 0 0.3rem;
    letter-spacing: -0.3px;
    line-height: 1.3;
}
.in-header-text p {
    color: rgba(255,255,255,0.82);
    font-size: 0.875rem;
    margin: 0;
    line-height: 1.5;
}

/* ── role badge ── */
.in-role-badge {
    margin-top: 0.75rem;
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    color: rgba(255,255,255,0.92);
    padding: 0.3rem 0.75rem;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.3px;
}

/* ── body scrollable ── */
.in-body {
    padding: 1.25rem 1.75rem;
    overflow-y: auto;
    flex: 1;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}
.in-body::-webkit-scrollbar { width: 5px; }
.in-body::-webkit-scrollbar-track { background: transparent; }
.in-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

/* ── footer sticky ── */
.in-footer-wrap {
    padding: 0.875rem 1.75rem;
    border-top: 1px solid #f1f5f9;
    background: #fff;
    flex-shrink: 0;
}

/* ── purpose statement ── */
.in-purpose {
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    border: 1px solid #bfdbfe;
    border-radius: 12px;
    padding: 0.875rem 1.1rem;
    margin-bottom: 1.1rem;
    display: flex;
    gap: 0.75rem;
    align-items: flex-start;
}
.in-purpose i {
    color: #1a3a6c;
    font-size: 1rem;
    margin-top: 2px;
    flex-shrink: 0;
}
.in-purpose p {
    margin: 0;
    font-size: 0.875rem;
    color: #1e3a5f;
    line-height: 1.6;
}
.in-purpose p strong { color: #1a3a6c; }

/* ── step list ── */
.in-steps-title {
    font-size: 0.78rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 0.6rem;
}

.in-steps {
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
    margin-bottom: 1.1rem;
}

.in-step {
    display: flex;
    gap: 0.875rem;
    align-items: flex-start;
}

.in-step-num {
    width: 28px; height: 28px;
    border-radius: 8px;
    background: #1a3a6c;
    color: #fff;
    font-size: 0.8rem;
    font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}

.in-step-content {
    padding-top: 3px;
}
.in-step-content strong {
    display: block;
    font-size: 0.9rem;
    color: #1e293b;
    margin-bottom: 0.2rem;
    font-weight: 600;
}
.in-step-content span {
    font-size: 0.825rem;
    color: #64748b;
    line-height: 1.5;
}

/* ── warning box ── */
.in-warning {
    background: #fff7ed;
    border: 1px solid #fed7aa;
    border-left: 4px solid #f59e0b;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    margin-bottom: 0;
    display: flex;
    gap: 0.75rem;
    align-items: flex-start;
}
.in-warning i {
    color: #d97706;
    font-size: 0.95rem;
    margin-top: 2px;
    flex-shrink: 0;
}
.in-warning p {
    margin: 0;
    font-size: 0.84rem;
    color: #92400e;
    line-height: 1.55;
}
.in-warning p strong { color: #78350f; }

/* ── footer ── */
.in-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}

.in-dont-show {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    user-select: none;
}
.in-dont-show input[type="checkbox"] {
    width: 16px; height: 16px;
    accent-color: #1a3a6c;
    cursor: pointer;
    flex-shrink: 0;
}
.in-dont-show label {
    font-size: 0.82rem;
    color: #64748b;
    cursor: pointer;
    margin: 0;
}

.in-btn-close {
    background: linear-gradient(135deg, #1a3a6c, #2c5282);
    color: #fff;
    border: none;
    padding: 0.6rem 1.5rem;
    border-radius: 9px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.25s ease;
    box-shadow: 0 4px 12px rgba(26, 58, 108, 0.3);
}
.in-btn-close:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(26, 58, 108, 0.4);
}
.in-btn-close:active { transform: translateY(0); }

/* ── mobile ── */
@media (max-width: 576px) {
    .in-header      { padding: 1.25rem 1.1rem 1rem; }
    .in-body        { padding: 1rem 1.1rem; }
    .in-footer-wrap { padding: 0.75rem 1.1rem; }
    .in-footer      { flex-direction: column; align-items: stretch; }
    .in-btn-close   { justify-content: center; }
    .in-dont-show   { justify-content: center; }
}
</style>

{{-- ── HTML ─────────────────────────────────────────────────── --}}
<div id="instansi-notice-overlay" role="dialog" aria-modal="true" aria-labelledby="in-title">
    <div class="in-modal">

        {{-- Header --}}
        <div class="in-header">
            <div class="in-header-row">
                <div class="in-icon-wrap">
                    <i class="fas fa-building-columns"></i>
                </div>
                <div class="in-header-text">
                    <h3 id="in-title">Pemberitahuan: Verifikasi Asal Instansi Peserta</h3>
                    <p>Panduan untuk PIC & Admin dalam menjaga keseragaman data instansi</p>
                </div>
            </div>
            <div class="in-role-badge">
                <i class="fas fa-shield-halved"></i>
                @if(auth()->user()->role->name === 'admin') Admin @else PIC Angkatan @endif
                &mdash; {{ auth()->user()->name }}
            </div>
        </div>

        {{-- Body --}}
        <div class="in-body">

            {{-- Tujuan --}}
            <div class="in-purpose">
                <i class="fas fa-circle-info"></i>
                <p>
                    Sistem kini menggunakan <strong>daftar instansi terstandar</strong> pada form pendaftaran peserta.
                    Tujuannya untuk <strong>menghindari duplikasi data</strong> akibat perbedaan penulisan nama instansi
                    yang sama (misalnya: "Pemkot Bandung" vs "Pemerintah Kota Bandung").
                    Data ini digunakan untuk <strong>analisis komposisi instansi</strong> seluruh peserta.
                </p>
            </div>

            {{-- Langkah --}}
            <div class="in-steps-title"><i class="fas fa-list-check" style="margin-right:6px;"></i>Yang perlu dilakukan</div>
            <div class="in-steps">
                <div class="in-step">
                    <div class="in-step-num">1</div>
                    <div class="in-step-content">
                        <strong>Informasikan ke seluruh peserta</strong>
                        <span>Minta peserta membuka form pembaruan data dan memilih asal instansi dari
                              daftar dropdown yang tersedia, bukan mengetik secara bebas.</span>
                    </div>
                </div>
                <div class="in-step">
                    <div class="in-step-num">2</div>
                    <div class="in-step-content">
                        <strong>Jika instansi tidak ditemukan di daftar</strong>
                        <span>Peserta wajib <strong>melaporkan ke PIC</strong> agar nama instansi dapat ditambahkan
                              ke daftar resmi oleh Administrator sebelum peserta mengisi form.</span>
                    </div>
                </div>
                <div class="in-step">
                    <div class="in-step-num">3</div>
                    <div class="in-step-content">
                        <strong>PIC meneruskan permintaan ke Admin</strong>
                        <span>PIC mengumpulkan nama-nama instansi yang belum ada, lalu menyampaikannya
                              ke Admin untuk ditambahkan ke konfigurasi sistem.</span>
                    </div>
                </div>
            </div>

            {{-- Peringatan --}}
            <div class="in-warning">
                <i class="fas fa-triangle-exclamation"></i>
                <p>
                    <strong>Penting:</strong> Peserta <strong>tidak boleh melewati langkah ini</strong> dengan mengetik
                    nama instansi secara manual di luar daftar. Data instansi yang tidak terstandar akan
                    menyebabkan <strong>distorsi pada laporan komposisi peserta</strong> dan mempersulit analisis data.
                </p>
            </div>

        </div>{{-- /in-body --}}

        {{-- Footer sticky di luar area scroll --}}
        <div class="in-footer-wrap">
            <div class="in-footer">
                <button class="in-btn-close" id="in-close-btn" onclick="closeInstansiNotice()">
                    <i class="fas fa-check"></i>
                    Mengerti, Tutup
                </button>
            </div>
        </div>
    </div>{{-- /in-modal --}}
</div>{{-- /overlay --}}

{{-- ── SCRIPT ───────────────────────────────────────────────── --}}
<script>
(function () {
    const STORAGE_KEY = 'instansi_notice_suppressed_{{ auth()->id() }}';
    const TODAY       = new Date().toISOString().slice(0, 10); // YYYY-MM-DD

    function shouldShow() {
        try {
            return sessionStorage.getItem(STORAGE_KEY) !== 'true'
                && localStorage.getItem(STORAGE_KEY) !== TODAY;
        } catch (e) { return true; }
    }

    function openModal() {
        const overlay = document.getElementById('instansi-notice-overlay');
        if (!overlay) return;
        overlay.style.display = 'flex';
        requestAnimationFrame(() => {
            requestAnimationFrame(() => overlay.classList.add('visible'));
        });
        document.body.style.overflow = 'hidden';
    }

    window.closeInstansiNotice = function () {
        const overlay = document.getElementById('instansi-notice-overlay');
        if (!overlay) return;

        // Simpan preferensi suppress
        const suppress = document.getElementById('in-suppress-check')?.checked;
        try {
            sessionStorage.setItem(STORAGE_KEY, 'true');
            if (suppress) localStorage.setItem(STORAGE_KEY, TODAY);
        } catch (e) { /* private mode */ }

        overlay.classList.remove('visible');
        setTimeout(() => {
            overlay.style.display = 'none';
            document.body.style.overflow = '';
        }, 380);
    };

    // Tutup jika klik di luar modal
    document.getElementById('instansi-notice-overlay')
        ?.addEventListener('click', function (e) {
            if (e.target === this) closeInstansiNotice();
        });

    // Tutup dengan Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeInstansiNotice();
    });

    // Tampilkan setiap kali halaman dimuat
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => setTimeout(openModal, 600));
    } else {
        setTimeout(openModal, 600);
    }
})();
</script>
@endif