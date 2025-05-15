<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Tagihan;
use Midtrans\Transaction;
use App\Models\WajibPajak;
use Midtrans\Notification;
use Illuminate\Http\Request;
use App\Models\MidtransTransaction;
use Illuminate\Support\Facades\Log;

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

            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $orderId = 'TAGIHAN-' . $tagihan->id . '-' . time();
            $tagihan->order_id = $orderId;
            $tagihan->save();

            $wajibPajak = WajibPajak::find($tagihan->wajib_pajak_id);

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $tagihan->jumlah,
                ],
                'customer_details' => [
                    'first_name' => $user->biodata->nama ?? $user->name ?? 'User',
                    'email' => $user->email ?? 'user@example.com',
                    'phone' => $user->biodata->no_hp ?? '',
                    'billing_address' => [
                        'address' => $user->biodata->alamat ?? '',
                        'city' => '',
                        'postal_code' => '',
                        'country_code' => 'IDN',
                    ],
                    'shipping_address' => [
                        'first_name' => $user->biodata->nama ?? $user->name ?? 'User',
                        'phone' => $user->biodata->no_hp ?? '',
                        'address' => $user->biodata->alamat ?? '',
                        'city' => '',
                        'postal_code' => '',
                        'country_code' => 'IDN',
                    ],
                ],
                'item_details' => [
                    [
                        'id' => (string)$wajibPajak->id,
                        'price' => (int)$tagihan->jumlah,
                        'quantity' => 1,
                        'name' => $wajibPajak->nop,
                        'subtotal' => (int)$tagihan->jumlah,
                    ]
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
            $paymentType = $request->input('payment_type');
            $fraudStatus = $request->input('fraud_status');

            Log::info('Extracted data from notification:', compact(
                'orderId', 'statusCode', 'grossAmount', 'transactionStatus', 'paymentType', 'fraudStatus'
            ));

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

            $existing = MidtransTransaction::where('order_id', $orderId)->first();
            if (!$existing) {
                $midtransTransaction = new MidtransTransaction();
                $midtransTransaction->tagihan_id = $tagihan->id;
                $midtransTransaction->order_id = $orderId;
                $midtransTransaction->transaction_status = $transactionStatus;
                $midtransTransaction->gross_amount = $grossAmount;
                $midtransTransaction->payment_type = $paymentType;
                $midtransTransaction->fraud_status = $fraudStatus;
                $midtransTransaction->save();
            }


            if (in_array($transactionStatus, ['settlement', 'capture'])) {
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
            } else {

                Log::info('Transaksi dengan status lain:', ['transaction_status' => $transactionStatus]);
            }

            return response()->json(['message' => 'success'], 200);

        } catch (\Exception $e) {
            Log::error('Error processing notification: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to process notification'], 500);
        }
    }

}
