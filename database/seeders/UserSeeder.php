<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'superAdmin']);
        $roleWarga = Role::firstOrCreate(['name' => 'warga']);

        // Buat user super admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'password' => Hash::make('password'),
                'photo' => null,
            ]
        );
        $admin->assignRole($roleSuperAdmin);

        // Buat 3 user warga manual
        for ($i = 1; $i <= 3; $i++) {
            $user = User::firstOrCreate(
                ['email' => "warga{$i}@gmail.com"],
                [
                    'name' => "Warga {$i}",
                    'username' => "warga{$i}",
                    'password' => Hash::make('password'),
                    'photo' => null,
                ]
            );
            $user->assignRole($roleWarga);
        }
    }

}
