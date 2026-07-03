@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 px-md-4 py-3" style="min-height: 100vh;">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="m-0 fw-bolder text-dark" style="letter-spacing: -0.5px;">DATA TENAGA MEDIS</h3>
            <p class="text-muted small m-0">Pengelolaan Anggota EMS KISAH</p>
        </div>
        <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-md-auto">
            <button type="button" class="btn btn-warning fw-bold px-3 py-2 text-dark border-0 shadow-sm rounded-3 w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#modalBulkMedis">
                <i class="fa-solid fa-users-viewfinder me-2"></i> Auto-Input
            </button>
            <button type="button" class="btn btn-primary fw-bold px-3 py-2 text-white border-0 shadow-sm rounded-3 w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#modalTambahMedis">
                <i class="fa-solid fa-user-plus me-2"></i> Input Manual
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 w-100 text-nowrap" style="font-size: 0.88rem;">
                    <thead class="table-light text-secondary border-bottom border-light-subtle">
                        <tr>
                            <th class="text-center py-3 px-3" width="60">No</th>
                            <th>NAMA ANGGOTA</th>
                            <th class="text-center">USIA</th>
                            <th class="text-center">JABATAN</th>
                            <th class="text-center">STATUS</th>
                            <th class="text-center" width="120">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medis as $index => $row)
                        <tr class="border-bottom border-light-subtle">
                            <td class="text-center text-muted px-3">{{ $index + 1 }}</td>
                            <td>
                                <button type="button" class="btn btn-link text-decoration-none fw-bold text-dark p-0 text-start" 
                                        data-bs-toggle="modal" data-bs-target="#modalDetail{{ $row->id }}">
                                    {{ $row->nama }} <i class="fa-solid fa-circle-info text-primary ms-1" style="font-size: 0.7rem;"></i>
                                </button>
                            </td>
                            <td class="text-center">{{ $row->usia ?? '-' }} Thn</td>
                            <td class="text-center">
                                @if(str_contains(strtolower($row->jabatan), 'intern'))
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-1 rounded-pill"><i class="fa-solid fa-user-graduate me-1"></i> {{ $row->jabatan }}</span>
                                @elseif(str_contains(strtolower($row->jabatan), 'head') || str_contains(strtolower($row->jabatan), 'deputy'))
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill"><i class="fa-solid fa-star me-1"></i> {{ $row->jabatan }}</span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill"><i class="fa-solid fa-user-doctor me-1"></i> {{ $row->jabatan }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($row->status === 'Aktif')
                                    <span class="badge bg-success px-3 py-1 rounded-pill">Aktif</span>
                                @elseif($row->status === 'Cuti')
                                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill">Cuti</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-1 rounded-pill">Alumni</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-light text-primary btn-sm shadow-sm border" data-bs-toggle="modal" data-bs-target="#modalEditMedis{{ $row->id }}"><i class="fa-solid fa-pen"></i></button>
                                <form action="{{ route('admin.tenaga_medis.destroy', $row->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-light text-danger btn-sm shadow-sm border" onclick="return confirm('Hapus anggota {{ addslashes($row->nama) }}?')"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-5">Belum ada anggota.</td></tr>
                        @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CONTAINER UNTUK MODAL DETAIL & EDIT (VALID HTML) -->
@foreach($medis as $row)
<div class="modal fade" id="modalDetail{{ $row->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 py-3">
                <h6 class="modal-title fw-bold text-white">Detail: {{ $row->nama }}</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="small text-white-50 mb-1">Username Roblox</p>
                <div class="bg-slate-900 text-white p-2 rounded mb-3 font-monospace fw-bold border border-secondary text-break">{{ $row->username_roblox ?? '-' }}</div>
                <p class="small text-white-50 mb-1">Username Discord</p>
                <div class="bg-slate-900 text-white p-2 rounded font-monospace fw-bold border border-secondary text-break">{{ $row->username_discord ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditMedis{{ $row->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 py-3">
                <h6 class="modal-title fw-bold text-white">Edit Profil</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.tenaga_medis.update', $row->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-8">
                            <label class="form-label small fw-bold text-white-50">Nama IC</label>
                            <input type="text" name="nama" class="form-control" value="{{ $row->nama }}" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold text-white-50">Usia IC</label>
                            <input type="number" name="usia" class="form-control" value="{{ $row->usia }}">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-white-50">Username Roblox</label>
                            <input type="text" name="username_roblox" class="form-control" value="{{ $row->username_roblox }}">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-white-50">Username Discord</label>
                            <input type="text" name="username_discord" class="form-control" value="{{ $row->username_discord }}">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-white-50">Jabatan</label>
                            <select name="jabatan" class="form-select text-dark">
                                @foreach($jabatans as $jb)
                                    <option value="{{ $jb->nama_jabatan }}" {{ $row->jabatan == $jb->nama_jabatan ? 'selected' : '' }}>{{ $jb->nama_jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-white-50">Status</label>
                            <select name="status" class="form-select text-dark">
                                <option value="Aktif" {{ $row->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Cuti" {{ $row->status == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                                <option value="Alumni" {{ $row->status == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<div class="modal fade" id="modalBulkMedis" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark border-0 py-3">
                <h5 class="modal-title fs-6 fw-bold"><i class="fa-solid fa-bolt text-dark me-2"></i>Auto-Input Biodata</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.tenaga_medis.storeBulk') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-gray">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small mb-1">Set Jabatan Default untuk Input Ini</label>
                        <select name="jabatan_global" class="form-select border-secondary-subtle fw-semibold text-dark">
                            @foreach($jabatans as $jb)
                                <option value="{{ $jb->nama_jabatan }}">{{ $jb->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <textarea class="form-control font-monospace border-secondary-subtle p-3" name="bulk_data" rows="10" required placeholder="Paste data Discord di sini..."></textarea>
                </div>
                <div class="modal-footer border-0 bg-gray py-3">
                    <button type="submit" class="btn btn-warning text-dark fw-bold btn-sm w-100 w-sm-auto"><i class="fa-solid fa-wand-magic-sparkles me-2"></i>Eksekusi Pendaftaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahMedis" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h6 class="modal-title fw-bold"><i class="fa-solid fa-user-plus text-warning me-2"></i>Input Manual</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.tenaga_medis.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-gray">
                    <div class="row g-3">
                        <div class="col-12 col-md-8">
                            <label class="form-label small fw-bold">Nama IC <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" placeholder="Nama IC" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold">Usia IC</label>
                            <input type="number" name="usia" class="form-control" placeholder="Usia">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">Username Roblox</label>
                            <input type="text" name="username_roblox" class="form-control" placeholder="Username Roblox">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">Username Discord</label>
                            <input type="text" name="username_discord" class="form-control" placeholder="Username Discord">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">Jabatan <span class="text-danger">*</span></label>
                            <select name="jabatan" class="form-select" required>
                                @foreach($jabatans as $jb)
                                    <option value="{{ $jb->nama_jabatan }}">{{ $jb->nama_jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="Aktif" selected>Aktif</option>
                                <option value="Cuti">Cuti</option>
                                <option value="Alumni">Alumni</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-gray">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Tambahkan Anggota</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection