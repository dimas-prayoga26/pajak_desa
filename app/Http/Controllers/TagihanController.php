<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\WajibPajak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TagihanController extends Controller
{

    protected $detailTagihan;

    public function __construct()
    {
        $this->detailTagihan = new Tagihan();
    }

    public function index(Request $request)
    {


        return view('dashboard.tagihan.index');
    }




    public function create()
    {

    }


    public function store(Request $request)
    {
        $request->merge([
            'jumlah' => (int) str_replace(['Rp', '.', ',', ' '], '', $request->jumlah)
        ]);

        $validator = Validator::make($request->all(), [
            'wajib_pajak_id' => 'required|exists:wajib_pajaks,id',
            'tahun' => 'required|digits:4|integer',
            'jumlah' => 'required|numeric|min:1',
            'jatuh_tempo' => 'required|date|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $existingTagihan = WajibPajak::where('id', $request->wajib_pajak_id)
                ->where('status_bayar', 'dibayar')
                ->first();

            if ($existingTagihan) {
                $existingTagihan->update([
                    'status_bayar' => 'belum'
                ]);
            }

            Tagihan::create([
                'wajib_pajak_id' => $request->wajib_pajak_id,
                'tahun' => $request->tahun,
                'jumlah' => $request->jumlah,
                'jatuh_tempo' => $request->jatuh_tempo,
                'status_bayar' => 'belum'
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Tagihan berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan tagihan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function show($id)
    {

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


    public function edit(string $id)
    {

    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();


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
        $nop = $request->nop;

        if ($user->hasRole('superAdmin')) {
            if (strlen($nop) !== 18) {
                return datatables()->of([])->make(true);
            }

            $exists = WajibPajak::where('nop', 'like', '%' . $nop . '%')->exists();

            if (!$exists) {
                $data = datatables()->of([])->make(true)->getData(true);
                return response()->json(array_merge($data, [
                    'status' => false,
                    'type' => 'error',
                    'message' => 'NOP tidak ditemukan.'
                ]));
            }

            $query = Tagihan::with('wajibPajak.user.biodata')
                ->whereHas('wajibPajak', function ($q) use ($nop) {
                    $q->where('nop', 'like', '%' . $nop . '%');
                });

            return datatables()->of($query)
                ->addIndexColumn()
                ->make(true);
        }

        // ✅ Perbaiki bagian warga → pakai juga NOP untuk filter
        if ($user->hasRole('warga')) {
            // dd($nop);
            if (!$nop) {
                return datatables()->of([])->make(true); // kalau belum pilih NOP
            }

            $query = Tagihan::with('wajibPajak.user.biodata')
                ->whereHas('wajibPajak', function ($q) use ($user, $nop) {
                    $q->where('user_id', $user->id)
                    ->where('nop', 'like', '%' . $nop . '%'); // filter berdasarkan NOP juga
                });

            return datatables()->of($query)
                ->addIndexColumn()
                ->make(true);
        }

        return datatables()->of([])->make(true);
}


    public function getNopOptions(Request $request)
    {
        $search = $request->input('q');
        $user = Auth::user();

        // dd($request);

        $query = WajibPajak::query();

        if ($user->hasRole('warga')) {
            $query->where('user_id', $user->id);
        }

        if ($search) {
            $query->where('nop', 'like', "%{$search}%");
        }

        $data = $query->limit(10)->get();


        $formatted = $data->map(function ($item) use ($user) {
            if ($user->hasRole('superAdmin')) {

                return [
                    'id' => $item->id,
                    'text' => $item->nop
                ];
            } else {

                return [
                    'id' => $item->nop,
                    'text' => $item->nop
                ];
            }
        });

        return response()->json($formatted);
    }



}
