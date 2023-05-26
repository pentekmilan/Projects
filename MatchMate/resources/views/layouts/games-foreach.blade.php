{{-- Game details layout --}}
@php
    use App\Models\Team;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Redirect;
    $user = Auth::check() ? Auth::user() : null;
    $favourites = getFavourites();
@endphp

<div id="items" style="padding-top: 30px">
    {{-- Type of the game --}}
    @if ($gameType != null)
        <h1 class="text-center" style="margin-top: 30px">{{ $gameType }}</h1>
    @endif

    {{-- Table of the game --}}
    <table class="table table-hover table-striped text-center align-items-center">
        <tr>
            <th>

            </th>
            <th>
            </th>
            <th>
                Home team
            </th>
            <th>
                Short name
            </th>
            <th>
                Start
            </th>
            <th>
                Short name
            </th>
            <th>
                Away team
            </th>
            <th>
            </th>
            <th>
            </th>
            <th>
                Score
            </th>
            @if (!request()->routeIs('games.show'))
                <th>
                    Details
                </th>
            @endif
            @can('admin')
                <th>
                    Edit
                </th>
                <th>
                    Delete
                </th>
            @endcan
        </tr>
        @foreach ($items as $item)
            {{-- Home team details --}}
            <tr>
                <div id="home-team">

                    <td>
                        <x-favourite-button :item="$item" :user="$user" :favourites="$favourites" :id="$item->homeTeam->id" />
                    </td>
                    <td id="homeTeam-logo">
                        <img src="{{ asset($item->homeTeam->image ? 'storage/' . $item->homeTeam->image : 'images/fc_placeholder.png') }}"
                            alt="{{ $item->homeTeam->name }}">
                    </td>
                    <td>
                        {{ $item->homeTeam->name }}
                    </td>
                    <td>
                        {{ $item->homeTeam->shortname }}
                    </td>

                </div>

                {{-- Start time --}}
                <td id="start-time">
                    {{ $item->start }}
                </td>

                {{-- Away team details --}}
                <div id="away-team">
                    <td>
                        {{ $item->awayTeam->shortname }}
                    </td>
                    <td>
                        {{ $item->awayTeam->name }}
                    </td>
                    <td id="awayTeam-logo">
                        <img src="{{ asset($item->awayTeam->image ? 'storage/' . $item->awayTeam->image : 'images/fc_placeholder.png') }}"
                            alt="{{ $item->awayTeam->name }}">
                    </td>
                    <td>
                        <x-favourite-button :item="$item" :user="$user" :favourites="$favourites" :id="$item->awayTeam->id" />
                    </td>
                </div>

                {{-- Score --}}
                <div id="result">
                    <td>
                        @if ($item->start <= date('Y-m-d'))
                            {{ CalculateHomeTeamGoals($item) }}
                            :
                            {{ CalculateAwayTeamGoals($item) }}
                        @endif
                    </td>
                </div>

                {{-- Details --}}
                @if (!request()->routeIs('games.show'))
                    <td>
                        <a href="{{ route('games.show', $item->id) }}">
                            <button type="button" class="btn"><i class="fa-solid fa-circle-info fa-xl"></i></button>
                        </a>
                    </td>
                @endif

                {{-- Edit and delete --}}
                @can('admin')
                    <td>
                        <a href="{{ route('games.edit', $item->id) }}">
                            <button type="button" class="btn "><i class="fa-solid fa-pen-to-square fa-xl"></i></button>
                        </a>
                    </td>
                    <td>
                        <form id="game-destroy" action="{{ route('games.destroy', $item->id) }}" method="POST">
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
