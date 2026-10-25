<?php

namespace App\Http\Controllers\insumos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProveedoresController extends Controller
{
    
    public function index()
    {
        //
    }

    public function getProveedores(){
        $data = DB::table('proveedores')->latest('id')->get();
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){                    
                                       
                })
                ->rawColumns(['action'])              
                ->make(true);
    }

    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        if($request->id!=''){ ///Actualizar cliente
            
            $request->validate([
                'razon_social'         => 'required',                
            ]);
            
            $id = DB::table('proveedores')->where('id',$request->id)->update([
                'desc'      => $request['razon_social'],
                'ruc'       => $request['ruc'],
                'telefono'  => $request['telefono'],               
            ]);

            if($id){
                return response()->json(['msg'=>'Actualizacion realizada...'], 200);
            }else{
                return response()->json(['msg'=>'Error interno...'], 500);
            }

        }else{/////CREAR NUEVO cliente
            
            $request->validate([
                'razon_social'         => 'required', 
            ]);
          
            $id = DB::table('proveedores')->insert([
                'desc'      => $request['razon_social'],
                'ruc'       => $request['ruc'],
                'telefono'  => $request['telefono'],
            ]);
                      
            if($id){
                return response()->json(['msg'=>'Registro insertado...'], 200);
            }else{
                return response()->json(['msg'=>'Error interno'], 500);
            }
            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        $datos = DB::table('proveedores')->where('id',$id)->get();
      
        if($datos){
            return response()->json($datos[0],200);
        }else{
            return response()->json(['msg'=>'Error al obtener datos'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
