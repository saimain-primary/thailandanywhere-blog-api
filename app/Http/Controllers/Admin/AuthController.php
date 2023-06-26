<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::guard('admin')->attempt($request->only('email', 'password'))) {
            return $this->error('', 'Credentials do not match', 401);
        }

        $admin = Auth::guard('admin')->user();

        $admin->tokens()->delete();

        return $this->success([
            'user' => $admin,
            'token' => $admin->createToken('API Token of admin id ' . $admin->id, ['admin'])->plainTextToken
        ], 'Successfully Login');
    }

    public function me(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        return $this->success($admin, 'Login Detail');
    }
}
