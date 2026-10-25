<?php

namespace App\Http\Controllers\insumos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;



class IngresoSalidaInsumosController extends Controller
{
    
    public function index()
    {
        $almacenes = DB::table('almacenes')->orderBy('id')->get();
        return view('insumos.ing_sal_rioseco',compact('almacenes'));
    }
    public function abrir_zolezzi(){
        $almacenes = DB::table('almacenes')->orderBy('id')->get();
        return view('insumos.ing_sal_zolezzi',compact('almacenes'));
    }

    public function getIngSalInsumos(Request $request){

        $from_date = $request->filled('from_date') ? (new \DateTime($request['from_date']))->format("Y-m-d") : null;
        $to_date = $request->filled('to_date') ? (new \DateTime($request['to_date']))->format("Y-m-d") : null;

        $id_alm = $request['idalm'];
        $tipo = $request['tipo'];

        $data = DB::table('vw_ingresos_salidas_insumos')
            ->where('id_almacen', $id_alm)
            ->when($tipo == 0 || $tipo == 1, function ($query) use ($tipo) {
                return $query->where('id_tipo', $tipo);
            })
            ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                return $query->whereBetween('fch', [$from_date, $to_date]);
            })            
            ->orderByDesc('id')->get();
        

        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if($row->fch==date('Y-m-d')){
                        
                        if($row->id_orden){
                            return "<span class='badge badge-danger badge-pill'> ORDEN </span>";
                        }else{
                            if($row->id_movimiento){
                                if($row->id_tipo==0){
                                    return "<button onclick='getdata(".$row->id.",".$row->id_tipo.",".($row->id_cliente??0).",".($row->id_proveedor??0).")' data-type='".$row->id_tipo."' class='btn btn-primary btn-sm' data-toggle='modal' 
                                                data-target='.default-example-modal-right-lg'><i class='fal fa-edit'></i></button>
                                            <button onclick='del(".$row->id.")' class='btn btn-danger btn-sm'><i class='fal fa-trash'></i></button> ";
                                }
                            }else{
                                return "<button onclick='getdata(".$row->id.",".$row->id_tipo.",".($row->id_cliente??0).",".($row->id_proveedor??0).")' data-type='".$row->id_tipo."' class='btn btn-primary btn-sm' data-toggle='modal' 
                                            data-target='.default-example-modal-right-lg'><i class='fal fa-edit'></i></button>
                                        <button onclick='del(".$row->id.")' class='btn btn-danger btn-sm'><i class='fal fa-trash'></i></button> ";
                            }
                            
                        }
                    }                   
                })
                ->addColumn('fch', function($row){ 
                    if($row->fch){
                        return (new \DateTime($row->fch))->format("d-m-Y");
                    }                    
                })
                ->addColumn('pvc', function($row){
                    if($row->id_pvc==0){                        
                        return "<span class='badge badge-warning badge-pill'> ".$row->pvc." </span>";
                    }else{
                        return "<span class='badge badge-info badge-pill'>  ".$row->pvc." </span>";
                    }            
                })
                ->addColumn('tipo', function($row){
                    if($row->id_tipo==0){                        
                        return "<span class='badge badge-danger badge-pill'> SALIDA </span>";
                    }else{
                        return "<span class='badge badge-success badge-pill'> INGRESO </span>";
                    }            
                })
                ->addColumn('cant', function($row){
                    if($row->cant<0){
                        return '<h5 class="text-danger my-auto" style="font-weight:bold;">'.$row->cant.'</h5>';
                    }else{
                        return '<h5 class="text-success my-auto" style="font-weight:bold;">+'.$row->cant.'</h5>';
                    }          
                })
                ->addColumn('porcentaje_gana', function($row){
                    if($row->porcentaje_gana){
                        return (float)$row->porcentaje_gana.'%';
                    }    
                })
                ->addColumn('pre_venta', function($row){
                    if($row->pre_venta){
                        return 'S/. '.$row->pre_venta.'';
                    }        
                })
                ->addColumn('pre_compra', function($row){
                    if($row->pre_compra){
                        return 'S/. '.$row->pre_compra.'';
                    }        
                })                   
                ->rawColumns(['action', 'tipo','cant','pvc','porcentaje_gana','pre_compra','pre_venta'])              
                ->make(true);
        
    }
    

    public function create() 
    {
        
    }
    public function store(Request $request){
        
        try {          
            $this->validateRequest($request);
            $data = $this->prepareData($request);
        
            if ($request->id) { // Actualizar
                $updated = DB::table('ing_sal_insumos')->where('id', $request->id)->update($data);
        
                if ($updated) {
                    if($request->movimiento==1){
                        $id=DB::table('ing_sal_insumos')->where('id', $request->id)->pluck('id_movimiento')->first();
                        $this->update_movimiento_otro_almacen($request,$id);
                    }
                    return response()->json(['msg' => 'Registro actualizado correctamente.'], 200);
                } else {
                    return response()->json(['msg' => 'No se encontró el registro para actualizar.'], 404);
                }
            } else { // Crear nuevo
                $id = DB::table('ing_sal_insumos')->insertGetId($data);
               
                if ($id) {
                    if($request->movimiento==1){
                        $this->insert_movimiento_otro_almacen($request,$id);
                    }
                    return response()->json(['msg' => 'Registro insertado correctamente.', 'id' => $id], 200);
                } else {
                    return response()->json(['msg' => 'Error interno al insertar el registro.'], 500);
                }
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['msg' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {          
            return response()->json(['msg' => 'Error interno: ' . $e->getMessage()], 500);
        }
    }

    protected function validateRequest(Request $request){
        $request->validate([
            'id_insumo'       => 'required',
            'cantidad'        => 'required|numeric',
            'tipo'            => 'required|in:0,1',
            'pre_compra'      => 'required_if:tipo,1',
            'porcentaje_gana' => 'required_if:tipo,1',
            'pre_venta'       => 'required_if:tipo,1',
            'id_almacen'      => 'required',
            'origen_destino'  => 'required',
            'id_proveedor'    => 'required_if:tipo,1',
            'id_cliente'      => 'required_if:tipo,0',
        ], [
            'porcentaje_gana.required_if' => 'Porcentaje de ganancia es requerido.',
            'cantidad.required'           => 'La cantidad es requerida.',
            'pre_compra.required_if'      => 'El precio de compra es requerido.',
            'pre_venta.required_if'       => 'El precio de venta es requerido.',
            'id_insumo.required'          => 'La descripción del insumo es requerida.',
            'id_proveedor.required_if'    => 'Seleccionar un proveedor es requerido.',
            'id_cliente.required_if'      => 'Seleccionar un cliente es requerido.',
            'tipo.in'                     => 'El campo tipo debe ser 0 o 1.',
        ]);
        
    }

    protected function prepareData(Request $request) {
        $data = [
            'id_insumo'       => $request->id_insumo,
            'tipo'            => $request->tipo,
            'obs'             => strtoupper($request->obs),
            'pre_compra'      => $request->pre_compra,
            'porcentaje_gana' => $request->porcentaje_gana,
            'pre_venta'       => $request->pre_venta,
            'id_almacen'      => $request->id_almacen,
            'pvc'             => $request->pvc,
            'origen_destino'  => $request->origen_destino,
            'id_proveedor'    => $request->id_proveedor,
            'id_cliente'      => $request->id_cliente,
            'fch'             => now()->format('Y-m-d'),
            'id_user'         => auth()->id(),
            'cant'            => ($request->tipo == 0 ? -abs($request->cantidad) : $request->cantidad),
            'id_orden'        => $request->id_orden,
            'id_orden_det'    => $request->id_orden_det,
        ];   

        return $data;
    }

    protected function insert_movimiento_otro_almacen(Request $request,$id_movimiento){ //INSERT INGRESO OTRO ALMACEN POR MOVIMIENTO
        $data=[                         
            'id_insumo'       => $request['id_insumo'],               
            'tipo'            => 1,
            'obs'             => 'MOVIMIENTO',
            'cant'            => $request['cantidad'],
            'pre_compra'      => $request['pre_venta'],
            'porcentaje_gana' => 0,
            'pre_venta'       => $request['pre_venta'],
            'pvc'             => $request['pvc'],
            'fch'             => date('Y-m-d'),
            'id_user'         => auth()->id(),
            'id_movimiento'    => $id_movimiento
        ];

        if ($request['id_almacen'] == 1) {
            $data['id_almacen']     = 2; // cambia el id_almacen para realizar el ingreso
            $data['origen_destino'] = 'ORIGEN: ALMACEN RIO SECO';
            $datos = DB::table('proveedores')
                    ->where('desc', 'like', '%' . strtoupper('RIO SECO') . '%')
                    ->where('desc', 'like', '%' . strtoupper('ALMACEN') . '%')
                    ->get();
            $data['id_proveedor']   = $datos[0]->id;
            
        }elseif ($request['id_almacen'] == 2){
            $data['id_almacen']     = 1;
            $data['origen_destino'] = 'ORIGEN: ALMACEN ZOLEZZI';
            $datos = DB::table('proveedores')
                    ->where('desc', 'like', '%' . strtoupper('RIO SECO') . '%')
                    ->where('desc', 'like', '%' . strtoupper('ALMACEN') . '%')
                    ->get();
            $data['id_proveedor']   = $datos[0]->id;
        }

        

        $id = DB::table('ing_sal_insumos')->insertGetId($data);
        
        if($id){
            DB::table('ing_sal_insumos')->where('id', $id_movimiento)->update(['id_movimiento'=>$id]);
            return response()->json(['msg'=>'Registro insertado...'], 200);
        }else{
            return response()->json(['msg'=>'Error interno'], 500);
        }
    }

    protected function update_movimiento_otro_almacen(Request $request,$id_movimiento){ //update INGRESO OTRO ALMACEN POR MOVIMIENTO
        
        $data=[  
            'obs'             => 'MOVIMIENTO',
            'cant'            => $request['cantidad'],
            'pre_compra'      => $request['pre_venta'],
            'porcentaje_gana' => 0,
            'pre_venta'       => $request['pre_venta'],
            'pvc'             => $request['pvc'],
            'fch'             => date('Y-m-d'),
            'id_user'         => auth()->id()            
        ];

        $id=DB::table('ing_sal_insumos')->where('id', $id_movimiento)->update($data);

        if($id){           
            return response()->json(['msg'=>'Registro insertado...'], 200);
        }else{
            return response()->json(['msg'=>'Error interno'], 500);
        }
    }
    
    // public function store(Request $request)
    // {
    //     if($request->id!=''){ ///Actualizar
            
    //         $request->validate([
    //             'id_insumo'       => 'required',
    //             'cantidad'        => 'required',
    //             'tipo'            => 'required|in:0,1',                
    //             'pre_compra'      => 'required_if:tipo,1',
    //             'porcentaje_gana' => 'required_if:tipo,1',              
    //             'pre_venta'       => 'required_if:tipo,1',            
    //             'id_almacen'      => 'required',                
    //             'origen_destino'  => 'required',
    //             'id_proveedor'    => 'required_if:tipo,1', // Requerido si tipo es 1.
    //             'id_cliente'      => 'required_if:tipo,0', // Requerido si tipo es 0.
    //         ], [
    //             'porcentaje_gana.required'       => 'Porcentaje de ganancia es requerida',
    //             'porcentaje_gana.required_if'    => 'Porcentaje de ganancia es requerida',
    //             'cantidad.required'              => 'Cantidad es requerida',
    //             'pre_compra.required'            => 'Precio de Compra es requerido',
    //             'pre_compra.required_if'         => 'Precio de Compra es requerido',
    //             'pre_venta.required'             => 'Precio de Venta es requerido',
    //             'pre_venta.required_if'          => 'Precio de Venta es requerido',
    //             'id_insumo.required'             => 'Descripcion del producto y/o insumo es requerido',
    //             'id_proveedor.required_if'       => 'Elegir el proveedor es requerido',
    //             'id_cliente.required_if'         => 'Elegir el cliente es requerido',
    //             'tipo.in'                        => 'El campo tipo debe ser 0 o 1.',
    //             'tipo.required'                  => 'Tipo  Ingreso/Salida es Requerido',
    //         ]);

    //         $data=[                         
    //             'id_insumo'       => $request['id_insumo'],               
    //             'tipo'            => $request['tipo'],
    //             'obs'             => $request['obs'],
    //             'pre_compra'      => $request['pre_compra'],
    //             'porcentaje_gana' => $request['porcentaje_gana'],
    //             'pre_venta'       => $request['pre_venta'],            
    //             'id_almacen'      => $request['id_almacen'],
    //             'pvc'             => $request['pvc'],
    //             'origen_destino'  => $request['origen_destino'],
    //             'id_proveedor'    => $request['id_proveedor'],
    //             'id_cliente'      => $request['id_cliente'],
    //             'fch'             => date('Y-m-d'),
    //             'id_user'         => auth()->id()
    //         ];
    //         if ($request['tipo'] == 0) {
    //             $data['cant'] = -abs($request['cantidad']); // Convierte a negativo
    //         }else{
    //             $data['cant'] = $request['cantidad'];
    //         }

    //         $affectedRows = DB::table('ing_sal_insumos')->where('id', $request->id)->update($data);

    //         if ($affectedRows > 0) {
    //             return response()->json(['msg' => 'Registro actualizado correctamente.'], 200);
    //         } else {
    //             return response()->json(['msg' => 'No se encontró el registro para actualizar.'], 404);
    //         }

    //     }else{/////CREAR NUEVO 
            
    //         $request->validate([
    //             'id_insumo'       => 'required',
    //             'cantidad'        => 'required',
    //             'tipo'            => 'required|in:0,1',                
    //             'pre_compra'      => 'required_if:tipo,1',
    //             'porcentaje_gana' => 'required_if:tipo,1',              
    //             'pre_venta'       => 'required_if:tipo,1',            
    //             'id_almacen'      => 'required',                
    //             'origen_destino'  => 'required',
    //             'id_proveedor'    => 'required_if:tipo,1', // Requerido si tipo es 1.
    //             'id_cliente'      => 'required_if:tipo,0', // Requerido si tipo es 0.
    //         ], [
    //             'porcentaje_gana.required'       => 'Porcentaje de ganancia es requerida',
    //             'porcentaje_gana.required_if'    => 'Porcentaje de ganancia es requerida',
    //             'cantidad.required'              => 'Cantidad es requerida',
    //             'pre_compra.required'            => 'Precio de Compra es requerido',
    //             'pre_compra.required_if'         => 'Precio de Compra es requerido',
    //             'pre_venta.required'             => 'Precio de Venta es requerido',
    //             'pre_venta.required_if'          => 'Precio de Venta es requerido',
    //             'id_insumo.required'             => 'Descripcion del producto y/o insumo es requerido',
    //             'id_proveedor.required_if'       => 'Elegir el proveedor es requerido',
    //             'id_cliente.required_if'         => 'Elegir el cliente es requerido',
    //             'tipo.in'                        => 'El campo tipo debe ser 0 o 1.',
    //             'tipo.required'                  => 'Tipo  Ingreso/Salida es Requerido',
    //         ]);

    //         $data=[                         
    //             'id_insumo'       => $request['id_insumo'],               
    //             'tipo'            => $request['tipo'],
    //             'obs'             => $request['obs'],
    //             'pre_compra'      => $request['pre_compra'],
    //             'porcentaje_gana' => $request['porcentaje_gana'],
    //             'pre_venta'       => $request['pre_venta'],            
    //             'id_almacen'      => $request['id_almacen'],
    //             'pvc'          => $request['pvc'],
    //             'origen_destino'  => $request['origen_destino'],
    //             'id_proveedor'    => $request['id_proveedor'],
    //             'id_cliente'      => $request['id_cliente'],
    //             'fch'             => date('Y-m-d'),
    //             'id_user'         => auth()->id()
    //         ];
    //         if ($request['tipo'] == 0) {
    //             $data['cant'] = -abs($request['cantidad']); // Convierte a negativo
    //         }else{
    //             $data['cant'] = $request['cantidad'];
    //         }

    //         $id = DB::table('ing_sal_insumos')->insert($data);
          
    //         if($id){
    //             return response()->json(['msg'=>'Registro insertado...'], 200);
    //         }else{
    //             return response()->json(['msg'=>'Error interno'], 500);
    //         }
            
    //     }
    // }

  
    public function show($id)
    {
        //
    }

   
    public function edit($id)
    {
        
        $datos = DB::table('vw_ingresos_salidas_insumos')->where('id', $id)->get();

        // Formatear la columna 'cant' a valores positivos
        $datos = $datos->map(function($item) {
            $item->cant = abs($item->cant); // Convierte a valor absoluto
            return $item;
        });
      
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
        $movimiento = DB::table('ing_sal_insumos')->where('id', $id)->first();

        if (!$movimiento) {
            return response()->json(['msg' => 'Registro no encontrado'], 404);
        }

        if ($movimiento->id_movimiento) {            
            DB::table('ing_sal_insumos')->where('id', $movimiento->id_movimiento)->delete();
        }

        // Eliminar el registro principal
        $deleted = DB::table('ing_sal_insumos')->where('id', $id)->delete();

        if ($deleted) {
            return response()->json(['msg' => 'Registro eliminado con éxito!'], 200);
        } else {
            return response()->json(['msg' => 'Error al eliminar registro'], 500);
        }
        
    }

    public function movimiento_buscar_almacen(Request $request){
        $datos = DB::table('vw_clientes')
            ->where('persona', 'like', '%' . strtoupper($request['alm']) . '%')
            ->where('persona', 'like', '%' . strtoupper('ALMACEN') . '%')
            ->get();
       

        if ($datos->isNotEmpty()) { 
            return response()->json($datos[0], 200);
        } else {
            return response()->json(['msg' => 'No se encontraron datos para el criterio proporcionado.'], 404); // Cambiar a un estado HTTP más adecuado
        }
    }
}
