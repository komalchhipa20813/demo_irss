<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Designation;

class DesignationSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data = [
            ['name' => 'Manager'],
            ['name' => 'Clerk'],
            ['name' => 'broker'],
            ['name' => 'Banking associate'],
            ['name' => 'Senior banker'],
        ];
        Designation::insert($data);
    }
}
