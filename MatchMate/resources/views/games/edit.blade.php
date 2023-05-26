@php
    use App\Models\Event;
    use App\Models\Game;
    use App\Models\Player;
    use App\Models\Team;

    $teams = Team::all()->sortBy('name');
    $user = Auth::user();
    $game = Game::find($id);
@endphp

@extends('layouts.app')
@section('title', 'Edit game')

{{-- Edit game --}}
@section('content')
    <div class="container">
        <h1 class="text-center">Edit game</h1>
        <div class="mb-4">
            <a href="{{ route('games.index') }}">
                <button type="button" class="btn btn-dark">Back</button>
            </a>
        </div>

       

        {{-- Edit game form --}}
        <form action="{{ route('games.update', $id) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="form-group row mb-3">
                <label for="start" class="col-sm-2 col-form-label">When does the game start: (MM-DD)</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control @error('minute') is-invalid @enderror" id="start"
                        name="start" value="{{ old('start') }}" placeholder={{ $game->start }}
                        onfocus="(this.type='date')">
                    @error('start')
                        <p class="text-danger">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="homeTeam_id" class="col-sm-2 col-form-label py-0">Home team</label>
                <div class="col-sm-10">
                    @foreach ($teams as $team)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="homeTeam_id"
                                id="homeTeam-{{ $team->id }}" value="{{ $team->id }}" @checked(old('homeTeam_id') == $team->id || $game->home_team_id == $team->id)
                                <label class="form-check-label" for="{{ $team->id }}">
                            <span class="">
                                {{ $team->name }}
                            </span>
                            </label>
                        </div>
                    @endforeach
                    @error('homeTeam_id')
                        <p class="text-danger">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="awayTeam_id" class="col-sm-2 col-form-label py-0">Away team</label>
                <div class="col-sm-10">
                    @foreach ($teams as $team)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="awayTeam_id"
                                id="awayTeam-{{ $team->id }}" value="{{ $team->id }}"
                                @checked(old('awayTeam_id') == $team->id || $game->away_team_id == $team->id)>
                            <label class="form-check-label" for="{{ $team->id }}">
                                <span class="">
                                    {{ $team->name }}
                                </span>
                            </label>
                        </div>
                    @endforeach
                    @error('awayTeam_id')
                        <p class="text-danger">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-dark fs-5"><i class="fas fa-save"></i> Store</button>
            </div>
        </form>
    </div>
@endsection
