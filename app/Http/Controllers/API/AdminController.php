<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Mahasiswa;
use App\Models\Presensi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke()
    {
        return response()->json(Admin::all());
    }
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function dataKompenDashboard(Response $response)
    {
        // $response = DB::select('SELECT mahasiswas.nama, ketidakhadiran, ket_status 
        //     FROM mahasiswas, presensis
        //     WHERE mahasiswas.id = presensis.mhs_id ');
        // $response = Mahasiswa::with('Mahasiswa:nama,Presensi:ketidakhadiran,Mahasiswa:ket_status')->get();
        // $response = Presensi::query()->select()->with(['Mahasiswa' => function ($query) {
        //     $query->select('id', 'nama');
        // }])
        //     ->get();
        // $presensi = Presensi::with("mahasiswa")->get();
        // Mahasiswa::with("presensi")->get();

        // var_dump($presensi, $mahasiswa);
        // $presensi->presensi->nama;
        // $response =
        //     DB::select(
        //         'SELECT mahasiswas.nama, ketidakhadiran, ket_status
        //         FROM mahasiswas, presensis'
        //     );
        // ($response); 
        // return response()->json($presensi);
        // return Response::json(['data' => $presensi, 'datas'=> $mahasiswa]);

        // $data = DB::table('mahasiswas')
        // ->join('presensis','id','=','presensis.mhs_id')
        // ->select('mahasiswas.nama, presensis.ketidakhadiran, mahasiswas.ket_status')->get();
        // // $users = DB::table('users')
        // //     ->join('contacts', 'users.id', '=', 'contacts.user_id')
        // //     ->join('orders', 'users.id', '=', 'orders.user_id')
        // //     ->select('users.*', 'contacts.phone', 'orders.price')
        // //     ->get();
        // // return $response->json($response);
        // var_dump($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
