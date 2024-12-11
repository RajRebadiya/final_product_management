<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Creating sample roles with permissions
        Role::create([
            'name' => 'Admin',
            'permissions' => json_encode([
                'dashboard' => ['read' => true, 'create' => true, 'update' => true, 'delete' => true],
                'users' => ['read' => true, 'create' => true, 'update' => true, 'delete' => true],
                'banners' => ['read' => true, 'create' => true, 'update' => true, 'delete' => true],
                // Add more modules/permissions here
            ]),
        ]);

        Role::create([
            'name' => 'Editor',
            'permissions' => json_encode([
                'dashboard' => ['read' => true, 'create' => false, 'update' => false, 'delete' => false],
                'users' => ['read' => true, 'create' => false, 'update' => false, 'delete' => false],
                'banners' => ['read' => true, 'create' => false, 'update' => false, 'delete' => false],
                // Add more modules/permissions here
            ]),
        ]);

        // User role with limited permissions
        Role::create([
            'name' => 'User',
            'permissions' => json_encode([
                'dashboard' => [
                    'read' => true,
                    'create' => false,
                    'update' => false,
                    'delete' => false
                ],
                'users' => [
                    'read' => false,
                    'create' => false,
                    'update' => false,
                    'delete' => false
                ],
                'banners' => [
                    'read' => true,
                    'create' => false,
                    'update' => false,
                    'delete' => false
                ],
                // Add more modules here
            ])
        ]);
    }
}
