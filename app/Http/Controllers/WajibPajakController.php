<?php

namespace App\Http\Controllers;

use App\Models\WajibPajak;
use App\Models\detailPajak;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

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

    public function index()
    {
        return view("dashboard.pajak.index");
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

            $this->detailPajak->create([
                "name" => $request->name,
            ]);

            DB::commit();

            return response()->json([
                "status" => true,
                "message" => "Data Berhasil di Tambah"
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
     * Display the specified resource.
     */
    public function show($id)
    {
        // dd($id);
        try {

            DB::beginTransaction();

            $data = $this->detailPajak->where("id", $id)->first();

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
        try {

            DB::beginTransaction();

            $this->detailPajak->where("id", $id)->update([
                "name" => $request->name,
            ]);

            DB::commit();

            return response()->json([
                "status" => true,
                "message" => "Update Data Success"
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
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {

            DB::beginTransaction();

            $this->detailPajak->where("id", $id)->delete();

            DB::commit();

            return response()->json([
                "status" => true,
                "message" => "Delete Data Success"
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    public function datatable(Request $request)
    {
        $data = WajibPajak::orderBy('created_at')->get();

        return DataTables::of($data)->make();
    }
}
