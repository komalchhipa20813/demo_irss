<?php

namespace Database\Seeders;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'customer_code',
                'key' => 'customer_code',
                'value' => '1000001',
            ],
            [
                'name' => 'policy_number',
                'key' => 'policy_no',
                'value' => '000001',
            ],
            [
                'name' => 'generate_pdf_count', 
                'key' => Carbon::now()->format('dMY'),
                'value' => 'RS1',
            ],
        ];
        Settings::insert($data);
    }
}
