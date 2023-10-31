<?php

namespace Database\Seeders;

use App\Models\IrssBranch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Irss_BranchSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data = [
            [
                'name' => 'Surat',
                'address' => 'Ring Rd, beside Gujarat Samachar Press, Udhana Darwaja',
                'city_id' => 1,
                'policy_inward_code' => 'RS'
            ],
        ];
        IrssBranch::insert($data);
    }
}
