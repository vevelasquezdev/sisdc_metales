<?php

namespace App\Http\Controllers\insumos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class InsumosController extends Controller
{
   
    public function index()
    {
        //$insumos =DB::table('servicios')->where('active',1)->orderBy('orden')->get();
        return view('insumos.almacen');//tabla insumos de base de datos
    }

    public function getInsumosAlmacen(){
        $data = DB::table('insumos')->get();
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return "
                        <button onclick='getdata(".$row->id.")' class='btn btn-primary btn-sm' data-toggle='modal' 
                            data-target='.default-example-modal-right'>Editar
                        </button>|
                        <button onclick='del(".$row->id.")' class='btn btn-danger btn-sm'>Eliminar</button> ";                     
                })
                ->addColumn('fch', function($row){ 
                    if($row->fch){
                        return (new \DateTime($row->fch))->format("d-m-Y") . PHP_EOL;
                    }                    
                })
                ->rawColumns(['action'])              
                ->make(true);
    }

    public function autocompletar($query,$id_almacen){  // almacen RIO SECO  
        
        $Consulta = DB::table('vw_stock_insumos')->where('insumo','like','%'.strtoupper($query).'%')->where('id_almacen',$id_almacen)->get();
        
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();
            $Lista->id = $Datos->id;
            $Lista->label = trim($Datos->insumo);
            $Lista->stock = trim($Datos->stock);
            $Lista->precio = trim($Datos->pre_venta);
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }

    
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        if($request->id!=''){ ///Actualizar Insumo
            
            $valoresAdmitidos = ['0.4','0.5','0.6','0.7','0.8','0.9','1.0','1.2','1.5','2.0','2.5','3.0','3.5','4.0','4.5','5.0','6.5','7.0','7.5','8.0','8.5','9.0','9.5','10.0','10.5','11.0','11.5','12.0','12.5','13.0','13.5','14.0','14.5','15.0','15.5','16.0','16.5','17.0','17.5','18.0','18.5','19.0','19.5','20.0','20.5','21.0','21.5','22.0','22.5','23.0','23.5','24.0','24.5','25.0'];
            $request->validate([
                'descripcion'    => 'required',        
                'abreviacion'    => 'required', 
                'dimension'      => 'required',
                'espesor'        => 'required|in:' . implode(',', $valoresAdmitidos),
                'calidad'        => 'required',
                'acabado'        => 'required_if:calidad,C201|required_if:calidad,C304|required_if:calidad,C430|required_if:calidad,316L',             
            ]);
            
            $id = DB::table('insumos')->where('id',$request->id)->update([
                'desc'         => $request['descripcion'],
                'abrev'        => $request['abreviacion'], 
                'dimension'    => $request['dimension'],
                'espesor'      => $request['espesor'],
                'calidad'      => $request['calidad'],
                'acabado'      => $request['acabado'],
                'peso'         => $request['peso']                
            ]);

            if($id){
                return response()->json(['msg'=>'Actualizacion realizada...','id'=>$id], 200);
            }else{
                return response()->json(['msg'=>'Error en base de datos...'], 500);
            }

        }else{/////CREAR NUEVO Insumo
            $valoresAdmitidos = ['0.4','0.5','0.6','0.7','0.8','0.9','1.0','1.2','1.5','2.0','2.5','3.0','3.5','4.0','4.5','5.0','6.5','7.0','7.5','8.0','8.5','9.0','9.5','10.0','10.5','11.0','11.5','12.0','12.5','13.0','13.5','14.0','14.5','15.0','15.5','16.0','16.5','17.0','17.5','18.0','18.5','19.0','19.5','20.0','20.5','21.0','21.5','22.0','22.5','23.0','23.5','24.0','24.5','25.0'];
            $request->validate([
                'descripcion'    => 'required',        
                'abreviacion'    => 'required', 
                'dimension'      => 'required',
                'espesor'        => 'required|in:' . implode(',', $valoresAdmitidos),
                'calidad'        => 'required',
                'acabado'        => 'required_if:calidad,C201|required_if:calidad,C304|required_if:calidad,C430|required_if:calidad,316L',        
            ]);
          
            $id = DB::table('insumos')->insert([
                'desc'         => $request['descripcion'],
                'abrev'        => $request['abreviacion'], 
                'dimension'    => $request['dimension'],
                'espesor'      => $request['espesor'],
                'calidad'      => $request['calidad'],
                'acabado'      => $request['acabado'],
                'peso'         => $request['peso'],
                'fch'          => date('Y-m-d'),
                'active'       => 1
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
        $datos = DB::table('insumos')->where('id',$id)->get();
      
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
        $data = DB::table('insumos')->where('id',$id)->delete();
        
        if($data){
            return response()->json([ 'msg' => 'Registro eliminado con éxito!'],200);
        }else{
            return response()->json(['msg'=>'Error al eliminar registro'], 500);
        }
    }
}
