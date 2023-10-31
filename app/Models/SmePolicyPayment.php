<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmePolicyPayment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function bank()
    {
        return $this->hasOne(Bank::class,'id','bank_id');
    }
}
