<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoketLayanan;
use App\Models\KategoriLayanan;
use Illuminate\Support\Facades\DB;

class LoketLayananController extends Controller
{
    public function index()
    {
        $kategoris = KategoriLayanan::orderBy('nama_layanan', 'asc')->get();
        $layanan = LoketLayanan::orderBy('id', 'desc')->get();

        foreach ($kategoris as $kat) {
            $kat->antrean_aktif = $layanan->where('kategori_layanan', $kat->nama_layanan)
                                          ->whereIn('status_penanganan', ['Menunggu', 'Diproses'])
                                          ->count();
            
            $kat->total_pasien = $layanan->where('kategori_layanan', $kat->nama_layanan)->count();
        }

        return view('admin.loket_layanan', compact('kategoris', 'layanan'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255|unique:kategori_layanans,nama_layanan',
            'deskripsi' => 'nullable|string'
        ]);

        KategoriLayanan::create([
            'nama_layanan' => $request->nama_layanan,
            'deskripsi' => $request->deskripsi,
            'status_loket' => 'Buka'
        ]);

        return redirect()->back()->with('success', 'Loket layanan baru berhasil dibuka!');
    }

    public function updateKategori(Request $request, $id)
    {
        $kat = KategoriLayanan::findOrFail($id);
        
        $request->validate([
            'nama_layanan' => 'required|string|max:255|unique:kategori_layanans,nama_layanan,'.$id,
            'deskripsi' => 'nullable|string',
            'status_loket' => 'required|in:Buka,Tutup'
        ]);

        if ($kat->nama_layanan !== $request->nama_layanan) {
            LoketLayanan::where('kategori_layanan', $kat->nama_layanan)
                        ->update(['kategori_layanan' => $request->nama_layanan]);
        }

        $kat->update([
            'nama_layanan' => $request->nama_layanan,
            'deskripsi' => $request->deskripsi,
            'status_loket' => $request->status_loket
        ]);

        return redirect()->back()->with('success', 'Konfigurasi loket berhasil diperbarui.');
    }

    public function destroyKategori($id)
    {
        $kat = KategoriLayanan::findOrFail($id);
        $ada_pasien = LoketLayanan::where('kategori_layanan', $kat->nama_layanan)->exists();
        if ($ada_pasien) {
            return redirect()->back()->with('error', 'Ditolak! Loket ini masih memiliki riwayat data pasien.');
        }

        $kat->delete();
        return redirect()->back()->with('success', 'Loket berhasil dihapus.');
    }

    /**
     * ENGINE UTAMA: SMART PARSER LOG PENDAFTARAN WARGA (8 FORMAT INTEGRASI)
     */
    public function storeBulk(Request $request)
    {
        $raw_data = $request->input('bulk_data');
        
        // Pembersihan normalisasi teks log
        $raw_data = str_replace(["\xC2\xA0", "\r", "\xE2\x80\x8B", "\xEF\xBB\xBF"], [' ', "", '', ''], $raw_data);
        
        // Pecah teks berdasarkan indikator Nama Pasien atau kemunculan kata kunci nama utama
        $blocks = preg_split('/(?=Nama\s*pasien|Nama\s*Pasien|Nama\s*:)/i', $raw_data);
        
        $berhasil = 0;

        DB::beginTransaction();
        try {
            foreach ($blocks as $block) {
                if (strlen(trim($block)) < 15) continue;

                $block_lower = strtolower($block);
                $kategori = 'Konsultasi Dokter Umum'; // Default fallback

                // Klasifikasi Loket Otomatis Berdasarkan Deteksi Kata Kunci Unik Form
                if (str_contains($block_lower, 'mcu') || str_contains($block_lower, 'medical check up') || str_contains($block_lower, 'tinggi badan')) {
                    $kategori = 'Loket Pendaftaran Medical Check Up';
                } elseif (str_contains($block_lower, 'operasi') || str_contains($block_lower, 'patah tulang') || str_contains($block_lower, 'ck/non ck')) {
                    $kategori = 'Loket Pendaftaran Operasi';
                } elseif (str_contains($block_lower, 'dna') || str_contains($block_lower, 'forensik') || str_contains($block_lower, 'dugaan hubungan')) {
                    $kategori = 'Loket Pendaftaran DNA Forensik';
                } elseif (str_contains($block_lower, 'usg') || str_contains($block_lower, 'kehamilan')) {
                    $kategori = 'Loket Pendaftaran USG Kehamilan';
                } elseif (str_contains($block_lower, 'autopsi') || str_contains($block_lower, 'hipovolemik') || str_contains($block_lower, 'meninggal akibat')) {
                    $kategori = 'Loket Pendaftaran Autopsi';
                } elseif (str_contains($block_lower, 'sunat') || str_contains($block_lower, 'foto pasien')) {
                    $kategori = 'Loket Pendaftaran Sunat';
                } elseif (str_contains($block_lower, 'psikiater') || str_contains($block_lower, 'jam temu')) {
                    $kategori = 'Loket Pendaftaran Psikiater';
                } elseif (str_contains($block_lower, 'konsultasi') || str_contains($block_lower, 'dokter umum')) {
                    $kategori = 'Loket Konsultasi Dokter Umum';
                }

                // Auto-Spawn Loket: Buat kategorinya jika belum tersedia di database master
                KategoriLayanan::firstOrCreate(
                    ['nama_layanan' => $kategori],
                    ['deskripsi' => 'Loket otomatis dibuka oleh Smart Parser Engine.', 'status_loket' => 'Buka']
                );

                // Ekstraksi Baris Key-Value Dinamis
                $lines = explode("\n", trim($block));
                $data_lengkap = [];
                $nama_pasien = 'Warga Anonim';

                foreach ($lines as $line) {
                    if (preg_match('/^\s*([^:]+)\s*:\s*(.*)$/', $line, $matches)) {
                        $key = trim($matches[1]);
                        $value = trim($matches[2]);

                        // Bersihkan simbol bintang markdown atau kurung pada penulisan Key
                        $key_clean = trim(preg_replace('/[^\p{L}\p{N}\s]/u', '', $key));
                        
                        if (empty($key_clean)) continue;

                        // Tangkap Nama Pasien utama
                        if (in_array(strtolower($key_clean), ['nama pasien', 'nama'])) {
                            $nama_pasien = trim(preg_replace('/[^\p{L}\p{N}\s\.\-\']/u', '', $value));
                        }

                        $data_lengkap[$key_clean] = empty($value) ? '-' : $value;
                    }
                }

                // Kirim rekam medis ke database antrean
                LoketLayanan::create([
                    'nama_pasien' => substr($nama_pasien, 0, 200),
                    'kategori_layanan' => $kategori,
                    'status_penanganan' => 'Menunggu',
                    'data_lengkap' => $data_lengkap // Pastikan kolom ini di-cast sebagai array/json di Model
                ]);

                $berhasil++;
            }

            DB::commit();
            return redirect()->back()->with('success', "Smart Parser Sukses: Berhasil memasukkan $berhasil berkas antrean pendaftaran warga.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses berkas log warga. Detail Galat: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_penanganan' => 'required|in:Menunggu,Diproses,Selesai'
        ]);

        LoketLayanan::findOrFail($id)->update([
            'status_penanganan' => $request->status_penanganan
        ]);

        return redirect()->back()->with('success', 'Status pelayanan warga berhasil diperbarui.');
    }
}