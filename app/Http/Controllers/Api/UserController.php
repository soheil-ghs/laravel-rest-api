<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller {

    public function register(Request $request) {
        $rules = [
            'email' => 'required|email|unique:users,email',
            'name' => 'required',
            'password' => 'required|string|min:8',
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
                'code' => 400
            ], 400);
        }

        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken($request->email)->accessToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'code' => 200
        ]);
    }

    public function login(Request $request) {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
                'code' => 400
            ], 400);
        }

        if (Auth::attempt($credentials)) {
            $token = auth()->user()->createToken($request->email)->accessToken;

            return response()->json([
                'success' => true,
                'token' => $token,
                'code' => 200
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'UnAuthorized',
            'code' => 401
        ], 401);
    }

    public function logout() {
        Auth::user()->token()->revoke();

        return response()->json([
            'success' => true,
            'message' => 'User logged out',
            'code' => 200
        ]);
    }
}
