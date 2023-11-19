<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KelasController extends Controller
{
    public function kelasSaatIni(Request $request)
    {
        $id_jdwl = $request->query('id_jdwl');
        $nidn = $request->query('nidn');

        $date = Carbon::parse(now())->locale('id');
        $date->settings(['formatFunction' => 'translatedFormat']);
        $day = $date->format('l');

        $kelasSaatIni = DB::table('jadwals')
            ->join('logs', 'jadwals.id_jdwl', '=', 'logs.id_jdwl')
            ->join('dosens', 'logs.id_dosen', '=', 'dosens.id_dosen')
            ->join('matkuls', 'jadwals.id_mk', '=', 'matkuls.id_mk')
            ->join('kelas', 'jadwals.id_kls', '=', 'kelas.id_kls')
            ->where('jadwals.hari', '=', 'senin')
            // ->where('jadwals.hari', '=', $day)
            ->where('jadwals.id_jdwl', '=', $id_jdwl)
            ->whereRaw('jadwals.finish < curtime()')
            ->where('dosens.nidn', '=', $nidn)
            ->select(
                'logs.id_tahun_ajar',
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
                ->select('mahasiswas.id_mhs', 'jadwals.jumlah_jam')
                ->get();
            // dd($datamahasiswa[0]);
            foreach ($datamahasiswa as $key => $value) {
                DB::table('presensis')
                    ->insert([
                        'id_mhs' => $value->id_mhs,
                        'id_tahun_ajar' => $id_jdwl,
                        'tgl' => date('Y-m-d'),
                        'start_kls' => date('H:i:s'),
                        'finish_kls' => null,
                        'kehadiran' => 0,
                        'ketidakhadiran' => $value->jumlah_jam,
                        'status' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
            }
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $string = '';
            for ($i = 0; $i < 5; $i++) {
                $string .= $characters[rand(0, strlen($characters) - 1)];
            }

            $limitToken = now()->addMinutes(2);
            $Jadwal = Jadwal::where("id_jdwl", $id_jdwl)->first();
            $Jadwal->token = $string;
            $Jadwal->expires_at = $limitToken;
            $Jadwal->save();
        } else {
            return response()->json([
                'status' => '400',
                'kelasSaatIni' => [
                    'message' => 'kelas kosong'
                ]
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
            'status' => '200',
            'kelasSaatIni' => [
                'detail' => $kelasSaatIni,
                'token' => $Jadwal->token,
                'tableKelas' => $tableKelas,
            ]
        ]);
    }
}
