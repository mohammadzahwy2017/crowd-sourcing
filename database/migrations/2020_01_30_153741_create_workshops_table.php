<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workshops', function (Blueprint $table) {
            $table->increments('id');
            $table->String('key')->unique();
            $table->integer('monitor')->unsigned();;
            $table->String('title')->nullable();
            $table->String('body');
            $table->Integer('nb_of_users');
            $table->Integer('nb_of_participated')->default(0);
            $table->Integer('answerd')->default(0);
            $table->Integer('started')->default(0);
            $table->String('question')->nullable();
            $table->Integer('shuffle_stage')->default(0);
            $table->String('finished')->nullable();
            $table->foreign('monitor')->references('id')->on('users');
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
        Schema::dropIfExists('workshops');
    }
}
