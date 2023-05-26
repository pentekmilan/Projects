{{-- Team details layout --}}
<div class="col-md-13">
    <table class="table table-striped table-hover text-center mt-5">
        <tr>
            <th>
            </th>
            <th>
                Team logo
            </th>
            <th>
                Team name
            </th>
            <th>
                Short name
            </th>
            @if (!request()->routeIs('teams.show'))
                <th>
                    Details
                </th>
            @endif
        </tr>

        @foreach ($teams as $team)
            <tr>

                <td>
                    <x-favourite-button :user="$user" :favourites="$favourites" :id="$team->id" />
                </td>
                <td id="team-logo">
                    <img src="{{ asset($team->image ? 'storage/' . $team->image : 'images/fc_placeholder.png') }}"
                        alt="{{ $team->name }}">
                </td>
                <td id="team-name">
                    {{ $team->name }}
                </td>
                <td id="team-shortname">
                    {{ $team->shortname }}
                </td>
                @if (!request()->routeIs('teams.show'))
                    <td class="">
                        <a href="{{ route('teams.show', $team->id) }}">
                            <button type="button" class="btn fs-5"><i
                                    class="fa-solid fa-circle-info fa-xl"></i></button>
                        </a>
                    </td>
                @endif
            </tr>
        @endforeach
    </table>
</div>
