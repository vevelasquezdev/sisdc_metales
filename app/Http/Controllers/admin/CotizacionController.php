<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Codedge\Fpdf\Fpdf\Fpdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use SebastianBergmann\CodeCoverage\Report\Xml\Totals;

class CotizacionController extends Controller
{
    protected $fpdf;
    protected $B = 0;
    protected $I = 0;
    protected $U = 0;
    protected $HREF = '';
    public function __construct()  {      
        $this->fpdf = new Fpdf('P','mm',array(203,297));       
    }
    
    public function index() {
        $servicios = DB::table('servicios')->get();
        $formapagos = DB::table('forma_pago')->orderBy("id")->get();
        $materiales = DB::table('conf_material')->orderBy("id")->get();
         
        return view('admin.cotizacion',compact('servicios','formapagos','materiales'));
    }

    public function getCotizacion(Request $request){
        $data = DB::table('vw_cotizacion')->orderBy('id', 'desc');
        
        if ($request->filled('from_date') && $request->filled('to_date')) {

            $from = date('Y-m-d', strtotime($request->from_date));
            $to   = date('Y-m-d', strtotime($request->to_date));

            $data = $data->whereRaw(
                "TO_DATE(fch_create, 'DD-MM-YYYY') BETWEEN ? AND ?",
                [$from, $to]
            );
        }

        $data = $data->get();

        return Datatables::of($data)
            ->addIndexColumn()            
            ->addColumn('action', function($row){
                $buttons = '<div style="display:flex; gap:4px; justify-content:center; align-items:center;">
                        <button class="btn btn-primary btn-sm" onclick="AbrirOrdenServicio('.$row->id.','.$row->id_user.',&quot;'.$row->rol.'&quot;,'.$row->id_cliente.')" data-toggle="modal" data-target=".default-example-modal-right" title="Abrir Orden de Servicio"><i class="fa fa-clipboard"></i></button>
                        <button class="btn btn-info btn-sm" type="button" onclick="print_boleta('.$row->id.')" data-toggle="tooltip" title="Imprimir Cotización"><i class="fal fa-print"></i></button>';

                    if($row->ruta_url || $row->ruta_url2 || $row->ruta_url3 || $row->ruta_url4 || $row->ruta_url5)
                        $buttons .= '<button class="btn btn-danger btn-sm" onclick="ver_archivos_cotizacion('.$row->id.')" data-toggle="modal" data-target=".default-example-modal-right" title="Ver archivos"><i class="fal fa-file-image"></i></button>';

                    return $buttons . '</div>'; 
            })
            ->addColumn('fecha_entrega', function($row){
                if($row->fecha_entrega){
                    return (new \DateTime($row->fecha_entrega))->format("d-m-Y");
                }
            })
            ->addColumn('fch_create', function($row){ 
                if($row->fch_create){
                    return (new \DateTime($row->fch_create))->format("d-m-Y");
                }
            })
            ->addColumn('estado', function($row){
                if($row->id_estado == 0){
                    return "<span class='badge badge-secondary badge-pill' onclick='cambiar_estado(".$row->id.")'style='cursor:pointer;'>
                                PENDIENTE
                            </span>";
                }elseif($row->id_estado == 1){
                    return "<span class='badge badge-warning badge-pill' onclick='cambiar_estado(".$row->id.")'style='cursor:pointer;'>
                                SEGUIMIENTO
                            </span>";                 
                }elseif($row->id_estado == 2){
                    return "<span class='badge badge-info badge-pill' onclick='cambiar_estado(".$row->id.")' style='cursor:pointer;'>
                                POR CONFIRMAR
                            </span> ";
                }elseif($row->id_estado == 3){
                    return "<span class='badge badge-success badge-pill' onclick='cambiar_estado(".$row->id.")' style='cursor:pointer;'>
                                VENTA CERRADA
                            </span>";
                }else{
                    return "<span class='badge badge-danger badge-pill' onclick='cambiar_estado(".$row->id.")' style='cursor:pointer;'>
                                PERDIDA
                            </span> ";
                }
            })
            ->rawColumns(['action', 'fecha_entrega', 'fch_create', 'estado'])
            ->make(true);        
    }

   
    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        $code = str_pad($request['id_orden'],7, "0", STR_PAD_LEFT);

        $validate_file=$request->hasFile('file');

        $cliente_name=DB::table('vw_clientes')->where('id',$request['id_cliente'])->get();
        $cliente_name=str_replace(' ','',$cliente_name[0]->persona);

        $descripciones = json_decode($request->descripcion, true);
        $cantidades = json_decode($request->cantidad, true);
        $precios = json_decode($request->precio_unitario, true);
        $subtotales = json_decode($request->subtotal, true);

        if ($validate_file==true) { 
           
            if($validate_file==true){
                $file = $request->file('file');          
                $extension = $file[0]->getClientOriginalExtension();
                $newfilename = str_replace(",","_",$cliente_name)."_".$code.'_1.'.$extension;
                
                $currentURL = '/storage/cotizacion/'.$newfilename;
                if($extension=='pdf' || $extension=='jpg' || $extension=='jpeg' || $extension=='dwg' || $extension=='png'){                
                    $file[0]->move('storage/cotizacion/',$newfilename);               
                }else{
                    return response()->json(['msg'=>'Solo archivos pdf, jpg, jpeg, dwg, png'], 500);
                }
            }
          

            $check_no = DB::table('cotizacion_detalle')
                ->where('id_orden', $request['id_orden'])
                ->where('orden', '=','NO')            
                ->count();

            if($check_no>0){
                
                $up_orden_det = DB::table('cotizacion_detalle')
                    ->where('id_orden', $request['id_orden'])
                    ->where('orden', '=','NO')
                    ->update(['orden' => 'SI']);

                $updateData = [
                    'procedencia'     => $request['procedencia'],
                    'fch_create'      => $request['fch_create'],
                    'fecha_entrega'   => $request['fecha_entrega'],
                    'tiempo_entrega'  => Carbon::now()->diffInDays( Carbon::parse($request->fecha_entrega) ) . ' días',
                    'hora_entrega'    => $request['hora_entrega'],
                    'id_cliente'      => $request['id_cliente'],
                    'igv'             => $request['igv'],
                    'material'        => $request['material'],
                    'plano'           => $request['plano'],
                    'des_gen'         => $request['des_gen'],  
                    'observacion'     => $request['observacion'], 
                    'chk_usd'         => $request['chk_usd'],
                    'descripcion_1' => $descripciones[0] ?? null,
                    'descripcion_2' => $descripciones[1] ?? null,
                    'descripcion_3' => $descripciones[2] ?? null,

                    'cantidad_1' => $cantidades[0] ?? null,
                    'cantidad_2' => $cantidades[1] ?? null,
                    'cantidad_3' => $cantidades[2] ?? null,

                    'pre_unitario_1' => $precios[0] ?? null,
                    'pre_unitario_2' => $precios[1] ?? null,
                    'pre_unitario_3' => $precios[2] ?? null,

                    'subtot_1' => $subtotales[0] ?? null,
                    'subtot_2' => $subtotales[1] ?? null,
                    'subtot_3' => $subtotales[2] ?? null,                                         
                ];
                
                if($validate_file==true){
                    $updateData+=['ruta_url' => $currentURL ?? null];}
                
                $up_orden = DB::table('cotizacion')->where('id', $request['id_orden'])->update($updateData);
           

                if($up_orden){                    
                    return response()->json(['msg'=>'Orden de servicio actualizada.','id_orden' =>$request['id_orden']], 200);
                }else{
                    return response()->json(['msg'=>'Error de base de datos...'], 200);
                }
            }else{
                $updateData = [
                    'procedencia'     => $request['procedencia'],
                    'fch_create'      => $request['fch_create'],
                    'fecha_entrega'   => $request['fecha_entrega'],
                    'tiempo_entrega'     => Carbon::now()->diffInDays( Carbon::parse($request->fecha_entrega) ) . ' días',
                    'hora_entrega'    => $request['hora_entrega'],
                    'id_cliente'      => $request['id_cliente'],
                    'igv'             => $request['igv'],
                    'material'        => $request['material'],
                    'plano'           => $request['plano'],
                    'des_gen'         => $request['des_gen'], 
                    'observacion'     => $request['observacion'],  
                    'chk_usd'         => $request['chk_usd'],
                    'descripcion_1' => $descripciones[0] ?? null,
                    'descripcion_2' => $descripciones[1] ?? null,
                    'descripcion_3' => $descripciones[2] ?? null,

                    'cantidad_1' => $cantidades[0] ?? null,
                    'cantidad_2' => $cantidades[1] ?? null,
                    'cantidad_3' => $cantidades[2] ?? null,

                    'pre_unitario_1' => $precios[0] ?? null,
                    'pre_unitario_2' => $precios[1] ?? null,
                    'pre_unitario_3' => $precios[2] ?? null,

                    'subtot_1' => $subtotales[0] ?? null,
                    'subtot_2' => $subtotales[1] ?? null,
                    'subtot_3' => $subtotales[2] ?? null,                                       
                ];
              
                if($validate_file==true){
                    $updateData+=['ruta_url' => $currentURL ?? null];}
            
                $up_orden = DB::table('cotizacion')->where('id', $request['id_orden'])->update($updateData);
                
                return response()->json(['msg'=>0], 200);
            }
        }else{
  
            $check_no = DB::table('cotizacion_detalle')
                ->where('id_orden', $request['id_orden'])
                ->where('orden', '=','NO')            
                ->count();

            if($check_no>0){
         
                $up_orden_det = DB::table('cotizacion_detalle')
                    ->where('id_orden', $request['id_orden'])
                    ->where('orden', '=','NO')
                    ->update(['orden' => 'SI']);

                $up_orden = DB::table('cotizacion')
                    ->where('id', $request['id_orden'])                
                    ->update([                    
                        'procedencia'     => $request['procedencia'],
                        'fch_create'      => $request['fch_create'],
                        'fecha_entrega'   => $request['fecha_entrega'],
                        'tiempo_entrega'     => Carbon::now()->diffInDays( Carbon::parse($request->fecha_entrega) ) . ' días',
                        'hora_entrega'    => $request['hora_entrega'],
                        'id_cliente'      => $request['id_cliente'],
                        'igv'             => $request['igv'],
                        'material'        => $request['material'],
                        'plano'           => $request['plano'],
                        'chk_usd'         => $request['chk_usd'],
                        'des_gen'         => $request['des_gen'],
                        'observacion'     => $request['observacion'],
                        'descripcion_1' => $descripciones[0] ?? null,
                        'descripcion_2' => $descripciones[1] ?? null,
                        'descripcion_3' => $descripciones[2] ?? null,

                        'cantidad_1' => $cantidades[0] ?? null,
                        'cantidad_2' => $cantidades[1] ?? null,
                        'cantidad_3' => $cantidades[2] ?? null,

                        'pre_unitario_1' => $precios[0] ?? null,
                        'pre_unitario_2' => $precios[1] ?? null,
                        'pre_unitario_3' => $precios[2] ?? null,

                        'subtot_1' => $subtotales[0] ?? null,
                        'subtot_2' => $subtotales[1] ?? null,
                        'subtot_3' => $subtotales[2] ?? null,         
                    ]);
              
               
                if($up_orden_det){                    
                    return response()->json(['msg'=>'Orden de servicio actualizada.','id_orden' =>$request['id_orden']], 200);
                }else{
                    return response()->json(['msg'=>'Error de base de datos...'], 200);
                }
            }else{
               
                $up_orden = DB::table('cotizacion')
                    ->where('id', $request['id_orden'])                
                    ->update([                    
                        'procedencia'     => $request['procedencia'],
                        'fch_create'      => $request['fch_create'],
                        'fecha_entrega'   => $request['fecha_entrega'],
                        'tiempo_entrega'     => Carbon::now()->diffInDays( Carbon::parse($request->fecha_entrega) ) . ' días',
                        'hora_entrega'    => $request['hora_entrega'],
                        'id_cliente'      => $request['id_cliente'],
                        'igv'             => $request['igv'],
                        'material'        => $request['material'],
                        'plano'           => $request['plano'],
                        'chk_usd'         => $request['chk_usd'],
                        'des_gen'         => $request['des_gen'],
                        'observacion'     => $request['observacion'],
                        'descripcion_1' => $descripciones[0] ?? null,
                        'descripcion_2' => $descripciones[1] ?? null,
                        'descripcion_3' => $descripciones[2] ?? null,

                        'cantidad_1' => $cantidades[0] ?? null,
                        'cantidad_2' => $cantidades[1] ?? null,
                        'cantidad_3' => $cantidades[2] ?? null,

                        'pre_unitario_1' => $precios[0] ?? null,
                        'pre_unitario_2' => $precios[1] ?? null,
                        'pre_unitario_3' => $precios[2] ?? null,

                        'subtot_1' => $subtotales[0] ?? null,
                        'subtot_2' => $subtotales[1] ?? null,
                        'subtot_3' => $subtotales[2] ?? null,                         
                    ]);
                
                return response()->json(['msg'=>0], 200);
            }
        }

    }

   
    public function show($id = null)
    {
        $datos = DB::table('vw_cotizacion')->where('id',$id)->get();
      
        if($datos){
            return response()->json($datos[0],200);
        }else{
            return response()->json(['msg'=>'Error al obtener datos'], 500);
        }
    }

   
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }

    
    public function destroy($id)
    {
        //
    }

    public function ver_archivos_cotizacion($id_orden=null){
        $urls = DB::table('vw_cotizacion')->where("id",$id_orden)->get();
        
        return response()->json($urls[0], 200);
    }

    public function get_tipo_cambio(){
        $response = Http::withHeaders([
            'Accept'                 => 'application/json',
            'Content-Type'          => 'application/json',
            'Content-Transfer-Encoding' => 'application/json',
        ])->post('https://api.migo.pe/api/v1/exchange/latest', [
            'token' => 'c0BKCZ8EM3zXPleF3nL0PwKqYw3NP2ymg7ozqLe0PR8nmTjZHimOq15s0T1v',
        ]);

        if (!$response->successful()) {
            return response()->json(['error' => 'No se pudo obtener el tipo de cambio.'], 500);
        }

        return response()->json($response->json());
    }

    public function print_cotizacion($id_orden = null){

        $datos = DB::table('vw_cotizacion')
                ->where('id', $id_orden)                
                ->get();
       
        $total = DB::table('vw_boleta_cotizacion')
            ->where('id_orden', $id_orden)
            ->selectRaw('COALESCE(SUM(cant * precio_unid),0) as total')
            ->value('total');
            
       
        $subtotal = DB::table('vw_boleta_cotizacion')
            ->where('id_orden', $id_orden)
            ->selectRaw('COALESCE(SUM(cant * precio_unid),0) as total')
            ->value('total');

        $total_servicios = DB::table('vw_boleta_cotizacion')
            ->where('id_orden', $id_orden)
            ->whereRaw("UPPER(descripcion) NOT LIKE '%MATERIAL%'")
            ->selectRaw('COALESCE(SUM(cant * precio_unid),0) as total')
            ->value('total');

        if($datos[0]->igv==1){
            $igv = $total_servicios * 0.18;
            $total = $subtotal + $igv;
        }else{
            $igv = 0;
            $total = $subtotal + $igv;
        }         
      

        // ── Conversión USD ───────────────────────────────────────────
        $simbolo = 'S/';
        if($datos[0]->chk_usd == 1){
            $simbolo     = '$';
        }
        // ────────────────────────────────────────────────────────────
        
        $hayTotal = $total > 0;

        $pdf = PDF::loadView('admin.dompdf.cotizacion', [
            'codigo'             => $datos[0]->codigo,
            'empresa'            => $datos[0]->raz_soc,
            'ruc'                => $datos[0]->ruc ?? '',
            'contacto'           => $datos[0]->cliente ?? '',
            'telefono_cliente'   => $datos[0]->telefono,
            'proyecto'           => $datos[0]->des_gen,
            'asesor_nombre'      => $datos[0]->usuario,
            'asesor_email'       => $datos[0]->user_email,
            'asesor_telefono'    => $datos[0]->user_telefono ?? '',
            'tiempo_entrega'     => $datos[0]->tiempo_entrega ?? '',

            'item1_descripcion'  => $datos[0]->descripcion_1 ?? '',
            'item1_cantidad'     => $datos[0]->cantidad_1 ?? null,
            'item1_precio'       => $datos[0]->pre_unitario_1 ?? null,

            'item2_descripcion'  => $datos[0]->descripcion_2 ?? '',
            'item2_cantidad'     => $datos[0]->cantidad_2 ?? null,
            'item2_precio'       => $datos[0]->pre_unitario_2 ?? null,

            'item3_descripcion'  => $datos[0]->descripcion_3 ?? '',
            'item3_cantidad'     => $datos[0]->cantidad_3 ?? null,
            'item3_precio'       => $datos[0]->pre_unitario_3 ?? null,

            'imagen_referencial' => $datos[0]->ruta_url ?? null,
            'observaciones'      => $datos[0]->observacion ?? null,

            // TOTALES
            'subtotal'           => $subtotal,
            'igv'                => $igv,
            'total'              => $total,
            'hayTotal'           => $hayTotal,
            'simbolo'            => $simbolo,
        ]);
        

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Cotizacion_' . $datos[0]->codigo . '.pdf');
    }
    

  

    public function print_cotizacion_pequeno($id_orden = null){
        $datos = DB::table('vw_boleta_cotizacion')->where('id_orden', $id_orden)->orderBy('numeracion')->get();
     
        if(!isset($datos[0])){
            abort(404, 'Could not create office or assign it to administrator');
        }else{
            $this->fpdf = new Fpdf('P','mm',array(80,150));            
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
            $this->fpdf->Cell(60,4, utf8_decode('Cotización N°- ').$datos[0]->codigo_orden,0,1,'');        
            $this->fpdf->Cell(60,4,'Fecha Imp.: ' .date('d-m-Y'). '          Hora Imp.:  ' .date('H:i'),0,1,'');
            $this->fpdf->Cell(60,4,'Recepcionista:  ' .$datos[0]->name,0,1,'L');
            $this->fpdf->Cell(60,4,'Ruc:  ' .$datos[0]->ruc,0,0,'L');
            // COLUMNAS
            
            $this->fpdf->Ln(5);
            $this->fpdf->Cell(60,0,'','T');
            $this->fpdf->Ln(0);

            # Tabla de productos #
            $this->fpdf->SetFont('Arial','',7);
            $this->fpdf->SetFillColor(0,0,0);
            $this->fpdf->SetDrawColor(0,0,0);//23,83,201
            $this->fpdf->SetTextColor(255,255,255);
            $this->fpdf->Cell(34,4,utf8_decode("Descripción"),1,0,'C',true);
            $this->fpdf->Cell(8,4,utf8_decode("Cant."),1,0,'C',true);
            $this->fpdf->Cell(10,4,utf8_decode("Precio"),1,0,'C',true);        
            $this->fpdf->Cell(10,4,utf8_decode("Subtotal"),1,0,'C',true);

            $this->fpdf->Ln(4);

            
            $this->fpdf->SetTextColor(39,39,51);
            
            $this->fpdf->SetFont('Arial', '', 6);        
      
            /*----------  Detalles de la tabla  ----------*/
            $to=0;
            foreach($datos as $data){               
                $y= $data->precio_unid*$data->cant;
                $to += $y;
            }
            $st = $to / 1.18;
            $igv = $st * 0.18;
            /*----------  Fin Detalles de la tabla  ----------*/

            /** A CUENTA SALDO Y TOTAL */
            $monto_pagado = DB::table('movimientos')->where('id_orden', $id_orden)->sum('monto');
            $precio_total = DB::table('cotizacion_detalle')->where('id_orden', $id_orden)->sum('pre_tot');
            $saldo = number_format((float)$precio_total-$monto_pagado, 2, '.', '');

            $this->fpdf->Cell(35,4,$datos[0]->des_gen,'L',0,'L');
            $this->fpdf->Cell(7,4,"",'L',0,'C');
            $this->fpdf->Cell(10,4,"",'L',0,'C'); 
            $this->fpdf->Cell(10,4,utf8_decode(number_format(round($precio_total,2), 2, ',', ' ')),'LR',0,'C');
            $this->fpdf->Ln(4);
            
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
            
            
            // Establecer texto de marca de agua
            $this->fpdf->SetFont('Arial', 'B', 50);
            $texto_marca_agua = 'COTIZACIÓN';

            // Obtener ancho y alto de la página
            $ancho_pagina = $this->fpdf->GetPageWidth();
            $alto_pagina = $this->fpdf->GetPageHeight();

            // Establecer posición x e y para la marca de agua
            $posicion_x = ($ancho_pagina - $this->fpdf->GetStringWidth($texto_marca_agua)+50) / 2;
            $posicion_y = ($alto_pagina + $this->fpdf->GetStringWidth($texto_marca_agua)) / 2;

            // Agregar marca de agua
            $this->fpdf->SetTextColor(128, 128, 128); // Color gris claro
            $this->fpdf->SetFont('Arial', '', 30, '', true); // Establecer fuente en diagonal
            $this->fpdf->Text($posicion_x, $posicion_y, utf8_decode($texto_marca_agua));
           
            # Nombre del archivo PDF #
            $this->fpdf->Output("I","Cotizacion_".$datos[0]->cliente."_".$datos[0]->codigo_orden.".pdf",true);
            
        }
    }

    public function cambiar_estado(Request $request){
        
        DB::table('cotizacion')
            ->where('id', $request->id)
            ->update(['estado' => $request->estado]);

        return response()->json([ 'success' => true]);
    }


}
