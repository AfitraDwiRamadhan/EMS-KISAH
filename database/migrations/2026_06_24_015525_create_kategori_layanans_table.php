<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kategori_layanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_layanan')->unique();
            $table->text('deskripsi')->nullable();
            $table->enum('status_loket', ['Buka', 'Tutup'])->default('Buka');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_layanans');
    }
};