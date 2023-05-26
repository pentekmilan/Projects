<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Faker\Core\Number;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;


class EventController extends Controller
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
        $this->authorize('create', Event::class);
        $game = Game::findOrFail($id);
        if ($game->start != date('Y-m-d') || $game->finished == true) {
            Session::flash('game_not_today_event_create', $game->id);
            return Redirect::route('games.show', $id);
        }
        return view('events.create', [
            'game' => Game::findOrFail($id)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $game_id = $request->input('game_id');
        $validated = $request->validate(
            [
                'minute' => [
                    'required', 'numeric', 'integer', 'min:1', 'max:90'
                ],
                'type' => [
                    'required', 'in:Goal,Own goal,Yellow card,Red card'
                ],
                'player_id' => [
                    'required', 'integer',

                ],
            ]
        );
        $event = Event::factory()->create([
            'minute' => $validated['minute'],
            'type' => $validated['type'],
            'game_id' => $game_id,
            'player_id' => $validated['player_id']
        ]);
        Session::flash('event_created', $event->type);
        return Redirect::route('games.show', $game_id);
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
    public function update(Request $request, Numeric $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        if ($event->game->start != date('Y-m-d')) {
            Session::flash('game_not_today_event_delete', $event->game_id);
            return Redirect::route('games.show', $event->game_id);
        }
        $this->authorize('delete', $event);
        $event->delete();
        Session::flash('event_deleted', $event->id);
        return Redirect::route('games.show', $event->game_id);
    }
}
