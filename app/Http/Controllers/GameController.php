<?php

namespace App\Http\Controllers;

use App\Game;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     * Load the game or the invitation page if user hasn't been invited yet.
     *
     * Load the static data corresponding to the Dutch league with the right time frame of the rounds.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(Request $request, $id)
    {
        $outcome =
            [
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 31],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 32],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 33],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 34],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 35],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 36],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 37],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 38],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 39],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 40],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 41],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 42],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 43],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 44],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 45],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 46],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 47],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 48],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 49],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 50],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 51],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 52],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 1],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 2],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 3],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 4],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 5],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 6],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 7],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 8],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 9],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 10],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 11],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 12],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3", 13],
            ];

        $league =
            [
                ["PEC", "Willem II", "Emmen", "Groningen", "Vitesse", "Ajax", "Twente", "PSV", "VVV", "RKC", "Heracles", "Heerenveen", 31], // Round: 0
                ["Sparta", "VVV", "Groningen", "Twente", "Ajax", "Emmen", "Willem II", "Vitesse", "Fortuna", "Heracles", "Heerenveen", "Feyenoord", 32], // 1
                ["Vitesse", "PEC", "VVV", "Ajax", "ADO", "Sparta", "Emmen", "Heerenveen", "Fortuna", "Willem II", "Twente", "RKC", 33], // 2
                ["Willem II", "Emmen", "Heracles", "Vitesse", "Heerenveen", "Twente", "RKC", "ADO", "Utrecht", "VVV", "PEC", "Sparta", 34], // 3
                ["Emmen", "PEC", "Heerenveen", "Fortuna", "Groningen", "Heracles", "ADO", "VVV", "Twente", "Utrecht", "Sparta", "Ajax", 35], // 4
                ["Ajax", "Heerenveen", "AZ", "Sparta", "VVV", "Groningen", "Utrecht", "Emmen", "PSV", "Vitesse", "PEC", "RKC", 36], // 5
                ["Twente", "Heracles", "Sparta", "RKC", "Groningen", "PEC", "Willem II", "VVV", "Vitesse", "Fortuna", "Heerenveen", "Utrecht", 37], // 6
                ["PSV", "Groningen", "Ajax", "Fortuna", "Feyenoord", "AZ", "PSV", "Groningen", "Ajax", "Fortuna", "Feyenoord", "AZ", 38], // 7
                ["Emmen", "ADO", "VVV", "Heerenveen", "Ajax", "Groningen", "Fortuna", "Sparta", "RKC", "Vitesse", "PEC", "PSV", 39], // 8
                ["Groningen", "RKC", "Heracles", "Emmen", "Vitesse", "Utrecht", "Sparta", "Twente", "Heerenveen", "PEC", "ADO", "Ajax", 40], // 9
                ["RKC", "Ajax", "AZ", "Heerenveen", "Twente", "Willem II", "Utrecht", "PSV", "VVV", "Vitesse", "PEC", "ADO", 41], // 10
                ["Twente", "Emmen", "Willem II", "RKC", "Vitesse", "ADO", "Fortuna", "VVV", "Heracles", "PEC", "Heerenveen", "Groningen", 42], // 11
                ["PEC", "Ajax", "RKC", "Heracles", "Sparta", "PSV", "AZ", "Twente", "Emmen", "Vitesse", "VVV", "Feyenoord", 43], // 12
                ["Vitesse", "Groningen", "Heerenveen", "Sparta", "Fortuna", "ADO", "Heracles", "VVV", "Ajax", "Utrecht", "Twente", "PEC", 44], // 13
                ["Utrecht", "AZ", "PEC", "Fortuna", "Ajax", "Heracles", "ADO", "Willem II", "Groningen", "Feyenoord", "RKC", "Emmen", 45], // 14
                ["Heerenveen", "Vitesse", "Willem II", "Sparta", "Fortuna", "Groningen", "Heracles", "ADO", "Twente", "Ajax", "Utrecht", "RKC", 46], // 15
                ["Ajax", "Willem II", "ADO", "Twente", "VVV", "Emmen", "PSV", "Fortuna", "PEC", "AZ", "Vitesse", "Feyenoord", 47], // 16
                ["Heerenveen", "Willem II", "ADO", "Groningen", "VVV", "PEC", "Fortuna", "RKC", "Twente", "Vitesse", "Emmen", "Sparta", 48], // 17
                ["RKC", "Twente", "Heerenveen", "Heracles", "PSV", "PEC", "Sparta", "AZ", "Willem II", "Fortuna", "Ajax", "ADO", 49], // 18
                ["PEC", "Utrecht", "Fortuna", "Vitesse", "Feyenoord", "Heerenveen", "Twente", "Groningen", "AZ", "Willem II", "VVV", "PSV", 50], // 19
                ["Utrecht", "ADO", "Heerenveen", "AZ", "RKC", "VVV", "Heracles", "Feyenoord", "Sparta", "Fortuna", "Vitesse", "Emmen", 51], // 20
                ["AZ", "RKC", "ADO", "Vitesse", "Feyenoord", "Emmen", "Twente", "Sparta", "PEC", "Groningen", "Willem II", "Heracles", 52], // 21
                ["Heracles", "Fortuna", "Groningen", "Vitesse", "Heerenveen", "VVV", "PSV", "Willem II", "RKC", "PEC", "Utrecht", "Ajax", 1], // 22
                ["Willem II", "Utrecht", "VVV", "Heracles", "Twente", "AZ", "ADO", "PSV", "Fortuna", "Emmen", "Vitesse", "Heerenveen", 2], // 23
                ["RKC", "Sparta", "Emmen", "Willem II", "AZ", "PEC", "Feyenoord", "Fortuna", "Groningen", "VVV", "Utrecht", "Twente", 3], // 24
                ["Willem II", "Groningen", "ADO", "Heracles", "PEC", "Vitesse", "VVV", "Fortuna", "Twente", "Heerenveen", "RKC", "Utrecht", 4], // 25
                ["Fortuna", "PEC", "Emmen", "VVV", "AZ", "ADO", "Heerenveen", "Ajax", "Vitesse", "Twente", "Utrecht", "Sparta", 5], // 26
                ["ADO", "Fortuna", "Willem II", "Heerenveen", "PEC", "Heracles", "Ajax", "Twente", "Utrecht", "Vitesse", "Sparta", "Feyenoord", 6], // 27
                ["Heracles", "Sparta", "Twente", "ADO", "PEC", "VVV", "Vitesse", "Willem II", "Heerenveen", "RKC", "Fortuna", "PSV", 7], // 28
                ["Sparta", "Heerenveen", "VVV", "Willem II", "PSV", "Heracles", "AZ", "Vitesse", "Utrecht", "Groningen", "RKC", "Feyenoord", 8], // 29
                ["Groningen", "Heerenveen", "Vitesse", "RKC", "Feyenoord", "VVV", "Heracles", "AZ", "Willem II", "ADO", "PSV", "Sparta", 9], // 30
                ["AZ", "PSV", "Sparta", "PEC", "RKC", "Willem II", "ADO", "Feyenoord", "Groningen", "Fortuna", "Utrecht", "Heracles", 10], // 31
                ["Fortuna", "AZ", "PSV", "Utrecht", "Emmen", "RKC", "PEC", "Heerenveen", "Feyenoord", "Groningen", "Heracles", "Twente", 11], // 32
                ["RKC", "Fortuna", "Sparta", "Willem II", "Twente", "Feyenoord", "PEC", "Emmen", "Heerenveen", "PSV", "Groningen", "ADO", 12], // 33
                ["ADO", "PEC", "Willem II", "Twente", "Utrecht", "Heerenveen", "Heracles", "Groningen", "PSV", "RKC", "Feyenoord", "Vitesse", 13], // Round: 34
            ];

        $game_id = Game::find($id);

        $allPlayers = $game_id->users;

        $user_id = auth()->user()->id;

        $current_game_id = $request->route('id');

        // Show time for each round for more clarity on the round time frame. Timer > Vue component.
        $current_week = Carbon::now()->week;

        // Whole league time-zone
        // https://weeknummers.com/weeknummers/2019/
        $week1date = Carbon::create(2019, 7, 29);
        $week2date = Carbon::create(2019, 8, 5);
        $week3date = Carbon::create(2019, 8, 12);
        $week4date = Carbon::create(2019, 8, 19);
        $week5date = Carbon::create(2019, 8, 26);
        $week6date = Carbon::create(2019, 9, 2);
        $week7date = Carbon::create(2019, 9, 9);
        $week8date = Carbon::create(2019, 9, 16);
        $week9date = Carbon::create(2019, 9, 23);
        $week10date = Carbon::create(2019, 9, 30);
        $week11date = Carbon::create(2019, 10, 7);
        $week12date = Carbon::create(2019, 10, 14);
        $week13date = Carbon::create(2019, 10, 21);
        $week14date = Carbon::create(2019, 10, 28);
        $week15date = Carbon::create(2019, 11, 4);
        $week16date = Carbon::create(2019, 11, 11);
        $week17date = Carbon::create(2019, 11, 18);
        $week18date = Carbon::create(2019, 11, 25);
        $week19date = Carbon::create(2019, 12, 2);
        $week20date = Carbon::create(2019, 12, 9);
        $week21date = Carbon::create(2019, 12, 16);
        $week22date = Carbon::create(2019, 12, 23);
        $week23date = Carbon::create(2019, 12, 30);
        $week24date = Carbon::create(2020, 1, 6);
        $week25date = Carbon::create(2020, 1, 13);
        $week26date = Carbon::create(2020, 1, 20);
        $week27date = Carbon::create(2020, 1, 27);
        $week28date = Carbon::create(2020, 2, 3);
        $week29date = Carbon::create(2020, 2, 10);
        $week30date = Carbon::create(2020, 2, 17);
        $week31date = Carbon::create(2020, 2, 24);
        $week32date = Carbon::create(2020, 3, 2);
        $week33date = Carbon::create(2020, 3, 9);
        $week34date = Carbon::create(2020, 3, 16);
        $week35date = Carbon::create(2020, 3, 23);
        $week36date = Carbon::create(2020, 3, 30);
        $week37date = Carbon::create(2020, 4, 6);
        $week38date = Carbon::create(2020, 4, 13);
        $week39date = Carbon::create(2020, 4, 20);
        $week40date = Carbon::create(2020, 4, 27);
        $week41date = Carbon::create(2020, 5, 4);

        $week1 = $week1date->toFormattedDateString();
        $week2 = $week2date->toFormattedDateString();
        $week3 = $week3date->toFormattedDateString();
        $week4 = $week4date->toFormattedDateString();
        $week5 = $week5date->toFormattedDateString();
        $week6 = $week6date->toFormattedDateString();
        $week7 = $week7date->toFormattedDateString();
        $week8 = $week8date->toFormattedDateString();
        $week9 = $week9date->toFormattedDateString();
        $week10 = $week10date->toFormattedDateString();
        $week11 = $week11date->toFormattedDateString();
        $week12 = $week12date->toFormattedDateString();
        $week13 = $week13date->toFormattedDateString();
        $week14 = $week14date->toFormattedDateString();
        $week15 = $week15date->toFormattedDateString();
        $week16 = $week16date->toFormattedDateString();
        $week17 = $week17date->toFormattedDateString();
        $week18 = $week18date->toFormattedDateString();
        $week19 = $week19date->toFormattedDateString();
        $week20 = $week20date->toFormattedDateString();
        $week21 = $week21date->toFormattedDateString();
        $week22 = $week22date->toFormattedDateString();
        $week23 = $week23date->toFormattedDateString();
        $week24 = $week24date->toFormattedDateString();
        $week25 = $week25date->toFormattedDateString();
        $week26 = $week26date->toFormattedDateString();
        $week27 = $week27date->toFormattedDateString();
        $week28 = $week28date->toFormattedDateString();
        $week29 = $week29date->toFormattedDateString();
        $week30 = $week30date->toFormattedDateString();
        $week31 = $week31date->toFormattedDateString();
        $week32 = $week32date->toFormattedDateString();
        $week33 = $week33date->toFormattedDateString();
        $week34 = $week34date->toFormattedDateString();
        $week35 = $week35date->toFormattedDateString();
        $week36 = $week36date->toFormattedDateString();
        $week37 = $week37date->toFormattedDateString();
        $week38 = $week38date->toFormattedDateString();
        $week39 = $week39date->toFormattedDateString();
        $week40 = $week40date->toFormattedDateString();
        $week41 = $week41date->toFormattedDateString();

        $weeksStart = [];
        $weeksStart[] = $week1;
        $weeksStart[] = $week2;
        $weeksStart[] = $week3;
        $weeksStart[] = $week4;
        $weeksStart[] = $week5;
        $weeksStart[] = $week6;
        $weeksStart[] = $week7;
        $weeksStart[] = $week8;
        $weeksStart[] = $week9;
        $weeksStart[] = $week10;
        $weeksStart[] = $week11;
        $weeksStart[] = $week12;
        $weeksStart[] = $week13;
        $weeksStart[] = $week14;
        $weeksStart[] = $week15;
        $weeksStart[] = $week16;
        $weeksStart[] = $week17;
        $weeksStart[] = $week18;
        $weeksStart[] = $week19;
        $weeksStart[] = $week20;
        $weeksStart[] = $week21;
        $weeksStart[] = $week22;
        $weeksStart[] = $week23;
        $weeksStart[] = $week24;
        $weeksStart[] = $week25;
        $weeksStart[] = $week26;
        $weeksStart[] = $week27;
        $weeksStart[] = $week28;
        $weeksStart[] = $week29;
        $weeksStart[] = $week30;
        $weeksStart[] = $week31;
        $weeksStart[] = $week32;
        $weeksStart[] = $week33;
        $weeksStart[] = $week34;
        $weeksStart[] = $week35;
        $weeksStart[] = $week36;
        $weeksStart[] = $week37;
        $weeksStart[] = $week38;
        $weeksStart[] = $week39;
        $weeksStart[] = $week40;
        $weeksStart[] = $week41;

        $week1dateEnd = Carbon::create(2019, 8, 04);
        $week2dateEnd = Carbon::create(2019, 8, 11);
        $week3dateEnd = Carbon::create(2019, 8, 18);
        $week4dateEnd = Carbon::create(2019, 8, 25);
        $week5dateEnd = Carbon::create(2019, 9, 1);
        $week6dateEnd = Carbon::create(2019, 9, 8);
        $week7dateEnd = Carbon::create(2019, 9, 15);
        $week8dateEnd = Carbon::create(2019, 9, 22);
        $week9dateEnd = Carbon::create(2019, 9, 29);
        $week10dateEnd = Carbon::create(2019, 10, 6);
        $week11dateEnd = Carbon::create(2019, 10, 13);
        $week12dateEnd = Carbon::create(2019, 10, 20);
        $week13dateEnd = Carbon::create(2019, 10, 27);
        $week14dateEnd = Carbon::create(2019, 11, 3);
        $week15dateEnd = Carbon::create(2019, 11, 10);
        $week16dateEnd = Carbon::create(2019, 11, 17);
        $week17dateEnd = Carbon::create(2019, 11, 24);
        $week18dateEnd = Carbon::create(2019, 12, 1);
        $week19dateEnd = Carbon::create(2019, 12, 8);
        $week20dateEnd = Carbon::create(2019, 12, 15);
        $week21dateEnd = Carbon::create(2019, 12, 22);
        $week22dateEnd = Carbon::create(2019, 12, 29);
        $week23dateEnd = Carbon::create(2020, 1, 5);
        $week24dateEnd = Carbon::create(2020, 1, 19);
        $week25dateEnd = Carbon::create(2020, 1, 26);
        $week26dateEnd = Carbon::create(2020, 2, 2);
        $week27dateEnd = Carbon::create(2020, 2, 9);
        $week28dateEnd = Carbon::create(2020, 2, 16);
        $week29dateEnd = Carbon::create(2020, 2, 23);
        $week30dateEnd = Carbon::create(2020, 3, 1);
        $week31dateEnd = Carbon::create(2020, 3, 8);
        $week32dateEnd = Carbon::create(2020, 3, 15);
        $week33dateEnd = Carbon::create(2020, 3, 22);
        $week34dateEnd = Carbon::create(2020, 3, 29);
        $week35dateEnd = Carbon::create(2020, 4, 5);
        $week36dateEnd = Carbon::create(2020, 4, 12);
        $week37dateEnd = Carbon::create(2020, 4, 19);
        $week38dateEnd = Carbon::create(2020, 4, 26);
        $week39dateEnd = Carbon::create(2020, 5, 3);
        $week40dateEnd = Carbon::create(2020, 5, 10);
        $week41dateEnd = Carbon::create(2020, 5, 11);

        $week1End = $week1dateEnd->toFormattedDateString();
        $week2End = $week2dateEnd->toFormattedDateString();
        $week3End = $week3dateEnd->toFormattedDateString();
        $week4End = $week4dateEnd->toFormattedDateString();
        $week5End = $week5dateEnd->toFormattedDateString();
        $week6End = $week6dateEnd->toFormattedDateString();
        $week7End = $week7dateEnd->toFormattedDateString();
        $week8End = $week8dateEnd->toFormattedDateString();
        $week9End = $week9dateEnd->toFormattedDateString();
        $week10End = $week10dateEnd->toFormattedDateString();
        $week11End = $week11dateEnd->toFormattedDateString();
        $week12End = $week12dateEnd->toFormattedDateString();
        $week13End = $week13dateEnd->toFormattedDateString();
        $week14End = $week14dateEnd->toFormattedDateString();
        $week15End = $week15dateEnd->toFormattedDateString();
        $week16End = $week16dateEnd->toFormattedDateString();
        $week17End = $week17dateEnd->toFormattedDateString();
        $week18End = $week18dateEnd->toFormattedDateString();
        $week19End = $week19dateEnd->toFormattedDateString();
        $week20End = $week20dateEnd->toFormattedDateString();
        $week21End = $week21dateEnd->toFormattedDateString();
        $week22End = $week22dateEnd->toFormattedDateString();
        $week23End = $week23dateEnd->toFormattedDateString();
        $week24End = $week24dateEnd->toFormattedDateString();
        $week25End = $week25dateEnd->toFormattedDateString();
        $week26End = $week26dateEnd->toFormattedDateString();
        $week27End = $week27dateEnd->toFormattedDateString();
        $week28End = $week28dateEnd->toFormattedDateString();
        $week29End = $week29dateEnd->toFormattedDateString();
        $week30End = $week30dateEnd->toFormattedDateString();
        $week31End = $week31dateEnd->toFormattedDateString();
        $week32End = $week32dateEnd->toFormattedDateString();
        $week33End = $week33dateEnd->toFormattedDateString();
        $week34End = $week34dateEnd->toFormattedDateString();
        $week35End = $week35dateEnd->toFormattedDateString();
        $week36End = $week36dateEnd->toFormattedDateString();
        $week37End = $week37dateEnd->toFormattedDateString();
        $week38End = $week38dateEnd->toFormattedDateString();
        $week39End = $week39dateEnd->toFormattedDateString();
        $week40End = $week40dateEnd->toFormattedDateString();
        $week41End = $week41dateEnd->toFormattedDateString();

        $weeksEnd = [];
        $weeksEnd[] = $week1End;
        $weeksEnd[] = $week2End;
        $weeksEnd[] = $week3End;
        $weeksEnd[] = $week4End;
        $weeksEnd[] = $week5End;
        $weeksEnd[] = $week6End;
        $weeksEnd[] = $week7End;
        $weeksEnd[] = $week8End;
        $weeksEnd[] = $week9End;
        $weeksEnd[] = $week10End;
        $weeksEnd[] = $week11End;
        $weeksEnd[] = $week12End;
        $weeksEnd[] = $week13End;
        $weeksEnd[] = $week14End;
        $weeksEnd[] = $week15End;
        $weeksEnd[] = $week16End;
        $weeksEnd[] = $week17End;
        $weeksEnd[] = $week18End;
        $weeksEnd[] = $week19End;
        $weeksEnd[] = $week20End;
        $weeksEnd[] = $week21End;
        $weeksEnd[] = $week22End;
        $weeksEnd[] = $week23End;
        $weeksEnd[] = $week24End;
        $weeksEnd[] = $week25End;
        $weeksEnd[] = $week26End;
        $weeksEnd[] = $week27End;
        $weeksEnd[] = $week28End;
        $weeksEnd[] = $week29End;
        $weeksEnd[] = $week30End;
        $weeksEnd[] = $week31End;
        $weeksEnd[] = $week32End;
        $weeksEnd[] = $week33End;
        $weeksEnd[] = $week34End;
        $weeksEnd[] = $week35End;
        $weeksEnd[] = $week36End;
        $weeksEnd[] = $week37End;
        $weeksEnd[] = $week38End;
        $weeksEnd[] = $week39End;
        $weeksEnd[] = $week40End;
        $weeksEnd[] = $week41End;

        // Whole league time-zone


        $test_week = 50;
        // On new round update the users their records.
        if($current_week !== $game_id->week) {

            // Onchange of week you can vote again.
            session()->forget('chooseTeam');

            // Update week/round.
            $game_id->week = $current_week;
            $game_id->save();

            $teams_in_previous_week = [];
            for($row = 0; $row < count($league); $row++) {
                for($col = 0; $col < count($league[0]); $col++) {
                    // Minus one because you need to have the teams from the previous week. Unless you do the update of the week after this, then remove it.
                    if(end($league[$row]) - 1 == $current_week) {
                        $teams_in_previous_week[] = $league[$row];
                    }
                }
            }

            $round = $teams_in_previous_week[0];

            $outcome_in_previous_week = [];
            for($row = 0; $row < count($outcome); $row++) {
                for($col = 0; $col < count($outcome[0]); $col++) {
                    if(end($outcome[$row]) - 1 == $current_week) {
                        $outcome_in_previous_week[] = $outcome[$row];
                    }
                }
            }

            $round_outcome = $outcome_in_previous_week[0];
            // If it's true, the team value in the even key number has won. If false, the odd key number lost.
            $comp1 = $round_outcome[0] < $round_outcome[1];
            $comp2 = $round_outcome[2] < $round_outcome[3];
            $comp3 = $round_outcome[4] < $round_outcome[5];
            $comp4 = $round_outcome[6] < $round_outcome[7];
            $comp5 = $round_outcome[8] < $round_outcome[9];
            $comp6 = $round_outcome[10] < $round_outcome[11];

            // Iterate through all players their choice, compare that with the victory team of each competition.
            for($users = 0; $users < count($allPlayers); $users++) {
                $user_choice = $game_id->users[$users]->pivot->team;
                $user_id = $game_id->users[$users]->id;

                if($comp1) {
                    if($user_choice == $round[0]) {
                        $game_id->users()->updateExistingPivot(['user_id' => $user_id], ['chosen' => 0, 'team' => ' ']);
                    }
                } else {
                    if($user_choice == $round[1]) {
                        $game_id->users()->updateExistingPivot(['user_id' => $user_id], ['chosen' => 0, 'team' => ' ', 'out' => 1]);
                    }
                }

                if($comp2) {
                    if($user_choice == $round[2]) {
                        $game_id->users()->updateExistingPivot(['user_id' => $user_id], ['chosen' => 0, 'team' => ' ']);
                    }
                } else {
                    if($user_choice == $round[3]) {
                        $game_id->users()->updateExistingPivot(['user_id' => $user_id], ['chosen' => 0, 'team' => ' ', 'out' => 1]);
                    }
                }

                if($comp3) {
                    if($user_choice == $round[4]) {
                        $game_id->users()->updateExistingPivot(['user_id' => $user_id], ['chosen' => 0, 'team' => ' ']);
                    }
                } else {
                    if($user_choice == $round[5]) {
                        $game_id->users()->updateExistingPivot(['user_id' => $user_id], ['chosen' => 0, 'team' => ' ', 'out' => 1]);
                    }
                }

                if($comp4) {
                    if($user_choice == $round[6]) {
                        $game_id->users()->updateExistingPivot(['user_id' => $user_id], ['chosen' => 0, 'team' => ' ']);
                    }
                } else {
                    if($user_choice == $round[7]) {
                        $game_id->users()->updateExistingPivot(['user_id' => $user_id], ['chosen' => 0, 'team' => ' ', 'out' => 1]);
                    }
                }

                if($comp5) {
                    if($user_choice == $round[8]) {
                        $game_id->users()->updateExistingPivot(['user_id' => $user_id], ['chosen' => 0, 'team' => ' ']);
                    }
                } else {
                    if($user_choice == $round[9]) {
                        $game_id->users()->updateExistingPivot(['user_id' => $user_id], ['chosen' => 0, 'team' => ' ', 'out' => 1]);
                    }
                }

                if($comp6) {
                    if($user_choice == $round[10]) {
                        $game_id->users()->updateExistingPivot(['user_id' => $user_id], ['chosen' => 0, 'team' => ' ']);
                    }
                } else {
                    if($user_choice == $round[11]) {
                        $game_id->users()->updateExistingPivot(['user_id' => $user_id], ['chosen' => 0, 'team' => ' ', 'out' => 1]);
                    }
                }
            }

            $users_out = [];
            for ($i = 0; $i < count($allPlayers); $i++) {
                $user_out = $game_id->users[$i]->out;
                $user_id = $game_id->users[$i]->user_id;

                // Check which users are out and put them in an array for counter check below.
                if ($user_out == 1) {
                    $users_out[] = $user_id;
                }

                // If there is only one player left add a point to this user his record. Reset game.
                if (count($users_out) <= count($allPlayers)) {
                    if ($game_id->users[$i]->out == 0) {
                        $game_id->users()->updateExistingPivot(['user_id' => $user_id], ['point' => + 1, 'chosen' => 0, 'team' => ' ', 'out' => 0]);
                    }

                    if ($game_id->users[$i]->out == 1) {
                        $game_id->users()->updateExistingPivot(['user_id' => $user_id], ['chosen' => 0, 'team' => ' ', 'out' => 0]);
                    }
                    // Reset chosen team record. So that any team can be chosen again by the user.
                    session()->forget('chosenTeamRecord');
                }


                /*
                 * 1. User needs to have a record of chosen teams. How else are you going to check whether an user already has chosen this certain team in a game?
                 * 2. Put team choices in session and forget this session on reset of game, which happens when points are being given and chosen and out are being reset to 0.
                 *
                 * */
            }
        }


        // If there are less then 2 players in a game, disable vote option.
        if(count($allPlayers) < 2) {
            $tooLittlePlayers = true;
        } else {
            $tooLittlePlayers = false;
        }

        // Check whether user exists in selected game.
        for ($i = 0; $i < count($allPlayers); $i++) {
            if ($game_id->users[$i]->id == $user_id) {

                // Remove success message on refresh.
                $pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';

                if($pageWasRefreshed ) {
                    session()->forget('rightLink');
                }

                return view('game')->with(
                    [
                        'user_id' => $user_id, 'allPlayers' => $allPlayers,
                        'game' => $game_id, 'uuid' => $game_id->link, 'game_name' => $game_id->name,
                        'outcome' => $outcome, 'league' => $league,
                        'current_week' => $current_week, 'weeksStart' => $weeksStart,
                        'weeksEnd' => $weeksEnd, 'tooLittlePlayers' => $tooLittlePlayers,
                        'weekOne' => $week1, 'weekTwo' => $week2, 'weekThree' => $week3, 'weekFour' => $week4
                    ]
                );
            }
        }
        return redirect()->route('invitation', ['id' => $current_game_id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $name
     * @param $link
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $name = $request->input('gameName');
        $uuid = session('uuid');

        if(!isset($name)) {
            $message = 'Vul alstublieft een naam in voor het spel.';
            return view('creategame')->with(['emptyName' => $message, 'uuid' => $uuid]);
        }

        $current_week = Carbon::now()->week;

        // Create the game with the corresponding attributes.
        Game::create(['name' => $name, 'link' => $uuid, 'week' => $current_week]);

        // Get this just generated game.
        $user_id = auth()->user()->id;
        $game_id = Game::all()->last()->id;
        $populateGame = Game::find($game_id);

        // Make logged in user admin of this generated game.
        $populateGame->users()->attach(['user_id' => $user_id], ['admin' => 1, 'point' => 0, 'invited' => 0]);

        // Add invited users to this game.
        $lobby = session('lobby');
        $madeGame_id = Game::all()->last()->id;

        // Check whether somebody was added.
        if($lobby !== null) {
            foreach ($lobby as $user_id => $invited) {
                $user = User::find($user_id);
                $user->games()->attach(['user_id' => $user_id, 'game_id' => $madeGame_id], ['admin' => 0, 'point' => 0, 'invited' => $invited]);
            }
        }

        session()->forget(['uuid', 'lobby']);

        $allGames = Game::all();
        $success = 'Succesvol spel ' . $allGames->last()->name . ' gemaakt.';

        return view('home')->with(['success' => $success, 'games' => $allGames]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource. Show create game page.
     * Save uuid in session so that it won't be renewed until used.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Game $game)
    {
        // Generate random link for the game
        $generateUuid = Str::uuid();
        $uuid = $generateUuid;

        $sessionUuid = session('uuid');

        // Store uuid in session so that it won't change on refresh.
        if(!isset($sessionUuid)) {
            session(['uuid' => $uuid]);
        }

        // If uuid wasn't generated, generate one.
        if($sessionUuid == null) {
            session(['uuid' => $uuid]);
        }

        $session = session('uuid');

        return view('creategame')->with('uuid', $session);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage. Update game name or/and lobby.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Game  $game
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Game $game)
    {
        $lobby = session('lobbyExistingGame');
        $game_id = $request->route('id');
        $name = $request->input('game_name');

        // Save new name of the game.
        $game = Game::find($game_id);
        $game->name = $name;
        $game->save();

        // Save new users to the game.
        if (isset($lobby)) {
            foreach ($lobby as $user_id => $invited) {
                $user = User::find($user_id);
                $user->games()->attach(['game_id' => $game_id], ['admin' => 0, 'point' => 0, 'invited' => $invited]);
            }
        }

        // Remove messages given to blade
        session()->forget(['lobbyExistingGame', 'saveNotice', 'succesfulDeleteOfUser']);

        return redirect()->route('game', ['id' => $game_id]);
    }

    // Handle user wanting to join a game through invitation link.
    public function invitation(Request $request) {
        $user_id = auth()->user()->id;
        $checkLink = $request->input('invitation');
        $current_game_id = $request->route('id');

        $wrongLink = 'De ingevoerde link is onjuist.';

        // Check if field is empty
        if(isset($checkLink)) {
            $check = Game::where('link', $checkLink)->get()->first();

            // Check if the game->link is found
            if(isset($check->link)) {
                // Check if the game->link is correct
                if ($check->link == $checkLink) {
                    // If the link is correct add the user to this game.
                    $check->users()->attach(['user_id' => $user_id], ['admin' => 0, 'point' => 0, 'invited' => 1]);
                    session(['rightLink' => true]);

                    return redirect()->route('game', ['id' => $current_game_id]);
                } else {
                    return view('invitation')->with('wrongLink', $wrongLink);
                }
            } else {
                return view('invitation')->with('wrongLink', $wrongLink);
            }
        } else {
            return view('invitation')->with('wrongLink', $wrongLink);
        }
    }

    /**
     * Remove the specified resource from storage.
     * Destroy specific game.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $game_id = $request->route('id');
        $game = Game::find($game_id);
        // Detach all users from the game.
        $game->users()->detach();
        // Delete the actual game.
        $game->delete();

        return redirect()->route('home');
    }

    /**
     * Remove the specified resource from storage.
     * Destroy specific user from certain game.
     *
     * @param $user_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyUser(Request $request)
    {
        $current_game_id = $request->route('id');
        $user_id = $request->route('user_id');
        $user = User::find($user_id);

        // Detach certain user from game > game_id
        $user->games()->detach($current_game_id);

        session(['succesfulDeleteOfUser' => true]);

        return redirect()->route('game', ['id' => $current_game_id]);
    }

    // Handle adding users when creating game.
    public function addUser(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->get()->first();
        $uuid = session('uuid');

        // Check whether the filled in user email has an account.
        if (isset($user)) {
            // Check whether you try to invite yourself.
            if ($user->id == auth()->user()->id) {
                // Unable to invite yourself.
                $self = 'U kan uzelf niet toevoegen.';
                return view('creategame')->with(['self' => $self, 'uuid' => $uuid]);
            } else {
                // Add invited user in session.
                $session = session('lobby');

                // Prevent from adding two same users.
                if (isset($session[$user->id])) {
                    $alreadyInvited = $user->name . ' is al toegevoegd.';
                    return view('creategame')->with(['alreadyInvited' => $alreadyInvited, 'uuid' => $uuid]);
                } else {
                    $session[$user->id] = 1;
                }

                session(['lobby' => $session]);
                return view('creategame')->with(['user' => $user->name, 'uuid' => $uuid]);
            }
        } else {
            // User not found.
            $nope = 'Gebruiker kan niet gevonden worden.';
            return view('creategame')->with(['nope' => $nope, 'uuid' => $uuid]);
        }
    }

    // Add user through email from an existing game.
    public function addUserExistingGame(Request $request) {
        $email = $request->input('email');
        $user = User::where('email', $email)->get()->first();
        $game_id = $request->route('id');
        $game = Game::find($game_id);

        // Check whether the filled in user email has an account.
        if (isset($user)) {
            // Check whether you try to invite yourself.
            if ($user->id == auth()->user()->id) {
                // Unable to invite yourself.
                $self = 'U kan uzelf niet toevoegen.';
                return view('game')->with(['self' => $self, 'user_id' => auth()->user()->id, 'allPlayers' => $game->users, 'game' => $game, 'uuid' => $game->link, 'game_name' => $game->name]);
            } else {
                // Add invited user in session.
                $session = session('lobbyExistingGame');

                // Prevent from adding two same users.
                if (isset($session[$user->id])) {
                    $alreadyInvited = $user->name . ' is al toegevoegd, klik op opslaan.';
                    return view('game')->with(['alreadyInvited' => $alreadyInvited, 'user_id' => auth()->user()->id, 'allPlayers' => $game->users, 'game' => $game, 'uuid' => $game->link, 'game_name' => $game->name]);
                } else {
                    $check = User::where('email', $email)->get()->first()->games->where('id', $game_id)->first();

                    if (isset($check)) {
                        // User already exists in this game.
                        $userExistsInGame = 'De gebruiker van de ingevoerde email is al in dit spel.';
                        return view('game')->with(['userExistsInGame' => $userExistsInGame, 'user_id' => auth()->user()->id, 'allPlayers' => $game->users, 'game' => $game, 'uuid' => $game->link, 'game_name' => $game->name]);
                    } else {
                        // IT'S NULL, UNSET. So user doesn't exist in the game.
                        $session[$user->id] = 1;
                        session(['saveNotice' => true]);
                    }
                }

                session(['lobbyExistingGame' => $session]);
                return redirect()->route('game', ['id' => $game_id]);
            }
        } else {
            // User not found.
            $nope = 'Gebruiker kan niet gevonden worden.';
            return view('game')->with(['nope' => $nope, 'user_id' => auth()->user()->id, 'allPlayers' => $game->users, 'game' => $game, 'uuid' => $game->link, 'game_name' => $game->name]);
        }
    }

    // Handle the voting of a team.
    public function voteTeam($id, Request $request) {
        $user_id = auth()->user()->id;
        $game = Game::find($id);

        // Grab the chosen team by the defined name in blade.
        $chosenTeam = $request->input('team');

        // Not chosen a team? Give error message.
        if(!isset($chosenTeam)) {
            session(['chooseTeam' => true]);
            return redirect()->back();
        }
        session()->forget('chooseTeam');

        session(['chosenTeamRecord' => $chosenTeam]);

        // Update user record to chosen true.
        $game->users()->updateExistingPivot(['user_id' => $user_id], ['chosen' => 1, 'team' => $chosenTeam]);

        return redirect()->route('game', ['id' => $game->id]);
    }
}
