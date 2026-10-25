<?php

namespace App\Http\Middleware;

use App\Models\AccessIp;
use Closure;
use Illuminate\Http\Request;

class CheckLocalIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Lista de IPs permitidas
        // $allowedIps = [
        //     //'127.0.0.1',
        //     '179.7.181.97',
            
        // ];
        $allowedIps = AccessIp::pluck('numero_ip')->toArray();
     
        $ip = $request->ip(true);

        if (!in_array($ip, $allowedIps)) {
            abort(403, 'Acceso Denegado');
        }

        return $next($request);
    }
}
