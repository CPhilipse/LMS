@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                {{-- Header --}}
                <div class="container" style="background-color: black;">
                    <div class="jumbotron" style="background-color: black;">
                        {{-- Show the name of the game you're in --}}
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

                @if(session('chooseTeam') == true)
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Mislukt!</h4>
                        <p>Kies een team om te stemmen.</p>
                    </div>
                @endif

                @if(session('alreadyVotedFor') == true)
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Mislukt!</h4>
                        <p>U kan niet op dezelfde team kiezen in één game.</p>
                    </div>
                @endif

                {{-- Tabs block --}}
                <div class="w3-bar w3-black">
                    {{-- Get all users --}}
                    @foreach($allPlayers as $player)
                        {{-- Check whether the logged in user is in this game --}}
                        @if($user_id == $player->pivot->user_id)
                            {{-- Check whether the logged in user is admin, if so show settings, otherwise don't. --}}
                            @if($player->pivot->admin == 1)
                                <button class="w3-bar-item w3-button tablink w3-btns" onclick="openTab(event,'Settings')">Instellingen</button>
                                <button class="w3-bar-item w3-button tablink" onclick="openTab(event,'Rules')">Spelregels</button>
                            @else
                                <button class="w3-bar-item w3-button tablink w3-btns" onclick="openTab(event,'Rules')">Spelregels</button>
                                <button style="display: none;" class="w3-bar-item w3-button tablink" onclick="openTab(event,'Settings')" disabled></button>
                            @endif
                        @endif
                    @endforeach
                    <button class="w3-bar-item w3-button tablink" onclick="openTab(event,'Players')">Spelers</button>
                    <button class="w3-bar-item w3-button tablink" onclick="openTab(event,'Rounds')">Rondes</button>
                </div>

                {{-- Tab > Rules --}}
                {{-- Get all users --}}
                @foreach($allPlayers as $player)
                    {{-- Check whether the logged in user is in this game --}}
                    @if($user_id == $player->pivot->user_id)
                        {{-- Check whether the logged in user is admin, if so don't put the rules tab overlap the settings tab. --}}
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

                {{-- Tab > Players --}}
                <div id="Players" class="w3-border tab" style="display:none">
                    {{-- Get all users --}}
                    @foreach($allPlayers as $player)
                        {{-- Check whether the logged in user is in this game --}}
                        @if($user_id == $player->pivot->user_id)
                            {{-- Check whether the logged in user is admin, if so show all users with option to delete them and option to delete the actual game. --}}
                            @if($player->pivot->admin == 1)
                                @foreach($allPlayers as $player)
                                    <div style="border-bottom: 1px solid black; height: 75px; list-style-type: none">
                                        {{-- Show all points of each user --}}
                                        <span style="float: left; padding-left: 25px; padding-top: 25px">{{$player->name}} - {{$player->pivot->point}}</span>
                                        {{-- Only admin can delete the game and delete users from his game --}}
                                        @if($player->pivot->admin == 1)
                                            {{-- Give game id to the controller by passing it to the route so the controller knows which game to delete --}}
                                            <a href="{{route('deleteGame', ['id' => $game->id])}}" style="float: right;padding-right: 25px; padding-top: 25px">Verwijder spel</a>
                                        @else
                                            {{-- Give player id to the controller by passing it to the route so the controller knows which user to delete --}}
                                            <a href="{{route('deleteUser', ['id' => $game->id, 'user_id' => $player->id])}}" style="float: right;padding-right: 25px; padding-top: 25px">X</a>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                {{-- All other players will not have the option to delete anything and only see the users with their points. --}}
                                @foreach($allPlayers as $player)
                                    <div style="width: 100%; border-bottom: 1px solid black; height: 75px; list-style-type: none">
                                        <span style="float: left; padding-left: 25px; padding-top: 25px">{{$player->name}} - {{$player->pivot->point}}</span>
                                    </div>
                                @endforeach
                            @endif
                        @endif
                    @endforeach
                </div>

                {{-- Tab > Rounds --}}
                <div id="Rounds" class="w3-container w3-border tab" style="display:none">
                    {{-- Count $league for that is the number of rounds in the league --}}
                    @for($row = 0; $row < count($league); $row++)
                        <div class="mySlides">
                            <a class="prevv" onclick="plusSlides(-1)">&#10094;</a>
                            <a class="nextt" onclick="plusSlides(1)">&#10095;</a>
                            <h2 class="round"><b>Ronde {{$row}}</b></h2>
                            <div style="margin: 0!important" id="timer" class="timer">
                                {{-- Vue timer component, clarity for the user what the time frames are for each round. --}}
                                <time-default
                                    {{-- Loop through all the dates defined in the controller where it was it an array. Start and end separately. --}}
                                    starttime="{{$weeksStart[$row] . " 00:00:00"}}"
                                    endtime="{{$weeksEnd[$row] . " 00:00:00"}}"
                                    trans='{
                                     "day":"Dagen",
                                     "hours":"Uren",
                                     "minutes":"Minuten",
                                     "seconds":"Seconden",
                                     "expired":"Ronde is afgelopen.",
                                     "running":"Ronde is nu gaande.",
                                     "upcoming":"Komt nog.",
                                     "status": {
                                        "expired":"Verlopen",
                                        "running":"Nu open",
                                        "upcoming":"Open in ↝"
                                       }}'
                                ></time-default>
                            </div>

                            {{-- Form for handling the voting of the user --}}
                            <form style="padding-top: 15px; margin: 0;" name="form" action="{{route('voteTeam', ['id' => $game->id])}}" method="POST">
                                {{-- Safety tag against attacks --}}
                                @csrf
                                {{-- Foreach over all users to access their pivot data. --}}
                                @foreach($allPlayers as $player)
                                    {{-- Check whether your id corresponds with id in game. --}}
                                    @if($player->id == $user_id)
                                        {{-- Check whether you are out. If so don't show vote/radio buttons --}}
                                        @if($player->pivot->out == 0)
                                            {{-- Check whether you've chosen already. If so don't show vote/radio buttons --}}
                                            @if($player->pivot->chosen == 0)
                                                {{-- Show option to vote based on whether there are more or less then 2 people in game. --}}
                                                @if($tooLittlePlayers == false)
                                                    <button style="height: 50px; margin-bottom: 2.5px;" type="submit" class="btn btn-outline-dark col-12">
                                                        Stemmen
                                                    </button>
                                                @else
                                                    <button style="cursor: default;height: 50px; margin-bottom: 2.5px;" type="button" class="btn btn-outline-dark col-12" disabled>
                                                        Te weinig spelers om te kunnen spelen.
                                                    </button>
                                                @endif

                                                <ul class="teams">
                                                    {{-- Loop through 2D dimensional array --}}
                                                    @for($col = 0; $col < count($league[0]); $col++)
                                                        {{-- Show buttons only in the round where the number in the end of that array is equal to the current week --}}
                                                        @if(end($league[$row]) == $current_week)
                                                            <li class="comp"><input type='radio' name='team' value='{{$league[$row][$col]}}'>{{$league[$row][$col]}}</li>
                                                        @else
                                                            {{-- Not current week so don't show buttons to vote. --}}
                                                            <li class="comp">{{$league[$row][$col]}}</li>
                                                        @endif
                                                    @endfor
                                                </ul>
                                            @else
                                                {{-- Show which team the user has voted on in a fancy disables button --}}
                                                <button style="cursor: default;height: 50px; margin-bottom: 2.5px;" type="button" class="btn btn-outline-dark col-12" disabled>
                                                    U heeft gekozen voor {{$player->pivot->team}}.
                                                </button>
                                                <ul class="teams">
                                                    {{-- Loop through 2D dimensional array to show the comps/teams --}}
                                                    @for($col = 0; $col < count($league[0]); $col++)
                                                        <li class="comp">{{$league[$row][$col]}}</li>
                                                    @endfor
                                                </ul>
                                            @endif
                                        @else
                                            <ul class="teams">
                                                {{-- Loop through 2D dimensional array to show the comps/teams --}}
                                                @for($col = 0; $col < count($league[0]); $col++)
                                                    <li class="comp">{{$league[$row][$col]}}</li>
                                                @endfor
                                            </ul>
                                        @endif
                                    @endif
                                @endforeach
                            </form>
                        </div>
                    @endfor

                    <script>
                        // JS for handling the round slides
                        var slideIndex = 1;
                        showSlides(slideIndex);

                        function plusSlides(n) {
                            showSlides(slideIndex += n);
                        }

                        function currentSlide(n) {
                            showSlides(slideIndex = n);
                        }

                        function showSlides(n) {
                            var i;
                            var slides = document.getElementsByClassName("mySlides");
                            if (n > slides.length) {slideIndex = 1}
                            if (n < 1) {slideIndex = slides.length}
                            for (i = 0; i < slides.length; i++) {
                                slides[i].style.display = "none";
                            }
                            slides[slideIndex-1].style.display = "block";
                        }
                    </script>
                </div>

                {{-- Tab > Settings --}}
                {{-- Show settings only to the admin. --}}
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

                            @if(session('nope') == true)
                                <div style="margin-top: 20px" class="alert alert-danger" role="alert">
                                    <h4 class="alert-heading">Mislukt!</h4>
                                    <p>Gebruiker kan niet gevonden worden.</p>
                                </div>
                            @endif

                            @if(session('self') == true)
                                <div style="margin-top: 20px" class="alert alert-danger" role="alert">
                                    <h4 class="alert-heading">Mislukt!</h4>
                                    <p>U kan uzelf niet toevoegen.</p>
                                </div>
                            @endif

                            @if(session('alreadyInvited') == true)
                                <div style="margin-top: 20px" class="alert alert-danger" role="alert">
                                    <h4 class="alert-heading">Mislukt!</h4>
                                    <p>Gebruiker is al toegevoegd, klik op opslaan.</p>
                                </div>
                            @endif

                            @if(session('userExistsInGame') == true)
                                <div style="margin-top: 20px" class="alert alert-danger" role="alert">
                                    <h4 class="alert-heading">Mislukt!</h4>
                                    <p>De gebruiker van de ingevoerde email is al in dit spel.</p>
                                </div>
                            @endif

                            @if(session('saveNotice') == true)
                                <div style="margin-top: 20px" class="alert alert-success" role="alert">
                                    <h4 class="alert-heading">Gelukt!</h4>
                                    <p>Klik op opslaan om de gebruiker toe te voegen.</p>
                                </div>
                            @endif

                            {{-- Form for adding users into the game you're on --}}
                            <form style="padding-top: 15px; margin: 0;" name="form" action="{{route('addUserExistingGame', ['id' => $game->id])}}" method="POST">
                                {{-- Safety tag against attacks --}}
                                @csrf
                                <input style="height: 50px; max-width: 91%;" class="col-10" type="text" name="email" placeholder="E-mail van diegene die uitgenodigd wil worden...">

                                <button style="height: 50px; margin-bottom: 2.5px;" type="submit" class="btn btn-outline-dark col-1">
                                    +
                                </button>
                            </form>

                            {{-- Form for changing the name of the game you're on --}}
                            <form name="form" action="{{route('updateGame', ['id' => $game->id])}}" method="POST">
                                {{-- Safety tag against attacks --}}
                                @csrf
                                <div class="pt-3">
                                    {{-- Show corresponding uuid of the game --}}
                                    <input style="height: 50px" class="col-12" type="text" name="uuid" value="{{$uuid}}" disabled>
                                </div>

                                <div class="pt-3">
                                    {{-- Show corresponding name of the game --}}
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
                // Hide all element with the class name "tab" and display the element with the given tab name.
                function openTab(evt, tabName) {
                    var i, x, tablinks;
                    x = document.getElementsByClassName("tab");
                    for (i = 0; i < x.length; i++) {
                        x[i].style.display = "none";
                    }
                    tablinks = document.getElementsByClassName("tablink");
                    for (i = 0; i < x.length; i++) {
                        tablinks[i].className = tablinks[i].className.replace(" w3-btns", "");
                    }
                    document.getElementById(tabName).style.display = "block";
                    evt.currentTarget.className += " w3-btns";
                }
            </script>
