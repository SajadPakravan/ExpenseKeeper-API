<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Models\UsersAuth;
use App\Models\UsersVerify;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function userInfo(Request $request)
    {
        $userAuth = $request->user();
        $user = Users::where('id', $userAuth->user_id)->first();

        return response()->json(
            [
                'id' => $user->id,
                'username' => $userAuth->username,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => $user->avatar,
            ],
            200
        );
    }

    public function username(Request $request)
    {
        $userAuth = $request->user();

        $data = $request->validate([
            'username' => 'required|string|unique:users_auth,username',
        ]);

        $userAuth->update(['username' => $data['username']]);

        return response()->json([
            'message' => 'نام کاربری با موفقیت تغییر کرد',
            'username' => $userAuth->username,
        ]);
    }

    public function name(Request $request)
    {
        $userAuth = $request->user();
        $user = Users::where('id', $userAuth->user_id)->first();

        $data = $request->validate([
            'name' => 'required|string',
        ]);

        $user->update(['name' => $data['name']]);

        return response()->json([
            'message' => 'نام با موفقیت تغییر کرد',
            'username' => $user->name,
        ]);
    }

    public function email(Request $request)
    {
        $userAuth = $request->user();
        $user = Users::where('id', $userAuth->user_id)->first();

        $data = $request->validate([
            'email' => 'required|email',
            'code' => 'required|integer',
        ]);

        $verification = UsersVerify::where('data', $data['email'])
            ->where('code', $data['code'])
            ->first();

        if (!$verification) {
            return response()->json(['message' => 'کد تأیید اشتباه است'], 400);
        }

        $user->update(['email' => $data['email']]);

        $verification->delete();

        return response()->json([
            'message' => 'ایمیل با موفقیت تغییر کرد',
            'email' => $user->email,
        ]);
    }
    public function phone(Request $request) {}
    public function avatar(Request $request) {}
}
