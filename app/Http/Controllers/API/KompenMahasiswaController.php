<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kompen_mahasiswa;
use Illuminate\Support\Facades\DB;

class KompenMahasiswaController extends Controller
{
    public function DashboardKompen()
    {
        try {
            // Mengambil data dari tabel kompen_mahasiswa
            $data = DB::table("kompen_mahasiswa")
                ->join("matkul", "kompen_mahasiswa.Id_matkul", "=", "matkul.Id_matkul")
                ->select("matkul.nama_matkul as Mata kuliah", "kompen_mahasiswa.jumlah_kompen", "kompen_mahasiswa.tgl_kompen")
                ->get();

            // Menghitung total kompen
            $totalkompen = Kompen_mahasiswa::sum("jumlah_kompen");

            // Mengembalikan response dalam format JSON
            return response()->json([
                'status' => 200,
                'KompenAll' => $data,
                'Totalkompen' => $totalkompen,
            ], 200);
        } catch (\Throwable $th) {
            // Default kode status HTTP untuk kesalahan server
            $statusCode = is_int($th->getCode()) && $th->getCode() >= 100 && $th->getCode() <= 599 ? $th->getCode() : 500;

            return response()->json([
                "error" => $th->getMessage(),
            ], $statusCode);
        }
    }
    
}