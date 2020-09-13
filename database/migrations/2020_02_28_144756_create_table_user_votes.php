<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserVotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_votes', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id')->unsigned();
        $table->string('workshop_key');
        $table->integer('stage');
        $table->integer('voted')->default(0);
        $table->integer('idea_id');
        $table->foreign('workshop_key')->references('key')->on('workshops');
        $table->foreign('user_id')->references('id')->on('users'); 
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
        Schema::dropIfExists('user_votes');
    }
}
