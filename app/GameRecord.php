<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameRecord extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'game_id', 'admin', 'point', 'invited', 'chosen', 'out'
    ];
}
