<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Models\UsersAuth;

class AuthController extends Controller
{
    public function signUp(Request $request)
    {
        $validatedData = $request->validate([
            'name'     => 'required|string',
            'username' => 'required|string|unique:users_auth,username',
            'password' => 'required|min:8'
        ]);

        try {
            $user = Users::create([
                'name'   => $validatedData['name'],
                'avatar' => asset('uploads/avatars/000.png')
            ]);

            $userAuth = UsersAuth::create([
                'user_id'  => $user->id,
                'username' => $validatedData['username'],
                'password' => Hash::make($validatedData['password'])
            ]);

            $token = $userAuth->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'ثبت‌نام موفقیت‌آمیز بود.',
                'token'   => $token
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'خطایی رخ داد. لطفاً دوباره تلاش کنید.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function signIn(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required'
        ]);

        $userAuth = UsersAuth::where('username', $credentials['username'])->first();

        if (!$userAuth || !Hash::check($credentials['password'], $userAuth->password)) {
            return response()->json(['message' => 'نام کاربری یا رمز عبور اشتباه است'], 401);
        }

        $userAuth->tokens()->delete();
        $token = $userAuth->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'ورود موفقیت‌آمیز بود.',
            'token'   => $token
        ]);
    }

    public function signOut(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'خروج موفقیت‌آمیز بود.'
        ]);
    }

    public function resetPassword(Request $request) {}
}
