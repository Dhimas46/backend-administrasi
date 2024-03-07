<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusFieldToHistoryPenghuniTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('history_penghuni', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->after('tanggal_selesai_hunian')->comment('1: Menghuni, 0: Tidak Menghuni');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('history_penghuni', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
