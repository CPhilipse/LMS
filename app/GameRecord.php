<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameRecord extends Model
{
    // Laravel takes timestamps by default with, even though it is not added in the migration. By setting it to false, laravel will ignore it.
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'game_id', 'admin', 'point', 'invited', 'chosen', 'out', 'team'
    ];
}
