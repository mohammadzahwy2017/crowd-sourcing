<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('groupes', function (Blueprint $table) {
                $table->increments('id');
                $table->string('workshop_key');
                $table->foreign('workshop_key')->references('key')->on('workshops');
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id')->references('id')->on('users'); 
                $table->integer('idea_id')->unsigned();
                $table->foreign('idea_id')->references('id')->on('ideas');
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
        Schema::dropIfExists('groupes');
    }
}
