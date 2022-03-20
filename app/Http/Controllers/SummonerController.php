<?php

namespace App\Http\Controllers;

use App\Http\Resources\SummonerResource;
use App\Models\Summoner;
use App\Services\LeagueService;
use App\Services\MatchService;
use App\Services\SummonerService;
use Illuminate\Support\Facades\Http;

class SummonerController extends Controller
{
    public function index(string $region, string $summonerName) {

        $SummonerService = new SummonerService($region);

        $summoner = $SummonerService->getSummoner($summonerName);

        if ($summoner) {

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

    public function saveCurrentLp(string $region, string $summonerId) {
        $leagueService = new LeagueService($region);
        $response = $leagueService->saveCurrentSummonerLp($summonerId);

        return response(['result' => $response]);
    }
}
