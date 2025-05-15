<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;
use App\Models\Tagihan;
use App\Models\WajibPajak;

class PembayaranController extends Controller
{
    public function snapToken($id)
    {
        try {
            $tagihan = Tagihan::findOrFail($id);
            $user = auth()->user();


            if ($tagihan->jumlah < 1000) {
                return response()->json([
                    'status' => false,
                    'message' => 'Jumlah tagihan terlalu kecil untuk diproses.'
                ]);
            }


            Config::$serverKey = 'SB-Mid-server-I9AT2p8HQZS_1DMsvcFIPoxW';
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;


            $params = [
                'transaction_details' => [
                    'order_id' => 'TAGIHAN-' . $tagihan->id . '-' . time(),
                    'gross_amount' => (int) $tagihan->jumlah,
                ],
                'customer_details' => [
                    'first_name' => $user->biodata->nama ?? $user->name ?? 'User',
                    'email' => $user->email ?? 'user@example.com',
                ]
            ];




            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'status' => true,
                'snap_token' => $snapToken
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal membuat Snap Token: ' . $e->getMessage()
            ]);
        }
    }

    public function notificationHandler(Request $request)
    {
        try {

            $notif = new Notification();

            $transactionStatus = $notif->transaction_status;
            $orderId = $notif->order_id;


            preg_match('/TAGIHAN\-(\d+)\-/', $orderId, $match);
            $tagihanId = $match[1] ?? null;

            if (!$tagihanId) {
                return response()->json(['message' => 'Tagihan tidak dikenali'], 400);
            }

            $tagihan = Tagihan::find($tagihanId);

            if (!$tagihan) {
                return response()->json(['message' => 'Tagihan tidak ditemukan'], 404);
            }


            if ($transactionStatus == 'capture') {
                $tagihan->status_bayar = 'dikonfirmasi';
                $tagihan->save();


                $wajibPajakId = $tagihan->wajib_pajak_id;

                $totalTagihan = Tagihan::where('wajib_pajak_id', $wajibPajakId)->count();
                $tagihanLunas = Tagihan::where('wajib_pajak_id', $wajibPajakId)
                    ->where('status_bayar', 'dikonfirmasi')
                    ->count();


                if ($totalTagihan > 0 && $totalTagihan == $tagihanLunas) {
                    WajibPajak::where('id', $wajibPajakId)
                        ->update(['status_bayar' => 'dibayar']);
                } else {

                    WajibPajak::where('id', $wajibPajakId)
                        ->update(['status_bayar' => 'belum']);
                }
            }

            return response()->json(['message' => 'Notifikasi diproses'], 200);

        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal memproses notifikasi'], 500);
        }
    }
}
