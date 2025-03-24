<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UsersVerify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PhoneController extends Controller
{
    public function sendVerifyCode(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|regex:/^09\d{9}$/|unique:users,phone',
        ]);

        $verificationCode = rand(100000, 999999);

        UsersVerify::updateOrCreate(
            ['data' => $data['phone']],
            ['code' => $verificationCode]
        );

        $response = Http::asForm()->withOptions(['verify' => false])->post('https://smspanel.trez.ir/SendPatternCodeWithUrl.ashx', [
            'AccessHash' => env('SMS_ACCESS_HASH'),
            'Mobile'     => $data['phone'],
            'PatternId'  => env('SMS_VERIFY_CODE_Pattern_Id'),
            'token1'     => $verificationCode,
        ]);

        if ($response->successful()) {
            return response()->json(['message' => 'کد تأیید ارسال شد']);
        } else {
            return response()->json(['error' => 'ارسال پیامک ناموفق بود'], 500);
        }
    }
}
