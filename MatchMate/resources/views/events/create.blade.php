@php
    use App\Models\Event;
    use App\Models\Game;
    use App\Models\Player;
@endphp

@extends('layouts.app')
@section('title', 'Add event')

@section('content')
    <div class="container">
        <h1 class="text-center">Add new event</h1>
        <div class="mb-4">
            <a href="{{ route('games.show', $game->id) }}">
                <button type="button" class="btn btn-dark">Back</button>
            </a>
        </div>

        {{-- Add Event --}}
        <form action="{{ route('events.store') }}" method="POST">
            @csrf
            <input type="hidden" name="game_id" value="{{ $game->id }}">
            <div class="form-group row mb-3">
                <label for="minute" class="col-sm-2 col-form-label">In what minute did it happen:*</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control @error('minute') is-invalid @enderror" id="minute"
                        name="minute" value="{{ old('minute') }}">
                    @error('minute')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="type" class="col-sm-2 col-form-label py-0">Event type*</label>
                <div class="col-sm-10">
                    @foreach (['Goal', 'Own goal', 'Yellow card', 'Red card'] as $type)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="event-{{ $loop->index }}"
                                value="{{ $type }}" @checked(old('type') == $type)>
                            <label class="form-check-label" for="{{ $type }}">
                                <span class="">
                                    {{ $type }}
                                </span>
                            </label>
                        </div>
                    @endforeach
                    @error('type')
                        <p class="text-danger">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="player_id" class="col-sm-2 col-form-label py-0">The player concerned*</label>
                <div class="col-sm-10">
                    @php
                        $playersFromHomeTeam = $game->homeTeam->players->sortBy('number');
                        $playersFromAwayTeam = $game->awayTeam->players->sortBy('number');
                    @endphp
                    <h3>{{ $game->homeTeam->name }}</h3>
                    @foreach ($playersFromHomeTeam as $player)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="player_id" id="{{ $player->id }}"
                                value="{{ $player->id }}" @checked(old('player_id') == $player->id)>
                            <label class="form-check-label" for="{{ $player->id }}">
                                <span class="">
                                    {{ $player->number }} {{ $player->name }}
                                </span>
                            </label>
                        </div>
                    @endforeach
                    <h3>{{ $game->awayTeam->name }}</h3>
                    @foreach ($playersFromAwayTeam as $player)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="player_id" id="{{ $player->id }}"
                                value="{{ $player->id }}" @checked(old('player_id') == $player->id)>
                            <label class="form-check-label" for="{{ $player->id }}">
                                <span class="">
                                    {{ $player->number }} {{ $player->name }}
                                </span>
                            </label>
                        </div>
                    @endforeach
                    @error('player_id')
                        <p class="text-danger">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Store</button>
            </div>

        </form>
    </div>
@endsection
