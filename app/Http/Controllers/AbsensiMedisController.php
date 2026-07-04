<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiMedis;
use App\Models\TenagaMedis;
use Illuminate\Support\Facades\DB;

class AbsensiMedisController extends Controller
{
    /**
     * Fungsi lawas dipertahankan untuk backward compatibility (jika dipanggil dari fitur lain)
     */
    private function hitungDurasi($jam_masuk, $jam_keluar) {
        $jm = preg_replace('/[^\d:]/', '', str_replace(['.', '-'], ':', $jam_masuk));
        $jk = preg_replace('/[^\d:]/', '', str_replace(['.', '-'], ':', $jam_keluar));
        
        if (strlen($jm) <= 5 && !empty($jm)) $jm .= ':00';
        if (strlen($jk) <= 5 && !empty($jk)) $jk .= ':00';
        
        $waktu_masuk = strtotime($jm);
        $waktu_keluar = strtotime($jk);
        $durasi = 0;
        
        if ($waktu_masuk !== false && $waktu_keluar !== false) {
            if ($waktu_keluar < $waktu_masuk) $waktu_keluar += 86400; // Proteksi shift melewati tengah malam
            $durasi = max(0, round(($waktu_keluar - $waktu_masuk) / 3600, 2));
        }
        
        return [
            'jam_masuk' => $jm,
            'jam_keluar' => $jk,
            'durasi' => $durasi
        ];
    }

    /**
     * Menampilkan Halaman Log Absensi dengan Filter Periode Mingguan Kronologis
     */
    public function index(Request $request)
    {
        // 1. Ambil seluruh data absensi dari database
        $all_absensi = AbsensiMedis::orderBy('id', 'desc')->get();

        $raw_periode = [];
        
        // 2. Mapping Periode Mingguan dengan Fault Tolerance & Kunci Waktu Kronologis
        foreach ($all_absensi as $row) {
            try {
                $date = \Carbon\Carbon::createFromFormat('d/m/Y', $row->tanggal);
            } catch (\Exception $e) {
                try {
                    $date = \Carbon\Carbon::parse($row->tanggal);
                } catch (\Exception $e2) {
                    $tgl_parts = explode('-', $row->tanggal);
                    if (count($tgl_parts) > 1 && str_contains($tgl_parts[1], '/')) {
                        $meta = explode('/', $tgl_parts[1]);
                        try {
                            $date = \Carbon\Carbon::createFromFormat('d/m/Y', trim($tgl_parts[0]) . '/' . $meta[1] . '/' . $meta[2]);
                        } catch (\Exception $e3) {
                            $date = \Carbon\Carbon::now();
                        }
                    } else {
                        $date = \Carbon\Carbon::now();
                    }
                }
            }

            $start = $date->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
            $end = $date->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
            
            // KUNCI RAHASIA: Gunakan format Y-m-d (contoh: 2026-06-21) sebagai key array agar sorting akurat!
            $sort_key = $start->format('Y-m-d'); 
            $label = "Minggu " . $start->format('d M') . " - " . $end->format('d M Y');
            
            $raw_periode[$sort_key] = $label;
        }
        
        // Urutkan array berdasarkan kunci waktu (Y-m-d) dari yang terbaru ke terlama
        krsort($raw_periode); 
        
        // Buang kunci Y-m-d dan ambil labelnya saja untuk ditampilkan di dropdown
        $list_periode = array_values($raw_periode);

        // 3. LOGIKA AKTIF FILTER: Saring data menggunakan PHP Collection Filter
        if ($request->filled('filter_periode')) {
            $selected_periode = $request->filter_periode;
            
            $absences = $all_absensi->filter(function($row) use ($selected_periode) {
                try {
                    $date = \Carbon\Carbon::createFromFormat('d/m/Y', $row->tanggal);
                } catch (\Exception $e) {
                    try {
                        $date = \Carbon\Carbon::parse($row->tanggal);
                    } catch (\Exception $e2) {
                        $tgl_parts = explode('-', $row->tanggal);
                        if (count($tgl_parts) > 1 && str_contains($tgl_parts[1], '/')) {
                            $meta = explode('/', $tgl_parts[1]);
                            try {
                                $date = \Carbon\Carbon::createFromFormat('d/m/Y', trim($tgl_parts[0]) . '/' . $meta[1] . '/' . $meta[2]);
                            } catch (\Exception $e3) {
                                $date = \Carbon\Carbon::now();
                            }
                        } else {
                            $date = \Carbon\Carbon::now();
                        }
                    }
                }
                
                $start = $date->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                $end = $date->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
                $row_label = "Minggu " . $start->format('d M') . " - " . $end->format('d M Y');
                
                return $row_label === $selected_periode;
            });
        } else {
            $absences = $all_absensi;
        }

        return view('admin.absensi', [
            'absensi' => $absences,
            'list_periode' => $list_periode
        ]);
    }

    /**
     * MESIN UTAMA V4: SMART PARSER MULTI-SHIFT & ANTI-ERROR
     */
    public function storeBulk(Request $request)
    {
        $raw_data = $request->input('bulk_data');
        $raw_data = preg_replace('/[\x00-\x1F\x7F-\x9F\xE2\x81\xA8]/u', "\n", $raw_data);
        $tanggal_global = $request->input('tanggal_global', date('Y-m-d'));
        
        $blocks = preg_split('/(?=TANGGAL\s*:)/i', $raw_data);
        $records = [];
        
        foreach ($blocks as $block) {
            if (strlen(trim($block)) < 20) continue; 
            
            $current_record = [];
            
            if (preg_match('/NAMA\s*:\s*([^\n]+)/i', $block, $m)) $current_record['nama_petugas'] = trim($m[1]);
            if (preg_match('/KABUPATEN\s*:\s*([^\n]+)/i', $block, $m)) $current_record['kabupaten'] = trim($m[1]);
            if (preg_match('/KETERANGAN\s*:\s*([^\n]*)/i', $block, $m)) $current_record['keterangan'] = trim($m[1]);
            
            if (preg_match('/TANGGAL\s*:\s*([^\n]+)/i', $block, $m)) {
                $current_record['tanggal'] = trim($m[1]);
            } else {
                $current_record['tanggal'] = $tanggal_global;
            }

            $jumlah_pasien = 0;
            if (preg_match('/JUMLAH PASIEN\s*:\s*(\d+)/i', $block, $m)) {
                $jumlah_pasien = (int) $m[1];
            } elseif (preg_match('/KELUHAN PASIEN\s*:\s*(\d+)/i', $block, $m)) {
                $jumlah_pasien = (int) $m[1]; 
            }
            $current_record['jumlah_pasien'] = $jumlah_pasien;

            if (preg_match('/KELUHAN PASIEN\s*:([\s\S]*?)(?:Sekian laporan|$)/is', $block, $m)) {
                $current_record['keluhan_pasien'] = trim($m[1]);
            } else {
                $current_record['keluhan_pasien'] = '-';
            }

            $on_raw = '';
            $off_raw = '';
            if (preg_match('/ON DUTY\s*:\s*([^\n]+)/i', $block, $m)) $on_raw = trim($m[1]);
            if (preg_match('/OFF DUTY\s*:\s*([^\n]+)/i', $block, $m)) $off_raw = trim($m[1]);
            
            $current_record['jam_masuk'] = $on_raw;
            $current_record['jam_keluar'] = $off_raw;

            preg_match_all('/(\d{1,2})[\.\:](\d{2})/', $on_raw, $on_times, PREG_SET_ORDER);
            preg_match_all('/(\d{1,2})[\.\:](\d{2})/', $off_raw, $off_times, PREG_SET_ORDER);

            $total_menit = 0;
            for ($i = 0; $i < min(count($on_times), count($off_times)); $i++) {
                $jam_on = (int) $on_times[$i][1];
                $mnt_on = (int) $on_times[$i][2];
                $jam_off = (int) $off_times[$i][1];
                $mnt_off = (int) $off_times[$i][2];

                $waktu_on = ($jam_on * 60) + $mnt_on;
                $waktu_off = ($jam_off * 60) + $mnt_off;

                if ($waktu_off < $waktu_on) {
                    $waktu_off += 1440; 
                }
                $total_menit += ($waktu_off - $waktu_on);
            }

            $current_record['durasi'] = round($total_menit / 60, 2);

            if (!empty($current_record['nama_petugas'])) {
                $current_record['nama_petugas'] = preg_replace('/[^\p{L}\p{N}\s\.\-\']/u', '', $current_record['nama_petugas']);
                $records[] = $current_record;
            }
        }

        if (empty($records)) {
            return redirect()->back()->with('error', 'Format log tidak dikenali atau kosong!');
        }
        
        $berhasil = 0;
        $diakumulasi = 0;
        $lewat = 0;
        
        DB::beginTransaction();
        try {
            $semua_medis = TenagaMedis::all();

            foreach ($records as $rec) {
                $nama_pencarian = rtrim(trim($rec['nama_petugas']), '.');
                
                // Cari medis yang cocok di memori (case-insensitive & clean character mapping)
                $medis = $semua_medis->first(function($m) use ($nama_pencarian) {
                    $nama_db_bersih = rtrim(trim($m->nama), '.');
                    if (strcasecmp($nama_db_bersih, $nama_pencarian) === 0) {
                        return true;
                    }
                    return mb_stripos($m->nama, $nama_pencarian) !== false;
                });

                if (!$medis) {
                    $lewat++;
                    continue; 
                }

                $existing = AbsensiMedis::where('nama_petugas', $medis->nama)
                                        ->where('tanggal', $rec['tanggal'])
                                        ->first();

                if ($existing) {
                    $existing->update([
                        'durasi' => $existing->durasi + $rec['durasi'],
                        'jumlah_pasien' => $existing->jumlah_pasien + $rec['jumlah_pasien'],
                        'keluhan_pasien' => $existing->keluhan_pasien . "\n\n-- Sesi Tambahan --\n" . $rec['keluhan_pasien']
                    ]);
                    $diakumulasi++;
                } else {
                    AbsensiMedis::create([
                        'nama_petugas' => $medis->nama,
                        'kabupaten' => $rec['kabupaten'] ?? '-',
                        'jam_masuk' => substr($rec['jam_masuk'], 0, 100),
                        'jam_keluar' => substr($rec['jam_keluar'], 0, 100),
                        'durasi' => $rec['durasi'],
                        'jumlah_pasien' => $rec['jumlah_pasien'],
                        'keterangan' => empty($rec['keterangan']) ? '-' : $rec['keterangan'],
                        'keluhan_pasien' => $rec['keluhan_pasien'],
                        'tanggal' => $rec['tanggal'],
                        'petugas_input' => 'Admin EMS'
                    ]);
                    $berhasil++;
                }
            }
            DB::commit();

            $pesan = "Proses Bulk Parser Selesai. Masuk $berhasil data rekap baru & memperbarui $diakumulasi data akumulasi.";
            if ($lewat > 0) {
                $pesan .= " (Terlewati $lewat laporan karena Nama IC tidak ditemukan).";
            }

            return redirect()->back()->with('success', $pesan);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Sistem gagal menyimpan! Detail Galat: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        AbsensiMedis::destroy($id);
        return redirect()->back()->with('success', 'Rekor absensi dinas berhasil dihapus dari database.');
    }
}