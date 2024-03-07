<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('history_penghuni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rumah_id')->constrained('rumah');
            $table->foreignId('penghuni_id')->constrained('penghuni');
            $table->date('tanggal_mulai_hunian');
            $table->date('tanggal_selesai_hunian')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('history_penghuni');
    }
};
