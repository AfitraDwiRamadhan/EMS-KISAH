<?php

namespace App\Http\Controllers;

use App\Models\JejakPengabdian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    // Daftar Kategori Statis
    private $kategori_list = [
        'Penanganan Kritis',
        'Operasi Medis',
        'Rawat Jalan',
        'Patroli Harian',
        'Koordinasi Tim',
        'Trainee Program'
    ];

    public function index()
    {
        $dokumentasi = JejakPengabdian::orderBy('tanggal', 'desc')->get();
        $kategori_list = $this->kategori_list;
        
        return view('admin.dokumentasi.index', compact('dokumentasi', 'kategori_list'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Maks 5MB
        ]);

        $fotoPath = $request->file('foto')->store('jejak_pengabdian', 'public');

        JejakPengabdian::create([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'foto' => $fotoPath,
        ]);

        return redirect()->back()->with('success', 'Dokumentasi Jejak Pengabdian berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $dokumentasi = JejakPengabdian::findOrFail($id);

        // Hapus file fisik foto dari storage
        if (Storage::disk('public')->exists($dokumentasi->foto)) {
            Storage::disk('public')->delete($dokumentasi->foto);
        }

        $dokumentasi->delete();

        return redirect()->back()->with('success', 'Dokumentasi berhasil dihapus permanen.');
    }
}