<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 10-May-19
 * Time: 5:01 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class EventLocationMedia extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'event_location_id', 'event_location_picture', 'picture_description'];
}