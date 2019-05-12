<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class News extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'title', 'created_at', 'last_modified', 'published_on', 'status', 'header_image', 'event_id'];
}
