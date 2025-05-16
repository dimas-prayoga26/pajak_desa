<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class HistoryTagihanController extends Controller
{
    protected $detailHistory;

    public function __construct()
    {
        $this->detailHistory = new Tagihan();
    }

    public function index(Request $request)
    {
        $tahunPertama = Tagihan::min('tahun');
        $tahunPertama = $tahunPertama ?? now()->year;

        $tahunSekarang = now()->year;

        $tahunList = range($tahunPertama, $tahunSekarang);

        return view('dashboard.history.index', compact('tahunList'));
    }

    public function create()
    {

    }


    public function store(Request $request)
    {

    }


    public function show($id)
    {


    }


    public function edit(string $id)
    {

    }


    public function update(Request $request, $id)
    {

    }


    public function destroy($id)
    {

    }


    public function datatable(Request $request)
    {
        $query = Tagihan::with(['wajibPajak.user.biodata'])
            ->whereHas('wajibPajak', function($q) {
                $q->whereNotNull('user_id');
            })
            ->whereIn('status_bayar', ['dibayar', 'dikonfirmasi']);

            if ($request->filled('daterange')) {
                $dates = explode(' to ', $request->daterange);

            if (count($dates) === 2) {
                $start = Carbon::parse($dates[0])->startOfDay();
                $end = Carbon::parse($dates[1])->endOfDay();

                $query->whereBetween('updated_at', [$start, $end]);
            }
        }


        return datatables()->of($query->get())
        ->addColumn('tanggal', function ($row) {
            return $row->updated_at->format('d-m-Y'); // hanya tanggal
        })
        ->addColumn('waktu', function ($row) {
            return $row->updated_at->format('H:i'); // hanya jam:menit
        })
        ->addColumn('nama', function ($row) {
            return optional($row->wajibPajak->user->biodata)->nama ?? '-';
        })
        ->addColumn('nop', function ($row) {
            return optional($row->wajibPajak)->nop ?? '-';
        })
        ->addColumn('tahun', function ($row) {
            return $row->tahun;
        })
        ->addColumn('total', function ($row) {
            return $row->jumlah;
        })
        ->make(true);

    }





    public function getNopOptions(Request $request)
    {

    }
}
