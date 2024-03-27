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
        Schema::create('penerimas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('nik');
            $table->bigInteger('no_kk');
            $table->string('nama');
            $table->string('status_pkj');
            $table->string('jk');
            $table->string('jb');
            $table->string('foto_diri');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimas');
    }
};
