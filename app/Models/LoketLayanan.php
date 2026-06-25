<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoketLayanan extends Model
{
    use HasFactory;

    protected $table = 'loket_layanan';

    protected $fillable = [
        'nama_pasien', 
        'kategori_layanan', 
        'data_lengkap', 
        'status_penanganan'
    ];

    // JURUS MUTLAK: Memberi tahu Laravel bahwa data_lengkap adalah JSON/Array, bukan teks biasa
    protected $casts = [
        'data_lengkap' => 'array',
    ];
}