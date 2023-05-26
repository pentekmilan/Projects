<?php

use App\Models\Game;
use App\Models\Team;
use App\Models\Event;
use App\Models\Player;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


function getOnGoingGames($games)
{
    return $games
        ->where('start', '=', date('Y-m-d'))->where('finished', '=', false)
        ->sortby('start');
}

function getFavourites()
{
    $user = Auth::user();
    return Auth::check() ? $user->teams()->get() : null;
}

//Games
function getUpcomingGames($games)
{
    return $games->where('finished', '=', false)
        ->where('start', '>', date('Y-m-d'))
        ->sortby('start');
}

function getFinishedGames($games)
{
    return $games->where('finished', '=', true)
        ->sortby('start');
}

function CalculateHomeTeamGoals($game)
{
    $homeTeamGoals = $game->events
        ->where('type', 'Goal')
        ->filter(function ($event) use ($game) {
            return $game->homeTeam->players->contains('id', $event->player_id);
        })
        ->count();
    $homeTeamGoals += $game->events
        ->where('type', 'Own goal')
        ->filter(function ($event) use ($game) {
            return $game->awayTeam->players->contains('id', $event->player_id);
        })->count();
    return $homeTeamGoals;
}

function CalculateAwayTeamGoals($game)
{
    $awayTeamGoals = $game->events
        ->where('type', 'Goal')
        ->filter(function ($event) use ($game) {
            return $game->awayTeam->players->contains('id', $event->player_id);
        })
        ->count();
    $awayTeamGoals += $game->events
        ->where('type', 'Own goal')
        ->filter(function ($event) use ($game) {
            return $game->homeTeam->players->contains('id', $event->player_id);
        })
        ->count();
    return $awayTeamGoals;
}

function CalculateTeamStandings($teams, $games)
{
    $standings = [];
    foreach ($teams as $team) {
        $teamPoints = 0;
        $teamGoals = 0;
        $teamGoalsConceded = 0;
        //check if team has played any games
        if ($team->homeGames->count() != 0 && $team->awayGames->count() != 0) {
            foreach ($games as $game) {
                //check if game is finished and if the team is playing in the game
                if ($game->finished == false || $game->homeTeam->name != $team->name && $game->awayTeam->name != $team->name) {
                    continue;
                }
                $homeTeamGoals = CalculateHomeTeamGoals($game);
                $awayTeamGoals = CalculateAwayTeamGoals($game);
                if ($homeTeamGoals > $awayTeamGoals) {
                    //if home team is the team we are calculating standings for
                    if ($game->homeTeam->name == $team->name) {
                        $teamPoints += 3;
                        $teamGoals += $homeTeamGoals;
                        $teamGoalsConceded += $awayTeamGoals;
                    }
                    //if away team is the team we are calculating standings for
                    else {
                        $teamPoints += 0;
                        $teamGoals += $awayTeamGoals;
                        $teamGoalsConceded += $homeTeamGoals;
                    }
                }

                if ($homeTeamGoals < $awayTeamGoals) {
                    //if home team is the team we are calculating standings for
                    if ($game->awayTeam->name == $team->name) {
                        $teamPoints += 3;
                        $teamGoals += $awayTeamGoals;
                        $teamGoalsConceded += $homeTeamGoals;
                    }
                    //if away team is the team we are calculating standings for
                    else {
                        $teamPoints += 0;
                        $teamGoals += $homeTeamGoals;
                        $teamGoalsConceded += $awayTeamGoals;
                    }
                }
                //if the games is a draw
                if ($homeTeamGoals == $awayTeamGoals) {
                    $teamPoints += 1;
                    $teamGoals += $homeTeamGoals;
                    $teamGoalsConceded += $awayTeamGoals;
                }
            }
        }
        $GoalDifference = $teamGoals - $teamGoalsConceded;
        $standings[] = [
            'team_name' => $team->name,
            'points' => $teamPoints,
            'Goal_difference' => $GoalDifference,
            'team_id' => $team->id,
        ];
    }
    return $standings;
}

function SortStandings($standings)
{
    usort($standings, function ($a, $b) {
        // Sort by points
        if ($a['points'] > $b['points']) {
            return -1;
        } elseif ($a['points'] < $b['points']) {
            return 1;
        } else {
            // Sort by goal difference
            if ($a['Goal_difference'] > $b['Goal_difference']) {
                return -1;
            } elseif ($a['Goal_difference'] < $b['Goal_difference']) {
                return 1;
            }
            //Sort by team name
            else {
                return strcmp($a['team_name'], $b['team_name']);
            }
        }
    });
    return $standings;
}

//User
function CheckIfFavourite($id)
{
    $user = Auth::user();
    $team_ids = $user
        ->teams()
        ->pluck('team_id')
        ->toArray();
    return in_array($id, $team_ids);
}
