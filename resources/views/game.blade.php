@extends('layouts.app')
<style>
    .mySlides {display: none}

    /* Slideshow container */
    .slideshow-container {
        max-width: 1000px;
        position: relative;
        margin: auto;
    }

    /* Next & previous buttons */
    .prev, .next {
        cursor: pointer;
        position: absolute;
        @if(session('rightLink') == true)
        top: 38%!important;
        @endif
        top: 31%;
        width: auto;
        padding: 16px;
        margin-top: -22px;
        color: white;
        font-weight: bold;
        font-size: 18px;
        transition: 0.6s ease;
        border-radius: 0 3px 3px 0;
        user-select: none;
    }

    /* Position the "next button" to the right */
    .next {
        right: 0;
        border-radius: 3px 0 0 3px;
    }

    /* On hover, add a black background color with a little bit see-through */
    .prev:hover, .next:hover {
        background-color: rgba(0,0,0,0.8);
    }

    /* Caption text */
    .text {
        color: #f2f2f2;
        font-size: 15px;
        padding: 8px 12px;
        position: absolute;
        bottom: 8px;
        width: 100%;
        text-align: center;
    }

    /* Number text (1/3 etc) */
    .numbertext {
        color: #f2f2f2;
        font-size: 12px;
        padding: 8px 12px;
        position: absolute;
        top: 0;
    }

    /* The dots/bullets/indicators */
    .dot {
        cursor: pointer;
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
        transition: background-color 0.6s ease;
    }

    .active, .dot:hover {
        background-color: #717171;
    }

    /* On smaller screens, decrease text size */
    @media only screen and (max-width: 300px) {
        .prev, .next,.text {font-size: 11px}
    }
</style>
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
                                        @if($player->pivot->admin == 1)
                                            <a href="{{route('deleteGame', ['id' => $game->id])}}" style="float: right;padding-right: 25px; padding-top: 25px">Verwijder spel</a>
                                        @else
                                            <a href="{{route('deleteUser', ['id' => $game->id, 'user_id' => $player->id])}}" style="float: right;padding-right: 25px; padding-top: 25px">X</a>
                                        @endif
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

                    <div id="timer" class="timer">
                        <Timer
                            starttime="Nov 5, 2018 15:37:25"
                            endtime="Nov 8, 2020 16:37:25"
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
                                "running":"Open",
                                "upcoming":"Komt nog"
                               }}'
                        ></Timer>
                    </div>

                    @php
                        $outcome =
                                [
                                    ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3"],
                                    ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3"],
                                    ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3"],
                                    ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3"],
                                ];

                            $league =
                                [
                                    [" Team 1", " Team 2 <hr>", " Team 3", " Team 4 <hr>", " Team 5", " Team 6 <hr>", " Team 7", " Team 8 <hr>", " Team 9", " Team 10 <hr>", " Team 11", " Team 12 <hr>"],
                                    [" Team 1 -", " Team 2 <hr>", " Team 3", " Team 4 <hr>", " Team 5", " Team 6 <hr>", " Team 7", " Team 8 <hr>", " Team 9", " Team 10 <hr>", " Team 11", " Team 12 <hr>"],
                                    [" Team 1 +", " Team 2 <hr>", " Team 3", " Team 4 <hr>", " Team 5", " Team 6 <hr>", " Team 7", " Team 8 <hr>", " Team 9", " Team 10 <hr>", " Team 11", " Team 12 <hr>"],
                                    [" Team 1 ?", " Team 2 <hr>", " Team 3", " Team 4 <hr>", " Team 5", " Team 6 <hr>", " Team 7", " Team 8 <hr>", " Team 9", " Team 10 <hr>", " Team 11", " Team 12 <hr>"],
                                ];



                            for ($row = 0; $row < count($league); $row++) {
                                    echo "<div class='mySlides'>";
                                echo '<a class="prev" style="color: #2196F3;" onclick="plusSlides(-1)">&#10094;</a>';
                                echo '<a class="next" style="right: 30px;color: #2196F3;" onclick="plusSlides(1)">&#10095;</a>';
                                echo "<h2 style='text-align: center; margin-top: 20px; padding-bottom: 25px'><b>Ronde $row</b></h2>";
                                for ($col = 0; $col < count($league[0]); $col++) {
                                    /*
                                     * Make a form around the radio button, with a button which will submit the choice.
                                     * Then in the controller get this value of the choice. Put it for now in a session, do a if/else statement on the radio button of the session.
                                     * If session is set to value, then don't show the radio button. Otherwise do show.
                                     * Make a new column in database with 'chosen'. On click of team, change chosen for this user to 1. Which means the person has chosen. Based on this remove radio button.
                                     * On new round, for the users who lose - read LOST::. For the users who's team won, change value chosen from 1 to 0.
                                     * Based on who's value of 'out' is 0, check who's still in the game. If there's one left, give this person a $point + 1.
                                     *
                                     * Q: * How to keep record which round a certain game is on
                                     * LOST::
                                     * WHEN having 'out' column in the DB, on wrong change it to 0. On reset of game, set it all to 1 and give the remaining user $point + 1.
                                     * RESET::
                                     * Resetting the games is basically changing all the users in the game their 'out' value to 0 and 'chosen' value to 0.
                                     *
                                     * Have the radio buttons show on date. At the end of the last game of a round, don't show radio buttons.
                                     * DESPERATE: put the <input radio in the array before the team names.
                                     *
                                     *  */
                                    echo "<span><input type='radio' name='team' value='" . $league[$row][$col] . "'>" . $league[$row][$col] . "</span><br>";
                                }
                                    echo "</div>";
                            }
                    @endphp
                    <script>
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
                            var dots = document.getElementsByClassName("dot");
                            if (n > slides.length) {slideIndex = 1}
                            if (n < 1) {slideIndex = slides.length}
                            for (i = 0; i < slides.length; i++) {
                                slides[i].style.display = "none";
                            }
                            for (i = 0; i < dots.length; i++) {
                                dots[i].className = dots[i].className.replace(" active", "");
                            }
                            slides[slideIndex-1].style.display = "block";
                            dots[slideIndex-1].className += " active";
                        }
                    </script>
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

                    @if(isset($userExistsInGame))
                        <div style="margin-top: 20px" class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Mislukt!</h4>
                            <p>{{$userExistsInGame}}</p>
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
