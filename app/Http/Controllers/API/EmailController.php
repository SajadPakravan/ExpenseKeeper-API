<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use App\Models\UsersVerify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendVerifyCode(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        $verificationCode = rand(100000, 999999);

        UsersVerify::updateOrCreate(
            ['data' => $data['email']],
            ['code' => $verificationCode]
        );

        Mail::to($data['email'])->send(new VerifyEmail($verificationCode));

        return response()->json([
            'message' => 'کد تأیید به ایمیل شما ارسال شد',
        ]);
    }
}
