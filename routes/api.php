<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\DosenController;
use App\Http\Controllers\API\JadwalController;
use App\Http\Controllers\API\MahasiswaController;
use App\Http\Controllers\API\KelasController;
use App\Http\Controllers\API\PresensiController;
use App\Http\Controllers\API\KaldikController;
use App\Http\Controllers\API\KompenMahasiswaController;
use App\Http\Controllers\API\SpController;
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
    // Jadwal - Admin
Route::get("/Dashboard-Jadwal", [JadwalController::class, 'DashboardJadwal']);

//Dosen / Kaprodi
Route::get("/Dashboard-Dosen-Profil", [DosenController::class, 'profilDosen']);
Route::get("/Dashboard-Dosen-Jadwal-Hari-Ini", [DosenController::class, 'jadwalHariIniDosen']);
Route::get("/Dashboard-Dosen-Konfirm-Mahasiswa", [DosenController::class, 'konfirmMahasiswa']);
Route::get('/Dashboard-Tolak-Surat', [DosenController::class, 'tolakSurat']);
Route::get("/Kelas-Data", [KelasController::class, 'dataKelasDosen']);
Route::get("/Kelas-Generate-Token-Kelas", [KelasController::class, 'generateTokenKelas']);
Route::get("/Kelas-Tabel-kelas-Dosen", [KelasController::class, 'kelasSaatIniDosen']);
Route::get("/Kelas-Edit-Kehadiran-Mahasiswa", [KelasController::class, 'editKehadiranMhs']);
Route::get("/Kelas-Tutup-Kelas", [KelasController::class, 'tutupKelas']);
Route::get("/Presensi-Dosen-Perminggu", [PresensiController::class, 'rekapPermingguDosen']);

//Mahasiswa
Route::get("/Dashboard-Mahasiswa", [MahasiswaController::class,'profilMahasiswa']);
Route::get("/Dashboard-Mahasiswa-Konfirmasi-Kehadiran-Anda", [MahasiswaController::class, 'daftarKetidakhadiranMhs']);
Route::get("/Dashboard-Kirim-Surat", [MahasiswaController::class, 'kirimSuratKetidakhadiran']);
Route::post("/Kelas-Validasi-Token", [MahasiswaController::class, 'checkTokenValid']);
Route::get("/Kelas-Mahasiswa", [KelasController::class, 'kelasSaatIniMahasiswa']);

//Jadwal
Route::get("/Dashboard-Mahasiswa-Jadwal-Harini", [JadwalController::class, 'jadwalHariIniMhs']);
    //admin
Route::get("/Dashboard-Jadwal", [JadwalController::class, 'dashboardJadwal']);
Route::post("/Simpan-Jadwal", [JadwalController::class, 'simpanJadwal']);
Route::patch("/Update-Jadwal", [JadwalController::class, 'updateJadwal']);
Route::delete("/Delete-Jadwal", [JadwalController::class, 'deleteJadwal']);


//Manajemen Kelas
Route::get("/Dashboard-Kelas", [KelasController::class, 'dashboardKelas']);
Route::get("/tabel-kelas", [KelasController::class, 'tableKelas']);
Route::put("/updatetable-Kelas/{id_kls}", [KelasController::class, 'updatetableKelas']);
Route::post("/createtable-Kelas", [KelasController::class, 'createtableKelas']);
Route::delete('/deletetable-Kelas/{id_kls}', [KelasController::class, 'deletetableKelas']);

//Kaldik
Route::get("/Dashboard-Kaldik", [KaldikController::class, 'dashboardKaldik']);
Route::post("/Tambah-Kaldik", [KaldikController::class, 'tambahKaldik']);
Route::patch('/Update-Kaldik', [KaldikController::class, 'updateKaldik']);
Route::delete('/Delete-Kaldik', [KaldikController::class, 'deleteKaldik']);

//Surat Peringatan
Route::get('/Dashboard-sp',[SpController::class, 'Dashboardsp']); 

//Kompensasi
Route::get('/Dashboard-kompen',[KompenMahasiswaController::class,'DashboardKompen']);