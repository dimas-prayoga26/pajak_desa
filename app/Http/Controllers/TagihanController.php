<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\WajibPajak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

        // SUPER ADMIN
        if ($user->hasRole('superAdmin')) {
            $nop = $request->nop;

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


        // WARGA
        elseif ($user->hasRole('warga')) {
            $query = Tagihan::with('wajibPajak.user.biodata')
                ->whereHas('wajibPajak', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
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

        $data = WajibPajak::query()
            ->where('nop', 'like', "%{$search}%")
            ->get();

        $formatted = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nop // <-- PENTING: gunakan key 'text'
            ];
        });

        return response()->json($formatted);
    }





}
