<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 01-Apr-19
 * Time: 5:44 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'display_name', 'description', 'profile_picture','user_id'];
}