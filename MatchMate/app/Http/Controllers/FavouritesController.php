<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;


class FavouritesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('favourites.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            Session::flash('login_required');
            return Redirect::route('login');
        }
        DB::table('team_user')->insert([
            'user_id' => $request->input('user_id'),
            'team_id' => $request->input('team_id'),
        ]);
        Session::flash('favourite_added', $request->input('team_id'));
        return Redirect::back();
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
        $team = Team::find($id);
        $this->authorize('delete', $team);
        $user = auth()->user();
        DB::table("team_user")->where('team_id', $id)->where('user_id', $user->id)->delete();
        Session::flash('favourite_deleted');
        return Redirect::back();
    }
}
