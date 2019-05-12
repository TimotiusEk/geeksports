<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 10-Apr-19
 * Time: 3:02 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class SponsorshipManagement extends Model
{
    public $timestamps = false;
    protected $fillable = ["id", "action", "company_id", "event_id"];
}