<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyBranch extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function company()
    {
        return $this->hasOne(Company::class,'id','company_id');
    }
    public function city()
    {
        return $this->hasOne(City::class,'id','city_id')->with('state');
    }
}
