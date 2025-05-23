<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Biodata;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BiodataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::with('roles')->get();

        $adminCount = 1;
        $wargaCount = 1;

        foreach ($users as $user) {
            $roleName = $user->roles->first()?->name;

            if ($roleName === 'superAdmin') {
                $nama = 'Admin ' . $adminCount++;
            } else {
                $nama = 'Warga ' . $wargaCount++;
            }

            Biodata::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nama' => $nama,
                    'alamat' => 'Jalan Mawar No. ' . rand(1, 100),
                    'no_hp' => '08' . rand(1111111111, 9999999999),
                    'tanggal_lahir' => now()->subYears(rand(18, 50))->subDays(rand(1, 365)),
                    'jenis_kelamin' => rand(0, 1) ? 'Laki-laki' : 'Perempuan',
                ]
            );
        }
    }
}
