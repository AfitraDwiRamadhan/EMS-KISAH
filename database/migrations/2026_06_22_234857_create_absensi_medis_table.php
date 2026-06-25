<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('absensi_medis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_petugas');
            $table->string('kabupaten')->nullable(); 
            $table->string('jam_masuk');
            $table->string('jam_keluar');
            $table->decimal('durasi', 5, 2)->default(0);
            $table->integer('jumlah_pasien')->default(0);
            $table->string('keterangan')->nullable(); 
            $table->text('keluhan_pasien')->nullable(); 
            $table->string('tanggal'); 
            $table->string('petugas_input');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('absensi_medis');
    }
};