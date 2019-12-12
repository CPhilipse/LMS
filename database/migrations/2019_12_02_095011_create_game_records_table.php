<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_records', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Define user_id - so define which user belongs to certain games.
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            // Define game_id - so define which game belongs to certain users.
            $table->unsignedBigInteger('game_id');
            // Make just made game_id FK so you can make a relation.
            $table->foreign('game_id')->references('id')->on('games');

            $table->boolean('admin');
            $table->integer('point');
            $table->boolean('invited');
            $table->boolean('chosen')->default(0);
            $table->boolean('out')->default(0);
            $table->string('team')->default(' ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_records');
    }
}
