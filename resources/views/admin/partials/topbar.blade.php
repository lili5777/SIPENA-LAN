<header class="topbar">
    <button class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars" style="font-size: 1.25rem;"></i>
    </button>

    <div class="breadcrumb-wrapper">
        <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">@yield('page-title', 'Dashboard')</li>
            </ol>
        </nav>
    </div>

    <!-- Real-time Clock Display -->
    <div class="clock-display me-3" style="display: none;">
        <div class="time-container" style="text-align: right;">
            <div id="currentTime" class="fw-bold" style="font-size: 1.1rem; color: var(--primary-color);"></div>
            <div id="currentDate" class="text-muted" style="font-size: 0.85rem;"></div>
        </div>
    </div>

    <!-- Notification Bell — hanya untuk selain role 'user' -->
    @if(auth()->user()->role && auth()->user()->role->name !== 'user')
    <div class="dropdown me-1" id="notifDropdownWrapper">
        <button class="notification-btn position-relative" type="button" id="notifDropdown"
            data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi Update Sistem">
            <i class="fas fa-bell" style="font-size: 1.25rem;"></i>
            <!-- Badge merah berkedip -->
            <span class="notif-badge" id="notifBadge">2</span>
        </button>

        <div class="dropdown-menu dropdown-menu-end p-0 notif-dropdown-menu"
            style="width: 360px; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.18);">

            <!-- Header -->
            <div class="notif-header px-4 py-3 d-flex align-items-center justify-content-between"
                style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
                <div>
                    <h6 class="mb-0 text-white fw-bold"><i class="fas fa-rocket me-2"></i>Update Sistem</h6>
                    <small class="text-white opacity-75">Fitur terbaru telah tersedia</small>
                </div>
                <span class="badge bg-white text-primary fw-bold" style="font-size: 0.75rem;">2 Baru</span>
            </div>

            <!-- Notif Items -->
            <div class="notif-body" style="max-height: 340px; overflow-y: auto;">

                <!-- Item 1 -->
                <div class="notif-item d-flex align-items-start px-3 py-3 notif-unread" data-id="1">
                    <div class="notif-icon-wrap me-3 flex-shrink-0">
                        <div class="notif-icon-circle" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                            <i class="fas fa-trash-alt text-white" style="font-size: 0.9rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="fw-bold" style="font-size: 0.88rem; color: #2d3436;">Multi Delete Peserta</span>
                            <span class="notif-dot"></span>
                        </div>
                        <p class="mb-1 text-muted" style="font-size: 0.82rem; line-height: 1.4;">
                            PIC kini dapat <strong>menghapus lebih dari 1 peserta sekaligus</strong> dengan fitur multi-select delete yang lebih cepat dan efisien.
                        </p>
                        <small class="text-primary fw-semibold" style="font-size: 0.75rem;">
                            <i class="fas fa-clock me-1"></i>Baru saja
                        </small>
                    </div>
                </div>

                <div class="notif-divider mx-3"></div>

                <!-- Item 2 -->
                <div class="notif-item d-flex align-items-start px-3 py-3 notif-unread" data-id="2">
                    <div class="notif-icon-wrap me-3 flex-shrink-0">
                        <div class="notif-icon-circle" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                            <i class="fas fa-code-branch text-white" style="font-size: 0.9rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="fw-bold" style="font-size: 0.88rem; color: #2d3436;">Merge Mentor Duplikat</span>
                            <span class="notif-dot"></span>
                        </div>
                        <p class="mb-1 text-muted" style="font-size: 0.82rem; line-height: 1.4;">
                            Di halaman <strong>Master Mentor</strong> kini tersedia tombol <strong>&ldquo;Rapikan Duplikat&rdquo;</strong> &mdash; sistem akan menemukan mentor yang sama, lalu memindahkan seluruh peserta dari mentor duplikat ke <strong>1 mentor utama</strong> secara otomatis.
                        </p>
                        <small class="text-primary fw-semibold" style="font-size: 0.75rem;">
                            <i class="fas fa-clock me-1"></i>Baru saja
                        </small>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="notif-footer px-4 py-2 d-flex align-items-center justify-content-between"
                style="border-top: 1px solid #f0f0f0; background: #fafafa;">
                <small class="text-muted">v2.1.0 — 24 Feb 2026</small>
                <button class="btn btn-sm btn-link text-primary p-0 fw-semibold" id="markAllRead"
                    style="font-size: 0.8rem; text-decoration: none;">
                    <i class="fas fa-check-double me-1"></i>Tandai Dibaca
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- User Profile -->
    <div class="dropdown ms-2">
        <button class="notification-btn" type="button" id="userDropdown" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fas fa-user-circle" style="font-size: 1.25rem;"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end p-0"
            style="width: 280px; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);">
            <div class="p-3 text-center"
                style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
                <div class="mb-2">
                    <i class="fas fa-user-circle" style="font-size: 3rem; color: white;"></i>
                </div>
                <h6 class="mb-0 text-white fw-bold">{{ auth()->user()->name }}</h6>
                <small class="text-white opacity-75">{{ auth()->user()->email }}</small>
            </div>
            <div class="p-2">
                @if (auth()->user()->role && auth()->user()->role->name !== 'user')
                    <a href="{{ route('admin.akun.index') }}" class="dropdown-item p-3">
                        <i class="fas fa-user-cog me-2"></i> Profil Saya
                    </a>
                @endif
                <a href="#" class="dropdown-item p-3">
                    <i class="fas fa-question-circle me-2"></i> Bantuan
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item p-3 text-danger">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </div>
        </div>
    </div>
</header>

<!-- ===================== STYLES ===================== -->
<style>
    /* Badge merah berkedip di atas ikon lonceng */
    .notif-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #e74c3c;
        color: #fff;
        font-size: 0.65rem;
        font-weight: 700;
        min-width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
        animation: pulseBadge 1.6s infinite;
        line-height: 1;
    }

    @keyframes pulseBadge {
        0%   { box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.7); transform: scale(1); }
        50%  { box-shadow: 0 0 0 6px rgba(231, 76, 60, 0); transform: scale(1.15); }
        100% { box-shadow: 0 0 0 0 rgba(231, 76, 60, 0); transform: scale(1); }
    }

    /* Icon lingkaran warna di tiap notif */
    .notif-icon-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Item notif hover */
    .notif-item {
        transition: background 0.2s;
        cursor: default;
    }
    .notif-item:hover {
        background: #f8f9ff;
    }

    /* Unread = background sedikit biru muda */
    .notif-unread {
        background: #f0f4ff;
    }
    .notif-unread.notif-read {
        background: #fff;
    }

    /* Titik merah penanda belum dibaca */
    .notif-dot {
        width: 8px;
        height: 8px;
        background: #e74c3c;
        border-radius: 50%;
        flex-shrink: 0;
        display: inline-block;
        transition: opacity 0.3s;
    }
    .notif-read .notif-dot {
        opacity: 0;
    }

    /* Garis pembatas tipis */
    .notif-divider {
        border-top: 1px solid #eef0f5;
    }

    /* Dropdown tidak terlalu kecil di mobile */
    @media (max-width: 400px) {
        .notif-dropdown-menu {
            width: 95vw !important;
        }
    }
</style>

<!-- ===================== SCRIPTS ===================== -->
<script>
    // ---- Real-time clock ----
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
        const dateString = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

        const currentTimeElement = document.getElementById('currentTime');
        const currentDateElement = document.getElementById('currentDate');
        if (currentTimeElement) currentTimeElement.textContent = timeString;
        if (currentDateElement) currentDateElement.textContent = dateString;

        const clockDisplay = document.querySelector('.clock-display');
        if (clockDisplay) {
            clockDisplay.style.display = window.innerWidth > 768 ? 'block' : 'none';
        }
    }
    setInterval(updateClock, 1000);
    updateClock();
    window.addEventListener('resize', updateClock);

    // ---- Notification logic ----
    const STORAGE_KEY = 'sys_notif_read_ids';

    function getReadIds() {
        try { return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]'); } catch { return []; }
    }

    function saveReadIds(ids) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(ids));
    }

    function updateBadge() {
        const readIds = getReadIds();
        const unreadItems = document.querySelectorAll('.notif-item[data-id]');
        let unreadCount = 0;
        unreadItems.forEach(item => {
            const id = item.getAttribute('data-id');
            if (!readIds.includes(id)) unreadCount++;
        });

        const badge = document.getElementById('notifBadge');
        if (badge) {
            if (unreadCount > 0) {
                badge.textContent = unreadCount;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }
    }

    function applyReadState() {
        const readIds = getReadIds();
        document.querySelectorAll('.notif-item[data-id]').forEach(item => {
            const id = item.getAttribute('data-id');
            if (readIds.includes(id)) {
                item.classList.remove('notif-unread');
                item.classList.add('notif-read');
            }
        });
        updateBadge();
    }

    // Tandai semua dibaca
    document.getElementById('markAllRead')?.addEventListener('click', function (e) {
        e.stopPropagation();
        const ids = Array.from(document.querySelectorAll('.notif-item[data-id]')).map(el => el.getAttribute('data-id'));
        saveReadIds(ids);
        applyReadState();
    });

    // Auto-tandai saat dropdown dibuka
    document.getElementById('notifDropdown')?.addEventListener('click', function () {
        // Tandai semua setelah 3 detik otomatis (opsional, bisa dihapus jika tidak ingin auto-read)
        // setTimeout(() => { document.getElementById('markAllRead')?.click(); }, 3000);
    });

    // Init saat halaman load
    document.addEventListener('DOMContentLoaded', applyReadState);
    applyReadState(); // juga panggil langsung
</script>