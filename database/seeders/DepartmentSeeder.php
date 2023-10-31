<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data = [
            ['name' => 'Account'],
            ['name' => 'Marketing'],
            ['name' => 'Investment'],
            ['name' => 'Credit unions'],
            ['name' => 'Savings and loan associations']
        ];
        Department::insert($data);
    }
}
