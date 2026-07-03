@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3" style="min-height: 100vh;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="m-0 fw-bolder text-dark" style="letter-spacing: -0.5px;">LOG ABSENSI MEDIS</h3>
            <p class="text-muted small m-0">Pencatatan dan rekapitulasi jam dinas serta aktivitas medis.</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary rounded-4">
                <div class="card-body p-3">
                    <div class="text-primary bg-primary bg-opacity-10 d-inline-block p-2 rounded-3 mb-3">
                        <i class="fa-solid fa-database fa-lg"></i>
                    </div>
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">TOTAL BARIS REKAP</div>
                    <h3 class="fw-bolder text-dark mb-1 font-monospace">{{ $absensi->count() }}</h3>
                    <div class="text-muted small" style="font-size: 0.75rem;">Total rekaman absensi</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-success rounded-4">
                <div class="card-body p-3">
                    <div class="text-success bg-success bg-opacity-10 d-inline-block p-2 rounded-3 mb-3">
                        <i class="fa-solid fa-heart-pulse fa-lg"></i>
                    </div>
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">PASIEN TERLAYANI</div>
                    <h3 class="fw-bolder text-dark mb-1 font-monospace">{{ $absensi->sum('jumlah_pasien') }}</h3>
                    <div class="text-muted small" style="font-size: 0.75rem;">Total pasien ditangani</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-warning rounded-4">
                <div class="card-body p-3">
                    <div class="text-warning bg-warning bg-opacity-10 d-inline-block p-2 rounded-3 mb-3">
                        <i class="fa-regular fa-clock fa-lg"></i>
                    </div>
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">TOTAL JAM DUTY</div>
                    <h3 class="fw-bolder text-dark mb-1 font-monospace">{{ number_format($absensi->sum('durasi'), 1) }}j</h3>
                    <div class="text-muted small" style="font-size: 0.75rem;">Akumulasi jam kerja</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-danger rounded-4">
                <div class="card-body p-3">
                    <div class="text-danger bg-danger bg-opacity-10 d-inline-block p-2 rounded-3 mb-3">
                        <i class="fa-solid fa-calendar-week fa-lg"></i>
                    </div>
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">FILTER PERIODE AKTIF</div>
                    <h5 class="fw-bolder text-dark mb-1 text-truncate" style="max-width: 200px;">{{ request('filter_periode') ? request('filter_periode') : 'Semua Rekaman' }}</h5>
                    <div class="text-muted small" style="font-size: 0.75rem;">Periode yang ditampilkan</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 rounded-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-7 mb-3 mb-md-0">
                    <form action="{{ route('admin.absensi.index') }}" method="GET" class="row align-items-center g-2">
                        <div class="col-sm-8 col-md-6">
                            <label class="form-label small fw-bold text-dark mb-1"><i class="fa-solid fa-filter text-primary me-1"></i> Pilih Tanggal Rekapitulasi</label>
                            <select name="filter_periode" class="form-select border-primary fw-bold text-primary font-monospace" onchange="this.form.submit()" style="font-size: 0.9rem;">
                                <option value="">-- Semua Periode (All Time) --</option>
                                @foreach($list_periode as $periode)
                                    <option value="{{ $periode }}" {{ request('filter_periode') == $periode ? 'selected' : '' }}>
                                        {{ $periode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if(request('filter_periode'))
                        <div class="col-sm-4 align-self-end">
                            <a href="{{ route('admin.absensi.index') }}" class="btn btn-light-secondary btn-sm fw-semibold text-muted text-decoration-none"><i class="fa-solid fa-circle-xmark me-1"></i>Reset</a>
                        </div>
                        @endif
                    </form>
                </div>
                
                <div class="col-md-5 text-md-end">
                    <button type="button" class="btn btn-primary fw-bold px-4 py-2 text-white border-0 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalBulkAbsensi" style="border-radius: 6px;">
                        <i class="fa-solid fa-paste me-2"></i> Smart Parser EMS V4
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header border-0 py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-dark"><i class="fa-solid fa-table-list text-primary me-2"></i>Data Log Aktivitas Tenaga Medis</h6>
            <span class="badge border px-2 py-1 small font-monospace">EMS SECURITY PROTOCOL v4.0</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 w-100" style="font-size: 0.88rem;">
                    <thead class="table-light text-secondary border-bottom border-top border-light-subtle">
                        <tr>
                            <th class="text-center py-3" width="50">No</th>
                            <th>Nama Tenaga Medis</th>
                            <th class="text-center">Wilayah</th>
                            <th>Waktu Shift (Raw)</th>
                            <th class="text-center">Total Durasi</th>
                            <th class="text-center">Pasien</th>
                            <th>Tindakan / Keluhan Medis</th>
                            <th>Tanggal Laporan</th>
                            <th class="text-center" width="80"><i class="fa-solid fa-gears"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensi as $index => $row)
                        <tr class="border-bottom border-light-subtle">
                            <td class="text-center font-monospace text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $row->nama_petugas }}</div>
                                <div class="text-muted small" style="font-size: 0.75rem;"><i class="fa-solid fa-user-shield me-1"></i>Verified Staff</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1 fw-semibold">{{ strtoupper($row->kabupaten) }}</span>
                            </td>
                            <td>
                                <div class="badge text-dark border font-monospace px-2 py-1 text-start" style="line-height: 1.5;">
                                    In: {{ $row->jam_masuk }}<br>
                                    Out: {{ $row->jam_keluar }}
                                </div>
                            </td>
                            <td class="text-center fw-bold text-primary">{{ $row->durasi }} Jam</td>
                            <td class="text-center">
                                <span class="badge bg-success-subtle text-success fw-bold px-2 py-1 font-monospace" style="font-size: 0.85rem;">{{ $row->jumlah_pasien }}</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-link btn-sm text-primary text-decoration-none fw-semibold p-0" data-bs-toggle="modal" data-bs-target="#modalKeluhan{{ $row->id }}">
                                    <i class="fa-solid fa-notes-medical me-1"></i> Lihat Rincian Kasus
                                </button>
                            </td>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 0.82rem;">
                                    <i class="fa-solid fa-calendar-day text-muted me-1" style="font-size: 0.75rem;"></i>{{ $row->tanggal }}
                                </div>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.absensi.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data absensi medis ini dari database induk?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm border-0 rounded-circle" style="width: 32px; height: 32px; padding: 0;">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="fa-solid fa-folder-open fa-2x mb-2 d-block text-secondary-subtle"></i>
                                Belum ada data log absensi medis yang terekam.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- CONTAINER UNTUK MODAL DETAIL KASUS (VALID HTML) -->
@foreach($absensi as $row)
<div class="modal fade" id="modalKeluhan{{ $row->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 py-3">
                <h6 class="modal-title fw-bold"><i class="fa-solid fa-suitcase-medical text-warning me-2"></i>Rekapitulasi Kasus Pasien</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-2 small text-slate-300 font-monospace">PETUGAS: <span class="text-white fw-bold">{{ strtoupper($row->nama_petugas) }}</span></div>
                <div class="mb-3 small text-slate-300 font-monospace">TANGGAL LAPORAN: <span class="text-white fw-bold">{{ $row->tanggal }}</span></div>
                <label class="form-label fw-bold text-white small mb-1"><i class="fa-solid fa-clipboard-list text-primary me-1"></i> Rincian Tindakan di Lapangan:</label>
                <div class="card p-3 border-secondary-subtle font-monospace text-slate-300 bg-slate-900/50" style="font-size: 0.85rem; white-space: pre-line; line-height: 1.6; border-radius: 8px;">
                    {{ $row->keluhan_pasien }}
                </div>
                @if($row->keterangan && $row->keterangan !== '-')
                <div class="mt-3">
                    <label class="form-label fw-bold text-white small mb-1">Catatan Tambahan (Keterangan):</label>
                    <div class="alert alert-secondary py-2 small mb-0 bg-slate-800 text-slate-300 border-slate-700">{{ $row->keterangan }}</div>
                </div>
                @endif
            </div>
            <div class="modal-footer border-0 py-2">
                <button type="button" class="btn btn-outline-secondary btn-sm fw-semibold" data-bs-dismiss="modal">Tutup Dokumen</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<div class="modal fade" id="modalBulkAbsensi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fs-6 fw-bold"><i class="fa-solid fa-bolt-lightning text-warning me-2"></i>EMS Smart Bulk Parser Engine V4</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.absensi.storeBulk') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    
                    <div class="alert alert-info py-2 small border-0 shadow-sm d-flex align-items-center mb-3" style="border-radius: 6px;">
                        <i class="fa-solid fa-robot me-2 fa-2x text-primary"></i>
                        <div>
                            <strong>Parser V4 Aktif!</strong> Mesin ini kini kebal terhadap error. Mampu membaca format shift ganda (cth: <code>10.00 - 15.00 - 20.00</code>), memperbaiki kelalaian kolom pasien, dan menyedot tanggal secara otomatis dari teks Discord Anda.
                        </div>
                    </div>

                    <div class="mb-1 d-flex justify-content-between">
                        <label class="form-label fw-bold text-dark small mb-1">Paste Log Laporan Discord Kedinasan</label>
                        <span class="text-muted small font-monospace" style="font-size: 0.72rem;">SUPPORT MULTI-SHIFT & RAW TEXT</span>
                    </div>
                    <textarea class="form-control font-monospace border-secondary-subtle shadow-sm p-3" name="bulk_data" rows="15" required placeholder="Paste laporan discord Anda di sini tanpa perlu diedit sedikit pun..." style="font-size: 0.82rem; border-radius: 6px; resize: vertical;"></textarea>
                </div>
                <div class="modal-footer border-0 py-3">
                    <button type="button" class="btn btn-secondary fw-semibold btn-sm px-3" data-bs-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn btn-primary text-white fw-bold btn-sm px-4"><i class="fa-solid fa-wand-magic-sparkles me-2"></i>Eksekusi & Urutkan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection