<?php

namespace App\Services;

use App\Models\SummonerChampionStat;
use App\Models\SummonerChampionStatsSummary;

class StatisticService
{

    public static function saveChampionStatisticsForGame($match) {

        foreach ($match['info']['participants'] as $participant) {
            SummonerChampionStat::create([
                'summonerId' => $participant['summonerId'],
                'championId' => $participant['championId'],
                'matchId' => $match['metadata']['matchId'],
                'win' => $participant['win'],
                'kills' => $participant['kills'],
                'deaths' => $participant['deaths'],
                'assits' => $participant['assists'],
                'cs' => $participant['totalMinionsKilled'],
                'damage' => $participant['totalDamageDealt'],
                'gold' => $participant['goldEarned'],
                'doubleKills' => $participant['doubleKills'],
                'tripleKills' => $participant['tripleKills'],
                'quadraKills' => $participant['quadraKills'],
                'pentaKills' => $participant['pentaKills'],
                'season'
            ]);
        }
    }

    public static function saveAndCalculateChampionStatisticsForSummoner(int $championId, string $summonerId) {

        $championStatistics = SummonerChampionStat::where('summonerId', $summonerId)->where('championId', $championId)->get();

        if ($championStatistics->count() > 0) {
            $summonerChampionStatistics = new SummonerChampionStatsSummary();
            $summonerChampionStatistics->summonerId = $summonerId;
            $summonerChampionStatistics->championId = $championId;
            foreach ($championStatistics as $championStatistic) {
                $summonerChampionStatistics->wins += ($championStatistic->win ? 1 : 0);
                $summonerChampionStatistics->losses += (!$championStatistic->win ? 1 : 0);
                $summonerChampionStatistics->kills += $championStatistic->kills;
                $summonerChampionStatistics->deaths += $championStatistic->deaths;
                $summonerChampionStatistics->assits += $championStatistic->assits;
                $summonerChampionStatistics->cs += $championStatistic->cs;
                $summonerChampionStatistics->damage += $championStatistic->damage;
                $summonerChampionStatistics->gold += $championStatistic->gold;
                $summonerChampionStatistics->doubleKills += $championStatistic->doubleKills;
                $summonerChampionStatistics->tripleKills += $championStatistic->tripleKills;
                $summonerChampionStatistics->quadraKills += $championStatistic->quadKills;
                $summonerChampionStatistics->pentaKills += $championStatistic->pentaKills;
            }
            $summonerChampionStatistics->kda = ($summonerChampionStatistics->kills + $summonerChampionStatistics->assits) / $summonerChampionStatistics->deaths;
            $summonerChampionStatistics->winRate = ($summonerChampionStatistics->wins / $championStatistics->count()) * 100;
            return SummonerChampionStatsSummary::create($summonerChampionStatistics->toArray());
        } else {
            return null;
        }
    }
}
