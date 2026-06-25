<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Menghancurkan gembok ENUM ('Aktif', 'Nonaktif') menjadi teks bebas (VARCHAR)
        DB::statement('ALTER TABLE tenaga_medis MODIFY status VARCHAR(50) DEFAULT "Aktif"');
        
        // Opsional: Mengubah data lama 'Nonaktif' menjadi 'Alumni' secara otomatis
        DB::table('tenaga_medis')->where('status', 'Nonaktif')->update(['status' => 'Alumni']);
    }

    public function down()
    {
        // Tidak perlu fungsi mundur
    }
};