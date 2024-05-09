<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request ) {
        
        $credentials = $request->only(['email', 'password']);

        $token = auth()->attempt($credentials);

        if($token === false){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]
        ]);
    }
}
