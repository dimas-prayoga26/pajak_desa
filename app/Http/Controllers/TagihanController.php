<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $detailTagihan;

    public function __construct()
    {
        $this->detailTagihan = new Tagihan();
    }

    public function index(Request $request)
    {
        

        return view('dashboard.tagihan.index');
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
        $request->validate([
            'name' => 'required|exists:users,id',
            'nop' => 'required|string|max:100',
            'alamat' => 'required|string',
            'luas_bumi' => 'required|numeric',
            'luas_bangunan' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $this->detailTagihan->create([
                "user_id" => $request->name,
                "nop" => $request->nop,
                "alamat" => $request->alamat,
                "luas_bumi" => $request->luas_bumi,
                "luas_bangunan" => $request->luas_bangunan,
                "tahun" => now()->year, // ⬅️ Tambahkan tahun sekarang
            ]);

            DB::commit();

            return response()->json([
                "status" => true,
                "message" => "Data berhasil ditambahkan"
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
     * Display the specified resource.
     */
    public function show($id)
    {
        // dd($id);
        try {

            DB::beginTransaction();

            $data = $this->detailTagihan->with('wajibPajak.user.biodata')->find($id);

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
            'jumlah' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            // Update jumlah tagihan
            Tagihan::where('id', $id)->update([
                'jumlah' => $request->jumlah,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Jumlah tagihan berhasil diperbarui'
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
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $detailTagihan = $this->detailTagihan->find($id);

            if (!$detailTagihan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $detailTagihan->delete();

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
        $user = auth()->user();

        if ($user->hasRole('superAdmin')) {
            // Superadmin harus input NOP untuk pencarian
            if (!$request->filled('nop')) {
                return datatables()->of([])->make(true);
            }

            $query = Tagihan::with('wajibPajak.user.biodata')
                ->whereHas('wajibPajak', function ($q) use ($request) {
                    $q->where('nop', 'like', '%' . $request->nop . '%');
                });

            return datatables()->of($query)
                ->addIndexColumn()
                ->make(true);
        } 
        elseif ($user->hasRole('warga')) {
            // Warga langsung ambil data berdasarkan user yang login
            $query = Tagihan::with('wajibPajak.user.biodata')
                ->whereHas('wajibPajak', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });

            return datatables()->of($query)
                ->addIndexColumn()
                ->make(true);
        } 
        else {
            // Untuk role lain, kosongkan data (atau bisa pakai abort(403))
            return datatables()->of([])->make(true);
        }
    }

}
