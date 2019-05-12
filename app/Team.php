<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 21-Apr-19
 * Time: 1:33 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'team_name', 'team_logo'];
}