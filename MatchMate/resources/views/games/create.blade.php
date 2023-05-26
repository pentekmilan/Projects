@php
    use App\Models\Event;
    use App\Models\Game;
    use App\Models\Player;
    use App\Models\Team;
    
    $teams = Team::all()->sortBy('name');
@endphp

@extends('layouts.app')
@section('title', 'Create new game')

{{-- Create new game --}}
@section('content')
    <div class="container">
        <h1 class="text-center">Create new game</h1>
        <div class="mb-4">
            <a href="{{ route('games.index') }}">
                <button type="button" class="btn btn-dark">Back</button>
            </a>
        </div>

        {{-- Create new game form --}}
        <form action="{{ route('games.store') }}" method="POST">
            @csrf
            <div class="form-group row mb-3">
                <label for="start" class="col-sm-2 col-form-label">When does the game start: (MM-DD)*</label>
                <div class="col-sm-10">
                    <input type="date" class="form-control @error('minute') is-invalid @enderror" id="start"
                        name="start" value="{{ old('start') }}">
                    @error('start')
                        <p class="text-danger">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="homeTeam_id" class="col-sm-2 col-form-label py-0">Home team*</label>
                <div class="col-sm-10">
                    @foreach ($teams as $team)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="homeTeam_id"
                                id="homeTeam-{{ $team->id }}" value="{{ $team->id }}" @checked(old('homeTeam_id') == $team->id)
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
                <label for="awayTeam_id" class="col-sm-2 col-form-label py-0">Away team*</label>
                <div class="col-sm-10">
                    @foreach ($teams as $team)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="awayTeam_id"
                                id="awayTeam-{{ $team->id }}" value="{{ $team->id }}" @checked(old('awayTeam_id') == $team->id)>
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
