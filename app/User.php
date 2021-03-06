<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'id', 'email', 'username', 'password', 'role_id'
    ];
}
