<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 26-Apr-19
 * Time: 9:16 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Winner extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'event_game_id', 'win_type', 'team_id'];
}