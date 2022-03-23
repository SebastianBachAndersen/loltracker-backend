<?php

namespace App\Console\Commands;

use App\Models\Champion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use function PHPSTORM_META\type;

class AddChampionsToDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:champions-to-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $champions = json_decode(file_get_contents(storage_path() . "/json/champions.json", true));
        foreach ($champions as $champion) {
            if (!Champion::where('championId', $champion->id)->first()) {
                Champion::create(['championId' => $champion->id, 'name' => $champion->name, 'nameId' => $champion->alias]);
            }
        }
    }
}
