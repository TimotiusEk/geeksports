<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 26-Apr-19
 * Time: 9:17 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'gamer_id', 'event_game_id', 'type'];
}