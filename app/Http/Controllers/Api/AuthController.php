<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([

            'username' => 'required',

            'password' => 'required',

        ]);

        if (
            !Auth::attempt([
                'username' => $request->username,
                'password' => $request->password
            ])
        ) {

            return response()->json([
                'message' => 'Username atau password salah'
            ], 401);
        }

        /** @var User $user */
        $user = Auth::user();

        if ($user->role != 'admin') {

            return response()->json([
                'message' => 'Akses ditolak'
            ], 403);
        }

        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([

            'token' => $token,

            'user' => $user

        ]);
    }

    public function logout(Request $request)
    {

        $request
            ->user()
            ->currentAccessToken()
            ->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
}
