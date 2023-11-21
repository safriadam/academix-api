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
            $table->unsignedBigInteger('id_mhs');
            $table->unsignedBigInteger('id_staff');
            $table->integer('jumlah_kompen');
            $table->string('keterangan');
            $table->date('tgl_kompen');
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
