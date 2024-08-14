<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Ket_mhs;
use App\Models\Presensi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpParser\Node\Stmt\Catch_;

class MahasiswaController extends Controller
{
    public function DashboardMahasiswa(String $id) {
        try {

            $id_mahasiswa = $id;

            // Perform the database queries using $id_mahasiswa
            $jumlahKehadiran = DB::table('presensi')
                ->where('id_mahasiswa', $id_mahasiswa)
                ->sum('kehadiran');
        
            $sakit = DB::table('presensi')
                ->where('id_mahasiswa', $id_mahasiswa)
                ->where('status', 'S')
                ->count();
        
            $izin = DB::table('presensi')
                ->where('id_mahasiswa', $id_mahasiswa)
                ->where('status', 'I')
                ->count();
        
            $alpha = DB::table('presensi')
                ->where('id_mahasiswa', $id_mahasiswa)
                ->where('status', 'A')
                ->count();
        
            $jumlahKompensasi = DB::table('kompen_mahasiswa')
                ->where('id_mahasiswa', $id_mahasiswa)
                ->sum('jumlah_kompen');
        
            return response()->json([
                'status' => 200,
                'jumlah_kehadiran' => $jumlahKehadiran,
                'sakit' => $sakit,
                'izin' => $izin,
                'alpha' => $alpha,
                'jumlah_kompensasi' => $jumlahKompensasi,
            ], 200);

        } catch (\Throwable $th) {
            // Default kode status HTTP untuk kesalahan server
            $statusCode = is_int($th->getCode()) && $th->getCode() >= 100 && $th->getCode() <= 599 ? $th->getCode() : 500;

            return response()->json([
                "error" => $th->getMessage(),
            ], $statusCode);
        }
    }
    
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
    public function checkTokenValid(Request $request)
    {
        $token = $request->token;
        $id_jdwl = $request->query('id_jdwl');
        try {
            $valid = DB::table('jadwals')->where('id_jdwl',$id_jdwl)->first();
            //tambahkan expired_at
            if($valid==$token){
                return response()->json([
                    'status' => 200,
                    'message' => 'Token Valid'
                ], 200);
            }else{
                return response()->json([
                    'status' => 200,
                    'message' => 'Token Tidak Valid'
                ], 200);
            }
        

        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ], $th->getCode());
        }
    }
    public function daftarKetidakhadiranMhs(Request $request)
    {
        // $nim = $request->query('nomor_induk');
        // $nomor_induk = $request->query('nomor_induk');
        $ket_mhs = Ket_mhs::where("id_presensi", $request->id_presensi)->first();
        $nim = Mahasiswa::where("nim", $request->nomor_induk)->first();
        $presensis = Presensi::where("id_presensi", $request->id_presensi)->first();

        return [
            'k' => $ket_mhs,
            'n' => $nim,
            'p' => $presensis
        ];
        if ($ket_mhs !== null) {
            if ($nim !== null) {
                try {
                    if ($ket_mhs->id_presensi == $presensis->id_presensi) {
                        if (now()->lt($ket_mhs->limit_surat)) {
                            // $nomor_induk = $request->query('nomor_induk');
                            // $Jadwal = Jadwal::where("id_jdwl", $request->id_jdwl)->first();
                            // $inputToken = Jadwal::where("token", $request->token)->first();
                            // if ($Jadwal !== null) {
                            //     if ($inputToken !== null) {
                            //         try {
                            // if ($Jadwal->token == $inputToken->token) {
                            //     if (now()->lt($inputToken->expires_at)) {
                            $checkKehadiran = DB::table('mahasiswas')
                                ->join('kelas', 'mahasiswas.id_kls', '=', 'kelas.id_kls')
                                ->join('presensis', 'mahasiswas.id_mhs', '=', 'presensis.id_mhs',)
                                ->join('ket_mhs', 'presensis.id_presensi', '=', 'ket_mhs.id_presensi')
                                ->join('logs', 'presensis.id_tahun_ajar', '=', 'logs.id_tahun_ajar')
                                ->join('dosens', 'logs.id_dosen', '=', 'dosens.id_dosen')
                                ->where('mahasiswas.nim', '=', $nim)
                                ->where('presensis.status', '=', 'A')
                                ->where('ket_mhs.status_confirm', '=', '0')
                                ->where('surat_bukti', '<>', NULL)
                                // ->whereNotNull('surat_bukti')
                                ->select('presensis.id_presensi', 'presensis.status', 'mahasiswas.nama', 'mahasiswas.nim', 'kelas.smt', 'kelas.abjad_kls')
                                ->get();
                        }
                    }
                    // ->insert();
                    // $limitSurat = now()->addDays(2);
                    // foreach ($konfirmKehadiran as $value) {
                    //     if ($konfirmKehadiran) {
                    //         DB::table('ket_mhs')
                    //             ->insert([
                    //                 'id_presensis' => $value->id_presensi,
                    //                 'status_confirm' => 0,
                    //                 'surat_bukti' => 'null',
                    //                 'deskripsi' => 'null',
                    //                 'limit_surat' => $limitSurat,
                    //                 'created_at' => now(),
                    //                 'updated_at' => now()
                    //             ]);
                    //     }
                    // }
                } catch (\Throwable $th) {
                    return response()->json([
                        "error" => $th->getMessage()
                    ], $th->getCode());
                }
                // return response()->json([
                //     'status' => 200,
                //     'daftarKetidakHadiran' => $konfirmKehadiran
                // ], 200);
            }
        }
    }
    public function kirimSuratKetidakhadiran(Request $request)
    {
        $nim = $request->nomor_induk;
        $sts = $request->status;
        $alamatsurat = $request->surat;
        $keterangan = $request->keterangan;
        $id_presensi = $request->id_presensi;
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
                    'ket_mhs.id_presensi' => $id_presensi,
                    'ket_mhs.surat_bukti' => $alamatsurat,
                    'ket_mhs.deskripsi' => $keterangan,
                    'ket_mhs.limit_surat' => now(), //masih bug, harusnya tidak now()
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

    
}
