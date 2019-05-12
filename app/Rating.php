<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 25-Apr-19
 * Time: 4:41 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'rating', 'user_id', 'event_id'];
}