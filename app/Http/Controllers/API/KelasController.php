<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KelasController extends Controller
{

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
                // dd($datamahasiswa[3]);
                $tableKelas = DB::table('presensis')
                    ->join('mahasiswas', 'presensis.id_mhs', '=', 'mahasiswas.id_mhs')
                    ->join('kelas', 'mahasiswas.id_kls', '=', 'kelas.id_kls')
                    ->join('jadwals', 'kelas.id_kls', '=', 'jadwals.id_kls')
                    ->where('presensis.id_tahun_ajar', '=', $id_jdwl)
                    ->where('presensis.tgl', '=', date('Y-m-d'))
                    ->select(DB::raw('distinct presensis.*'))->get();

                if ($tableKelas) {
                    foreach ($datamahasiswa as $key => $value) {
                        DB::table('presensis')
                            ->insert([
                                'id_mhs' => $value->id_mhs,
                                'id_tahun_ajar' => $id_jdwl,
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
                }
                return response()->json([
                    'status' => 200,
                    'kelasSaatIni' => $kelasSaatIni,
                    'datamahasiswa' => $tableKelas
                ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'kelasSaatIni' => [
                        'message' => 'kelas kosong'
                    ]
                ], 400);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th->getCode());
        }
    }
    public function tutupKelas(Request $request)
    {
        $id_jdwl = $request->query('id_jdwl');
        $nidn = $request->query('nomor_induk');
        $pkk_bhsn = $request->query('pokok_bahasan');
        $spkk_bhsn = $request->query('sub_pokok_bahasan');
        $media = $request->query('media');

        // $autho = $request->header->token
        // try {
        //get id dosen
        $id_dosen = DB::table('dosens')->where('dosens.nidn', '=', $nidn)->value('id_dosen');
        //get jumlah_jam_ajar
        $jam_ajar = DB::table('presensis')
            ->selectRaw("
            CASE
                WHEN finish_kls > '15:30:00' THEN
                    CASE
                        WHEN start_kls < '09:30:00' THEN TIMESTAMPADD(MINUTE, -100, TIMEDIFF(finish_kls, start_kls))
                        WHEN start_kls < '12:15:00' THEN TIMESTAMPADD(MINUTE, -95, TIMEDIFF(finish_kls, start_kls))
                        WHEN start_kls < '14:40:00' THEN TIMESTAMPADD(MINUTE, -50, TIMEDIFF(finish_kls, start_kls))
                    END
                WHEN finish_kls > '13:00:00' THEN
                    CASE
                        WHEN start_kls < '09:30:00' THEN TIMESTAMPADD(MINUTE, -60, TIMEDIFF(finish_kls, start_kls))
                        WHEN start_kls < '12:15:00' THEN TIMESTAMPADD(MINUTE, -45, TIMEDIFF(finish_kls, start_kls))
                    END
                WHEN finish_kls > '09:45:00' THEN
                    CASE
                        WHEN start_kls < '09:30:00' THEN TIMESTAMPADD(MINUTE, -15, TIMEDIFF(finish_kls, start_kls))
                    END
                ELSE finish_kls
            END AS jam_ajar")
            ->join('logs', 'presensis.id_tahun_ajar', '=', 'logs.id_tahun_ajar')
            ->join('jadwals', 'jadwals.id_jdwl', '=', 'logs.id_jdwl')
            ->where('jadwals.id_jdwl', '=', '11')
            ->where('logs.id_dosen', '=', '2')
            ->where('tgl', '=', '2023-11-13')
            // ->where('jadwals.id_jdwl', '=', $id_jdwl)
            // ->where('logs.id_dosen', '=', $id_dosen)
            // ->where('tgl', '=', date('Y-m-d'))
            ->first();

        // return var_dump($jam_ajar);
        $inputBeritaAcara = DB::table('berita_acara')
            ->join('dosens', 'berita_acara.id_dosen', '=', 'dosens.id_dosen')
            ->where('presensis.tgl', '=', date('Y-m-d'))
            ->where('kehadiran', '=', 0)
            ->where('dosens.id_dosen', '=', $id_dosen)
            ->insert([
                'id_jdwl' => $id_jdwl,
                'id_dosen' => $id_dosen,
                'tgl' => date('Y-m-d'),
                'pkk_bhsn' => $pkk_bhsn,
                'spkk_bhsn' => $spkk_bhsn,
                'media' => $media,
                'jam_ajar' => $jam_ajar->jam_ajar,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        $simpanDataKelas = DB::table('presensis')
            ->where('presensis.tgl', '=', date('Y-m-d'))
            ->where('presensis.id_tahun_ajar', '=', $id_jdwl)
            // ->where('presensis.kehadiran', '=', null)
            ->update(['finish_kls' => date('H:i:s')]);
        // dd($simpanKehadiran);
        return response()->json([
            'status' => 200,
            'berita_acara' => $inputBeritaAcara,
            'datakelas' => $simpanDataKelas
        ], 200);
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
                ->where('presensis.id_tahun_ajar', '=', 9)
                ->where('mahasiswas.nim', '=', '3202116025')
                ->where('presensis.tgl', '=', '2023-10-27')
                // ->where('mahasiswas.nim', '=', $nim)
                // ->where('presensis.id_tahun_ajar', '=', $id_jdwl)
                // ->where('presensis.tgl', '=', date('Y-m-d'))->first()
                ->first(); // Using first() instead of get() to fetch a single record

            if ($getIdMhs) {
                $updateData = [
                    'presensis.kehadiran' => ($ktngPrsn === 'Masuk') ? ($getIdMhs->ketidakhadiran - $jmlh) : ($getIdMhs->kehadiran - $jmlh),
                    'presensis.ketidakhadiran' => $jmlh
                ];

                // Mapping status values to their respective codes
                $statusCodes = [
                    'Alpa' => 'A',
                    'Izin' => 'I',
                    'Sakit' => 'S'
                ];

                if (array_key_exists($sts, $statusCodes)) {
                    $updateData['presensis.status'] = $statusCodes[$sts];
                }

                // Update the attendance record based on conditions
                $updateMhs = DB::table('presensis')
                    ->where('presensis.id_presensi', '=', $getIdMhs->id_presensi)
                    ->update($updateData);

                return response()->json([
                    'status' => 200,
                    'getid' => $getIdMhs,
                    'updated' => $updateMhs
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

    //---------------------------------------------------------------------------MAHASISWA//////////////////////////////////////////////////////////////////////////
    public function kelasSaatIniMahasiswa(Request $request)
    {
        $id_jdwl = $request->query('id_jdwl');
        // if (!$kelasSaatIni->isEmpty()) {
        // } else {
        //     return response()->json([
        //         'status' => '400',
        //         'kelasSaatIni' => [
        //             'message' => 'kelas kosong'
        //         ]
        //     ]);
        // }
        $tableKelas = DB::table('presensis')
            ->join('mahasiswas', 'presensis.id_mhs', '=', 'mahasiswas.id_mhs')
            ->join('kelas', 'mahasiswas.id_kls', '=', 'kelas.id_kls')
            ->join('jadwals', 'kelas.id_kls', '=', 'jadwals.id_kls')
            ->where('presensis.id_tahun_ajar', '=', $id_jdwl)
            ->where('presensis.tgl', '=', date('Y-m-d'))
            ->select(DB::raw('distinct presensis.*'))->get();

        return response()->json([
            'status' => 200,
            'kelasSaatIni' => [
                'tableKelas' => $tableKelas,
            ]
        ], 200);
    }

    public function dataKelasSaatIni(Request $request)
    {
        $id_jdwl = $request->query('id_jdwl');
        $nim = $request->query('nim');

        $date = Carbon::parse(now())->locale('id');
        $date->settings(['formatFunction' => 'translatedFormat']);
        $day = $date->format('l');

        $kelasSaatIni = DB::table('jadwals')
            ->join('logs', 'jadwals.id_jdwl', '=', 'logs.id_jdwl')
            ->join('dosens', 'logs.id_dosen', '=', 'dosens.id_dosen')
            ->join('matkuls', 'jadwals.id_mk', '=', 'matkuls.id_mk')
            ->join('kelas', 'jadwals.id_kls', '=', 'kelas.id_kls')
            ->join('mahasiswas', 'kelas.id_kls', '=', 'mahasiswas.id_kls')
            ->where('jadwals.hari', '=', $day)
            ->where('jadwals.id_jdwl', '=', $id_jdwl)
            ->whereRaw('jadwals.finish > curtime()')
            ->where('mahasiswas.nim', '=', $nim)
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
            'dataKelasMhs' => $kelasSaatIni
        ], 200);
    }

    public function updateTablePresensi()
    {
    }
}
