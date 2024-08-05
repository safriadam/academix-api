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
        Schema::create('kompen_mhs', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kompen');
            $table->foreignId('id_mhs')->references('id_mhs')->on('mahasiswas')->onDelete('cascade');
            $table->foreignId('id_mk')->references('id_mk')->on('matkuls')->onDelete('cascade');
            $table->foreignId('id_tahun_ajar')->references('id')->on('kaldiks')->onDelete('cascade');
            $table->unsignedBigInteger('id_staff');
            $table->integer('jumlah_kompen');
            $table->string('keterangan');
            $table->date('tgl_alpha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kompen_mhs');
    }
};
