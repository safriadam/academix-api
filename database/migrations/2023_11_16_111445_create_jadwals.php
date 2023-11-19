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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->unsignedBigInteger('id_jdwl')->autoIncrement();
            $table->unsignedInteger('id_kls');
            $table->unsignedBigInteger('id_mk');
            $table->string('ruang');
            $table->string('hari');
            $table->time('start');
            $table->time('finish');
            $table->integer('jumlah_jam');
            $table->string('token')->nullable();
            $table->string('expires_at')->nullable();   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_jdwl');
    }
};
