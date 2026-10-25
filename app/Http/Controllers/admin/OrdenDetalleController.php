<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class OrdenDetalleController extends Controller
{
    
    public function index()
    {
        //
    }

    public function get_ittems_orden_detalle(Request $request){

        $data = DB::table('vw_orden_detalle')
                    ->where('id_orden','=',$request['id_orden'])
                    ->orderBy('id')
                    ->get();

        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if($row->pago_caja==0){
                        if(Auth::user()->rol=="ADMINISTRADOR"){
                            $material=DB::table('vw_consumo_mat')->where('id_orden_det',$row->id)->first();
                            $insumo=DB::table('vw_ingresos_salidas_insumos')->where('id_orden_det',$row->id)->first();
                            if(isset($material->id)){
                                return " <a onclick='editar_precio_mat(".$row->id.")' href='javascript:void(0);' title='Editar precio' class='btn btn-info btn-sm btn-icon waves-effect waves-themed'>
                                            <i class='fal fa-dollar-sign'></i>
                                        </a>
                                        <a onclick='del_ittem(".$row->id.")' href='javascript:void(0);' title='Eliminar' class='btn btn-danger btn-sm btn-icon waves-effect waves-themed'>
                                            <i class='fal fa-trash'></i>
                                        </a>";
                            }else if(isset($insumo->id)){
                                if(date('Y-m-d')==$insumo->fch){
                                    return " <a onclick='editar_precio(".$row->id.")' href='javascript:void(0);' title='Editar precio' class='btn btn-info btn-sm btn-icon waves-effect waves-themed'>
                                            <i class='fal fa-dollar-sign'></i>
                                        </a>
                                        <a onclick='del_ittem(".$row->id.")' href='javascript:void(0);' title='Eliminar' class='btn btn-danger btn-sm btn-icon waves-effect waves-themed'>
                                            <i class='fal fa-trash'></i>
                                        </a>";
                                }                            
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
                    }                   
                                     
                })
                ->addColumn('comentario', function($row){
                    $comentarioBtn = "<a onclick='add_comentario({$row->id})' href='javascript:void(0);' title='Agregar comentario' class='btn btn-info btn-sm btn-icon waves-effect waves-themed'>
                                            <i class='fa fa-comment' aria-hidden='true'></i>
                                        </a>";

                    if ($row->pago_caja == 0) {
                        if (!empty($row->comentario)) {
                            return $row->comentario;
                        }
                        return $comentarioBtn;
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
                    $material=DB::table('vw_consumo_mat')->where('id_orden_det',$row->id)->get();
                    $insumo=DB::table('vw_ingresos_salidas_insumos')->where('id_orden_det',$row->id)->get();
                    if(isset($material[0]->id)){
                        return "RETAZO ".$material[0]->new_desc;
                    }elseif(isset($insumo[0]->id)){
                        return "MATERIAL DC - ".$insumo[0]->insumo;
                    }else{
                        return $row->descripcion;
                    }                    
                })
                ->rawColumns(['action','comentario','estado','descripcion'])              
                ->make(true);
    }

    
    public function create(Request $request) {
        if($request['tipo']=='DESCUENTO'){
            // $id_carta_dcsto = DB::table('carta')->where('des_pro','DESCUENTO')->get();
            // $dscto=$request['descuento'];
            // if($dscto>0){
            //     $dscto=$dscto*-1;
            // }           
            // $sql = DB::table('pedido_detalle_temp')->insert([
            //     'id_orden'      => $request['id_orden'],            
            //     'precio_unid'   => $request['precio_unid']
            // ]);
        }elseif($request['tipo']=='MISELANEO'){
            // $id_carta_dcsto = DB::table('carta')->where('des_pro','MISELANEO')->get();
            // $miselaneo=$request['miselaneo'];
            // $sql = DB::table('pedido_detalle_temp')->insert([
            //     'id_orden'      => $request['id_orden'],           
            //     'precio_unid'   => $request['precio_unid']
            // ]);
        }else{ 
            
            $count = DB::table('orden_detalle')->where('id_orden',$request['id_orden'])->count();
            if($count==0){
                $count=1;
            }else{
                $count+=1;
            }

            $Data = [
                'id_orden'      => $request['id_orden'],
                'cant'          => $request['cant'] ?? 1,
                'id_servicio'   => $request['id_servicio'],
                'orden'         => "NO",
                'numeracion'    => $count,
                'precio_unid'   => number_format((float) $request['precio_unid'], 2, '.', ''),
                'pre_tot'       => number_format((float) $request['precio_unid']*($request['cant'] ?? 1), 2, '.', ''),
                'fch'           => date('Y-m-d')
            ];
           
            if(isset($request['material'])){
                $Data+=['estado' => 2];
            }

            
          
            $id_detalle = DB::table('orden_detalle')->insertGetId($Data);
          
            if($id_detalle){// derivar automatico

                $derivar_auto=DB::table('servicios')->where('id',$request['id_servicio'])->select('id_operador')->get();

                if($derivar_auto[0]->id_operador>0){
                    DB::table('orden_detalle')->where('id',$id_detalle)->update([
                        'fch_ent'       => date('Y-m-d'), 
                        'hora_ent'      => date('H:i'),
                        'id_operario'   => $derivar_auto[0]->id_operador,
                    ]);
            
                    DB::table('lista_trabajo')->insert([                      
                        'id_orden_det'  => $id_detalle,
                        'id_operador'   => $derivar_auto[0]->id_operador,
                        'fch_ent'       => date('Y-m-d'), 
                        'hora_ent'      => date('H:i'), 
                        'id_servicio'   => $request['id_servicio'],
                        'id_orden'      => $request['id_orden'],
                        'id_tiempo_ref' => 3,//45min id_horas
                        'tiempo_ref'    => '00h:45m',
                        'llave'         => 1
                    ]);
                }
                
                return response()->json(['msg'=>'Producto agregado...','id_detalle'=>$id_detalle], 200);
            }else{
                return response()->json(['msg'=>'Error de base de datos'], 500);
            }
        }

       
    }

    
    public function store(Request $request){
        /////CREAR NUEVO ORDEN TEMPORAL                  
        $id_orden = DB::table('orden')->insertGetId([
            'fch_create' => date('Y-m-d'),          
            'id_user' => $request['id_user']
        ]);

        if($id_orden){
            return response()->json(['id_orden' => $id_orden ], 200);
        }else{
            return response()->json(['msg'=>'Error de base de datos...'], 500);
        } 
    }

    
    public function show($id_orden){
        
        
        
    }

    public function borrar_orden_temporal($id_orden){
        DB::table('orden_detalle')// eliminar orden en NO, en pedido
            ->where('id_orden', $id_orden)
            ->where('orden', '=','NO')           
            ->delete();
        $del=DB::table('orden')->where('id', $id_orden)->delete();
        
        if($del){
            return response()->json(['msg'=>'Orden temporal eliminada'], 200);
        }else{
            return response()->json(['msg'=>'Error de base de datos...'], 500);
        }
    }

    public function borrar_orden_no($id_orden){

        $ordenDetalles = DB::table('orden_detalle')
                            ->where('id_orden', $id_orden)
                            ->where('orden', '=', 'NO')
                            ->get();

        foreach ($ordenDetalles as $detalle) {
            // Elimina los registros relacionados en la tabla ing_sal_insumos
            DB::table('ing_sal_insumos')->where('id_orden_det', $detalle->id)->delete();
        
            // Elimina el registro actual de la tabla orden_detalle
            DB::table('orden_detalle')->where('id', $detalle->id)->delete();
        }
        return response()->json(['msg'=>'Registros orden NO eliminados..'], 200);

       
    }

    
    public function edit(Request $request, $id_orden){
       
    }

    
    public function update(Request $request, $id){// funcion para editar el precio y cantidad del detalle orden en TABLADINAMICA
        
       
        $idInsumo = DB::table('ing_sal_insumos')->where('id_orden_det', $id)->value('id_insumo');
       
        if ($idInsumo) {
            $stockDisponible = DB::table('vw_stock_insumos')->where('id', $idInsumo)->value('stock');

            if ($request->cantidad > $stockDisponible) {
                return response()->json([
                    'msg' => 'La cantidad excede el stock disponible.',
                    'stock_disponible' => $stockDisponible,
                ], 400);
            }
            
            DB::table('ing_sal_insumos')->where('id_orden_det', $id)->update([
                'cant' => ($request->cantidad*-1),
                'pre_venta' => $request->precio,
            ]);
        }
      
        $editDetalle = [
            'cant' => $request->cantidad,
            'precio_unid' => $request->precio,
            'pre_tot' => number_format((float) $request->precio * $request->cantidad, 2, '.', ''),
        ];

        $editDetalleSql = DB::table('orden_detalle')->where('id', $id)->update($editDetalle);

        if ($editDetalleSql) {
            return response()->json(['msg' => 'El registro se ha modificado.'], 200);
        } else {
            return response()->json(['msg' => 'Error al actualizar el registro.'], 500);
        }
        
        
    }

    
    public function destroy(Request $request,$id)
    {
        if($request['tipo']=='ELIMINAR'){
            DB::table('consumo_material')->where('id_orden_det',$id)->delete();
            DB::table('ing_sal_insumos')->where('id_orden_det', $id)->delete();
            $eliminar_detalle = DB::table('orden_detalle')->where('id', $id)->delete();
        }else{
            $detalle_pedido = DB::table('vw_orden_detalle')->where('id','=',$id)->get();

            $data[]=[                         
                'cant'          => $detalle_pedido[0]->cant,
                'descripcion'   => $detalle_pedido[0]->descripcion,
                'precio_unid'   => $detalle_pedido[0]->precio_unid,
                'pre_tot'       => $detalle_pedido[0]->pre_tot,
                'fch_elim'      => date('Y-m-d'),
                'razon'         => strtoupper($request['des_elim']),
                'id_user'       => Auth::user()->id
            ];
            DB::table('consumo_material')->where('id_orden_det',$id)->delete();
            DB::table('ing_sal_insumos')->where('id_orden_det', $id)->delete();
            $eliminar_detalle = DB::table('eliminados')->insert($data);
            $eliminar_detalle = DB::table('orden_detalle')->where('id', $id)->delete();
        }
        
        
        if($eliminar_detalle){
            return response()->json(['msg'=>'Eliminado...'], 200);
        }else{
            return response()->json(['msg'=>'Error de base de datos...'], 500);
        }
    }

    public function add_coment(Request $request){
        $ad_coment = DB::table('orden_detalle')
            ->where('id', $request['id_orden_detalle'])
            ->update([
                'comentario' => strtoupper($request['comentario']),               
            ]);

        if($ad_coment){
            return response()->json(['msg'=>'Comentario agregado'], 200);
        }else{
            return response()->json(['msg'=>'Error de base de datos...'], 500);
        }
    }

    public function check_orden($id_orden_detalle){        
        $check_no = DB::table('orden_detalle')
            ->where('id', $id_orden_detalle)            
            ->get();        
        return response()->json(['msg'=>$check_no[0]->orden], 200);
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
}
