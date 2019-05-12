<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 01-Apr-19
 * Time: 4:49 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class EventOrganizer extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'id', 'display_name', 'profile_picture', 'contact_person', 'company_name', 'user_id'
    ];
}