<?php

namespace App\Http\Controllers;

use App\Models\EmsRegistration;
use App\Models\EmsRegistrationBatch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PendaftaranController extends Controller
{
    public function index()
    {
        $batches = EmsRegistrationBatch::withCount('registrations')->latest()->get();
        return view('admin.pendaftaran.index', compact('batches'));
    }

    public function storeBatch(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_link' => 'nullable|url|max:255',
        ]);

        EmsRegistrationBatch::create([
            'name' => $validated['name'],
            'registration_link' => $validated['registration_link'] ?? null,
            'is_active' => false,
        ]);

        return back()->with('success', 'Batch pendaftaran baru berhasil dibuat.');
    }

    public function toggleBatch(EmsRegistrationBatch $batch)
    {
        if (!$batch->is_active) {
            // Nonaktifkan batch lain secara otomatis
            EmsRegistrationBatch::where('id', '!=', $batch->id)->update(['is_active' => false]);
            $batch->update(['is_active' => true]);
            $msg = 'Batch ' . $batch->name . ' resmi dibuka. Akses formulir publik aktif.';
        } else {
            $batch->update(['is_active' => false]);
            $msg = 'Batch ' . $batch->name . ' ditutup. Formulir publik dinonaktifkan.';
        }

        return back()->with('success', $msg);
    }

    public function destroyBatch(EmsRegistrationBatch $batch)
    {
        $batch->registrations()->delete();
        $batch->delete();
        return back()->with('success', 'Batch beserta data pendaftarnya berhasil dihapus permanen.');
    }

    public function showBatch(Request $request, EmsRegistrationBatch $batch)
    {
        $registrations = $batch->registrations()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->get();

        return view('admin.pendaftaran.show', compact('batch', 'registrations'));
    }

    public function updateStatus(Request $request, EmsRegistration $registration)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['Pending', 'Diterima', 'Ditolak'])],
        ]);

        $registration->update(['status' => $validated['status']]);
        return back()->with('success', 'Status kandidat berhasil diperbarui menjadi ' . $validated['status']);
    }

    public function destroy(EmsRegistration $registration)
    {
        $registration->delete();
        return back()->with('success', 'Data pendaftar berhasil dihapus.');
    }
}
