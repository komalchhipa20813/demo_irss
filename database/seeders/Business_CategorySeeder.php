<?php

namespace Database\Seeders;

use App\Models\BusinessCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Business_CategorySeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data = [
            ['name' => 'Partner'],
            ['name' => 'Agency'],
            ['name' => 'Call Center'],
            ['name' => 'Banca Channel'],
            ['name' => 'Finance'],
        ];
        BusinessCategory::insert($data);
    }
}
