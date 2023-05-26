@php
    use App\Models\Event;
    use App\Models\Game;
    use App\Models\Player;
    use App\Models\Team;
    
    $teams = Team::all()->sortBy('name');
@endphp

@extends('layouts.app')
@section('title', 'Csapat módosítása')

@section('content')
    <div class="container">
        <h1 class="text-center">Edit team</h1>
        <div class="mb-4">
            <a href="{{ route('teams.index') }}">
                <button type="button" class="btn btn-dark">Back</button>
            </a>
        </div>


        {{-- Form --}}
        <form action="{{ route('teams.update', $team->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            {{-- Name --}}
            <div class="form-group row mb-3">
                <label for="name" class="col-sm-2 col-form-label">Name:</label>
                <div class="col-sm-10">
                    <input type="name" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" value="{{ old('name') }}" placeholder="{{ $team->name }}">
                    @error('name')
                        <p class="text-danger">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Short name --}}
            <div class="form-group row mb-3">
                <label for="shortname" class="col-sm-2 col-form-label">Short name:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control @error('shortname') is-invalid @enderror" id="shortName"
                        name="shortname" value="{{ old('shortname') }}" placeholder="{{ $team->shortname }}">
                    @error('shortname')
                        <p class="text-danger">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Logo --}}
            <div class="form-group row mb-3">
                <label for="logo_image" class="col-sm-2 col-form-label">Team Logo</label>
                <div class="col-sm-10">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <input type="file" class="form-control-file" id="logo_image" name="logo_image">
                            </div>
                            <div id="logo_preview" class="col-12 d-none">
                                <p>Logo preview:</p>
                                <img width="350px" id="logo_preview_image" src="#" alt="Logo preview">
                            </div>
                        </div>
                    </div>
                </div>
                @error('logo_image')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror
            </div>



            <div class="text-center">
                <button type="submit" class="btn btn-dark"><i class="fas fa-save"></i> Store</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        const logoImageInput = document.querySelector('input#logo_image');
        const logoPreviewContainer = document.querySelector('#logo_preview');
        const logoPreviewImage = document.querySelector('img#logo_preview_image');
        logoImageInput.onchange = event => {
            const [file] = logoImageInput.files;
            if (file) {
                logoPreviewContainer.classList.remove('d-none');
                logoPreviewImage.src = URL.createObjectURL(file);
            } else {
                logoPreviewContainer.classList.add('d-none');
            }
        }
        //if team has logo image show preview
        if ('{{ $team->image }}') {
            logoPreviewContainer.classList.remove('d-none');
            logoPreviewImage.src = '{{ asset('storage/' . $team->image) }}';
        }
    </script>
@endsection
