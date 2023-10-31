<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Document_TypeSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data = [
            ['name' => 'Adhar Card'],
            ['name' => 'Pan Card'],
        ];
        DocumentType::insert($data);
    }
}
