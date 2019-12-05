@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="container" style="background-color: black;">
                    <div class="jumbotron" style="background-color: black;">
                        <h1 style="text-align: center; color: white; font-weight: bold;">{{$game->name}}</h1>
                    </div>
                </div>

                @if(isset($delete))
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Gelukt!</h4>
                        <p>Verwijderen van de gebruiker is succesvol.</p>
                    </div>
                @endif

                @if(session('rightLink') == true)
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Gelukt!</h4>
                        <p>Welkom bij het spel!</p>
                    </div>
                @endif

                <div class="w3-bar w3-black">
                    @foreach($allPlayers as $player)
                        @if($user_id == $player->pivot->user_id)
                            @if($player->pivot->admin == 1)
                                <button class="w3-bar-item w3-button tablink w3-blue" onclick="openTab(event,'Settings')">Instellingen</button>
                                <button class="w3-bar-item w3-button tablink" onclick="openTab(event,'Rules')">Spelregels</button>
                            @else
                                <button class="w3-bar-item w3-button tablink w3-blue" onclick="openTab(event,'Rules')">Spelregels</button>
                                <button style="display: none;" class="w3-bar-item w3-button tablink" onclick="openTab(event,'Settings')" disabled></button>
                            @endif
                        @endif
                    @endforeach
                    <button class="w3-bar-item w3-button tablink" onclick="openTab(event,'Players')">Spelers</button>
                    <button class="w3-bar-item w3-button tablink" onclick="openTab(event,'Rounds')">Rondes</button>
                </div>

                @foreach($allPlayers as $player)
                    @if($user_id == $player->pivot->user_id)
                        @if($player->pivot->admin == 1)
                                <div id="Rules" class="w3-container w3-border tab" style="display:none">
                            @else
                                <div id="Rules" class="w3-container w3-border tab">
                            @endif
                    @endif
                @endforeach
                    <ul style="padding-top: 35px; padding-bottom: 25px;">
                        <li>
                            Voordat de speelronde begint selecteer je 1 team in de desbetreffende competitie waarin je speelt.
                        </li>
                        <li>
                            Wanneer je een team hebt gekozen, kun je dit niet meer aanpassen of ongedaan maken.
                        </li>
                        <li>
                            Wint je team, ga je door naar de volgende ronde. Verliest je team of spelen ze gelijk, lig je uit het spel.
                        </li>
                        <li>
                            Wanneer je door bent naar de volgende ronde kies je een ander team dan de ronde(s) daarvoor.
                        </li>
                        <li>
                            De speler die als laatste overblijft, wint en krijgt 1 punt.
                        </li>
                        <li>
                            Wanneer in de laatste speelronde alle teams onjuist zijn gekozen, winnen de spelers die in de laatste ronde waren overgelven en krijgen allemaal 1 punt.
                        </li>
                        <li>
                            Tijdens het spel mag je maar 1 keer hetzelfde team kiezen.
                        </li>
                        <li>
                            Indien er meer dan één deelnemer over is nadat deze alle teams zijn gekozen, dan zijn de overgebleven deelnemers de winnaars van het spel.
                        </li>
                    </ul>
                </div>

                <div id="Players" class="w3-border tab" style="display:none">
                    @foreach($allPlayers as $player)
                        @if($user_id == $player->pivot->user_id)
                            @if($player->pivot->admin == 1)
                                @foreach($allPlayers as $player)
                                    <div style="border-bottom: 1px solid black; height: 75px; list-style-type: none">
                                        <span style="float: left; padding-left: 25px; padding-top: 25px">{{$player->name}} - {{$player->pivot->point}}</span>
                                        <a href="{{route('deleteUser', ['id' => $player->id])}}" style="float: right;padding-right: 25px; padding-top: 25px">X</a>
                                    </div>
                                @endforeach
                            @else
                                @foreach($allPlayers as $player)
                                    <div style="width: 100%; border-bottom: 1px solid black; height: 75px; list-style-type: none">
                                        <span style="float: left; padding-left: 25px; padding-top: 25px">{{$player->name}} - {{$player->pivot->point}}</span>
                                    </div>
                                @endforeach
                            @endif
                        @endif
                    @endforeach
                </div>

                <div id="Rounds" class="w3-container w3-border tab" style="display:none">
                    @php
                        $league =
                [
                    ["Team 1", "Team 2", "-", "Team 3", "Team 4", "-", "Team 5", "Team 6", "-", "Team 7", "Team 8"],
                    ["Team 1", "Team 2", "-", "Team 3", "Team 4", "-", "Team 5", "Team 6", "-", "Team 7", "Team 8"],
                    ["Team 1", "Team 2", "-", "Team 3", "Team 4", "-", "Team 5", "Team 6", "-", "Team 7", "Team 8"],
                    ["Team 1", "Team 2", "-", "Team 3", "Team 4", "-", "Team 5", "Team 6", "-", "Team 7", "Team 8"],
                ];

                        for ($row = 0; $row < count($league); $row++) {
                            echo "<p><b>Row number $row</b></p>";
                            for ($col = 0; $col < count($league[1]); $col++) {
                                // Now every team has it's own callable name, which is the same value as that what is being shown.
                                echo "<span><input type='radio' name='" . $league[$row][$col] . "' value='other'>" . $league[$row][$col] . "</span><br>";
                            }
                        }
                    @endphp


{{--                    @for($row = 0; $row < count($league); $row++)--}}
{{--                        {{var_dump($league)}}--}}
{{--                        <h3>Ronde {{$row}}</h3>--}}
{{--                        @for($col = 0; $col < count($league); $col++)--}}
{{--                            <p>{{print_r($competitions)}}</p>--}}
{{--                            --}}{{--                            <p>{{$competitions[$row][$col]}}</p>--}}
{{--                        @endfor--}}
{{--                    @endfor--}}
                </div>

                    @foreach($allPlayers as $player)
                        @if($user_id == $player->pivot->user_id)
                            @if($player->pivot->admin == 1)
                                    <div id="Settings" class="w3-container w3-border tab">
                                @else
                                    <div id="Settings" class="w3-container w3-border tab" style="display:none">
                            @endif
                        @endif
                    @endforeach

                    @if(isset($user))
                        <div style="margin-top: 20px" class="alert alert-success" role="alert">
                            <h4 class="alert-heading">Gelukt!</h4>
                            <p>{{$user}} is toegevoegd.</p>
                        </div>
                    @endif

                    @if(isset($nope))
                        <div style="margin-top: 20px" class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Mislukt!</h4>
                            <p>{{$nope}}</p>
                        </div>
                    @endif

                    @if(isset($self))
                        <div style="margin-top: 20px" class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Mislukt!</h4>
                            <p>{{$self}}</p>
                        </div>
                    @endif

                    @if(isset($alreadyInvited))
                        <div style="margin-top: 20px" class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Mislukt!</h4>
                            <p>{{$alreadyInvited}}</p>
                        </div>
                    @endif

                    @if(isset($emptyName))
                        <div style="margin-top: 20px" class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Mislukt!</h4>
                            <p>{{$emptyName}}</p>
                        </div>
                    @endif

                    @if(session('saveNotice') == true)
                        <div style="margin-top: 20px" class="alert alert-success" role="alert">
                            <h4 class="alert-heading">Gelukt!</h4>
                            <p>Klik op opslaan om de gebruiker toe te voegen.</p>
                        </div>
                    @endif

                    <form style="padding-top: 15px; margin: 0;" name="form" action="{{route('addUserExistingGame', ['id' => $game->id])}}" method="POST">
                        @csrf
                        <input style="height: 50px; max-width: 91%;" class="col-10" type="text" name="email" placeholder="E-mail van diegene die uitgenodigd wil worden...">

                        <button style="height: 50px; margin-bottom: 2.5px;" type="submit" class="btn btn-outline-dark col-1">
                            +
                        </button>
                    </form>

                    <form name="form" action="{{route('updateGame', ['id' => $game->id])}}" method="POST">
                        @csrf
                        <div class="pt-3">
                            <input style="height: 50px" class="col-12" type="text" name="uuid" value="{{$uuid}}" disabled>
                        </div>

                        <div class="pt-3">
                            <input style="height: 50px" class="col-12" type="text" name="game_name" value="{{$game_name}}">
                        </div>

                        <br>
                        <div class="text-center">
                            <button style="height: 50px;" type="submit" class="btn btn-outline-dark col-3">
                                Wijzigingen opslaan
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

<script>
    // Hide all element with the class name "city" and display the element with the given city name.
    function openTab(evt, tabName) {
        var i, x, tablinks;
        x = document.getElementsByClassName("tab");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < x.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" w3-blue", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " w3-blue";
    }
</script>
