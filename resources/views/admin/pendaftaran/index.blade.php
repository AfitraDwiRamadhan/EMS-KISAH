@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3" style="min-height: 100vh;">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="m-0 fw-bolder text-dark" style="letter-spacing: -0.5px;">PENDAFTARAN EMS</h3>
            <p class="text-muted small m-0">EMS KISAH • Kelola batch rekrutmen dan formulir pendaftaran.</p>
        </div>
        <div class="d-flex gap-2">
            <div class="badge text-dark border p-2 shadow-sm d-flex align-items-center font-monospace" style="font-size: 0.8rem;">
                <i class="fa-regular fa-calendar text-primary me-2"></i> {{ date('d M Y') }}
            </div>
            <div class="badge text-dark border p-2 shadow-sm d-flex align-items-center font-monospace" style="font-size: 0.8rem;">
                <i class="fa-regular fa-clock text-primary me-2"></i> <span id="clock">00:00:00</span>
            </div>
        </div>
    </div>

    <!-- FORM BUAT BATCH -->
    <div class="card border-0 shadow-sm mb-4 rounded-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.pendaftaran.store') }}" method="POST" class="row align-items-center m-0">
                @csrf
                <div class="col-md-4">
                    <label for="name" class="form-label small fw-bold text-dark mb-1">Nama Batch</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Gelombang Pendaftaran Juni" required autocomplete="off">
                </div>
                <div class="col-md-5">
                    <label for="registration_link" class="form-label small fw-bold text-dark mb-1">Link Pendaftaran (Opsional)</label>
                    <input type="url" name="registration_link" id="registration_link" class="form-control" placeholder="https://discord.gg/link-pendaftaran">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary fw-bold px-3 w-100 mt-3 mt-md-0">
                        <i class="fa-solid fa-plus me-1"></i> Buat Batch Baru
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-primary h-100">
                <div class="card-body p-3">
                    <div class="text-primary bg-primary bg-opacity-10 d-inline-block p-2 rounded-3 mb-3">
                        <i class="fa-solid fa-folder-tree fa-lg"></i>
                    </div>
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">TOTAL BATCH</div>
                    <h3 class="fw-bolder text-dark mb-0 font-monospace">{{ $batches->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-success h-100">
                <div class="card-body p-3">
                    <div class="text-success bg-success bg-opacity-10 d-inline-block p-2 rounded-3 mb-3">
                        <i class="fa-solid fa-play-circle fa-lg"></i>
                    </div>
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">BATCH AKTIF</div>
                    <h3 class="fw-bolder text-dark mb-0 font-monospace">{{ $batches->where('is_active', true)->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-danger h-100">
                <div class="card-body p-3">
                    <div class="text-danger bg-danger bg-opacity-10 d-inline-block p-2 rounded-3 mb-3">
                        <i class="fa-solid fa-users fa-lg"></i>
                    </div>
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">TOTAL PENDAFTAR</div>
                    <h3 class="fw-bolder text-dark mb-0 font-monospace">{{ $batches->sum('registrations_count') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 bg-gray">
        <div class="card-header bg-gray border-0 p-4 pb-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bolder text-dark mb-1">Daftar Batch</h5>
                    <p class="text-muted small mb-0">Aktifkan satu batch untuk membuka pendaftaran publik.</p>
                </div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                    {{ $batches->count() }} Batch
                </span>
            </div>
        </div>

        <div class="card-body p-4">
            @forelse($batches as $batch)
                <div class="border rounded-4 p-3 mb-3 {{ $batch->is_active ? 'border-primary bg-primary bg-opacity-10' : 'bg-gray' }}">
                    <div class="row align-items-center g-3">
                        <div class="col-lg-5">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center {{ $batch->is_active ? 'bg-primary text-white' : 'bg-light text-primary' }}" style="width: 46px; height: 46px;">
                                    <i class="fa-solid fa-folder-open"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bolder text-dark mb-1">{{ $batch->name }}</h6>
                                    <div class="text-muted small">
                                        Dibuat {{ $batch->created_at?->format('d M Y H:i') ?? '-' }}
                                    </div>
                                    @if($batch->registration_link)
                                        <div class="small mt-1">
                                            <a href="{{ $batch->registration_link }}" target="_blank" class="text-primary-emphasis text-decoration-none" style="font-size: 0.75rem;">
                                                <i class="fa-solid fa-link me-1"></i> {{ \Illuminate\Support\Str::limit($batch->registration_link, 30) }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-6">
                            <div class="text-muted small fw-bold">Pendaftar</div>
                            <div class="fw-bolder font-monospace">{{ $batch->registrations_count }}</div>
                        </div>

                        <div class="col-lg-2 col-6">
                            @if($batch->is_active)
                                <span class="badge bg-success px-3 py-2"><i class="fa-solid fa-circle-check me-1"></i> Aktif</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border px-3 py-2"><i class="fa-solid fa-circle-xmark me-1"></i> Tutup</span>
                            @endif
                        </div>

                        <div class="col-lg-3">
                            <div class="d-flex flex-wrap justify-content-lg-end gap-2">
                                <a href="{{ route('admin.pendaftaran.show', $batch) }}" class="btn btn-outline-primary btn-sm fw-bold">
                                    <i class="fa-solid fa-eye me-1"></i> Detail
                                </a>

                                <form action="{{ route('admin.pendaftaran.toggle', $batch) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $batch->is_active ? 'btn-outline-secondary' : 'btn-success' }} btn-sm fw-bold">
                                        <i class="fa-solid {{ $batch->is_active ? 'fa-lock' : 'fa-lock-open' }} me-1"></i>
                                        {{ $batch->is_active ? 'Tutup' : 'Aktifkan' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.pendaftaran.destroy', $batch) }}" method="POST" onsubmit="return confirm('Hapus batch ini beserta semua pendaftar di dalamnya?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm fw-bold">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 border border-2 border-dashed rounded-4 bg-grey">
                    <div class="text-primary bg-primary bg-opacity-10 rounded-4 d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                        <i class="fa-solid fa-folder-plus fa-lg"></i>
                    </div>
                    <h6 class="fw-bolder text-dark mb-1">Belum ada batch pendaftaran</h6>
                    <p class="text-muted small mb-0">Buat batch pertama lewat form di kanan atas.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    // System Clock Live Engine
    function updateClock() {
        const now = new Date();
        const timeString = now.toTimeString().split(' ')[0];
        const clockElement = document.getElementById('clock');
        if(clockElement) {
            clockElement.textContent = timeString;
        }
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endsection
