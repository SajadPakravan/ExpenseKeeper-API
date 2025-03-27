<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Models\UsersAuth;
use App\Models\UsersVerify;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

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
                'message' => 'ثبت‌نام موفقیت‌آمیز بود',
                'token'   => $token
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'خطایی رخ داد. لطفاً دوباره تلاش کنید',
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

    public function sendVerifyCode(Request $request)
    {
        $data = $request->validate([
            'email_phone' => 'required|string',
        ]);

        $input = $data['email_phone'];

        if (preg_match('/^09\d{9}$/', $input)) {
            $user = Users::where('phone', $input)->first();

            if (!$user) {
                return response()->json(['message' => 'شماره تلفن یافت نشد'], 404);
            }

            $verificationCode = rand(100000, 999999);

            UsersVerify::updateOrCreate(
                ['data' => $input],
                ['code' => $verificationCode]
            );

            Http::asForm()->withOptions(['verify' => false])->post('https://smspanel.trez.ir/SendPatternCodeWithUrl.ashx', [
                'AccessHash' => env('SMS_ACCESS_HASH'),
                'Mobile'     => $input,
                'PatternId'  => env('SMS_VERIFY_CODE_Pattern_Id'),
                'token1'     => $verificationCode,
            ]);

            return response()->json(['message' => 'کد تأیید به شماره تلفن ارسال شد']);
        } elseif (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            $user = Users::where('email', $input)->first();

            if (!$user) {
                return response()->json(['message' => 'ایمیل یافت نشد'], 404);
            }

            $verificationCode = rand(100000, 999999);

            UsersVerify::updateOrCreate(
                ['data' => $input],
                ['code' => $verificationCode]
            );

            Mail::to($input)->send(new VerifyEmail($verificationCode));

            return response()->json(['message' => 'کد تأیید به ایمیل ارسال شد']);
        } else {
            return response()->json(['message' => 'شماره یا ایمیل نامعتبر است'], 422);
        }
    }

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'email_phone' => 'required|string',
            'code' => 'required|integer',
            'password' => 'required',
        ]);

        $input = $data['email_phone'];

        $verification = UsersVerify::where('data', $input)
            ->where('code', $data['code'])
            ->first();

        if (!$verification) {
            return response()->json(['message' => 'کد تأیید اشتباه است'], 400);
        }

        $user = '';

        if (preg_match('/^09\d{9}$/', $input)) {
            $user = Users::where('phone', $input)->first();
        } elseif (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            $user = Users::where('email', $input)->first();
        }

        $userAuth = UsersAuth::where('user_id', $user['id'])->first();
        $userAuth->update(['password' => Hash::make($data['password'])]);

        $verification->delete();

        return response()->json([
            'message' => 'کلمه عبور با موفقیت تغییر کرد',
        ]);
    }
}
