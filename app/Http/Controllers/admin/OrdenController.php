<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Codedge\Fpdf\Fpdf\Fpdf;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use PDF;
use Illuminate\Support\Carbon;


class OrdenController extends Controller
{
    protected $fpdf;
    protected $B = 0;
    protected $I = 0;
    protected $U = 0;
    protected $HREF = '';
    public function __construct()
    {
        //$this->fpdf = new Fpdi();
        $this->fpdf = new Fpdf('P','mm',array(203,297));
    }

    public function index()
    {
        $servicios = DB::table('servicios')->get();
        $formapagos = DB::table('forma_pago')->orderBy("id")->get();
        $materiales = DB::table('conf_material')->orderBy("id")->get();
         
        return view('admin.orden',compact('servicios','formapagos','materiales'));
    }

    public function getOrdenesServicio(Request $request){
        $data = DB::table('vw_orden')->whereNotNull('id_cliente')->whereNotNull('des_gen')->latest('id')->get();

        if($request->filled('from_date') && $request->filled('to_date')){
            $data = $data->whereBetween('fch_create', [date("Y-m-d", strtotime($request->from_date)), date("Y-m-d", strtotime($request->to_date))]);
        }

        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){  
                   
                       $buttons = '<div style="display:flex; gap:4px; justify-content:center; align-items:center;">
                            <button class="btn btn-primary btn-sm" onclick="AbrirOrdenServicio('.$row->id.','.$row->id_user.',&quot;'.$row->rol.'&quot;,'.$row->id_cliente.')" data-toggle="modal" data-target=".default-example-modal-right" title="Abrir Orden de Servicio"><i class="fa fa-clipboard"></i></button>
                            <button class="btn btn-info btn-sm" type="button" onclick="hoja_produccion('.$row->id.')" data-toggle="tooltip" title="Imprimir Cotización"><i class="fal fa-print"></i></button>';

                        if($row->ruta_url || $row->ruta_url2 || $row->ruta_url3 || $row->ruta_url4 || $row->ruta_url5)
                            $buttons .= '<button class="btn btn-danger btn-sm" onclick="open_modal_ver_archivos('.$row->id.')" data-toggle="modal" data-target=".default-example-modal-right" title="Ver archivos"><i class="fal fa-file-image"></i></button>';

                        return $buttons . '</div>';    
                })
                ->addColumn('estado', function($row){
                    return $row->id_estado;                            
                })
                ->addColumn('entrega', function($row){
                    if($row->entrega==0){ 
                        if($row->pago_caja==1 && $row->id_estado==2){
                            return "<button onclick='realizar_entrega(".$row->id.")' class='btn btn-primary btn-sm' data-toggle='modal' 
                                    data-target='.default-example-modal-right' title='Realizar entrega del producto'><i class='fa fa-thumbs-up'></i>
                                </button>";
                        }
                    }else{
                        return "<span class='badge badge-success badge-pill'> SI </span>";
                    }            
                })
                ->addColumn('pago_caja', function($row){
                    if($row->pago_caja==0){                        
                        return "<span class='badge badge-danger badge-pill'> NO </span>";
                    }else{
                        return "<span class='badge badge-success badge-pill'> SI </span>";
                    }            
                })
                ->addColumn('fecha_entrega', function($row){
                    if($row->fecha_entrega){
                        return (new \DateTime($row->fecha_entrega))->format("d-m-Y") . PHP_EOL;
                    }                   
                })
                ->addColumn('fch_create', function($row){
                    if($row->fch_create){
                        return (new \DateTime($row->fch_create))->format("d-m-Y") . PHP_EOL;
                    }                   
                })
                ->rawColumns(['action','estado','entrega','pago_caja','fecha_entrega','fch_create'])              
                ->make(true);
    }

    public function print_boleta($id_orden)
    {
        $datos = DB::table('vw_boleta')->where('id_orden', $id_orden)->orderBy('numeracion')->get();

        if ($datos->isEmpty()) {
            abort(404, 'Could not create office or assign it to administrator');
        }

        $this->fpdf->AddPage();
        $this->fpdf->Image('smartadmin/dist/img/DC_PNG.jpg', 140, 12, 53, 20, 'JPG');

        // Encabezado de la empresa
        $this->fpdf->SetFont('Arial', 'B', 18);
        $this->fpdf->SetTextColor(0, 0, 0);
        $this->fpdf->Cell(150, 10, utf8_decode(strtoupper("PROFORMA N° {$datos[0]->codigo_orden}")), 0, 1, 'L');

        $this->fpdf->SetFont('Arial', '', 10);
        $this->fpdf->SetTextColor(39, 39, 51);
        $empresa_info = [
            "RUC: 20607329843",
            "Dirección: Urb. San Lorenzo Av. Malecón Zolezzi 403-A Mro. Melgar - Arequipa",
            "Teléfono: 054 625743  Celular: 956778012 - 946036052",
            "Email: dhymarkcompanysac@gmail.com",
        ];
        foreach ($empresa_info as $line) {
            $this->fpdf->Cell(150, 5, utf8_decode($line), 0, 1, 'L');
        }

        $this->fpdf->Ln(5);

        // Información general de la orden
        $this->fpdf->Cell(59, 5, utf8_decode("Local: {$datos[0]->procedencia}"), 0, 0);
        $this->fpdf->Cell(50, 5, utf8_decode("Fecha de emisión: " . date('d-m-Y H:i')), 0, 1, 'L');
        $this->fpdf->Cell(80, 5, utf8_decode("Asesor: {$datos[0]->name}"), 0, 1, 'L');

        $this->fpdf->Ln(5);

        // Información del cliente
        $this->fpdf->Cell(90, 5, utf8_decode("Cliente: {$datos[0]->cliente}                                           Ruc:{$datos[0]->ruc}"), 0, 1);
        $this->fpdf->Cell(75, 5, utf8_decode("Email: {$datos[0]->email}"), 0, 0);
        $this->fpdf->Cell(60, 5, utf8_decode("Teléfono: {$datos[0]->telefono}"), 0, 1);
        $this->fpdf->Cell(90, 5, utf8_decode("Dirección: {$datos[0]->direccion}"), 0, 1);
        $this->fpdf->Cell(120, 5, utf8_decode("Fecha y Hora de entrega: " . date('d-m-Y', strtotime($datos[0]->fecha_entrega)) . " {$datos[0]->hora_entrega}"), 0, 1);

        $this->fpdf->Ln(7);

        // Tabla de productos
        $this->fpdf->SetFont('Arial', 'B', 10);
        $this->fpdf->SetFillColor(178, 178, 178);
        $this->fpdf->Cell(109, 8, utf8_decode("Descripción"), 1, 0, 'C', true);
        $this->fpdf->Cell(15, 8, utf8_decode("Cant."), 1, 0, 'C', true);
        $this->fpdf->Cell(25, 8, utf8_decode("Precio"), 1, 0, 'C', true);
        $this->fpdf->Cell(32, 8, utf8_decode("Subtotal"), 1, 1, 'C', true);

        $this->fpdf->SetFont('Arial', '', 9);
        $this->fpdf->SetTextColor(39, 39, 51);

        // $total = 0;
        // foreach ($datos as $data) {
        //     $subtotal = $data->precio_unid * $data->cant;
        //     //$this->fpdf->Cell(109, 7, utf8_decode($data->descripcion), 1, 0, 'L');
        //     // $this->fpdf->Cell(15, 7, utf8_decode($data->cant), 1, 0, 'C');
        //     // $this->fpdf->Cell(25, 7, utf8_decode(number_format($data->precio_unid, 2)), 1, 0, 'C');
        //     // $this->fpdf->Cell(32, 7, utf8_decode(number_format($subtotal, 2)), 1, 1, 'C');
        //     $total += $subtotal;
        // }

        /*----------  Detalles de la tabla  ----------*/
        $to=0;
        $resta_material_dc=0;
        foreach($datos as $data){
            $material_dc=DB::table('vw_ingresos_salidas_insumos')->where('id_orden_det',$data->id)->first();
            if($material_dc){
                $this->fpdf->Cell(109,7,utf8_decode($material_dc->insumo),'L',0,'L');
                $this->fpdf->Cell(15,7,utf8_decode($data->cant),'L',0,'C');
                $this->fpdf->Cell(25,7,utf8_decode(number_format(round($data->precio_unid,2), 2, ',', ' ')),'L',0,'C');    
                $this->fpdf->Cell(32,7,utf8_decode(number_format(round($data->precio_unid*$data->cant,2), 2, ',', ' ')),'LR',0,'C');
                $this->fpdf->Ln(7);
                $resta_material_dc+=number_format((float)$data->precio_unid*$data->cant, 2, '.', '');
            }
            // $this->fpdf->Cell(109,7,utf8_decode($data->descripcion),'L',0,'L');
            // $this->fpdf->Cell(15,7,utf8_decode($data->cant),'L',0,'C');
            // $this->fpdf->Cell(25,7,utf8_decode(number_format(round($data->precio_unid,2), 2, ',', ' ')),'L',0,'C');    
            $y= $data->precio_unid*$data->cant;    
            // $this->fpdf->Cell(32,7,utf8_decode(number_format(round($data->precio_unid*$data->cant,2), 2, ',', ' ')),'LR',0,'C');
            // $this->fpdf->Ln(7);
            $to += $y;
        }
           
        /*----------  Fin Detalles de la tabla  ----------*/

        /** A CUENTA SALDO Y TOTAL */
        $monto_pagado = DB::table('movimientos')->where('id_orden', $id_orden)->sum('monto');
        $precio_total = DB::table('orden_detalle')->where('id_orden', $id_orden)->sum('pre_tot');
        $saldo = number_format((float)$precio_total-$monto_pagado, 2, '.', '');

        $material_dc=DB::table('vw_ingresos_salidas_insumos')->where('id_orden',$data->id_orden)->first();
        if($material_dc){
            $this->fpdf->Cell(109,7,"SERVICIO",'LB',0,'L');
            $this->fpdf->Cell(15,7,"",'LB',0,'C');            
            $this->fpdf->Cell(25,7,'','LRB',0,'C');
            $this->fpdf->Cell(32,7,utf8_decode(number_format(round($to-$resta_material_dc,2), 2, ',', ' ')),'LRB',0,'C');
            $this->fpdf->Ln(7);
        }

        $this->fpdf->Cell(149,7,utf8_decode($datos[0]->des_gen),'LB',0,'L');
        $this->fpdf->Cell(32,7,"Total:".utf8_decode(number_format(round($precio_total,2), 2, ',', ' ')),'LRB',0,'C');
        $this->fpdf->Ln(7);


        $this->fpdf->SetY(242);
        $this->fpdf->SetFont('Arial','B',9);
        if($datos[0]->igv==0){
            $this->fpdf->Cell(109,7,utf8_decode("Incl. IGV: NO"),'',0,'L');
        }else{
            $this->fpdf->Cell(109,7,utf8_decode("Incl. IGV: SI"),'',0,'L');
        }
        
        $this->fpdf->Cell(38,7,utf8_decode("A CUENTA: "),'',0,'R');
        $this->fpdf->Cell(34,7,utf8_decode("S/. ".$monto_pagado),'',0,'C');
        $this->fpdf->Ln(5);
        if($datos[0]->material==0){
            $this->fpdf->Cell(109,7,utf8_decode("Incl. Material: NO"),'',0,'L');
        }else{
            $this->fpdf->Cell(109,7,utf8_decode("Incl. Material: SI"),'',0,'L');
        }         
        
        $this->fpdf->Cell(38,7,utf8_decode("SALDO: "),'',0,'R');
        $this->fpdf->Cell(34,7,utf8_decode("S/. ".$saldo),'',0,'C');
        $this->fpdf->Ln(5);

        if($datos[0]->plano==0){
            $this->fpdf->Cell(109,7,utf8_decode("Incl. Plano: NO"),'',0,'L');
        }else{
            $this->fpdf->Cell(109,7,utf8_decode("Incl. Plano: SI"),'',0,'L');
        }  
        

        $this->fpdf->Cell(38,7,utf8_decode("TOTAL: "),'',0,'R');
        $this->fpdf->Cell(34,7,utf8_decode("S/. ".$precio_total),'',0,'C');

    
        $this->fpdf->Ln(5);
        
        $this->fpdf->SetFont('Arial','',6);

        $this->fpdf->SetTextColor(39,39,51);
        $this->fpdf->Ln(1);
        $this->fpdf->MultiCell(0,4,utf8_decode("NOTA: "),0,'L',false);
        $this->fpdf->MultiCell(0,3,utf8_decode("** Una vez entregado el producto y/o servicio no se aceptan cambios ni devoluciones. **"),0,'L',false);
        $this->fpdf->MultiCell(0,3,utf8_decode("** Los productos deberán ser recogidos dentro de las 24 horas como contrato pagará derecho de almacenamiento. **"),0,'L',false);
        $this->fpdf->MultiCell(0,3,utf8_decode("** La empresa no se responsabiliza por trabajos y/o productos que no han sido recogidos en la fecha pactada. **"),0,'L',false);
        $this->fpdf->MultiCell(0,3,'Sistemas para empresas WebSoftAqp 927245347',0,'C',false);


        # Nombre del archivo PDF #
        $this->fpdf->Output("I","Proforma_".$datos[0]->cliente."_".$datos[0]->codigo_orden.".pdf",true);
    }


   


    public function get_saldo($id_orden){
        $monto_pagado = DB::table('movimientos')->where("id_orden",$id_orden)->sum('monto');
        return response()->json(['msg'=>$monto_pagado], 200);
    }

    public function realizar_entrega_producto($id_orden){
        DB::table('orden')->where('id',$id_orden)->update([ //orden detalle actual
            'entrega'        => 1
        ]);
        return response()->json(['msg'=>'Se realizo el cambio de estado a Entregado.'], 200);
    }


    
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {       
        
        $code = str_pad($request['id_orden'],7, "0", STR_PAD_LEFT);

        $validate_file=$request->hasFile('file');
        $validate_file2=$request->hasFile('file2');
        $validate_file3=$request->hasFile('file3');
        $validate_file4=$request->hasFile('file4');
        $validate_file5=$request->hasFile('file5');

        

        $cliente_name=DB::table('vw_clientes')->where('id',$request['id_cliente'])->get();
        $cliente_name=str_replace(' ','',trim($cliente_name[0]->persona));

        if ($validate_file==true || $validate_file2==true || $validate_file3==true || $validate_file4==true || $validate_file5==true) { 
           
            if($validate_file==true){
                $file = $request->file('file');          
                $extension = $file[0]->getClientOriginalExtension();
                $newfilename = str_replace(",","_",$cliente_name)."_".$code.'_1.'.$extension;
                
                $currentURL = '/storage/'.$newfilename;
                if($extension=='pdf' || $extension=='jpg' || $extension=='jpeg' || $extension=='dwg'){                
                    $file[0]->move('storage',$newfilename);               
                }else{
                    return response()->json(['msg'=>'Solo archivos pdf, jpg, jpeg, dwg'], 500);
                }
            }

            if($validate_file2==true){
                $file2 = $request->file('file2');          
                $extension2 = $file2[0]->getClientOriginalExtension();
                $newfilename2 = str_replace(",","_",$cliente_name)."_".$code.'_2.'.$extension2;
                
                $currentURL2 = '/storage/'.$newfilename2;
                if($extension2=='pdf' || $extension2=='jpg' || $extension2=='jpeg' || $extension2=='dwg'){                
                    $file2[0]->move('storage',$newfilename2);              
                }else{
                    return response()->json(['msg'=>'Solo archivos pdf, jpg, jpeg, dwg'], 500);
                }
            }
            if($validate_file3==true){
                $file3 = $request->file('file3');          
                $extension3 = $file3[0]->getClientOriginalExtension();
                $newfilename3 = str_replace(",","_",$cliente_name)."_".$code.'_3.'.$extension3;
                
                $currentURL3 = '/storage/'.$newfilename3;
                if($extension3=='pdf' || $extension3=='jpg' || $extension3=='jpeg' || $extension3=='dwg'){                
                    $file3[0]->move('storage',$newfilename3);              
                }else{
                    return response()->json(['msg'=>'Solo archivos pdf, jpg, jpeg, dwg'], 500);
                }
            }
            if($validate_file4==true){
                $file4 = $request->file('file4');          
                $extension4 = $file4[0]->getClientOriginalExtension();
                $newfilename4 = str_replace(",","_",$cliente_name)."_".$code.'_4.'.$extension4;
                
                $currentURL4 = '/storage/'.$newfilename4;
                if($extension4=='pdf' || $extension4=='jpg' || $extension4=='jpeg' || $extension4=='dwg'){                
                    $file4[0]->move('storage',$newfilename4);              
                }else{
                    return response()->json(['msg'=>'Solo archivos pdf, jpg, jpeg, dwg'], 500);
                }
            }
            if($validate_file5==true){
                $file5 = $request->file('file5');          
                $extension5 = $file5[0]->getClientOriginalExtension();
                $newfilename5 = str_replace(",","_",$cliente_name)."_".$code.'_5.'.$extension5;
                
                $currentURL5 = '/storage/'.$newfilename5;
                if($extension5=='pdf' || $extension5=='jpg' || $extension5=='jpeg' || $extension5=='dwg'){                
                    $file5[0]->move('storage',$newfilename5);              
                }else{
                    return response()->json(['msg'=>'Solo archivos pdf, jpg, jpeg, dwg'], 500);
                }
            }
        

            $check_no = DB::table('orden_detalle')
                ->where('id_orden', $request['id_orden'])
                ->where('orden', '=','NO')            
                ->count();

            if($check_no>0){
                
                $up_orden_det = DB::table('orden_detalle')
                    ->where('id_orden', $request['id_orden'])
                    ->where('orden', '=','NO')
                    ->update(['orden' => 'SI']);

                $updateData = [
                    'procedencia'     => $request['procedencia'],
                    'fch_create'      => $request['fch_create'],
                    'fecha_entrega'   => $request['fecha_entrega'],
                    'hora_entrega'    => $request['hora_entrega'],
                    'id_cliente'      => $request['id_cliente'],
                    'igv'             => $request['igv'],
                    'material'        => $request['material'],
                    'plano'           => $request['plano'],
                    'des_gen'         => $request['des_gen'],
                    'correccion'      => $request['correccion'],
                    'rapido'          => $request['rapido'],
                    'chek_material'   => $request['chek_material'],
                    'aprobado_por_cliente' => $request['aprobado_por_cliente']           
                ];
                
                if($validate_file==true){
                    $updateData+=['ruta_url' => $currentURL ?? null];}
                if($validate_file2==true){
                    $updateData+=['ruta_url2' => $currentURL2 ?? null];}
                if($validate_file3==true){
                    $updateData+=['ruta_url3' => $currentURL3 ?? null];}
                if($validate_file4==true){
                    $updateData+=['ruta_url4' => $currentURL4 ?? null];}
                if($validate_file5==true){
                    $updateData+=['ruta_url5' => $currentURL5 ?? null];}

                
                $up_orden = DB::table('orden')->where('id', $request['id_orden'])->update($updateData);

                $cliente=DB::table('cliente')->where('id', $request['id_cliente'])->get();
                DB::table('movimientos')->where('id_orden', $request['id_orden'])->update([
                    'razon_social'      => $cliente[0]->name." ".$cliente[0]->surname,
                ]);

                if($up_orden_det){                    
                    return response()->json(['msg'=>'Orden de servicio actualizada.','id_orden' =>$request['id_orden']], 200);
                }else{
                    return response()->json(['msg'=>'Error de base de datos...'], 200);
                }
            }else{
                $updateData = [
                    'procedencia'     => $request['procedencia'],
                    'fch_create'      => $request['fch_create'],
                    'fecha_entrega'   => $request['fecha_entrega'],
                    'hora_entrega'    => $request['hora_entrega'],
                    'id_cliente'      => $request['id_cliente'],
                    'igv'             => $request['igv'],
                    'material'        => $request['material'],
                    'plano'           => $request['plano'],
                    'des_gen'         => $request['des_gen'],
                    'correccion'      => $request['correccion'],
                    'rapido'          => $request['rapido'],
                    'chek_material'   => $request['chek_material'],
                    'aprobado_por_cliente' => $request['aprobado_por_cliente']
                ];
              
                if($validate_file==true){
                    $updateData+=['ruta_url' => $currentURL ?? null];}
                if($validate_file2==true){
                    $updateData+=['ruta_url2' => $currentURL2 ?? null];}
                if($validate_file3==true){
                    $updateData+=['ruta_url3' => $currentURL3 ?? null];}
                if($validate_file4==true){
                    $updateData+=['ruta_url4' => $currentURL4 ?? null];}
                if($validate_file5==true){
                    $updateData+=['ruta_url5' => $currentURL5 ?? null];}
                
            
                $up_orden = DB::table('orden')->where('id', $request['id_orden'])->update($updateData);

                $cliente=DB::table('cliente')->where('id', $request['id_cliente'])->get();
                DB::table('movimientos')->where('id_orden', $request['id_orden'])->update([
                    'razon_social'      => $cliente[0]->name." ".$cliente[0]->surname,
                ]);
                
                return response()->json(['msg'=>0], 200);
            }
        }else{
  
            $check_no = DB::table('orden_detalle')
                ->where('id_orden', $request['id_orden'])
                ->where('orden', '=','NO')            
                ->count();

            if($check_no>0){
         
                $up_orden_det = DB::table('orden_detalle')
                    ->where('id_orden', $request['id_orden'])
                    ->where('orden', '=','NO')
                    ->update(['orden' => 'SI']);

                $up_orden = DB::table('orden')
                    ->where('id', $request['id_orden'])                
                    ->update([                    
                        'procedencia'     => $request['procedencia'],
                        'fch_create'      => $request['fch_create'],
                        'fecha_entrega'   => $request['fecha_entrega'],
                        'hora_entrega'    => $request['hora_entrega'],
                        'id_cliente'      => $request['id_cliente'],
                        'igv'             => $request['igv'],
                        'material'        => $request['material'],
                        'plano'           => $request['plano'],
                        'des_gen'         => $request['des_gen'],
                        'correccion'      => $request['correccion'],
                        'rapido'          => $request['rapido'],
                        'chek_material'   => $request['chek_material'],
                        'aprobado_por_cliente' => $request['aprobado_por_cliente']
                    ]);
                $cliente=DB::table('cliente')->where('id', $request['id_cliente'])->get();
                DB::table('movimientos')->where('id_orden', $request['id_orden'])->update([
                    'razon_social'      => $cliente[0]->name." ".$cliente[0]->surname,
                ]);
               
                if($up_orden_det){                    
                    return response()->json(['msg'=>'Orden de servicio actualizada.','id_orden' =>$request['id_orden']], 200);
                }else{
                    return response()->json(['msg'=>'Error de base de datos...'], 200);
                }
            }else{
               
                $up_orden = DB::table('orden')
                    ->where('id', $request['id_orden'])                
                    ->update([                    
                        'procedencia'     => $request['procedencia'],
                        'fch_create'      => $request['fch_create'],
                        'fecha_entrega'   => $request['fecha_entrega'],
                        'hora_entrega'    => $request['hora_entrega'],
                        'id_cliente'      => $request['id_cliente'],
                        'igv'             => $request['igv'],
                        'material'        => $request['material'],
                        'plano'           => $request['plano'],
                        'des_gen'         => $request['des_gen'],
                        'correccion'      => $request['correccion'],
                        'rapido'          => $request['rapido'],
                        'chek_material'   => $request['chek_material'],
                        'aprobado_por_cliente' => $request['aprobado_por_cliente']
                    ]);
                $cliente=DB::table('cliente')->where('id', $request['id_cliente'])->get();
                
                DB::table('movimientos')->where('id_orden', $request['id_orden'])->update([
                    'razon_social'      => $cliente[0]->name." ".$cliente[0]->surname,
                ]);

                return response()->json(['msg'=>0], 200);
            }
        }

        
    }

    function enviar_lista_trab_automatico($id_orden){
        /**AQUI SE ENVIA DIRECTO AL OPERADOR PLEGADO/GUILLOTINA/SOLTADURA TIG 1 */
        $detalle_plegado = DB::table('orden_detalle')->where('id_orden',$id_orden)->where('id_servicio',16)->where('orden','SI')->get();
       
        
        if(isset($detalle_plegado[0]->id)){
          
            DB::table('orden_detalle')->where('id',$detalle_plegado[0]->id)->update([
                'fch_ent'       => date('Y-m-d'), 
                'hora_ent'      => date('H:i'),
                'id_operario'   => 32 //Antonio Chuctaya       
            ]);
            
            $detalle = DB::table('orden_detalle')->where('id',$detalle_plegado[0]->id)->get();
    
            DB::table('lista_trabajo')->insert([                      
                'id_orden_det'  => $detalle_plegado[0]->id,
                'id_operador'   => 32, //Antonio Chuctaya
                'fch_ent'       => date('Y-m-d'), 
                'hora_ent'      => date('H:i'), 
                'id_servicio'   => 16,
                'id_orden'      => $id_orden,
                'tiempo_ref'    => '01h:00m'
            ]);

            echo "ENVIADO HA LISTA DE TRABAJO SERVICIO DE PLEGADO";
        }else{
            echo "NO SE ENVIO A LISTA DE TRABAJOS GUILLOTINA/PLEGADO/SOLDADURA TIG 1";
        }
    }

    
    public function show($id_orden){
        $datos = DB::table('vw_orden')->where('id',$id_orden)->get();
      
        if($datos){
            return response()->json($datos[0],200);
        }else{
            return response()->json(['msg'=>'Error al obtener datos'], 500);
        }
    }

   
    public function edit(Request $request,$id){ //eliminar plano
        $data = DB::table('orden')->where('id',$id)->get();     
        
        $num=$request['num_plano'];
        
        switch ($num):
            case 1:
                if($data[0]->ruta_url=="" || $data[0]->ruta_url==null){
                    return response()->json(['msg'=>'0'], 200);
                }else{
                    $url = $data[0]->ruta_url;
                    if(\File::exists(public_path($url))){
                        DB::table('orden')->where('id',$id)->update([
                            'ruta_url'      => null,
                        ]);
                        \File::delete(public_path($url));              
                    }else{
                        echo 'File does not exists.';               
                    }
                    return response()->json(['msg'=>'1'], 200);
                }
                break;
            case 2:
                if($data[0]->ruta_url2=="" || $data[0]->ruta_url2==null){
                    return response()->json(['msg'=>'0'], 200);
                }else{
                    $url = $data[0]->ruta_url2;
                    if(\File::exists(public_path($url))){
                        DB::table('orden')->where('id',$id)->update([
                            'ruta_url2'      => null,
                        ]);
                        \File::delete(public_path($url));              
                    }else{
                        echo 'File does not exists.';               
                    }
                    return response()->json(['msg'=>'1'], 200);
                }
                break;
            case 3:
                if($data[0]->ruta_url3=="" || $data[0]->ruta_url3==null){
                    return response()->json(['msg'=>'0'], 200);
                }else{
                    $url = $data[0]->ruta_url3;
                    if(\File::exists(public_path($url))){
                        DB::table('orden')->where('id',$id)->update([
                            'ruta_url3'      => null,
                        ]);
                        \File::delete(public_path($url));              
                    }else{
                        echo 'File does not exists.';               
                    }
                    return response()->json(['msg'=>'1'], 200);
                }
                break;
            case 4:
                if($data[0]->ruta_url4=="" || $data[0]->ruta_url4==null){
                    return response()->json(['msg'=>'0'], 200);
                }else{
                    $url = $data[0]->ruta_url4;
                    if(\File::exists(public_path($url))){
                        DB::table('orden')->where('id',$id)->update([
                            'ruta_url4'      => null,
                        ]);
                        \File::delete(public_path($url));              
                    }else{
                        echo 'File does not exists.';               
                    }
                    return response()->json(['msg'=>'1'], 200);
                }
                break;
            case 5:
                if($data[0]->ruta_url5=="" || $data[0]->ruta_url5==null){
                    return response()->json(['msg'=>'0'], 200);
                }else{
                    $url = $data[0]->ruta_url5;
                    if(\File::exists(public_path($url))){
                        DB::table('orden')->where('id',$id)->update([
                            'ruta_url5'      => null,
                        ]);
                        \File::delete(public_path($url));              
                    }else{
                        echo 'File does not exists.';               
                    }
                    return response()->json(['msg'=>'1'], 200);
                }
                break;
        endswitch;
    }

    
    public function update(Request $request, $id){
        //
    }

    
    public function destroy($id){
        //
    }

    public function EnviarEmail($id_orden){
        $data_orden = DB::table('vw_orden')->where('id', $id_orden)->get();
        $datos = DB::table('vw_boleta')->where('id_orden', $id_orden)->orderBy('numeracion')->get();
        $this->fpdf->AddPage();        
        # Logo de la empresa formato png #
        $this->fpdf->Image('smartadmin/dist/img/DC_PNG.jpg',140,12,53,20,'JPG');

        # Encabezado y datos de la empresa #
        $this->fpdf->SetFont('Arial','B',18);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(150,10,utf8_decode(strtoupper("PROFORMA N° ".$datos[0]->codigo_orden)),0,0,'L');

        $this->fpdf->Ln(9);

        $this->fpdf->SetFont('Arial','',10);
        $this->fpdf->SetTextColor(39,39,51);
        $this->fpdf->Cell(150,9,utf8_decode("RUC: 20455462844"),0,0,'L');

        $this->fpdf->Ln(5);
        $this->fpdf->Cell(150,9,utf8_decode("Dirección: Urb. San Lorenzo Av. Malecón Zolezzi 403-A Mro. Melgar - Arequipa"),0,0,'L');
        $this->fpdf->Ln(5);
        $this->fpdf->Cell(150,9,utf8_decode("Teléfono: 054 625743  Celular: 956778012 - 946036052"),0,0,'L');
        $this->fpdf->Ln(5);
        $this->fpdf->Cell(150,9,utf8_decode("Email: dhymarkcompanysac@gmail.com"),0,0,'L');

        $this->fpdf->Ln(10);
        
        $this->fpdf->Cell(125,0,'','T');
        $this->fpdf->Ln(1);

        $this->fpdf->SetFont('Arial','',10);
        $this->fpdf->Cell(59,7,utf8_decode("Procedencia: ".$datos[0]->procedencia),0,0); 
        $this->fpdf->Cell(50,7,utf8_decode("Fecha de emisión: ".date('Y-m-d H:i')),0,0,'L');  
        

        $this->fpdf->Ln(5);

        $this->fpdf->SetFont('Arial','',10);
        $this->fpdf->Cell(80,7,utf8_decode("Recepcionista: ".$datos[0]->name),0,0,'L'); 
       

        $this->fpdf->Ln(8);
        
        $this->fpdf->Cell(125,0,'','T');
        $this->fpdf->Ln(1);


        $this->fpdf->SetFont('Arial','',10);
        $this->fpdf->Cell(90,7,utf8_decode("Cliente: ".$datos[0]->cliente),0,0);
        $this->fpdf->Ln(5);
        $this->fpdf->Cell(75,7,utf8_decode("Email: ".$datos[0]->email),0,0);        
        $this->fpdf->Cell(60,7,utf8_decode("Teléfono: ".$datos[0]->telefono),0,0);
        $this->fpdf->Ln(5);
        $this->fpdf->Cell(90,7,utf8_decode("Dirección: ".$datos[0]->direccion),0,0);
        $this->fpdf->Ln(5);
        $this->fpdf->Cell(6,7,utf8_decode("Fecha y Hora de entrega: ".$datos[0]->fecha_entrega." ".$datos[0]->hora_entrega),0,0);
        
        $this->fpdf->Ln(9);
      

        # Tabla de productos #
        $this->fpdf->SetFont('Arial','',9);
        $this->fpdf->SetFillColor(0,0,0);
        $this->fpdf->SetDrawColor(0,0,0);//23,83,201
        $this->fpdf->SetTextColor(255,255,255);
        $this->fpdf->Cell(109,8,utf8_decode("Descripción"),1,0,'C',true);
        $this->fpdf->Cell(15,8,utf8_decode("Cant."),1,0,'C',true);
        $this->fpdf->Cell(25,8,utf8_decode("Precio"),1,0,'C',true);        
        $this->fpdf->Cell(32,8,utf8_decode("Subtotal"),1,0,'C',true);

        $this->fpdf->Ln(8);

        
        $this->fpdf->SetTextColor(39,39,51);


        /*----------  Detalles de la tabla  ----------*/
        $to=0;
        foreach($datos as $data){
            $this->fpdf->Cell(109,7,utf8_decode($data->descripcion),'L',0,'L');
            $this->fpdf->Cell(15,7,utf8_decode($data->cant),'L',0,'C');
            $this->fpdf->Cell(25,7,utf8_decode(number_format(round($data->precio_unid,2), 2, ',', ' ')),'L',0,'C');    
            $y= $data->precio_unid*$data->cant;    
            $this->fpdf->Cell(32,7,utf8_decode(number_format(round($data->precio_unid*$data->cant,2), 2, ',', ' ')),'LR',0,'C');
            $this->fpdf->Ln(7);
            $to += $y;
        }
        $st = $to / 1.18;
        $igv = $st * 0.18;
        /*----------  Fin Detalles de la tabla  ----------*/

        /** A CUENTA SALDO Y TOTAL */
        $monto_pagado = DB::table('movimientos')->where('id_orden', $id_orden)->sum('monto');
        $precio_total = DB::table('orden_detalle')->where('id_orden', $id_orden)->sum('pre_tot');
        $saldo = number_format((float)$precio_total-$monto_pagado, 2, '.', '');
        
        $this->fpdf->SetFont('Arial','B',9);
        if($datos[0]->igv==0){
            $this->fpdf->Cell(109,7,utf8_decode("Incl. IGV: NO"),'T',0,'L');
        }else{
            $this->fpdf->Cell(109,7,utf8_decode("Incl. IGV: SI"),'T',0,'L');
        }
         
        $this->fpdf->Cell(38,7,utf8_decode("A CUENTA: "),'T',0,'R');
        $this->fpdf->Cell(34,7,utf8_decode("S/. ".$monto_pagado),'T',0,'C');
        $this->fpdf->Ln(5);
        if($datos[0]->material==0){
            $this->fpdf->Cell(109,7,utf8_decode("Incl. Material: NO"),'',0,'L');
        }else{
            $this->fpdf->Cell(109,7,utf8_decode("Incl. Material: SI"),'',0,'L');
        }         
        
        $this->fpdf->Cell(38,7,utf8_decode("SALDO: "),'',0,'R');
        $this->fpdf->Cell(34,7,utf8_decode("S/. ".$saldo),'',0,'C');
        $this->fpdf->Ln(5);

        if($datos[0]->plano==0){
            $this->fpdf->Cell(109,7,utf8_decode("Incl. Plano: NO"),'',0,'L');
        }else{
            $this->fpdf->Cell(109,7,utf8_decode("Incl. Plano: SI"),'',0,'L');
        }  
         

        $this->fpdf->Cell(38,7,utf8_decode("TOTAL: "),'',0,'R');
        $this->fpdf->Cell(34,7,utf8_decode("S/. ".$precio_total),'',0,'C');

        # Impuestos & totales #
     

        $this->fpdf->Ln(5);
        

        $this->fpdf->Ln(12);

        $this->fpdf->SetFont('Arial','',8);

        $this->fpdf->SetTextColor(39,39,51);
        $this->fpdf->MultiCell(0,4,utf8_decode("NOTA: "),0,'L',false);
        $this->fpdf->Ln(1);

        $this->fpdf->MultiCell(0,4,utf8_decode("** Una vez entregado el producto y/o servicio no se aceptan cambios ni devoluciones. **"),0,'L',false);
        $this->fpdf->MultiCell(0,4,utf8_decode("** Los productos deberán ser recogidos dentro de las 24 horas como contrato pagará derecho de almacenamiento. **"),0,'L',false);
        $this->fpdf->MultiCell(0,4,utf8_decode("** La empresa no se responsabiliza por trabajos y/o productos que no han sido recogidos en la fecha pactada. **"),0,'L',false);
        $this->fpdf->Ln(9);

        // email stuff (change data below)
        $to = $data_orden[0]->email; 
        $from = "dhymarkcompanysac@gmail.com"; 
        $subject = "Proforma: ".$datos[0]->codigo_orden." Cliente: ".$datos[0]->cliente; 
        $message = "<p>Comuníquese con nosotros vía Whatsapp 946 036 052</p>";

        // a random hash will be necessary to send mixed content
        $separator = md5(time());

        // carriage return type (we use a PHP end of line constant)
        $eol = PHP_EOL;

        // attachment name
        $filename = "DCProforma".$datos[0]->codigo_orden.".pdf";

        // encode data (puts attachment in proper format)
        $pdfdoc = $this->fpdf->Output("", "S");
        $attachment = chunk_split(base64_encode($pdfdoc));

        // main header
        $headers  = "From: ".$from.$eol;
        $headers .= "MIME-Version: 1.0".$eol; 
        $headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"";

        // no more headers after this, we start the body! //

        $body = "--".$separator.$eol;
        $body .= "Content-Transfer-Encoding: 7bit".$eol.$eol;
        $body .= "Estimado cliente, gracias por su preferencia. Le hacemos llegar su orden de servicio.".$eol;

        // message
        $body .= "--".$separator.$eol;
        $body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
        $body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
        $body .= $message.$eol;

        // attachment
        $body .= "--".$separator.$eol;
        $body .= "Content-Type: application/octet-stream; name=\"".$filename."\"".$eol; 
        $body .= "Content-Transfer-Encoding: base64".$eol;
        $body .= "Content-Disposition: attachment".$eol.$eol;
        $body .= $attachment.$eol;
        $body .= "--".$separator."--";

        // send message
        mail($to, $subject, $body, $headers);

        return response()->json(['cod'=>'1','msg'=>'Email enviado'], 200);
      
    }

   

    public function print_ticket_png($id_orden){   
        // este codigo funciona en produccion
        $datos = DB::table('vw_boleta')->where('id_orden', $id_orden)->orderBy('numeracion')->get();
        //Dimensiones en píxeles para una imagen de 62mm x 100mm a 150 DPI
        $width = 366;
        $height = 590;

        //Crear una instancia de la imagen
        $img = Image::canvas($width, $height, '#ffffff');

        $overlayImagePath = url('smartadmin/dist/img/DC_PNG.jpg');//'https://' . $domain . '/smartadmin/dist/img/DC_PNG.jpg';//public_path('smartadmin/dist/img/DC_PNG.jpg');
        $overlayImage = Image::make($overlayImagePath)->resize(335, 95); 
        $img->insert($overlayImage, 'top-left', 16, 20);
      
        $img->text(utf8_decode('Urb. San Lorenzo Av. Malecón Zolezzi 403-A'), 25, 140, function($font){$font->file(3); });
        $img->text('Mariano Melgar - Arequipa', 90, 157, function($font){$font->file(3); });
        $img->text('Cel. 956778012 - 946036052 Tel. 054-625743', 20, 174, function($font){$font->file(3); });
        
        $img->text('Cliente:', 140, 194, function($font){$font->file(5); });

        $text = utf8_decode($datos[0]->cliente);
        $fontSize = 18;
        $yPosition = 200; // Ajusta esta posición según tus necesidades      
        $tempImg = Image::canvas(1, 1);
        $tempImg->text($text, 0, 0, function($font) use ($fontSize) {
            $font->file(5);
            $font->size($fontSize);
        });
        $textWidth = strlen($text)*10;
        $xPosition = ($width - $textWidth)/2;

        $img->text($text, $xPosition, $yPosition, function($font) use ($fontSize) {
            $font->file(5);
            $font->size($fontSize);
            $font->color('#000000'); // Color del texto
            $font->align('left'); // Alineación horizontal (ya no se necesita centrado aquí)
            $font->valign('top'); // Alineación vertical desde arriba
        });
        
       
        $img->text('Cod. Orden :  '.$datos[0]->codigo_orden, 16, 235, function($font){ $font->file(3); });
        $img->text('Fecha Creacion:  ' .date('d-m-Y', strtotime($datos[0]->fch_create)), 16, 252, function($font){ $font->file(3); });
        $img->text('Fecha Imp.: ' .date('d-m-Y'). '   Hora: ' .date('H:i'), 16, 269, function($font){ $font->file(3); });
        $img->text('Recepcionista:  ' .utf8_decode($datos[0]->name), 16, 286, function($font){ $font->file(3); });
        $img->text('Telf. Cliente:  ' .utf8_decode($datos[0]->telefono), 16, 303, function($font){ $font->file(3); });
      
        $link_qr="https://dcmetales.com/print_boleta/".$datos[0]->id_orden;
        $overlayImage = Image::make("https://image-charts.com/chart?chs=180x180&cht=qr&chl=".urlencode($link_qr))->resize(220, 220); 
        $img->insert($overlayImage, '', 75, 313);

        $overlayImagePath = url('smartadmin/dist/img/FONDO_QR.png');//'https://' . $domain . '/smartadmin/dist/img/FONDO_QR.png';//public_path('smartadmin/dist/img/FONDO_QR.png');
        $overlayImage = Image::make($overlayImagePath)->resize(230, 225); 
        $img->insert($overlayImage, '', 70, 310);

        $img->text('Sistemas para empresas WebSoftAqp 927245347', 50, 570, function($font){ $font->file(2); });
        
    
        $directory = base_path('public_html/storage/qr/');//'https://' . $domain . '/storage/qr/'; //public_path('storage/qr/');      
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
                
        $filePath = $directory . 'QR_generada_'.$datos[0]->codigo_orden.'.png';
        
        $img->save($filePath);
        $imageData = (string) $img->encode('png');
        return response($imageData, 200)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', "attachment; filename=QR_generada_".$datos[0]->codigo_orden.".png");
    }

    public function print_ticket_pdf($id_orden){
        
        $datos = DB::table('vw_boleta')->where('id_orden', $id_orden)->orderBy('numeracion')->get();
     
        
        if(!isset($datos[0])){
            abort(404, 'Could not create office or assign it to administrator');
        }else{
            $this->fpdf = new Fpdf('P','mm',array(70,110));
            $datos = DB::table('vw_boleta')->where('id_orden', $id_orden)->get();
            $this->fpdf->AddPage();        
            $this->fpdf->Image('smartadmin/dist/img/DC_PNG.jpg', '5','4','60','15','JPG');
            $this->fpdf->Ln(10);
            $this->fpdf->SetFont('Helvetica','', 8);       
            $this->fpdf->Cell(50,4,utf8_decode('Urb. San Lorenzo Av. Malecón Zolezzi 403-A'),0,1,'C');
            $this->fpdf->Cell(50,4,'Mro. Melgar - Arequipa',0,1,'C');
            $this->fpdf->Cell(50,4,'Cel. 956778012 - 946036052  Tel. 054 625743',0,1,'C');
            
            

            // DATOS FACTURA        
            $this->fpdf->Ln(1);
            $this->fpdf->SetFont('Helvetica','B',10);
            $this->fpdf->Cell(50,4,'Cliente:',0,1,'C');
            $this->fpdf->Cell(50,4,utf8_decode($datos[0]->cliente),0,1,'C');
            $this->fpdf->SetFont('Helvetica', '', 8);
            $this->fpdf->SetX(5);
            $this->fpdf->Cell(60,4,'Cod. Orden :  ' .$datos[0]->codigo_orden,0,1,'L');
            $this->fpdf->SetX(5);
            $this->fpdf->Cell(60,4,'Fecha Creacion.:  ' .date('d-m-Y', strtotime($datos[0]->fch_create)),0,1,'L');
            $this->fpdf->SetX(5);
            $this->fpdf->Cell(60,4,'Fecha Imp.: ' .date('d-m-Y'). '          Hora:  ' .date('H:i'),0,1,'L');
            $this->fpdf->SetX(5);
            $this->fpdf->Cell(60,4,'Recepcionista:  ' .utf8_decode($datos[0]->name),0,1,'L');
            $this->fpdf->SetX(5);
            $this->fpdf->Cell(60,4,'Telf.Cliente:  ' .$datos[0]->telefono,0,1,'L');
            
            
            $this->fpdf->SetFont('Helvetica', '', 7);
            //$link_qr="https://dhymarkperu.com/print_boleta/".encrypt($datos[0]->id_orden);
            $link_qr="https://dcmetales.com/print_boleta/".$datos[0]->id_orden;
            $this->fpdf->Image("https://image-charts.com/chart?chs=180x180&cht=qr&chl=".urlencode($link_qr), 15, 62, 40, 40, "png");
            $this->fpdf->Image('smartadmin/dist/img/FONDO_QR.png', '15','62','40','40.5','png');
     
            // PIE DE PAGINA
            //$this->fpdf->Ln(60);
            //$this->fpdf->text(15,103,'GRACIAS POR SU PREFERENCIA',0,1,'C');    
            $this->fpdf->Ln(1);    
            $this->fpdf->SetFont('Helvetica', '', 7);
            $this->fpdf->text(7,106,'Sistemas para empresas WebSoftAqp 927245347');
            
            
            //abrir
            $this->fpdf->Output('ORDEN-'.$datos[0]->codigo_orden.'.pdf','i');

        }        
    }

    

    public function print_ticket_proforma($id_orden){  //proforma formato pequeño
        $datos = DB::table('vw_boleta')->where('id_orden', $id_orden)->orderBy('numeracion')->get();
        // dd($datos);
        if(!isset($datos[0])){
            abort(404, 'Could not create office or assign it to administrator');
        }else{
            $this->fpdf = new Fpdf('P','mm',array(80,150));
            // $datos = DB::table('vw_boleta')->where('id_orden', $id_orden)->get();
            $this->fpdf->AddPage();        
            $this->fpdf->Image('smartadmin/dist/img/DC_PNG.jpg', '10','7','60','20','JPG');
            $this->fpdf->Ln(19);
            $this->fpdf->SetFont('Helvetica','', 8);
            $this->fpdf->Cell(60,4,utf8_decode("RUC: 20455462844"),0,1,'C');      
            $this->fpdf->Cell(60,4,utf8_decode('Urb. San Lorenzo Av. Malecón Zolezzi 403-A'),0,1,'C');
            $this->fpdf->Cell(60,4,'Mro. Melgar - Arequipa',0,1,'C');
            $this->fpdf->Cell(60,4,'Cel. 956778012 - 946036052  Tel. 054 625743',0,1,'C');
            
            

            // DATOS FACTURA        
            $this->fpdf->Ln(2);
            $this->fpdf->SetFont('Helvetica','B',10);
            $this->fpdf->Cell(60,4,'Cliente:',0,1,'C');
            $this->fpdf->Cell(60,4,utf8_decode($datos[0]->cliente),0,1,'C');
            $this->fpdf->SetFont('Helvetica', '', 8);
            $this->fpdf->Cell(60,4, utf8_decode('Proforma N°- ').$datos[0]->codigo_orden,0,1,'');
            // $this->fpdf->Cell(60,4, utf8_decode('Fecha Emisión.:  ') .date('d-m-Y', strtotime($datos[0]->fch_create)),0,1,'');
            $this->fpdf->Cell(60,4,'Fecha Imp.: ' .date('d-m-Y'). '          Hora Imp.:  ' .date('H:i'),0,1,'');
            $this->fpdf->Cell(60,4,'Asesor:  ' .$datos[0]->name,0,1,'L');
            $this->fpdf->Cell(60,4,'Ruc:  ' .$datos[0]->ruc,0,0,'L');
            // COLUMNAS
            
            $this->fpdf->Ln(5);
            $this->fpdf->Cell(60,0,'','T');
            $this->fpdf->Ln(0);

            # Tabla de productos #
            $this->fpdf->SetFont('Arial', '', 7);
            $this->fpdf->SetFillColor(0, 0, 0);
            $this->fpdf->SetDrawColor(0, 0, 0); // 23,83,201
            $this->fpdf->SetTextColor(255, 255, 255);
            $this->fpdf->Cell(34, 4, utf8_decode("Descripción"), 1, 0, 'C', true);
            $this->fpdf->Cell(8, 4, utf8_decode("Cant."), 1, 0, 'C', true);
            $this->fpdf->Cell(10, 4, utf8_decode("Precio"), 1, 0, 'C', true);
            $this->fpdf->Cell(10, 4, utf8_decode("Subtotal"), 1, 0, 'C', true);

            $this->fpdf->Ln(4);

            $this->fpdf->SetTextColor(39, 39, 51);

            $this->fpdf->SetFont('Arial', '', 6);

            $to = 0;
            $resta_material_dc = 0;

            foreach ($datos as $data) {
                $material_dc = DB::table('vw_ingresos_salidas_insumos')->where('id_orden_det', $data->id)->first();
                if ($material_dc) {
                    // Calcular el alto de la celda más alta en la fila
                    $descripcion = utf8_decode($material_dc->insumo);
                    $descripcionWidth = 34; // Ancho de la celda de descripción
                    $cellHeight = 4; // Altura de cada línea en MultiCell
                    $lines = $this->fpdf->GetStringWidth($descripcion) > $descripcionWidth ? ceil($this->fpdf->GetStringWidth($descripcion) / $descripcionWidth) : 1;
                    $maxHeight = $lines * $cellHeight;
            
                    // Columna de descripción con MultiCell
                    $x = $this->fpdf->GetX();
                    $y = $this->fpdf->GetY();
                    $this->fpdf->MultiCell($descripcionWidth, $cellHeight, $descripcion, 1, 'L');
                    $this->fpdf->SetXY($x + $descripcionWidth, $y);
            
                    // Rellenar las demás columnas con la misma altura máxima
                    $this->fpdf->Cell(8, $maxHeight, utf8_decode($data->cant), 1, 0, 'C');
                    $this->fpdf->Cell(10, $maxHeight, utf8_decode(number_format(round($data->precio_unid, 2), 2, ',', ' ')), 1, 0, 'C');
                    $this->fpdf->Cell(10, $maxHeight, utf8_decode(number_format(round($data->precio_unid * $data->cant, 2), 2, ',', ' ')), 1, 0, 'C');
            
                    // Mover el cursor a la siguiente línea
                    $this->fpdf->Ln($maxHeight);
            
                    // Calcular el subtotal
                    $resta_material_dc += number_format((float)$data->precio_unid * $data->cant, 2, '.', '');
                }
            
                $y = $data->precio_unid * $data->cant;
                $to += $y;
            }
            
            $st = $to / 1.18;
            $igv = $st * 0.18;
            /*----------  Fin Detalles de la tabla  ----------*/

            /** A CUENTA SALDO Y TOTAL */
            $monto_pagado = DB::table('movimientos')->where('id_orden', $id_orden)->sum('monto');
            $precio_total = DB::table('orden_detalle')->where('id_orden', $id_orden)->sum('pre_tot');
            $saldo = number_format((float)$precio_total-$monto_pagado, 2, '.', '');


            /** A CUENTA SALDO Y TOTAL */
            $monto_pagado = DB::table('movimientos')->where('id_orden', $id_orden)->sum('monto');
            $precio_total = DB::table('orden_detalle')->where('id_orden', $id_orden)->sum('pre_tot');
            $saldo = number_format((float)$precio_total-$monto_pagado, 2, '.', '');

            $material_dc=DB::table('vw_ingresos_salidas_insumos')->where('id_orden',$data->id_orden)->first();
            if($material_dc){
                $this->fpdf->Cell(34,7,"SERVICIO",'LB',0,'L');
                $this->fpdf->Cell(8,7,"",'LB',0,'C');            
                $this->fpdf->Cell(10,7,'','LRB',0,'C');
                $this->fpdf->Cell(10,7,utf8_decode(number_format(round($to-$resta_material_dc,2), 2, ',', ' ')),'LRB',0,'C');
                $this->fpdf->Ln(7);
            }

            $this->fpdf->Cell(42,7,utf8_decode($datos[0]->des_gen),'LB',0,'L');
            $this->fpdf->Cell(20,7,"Total:".utf8_decode(number_format(round($precio_total,2), 2, ',', ' ')),'LRB',0,'C');
            $this->fpdf->Ln(7);
            
            $this->fpdf->SetFont('Arial','B',6);
            if($datos[0]->igv==0){
                $this->fpdf->Cell(35,5,utf8_decode("Incl. IGV: NO"),'T',0,'L');
            }else{
                $this->fpdf->Cell(35,5,utf8_decode("Incl. IGV: SI"),'T',0,'L');
            }
            
            $this->fpdf->Cell(15,5,utf8_decode("A CUENTA: "),'T',0,'R');
            $this->fpdf->Cell(12,5,utf8_decode("S/. ".$monto_pagado),'T',0,'R');
            $this->fpdf->Ln(5);
            if($datos[0]->material==0){
                $this->fpdf->Cell(35,1,utf8_decode("Incl. Material: NO"),'',0,'L');
            }else{
                $this->fpdf->Cell(35,1,utf8_decode("Incl. Material: SI"),'',0,'L');
            }         
            
            $this->fpdf->Cell(15,1,utf8_decode("SALDO: "),'',0,'R');
            $this->fpdf->Cell(12,1,utf8_decode("S/. ".$saldo),'',0,'R');
            $this->fpdf->Ln(3);

            if($datos[0]->plano==0){
                $this->fpdf->Cell(15,1,utf8_decode("Incl. Plano: NO"),'',0,'L');
            }else{
                $this->fpdf->Cell(15,1,utf8_decode("Incl. Plano: SI"),'',0,'L');
            }  
            

            $this->fpdf->Cell(35,1,utf8_decode("TOTAL: "),'',0,'R');
            $this->fpdf->Cell(12,1,utf8_decode("S/. ".$precio_total),'',0,'R');


            
            

            $this->fpdf->Ln(2);

            $this->fpdf->SetFont('Arial','',5);

            $this->fpdf->SetTextColor(39,39,51);
            $this->fpdf->MultiCell(0,3,utf8_decode("NOTA: "),0,'L',false);
            $this->fpdf->MultiCell(0,3,utf8_decode("*Una vez entregado el producto y/o servicio no se aceptan cambios ni devoluciones."),0,'L',false);
            $this->fpdf->MultiCell(0,3,utf8_decode("*Los productos deberán ser recogidos dentro de las 24 horas como contrato pagará derecho de almacenamiento."),0,'L',false);
            $this->fpdf->MultiCell(0,3,utf8_decode("*La empresa no se responsabiliza por trabajos y/o productos que no han sido recogidos en la fecha pactada."),0,'L',false);
            

            // PIE DE PAGINA
            //$this->fpdf->Ln(60);
            $this->fpdf->text(23,137,'GRACIAS POR SU PREFERENCIA',0,1,'C');    
            $this->fpdf->Ln(1);    
            $this->fpdf->SetFont('Helvetica', '', 6);
            $this->fpdf->text(17,140,'Sistemas para empresas WebSoftAqp 927245347');
            
            
            //abrir
            $this->fpdf->Output('ORDEN-'.$datos[0]->codigo_orden.'.pdf','i');

            //descarga
            //$pdf->Output('ticket_cuenta.pdf', 'D');

            //guardar
            $this->fpdf->Output('ORDEN-'.$datos[0]->codigo_orden.'.pdf','f');
            
        }
    }


    public function ver_archivos_subidos($id_orden){
        $urls = DB::table('vw_orden')->where("id",$id_orden)->get();
        
        return response()->json($urls[0], 200);
    }

    public function print_hoja_produccion($id_orden = null)
    {
        $datos = DB::table('vw_orden')
                    ->where('id', $id_orden)
                    ->get();

        $map_material = [
            'ma36'         => 'A36',
            'ma201'        => '201',
            'ma304'        => '304',
            'ma430'        => '430',
            'maluminio'    => 'ALUM',
            'mgalvanizado' => 'GALV',
            'motros'       => 'OTROS',
        ];

        $material_raw = $datos[0]->chek_material ?? '';
        $material     = $map_material[$material_raw] ?? $material_raw;

        // Procesos desde la vista, ordenados por 'orden'
        $detalle = DB::table('vw_orden_detalle')
                    ->where('id_orden', $id_orden)
                    ->orderBy('id')
                    ->get();

        // Los dos primeros van siempre fijos (sin datos de BD)
        $proceso_data = [
            ['nombre' => 'ATENCIÓN AL CLIENTE', 'responsable' => $datos[0]->usuario, 'observaciones' => ''],
            ['nombre' => 'DISEÑO',              'responsable' => $datos[0]->usuario, 'observaciones' => ''],
        ];

        // El resto viene de vw_orden_detalle
        foreach ($detalle as $row) {
             $nombre_proceso = strtoupper($row->descripcion);

            // Si el proceso es de MATERIAL, indica quién lo proporciona
            if (str_contains($nombre_proceso, 'MATERIAL')) {
                $obs_proceso = ($datos[0]->material == 1)
                    ? 'PROPORCIONADO POR DC METALES'
                    : 'PROPORCIONADO POR EL CLIENTE';
            } else {
                $obs_proceso = $row->comentario ?? '';
            }

            $proceso_data[] = [
                'nombre'        => $nombre_proceso,
                'responsable'   => $row->operario ?? '',
                'fecha'         => $row->fch_hor_ejecucion ? Carbon::parse($row->fch_hor_ejecucion)->format('d/m/Y') : "__/__/2026",
                'observaciones' => $obs_proceso,
            ];
        }

        $pdf = PDF::loadView('admin.dompdf.hoja_produccion', [
            'codigo'                  => $datos[0]->codigo,
            'cliente'                 => $datos[0]->cliente,
            'telefono'                => $datos[0]->telefono,
            'detalle'                 => $datos[0]->des_gen,
            'fecha_ingreso'           => Carbon::parse($datos[0]->fch_create)->format('d/m/Y'),
            'fecha_entrega'           => Carbon::parse($datos[0]->fecha_entrega)->format('d/m/Y'),
            'hora_entrega'            => Carbon::parse($datos[0]->hora_entrega)->format('h:i A'),
            'material'                => $material,
            'cantidad'                => $datos[0]->cantidad      ?? '',
            'observaciones_generales' => $datos[0]->observaciones ?? '',
            'aprobado_cliente'        => $datos[0]->aprobado_cliente ?? false,
            'proceso_data'            => $proceso_data,   // ← array listo para el blade
        ]);

        $pdf->setPaper('A5', 'landscape');

        return $pdf->stream('Hoja_Produccion_' . $id_orden . '.pdf');
    }

    public function subir_archivo(Request $request){ // desde vista de operador
        $archivo = $request->file('archivo');

        $code = str_pad($request['id_orden'],7, "0", STR_PAD_LEFT);

        $orden=DB::table('vw_orden')->where('id',$request['id_orden'])->get();
        
        $cliente_name=DB::table('vw_clientes')->where('id',$orden[0]->id_cliente)->get();
        $cliente_name=str_replace(' ','',trim($cliente_name[0]->persona));
        
        $extension = $archivo->getClientOriginalExtension();
        $newfilename = str_replace(",","_",$cliente_name)."_".$code.'_'.$request['num'].".".$extension;
        
        $ruta_url = public_path('storage/'. $newfilename);
      
        if (\File::exists($ruta_url)) {
                    
            \File::delete($ruta_url);
        } else {
            
        }
        $archivo->move('storage',$newfilename);
         
        
        if($request['num']==1){
            $updateData=['ruta_url' => '/storage/'.$newfilename ?? null]; }
        if($request['num']==2){
            $updateData=['ruta_url2' => '/storage/'.$newfilename ?? null]; }
        if($request['num']==3){
            $updateData=['ruta_url3' => '/storage/'.$newfilename ?? null]; }
        if($request['num']==4){
            $updateData=['ruta_url4' => '/storage/'.$newfilename ?? null]; }
        if($request['num']==5){
            $updateData=['ruta_url5' => '/storage/'.$newfilename ?? null]; }
       
        DB::table('orden')->where('id', $request['id_orden'])->update($updateData);
        
        return response()->json(['msg' => 'si', 'ruta_url' => '/storage/'.$newfilename], 200);
    }
}


 // public function print_boleta($id_orden){     
    //     $datos = DB::table('vw_boleta')->where('id_orden', $id_orden)->orderBy('numeracion')->get();
        
    //     if(!isset($datos[0])){
    //         abort(404, 'Could not create office or assign it to administrator');
    //     }else{
        
    //         $this->fpdf->AddPage();        
    //         # Logo de la empresa formato png #
    //         $this->fpdf->Image('smartadmin/dist/img/DC_PNG.jpg',140,12,53,20,'JPG');

    //         # Encabezado y datos de la empresa #
    //         $this->fpdf->SetFont('Arial','B',18);
    //         $this->fpdf->SetTextColor(0,0,0);
    //         $this->fpdf->Cell(150,10,utf8_decode(strtoupper("PROFORMA N° ".$datos[0]->codigo_orden)),0,0,'L');

    //         $this->fpdf->Ln(9);

    //         $this->fpdf->SetFont('Arial','',10);
    //         $this->fpdf->SetTextColor(39,39,51);
    //         $this->fpdf->Cell(150,9,utf8_decode("RUC: 20455462844"),0,0,'L');

    //         $this->fpdf->Ln(5);
    //         $this->fpdf->Cell(150,9,utf8_decode("Dirección: Urb. San Lorenzo Av. Malecón Zolezzi 403-A Mro. Melgar - Arequipa"),0,0,'L');
    //         $this->fpdf->Ln(5);
    //         $this->fpdf->Cell(150,9,utf8_decode("Teléfono: 054 625743  Celular: 956778012 - 946036052"),0,0,'L');
    //         $this->fpdf->Ln(5);
    //         $this->fpdf->Cell(150,9,utf8_decode("Email: dhymarkcompanysac@gmail.com"),0,0,'L');

    //         $this->fpdf->Ln(10);
            
    //         $this->fpdf->Cell(125,0,'','T');
    //         $this->fpdf->Ln(1);

    //         $this->fpdf->SetFont('Arial','',10);
    //         $this->fpdf->Cell(59,7,utf8_decode("Procedencia: ".$datos[0]->procedencia),0,0); 
    //         $this->fpdf->Cell(50,7,utf8_decode("Fecha de emisión: ".date('d-m-Y H:i')),0,0,'L');  
            

    //         $this->fpdf->Ln(5);

    //         $this->fpdf->SetFont('Arial','',10);
    //         $this->fpdf->Cell(80,7,utf8_decode("Recepcionista: ".$datos[0]->name),0,0,'L'); 
        

    //         $this->fpdf->Ln(8);
            
    //         $this->fpdf->Cell(125,0,'','T');
    //         $this->fpdf->Ln(1);


    //         $this->fpdf->SetFont('Arial','',10);
    //         $this->fpdf->Cell(90,7,utf8_decode("Cliente: ".$datos[0]->cliente."                                           Ruc:".$datos[0]->ruc),0,0);
            
    //         $this->fpdf->Ln(5);
    //         $this->fpdf->Cell(75,7,utf8_decode("Email: ".$datos[0]->email),0,0);        
    //         $this->fpdf->Cell(60,7,utf8_decode("Teléfono: ".$datos[0]->telefono),0,0);
    //         $this->fpdf->Ln(5);
    //         $this->fpdf->Cell(90,7,utf8_decode("Dirección: ".$datos[0]->direccion),0,0);
    //         $this->fpdf->Ln(5);
    //         $this->fpdf->Cell(120,7,utf8_decode("Fecha y Hora de entrega: ".date('d-m-Y', strtotime($datos[0]->fecha_entrega))." ".$datos[0]->hora_entrega),0,0);
            
    //         $this->fpdf->Ln(9);
        

    //         # Tabla de productos #
    //         $this->fpdf->SetFont('Arial','B',10);
    //         $this->fpdf->SetFillColor(178,178,178);
    //         $this->fpdf->SetDrawColor(0,0,0);//23,83,201
    //         // $this->fpdf->SetTextColor(255,255,255);
    //         $this->fpdf->Cell(109,8,utf8_decode("Descripción"),1,0,'C',true);
    //         $this->fpdf->Cell(15,8,utf8_decode("Cant."),1,0,'C',true);
    //         $this->fpdf->Cell(25,8,utf8_decode("Precio"),1,0,'C',true);        
    //         $this->fpdf->Cell(32,8,utf8_decode("Subtotal"),1,0,'C',true);

    //         $this->fpdf->Ln(8);

    //         $this->fpdf->SetFont('Arial','',9);
    //         $this->fpdf->SetTextColor(39,39,51);


    //         /*----------  Detalles de la tabla  ----------*/
    //         $to=0;
            
    //         foreach($datos as $data){
    //             // $this->fpdf->Cell(109,7,utf8_decode($data->descripcion),'L',0,'L');
    //             // $this->fpdf->Cell(15,7,utf8_decode($data->cant),'L',0,'C');
    //             // $this->fpdf->Cell(25,7,utf8_decode(number_format(round($data->precio_unid,2), 2, ',', ' ')),'L',0,'C');    
    //             $y= $data->precio_unid*$data->cant;    
    //             // $this->fpdf->Cell(32,7,utf8_decode(number_format(round($data->precio_unid*$data->cant,2), 2, ',', ' ')),'LR',0,'C');
    //             // $this->fpdf->Ln(7);
    //             $to += $y;
    //         }
    //         $st = $to / 1.18;
    //         $igv = $st * 0.18;
    //         /*----------  Fin Detalles de la tabla  ----------*/

    //         /** A CUENTA SALDO Y TOTAL */
    //         $monto_pagado = DB::table('movimientos')->where('id_orden', $id_orden)->sum('monto');
    //         $precio_total = DB::table('orden_detalle')->where('id_orden', $id_orden)->sum('pre_tot');
    //         $saldo = number_format((float)$precio_total-$monto_pagado, 2, '.', '');

    //         $this->fpdf->Cell(109,7,utf8_decode($datos[0]->des_gen),'LB',0,'L');
    //         $this->fpdf->Cell(15,7,"",'LB',0,'C');
    //         $this->fpdf->Cell(25,7,"",'LB',0,'C');
    //         $this->fpdf->Cell(32,7,utf8_decode(number_format(round($precio_total,2), 2, ',', ' ')),'LRB',0,'C');
    //         $this->fpdf->Ln(7);


    //         $this->fpdf->SetY(245);
    //         $this->fpdf->SetFont('Arial','B',9);
    //         if($datos[0]->igv==0){
    //             $this->fpdf->Cell(109,7,utf8_decode("Incl. IGV: NO"),'',0,'L');
    //         }else{
    //             $this->fpdf->Cell(109,7,utf8_decode("Incl. IGV: SI"),'',0,'L');
    //         }
            
    //         $this->fpdf->Cell(38,7,utf8_decode("A CUENTA: "),'',0,'R');
    //         $this->fpdf->Cell(34,7,utf8_decode("S/. ".$monto_pagado),'',0,'C');
    //         $this->fpdf->Ln(5);
    //         if($datos[0]->material==0){
    //             $this->fpdf->Cell(109,7,utf8_decode("Incl. Material: NO"),'',0,'L');
    //         }else{
    //             $this->fpdf->Cell(109,7,utf8_decode("Incl. Material: SI"),'',0,'L');
    //         }         
            
    //         $this->fpdf->Cell(38,7,utf8_decode("SALDO: "),'',0,'R');
    //         $this->fpdf->Cell(34,7,utf8_decode("S/. ".$saldo),'',0,'C');
    //         $this->fpdf->Ln(5);

    //         if($datos[0]->plano==0){
    //             $this->fpdf->Cell(109,7,utf8_decode("Incl. Plano: NO"),'',0,'L');
    //         }else{
    //             $this->fpdf->Cell(109,7,utf8_decode("Incl. Plano: SI"),'',0,'L');
    //         }  
            

    //         $this->fpdf->Cell(38,7,utf8_decode("TOTAL: "),'',0,'R');
    //         $this->fpdf->Cell(34,7,utf8_decode("S/. ".$precio_total),'',0,'C');

       
    //         $this->fpdf->Ln(5);

    //         $this->fpdf->SetFont('Arial','',6);

    //         $this->fpdf->SetTextColor(39,39,51);
    //         $this->fpdf->Ln(3);
    //         $this->fpdf->MultiCell(0,4,utf8_decode("NOTA: "),0,'L',false);
    //         $this->fpdf->MultiCell(0,3,utf8_decode("** Una vez entregado el producto y/o servicio no se aceptan cambios ni devoluciones. **"),0,'L',false);
    //         $this->fpdf->MultiCell(0,3,utf8_decode("** Los productos deberán ser recogidos dentro de las 24 horas como contrato pagará derecho de almacenamiento. **"),0,'L',false);
    //         $this->fpdf->MultiCell(0,3,utf8_decode("** La empresa no se responsabiliza por trabajos y/o productos que no han sido recogidos en la fecha pactada. **"),0,'L',false);
            


    //         # Nombre del archivo PDF #
    //         $this->fpdf->Output("I","Proforma_".$datos[0]->cliente."_".$datos[0]->codigo_orden.".pdf",true);
    //     }
        
    // }

