<?php

namespace App\Http\Controllers;

use App\Models\Summoner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SummonerController extends Controller
{
    public function index(string $summonerName) {
        $response = Http::withHeaders(['X-Riot-Token' => env('RIOT_GAMES_API_KEY')])
            ->get(env('RIOT_GAMES_API_URL') . "/lol/summoner/v4/summoners/by-name/$summonerName");

        Log::info($response);
//"id":"nzDvmF-muHztmm9ymcEcemtlwJZb22lXg-Nmaffs9U6LZVA","accountId":"xyl36KBxZKmgviHTvevqI77puxvDvP5Ot0_jtmp4lAuFuw","puuid":"skRlolfMPNAa_0CKluimlBt55xp33XdpxYYSlf2jZE0HtM4Hsaj5HbqXhtZSGXp2Vg5aABGJetdN3A","name":"Sex med mænd","profileIconId":505,"revisionDate":1646833798739,"summonerLevel":281}
        $summoner = null;
        if ($response->successful()) {
            Log::info("success");
            if (!Summoner::where('summonerId', $response['id'])->first()) {
                $summoner = Summoner::create([
                    'summonerId' => $response['id'],
                    'accountId' => $response['accountId'],
                    'puuid' => $response['puuid'],
                    'name' => $response['name'],
                    'profileIconId' => $response['profileIconId'],
                    'revisionDate' => Carbon::createFromTimestamp($response['revisionDate'])->format('Y-m-d h:i:s'),
                    'summonerLevel' => $response['summonerLevel']

                ]);
                Log::info($summoner);
            }
            return response(["response" => "success"], 200);
        } else {
            return response(['error' => 'not found'], 404);
        }
    }
}
