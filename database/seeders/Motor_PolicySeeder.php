<?php

namespace Database\Seeders;

use App\Models\MotorPolicy;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Motor_PolicySeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data = [
            [
                'policy_type' => 1,
                'name' => 'COMMERCIAL VEHICLE',
            ],
            [
                'policy_type' => 1,
                'name' => 'PRIVATE CAR',
            ], [
                'policy_type' => 1,
                'name' => 'TWO WHEELER',
            ], [
                'policy_type' => 1,
                'name' => 'GCV',
            ],
        ];
        Product::insert($data);
    }
}
