<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Biodata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function index(Request $request)
    {
        return view('dashboard.user.index');
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
            'nama' => 'required|string',
            'email' => 'required|email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
            'no_hp' => 'required|string',
            'tanggal_lahir' => 'required|string|date_format:d/m/Y',
            'jenis_kelamin' => 'required|string',
            'alamat' => 'required|string',
            'file_upload' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $tanggalLahir = Carbon::createFromFormat('d/m/Y', $request->input('tanggal_lahir'))->format('Y-m-d');

        $filePath = null;

        if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            $extension = $file->getClientOriginalExtension();
            $encryptedName = md5($file->getClientOriginalName() . time()) . '.' . $extension;

            $dir = 'storage/image-profile/';
            $relativePath = $dir . $encryptedName;

            $fullPath = public_path($relativePath);
            if (!File::exists(dirname($fullPath))) {
                File::makeDirectory(dirname($fullPath), 0755, true);
            }

            $file->move(dirname($fullPath), $encryptedName);

            $filePath = $relativePath;
        }

        DB::beginTransaction();

        try {
            $user = new User();
            $user->email = $request->input('email');
            $user->username = $request->input('username');
            $user->password = bcrypt($request->input('password'));
            $user->photo = $filePath;
            $user->save();

            $profile = new Biodata();
            $profile->user_id = $user->id;
            $profile->nama = $request->input('nama');
            $profile->no_hp = $request->input('no_hp');
            $profile->tanggal_lahir = $tanggalLahir;
            $profile->jenis_kelamin = $request->input('jenis_kelamin');
            $profile->alamat = $request->input('alamat');
            $profile->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy($id)
{
    DB::beginTransaction();

    try {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        if (File::exists(public_path($user->photo))) {
            File::delete(public_path($user->photo));
        }

        if ($user->biodata) {
            $user->biodata->delete(); 
        }

        $user->delete();

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => "Data successfully deleted"
        ]);
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     */
    public function datatable(Request $request)
    {
        $data = Biodata::with('user')->get();

        return datatables()->of($data)->make(true);
    }
}
