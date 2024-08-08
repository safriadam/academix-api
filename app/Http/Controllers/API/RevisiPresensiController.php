<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevisiPresensiController extends Controller
{
    public function DashboardRevisiPresensi()
    {
        try {
            // Fetch data from the Revisi_Presensi table with related data
            $revisi = DB::table('revisi_presensi')
                ->join('mahasiswas', 'mahasiswas.nim', '=', 'mahasiswas.nim') // Correct the join condition
                ->join('matkul', 'matkul.id_matkul', '=', 'matkul.id_matkul') // Check if id_matkul exists
                ->join('presensis', 'revisi_presensi.id_presensi', '=', 'presensis.id_presensi')
                ->select(
                    'mahasiswas.nim',
                    'mahasiswas.nama as Nama_mahasiswa', // Ensure this column exists in mahasiswas table
                    'matkul.nama_matkul as Mata_kuliah',
                    'presensis.status as keterangan'
                )
                ->get();

            // Return response as JSON
            return response()->json([
                'status' => 200,
                'RevisiPresensi' => $revisi
            ], 200);
        } catch (\Throwable $th) {
            // Handle exceptions and return error message
            $statusCode = is_int($th->getCode()) && $th->getCode() >= 100 && $th->getCode() <= 599 ? $th->getCode() : 500;

            return response()->json([
                "error" => $th->getMessage()
            ], $statusCode);
        }
    }
}