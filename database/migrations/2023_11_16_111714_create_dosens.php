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
        Schema::create('dosens', function (Blueprint $table) {
            $table->unsignedBigInteger('id_dosen')->autoIncrement();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('nip')->unique();
            $table->string('nidn')->unique();
            $table->string('nama');
            $table->boolean('is_kaprodi');
            $table->string('no_hp');
            $table->string('foto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_dosen');
    }
};
