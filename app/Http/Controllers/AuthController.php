<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\ApiResponse;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            $user = Auth::user();
            $token = $user->createToken('PrexChallengeToken')->accessToken;

            return ApiResponse::success(
                data: ['token' => $token],
                message: 'Login exitoso'
            );
        }

        return ApiResponse::error(
            message: 'Credenciales inválidas', 
            code: 401
        );  
    }
}