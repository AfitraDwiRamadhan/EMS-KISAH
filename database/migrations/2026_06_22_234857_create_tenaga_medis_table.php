<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tenaga_medis', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->index();// Mengambil "Nama IC"
            $table->integer('usia')->nullable(); // Mengambil "Usia IC"
            $table->string('username_roblox')->nullable(); // Mengambil "Username Roblox"
            $table->string('username_discord')->nullable(); // Mengambil "Username Discord"
            $table->enum('jabatan', ['Tenaga Medis', 'Tenaga Medis Intern']);
            $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tenaga_medis');
    }
};