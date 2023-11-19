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
        Schema::create('logs', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tahun_ajar')->autoIncrement();
            $table->unsignedBigInteger('id_jdwl');
            $table->unsignedBigInteger('id_dosen');
            $table->date('tahun_awal');
            $table->date('tahun_akhir');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log');
    }
};
