<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SummonerController extends Controller
{
    public function index(string $summonerName) {
        $response = Http::withHeaders(['X-Riot-Token' => env('RIOT_GAMES_API_KEY')])
            ->get(env('RIOT_GAMES_API_URL') . "/lol/summoner/v4/summoners/by-name/$summonerName");

        Log::info($response);
    }
}
