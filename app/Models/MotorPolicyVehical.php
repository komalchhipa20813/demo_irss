<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotorPolicyVehical extends Model
{
    use HasFactory;
    protected $table = 'motor_policy_vehicals';
    protected $guarded = [];

    public function make()
    {
        return $this->hasOne(Make::class,'id','make_id');
    }

    public function product_model()
    {
        return $this->hasOne(ProductModel::class,'id','model_id');
    }

    public function product_variant()
    {
        return $this->hasOne(ProductVariant::class,'id','variant_id');
    }

    public function bank()
    {
        return $this->hasOne(Bank::class,'id','hypothication');
    }
    public function rto_city()
    {
        return $this->hasOne(City::class,'id','city_id');
    }
}
