<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkshopUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workshop_users', function (Blueprint $table) {
            $table->increments('id');
            $table->String('workshop');
            $table->foreign('workshop')->references('key')->on('workshops');
            $table->integer('participant')->unsigned();
            $table->foreign('participant')->references('id')->on('users');
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
        Schema::dropIfExists('workshop_users');
    }
}
