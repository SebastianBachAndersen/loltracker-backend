<?php

use App\Http\Controllers\SummonerController;
use App\http\Controllers\MatchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'ability:client'])->group(function () {
    Route::get('match/{region}/{matchRegionId}/timeline', [MatchController::class, 'index']);
    Route::get('summoner/{region}/{summonerName}', [SummonerController::class, 'index']);
});
Route::middleware(['throttle:openEndpoints'])->group(function () {
    Route::get('summoner/savelp/{region}/{summonerId}', [SummonerController::class, 'saveCurrentLp']);
});
