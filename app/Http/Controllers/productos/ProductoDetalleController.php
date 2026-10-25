<?php

namespace App\Http\Controllers\productos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class ProductoDetalleController extends Controller
{
    
    public function index()
    {
        //
    }

    public function tabla_servicios_por_producto($id) {
        $data = DB::table('producto_detalle')->where('id_producto',$id)->orderBy('orden')->get();
            
        
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($row){                    
            $buttons =  "<button onclick='editar_servicio(".$row->id.")' class='btn btn-primary btn-sm btn-edit'  
                            title='Editar Producto'><i class='fa fa-edit'></i>
                        </button>|
                        <button onclick='eliminar_servicio(".$row->id.")' class='btn btn-danger btn-sm btn-delete'  
                                title='Eliminar Producto'><i class='fa fa-trash'></i>
                        </button>";
            return $buttons;                
        })
        ->addColumn('descripcion', function($row){                    
           
            $desc = DB::table('servicios')->where('id', $row->id_serv)->get();
            return $desc[0]->descripcion;
                            
        })
        ->rawColumns(['action','descripcion'])              
        ->make(true);
        
        
    }

    
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
      
        if($request->id!=''){ ///Actualizar producto-detalle (servicio x prod)
           
            $request->validate([
                'id_serv' => 'required',
                'precio' => 'required',                
            ]);
            
            $id = DB::table('producto_detalle')->where('id',$request->id)->update([
                'id_producto'   => $request['id_producto'],
                'id_serv'       => $request['id_serv'],                
                'precio'        => $request['precio'],
                'orden'         => $request['orden'],
            ]);

            if($id){
                return response()->json(['msg'=>'Actualizacion realizada...','id'=>$id], 200);
            }else{
                return response()->json(['msg'=>'Error en base de datos...'], 500);
            }

        }else{/////Nuevo producto-detalle (servicio x prod)
           
            $request->validate([
                'id_serv' => 'required',
                'precio' => 'required',                
            ]);
          
            $sql=DB::table('producto_detalle')->insertGetId([
                'id_producto'   => $request['id_producto'],
                'id_serv'       => $request['id_serv'],
                'fch_create'    => date('d-m-Y'),
                'precio'       => $request['precio'],
                'orden'       => $request['orden'],
            ]);
                      
            if($sql){
                return response()->json(['msg'=>'Servicio agregado...','id'=>$sql], 200);
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
        $datos = DB::table('producto_detalle')->where('id',$id)->get();
      
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
        $id = DB::table('producto_detalle')->where('id',$id)->delete();
        
        if($id){
            return response()->json([ 'msg' => 'Registro eliminado con éxito!'],200);
        }else{
            return response()->json(['msg'=>'Error al eliminar registro'], 500);
        }
    }
}
