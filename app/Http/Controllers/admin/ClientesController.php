<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ClientesController extends Controller
{
    
    public function index() {
        return view('admin.clientes');
    }

    public function getClientes(){
        $data = DB::table('vw_clientes')->latest('id')->get();
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){                    
                    return "
                        <button onclick='getdata(".$row->id.")' class='btn btn-primary btn-sm' 
                            data-target='.default-example-modal-right'>Editar
                        </button>";                     
                })
                ->rawColumns(['action'])              
                ->make(true);
    }

    
    public function create(){
        //
    }

    
    public function store(Request $request){
        if($request->id!=''){ ///Actualizar cliente
            
            $request->validate([
                'nombres'         => 'required',
                'apellidos'       => 'required',
            ]);
            
            $id = DB::table('cliente')->where('id',$request->id)->update([
                'name'      => $request['nombres'],
                'surname'   => $request['apellidos'],               
                'dni'       => $request['dni'], 
                'email'     => $request['email'],
                'ruc'       => $request['ruc'],
                'raz_soc'   => $request['razon_social'],
                'telefono'  => $request['telefono'],
                'fch_nac'   => $request['fch_nac'],
                'direccion' => $request['direccion'],
            ]);

            if($id){
                return response()->json(['msg'=>'Actualizacion realizada...'], 200);
            }else{
                return response()->json(['msg'=>'Error interno...'], 500);
            }

        }else{/////CREAR NUEVO cliente
            
            $request->validate([
                'nombres'         => 'required',
                'apellidos'       => 'required',
            ]);
          
            $id = DB::table('cliente')->insert([
                'name'      => $request['nombres'],
                'surname'   => $request['apellidos'], 
                'dni'       => $request['dni'], 
                'email'     => $request['email'],
                'ruc'       => $request['ruc'],
                'raz_soc'   => $request['razon_social'],
                'telefono'  => $request['telefono'],
                'fch_nac'   => $request['fch_nac'],
                'direccion' => $request['direccion'],
            ]);
                      
            if($id){
                return response()->json(['msg'=>'Registro insertado...'], 200);
            }else{
                return response()->json(['msg'=>'Error interno'], 500);
            }
            
        }
    }

    
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        $datos = DB::table('cliente')->where('id',$id)->get();
      
        if($datos){
            return response()->json($datos[0],200);
        }else{
            return response()->json(['msg'=>'Error al obtener datos'], 500);
        }
    }

    
    public function update(Request $request, $id)
    {
        //
    }

    
    public function destroy($id)
    {
        $data = DB::table('cliente')->where('id',$id)->delete();
        
        if($data){
            return response()->json([ 'msg' => 'Registro eliminado con éxito!'],200);
        }else{
            return response()->json(['msg'=>'Error al eliminar registro'], 500);
        }
    }
}
