<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function model()
    {
        return $this->hasOne(ProductModel::class,'id','model_id')->with('make');
    }
}
