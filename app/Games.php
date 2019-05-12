<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Games extends Model
{
    protected $fillable = ['id', 'name', 'genre_id'];
}
