<?php

namespace App\Http\Controllers\caja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MovimientosController extends Controller
{
    
    public function index()
    {
        $formapagos = DB::table('forma_pago')->orderBy("id")->get();       
        return view('caja.movimientos',compact('formapagos'));
    }

    public function getMovimientos(){
        $data = DB::table('vw_movimientos')->latest('id')->get();
        
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){  
                if($row->estado_id==0){
                    if(Auth::user()->rol=="ADMINISTRADOR"){
                        if(Auth::user()->name=="SADMIN"){
                            return "<button title='Ver' onclick='getDataMovimiento(".$row->id.",".'"view"'.")' class='btn btn-info btn-sm' data-toggle='modal' 
                                    data-target='#DldModalIngreso'><span class='fal fa-eye'></span>
                                </button>&nbsp;|
                                <button title='Editar' onclick='getDataMovimiento(".$row->id.")' class='btn btn-primary btn-sm' data-toggle='modal' 
                                data-target='#DldModalIngreso'><span class='fal fa-pencil'></span>
                                </button>&nbsp;|
                                <button title='Anular Ingreso' onclick='anular_Movimiento(".$row->id.")' class='btn btn-warning btn-sm'>
                                    <span class='fal fa-trash'></span>
                                </button>&nbsp;|
                                <button title='Eliminar este Ingreso' onclick='eliminar_Movimiento(".$row->id.")' class='btn btn-sm btn-danger'>
                                    <span class='fa fa-window-close'></span>
                                </button>";
                            
                        }else{
                            if($row->id_orden==""){
                                return "<button title='Ver' onclick='getDataMovimiento(".$row->id.",".'"view"'.")' class='btn btn-info btn-sm' data-toggle='modal' 
                                    data-target='#DldModalIngreso'><span class='fal fa-eye'></span>
                                    </button>&nbsp;|
                                    <button title='Editar' onclick='getDataMovimiento(".$row->id.")' class='btn btn-primary btn-sm' data-toggle='modal' 
                                    data-target='#DldModalIngreso'><span class='fal fa-pencil'></span>
                                    </button>";
                            }else{
                                return "<button title='Ver' onclick='getDataMovimiento(".$row->id.",".'"view"'.")' class='btn btn-info btn-sm' data-toggle='modal' 
                                    data-target='#DldModalIngreso'><span class='fal fa-eye'></span>
                                    </button>";
                            }
                        }
                    }else{
                        return "<button title='Ver' onclick='getDataMovimiento(".$row->id.",".'"view"'.")' class='btn btn-sm btn-info' data-toggle='modal' 
                                    data-target='#DldModalIngreso'><span class='fal fa-eye'></span>
                                </button>";
                    }                                    
                }                 
                
            })
            ->addColumn('pdf', function($row){               
                if($row->id_orden!=""){
                    return "<button title='Proforma' onclick='print_boleta(".$row->id_orden.")' class='btn btn-danger btn-sm'>
                                <span class='fa fa-print'></span>
                            </button>";
                }    
            })
            ->addColumn('monto', function($row){
                if($row->monto<0){
                    return '<h5 class="text-danger my-auto" style="font-weight:bold;">'.$row->monto.'</h5>';
                }else{
                    return '<h5 class="text-success my-auto" style="font-weight:bold;">'.$row->monto.'</h5>';
                }
                                     
            })
            ->addColumn('estado', function($row){
                if($row->estado_id == 0){
                    return "<span class='badge badge-warning badge-pill'>".$row->estado."</span>";
                }elseif($row->estado_id == 1){
                    return "<span class='badge badge-success badge-pill'>".$row->estado."</span>";
                }elseif($row->estado_id == 2){
                    return "<span class='badge badge-danger badge-pill'>".$row->estado."</span>";}                
            })
            ->addColumn('fch_emi', function($row){
                if($row->fch_emi){
                    return (new \DateTime($row->fch_emi))->format("j F Y - G:ia") . PHP_EOL;
                }                   
            })
            ->addColumn('caja', function($row){
                if($row->caja==1){
                    return "CAJA_1";
                }elseif($row->caja==2){
                    return "CAJA_2";
                }elseif($row->caja==3){
                    return "CAJA_3";
                }                   
            })               
            ->rawColumns(['action','pdf', 'monto', 'estado', 'fch_emi','caja'])              
            ->make(true);
    }

   
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        //desde 
        request()->validate([
            'fch_emi'        => 'required', 
            'id_orden'      => 'required',               
            'id_forma_pago'  => 'required',  
            'caja'           => 'required',           
        ],[
            'id_forma_pago.required' => 'Forma de pago es obligatorio',               
            'fch_emi.required'       => 'Fecha y hora es obligatorio',                          
        ]);
        
        //echo "monto: ".$request['monto']."<br>saldo: ".$request['saldo'];

        if($request['monto']>$request['saldo']){
            return response()->json(['msg'=>'0'], 200);
        }else{

            $efectivo = $request['efectivo'] ?? 0;
            $tarjeta = $request['tarjeta'] ?? 0;
            $yape = $request['yape'] ?? 0;
            $transferencia = $request['transferencia'] ?? 0;
            $credito = $request['credito'] ?? 0;
            $monto=number_format((float)($efectivo+$tarjeta+$yape+$transferencia+$credito), 2, '.', '');

            $id_movimiento = DB::table('movimientos')->insertGetId([                
                'fch_emi'        => date('Y-m-d H:i',strtotime($request['fch_emi'])),
                'caja'           => $request['caja'],
                'razon_social'   => $request['razon_social'],
                'descripcion'    => $request['descripcion'],
                'estado'         => 0,                
                'monto'          => $monto,
                'id_usuario'     => Auth::user()->id,
                'id_orden'       => $request['id_orden'],             
                'id_forma_pago'  => $request['id_forma_pago'],
                'efectivo'       => $request['efectivo'],
                'tarjeta'        => $request['tarjeta'],
                'yape'           => $request['yape'],
                'transferencia'  => $request['transferencia'],
                'credito'        => $request['credito'],
                'cupon'          => $request['cupon'],
            ]);
            
            if($id_movimiento){
                $monto_tot_orden = DB::table('orden_detalle')->where('id_orden',$request['id_orden'])->sum('pre_tot');
                $monto_tot_pagado = DB::table('movimientos')->where('id_orden',$request['id_orden'])->sum('monto');

                if($monto_tot_orden==$monto_tot_pagado){
                    DB::table('orden')->where('id',$request['id_orden'])->update([
                        'pago_caja'=>1
                    ]);
                }

                return response()->json(['msg'=>'Movimiento guardado...','id_movimiento'=>$id_movimiento], 200);
            }else{
                return response()->json(['msg'=>'Error en base de datos...'], 500);
            }

        }
        
        
        
    }


    public function insert_movimiento(Request $request){
        if($request->id!=''){
            request()->validate([
                'fch_emi'        => 'required',                
                'id_forma_pago'  => 'required',    
                'razon_social'   => 'required',              
                'caja'           => 'required',             
            ],[
                'id_forma_pago.required' => 'Forma de pago es obligatorio',               
                'fch_emi.required'       => 'Fecha y hora es obligatorio',                           
            ]);
            $efectivo = $request['efectivo'] ?? 0;
            $tarjeta = $request['tarjeta'] ?? 0;
            $yape = $request['yape'] ?? 0;
            $transferencia = $request['transferencia'] ?? 0;
            $credito = $request['credito'] ?? 0;          
    
            $monto=number_format((float)($efectivo+$tarjeta+$yape+$transferencia+$credito), 2, '.', '');
    
            $id_movimiento = DB::table('movimientos')->where('id',$request->id)->update([                
                'fch_emi'        => date('Y-m-d H:i',strtotime($request['fch_emi'])),
                'caja'           => $request['caja'],
                'razon_social'   => $request['razon_social'],
                'descripcion'    => $request['descripcion'],                               
                'monto'          => $monto,                          
                'id_forma_pago'  => $request['id_forma_pago'],
                'efectivo'       => $request['efectivo'],
                'tarjeta'        => $request['tarjeta'],
                'yape'           => $request['yape'],
                'transferencia'  => $request['transferencia'],
                'credito'        => $request['credito'],               
            ]);
           
            if($id_movimiento){
                return response()->json(['msg'=>'Movimiento Actualizado...','id_movimiento'=>$id_movimiento], 200);
            }else{
                return response()->json(['msg'=>'Error en base de datos...'], 500);
            }
        }else{
            request()->validate([
                'fch_emi'        => 'required',                
                'id_forma_pago'  => 'required',  
                'caja'           => 'required',  
                'descripcion'    => 'required',         
            ],[
                'id_forma_pago.required' => 'Forma de pago es obligatorio',               
                'fch_emi.required'       => 'Fecha y hora es obligatorio',                          
            ]);
            
            $efectivo = $request['efectivo'] ?? 0;
            $tarjeta = $request['tarjeta'] ?? 0;
            $yape = $request['yape'] ?? 0;
            $transferencia = $request['transferencia'] ?? 0;
            $credito = $request['credito'] ?? 0;          
    
            $monto=number_format((float)($efectivo+$tarjeta+$yape+$transferencia+$credito), 2, '.', '');
                
            $id_movimiento = DB::table('movimientos')->insertGetId([                
                'fch_emi'        => date('Y-m-d H:i',strtotime($request['fch_emi'])),
                'caja'           => $request['caja'],
                'razon_social'   => $request['razon_social'],
                'descripcion'    => $request['descripcion'],
                'estado'         => 0,                
                'monto'          => $monto,
                'id_usuario'     => Auth::user()->id,                         
                'id_forma_pago'  => $request['id_forma_pago'],
                'efectivo'       => $request['efectivo'],
                'tarjeta'        => $request['tarjeta'],
                'yape'           => $request['yape'],
                'transferencia'  => $request['transferencia'],
                'credito'        => $request['credito'],               
            ]);
            
            if($id_movimiento){    
                return response()->json(['msg'=>'Movimiento guardado...','id_movimiento'=>$id_movimiento], 200);
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
        $get_movimiento = DB::table('vw_movimientos')->where('id',$id)->get();
        if($get_movimiento){
            return response()->json($get_movimiento, 200);
        }else{
            return response()->json(['msg'=>'Error en base de datos...'], 500);
        }
    }

    
    public function update(Request $request, $id)
    {
        $anular_movimiento = DB::table('movimientos')->where('id',$id)->update([
            'estado'=>2
        ]);
        
        if($anular_movimiento){
            return response()->json([ 'msg' => 'Movimiento anulado con éxito...!'],200);
        }else{
            return response()->json(['msg'=>'Error al anular Movimiento...!'], 500);
        }
    }

    
    public function destroy($id)
    {
        $eliminar_movimiento = DB::table('movimientos')->where('id',$id)->delete();
        
        if($eliminar_movimiento){
            return response()->json([ 'msg' => 'Movimiento Eliminado con éxito...!'],200);
        }else{
            return response()->json(['msg'=>'Error al Eliminar Movimiento...!'], 500);
        }
    }
}
