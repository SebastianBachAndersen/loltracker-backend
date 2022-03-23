<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SummonerChampionStatsSummary extends Model
{
    use HasFactory;

    protected $table = 'summoner_champion_stats_summarys';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'summonerId',
        'championId',
        'wins',
        'losses',
        'winRate',
        'kda',
        'kills',
        'deaths',
        'assits',
        'cs',
        'damage',
        'gold',
        'doubleKills',
        'tripleKills',
        'quadraKills',
        'pentaKills',
        'season'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    public function champion()
    {
        return $this->hasOne(\App\Models\Champion::class, 'championId', 'championId');
    }

    public function getChampionAttribute()
    {
        return $this->champion()->first();
    }
}
