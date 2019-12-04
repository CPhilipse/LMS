<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'HomeController@index')->name('home');

Route::post('nieuw-spel','GameController@create')->name('createGame');
Route::get('maak-nieuw-spel','GameController@show')->name('showGame');
Route::post('maak-nieuw-spel-invite','GameController@addUser')->name('addUser');

//Route::get('spel/{id}/invitatie','GameController@invitation')->name('invitation');
//Route::post('spel/check-link', 'GameController@checkLink')->name('checkLink');
Route::get('spel/{id}/invitatie','GameController@invitation')->name('invitation');
Route::get('spel/{id}','GameController@index')->name('game');
Route::get('spel/{id}/verwijderen','GameController@destroy')->name('deleteUser');

Route::get('/overzicht', 'HomeController@overview')->name('overview');

/*
         * Create a game.
         * $game = Game::create(['name' => $filledInName, 'link' => $autoGeneratedLink];
         * $user = Auth::user()->id;
         * // Fill pivot table
         * $game->users()->attach(['user_id' => $user, 'game_id' => $game['id'], 'admin' => true, 'point' => 0, 'invited' => 0]);
         *
         * Add invited users in the session.
         * $findUser = User::findOrFail($email); // $email of user filled in, not sure if it works like this.
         * $session = session('inviteUsers');
         * $user = $findUser->id;
         *
         * if (isset($session[$id])) {
         *  $session[$user['id']]++;
         * } else {
         *  $session[$user['id']] = 1;
         * }
         *
         * session(['pool' => $session]);
         *
         * Add invited users to the database from the session.
         * $poolEmail = session('inviteUsers');
         * $existingGameId = Game::find($id);
         * $gameId = $game['id'] === null ? $existingGameId : $game['id'];
         * foreach($poolEmail as $number => $userId) {
         *  $user->game()->attach(['user_id' => $userId, 'game_id' => $gameId, 'admin' => false, 'point' => 0, 'invited' => true]);
         * OR?
         *  $user->game()->attach(['user_id' => $userId],['game_id' => $gameId, 'admin' => false, 'point' => 0, 'invited' => true]);
         * }
         *
         *  Return view('...')->with('message', $message);
         *
         */
