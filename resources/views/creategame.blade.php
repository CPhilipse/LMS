@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="container" style="background-color: black;">
                    <div class="jumbotron" style="background-color: black;">
                        <h1 style="text-align: center; color: white; font-weight: bold;">Nieuw spel aanmaken</h1>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">

                        @if(isset($user))
                            <div class="alert alert-success" role="alert">
                                <h4 class="alert-heading">Gelukt!</h4>
                                <p>{{$user}} is toegevoegd.</p>
                            </div>
                        @endif

                        @if(isset($nope))
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading">Mislukt!</h4>
                                <p>{{$nope}}</p>
                            </div>
                        @endif

                        @if(isset($self))
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading">Mislukt!</h4>
                                <p>{{$self}}</p>
                            </div>
                        @endif

                        @if(isset($alreadyInvited))
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading">Mislukt!</h4>
                                <p>{{$alreadyInvited}}</p>
                            </div>
                        @endif

                        @if(isset($emptyName))
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading">Mislukt!</h4>
                                <p>{{$emptyName}}</p>
                            </div>
                        @endif

                        <form name="form" action="{{route('addUser')}}" method="POST">
                            @csrf
                            <input style="height: 50px; max-width: 91%;" class="col-10" type="text" name="email" placeholder="E-mail van diegene die uitgenodigd wil worden...">

                            <button style="height: 50px; margin-bottom: 2.5px;" type="submit" class="btn btn-outline-dark col-1">
                                +
                            </button>
                        </form>

                        <form name="form" action="{{route('createGame')}}" method="POST">
                            @csrf
                            <div class="pt-3">
                                <input style="height: 50px" class="col-12" type="text" name="gameName" placeholder="Naam van het spel...">
                            </div>
                            <div class="pt-3">
                                <input style="height: 50px" class="col-12" type="text" name="uuid" value="{{$uuid}}" disabled>
                            </div>

                            <br>
                            <div class="text-center">
                                <button style="height: 50px;" type="submit" class="btn btn-outline-dark col-3">
                                    Maak spel aan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
