@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3" style="min-height: 100vh;">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h3 class="m-0 fw-bolder text-dark" style="letter-spacing: -0.5px;">Dokumentasi EMS</h3>
            <p class="text-muted small m-0">Kelola galeri Jejak Pengabdian untuk dokumentasi kegiatan EMS KISAH.</p>
        </div>

        <button type="button" class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahDokumentasi">
            <i class="fa-solid fa-cloud-arrow-up me-2"></i>Unggah Dokumentasi
        </button>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-primary h-100">
                <div class="card-body p-3">
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">TOTAL DOKUMENTASI</div>
                    <h3 class="fw-bolder text-dark mb-0 font-monospace">{{ $dokumentasi->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-success h-100">
                <div class="card-body p-3">
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">KATEGORI TERPAKAI</div>
                    <h3 class="fw-bolder text-dark mb-0 font-monospace">{{ $dokumentasi->pluck('kategori')->unique()->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-danger h-100">
                <div class="card-body p-3">
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">UPLOAD TERBARU</div>
                    <h6 class="fw-bolder text-dark mb-0">{{ $dokumentasi->first()?->judul ?? 'Belum ada data' }}</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header border-0 p-4 pb-0">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <div>
                    <h5 class="fw-bolder text-dark mb-1">Galeri Jejak Pengabdian</h5>
                    <p class="text-muted small mb-0">Foto akan ditampilkan berdasarkan tanggal kegiatan terbaru.</p>
                </div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                    {{ $dokumentasi->count() }} Foto
                </span>
            </div>
        </div>

        <div class="card-body p-4">
            @if($dokumentasi->isNotEmpty())
                <div class="row g-4">
                    @foreach($dokumentasi as $item)
                        <div class="col-xxl-3 col-xl-4 col-md-6">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                                <div class="ratio ratio-4x3 bg-light">
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul }}" class="w-100 h-100 object-fit-cover">
                                </div>

                                <div class="card-body p-3 d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                            {{ $item->kategori }}
                                        </span>
                                        <span class="text-muted small font-monospace">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                        </span>
                                    </div>

                                    <h6 class="fw-bolder text-dark mb-2">{{ $item->judul }}</h6>
                                    <p class="text-muted small mb-3 flex-grow-1" style="min-height: 42px;">
                                        {{ \Illuminate\Support\Str::limit($item->deskripsi ?: 'Tidak ada deskripsi.', 95) }}
                                    </p>

                                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-auto">
                                        <button type="button"
                                                class="btn btn-outline-primary btn-sm fw-bold"
                                                data-bs-toggle="modal"
                                                data-bs-target="#previewDokumentasiModal"
                                                data-title="{{ $item->judul }}"
                                                data-category="{{ $item->kategori }}"
                                                data-date="{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}"
                                                data-description="{{ $item->deskripsi ?: 'Tidak ada deskripsi.' }}"
                                                data-image="{{ asset('storage/' . $item->foto) }}">
                                            <i class="fa-solid fa-eye me-1"></i> Detail
                                        </button>

                                        <form action="{{ route('admin.dokumentasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus dokumentasi ini permanen? File foto juga akan terhapus.');">
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
                    @endforeach
                </div>
            @else
                <div class="text-center py-5 border border-2 border-dashed rounded-4 bg-grey-100">
                    <div class="text-primary bg-primary bg-opacity-10 rounded-4 d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                        <i class="fa-solid fa-photo-film fa-lg"></i>
                    </div>
                    <h6 class="fw-bolder text-dark mb-1">Belum ada dokumentasi</h6>
                    <p class="text-muted small mb-0">Klik tombol unggah untuk menambahkan Jejak Pengabdian pertama.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahDokumentasi" tabindex="-1" aria-labelledby="modalTambahDokumentasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('admin.dokumentasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title fw-bolder" id="modalTambahDokumentasiLabel">Unggah Dokumentasi Baru</h5>
                        <p class="text-muted small mb-0">Lengkapi informasi kegiatan dan unggah foto dokumentasi.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Judul Dokumentasi</label>
                            <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Kategori</label>
                            <select name="kategori" class="form-select" required>
                                @foreach($kategori_list as $kat)
                                    <option value="{{ $kat }}" {{ old('kategori') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Tanggal Kegiatan</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">File Foto</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" required>
                            <div class="form-text">Format JPG, JPEG, PNG, atau GIF. Maksimal 5 MB.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-dark">Deskripsi</label>
                            <textarea name="deskripsi" rows="4" class="form-control">{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="previewDokumentasiModal" tabindex="-1" aria-labelledby="previewDokumentasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow overflow-hidden">
            <img id="previewDokumentasiImage" src="" alt="Preview dokumentasi" class="w-100 bg-light object-fit-cover" style="max-height: 420px;">
            <div class="modal-body p-4">
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle" id="previewDokumentasiCategory"></span>
                    <span class="badge bg-dark-subtle text-white-50 border-secondary" id="previewDokumentasiDate"></span>
                </div>
                <h5 class="fw-bolder text-dark mb-2" id="previewDokumentasiTitle"></h5>
                <p class="text-muted mb-0" id="previewDokumentasiDescription"></p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const previewModal = document.getElementById('previewDokumentasiModal');

        previewModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            document.getElementById('previewDokumentasiImage').src = button.dataset.image;
            document.getElementById('previewDokumentasiTitle').textContent = button.dataset.title;
            document.getElementById('previewDokumentasiCategory').textContent = button.dataset.category;
            document.getElementById('previewDokumentasiDate').textContent = button.dataset.date;
            document.getElementById('previewDokumentasiDescription').textContent = button.dataset.description;
        });

        previewModal.addEventListener('hidden.bs.modal', function () {
            document.getElementById('previewDokumentasiImage').removeAttribute('src');
        });
    });
</script>
@endsection
