<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cicil_kompen;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CicilKompenController extends Controller
{
    public function DashboardCicil()
    {

        try {
            $cicil = Cicil_kompen::select('jenis_kompen', 'tgl_cicil', 'jlh_jam_konversi')->get();
            $totalJamKonversi = Cicil_kompen::sum('jlh_jam_konversi');
            return response()->json([
                'status' => 200,
                'CicilAll' => $cicil,
                'TotalJamKonversi' => $totalJamKonversi
                
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th ->getCode());
            }
        }

        //tambah data Cicil Kompen
       public function tambahCicilKompen(Request $request)
       {
            $validator = Validator::make($request->all(), [
                'id_cicil' => 'required|integer',
                'id_kompen' => 'required|integer',
                'id_tahun_ajar' => 'required|integer',
                'id_mahasiswa' => 'required|integer',
                'tgl_cicil' => 'required|date',
                'jlh_jam_konversi' => 'required|integer',
                'jenis_kompen' => 'required|string|max:255',
                'status' => 'required|in:1,2,3',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                ], 422);
            }

            try {
                // $cicil = new Cicil_kompen();
                // $cicil->id_kompen = $request->id_kompen;
                // $cicil->id_tahun_ajar = $request->id_tahun_ajar;
                // $cicil->id_mahasiswa = $request->id_mahasiswa;
                // $cicil->tgl_cicil = $request->tgl_cicil;
                // $cicil->jlh_jam_konversi = $request->jlh_jam_konversi;
                // $cicil->jenis_kompen = $request->jenis_kompen;
                // $cicil->status = $request->status;
                // $cicil->save();
            
                // Buat entri Cicil Kompen baru
            $data = Cicil_kompen::create([
                'id_kompen' => $request->id_kompen,
                'id_tahun_ajar' => $request->id_tahun_ajar,
                'id_mahasiswa' => $request->id_mahasiswa,
                'tgl_cicil' => $request->tgl_cicil,
                'jlh_jam_konversi' => $request->jlh_jam_konversi,
                'jenis_kompen' => $request->jenis_kompen,
                'status' => $request->status,
            ]);
            return response()->json([
                'status' => 201,
                'message' => 'Data cicilan kompensasi berhasil ditambahkan',
                'data' => $data
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th->getCode() ?: 400);
        }
    }
       


       //update data Cicil Kompen
       public function updateCicilKompen(Request $request, $id)
       {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'id_cicil' => 'required|integer',
                'id_kompen' => 'required|integer',
                'id_tahun_ajar' => 'required|integer',
                'id_mahasiswa' => 'required|integer',
                'tgl_cicil' => 'required|date',
                'jlh_jam_konversi' => 'required|integer',
                'jenis_kompen' => 'required|string|max:255',
                'status' => 'required|in:1,2,3',
            ]); 

            // Cek jika validasi gagal
           if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        try {
            $cicil = Cicil_kompen::findOrFail($id);
            $cicil->id_kompen = $request->id_kompen;
            $cicil->id_tahun_ajar = $request->id_tahun_ajar;
            $cicil->id_mahasiswa = $request->id_mahasiswa;
            $cicil->tgl_cicil = $request->tgl_cicil;
            $cicil->jlh_jam_konversi = $request->jlh_jam_konversi;
            $cicil->jenis_kompen = $request->jenis_kompen;
            $cicil->status = $request->status;
            $cicil->save();

            return response()->json([
                'status' => 200,
                'message' => 'Data cicilan kompensasi berhasil diperbarui',
                'data' => $cicil
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th->getCode() ?: 400);
        }
    }

        //Hapus data
        public function deleteCicilKompen(Request $request, $id)
        {
            try {
                $cicil = Cicil_kompen::where('id_cicil', $id)->firstOrFail();
                $cicil->delete();

                return response()->json([
                    'message' => 'Berhasil menghapus cicil kompen',
                ], 200);
            } catch (\Exception $e) {
                // Log the error for debugging purposes
                Log::error('Error deleting class: ' . $e->getMessage());
        
                return response()->json([
                    'message' => 'Tidak berhasil menghapus cicil kompen',
                ], 500);


            }

        }
    }


    