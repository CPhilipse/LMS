@extends('layouts.app')
{{-- Overzichtspagina --}}
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

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
                            @foreach($adminGames as $game)
                                <hr>
                                <div class="p-1 mt-3" style="text-align: center;">
                                    <a href="{{ route('game', ['id' => $game->id]) }}">
                                        {{$game->name}}
                                    </a>
                                </div>
                            @endforeach

                            <hr>

                            <button style="cursor: default;" type="button" class="btn btn-outline-dark col-12" disabled>Spellen van anderen - ontsleuteld</button>
                            @foreach($invitedGames as $game)
                                <hr>
                                <div class="p-1 mt-3" style="text-align: center;">
                                    <a href="{{ route('game', ['id' => $game->id]) }}">
                                        {{$game->name}}
                                    </a>
                                </div>
                            @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
