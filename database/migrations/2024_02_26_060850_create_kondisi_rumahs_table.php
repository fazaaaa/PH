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
        Schema::create('kondisi_rumahs', function (Blueprint $table) {
            $table->id();
            $table->integer('nik');
            $table->integer('id_penerima');
            $table->string('tmpt_berteduh');
            $table->string('jenis_lantai');
            $table->string('jenis_dinding');
            $table->string('fasilitas_mck');
            $table->string('sumber_listrik');
            $table->string('foto_rumah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kondisi_rumahs');
    }
};
