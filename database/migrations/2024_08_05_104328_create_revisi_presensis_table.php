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
        Schema::create('revisi_presensi', function (Blueprint $table) {
            $table->bigInteger('id_revisi_presensi')->autoIncrement();
            $table->bigInteger('id_presensi');
            $table->date('tanggal_revisi');
            $table->enum('status',['diajukan','menunggu_verif','disetujui'])->nullable();
            $table->string('bukti_revisi');
            $table->string('revisi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisi_presensi');
    }
};
