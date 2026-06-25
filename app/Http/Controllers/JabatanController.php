<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jabatan;
use App\Models\TenagaMedis;

class JabatanController extends Controller
{
    public function index()
    {
        // Urutkan jabatan dari penawaran struktur upah tertinggi
        $jabatans = Jabatan::orderBy('gaji_mingguan', 'desc')->get();
        
        foreach ($jabatans as $jb) {
            $jb->total_anggota = TenagaMedis::where('jabatan', $jb->nama_jabatan)->count();
        }
        
        return view('admin.jabatan', compact('jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255|unique:jabatan,nama_jabatan',
            'gaji_mingguan' => 'required|numeric|min:0'
        ]);

        Jabatan::create([
            'nama_jabatan' => $request->nama_jabatan,
            'gaji_mingguan' => $request->gaji_mingguan,
            'bonus_tindakan' => 0 // Default 0 karena akan diurus manual nanti
        ]);

        return redirect()->back()->with('success', 'Master rincian role baru beserta skema upah berhasil didaftarkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255|unique:jabatan,nama_jabatan,'.$id,
            'gaji_mingguan' => 'required|numeric|min:0'
        ]);

        $jabatan = Jabatan::findOrFail($id);
        $nama_lama = $jabatan->nama_jabatan;
        $nama_baru = $request->nama_jabatan;

        $jabatan->update([
            'nama_jabatan' => $nama_baru,
            'gaji_mingguan' => $request->gaji_mingguan
            // bonus_tindakan tidak di-update agar tetap aman
        ]);

        // Auto-Cascade sinkronisasi ke tabel personil
        if ($nama_lama !== $nama_baru) {
            TenagaMedis::where('jabatan', $nama_lama)->update(['jabatan' => $nama_baru]);
        }

        return redirect()->back()->with('success', 'Perubahan nominal upah pokok berhasil disinkronkan.');
    }

    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $sedangDipakai = TenagaMedis::where('jabatan', $jabatan->nama_jabatan)->exists();
        
        if ($sedangDipakai) {
            return redirect()->back()->with('error', 'Gagal! Otoritas role ini masih aktif melekat pada personil medis.');
        }

        $jabatan->delete();
        return redirect()->back()->with('success', 'Struktur jabatan berhasil dihapus.');
    }
}