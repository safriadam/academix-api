<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sp;

class SpController extends Controller
{
    public function DashboardSp()
    {
        try{

            $sp_data = Sp::all();
            return response()->json([
                'status' => 200,
                'all_sp' => $sp_data
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th->getCode());
        

    }

    }
}
