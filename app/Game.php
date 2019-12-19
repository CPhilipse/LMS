<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    // Laravel takes timestamps by default with, even though it is not added in the migration. By setting it to false, laravel will ignore it.
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'link', 'week'
    ];

    // Users can have many games
    public function users () {
        // $this game belongs to many users. withPivot so these attributes will be accessible from the controller
        return $this->belongsToMany('App\User','App\GameRecord')->withPivot('user_id', 'game_id', 'admin', 'invited', 'point', 'chosen', 'out', 'team');
    }
}
