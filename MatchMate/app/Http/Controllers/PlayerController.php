<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\Player;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $this->authorize('create', Player::class);
        return view('players.create', [
            'team' => Team::findOrFail($id)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required', 'string'
            ],
            'number' => [
                'required', 'integer', 'min:1'
            ],
            'birthdate' => ['required', 'date', 'before:today'],
        ]);
        $team = Team::findOrFail($request->input('team_id'));
        $player = $team->players()->create($validated);
        Session::flash('player_created', $player->name);
        return Redirect::route('teams.show', $request->input('team_id'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $player = Player::findOrFail($id);
        $this->authorize('delete', $player);
        if ($player->events()->count() === 0) {
            $player->delete();
            Session::flash('player_deleted', $player->name);
            return Redirect::route('teams.show', $player->team_id);
        } else {
            Session::flash('player_not_deleted', $player->name);
            return Redirect::route('teams.show', $player->team_id);
        }
    }
}
