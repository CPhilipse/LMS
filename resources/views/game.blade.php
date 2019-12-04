@extends('layouts.app')
{{-- Overzichtspagina --}}
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="container" style="background-color: black;">
                    <div class="jumbotron" style="background-color: black;">
                        <h1 style="text-align: center; color: white; font-weight: bold;">{{$game->name}}</h1>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">

                        @if(isset($delete))
                            <div class="alert alert-success" role="alert">
                                <h4 class="alert-heading">Gelukt!</h4>
                                <p>Verwijderen van de gebruiker is succesvol.</p>
                            </div>
                        @endif

                        De spelers:
                        <ul>
                            @foreach($allPlayers as $player)
                                    @if($user_id == $player->pivot->user_id)
                                        @if($player->pivot->admin == 1)
                                            @foreach($allPlayers as $player)
                                                <li style="border: 1px solid black; height: 75px; list-style-type: none">
                                                    <span style="float: left; padding-left: 25px; padding-top: 25px">{{$player->name}}</span>
                                                    <a href="{{route('deleteUser', ['id' => $player->id])}}" style="float: right;padding-right: 25px; padding-top: 25px">X</a>
                                                </li>
                                            @endforeach
                                        @else
                                            @foreach($allPlayers as $player)
                                                <li style="border: 1px solid black; height: 75px; list-style-type: none">
                                                    <span style="float: left; padding-left: 25px; padding-top: 25px">{{$player->name}}</span>
                                                </li>
                                            @endforeach
                                        @endif
                                    @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
