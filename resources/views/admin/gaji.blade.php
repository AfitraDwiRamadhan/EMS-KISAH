@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3" style="min-height: 100vh;">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="m-0 fw-bolder text-white" style="letter-spacing: -0.5px;">REKAPITULASI & DISTRIBUSI GAJI</h3>
            <p class="text-secondary small m-0">EMS KISAH • Klik pada kartu periode untuk membuka rincian rekam medis.</p>
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

    <div class="row g-4">
        @foreach($cards as $card)
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 bg-dark text-white border-start border-4 {{ $card['tipe'] == 'overall' ? 'border-primary' : 'border-info' }}">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge {{ $card['tipe'] == 'overall' ? 'bg-primary text-white' : 'bg-info text-white' }} rounded-pill px-3 py-1 fw-bold font-monospace" style="font-size: 0.75rem;">
                                {{ strtoupper($card['tipe']) }} DATA
                            </span>
                            <div class="text-secondary"><i class="fa-solid fa-wallet {{ $card['tipe'] == 'overall' ? 'text-primary' : 'text-info' }}"></i></div>
                        </div>
                        <h5 class="fw-bolder text-white mb-1">{{ $card['label'] }}</h5>
                        <p class="text-secondary small mb-4">Total anggaran distribusi upah komparatif operasional tim.</p>
                    </div>

                    <div>
                        <div class="bg-black p-3 rounded-3 mb-3 border-start border-3 border-dark-subtle">
                            <div class="text-dark small fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">TOTAL ALOKASI ANGGARAN</div>
                            <h4 class="fw-extrabold text-dark font-monospace m-0">$ {{ number_format($card['total_pengeluaran'], 2, '.', ',') }}</h4>
                        </div>
                        <button type="button" class="btn {{ $card['tipe'] == 'overall' ? 'btn-primary' : 'btn-info' }} w-100 fw-bold rounded-3 text-white border-0 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalPeriode_{{ $card['id_slug'] }}">
                            <i class="fa-solid fa-folder-open me-2"></i> Buka Rincian Gaji
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalPeriode_{{ $card['id_slug'] }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-black border-0 py-3 px-4">
                        <div>
                            <h5 class="modal-title fw-bold m-0 text-dark"><i class="fa-solid fa-file-invoice-dollar text-primary me-2"></i>Rincian Penggajian: {{ $card['label'] }}</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 w-100" style="font-size: 0.88rem;">
                                <thead class="table-light text-secondary border-bottom">
                                    <tr>
                                        <th class="ps-4 py-3">NAMA TENAGA MEDIS</th>
                                        <th>JABATAN</th>
                                        <th class="text-center">AKUMULASI WAKTU</th>
                                        <th class="text-center">VALIDASI TARGET (2J X 4H)</th>
                                        <th class="text-end">GAJI POKOK</th>
                                        <th class="text-end">BONUS</th>
                                        <th class="text-end">TOTAL TERIMA</th>
                                        <th class="text-center" width="80"><i class="fa-solid fa-gears"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($card['staff'] as $staff_index => $row)
                                    <tr class="border-bottom border-light-subtle">
                                        <td class="ps-4 fw-bold text-dark">{{ $row['nama'] }}</td>
                                        <td><span class="badge bg-light text-secondary border px-2 py-1">{{ $row['jabatan'] }}</span></td>
                                        <td class="text-center font-monospace fw-bold">{{ number_format($row['total_jam'], 1) }} Jam</td>
                                        <td class="text-center font-monospace">
                                            @if($card['tipe'] == 'overall')
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill fw-bold">KUMULATIF</span>
                                            @else
                                                <span class="badge {{ $row['target_lolos'] ? 'bg-success-subtle text-success border-success-subtle' : 'bg-danger-subtle text-danger border-danger-subtle' }} border px-3 py-1 rounded-pill fw-bold">
                                                    {{ $row['target_lolos'] ? 'Lolos' : 'Gugur' }} ({{ $row['hari_aktif'] }}/4H)
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end font-monospace">$ {{ number_format($row['gaji_pokok'], 2, '.', ',') }}</td>
                                        <td class="text-end font-monospace text-warning fw-bold">$ {{ number_format($row['bonus'], 2, '.', ',') }}</td>
                                        <td class="text-end font-monospace fw-bolder text-dark fs-6">$ {{ number_format($row['total'], 2, '.', ',') }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-light text-primary btn-sm border shadow-sm rounded-circle" style="width: 30px; height: 30px; padding:0;" data-bs-toggle="modal" data-bs-target="#modalEditGaji_{{ $card['id_slug'] }}_{{ $staff_index }}">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                <tfoot class="table-light border-top border-dark-subtle fw-bolder text-dark">
                                    <tr>
                                        <td colspan="6" class="ps-4 py-3 text-start">TOTAL ESTIMASI KELUAR ANGGARAN PERIODE</td>
                                        <td class="text-end font-monospace text-primary fs-5">$ {{ number_format($card['total_pengeluaran'], 2, '.', ',') }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@foreach($cards as $card)
    @foreach($card['staff'] as $staff_index => $row)
    <div class="modal fade" id="modalEditGaji_{{ $card['id_slug'] }}_{{ $staff_index }}"  tabindex="-1" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-0 py-3">
                    <h6 class="modal-title fw-bold text-dark"><i class="fa-solid fa-user-gear me-2 text-primary"></i>Kunci Gaji Manual</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.gaji.storeOverride') }}" method="POST">
                    @csrf
                    <input type="hidden" name="periode_label" value="{{ $card['label'] }}">
                    <input type="hidden" name="nama_petugas" value="{{ $row['nama'] }}">

                    <div class="modal-body p-4" style="font-size:0.85rem;">
                        <p class="mb-3 text-muted">Mengubah data finansial resmi untuk <strong>{{ $row['nama'] }}</strong>.</p>

                        <label class="form-label fw-bold mb-1">Jabatan Periode Ini</label>
                        <input type="text" name="jabatan" class="form-control form-control-sm mb-2 fw-semibold" value="{{ $row['jabatan'] }}" required>

                        <label class="form-label fw-bold mb-1">Akumulasi Waktu (Jam)</label>
                        <input type="number" step="0.1" id="override_jam_{{ $card['id_slug'] }}_{{ $staff_index }}" name="total_jam" class="form-control form-control-sm mb-2 font-monospace" value="{{ $row['total_jam'] }}" required>

                        <label class="form-label fw-bold mb-1">Validasi Target (Hari Kerja)</label>
                        <input type="number" id="override_hari_{{ $card['id_slug'] }}_{{ $staff_index }}" name="hari_aktif" class="form-control form-control-sm mb-2 font-monospace" value="{{ $row['hari_aktif'] }}" required>

                        <label class="form-label fw-bold mb-1">Gaji Pokok ($)</label>
                        <input type="number" step="0.01" id="override_pokok_{{ $card['id_slug'] }}_{{ $staff_index }}" name="gaji_pokok" class="form-control form-control-sm mb-2 font-monospace fw-bold text-success" value="{{ $row['gaji_pokok'] }}" required oninput="calculateTotalTotal('{{ $card['id_slug'] }}', '{{ $staff_index }}')">

                        <label class="form-label fw-bold mb-1">Bonus Tindakan / Sesi ($)</label>
                        <input type="number" step="0.01" id="override_bonus_{{ $card['id_slug'] }}_{{ $staff_index }}" name="bonus" class="form-control form-control-sm mb-2 font-monospace fw-bold text-warning" value="{{ $row['bonus'] }}" required oninput="calculateTotalTotal('{{ $card['id_slug'] }}', '{{ $staff_index }}')">

                        <label class="form-label fw-bold mb-1 text-primary">Total Bersih Diterima ($)</label>
                        <input type="number" step="0.01" id="override_total_{{ $card['id_slug'] }}_{{ $staff_index }}" name="total" class="form-control form-control-sm font-monospace fw-bolder bg-white text-dark" value="{{ $row['total'] }}" readonly required>
                    </div>
                    <div class="modal-footer border-0 bg-light py-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold btn-sm">Simpan Modifikasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endforeach

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

    // Rumus Matematika Instan Penjumlahan Total Form Override
    function calculateTotalTotal(slug, index) {
        const pokok = parseFloat(document.getElementById(`override_pokok_${slug}_${index}`).value) || 0;
        const bonus = parseFloat(document.getElementById(`override_bonus_${slug}_${index}`).value) || 0;
        document.getElementById(`override_total_${slug}_${index}`).value = (pokok + bonus).toFixed(2);
    }
</script>
@endsection