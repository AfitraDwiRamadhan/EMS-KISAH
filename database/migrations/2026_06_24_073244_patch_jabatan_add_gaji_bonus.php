<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Pengecekan cerdas: Hanya tambahkan jika kolomnya benar-benar belum ada
        if (!Schema::hasColumn('jabatan', 'gaji_mingguan')) {
            Schema::table('jabatan', function (Blueprint $table) {
                $table->decimal('gaji_mingguan', 15, 2)->default(0)->after('nama_jabatan');
            });
        }

        if (!Schema::hasColumn('jabatan', 'bonus_tindakan')) {
            Schema::table('jabatan', function (Blueprint $table) {
                $table->decimal('bonus_tindakan', 15, 2)->default(0)->after('gaji_mingguan');
            });
        }
    }

    public function down()
    {
        Schema::table('jabatan', function (Blueprint $table) {
            $table->dropColumn(['gaji_mingguan', 'bonus_tindakan']);
        });
    }
};