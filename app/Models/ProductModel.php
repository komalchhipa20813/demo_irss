<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function make()
    {
        return $this->hasOne(Make::class,'id','make_id')->with('product');
    }
}
