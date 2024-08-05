<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cicil_kompen;

class CicilKompenController extends Controller
{
    public function DashboardCicil()
    {
       
        try{
            $cicil = Cicil_kompen::all();
            return response()->json([
                'status' => 200,
                'CicilAll' => $cicil
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th ->getCode());
            }
        }
}
