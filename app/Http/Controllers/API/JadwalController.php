<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Validator;

class JadwalController extends Controller
{
    
    public function DashboardJadwal(){
        try {
            $jadwal_all = DB::table('jadwals')
                ->get();
            return response()->json([
                'status' => 200,
                "jadwal_all" => $jadwal_all
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th->getCode());
        }

    }

    public function SimpanJadwal(Request $request){

        //set validation
        $validator = Validator::make($request->all(), [
            'id_kls' => 'required',
            'id_mk'      => 'required',
            'ruang' => 'required',
            'hari' => 'required',
            'start' => 'required',
            'finish' => 'required',
            'jumlah_jam' => 'required',
        ]);

        //if validation fails
          if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $jadwal = Jadwal::create([
                'id_kls'    => $request->id_kls,
                'id_mk'     => $request->id_mk,
                'ruang'     => $request->ruang,
                'hari'      => $request->hari,
                'start'     => $request->start,
                'finish'    => $request->finish,
                'jumlah_jam' => $request->jumlah_jam,
            ]);
            
            return response()->json([
                'status' => '201',
                'success' => true,
                'jadwal' => $jadwal,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th->getCode());
        }

    }

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
