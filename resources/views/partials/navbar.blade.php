<!-- Header & Navbar -->
<header class="fade-in">
    <div class="container">
        <nav class="navbar">
            <a href="#home" class="logo">
                <img src="https://www.sipena.info/images/skmp1.png" alt="Logo SIPENA" class="logo-img">
            </a>

            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>

            <ul class="nav-links" id="navLinks">
                <li><a href="{{ route('home') }}">Beranda</a></li>
                <li><a href="{{ route('profil') }}">Profil</a></li>
                <li><a href="{{ route('publikasi') }}">Publikasi</a></li>
                <li><a href="#contact">Kontak</a></li>
                <li><a href="{{ route('login') }}" class="login-btn">Login</a></li>
            </ul>
        </nav>
    </div>
</header>

<style>
    /* Header & Navbar */
    header {
        background-color: white;
        box-shadow: var(--shadow);
        position: sticky;
        top: 0;
        z-index: 1000;
        padding: 10px 0;
    }

    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
    }

    .logo-img {
        height: 50px;
        width: auto;
    }

    /* Navbar Links Styling - Tanpa Icon */
    .nav-links {
        display: flex;
        list-style: none;
        gap: 25px;
        align-items: center;
    }

    .nav-links li {
        display: flex;
        align-items: center;
        height: 100%;
    }

    .nav-links a {
        text-decoration: none;
        color: var(--dark-color);
        font-weight: 500;
        transition: var(--transition);
        position: relative;
        display: flex;
        align-items: center;
        height: 40px;
        font-size: 15px;
        padding: 0 5px;
    }

    .nav-links a:not(.login-btn)::after {
        content: '';
        position: absolute;
        bottom: 10px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--gradient-primary);
        transition: var(--transition);
    }

    .nav-links a:not(.login-btn):hover::after {
        width: 100%;
    }

    .nav-links a:hover:not(.login-btn) {
        color: var(--primary-color);
    }

    /* Login Button */
    .nav-links a.login-btn {
        background: var(--gradient-primary);
        color: white !important;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        line-height: 1;
        white-space: nowrap;
        box-shadow: 0 4px 12px rgba(26, 58, 108, 0.2);
    }

    .nav-links a.login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(26, 58, 108, 0.3);
    }

    .mobile-menu-btn {
        display: none;
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--primary-color);
        cursor: pointer;
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background-color: rgba(26, 58, 108, 0.05);
        z-index: 1001;
    }

    @media (max-width: 768px) {
        .mobile-menu-btn {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-links {
            position: fixed;
            top: 70px;
            left: 0;
            width: 100%;
            background-color: white;
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
            box-shadow: var(--shadow);
            transform: translateY(-100%);
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            z-index: 1000;
            gap: 0;
            border-radius: 0 0 16px 16px;
            max-height: 0;
            overflow: hidden;
        }

        .nav-links.active {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
            max-height: 500px;
        }

        .nav-links li {
            width: 100%;
            margin-bottom: 10px;
        }

        .nav-links a {
            height: 50px;
            padding: 0 15px;
            width: 100%;
            border-radius: 8px;
            transition: all 0.2s;
            justify-content: flex-start;
        }

        .nav-links a:hover {
            background-color: rgba(26, 58, 108, 0.05);
        }

        .nav-links a.login-btn {
            margin-top: 10px;
            justify-content: center;
        }
    }
</style>