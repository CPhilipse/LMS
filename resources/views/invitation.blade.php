@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="container" style="background-color: black;">
                    <div class="jumbotron" style="background-color: black;">
                        <h1 style="text-align: center; color: white; font-weight: bold;">Uitnodiging</h1>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form name="form" action="{{route('invitation')}}" method="POST">
                            @csrf
                            <input style="height: 50px" class="col-12" type="text" name="invitation" placeholder="Uitnodigingslink...">

                            <div class="pt-3 text-center">
                                <button type="submit" class="btn btn-outline-dark col-2">
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
