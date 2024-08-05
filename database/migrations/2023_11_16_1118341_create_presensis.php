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
        Schema::create('presensis', function (Blueprint $table) {
            $table->unsignedBigInteger('id_presensi')->autoIncrement();
            $table->foreignId('id_mhs')->references('id_mhs')->on('mahasiswas')->onDelete('cascade');
            $table->foreignId('id_jdwl')->references('id_jdwl')->on('jadwals')->onDelete('cascade');
            $table->unsignedBigInteger('id_tahun_ajar');
            $table->date('tgl');
            $table->time('start_kls');
            $table->time('finish_kls')->nullable();
            $table->integer('kehadiran')->nullable();
            $table->integer('ketidakhadiran');
            $table->enum('status', ['A','I','S'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_presensi');
    }
};
