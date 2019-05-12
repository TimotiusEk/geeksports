<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 10-May-19
 * Time: 5:00 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class EventLocation extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'name', 'logo', 'city_id', 'contact_person', 'gmaps_url'];
}