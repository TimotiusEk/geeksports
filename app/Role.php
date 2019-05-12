<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 01-Apr-19
 * Time: 3:27 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['id', 'name'];
}