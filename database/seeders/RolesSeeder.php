<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Peran dan izin konten (PRD §13). Akun pengguna dibuat UserSeeder.
     */
    public function run(): void
    {
        $permissions = [
            'view_any_content', 'view_content',
            'create_content', 'update_content', 'delete_content',
            'manage_menus', 'manage_settings', 'manage_users',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $roles = [
            'super_admin' => $permissions,
            'administrator' => ['view_any_content', 'view_content', 'create_content', 'update_content', 'delete_content', 'manage_menus', 'manage_settings'],
            'editor' => ['view_any_content', 'view_content', 'create_content', 'update_content'],
            'kontributor' => ['view_any_content', 'view_content', 'create_content'],
            'viewer' => ['view_any_content', 'view_content'],
        ];

        foreach ($roles as $role => $perms) {
            $model = Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
            $model->syncPermissions($perms);
        }
    }
}
