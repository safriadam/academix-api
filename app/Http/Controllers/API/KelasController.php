<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class KelasController extends Controller
{

    public function dashboardKelas()
    {
        // $nidn = $request->query('nomor_induk');
        try {
            $kelas_all = DB::table('kelas')
                ->get();
            return response()->json([
                'status' => 200,
                "kelas_all" => $kelas_all
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th->getCode());
        }
    }
    // ----------------------------------------------------------------DOSEN-----------------------------------------------------------------------------
    public function dataKelasDosen(Request $request)
    {
        // $user = Auth::user();

        // $task = Dosen::where('user_id', $user->id)->find($id);
        // // Update the task properties based on request data
        // if (!$task) {
        //     return response()->json(['error' => 'Task not found'], 404);
        // }
        // $task->update($request->all());

        // return response()->json($task);

        $id_jdwl = $request->query('id_jdwl');
        $nidn = $request->query('nomor_induk');
        try {
            $date = Carbon::parse(now())->locale('id');
            $date->settings(['formatFunction' => 'translatedFormat']);
            $day = $date->format('l');

            $kelasSaatIni = DB::table('jadwals')
                ->join('logs', 'jadwals.id_jdwl', '=', 'logs.id_jdwl')
                ->join('dosens', 'logs.id_dosen', '=', 'dosens.id_dosen')
                ->join('matkuls', 'jadwals.id_mk', '=', 'matkuls.id_mk')
                ->join('kelas', 'jadwals.id_kls', '=', 'kelas.id_kls')
                ->where('jadwals.hari', '=', $day)
                ->where('jadwals.id_jdwl', '=', $id_jdwl)
                ->whereRaw('jadwals.finish > curtime() ')
                ->where('dosens.nidn', '=', $nidn)
                ->select(
                    'logs.id_tahun_ajar',
                    'dosens.foto',
                    'dosens.nama',
                    'matkuls.nama as Mata Kuliah',
                    DB::raw(
                        'DATE_FORMAT(CURDATE(), "%e %M %Y") AS Tanggal
                        ,CONCAT(TIME_FORMAT(jadwals.start, "%H.%i"), " - ",TIME_FORMAT(jadwals.finish   , "%H.%i")) AS Waktu'
                    ),
                    'kelas.smt',
                    'kelas.abjad_kls',
                    'jadwals.jumlah_jam'
                )->get();
            return response()->json([
                'status' => 200,
                'DataKelas' => $kelasSaatIni
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th->getCode());
        }
    }
    public function generateTokenKelas(Request $request)
    {
        $id_jdwl = $request->query('id_jdwl');
        try {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $string = '';
            for ($i = 0; $i < 5; $i++) {
                $string .= $characters[rand(0, strlen($characters) - 1)];
            }

            $limitToken = now()->addMinutes(5);
            $Jadwal = Jadwal::where("id_jdwl", $id_jdwl)->first();
            $Jadwal->token = $string;
            $Jadwal->expires_at = $limitToken;
            $Jadwal->save();

            return response()->json([
                'status' => 200,
                'token' => $Jadwal->token,
                'expires_at' => $Jadwal->expires_at
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th->getCode());
        }
    }
    public function kelasSaatIniDosen(Request $request)
{
    $id_jdwl = $request->query('id_jdwl');
    $nidn = $request->query('nomor_induk');
    $date = Carbon::parse(now())->locale('id');
    $date->settings(['formatFunction' => 'translatedFormat']);
    $day = $date->format('l');

    $kelasSaatIni = DB::table('jadwals')
        ->join('logs', 'jadwals.id_jdwl', '=', 'logs.id_jdwl')
        ->join('dosens', 'logs.id_dosen', '=', 'dosens.id_dosen')
        ->join('matkuls', 'jadwals.id_mk', '=', 'matkuls.id_mk')
        ->join('kelas', 'jadwals.id_kls', '=', 'kelas.id_kls')
        ->where('jadwals.hari', '=', $day)
        ->where('jadwals.id_jdwl', '=', $id_jdwl)
        ->whereRaw('jadwals.finish > curtime()')
        ->where('dosens.nidn', '=', $nidn)
        ->select(
            'logs.id_tahun_ajar',
            'dosens.foto',
            'dosens.nama',
            'matkuls.nama as Mata Kuliah',
            DB::raw(
                'DATE_FORMAT(CURDATE(), "%e %M %Y") AS Tanggal,
                CONCAT(TIME_FORMAT(jadwals.start, "%H.%i"), " - ", TIME_FORMAT(jadwals.finish, "%H.%i")) AS Waktu'
            ),
            'kelas.smt',
            'kelas.abjad_kls',
            'jadwals.jumlah_jam'
        )->get();

    if (!$kelasSaatIni->isEmpty()) {
        $datamahasiswa = DB::table('mahasiswas')
            ->join('kelas', 'mahasiswas.id_kls', '=', 'kelas.id_kls')
            ->join('jadwals', 'kelas.id_kls', '=', 'jadwals.id_kls')
            ->join('logs', 'jadwals.id_jdwl', '=', 'logs.id_jdwl')
            ->join('dosens', 'logs.id_dosen', '=', 'dosens.id_dosen')
            ->where('jadwals.id_jdwl', '=', $id_jdwl)
            ->where('dosens.nidn', '=', $nidn)
            ->select('mahasiswas.id_mhs', 'jadwals.jumlah_jam')
            ->get();

        $tableKelas = DB::table('presensis')
            ->join('mahasiswas', 'presensis.id_mhs', '=', 'mahasiswas.id_mhs')
            ->join('kelas', 'mahasiswas.id_kls', '=', 'kelas.id_kls')
            ->join('jadwals', 'kelas.id_kls', '=', 'jadwals.id_kls')
            ->where('presensis.id_tahun_ajar', '=', $id_jdwl)
            ->where('presensis.tgl', '=', date('Y-m-d'))
            ->select(DB::raw('distinct presensis.*'))->get();

        if ($tableKelas->isEmpty()) {
            foreach ($datamahasiswa as $value) {
                DB::table('presensis')
                    ->insert([
                        'id_mhs' => $value->id_mhs,
                        'id_tahun_ajar' => $id_jdwl,
                        'id_jdwl' => $id_jdwl, // Sertakan id_jdwl di sini
                        'tgl' => date('Y-m-d'),
                        'start_kls' => date('H:i:s'),
                        'finish_kls' => null,
                        'kehadiran' => null,
                        'ketidakhadiran' => $value->jumlah_jam,
                        'status' => 'A',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
            }
            $tableKelas = DB::table('presensis')
                ->join('mahasiswas', 'presensis.id_mhs', '=', 'mahasiswas.id_mhs')
                ->join('kelas', 'mahasiswas.id_kls', '=', 'kelas.id_kls')
                ->join('jadwals', 'kelas.id_kls', '=', 'jadwals.id_kls')
                ->where('presensis.id_tahun_ajar', '=', $id_jdwl)
                ->where('presensis.tgl', '=', date('Y-m-d'))
                ->select(DB::raw('distinct presensis.*'))->get();
            return response()->json([
                'status' => 200,
                'kelasSaatIni' => $kelasSaatIni,
                'datamahasiswa' => $tableKelas
            ], 200);
        } else {
            return response()->json([
                'status' => 200,
                'kelasSaatIni' => $kelasSaatIni,
                'datamahasiswa' => $tableKelas
            ], 200);
        }
    } else {
        return response()->json([
            'status' => 400,
            'kelas' => 'Kelas Hari ini kosong'
        ], 400);
    }
}


    // public function getjmlhJam(Request $request)
    // {
    //     $ktngPrsn = $request->query('Keterangan_presensi');
    //     $sts = $request->query('status');
    //     $jmlh = $request->query('jumlah_jam');
    //     $nim = $request->query('nomor_induk');
    //     $id_jdwl = $request->query('id_jdwl');
    //     //get Row mhs tersebut
    //     $jmljammax = DB::table('presensis')
    //         ->join('mahasiswas', 'presensis.id_mhs', '=', 'mahasiswas.id_mhs')
    //         ->where('mahasiswas.nim', '=', $nim)
    //         ->where('presensis.id_tahun_ajar', '=', $id_jdwl)
    //         ->where('presensis.tgl', '=', date('Y-m-d'))->first();
    //     // ->where('presensis.id_tahun_ajar', '=', 9)
    //     // ->where('mahasiswas.nim', '=', '3202116025')
    //     // ->where('presensis.tgl', '=', '2023-10-27')->get();
    // }
    public function editKehadiranMhs(Request $request)
    {
        $ktngPrsn = $request->query('Keterangan_presensi');
        $sts = $request->query('status');
        $jmlh = $request->query('jumlah_jam');
        $nim = $request->query('nomor_induk');
        $id_jdwl = $request->query('id_jdwl');

        try {
            // Fetching the student record based on provided filters
            $getIdMhs = DB::table('presensis')
                ->join('mahasiswas', 'presensis.id_mhs', '=', 'mahasiswas.id_mhs')
                ->where('mahasiswas.nim', '=', $nim)
                ->where('presensis.id_tahun_ajar', '=', $id_jdwl)
                ->where('presensis.tgl', '=', date('Y-m-d'))
                ->first(); // Using first() instead of get() to fetch a single record

            if ($getIdMhs) {
                $updateData = [
                    'kehadiran' => ($ktngPrsn === 'Masuk') ? ($getIdMhs->ketidakhadiran - $jmlh) : ($getIdMhs->kehadiran - $jmlh),
                    'ketidakhadiran' => $jmlh
                ];

                // Mapping status values to their respective codes
                $statusCodes = [
                    'Alpa' => 'A',
                    'Izin' => 'I',
                    'Sakit' => 'S'
                ];

                if (array_key_exists($sts, $statusCodes)) {
                    $updateData['status'] = $statusCodes[$sts];
                }

                // Update the attendance record based on conditions
                $updateMhs = DB::table('presensis')
                    ->where('id_presensi', '=', $getIdMhs->id_presensi)
                    ->update($updateData);

                $limitSurat = now()->addDays(2);
                $ket_mhs = DB::table('ket_mhs')
                    ->insert([
                        'id_presensi' => $getIdMhs->id_presensi,
                        'status_confirm' => 0,
                        'surat_bukti' => null,
                        'deskripsi' => null,
                        'limit_surat' => $limitSurat
                    ]);

                return response()->json([
                    'status' => 200,
                    'getid' => $getIdMhs,
                    'updated' => $updateMhs,
                    'ket_mhs' => $ket_mhs
                ], 200);
            } else {
                return response()->json([
                    'data' => "data kosong"
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], 400);
        }
    }

    // public function tutupKelas(Request $request)
    // {
    //     $id_jdwl = $request->query('id_jdwl');
    //     $nidn = $request->query('nomor_induk');
    //     $pkk_bhsn = $request->query('pokok_bahasan');
    //     $spkk_bhsn = $request->query('sub_pokok_bahasan');
    //     $media = $request->query('media');

    //     // $autho = $request->header->token
    //     // try {
    //     //get id dosen
    //     $id_dosen = DB::table('dosens')->where('dosens.nidn', '=', $nidn)->value('id_dosen');
    //     //get jumlah_jam_ajar
    //     // if semester ganjil
    //     $jam_ajar = DB::table('presensis')
    //         ->selectRaw("
    //         // CASE
    //         //     WHEN finish_kls > '15:30:00' THEN
    //         //         CASE
    //         //             WHEN start_kls < '09:30:00' THEN TIMESTAMPADD(MINUTE, -100, TIMEDIFF(finish_kls, start_kls))
    //         //             WHEN start_kls < '12:15:00' THEN TIMESTAMPADD(MINUTE, -95, TIMEDIFF(finish_kls, start_kls))
    //         //             WHEN start_kls < '14:40:00' THEN TIMESTAMPADD(MINUTE, -50, TIMEDIFF(finish_kls, start_kls))
    //         //         END
    //         //     WHEN finish_kls > '13:00:00' THEN
    //         //         CASE
    //         //             WHEN start_kls < '09:30:00' THEN TIMESTAMPADD(MINUTE, -60, TIMEDIFF(finish_kls, start_kls))
    //         //             WHEN start_kls < '12:15:00' THEN TIMESTAMPADD(MINUTE, -45, TIMEDIFF(finish_kls, start_kls))
    //         //         END
    //         //     WHEN finish_kls > '09:45:00' THEN
    //         //         CASE
    //         //             WHEN start_kls < '09:30:00' THEN TIMESTAMPADD(MINUTE, -15, TIMEDIFF(finish_kls, start_kls))
    //         //         END
    //         //     ELSE finish_kls
    //         // END AS jam_ajar")
    //         //if semester genap
    //         //     Start_kls, finish_kls,

    //         //     CASE
    //         //         WHEN finish_kls > '12:40:00' THEN
    //         //             CASE
    //         //                 WHEN start_kls < '09:30:00' THEN TIMESTAMPADD(MINUTE, -90, TIMEDIFF(finish_kls, start_kls))
    //         //                 WHEN start_kls < '11:40:00' THEN TIMESTAMPADD(MINUTE, -30, TIMEDIFF(finish_kls, start_kls))
    //         //             END
    //         //         WHEN finish_kls > '10:00:00' THEN
    //         //             CASE
    //         //                 WHEN start_kls < '09:30:00' THEN TIMESTAMPADD(MINUTE, -30, TIMEDIFF(finish_kls, start_kls))
    //         //             END
    //         //         ELSE finish_kls
    //         //         END AS jam_ajar
    //         // FROM
    //         // tb_presensi,log,tb_jdwl
    //         // WHERE
    //         // tb_presensi.id_tahun_ajar=log.id_tahun_ajar
    //         // AND
    //         // tb_jdwl.id_jdwl=log.id_jdwl
    //         // AND
    //         // tb_jdwl.id_jdwl=1
    //         // AND log.id_dosen=23
    //         // AND tgl = CURDATE();
    //         ->join('logs', 'presensis.id_tahun_ajar', '=', 'logs.id_tahun_ajar')
    //         ->join('jadwals', 'jadwals.id_jdwl', '=', 'logs.id_jdwl')
    //         ->where('jadwals.id_jdwl', '=', '11')
    //         ->where('logs.id_dosen', '=', '2')
    //         ->where('tgl', '=', '2023-11-13')
    //         // ->where('jadwals.id_jdwl', '=', $id_jdwl)
    //         // ->where('logs.id_dosen', '=', $id_dosen)
    //         // ->where('tgl', '=', date('Y-m-d'))
    //         ->first();

    //     // return var_dump($jam_ajar);
    //     $inputBeritaAcara = DB::table('berita_acara')
    //         ->join('dosens', 'berita_acara.id_dosen', '=', 'dosens.id_dosen')
    //         ->where('presensis.tgl', '=', date('Y-m-d'))
    //         ->where('kehadiran', '=', 0)
    //         ->where('dosens.id_dosen', '=', $id_dosen)
    //         ->insert([
    //             'id_jdwl' => $id_jdwl,
    //             'id_dosen' => $id_dosen,
    //             'tgl' => date('Y-m-d'),
    //             'pkk_bhsn' => $pkk_bhsn,
    //             'spkk_bhsn' => $spkk_bhsn,
    //             'media' => $media,
    //             'jam_ajar' => $jam_ajar->jam_ajar,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);
    //     $simpanDataKelas = DB::table('presensis')
    //         ->where('presensis.tgl', '=', date('Y-m-d'))
    //         ->where('presensis.id_tahun_ajar', '=', $id_jdwl)
    //         // ->where('presensis.kehadiran', '=', null)
    //         ->update(['finish_kls' => date('H:i:s')]);
    //     // dd($simpanKehadiran);
    //     return response()->json([
    //         'status' => 200,
    //         'berita_acara' => $inputBeritaAcara,
    //         'datakelas' => $simpanDataKelas
    //     ], 200);
    // }

    public function tutupKelas(Request $request)
{
    $id_jdwl = $request->query('id_jdwl');
    $nidn = $request->query('nomor_induk');
    $pkk_bhsn = $request->query('pokok_bahasan');
    $spkk_bhsn = $request->query('sub_pokok_bahasan');
    $media = $request->query('media');

    // Get id_dosen
    $id_dosen = DB::table('dosens')->where('nidn', '=', $nidn)->value('id_dosen');

    $simpanDataKelas = DB::table('presensis')
    ->where('presensis.tgl', '=', date('Y-m-d'))
    ->where('presensis.id_tahun_ajar', '=', $id_jdwl)
    ->update(['finish_kls' => date('H:i:s')]);

    // Get jam_ajar
    $jam_ajar = DB::table('presensis')
    ->selectRaw("
        CASE
            WHEN finish_kls > '15:30:00' THEN
                CASE
                    WHEN start_kls < '09:30:00' THEN SEC_TO_TIME(TIMESTAMPDIFF(MINUTE, start_kls, finish_kls) * 60 - 100 * 60)
                    WHEN start_kls < '12:15:00' THEN SEC_TO_TIME(TIMESTAMPDIFF(MINUTE, start_kls, finish_kls) * 60 - 95 * 60)
                    WHEN start_kls < '14:40:00' THEN SEC_TO_TIME(TIMESTAMPDIFF(MINUTE, start_kls, finish_kls) * 60 - 50 * 60)
                END
            WHEN finish_kls > '13:00:00' THEN
                CASE
                    WHEN start_kls < '09:30:00' THEN SEC_TO_TIME(TIMESTAMPDIFF(MINUTE, start_kls, finish_kls) * 60 - 60 * 60)
                    WHEN start_kls < '12:15:00' THEN SEC_TO_TIME(TIMESTAMPDIFF(MINUTE, start_kls, finish_kls) * 60 - 45 * 60)
                END
            WHEN finish_kls > '09:45:00' THEN
                CASE
                    WHEN start_kls < '09:30:00' THEN SEC_TO_TIME(TIMESTAMPDIFF(MINUTE, start_kls, finish_kls) * 60 - 15 * 60)
                END
            ELSE SEC_TO_TIME(TIMESTAMPDIFF(MINUTE, start_kls, finish_kls) * 60)
        END AS jam_ajar")
    ->join('logs', 'presensis.id_tahun_ajar', '=', 'logs.id_tahun_ajar')
    ->join('jadwals', 'jadwals.id_jdwl', '=', 'logs.id_jdwl')
    ->where('jadwals.id_jdwl', '=', $id_jdwl)
    ->where('logs.id_dosen', '=', $id_dosen)
    ->where('presensis.tgl', '=', date('Y-m-d'))
    ->first();

if ($jam_ajar) {
    $jam_ajar_value = $jam_ajar->jam_ajar;
} else {
    $jam_ajar_value = '00:00:00'; // Nilai default jika tidak ditemukan
}

    // Insert into berita_acara
    $inputBeritaAcara = DB::table('berita_acara')->insert([
        'id_jdwl' => $id_jdwl,
        'id_dosen' => $id_dosen,
        'tgl' => date('Y-m-d'),
        'pkk_bhsn' => $pkk_bhsn,
        'spkk_bhsn' => $spkk_bhsn,
        'media' => $media,
        'jam_ajar' => $jam_ajar_value,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Update finish_kls


    return response()->json([
        'status' => 200,
        'berita_acara' => $inputBeritaAcara,
        'datakelas' => $simpanDataKelas
    ], 200);
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
                                ->select('mahasiswas.id_mhs', 'mahasiswas.nama', 'jadwals.jumlah_jam')
                                ->get();

                            // Log data to debug
                            Log::info('Presensi Data: ' . $presensiToUpdate);

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
    public function updateTablePresensi()
    {
    }


    public function updatetableKelas(Request $request, $id_kls)
    {
        // Validate the request
        $request->validate([
            'abjad_kls' => 'string',
            'smt' => 'integer',
        ]);

        try {
            // Log request data
            Log::info('Request data: ', $request->all());
            Log::info('Updating class with ID: ' . $id_kls);

            // Find the class by ID
            $kelas = Kelas::findOrFail($id_kls);

            // Log the found class data
            Log::info('Class found: ', $kelas->toArray());

            // Update the class with new data
            $kelas->update($request->only(['abjad_kls', 'smt'])); // Update only the specified fields

            // Log the updated class data
            Log::info('Class updated successfully: ', $kelas->toArray());

            // Return success response
            return response()->json([
                'message' => 'Berhasil memperbarui kelas',
                'data' => $kelas,
            ], 200);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error updating class: ' . $e->getMessage());

            // Return error response
            return response()->json([
                'message' => 'Tidak berhasil memperbarui kelas',
            ], 500);
        }
    }

    public function createtableKelas(Request $request)
    {
        $request->validate([
            'abjad_kls' => 'required|string',
            'smt' => 'required|integer',
        ]);

        try {
            $kelas = Kelas::create($request->all());

            return response()->json([
                'message' => 'Berhasil menambahkan kelas',
                'data' => $kelas,
            ], 201);
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error creating class: ' . $e->getMessage());

            return response()->json([
                'message' => 'Tidak berhasil menambahkan kelas',
            ], 500);
        }
    }

    public function deletetableKelas($id_kls)
    {
        try {
            $kelas = Kelas::where('id_kls', $id_kls)->firstOrFail();
            $kelas->delete();

            return response()->json([
                'message' => 'Berhasil menghapus kelas',
            ], 200);
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error deleting class: ' . $e->getMessage());

            return response()->json([
                'message' => 'Tidak berhasil menghapus kelas',
            ], 500);
        }
    }
}