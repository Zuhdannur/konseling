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
            $table->date('tgl_pengajuan')->nullable();
            $table->text('desc');
            $table->string('type_schedule');

            $table->integer('status')->default(0);
            $table->integer('exp')->default(0);
            $table->integer('ended')->default(0);
            $table->integer('canceled')->default(0);

            $table->integer('consultant_id')->unsigned()->default(0);
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
