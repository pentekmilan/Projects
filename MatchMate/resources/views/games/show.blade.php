@extends('layouts.app')
@section('title', 'Details')
@section('content')

    @php
        $games = App\Models\Game::all();
        $game = $games->find($id);
    @endphp

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">

                {{-- Session flashes --}}
                @if (Session::has('game_finished'))
                    <div class="alert alert-success" role="alert">
                        The game has been finished!
                    </div>
                @endif
                @if (Session::has('game_not_today_event_create'))
                    <div class="alert alert-danger" role="alert">
                        The event cannot be created because the game is not today!
                    </div>
                @endif
                @if (Session::has('game_not_today_event_delete'))
                    <div class="alert alert-danger" role="alert">
                        The event cannot be deleted because the game is not today!
                    </div>
                @endif
                @if (Session::has('game_not_today_finish'))
                    <div class="alert alert-danger" role="alert">
                        The game cannot be finished because the game is not today!
                    </div>
                @endif
                @if (Session::has('team_added'))
                    <div class="alert alert-success" role="alert">
                        The team has been added to favourites!
                    </div>
                @endif

                @if (Session::has('favourite_deleted'))
                    <div class="alert alert-success" role="alert">
                        The team has been deleted from favourites!
                    </div>
                @endif

                @if (Session::has('event_deleted'))
                    <div class="alert alert-success" role="alert">
                        The event has been deleted!
                    </div>
                @endif

                @if (Session::has('game_updated'))
                    <div class="alert alert-success" role="alert">
                        Game has been updated successfully!
                    </div>
                @endif

                @if (Session::has('event_created'))
                    <div class="alert alert-success" role="alert">
                        The event has been created!
                    </div>
                @endif

                {{-- Game details --}}
                <a href="{{ route('games.index') }}" class="badge badge-dark text-decoration-none fs-5 mb-5">Back</a>
                @include('layouts.games-foreach', ['items' => [$game], 'gameType' => null])

                {{-- Game events --}}
                @php
                    $events = $game->events->sortBy('minute');
                @endphp
                <table class="table table-striped text-center">
                    <tr>
                        <th>
                            Time
                        </th>
                        <th>
                            Team
                        </th>
                        <th>
                            Player
                        </th>
                        <th>
                            Event
                        </th>
                        @can('admin')
                            <th>
                                Delete
                            </th>
                        @endcan
                    </tr>
                    @foreach ($events as $event)
                        <tr>
                            <td>
                                {{ $event->minute }}'
                            </td>
                            <td>
                                @if ($game->homeTeam->players->contains('id', $event->player_id))
                                    {{ $game->homeTeam->name }}
                                @else
                                    {{ $game->awayTeam->name }}
                                @endif
                            </td>
                            <td>
                                {{ $event->player->name }}
                            </td>
                            <td>
                                {{ $event->type }}
                            </td>
                            @can('admin')
                                <td>
                                    <form id="event-destroy" action="{{ route('events.destroy', $event->id) }}" method="POST">
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
                @can('admin')
                    @if (request()->routeIs('games.show') && $game->isfinished == 0 && $game->start <= now())
                        @if ($game->start == date('Y-m-d') && $game->finished == false)
                            <div class="text-center">
                                <a class="nav-link" href="{{ route('events.create', $game->id) }}">
                                    <button type="button" class="btn btn-success mt-2">Create new event
                                        <i class="fa-solid fa-plus fa-xs"></i></button>
                                </a>
                            </div>
                        @endif
                        <div class="text-center">
                            <a class="nav-link mt-2" href="{{ route('games.edit', $game->id) }}">
                                <button type="button" class="btn btn-dark">Edit game
                                    <i class="fa-solid fa-pen-to-square fa-xs"></i></button>
                            </a>
                        </div>
                        @if ($game->finished == false)
                            <div class="text-center mt-2">
                                <form id="game-finish" action="{{ route('games.finish', $game->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-danger">Finish game
                                        <i class="fa-solid fa-lock fa-xs"></i></button>
                                </form>
                            </div>
                        @endif
                    @endif
                @endcan
            </div>
        </div>
    </div>
@endsection
