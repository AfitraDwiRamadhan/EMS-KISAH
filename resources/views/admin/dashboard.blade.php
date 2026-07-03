@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 px-md-4 py-3" style="min-height: 100vh;">
    
    <!-- HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="m-0 fw-bolder text-dark" style="letter-spacing: -0.5px;">EMS KISAH DASHBOARD</h3>
            <p class="text-muted small m-0">EMS KISAH • Overview Beranda Utama</p>
        </div>
        <div class="d-flex flex-wrap gap-2 w-100 w-sm-auto">
            <div class="badge text-dark border p-2 shadow-sm d-flex align-items-center font-monospace flex-grow-1 justify-content-center" style="font-size: 0.8rem;">
                <i class="fa-regular fa-calendar text-primary me-2"></i> {{ date('d M Y') }}
            </div>
            <div class="badge text-dark border p-2 shadow-sm d-flex align-items-center font-monospace flex-grow-1 justify-content-center" style="font-size: 0.8rem;">
                <i class="fa-regular fa-clock text-primary me-2"></i> <span id="clock">00:00:00</span>
            </div>
        </div>
    </div>

    <!-- FILTER MINGGUAN -->
    <div class="card border-0 shadow-sm mb-4 rounded-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.dashboard') }}" method="GET" class="row align-items-center m-0 gy-3">
                <div class="col-12 col-lg-7">
                    <label class="form-label small fw-bold text-dark mb-2"><i class="fa-solid fa-filter text-primary me-1"></i> Dashboard Time Filter</label>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <select name="filter_periode" class="form-select border-primary fw-bold text-primary font-monospace shadow-sm w-100" onchange="this.form.submit()" style="font-size: 0.85rem;">
                            <option value="">-- Tampilkan Seluruh Waktu (All Time) --</option>
                            @foreach($list_periode as $periode)
                                <option value="{{ $periode }}" {{ $selected_periode == $periode ? 'selected' : '' }}>{{ $periode }}</option>
                            @endforeach
                        </select>
                        @if($selected_periode)
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-danger btn-sm px-3 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-xmark"></i> <span class="d-none d-sm-inline ms-2">Reset</span>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-lg-5 text-start text-lg-end mt-2 mt-lg-0">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 font-monospace w-100 w-lg-auto d-block d-lg-inline-block text-truncate">
                        Status: {{ $selected_periode ? $selected_periode : 'Semua Periode' }}
                    </span>
                </div>
            </form>
        </div>
    </div>

    <!-- ZONA KARTU KPI (Grid Formasi 4 dan 3) -->
    <div class="row g-3 mb-4">
        <!-- BARIS 1: 4 KARTU -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-4 border-start border-4 border-primary">
                <div class="card-body p-3">
                    <div class="text-primary bg-primary bg-opacity-10 d-inline-block p-2 rounded-3 mb-3">
                        <i class="fa-solid fa-users-medical fa-lg"></i>
                    </div>
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">TOTAL ANGGOTA</div>
                    <h3 class="fw-bolder text-dark mb-1 font-monospace">{{ $total_anggota }}</h3>
                    <div class="text-muted small" style="font-size: 0.75rem;">Anggota terdaftar</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-4 border-start border-4 border-warning">
                <div class="card-body p-3">
                    <div class="text-warning bg-warning bg-opacity-10 d-inline-block p-2 rounded-3 mb-3">
                        <i class="fa-solid fa-briefcase text-warning fa-lg"></i>
                    </div>
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">TOTAL SESI DUTY</div>
                    <h3 class="fw-bolder text-dark mb-1 font-monospace">{{ $duty_minggu_ini }}</h3>
                    <div class="text-muted small" style="font-size: 0.75rem;">Pada periode aktif</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-4 border-start border-4 border-purple" style="border-left-color: #6f42c1 !important;">
                <div class="card-body p-3">
                    <div class="text-purple d-inline-block p-2 rounded-3 mb-3" style="color: #6f42c1; background-color: rgba(111, 66, 193, 0.1);">
                        <i class="fa-regular fa-clock fa-lg"></i>
                    </div>
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">JAM KERJA KOTOR</div>
                    <h3 class="fw-bolder text-dark mb-1 font-monospace">{{ number_format($total_jam, 1) }}j</h3>
                    <div class="text-muted small" style="font-size: 0.75rem;">Akumulasi jam kerja</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 rounded-4 border-start border-4 border-info">
                <div class="card-body p-3">
                    <div class="text-info bg-info bg-opacity-10 d-inline-block p-2 rounded-3 mb-3">
                        <i class="fa-solid fa-hospital-user fa-lg text-info"></i>
                    </div>
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">LOKET LAYANAN</div>
                    <h3 class="fw-bolder text-dark mb-1 font-monospace">{{ $total_loket }}</h3>
                    <div class="text-muted small" style="font-size: 0.75rem;">Form pendaftaran publik</div>
                </div>
            </div>
        </div>

        <!-- BARIS 2: 3 KARTU -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 rounded-4 border-start border-4 border-orange" style="border-left-color: #fd7e14 !important;">
                <div class="card-body p-3">
                    <div class="text-orange d-inline-block p-2 rounded-3 mb-3" style="color: #fd7e14; background-color: rgba(253, 126, 20, 0.1);">
                        <i class="fa-solid fa-heart-pulse fa-lg"></i>
                    </div>
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">PENANGANAN PASIEN</div>
                    <h3 class="fw-bolder text-dark mb-1 font-monospace">{{ $penanganan_pasien }}</h3>
                    <div class="text-muted small" style="font-size: 0.75rem;">Pasien terevakuasi</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 rounded-4 border-start border-4 border-success">
                <div class="card-body p-3">
                    <div class="text-success bg-success bg-opacity-10 d-inline-block p-2 rounded-3 mb-3">
                        <i class="fa-solid fa-file-invoice-dollar fa-lg"></i>
                    </div>
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">TOTAL PEMASUKAN</div>
                    <h3 class="fw-bolder text-dark mb-1 font-monospace text-truncate">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</h3>
                    <div class="text-muted small" style="font-size: 0.75rem;">Kas masuk loket</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100 rounded-4 border-start border-4 border-danger">
                <div class="card-body p-3">
                    <div class="text-danger bg-danger bg-opacity-10 d-inline-block p-2 rounded-3 mb-3">
                        <i class="fa-solid fa-hand-holding-dollar fa-lg"></i>
                    </div>
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">PENGELUARAN GAJI</div>
                    <h3 class="fw-bolder text-dark mb-1 font-monospace text-truncate">Rp {{ number_format($pengeluaran_gaji, 0, ',', '.') }}</h3>
                    <div class="text-muted small" style="font-size: 0.75rem;">Estimasi pengeluaran</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ZONA GRAFIK -->
    <div class="row g-4">
        <!-- CHART: DUTY HARIAN -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 p-md-4">
                    <h6 class="fw-bolder text-dark mb-4 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;"><i class="fa-solid fa-chart-line text-primary me-2"></i>Frekuensi Duty Harian</h6>
                    <div class="chart-container" style="position: relative; height: 250px; width: 100%;">
                        <canvas id="chartDutyHarian"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- CHART: PASIEN BULANAN -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 p-md-4">
                    <h6 class="fw-bolder text-dark mb-4 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;"><i class="fa-solid fa-chart-area text-success me-2"></i>Penanganan Pasien (All Time)</h6>
                    <div class="chart-container" style="position: relative; height: 250px; width: 100%;">
                        <canvas id="chartPasienHarian"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- CHART: KEUANGAN -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 p-md-4">
                    <h6 class="fw-bolder text-dark mb-4 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;"><i class="fa-solid fa-scale-balanced text-warning me-2"></i>Simulasi Keuangan</h6>
                    <div class="chart-container" style="position: relative; height: 250px; width: 100%;">
                        <canvas id="chartKeuangan"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- CHART: TOP 7 ANGGOTA -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 p-md-4">
                    <h6 class="fw-bolder text-dark mb-4 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;"><i class="fa-solid fa-ranking-star text-danger me-2"></i>Top 7 Jam Kerja Anggota</h6>
                    <div class="chart-container" style="position: relative; height: 250px; width: 100%;">
                        <canvas id="chartTopAnggota"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // System Clock Live Engine
    function updateClock() {
        const now = new Date();
        const timeString = now.toTimeString().split(' ')[0];
        document.getElementById('clock').textContent = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Responsive Global Chart Options
    Chart.defaults.maintainAspectRatio = false;
    Chart.defaults.responsive = true;

    // Chart 1: Duty Harian Mingguan
    const ctxDuty = document.getElementById('chartDutyHarian').getContext('2d');
    new Chart(ctxDuty, {
        type: 'line',
        data: {
            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
            datasets: [{
                label: 'Jumlah Sesi',
                data: [
                    {{ $duty_harian['Senin'] ?? 0 }}, {{ $duty_harian['Selasa'] ?? 0 }}, {{ $duty_harian['Rabu'] ?? 0 }}, 
                    {{ $duty_harian['Kamis'] ?? 0 }}, {{ $duty_harian['Jumat'] ?? 0 }}, {{ $duty_harian['Sabtu'] ?? 0 }}, {{ $duty_harian['Minggu'] ?? 0 }}
                ],
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.05)',
                fill: true,
                tension: 0.3
            }]
        }
    });

    // Chart 2: Penanganan Pasien BULANAN
    const ctxPasien = document.getElementById('chartPasienHarian').getContext('2d');
    new Chart(ctxPasien, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labels_bulanan ?? []) !!},
            datasets: [{
                label: 'Pasien Dievakuasi',
                data: {!! json_encode($data_bulanan ?? []) !!},
                backgroundColor: '#198754',
                borderRadius: 4
            }]
        }
    });

    // Chart 3: Finansial Matrix Tracker
    const ctxUang = document.getElementById('chartKeuangan').getContext('2d');
    new Chart(ctxUang, {
        type: 'line',
        data: {
            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
            datasets: [
                { 
                    label: 'Pemasukan', 
                    data: [
                        {{ $keuangan_pemasukan_harian['Senin'] ?? 0 }}, 
                        {{ $keuangan_pemasukan_harian['Selasa'] ?? 0 }}, 
                        {{ $keuangan_pemasukan_harian['Rabu'] ?? 0 }}, 
                        {{ $keuangan_pemasukan_harian['Kamis'] ?? 0 }}, 
                        {{ $keuangan_pemasukan_harian['Jumat'] ?? 0 }}, 
                        {{ $keuangan_pemasukan_harian['Sabtu'] ?? 0 }}, 
                        {{ $keuangan_pemasukan_harian['Minggu'] ?? 0 }}
                    ], 
                    borderColor: '#ffc107', 
                    backgroundColor: 'rgba(255, 193, 7, 0.05)',
                    fill: true, 
                    tension: 0.2 
                },
                { 
                    label: 'Pengeluaran', 
                    data: [
                        {{ $keuangan_pengeluaran_harian['Senin'] ?? 0 }}, 
                        {{ $keuangan_pengeluaran_harian['Selasa'] ?? 0 }}, 
                        {{ $keuangan_pengeluaran_harian['Rabu'] ?? 0 }}, 
                        {{ $keuangan_pengeluaran_harian['Kamis'] ?? 0 }}, 
                        {{ $keuangan_pengeluaran_harian['Jumat'] ?? 0 }}, 
                        {{ $keuangan_pengeluaran_harian['Sabtu'] ?? 0 }}, 
                        {{ $keuangan_pengeluaran_harian['Minggu'] ?? 0 }}
                    ], 
                    borderColor: '#dc3545', 
                    backgroundColor: 'rgba(220, 53, 69, 0.05)',
                    fill: true, 
                    tension: 0.2 
                }
            ]
        }
    });

    // Chart 4: Top 7 Aktivitas Anggota
    const ctxTop = document.getElementById('chartTopAnggota').getContext('2d');
    new Chart(ctxTop, {
        type: 'bar',
        data: {
            labels: {!! json_encode(isset($top_anggota) ? $top_anggota->pluck('nama_petugas') : []) !!},
            datasets: [{
                label: 'Jam Kerja',
                data: {!! json_encode(isset($top_anggota) ? $top_anggota->pluck('total_jam') : []) !!},
                backgroundColor: 'rgba(111, 66, 193, 0.8)',
                borderRadius: 4
            }]
        },
        options: { indexAxis: 'y' }
    });
</script>
@endsection