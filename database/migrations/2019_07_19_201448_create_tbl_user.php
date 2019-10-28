<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('avatar')->nullable();
            $table->string('name',50)->nullable();

            /*Username dapat berupa NIS ataupun NIP*/
            $table->string('username',30)->unique();

            $table->string('password',255);
            $table->string('role',10);
            $table->string('api_token',255)->nullable();
            $table->string('firebase_token',255)->nullable();
            $table->integer('hasEverChangePassword')->default(0);
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
        Schema::dropIfExists('tbl_user');
    }
}
