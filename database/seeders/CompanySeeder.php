<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data = [
            [
                'name' => 'ADITYA BIRLA HEALTH INSURANCE COMPANY LIMITED',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Bajaj Allianz General Insurance Company Limited',
                'address' => 'Mezzanine Floor Megh Mayur PlazaRatan baug, Surat',
                'city_id' => 1
            ],
            [
                'name' => 'Bajaj Allianz Life Insurance Company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Bharti AXA General Insurance company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Bharti AXA Life Insurance company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Care Health Insurance Company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Cholamandalam MS General Insurance Company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Cigna TTK Health Insurance company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Edelweiss General Insurance company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Future Generali India Insurance Company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Go Digit General Insurance Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'HDFC Ergo General Insurance Company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'HDFC Ergo Life Insurance Company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'ICICI Lombard General Insurance Company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'ICICI Prodential Life Insurance Company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Iffco Tokio General Insurance Company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Kotak Mahindra General Insurance Company LImited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Kotak Life Insurance Company LImited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Liberty General Insurance Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Life Insurance Corporation of India',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Magma HDI General Insurance Company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'National Insurance company limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Niva Bupa Health Insurance',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Raheja QBE General Insurance Company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Reliance General Insurance Company Limited',
                'address' => 'ITC, Trade Centre, Ring Road A - 701, International, Majura Gate, Surat,',
                'city_id' => 1
            ],
            [
                'name' => 'Royal Sundaram General Insurance Company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'SBI General Insurance Company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'SBI Life Insurance Company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Shri Ram General Insurance company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Star Health & Allied Insurance Company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'Tata AIG General Insurance Company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'TATA AIA Life Insurance Company',
                'address' => null,
                'city_id' => 1
            ], 
            [
                'name' => 'The New India Assurance Company Limited',
                'address' => 'Subhash Chandra Bose Marg, TGB, Adajan Gam, Adajan Patia, Surat',
                'city_id' => 1
            ],
            [
                'name' => 'The Oriental Insurance Company Limited',
                'address' => null,
                'city_id' => 1
            ],
            [
                'name' => 'United India Insurance Company Limited',
                'address' => null,
                'city_id' => 1
            ], 
            [
                'name' => 'Universal Sompo General Insurance Company Limited',
                'address' => null,
                'city_id' => 1
            ]
        ];
        Company::insert($data);
    }
}