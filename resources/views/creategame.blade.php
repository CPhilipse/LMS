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

                        @if(isset($nope) == 'nope')
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading">Mislukt!</h4>
                                <p>Gebruiker kan niet gevonden worden.</p>
                            </div>
                        @endif

                        @if(isset($self) == 'self')
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading">Mislukt!</h4>
                                <p>{{isset($self) ? $self : ''}}</p>
                            </div>
                        @endif

                        @if(isset($alreadyInvited))
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading">Mislukt!</h4>
                                <p>{{isset($alreadyInvited) ? $alreadyInvited : ''}}</p>
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
                            <input class="col-9" type="text" name="email" placeholder="E-mail van diegene die uitgenodigd wil worden...">

                            <button type="submit" class="btn btn-outline-dark col-2">
                                +
                            </button>
                        </form>

                        <form name="form" action="{{route('createGame')}}" method="POST">
                            @csrf
                            <div class="pt-3">
                                <input class="col-12" type="text" name="gameName" placeholder="Naam van het spel...">
                            </div>
                            <div class="pt-3">
                                <input class="col-12" type="text" name="uuid" value="{{$uuid}}" disabled>
                            </div>

                            <br><br>

                            <button type="submit" class="btn btn-outline-dark">
                                Maak spel aan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
