<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class CotizacionDetalleController extends Controller
{
   
    public function index()
    {
        //
    }

    public function get_ittems_cotizacion_detalle(Request $request){
        $data = DB::table('vw_cotizacion_detalle')
                    ->where('id_orden','=',$request['id_orden'])
                    ->orderBy('id')
                    ->get();

        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){                    
                    if(Auth::user()->rol=="ADMINISTRADOR"){                     
                        $material=DB::table('vw_consumo_mat_cotizacion')->where('id_orden_det',$row->id)->get();
                        if(isset($material[0]->id)){
                            return " <a onclick='editar_precio_mat_cotizacion(".$row->id.")' href='javascript:void(0);' title='Editar precio' class='btn btn-info btn-sm btn-icon waves-effect waves-themed'>
                                    <i class='fal fa-dollar-sign'></i>
                                </a>
                                <a onclick='del_ittem(".$row->id.")' href='javascript:void(0);' title='Eliminar' class='btn btn-danger btn-sm btn-icon waves-effect waves-themed'>
                                    <i class='fal fa-trash'></i>
                                </a>";
                        }else{
                            return " <a onclick='editar_precio(".$row->id.")' href='javascript:void(0);' title='Editar precio' class='btn btn-info btn-sm btn-icon waves-effect waves-themed'>
                                    <i class='fal fa-dollar-sign'></i>
                                </a>
                                <a onclick='del_ittem(".$row->id.")' href='javascript:void(0);' title='Eliminar' class='btn btn-danger btn-sm btn-icon waves-effect waves-themed'>
                                    <i class='fal fa-trash'></i>
                                </a>";
                        }
                        
                    }else{
                        if($row->orden=="NO"){
                            return "<a onclick='del_ittem(".$row->id.")' href='javascript:void(0);' title='Eliminar' class='btn btn-danger btn-sm btn-icon waves-effect waves-themed'>
                                    <i class='fal fa-trash'></i>
                                </a>";
                        }else{}
                    }                  
                })
                ->addColumn('comentario', function($row){
                    if($row->comentario){                        
                        return $row->comentario;
                    }else{
                        return "<a onclick='add_comentario(".$row->id.")' href='javascript:void(0);' title='Agregar comentario' class='btn btn-info btn-sm btn-icon waves-effect waves-themed'>
                                    <i class='fa fa-comment' aria-hidden='true'></i>
                                </a>";
                    }                
                })
                ->addColumn('estado', function($row){
                    if($row->id_estado==0){
                        return "<span class='badge badge-warning badge-pill'>".$row->estado."</span>";
                    }elseif($row->id_estado==1){
                        return "<span class='badge badge-info badge-pill'>".$row->estado."</span>";
                    }else{
                        return "<span class='badge badge-success badge-pill'>".$row->estado."</span>";
                    }             
                })
                ->addColumn('descripcion', function($row){
                    $material=DB::table('vw_consumo_mat_cotizacion')->where('id_orden_det',$row->id)->get();
                    if(isset($material[0]->id)){
                        return $material[0]->new_desc;
                    }else{
                        return $row->descripcion;
                    }
                })
                ->rawColumns(['action','comentario','estado','descripcion'])              
                ->make(true);
    }

   
    public function create(Request $request){

        $count = DB::table('cotizacion_detalle')->where('id_orden',$request['id_orden'])->count();
        if($count==0){
            $count=1;
        }else{
            $count+=1;
        }

        $Data = [
            'id_orden'      => $request['id_orden'],
            'cant'          => $request['cant'],
            'id_servicio'   => $request['id_servicio'],
            'orden'         => "NO",
            'numeracion'    => $count,
            'precio_unid'   => number_format((float) $request['precio_unid'], 2, '.', ''),
            'pre_tot'       => number_format((float) $request['precio_unid']*$request['cant'], 2, '.', ''),
            'fch'           => date('Y-m-d')
        ];
        
        if(isset($request['material'])){
            $Data+=['estado' => 2];
        }
        
        $id_detalle = DB::table('cotizacion_detalle')->insertGetId($Data);
        
        if($id_detalle){

            return response()->json(['msg'=>'Producto agregado...','id_detalle'=>$id_detalle], 200);
        }else{
            return response()->json(['msg'=>'Error de base de datos'], 500);
        }
    }

   
    public function store(Request $request)
    {
        /////CREAR NUEVO COTIZACION TEMPORAL   
                    
        $id_orden = DB::table('cotizacion')->insertGetId([
            'fch_create' => date('d-m-Y'),          
            'id_user' => $request['id_user']
        ]);

        if($id_orden){
            return response()->json(['id_orden' => $id_orden ], 200);
        }else{
            return response()->json(['msg'=>'Error de base de datos...'], 500);
        } 
    }

    
    public function show($id)
    {
        //
    }

    public function borrar_cotizacion_temporal($id_orden){
       
        DB::table('cotizacion_detalle')// eliminar cotizacion temporal
            ->where('id_orden', $id_orden)
            ->where('orden', '=','NO')           
            ->delete();
        $del=DB::table('cotizacion')->where('id', $id_orden)->delete();
        
        if($del){
            return response()->json(['msg'=>'Orden temporal eliminada'], 200);
        }else{
            return response()->json(['msg'=>'Error de base de datos...'], 500);
        }
    }
    
    public function borrar_cotizacion_no($id_orden){
        $del = DB::table('cotizacion_detalle')// eliminar orden en NO, en pedido
            ->where('id_orden', $id_orden)
            ->where('orden', '=','NO')
            ->delete();
    }

   
    public function edit($id)
    {
        //
    }

   
    public function update(Request $request, $id)
    {
        $edit_detalle = [
            'cant'          => $request['cantidad'],               
            'precio_unid'   => $request['precio'],
            'pre_tot'       => number_format((float) $request['precio']*$request['cantidad'], 2, '.', '')
        ];
        
        $edit_detalle_sql = DB::table('cotizacion_detalle')->where('id', $id)->update($edit_detalle);

        if($edit_detalle_sql){
            return response()->json(['msg'=>'El registro se ha modificado.'], 200);
        }else{
            return response()->json(['msg'=>'Error de base de datos...'], 500);
        }
    }

    
    public function destroy($id) {
        DB::table('cotizacion_detalle')->where('id', $id)->delete();
    }

    function limitar_cadena($cadena, $limite, $sufijo){
        // Si la longitud es mayor que el límite...
        if(strlen($cadena) > $limite){
            // Entonces corta la cadena y ponle el sufijo
            return substr($cadena, 0, $limite) . $sufijo;
        }
        // Si no, entonces devuelve la cadena normal
        return $cadena;
    }


    // public function check_cotizacion($id_orden_detalle){        
    //     $check_no = DB::table('cotizacion_detalle')
    //         ->where('id', $id_orden_detalle)            
    //         ->get();        
    //     return response()->json(['msg'=>$check_no[0]->orden], 200);
    // }
}
