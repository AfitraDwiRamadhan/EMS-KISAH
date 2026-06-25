<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Publik - EMS KISAH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* Smooth scrolling untuk navigasi anchor link (#) */
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="text-white antialiased flex flex-col min-h-screen">

    <header class="bg-slate-900 text-white shadow-xl shadow-red-500/10 sticky top-0 z-50 border-b-2 border-red-600">
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
                <div class="flex items-center gap-4">
                    <a href="{{ route('public.home') }}#pendaftaran" class="hidden sm:inline-flex items-center rounded-lg bg-red-600 px-5 py-2 text-sm font-bold text-white hover:bg-red-700 transition shadow-lg shadow-red-500/20">
                        <i class="fa-solid fa-file-signature mr-2"></i> Daftar EMS
                    </a>
                    <button type="button" id="open-login-modal-header" class="hidden sm:inline-flex items-center rounded-lg border-2 border-slate-700 bg-slate-800/50 px-5 py-2 text-sm font-bold text-white hover:bg-slate-700 hover:border-slate-500 transition">
                        Login
                    </button>
                    <button type="button" id="open-login-modal-header-mobile" class="text-slate-500 hover:text-red-600 transition sm:hidden">
                        <i class="fa-solid fa-lock text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-slate-900 border-t-2 border-red-600 mt-auto py-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm font-medium text-slate-400">
            &copy; {{ date('Y') }} EMS KISAH. Hak Cipta Dilindungi.
        </div>
    </footer>

</body>
</html>
