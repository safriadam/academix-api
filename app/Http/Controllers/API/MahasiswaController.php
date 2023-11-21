<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Presensi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MahasiswaController extends Controller
{
    public function profilMahasiswa(Request $request)
    {
        $nim = $request->nomor_induk;
        try {
            $Mahasiswa = Mahasiswa::select('nim', 'nama', 'foto')
                ->where('nim', $nim)->get();
            return response()->json([
                'status' => 200,
                $Mahasiswa
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th->getCode());
        }
    }
    public function daftarKetidakhadiranMhs(Request $request)
    {
        $nim = $request->nomor_induk;

        // try {
        $konfirmKehadiran = DB::table('mahasiswas')
            ->join('kelas', 'mahasiswas.id_kls', '=', 'kelas.id_kls')
            ->join('presensis', 'mahasiswas.id_mhs', '=', 'presensis.id_mhs',)
            ->join('ket_mhs', 'presensis.id_presensi', '=', 'ket_mhs.id_presensi')
            ->join('logs', 'presensis.id_tahun_ajar', '=', 'logs.id_tahun_ajar')
            ->join('dosens', 'logs.id_dosen', '=', 'dosens.id_dosen')
            // ->where('mahasiswas.nim', '=', $nim)
            // ->where('presensis.status', '=', 'A')
            // ->where('ket_mhs.status_confirm', '=', '0')
            // ->where('surat_bukti', '<>', null)
            // ->whereNotNull('surat_bukti')
            ->select('presensis.id_presensi', 'presensis.status', 'mahasiswas.nama', 'mahasiswas.nim', 'kelas.smt', 'kelas.abjad_kls')
            ->get();
        // ->insert();
        $limitSurat = now()->addDays(2);
        foreach ($konfirmKehadiran as $value) {
            if ($konfirmKehadiran) {
                DB::table('ket_mhs')
                    ->insert([
                        'id_presensis' => $value->id_presensi,
                        'status_confirm' => 0,
                        'surat_bukti' => 'null',
                        'deskripsi' => 'null',
                        'limit_surat' => $limitSurat,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
            }
        }
        return response()->json([
            'status' => 200,
            'daftarKetidakHadiran' => $konfirmKehadiran
        ], 200);
        // } catch (\Throwable $th) {
        //     return response()->json([
        //         "error" => $th->getMessage(),
        //     ], $th->getCode());
        // }
    }
    public function kirimSuratKetidakhadiran(Request $request)
    {
        $nim = $request->nomor_induk;
        $sts = $request->status;
        $alamatsurat = $request->surat;
        $keterangan = $request->keterangan;
        // try {
        $kirimKetidakhadiran = DB::table('mahasiswas')
            ->join('kelas', 'mahasiswas.id_kls', '=', 'kelas.id_kls')
            ->join('presensis', 'mahasiswas.id_mhs', '=', 'presensis.id_mhs')
            ->join('logs', 'presensis.id_tahun_ajar', '=', 'logs.id_tahun_ajar')
            ->join('dosens', 'logs.id_dosen', '=', 'dosens.id_dosen')
            ->join('ket_mhs', 'presensis.id_presensi', '=', 'ket_mhs.id_presensi')
            ->where('mahasiswas.nim', '=', $nim)
            ->where('presensis.status', '=', 'A')
            ->where('ket_mhs.status_confirm', '=', '0')
            ->select('presensis.id_presensi', 'presensis.status', 'mahasiswas.nama', 'mahasiswas.nim', 'kelas.smt', 'kelas.abjad_kls')->get();
        if ($kirimKetidakhadiran) {
            $insertKetidakHadiran = DB::table('ket_mhs')
                ->insert([
                    'ket_mhs.status_confirm' => 0,
                    'ket_mhs.surat_bukti' => $alamatsurat,
                    'ket_mhs.deskripsi' => $keterangan,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            $updatepresensi = DB::table('presensis')
                ->join('mahasiswas', 'presensis.id_mhs', '=', 'mahasiswas.id_mhs')
                ->where('mahasiswas.nim', '=', $nim)->update([
                    'status' => $sts
                ]);
        } else {
        }
        return response()->json([
            'status' => 200,
            'daftarKetidakHadiran' => $kirimKetidakhadiran,
            'surat' => $insertKetidakHadiran,
            'status' => $updatepresensi
        ], 200);
        // } catch (\Throwable $th) {
        //     return response()->json([
        //         "error" => $th->getMessage(),
        //     ], $th->getCode());
        // }
    }
    public function jadwalHariIniMhs(Request $request)
    {
        $nim = $request->nomor_induk;
        try {
            $date = Carbon::parse(now())->locale('id');
            $date->settings(['formatFunction' => 'translatedFormat']);
            $day = $date->format('l');

            $jadwalHariIni = DB::table('jadwals')
                ->join('logs', 'jadwals.id_jdwl', '=', 'logs.id_jdwl')
                ->join('dosens', 'logs.id_dosen', '=', 'dosens.id_dosen')
                ->join('matkuls', 'jadwals.id_mk', '=', 'matkuls.id_mk')
                ->join('kelas', 'jadwals.id_kls', '=', 'kelas.id_kls')
                ->join('mahasiswas', 'kelas.id_kls', '=', 'mahasiswas.id_kls')
                ->where('jadwals.hari', '=', $day)
                ->orderBy('jadwals.start')
                ->whereRaw('jadwals.finish > curtime() ')
                ->where('mahasiswas.nim', '=', $nim)
                ->select('jadwals.id_jdwl', 'matkuls.nama as Mata Kuliah', DB::raw('CONCAT(TIME_FORMAT(jadwals.start, "%H.%i"), " - ",TIME_FORMAT(jadwals.finish , "%H.%i")) AS Waktu'), 'kelas.smt', 'kelas.abjad_kls', 'jadwals.ruang', 'jadwals.jumlah_jam')
                ->get();
            return response()->json([
                'status' => '200',
                'jadwalHariIni' => $jadwalHariIni
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th->getCode());
        }
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
                                ->select('mahasiswas.id_mhs', 'mahasiswas.nama', 'jadwals.jumlah_jam')->get();
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
                                        'ketidakhadiran' => 0,
                                        'status' => null
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
