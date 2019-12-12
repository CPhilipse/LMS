<?php

namespace App\Http\Controllers;

use App\Game;
use App\User;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
//use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     * Load the game or the invitation page if user hasn't been invited yet.
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
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3"],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3"],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3"],
                ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3"],
            ];

        $league =
            [
                ["Team 1", "Team 2", "Team 3", "Team 4", "Team 5", "Team 6", "Team 7", "Team 8", 49],
                ["Team 1", "Team 2", "Team 3", "Team 4", "Team 5", "Team 6", "Team 7", "Team 8", 50],
                ["Team 1", "Team 2", "Team 3", "Team 4", "Team 5", "Team 6", "Team 7", "Team 8", 51],
                ["Team 1", "Team 2", "Team 3", "Team 4", "Team 5", "Team 6", "Team 7", "Team 8", 52],
            ];

// In the end:: try this league with this: https://stackoverflow.com/questions/1551822/looping-a-multidimensional-array-in-php/24457508
//        $league =
//            [
//                ["PEC", "Willem II", "Emmen", "Groningen", "Vitesse", "Ajax", "Twente", "PSV", "VVV",
//                    "RKC", "Heracles", "Heerenveen", "Feyenoord", "Sparta", "ADO", "Utrecht", "AZ", "Fortuna", 49],
//                ["Sparta", "VVV", "Groningen", "Twente", "Ajax", "Emmen", "Willem II", "Vitesse", "Fortuna",
//                    "Heracles", "Heerenveen", "Feyenoord", "RKC", "AZ", "Utrecht", "PEC", "PSV", "ADO", 50],
//                ["Vitesse", "PEC", "VVV", "Ajax", "ADO", "Sparta", "Emmen", "Heerenveen",
//                    "Fortuna", "Willem II", "Twente", "RKC", "AZ", "Groningen", "Feyenoord", "Utrecht", "Heracles", "PSV", 51],
//                ["Team 1", "Team 2", "Team 3", "Team 4", "Team 5", "Team 6", "Team 7", "Team 8", 52],
//            ];

        $game_id = Game::find($id);

        $allPlayers = $game_id->users;

        $user_id = auth()->user()->id;

        $current_game_id = $request->route('id');

        // Show time for each round for more clarity on the round time frame. Timer > Vue component.
        $current_week = Carbon::now()->week;

        // https://weeknummers.com/weeknummers/2019/
        $week1date = Carbon::create(2019, 12, 2);
        $week2date = Carbon::create(2019, 12, 9);
        $week3date = Carbon::create(2019, 12, 16);
        $week4date = Carbon::create(2019, 12, 23);
        $week1 = $week1date->toFormattedDateString();
        $week2 = $week2date->toFormattedDateString();
        $week3 = $week3date->toFormattedDateString();
        $week4 = $week4date->toFormattedDateString();

        $weeksStart = [];
        $weeksStart[] = $week1;
        $weeksStart[] = $week2;
        $weeksStart[] = $week3;
        $weeksStart[] = $week4;

        $week1dateEnd = Carbon::create(2019, 12, 8);
        $week2dateEnd = Carbon::create(2019, 12, 15);
        $week3dateEnd = Carbon::create(2019, 12, 22);
        $week4dateEnd = Carbon::create(2019, 12, 29);
        $week1End = $week1dateEnd->toFormattedDateString();
        $week2End = $week2dateEnd->toFormattedDateString();
        $week3End = $week3dateEnd->toFormattedDateString();
        $week4End = $week4dateEnd->toFormattedDateString();

        $weeksEnd = [];
        $weeksEnd[] = $week1End;
        $weeksEnd[] = $week2End;
        $weeksEnd[] = $week3End;
        $weeksEnd[] = $week4End;

        // Every user that goes in a game, it'll update this data for each user separately. Not all at once, which is fine.
        // Because it will be updated for every user anyway.
        // Update week when round/week is over, empty chosen and team so users can choose again, unless they are out.
        // Also check whether the user is out based on the outcome.
        $test_week = 50;
        if($test_week !== $game_id->week) {
            // Onchange of week you can vote again.
            session()->forget('chooseTeam');

            // Update week works*
            $game_id->week = $test_week;
            $game_id->save();

            // check outcome. - outcome[0] will be the outcome for team one and vice versa. Iterate through the outcomes and teams.
            // In the interation/loop if($team_in_db_of_this_user == $team_in_loop) { which will only be true if it .. }
            // if($outcome[0] < $outcome[1]) {
            // $team_won = $league[0];
            // $game_id->users()->updateExistingPivot(['user_id' => $user_id], ['chosen' => 0, 'team' => ' ']);
            // } else {
            // $team_lost = $league[1]
            // $game_id->users()->updateExistingPivot(['user_id' => $user_id], ['chosen' => 0, 'team' => ' ', 'out' => 1]);
            // }



            // Everyone got it right | TEST
            // Update pivot data works*
            $game_id->users()->updateExistingPivot(['user_id' => $user_id], ['chosen' => 0, 'team' => ' ']);

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
//        dd($current_week);
        Game::create(['name' => $name, 'link' => $uuid, 'week' => $current_week]);

        $user_id = auth()->user()->id;
        $game_id = Game::all()->last()->id;
        $populateGame = Game::find($game_id);

        $populateGame->users()->attach(['user_id' => $user_id], ['admin' => 1, 'point' => 0, 'invited' => 0]);

        // Add invited users to the game.
        $lobby = session('lobby');
        $madeGame_id = Game::all()->last()->id;
//        dd($lobby);
        if($lobby !== null) {
            foreach ($lobby as $user_id => $invited) {
                $user = User::find($user_id);
                $user->games()->attach(['user_id' => $user_id, 'game_id' => $madeGame_id], ['admin' => 0, 'point' => 0, 'invited' => $invited]);
            }
        }

        session()->forget(['uuid', 'lobby']);

        $success = 'Succesvol spel ' . Game::all()->last()->name . ' gemaakt.';
        $allGames = Game::all();

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

        // Store uuid in session so that it won't change for this game when adding users through email.
        if(!isset($sessionUuid)) {
            session(['uuid' => $uuid]);
        }

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
     * Update the specified resource in storage.
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

        $current_week = Carbon::now()->week;

        // Save new users to the game.
        if (isset($lobby)) {
            foreach ($lobby as $user_id => $invited) {
                $user = User::find($user_id);
                $user->games()->attach(['game_id' => $game_id], ['admin' => 0, 'point' => 0, 'invited' => $invited]);
            }
        }

        session()->forget(['lobbyExistingGame', 'saveNotice', 'succesfulDeleteOfUser']);

        return redirect()->route('game', ['id' => $game_id]);
    }

    // Handle user wanting to join a game.
    public function invitation(Request $request) {
        $user_id = auth()->user()->id;
        $checkLink = $request->input('invitation');
        $current_game_id = $request->route('id');
        $current_week = Carbon::now()->week;

        $wrongLink = 'De ingevoerde link is onjuist.';

        if(isset($checkLink)) {
            $check = Game::where('link', $checkLink)->get()->first();

            if(isset($check->link)) {
                if ($check->link == $checkLink) {
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
            return view('invitation');
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

    public function addUser(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->get()->first();
        $uuid = session('uuid');

        if (isset($user)) {
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

        if (isset($user)) {
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

        // Update user record to chosen true.
        $game->users()->updateExistingPivot(['user_id' => $user_id], ['chosen' => 1, 'team' => $chosenTeam]);

        return redirect()->route('game', ['id' => $game->id]);
    }
}
