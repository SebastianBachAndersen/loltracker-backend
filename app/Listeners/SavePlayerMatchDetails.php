<?php

namespace App\Listeners;

use App\Services\StatisticService;

class SavePlayerMatchDetails
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        StatisticService::saveChampionStatisticsForGame($event->match->details);
    }
}
