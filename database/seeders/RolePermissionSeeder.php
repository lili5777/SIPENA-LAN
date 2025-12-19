<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat role
        $adminRole = Role::create([
            'name' => 'admin',
            'description' => 'Administrator, full access'
        ]);

        $userRole = Role::create([
            'name' => 'user',
            'description' => 'Regular user, limited access'
        ]);

        // buat daftar permission CRUD dasar
        $crudPermissions = [
            'user.create',
            'user.read',
            'user.update',
            'user.delete',
            'role.create',
            'role.read',
            'role.update',
            'role.delete',
        ];

        foreach ($crudPermissions as $perm) {
            Permission::updateOrCreate(
                ['name' => $perm],
                ['description' => "Boleh {$perm} semua data"]
            );
        }

        // hubungkan role admin dengan semua permission
        $allPermissions = Permission::all();
        $adminRole->permissions()->sync($allPermissions->pluck('id')->toArray());

        // hubungkan role user dengan permission read saja
        $readPermissions = Permission::where('name', 'like', '%.read')->get();
        $userRole->permissions()->sync($readPermissions->pluck('id')->toArray());

        // Buat user Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('111'),
            'role_id' => $adminRole->id
        ]);

        // Buat user biasa
        User::create([
            'name' => 'Ali',
            'email' => 'ali@example.com',
            'password' => bcrypt('222'),
            'role_id' => $userRole->id
        ]);
    }
}
