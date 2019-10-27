<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblDetailUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_detail_user', function (Blueprint $table) {
            $table->bigIncrements('id');

            /*Foreign key*/
            $table->integer('id_user')->length(11)->unsigned();

            $table->string('jenkel', 25)->nullable();
            $table->string('nomor_hp', 12)->nullable();
            $table->string('kelas', 25)->nullable();
            $table->integer('id_sekolah')->length(11)->unsigned();

            $table->text('alamat')->nullable();
            $table->string('kota', 50)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('kota_lahir', 50)->nullable();
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
        Schema::dropIfExists('tbl_detail_user');
    }
}
