<?php

namespace App\Http\Controllers;

use App\Models\WajibPajak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index'); // View untuk form login
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

            // dd(auth()->id());
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
