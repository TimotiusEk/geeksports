<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 27-Apr-19
 * Time: 8:50 PM
 */

namespace App\Http;


use Illuminate\Database\Eloquent\Model;

class StreamingChannel extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'title', 'url', 'start_date_time'];
}