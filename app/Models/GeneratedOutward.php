<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneratedOutward extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function branch()
    {
        return $this->hasOne(IrssBranch::class, 'id', 'irss_branch_id')->where('status',1);
    }
    public function company()
    {
        return $this->hasOne(Company::class,'id','company_id');
    }
    public function company_branch()
    {
        return $this->hasOne(CompanyBranch::class,'id','company_branch_id');
    }
    public function branch_imd_name()
    {
        return $this->hasOne(BranchImdName::class,'id','branch_imd_id');
    }
    public function motor_policies()
    {
        return $this->hasMany(MotorPolicy::class, 'outward_id', 'id')->where('status',1);
    }
    public function health_policies()
    {
        return $this->hasMany(HealthPolicy::class, 'outward_id', 'id')->where('status',1);
    }
    public function sme_policies()
    {
        return $this->hasMany(SmePolicy::class, 'outward_id', 'id')->where('status',1);
    }
}
