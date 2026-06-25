<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiMedis extends Model
{
    use HasFactory;

    protected $table = 'absensi_medis';

    protected $fillable = [
        'nama_petugas', 
        'kabupaten', 
        'jam_masuk', 
        'jam_keluar', 
        'durasi', 
        'jumlah_pasien', 
        'keterangan', 
        'keluhan_pasien', 
        'tanggal', 
        'petugas_input'
    ];
}