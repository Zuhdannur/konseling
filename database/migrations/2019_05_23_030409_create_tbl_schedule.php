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
            $table->string('title');
            $table->date('tgl_pengajuan');
            $table->text('desc');
            $table->string('type_schedule');
            $table->integer('status')->default(0);
            $table->integer('consultant_id')->unsigned()->default(0);
            $table->string('room_id')->nullable();
            $table->string('time')->nullable();
            $table->string('location')->nullable();
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
