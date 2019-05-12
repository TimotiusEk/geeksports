<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 01-Apr-19
 * Time: 5:15 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class UserSubroleConnector extends Model
{
    public $timestamps = false;
    protected $table = "users_subroles";
    protected $fillable = ["id", "user_id", "subrole_id"];
}