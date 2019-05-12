<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 01-Apr-19
 * Time: 4:49 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'id', 'name', 'city_id', 'start_date', 'end_date', 'details', 'status','brochure', 'event_organizer_id', 'participant_registration'
    ];
}