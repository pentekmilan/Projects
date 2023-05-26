<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\Team;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('teams.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Team::class);
        return view('teams.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required', 'string', 'unique:teams,name'
            ],
            'shortname' => [
                'required', 'string', 'max:4', 'unique:teams,shortname'
            ],

            'logo_image' => [
                'nullable', 'file', 'image', 'max:4096'
            ],
        ]);

        $logo_image = null;

        if ($request->hasFile('logo_image')) {
            $file = $request->file('logo_image');
            $logo_image = $file->store('logo_images', ['disk' => 'public']);
        }

        $team = Team::factory()->create([
            'name' => $validated['name'],
            'shortname' => $validated['shortname'],
            'image' => $logo_image,
        ]);

        Session::flash('team_created', $team->name);
        return Redirect::route('teams.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('teams.show', [
            'id' => $id,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $team = Team::findOrFail($id);
        $this->authorize('update', $team);
        return view('teams.edit', [
            'team' => $team,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $team = Team::findOrFail($id);
        $validated = $request->validate([
            'name' => [
                'nullable', 'string', 'unique:teams,name'
            ],
            'shortname' => [
                'nullable', 'string', 'max:4', 'unique:teams,shortname'
            ],

            'logo_image' => [
                'nullable', 'file', 'image', 'max:4096'
            ],
        ]);

        $logo_image = $team->image;
        if ($validated['name'] == null) {
            $validated['name'] = $team->name;
        }
        if ($validated['shortname'] == null) {
            $validated['shortname'] = $team->shortname;
        }
        if ($request->hasFile('logo_image')) {
            $file = $request->file('logo_image');
            $logo_image = $file->store('logo_images', ['disk' => 'public']);
        }

        $team->update([
            'name' => $validated['name'],
            'shortname' => $validated['shortname'],
            'image' => $logo_image,
        ]);

        Session::flash('team_updated', $team->name);
        return Redirect::route('teams.show', $team->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
