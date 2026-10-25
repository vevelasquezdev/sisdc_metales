<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LineaTiempoController extends Controller
{
   
    public function index(){
        return view('admin.linea-tiempo');
    }

    
    public function create(){
        
    }

    
    public function store(Request $request)
    {
        //
    }

    
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }

    
    public function destroy($id)
    {
        //
    }
}
