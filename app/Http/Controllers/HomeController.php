<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $allGames = Game::all();
//        dd($allGames);

        return view('home')->with('games', $allGames);
    }

    public function overview() {
        $adminGames = [];
        $invitedGames = [];

        foreach(auth()->user()->games as $game) {

            if($game->pivot->admin == 1) {
                $adminGames[] = $game;
            }
            if($game->pivot->invited == 1) {
                $invitedGames[] = $game;
            }
        }

        return view('overview')->with(['adminGames' => $adminGames, 'invitedGames' => $invitedGames]);
    }
}
