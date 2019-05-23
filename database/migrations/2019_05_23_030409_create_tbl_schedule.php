<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_schedule', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('requester_id')->unsigned();
            $table->date('tgl_pengajuan');
            $table->integer('status')->default(0);
            $table->integer('consultant_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_schedule');
    }
}
