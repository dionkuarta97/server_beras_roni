<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    //

    public function register(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'nama' => 'required',
                'username' => 'required|min:6|unique:users',
                'password' => 'required|min:7',
                'level' => 'required'
            ], [
                'nama.required' => 'nama tidak boleh kosong',
                'username.required' => 'nama tidak boleh kosong',
                'username.min' => 'username minimal 6 karakter',
                'username.unique' => 'username sudah digunakan',
                'password.required' => 'password tidak boleh kosong',
                'password.min' => 'password minimal 7 karakter',
                'level.required' => 'level tidak boleh kosong'
            ]);

            if ($validation->fails()) return response()->json($validation->errors(), 400);
            $user = Users::create([
                'nama' => $request->nama,
                'username' => $request->username,
                'password' => $request->password,
                'level' => $request->level
            ]);

            return response()->json($user, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function login(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'username' => "required",
                'password' => "required"
            ], [
                'username.required' => 'username tidak boleh kosong',
                'password' => 'password tidak boleh kosong'
            ]);
            if ($validation->fails()) return response()->json($validation->errors(), 400);
            $checkLogin = Users::where('username', $request->username)->first();
            if (!$checkLogin) return response()->json(['message' => 'username/password salah'], 401);
            if ($checkLogin['password'] !== $request->password) return response()->json(['message' => 'username/password salah'], 401);

            $payload = [
                'id' => $checkLogin['id'],
                'nama' => $checkLogin['nama'],
                'level' => $checkLogin['level']
            ];
            $key = config('app.jwt_key');
            $access_token = JWT::encode($payload, $key, 'HS256');

            return response()->json(['access_token' => $access_token, "user" => $payload], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function getMe(Request $request)
    {
        try {
            //code...
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
}
