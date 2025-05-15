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

            Log::info('Midtrans notification received:', $request->all());


            $orderId = $request->input('order_id');
            $statusCode = $request->input('status_code');
            $grossAmount = $request->input('gross_amount');
            $signatureKey = $request->input('signature_key');
            $transactionStatus = $request->input('transaction_status');


            Log::info('Extracted data from notification:', [
                'order_id' => $orderId,
                'status_code' => $statusCode,
                'gross_amount' => $grossAmount,
                'transaction_status' => $transactionStatus
            ]);


            $serverKey = env('MIDTRANS_SERVER_KEY');


            $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);


            if ($signatureKey !== $expectedSignature) {
                Log::warning('Invalid signature for order:', ['order_id' => $orderId]);
                return response()->json(['message' => 'Invalid signature'], 403);
            }


            $tagihan = Tagihan::where('order_id', $orderId)->first();

            if (!$tagihan) {
                Log::warning('Tagihan not found for order:', ['order_id' => $orderId]);
                return response()->json(['message' => 'Tagihan not found'], 404);
            }


            $tagihan->status_bayar = 'dikonfirmasi';
            $tagihan->save();


            $wajibPajakId = $tagihan->wajib_pajak_id;


            $totalTagihan = Tagihan::where('wajib_pajak_id', $wajibPajakId)->count();
            $tagihanLunas = Tagihan::where('wajib_pajak_id', $wajibPajakId)
                ->where('status_bayar', 'dikonfirmasi')
                ->count();


            Log::info('Total tagihan and tagihan lunas:', [
                'total_tagihan' => $totalTagihan,
                'tagihan_lunas' => $tagihanLunas
            ]);


            if ($totalTagihan > 0 && $totalTagihan == $tagihanLunas) {

                WajibPajak::where('id', $wajibPajakId)
                    ->update(['status_bayar' => 'dibayar']);
                Log::info('Wajib pajak status updated to "dibayar".', ['wajib_pajak_id' => $wajibPajakId]);
            } else {

                WajibPajak::where('id', $wajibPajakId)
                    ->update(['status_bayar' => 'belum']);
                Log::info('Wajib pajak status updated to "belum".', ['wajib_pajak_id' => $wajibPajakId]);
            }


            return response()->json(['message' => 'Notification processed successfully'], 200);

        } catch (\Exception $e) {

            Log::error('Error processing notification: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to process notification'], 500);
        }
    }
}
