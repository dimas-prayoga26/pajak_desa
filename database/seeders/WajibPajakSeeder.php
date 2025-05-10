<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WajibPajak;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WajibPajakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 0; $i < 50; $i++) {
            WajibPajak::create([
                'user_id' => null,
                'name' => $faker->name,
                'nop' => $faker->unique()->regexify('[0-9]{18}'),
                'alamat' => $faker->address,
                'luas_bumi' => $faker->numberBetween(50, 150),
                'luas_bangunan' => $faker->numberBetween(30, 120),
                'status_bayar' => null,
            ]);
        }


    }
}
