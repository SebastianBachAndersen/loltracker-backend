<?php

namespace App\Services;

use App\Models\Summoner;
use App\Models\SummonerLp;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LeagueService
{

    private PendingRequest $client;
    private string $url;

    public function __construct($region)
    {
        $this->client = Http::withHeaders(['X-Riot-Token' => config("services.riot_games_api.api_key")]);
        $this->url = "https://" . config("services.riot_games_api.servers.$region") .  config("services.riot_games_api.url");
    }

    public function saveCurrentSummonerLp(string $summonerId)
    {
        $response = $this->client->get($this->url . "/lol/league/v4/entries/by-summoner/$summonerId");
        if ($response->successful()) {
            $soloRanked = null;
            foreach ($response->json() as $rank) {
                if ($rank['queueType'] === "RANKED_SOLO_5x5") {
                    $soloRanked = $rank;
                }
            }
            SummonerLp::create([
                'summonerId' => $summonerId,
                'queueType' => $soloRanked['queueType'],
                'tier' => $soloRanked['tier'],
                'rank' => $soloRanked['rank'],
                'leaguePoints' => $soloRanked['leaguePoints'],
                'wins' => $soloRanked['wins'],
                'losses' => $soloRanked['losses'],
                'details' => $response->body()
            ]);
            return "created";
        } else {
            Log::alert($response);
            return "error";
        }
    }
}
