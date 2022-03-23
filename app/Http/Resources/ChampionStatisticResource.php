<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChampionStatisticResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $totalGames = $this->wins + $this->losses;

        return [
            "average" => [
                "kills" => $this->kills ?? 1 / $totalGames ?? 1,
                "deaths" => $this->deaths ?? 1 / $totalGames ?? 1,
                "assits" => $this->assits ?? 1 / $totalGames ?? 1,
                "cs" => $this->cs ?? 1 / $totalGames ?? 1,
                "damage" => $this->damage ?? 1 / $totalGames ?? 1,
                "gold" => $this->gold ?? 1 / $totalGames ?? 1,
            ],
            "name" => $this->champion->name,
            "wins" => $this->wins,
            "losses" => $this->losses,
            "winRate" => $this->winRate,
            "kda" => $this->kda,
            "doubleKills" => $this->doubleKills,
            "tripleKills" => $this->tripleKills,
            "quadraKills" => $this->quadraKills,
            "pentaKills" => $this->pentaKills,
            "season" => $this->season,
        ];
    }
}
