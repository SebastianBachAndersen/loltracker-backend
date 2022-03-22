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

    public function getSummoner(string $summonerName)
    {

        $response = $this->client->get($this->url . "/lol/summoner/v4/summoners/by-name/$summonerName");

        if ($response->successful()) {
            $this->summoner = $summoner = Summoner::where('summonerId', $response['id'])->first();
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
    public function getLpGrafData()
    {

        $arrayToReturn = [];

        foreach ($this->summoner->lp()->take(10)->get() as $key => $data) {
            $points = $this->tierToPoints($data);
            $arrayToReturn['data'][] = array(
                'data' => $data->created_at,
                'league_points' => $points,
            );
        }
        return $arrayToReturn;
    }
    private function tierToPoints($data)
    {
        $tierAsPoints = config("leagueRanks.tierAsPoints");
        $rankAsPoints = config("leagueRanks.rankAsPoints");
        $tierName = strtolower($data->tier);
        $tierAsPoints = $tierAsPoints[$tierName];
        $rankAsPoints = $rankAsPoints[$data->rank];
        return $tierAsPoints + $rankAsPoints + $data->leaguePoints;
    }
}
