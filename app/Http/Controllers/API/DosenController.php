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

    public function __invoke(Request $request)
    {
        $nidn = $request->nomor_induk;
        try {
            $dosen = Dosen::select('nidn', 'nama', 'foto')
                ->where('nidn', $nidn)->get();

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
                ->where('dosens.nidn', '=', $nidn)
                ->select('jadwals.id_jdwl', 'matkuls.nama as Mata Kuliah', DB::raw('CONCAT(TIME_FORMAT(jadwals.start, "%H.%i"), " - ",TIME_FORMAT(jadwals.finish , "%H.%i")) AS Waktu'), 'kelas.smt', 'kelas.abjad_kls', 'jadwals.ruang', 'jadwals.jumlah_jam')
                ->get();

            return response()->json([
                'status' => '201',
                'Profile' => $dosen,
                'KonfirmStatusMahasiswa' => $konfirmMahasiswa,
                'jadwalHariIni' => $jadwalHariIni
            ]);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    
    public function tutupKelas(){
        
    }
}
