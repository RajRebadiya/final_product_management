<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Insert permissions for each module
        DB::table('permissions')->insert([
            [
                'module' => 'Dashboard',
                'permissions' => json_encode([
                    'read' => true,
                    'create' => false,
                    'update' => false,
                    'delete' => false,
                ]),
            ],
            [
                'module' => 'Users',
                'permissions' => json_encode([
                    'read' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true,
                ]),
            ],
            [
                'module' => 'Banners',
                'permissions' => json_encode([
                    'read' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true,
                ]),
            ],
            [
                'module' => 'Business',
                'permissions' => json_encode([
                    'read' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true,
                ]),
            ],
            [
                'module' => 'Plans',
                'permissions' => json_encode([
                    'read' => true,
                    'create' => false,
                    'update' => true,
                    'delete' => false,
                ]),
            ],
            [
                'module' => 'Features',
                'permissions' => json_encode([
                    'read' => true,
                    'create' => false,
                    'update' => true,
                    'delete' => false,
                ]),
            ],
            // Add more modules here as needed
        ]);
    }
}
