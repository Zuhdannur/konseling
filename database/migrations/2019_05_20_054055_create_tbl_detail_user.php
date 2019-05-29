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
            $table->integer('id_user')->lenght(11)->unsigned();
            $table->string('gender',25);
            $table->text('address');
            $table->string('phone_number');
            $table->string('kelas')->nullable();
            $table->string('school')->nullable();
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
