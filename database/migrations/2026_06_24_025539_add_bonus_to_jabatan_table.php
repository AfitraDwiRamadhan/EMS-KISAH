<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
            Schema::table('jabatan', function (Blueprint $table) {
            $table->decimal('bonus_tindakan', 15, 2)->default(0)->after('gaji_mingguan');
        });
    }
};
