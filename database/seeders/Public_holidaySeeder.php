<?php

namespace Database\Seeders;

use App\Models\PublicHoliday;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Public_holidaySeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data = [
            [
                'title' => 'Mahatma Gandhi Jayanti',
                'date' => '2022-10-02',
                'holiday_type' => 'F',
            ],
            [
                'title' => 'Maha Saptami',
                'date' => '2022-10-03',
                'holiday_type' => 'H',
            ], [
                'title' => 'Dussehra',
                'date' => '2022-10-05',
                'holiday_type' => 'H',
            ], [
                'title' => 'Diwali',
                'date' => '2022-11-24',
                'holiday_type' => 'F',
            ], [
                'title' => 'Bhai Duj',
                'date' => '2022-11-26',
                'holiday_type' => 'F',
            ],
        ];
        PublicHoliday::insert($data);
    }
}
