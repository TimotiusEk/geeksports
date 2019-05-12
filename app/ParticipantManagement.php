<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 31-Mar-19
 * Time: 3:58 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParticipantManagement extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'event_id', 'event_game_id','gamer_id', 'team_id', 'action'];
}