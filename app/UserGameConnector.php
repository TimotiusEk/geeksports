<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 01-Apr-19
 * Time: 5:15 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class UserGameConnector extends Model
{
    public $timestamps = false;
    protected $table = "users_games";
    protected $fillable = ["id", "user_id", "game_id"];
}