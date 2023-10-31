<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Product_TypeSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data = [
            [
                'product_id' => 1,
                'type' => 'COMPREHENSIVE',
            ],
            [
                'product_id' => 1,
                'type' => 'LIABILITY ONLY',
            ],
        ];
        ProductType::insert($data);
    }
}
