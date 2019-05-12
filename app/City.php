<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 05-Apr-19
 * Time: 9:36 AM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['id', 'name'];
}