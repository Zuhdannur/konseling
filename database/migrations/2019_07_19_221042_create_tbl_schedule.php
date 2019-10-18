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
            $table->integer('consultant_id')->unsigned()->default(0);

            $table->string('title');
            $table->text('desc');
            $table->date('tgl_pengajuan')->nullable();
            $table->string('type_schedule');
            $table->string('channel_url')->nullable();


            /*Saat konseling tidak diterima sama sekali*/
            $table->integer('expired')->default(0);
            /*Saat konseling diterima lalu dibatalkan oleh guru/siswa */
            $table->integer('canceled')->default(0);
            /*3 tipe, 0 = pending, 1 = diterima, 2 = selesai*/
            $table->integer('status')->default(0);
            /*Saat konseling sedang berlangsung*/
            /*Pilihan 0 atau 1*/
            $table->integer('progress')->default(0);


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
