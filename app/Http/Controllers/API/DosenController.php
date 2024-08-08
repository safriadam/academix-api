<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Dosen;
use App\Models\Jadwal;
use Carbon\Carbon;


class DosenController extends Controller
{
    public function profilDosen(Request $request)
    {
        $nidn = $request->query('nomor_induk');
        try {
            $dosen = Dosen::select('nidn','nip', 'nama', 'foto', 'nama', 'no_hp')
                ->where('nidn', $nidn)->get();
            return response()->json([
                'status' => 200,
                'Data Profile' => $dosen
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th->getCode());
        }
    }
    public function jadwalHariIniDosen(Request $request)
    {
        $nidn = $request->query('nomor_induk');
        try {
            // Mendapatkan nama hari
            $date = Carbon::parse(now())->locale('id');
            $date->settings(['formatFunction' => 'translatedFormat']);
            $day = $date->format('l');

            $jadwalHariIni = DB::table('jadwals')
                ->join('logs', 'jadwals.id_jdwl', '=', 'logs.id_jdwl')
                ->join('dosens', 'logs.id_dosen', '=', 'dosens.id_dosen')
                ->join('matkuls', 'jadwals.id_mk', '=', 'matkuls.id_mk')
                ->join('kelas', 'jadwals.id_kls', '=', 'kelas.id_kls')
                ->where('jadwals.hari', '=', $day)
                ->orderBy('jadwals.start')
                ->whereRaw('jadwals.finish > curtime() ')
                ->where('dosens.nidn', '=', $nidn)
                ->select('jadwals.id_jdwl', 'matkuls.nama as Mata Kuliah', DB::raw('CONCAT(TIME_FORMAT(jadwals.start, "%H.%i"), " - ",TIME_FORMAT(jadwals.finish , "%H.%i")) AS Waktu'), 'kelas.smt', 'kelas.abjad_kls', 'jadwals.ruang', 'jadwals.jumlah_jam')
                ->get();
            return response()->json([
                'status' => 200,
                'Konfirm' => $jadwalHariIni
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th->getCode());
        }
    }
    public function konfirmMahasiswa(Request $request)
{
    $nidn = $request->query('nomor_induk');
    try {
        $konfirmMahasiswa = DB::table('mahasiswas')
            ->join('kelas', 'mahasiswas.id_kls', '=', 'kelas.id_kls')
            ->join('presensis', 'mahasiswas.id_mhs', '=', 'presensis.id_mhs',)
            ->join('ket_mhs', 'presensis.id_presensi', '=', 'ket_mhs.id_presensi')
            ->join('logs', 'presensis.id_tahun_ajar', '=', 'logs.id_tahun_ajar')
            ->join('dosens', 'logs.id_dosen', '=', 'dosens.id_dosen')
            ->where('dosens.nidn', '=', $nidn)
            ->where('ket_mhs.status_confirm', '=', '0')
            ->where('surat_bukti', '<>', 'NULL')
            ->select('presensis.id_presensi', 'presensis.status', 'mahasiswas.nama', 'mahasiswas.nim', 'kelas.smt', 'kelas.abjad_kls')->get();
        return response()->json([
            'status' => 200,
            'Konfirm' => $konfirmMahasiswa
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            "error" => $th->getMessage()
        ], $th->getCode());
    }
}


    public function tolakSurat(Request $request, $konfirmMahasiswa)
    {
        $nidn = $request->query('nomor_induk');
        try {
            $tolak = DB::table('ket_mhs')
                ->update([
                    'status_confirm' => 1
                ]);
            return response()->json([
                'status' => 200,
                "konfirmasi" => $tolak
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th->getCode());
        }
    }
}