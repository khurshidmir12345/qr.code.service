<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Permission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Permission::query()->insert([
            [
                'id'=>1,
                'name'=>'user_access',
            ],
            [
                'id'=>2,
                'name'=>'user_index',
            ],
            [
                'id'=>3,
                'name'=>'user_create',
            ],
            [
                'id'=>4,
                'name'=>'user_update',
            ],
            [
                'id'=>5,
                'name'=>'user_delete',
            ],
            [
                'id'=>6,
                'name'=>'role_access',
            ],
            [
                'id'=>7,
                'name'=>'role_index',
            ],
            [
                'id'=>8,
                'name'=>'role_create',
            ],
            [
                'id'=>9,
                'name'=>'role_update',
            ],
            [
                'id'=>10,
                'name'=>'role_delete',
            ],
            [
                'id'=>11,
                'name'=>'permission_access',
            ],
            [
                'id'=>12,
                'name'=>'permission_index',
            ],
            [
                'id'=>13,
                'name'=>'permission_create',
            ],
            [
                'id'=>14,
                'name'=>'permission_update',
            ],
            [
                'id'=>15,
                'name'=>'permission_delete',
            ],
            [
                'id'=>16,
                'name'=>'qr_access',
            ],
            [
                'id'=>17,
                'name'=>'qr_index',
            ],
            [
                'id'=>18,
                'name'=>'qr_create',
            ],
            [
                'id'=>19,
                'name'=>'qr_update',
            ],
            [
                'id'=>20,
                'name'=>'qr_delete',
            ],
        ]);
    }
}
