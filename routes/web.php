<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TenagaMedisController;
use App\Http\Controllers\AbsensiMedisController;
use App\Http\Controllers\LoketLayananController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\DokumentasiController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Auth\PetinggiLoginController;

// RUTE PUBLIK (WARGA)
Route::get('/', [PublicController::class, 'index'])->name('public.home');
Route::post('/pendaftaran', [PublicController::class, 'storeRegistration'])->name('public.registration.store');
Route::post('/layanan/submit', [PublicController::class, 'storeLayanan'])->name('public.layanan.submit');
Route::post('/petinggi/login', [PetinggiLoginController::class, 'login'])->name('petinggi.login.submit')->middleware('guest:petinggi');
Route::post('/petinggi/logout', [PetinggiLoginController::class, 'logout'])->name('petinggi.logout')->middleware('auth:petinggi');


// RUTE ADMIN

Route::prefix('admin')->name('admin.')->middleware('auth:petinggi')->group(function () {
    
    // 1. Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Modul Data Tenaga Medis
    Route::get('/tenaga-medis', [TenagaMedisController::class, 'index'])->name('tenaga_medis.index');
    Route::post('/tenaga-medis', [TenagaMedisController::class, 'store'])->name('tenaga_medis.store');
    Route::post('/tenaga-medis/bulk', [TenagaMedisController::class, 'storeBulk'])->name('tenaga_medis.storeBulk');
    Route::put('/tenaga-medis/{id}', [TenagaMedisController::class, 'update'])->name('tenaga_medis.update');
    Route::delete('/tenaga-medis/{id}', [TenagaMedisController::class, 'destroy'])->name('tenaga_medis.destroy');

    // 3. Modul Absensi Medis (Duty Log)
    Route::get('/absensi', [AbsensiMedisController::class, 'index'])->name('absensi.index');
    Route::post('/absensi/bulk', [AbsensiMedisController::class, 'storeBulk'])->name('absensi.storeBulk');
    Route::delete('/absensi/{id}', [AbsensiMedisController::class, 'destroy'])->name('absensi.destroy');

    // 4. Modul Struktur Jabatan (Master Role)
    Route::get('/jabatan', [JabatanController::class, 'index'])->name('jabatan.index');
    Route::post('/jabatan', [JabatanController::class, 'store'])->name('jabatan.store');
    Route::put('/jabatan/{id}', [JabatanController::class, 'update'])->name('jabatan.update');
    Route::delete('/jabatan/{id}', [JabatanController::class, 'destroy'])->name('jabatan.destroy');

    // 5. Modul Loket Pelayanan Warga (Multi-Counter System)
    Route::get('/loket', [LoketLayananController::class, 'index'])->name('loket.index');
    Route::post('/loket/kategori', [LoketLayananController::class, 'storeKategori'])->name('loket.storeKategori');
    Route::put('/loket/kategori/{id}', [LoketLayananController::class, 'updateKategori'])->name('loket.updateKategori');
    Route::delete('/loket/kategori/{id}', [LoketLayananController::class, 'destroyKategori'])->name('loket.destroyKategori');
    Route::post('/loket/bulk', [LoketLayananController::class, 'storeBulk'])->name('loket.storeBulk');
    Route::put('/loket/{id}/status', [LoketLayananController::class, 'updateStatus'])->name('loket.updateStatus');

    // 6. Modul Penggajian & Override Manual
    Route::get('/gaji', [GajiController::class, 'index'])->name('gaji.index');
    Route::post('/gaji/override', [GajiController::class, 'storeOverride'])->name('gaji.storeOverride');

    // 7. Modul Manajemen Pendaftaran / Rekrutmen EMS
    Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::post('/pendaftaran/batch', [PendaftaranController::class, 'storeBatch'])->name('pendaftaran.store');
    Route::post('/pendaftaran/batch/{batch}/toggle', [PendaftaranController::class, 'toggleBatch'])->name('pendaftaran.toggle');
    Route::delete('/pendaftaran/batch/{batch}', [PendaftaranController::class, 'destroyBatch'])->name('pendaftaran.destroy');
    Route::get('/pendaftaran/batch/{batch}', [PendaftaranController::class, 'showBatch'])->name('pendaftaran.show');
    Route::put('/pendaftaran/{registration}/status', [PendaftaranController::class, 'updateStatus'])->name('pendaftaran.updateStatus');
    Route::delete('/pendaftaran/{registration}', [PendaftaranController::class, 'destroy'])->name('pendaftaran.destroyRegistration');

    // 8. Modul CMS Dokumentasi (Jejak Pengabdian)
    Route::prefix('dokumentasi')->name('dokumentasi.')->group(function () {
        Route::get('/', [DokumentasiController::class, 'index'])->name('index');
        Route::post('/store', [DokumentasiController::class, 'store'])->name('store');
        Route::delete('/{id}/destroy', [DokumentasiController::class, 'destroy'])->name('destroy');
    });

});
