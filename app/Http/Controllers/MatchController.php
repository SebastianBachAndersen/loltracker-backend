<?php

namespace App\Http\Controllers;

use App\Services\MatchService;
use Illuminate\Support\Facades\Log;

class MatchController extends Controller
{
    public function index(string $region, string $matchRegionId)
    {
        $matchService = new MatchService($region);
        $timeline = $matchService->getMatchTimeLine($matchRegionId);
        if (!empty($timeline)) {

            return [
                'matchTimeLine' => $timeline,
                'result' => 'success'
            ];
        } else {
            return response(['error' => 'not found'], 404);
        }
    }
}
