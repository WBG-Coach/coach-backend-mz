<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Configuracao;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if (\Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended('home');
        }
        
        abort('500', 'ERRO!');
    }

    public function logout(Request $request)
    {
        \Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
