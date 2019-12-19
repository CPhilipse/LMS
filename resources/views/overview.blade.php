@extends('layouts.app')
{{-- Overzichtspagina --}}
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                {{-- Header --}}
                <div class="container" style="background-color: black;">
                    <div class="jumbotron" style="background-color: black;">
                        <h1 style="text-align: center; color: white; font-weight: bold;">Overzicht</h1>
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

                        <button style="cursor: default;" type="button" class="btn btn-outline-dark col-12" disabled>
                            Mijn spellen
                        </button>
                            {{-- Show all the games made by the user --}}
                            @foreach($adminGames as $game)
                                <hr>
                                {{-- Onclick of the game, give the id to the route so the controller can redirect the user to this game. --}}
                                <a style="color: black; font-size: 15px;" href="{{ route('game', ['id' => $game->id]) }}">
                                    <div class="p-1 mt-3" style="text-align: center;">
                                        {{-- Show the name of the game --}}
                                        {{$game->name}}
                                    </div>
                                </a>
                            @endforeach

                            <hr>

                            <button style="cursor: default;" type="button" class="btn btn-outline-dark col-12" disabled>Spellen van anderen - ontsleuteld</button>
                            {{-- Show all the games the user is invited by --}}
                            @foreach($invitedGames as $game)
                                <hr>
                                {{-- Onclick of the game, give the id to the route so the controller can redirect the user to this game. --}}
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
