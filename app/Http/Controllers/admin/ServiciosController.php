<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ServiciosController extends Controller
{
    
    public function index(Request $request)
    {
      
        if ($request->ajax()) {
            
            $data = DB::table('servicios')->where('active',1)->orderBy('orden')->get();
            
        
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
                ->addColumn('auto', function($row){                    
                    if($row->id_operador){
                        return "<span class='badge badge-success badge-pill'>si</span>";  
                    }else{
                        return "<span class='badge badge-info badge-pill'>no</span>"; 
                    }               
                })
                ->addColumn('id_operador', function($row){                    
                    if($row->id_operador){
                        $name=DB::table('users')->where('id',$row->id_operador)->get();
                        return $name[0]->name." ".$name[0]->surname;  
                    }else{
                        return ""; 
                    }               
                })
                ->rawColumns(['action','auto','id_operador'])              
                ->make(true);
        }

        $operadores=DB::table('users')->where('rol','OPERARIO')->where('active',1)->get();
        
        return view('admin.servicios',compact('operadores'));
    }

    
    public function create(Request $request,$query) // para buscador autocompletar en la orden
    {
        
    }

    public function autocompletar($query=null){ // para buscador autocompletar en la orden
        $Consulta = DB::table('servicios')->where('descripcion','like','%'.strtoupper($query).'%')->where('active',1);

        $Consulta = $Consulta->get();

        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();
            $Lista->id = $Datos->id;
            $Lista->label = trim($Datos->descripcion);
            $Lista->descripcion = trim($Datos->descripcion);
            $Lista->local = trim($Datos->local);
            
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }

    
    public function store(Request $request)
    {
        if($request->id!=''){ ///Actualizar usuario
            
            $request->validate([
                'descripcion' => 'required',        
                'maquina' => 'required',                    
            ]);
            
            $id = DB::table('servicios')->where('id',$request->id)->update([
                'descripcion'   => $request['descripcion'],
                'maquina'       => $request['maquina'], 
                'local'        => $request['local'],
                'orden'         => $request['orden'],
                'id_operador'   => $request['id_operador'],
            ]);

            if($id){
                return response()->json(['msg'=>'Actualizacion realizada...','id'=>$id], 200);
            }else{
                return response()->json(['msg'=>'Error en base de datos...'], 500);
            }

        }else{/////CREAR NUEVO SERVICIO
            
            $request->validate([
                'descripcion' => 'required',        
                'maquina' => 'required',                            
            ]);
          
            $id = DB::table('servicios')->insert([
                'descripcion'   => $request['descripcion'],
                'maquina'       => $request['maquina'], 
                'local'        => $request['local'],
                'orden'         => $request['orden'],
                'id_operador'   => $request['id_operador'], 
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
        $datos = DB::table('servicios')->where('id',$id)->get();
      
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
        //$data = DB::table('servicios')->where('id',$id)->update();
        $id = DB::table('servicios')->where('id',$id)->update([
            'active'   => 0,
        ]);
        
        if($id){
            return response()->json([ 'msg' => 'Registro eliminado con éxito!'],200);
        }else{
            return response()->json(['msg'=>'Error al eliminar registro'], 500);
        }
    }
}
