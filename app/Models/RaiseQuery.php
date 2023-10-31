<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaiseQuery extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function motor_policy()
    {
        return $this->hasOne(MotorPolicy::class, 'id', 'motor_policy_id')->with('motor_policy_vehicle_only','sub_product','company','agent_only');
    }

    public function health_policy()
    {
        return $this->hasOne(HealthPolicy::class, 'id', 'health_policy_id')->with('sub_product','company','agent_only');
    }

    public function sme_policy()
    {
        return $this->hasOne(SmePolicy::class, 'id', 'sme_policy_id')->with('sub_product','company','agent');
    }

    public function query_closed_by()
    {
        return $this->hasOne(User::class,'id','closed_by');
    }
}
