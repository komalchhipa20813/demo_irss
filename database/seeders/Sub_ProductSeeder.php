<?php

namespace Database\Seeders;

use App\Models\SubProduct;
use Illuminate\Database\Seeder;

class Sub_ProductSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $commercial_vehicles = ["3-WHEELER (LOADING RIXA) - PRIVATE CARRIERS", "3-WHEELER (LOADING RIXA) - PUBLIC CARRIERS", "3-WHEELER AUTO RIXA - PASSENGER CARRYING"];
        $gcvs = ["HCV (12001 TO 20000 GVW) PRIVATE CARRIERS", "HCV (12001 TO 20000 GVW) PUBLIC CARRIERS", "HCV (20001 TO 25000 GVW) PUBLIC CARRIERS","HCV (20001 TO 25000 GVW) PRIVATE CARRIERS","HCV (25001 TO 40000 GVW) PRIVATE CARRIERS","HCV (25001 TO 40000 GVW) PUBLIC CARRIERS","HCV (4000 ABOVE GVW) PUBLIC CARRIERS","HCV (4000 ABOVE GVW) PRIVATE CARRIERS","HCV (7501 TO 12000 GVW) PRIVATE CARRIERS","HCV (7501 TO 12000 GVW) PUBLIC CARRIERS","LCV (0 TO 2500 GVW) PRIVATE CARRIERS","LCV (0 TO 2500 GVW) PUBLIC CARRIERS","LCV (2501 TO 3500 GVW) PRIVATE CARRIERS","LCV (2501 TO 3500 GVW) PUBLIC CARRIERS","LCV (3501 TO 7500 GVW) PUBLIC CARRIERS","LCV (3501 TO 7500 GVW) PRIVATE CARRIERS","MIS-D VEHICLES","SCHOOL BUS / SCHOOL VAN","STAFF BUS"];
        $private_cars = ["ADD-ON", "COMPREHENSIVE", "THIRD PARTY"];
        $two_wheelers = ["ADD-ON", "COMPREHENSIVE", "THIRD PARTY"];
        $fires = ["Bharat Griha Raksha", "Bharat Laghu Udyam Suraksha", "BHARAT SOOKSHMA UDHYAM SURAKSHA","NON INDUSTRIEA / INDUSTRIAL FIRE POLICY","SFSP POLICY"];
        $marines = ["OPEN MARINE DOMESTIC", "SPECIFIC MARINE - DOMESTIC / EXPORT / IMPORT"];
        $packages = ["COMMERCIAL PACKAGE - CPP", "PACKAGE POLICY - OFFICE PACKAGE", "PACKAGE POLICY - SHOPKEEPER"];
        $sme_others = ["BURGLARY INSURANCE POLICY", "CONTACTOR PLANT & MACHINERY (CPM)", "CONTRACTOR ALL RISK (CAR)","ERECTION ALL RISK POLICY (EAR)","GROUP MEDICLAIM POLICY (GMC)","GROUP PERSONAL ACCIDENT POLICY (GPA)","JWELLER'S BLOCK","MONERY INSURANCE","Special Contingency Excluding Liability"];
        $liabilities = ["COMMERCIAL GENERAL LIABILITY - CGL", "PUBLIC LIABILITY - INDUSTRIAL", "PUBLIC LIABILITY - NON INDUSTRIAL","PUBLIC LIABILITY ACT","Professional Liability","fidelity guarantee insurance"];
        $wcs = ["WORKMEN COMPENSATION (WC)"];
        $group_policies = [];
        $mediclaims = ["Activ Assure - Diamond","Activ health","Activ health - Platinum Enhanced",'EXTRA CARE','HEALTH ENSURE','HEALTH GUARD','STAR PACKAGE','CARE','CARE ADVANTAGE','CARE FREEDOM','CARE NCB SUPER','CARE SENIOR','CARE SHIELD','CITIZEN PLAN','HEART MEDICLAIM','HEALTH SURAKSHA','MY HEALTH SURAKSHA SILVER','MY HEALTH SURAKSHA GOLD','OPTIMA RESTORE','OPTIMA SENIOR POLICY','OPTIMA SECURE','MY HEALTH MEDISURE CLASSIC',' HEALTH ADVANTAGE','COMPLETE HEALTH INSURANCE','FAMILY HEALTH PROTECTOR','HEALTH PROTECTOR PLUS POLICY','INDIVIDUAL HEALTH PROTECTOR','INDIVIDUAL MEDISHIELD POLICY','HEALTH CARE','HEALTH PREMIER','SECURE SHIELD','E CONNECT PLAN','BASIC PLAN','ELITE PLAN','SUPREME PLAN','REASSURE PLAN','SENIOR FIRST','HEALTH COMPANION VARIANT 2','HEALTH WISE PLAN','HEALTH GAIN PLAN','NEW HEALTH GAIN PLAN','HEALTH INFINITY PLAN','FAMILY PLUS','MULTIPLIER HEALTH INSURANCE PLAN','LIFELINE PLAN','AROGYA SUPREME PLAN','AROGYA PLUS PLAN','RETAIL PLAN','AROGYA PREMIER PLAN','COMPREHENSIVE INSURANCE','SENIOR CITIZEN RED CARPET','FAMILY HEALTH OPTIMA','STAR HEALTH GAIN PLAN','MEDI-CLASSIC PLAN','YOUNG STAR PLAN - SILVER','YOUNG STAR PLAN - GOLD','DIABETES SAFE PLAN','STAR CARDIAC CARE PLAN','STAR CANCER CARE PLAN','STAR HEALTH PREMIER PLAN','STAR HEALTH ASSURE PLAN','AROGYA SANJEEVANI PLAN','MEDICARE PROTECT PLAN','MEDICARE PLAN','MEDICARE PREMIER PLAN','ASHA KIRAN POLICY','NEW INDIA FLOATER MEDICLAIM PLAN','NEW INDIA INDIVIDUL PLAN','SENIOR CITIZENS MEDICLAIM PLAN','NEW INDIA SIXTY PLUS MEDICLAIM PLAN','JANATA MEDICLAIM POLICY','NEW INDIA PREMIER MEDICLAIM PLAN','NEW INDIA AROGYA SANJEEVNI PLAN','NEW INDIA GLOBAL MEDICALIM PLAN','NEW INDIA CANCER GURD POLICY','HAPPY FAMILY FLOATER','MEDICLAIM INDIVIDUAL POLICY','FAMILY MEDICARE POLICY','INDIVIDUAL HEALTH POLICY','COMPLETE HEALTHCARE INSURANCE'];
        $pas = ["ACTIVE SECURE PERSONAL ACCIDENT",'GLOBAL PERSONAL GUARD','PREMIUM PERSONAL GUARD','SANKAT MOCHAN','SECURE-3','ACCIDENT SURAKSHA','MY HEALTH KOTI SURAKSHA','FAMILY SHIELD','INDIVIDUAL PERSONAL ACCIDENT','ACCIDENT CARE PLAN','Accident Guard Plus - ELITE','Accident Guard Plus - PROTECT','Accident Guard Plus - PREMIER','PERSONAL ACCIDENT','INDIVIDUAL PERSONAL ACCIDENT'];
        $travels = ['Student Elite Standard','Travel Age Insure Gold','TRAVEL COMPANION','TRAVEL ELITE','Travel Elite Age - Gold','Travel Elite Gold','TRAVEL ELITE PLATINUM','Travel Elite Silver','Travel Student Elite Silver','Travel Student Elite Standard','TRVEL PRIME','TRAVEL ELITE PLATINUM','TRAVEL INSURANCE','STAR TRAVEL PROTECT PLAN','STAR CORPORATE TRAVEL PROTECT PLAN','TRAVEL GUARD SILVER PLAN','TRAVEL GUARD SILVER PLUS PLAN','TRAVEL GUARD GOLD PLAN','TRAVEL GUARD PLATINUM PLAN','TRAVEL GUARD 70+ SENIOR PLAN','ANNUAL MILTI TRIP','DOMESTIC TRAVEL PLAN'];
        $top_ups = ['Active Super Top-up','EXTRA CARE PLUS','HEALTH SURAKSHA TOP UP PLUS','HEALTH BOOSTER','HEALTH SUPER TOP UP','HEALTH CONNECT SUPRA','HEALTH RECHARGE SUPER TOP UP','ADVANCED TOP UP PLAN','AROGYA TOP UP PLAN','SUPER SURPLUS PLAN','MEDICARE PLUS  PLAN','NEW INDIA TOP UP MEDICLAIM'];
        $others = ['CRITICAL ILLNESS PLAN','CRITICAL ILLNESS','HOSPITAL CASH','HOSPITAL CASH INSURANCE POLICY','ICAN CANCER CARE POLICY','CRITICAL ILLNESS INSURANCE','CRITICAL CONNECT','STAR HOSPITAL CASH','STAR CRITICAL PLUS PLAN','STAR CRITICAL ILLNESS MULTIPLAY PLAN','STAR WOMEN CARE PLAN','CRITI MEDICARE PLAN'];
        foreach ($commercial_vehicles as $commercial_vehicle) {
            $data[] = ['product_id' => '1', 'name' => $commercial_vehicle];
        }
        foreach ($gcvs as $gcv) {
            $data[] = ['product_id' => '4', 'name' => $gcv];
        }
        foreach ($private_cars as $private_car) {
            $data[] = ['product_id' => '2', 'name' => $private_car];
        }
        foreach ($two_wheelers as $two_wheeler) {
            $data[] = ['product_id' => '3', 'name' => $two_wheeler];
        }
        foreach ($fires as $fire) {
            $data[] = ['product_id' => '5', 'name' => $fire];
        }
        foreach ($marines as $marine) {
            $data[] = ['product_id' => '6', 'name' => $marine];
        }
        foreach ($packages as $package) {
            $data[] = ['product_id' => '7', 'name' => $package];
        }
        foreach ($sme_others as $sme_other) {
            $data[] = ['product_id' => '8', 'name' => $sme_other];
        }
        foreach ($liabilities as $liability) {
            $data[] = ['product_id' => '9', 'name' => $liability];
        }
        foreach ($wcs as $wc) {
            $data[] = ['product_id' => '10', 'name' => $wc];
        }
        foreach ($group_policies as $group_policy) {
            $data[] = ['product_id' => '13', 'name' => $group_policy];
        }
        foreach ($mediclaims as $mediclaim) {
            $data[] = ['product_id' => '14', 'name' => $mediclaim];
        }
        foreach ($pas as $pa) {
            $data[] = ['product_id' => '15', 'name' => $pa];
        }
        foreach ($travels as $travel) {
            $data[] = ['product_id' => '16', 'name' => $travel];
        }
        foreach ($top_ups as $top_up) {
            $data[] = ['product_id' => '17', 'name' => $top_up];
        }
        foreach ($others as $other) {
            $data[] = ['product_id' => '18', 'name' => $other];
        }
        SubProduct::insert($data);
    }
}
