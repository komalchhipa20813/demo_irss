<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class BranchImdName extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $PrimaryKey = 'id';

    public function company()
    {
        return $this->hasOne(Company::class,'id','company_id');
    }

    public function companyBranch()
    {
        return $this->hasOne(CompanyBranch::class,'id','company_branch_id');
    }
}
