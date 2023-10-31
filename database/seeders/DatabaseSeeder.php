<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\HealthPolicy;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        $this->call([
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            CompanySeeder::class,
            Company_BranchSeeder::class,
            Branch_Imd_NameSeeder::class,
            Business_CategorySeeder::class,
            DesignationSeeder::class,
            Irss_BranchSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            Role_PermissionSeeder::class,
            UserSeeder::class,
            DepartmentSeeder::class,
            Document_TypeSeeder::class,
            BankSeeder::class,
            ProductSeeder::class,
            Product_CompaniesSeeder::class,
            Sub_ProductSeeder::class,
            Product_TypeSeeder::class,
            Public_holidaySeeder::class,
            Leave_ApplicationsSeeder::class,
            Product_MakeSeeder::class,
            Product_ModelSeeder::class,
            Product_VariantSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
