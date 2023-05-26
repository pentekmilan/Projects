<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Event;
use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\Player;
use App\Models\User;
use App\Models\Game;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $teams = Team::factory(rand(15, 20))->create();
        //create 12 player for each team
        $teams->each(function ($team) {
            $team->players()->createMany(
                Player::factory(12)->make()->toArray()
            );
        });
        $teams->each(function ($team) use ($teams) {
            $reducedTeams = $teams->filter(function ($item) use ($team) {
                return $item->id !== $team->id;
            });
            for ($i = 0; $i < rand(3, 5); $i++) {
                $games[] = Game::factory()->create([
                    'home_team_id' => $team->id,
                    'away_team_id' => $reducedTeams->random()->id,
                ]);
            }
        });

        $games = Game::all();
        $games->each(function ($game) {
            for ($i = 0; $i < rand(3, 5); $i++) {
                $team = rand(0, 1) ? $game->homeTeam : $game->awayTeam;
                $player = $team->players->random();
                if ($game->start <= date("Y-m-d")) {
                    $events[] = Event::factory()->create([
                        'game_id' => $game->id,
                        'player_id' => $player->id,
                    ]);
                }
            }
        });

        $users = User::factory(15)->create();
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@szerveroldali.hu',
            'password' => bcrypt('adminpwd'),
            'is_admin' => true,
        ]);
        $users->push($admin);
    }
}
