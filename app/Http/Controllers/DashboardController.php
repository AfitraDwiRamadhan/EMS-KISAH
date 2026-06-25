<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiMedis;
use App\Models\TenagaMedis;
use App\Models\LoketLayanan; // Tambahkan pemanggilan model ini
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $all_absensi = AbsensiMedis::all();
        $all_loket = LoketLayanan::all(); // Tarik semua data pendaftaran loket
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
            
            // Filter Loket berdasarkan rentang tanggal dari string label (Misal: "Minggu 21 Jun - 27 Jun 2026")
            // Karena Loket menggunakan timestamps asli Laravel (created_at)
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
        $total_loket = $filtered_loket->count(); // Data KPI Loket Baru
        $total_pemasukan = 0; 
        $pengeluaran_gaji = 0; 

        // 4. DATA GRAFIK: Duty Harian (Berdasarkan Minggu yang Dipilih)
        $hari_map = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'
        ];
        $duty_harian = ['Senin'=>0, 'Selasa'=>0, 'Rabu'=>0, 'Kamis'=>0, 'Jumat'=>0, 'Sabtu'=>0, 'Minggu'=>0];
        
        foreach ($filtered_absensi as $row) {
            $hari_inggris = $row->parsed_date->format('l');
            $hari_indo = $hari_map[$hari_inggris];
            $duty_harian[$hari_indo]++;
        }

        // 5. DATA GRAFIK: Penanganan Pasien BULANAN
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

        // 6. DATA GRAFIK: Top 7 Anggota
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
            'total_loket', // Variabel baru
            'penanganan_pasien', 
            'total_pemasukan', 
            'pengeluaran_gaji',
            'list_periode',
            'selected_periode',
            'duty_harian',
            'labels_bulanan',
            'data_bulanan',
            'top_anggota'
        ));
    }
}