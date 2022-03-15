<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('summoner_lp', function (Blueprint $table) {
            $table->id();
            $table->string('summonerId');
            $table->string('queueType');
            $table->string('tier');
            $table->string('rank');
            $table->integer('leaguePoints');
            $table->integer('wins');
            $table->integer('losses');
            $table->json('details');
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
        Schema::drop('summoner_lp');
    }
};
