<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use App\Models\City;

class CitySeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        Schema::disableForeignKeyConstraints();
        City::truncate();
        Schema::enableForeignKeyConstraints();
        $data = [
            [
                'state_id' => '2',
                'name' => 'Surat',
                'rto_code'=> 'GJ-5',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'state_id' => '1',
                'name' => 'Ludhiana',
                'rto_code'=> 'PB-1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ], [
                'state_id' => '1',
                'name' => 'Amritsar',
                'rto_code'=> 'PB-2',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ], [
                'state_id' => '2',
                'name' => 'Ahmedabad',
                'rto_code'=> 'GJ-1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ], [
                'state_id' => '3',
                'name' => 'Aurangabad',
                'rto_code'=> 'MH-20',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ], [
                'state_id' => '3',
                'name' => 'Navi Mumbai',
                'rto_code'=> 'MH-43',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ];

        City::insert($data);
    }
}
