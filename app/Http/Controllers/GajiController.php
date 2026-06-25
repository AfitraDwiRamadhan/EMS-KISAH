<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TenagaMedis;
use App\Models\AbsensiMedis;
use App\Models\Jabatan;
use App\Models\GajiOverride;
use Carbon\Carbon;

class GajiController extends Controller
{
    public function index(Request $request)
    {
        $all_absensi = AbsensiMedis::all();
        $overrides = GajiOverride::all();
        $raw_periode = [];

        foreach ($all_absensi as $row) {
            $tgl_clean = trim($row->tanggal);
            if (preg_match('/(\d{1,2})-\d{1,2}\/(\d{1,2})\/(\d{4})/', $tgl_clean, $matches)) {
                $tgl_clean = $matches[1] . '/' . $matches[2] . '/' . $matches[3];
            }
            $tgl_clean = str_replace('-', '/', $tgl_clean);

            try {
                $date = Carbon::createFromFormat('j/n/Y', $tgl_clean);
            } catch (\Exception $e) {
                try {
                    $date = Carbon::parse($tgl_clean);
                } catch (\Exception $e2) {
                    $date = Carbon::now();
                }
            }

            $start = $date->copy()->startOfWeek(Carbon::SUNDAY);
            $end = $date->copy()->endOfWeek(Carbon::SATURDAY);
            
            $sort_key = $start->format('Y-m-d');
            $label = "Minggu " . $start->format('d M') . " - " . $end->format('d M Y');
            
            $raw_periode[$sort_key] = $label;
            $row->periode_label = $label;
        }

        krsort($raw_periode);
        $sorted_weeks = array_values($raw_periode);

        $anggota = TenagaMedis::where('status', '!=', 'Alumni')->get();
        $jabatans = Jabatan::all()->keyBy('nama_jabatan');

        $cards = [];

        // 2. KARTU MINGGUAN
        foreach ($sorted_weeks as $week) {
            $week_logs = $all_absences = $all_absensi->where('periode_label', $week);
            $staff_data = [];
            $grand_total_gaji = 0;

            foreach ($anggota as $staff) {
                // CEK INTEGRASI OVERRIDE ENGINE
                $has_override = $overrides->where('periode_label', $week)->where('nama_petugas', $staff->nama)->first();

                if ($has_override) {
                    $jabatan_final = $has_override->jabatan;
                    $total_jam = $has_override->total_jam;
                    $hari_aktif = $has_override->hari_aktif;
                    $gaji_pokok_cair = $has_override->gaji_pokok;
                    $bonus_total = $has_override->bonus;
                    $total_terima = $has_override->total;
                    $target_lolos = ($hari_aktif >= 4 && $total_jam >= 8);
                } else {
                    // Logika Kalkulasi Otomatis Standard
                    $staff_log = $week_logs->where('nama_petugas', $staff->nama);
                    $total_jam = $staff_log->sum('durasi');
                    $logs_by_date = $staff_log->groupBy('tanggal');
                    $hari_memenuhi_syarat = 0;
                    foreach ($logs_by_date as $day_logs) {
                        if ($day_logs->sum('durasi') >= 2) $hari_memenuhi_syarat++;
                    }

                    $jabatan_info = $jabatans->get($staff->jabatan);
                    $gaji_pokok_master = $jabatan_info->gaji_mingguan ?? 0;
                    $bonus_per_tindakan = $jabatan_info->bonus_tindakan ?? 0;

                    $jabatan_final = $staff->jabatan;
                    $hari_aktif = $hari_memenuhi_syarat;
                    $target_lolos = $hari_aktif >= 4;

                    if ($target_lolos) {
                        $gaji_pokok_cair = $gaji_pokok_master;
                        $bonus_total = $staff_log->sum('jumlah_pasien') * $bonus_per_tindakan;
                        $total_terima = $gaji_pokok_cair + $bonus_total;
                    } else {
                        $gaji_pokok_cair = 0; $bonus_total = 0; $total_terima = 0;
                    }
                }

                $grand_total_gaji += $total_terima;

                $staff_data[] = [
                    'nama' => $staff->nama,
                    'jabatan' => $jabatan_final,
                    'total_jam' => $total_jam,
                    'hari_aktif' => $hari_aktif,
                    'target_lolos' => $target_lolos,
                    'gaji_pokok' => $gaji_pokok_cair,
                    'bonus' => $bonus_total,
                    'total' => $total_terima
                ];
            }

            usort($staff_data, function($a, $b) { return $b['total'] <=> $a['total']; });

            $cards[] = [
                'id_slug' => md5($week),
                'tipe' => 'weekly',
                'label' => $week,
                'total_pengeluaran' => $grand_total_gaji,
                'staff' => $staff_data
            ];
        }

        // 3. KARTU OVERALL
        $overall_staff = []; $overall_grand_total = 0;
        foreach ($anggota as $staff) {
            // Cek apakah ada override global khusus kartu overall
            $global_override = $overrides->where('periode_label', 'overall_all_time')->where('nama_petugas', $staff->nama)->first();

            if ($global_override) {
                $jabatan_final = $global_override->jabatan;
                $total_jam_all = $global_override->total_jam;
                $total_hari_all = $global_override->hari_aktif;
                $total_gaji_pokok = $global_override->gaji_pokok;
                $total_bonus = $global_override->bonus;
                $total_diterima = $global_override->total;
            } else {
                $total_gaji_pokok = 0; $total_bonus = 0; $total_diterima = 0; $total_jam_all = 0; $total_hari_all = 0;
                $jabatan_final = $staff->jabatan;

                foreach ($cards as $c) {
                    $s_data = collect($c['staff'])->firstWhere('nama', $staff->nama);
                    if ($s_data) {
                        $total_gaji_pokok += $s_data['gaji_pokok'];
                        $total_bonus += $s_data['bonus'];
                        $total_diterima += $s_data['total'];
                        $total_jam_all += $s_data['total_jam'];
                        $total_hari_all += $s_data['hari_aktif'];
                    }
                }
            }

            $overall_grand_total += $total_diterima;
            $overall_staff[] = [
                'nama' => $staff->nama,
                'jabatan' => $jabatan_final,
                'total_jam' => $total_jam_all,
                'hari_aktif' => $total_hari_all,
                'target_lolos' => $total_diterima > 0,
                'gaji_pokok' => $total_gaji_pokok,
                'bonus' => $total_bonus,
                'total' => $total_diterima
            ];
        }

        usort($overall_staff, function($a, $b) { return $b['total'] <=> $a['total']; });

        array_unshift($cards, [
            'id_slug' => 'overall_all_time',
            'tipe' => 'overall',
            'label' => 'Semua Periode (Overall All-Time)',
            'total_pengeluaran' => $overall_grand_total,
            'staff' => $overall_staff
        ]);

        return view('admin.gaji', compact('cards'));
    }

    public function storeOverride(Request $request)
    {
        $request->validate([
            'periode_label' => 'required|string',
            'nama_petugas' => 'required|string',
            'jabatan' => 'required|string',
            'total_jam' => 'required|numeric|min:0',
            'hari_aktif' => 'required|integer|min:0',
            'gaji_pokok' => 'required|numeric|min:0',
            'bonus' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        GajiOverride::updateOrCreate(
            [
                'periode_label' => $request->periode_label,
                'nama_petugas' => $request->nama_petugas
            ],
            $request->all()
        );

        return redirect()->back()->with('success', 'Berkas penggajian berhasil dimodifikasi dan dikunci manual.');
    }
}