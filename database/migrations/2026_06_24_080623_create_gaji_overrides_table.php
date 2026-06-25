<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gaji_overrides', function (Blueprint $table) {
            $table->id();
            $table->string('periode_label'); // Menyimpan identitas minggu/overall
            $table->string('nama_petugas');
            $table->string('jabatan');
            $table->double('total_jam', 8, 2);
            $table->integer('hari_aktif');
            $table->decimal('gaji_pokok', 15, 2);
            $table->decimal('bonus', 15, 2);
            $table->decimal('total', 15, 2);
            $table->timestamps();
            
            // Indeks unik agar satu orang hanya punya satu rekaman modifikasi per periode
            $table->unique(['periode_label', 'nama_petugas']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('gaji_overrides');
    }
};