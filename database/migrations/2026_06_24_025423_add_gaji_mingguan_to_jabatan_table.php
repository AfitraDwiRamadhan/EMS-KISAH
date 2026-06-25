<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jabatan', function (Blueprint $table) {
            // Menambahkan kolom gaji di sebelah nama jabatan
            $table->decimal('gaji_mingguan', 15, 2)->default(0)->after('nama_jabatan');
        });
    }

    public function down()
    {
        Schema::table('jabatan', function (Blueprint $table) {
            $table->dropColumn('gaji_mingguan');
        });
    }
};