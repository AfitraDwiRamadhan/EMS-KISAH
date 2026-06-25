<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GajiOverride extends Model
{
    use HasFactory;

    protected $table = 'gaji_overrides';
    protected $fillable = [
        'periode_label',
        'nama_petugas',
        'jabatan',
        'total_jam',
        'hari_aktif',
        'gaji_pokok',
        'bonus',
        'total'
    ];
}