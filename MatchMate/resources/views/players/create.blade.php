@php
    use App\Models\Event;
    use App\Models\Game;
    use App\Models\Player;
    use App\Models\Team;

    $teams = Team::all()->sortBy('name');
@endphp

@extends('layouts.app')
@section('title', 'Add new player')

@section('content')
    <div class="container">
        <h1 class="text-center">Add new player</h1>
        <div class="mb-4">
            <a href="{{ route('teams.show', $team->id) }}">
                <button type="button" class="btn btn-dark">Back</button>
            </a>
        </div>

        {{-- Add new player form --}}
        <form action="{{ route('players.store') }}" method="POST">
            @csrf
            <input type="hidden" name="team_id" value="{{ $team->id }}">

            {{-- Name --}}
            <div class="form-group row mb-3">
                <label for="name" class="col-sm-2 col-form-label">Name*</label>
                <div class="col-sm-10">
                    <input type="name" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" value="{{ old('name') }}">
                    @error('name')
                        <p class="text-danger">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Number --}}
            <div class="form-group row mb-3">
                <label for="number" class="col-sm-2 col-form-label">Field Number*</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control @error('number') is-invalid @enderror" id="number"
                        name="number" value="{{ old('number') }}">
                    @error('number')
                        <p class="text-danger">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Date of Birth --}}
            <div class="form-group row mb-3">
                <label for="birthdate" class="col-sm-2 col-form-label">Date of Birth:*</label>
                <div class="col-sm-10">
                    <input type="date" class="form-control @error('birthdate') is-invalid @enderror" id="birthdate"
                        name="birthdate" value="{{ old('birthdate') }}">
                    @error('birthdate')
                        <p class="text-danger">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
    </div>



    <div class="text-center">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Add</button>
    </div>

    </form>
    </div>
@endsection
