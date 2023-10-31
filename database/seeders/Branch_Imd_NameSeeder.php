<?php

namespace Database\Seeders;

use App\Models\BranchImdName;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class Branch_Imd_NameSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Schema::disableForeignKeyConstraints();
        BranchImdName::truncate();
        Schema::enableForeignKeyConstraints();
        $data = [
            [
                'company_id' => 1,
                'Company_branch_id' => 1,
                'name' => 'ABH1186594 - SHEFALI RANA'
            ],
            [
                'company_id' => 2,
                'Company_branch_id' => 2,
                'name' => '22060022 - RISHAD BHIMANI'
            ],
            [
                'company_id' => 6,
                'Company_branch_id' => 6,
                'name' => 'MINAKSHI KASHYAP'
            ],
            [
                'company_id' => 7,
                'Company_branch_id' => 7,
                'name' => '201896999421 - MAYUR PATEL'
            ],
            [
                'company_id' => 9,
                'Company_branch_id' => 9,
                'name' => '2210002684 - KISHAN RANA'
            ],
            [
                'company_id' => 10,
                'Company_branch_id' => 10,
                'name' => '60094756 - SHEFALI RANA'
            ],
            [
                'company_id' => 11,
                'Company_branch_id' => 11,
                'name' => '1114130 - AKASH BHAGWAT'
            ],
            [
                'company_id' => 12,
                'Company_branch_id' => 12,
                'name' => '200165226494 - BINISHA JIGNESH PATEL'
            ],
            [
                'company_id' => 14,
                'Company_branch_id' => 14,
                'name' => 'ILG63176 - TEJAL TAILOR'
            ],
            [
                'company_id' => 16,
                'Company_branch_id' => 16,
                'name' => '52001876 - MAYURI RAHUL RANA'
            ],
            [
                'company_id' => 17,
                'Company_branch_id' => 17,
                'name' => '6480470000 - AJAY MAISURIYA'
            ],
            [
                'company_id' => 19,
                'Company_branch_id' => 19,
                'name' => 'IMD1262675 - DARSHANKUMAR BHARATBHAI PATEL'
            ],
            [
                'company_id' => 21,
                'Company_branch_id' => 21,
                'name' => 'AGD0004133 - VINAY SURTI'
            ],
            [
                'company_id' => 23,
                'Company_branch_id' => 23,
                'name' => 'NBHSUR02145430 - VINAY SURTI'
            ],
            [
                'company_id' => 24,
                'Company_branch_id' => 24,
                'name' => '1034918 - MINAKSHI KASHYAP'
            ],
            [
                'company_id' => 25,
                'Company_branch_id' => 25,
                'name' => '16A01472 - SHAILESH B TRIVEDI'
            ],
            [
                'company_id' => 26,
                'Company_branch_id' => 26,
                'name' => 'OA510282 - PREETI GUPTA'
            ],
            [
                'company_id' => 27,
                'Company_branch_id' => 27,
                'name' => '0084625-ANKITABEN KANCHANLAL RANA'
            ],
            [
                'company_id' => 30,
                'Company_branch_id' => 30,
                'name' => 'BA0000400297 - BINISHA PATEL'
            ],
            [
                'company_id' => 31,
                'Company_branch_id' => 31,
                'name' => 'AIG2125340000 - SORATHIA DEVDEEPKUMAR VINODCHANDRA'
            ],
            [
                'company_id' => 33,
                'Company_branch_id' => 33,
                'name' => 'NIAAG00038130 - CHAITALI SORATHIA'
            ],
            [
                'company_id' => 34,
                'Company_branch_id' => 34,
                'name' => 'BA0000124207 - SONALIKA S TRIVEDI'
            ],
            [
                'company_id' => 35,
                'Company_branch_id' => 35,
                'name' => 'IAND INSURANCE BROKER PVT LTD'
            ],
            [
                'company_id' => 36,
                'Company_branch_id' => 36,
                'name' => '200041973516 - VIRAL R PATEL'
            ],
            
        ];
        BranchImdName::insert($data);
    }
}