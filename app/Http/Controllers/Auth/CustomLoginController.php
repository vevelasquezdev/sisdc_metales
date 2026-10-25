<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomLoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Intentar iniciar sesión
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Verificar si el usuario está activo
            if (!$user->active) {
                Auth::logout();
                return redirect('/login')->with('error', 'Tu cuenta no está activa.');
            }

            // Redirigir al dashboard
            return redirect()->intended('/dashboard');
        }

        // Si las credenciales no son correctas
        return redirect('/login')->with('error', 'Credenciales incorrectas.');
    }
}
