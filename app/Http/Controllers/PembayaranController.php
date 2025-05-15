<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Tagihan;
use App\Models\WajibPajak;
use Midtrans\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    public function snapToken($id)
    {
        try {
            Log::info('Memulai proses pembuatan Snap Token untuk tagihan ID:', ['tagihan_id' => $id]);

            $tagihan = Tagihan::findOrFail($id);

            // Validasi jika tagihan tidak ditemukan
            if (!$tagihan) {
                Log::warning('Tagihan tidak ditemukan untuk ID:', ['tagihan_id' => $id]);
                return response()->json([
                    'status' => false,
                    'message' => 'Tagihan tidak ditemukan.'
                ], 404);
            }

            $user = auth()->user();

            if ($tagihan->jumlah < 1000) {
                Log::warning('Jumlah tagihan terlalu kecil untuk diproses:', ['tagihan_id' => $id, 'jumlah' => $tagihan->jumlah]);
                return response()->json([
                    'status' => false,
                    'message' => 'Jumlah tagihan terlalu kecil untuk diproses.'
                ]);
            }

            Log::info('Menyiapkan konfigurasi Midtrans', [
                'server_key' => env('MIDTRANS_SERVER_KEY'),
                'isProduction' => Config::$isProduction
            ]);

            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $orderId = 'TAGIHAN-' . $tagihan->id . '-' . time();
            $tagihan->order_id = $orderId;
            $tagihan->save();

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $tagihan->jumlah,
                ],
                'customer_details' => [
                    'first_name' => $user->biodata->nama ?? $user->name ?? 'User',
                    'email' => $user->email ?? 'user@example.com',
                ]
            ];

            Log::info('Menyusun parameter untuk Snap Token:', ['params' => $params]);

            // Mencoba mendapatkan Snap Token dari Midtrans
            try {
                $snapToken = Snap::getSnapToken($params);
                Log::info('Snap Token berhasil dibuat:', ['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                Log::error('Gagal mendapatkan Snap Token dari Midtrans:', [
                    'error' => $e->getMessage(),
                    'params' => $params
                ]);
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal membuat Snap Token dari Midtrans: ' . $e->getMessage()
                ]);
            }

            return response()->json([
                'status' => true,
                'snap_token' => $snapToken
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal membuat Snap Token:', [
                'error' => $e->getMessage(),
                'tagihan_id' => $id
            ]);

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
            $paymentType = $request->input('payment_type');
            $fraudStatus = $request->input('fraud_status');


            Log::info('Extracted data from notification:', [
                'order_id' => $orderId,
                'status_code' => $statusCode,
                'gross_amount' => $grossAmount,
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
                'fraud_status' => $fraudStatus
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


            $midtransTransaction = new MidtransTransaction();
            $midtransTransaction->tagihan_id = $tagihan->id;
            $midtransTransaction->order_id = $orderId;
            $midtransTransaction->transaction_status = $transactionStatus;
            $midtransTransaction->gross_amount = $grossAmount;
            $midtransTransaction->payment_type = $paymentType;
            $midtransTransaction->fraud_status = $fraudStatus;
            $midtransTransaction->save();


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
