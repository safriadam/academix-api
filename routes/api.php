<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\DosenController;
use App\Http\Controllers\API\MahasiswaController;
use App\Http\Controllers\API\KelasController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\RegisterController;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * route "/register"    
 * @method "POST"
 */
Route::post('/register', RegisterController::class)->name('register');

/**
 * route "/login"
 * @method "POST"
 */
Route::post('/login', LoginController::class)->name('login');

/**
 * route "/user"
 * @method "GET"
 */
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post("/logout", LogoutController::class)->name("logout");

//Admin
Route::get("/Dashboard-Admin", AdminController::class);

//Dosen / Kaprodi
Route::get("/Dashboard-Dosen", DosenController::class);
// Route::post("/Dosen-Kelas/{nidn}/{id_jdwl}", [DosenController::class, "generateTokenKelas"]);

//Mahasiswa
Route::get("/Dashboard-Mahasiswa", MahasiswaController::class);
Route::post("/validasi-token-kelas/{id_jdwl}/{token}/{nomor_induk}", [MahasiswaController::class, "checkTokenValid"]);

//Kelas
Route::get("/Kelas-Dosen/{nidn}/{id_jdwl}",[KelasController::class,'kelasSaatIni']);