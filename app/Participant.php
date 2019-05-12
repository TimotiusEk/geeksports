<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 31-Mar-19
 * Time: 3:52 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'participant_id', 'team_id'];
}