@php
    use App\Models\Game;
    use App\Models\Team;
    use App\Models\Event;
    use App\Models\Player;
    use App\Models\User;
    use Illuminate\Support\Facades\Auth;
    $user = Auth::check() ? Auth::user() : null;
    $favourites = getFavourites();
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Custom title -->
    <title>
        @if (View::hasSection('title'))
            @yield('title') |
        @endif
        {{ config('app.name', 'Laravel') }}
    </title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }} ">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

    <!-- Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        <nav class="navbar fixed-top navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                {{-- Left Side Of Navbar --}}
                <a class="navbar-brand" href="{{ url('/') }}">
                    MatchMate
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                {{-- Games --}}
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav me-auto">
                        @if (request()->routeIs('games.index'))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    href="{{ route('games.index') }}">Games</a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    @if ($ongoingGames->isNotEmpty())
                                        <li>
                                            <a class="dropdown-item" href="#ongoingGames">Ongoing Games</a>
                                        </li>
                                    @endif
                                    @if ($upcomingGames->isNotEmpty())
                                        <li>
                                            <a class="dropdown-item" href="#upcomingGames">Upcoming Games</a>
                                        </li>
                                    @endif
                                    @if ($finishedGames->isNotEmpty())
                                        <li>
                                            <a class="dropdown-item" href="#finishedGames">Finished Games</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('games.index') }}">Games</a>
                            </li>
                        @endif
                        {{-- Teams --}}
                        <li class="nav-item">
                            <a class="nav-link" href={{ route('teams.index') }}>Teams</a>
                        </li>
                        @if (request()->routeIs('teams.show'))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"s
                                    href="../teams">{{ $team->name }}</a>

                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    @if ($ongoingGames->isNotEmpty())
                                        <li>
                                            <a class="dropdown-item" href="#ongoingGames">Ongoing Games</a>
                                        </li>
                                    @endif
                                    @if ($upcomingGames->isNotEmpty())
                                        <li>
                                            <a class="dropdown-item" href="#upcomingGames">Upcoming Games</a>
                                        </li>
                                    @endif
                                    @if ($finishedGames->isNotEmpty())
                                        <li>
                                            <a class="dropdown-item" href="#finishedGames">Finished Games</a>
                                        </li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="#statistics">Players and statistics</a>
                                    </li>
                                </ul>
                        @endif
                        {{-- Standings --}}
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('standings.index') }}">Standings</a>
                        </li>
                        {{-- Favourites --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('favourites.index') }}">Favourites</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Log in</a>
                            </li>
                            @endif @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                </ul>

            </div>
        </div>
    </nav>

    {{-- Main content --}}
    <main>
        @yield('content')
    </main>
    {{-- Footer --}}
    <footer class="mb-4">
        <div class="container">
            <hr>
            <div class="d-flex flex-column align-items-center">
                <div>
                    <span class="small">MatchMate</span>
                    <span class="mx-1">·</span>
                    <span class="small">Laravel {{ app()->version() }}</span>
                    <span class="mx-1">·</span>
                    <span class="small">PHP {{ phpversion() }}</span>
                </div>

                <div>
                    Made by : Péntek Milán
                </div>
            </div>
        </div>
    </footer>

    @yield('scripts')
</div>
</body>

</html>
