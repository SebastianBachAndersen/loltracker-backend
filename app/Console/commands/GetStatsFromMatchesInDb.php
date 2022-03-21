<?php

namespace App\Console\Commands;

use App\Models\MatchDetail;
use App\Models\SummonerChampionStat;
use App\Services\StatisticService;
use Illuminate\Console\Command;

class GetStatsFromMatchesInDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:getStatFromMatchesInDb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Takes all matches from database and checks if the stats have been extracted, otherwise it extracts them';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->withProgressBar(MatchDetail::all(), function($match) {
            if (SummonerChampionStat::where('matchId', $match->matchId)->first()) {
                return;
            }
            StatisticService::saveChampionStatisticsForGame($match->details);
        });
    }
}
