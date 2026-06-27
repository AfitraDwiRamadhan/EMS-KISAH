<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Buat tabel master jabatan
        Schema::create('jabatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jabatan')->unique();
            $table->timestamps();
        });

        // 2. Suntik data awal secara otomatis sesuai permintaan Boss
        $roles = [
            ['nama_jabatan' => 'Dokter Anak', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jabatan' => 'Dokter Bedah', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jabatan' => 'Dokter Umum', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jabatan' => 'Dokter Kandungan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jabatan' => 'EMT', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jabatan' => 'Paramedic', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jabatan' => 'Apoteker', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jabatan' => 'Sekretaris', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jabatan' => 'Head EMS', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jabatan' => 'Deputy EMS', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jabatan' => 'Intern', 'created_at' => now(), 'updated_at' => now()]
        ];
        DB::table('jabatan')->insert($roles);

        // 3. Hancurkan batasan ENUM lama di tabel tenaga_medis, ubah jadi teks bebas
        // DB::statement('ALTER TABLE tenaga_medis MODIFY jabatan VARCHAR(255)');
    }

    public function down()
    {
        Schema::dropIfExists('jabatan');
    }
};