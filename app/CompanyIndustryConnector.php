<?php
/**
 * Created by PhpStorm.
 * User: TeamO
 * Date: 01-Apr-19
 * Time: 5:03 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class CompanyIndustryConnector extends Model
{
    public $timestamps = false;
    protected $table = "companies_industries";
    protected $fillable = ['id', 'company_id', 'industry_id'];
}