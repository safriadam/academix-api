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

    public function updateJadwal(Request $request)
    {
        // Set validation
        $validator = Validator::make($request->all(), [
            'id_jdwl' => 'required|integer|exists:jadwals,id_jdwl', // Pastikan nama kolom sesuai
            'id_kls'  => 'required',
            'id_mk'   => 'required',
            'ruang'   => 'required',
            'hari'    => 'required',
            'start'   => 'required|date_format:H:i',
            'finish'  => 'required|date_format:H:i',
        ]);

        // If validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Ambil ID dari input request
        $id = (int) $request->input('id_jdwl'); // Pastikan nama kolom sesuai

        // Find the jadwal by ID
        $jadwal = Jadwal::find($id);

        // Cek jika entri tidak ditemukan
        if (!$jadwal) {
            return response()->json([
                'message' => 'Jadwal not found',
            ], 404);
        }

        try {
            // Calculate jumlah_jam
            $start = Carbon::createFromFormat('H:i', $request->start);
            $finish = Carbon::createFromFormat('H:i', $request->finish);

            // Calculate total hours. If finish is before start, add 24 hours.
            $jumlah_jam = ($finish->gt($start) ? $finish->diffInHours($start) : $finish->addDay()->diffInHours($start));

            // Update jadwal
            $jadwal->update([
                'id_kls'     => $request->id_kls,
                'id_mk'      => $request->id_mk,
                'ruang'      => $request->ruang,
                'hari'       => $request->hari,
                'start'      => $request->start,
                'finish'     => $request->finish,
                'jumlah_jam' => $jumlah_jam,
            ]);

            return response()->json([
                'status' => '200',
                'success' => true,
                'jadwal' => $jadwal,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th->getCode());
        }
    }

    public function deleteJadwal(Request $request)
    {
        // Validasi ID dari body request
        $validator = Validator::make($request->all(), [
            'id_jdwl' => 'required|integer|exists:jadwals,id_jdwl', // Validasi ID
        ], [
            'id_jdwl.required' => 'ID harus disertakan.',
            'id_jdwl.integer' => 'ID harus berupa angka.',
            'id_jdwl.exists' => 'ID tidak ditemukan dalam database.',
        ]);

        // Cek jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Ambil ID dari body request
        $id = $request->input('id_jdwl');

        // Temukan entri jadwal berdasarkan ID
        $jadwal = Jadwal::find($id);

        // Cek jika entri tidak ditemukan
        if (!$jadwal) {
            Log::info('Jadwal not found:', ['id_jdwl' => $id]);
            return response()->json([
                'message' => 'Jadwal not found',
            ], 404);
        }

        // Hapus entri jadwal
        $jadwal->delete();

        return response()->json([
            'message' => 'Jadwal deleted successfully',
        ], 200);
    }

}
