<?php

namespace App\Models;

use GuzzleHttp\Handler\Proxy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCompany extends Model {
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;
    public function company_product() {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }
    public function product_company() {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
