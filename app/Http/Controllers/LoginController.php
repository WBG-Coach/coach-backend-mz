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
            return \Auth::user();
        }

        return response()->json([
            'code'      =>  500,
            'message'   =>  'Authentication failed.'
        ], 500); 
    }
}
