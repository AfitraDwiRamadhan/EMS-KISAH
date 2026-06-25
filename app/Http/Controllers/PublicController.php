<?php

namespace App\Http\Controllers;

use App\Models\EmsRegistration;
use App\Models\EmsRegistrationBatch;
use App\Models\KategoriLayanan;
use App\Models\JejakPengabdian;
use App\Models\LoketLayanan;
use App\Models\TenagaMedis;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        // 1. Ambil Batch Pendaftaran yang sedang Aktif (Buka) beserta relasi pendaftar
        $activeBatch = EmsRegistrationBatch::with(['registrations' => function ($query) {
                $query->select('id', 'batch_id', 'nama_ic', 'created_at')->latest();
            }])
            ->where('is_active', true)
            ->latest()
            ->first();

        // 2. Ambil Data Loket yang sedang 'Buka' dan Hitung Antrean 'Menunggu'
        $loketBuka = KategoriLayanan::where('status_loket', 'Buka')->count();
        $antreanAktif = LoketLayanan::where('status_penanganan', 'Menunggu')->count();

        // 3. Ambil Total Foto Dokumentasi Publik
        $totalDokumentasi = JejakPengabdian::count();
        $latestDokumentasi = JejakPengabdian::orderBy('tanggal', 'desc')->take(3)->get();

        // 4. Ambil anggota tenaga medis aktif yang dikelola dari sisi admin.
        $tenagaMedis = TenagaMedis::where('status', 'Aktif')
            ->orderBy('jabatan', 'asc')
            ->orderBy('nama', 'asc')
            ->get();

        // 5. Ambil semua kategori layanan
        $layanans = KategoriLayanan::orderBy('nama_layanan', 'asc')->get();

        // Kirim semua variabel ke tampilan beranda
        return view('public.home', compact(
            'activeBatch',
            'loketBuka',
            'antreanAktif',
            'totalDokumentasi',
            'latestDokumentasi',
            'tenagaMedis',
            'layanans'
        ));
    }

    public function storeRegistration(Request $request)
    {
        $activeBatch = EmsRegistrationBatch::where('is_active', true)->first();

        if (!$activeBatch) {
            return back()->with('error', 'Pendaftaran saat ini sedang ditutup.');
        }

        $validated = $request->validate([
            'nama_ic'       => 'required|string|max:255',
            'umur_ic'       => 'required|integer|min:18',
            'umur_ooc'      => 'required|integer|min:16',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'roblox'        => 'required|string|max:255',
            'discord'       => 'required|string|max:255',
            'jam_aktif'     => 'required|string|max:255',
            'pengalaman'    => 'required|string|max:4000',
            'visi_misi'     => 'required|string|max:4000',
            'pernyataan'    => 'accepted',
        ]);

        $activeBatch->registrations()->create($validated);

        return redirect('/#pendaftaran')->with('success', 'Pendaftaran Anda telah berhasil dikirim! Silakan tunggu informasi selanjutnya.');
    }

    public function storeLayanan(Request $request)
    {
        $validated = $request->validate([
            'kategori_layanan' => 'required|string|exists:kategori_layanans,nama_layanan',
            'nama_pasien' => 'required|string|max:255',
        ]);

        // Pastikan loket untuk kategori ini sedang buka
        $kategori = KategoriLayanan::where('nama_layanan', $validated['kategori_layanan'])->first();
        if (!$kategori || $kategori->status_loket !== 'Buka') {
            return back()->with('error', 'Maaf, loket untuk layanan ini sedang ditutup.');
        }

        // Ambil semua data input, kecuali token CSRF dan nama kategori
        $data_lengkap = $request->except('_token', 'kategori_layanan');

        LoketLayanan::create([
            'nama_pasien' => $validated['nama_pasien'],
            'kategori_layanan' => $validated['kategori_layanan'],
            'status_penanganan' => 'Menunggu',
            'data_lengkap' => $data_lengkap
        ]);

        return redirect('/#layanan-kami')->with('success', 'Anda berhasil mengambil nomor antrean! Silakan tunggu panggilan dari petugas medis.');
    }
}
