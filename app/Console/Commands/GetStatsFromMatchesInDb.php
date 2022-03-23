<?php

namespace App\Console\Commands;

use App\Models\MatchDetail;
use App\Services\StatisticService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        Log::info("STARTING CRON: GetStatsFromMatchesInDb");

        $this->withProgressBar(MatchDetail::where('calculated', false)->get(), function($match) {
            StatisticService::saveChampionStatisticsForGame($match->details);
            $match->calculated = true;
            $match->save();
        });

        Log::info("FINISHED CRON: GetStatsFromMatchesInDb");
    }
}
