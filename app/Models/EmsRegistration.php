<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmsRegistration extends Model
{
    protected $fillable = [
        'batch_id', 'nama_ic', 'umur_ic', 'umur_ooc', 'jenis_kelamin', 
        'roblox', 'discord', 'jam_aktif', 'pengalaman', 'visi_misi', 
        'pernyataan', 'status'
    ];

    public function batch()
    {
        return $this->belongsTo(EmsRegistrationBatch::class, 'batch_id');
    }
}