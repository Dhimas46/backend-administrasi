<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penghuni', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('foto_ktp')->nullable();
            $table->string('status_penghuni');
            $table->string('nomor_telepon');
            $table->string('status_pernikahan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penghuni');
    }
};
