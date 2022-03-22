<?php

namespace App\Services;

use App\Models\SummonerChampionStat;
use App\Models\SummonerChampionStatsSummary;
use Illuminate\Support\Facades\DB;

class StatisticService
{

    public static function saveChampionStatisticsForGame($match) {
            DB::transaction( function () use ($match) {
                foreach ($match['info']['participants'] as $participant) {
                    $champStats = SummonerChampionStat::create([
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
                    if ($sumStats = SummonerChampionStatsSummary::where('championId', $participant['championId'])->where('summonerId',$participant['summonerId'])->first()) {
                            $sumStats->wins += ($champStats->win ? 1 : 0);
                            $sumStats->losses += (!$champStats->win ? 1 : 0);
                            $sumStats->kills += $champStats->kills;
                            $sumStats->deaths += $champStats->deaths;
                            $sumStats->assits += $champStats->assits;
                            $sumStats->cs += $champStats->cs;
                            $sumStats->damage += $champStats->damage;
                            $sumStats->gold += $champStats->gold;
                            $sumStats->doubleKills += $champStats->doubleKills;
                            $sumStats->tripleKills += $champStats->tripleKills;
                            $sumStats->quadraKills += $champStats->quadKills;
                            $sumStats->pentaKills += $champStats->pentaKills;
                            $sumStats->kda = ($sumStats->kills + $sumStats->assits) ?? 1 / $sumStats->deaths ?? 1;
                            $sumStats->winRate = ($sumStats->wins / ($sumStats->wins + $sumStats->losses)) * 100;
                            $sumStats->save();
                            return;
                    }

                    SummonerChampionStatsSummary::create([
                        ...$champStats->toArray(),
                        'wins' => ($champStats->win ? 1 : 0),
                        'losses' => (!$champStats->win ? 1 : 0),
                        'kda' => ($champStats->kills + $champStats->assits) ?? 1 / $champStats->deaths ?? 1,
                        'winRate' => $champStats->win / ($champStats->win + !$champStats->win) * 100,
                    ]);

                }
            });

    }

    public static function saveAndCalculateChampionStatisticsForSummoner(int $championId, string $summonerId) {

        $championStatistics = SummonerChampionStat::where('summonerId', $summonerId)->where('championId', $championId)->get();

        if ($championStatistics->count() > 0) {
            $sumStats = new SummonerChampionStatsSummary();
            $sumStats->summonerId = $summonerId;
            $sumStats->championId = $championId;
            foreach ($championStatistics as $championStatistic) {
                $sumStats->wins += ($championStatistic->win ? 1 : 0);
                $sumStats->losses += (!$championStatistic->win ? 1 : 0);
                $sumStats->kills += $championStatistic->kills;
                $sumStats->deaths += $championStatistic->deaths;
                $sumStats->assits += $championStatistic->assits;
                $sumStats->cs += $championStatistic->cs;
                $sumStats->damage += $championStatistic->damage;
                $sumStats->gold += $championStatistic->gold;
                $sumStats->doubleKills += $championStatistic->doubleKills;
                $sumStats->tripleKills += $championStatistic->tripleKills;
                $sumStats->quadraKills += $championStatistic->quadKills;
                $sumStats->pentaKills += $championStatistic->pentaKills;
            }
            $sumStats->kda = ($sumStats->kills + $sumStats->assits) ?? 1 / $sumStats->deaths ?? 1;
            $sumStats->winRate = ($sumStats->wins / $championStatistics->count()) * 100;
            return SummonerChampionStatsSummary::create($sumStats->toArray());
        } else {
            return null;
        }
    }
}
