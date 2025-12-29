<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - LAN Pusjar SKMP')</title>
    <link rel="icon" href="{{ asset('gambar/lanri.png') }}" type="image/png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary-color: #1a3a6c;
            --secondary-color: #2c5aa0;
            --accent-color: #e63946;
            --gold-color: #d4af37;
            --dark-color: #0d1b2a;
            --light-color: #f8f9fa;
            --success-color: #2a9d8f;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --topbar-height: 70px;
            --sidebar-bg: #1a3a6c;
            --sidebar-hover: #2c5aa0;
            --text-light: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f5f7fb;
            overflow-x: hidden;
            color: #333;
        }

        /* Sidebar Styles */
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: var(--sidebar-width);
    height: 100vh;
    background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: var(--text-light);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1050;
    overflow: hidden; /* Menyembunyikan overflow di seluruh sidebar */
    box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
}

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

       .sidebar-header {
    padding: 1.75rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    display: flex;
    align-items: center;
    gap: 1rem;
    background: rgba(0, 0, 0, 0.1);
    position: sticky; /* Menjaga header tetap di atas */
    top: 0;
    z-index: 1100;
}


        .sidebar-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .sidebar-logo {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #fff, var(--gold-color));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--primary-color);
            font-size: 1.5rem;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .sidebar-title {
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            transition: all 0.3s;
        }

        .sidebar.collapsed .sidebar-title {
            opacity: 0;
            width: 0;
        }

        .sidebar-title h5 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            letter-spacing: 0.5px;
        }

        .sidebar-title small {
            font-size: 0.8rem;
            opacity: 0.9;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Menu Styles */
       .sidebar-menu {
    padding: 0;
    overflow-y: auto; /* Membuat menu dapat digulir */
    height: calc(100vh - var(--topbar-height) - 3.5rem); /* Menyisakan ruang untuk header dan footer */
}

.menu-item {
    margin: 0.25rem 1rem;
}

.menu-link {
    display: flex;
    align-items: center;
    padding: 0.9rem 1rem;
    color: var(--text-light);
    text-decoration: none;
    border-radius: 12px;
    transition: all 0.3s;
    position: relative;
    gap: 1rem;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(5px);
}

.menu-link:hover {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

        .menu-link.active {
            background: linear-gradient(135deg, var(--gold-color), #ffd700);
            color: var(--dark-color);
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
        }

        .menu-link.active .menu-icon {
            color: var(--dark-color);
        }

        .menu-icon {
            width: 24px;
            font-size: 1.25rem;
            flex-shrink: 0;
            text-align: center;
            color: rgba(255, 255, 255, 0.9);
        }

        .menu-link.active .menu-icon {
            color: var(--dark-color);
        }

        .menu-text {
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.3s;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .sidebar.collapsed .menu-text,
        .sidebar.collapsed .menu-arrow,
        .sidebar.collapsed .badge-new {
            opacity: 0;
            width: 0;
        }

        .menu-arrow {
            font-size: 0.875rem;
            transition: transform 0.3s;
            color: rgba(255, 255, 255, 0.7);
        }

        .menu-item.active .menu-arrow {
            transform: rotate(90deg);
        }

        .badge-new {
            background: var(--accent-color);
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(230, 57, 70, 0.2);
        }

        /* Submenu */
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            margin-left: 1rem;
        }

        .menu-item.active .submenu {
            max-height: 500px;
        }

        .submenu-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem 0.75rem 3rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s;
            font-size: 0.9rem;
            margin: 0.2rem 0;
            background: rgba(255, 255, 255, 0.03);
        }

        .submenu-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding-left: 3.5rem;
        }

        .submenu-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left: 3px solid var(--gold-color);
        }

       

        /* Topbar */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            padding: 0 2rem;
            gap: 1.5rem;
            z-index: 999;
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar.collapsed~.topbar {
            left: var(--sidebar-collapsed-width);
        }

        .menu-toggle {
            width: 44px;
            height: 44px;
            border: none;
            background: linear-gradient(135deg, var(--light-color), #e9ecef);
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            color: var(--primary-color);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .menu-toggle:hover {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(26, 58, 108, 0.2);
        }

        .breadcrumb-wrapper {
            flex: 1;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
            letter-spacing: -0.5px;
        }

        .breadcrumb {
            margin: 0;
            padding: 0;
            background: none;
            font-size: 0.9rem;
        }

        .breadcrumb-item a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb-item.active {
            color: #6c757d;
        }

        .quick-actions {
            display: flex;
            gap: 0.75rem;
        }

        @media (max-width: 1200px) {
            .quick-actions {
                display: none;
            }
        }

        .quick-action-btn {
            padding: 0.6rem 1.2rem;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border: 1px solid #dee2e6;
            border-radius: 10px;
            color: var(--dark-color);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }

        .quick-action-btn:hover {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 58, 108, 0.2);
        }

        /* Notification Dropdown */
        .notification-btn {
            position: relative;
            width: 44px;
            height: 44px;
            border: none;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            color: var(--dark-color);
        }

        .notification-btn:hover {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--accent-color);
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
            font-weight: 700;
            min-width: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(230, 57, 70, 0.2);
        }

        /* Notification Dropdown Styling */
        .dropdown-menu {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            margin-top: 10px;
        }

        .dropdown-item {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.3s;
        }

        .dropdown-item:hover {
            background-color: #f8fafc;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            padding: 2rem;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: calc(100vh - var(--topbar-height));
            background: linear-gradient(135deg, #f5f7fb 0%, #eef2ff 100%);
        }

        .sidebar.collapsed~.main-content {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Mobile Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar.collapsed {
                width: var(--sidebar-width);
                transform: translateX(-100%);
            }

            .sidebar.collapsed.active {
                transform: translateX(0);
            }

            .topbar {
                left: 0 !important;
            }

            .main-content {
                margin-left: 0 !important;
                padding: 1.5rem;
            }

            .quick-actions {
                display: none;
            }

            .breadcrumb {
                display: none;
            }

            .topbar {
                padding: 0 1.25rem;
                gap: 1rem;
            }

            .sidebar.active .sidebar-title,
            .sidebar.active .menu-text,
            .sidebar.active .menu-arrow,
            .sidebar.active .badge-new {
                opacity: 1;
                width: auto;
            }

            .sidebar.active .sidebar-footer-content {
                opacity: 1;
            }
        }

        @media (max-width: 576px) {
            .page-title {
                font-size: 1.25rem;
            }

            .main-content {
                padding: 1.25rem;
            }

            .topbar {
                padding: 0 1rem;
            }

            .menu-toggle {
                width: 40px;
                height: 40px;
            }

            .notification-btn {
                width: 40px;
                height: 40px;
            }
        }

        @media (max-width: 380px) {
            .page-title {
                font-size: 1.1rem;
            }

            .main-content {
                padding: 1rem;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        /* Custom styles for dashboard */
        .dashboard-content {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
    @yield('styles')
</head>

<body>
    @include('admin.partials.sidebar')
    @include('admin.partials.topbar')

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <main class="main-content dashboard-content">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>
        // Sidebar Toggle
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.getElementById('menuToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            function isMobileView() {
                return window.innerWidth < 992;
            }

            menuToggle.addEventListener('click', () => {
                if (isMobileView()) {
                    sidebar.classList.toggle('active');
                    sidebarOverlay.classList.toggle('active');
                    document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
                } else {
                    sidebar.classList.toggle('collapsed');
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
                }
            });

            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });

            // Load sidebar state on desktop
            window.addEventListener('load', () => {
                if (!isMobileView()) {
                    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                    if (isCollapsed) {
                        sidebar.classList.add('collapsed');
                    }
                }
            });

            // Submenu Toggle - FIXED VERSION
            document.querySelectorAll('.has-submenu').forEach(item => {
                const menuLink = item.querySelector('.menu-link');

                if (menuLink) {
                    menuLink.addEventListener('click', (e) => {
                        // Cegah aksi default hanya jika bukan link yang mengarah ke halaman lain
                        if (menuLink.getAttribute('href') === '#' || !menuLink.getAttribute('href')) {
                            e.preventDefault();

                            // Cek apakah sidebar collapsed (desktop)
                            if (!isMobileView() && sidebar.classList.contains('collapsed')) {
                                // Jika sidebar collapsed, expand sidebar dulu
                                sidebar.classList.remove('collapsed');
                                localStorage.setItem('sidebarCollapsed', false);
                                return;
                            }

                            // Toggle current submenu
                            item.classList.toggle('active');

                            // Tampilkan atau sembunyikan submenu sesuai dengan status submenu
                            const submenu = item.querySelector('.submenu');
                            if (submenu) {
                                if (item.classList.contains('active')) {
                                    submenu.style.maxHeight = submenu.scrollHeight + "px"; // buka submenu
                                } else {
                                    submenu.style.maxHeight = '0'; // tutup submenu
                                }
                            }

                            // Close other submenus pada level yang sama
                            const parentMenu = item.parentElement;
                            if (parentMenu.classList.contains('sidebar-menu')) {
                                parentMenu.querySelectorAll('.has-submenu').forEach(otherItem => {
                                    if (otherItem !== item && !otherItem.contains(item)) {
                                        otherItem.classList.remove('active');
                                        const otherSubmenu = otherItem.querySelector('.submenu');
                                        if (otherSubmenu) {
                                            otherSubmenu.style.maxHeight = '0'; // tutup submenu lainnya
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
            });

            // Set active menu based on current URL
            window.addEventListener('load', () => {
                const currentPath = window.location.pathname;

                // Cari semua link di sidebar
                document.querySelectorAll('.sidebar a').forEach(link => {
                    const href = link.getAttribute('href');

                    // Jika href adalah route yang valid dan cocok dengan current path
                    if (href && href !== '#' && href !== '') {
                        // Hapus parameter query jika ada
                        const cleanHref = href.split('?')[0];

                        // Cek jika current path cocok dengan href
                        if (currentPath === cleanHref) {
                            link.classList.add('active');

                            // Aktifkan parent submenu jika ada
                            const parentSubmenu = link.closest('.submenu');
                            if (parentSubmenu) {
                                const parentMenuItem = parentSubmenu.closest('.has-submenu');
                                if (parentMenuItem) {
                                    parentMenuItem.classList.add('active');
                                }
                            }
                        }
                    }
                });
            });

            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    if (!isMobileView()) {
                        // Switch to desktop mode
                        sidebar.classList.remove('active');
                        sidebarOverlay.classList.remove('active');
                        document.body.style.overflow = '';
                    } else {
                        // Switch to mobile mode
                        sidebar.classList.remove('collapsed');
                        // Tutup semua submenu saat pindah ke mobile
                        document.querySelectorAll('.has-submenu').forEach(item => {
                            item.classList.remove('active');
                            const submenu = item.querySelector('.submenu');
                            if (submenu) {
                                submenu.style.maxHeight = '0'; // tutup semua submenu saat pindah ke mobile
                            }
                        });
                    }
                }, 250);
            });

            // Close sidebar when clicking links in mobile
            if (isMobileView()) {
                document.querySelectorAll('.sidebar a').forEach(link => {
                    link.addEventListener('click', (e) => {
                        // Jika bukan link untuk toggle submenu
                        if (!link.closest('.has-submenu') || link.getAttribute('href') !== '#') {
                            sidebar.classList.remove('active');
                            sidebarOverlay.classList.remove('active');
                            document.body.style.overflow = '';
                        }
                    });
                });
            }

            // Close all submenus when clicking outside in mobile
            document.addEventListener('click', (e) => {
                if (isMobileView() && sidebar.classList.contains('active')) {
                    if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                        document.querySelectorAll('.has-submenu').forEach(item => {
                            item.classList.remove('active');
                            const submenu = item.querySelector('.submenu');
                            if (submenu) {
                                submenu.style.maxHeight = '0'; // tutup submenu jika klik di luar
                            }
                        });
                    }
                }
            });

    </script>
    @yield('scripts')
</body>

</html>