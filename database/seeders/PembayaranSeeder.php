<?php

namespace Database\Seeders;

use App\Models\Tagihan;
use App\Models\Pembayaran;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tagihans = Tagihan::all();

        foreach ($tagihans as $tagihan) {
            // 50% kemungkinan sudah dibayar
            if (rand(0, 1)) {
                Pembayaran::create([
                    'tagihan_id' => $tagihan->id,
                    'tahun_pembayaran' => $tagihan->tahun,
                    'status' => 'dibayar',
                    'jumlah_dibayar' => $tagihan->jumlah,
                    'bukti_bayar' => 'bukti_' . Str::uuid() . '.jpg',
                    'tanggal_bayar' => now()->subDays(rand(1, 90)),
                ]);

                // Update status tagihan
                $tagihan->update(['status_bayar' => 'dibayar']);
            }
        }
    }
}
