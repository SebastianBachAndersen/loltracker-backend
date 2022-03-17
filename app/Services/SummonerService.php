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








}

