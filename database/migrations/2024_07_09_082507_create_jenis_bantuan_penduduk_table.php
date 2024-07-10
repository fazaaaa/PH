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
        Schema::create('jenis_bantuan_penduduk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_penduduk');
            $table->unsignedBigInteger('jenis_bantuan_id');
            $table->timestamps();

            $table->foreign('id_penduduk')->references('id')->on('penduduks')->onDelete('cascade');
            $table->foreign('jenis_bantuan_id')->references('id')->on('jenis_bantuans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_bantuan_penduduk');
    }
};
