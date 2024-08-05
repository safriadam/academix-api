<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Presensi;

class PresensiController extends Controller
{
    public function rekapPermingguDosen(Request $request)
    {
//         $rekap = DB::statement("
//     CREATE VIEW tb_mingguke AS
//     SELECT 
//         week(tgl, 3) - tminggu.minggu AS minggu_ke,
//         date_sub(min(tgl), interval dayofweek(min(tgl)) - 2 day) AS tgl_mulai,
//         date_add(max(tgl), interval 7 - dayofweek(max(tgl)) - 1 day) AS tgl_akhir
//     FROM 
//         presensis
//     JOIN 
//         tminggu ON -- Define the relationship between presensis and tminggu here
//     GROUP BY 
//         week(tgl, 3)
// ");
    
    try {
        $rekap = Presensi::all();
        return response()->json([
            'status' => 200,
            'Rekap' => $rekap
        ], 200);
    } catch (\Throwable $th){
        return response()->json([
            "error" => $th->getMessage()
        ], $th->getCode());
    }
        return $rekap;
        }
}
