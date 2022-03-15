<?php

namespace App\Services;

use App\Models\MatchDetail;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MatchService
{

    private PendingRequest $client;
    private string $url;

    public function __construct($region)
    {
        foreach (config('services.riot_games_api.regions') as $key => $value) {
            if (in_array($region, $value)) {
                $region = $key;
            }
        }
        $this->client = Http::withHeaders(['X-Riot-Token' => config("services.riot_games_api.api_key")]);
        $this->url = "https://" . $region .  config("services.riot_games_api.url");
    }


    public function getMatch(string $matchId) {

        if ($matchDetail = MatchDetail::where('matchId', $matchId)->first()) {
            return $matchDetail;
        }

        $response = $this->client->get($this->url . "/lol/match/v5/matches/$matchId");

        if ($response->successful()) {

            return MatchDetail::create([
                'matchId' => $response['metadata']['matchId'],
                'match_created_at' =>  $response['info']['gameCreation'],
                'details' => $response->body()]);
        }

    }

    public function getMatchHistory(string $puuid) {

        $response = $this->client->get($this->url . "/lol/match/v5/matches/by-puuid/$puuid/ids");

        if ($response->successful()) {
            return $response->object();
        } else {
            return null;
        }
    }


}
