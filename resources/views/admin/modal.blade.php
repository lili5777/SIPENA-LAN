@if(in_array(auth()->user()->role->name, ['pic', 'admin','evaluator']))
<style>
#penilaian-notice-overlay {
    position: fixed; inset: 0;
    background: rgba(15, 23, 42, 0.72);
    backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
    z-index: 99999; display: flex; align-items: center; justify-content: center;
    padding: 1rem; opacity: 0; transition: opacity 0.35s ease;
}
#penilaian-notice-overlay.visible { opacity: 1; }

.pn-modal {
    background: #fff; border-radius: 20px; max-width: 520px; width: 100%;
    max-height: calc(100vh - 2rem); display: flex; flex-direction: column;
    box-shadow: 0 32px 80px rgba(15,23,42,0.28), 0 8px 24px rgba(15,23,42,0.12);
    overflow: hidden;
    transform: translateY(28px) scale(0.97);
    transition: transform 0.4s cubic-bezier(0.34,1.56,0.64,1), opacity 0.35s ease;
    opacity: 0;
}
#penilaian-notice-overlay.visible .pn-modal { transform: translateY(0) scale(1); opacity: 1; }

.pn-header {
    background: linear-gradient(135deg, #1a3a6c 0%, #2c5282 60%, #1e4b9c 100%);
    padding: 1.25rem 1.6rem 1rem; flex-shrink: 0; position: relative; overflow: hidden;
}
.pn-header::before {
    content:''; position:absolute; top:-40px; right:-40px;
    width:180px; height:180px; border-radius:50%; background:rgba(255,255,255,0.06);
}
.pn-header-row { display:flex; align-items:flex-start; gap:1rem; position:relative; z-index:1; }
.pn-icon-wrap {
    width:50px; height:50px; border-radius:13px;
    background:rgba(255,255,255,0.16); border:1.5px solid rgba(255,255,255,0.28);
    display:flex; align-items:center; justify-content:center; flex-shrink:0; color:#fff; font-size:1.3rem;
}
.pn-header-text h3 { color:#fff; font-size:1.05rem; font-weight:700; margin:0 0 0.2rem; line-height:1.3; }
.pn-header-text p  { color:rgba(255,255,255,0.8); font-size:0.82rem; margin:0; line-height:1.5; }
.pn-badge {
    margin-top:0.75rem; position:relative; z-index:1;
    display:inline-flex; align-items:center; gap:0.4rem;
    background:rgba(255,255,255,0.14); border:1px solid rgba(255,255,255,0.22);
    color:rgba(255,255,255,0.9); padding:0.28rem 0.7rem; border-radius:20px;
    font-size:0.75rem; font-weight:600; letter-spacing:0.3px;
}

.pn-body { padding:1.2rem 1.6rem; display:flex; flex-direction:column; gap:0.9rem; }

.pn-info {
    background:linear-gradient(135deg,#eff6ff,#dbeafe);
    border:1px solid #bfdbfe; border-radius:12px; padding:0.85rem 1rem;
    display:flex; gap:0.75rem; align-items:flex-start;
}
.pn-info i { color:#1a3a6c; font-size:0.9rem; margin-top:2px; flex-shrink:0; }
.pn-info p { margin:0; font-size:0.83rem; color:#1e3a5f; line-height:1.65; }
.pn-info p strong { color:#1a3a6c; }

.pn-access-card { background:#eff6ff; border:1px solid #bfdbfe; border-radius:11px; padding:0.85rem 1rem; }
.pn-ac-roles { display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:0.65rem; }
.pn-role-chip {
    display:inline-flex; align-items:center; gap:5px; padding:0.25rem 0.65rem;
    border-radius:20px; font-size:0.76rem; font-weight:700;
    background:#dbeafe; border:1px solid #93c5fd; color:#1e40af;
}
.pn-ac-list { margin:0; padding:0; list-style:none; display:flex; flex-direction:column; gap:7px; }
.pn-ac-list li { font-size:0.83rem; color:#1e3a5f; display:flex; align-items:center; gap:8px; }
.pn-ac-list li::before {
    content:''; display:inline-block; width:6px; height:6px;
    border-radius:50%; background:#2c5282; flex-shrink:0;
}

.pn-warning {
    background:#fff7ed; border:1px solid #fed7aa; border-left:4px solid #f59e0b;
    border-radius:10px; padding:0.75rem 1rem; display:flex; gap:0.7rem; align-items:flex-start;
}
.pn-warning i { color:#d97706; font-size:0.9rem; margin-top:2px; flex-shrink:0; }
.pn-warning p { margin:0; font-size:0.82rem; color:#92400e; line-height:1.6; }
.pn-warning p strong { color:#78350f; }

.pn-section-label { font-size:0.72rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:0.5rem; }
.pn-contact-row { display:flex; gap:0.5rem; flex-wrap:wrap; }
.pn-chip {
    display:inline-flex; align-items:center; gap:5px; padding:0.3rem 0.7rem;
    border-radius:20px; font-size:0.77rem; font-weight:600;
    background:#dbeafe; border:1px solid #93c5fd; color:#1e40af;
}

.pn-footer {
    padding:0.8rem 1.6rem; border-top:1px solid #f1f5f9; background:#fff; flex-shrink:0;
    display:flex; align-items:center; justify-content:space-between; gap:0.75rem; flex-wrap:wrap;
}
.pn-dismiss { font-size:0.78rem; color:#94a3b8; }
.pn-btn-close {
    background:linear-gradient(135deg,#1a3a6c,#2c5282); color:#fff; border:none;
    padding:0.55rem 1.4rem; border-radius:9px; font-size:0.88rem; font-weight:600; cursor:pointer;
    display:flex; align-items:center; gap:0.45rem; box-shadow:0 4px 12px rgba(26,58,108,0.28); transition:all .25s;
}
.pn-btn-close:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(26,58,108,0.4); }
</style>

<div id="penilaian-notice-overlay" role="dialog" aria-modal="true" aria-labelledby="pn-title">
    <div class="pn-modal">

        <div class="pn-header">
            <div class="pn-header-row">
                <div class="pn-icon-wrap">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="pn-header-text">
                    <h3 id="pn-title">Fitur Baru: Penilaian Peserta</h3>
                    <p>Sistem penilaian kini aktif — akses diatur berdasarkan peran pengguna</p>
                </div>
            </div>
            <div class="pn-badge">
                <i class="fas fa-lock"></i>
                Akses Terbatas &mdash; Role Tertentu
            </div>
        </div>

        <div class="pn-body">

            <div class="pn-info">
                <i class="fas fa-circle-info"></i>
                <p>
                    Sistem kini dilengkapi modul <strong>penilaian peserta</strong> yang terstruktur.
                    Pengelolaan jenis nilai, indikator, bobot, dan konfigurasi lainnya
                    <strong>hanya dapat dilakukan oleh peran yang berwenang</strong>
                    untuk menjaga konsistensi data penilaian.
                </p>
            </div>

            <div class="pn-access-card">
                <div class="pn-ac-roles">
                    <span class="pn-role-chip"><i class="fas fa-shield-halved"></i> Administrator</span>
                    <span class="pn-role-chip"><i class="fas fa-pen-to-square"></i> Evaluator</span>
                </div>
                <ul class="pn-ac-list">
                    <li>Kelola jenis nilai &amp; indikator</li>
                    <li>Atur bobot &amp; passing grade</li>
                </ul>
            </div>

            <div class="pn-warning">
                <i class="fas fa-triangle-exclamation"></i>
                <p>
                    <strong>Perlu perubahan konfigurasi penilaian?</strong>
                    Jenis nilai baru, perubahan bobot, atau penambahan indikator harus
                    dikoordinasikan dengan <strong>Administrator</strong> atau <strong>Evaluator</strong>
                    yang bertugas. Peran lain tidak memiliki akses untuk memodifikasi pengaturan ini.
                </p>
            </div>

            <div>
                <div class="pn-section-label">Hubungi untuk koordinasi</div>
                <div class="pn-contact-row">
                    <span class="pn-chip"><i class="fas fa-shield-halved"></i> Tim Administrator</span>
                    <span class="pn-chip"><i class="fas fa-pen-to-square"></i> Tim Evaluator</span>
                </div>
            </div>

        </div>

        <div class="pn-footer">
            <span class="pn-dismiss">Notifikasi ini hanya tampil sekali per sesi</span>
            <button class="pn-btn-close" onclick="closePenilaianNotice()">
                <i class="fas fa-check"></i> Mengerti, Tutup
            </button>
        </div>

    </div>
</div>

<script>
(function () {
    const STORAGE_KEY = 'penilaian_notice_{{ auth()->id() }}';

    function openModal() {
        const overlay = document.getElementById('penilaian-notice-overlay');
        if (!overlay) return;
        overlay.style.display = 'flex';
        requestAnimationFrame(() => requestAnimationFrame(() => overlay.classList.add('visible')));
        document.body.style.overflow = 'hidden';
    }

    window.closePenilaianNotice = function () {
        const overlay = document.getElementById('penilaian-notice-overlay');
        if (!overlay) return;
        try { sessionStorage.setItem(STORAGE_KEY, 'true'); } catch (e) {}
        overlay.classList.remove('visible');
        setTimeout(() => { overlay.style.display = 'none'; document.body.style.overflow = ''; }, 380);
    };

    document.getElementById('penilaian-notice-overlay')
        ?.addEventListener('click', function (e) { if (e.target === this) closePenilaianNotice(); });

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closePenilaianNotice(); });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => setTimeout(openModal, 600));
    } else {
        setTimeout(openModal, 600);
    }
})();
</script>
@endif