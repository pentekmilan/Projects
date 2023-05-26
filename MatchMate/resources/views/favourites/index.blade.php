@php
    use Illuminate\Support\Facades\Redirect;
    use App\Models\Team;
    use App\Models\Game;
@endphp
@extends('layouts.app')
@section('title', 'Kedvencek')
{{-- Check if user logged in --}}
@auth
    @php
        $user = Auth::check() ? Auth::user() : null;
        $favourites = getFavourites();
        $games = Game::all();
        $games = $games
            ->filter(function ($game) use ($favourites) {
                return $favourites->contains('id', $game->home_team_id) || $favourites->contains('id', $game->away_team_id);
            })
            ->sortBy('start');
    @endphp
@endauth


@section('content')

    {{-- List all favourites --}}
    @if (Auth::check())
        <div class="container">
            <div class="row justify-content-center">
                {{-- Session flashes --}}
                @if (Session::has('favourite_added'))
                    <div class="alert alert-success" role="alert">
                        The team has been added to your favourites!
                    </div>
                @endif

                @if (Session::has('favourite_deleted'))
                    <div class="alert alert-success" role="alert">
                        The team has been removed from your favourites!
                    </div>
                @endif

                {{-- Favourites table --}}
                @include('layouts.games-foreach', ['items' => $games, 'gameType' => null])
            </div>
        </div>

        {{-- Log in required --}}
    @else
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-11">
                    <div class="text-center">
                        <h1 class="text-center">You must be logged in to see your favourites!</h1>
                        <a class="nav-link" href="{{ route('login') }}">
                            <button type="button" class="btn btn-primary fs-5 mt-2">Log in</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
