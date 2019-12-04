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

                        <span class="ml-4" style="padding-left: 6px">
                        <button style="cursor: default;" type="button" class="btn btn-outline-dark col-5" disabled>Spellen van anderen - ontsleuteld</button>
                    </span>
                        <span class="pl-5">
                        <button style="cursor: default;" type="button" class="btn btn-outline-dark col-5" disabled>
                            Mijn spellen
                        </button>
                    </span>

                        <ul>
                            Mijn spellen
                            @foreach($adminGames as $game)
                                <li>
                                    <a href="{{ route('game', ['id' => $game->id]) }}">
                                        {{$game->name}}
                                    </a>
                                </li>
                                Admin: {{$game->pivot->admin == 1 ? 'U bent admin' : 'U bent gebruiker'}} <br>
                                Punten: {{$game->pivot->point}}
                            @endforeach
                        </ul>

                        <ul>
                            Spellen anderen
                            @foreach($invitedGames as $game)
                                <li>
                                    <a href="{{ route('game', ['id' => $game->id]) }}">
                                        {{$game->name}}
                                    </a>
                                </li>

                                Admin: {{$game->pivot->admin == 1 ? 'U bent admin' : 'U bent gebruiker'}} <br>
                                Punten: {{$game->pivot->point}}
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
