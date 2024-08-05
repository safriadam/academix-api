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
        Schema::create('cicil_kompen', function (Blueprint $table) {
            $table->unsignedInteger('id_cicil');
            $table->unsignedBigInteger('id_kompen');
            $table->unsignedBigInteger('id_tahun_ajar');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->date('tgl_cicil');
            $table->integer('jlh_jam_konversi');
            $table->string('jenis_kompen');
            $table->enum('status',['1','2','3']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cicil_kompen');
    }
};
