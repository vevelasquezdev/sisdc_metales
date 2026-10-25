<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckIpAuthenticated
{
    
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->get('authenticatedIp', false)) {
            return redirect()->route('protected')->with('error', 'Acceso no autorizado');
        }

        return $next($request);
    }
}
