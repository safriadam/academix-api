<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\berita_acara;
use Illuminate\Support\Facades\DB;

class BeritaAcaraController extends Controller
{
    public function getMhsData (String $id) {
        try {
            $data = Mahasiswa::where('id_mahasiswa', $id)->first();
            $berita = berita_acara::get('pkk_bhsn')->first();
            
            return response()->json([
                'status' => 200,
                'message' => 'Datadsklfnslkd berhasil diunggah',
                'Data Mahasiswa' => [
                    'Nama Mahasiswa' => $data->nama,
                    'NIM' => $data->nim,
                    'Kelas' => $data->id_kelas,
                    'Total Ketidakhadiran' => $data->ket_status,
                ],
                'Berita Acara' => $berita,
            ], 200);
        } catch (\Throwable $th) {
            $statusCode = is_int($th->getCode()) && $th->getCode() >= 100 && $th->getCode() <= 599 ? $th->getCode() : 500;

            return response()->json([
                "error" => $th->getMessage()
            ], $statusCode);
        }   
    }
}