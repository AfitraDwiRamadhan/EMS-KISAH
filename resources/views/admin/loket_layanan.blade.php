@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3" style="min-height: 100vh;">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="m-0 fw-bolder text-dark" style="letter-spacing: -0.5px;">MANAJEMEN LOKET PELAYANAN</h3>
            <p class="text-muted small m-0">Kontrol ketersediaan layanan publik dan antrean warga</p>
        </div>
        <div class="d-flex flex-wrap gap-2 w-100 w-md-auto">
            <button type="button" class="btn btn-purple fw-bold px-3 py-2 text-white border-0 shadow-sm rounded-3 flex-grow-1 flex-md-grow-0 text-center" data-bs-toggle="modal" data-bs-target="#modalBulkPasien" style="background-color: #6f42c1;">
                <i class="fa-solid fa-bolt-lightning me-2"></i> Smart Parser (Warga Log)
            </button>
            <button type="button" class="btn btn-primary fw-bold px-3 py-2 text-white border-0 shadow-sm rounded-3 flex-grow-1 flex-md-grow-0 text-center" data-bs-toggle="modal" data-bs-target="#modalTambahLoket">
                <i class="fa-solid fa-plus me-2"></i> Buka Loket Baru
            </button>
        </div>
    </div>

    <div class="row g-4">
        @forelse($kategoris as $kat)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 {{ $kat->status_loket == 'Buka' ? 'border-bottom border-4 border-success' : 'border-bottom border-4 border-danger opacity-75' }}">
                <div class="card-body p-4 text-center">
                    
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge {{ $kat->status_loket == 'Buka' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill px-3 py-1">
                            @if($kat->status_loket == 'Buka')
                                <i class="fa-solid fa-door-open me-1"></i> Buka
                            @else
                                <i class="fa-solid fa-door-closed me-1"></i> Tutup
                            @endif
                        </span>
                        
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm text-muted border-0 shadow-none" type="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 font-monospace" style="font-size: 0.8rem;">
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalEditLoket{{ $kat->id }}"><i class="fa-solid fa-pen text-primary me-2"></i> Pengaturan Loket</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('admin.loket.destroyKategori', $kat->id) }}" method="POST" onsubmit="return confirm('Bongkar/Hapus loket ini permanen?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-trash-can text-danger me-2"></i> Bongkar Loket</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="bg-light d-inline-block p-3 rounded-circle mb-2 text-primary">
                            <i class="fa-solid fa-hospital-user fa-2x"></i>
                        </div>
                        <h5 class="fw-bolder text-dark mb-1">{{ $kat->nama_layanan }}</h5>
                        <p class="text-muted small mb-0" style="font-size: 0.75rem; height: 36px; overflow: hidden;">{{ $kat->deskripsi ?? 'Loket pendaftaran resmi.' }}</p>
                    </div>

                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <div class="text-center">
                            <h4 class="fw-bold text-dark mb-0 font-monospace">{{ $kat->antrean_aktif }}</h4>
                            <span class="text-muted" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Antrean</span>
                        </div>
                        <div class="text-center">
                            <h4 class="fw-bold text-dark mb-0 font-monospace">{{ $kat->total_pasien }}</h4>
                            <span class="text-muted" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Total Pasien</span>
                        </div>
                    </div>

                    <button type="button" class="btn {{ $kat->status_loket == 'Buka' ? 'btn-primary' : 'btn-secondary' }} w-100 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAntrean{{ $kat->id }}">
                        <i class="fa-solid fa-users-viewfinder me-1"></i> Lihat Antrean Pasien
                    </button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalEditLoket{{ $kat->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-0 py-3">
                        <h6 class="modal-title fw-bold text-white">Pengaturan Loket</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.loket.updateKategori', $kat->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body p-4">
                            <label class="form-label small fw-bold text-white-50">Nama Layanan</label>
                            <input type="text" name="nama_layanan" class="form-control mb-2" value="{{ $kat->nama_layanan }}" required>
                            <label class="form-label small fw-bold text-white-50">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control mb-2" rows="2">{{ $kat->deskripsi }}</textarea>
                            <label class="form-label small fw-bold text-white-50">Status Operasional</label>
                            <select name="status_loket" class="form-select">
                                <option value="Buka" {{ $kat->status_loket == 'Buka' ? 'selected' : '' }}>BUKA</option>
                                <option value="Tutup" {{ $kat->status_loket == 'Tutup' ? 'selected' : '' }}>TUTUP</option>
                            </select>
                        </div>
                        <div class="modal-footer border-0 py-2">
                            <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan Pengaturan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalAntrean{{ $kat->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-0 py-3">
                        <h5 class="modal-title fw-bold text-white"><i class="fa-solid fa-users text-primary me-2"></i>Antrean Pasien: {{ $kat->nama_layanan }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0">
                        <table class="table table-hover align-middle mb-0 w-100" style="font-size: 0.88rem;">
                            <thead class="table-light text-secondary">
                                <tr>
                                    <th class="ps-4">Nama Pasien</th>
                                    <th class="text-center">Waktu Pendaftaran</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center pe-4">Aksi / Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($layanan->where('kategori_layanan', $kat->nama_layanan) as $row)
                                <tr class="border-bottom border-light-subtle">
                                    <td class="ps-4 fw-bold text-dark fs-6">{{ $row->nama_pasien }}</td>
                                    <td class="text-center">
                                        <div class="fw-bold text-dark" style="font-size: 0.82rem;">{{ $row->created_at->format('d M Y') }}</div>
                                        <div class="text-muted" style="font-size: 0.72rem;">{{ $row->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="text-center">
                                        @if($row->status_penanganan === 'Menunggu')
                                            <span class="badge bg-warning text-dark px-2 py-1 rounded-pill">Menunggu</span>
                                        @elseif($row->status_penanganan === 'Diproses')
                                            <span class="badge bg-info px-2 py-1 rounded-pill text-white">Diproses</span>
                                        @else
                                            <span class="badge bg-success px-2 py-1 rounded-pill">Selesai</span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-4">
                                        <!-- Tutup modal antrean saat modal berkas dibuka agar tidak tumpang tindih -->
                                        <button type="button" class="btn btn-sm btn-primary fw-bold px-3" data-bs-toggle="modal" data-bs-target="#modalLihatForm{{ $row->id }}" data-bs-dismiss="modal">
                                            <i class="fa-solid fa-clipboard-user me-1"></i> Buka Berkas
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-folder-open fa-2x mb-2 text-secondary-subtle"></i><br>
                                        Belum ada antrean di loket ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fa-solid fa-store-slash fa-3x text-muted mb-3 opacity-50"></i>
                <h5 class="fw-bold text-dark">Belum Ada Loket Layanan</h5>
                <p class="text-muted">Gunakan tombol di atas untuk memproses kiriman log warga atau membuat loket manual.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<div class="modal fade" id="modalTambahLoket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 py-3">
                <h6 class="modal-title fw-bold text-white"><i class="fa-solid fa-plus text-warning me-2"></i>Buka Loket Baru</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.loket.storeKategori') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <label class="form-label small fw-bold text-white-50">Nama Layanan (Formulir)</label>
                    <input type="text" name="nama_layanan" class="form-control mb-3" placeholder="Cth: Loket Pendaftaran Bedah" required>
                    <label class="form-label small fw-bold text-white-50">Deskripsi Info</label>
                    <textarea name="deskripsi" class="form-control" rows="2" placeholder="Keterangan singkat..."></textarea>
                </div>
                <div class="modal-footer border-0 py-2">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Buat Loket Layanan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBulkPasien" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 py-3">
                <h5 class="modal-title fs-6 fw-bold text-white"><i class="fa-solid fa-wand-magic-sparkles text-warning me-2"></i>Smart Bulk Parser Antrean Warga</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.loket.storeBulk') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-secondary py-2 small border-0 shadow-sm d-flex align-items-center mb-3 bg-slate-900/50 text-slate-300 border-secondary-subtle">
                        <i class="fa-solid fa-circle-info me-2 fa-lg text-primary"></i>
                        <div>Sistem secara dinamis mendeteksi 8 kategori format pendaftaran warga, memilah isinya, dan menyalurkannya langsung ke kartu loket masing-masing.</div>
                    </div>
                    <textarea class="form-control font-monospace border-secondary-subtle p-3 shadow-sm" name="bulk_data" rows="14" required placeholder="Paste kumpulan log pendaftaran dari Discord warga di sini..." style="font-size: 0.82rem; border-radius: 6px; resize: vertical;"></textarea>
                </div>
                <div class="modal-footer border-0 py-3">
                    <button type="button" class="btn btn-secondary btn-sm fw-semibold px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success text-white fw-bold btn-sm px-4"><i class="fa-solid fa-expand me-2"></i>Urai & Masukkan Antrean</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($layanan as $row)
<div class="modal fade" id="modalLihatForm{{ $row->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 py-3">
                <h6 class="modal-title fw-bold text-white"><i class="fa-solid fa-clipboard-list text-warning me-2"></i>Berkas Pasien: {{ $row->nama_pasien }}</h6>
                <!-- Saat modal berkas pasien ditutup, buka kembali modal antrean kategori yang bersangkutan -->
                <button type="button" class="btn-close btn-close-white" data-bs-toggle="modal" data-bs-target="#modalAntrean{{ $kategoris->firstWhere('nama_layanan', $row->kategori_layanan)->id ?? '' }}" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between mb-2 small font-monospace text-slate-400">
                    <span>LOKET: <strong>{{ strtoupper($row->kategori_layanan) }}</strong></span>
                    <span>{{ $row->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="card p-3 border-secondary-subtle mb-4 shadow-sm bg-slate-900/50 text-slate-300" style="border-radius: 8px;">
                    <table class="table table-sm table-borderless mb-0 text-slate-300" style="font-size: 0.85rem;">
                        <tbody>
                            @if(is_array($row->data_lengkap) || is_object($row->data_lengkap))
                                @foreach($row->data_lengkap as $kunci => $nilai)
                                <tr>
                                    <td width="45%" class="text-slate-400 fw-bold text-uppercase" style="font-size: 0.73rem;">{{ $kunci }}</td>
                                    <td width="5%" class="text-slate-400">:</td>
                                    <td class="fw-semibold text-white">{{ $nilai ?? '-' }}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr><td class="text-danger small">Format data formulir tidak dikenali.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <form action="{{ route('admin.loket.updateStatus', $row->id) }}" method="POST" class="p-3 bg-slate-900/30 rounded border border-secondary-subtle">
                    @csrf @method('PUT')
                    <label class="form-label small fw-bold text-white mb-2">Progres Penanganan Medis:</label>
                    <div class="input-group input-group-sm">
                        <select name="status_penanganan" class="form-select fw-semibold text-dark">
                            <option value="Menunggu" {{ $row->status_penanganan == 'Menunggu' ? 'selected' : '' }}>Menunggu Dokter</option>
                            <option value="Diproses" {{ $row->status_penanganan == 'Diproses' ? 'selected' : '' }}>Sedang Ditangani</option>
                            <option value="Selesai" {{ $row->status_penanganan == 'Selesai' ? 'selected' : '' }}>Tindakan Selesai</option>
                        </select>
                        <button type="submit" class="btn btn-primary fw-bold">Update Status</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 py-2">
                <!-- Tombol kembali ke modal antrean kategori yang bersangkutan -->
                <button type="button" class="btn btn-outline-secondary btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#modalAntrean{{ $kategoris->firstWhere('nama_layanan', $row->kategori_layanan)->id ?? '' }}">Kembali Ke Antrean</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection