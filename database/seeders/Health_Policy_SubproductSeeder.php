<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Health_Policy_SubproductSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $product['GROUP POLICY'] = [];
        $product['MEDICLAIM'] = [];
        $product['PA'] = ['EASY HEALTH', 'OPTIMA RESTORE', 'OPTIMA CASH PLATINUM', 'OPTIMA PLUS', 'OPTIMA VITAL', 'OPTIMA SUPER', 'DENGUE CARE', 'DAY2DAY CARE', 'HEALTH WALLET', 'ICAN-CANCER INSURANCE FOR WOMEN'];
        $product['TRAVEL'] = ['ESSENTIAL', 'ADVANCED', 'ELITE', 'INDIVIDUAL PERSONAL ACCIDENT POLICY'];
    }
}
