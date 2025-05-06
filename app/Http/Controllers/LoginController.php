<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {

            $request->validate([
                'username' => 'required',
                'password' => 'required|min:6',
            ]);

            $credentials = [
                'username' => $request->username,
                'password' => $request->password,
            ];

            if (Auth::attempt($credentials)) {

                return redirect()->intended('/super-admin/dashboard')->with('success', 'Login berhasil!');

            } else {
                return redirect()->route("login")->with("error", "Gagal Login");
            }

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }
}
