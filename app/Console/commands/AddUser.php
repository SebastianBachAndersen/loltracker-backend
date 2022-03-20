<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;

class AddUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:user {name} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $psw = "";

        if ($this->argument('password')) {
            $psw = $this->argument('password');
        } else {
            $psw = Random::generate(14);
        }

        $user = User::create(['name' => $this->argument('name'), 'email' => $this->argument('name') . "@loltracker.net", 'password' => Hash::make($psw)]);
        $this->info($user->createToken('client', [config('sanctum.abilities.client')])->plainTextToken);
    }
}
