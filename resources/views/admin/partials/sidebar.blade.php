@php
    $user           = auth()->user();
    $kodePelatihan  = null;

    if ($user->role->name === 'user' && $user->peserta) {
        $kodePelatihan = \App\Models\Pendaftaran::query()
            ->where('pendaftaran.id_peserta', $user->peserta->id)
            ->join('jenis_pelatihan', 'pendaftaran.id_jenis_pelatihan', '=', 'jenis_pelatihan.id')
            ->orderByDesc('pendaftaran.tanggal_daftar')
            ->value('jenis_pelatihan.kode_pelatihan');
    }

    $labelMenu = match ($kodePelatihan) {
        'PKN_TK_II'  => 'Proyek Perubahan',
        'LATSAR'     => 'Aktualisasi',
        'PKA', 'PKP' => 'Aksi Perubahan',
        default      => null,
    };

    $isAdmin = in_array($user->role->name, ['admin', 'super admin']);
    $isPic   = $user->role->name === 'pic';
@endphp

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

        {{-- Dashboard --}}
        <div class="menu-item">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="fas fa-tachometer-alt menu-icon"></i>
                <span class="menu-text">Dashboard</span>
                <span class="badge-new">New</span>
            </a>
        </div>

        {{-- Aktualisasi / Aksi Perubahan --}}
        @if($user->hasPermission('menu.aktualisasi'))
            <div class="menu-item">
                <a href="{{ route('aksiperubahan.index') }}" class="menu-link">
                    <i class="fas fa-project-diagram menu-icon"></i>
                    <span class="menu-text">{{ $labelMenu }}</span>
                </a>
            </div>
        @endif

        {{-- Master Data --}}
        @if($user->hasPermission('menu.master'))
            <div class="menu-item has-submenu">
                <a href="#" class="menu-link">
                    <i class="fas fa-database menu-icon"></i>
                    <span class="menu-text">Master Data</span>
                    <i class="fas fa-chevron-right menu-arrow"></i>
                </a>
                <div class="submenu">
                    @if($user->hasPermission('angkatan.read'))
                        <a href="{{ route('angkatan.index') }}" class="submenu-link">
                            <i class="fas fa-handshake me-2"></i> Angkatan
                        </a>
                    @endif

                    @if($user->hasPermission('mentor.read'))
                        <a href="{{ route('mentor.index') }}" class="submenu-link">
                            <i class="fas fa-chalkboard-teacher me-2"></i> Mentor
                        </a>
                    @endif

                    @if($user->hasPermission('coach.read'))
                        <a href="{{ route('coach.index') }}" class="submenu-link">
                            <i class="fas fa-user-tie me-2"></i> Coach
                        </a>
                    @endif

                    @if($user->hasPermission('penguji.read'))
                        <a href="{{ route('penguji.index') }}" class="submenu-link">
                            <i class="fas fa-user-check me-2"></i> Penguji
                        </a>
                    @endif

                    @if($user->hasPermission('evaluator.read'))
                        <a href="{{ route('evaluator.index') }}" class="submenu-link">
                            <i class="fas fa-user-graduate me-2"></i> Evaluator
                        </a>
                    @endif

                    @if($user->hasPermission('gelombang.read'))
                        <a href="{{ route('gelombang.index') }}" class="submenu-link">
                            <i class="fas fa-layer-group me-2"></i> Gelombang
                        </a>
                    @endif

                    @if($user->hasPermission('kelompok.read'))
                        <a href="{{ route('kelompok.index') }}" class="submenu-link">
                            <i class="fas fa-users me-2"></i> Kelompok
                        </a>
                    @endif               

                    @unless($isPic)
                        <a href="{{ route('visi-misi.index') }}" class="submenu-link">
                            <i class="fas fa-bullseye me-2"></i> Visi & Misi
                        </a>
                        <a href="{{ route('pejabat.index') }}" class="submenu-link">
                            <i class="fas fa-users me-2"></i> Pejabat
                        </a>
                        <a href="{{ route('berita.index') }}" class="submenu-link">
                            <i class="fas fa-newspaper me-2"></i> Berita
                        </a>
                        <a href="{{ route('kontak.index') }}" class="submenu-link">
                            <i class="fas fa-address-book me-2"></i> Kontak
                        </a>
                    @endunless
                </div>
            </div>
        @endif

        {{-- Peserta --}}
        @if($user->hasPermission('menu.peserta'))
            <div class="menu-item has-submenu">
                <a href="#" class="menu-link">
                    <i class="fas fa-user menu-icon"></i>
                    <span class="menu-text">Peserta</span>
                    <i class="fas fa-chevron-right menu-arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('admin.import.peserta') }}" class="submenu-link">
                        <i class="fas fa-file-invoice me-2"></i> Import Peserta
                    </a>

                    @if($isAdmin || $user->picPesertas->where('jenispelatihan_id', 1)->count() > 0)
                        <a href="{{ route('peserta.index', ['jenis' => 'pkn']) }}" class="submenu-link">
                            <i class="fas fa-user-graduate me-2"></i> Peserta PKN TK II
                        </a>
                    @endif

                    @if($isAdmin || $user->picPesertas->where('jenispelatihan_id', 2)->count() > 0)
                        <a href="{{ route('peserta.index', ['jenis' => 'latsar']) }}" class="submenu-link">
                            <i class="fas fa-user-tie me-2"></i> Peserta LATSAR
                        </a>
                    @endif

                    @if($isAdmin || $user->picPesertas->where('jenispelatihan_id', 3)->count() > 0)
                        <a href="{{ route('peserta.index', ['jenis' => 'pka']) }}" class="submenu-link">
                            <i class="fas fa-users-gear me-2"></i> Peserta PKA
                        </a>
                    @endif

                    @if($isAdmin || $user->picPesertas->where('jenispelatihan_id', 4)->count() > 0)
                        <a href="{{ route('peserta.index', ['jenis' => 'pkp']) }}" class="submenu-link">
                            <i class="fas fa-user-check me-2"></i> Peserta PKP
                        </a>
                    @endif
                </div>
            </div>
        @endif

        {{-- Export Laporan --}}
        @if($user->hasPermission('menu.export'))
            <div class="menu-item has-submenu">
                <a href="#" class="menu-link">
                    <i class="fas fa-chart-line menu-icon"></i>
                    <span class="menu-text">Export Laporan</span>
                    <i class="fas fa-chevron-right menu-arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('admin.export.datapeserta') }}" class="submenu-link">
                        <i class="fas fa-chart-bar me-2"></i> Data Peserta
                    </a>
                    <a href="{{ route('admin.export.absenpeserta') }}" class="submenu-link">
                        <i class="fas fa-chart-pie me-2"></i> Absensi Peserta
                    </a>
                    <a href="{{ route('admin.export.komposisipeserta') }}" class="submenu-link">
                        <i class="fas fa-file-invoice me-2"></i> Komposisi Peserta
                    </a>
                    <a href="{{ route('admin.export.jadwal-seminar.index') }}" class="submenu-link">
                        <i class="fas fa-calendar-alt me-2"></i> Jadwal Seminar
                    </a>
                    <a href="{{ route('export.foto') }}" class="submenu-link">
                        <i class="fas fa-camera me-2"></i> Export Foto
                    </a>
                    <a href="{{ route('admin.export.sertifikat.view') }}" class="submenu-link">
                        <i class="fas fa-certificate me-2"></i> Export Sertifikat
                    </a>
                </div>
            </div>
        @endif

        {{-- Penilaian --}}
        @if($user->hasPermission('menu.penilaian'))
            <div class="menu-item">
                <a href="#" class="menu-link">
                    <i class="fas fa-clipboard-list menu-icon"></i>
                    <span class="menu-text">Penilaian</span>
                </a>
            </div>
        @endif

        {{-- Pengaturan Sistem --}}
        @if($user->hasPermission('menu.aturan'))
            <div class="menu-item has-submenu">
                <a href="#" class="menu-link">
                    <i class="fas fa-cog menu-icon"></i>
                    <span class="menu-text">Pengaturan Sistem</span>
                    <i class="fas fa-chevron-right menu-arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('users.index') }}" class="submenu-link">
                        <i class="fas fa-users-cog me-2"></i> Manajemen User
                    </a>
                    <a href="{{ route('roles.index') }}" class="submenu-link">
                        <i class="fas fa-user-shield me-2"></i> Peran & Hak Akses
                    </a>
                    <a href="{{ route('permissions.index') }}" class="submenu-link">
                        <i class="fas fa-sliders-h me-2"></i> Konfigurasi Sistem
                    </a>
                </div>
            </div>
        @endif

        {{-- Aktivitas --}}
        @if($user->hasPermission('menu.aktifitas'))
            <div class="menu-item">
                <a href="{{ route('aktifitas.index') }}" class="menu-link {{ request()->routeIs('admin.aktifitas.*') ? 'active' : '' }}">
                    <i class="fas fa-history menu-icon"></i>
                    <span class="menu-text">Aktivitas</span>
                </a>
            </div>
        @endif

        {{-- Logout --}}
        <div class="menu-item">
            <a href="{{ route('logout') }}" class="menu-link">
                <i class="fas fa-sign-out-alt menu-icon"></i>
                <span class="menu-text">Logout</span>
            </a>
        </div>

    </nav>
</aside>