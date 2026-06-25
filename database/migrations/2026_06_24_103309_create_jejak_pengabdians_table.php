<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jejak_pengabdians', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori'); // Penanganan Kritis, Operasi Medis, dll
            $table->text('deskripsi')->nullable();
            $table->string('foto'); // Path gambar
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jejak_pengabdians');
    }
};