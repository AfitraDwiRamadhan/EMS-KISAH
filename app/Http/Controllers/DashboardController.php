<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiMedis;
use App\Models\TenagaMedis;
use App\Models\LoketLayanan;
use App\Models\Jabatan;
use App\Models\GajiOverride;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $all_absensi = AbsensiMedis::all();
        $all_loket = LoketLayanan::all();
        $overrides = GajiOverride::all();
        $anggota = TenagaMedis::where('status', '!=', 'Alumni')->get();
        $jabatans = Jabatan::all()->keyBy('nama_jabatan');
        
        $raw_periode = [];

        // 1. ENGINE PEMROSESAN TANGGAL KRONOLOGIS
        foreach ($all_absensi as $row) {
            try {
                $date = Carbon::createFromFormat('d/m/Y', $row->tanggal);
            } catch (\Exception $e) {
                try {
                    $date = Carbon::parse($row->tanggal);
                } catch (\Exception $e2) {
                    $tgl_parts = explode('-', $row->tanggal);
                    if (count($tgl_parts) > 1 && str_contains($tgl_parts[1], '/')) {
                        $meta = explode('/', $tgl_parts[1]);
                        try {
                            $date = Carbon::createFromFormat('d/m/Y', trim($tgl_parts[0]) . '/' . $meta[1] . '/' . $meta[2]);
                        } catch (\Exception $e3) {
                            $date = Carbon::now();
                        }
                    } else {
                        $date = Carbon::now();
                    }
                }
            }

            $start = $date->copy()->startOfWeek(Carbon::SUNDAY);
            $end = $date->copy()->endOfWeek(Carbon::SATURDAY);
            $sort_key = $start->format('Y-m-d');
            $label = "Minggu " . $start->format('d M') . " - " . $end->format('d M Y');
            
            $raw_periode[$sort_key] = $label;
            
            $row->parsed_date = $date;
            $row->periode_label = $label;
        }

        krsort($raw_periode);
        $list_periode = array_values($raw_periode);

        $selected_periode = $request->input('filter_periode');
        
        // 2. LOGIKA FILTER MINGGUAN UNTUK ABSENSI & LOKET
        if ($selected_periode) {
            $filtered_absensi = $all_absensi->where('periode_label', $selected_periode);
            
            // Filter Loket berdasarkan rentang tanggal dari string label
            $filtered_loket = $all_loket->filter(function($item) use ($selected_periode) {
                $start = $item->created_at->copy()->startOfWeek(Carbon::SUNDAY);
                $end = $item->created_at->copy()->endOfWeek(Carbon::SATURDAY);
                $item_label = "Minggu " . $start->format('d M') . " - " . $end->format('d M Y');
                return $item_label === $selected_periode;
            });
        } else {
            $filtered_absensi = $all_absensi;
            $filtered_loket = $all_loket;
        }

        // 3. STATISTIK KARTU KPI
        $total_anggota = TenagaMedis::count(); 
        $duty_minggu_ini = $filtered_absensi->count();
        $total_jam = $filtered_absensi->sum('durasi');
        $penanganan_pasien = $filtered_absensi->sum('jumlah_pasien');
        $total_loket = $filtered_loket->count();

        // 4. DAFTAR TARIF LAYANAN UNTUK HITUNG PEMASUKAN DINAMIS (IDR)
        $tarif_layanan = [
            'Loket Pendaftaran Medical Check Up' => 150000,
            'Loket Pendaftaran Operasi' => 1500000,
            'Loket Pendaftaran DNA Forensik' => 750000,
            'Loket Pendaftaran USG Kehamilan' => 300000,
            'Loket Pendaftaran Autopsi' => 1000000,
            'Loket Pendaftaran Sunat' => 250000,
            'Loket Pendaftaran Psikiater' => 200000,
            'Loket Konsultasi Dokter Umum' => 100000,
        ];

        // Hitung total pemasukan
        $total_pemasukan = $filtered_loket->sum(function ($item) use ($tarif_layanan) {
            return $tarif_layanan[$item->kategori_layanan] ?? 100000;
        });

        // Hitung estimasi pengeluaran gaji secara dinamis berdasarkan data filter periode
        $pengeluaran_gaji = 0;
        $active_weeks = $selected_periode ? [$selected_periode] : $list_periode;
        
        // Agar efisien, kita konversi pengeluaran gaji ke Rupiah dengan kurs simulasi 1 USD = 15.000 IDR
        $kurs_usd_to_idr = 15000;

        foreach ($active_weeks as $week) {
            $week_logs = $all_absensi->where('periode_label', $week);
            foreach ($anggota as $staff) {
                $has_override = $overrides->where('periode_label', $week)->where('nama_petugas', $staff->nama)->first();

                if ($has_override) {
                    $total_terima_usd = $has_override->total;
                } else {
                    $staff_log = $week_logs->where('nama_petugas', $staff->nama);
                    $logs_by_date = $staff_log->groupBy('tanggal');
                    $hari_memenuhi_syarat = 0;
                    foreach ($logs_by_date as $day_logs) {
                        if ($day_logs->sum('durasi') >= 2) $hari_memenuhi_syarat++;
                    }

                    $jabatan_info = $jabatans->get($staff->jabatan);
                    $gaji_pokok_master = $jabatan_info->gaji_mingguan ?? 0;
                    $bonus_per_tindakan = $jabatan_info->bonus_tindakan ?? 0;

                    $target_lolos = $hari_memenuhi_syarat >= 4;

                    if ($target_lolos) {
                        $gaji_pokok_cair = $gaji_pokok_master;
                        $bonus_total = $staff_log->sum('jumlah_pasien') * $bonus_per_tindakan;
                        $total_terima_usd = $gaji_pokok_cair + $bonus_total;
                    } else {
                        $total_terima_usd = 0;
                    }
                }
                $pengeluaran_gaji += ($total_terima_usd * $kurs_usd_to_idr);
            }
        }

        // 5. DATA GRAFIK: Duty Harian & Keuangan Harian (Berdasarkan Periode)
        $hari_map = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'
        ];
        $duty_harian = ['Senin'=>0, 'Selasa'=>0, 'Rabu'=>0, 'Kamis'=>0, 'Jumat'=>0, 'Sabtu'=>0, 'Minggu'=>0];
        $keuangan_pemasukan_harian = ['Senin'=>0, 'Selasa'=>0, 'Rabu'=>0, 'Kamis'=>0, 'Jumat'=>0, 'Sabtu'=>0, 'Minggu'=>0];
        $keuangan_pengeluaran_harian = ['Senin'=>0, 'Selasa'=>0, 'Rabu'=>0, 'Kamis'=>0, 'Jumat'=>0, 'Sabtu'=>0, 'Minggu'=>0];
        
        foreach ($filtered_absensi as $row) {
            $hari_inggris = $row->parsed_date->format('l');
            $hari_indo = $hari_map[$hari_inggris] ?? 'Minggu';
            $duty_harian[$hari_indo]++;
            
            // Estimasi pengeluaran gaji didistribusikan per hari tugas petugas medis
            // Gaji harian = Gaji per petugas yang bertugas di hari tersebut (proporsional per sesi)
            $jabatan_info = $jabatans->get($row->jabatan);
            $bonus_per_tindakan = $jabatan_info->bonus_tindakan ?? 0;
            $gaji_pokok_harian = ($jabatan_info->gaji_mingguan ?? 0) / 4; // Asumsi 4 hari kerja target
            
            // Cek kelayakan target (sederhananya jika ada tugas)
            $estimasi_gaji_hari_ini = $gaji_pokok_harian + ($row->jumlah_pasien * $bonus_per_tindakan);
            $keuangan_pengeluaran_harian[$hari_indo] += ($estimasi_gaji_hari_ini * $kurs_usd_to_idr);
        }

        foreach ($filtered_loket as $item) {
            $hari_inggris = $item->created_at->format('l');
            $hari_indo = $hari_map[$hari_inggris] ?? 'Minggu';
            $biaya = $tarif_layanan[$item->kategori_layanan] ?? 100000;
            $keuangan_pemasukan_harian[$hari_indo] += $biaya;
        }

        // 6. DATA GRAFIK: Penanganan Pasien BULANAN
        $pasien_bulanan_raw = [];
        foreach ($all_absensi as $row) {
            $bulan_tahun = $row->parsed_date->format('M Y'); 
            $sort_bulan = $row->parsed_date->format('Y-m');  
            
            if(!isset($pasien_bulanan_raw[$sort_bulan])) {
                $pasien_bulanan_raw[$sort_bulan] = ['label' => $bulan_tahun, 'total' => 0];
            }
            $pasien_bulanan_raw[$sort_bulan]['total'] += $row->jumlah_pasien;
        }
        ksort($pasien_bulanan_raw); 
        $pasien_bulanan_raw = array_slice($pasien_bulanan_raw, -12, 12, true); 

        $labels_bulanan = array_column($pasien_bulanan_raw, 'label');
        $data_bulanan = array_column($pasien_bulanan_raw, 'total');

        // 7. DATA GRAFIK: Top 7 Anggota
        $top_anggota = $filtered_absensi->groupBy('nama_petugas')->map(function($group, $key) {
            return (object) [
                'nama_petugas' => $key,
                'total_jam' => $group->sum('durasi')
            ];
        })->sortByDesc('total_jam')->take(7)->values();

        return view('admin.dashboard', compact(
            'total_anggota', 
            'duty_minggu_ini', 
            'total_jam', 
            'total_loket',
            'penanganan_pasien', 
            'total_pemasukan', 
            'pengeluaran_gaji',
            'list_periode',
            'selected_periode',
            'duty_harian',
            'keuangan_pemasukan_harian',
            'keuangan_pengeluaran_harian',
            'labels_bulanan',
            'data_bulanan',
            'top_anggota'
        ));
    }
}