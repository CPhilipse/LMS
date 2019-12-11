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
Route::post('spel/{id}/invite','GameController@addUserExistingGame')->name('addUserExistingGame');
Route::post('spel/{id}/wijzigingen-spel','GameController@update')->name('updateGame');
Route::get('spel/{id}/verwijderen/{user_id}','GameController@destroyUser')->name('deleteUser');
Route::get('spel/{id}/verwijderen','GameController@destroy')->name('deleteGame');
Route::post('spel/{id}/vote','GameController@voteTeam')->name('voteTeam');

Route::get('/overzicht', 'HomeController@overview')->name('overview');
