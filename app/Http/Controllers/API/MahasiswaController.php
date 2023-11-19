<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Presensi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    public function __invoke()
    {
    }

    public function checkTokenValid(Request $request)
    {
        $nomor_induk = $request->query('nomor_induk');
        $Jadwal = Jadwal::where("id_jdwl", $request->id_jdwl)->first();
        $inputToken = Jadwal::where("token", $request->token)->first();
        if ($Jadwal !== null) {
            if ($inputToken !== null) {
                try {
                    if ($Jadwal->token == $inputToken->token) {
                        if (now()->lt($inputToken->expires_at)) {
                            // Update Presensi records for the given id_mhs
                            $presensiToUpdate = DB::table('presensis')
                                ->join('mahasiswas', 'presensis.id_mhs', '=', 'mahasiswas.id_mhs')
                                ->join('kelas', 'mahasiswas.id_kls', '=', 'kelas.id_kls')
                                ->join('jadwals', 'kelas.id_kls', '=', 'jadwals.id_kls')
                                ->where('mahasiswas.nim', '=', $nomor_induk)
                                ->where('jadwals.id_jdwl', '=', $Jadwal->id_jdwl)
                                ->where('presensis.tgl', '=', date('Y-m-d'))
                                ->select('mahasiswas.id_mhs', 'jadwals.jumlah_jam')->get();
                            foreach ($presensiToUpdate as $key => $value) {
                                DB::table('presensis')
                                    ->join('mahasiswas', 'presensis.id_mhs', '=', 'mahasiswas.id_mhs')
                                    ->join('kelas', 'mahasiswas.id_kls', '=', 'kelas.id_kls')
                                    ->join('jadwals', 'kelas.id_kls', '=', 'jadwals.id_kls')
                                    ->where('mahasiswas.nim', '=', $nomor_induk)
                                    ->where('jadwals.id_jdwl', '=', $Jadwal->id_jdwl)
                                    ->where('presensis.tgl', '=', date('Y-m-d'))
                                    ->update([
                                        'kehadiran' => $value->jumlah_jam,
                                        'ketidakhadiran' => 0
                                    ]);
                            }

                            return response()->json([
                                "status" => "202",
                                "token input" => $inputToken->token,
                                "message" => "Kehadiran berhasil disimpan",
                                "mahasiswas" => $presensiToUpdate
                            ], 202);
                        } else {
                            return response()->json([
                                "status" => "400",
                                "token input" => $inputToken->token,
                                "message" => "Token expired"
                            ], 408);
                        }
                    } else {
                        return response()->json([
                            "status" => "400",
                            "token input" => $inputToken->token,
                            "message" => "Token invalid, Silahkan hubungi dosen atau staff"
                        ], 408);
                    }
                } catch (\Throwable $th) {
                    return response()->json([
                        "status" => "500",
                        "message" => "Error: " . $th->getMessage(),
                    ], 500);
                }
            } else {
                return response()->json([
                    "status" => "400",
                    "message" => "Input token not found or invalid",
                ], 400);
            }
        } else {
            return response()->json([
                "status" => "404",
                "message" => "Jadwal not found",
            ], 404);
        }
    }
}
