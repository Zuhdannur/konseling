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

            $table->string('jenkel', 25);
            $table->string('nomor_hp', 12);
            $table->string('kelas', 25);
            $table->integer('id_sekolah')->length(11)->unsigned();

            $table->text('alamat');
            $table->string('kota', 50);
            $table->date('tanggal_lahir');
            $table->string('kota_lahir', 50);
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
