<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\ProductCompany;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Product_CompaniesSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $company = Company::where('status', 1)->get();
        $data = [];
        foreach ($company as $companys) {
            $data[] = ['product_id' => '1', 'company_id' => $companys->id];
            $data[] = ['product_id' => '2', 'company_id' => $companys->id];
            $data[] = ['product_id' => '3', 'company_id' => $companys->id];
            $data[] = ['product_id' => '4', 'company_id' => $companys->id];
            $data[] = ['product_id' => '5', 'company_id' => $companys->id];
            $data[] = ['product_id' => '6', 'company_id' => $companys->id];
            $data[] = ['product_id' => '7', 'company_id' => $companys->id];
            $data[] = ['product_id' => '8', 'company_id' => $companys->id];
            $data[] = ['product_id' => '9', 'company_id' => $companys->id];
            $data[] = ['product_id' => '10', 'company_id' => $companys->id];
            $data[] = ['product_id' => '11', 'company_id' => $companys->id];
            $data[] = ['product_id' => '12', 'company_id' => $companys->id];
        }
        $mediclaims=[1,2,6,10,12,14,16,17,19,23,25,26,27,30,31,33,34,35,36];
        foreach ($mediclaims as $companys) {
            $data[] = ['product_id' => '14', 'company_id' => $companys];
        }
        $pas=[1,2,6,10,11,12,14,16,30,31,33,34];
        foreach ($pas as $companys) {
            $data[] = ['product_id' => '15', 'company_id' => $companys];
        }
        $travels=[2,14,30,31];
        foreach ($travels as $companys) {
            $data[] = ['product_id' => '16', 'company_id' => $companys];
        }
        $top_ups=[1,2,12,14,17,19,23,26,27,30,31,33];
        foreach ($top_ups as $companys) {
            $data[] = ['product_id' => '17', 'company_id' => $companys];
        }
        $others=[1,2,12,19,30,31];
        foreach ($others as $companys) {
            $data[] = ['product_id' => '18', 'company_id' => $companys];
        }
        ProductCompany::insert($data);
    }
}
