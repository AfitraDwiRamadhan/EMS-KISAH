@extends('layouts.public')

@section('content')
@php
    // --- Data Persiapan ---
    $heroImage = $latestDokumentasi->first()?->foto ? asset('storage/' . $latestDokumentasi->first()->foto) : null;
    $jabatanFilters = $tenagaMedis->pluck('jabatan')->filter()->unique()->values();

    // --- Helper Function untuk Deskripsi Jabatan ---
    $deskripsiJabatan = function ($jabatan) {
        $jabatanLower = strtolower($jabatan ?? '');
        if (str_contains($jabatanLower, 'head') || str_contains($jabatanLower, 'chief') || str_contains($jabatanLower, 'komando')) {
            return 'Bertanggung jawab penuh atas koordinasi, arahan lapangan, dan strategi pelayanan EMS.';
        }
        if (str_contains($jabatanLower, 'intern') || str_contains($jabatanLower, 'trainee')) {
            return 'Anggota dalam masa pembinaan yang aktif belajar prosedur medis dan kedisiplinan lapangan.';
        }
        if (str_contains($jabatanLower, 'dokter')) {
            return 'Fokus pada konsultasi kesehatan, pemeriksaan rutin, dan penanganan medis lanjutan.';
        }
        if (str_contains($jabatanLower, 'paramedic') || str_contains($jabatanLower, 'medis')) {
            return 'Ahli penanganan darurat lapangan dengan kecepatan dan ketepatan tinggi.';
        }
        return 'Siap mendukung pelayanan medis dan respon kedaruratan roleplay kota.';
    };
@endphp

<div id="parallax-container" class="fixed inset-0 pointer-events-none" style="z-index: -1;">
    <div class="absolute inset-0 bg-slate-900 z-0"></div> <img src="https://drive.google.com/uc?export=download&id=1vBemwMNyJ4N9_fafldudcPtPdOezo1rL" id="bg-img-1" class="absolute inset-0 h-full w-full object-cover transition-opacity duration-1000 ease-in-out opacity-100 z-10">
    <img src="https://drive.google.com/uc?export=download&id=1TYH3Z1Ki3c_kA3jLGoFYWYWOk1Mhs5WN" id="bg-img-2" class="absolute inset-0 h-full w-full object-cover transition-opacity duration-1000 ease-in-out opacity-0 z-10">
    <img src="https://drive.google.com/uc?export=download&id=1sizxGMf4yuiUueOQOxYCDB5ELn4o6-V8" id="bg-img-3" class="absolute inset-0 h-full w-full object-cover transition-opacity duration-1000 ease-in-out opacity-0 z-10">
    <img src="https://drive.google.com/uc?export=download&id=1VGwdJSrYZ23mWkSCq3OBRiYHjlfQ0Lvv" id="bg-img-4" class="absolute inset-0 h-full w-full object-cover transition-opacity duration-1000 ease-in-out opacity-0 z-10">
    
    <div class="absolute inset-0 bg-slate-900/80 z-20"></div>
</div>

<style>
    body { font-family: 'Inter', sans-serif; background-color: transparent; } /* Body transparan agar parallax terlihat */
    
    /* DEFINISI GAYA STRUKTUR INPUT DARK MODE UNTUK TINGKAT KETERBACAAN MAKSIMAL */
    .input-dark {
        margin-top: 0.375rem;
        display: block;
        width: 100%;
        border-radius: 0.75rem;
        border: 2px solid #334155; 
        background-color: #1e293b; 
        color: #ffffff !important; 
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        outline: none;
        transition: all 0.2s ease-in-out;
    }
    .input-dark:focus {
        border-color: #dc2626; 
        box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.2);
        background-color: #0f172a; 
    }
    .input-dark::placeholder {
        color: #94a3b8; 
        font-weight: 400;
    }
    select.input-dark option {
        background-color: #1e293b;
        color: #ffffff;
    }
    /* Pastikan section bersifat transparan agar parallax di baliknya terlihat */
    .parallax-section {
        background-color: transparent !important;
    }
</style>

<div class="relative z-10 text-white min-h-screen">

<section class="parallax-section relative overflow-hidden border-b-4 border-red-600">
    @if($heroImage)
        <div class="absolute inset-0 bg-black z-[-1]">
             <img src="{{ $heroImage }}" alt="Dokumentasi EMS KISAH" class="absolute inset-0 h-full w-full object-cover opacity-10">
        </div>
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent z-0"></div>

    <div class="relative z-10 mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
        <div class="mx-auto max-w-4xl text-center">
            <p class="text-sm font-black uppercase tracking-widest text-red-500">
                KISAH Roleplay Emergency Medical Service
            </p>

            <h1 class="mt-4 text-4xl font-black uppercase tracking-tighter text-white sm:text-6xl lg:text-7xl drop-shadow-md">
                Rekrutmen &amp; <span class="text-red-500">Layanan Publik</span>
            </h1>
            <p class="mx-auto mt-6 max-w-2xl text-base leading-7 text-slate-300 drop-shadow">
                Informasi real-time mengenai status pendaftaran, layanan medis, dokumentasi, dan daftar anggota aktif, semuanya dikelola langsung oleh tim Management EMS KISAH.
            </p>

            <div class="mt-10 flex flex-col justify-center gap-4 sm:flex-row">
                <a href="#pendaftaran" class="inline-flex items-center justify-center rounded-lg bg-red-600 px-8 py-3 text-sm font-bold text-white shadow-lg shadow-red-500/20 transition hover:bg-red-700">
                    <i class="fa-solid fa-file-signature mr-2"></i> Cek Status Rekrutmen
                </a>
                <button type="button" id="open-login-modal" class="inline-flex items-center justify-center rounded-lg border-2 border-slate-700 bg-slate-800/50 px-8 py-3 text-sm font-bold text-white transition hover:border-slate-500 hover:bg-slate-700 backdrop-blur-sm">
                    <i class="fa-solid fa-lock mr-2"></i> Login
                </button>
            </div>
        </div>
    </div>
</section>

<div class="border-y-2 border-slate-800 bg-slate-900/60 backdrop-blur-md">
    <div class="mx-auto grid max-w-7xl grid-cols-1 sm:grid-cols-3">
        <div class="flex items-center justify-center gap-4 border-b-2 sm:border-b-0 sm:border-r-2 border-slate-800/50 p-4">
            <span class="text-sm font-bold uppercase text-slate-400">Rekrutmen</span>
            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $activeBatch ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                {{ $activeBatch ? 'Dibuka' : 'Ditutup' }}
            </span>
        </div>
        <div class="flex items-center justify-center gap-4 border-b-2 sm:border-b-0 sm:border-r-2 border-slate-800/50 p-4">
            <span class="text-sm font-bold uppercase text-slate-400">Loket Layanan</span>
            <span class="font-bold text-white">{{ $loketBuka }} Buka</span>
        </div>
        <div class="flex items-center justify-center gap-4 p-4">
             <span class="text-sm font-bold uppercase text-slate-400">Anggota Aktif</span>
            <span class="font-bold text-white">{{ $tenagaMedis->count() }} Medis</span>
        </div>
    </div>
</div>

<section id="pendaftaran" class="parallax-section py-16 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        @if (session('success') || session('error') || $errors->any())
        <div class="mb-8 rounded-lg p-4 {{ session('success') ? 'bg-green-500/20 text-green-300 backdrop-blur-md' : 'bg-red-500/20 text-red-300 backdrop-blur-md' }}" role="alert">
            <div class="flex items-center gap-3">
                <i class="fa-solid {{ session('success') ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                <div class="text-sm font-bold">
                    @if (session('success'))
                        {{ session('success') }}
                    @elseif (session('error'))
                        {{ session('error') }}
                    @else
                        Terjadi kesalahan validasi pada formulir. Silakan periksa kembali isian Anda.
                    @endif
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <article class="flex flex-col rounded-2xl border-2 border-slate-800 bg-slate-900/60 backdrop-blur-md p-6 shadow-xl">
                 <div>
                    <span class="text-sm font-black uppercase tracking-widest text-red-500">Status Saat Ini</span>
                    <h2 class="mt-2 text-3xl font-black uppercase tracking-tighter text-white drop-shadow-md">Rekrutmen Anggota</h2>
                    <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300">
                        Batch pendaftaran dibuka dan ditutup secara terpusat oleh admin. Status di bawah ini adalah real-time.
                    </p>
                </div>
                <div class="mt-4 rounded-xl bg-slate-800/80 p-4 border border-slate-700/50">
                    <div class="flex items-center justify-between">
                        <div class="text-xs font-bold uppercase text-slate-400">Gelombang Aktif</div>
                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold {{ $activeBatch ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                            <i class="fa-solid fa-circle {{ $activeBatch ? 'animate-pulse' : '' }}"></i>
                            {{ $activeBatch ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>
                    <div class="mt-2 text-lg font-bold text-white truncate" title="{{ $activeBatch?->name ?? 'Belum tersedia' }}">
                        {{ $activeBatch?->name ?? 'Belum ada batch dibuka' }}
                    </div>
                </div>

                <div class="mt-auto border-t-2 border-slate-800/50 pt-6 mt-6">
                    @if($activeBatch)
                        @if($activeBatch->registration_link)
                            <p class="text-sm font-semibold text-slate-300">
                                Pendaftaran untuk batch <span class="font-bold text-white">{{ $activeBatch->name }}</span> dibuka melalui link eksternal.
                            </p>
                            <a href="{{ $activeBatch->registration_link }}" target="_blank" class="mt-4 inline-flex w-full items-center justify-center rounded-lg bg-red-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-red-500/20 transition hover:bg-red-700 sm:w-auto">
                                <i class="fa-solid fa-arrow-up-right-from-square mr-2"></i> Daftar Sekarang
                            </a>
                        @else
                             <p class="text-sm font-semibold text-slate-300">
                                Pendaftaran untuk batch <span class="font-bold text-white">{{ $activeBatch->name }}</span> dibuka melalui formulir di website ini.
                            </p>
                            <button type="button" id="open-ems-registration-modal" class="mt-4 inline-flex w-full items-center justify-center rounded-lg bg-red-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-red-500/20 transition hover:bg-red-700 sm:w-auto">
                                <i class="fa-solid fa-file-lines mr-2"></i> Buka Formulir
                            </button>
                        @endif
                    @else
                        <p class="text-sm font-semibold text-slate-400">
                           Rekrutmen saat ini sedang ditutup. Pantau terus halaman ini untuk informasi pembukaan batch selanjutnya.
                        </p>
                        <button disabled class="mt-4 inline-flex w-full cursor-not-allowed items-center justify-center rounded-lg bg-slate-800/80 px-6 py-3 text-sm font-bold text-slate-500 sm:w-auto border border-slate-700/50">
                            <i class="fa-solid fa-lock mr-2"></i> Pendaftaran Ditutup
                        </button>
                    @endif
                </div>
            </article>

            <div class="flex flex-col rounded-2xl border-2 border-slate-800 bg-slate-900/60 backdrop-blur-md p-6 shadow-xl">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter text-white drop-shadow-md">Pendaftar <span class="text-red-500">Saat Ini</span></h3>
                        <p class="mt-1 text-sm text-slate-300">Total pendaftar pada batch aktif.</p>
                    </div>
                    <div class="text-5xl font-black tracking-tighter text-white drop-shadow-lg">{{ $activeBatch ? $activeBatch->registrations->count() : 0 }}</div>
                </div>
                <div class="mt-4 flex-grow border-t-2 border-slate-800/50 min-h-0 pt-4">
                    @if($activeBatch && $activeBatch->registrations->isNotEmpty())
                        <ul class="h-full space-y-3 overflow-y-auto pr-2">
                            @foreach($activeBatch->registrations as $registration)
                            <li class="flex items-center gap-4 animate-fade-in bg-slate-800/40 p-3 rounded-xl border border-slate-700/30">
                                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-red-600 text-sm font-black text-white shadow-md">
                                    {{ strtoupper(substr($registration->nama_ic, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-white">{{ $registration->nama_ic }}</p>
                                    <p class="text-xs text-slate-400">{{ $registration->created_at->diffForHumans() }}</p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="flex h-full items-center justify-center text-center py-10">
                            <div class="text-slate-500">
                                <i class="fa-solid fa-user-plus fa-3x mb-3 opacity-50"></i>
                                <p class="text-sm font-semibold">Belum ada pendaftar pada batch ini.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section id="layanan-kami" class="parallax-section py-16 sm:py-24 bg-black/30">
    <div class="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
        <p class="text-sm font-black uppercase tracking-widest text-red-500">Our Services</p>
        <h2 class="mt-4 text-4xl font-black uppercase tracking-tighter text-white sm:text-6xl drop-shadow-md">
            Layanan Medis <span class="text-red-500">Publik</span>
        </h2>
        <p class="mx-auto mt-6 max-w-2xl text-base leading-7 text-slate-300 drop-shadow">
            Daftar layanan medis yang tersedia untuk publik. Klik pada layanan untuk melihat detail dan membuka formulir pendaftaran.
        </p>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($layanans as $layanan)
                <div class="layanan-card group cursor-pointer overflow-hidden rounded-xl border-2 border-slate-800 bg-slate-900/70 backdrop-blur-md text-left transition duration-300 hover:-translate-y-1 hover:border-red-600 hover:bg-slate-800/90 hover:shadow-xl hover:shadow-red-900/20 flex flex-col h-full"
                    data-nama="{{ $layanan->nama_layanan }}"
                    data-deskripsi="{{ $layanan->deskripsi }}"
                    data-status="{{ $layanan->status_loket }}">
                    <div class="p-6 flex-grow">
                        <div class="flex items-start justify-between gap-4">
                            <h3 class="text-xl font-bold text-white drop-shadow-sm">{{ $layanan->nama_layanan }}</h3>
                             <span class="mt-1 inline-flex shrink-0 items-center rounded-full px-3 py-1 text-xs font-bold shadow-inner {{ $layanan->status_loket == 'Buka' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' }}">
                                {{ $layanan->status_loket }}
                            </span>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-slate-300">{{ \Illuminate\Support\Str::limit($layanan->deskripsi, 100) }}</p>
                    </div>
                    <div class="border-t-2 border-slate-800/60 bg-slate-900/60 px-6 py-4 text-xs font-bold text-red-500 transition group-hover:bg-red-600 group-hover:text-white">
                        Buka Formulir <i class="fa-solid fa-arrow-right ml-1"></i>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-xl border-2 border-dashed border-slate-700 bg-slate-900/50 backdrop-blur-md p-10 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-800 text-red-500">
                        <i class="fa-solid fa-pills text-xl"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-white">Belum ada layanan tersedia</h3>
                    <p class="mt-2 text-sm text-slate-400">Admin belum menambahkan layanan publik.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section id="tenaga-medis" class="parallax-section py-16 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
        <p class="text-sm font-black uppercase tracking-widest text-red-500">The Lifesavers</p>
        <h2 class="mt-4 text-4xl font-black uppercase tracking-tighter text-white sm:text-6xl drop-shadow-md">
            Tenaga Medis <span class="text-red-500">Profesional</span>
        </h2>
        <p class="mx-auto mt-6 max-w-2xl text-base leading-7 text-slate-300 drop-shadow">
            Mengenal lebih dekat para pahlawan medis EMS KISAH yang berdedikasi tinggi.
        </p>

        <div class="mx-auto mt-10 flex max-w-fit flex-wrap justify-center gap-2 rounded-xl border-2 border-slate-800/80 bg-slate-900/60 backdrop-blur-md p-2 shadow-lg">
            <button type="button" data-filter="all" class="team-filter rounded-lg bg-red-600 px-5 py-2 text-sm font-bold text-white shadow-md">All Team</button>
            @foreach($jabatanFilters as $jabatan)
                <button type="button" data-filter="{{ \Illuminate\Support\Str::slug($jabatan) }}" class="team-filter rounded-lg bg-slate-800/80 px-5 py-2 text-sm font-bold text-slate-300 transition hover:bg-slate-700 border border-transparent hover:border-slate-600">
                    {{ $jabatan }}
                </button>
            @endforeach
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($tenagaMedis as $medis)
                @php
                    $initials = collect(explode(' ', trim($medis->nama)))->filter()->take(2)->map(fn ($word) => strtoupper(substr($word, 0, 1)))->implode('');
                    $roleSlug = \Illuminate\Support\Str::slug($medis->jabatan);
                    $isCommand = str_contains(strtolower($medis->jabatan ?? ''), 'head') || str_contains(strtolower($medis->jabatan ?? ''), 'chief') || str_contains(strtolower($medis->jabatan ?? ''), 'komando');
                @endphp

                <article class="team-card flex flex-col items-center rounded-xl border-2 border-slate-800 bg-slate-900/70 backdrop-blur-md p-6 text-center transition duration-300 hover:border-red-500/50 hover:bg-slate-800 hover:shadow-xl hover:shadow-red-900/20" data-role="{{ $roleSlug }}">
                    <div class="relative">
                        <div class="relative flex h-24 w-24 items-center justify-center rounded-full bg-slate-800 text-3xl font-black text-white ring-4 ring-slate-700 shadow-inner">
                            {{ $initials ?: 'EM' }}
                        </div>
                        @if($isCommand)
                            <div class="absolute -bottom-2 -right-2 flex h-8 w-8 items-center justify-center rounded-full bg-red-600 text-white ring-4 ring-slate-900 shadow-md">
                                <i class="fa-solid fa-star text-xs"></i>
                            </div>
                        @endif
                    </div>

                    <span class="mt-6 rounded-full bg-red-500/20 border border-red-500/30 px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-red-400">
                        {{ $medis->jabatan }}
                    </span>

                    <h3 class="mt-3 text-xl font-bold text-white drop-shadow-sm">{{ $medis->nama }}</h3>
                    <p class="mt-2 text-center text-xs leading-6 text-slate-300">
                        {{ $deskripsiJabatan($medis->jabatan) }}
                    </p>
                </article>
            @empty
                <div class="col-span-full rounded-xl border-2 border-dashed border-slate-700 bg-slate-900/50 backdrop-blur-md p-10 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-800 text-red-500">
                        <i class="fa-solid fa-user-doctor text-xl"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-white">Belum ada anggota tenaga medis</h3>
                    <p class="mt-2 text-sm text-slate-400">Data akan tampil otomatis setelah admin menambahkan anggota aktif.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section id="dokumentasi" class="parallax-section py-16 sm:py-24 bg-black/40">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-10 text-center">
            <p class="text-sm font-black uppercase tracking-widest text-red-500">Gallery</p>
            <h2 class="mt-4 text-4xl font-black uppercase tracking-tighter text-white sm:text-6xl drop-shadow-md">Jejak <span class="text-red-500">Pengabdian</span></h2>
            <p class="mx-auto mt-6 max-w-2xl text-base leading-7 text-slate-300 drop-shadow">Dokumentasi terbaru dari kegiatan dan pelayanan EMS KISAH.</p>
        </div>

        @if($latestDokumentasi->isNotEmpty())
            <div class="grid gap-6 md:grid-cols-3">
                @foreach($latestDokumentasi as $item)
                    <article class="group overflow-hidden rounded-xl border-2 border-slate-800 bg-slate-900/80 backdrop-blur-md transition hover:border-red-600 hover:shadow-xl hover:shadow-red-900/20">
                        <div class="aspect-[4/3] overflow-hidden bg-slate-950">
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-110 group-hover:opacity-80">
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between gap-3">
                                <span class="rounded-full bg-red-500/20 border border-red-500/30 px-3 py-1 text-xs font-bold text-red-400">{{ $item->kategori }}</span>
                                <span class="text-xs font-semibold text-slate-400">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</span>
                            </div>
                            <h3 class="mt-4 text-lg font-bold text-white drop-shadow-sm">{{ $item->judul }}</h3>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="rounded-xl border-2 border-dashed border-slate-700 bg-slate-900/50 backdrop-blur-md p-10 text-center">
                 <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-800 text-red-500">
                    <i class="fa-solid fa-photo-film text-xl"></i>
                </div>
                <h3 class="mt-4 text-lg font-bold text-white">Belum ada dokumentasi publik</h3>
                <p class="mt-2 text-sm text-slate-400">Foto kegiatan akan tampil otomatis setelah admin mengunggahnya.</p>
            </div>
        @endif
    </div>
</section>

</div> <div id="ems-registration-modal" class="fixed inset-0 z-[100] hidden bg-black/90 backdrop-blur-md p-4 overflow-y-auto" aria-labelledby="ems-modal-title" role="dialog" aria-modal="true">
    <div class="min-h-full w-full flex items-center justify-center py-10">
        <div id="ems-registration-modal-content" class="w-full max-w-2xl transform scale-95 rounded-2xl bg-slate-900 border-2 border-slate-700 shadow-2xl shadow-red-500/20 transition-all duration-300 flex flex-col">
            <form id="ems-registration-form" action="{{ route('public.registration.store') }}" method="POST">
                @csrf
                <div class="p-6">
                    <div class="flex items-start justify-between border-b-2 border-slate-800 pb-4">
                        <div>
                            <h3 id="ems-modal-title" class="text-2xl font-black uppercase tracking-tight text-white">Formulir Pendaftaran <span class="text-red-500">EMS</span></h3>
                            <p class="mt-1 text-sm font-medium text-slate-400">Batch: {{ $activeBatch?->name ?? 'Tidak Aktif' }}</p>
                        </div>
                        <button id="ems-modal-close" type="button" class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-800 text-slate-400 transition hover:bg-red-600 hover:text-white">
                            <i class="fa-solid fa-xmark fa-lg"></i>
                        </button>
                    </div>
                </div>

                <div class="space-y-5 px-6 pb-6">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="nama_ic" class="block text-xs font-black uppercase tracking-wider text-slate-300">1. Nama Lengkap IC</label>
                            <input type="text" name="nama_ic" id="nama_ic" value="{{ old('nama_ic') }}" required class="input-dark" placeholder="Contoh: Kim Kyun-Mo">
                        </div>
                        <div class="sm:col-span-3">
                            <label for="jenis_kelamin" class="block text-xs font-black uppercase tracking-wider text-slate-300">4. Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" required class="input-dark">
                                <option value="">Pilih...</option>
                                <option value="Laki-laki" @if(old('jenis_kelamin') == 'Laki-laki') selected @endif>Laki-laki</option>
                                <option value="Perempuan" @if(old('jenis_kelamin') == 'Perempuan') selected @endif>Perempuan</option>
                            </select>
                        </div>
                         <div class="sm:col-span-3">
                            <label for="umur_ic" class="block text-xs font-black uppercase tracking-wider text-slate-300">2. Umur IC</label>
                            <input type="number" name="umur_ic" id="umur_ic" value="{{ old('umur_ic') }}" required class="input-dark" placeholder="Min. 18">
                        </div>
                        <div class="sm:col-span-3">
                            <label for="umur_ooc" class="block text-xs font-black uppercase tracking-wider text-slate-300">3. Umur OOC</label>
                            <input type="number" name="umur_ooc" id="umur_ooc" value="{{ old('umur_ooc') }}" required class="input-dark" placeholder="Min. 16">
                        </div>
                        <div class="sm:col-span-3">
                            <label for="roblox" class="block text-xs font-black uppercase tracking-wider text-slate-300">5. Username Roblox</label>
                            <input type="text" name="roblox" id="roblox" value="{{ old('roblox') }}" required class="input-dark" placeholder="Contoh: KumoHara">
                        </div>
                        <div class="sm:col-span-3">
                            <label for="discord" class="block text-xs font-black uppercase tracking-wider text-slate-300">6. Username Discord</label>
                            <input type="text" name="discord" id="discord" value="{{ old('discord') }}" required class="input-dark" placeholder="Contoh: kogahara09">
                        </div>
                         <div class="sm:col-span-6">
                            <label for="jam_aktif" class="block text-xs font-black uppercase tracking-wider text-slate-300">7. Jam Hack / Aktif Duty</label>
                            <input type="text" name="jam_aktif" id="jam_aktif" value="{{ old('jam_aktif') }}" required class="input-dark" placeholder="Contoh: 16:00 WIB - 22:00 WIB">
                        </div>
                        <div class="sm:col-span-6">
                            <label for="pengalaman" class="block text-xs font-black uppercase tracking-wider text-slate-300">8. Pengalaman di Bidang Medis</label>
                            <textarea name="pengalaman" id="pengalaman" rows="4" required class="input-dark" placeholder="Jelaskan pengalaman medis Anda sebelumnya... (Isi 'Tidak ada' jika pemula)">{{ old('pengalaman') }}</textarea>
                        </div>
                        <div class="sm:col-span-6">
                            <label for="visi_misi" class="block text-xs font-black uppercase tracking-wider text-slate-300">9. Visi &amp; Misi Bergabung</label>
                            <textarea name="visi_misi" id="visi_misi" rows="5" required class="input-dark" placeholder="Jelaskan visi misi kontribusi Anda secara jelas untuk kemajuan EMS.">{{ old('visi_misi') }}</textarea>
                        </div>
                        <div class="sm:col-span-6 mt-2">
                            <div class="relative flex items-start rounded-xl bg-slate-800/40 p-4 border border-slate-700/50">
                                <div class="flex h-6 items-center">
                                    <input id="pernyataan" name="pernyataan" type="checkbox" value="1" required class="h-5 w-5 rounded border-slate-600 bg-slate-700 text-red-600 focus:ring-red-600 cursor-pointer">
                                </div>
                                <div class="ml-3 text-sm leading-6">
                                    <label for="pernyataan" class="font-black uppercase tracking-wide text-white text-xs cursor-pointer">Pakta Integritas Anggota</label>
                                    <p class="text-xs text-slate-400 mt-1">Saya menyatakan siap mengikuti program pelatihan, patuh pada rantai komando, serta menjalankan aktivitas operasional medis dengan komitmen tinggi.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 bg-slate-950/60 p-6 rounded-b-2xl border-t border-slate-800">
                    <button id="ems-modal-cancel" type="button" class="rounded-lg bg-slate-800 px-6 py-3 text-sm font-bold text-slate-300 transition hover:bg-slate-700">
                        Batal
                    </button>
                    <button type="submit" class="rounded-lg bg-red-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-red-600/20 transition hover:bg-red-700">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Form Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL LOGIN -->
<div id="login-modal" class="fixed inset-0 z-[100] hidden bg-black/90 backdrop-blur-md p-4 overflow-y-auto" aria-labelledby="login-modal-title" role="dialog" aria-modal="true">
    <div class="min-h-full w-full flex items-center justify-center py-10">
        <div id="login-modal-content" class="w-full max-w-md transform scale-95 rounded-2xl bg-slate-900 border-2 border-slate-700 shadow-2xl shadow-red-500/20 transition-all duration-300 flex flex-col">
            <form id="login-form" action="{{ route('petinggi.login.submit') }}" method="POST">
                @csrf
                <div class="p-6">
                    <div class="flex items-start justify-between border-b-2 border-slate-800 pb-4">
                        <div>
                            <h3 id="login-modal-title" class="text-2xl font-black uppercase tracking-tight text-white">Login <span class="text-red-500">Petinggi</span></h3>
                            <p class="mt-1 text-sm font-medium text-slate-400">Akses panel admin EMS.</p>
                        </div>
                        <button id="login-modal-close" type="button" class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-800 text-slate-400 transition hover:bg-red-600 hover:text-white">
                            <i class="fa-solid fa-xmark fa-lg"></i>
                        </button>
                    </div>
                </div>

                @if ($errors->has('username'))
                <div class="px-6 pb-2">
                    <div class="bg-red-500/20 text-red-400 text-sm font-bold p-3 rounded-lg flex items-center gap-2">
                        <i class="fa-solid fa-circle-xmark"></i>
                        <span>{{ $errors->first('username') }}</span>
                    </div>
                </div>
                @endif

                <div class="space-y-5 px-6 pb-6">
                    <div>
                        <label for="username" class="block text-xs font-black uppercase tracking-wider text-slate-300">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required class="input-dark" placeholder="Username petinggi">
                    </div>
                    <div>
                        <label for="password" class="block text-xs font-black uppercase tracking-wider text-slate-300">Password</label>
                        <input type="password" name="password" id="password" required class="input-dark" placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 bg-slate-950/60 p-6 rounded-b-2xl border-t border-slate-800 mt-2">
                    <button type="submit" class="w-full rounded-lg bg-red-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-red-600/20 transition hover:bg-red-700">
                        <i class="fa-solid fa-right-to-bracket mr-2"></i> Login
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="layanan-modal" class="fixed inset-0 z-[100] hidden bg-black/90 backdrop-blur-md p-4 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="min-h-full w-full flex items-center justify-center py-10">
        <div id="layanan-modal-content" class="w-full max-w-xl transform scale-95 rounded-2xl bg-slate-900 border-2 border-slate-700 p-6 shadow-2xl shadow-red-500/20 transition-all duration-300">
            <div class="flex items-start justify-between border-b-2 border-slate-800 pb-4">
                <h3 id="modal-title" class="text-2xl font-black uppercase text-white">Formulir Layanan: <span class="text-red-500"></span></h3>
                <button id="modal-close" type="button" class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-800 text-slate-400 transition hover:bg-red-600 hover:text-white">
                    <i class="fa-solid fa-xmark fa-lg"></i>
                </button>
            </div>
            <div class="mt-4 pt-2">
                <div id="modal-form-area"></div>
            </div>
            <div class="mt-6 pt-4 border-t-2 border-slate-800 flex justify-end">
                <button type="submit" form="dynamic-form" class="w-full rounded-lg bg-red-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-red-600/20 transition hover:bg-red-700">
                    <i class="fa-solid fa-circle-check mr-2"></i> Ambil Nomor Antrean Layanan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // --- Script untuk Team Filter ---
        const filterButtons = document.querySelectorAll('.team-filter');
        const cards = document.querySelectorAll('.team-card');
        if(filterButtons.length) {
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const filter = button.dataset.filter;
                    filterButtons.forEach(item => {
                        item.classList.remove('bg-red-600', 'text-white', 'shadow-md');
                        item.classList.add('bg-slate-800/80', 'text-slate-300');
                    });
                    button.classList.add('bg-red-600', 'text-white', 'shadow-md');
                    button.classList.remove('bg-slate-800/80', 'text-slate-300');
                    cards.forEach(card => {
                        card.classList.toggle('hidden', filter !== 'all' && card.dataset.role !== filter);
                    });
                });
            });
        }

        // --- Script untuk Service Modal ---
        const layananModal = document.getElementById('layanan-modal');
        if (layananModal) {
            const layananCards = document.querySelectorAll('.layanan-card');
            const modalContent = document.getElementById('layanan-modal-content');
            const modalTitle = document.getElementById('modal-title');
            const modalFormArea = document.getElementById('modal-form-area');
            const modalClose = document.getElementById('modal-close');
            
            const formTemplates = {
                'mcu': `
                    <form id="dynamic-form" class="space-y-4">
                        <p class="text-xs font-black tracking-widest text-red-500 uppercase">PENDAFTARAN MCU (CEK KESEHATAN)</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Nama Pasien</label>
                                <input type="text" name="nama_pasien" class="input-dark" placeholder="Zhang Max" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Usia Pasien</label>
                                <input type="text" name="usia" class="input-dark" placeholder="18 Tahun" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Jenis Kelamin</label>
                                <input type="text" name="jenis_kelamin" class="input-dark" placeholder="Laki Laki" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Pekerjaan</label>
                                <input type="text" name="pekerjaan" class="input-dark" placeholder="Head ATZ" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">Alamat Rumah</label>
                                <input type="text" name="alamat" class="input-dark" placeholder="Beijing, China" required>
                            </div>
                             <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Tinggi Badan</label>
                                <input type="text" name="tinggi_badan" class="input-dark" placeholder="185cm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Berat Badan</label>
                                <input type="text" name="berat_badan" class="input-dark" placeholder="60" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Golongan Darah</label>
                                <input type="text" name="golongan_darah" class="input-dark" placeholder="B" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Keperluan Cek</label>
                                <input type="text" name="keperluan" class="input-dark" placeholder="Pengecekan Kesehatan" required>
                            </div>
                        </div>
                    </form>
                `,
                'operasi': `
                    <form id="dynamic-form" class="space-y-4">
                        <p class="text-xs font-black tracking-widest text-red-500 uppercase">Format Formulir Tindakan Operasi</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Nama Pasien</label>
                                <input type="text" name="nama_pasien" class="input-dark" placeholder="Odoy Junior" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Umur</label>
                                <input type="text" name="umur" class="input-dark" placeholder="21" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Jenis Kelamin</label>
                                <input type="text" name="jenis_kelamin" class="input-dark" placeholder="Pria" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Pekerjaan</label>
                                <input type="text" name="pekerjaan" class="input-dark" placeholder="Resto KR" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">Keterangan Kondisi Medis</label>
                                <textarea name="keterangan" rows="3" class="input-dark" placeholder="Patah tulang di bagian kaki..." required></textarea>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">CK / Non CK</label>
                                <input type="text" name="ck_non_ck" class="input-dark" placeholder="Non CK" required>
                            </div>
                        </div>
                    </form>
                `,
                'dna': `
                    <form id="dynamic-form" class="space-y-4">
                        <p class="text-xs font-black tracking-widest text-red-500 uppercase">Format Uji Laboratorium Uji DNA</p>
                         <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Nama Pasien</label>
                                <input type="text" name="nama_pasien" class="input-dark" placeholder="Nama lengkap" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Umur</label>
                                <input type="text" name="umur" class="input-dark" placeholder="Usia" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">Jenis Kelamin</label>
                                <input type="text" name="jenis_kelamin" class="input-dark" placeholder="Pria/Wanita" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">Tujuan Pemeriksaan</label>
                                <input type="text" name="tujuan_pemeriksaan" class="input-dark" placeholder="Tujuan uji keturunan" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">Dugaan Hubungan Keluarga</label>
                                <input type="text" name="dugaan_hubungan" class="input-dark" placeholder="Anak, Ayah, Ibu, dll." required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">Penanggung Jawab Pasien</label>
                                <input type="text" name="penanggung_jawab" class="input-dark" placeholder="Nama wali" required>
                            </div>
                        </div>
                    </form>
                `,
                'usg': `
                    <form id="dynamic-form" class="space-y-4">
                        <p class="text-xs font-black tracking-widest text-red-500 uppercase">Format Pendaftaran Pemindaian USG</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Nama Pasien</label>
                                <input type="text" name="nama_pasien" class="input-dark" placeholder="Xaviera Miyuki O'sama" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Umur</label>
                                <input type="text" name="umur" class="input-dark" placeholder="20" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">Pekerjaan</label>
                                <input type="text" name="pekerjaan" class="input-dark" placeholder="Staff Apotek" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">Keterangan / Keluhan Kelahiran</label>
                                <textarea name="keterangan" rows="3" class="input-dark" placeholder="Cek berkala USG kedua..." required></textarea>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">Penanggung Jawab Pasien</label>
                                <input type="text" name="penanggung_jawab" class="input-dark" placeholder="Luzy O'sama" required>
                            </div>
                        </div>
                    </form>
                `,
                'konsultasi': `
                     <form id="dynamic-form" class="space-y-4">
                        <p class="text-xs font-black tracking-widest text-red-500 uppercase">Format Formulir Konsultasi Umum</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Nama Pasien</label>
                                <input type="text" name="nama_pasien" class="input-dark" placeholder="Evander Valerius" required>
                            </div>
                             <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Usia</label>
                                <input type="text" name="usia" class="input-dark" placeholder="21" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">Jenis Kelamin</label>
                                <input type="text" name="jenis_kelamin" class="input-dark" placeholder="Laki-laki" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">Penanggung Jawab Pasien</label>
                                <input type="text" name="penanggung_jawab" class="input-dark" placeholder="Isi '-' jika mandiri" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">Keterangan Gejala Fisik</label>
                                <textarea name="keterangan" rows="3" class="input-dark" placeholder="Tuliskan keluhan kesehatan..." required></textarea>
                            </div>
                        </div>
                    </form>
                `,
                'autopsi': `
                    <form id="dynamic-form" class="space-y-4">
                        <p class="text-xs font-black tracking-widest text-red-500 uppercase">Format Permohonan Berkas Bedah Autopsi Mayat</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Nama Pasien (Jenazah)</label>
                                <input type="text" name="nama_pasien" class="input-dark" placeholder="Zeynna Tsuzeth Valmore" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Estimasi Umur</label>
                                <input type="text" name="umur" class="input-dark" placeholder="25" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">Jenis Kelamin</label>
                                <input type="text" name="jenis_kelamin" class="input-dark" placeholder="Perempuan" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">Keterangan Kronologi Kematian</label>
                                <textarea name="keterangan" rows="4" class="input-dark" placeholder="Meninggal akibat syok berat..." required></textarea>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">Penanggung Jawab (Saksi/Keluarga)</label>
                                <input type="text" name="penanggung_jawab" class="input-dark" placeholder="Arabella" required>
                            </div>
                        </div>
                    </form>
                `,
                'sunat': `
                    <form id="dynamic-form" class="space-y-4">
                        <p class="text-xs font-black tracking-widest text-red-500 uppercase">Format Pendaftaran Khitan / Sunat Massal</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Nama Pasien</label>
                                <input type="text" name="nama_pasien" class="input-dark" placeholder="Benjamin Modra" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Umur</label>
                                <input type="text" name="umur" class="input-dark" placeholder="20 tahun" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">Jenis Kelamin</label>
                                <input type="text" name="jenis_kelamin" class="input-dark" placeholder="Laki-laki" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">Sertakan Dokumen Berkas Pendukung (Opsional)</label>
                                <input type="file" name="foto_pasien" class="mt-2 block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-bold file:bg-slate-800 file:text-red-500 hover:file:bg-slate-700 cursor-pointer"/>
                            </div>
                        </div>
                    </form>
                `,
                'psikiater': `
                    <form id="dynamic-form" class="space-y-4">
                        <p class="text-xs font-black tracking-widest text-red-500 uppercase">Format Konsultasi Jiwa Psikiatri</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                             <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Nama Pasien</label>
                                <input type="text" name="nama_pasien" class="input-dark" placeholder="Nama lengkap" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Usia</label>
                                <input type="text" name="usia" class="input-dark" placeholder="Usia" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Jenis Kelamin</label>
                                <input type="text" name="jenis_kelamin" class="input-dark" placeholder="Pria/Wanita" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-300">Pekerjaan</label>
                                <input type="text" name="pekerjaan" class="input-dark" placeholder="Pekerjaan" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">Janji Tanggal &amp; Jam Temu</label>
                                <input type="datetime-local" name="jadwal_temu" class="input-dark w-full" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">Wali / Penanggung Jawab</label>
                                <input type="text" name="penanggung_jawab" class="input-dark" placeholder="Nama wali terdekat" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-300">Keluhan Depresi / Tekanan Mental</label>
                                <textarea name="keluhan" rows="3" class="input-dark" placeholder="Ceritakan keluhan trauma atau masalah psikis Anda..." required></textarea>
                            </div>
                        </div>
                    </form>
                `,
            };

            function getFormTemplate(serviceName, defaultDescription) {
                const lowerServiceName = serviceName.toLowerCase();

                // Logika pencocokan yang lebih spesifik untuk menghindari kesalahan
                if (lowerServiceName.includes('mcu') || lowerServiceName.includes('medical check up')) {
                    return formTemplates['mcu'];
                }
                if (lowerServiceName.includes('operasi')) {
                    return formTemplates['operasi'];
                }
                if (lowerServiceName.includes('dna')) {
                    return formTemplates['dna'];
                }
                if (lowerServiceName.includes('usg')) {
                    return formTemplates['usg'];
                }
                if (lowerServiceName.includes('psikiater')) {
                    return formTemplates['psikiater'];
                }
                if (lowerServiceName.includes('konsultasi')) {
                    return formTemplates['konsultasi'];
                }
                if (lowerServiceName.includes('autopsi')) {
                    return formTemplates['autopsi'];
                }
                if (lowerServiceName.includes('sunat')) {
                    return formTemplates['sunat'];
                }
                
                return `<p class="text-slate-300 font-medium">Formulir untuk layanan "${serviceName}" belum diatur secara spesifik.</p><p class="mt-3 text-xs text-slate-500 leading-relaxed">${defaultDescription}</p>`;
            }

            const submitLayananUrl = "{{ route('public.layanan.submit') }}";
            const csrfToken = "{{ csrf_token() }}";

            const openModal = (card) => {
                const nama = card.dataset.nama;
                const deskripsi = card.dataset.deskripsi;
                const template = getFormTemplate(nama, deskripsi);

                modalTitle.querySelector('span').textContent = nama;
                modalFormArea.innerHTML = template;

                const dynamicForm = document.getElementById('dynamic-form');
                if (dynamicForm) {
                    dynamicForm.action = submitLayananUrl;
                    dynamicForm.method = 'POST';

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    dynamicForm.appendChild(csrfInput);

                    const kategoriInput = document.createElement('input');
                    kategoriInput.type = 'hidden';
                    kategoriInput.name = 'kategori_layanan';
                    kategoriInput.value = nama;
                    dynamicForm.appendChild(kategoriInput);
                }
                
                layananModal.classList.remove('hidden');
                layananModal.classList.add('flex');
                document.body.style.overflow = 'hidden';
                setTimeout(() => modalContent.classList.remove('scale-95'), 10);
            };

            const closeModal = () => {
                modalContent.classList.add('scale-95');
                document.body.style.overflow = '';
                setTimeout(() => {
                    layananModal.classList.add('hidden');
                    layananModal.classList.remove('flex');
                }, 300);
            };

            layananCards.forEach(card => card.addEventListener('click', () => openModal(card)));
            if(modalClose) modalClose.addEventListener('click', closeModal);
            layananModal.addEventListener('click', e => (e.target === layananModal || e.target.firstElementChild === modalContent) && closeModal());
        }
        
        // --- Script untuk EMS Registration Modal ---
        const emsRegModal = document.getElementById('ems-registration-modal');
        if (emsRegModal) {
            const openBtn = document.getElementById('open-ems-registration-modal');
            const closeBtn = document.getElementById('ems-modal-close');
            const cancelBtn = document.getElementById('ems-modal-cancel');
            const modalContent = document.getElementById('ems-registration-modal-content');

            const openEmsModal = () => {
                emsRegModal.classList.remove('hidden');
                emsRegModal.classList.add('flex');
                document.body.style.overflow = 'hidden';
                setTimeout(() => modalContent.classList.remove('scale-95'), 10);
            };
            const closeEmsModal = () => {
                modalContent.classList.add('scale-95');
                document.body.style.overflow = '';
                setTimeout(() => {
                    emsRegModal.classList.add('hidden');
                    emsRegModal.classList.remove('flex');
                }, 300);
            };

            if (openBtn) openBtn.addEventListener('click', openEmsModal);
            if (closeBtn) closeBtn.addEventListener('click', closeEmsModal);
            if (cancelBtn) cancelBtn.addEventListener('click', closeEmsModal);
            emsRegModal.addEventListener('click', e => (e.target === emsRegModal || e.target.firstElementChild === modalContent) && closeEmsModal());
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape' && !layananModal.classList.contains('hidden')) closeModal();
                if (e.key === 'Escape' && !emsRegModal.classList.contains('hidden')) closeEmsModal();
            });

            @if($errors->any())
                openEmsModal();
            @endif
        }

        // --- Script untuk Login Modal ---
        const loginModal = document.getElementById('login-modal');
        if (loginModal) {
            const openBtn = document.getElementById('open-login-modal');
            const openBtnHeader = document.getElementById('open-login-modal-header');
            const openBtnHeaderMobile = document.getElementById('open-login-modal-header-mobile');
            const closeBtn = document.getElementById('login-modal-close');
            const modalContent = document.getElementById('login-modal-content');

            const openLoginModal = () => {
                loginModal.classList.remove('hidden');
                loginModal.classList.add('flex');
                document.body.style.overflow = 'hidden';
                setTimeout(() => modalContent.classList.remove('scale-95'), 10);
            };
            const closeLoginModal = () => {
                modalContent.classList.add('scale-95');
                document.body.style.overflow = '';
                setTimeout(() => {
                    loginModal.classList.add('hidden');
                    loginModal.classList.remove('flex');
                }, 300);
            };

            if(openBtn) openBtn.addEventListener('click', openLoginModal);
            if(openBtnHeader) openBtnHeader.addEventListener('click', openLoginModal);
            if(openBtnHeaderMobile) openBtnHeaderMobile.addEventListener('click', openLoginModal);
            if(closeBtn) closeBtn.addEventListener('click', closeLoginModal);
            loginModal.addEventListener('click', e => (e.target === loginModal) && closeLoginModal());

            @if ($errors->has('username'))
                openLoginModal();
            @endif
        }

        // --- Script untuk Parallax Background ---
        const parallaxImages = [
            document.getElementById('bg-img-1'),
            document.getElementById('bg-img-2'),
            document.getElementById('bg-img-3'),
            document.getElementById('bg-img-4'),
        ].filter(Boolean);

        if (parallaxImages.length > 1) {
            let lastImageIndex = 0;

            window.addEventListener('scroll', () => {
                const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
                const scrollTop = window.scrollY;
                // Hitung fraksi scroll, tambahkan sedikit margin untuk kelancaran transisi di akhir halaman
                const scrollFraction = scrollHeight > 0 ? scrollTop / scrollHeight : 0;

                const imageIndex = Math.min(
                    parallaxImages.length - 1,
                    Math.floor(scrollFraction * parallaxImages.length)
                );

                if (imageIndex !== lastImageIndex) {
                    parallaxImages[lastImageIndex].style.opacity = 0;
                    parallaxImages[imageIndex].style.opacity = 1;
                    lastImageIndex = imageIndex;
                }
            }, { passive: true });
        }
    });
</script>
@endsection