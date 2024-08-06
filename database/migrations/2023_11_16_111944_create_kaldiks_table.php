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
        Schema::create('kaldiks', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->string('semester');
            $table->string('kegiatan');
            $table->date('waktu_mulai');
            $table->date('waktu_selesai');
            $table->enum('status', ['kuliah', 'fakultatif','libur']);
            $table->string('lampiran');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kaldiks');
    }
};
