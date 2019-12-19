@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                {{-- Header --}}
                <div class="container" style="background-color: black;">
                    <div class="jumbotron" style="background-color: black;">
                        <h1 style="text-align: center; color: white; font-weight: bold;">Uitnodiging</h1>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        @if(isset($wrongLink))
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading">Mislukt!</h4>
                                <p>{{$wrongLink}}</p>
                            </div>
                        @endif

                        {{-- Request id of the game to fill in the id for the route, so you'll be on the invitation page of a certain game --}}
                        <form name="form" action="{{route('invitation', request()->route('id'))}}" method="GET">
                            {{-- Safety tag against attacks --}}
                            @csrf
                            <input style="height: 50px" class="col-12" type="text" name="invitation" placeholder="Uitnodigingslink...">

                            <div class="pt-3 text-center">
                                <button type="submit" class="btn btn-outline-dark col-3">
                                    Naar spel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
