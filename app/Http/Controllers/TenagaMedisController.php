<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TenagaMedis;
use App\Models\Jabatan;

class TenagaMedisController extends Controller
{
    public function index()
    {
        $medis = TenagaMedis::orderBy('nama', 'asc')->get();
        // Mengambil daftar jabatan dinamis untuk dikirim ke Dropdown UI
        $jabatans = Jabatan::orderBy('nama_jabatan', 'asc')->get();
        return view('admin.tenaga_medis', compact('medis', 'jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'usia' => 'nullable|integer',
            'username_roblox' => 'nullable|string|max:255',
            'username_discord' => 'nullable|string|max:255',
            'jabatan' => 'required|string|max:255', // Validasi telah dibuka
            'status' => 'required|in:Aktif,Cuti,Alumni'
        ]);

        TenagaMedis::create($request->all());
        return redirect()->back()->with('success', 'Biodata Tenaga Medis baru berhasil diregistrasi.');
    }

    public function storeBulk(Request $request)
    {
        $raw_data = $request->input('bulk_data');
        $jabatan_global = $request->input('jabatan_global', 'Intern');
        
        $raw_data = str_replace(["\xC2\xA0", "\r", "\xE2\x80\x8B", "\xEF\xBB\xBF", "”", "“", '"'], [' ', "\n", '', '', '', '', ''], $raw_data);
        $blocks = preg_split('/(?=Nama\s*ic|Nama\s*lengkap|BIODATA\s*DIRI)/i', $raw_data);
        
        $berhasil = 0;
        $dilewati = 0;

        foreach ($blocks as $block) {
            if (strlen(trim($block)) < 15) continue; 
            
            $current_record = [
                'nama' => '',
                'usia' => null,
                'username_roblox' => '-',
                'username_discord' => '-'
            ];
            
            if (preg_match('/(?:Nama\s*ic|Nama\s*lengkap.*?)\s*[:\-]?\s*(.+)/i', $block, $m)) {
                $current_record['nama'] = trim(preg_split('/\n/', $m[1])[0]);
            }
            if (preg_match('/(?:Umur\s*ic|Usia\s*IC|Umur)\s*[:\-]?\s*(\d+)/i', $block, $m)) {
                $current_record['usia'] = (int) $m[1];
            }
            if (preg_match('/(?:Username\s*Roblox|Username\s*Rblx|Nama\s*Rblx|Username\s*ic|Usn\s*IC)\s*[:\-]?\s*(.+)/i', $block, $m)) {
                $current_record['username_roblox'] = trim(preg_split('/\n/', $m[1])[0]);
            }
            if (preg_match('/(?:Username\s*dc|Usn\s*DC|Username\s*Discord)\s*[:\-]?\s*(.+)/i', $block, $m)) {
                $current_record['username_discord'] = trim(preg_split('/\n/', $m[1])[0]);
            }

            $nama_bersih = preg_replace('/[^\p{L}\p{N}\s\.\-\(\)]/u', '', $current_record['nama']);
            $nama_final = substr(trim($nama_bersih), 0, 150);

            if (!empty($nama_final)) {
                $exists = TenagaMedis::where('nama', $nama_final)->exists();
                if (!$exists) {
                    TenagaMedis::create([
                        'nama' => $nama_final,
                        'usia' => $current_record['usia'],
                        'username_roblox' => substr($current_record['username_roblox'], 0, 100),
                        'username_discord' => substr($current_record['username_discord'], 0, 100),
                        'jabatan' => $jabatan_global,
                        'status' => 'Aktif'
                    ]);
                    $berhasil++;
                } else {
                    $dilewati++;
                }
            }
        }
        return redirect()->back()->with('success', "Parser Selesai: $berhasil anggota ditambahkan. ($dilewati duplikat).");
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'usia' => 'nullable|integer',
            'username_roblox' => 'nullable|string|max:255',
            'username_discord' => 'nullable|string|max:255',
            'jabatan' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Cuti,Alumni'
        ]);

        TenagaMedis::findOrFail($id)->update($request->all());
        return redirect()->back()->with('success', 'Data profil Tenaga Medis berhasil diperbarui.');
    }

    public function destroy($id)
    {
        TenagaMedis::destroy($id);
        return redirect()->back()->with('success', 'Tenaga Medis berhasil dihapus.');
    }
}