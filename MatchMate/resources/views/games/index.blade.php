@php
    use App\Models\Game;
    use App\Models\Team;
    use App\Models\Event;
    use App\Models\Player;
    use App\Models\User;
    use Illuminate\Support\Facades\Auth;
@endphp


@extends('layouts.app');
@section('title', 'Games');
@section('content');

    {{-- Sort by date --}}
    @php
        $ongoingGames = getOngoingGames(Game::all());
        $favourites = getFavourites();
        $upcomingGames = getUpcomingGames($games);
        $finishedGames = getFinishedGames($games);
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

                @if (Session::has('game_deleted'))
                    <div class="alert alert-success" role="alert">
                        The game has been deleted!
                    </div>
                @endif

                @if (Session::has('game_not_deleted'))
                    <div class="alert alert-warning" role="alert">
                        The game has not been deleted, because it has already started!
                    </div>
                @endif

                @if (Session::has('game_created'))
                    <div class="alert alert-success" role="alert">
                        Game has been created successfully!
                    </div>
                @endif

                {{-- Create new game --}}
                @can('admin')
                    <div class="text-center">
                        <a class="nav-link" href="{{ route('games.create') }}">
                            <button type="button" class="btn btn-success fs-5 mt-2">Create new game <i
                                    class="fa-solid fa-plus fa-xs"></i></button>
                        </a>
                    </div>
                @endcan

                {{-- How may games are shown per page --}}
                <form action="{{ route('games.index') }}" method="get" class="form-inline">
                    <label for="per_page" class="my-1 mr-2">Show:</label>
                    <select name="per_page" id="per_page" onchange="this.form.submit()" class="custom-select my-1 mr-sm-2">
                        <option value="10" {{ $games->perPage() == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ $games->perPage() == 15 ? 'selected' : '' }}>15</option>
                        <option value="20" {{ $games->perPage() == 20 ? 'selected' : '' }}>20</option>
                    </select>
                    <input type="hidden" name="page" value="{{ $games->currentPage() }}">
                </form>

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

                {{-- Pagination --}}
                <div class="pagination justify-content-center" id="paginate">
                    {{ $games->appends(['per_page' => $games->perPage(), 'page' => $games->currentPage()])->links() }}</div>
            </div>
        </div>
    </div>
@endsection
