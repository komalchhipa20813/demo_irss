<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function city()
    {
        return $this->hasOne(City::class,'id','city_id')->with('state');
    }
    public function health_policies()
    {
        return $this->hasMany(HealthPolicy::class,'company_id','id');
    }
    public function motor_policies()
    {
        return $this->hasMany(MotorPolicy::class,'company_id','id');
    }
    public function sme_policies()
    {
        return $this->hasMany(SmePolicy::class,'company_id','id');
    }
}
