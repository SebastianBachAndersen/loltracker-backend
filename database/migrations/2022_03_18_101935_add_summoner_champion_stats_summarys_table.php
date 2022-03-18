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
        Schema::create('summoner_champion_stats_summarys', function (Blueprint $table) {
            $table->id();
            $table->string('summonerId');
            $table->integer('championId');
            $table->integer('wins');
            $table->integer('losses');
            $table->integer('winRate');
            $table->float('kda');
            $table->integer('kills');
            $table->integer('deaths');
            $table->integer('assits');
            $table->integer('cs');
            $table->integer('damage');
            $table->integer('gold');
            $table->integer('doubleKills');
            $table->integer('tripleKills');
            $table->integer('quadraKills');
            $table->integer('pentaKills');
            $table->integer("season")->default(11);
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
        Schema::drop('summoner_champion_stats_summarys');
    }
};
