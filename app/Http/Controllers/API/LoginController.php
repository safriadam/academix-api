<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\password;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "nomor_induk" => "required",
                "password" => "required",
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
            $user = User::where("nomor_induk", $request->nomor_induk)->first();
            if (!$user) {
                return response()->json([
                    'status' => 401,
                    'message' => "Nomor induk tidak ditemukan"
                ], 401);
            }
            $password = $request->password;
            $credentials = $request->only('nomor_induk', 'password');
            if (!$token = auth()->guard('api')->attempt($credentials)) {
                if (!$user->password = Hash::check($password, $user->password)) {
                    return response()->json([
                        'message' => "Password anda salah"
                    ], 401);
                }
            } else {
                return response()->json([
                    'status' => 200,
                    'user'    => auth()->guard('api')->user(),
                    'token'   => $token
                ], 200);
            }
        } catch (\Throwable $t) {
            return response()->json([
                "error" => $t->getMessage()
            ], $t->getCode());
        }
    }
}
