<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotorPolicy extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function branch()
    {
        return $this->hasOne(IrssBranch::class, 'id', 'irss_branch_id')->where('status',1);
    }
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id')->where('status',1);
    }
    public function companyBranch()
    {
        return $this->hasOne(CompanyBranch::class,'id','company_branch_id')->with('company');
    }
    public function branch_imd_name()
    {
        return $this->hasOne(BranchImdName::class,'id','branch_imd_id')->with('companyBranch');
    }
    public function product()
    {
        return $this->hasOne(Product::class,'id','product_id');
    }
    public function sub_product()
    {
        return $this->hasOne(SubProduct::class,'id','sub_product_id')->with('product');
    }
    public function payments()
    {
        return $this->hasMany(MotorPolicyPayment::class,'policy_id','id')->with('bank');
    }
    public function motor_policy_vehicle_only()
    {
        return $this->hasOne(MotorPolicyVehical::class,'policy_id','id');
    }
    public function motor_policy_vehicle()
    {
        return $this->hasOne(MotorPolicyVehical::class,'policy_id','id')->with(['make','product_model','product_variant','bank','rto_city']);
    }
    public function product_type()
    {
        return $this->hasOne(ProductType::class,'id','product_type_id');
    }
    public function company()
    {
        return $this->hasOne(Company::class,'id','company_id');
    }
    public function agent_only()
    {
        return $this->hasOne(Agent::class,'id','agent_id');
    }
    public function agent()
    {
        return $this->hasOne(Agent::class,'id','agent_id')->with('fdo');
    }
    public function outward()
    {
        return $this->hasOne(GeneratedOutward::class,'id','outward_id');
    }
    public function previous_company()
    {
        return $this->hasOne(Company::class,'id','previous_company_id');
    }
    public function renewal_previous_company()
    {
        return $this->hasOne(Company::class,'id','renewal_previous_company_id');
    }
    public function created_by()
    {
        return $this->hasOne(User::class,'id','created_by_id');
    }
    public function updated_by()
    {
        return $this->hasOne(User::class,'id','updated_by_id');
    }
    public function edited_by()
    {
        return $this->hasOne(User::class,'id','edited_by_id');
    }
}
