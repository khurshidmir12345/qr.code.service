<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create([
            'id' => 1,
            'name' => 'Admin',
        ]);

        $userRole = Role::create([
            'id' => 2,
            'name' => 'User',
        ]);

        // Sync permissions
        $adminRole->permissions()->sync([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]);
        $userRole->permissions()->sync([16,17,18,19,20]);
    }
}
