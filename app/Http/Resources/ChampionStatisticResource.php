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
                "kills" => $this->average($this->kills ?? 1, $totalGames ?? 1),
                "deaths" => $this->average($this->deaths ?? 1, $totalGames ?? 1),
                "assits" => $this->average($this->assits ?? 1, $totalGames ?? 1),
                "cs" => $this->average($this->cs ?? 1, $totalGames ?? 1, 0),
            ],
            "championName" => $this->champion->name,
            "championNameId" => $this->champion->nameId,
            "wins" => $this->wins,
            "losses" => $this->losses,
            "totalGames" => $totalGames,
            "winRate" => $this->winRate,
            "kda" => $this->kda,
            "doubleKills" => $this->doubleKills,
            "tripleKills" => $this->tripleKills,
            "quadraKills" => $this->quadraKills,
            "pentaKills" => $this->pentaKills,
            "season" => $this->season,
        ];
    }

    function average($num, $total, $decimals = 1)
    {
        $result = $num / $total;
        return round($result, $decimals);
    }
}
