<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::guard('user')->attempt($request->only('email', 'password'))) {
            return $this->error(null, 'Credentials do not match', 401);
        }

        $user = Auth::guard('user')->user();

        $user->tokens()->delete();

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of admin id ' . $user->id)->plainTextToken
        ], 'Successfully Login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of user id ' . $user->id)->plainTextToken
        ], 'Successfully Registered');

    }
}
