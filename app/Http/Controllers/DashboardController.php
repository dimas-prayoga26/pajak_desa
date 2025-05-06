<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index'); // View untuk form login
    }

    public function authLogout()
    {
        Auth::logout();

        return redirect()->route("login")->with("success", "Logout Berhasil");
    }
}
