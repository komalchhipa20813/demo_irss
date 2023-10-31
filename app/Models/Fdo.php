<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Fdo extends Authenticatable
{
    use HasFactory,Notifiable;
    protected $guard = 'fdo';
    protected $guarded = [];
    public function branch()
    {
        return $this->hasOne(IrssBranch::class, 'id', 'home_irss_branch_id')->where('status',1);
    }
    public function business_category()
    {
        return $this->hasOne(BusinessCategory::class, 'id', 'business_category_id')->where('status',1);
    }
    public function documents()
    {
        return $this->hasMany(FdoDocuments::class, 'fdo_id', 'id')->with('documents_type')->where('status',1);
    }
    public function bank()
    {
        return $this->hasOne(Bank::class, 'id', 'bank_id')->where('status',1);
    }
}
