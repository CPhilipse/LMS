<?php

namespace App\Http\Controllers;

use App\Game;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\Input;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id)
    {
        $game_id =  Game::find($id);

        $allPlayers = $game_id->users;

        $user_id = auth()->user()->id;

        return view('game')->with(['user_id' => $user_id, 'allPlayers' => $allPlayers, 'game' => $game_id]);
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
//        $uuid = json_encode($generateUuid) . intval( "0" . rand(1,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) );
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        //
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

    public function addUser(Request $request) {
//        dd(session('lobby'));
        $email = $request->input('email');
        $user = User::where('email', $email)->get()->first();
        $uuid = session('uuid');

        if(isset($user)) {
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
            $nope = 'nope';
            return view('creategame')->with(['nope' => $nope, 'uuid' => $uuid]);
        }
    }
}
