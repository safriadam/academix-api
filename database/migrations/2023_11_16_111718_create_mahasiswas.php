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
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_mhs')->autoIncrement();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('id_kls');
            $table->string('nim')->unique();
            $table->string('nama');
            $table->unsignedBigInteger('dosen_pa');
            $table->string('foto');
            $table->string('no_hp');
            $table->enum('ket_status', ['-', 'sp1', 'sp2', 'sp3', 'do',])->default('-');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_mhs');
    }
};
