<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 06-Apr-19
 * Time: 5:48 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class SponsorshipPackage extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'package_name', 'sponsor_rights', 'sponsor_obligations', 'event_id'];
}