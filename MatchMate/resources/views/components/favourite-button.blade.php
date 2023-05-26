@if ($user != null && $favourites != null && CheckIfFavourite($id))
    <form id="favourite-destroy" action="{{ route('favourites.destroy', $id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn ">
            <i class="fa-solid fa-star fa-lg"></i>
        </button>
    </form>
@else
    <form action="{{ route('favourites.store') }}" method="POST">
        @csrf
        @if ($user != null)
            <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
        @endif
        <input type="hidden" name="team_id" id="team_id" value="{{ $id }}">
        <button type="submit" class="btn ">
            <i class="fa-regular fa-star fa-lg"></i>
        </button>
    </form>
@endif
