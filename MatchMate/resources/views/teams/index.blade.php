@php
    use App\Models\Team;
    $user = Auth::check() ? Auth::user() : null;
    $favourites = getFavourites();
@endphp
@extends('layouts.app')
@section('title', 'Teams')

@section('content')
    @php
        $teams = App\Models\Team::all()->sortBy('name');
    @endphp
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                {{-- Create new team --}}
                @can('admin')
                    <div class="text-center">
                        <a class="nav-link" href="{{ route('teams.create') }}">
                            <button type="button" class="btn btn-dark fs-5 mt-2">Create new team
                                <i class="fa-solid fa-plus fa-xs"></i></button>
                        </a>
                    </div>
                @endcan

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

                @if (Session::has('team_created'))
                    <div class="alert alert-success" role="alert">
                        {{ Session::get('team_created') }} team has been created!
                    </div>
                @endif

              

                {{-- Teams table --}}
                @include('layouts.team-show', [
                    'teams' => $teams,
                    'favourites' => $favourites,
                    'user' => $user,
                ])
            </div>
        </div>
    </div>
@endsection
