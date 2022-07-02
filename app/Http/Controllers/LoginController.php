<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\Login\GetTokenRequest;

use App\Models\User;

class LoginController extends Controller
{
    public function getToken(GetTokenRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return ['api_token' => User::where('email', $request->email)->first()->api_token];
        }

        return response()->json([
            'code'      =>  500,
            'message'   =>  'Authentication failed.'
        ], 500); 
    }
}
