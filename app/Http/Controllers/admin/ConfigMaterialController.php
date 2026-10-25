<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ConfigMaterialController extends Controller
{
   
    public function index()
    {
        return view('admin.config-material');
    }

    public function getMateriales(){
        $data = DB::table('conf_material')->get();
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){                    
                    return "
                        <button onclick='getdata(".$row->id.")' class='btn btn-primary btn-sm' data-toggle='modal' 
                            data-target='.default-example-modal-right'>Editar
                        </button>|
                        <button onclick='del(".$row->id.")' class='btn btn-danger btn-sm'>Eliminar</button>                                         
                        ";                     
                })
                ->rawColumns(['action'])              
                ->make(true);
    }
    
    public function create()
    {
        //
    }

    
    public function store(Request $request){
        if($request->id!=''){ ///Actualizar Material
            
            $request->validate([
                'desc'          => 'required',        
                'abreviacion'   => 'required', 
                'precio'        => 'required'               
            ]);
            
            $id = DB::table('conf_material')->where('id',$request->id)->update([
                'desc'          => $request['desc'],
                'abreviacion'   => $request['abreviacion'], 
                'precio'        => $request['precio'],
            ]);

            if($id){
                return response()->json(['msg'=>'Actualizacion realizada...','id'=>$id], 200);
            }else{
                return response()->json(['msg'=>'Error en base de datos...'], 500);
            }

        }else{/////CREAR NUEVO material
            
            $request->validate([
                'desc'          => 'required',        
                'abreviacion'   => 'required', 
                'precio'        => 'required'                 
            ]);
          
            $id = DB::table('conf_material')->insert([
                'desc'          => $request['desc'],
                'abreviacion'   => $request['abreviacion'], 
                'precio'        => $request['precio'],  
            ]);
                      
            if($id){
                return response()->json(['msg'=>'Servicio agregado...','id'=>$id], 200);
            }else{
                return response()->json(['msg'=>'Error en base de datos...'], 500);
            }
            
        }
    }

    
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        $datos = DB::table('conf_material')->where('id',$id)->get();
      
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
        $data = DB::table('conf_material')->where('id',$id)->delete();
        
        if($data){
            return response()->json([ 'msg' => 'Registro eliminado con éxito!'],200);
        }else{
            return response()->json(['msg'=>'Error al eliminar registro'], 500);
        }
    }
}
