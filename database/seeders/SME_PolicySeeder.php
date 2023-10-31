<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SME_PolicySeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data = [
            [
                'policy_type' => 3,
                'name' => 'FIRE',
            ],[
                'policy_type' => 3,
                'name' => 'MARINE',
            ],[
                'policy_type' => 3,
                'name' => 'PACKAGE',
            ],[
                'policy_type' => 3,
                'name' => 'SME OTHER',
            ], [
                'policy_type' => 3,
                'name' => 'LIABILITY',
            ], [
                'policy_type' => 3,
                'name' => 'WC',
            ],
            [
                'policy_type' => 3,
                'name' => 'MISCELLANEOUS',
            ],
            [
                'policy_type' => 3,
                'name' => 'ENGINEERING',
            ],
        ];
        Product::insert($data);
    }
}
