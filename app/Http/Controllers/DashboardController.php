<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Tagihan;
use App\Models\WajibPajak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isWarga = $user->hasRole('warga');



        $totalUser = User::count();
        $totalWajibPajak = WajibPajak::count();
        $totalHariIni = Tagihan::where('status_bayar', 'dikonfirmasi')
            ->whereDate('updated_at', Carbon::today())
            ->sum('jumlah');
        $totalKeseluruhan = Tagihan::where('status_bayar', 'dikonfirmasi')->sum('jumlah');

        if ($isWarga) {

            $wajibPajakQuery = WajibPajak::where('user_id', $user->id);
            $nopSubscribed = $wajibPajakQuery->count();


            $tagihanQuery = Tagihan::whereIn('wajib_pajak_id', $wajibPajakQuery->pluck('id'));

            $jumlahSudahBayar = $tagihanQuery
            ->whereIn('status_bayar', ['dibayar', 'dikonfirmasi'])
            ->count();

            $jumlahBelumBayar = $tagihanQuery->where('status_bayar', 'belum')->count();
        } else {

            $nopSubscribed = WajibPajak::count();
            $jumlahSudahBayar = Tagihan::where('status_bayar', 'dibayar')->count();
            $jumlahBelumBayar = Tagihan::where('status_bayar', 'belum')->count();
        }

        return view('dashboard.index', compact(
            'totalUser',
            'totalWajibPajak',
            'totalHariIni',
            'totalKeseluruhan',
            'nopSubscribed',
            'jumlahSudahBayar',
            'jumlahBelumBayar'
        ));
    }

    public function searchNop(Request $request)
    {
        $query = $request->nop;


        $results = WajibPajak::where('nop', 'like', '%' . $query . '%')->get();

        return response()->json($results);
    }

    public function authLogout()
    {
        Auth::logout();

        return redirect()->route("login")->with("success", "Logout Berhasil");
    }

    public function updateUser(Request $request)
{
        $request->validate([
            'nop' => 'required|string',
            'mode' => 'required|in:subscribe,unsubscribe',
        ]);

        try {
            DB::beginTransaction();

            $pajak = WajibPajak::where('nop', $request->nop)->first();

            if (!$pajak) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data dengan NOP tersebut tidak ditemukan.'
                ], 404);
            }

            if ($request->mode === 'subscribe') {
                $pajak->user_id = auth()->id();
            }


            if ($request->mode === 'unsubscribe') {
                if ((int)$pajak->user_id !== (int)auth()->id()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Anda tidak memiliki hak untuk membatalkan kepemilikan data ini.'
                    ], 403);
                }

                $pajak->user_id = null;
            }

            $pajak->save();
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => $request->mode === 'subscribe'
                    ? 'Kepemilikan berhasil ditambahkan.'
                    : 'Kepemilikan berhasil dibatalkan.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update user_id wajib pajak: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data.'
            ], 500);
        }
    }

}
