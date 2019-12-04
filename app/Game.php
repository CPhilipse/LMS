<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'link',
    ];

    // Users can have many games
    public function users () {
        // $this game belongs to many users.
        return $this->belongsToMany('App\User','App\GameRecord')->withPivot('user_id', 'game_id', 'admin', 'invited', 'point');
    }
}
