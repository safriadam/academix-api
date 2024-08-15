<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LaporanController extends Controller
{
    public function LaporanMhs(Request $request)
{
    $nim = $request->input('nim');

    try {
        // Find the mahasiswa by NIM
        $mahasiswa = Mahasiswa::where('nim', $nim)
            ->with([
                'presensi' => function($query) {
                    $query->select('id_mhs', 'status', 'ketidakhadiran');
                },
                'kompen_mhs' => function($query) {
                    $query->select('id_mhs', 'jumlah_kompen');
                }
            ])
            ->firstOrFail(['id_mhs', 'nim', 'nama']);

        // Prepare the report data
        $report = [
            'id_mhs' => $mahasiswa->id_mhs,
            'nim' => $mahasiswa->nim,
            'nama' => $mahasiswa->nama,
            'status' => optional($mahasiswa->presensi)->status,
            'ketidakhadiran' => optional($mahasiswa->presensi)->ketidakhadiran,
            'jumlah_kompen' => optional($mahasiswa->kompen_mhs)->jumlah_kompen,
        ];

        // Return the report as JSON
        return response()->json([
            'message' => 'Laporan berhasil diambil',
            'data' => $report,
        ], 200);

    } catch (\Exception $e) {
        // Log the error
        Log::error('Error retrieving report: ' . $e->getMessage());

        return response()->json([
            'message' => 'Tidak berhasil mengambil laporan',
        ], 500);
    }
}

public function LaporanMhsdosen(Request $request)
{
    $id_kls = $request->input('id_kls');

    try {
        // Fetch all students related to the given id_kls
        $mahasiswaList = Mahasiswa::where('id_kls', $id_kls)
            ->with([
                'presensi' => function($query) {
                    $query->select('id_mhs', 'status', 'ketidakhadiran');
                },
                'kompen_mhs' => function($query) {
                    $query->select('id_mhs', 'jumlah_kompen');
                },
                'kelas' => function($query) {
                    $query->select('id_kls', 'abjad_kls', 'smt');
                }
            ])
            ->get(['id_mhs', 'nim', 'nama', 'id_kls']);

        // Check if any data was found
        if ($mahasiswaList->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada mahasiswa ditemukan dengan ID kelas ini',
                'data' => [],
            ], 200);
        }

        // Prepare the report data
        $report = $mahasiswaList->map(function($mahasiswa) {
            return [
                'id_kls' => optional($mahasiswa->kelas)->id_kls,
                'abjad_kls' => optional($mahasiswa->kelas)->abjad_kls,
                'smt' => optional($mahasiswa->kelas)->smt,
                'id_mhs' => $mahasiswa->id_mhs,
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->nama,
                'status' => optional($mahasiswa->presensi)->status,
                'ketidakhadiran' => optional($mahasiswa->presensi)->ketidakhadiran,
                'jumlah_kompen' => optional($mahasiswa->kompen_mhs)->jumlah_kompen,
            ];
        });

        // Return the report as JSON
        return response()->json([
            'message' => 'Laporan berhasil diambil',
            'data' => $report,
        ], 200);

    } catch (\Exception $e) {
        // Log the error
        Log::error('Error retrieving report: ' . $e->getMessage());

        return response()->json([
            'message' => 'Tidak berhasil mengambil laporan',
        ], 500);
    }
}

}