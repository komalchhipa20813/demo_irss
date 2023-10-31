<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $this->call([
            Motor_PolicySeeder::class,
            SME_PolicySeeder::class,
            Health_PolicySeeder::class,
        ]);
    }
}
