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
 // https://www.php.net/manual/en/function.date.php
 // https://stackoverflow.com/questions/5180730/string-date-current-date-time
 // https://www.php.net/manual/en/class.dateperiod.php
 // https://carbon.nesbot.com/docs/
                                                $now = date("y-m-d");

                                                $round1day0 = new DateTime( '19-12-1' );
                                                $round1day1 = new DateTime( '19-12-2' );
                                                $round1day2 = new DateTime( '19-12-3' );
                                                $round1day3 = new DateTime( '19-12-4' );
                                                $round1day4 = new DateTime( '19-12-5' );
                                                $round1day5 = new DateTime( '19-12-6' );
                                                $round1day6 = new DateTime( '19-12-7' );
                                                $resultround1day0 = $round1day0->format('y-m-d');
                                                $resultround1day1 = $round1day1->format('y-m-d');
                                                $resultround1day2 = $round1day2->format('y-m-d');
                                                $resultround1day3 = $round1day3->format('y-m-d');
                                                $resultround1day4 = $round1day4->format('y-m-d');
                                                $resultround1day5 = $round1day5->format('y-m-d');
                                                $resultround1day6 = $round1day6->format('y-m-d');

                                                $round2day0 = new DateTime( '19-12-8' );
                                                $round2day1 = new DateTime( '19-12-9' );
                                                $round2day2 = new DateTime( '19-12-10' );
                                                $round2day3 = new DateTime( '19-12-11' );
                                                $round2day4 = new DateTime( '19-12-12' );
                                                $round2day5 = new DateTime( '19-12-13' );
                                                $round2day6 = new DateTime( '19-12-14' );
                                                $resultround2day0 = $round2day0->format('y-m-d');
                                                $resultround2day1 = $round2day1->format('y-m-d');
                                                $resultround2day2 = $round2day2->format('y-m-d');
                                                $resultround2day3 = $round2day3->format('y-m-d');
                                                $resultround2day4 = $round2day4->format('y-m-d');
                                                $resultround2day5 = $round2day5->format('y-m-d');
                                                $resultround2day6 = $round2day6->format('y-m-d');

                                                $round2 = new DateTime( '19-12-10' );
                                                $result2 = $round2->format('y-m-d');

                                                $round3 = new DateTime( '19-12-15' );
                                                $result3 = $round3->format('y-m-d');

                                                $round4 = new DateTime( '19-12-22' );
                                                $result4 = $round4->format('y-m-d');

                                            for ($col = 0; $col < count($league[0]); $col++) {
                                                if($resultround1day0 || $resultround1day1 || $resultround1day2 || $resultround1day3 || $resultround1day4 || $resultround1day5 || $resultround1day6 == $now) {
                                                    echo "<span><input type='radio' name='team' value='" . $league[$row][$col] . "'>" . $league[$row][$col] . "</span><br>";
                                                } else {
                                                    echo 'nope';
                                                }

                                                if($resultround2day0 || $resultround2day1 || $resultround2day2 || $resultround2day3 || $resultround2day4 || $resultround2day5 || $resultround2day6 == $now) {
                                                    echo "<span><input type='radio' name='team' value='" . $league[$row][$col] . "'>" . $league[$row][$col] . "</span><br>";
                                                }
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
