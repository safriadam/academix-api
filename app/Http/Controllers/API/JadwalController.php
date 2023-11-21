<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    public function JadwalMahasiswa(Request $request)
    {

        $nim = $request->nomor_induk;
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
            ->where('mahasiswas.nim', '=', $nim)
            ->select('jadwals.id_jdwl', 'matkuls.nama as Mata Kuliah', DB::raw('CONCAT(TIME_FORMAT(jadwals.start, "%H.%i"), " - ",TIME_FORMAT(jadwals.finish , "%H.%i")) AS Waktu'), 'kelas.smt', 'kelas.abjad_kls', 'jadwals.ruang', 'jadwals.jumlah_jam')
            ->get();
    }
}
