<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WajibPajak;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WajibPajakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::role('warga')->get();

        foreach ($users as $user) {
            WajibPajak::create([
                'user_id' => $user->id,
                'nop' => 'NOP-' . rand(100000, 999999),
                'alamat' => 'Jl. Citra Nomor ' . rand(1, 100),
                'luas_bumi' => rand(50, 150),
                'luas_bangunan' => rand(30, 120),
            ]);
        }
    }
}
