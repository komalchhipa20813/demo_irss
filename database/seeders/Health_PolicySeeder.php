<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Health_PolicySeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data = [
            [
                'policy_type' => 2,
                'name' => 'GROUP POLICY',
            ],
            [
                'policy_type' => 2,
                'name' => 'MEDICLAIM',
            ], [
                'policy_type' => 2,
                'name' => 'PA',
            ], [
                'policy_type' => 2,
                'name' => 'TRAVEL',
            ], [
                'policy_type' => 2,
                'name' => 'TOP UP',
            ], [
                'policy_type' => 2,
                'name' => 'OTHER',
            ],
        ];
        Product::insert($data);
    }
}
