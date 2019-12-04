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

                    <span class="ml-4" style="padding-left: 6px">
                        <button type="button" class="btn btn-outline-dark col-5" onclick="location.href = '{{route('showGame')}}';">Nieuw spel aanmaken</button>
                    </span>
                    <span class="pl-5">
                        <button type="button" class="btn btn-outline-dark col-5" onclick="location.href = '{{route('overview')}}';">
                            Mijn spellen
                        </button>
                    </span>

                    @foreach($games as $game)
                            <hr>
                        <div class="p-1 mt-3" style="text-align: center;">
                            <a href="{{ route('game', ['id' => $game->id]) }}">{{$game->name}}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
