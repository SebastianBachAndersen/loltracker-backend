<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SummonerLp extends Model
{
    use HasFactory;

    protected $table = 'summoner_lp';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'summonerId',
        'queueType',
        'tier',
        'rank',
        'leaguePoints',
        'wins',
        'losses',
        'details'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    ];

    
    public function getDetailsAttribute() {
        return json_decode($this->attributes['details'], true);
    }
}
