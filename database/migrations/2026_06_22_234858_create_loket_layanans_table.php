<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('loket_layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pasien');
            $table->string('kategori_layanan'); 
            $table->json('data_lengkap'); 
            $table->enum('status_penanganan', ['Menunggu', 'Diproses', 'Selesai'])->default('Menunggu');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('loket_layanan');
    }
};