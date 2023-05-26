@extends('layouts.app')
@section('title', 'Team details')
@section('content')
    @php
        use App\Models\Team;
        $team = App\Models\Team::find($id);
        $games = App\Models\Game::where('home_team_id', $id)
            ->orWhere('away_team_id', $id)
            ->get()
            ->sortBy('start');
        $ongoingGames = getOngoingGames($games);
        $upcomingGames = getUpcomingGames($games);
        $finishedGames = getFinishedGames($games);
        $user = Auth::check() ? Auth::user() : null;
        $favourites = getFavourites();
    @endphp
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">

                {{-- Session flashes --}}
                @if (Session::has('player_deleted'))
                    <div class="alert alert-success" role="alert">
                        Player deleted successfully!
                    </div>
                @endif

                @if (Session::has('player_not_deleted'))
                    <div class="alert alert-danger" role="alert">
                        Player has not been deleted, because he has events!
                    </div>
                @endif
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

                @if (Session::has('player_created'))
                    <div class="alert alert-success" role="alert">
                        {{ Session::get('player_created') }} has been added!
                    </div>
                @endif

                @if (Session::has('team_updated'))
                    <div class="alert alert-success" role="alert">
                        {{ $team->name }} has been updated!
                    </div>
                @endif

                <a href="{{ route('teams.index') }}" class="badge badge-dark text-decoration-none fs-5 mb-5">Back!</a>
                @can('admin')
                    <div class="text-center">
                        <a class="nav-link mt-2" href="{{ route('teams.edit', $team->id) }}">
                            <button type="button" class="btn btn-dark">Edit Team <i
                                    class="fa-solid fa-pen-to-square fa-xs"></i></button>
                        </a>
                    </div>


                    <div class="text-center">
                        <a class="nav-link mt-2" href="{{ route('players.create', $team->id) }}">
                            <button type="button" class="btn btn-success mt-2">Add new Player<i
                                    class="fa-solid fa-plus fa-xs"></i></button>
                        </a>
                    </div>
                @endcan

                {{-- Team details --}}
                @include('layouts.team-show', [
                    'teams' => [$team],
                    'favourites' => $favourites,
                    'user' => $user,
                ])

                {{-- On going games --}}
                <div id="ongoingGames">
                    @includeWhen($ongoingGames->isNotEmpty(), 'layouts.games-foreach', [
                        'items' => $ongoingGames,
                        'gameType' => 'ONGOING',
                    ])
                </div>

                {{-- Upcoming games --}}
                <div id="upcomingGames">
                    @includeWhen($upcomingGames->isNotEmpty(), 'layouts.games-foreach', [
                        'items' => $upcomingGames,
                        'gameType' => 'UPCOMING',
                    ])
                </div>

                {{-- Finished games --}}
                <div id="finishedGames">
                    @includeWhen($finishedGames->isNotEmpty(), 'layouts.games-foreach', [
                        'items' => $finishedGames,
                        'gameType' => 'FINISHED',
                    ])
                </div>

                {{-- Statistics --}}
                <div id="statistics" style="padding-top: 80px">
                    <h1 class="text-center">Players and statistics</h1>

                    <table class="table table-hover table-striped text-center align-items-center">
                        <tr>
                            <th>
                                Name
                            </th>
                            <th>
                                Date of birth
                            </th>
                            <th>
                                Goals
                            </th>
                            <th>
                                Own goals
                            </th>
                            <th>
                                Yellow cards
                            </th>
                            <th>
                                Red cards
                            </th>
                            @can('admin')
                                <th>
                                    Delete player
                                </th>
                            @endcan
                        </tr>
                        @foreach ($team->players as $player)
                            <tr>
                                <td>
                                    {{ $player->name }}
                                </td>
                                <td>
                                    {{ $player->birthdate }}
                                </td>
                                <td>
                                    {{ $player->events->where('type', 'Goal')->count() }}
                                </td>
                                <td>
                                    {{ $player->events->where('type', 'Own goal')->count() }}
                                </td>
                                <td>
                                    {{ $player->events->where('type', 'Yellow card')->count() }}
                                </td>
                                <td>
                                    {{ $player->events->where('type', 'Red card')->count() }}
                                </td>
                                @can('admin')
                                    <td>
                                        <form id="game-destroy" action="{{ route('players.destroy', $player->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn ">
                                                <i class="fa-solid fa-trash fa-xl"></i>
                                            </button>
                                        </form>
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
