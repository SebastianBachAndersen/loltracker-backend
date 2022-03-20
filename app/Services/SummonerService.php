<?php

namespace App\Services;

use App\Models\Summoner;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class SummonerService
{


    private PendingRequest $client;
    private string $url;

    public function __construct($region)
    {
        $this->client = Http::withHeaders(['X-Riot-Token' => config("services.riot_games_api.api_key")]);
        $this->url = "https://" . config("services.riot_games_api.servers.$region") .  config("services.riot_games_api.url");
    }

    public function getSummoner(string $summonerName) {

        $response = $this->client->get($this->url . "/lol/summoner/v4/summoners/by-name/$summonerName");

        if ($response->successful()) {
            $summoner = Summoner::where('summonerId', $response['id'])->first();
            if (!$summoner) {
                $summoner = Summoner::create([
                    'summonerId' => $response['id'],
                    'accountId' => $response['accountId'],
                    'puuid' => $response['puuid'],
                    'name' => $response['name'],
                    'profileIconId' => $response['profileIconId'],
                    'revisionDate' => $response['revisionDate'],
                    'summonerLevel' => $response['summonerLevel']

                ]);
            }
            return $summoner;
        }

        return null;
    }



}

