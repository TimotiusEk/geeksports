<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 04-Apr-19
 * Time: 5:09 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventGameConnector extends Model
{
    public $timestamps = false;
    protected $table = "events_games";
    protected $fillable = [
        'id', 'event_id', 'game_id'
    ];
}