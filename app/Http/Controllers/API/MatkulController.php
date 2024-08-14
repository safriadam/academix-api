<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Matkul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MatkulController extends Controller
{
    public function dashboardMatkul()
    {
        try {
            $matkul_all = DB::table('matkuls')
                ->get();
            return response()->json([
                'status' => 200,
                "jadwal_all" => $matkul_all
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th->getCode());
        }
    }
    public function tambahMatkul(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'kd_mk' => 'required|string|max:10',
            'nama' => 'required|string|max:255',
            'smt' => 'required|integer',
            'sks' => 'required|integer'
        ]);

        //if validation fails
          if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $matkul = Matkul::create([
                'kd_mk' => $request->kd_mk,
            'nama' => $request->nama,
            'smt' => $request->smt,
            'sks' => $request->sks,
            ]);

            return response()->json([
                'status' => '201',
                'success' => true,
                'matkul' => $matkul,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th->getCode());
        }
    }

    public function updateMatkul(Request $request)
{
    // Validasi input
    $validator = Validator::make($request->all(), [
        'id_mk' => 'required|integer|exists:matkuls,id_mk',
        'kd_mk' => 'required|string|max:10',
        'nama' => 'required|string|max:255',
        'smt' => 'required|integer',
        'sks' => 'required|integer'
    ]);

    // Jika validasi gagal
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    try {
        // Temukan data berdasarkan ID
        $matkul = Matkul::findOrFail($request->id_mk);

        // Perbarui data
        $matkul->update([
            'kd_mk' => $request->kd_mk,
            'nama' => $request->nama,
            'smt' => $request->smt,
            'sks' => $request->sks,
        ]);

        return response()->json([
            'status' => 200,
            'success' => true,
            'matkul' => $matkul,
        ], 200);
    } catch (\Throwable $th) {

        return response()->json([
            'error' => $th->getMessage()
        ], 500);
    }
}

public function deleteMatkul(Request $request)
    {
        // Validasi ID input
        $validator = Validator::make($request->all(), [
            'id_mk' => 'required|integer|exists:matkuls,id_mk'
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            // Temukan data berdasarkan ID dan hapus
            $matkul = Matkul::findOrFail($request->id_mk);
            $matkul->delete();

            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'Matkul berhasil dihapus',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }
    }

}