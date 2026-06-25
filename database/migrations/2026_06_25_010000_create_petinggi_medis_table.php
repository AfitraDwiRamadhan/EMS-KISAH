<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petinggi_medis', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('petinggi_medis')->insert([
            'username' => 'Kisah',
            'password' => Hash::make('kisah123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('petinggi_medis')->insert([
            'username' => 'dokter',
            'password' => Hash::make('dokter123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('petinggi_medis');
    }
};