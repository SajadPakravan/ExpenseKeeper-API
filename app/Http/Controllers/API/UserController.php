<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Models\UsersAuth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function userInfo(Request $request)
    {
        $userAuth = $request->user();
        $user = Users::where('id', $userAuth->user_id)->first();

        return response()->json(
            $user,
            200
        );
    }
}
