<?php

namespace App\Http\Controllers\insumos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class StockController extends Controller
{
    
    public function index()
    {
        return view('insumos.insumos_stock');
    }

    public function getInsumosStock(Request $request){
        $fecha=$request['fch'];
        $almacen=$request['id_almacen'];

        if($request['fch']==date('d-m-Y')){
            if($almacen==1 || $almacen==2){
                $data = DB::table('vw_stock_insumos')->where('id_almacen',$almacen)->orderByDesc('id_almacen')->orderByDesc('stock')->get();
            }else{               
                $data = DB::table('vw_stock_insumos')->orderByDesc('id_almacen')->orderByDesc('stock')->get();
            }
        }else{
            if($almacen==1 || $almacen==2){
                $data = DB::table('vw_historial_stock')->where('id_almacen',$almacen)->where('fch', $request['fch'])->orderByDesc('id_almacen')->orderByDesc('stock')->get();
            }else{
                $data = DB::table('vw_historial_stock')->where('fch', $request['fch'])->orderByDesc('id_almacen')->orderByDesc('stock')->get();                
            }
        }
       
        return Datatables::of($data)
                ->addIndexColumn()
                
                ->addColumn('ingresos', function($row){
                    if($row->ingresos<=0){
                        return '<h5 class="text-danger my-auto" style="font-weight:bold;">'.$row->ingresos.'</h5>';
                    }else{
                        return '<h5 class="text-success my-auto" style="font-weight:bold;">'.$row->ingresos.'</h5>';
                    }          
                })
                ->addColumn('salidas', function($row){
                    if($row->salidas<=0){
                        return '<h5 class="text-danger my-auto" style="font-weight:bold;">'.$row->salidas.'</h5>';
                    }else{
                        return '<h5 class="text-success my-auto" style="font-weight:bold;">'.$row->salidas.'</h5>';
                    }          
                }) 
                ->addColumn('stock', function($row){
                    if($row->stock<=0){
                        return '<h5 class="text-danger my-auto" style="font-weight:bold;">'.$row->stock.'</h5>';
                    }else{
                        return '<h5 class="text-success my-auto" style="font-weight:bold;">'.$row->stock.'</h5>';
                    }          
                })
                ->addColumn('pre_venta', function($row){
                    if($row->pre_venta){
                        return 'S/. '.$row->pre_venta;
                    }else{
                        return 'S/. 0.00';
                    }        
                })             
                ->rawColumns(['ingresos','salidas','stock','pre_venta'])              
                ->make(true);
    }

   
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        //
    }

    
    public function show($id)
    {
        //
    }

   
    public function edit($id,Request $request)
    {
        $datos = DB::table('vw_stock_insumos')->where('id',$id)->where('id_almacen',$request['id_almacen'])->get();
      
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
        //
    }


    public function llenarHistorialStock(){
        $stockInsumos = DB::table('vw_stock_insumos')->get(); // Obtén los datos de la vista

        try {
            foreach ($stockInsumos as $stock) {
                DB::table('historial_stock')->updateOrInsert(
                    [
                        'id_insumo' => $stock->id,
                        'id_almacen' => $stock->id_almacen,
                        'fch' => $stock->fch,
                    ],
                    [
                        'ingresos' => $stock->ingresos,
                        'salidas' => $stock->salidas,
                        'stock' => $stock->stock,
                        'id_user' => auth()->id(),
                        'pre_venta' => $stock->pre_venta                        
                    ]
                );
            }
        
            return response()->json(['msg' => 'Historial de stock registrado correctamente.'], 200);
        } catch (\Exception $e) {
            return response()->json(['msg' => 'Ocurrió un error: ' . $e->getMessage()], 500);
        }   
        
      
    }
}

// $stockInsumos = DB::table('vw_stock_insumos')->get();          
//         // $data = [];
//         foreach ($stockInsumos as $stock) {
           

//             DB::table('historial_stock')->updateOrInsert(
//                 [
//                     'id_insumo' => $stock->id,
//                     'id_almacen' => $stock->id_almacen,
//                     'fch' => $stock->fch,
//                 ],
//                 [
//                     'ingresos' => $stock->ingresos,
//                     'salidas' => $stock->salidas,
//                     'stock' => $stock->stock,
//                     'id_user' => auth()->id(),
//                     'pre_venta' => $stock->pre_venta,
//                     'estado' => 1, // Ajusta este valor si corresponde
//                 ]
//             );
//         }
        
        
//         $data=DB::table('historial_stock')->insert($data);
       
//         if($data){
//             return response()->json([ 'msg' => 'Historial de stock registrado correctamente.'],200);
//         }else{
//             return response()->json(['msg'=>'Ocurrio un error'], 500);
//         }
 // $data[] = [
            //     'id_insumo'     => $stock->id,
            //     'id_almacen'    => $stock->id_almacen,                   
            //     'ingresos'      => $stock->ingresos,
            //     'salidas'       => $stock->salidas,
            //     'stock'         => $stock->stock,                                        
            //     'fch'           => $stock->fch,
            //     'id_user'       => auth()->id(), // Usuario que realiza el registro
            //     'pre_venta'     => $stock->pre_venta, // Ajusta si tienes datos de precio
            // ];