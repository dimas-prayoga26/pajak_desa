<?php

namespace Database\Seeders;

use App\Models\Tagihan;
use App\Models\WajibPajak;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TagihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wajibPajaks = WajibPajak::all();

        foreach ($wajibPajaks as $wajib) {
            for ($tahun = 2023; $tahun <= 2025; $tahun++) {
                Tagihan::create([
                    'wajib_pajak_id' => $wajib->id,
                    'tahun' => $tahun,
                    'jumlah' => rand(100000, 500000),
                    'status_bayar' => 'belum',
                ]);
            }
        }
    }
}
