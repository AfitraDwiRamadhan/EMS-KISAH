<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Publik - EMS KISAH</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* Smooth scrolling untuk navigasi anchor link (#) */
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="text-white antialiased flex flex-col min-h-screen bg-slate-950">

    <header class="bg-slate-900/90 backdrop-blur-md text-white shadow-xl shadow-red-500/5 sticky top-0 z-50 border-b border-red-500/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="{{ route('public.home') }}" class="flex-shrink-0 flex items-center gap-3 transition hover:opacity-80">
                    <div class="w-12 h-12 bg-red-600/20 text-red-500 rounded-full flex items-center justify-center font-bold text-xl ring-2 ring-red-500/50">
                        <i class="fa-solid fa-truck-medical"></i>
                    </div>
                    <div>
                        <span class="block font-black text-2xl tracking-tighter text-white leading-tight">EMS <span class="text-red-500">KISAH</span></span>
                        <span class="block text-[10px] font-medium uppercase text-slate-400">Professional Roleplay Experience</span>
                    </div>
                </a>

                <!-- Navigasi Desktop -->
                <nav class="hidden lg:flex items-center space-x-8">
                    <a href="{{ route('public.home') }}" class="text-sm font-bold text-white/80 hover:text-red-500 transition uppercase">Home</a>
                    <a href="{{ route('public.home') }}#tenaga-medis" class="text-sm font-bold text-white/80 hover:text-red-500 transition uppercase">Tenaga Medis</a>
                    <a href="{{ route('public.home') }}#dokumentasi" class="text-sm font-bold text-white/80 hover:text-red-500 transition uppercase">Dokumentasi</a>
                    <a href="{{ route('public.home') }}#layanan-kami" class="text-sm font-bold text-white/80 hover:text-red-500 transition uppercase">Layanan</a>
                </nav>

                <!-- Tombol Aksi -->
                <div class="flex items-center gap-2 sm:gap-4">
                    <a href="{{ route('public.home') }}#pendaftaran" class="hidden sm:inline-flex items-center rounded-lg bg-red-600 px-5 py-2 text-sm font-bold text-white hover:bg-red-700 transition shadow-lg shadow-red-500/20">
                        <i class="fa-solid fa-file-signature mr-2"></i> Daftar EMS
                    </a>
                    <button type="button" id="open-login-modal-header" class="hidden sm:inline-flex items-center rounded-lg border-2 border-slate-700 bg-slate-800/50 px-5 py-2 text-sm font-bold text-white hover:bg-slate-700 hover:border-slate-500 transition">
                        Login Petinggi
                    </button>
                    <button type="button" id="open-login-modal-header-mobile" class="text-slate-400 hover:text-red-500 transition sm:hidden p-2">
                        <i class="fa-solid fa-lock text-xl"></i>
                    </button>
                    
                    <!-- Hamburger button mobile -->
                    <button type="button" id="mobile-menu-toggle" class="text-slate-400 hover:text-white transition lg:hidden p-2">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu (Hidden by default) -->
        <div id="mobile-menu" class="hidden lg:hidden border-t border-slate-800 bg-slate-900 px-4 py-4 space-y-3 transition duration-300">
            <a href="{{ route('public.home') }}" class="block text-base font-bold text-slate-300 hover:text-red-500 transition py-2 border-b border-slate-800">Home</a>
            <a href="{{ route('public.home') }}#tenaga-medis" class="block text-base font-bold text-slate-300 hover:text-red-500 transition py-2 border-b border-slate-800">Tenaga Medis</a>
            <a href="{{ route('public.home') }}#dokumentasi" class="block text-base font-bold text-slate-300 hover:text-red-500 transition py-2 border-b border-slate-800">Dokumentasi</a>
            <a href="{{ route('public.home') }}#layanan-kami" class="block text-base font-bold text-slate-300 hover:text-red-500 transition py-2 border-b border-slate-800">Layanan</a>
            <a href="{{ route('public.home') }}#pendaftaran" class="block text-center rounded-lg bg-red-600 py-3 text-base font-bold text-white hover:bg-red-700 transition shadow-lg">
                <i class="fa-solid fa-file-signature mr-2"></i> Daftar EMS
            </a>
        </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-slate-950 border-t border-slate-800/80 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm font-medium text-slate-500">
            &copy; {{ date('Y') }} EMS KISAH. Hak Cipta Dilindungi.
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('mobile-menu-toggle');
            const menu = document.getElementById('mobile-menu');
            
            if(toggle && menu) {
                toggle.addEventListener('click', function() {
                    menu.classList.toggle('hidden');
                    const icon = toggle.querySelector('i');
                    if (menu.classList.contains('hidden')) {
                        icon.className = 'fa-solid fa-bars text-xl';
                    } else {
                        icon.className = 'fa-solid fa-xmark text-xl';
                    }
                });
                
                // Tutup menu saat link diklik
                const links = menu.querySelectorAll('a');
                links.forEach(link => {
                    link.addEventListener('click', function() {
                        menu.classList.add('hidden');
                        toggle.querySelector('i').className = 'fa-solid fa-bars text-xl';
                    });
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
