<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Revisi_Presensi;

class RevisiPresensiController extends Controller
{
    public function DashboardRevisiPresensi()
    {

        try {
            $revisi = Revisi_Presensi::all();
            return response()->json([
                'status' => 200,
                'RevisiPresensi' => $revisi
            ], 200);
    } catch (\Throwable $th){
        return response()->json([
            "error" => $th->getMessage()
        ], $th->getCode());
    }
    }
}
