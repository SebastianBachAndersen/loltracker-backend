<?php

namespace App\Http\Controllers;

use App\Http\Resources\SummonerResource;
use App\Models\Summoner;
use App\Services\MatchService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SummonerController extends Controller
{
    public function index(string $region, string $summonerName) {
        $response = Http::withHeaders(['X-Riot-Token' => config("services.riot_games_api.api_key")])
            ->get("https://" . config("services.riot_games_api.servers.$region") .  config("services.riot_games_api.url") . "/lol/summoner/v4/summoners/by-name/$summonerName");

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

            $matchService = new MatchService($region);

            $matches = $matchService->getMatchHistory($summoner->puuid);

            $matchHistory = [];
            foreach ($matches as $matchId) {
                $matchHistory[] = $matchService->getMatch($matchId);
            }

            return [
                'matchHistory' => $matchHistory,
                'summoner' => new SummonerResource($summoner),
                'result' => 'success'
            ];
        } else {
            return response(['error' => 'not found'], 404);
        }
    }
}
