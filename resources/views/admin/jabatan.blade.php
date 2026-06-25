@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 px-md-4 py-3" style="min-height: 100vh;">
    
    <!-- HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="m-0 fw-bolder text-dark" style="letter-spacing: -0.5px;">STRUKTUR JABATAN & GAJI</h3>
            <p class="text-muted small m-0">Manajemen Role, Tingkatan, dan Gaji Pokok Mingguan EMS</p>
        </div>
        <div class="w-100 w-md-auto">
            <button type="button" class="btn btn-primary fw-bold px-3 py-2 text-white border-0 shadow-sm rounded-3 w-100" data-bs-toggle="modal" data-bs-target="#modalTambahJabatan">
                <i class="fa-solid fa-plus me-2"></i> Buat Jabatan Baru
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-9 col-lg-12">
            <!-- MAIN TABLE -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <!-- Tambahkan text-nowrap agar tabel tidak tergencet di HP -->
                        <table class="table table-hover align-middle mb-0 w-100 text-nowrap" style="font-size: 0.9rem;">
                            <thead class="table-light text-secondary border-bottom border-light-subtle">
                                <tr>
                                    <th class="text-center py-3 px-3" width="60">No</th>
                                    <th>NAMA JABATAN / ROLE</th>
                                    <th class="text-start">GAJI MINGGUAN (USD)</th>
                                    <th class="text-center">DIGUNAKAN OLEH</th>
                                    <th class="text-center" width="120">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jabatans as $index => $row)
                                <tr class="border-bottom border-light-subtle">
                                    <td class="text-center text-muted px-3">{{ $index + 1 }}</td>
                                    <td class="fw-bold text-dark"><i class="fa-solid fa-sitemap text-primary me-2"></i> {{ $row->nama_jabatan }}</td>
                                    <td class="fw-bolder text-success font-monospace fs-6">
                                        $ {{ number_format($row->gaji_mingguan, 2, '.', ',') }}
                                    </td>
                                    <td class="text-center">
                                        @if($row->total_anggota > 0)
                                            <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill">{{ $row->total_anggota }} Anggota</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary px-3 py-1 rounded-pill">Kosong</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-light text-primary btn-sm shadow-sm border" data-bs-toggle="modal" data-bs-target="#modalEditJabatan{{ $row->id }}"><i class="fa-solid fa-pen"></i></button>
                                        <form action="{{ route('admin.jabatan.destroy', $row->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-light text-danger btn-sm shadow-sm border" onclick="return confirm('Yakin ingin menghapus role {{ $row->nama_jabatan }}?')"><i class="fa-solid fa-trash-can"></i></button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- MODAL EDIT DATA -->
                                <div class="modal fade" id="modalEditJabatan{{ $row->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-dark text-white border-0 py-3">
                                                <h6 class="modal-title fw-bold">Edit Jabatan & Gaji</h6>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.jabatan.update', $row->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-body p-4">
                                                    <label class="form-label small fw-bold">Nama Jabatan</label>
                                                    <input type="text" name="nama_jabatan" class="form-control mb-3" value="{{ $row->nama_jabatan }}" required>
                                                    
                                                    <label class="form-label small fw-bold">Gaji Mingguan (USD)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light text-success fw-bold">$</span>
                                                        <input type="number" step="0.01" name="gaji_mingguan" class="form-control fw-bold font-monospace" value="{{ $row->gaji_mingguan }}" required>
                                                    </div>

                                                    @if($row->total_anggota > 0)
                                                        <div class="alert alert-warning mt-3 mb-0" style="font-size: 0.75rem;">
                                                            <i class="fa-solid fa-circle-info me-1"></i> Perubahan ini akan memengaruhi perhitungan gaji <strong>{{ $row->total_anggota }} anggota</strong>.
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer border-0 bg-gray-subtle">
                                                    <button type="submit" class="btn btn-primary w-100 fw-bold">Update & Sinkronkan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr><td colspan="5" class="text-center py-5">Belum ada role/jabatan terdaftar.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH JABATAN -->
<div class="modal fade" id="modalTambahJabatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h6 class="modal-title fw-bold"><i class="fa-solid fa-plus text-warning me-2"></i>Jabatan Baru</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.jabatan.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-gray">
                    <label class="form-label small fw-bold text-dark">Nama Jabatan (Role) <span class="text-danger">*</span></label>
                    <input type="text" name="nama_jabatan" class="form-control mb-3" placeholder="Cth: Head Paramedic" required>
                    
                    <label class="form-label small fw-bold text-dark">Gaji Pokok Mingguan ($) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-success fw-bold">$</span>
                        <input type="number" step="0.01" name="gaji_mingguan" class="form-control fw-bold font-monospace" placeholder="7600" required>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-gray">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Tambahkan Jabatan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection