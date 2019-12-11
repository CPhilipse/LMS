<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // User (model/entity) has many games
    public function games () {
        // This user belongs to many games through the GameRecord.
        return $this->belongsToMany('App\Game', 'App\GameRecord')->withPivot('user_id', 'game_id', 'admin', 'invited', 'point', 'chosen', 'out');
    }

    public function isAdmin()
    {
        $isAdmin = false;
//        dd($this->games);
//        $isAdmin = !$this->roles->filter(function($item) {
//            return $item->role == 'admin';
//        })->isEmpty();

        $isAdmin = $this->games->filter(function($user) {
            dd($user);
            return empty(!$user->pivot->admin);
            // show the games where the logged in user is not admin of.
            // Now foreach through this array and return the game with the current game_id
            // Then grab the user_id of this game.
        });

        return $isAdmin;
    }
}
