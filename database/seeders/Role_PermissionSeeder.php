<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role_Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Role_PermissionSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $permissions = Permission::where('status', 1)->get();
        $data = [];
        foreach ($permissions as $permission) {
            $data[] = ['role_id' => '1', 'permission_id' => $permission->id];
        }
        Role_Permission::insert($data);
    }
}
