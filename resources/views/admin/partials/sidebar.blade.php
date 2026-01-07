<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="fas fa-landmark"></i>
        </div>
        <div class="sidebar-title">
            <h5>LAN Pusjar SKMP</h5>
            <small>Sistem Pembelajaran</small>
        </div>
    </div>

    <nav class="sidebar-menu">
        <!-- Dashboard -->
        <div class="menu-item">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="fas fa-tachometer-alt menu-icon"></i>
                <span class="menu-text">Dashboard</span>
                <span class="badge-new">New</span>
            </a>
        </div>

        <!-- Master Data -->
        <div class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <i class="fas fa-database menu-icon"></i>
                <span class="menu-text">Master Data</span>
                <i class="fas fa-chevron-right menu-arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('angkatan.index') }}" class="submenu-link">
                    <i class="fas fa-handshake me-2"></i> Angkatan
                </a>
                {{-- <a href="" class="submenu-link">
                    <i class="fas fa-handshake me-2"></i> Mitra Kerja
                </a>
                <a href="" class="submenu-link">
                    <i class="fas fa-book me-2"></i> Materi Pelatihan
                </a> --}}
                <a href="{{ route('mentor.index') }}" class="submenu-link">
                    <i class="fas fa-chalkboard-teacher me-2"></i> Mentor
                </a>
            </div>
        </div>

        {{-- Peserta --}}
        <div class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <i class="fas fa-user menu-icon"></i>
                <span class="menu-text">Peserta</span>
                <i class="fas fa-chevron-right menu-arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('peserta.index', ['jenis' => 'pkn']) }}" class="submenu-link">
                    <i class="fas fa-user-graduate me-2"></i> Peserta PKN TK II
                </a>
                <a href="{{ route('peserta.index', ['jenis' => 'latsar']) }}" class="submenu-link">
                    <i class="fas fa-user-tie me-2"></i> Peserta LATSAR
                </a>
                <a href="{{ route('peserta.index', ['jenis' => 'pka']) }}" class="submenu-link">
                    <i class="fas fa-users-gear me-2"></i> Peserta PKA
                </a>
                <a href="{{ route('peserta.index', ['jenis' => 'pkp']) }}" class="submenu-link">
                    <i class="fas fa-user-check me-2"></i> Peserta PKP
                </a>
            </div>
        </div>

        <!-- Pelatihan & Kelas -->
        <div class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <i class="fas fa-graduation-cap menu-icon"></i>
                <span class="menu-text">Pelatihan & Kelas</span>
                <i class="fas fa-chevron-right menu-arrow"></i>
            </a>
            <div class="submenu">
                <a href="" class="submenu-link">
                    <i class="fas fa-calendar-alt me-2"></i> Jadwal Pelatihan
                </a>
                <a href="" class="submenu-link">
                    <i class="fas fa-users me-2"></i> Kelas Aktif
                </a>
                <a href="" class="submenu-link">
                    <i class="fas fa-clipboard-check me-2"></i> Presensi
                </a>
                <a href="" class="submenu-link">
                    <i class="fas fa-star me-2"></i> Penilaian
                </a>
            </div>
        </div>

        <!-- Laporan & Analisis -->
        <div class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <i class="fas fa-chart-line menu-icon"></i>
                <span class="menu-text">Laporan & Analisis</span>
                <i class="fas fa-chevron-right menu-arrow"></i>
            </a>
            <div class="submenu">
                <a href="" class="submenu-link">
                    <i class="fas fa-chart-bar me-2"></i> Statistik Peserta
                </a>
                <a href="" class="submenu-link">
                    <i class="fas fa-chart-pie me-2"></i> Analisis Pelatihan
                </a>
                <a href="" class="submenu-link">
                    <i class="fas fa-file-invoice me-2"></i> Laporan Keuangan
                </a>
                <a href="" class="submenu-link">
                    <i class="fas fa-clipboard-list me-2"></i> Evaluasi Program
                </a>
            </div>
        </div>

        <!-- Pengaturan Sistem -->
        <div class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <i class="fas fa-cog menu-icon"></i>
                <span class="menu-text">Pengaturan Sistem</span>
                <i class="fas fa-chevron-right menu-arrow"></i>
            </a>
            <div class="submenu">
                {{-- @if(auth()->user()->hasPermission('user.read')) --}}
                    <a href="{{ route('users.index') }}" class="submenu-link">
                        <i class="fas fa-users-cog me-2"></i> Manajemen User
                    </a>
                {{-- @endif --}}
                {{-- @if(auth()->user()->hasPermission('role.read')) --}}
                    <a href="{{ route('roles.index') }}" class="submenu-link">
                        <i class="fas fa-user-shield me-2"></i> Peran & Hak Akses
                    </a>
                {{-- @endif --}}
                <a href="#" class="submenu-link">
                    <i class="fas fa-sliders-h me-2"></i> Konfigurasi Sistem
                </a>
            </div>
        </div>

        <!-- Logout -->
        <div class="menu-item">
            <a href="{{ route('logout') }}" class="menu-link">
                <i class="fas fa-sign-out-alt menu-icon"></i>
                <span class="menu-text">Logout</span>
            </a>
            
        </div>
    </nav>

   
</aside>