<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 01-Apr-19
 * Time: 5:15 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    public $timestamps = false;
    protected $fillable = ["id", "commentator", "spectator", "admin", "videographer", "description", "event_id"];

    protected $attributes = ["commentator" => 0, "spectator" => 0, "admin" => 0, "videographer" => 0, "open" => 1];
}