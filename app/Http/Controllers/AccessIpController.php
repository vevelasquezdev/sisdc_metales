<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\AccessIp;

use Illuminate\Support\Facades\DB;


class AccessIpController extends Controller
{
    public function index()
    {
        $accessIps = DB::table('ip_locales')->get();
      
        return view('acceso_ip.accesos-ip', compact('accessIps'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero_ip' => 'required|ip',
            'descripcion' => 'nullable|string',
        ]);

        AccessIp::create([
            'numero_ip' => $request->numero_ip,
            'descripcion' => $request->descripcion,
            'fch' => date('d-m-Y'),
        ]);

        return redirect()->route('protected-content');
    }

    public function destroy($id)
    {
        AccessIp::findOrFail($id)->delete();
        return redirect()->route('protected-content');
    }
}
