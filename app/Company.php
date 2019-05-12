<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 01-Apr-19
 * Time: 5:03 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'company_name', 'contact_person', 'company_logo', 'user_id'];
}