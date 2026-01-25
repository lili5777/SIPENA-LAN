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
                {{-- <a href="{{ route('admin.akun.index') }}" class="dropdown-item p-3">
                    <i class="fas fa-user-cog me-2"></i> Profil Saya
                </a> --}}
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

<script>
    // Real-time clock function
    function updateClock() {
        const now = new Date();

        // Format time
        const timeOptions = {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        };
        const timeString = now.toLocaleTimeString('id-ID', timeOptions);

        // Format date
        const dateOptions = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        const dateString = now.toLocaleDateString('id-ID', dateOptions);

        // Update DOM
        const currentTimeElement = document.getElementById('currentTime');
        const currentDateElement = document.getElementById('currentDate');

        if (currentTimeElement) {
            currentTimeElement.textContent = timeString;
        }
        if (currentDateElement) {
            currentDateElement.textContent = dateString;
        }

        // Show clock display on desktop
        const clockDisplay = document.querySelector('.clock-display');
        if (window.innerWidth > 768 && clockDisplay) {
            clockDisplay.style.display = 'block';
        } else if (clockDisplay) {
            clockDisplay.style.display = 'none';
        }
    }

    // Update clock every second
    setInterval(updateClock, 1000);

    // Initial call
    updateClock();

    // Handle window resize
    window.addEventListener('resize', updateClock);
</script>