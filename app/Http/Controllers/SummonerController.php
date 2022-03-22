<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChampionStatisticResource;
use App\Http\Resources\SummonerResource;
use App\Models\Summoner;
use App\Models\SummonerChampionStatsSummary;
use App\Services\LeagueService;
use App\Services\MatchService;
use App\Services\SummonerService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

            $championStats = SummonerChampionStatsSummary::where('summonerId', $summoner->summonerId)->get();
            $championStats = $championStats->map(function ($champStat) {
                return (new ChampionStatisticResource($champStat))->resolve();
            });
            return [
                'matchHistory' => $matchHistory,
                'championStats' => $championStats,
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
