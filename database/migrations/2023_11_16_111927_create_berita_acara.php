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
        Schema::create('berita_acara', function (Blueprint $table) {
            $table->unsignedBigInteger('id_jdwl');
            $table->unsignedBigInteger('id_dosen');
            $table->date('tgl');
            $table->string('pkk_bhsn');
            $table->string('spkk_bhsn');
            $table->string('media');
            $table->time('jam_ajar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_acara');
    }
};
