<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', session('per_page', 10));
        session('per_page', $perPage);
        $games = Game::where('start', '!=', date('Y-m-d'))
            ->orderby('start')
            ->paginate($perPage);
        //if cannot find any games on the current page, redirect to the last page
        if (count($games->all()) == 0) {
            return Redirect::route('games.index', ['per_page' => $perPage, 'page' => $games->lastPage()]);
        }
        $games->appends(['per_page' => $perPage, 'page' => $games->currentPage()]);
        return view('games.index', compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Game::class);
        return view('games.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'start' => ['required', 'date', 'after:today'],
                'homeTeam_id' => ['required', 'integer'],
                'awayTeam_id' => ['required', 'integer', 'different:homeTeam_id'],
            ]
        );
        $game = Game::factory()->create([
            'start' => $validated['start'],
            'home_team_id' => $validated['homeTeam_id'],
            'away_team_id' => $validated['awayTeam_id'],
            'finished' => false,
        ]);
        Session::flash('game_created', $game);
        return Redirect::route('games.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('games.show', [
            'id' => $id,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $game = Game::find($id);
        $this->authorize('update', $game);
        return view('games.edit', ['id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $game = Game::find($id);
        $this->authorize('update', $game);
        $validated = $request->validate(
            [
                'start' => ['nullable', 'date'],
                'homeTeam_id' => ['nullable', 'integer'],
                'awayTeam_id' => ['nullable', 'integer', 'different:homeTeam_id'],
            ]
        );
        if ($validated['start'] == null) {
            $validated['start'] = $game->start;
        }
        if ($validated['homeTeam_id'] == null) {
            $validated['homeTeam_id'] = $game->home_team_id;
        }
        if ($validated['awayTeam_id'] == null) {
            $validated['awayTeam_id'] = $game->away_team_id;
        }
        $game->update([
            'start' => $validated['start'],
            'home_team_id' => $validated['homeTeam_id'],
            'away_team_id' => $validated['awayTeam_id'],
            'finished' => $validated['start'] >= date('Y-m-d') ? false : true,
        ]);
        Session::flash('game_updated', $game);
        return Redirect::route('games.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $game = Game::find($id);
        $this->authorize('delete', $game);
        if ($game->events()->count() == 0) {
            $game->delete();
            Session::flash('game_deleted', $game);
        } else {
            Session::flash('game_not_deleted', $game);
        }
        return Redirect::route('games.index');
    }

    public function finish(string $id)
    {
        $game = Game::find($id);
        $this->authorize('update', $game);
        if ($game->start != date('Y-m-d')) {
            Session::flash('game_not_today_finish', $game->id);
            return Redirect::route('games.show', $id);
        }
        $game->update([
            'finished' => true,
        ]);
        Session::flash('game_finished', $game);
        return Redirect::route('games.show', $id);
    }
}
