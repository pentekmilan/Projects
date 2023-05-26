@php
    use App\Models\Team;
    $user = Auth::check() ? Auth::user() : null;
    $favourites = getFavourites();
@endphp
@extends('layouts.app')
@section('title', 'Standings')
@section('content')
    @php
        $teams = App\Models\Team::all()->sortBy('name');
        $games = App\Models\Game::all();
        $standings = SortStandings(CalculateTeamStandings($teams, $games));
    @endphp

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">

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

                {{-- Standings table --}}
                <table class="table table-striped table-hover text-center fs-5">
                    <tr class="table-dark">
                        <th>
                        </th>
                        <th>
                            Team
                        </th>
                        <th>
                            Points
                        </th>
                        <th>
                            Goal difference
                        </th>
                        <th>
                            Details
                        </th>
                    </tr>
                    @foreach ($standings as $standing)
                        <tr>
                            <td>
                                <x-favourite-button :id="$standing['team_id']" :favourites="$favourites" :user="$user" />
                            </td>
                            <td id="team-name">
                                {{ $standing['team_name'] }}
                            </td>
                            <td id="team-points">
                                {{ $standing['points'] }}
                            </td>
                            <td id="team-Goal-difference">
                                {{ $standing['Goal_difference'] }}
                            </td>
                            <td>
                                <a href="{{ route('teams.show', $standing['team_id']) }}">
                                    <button type="button" class="btn"><i
                                            class="fa-solid fa-circle-info fa-xl"></i></button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
