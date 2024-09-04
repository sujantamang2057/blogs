<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        $editArticlesPermission = Permission::create(['name' => 'edit articles']);
        $deleteArticlesPermission = Permission::create(['name' => 'delete articles']);

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $editorRole = Role::create(['name' => 'editor']);

        // Assign permissions to roles
        $adminRole->givePermissionTo($editArticlesPermission);
        $adminRole->givePermissionTo($deleteArticlesPermission);

        $editorRole->givePermissionTo($editArticlesPermission);

        // Assign roles to users
        $user = \App\Models\User::find(1); // Find a user by ID
        $user->assignRole($adminRole); // Assign the admin role to this user
    }
}
