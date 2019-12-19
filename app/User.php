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
        // This user belongs to many games through the GameRecord. withPivot so these attributes will be accessible from the controller
        return $this->belongsToMany('App\Game', 'App\GameRecord')->withPivot('user_id', 'game_id', 'admin', 'invited', 'point', 'chosen', 'out', 'team');
    }
}
