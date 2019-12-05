<?php

namespace App\Http\Controllers;

use App\Game;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request, $id)
    {
        $game_id = Game::find($id);

        $allPlayers = $game_id->users;

        $user_id = auth()->user()->id;

        $current_game_id = $request->route('id');


//        $check = User::where('id', $user_id)->get()->first();
//        $check = $allPlayers

//        if ($check->id == $user_id) {
//            $allPlayers = $check->users;
//            return view('game')->with(['rightLink' => $rightLink, 'id' => $current_game_id, 'game' => $check, 'allPlayers' => $allPlayers]);
//        } else {
//            return view('invitation')->with('wrongLink', $wrongLink);
//        }

        // Check whether user exists in selected game.
        for ($i = 0; $i < count($allPlayers); $i++) {
            if ($game_id->users[$i]->id == $user_id) {
                return view('game')->with(['user_id' => $user_id, 'allPlayers' => $allPlayers, 'game' => $game_id, 'uuid' => $game_id->link, 'game_name' => $game_id->name]);
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
        Game::create(['name' => $name, 'link' => $uuid]);

        $user_id = auth()->user()->id;
        $game_id = Game::all()->last()->id;
        $populateGame = Game::find($game_id);

        $populateGame->users()->attach(['user_id' => $user_id], ['admin' => 1, 'point' => 0, 'invited' => 0]);

        // Add invited users to the game.
        $lobby = session('lobby');
        $madeGame_id = Game::all()->last()->id;

        if(isset($lobby)) {
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
     * Display the specified resource.
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
        $lobby = session('lobby');
        $game_id = $request->route('id');
        $name = $request->input('game_name');

        // Save new name
        $game = Game::find($game_id);
        $game->name = $name;
        $game->save();

        if (isset($lobby)) {
            foreach ($lobby as $user_id => $invited) {
                $user = User::find($user_id);
                $user->games()->attach(['user_id' => $user_id, 'game_id' => $game_id], ['admin' => 0, 'point' => 0, 'invited' => $invited]);
            }
        }

        session()->forget(['lobby']);

        return redirect()->route('game', ['id' => $game_id]);
    }

    // Handle user wanting to join a game.
    public function invitation(Request $request) {
        $user_id = auth()->user()->id;
        $checkLink = $request->input('invitation');
        $current_game_id = $request->route('id');

        $wrongLink = 'De ingevoerde link is onjuist.';
        $rightLink = 'Welkom bij het spel!';

        if(isset($checkLink)) {
            $check = Game::where('link', $checkLink)->get()->first();

            if(isset($check->link)) {
                if ($check->link == $checkLink) {
                    $allPlayers = $check->users;

                    $check->users()->attach(['user_id' => $user_id], ['admin' => 0, 'point' => 0, 'invited' => 1]);
                    return view('game')->with(['rightLink' => $rightLink, 'id' => $current_game_id, 'game' => $check, 'allPlayers' => $allPlayers]);
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function destroy(Game $game)
    {
        $game = Game::find($game);
        // Detach all users from the game.
        $game->users()->detach();

        $message = 'delete';
        return view('game')->with('delete', $message);
    }

    /**
     * Remove the specified resource from storage.
     * Destroy specific user from certain game.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function destroyUser($id, $game_id)
    {
        $user = User::find($id);
        // Detach certain user from game > game_id
        $user->games()->detach($game_id);

        $message = 'delete';
        return view('game')->with('deleteUser', $message);
    }

    public function addUser(Request $request)
    {
//        dd(session('lobby'));
        $email = $request->input('email');
        $user = User::where('email', $email)->get()->first();
        $uuid = session('uuid');
        $game_id = $request->route('id');
        $game = Game::find($game_id);

        if (isset($user)) {
            if ($user->id == auth()->user()->id) {
                // Unable to invite yourself.
                $self = 'U kan uzelf niet toevoegen.';
                if(isset($game_id)) {
                    return view('game')->with(['self' => $self, 'user_id' => auth()->user()->id, 'allPlayers' => $game->users, 'game' => $game, 'uuid' => $game->link, 'game_name' => $game->name]);
                } else {
                    return view('creategame')->with(['self' => $self, 'uuid' => $uuid]);
                }
            } else {
                // Add invited user in session.
                $session = session('lobby');

                // Prevent from adding two same users.
                if (isset($session[$user->id])) {
                    $alreadyInvited = $user->name . ' is al toegevoegd.';
                    if(isset($game_id)) {
                        return view('game')->with(['alreadyInvited' => $alreadyInvited, 'user_id' => auth()->user()->id, 'allPlayers' => $game->users, 'game' => $game, 'uuid' => $game->link, 'game_name' => $game->name]);
                    }
                } else {
                    $session[$user->id] = 1;
                }

                session(['lobby' => $session]);
                if(isset($game_id)) {
                    if(isset($user)) {
                        return view('game')->with(['user' => $user->name, 'user_id' => auth()->user()->id, 'allPlayers' => $game->users, 'game' => $game_id, 'uuid' => $game->link, 'game_name' => $game_id->name]);
                    } else {
                        dd('die');
                        return view('game')->with(['user_id' => auth()->user()->id, 'allPlayers' => $game->users, 'game' => $game_id, 'uuid' => $game->link, 'game_name' => $game_id->name]);
                    }
                } else {
                    return view('creategame')->with(['user' => $user->name, 'uuid' => $uuid]);
                }
            }
        } else {
            // User not found.
            $nope = 'Gebruiker kan niet gevonden worden.';
            if(isset($game_id)) {
                return view('game')->with(['nope' => $nope, 'user_id' => auth()->user()->id, 'allPlayers' => $game->users, 'game' => $game, 'uuid' => $game->link, 'game_name' => $game->name]);
            } else {
                return view('creategame')->with(['nope' => $nope, 'uuid' => $uuid]);
            }
        }
    }

    // Put the code for well adding user into existing game from the above function in here.
    public function addUserExistingGame() {

    }
}
