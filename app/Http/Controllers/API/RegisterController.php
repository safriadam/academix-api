<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Admin;
use App\Models\Dosen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'role' => 'required',
            'name'      => 'required',
            'nomor_induk' => 'required',
            'password'  => 'required|min:8|confirmed'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create user
        if ($request->role == '0') {
            response()->json([
                'status' => '409',
                'success' => false,
            ], 409);
            $user = User::create([
                'role' => $request->role,
                'name'      => $request->name,
                'nomor_induk'     => $request->nomor_induk,
                'password'  => bcrypt($request->password)
            ]);
            $admin = Admin::create([
                'user_id' => $user->id,
                'nama' => $request->name,
                'no_induk' => $request->nomor_induk,
                'no_hp' => $request->no_hp,
            ]);
            return response()->json([
                'status' => '201',
                'success' => true,
                'user'    => $user,
                'admin' => $admin,
            ], 201);
        } elseif ($request->role == '1' || $request->role == '2') {
            $user = User::create([
                'role' => $request->role,
                'name'      => $request->name,
                'nomor_induk'     => $request->nomor_induk,
                'password'  => bcrypt($request->password)
            ]);
            $dosen = Dosen::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'nidn' => $request->nomor_induk,
                'nama' => $request->name,
                'is_kaprodi' => $request->is_kaprodi,
                'no_hp' => $request->no_hp,
                'foto' => $request->foto,
            ]);
            return response()->json([
                'status' => '201',
                'success' => true,
                'user'    => $user,
                'dosen' => $dosen,
            ], 201);
        } elseif ($request->role == '3') {
            $user = User::create([
                'role' => $request->role,
                'name'      => $request->name,
                'nomor_induk'     => $request->nomor_induk,
                'password'  => bcrypt($request->password)
            ]);
            $mahasiswa = Mahasiswa::create([
                'user_id' => $user->id,
                'id_kls' => $request->id_kls,
                'nim' => $request->nomor_induk,
                'nama' => $request->name,
                'dosen_pa' => $request->dosen_pa,
                'foto' => $request->foto,
                'no_hp' => $request->no_hp,
                'ket_status' => '-',
            ]);
            return response()->json([
                'status' => '201',
                'success' => true,
                'user'    => $user,
                'mahasiswa' => $mahasiswa,
            ], 201);
        }

        //return response JSON user is created
        // if ($user) {
        //     return response()->json([
        //         'status' => '201',
        //         'success' => true,
        //         'user'    => $user,
        //         'mahasiswa' => $mahasiswa,
        //     ], 201);
        // }

        //return JSON process insert failed 
        return response()->json([
            'status' => '409',
            'success' => false,
        ], 409);
    }
}
