@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">



            <div class="container" style="background-color: black;">
                <div class="jumbotron" style="background-color: black;">
                    <h1 style="text-align: center; color: white; font-weight: bold;">Home</h1>
                </div>
            </div>

            <div class="card">
                <div class="card-body">

                    @if(isset($success))
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">Gelukt!</h4>
                            <p>{{$success}}</p>
                        </div>
                    @endif

                        @if(isset($delete))
                            <div class="alert alert-success" role="alert">
                                <h4 class="alert-heading">Gelukt!</h4>
                                <p>{{$delete}}</p>
                            </div>
                        @endif

                    <span class="ml-4" style="padding-left: 6px">
                        {{-- Button redirect to create game page --}}
                        <button type="button" class="btn btn-outline-dark col-5" onclick="location.href = '{{route('showGame')}}';">
                            Nieuw spel aanmaken
                        </button>
                    </span>
                    <span class="pl-5">
                        {{-- Button redirect to overview route --}}
                        <button type="button" class="btn btn-outline-dark col-5" onclick="location.href = '{{route('overview')}}';">
                            Mijn spellen
                        </button>
                    </span>

                    {{-- Show all games in reverse so that the new made games go on top --}}
                    @foreach($games->reverse() as $game)
                            <hr>
                            {{-- Give id to route so the controller will know which game the user needs to be redirected to --}}
                            <a style="color: black; font-size: 15px;" href="{{ route('game', ['id' => $game->id]) }}">
                                <div class="p-1 mt-3" style="text-align: center;">
                                    {{-- Show the name of the game --}}
                                    {{$game->name}}
                                </div>
                            </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
