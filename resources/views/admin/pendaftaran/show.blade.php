@extends('layouts.app')

@section('content')
@php
    $statusMeta = [
        'Pending' => ['class' => 'bg-warning-subtle text-warning border-warning-subtle', 'icon' => 'fa-clock', 'label' => 'Pending'],
        'Diterima' => ['class' => 'bg-success-subtle text-success border-success-subtle', 'icon' => 'fa-circle-check', 'label' => 'Diterima'],
        'Ditolak' => ['class' => 'bg-danger-subtle text-danger border-danger-subtle', 'icon' => 'fa-circle-xmark', 'label' => 'Ditolak'],
    ];
@endphp

<div class="container-fluid px-4 py-3" style="min-height: 100vh;">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
             <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-white border shadow-sm rounded-3">
                <i class="fa-solid fa-arrow-left text-primary"></i>
            </a>
            <div>
                <h3 class="m-0 fw-bolder text-dark" style="letter-spacing: -0.5px;">{{ $batch->name }}</h3>
                <p class="text-muted small m-0">EMS KISAH • Daftar kandidat EMS dalam batch ini.</p>
            </div>
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

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-primary h-100">
                <div class="card-body p-3">
                    <div class="text-primary bg-primary bg-opacity-10 d-inline-block p-2 rounded-3 mb-3">
                        <i class="fa-solid fa-users fa-lg"></i>
                    </div>
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">TOTAL PENDAFTAR</div>
                    <h3 class="fw-bolder text-dark mb-0 font-monospace">{{ $registrations->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-warning h-100">
                <div class="card-body p-3">
                    <div class="text-warning bg-warning bg-opacity-10 d-inline-block p-2 rounded-3 mb-3">
                        <i class="fa-solid fa-clock fa-lg"></i>
                    </div>
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">PENDING</div>
                    <h3 class="fw-bolder text-dark mb-0 font-monospace">{{ $registrations->where('status', 'Pending')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-success h-100">
                <div class="card-body p-3">
                     <div class="text-success bg-success bg-opacity-10 d-inline-block p-2 rounded-3 mb-3">
                        <i class="fa-solid fa-check-circle fa-lg"></i>
                    </div>
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">DITERIMA</div>
                    <h3 class="fw-bolder text-dark mb-0 font-monospace">{{ $registrations->where('status', 'Diterima')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-danger h-100">
                <div class="card-body p-3">
                    <div class="text-danger bg-danger bg-opacity-10 d-inline-block p-2 rounded-3 mb-3">
                        <i class="fa-solid fa-times-circle fa-lg"></i>
                    </div>
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">DITOLAK</div>
                    <h3 class="fw-bolder text-dark mb-0 font-monospace">{{ $registrations->where('status', 'Ditolak')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER & ACTIONS -->
    <div class="card border-0 shadow-sm mb-4 rounded-4">
        <div class="card-body p-3">
            <div class="row align-items-center m-0">
                <div class="col-md-6">
                    <form action="{{ route('admin.pendaftaran.show', $batch) }}" method="GET" class="d-flex gap-2">
                        <label class="form-label small fw-bold text-dark mb-1 d-none">Filter Status</label>
                        <select name="status" class="form-select border-primary fw-bold text-primary font-monospace shadow-sm" onchange="this.form.submit()" style="font-size: 0.85rem;">
                            <option value="">-- Tampilkan Semua Status --</option>
                            <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Tampilkan Pending</option>
                            <option value="Diterima" {{ request('status') === 'Diterima' ? 'selected' : '' }}>Tampilkan Diterima</option>
                            <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Tampilkan Ditolak</option>
                        </select>
                        @if(request('status'))
                            <a href="{{ route('admin.pendaftaran.show', $batch) }}" class="btn btn-outline-danger btn-sm px-3 d-flex align-items-center"><i class="fa-solid fa-xmark"></i></a>
                        @endif
                    </form>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                     <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                        @if($batch->is_active)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 align-self-center"><i class="fa-solid fa-lock-open me-1"></i> Batch Aktif</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border px-3 py-2 align-self-center"><i class="fa-solid fa-lock me-1"></i> Batch Ditutup</span>
                        @endif

                        <form action="{{ route('admin.pendaftaran.toggle', $batch) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn {{ $batch->is_active ? 'btn-outline-dark' : 'btn-success' }} fw-bold btn-sm">
                                <i class="fa-solid {{ $batch->is_active ? 'fa-lock' : 'fa-lock-open' }} me-1"></i>
                                {{ $batch->is_active ? 'Tutup Batch' : 'Aktifkan Batch' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-gray border-0 p-4 pb-0">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <div>
                    <h5 class="fw-bolder text-dark mb-1">Data Pendaftar</h5>
                    <p class="text-muted small mb-0">Klik detail untuk membaca jawaban kandidat lengkap.</p>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-muted small text-uppercase">Nama IC</th>
                            <th class="text-muted small text-uppercase">Umur</th>
                            <th class="text-muted small text-uppercase">Kontak</th>
                            <th class="text-muted small text-uppercase">Jam Aktif</th>
                            <th class="text-muted small text-uppercase">Status</th>
                            <th class="text-muted small text-uppercase text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $registration)
                            @php
                                $meta = $statusMeta[$registration->status] ?? $statusMeta['Pending'];
                                $detail = [
                                    'nama_ic' => $registration->nama_ic,
                                    'umur_ic' => $registration->umur_ic,
                                    'umur_ooc' => $registration->umur_ooc,
                                    'jenis_kelamin' => $registration->jenis_kelamin,
                                    'roblox' => $registration->roblox,
                                    'discord' => $registration->discord,
                                    'jam_aktif' => $registration->jam_aktif,
                                    'pengalaman' => $registration->pengalaman,
                                    'visi_misi' => $registration->visi_misi,
                                    'status' => $registration->status,
                                    'created_at' => $registration->created_at?->format('d M Y H:i') ?? '-',
                                ];
                            @endphp
                            <tr>
                                <td>
                                    <button type="button" class="btn btn-link p-0 text-decoration-none fw-bolder text-primary text-start detail-btn" data-detail='@json($detail)'>
                                        {{ $registration->nama_ic }}
                                    </button>
                                    <div class="text-muted small">{{ $registration->jenis_kelamin }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $registration->umur_ic }} IC</div>
                                    <div class="text-muted small">{{ $registration->umur_ooc }} OOC</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $registration->discord }}</div>
                                    <div class="text-muted small">{{ $registration->roblox }}</div>
                                </td>
                                <td class="text-muted">{{ $registration->jam_aktif }}</td>
                                <td>
                                    <span class="badge border {{ $meta['class'] }} px-3 py-2">
                                        <i class="fa-solid {{ $meta['icon'] }} me-1"></i> {{ $meta['label'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap justify-content-end gap-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm fw-bold detail-btn" data-detail='@json($detail)'>
                                            <i class="fa-solid fa-eye"></i>
                                        </button>

                                        @if($registration->status !== 'Diterima')
                                            <form action="{{ route('admin.pendaftaran.updateStatus', $registration) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="Diterima">
                                                <button type="submit" class="btn btn-success btn-sm fw-bold">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($registration->status !== 'Ditolak')
                                            <form action="{{ route('admin.pendaftaran.updateStatus', $registration) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="Ditolak">
                                                <button type="submit" class="btn btn-outline-danger btn-sm fw-bold">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.pendaftaran.destroyRegistration', $registration) }}" method="POST" onsubmit="return confirm('Hapus data pendaftar ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-secondary btn-sm fw-bold">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="text-center py-5">
                                        <div class="text-primary bg-primary bg-opacity-10 rounded-4 d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                                            <i class="fa-solid fa-user-plus fa-lg"></i>
                                        </div>
                                        <h6 class="fw-bolder text-dark mb-1">Belum ada pendaftar</h6>
                                        <p class="text-muted small mb-0">Data kandidat akan muncul saat formulir publik diisi.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="registrationDetailModal" tabindex="-1" aria-labelledby="registrationDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bolder" id="registrationDetailLabel">Detail Pendaftaran</h5>
                    <p class="text-muted small mb-0" id="detailCreatedAt"></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small fw-bold text-uppercase mb-1">Nama IC</div>
                            <div class="fw-bolder text-dark" id="detailNama"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small fw-bold text-uppercase mb-1">Status</div>
                            <div id="detailStatus"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small fw-bold text-uppercase mb-1">Umur IC/OOC</div>
                            <div class="fw-semibold" id="detailUmur"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small fw-bold text-uppercase mb-1">Jenis Kelamin</div>
                            <div class="fw-semibold" id="detailGender"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small fw-bold text-uppercase mb-1">Jam Aktif</div>
                            <div class="fw-semibold" id="detailJamAktif"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small fw-bold text-uppercase mb-1">Discord</div>
                            <div class="fw-semibold" id="detailDiscord"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small fw-bold text-uppercase mb-1">Roblox</div>
                            <div class="fw-semibold" id="detailRoblox"></div>
                        </div>
                    </div>
                </div>

                <div class="border rounded-4 p-3 mb-3">
                    <div class="text-muted small fw-bold text-uppercase mb-2">Pengalaman</div>
                    <div class="text-dark small lh-lg" id="detailPengalaman" style="white-space: pre-wrap;"></div>
                </div>

                <div class="border rounded-4 p-3">
                    <div class="text-muted small fw-bold text-uppercase mb-2">Visi & Misi</div>
                    <div class="text-dark small lh-lg" id="detailVisiMisi" style="white-space: pre-wrap;"></div>
                </div>
            </div>
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

    document.addEventListener('DOMContentLoaded', function () {
        const detailModal = new bootstrap.Modal(document.getElementById('registrationDetailModal'));
        const statusMap = {
            Pending: '<span class="badge border bg-warning-subtle text-warning border-warning-subtle px-3 py-2"><i class="fa-solid fa-clock me-1"></i> Pending</span>',
            Diterima: '<span class="badge border bg-success-subtle text-success border-success-subtle px-3 py-2"><i class="fa-solid fa-circle-check me-1"></i> Diterima</span>',
            Ditolak: '<span class="badge border bg-danger-subtle text-danger border-danger-subtle px-3 py-2"><i class="fa-solid fa-circle-xmark me-1"></i> Ditolak</span>',
        };

        document.querySelectorAll('.detail-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                const data = JSON.parse(this.dataset.detail);

                document.getElementById('detailCreatedAt').textContent = 'Dikirim: ' + (data.created_at || '-');
                document.getElementById('detailNama').textContent = data.nama_ic || '-';
                document.getElementById('detailStatus').innerHTML = statusMap[data.status] || statusMap.Pending;
                document.getElementById('detailUmur').textContent = `${data.umur_ic || '-'} IC / ${data.umur_ooc || '-'} OOC`;
                document.getElementById('detailGender').textContent = data.jenis_kelamin || '-';
                document.getElementById('detailJamAktif').textContent = data.jam_aktif || '-';
                document.getElementById('detailDiscord').textContent = data.discord || '-';
                document.getElementById('detailRoblox').textContent = data.roblox || '-';
                document.getElementById('detailPengalaman').textContent = data.pengalaman || '-';
                document.getElementById('detailVisiMisi').textContent = data.visi_misi || '-';

                detailModal.show();
            });
        });
    });
</script>
@endsection
