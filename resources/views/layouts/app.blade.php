<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMS KISAH Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --bs-body-bg: #0f172a; 
            --bs-body-color: #cbd5e1; 
            --bs-emphasis-color: #ffffff;
            --bs-secondary-color: #94a3b8; 
            --bs-secondary-bg: #1e293b; 
            --bs-tertiary-color: #94a3b8; 
            --bs-tertiary-bg: #1e293b; 
            --bs-border-color: #334155; 
            --bs-red: #ef4444;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            overflow-x: hidden;
        }

        /* SIDEBAR EMS KISAH STYLE (DARK THEME) */
        #sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #1e293b; 
            border-right: 1px solid #334155; 
            z-index: 1050;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease-in-out;
        }

        .sidebar-brand {
            padding: 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #334155; 
            color: white;
            text-decoration: none;
        }

        .sidebar-brand .logo-icon {
            background-color: var(--bs-red);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        .sidebar-menu {
            padding: 15px 0;
            flex-grow: 1;
            overflow-y: auto;
        }

        .menu-label {
            padding: 10px 20px;
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--bs-red);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .nav-link {
            color: #94a3b8; 
            padding: 12px 20px;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border-radius: 0;
        }

        .nav-link i {
            width: 25px;
            font-size: 1.1rem;
        }

        .nav-link:hover {
            color: #ffffff;
            background-color: rgba(239, 68, 68, 0.1);
        }

        .nav-link.active {
            color: #ffffff;
            background-color: rgba(239, 68, 68, 0.2);
            border-left: 4px solid var(--bs-red);
        }
        
        .nav-link.text-white-50 {
            color: #64748b !important; 
        }
        .nav-link.text-white-50:hover {
            color: #94a3b8 !important; 
        }

        /* MAIN CONTENT AREA */
        #main-content {
            margin-left: 260px;
            min-height: 100vh;
            width: calc(100% - 260px);
            transition: all 0.3s ease-in-out;
            padding-top: 1rem;
        }

        /* TOMBOL TOGGLE MOBILE (Hidden di Desktop) */
        #mobile-nav-toggle {
            display: none;
            background-color: #1e293b;
            color: white;
            border: 1px solid #334155;
            padding: 10px 15px;
            border-radius: 8px;
            margin: 10px 15px;
            cursor: pointer;
            z-index: 1040;
            width: fit-content;
        }

        /* OVERLAY BACKDROP UNTUK MOBILE */
        #sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(3px);
            z-index: 1040;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        /* RESPONSIVE MEDIA QUERIES (Untuk layar < 992px) */
        @media (max-width: 991.98px) {
            #sidebar {
                left: -260px; /* Sembunyikan ke kiri saat layar kecil */
            }
            
            #sidebar.active {
                left: 0; /* Tampilkan saat ditoggle */
                box-shadow: 5px 0 15px rgba(0,0,0,0.5);
            }

            #main-content {
                margin-left: 0;
                width: 100%;
                padding-top: 0;
            }

            #mobile-nav-toggle {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            #sidebar-overlay.active {
                display: block;
                opacity: 1;
            }
            
            /* Sembunyikan close button di dalam sidebar jika di desktop, munculkan di mobile */
            .sidebar-close-btn {
                display: block !important;
            }
        }

        .sidebar-close-btn {
            display: none;
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 1.2rem;
            cursor: pointer;
            margin-left: auto;
        }
        .sidebar-close-btn:hover { color: white; }

        /* BOOTSTRAP OVERRIDES */
        .card {
            background-color: #1e293b; 
            border: 1px solid #334155; 
        }
        .card-header, .card-footer {
            background-color: #283447; 
            border-bottom-color: #334155;
            border-top-color: #334155;
        }
        
        .table {
            --bs-table-bg: transparent;
            --bs-table-border-color: #334155; 
            --bs-table-striped-color: #cbd5e1;
            --bs-table-striped-bg: rgba(255, 255, 255, 0.03);
            --bs-table-active-color: #ffffff;
            --bs-table-active-bg: rgba(255, 255, 255, 0.05);
            --bs-table-hover-color: #ffffff;
            --bs-table-hover-bg: rgba(255, 255, 255, 0.05);
            color: var(--bs-body-color);
        }
        .table-light {
            --bs-table-bg: #334155; 
            --bs-table-color: #cbd5e1; 
            border-color: #475569;
        }
        
        .text-dark, .fw-bolder.text-dark { color: #ffffff !important; }
        .text-muted { color: #94a3b8 !important; }
        
        .form-control, .form-select {
            background-color: #334155; 
            color: #ffffff;
            border-color: #475569; 
        }
        .form-control:focus, .form-select:focus {
            background-color: #334155;
            color: #ffffff;
            border-color: var(--bs-red);
            box-shadow: 0 0 0 0.25rem rgba(239, 68, 68, 0.25);
        }
        .form-control::placeholder { color: #64748b; }

        .modal-content {
            background-color: #1e293b; 
            border: 1px solid #334155; 
        }
        .modal-header, .modal-footer {
            border-bottom-color: #334155;
            border-top-color: #334155;
        }
        .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

        .alert-success { background-color: #14532d; color: #a3e635; border-color: #166534; }
        .alert-danger { background-color: #7f1d1d; color: #fca5a5; border-color: #991b1b; }
        .alert-warning { background-color: #78350f; color: #fdba74; border-color: #9a3412; }

        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #1e293b; }
        ::-webkit-scrollbar-thumb { background: #475569; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }
    </style>
</head>
<body>

    <div id="sidebar-overlay"></div>

    <nav id="sidebar">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-white text-decoration-none w-100">
                <div class="logo-icon"><i class="fa-solid fa-shield-heart"></i></div>
                <div>
                    <h5 class="m-0 fw-bolder" style="letter-spacing: 1px;">EMS KISAH</h5>
                </div>
            </a>
            <button class="sidebar-close-btn" id="close-sidebar-btn"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div class="sidebar-menu">
            <div class="menu-label">Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-border-all"></i> Dashboard
            </a>
            <a href="{{ route('admin.tenaga_medis.index') }}" class="nav-link {{ request()->routeIs('admin.tenaga_medis.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i> Data Anggota
            </a>
            <a href="{{ route('admin.absensi.index') }}" class="nav-link {{ request()->routeIs('admin.absensi.*') ? 'active' : '' }}">
                <i class="fa-regular fa-file-lines"></i> Absensi Duty
            </a>
            <a href="{{ route('admin.loket.index') }}" class="nav-link {{ request()->routeIs('admin.loket.*') ? 'active' : '' }}">
                <i class="fa-solid fa-laptop-medical"></i> Loket Pelayanan
            </a>
            <a href="{{ route('admin.dokumentasi.index') }}" class="nav-link {{ request()->routeIs('admin.dokumentasi.*') ? 'active' : '' }}">
                <i class="fa-solid fa-photo-film"></i> Dokumentasi
            </a>
            
            <div class="menu-label mt-3">Pengelolaan Khusus</div>
            <a href="{{ route('admin.jabatan.index') }}" class="nav-link {{ request()->routeIs('admin.jabatan.*') ? 'active' : '' }}">
                <i class="fa-solid fa-sitemap"></i> Struktur Jabatan
            </a>
            <a href="{{ route('admin.gaji.index') }}" class="nav-link {{ request()->routeIs('admin.gaji.*') ? 'active' : '' }}">
                <i class="fa-solid fa-money-check-dollar"></i> Hitung Gaji
            </a>
            <a href="{{ route('admin.pendaftaran.index') }}" class="nav-link {{ request()->routeIs('admin.pendaftaran.*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-circle-plus"></i> Pendaftaran EMS
            </a>
        </div>

        <div class="p-3 border-top" style="border-color: #334155 !important;">
            <button type="button" class="btn btn-danger w-100 fw-bold btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal">
                <i class="fa-solid fa-sign-out-alt me-2"></i>Logout
            </button>
            <div class="text-center mt-3" style="font-size: 0.65rem; font-style: italic; color: #64748b;">
                "We Care, We Heal,<br>We're EMS KISAH."
            </div>
        </div>
    </nav>

    <main id="main-content">
        
        <button id="mobile-nav-toggle" class="shadow-sm">
            <i class="fa-solid fa-bars"></i> <span class="fw-bold small">Menu EMS</span>
        </button>
        
        <div class="container-fluid px-3 px-md-4 pt-2 pb-0">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i><strong>Sukses!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i><strong>Peringatan!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-xmark me-2"></i><strong>Gagal memproses data:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
        </div>

        @yield('content')
        
    </main>

    <!-- MODAL KONFIRMASI LOGOUT -->
    <div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-labelledby="logoutConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-dark text-white border-0 py-3">
                    <h6 class="modal-title fw-bold" id="logoutConfirmModalLabel"><i class="fa-solid fa-right-from-bracket text-danger me-2"></i>Konfirmasi Logout</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <p class="text-white mb-0">Anda yakin ingin mengakhiri sesi admin ini?</p>
                </div>
                <div class="modal-footer border-0 p-2">
                    <button type="button" class="btn btn-secondary fw-bold btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="confirm-logout-button" class="btn btn-danger fw-bold btn-sm px-4">Ya, Logout</button>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM LOGOUT TERSEMBUNYI -->
    <form id="logout-form" action="{{ route('petinggi.logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // --- Sidebar Mobile Toggle Logic ---
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('mobile-nav-toggle');
        const closeBtn = document.getElementById('close-sidebar-btn');
        const overlay = document.getElementById('sidebar-overlay');

        function openSidebar() {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Cegah scroll saat menu terbuka
        }

        function closeSidebar() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = ''; 
        }

        toggleBtn.addEventListener('click', openSidebar);
        closeBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);

        // --- Logout Confirmation Logic ---
        const confirmLogoutBtn = document.getElementById('confirm-logout-button');
        if (confirmLogoutBtn) {
            confirmLogoutBtn.addEventListener('click', function() {
                document.getElementById('logout-form').submit();
            });
        }


        // --- Chart.js Dark Theme Configuration ---
        if (typeof Chart !== 'undefined') {
            Chart.defaults.color = '#94a3b8'; // slate-400
            Chart.defaults.borderColor = '#334155'; // slate-700
            
            Chart.defaults.plugins.tooltip.backgroundColor = '#0f172a'; // slate-900
            Chart.defaults.plugins.tooltip.titleColor = '#ffffff';
            Chart.defaults.plugins.tooltip.bodyColor = '#cbd5e1'; // slate-300
            Chart.defaults.plugins.tooltip.borderColor = '#ef4444'; // red-500
            Chart.defaults.plugins.tooltip.borderWidth = 1;

            Chart.defaults.scale.grid.color = '#334155'; // slate-700
            Chart.defaults.scale.ticks.color = '#94a3b8'; // slate-400
        }
    });
    </script>

</body>
</html>