<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 09-Apr-19
 * Time: 4:09 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class VacancyManagement extends Model
{
    public $timestamps = false;
    protected $fillable = ["id", "action", "vacancy_id", "worker_id", "subrole_id"];
}