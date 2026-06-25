<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ems_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('ems_registration_batches')->onDelete('cascade');
            $table->string('nama_ic');
            $table->integer('umur_ic');
            $table->integer('umur_ooc');
            $table->string('jenis_kelamin');
            $table->string('roblox');
            $table->string('discord');
            $table->string('jam_aktif');
            $table->text('pengalaman');
            $table->text('visi_misi');
            $table->boolean('pernyataan')->default(true); // Persetujuan aturan
            $table->enum('status', ['Pending', 'Diterima', 'Ditolak'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ems_registrations');
    }
};