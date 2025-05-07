<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use Illuminate\Http\Request;

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
        // $tahunList = WajibPajak::select('tahun')
        //     ->distinct()
        //     ->orderBy('tahun', 'desc')
        //     ->pluck('tahun')
        //     ->toArray();

        // $tahunTerpilih = $request->tahun ?? now()->year;

        // if (!in_array($tahunTerpilih, $tahunList)) {
        //     array_unshift($tahunList, $tahunTerpilih);
        // }

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

            $data = $this->detailTagihan->with('user.biodata')->find($id);

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
            'user_id' => 'required|exists:users,id',
            'nop' => 'required|string|max:100',
            'alamat' => 'required|string',
            'luas_bumi' => 'required|numeric',
            'luas_bangunan' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $this->detailTagihan->where("id", $id)->update([
                "user_id" => $request->user_id,
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
        $tahun = $request->tahun ?? now()->year;

        $data = WajibPajak::with('user.biodata')->get();
        return datatables()->of($data)->make(true);
    }



    public function getUserOptions(Request $request)
    {
        $search = $request->input('q');

        $users = User::role('warga') // ⬅️ hanya ambil user dengan role warga
            ->with('biodata')
            ->whereHas('biodata', function ($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%");
            })
            ->get();

        $formatted = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'nama' => optional($user->biodata)->nama ?? $user->email,
            ];
        });

        return response()->json($formatted);
    }
}
