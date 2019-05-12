<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 24-Apr-19
 * Time: 1:56 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['id', 'comment', 'user_id', 'event_id'];
    public $timestamps = false;
}