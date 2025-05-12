<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tagihan;
use App\Models\WajibPajak;
use App\Models\detailPajak;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class WajibPajakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $detailPajak;

    public function __construct()
    {
        $this->detailPajak = new WajibPajak();
    }

    public function index(Request $request)
    {
        // $tahunList = WajibPajak::select('tahun')
        //     ->distinct()
        //     ->orderBy('tahun', 'desc')
        //     ->pluck('tahun')
        //     ->toArray();

        // $tahunTerpilih = $request->tahun ?? now()->year;

        // if (!in_array($tahunTerpilih, $tahunList)) {
        //     array_unshift($tahunList, $tahunTerpilih);
        // }

        return view('dashboard.pajak.index');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $wajibPajak = $this->detailPajak->create([
                'name' => $request->name,
                'nop' => $request->nop,
                'alamat' => $request->alamat,
                'luas_bumi' => $request->luas_bumi,
                'luas_bangunan' => $request->luas_bangunan,
                'status_bayar' => 'belum'
            ]);

            $wajibPajak->tagihans()->create([
                'jumlah' => $request->jumlah,
                'tahun' => $request->tahun,
                'jatuh_tempo' => $request->jatuh_tempo,
                'status_bayar' => 'belum'
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }




    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // dd($id);
        try {

            DB::beginTransaction();

            $data = $this->detailPajak->with('tagihans', 'user.biodata')->find($id);

            DB::commit();

            return response()->json([
                "status" => true,
                "message" => "Data Showed By ID Successfully",
                "data" => $data
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:30',
            'nop' => 'required|string|max:100',
            'alamat' => 'required|string',
            'luas_bumi' => 'required|numeric',
            'luas_bangunan' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $this->detailPajak->where("id", $id)->update([
                "name" => $request->name,
                "nop" => $request->nop,
                "alamat" => $request->alamat,
                "luas_bumi" => $request->luas_bumi,
                "luas_bangunan" => $request->luas_bangunan,
            ]);

            DB::commit();

            return response()->json([
                "status" => true,
                "message" => "Data berhasil diperbarui"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $detailPajak = $this->detailPajak->find($id);

            if (!$detailPajak) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $detailPajak->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function datatable(Request $request)
    {
        $data = WajibPajak::with('user.biodata')->get();

        return datatables()->of($data)->make(true);
    }



    // public function getUserOptions(Request $request)
    // {
    //     $search = $request->input('q');

    //     $users = User::role('warga')
    //         ->with('biodata')
    //         ->whereHas('biodata', function ($query) use ($search) {
    //             $query->where('nama', 'like', "%{$search}%");
    //         })
    //         ->get();

    //     // dd($users)
    //     $formatted = $users->map(function ($user) {
    //         return [
    //             'id' => $user->id,
    //             'nama' => optional($user->biodata)->nama ?? $user->email,
    //         ];
    //     });

    //     return response()->json($formatted);
    // }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:wajib_pajaks,id',
            'jumlah_tagihan' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $wajibPajak = WajibPajak::findOrFail($request->id);
            $wajibPajak->status_bayar = 'belum';
            $wajibPajak->save();

            Tagihan::create([
                'wajib_pajak_id' => $wajibPajak->id,
                'tahun' => date('Y'),
                'jumlah' => $request->jumlah_tagihan,
                'status_bayar' => 'belum',
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Tagihan berhasil dikirim.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getByWajibPajak($id)
    {
        $wajib = WajibPajak::with('user.biodata')->findOrFail($id);

        $tagihans = $wajib->tagihans()
            ->orderBy('tahun', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'nama' => optional($wajib->user->biodata)->nama ?? $wajib->user->email,
            'data' => $tagihans
        ]);
    }

    public function updatePaymentStatus($id)
    {
        try {
            DB::beginTransaction();

            $tagihan = Tagihan::findOrFail($id);

            $tagihan->status_bayar = 'dikonfirmasi';
            $tagihan->save();

            $wajibPajak = WajibPajak::findOrFail($tagihan->wajib_pajak_id);
            $wajibPajak->status_bayar = 'dibayar';
            $wajibPajak->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Status pembayaran berhasil dikonfirmasi.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Gagal mengupdate status: ' . $e->getMessage()
            ], 500);
        }
    }

    


}
