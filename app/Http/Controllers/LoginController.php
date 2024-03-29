<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\Login\GetTokenRequest;
use App\Http\Requests\Login\GetTokenAdminRequest;

use App\Models\User;
use App\Models\Profile;

class LoginController extends Controller
{
    public function getToken(GetTokenRequest $request)
    {
        $profile = Profile::where('name', 'COACH')->first();

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'profile_id' => $profile->id, 'project_id' => $request->project_id])) {
            return \Auth::user();
        }

        return response()->json([
            'code'      =>  500,
            'message'   =>  'Authentication failed.'
        ], 500);
    }

    public function getTokenAdmin(GetTokenAdminRequest $request)
    {
        $profile = Profile::where('name', 'ADMIN')->first();

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'profile_id' => $profile->id])) {
            return \Auth::user();
        }

        return response()->json([
            'code'      =>  500,
            'message'   =>  'Authentication failed.'
        ], 500);
    }
}
