<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WajibPajakController;

Route::group(["middleware" => ["guest"]], function() {
    Route::prefix("auth")->group(function() {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    });
});


Route::middleware(['web', 'auth'])->group(function () {

    Route::prefix("auth")->group(function() {
        Route::post("/logout", [DashboardController::class, "authLogout"])->name("auth.logout");
    });

    Route::prefix("super-admin")->group(function() {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get("/detail-pajak/datatable", [WajibPajakController::class, "datatable"])->name("detail-pajak.datatable");
        Route::get('/detail-pajak/tagihan/{id}', [WajibPajakController::class, 'getByWajibPajak'])->name("detail-pajak.getByWajibPajak");
        Route::post("/detail-pajak/{id}/kirim", [WajibPajakController::class, "sendNotification"])->name("detail-pajak.notification");
        Route::get('/detail-pajak/user-options', [WajibPajakController::class, 'getUserOptions'])->name('detail-pajak.user-options');
        Route::resource("detail-pajak", WajibPajakController::class);

        Route::get("/detail-tagihan/datatable", [TagihanController::class, "datatable"])->name("detail-tagihan.datatable");
        Route::resource("detail-tagihan", TagihanController::class);

        Route::get("/user/datatable", [UserController::class, "datatable"])->name("user.datatable");
        Route::resource("user", UserController::class);
    });
});