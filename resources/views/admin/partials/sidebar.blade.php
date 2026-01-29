@php
$user = auth()->user();
$kodePelatihan = null;

// Ambil kode pelatihan terakhir user (1 query)
if ($user->role->name === 'user' && $user->peserta) {
    $kodePelatihan = \App\Models\Pendaftaran::query()
        ->where('pendaftaran.id_peserta', $user->peserta->id)
        ->join('jenis_pelatihan', 'pendaftaran.id_jenis_pelatihan', '=', 'jenis_pelatihan.id')
        ->orderByDesc('pendaftaran.tanggal_daftar')  // jika sering null, ganti id desc (lihat catatan)
        ->value('jenis_pelatihan.kode_pelatihan');
}

// Mapping label menu
$labelMenu = match ($kodePelatihan) {
    'PKN_TK_II' => 'Proyek Perubahan',
    'LATSAR' => 'Aktualisasi',
    'PKA', 'PKP' => 'Aksi Perubahan',
    default => null,
};
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
        <!-- Dashboard -->
        <div class="menu-item">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="fas fa-tachometer-alt menu-icon"></i>
                <span class="menu-text">Dashboard</span>
                <span class="badge-new">New</span>
            </a>
        </div>

        @if ($user->role->name === 'user' && $labelMenu)
            <div class="menu-item">
                <a href="{{ route('aksiperubahan.index') }}" class="menu-link">
                    <i class="fas fa-project-diagram menu-icon"></i>
                    <span class="menu-text">{{ $labelMenu }}</span>
                </a>
            </div>
        @endif

        @if (auth()->user()->role->name != "user")
                    <!-- Master Data -->
                    <div class="menu-item has-submenu">
                        <a href="#" class="menu-link">
                            <i class="fas fa-database menu-icon"></i>
                            <span class="menu-text">Master Data</span>
                            <i class="fas fa-chevron-right menu-arrow"></i>
                        </a>
                        <div class="submenu">
                            @if(auth()->user()->hasPermission('angkatan.read'))
                            <a href="{{ route('angkatan.index') }}" class="submenu-link">
                                <i class="fas fa-handshake me-2"></i> Angkatan
                            </a>
                            @endif
                            {{-- <a href="" class="submenu-link">
                                <i class="fas fa-handshake me-2"></i> Mitra Kerja
                            </a>
                            <a href="" class="submenu-link">
                                <i class="fas fa-book me-2"></i> Materi Pelatihan
                            </a> --}}
                            @if(auth()->user()->hasPermission('mentor.read'))
                            <a href="{{ route('mentor.index') }}" class="submenu-link">
                                <i class="fas fa-chalkboard-teacher me-2"></i> Mentor
                            </a>
                            @endif
                            <a href="{{ route('admin.import.peserta') }}" class="submenu-link">
                                <i class="fas fa-file-invoice me-2"></i> Import Peserta
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
                            @php
    $user = auth()->user();
    $isAdmin = in_array($user->role->name, ['admin', 'super admin']);
                            @endphp

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


                    <!-- Pelatihan & Kelas -->
                    {{-- <div class="menu-item has-submenu">
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
                    </div> --}}


                    <!-- Laporan & Analisis -->
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
                            <a href="{{ route('export.foto') }}" class="submenu-link">
                                <i class="fas fa-user-tie me-2"></i> Export Foto
                            </a>
                            <a href="{{ route('admin.export.sertifikat.view') }}" class="submenu-link">
                                <i class="fas fa-user-tie me-2"></i> Export Sertifikat
                            </a>
                            {{-- <a href="" class="submenu-link">
                                <i class="fas fa-clipboard-list me-2"></i> Evaluasi Program
                            </a> --}}
                        </div>
                    </div>


                    <!-- Pengaturan Sistem -->
                    @if (auth()->user()->role->name == "admin")
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
                                {{-- <a href="#" class="submenu-link">
                                    <i class="fas fa-sliders-h me-2"></i> Konfigurasi Sistem
                                </a> --}}
                                <a href="{{ route('visi-misi.index') }}" class="submenu-link">
                                    <i class="fas fa-bullseye me-2"></i> Visi & Misi
                                </a>
                            </div>
                        </div>
                    @endif
        @endif

        @if(auth()->user()->hasPermission('aktifitas.read'))
        <div class="menu-item">
            <a href="{{ route('aktifitas.index') }}" class="menu-link {{ request()->routeIs('admin.aktifitas.*') ? 'active' : '' }}">
                <i class="fas fa-history menu-icon"></i>
                <span class="menu-text">Aktivitas</span>
            </a>
        </div>
        @endif
        
        <!-- Logout -->
        <div class="menu-item">
            <a href="{{ route('logout') }}" class="menu-link">
                <i class="fas fa-sign-out-alt menu-icon"></i>
                <span class="menu-text">Logout</span>
            </a>
            
        </div>
    </nav>

   
</aside>