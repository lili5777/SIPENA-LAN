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

        $picRole = Role::create([
            'name' => 'pic',
            'description' => 'PIC Pelatihan, akses menyesuaikan kebutuhan'
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
            'peserta.create',
            'peserta.read',
            'peserta.update',
            'peserta.delete',
            'angkatan.create',
            'angkatan.read',
            'angkatan.update',
            'angkatan.delete',
            'mentor.create',
            'mentor.read',
            'mentor.update',
            'mentor.delete',
            'export.data',
            'export.absen',
            'export.komposisi',
            'aktifitas.read'
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

        // hubungkan role pic dengan permission kecuali role dan user
        $picPermissions = Permission::where('name', 'not like', 'role.%')->where('name', 'not like', 'user.%')->get();
        $picRole->permissions()->sync($picPermissions->pluck('id')->toArray());

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

        // Buat user PIC
        User::create([
            'name' => 'PIC Pelatihan',
            'email' => 'pic@example.com',
            'password' => bcrypt('333'),
            'role_id' => $picRole->id
        ]);

        

    }
}
