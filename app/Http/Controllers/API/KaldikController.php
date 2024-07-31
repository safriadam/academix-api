<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\Kaldik;

class KaldikController extends Controller
{
    public function dashboardKaldik()
    {
        
                 
        try {
            $kaldik = DB::table('kaldiks')
            ->where('tahun',Carbon::now()->format('Y'))->get();      
            return response()->json([
                'status' => 200,
                'all_kaldik' => $kaldik
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th->getCode());
        }
    }
}
