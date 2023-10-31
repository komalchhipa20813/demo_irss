<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            'first_name' => 'henish',
            'last_name' => 'patel',
            'role_id' => 1,
            'email' =>'admin@gmail.com',
            'code' => 'RETINUE-1',
            'password' => Hash::make('Henish_12'),
            'phone' => '9999999999',
        ]);

    }
}
