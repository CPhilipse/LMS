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

        session()->forget(['lobbyExistingGame']);

        return redirect()->route('game', ['id' => $game_id]);
    }

    // Handle user wanting to join a game.
    public function invitation(Request $request) {
        $user_id = auth()->user()->id;
        $checkLink = $request->input('invitation');
        $current_game_id = $request->route('id');

        $wrongLink = 'De ingevoerde link is onjuist.';
        $rightLink = 'Welkom bij het spel!';

        $game_id = $request->route('id');
        $game = Game::find($game_id);

        if(isset($checkLink)) {
            $check = Game::where('link', $checkLink)->get()->first();

            if(isset($check->link)) {
                if ($check->link == $checkLink) {
//                    $allPlayers = $check->users;
                    $check->users()->attach(['user_id' => $user_id], ['admin' => 0, 'point' => 0, 'invited' => 1]);
                    session(['rightLink' => true]);

                    return redirect()->route('game', ['id' => $current_game_id]);

//                    return view('game')->with(['rightLink' => $rightLink, 'id' => $current_game_id, 'game' => $check, 'allPlayers' => $allPlayers, 'user_id' => $user_id, 'uuid' => $game->link, 'game_name' => $game->name]);
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
                    }
                }

                session(['lobbyExistingGame' => $session]);
                return view('game')->with(['user_id' => auth()->user()->id, 'allPlayers' => $game->users, 'game' => $game_id, 'uuid' => $game->link, 'game_name' => $game_id->name]);
            }
        } else {
            // User not found.
            $nope = 'Gebruiker kan niet gevonden worden.';
            return view('game')->with(['nope' => $nope, 'user_id' => auth()->user()->id, 'allPlayers' => $game->users, 'game' => $game, 'uuid' => $game->link, 'game_name' => $game->name]);
        }
    }
}
