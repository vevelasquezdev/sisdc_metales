<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Serv_Operador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class ListaTrabajoController extends Controller
{
    
    public function index(){
        $operadores=DB::table('users')->where('rol','OPERARIO')->where('id','!=',Auth::user()->id)->get();       
        return view('admin.lista-trabajo', compact('operadores'));
    }

    public function getTrabajos(){

        if(Auth::user()->rol=="ADMINISTRADOR"){
            $data = DB::table('vw_lista_trabajo')->orderBy('id_orden', 'desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){ 
                })
                ->addColumn('codigo_orden', function($row){ 
                    $des_gen=DB::table('vw_orden')->where('id',$row->id_orden)->get();
                    return $row->codigo_orden." - (".$des_gen[0]->cliente.") - ".$this->limitar_cadena($des_gen[0]->des_gen,60,'...');
                })
                ->addColumn('estado_trab', function($row){
                    if($row->id_estado_trab==0){
                        return "<span class='badge badge-danger badge-pill'>".$row->estado_trab."</span>";
                    }elseif($row->id_estado_trab==1){
                        return "<span class='badge badge-info badge-pill'>".$row->estado_trab."</span>";
                    }else{
                        return "<span class='badge badge-success badge-pill'>".$row->estado_trab."</span>";
                    }                
                })
                ->addColumn('fch_entrega', function($row){                    
                    $f_entrega=DB::table('vw_orden')->where('id',$row->id_orden)->get();               
                    return (new \DateTime($f_entrega[0]->fecha_entrega))->format("d-m-Y") . PHP_EOL." ".$f_entrega[0]->hora_entrega;
                })
                ->addColumn('fch_ent', function($row){  
                    if($row->fch_ent){                  
                        return (new \DateTime($row->fch_ent))->format("d-m-Y") . PHP_EOL." ".$row->hora_ent;   
                    }                   
                })
                ->addColumn('fch_hora_ejec', function($row){
                    if($row->fch_hora_ejec){
                        return (new \DateTime($row->fch_hora_ejec))->format("d-m-Y G:i") . PHP_EOL;  
                    }                   
                })
                ->addColumn('ruta_url', function($row){ 
                    if($row->ruta_url!="" || $row->ruta_url2!="" || $row->ruta_url3!="" || $row->ruta_url4!="" || $row->ruta_url5!=""){
                        return "<button onclick='open_modal_ver_archivos(".$row->id_orden.")' class='btn btn-danger btn-sm' data-toggle='modal' 
                                        data-target='.default-example-modal-right' title='Ver archivos'><i class='fal fa-file-image'></i>
                                    </button>";
                    }       
                })  
                ->addColumn('comentario_operador', function($row){                    
                    return $row->coment_trabajo;                     
                })            
                ->rawColumns(['action','fch_ent','estado_trab','ruta_url','fch_hora_ejec','codigo_orden','comentario_operador'])              
                ->make(true);
        }else{
            $data = DB::table('vw_lista_trabajo')->where('id_operador',Auth::user()->id)->where('id_estado_trab','<>',2)->orderBy('id')->orderBy('fch_ent')->orderBy('hora_ent')->get();
    
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        // $total = DB::table('vw_orden_detalle')->where('id_orden', $row->id_orden)->count();
                        $servicio = DB::table('vw_orden_detalle')->where('id_orden', $row->id_orden)->where('numeracion', $row->numeracion)->get('descripcion');                        
                     
                        $butons = '';
                
                        // Verificar estado de trabajo
                        if ($row->id_estado_trab == 0) { // Trabajo en espera
                            return "<button onclick='InicioTrabajo({$row->id_orden_det})' class='btn btn-success btn-sm' title='Inicio de trabajo'>
                                        <i class='fas fa-check'></i>
                                    </button>";
                        } else { // Trabajo aceptado
                            if (!empty($servicio) && isset($servicio[0])) {
                                $descripcion = $servicio[0]->descripcion;
                            } else {
                                $descripcion = 'Sin descripción';
                            }
                
                            if ($row->ini_pausa == 0 && $row->llave == 1) {
                                $butons = "<button onclick='enviarSgteServicio({$row->id}, {$row->id_orden_det}, {$row->id_orden}, {$row->numeracion}, \"$descripcion\", {$row->llave}, {$row->grupal})' 
                                            class='btn btn-primary btn-sm' title='Terminar este trabajo'>
                                            <i class='fas fa-thumbs-up'></i>
                                        </button>
                                        |
                                        <button onclick='open_modal({$row->id_orden_det})' class='btn btn-warning btn-sm' title='Agregar colaborador'>
                                            <i class='fas fa-user-plus'></i>
                                        </button>
                                        |
                                        <button onclick='add_coment({$row->id})' class='btn btn-info btn-sm' title='Comentario'>
                                            <i class='fa fa-comment' aria-hidden='true'></i>
                                        </button>
                                        |
                                        <button onclick='pausa_tiempo({$row->id})' class='btn btn-danger btn-sm' title='Pausar Trabajo'>
                                            <i class='fas fa-pause-circle' aria-hidden='true'></i>
                                        </button>";
                            } elseif ($row->ini_pausa == 1 && $row->llave == 1) {
                                $butons = "<button onclick='enviarSgteServicio({$row->id}, {$row->id_orden_det}, {$row->id_orden}, {$row->numeracion}, \"$descripcion\", {$row->llave}, {$row->grupal})' 
                                            class='btn btn-primary btn-sm' title='Terminar este trabajo'>
                                            <i class='fas fa-thumbs-up'></i>
                                        </button>
                                        |
                                        <button onclick='open_modal({$row->id_orden_det})' class='btn btn-warning btn-sm' title='Agregar colaborador'>
                                            <i class='fas fa-user-plus'></i>
                                        </button>
                                        |
                                        <button onclick='add_coment({$row->id})' class='btn btn-info btn-sm' title='Comentario'>
                                            <i class='fa fa-comment' aria-hidden='true'></i>
                                        </button>
                                        |
                                        <button onclick='reanudar_tiempo({$row->id})' class='btn btn-danger btn-sm' title='Reanudar Trabajo'>
                                            <i class='fas fa-play-circle' aria-hidden='true'></i>
                                        </button>";
                            }
                
                            if ($row->id_servicio == 17) { // ID DISEÑO
                                $butons .= "|<button class='btn' onclick='subir_archivo({$row->id_orden}, {$row->id_orden_det})' style='padding: 0px;' title='Subir Archivos'>
                                                <img src='images/autocad_logo.jpg' alt='Imagen del botón' width='32' height='30'>
                                            </button>";
                            }
                
                            return $butons;
                        }
                    })
                    // ->addColumn('action', function($row){  
                        
                    //     $total = DB::table('vw_orden_detalle')->where('id_orden',$row->id_orden)->count();
                    //     $servicio = DB::table('vw_orden_detalle')->where('id_orden',$row->id_orden)->where('numeracion',$row->numeracion)->get('descripcion');
                        
                        
                    //     if($row->id_estado_trab==0){ //aceptar trabajo
                    //         return "<button onclick='InicioTrabajo(".$row->id_orden_det.")' class='btn btn-success btn-sm' 
                    //                     title='Inicio de trabajo'><i class='fas fa-check'></i>
                    //                 </button>";                           
                    //     }else{ // trabajo aceptado - botones sgte y colaborador habilidatos
                    //         if($row->ini_pausa==0 && $row->llave==1){
                    //             $butons= "<button onclick='enviarSgteServicio(".$row->id.",".$row->id_orden_det.",".$row->id_orden.",".$row->numeracion.",\"".$servicio[0]->descripcion."\",".$row->llave.",".$row->grupal.")' class='btn btn-primary btn-sm' 
                    //                         title='Terminar este trabajo'><i class='fas fa-thumbs-up'></i>
                    //                     </button>|
                    //                 <button onclick='open_modal(".$row->id_orden_det.")' class='btn btn-warning btn-sm' 
                    //                         title='Agregar colaborador'><i class='fas fa-user-plus'></i>
                    //                 </button>|
                    //                 <button onclick='add_coment(".$row->id.")' class='btn btn-info btn-sm' 
                    //                         title='Comentario'><i class='fa fa-comment' aria-hidden='true'></i>
                    //                 </button>|
                    //                 <button onclick='pausa_tiempo(".$row->id.")' class='btn btn-danger btn-sm' 
                    //                         title='Pausar Trabajo'><i class='fas fa-pause-circle' aria-hidden='true'></i>
                    //                 </button>";
                                   
                    //         }elseif($row->ini_pausa==1 && $row->llave==1){
                    //             $butons= "<button onclick='enviarSgteServicio(".$row->id.",".$row->id_orden_det.",".$row->id_orden.",".$row->numeracion.",\"".$servicio[0]->descripcion."\",".$row->llave.",".$row->grupal.")' class='btn btn-primary btn-sm' 
                    //                     title='Terminar este trabajo'><i class='fas fa-thumbs-up'></i>
                    //                 </button>|
                    //                 <button onclick='open_modal(".$row->id_orden_det.")' class='btn btn-warning btn-sm' 
                    //                         title='Agregar colaborador'><i class='fas fa-user-plus'></i>
                    //                 </button>|
                    //                 <button onclick='add_coment(".$row->id.")' class='btn btn-info btn-sm' 
                    //                         title='Comentario'><i class='fa fa-comment' aria-hidden='true'></i>
                    //                 </button>|
                    //                 <button onclick='reanudar_tiempo(".$row->id.")' class='btn btn-danger btn-sm' 
                    //                         title='Reanudar Trabajo'><i class='fas fa-play-circle' aria-hidden='true'></i>
                    //                 </button>";
                    //         }
                    //         if($row->id_servicio==17){ // ID DISEÑO                              
                    //             $butons.="|<button class='btn' onclick='subir_archivo(".$row->id_orden.",".$row->id_orden_det.")' style='padding: 0px 0px 0px 0px;' title='Subir Archivos'>
                    //                                 <img src='images/autocad_logo.jpg' alt='Imagen del botón' width='32' height='30'>
                    //                         </button>";
                    //         }
                    //         return $butons;  
                    //     }
                    // })
                    ->addColumn('codigo_orden', function($row){ 
                        $des_gen=DB::table('vw_orden')->where('id',$row->id_orden)->get();
                        return $row->codigo_orden." - (".$des_gen[0]->cliente.") - ".$this->limitar_cadena($des_gen[0]->des_gen,60,'...');
                    })
                    ->addColumn('estado_trab', function($row){
                        if($row->id_estado_trab==0){
                            return "<span class='badge badge-danger badge-pill'>".$row->estado_trab."</span>";
                        }elseif($row->id_estado_trab==1){
                            return "<span class='badge badge-info badge-pill'>".$row->estado_trab."</span>";
                        }else{
                            return "<span class='badge badge-success badge-pill'>".$row->estado_trab."</span>";
                        }                
                    })
                    ->addColumn('comentario_operador', function($row){                    
                        return $row->coment_trabajo;                     
                    }) 
                    ->addColumn('fch_entrega', function($row){ 
                        return (new \DateTime($row->fecha_entrega))->format("d-m-Y") . PHP_EOL." ".$row->hora_entrega;                   
                        //$f_entrega=DB::table('vw_orden')->where('id',$row->id_orden)->get();               
                        //return (new \DateTime($f_entrega[0]->fecha_entrega))->format("d-m-Y") . PHP_EOL." ".$f_entrega[0]->hora_entrega;
                    })
                    ->addColumn('fch_hora_ejec', function($row){                    
                        if($row->fch_hora_ejec){
                            return (new \DateTime($row->fch_hora_ejec))->format("d-m-Y G:i") . PHP_EOL;  
                        }                       
                    })
                    ->addColumn('ruta_url', function($row){ 
                        if($row->ruta_url!="" || $row->ruta_url2!="" || $row->ruta_url3!="" || $row->ruta_url4!="" || $row->ruta_url5!=""){
                            return "<button onclick='open_modal_ver_archivos(".$row->id_orden.")' class='btn btn-danger btn-sm' data-toggle='modal' 
                                            data-target='.default-example-modal-right' title='Ver archivos'><i class='fal fa-file-image'></i>
                                        </button>";
                        }   
                    })
                    ->addColumn('fch_ent', function($row){                    
                        if($row->fch_ent){                  
                            return (new \DateTime($row->fch_ent))->format("d-m-Y") . PHP_EOL." ".$row->hora_ent;   
                        }                     
                    })
                    ->addColumn('comentario_operador', function($row){                    
                        return $row->coment_trabajo;                     
                    })
                    ->addColumn('id_user', function($row){                    
                        if($row->id_user){
                            $name_surname=DB::table('users')->where('id',$row->id_user)->get();                  
                            return $name_surname[0]->name." ".$name_surname[0]->surname;   
                        }                     
                    })          
                    ->rawColumns(['action','fch_entrega','fch_ent','estado_trab','ruta_url','fch_hora_ejec','comentario_operador','id_user'])              
                    ->make(true);
        }
        
        
    }

    public function getTrabajosTerminados(){
        $data = DB::table('vw_lista_trabajo')->where('id_operador',Auth::user()->id)->where('id_estado_trab',2)->orderByDesc('id_orden')->get();
    
            return Datatables::of($data)
                    ->addIndexColumn()                    
                    ->addColumn('estado_trab', function($row){
                        if($row->id_estado_trab==0){
                            return "<span class='badge badge-danger badge-pill'>".$row->estado_trab."</span>";
                        }elseif($row->id_estado_trab==1){
                            return "<span class='badge badge-info badge-pill'>".$row->estado_trab."</span>";
                        }else{
                            return "<span class='badge badge-success badge-pill'>".$row->estado_trab."</span>";
                        }                
                    })
                    ->addColumn('fch_entrega', function($row){                    
                        $f_entrega=DB::table('vw_orden')->where('id',$row->id_orden)->get();               
                        return (new \DateTime($f_entrega[0]->fecha_entrega))->format("d-m-Y") . PHP_EOL." ".$f_entrega[0]->hora_entrega;
                    })
                    ->addColumn('fch_ent', function($row){                    
                        if($row->fch_ent){                  
                            return (new \DateTime($row->fch_ent))->format("d-m-Y") . PHP_EOL." ".$row->hora_ent;   
                        }                     
                    })
                    ->addColumn('fch_hora_ejec', function($row){                    
                        if($row->fch_hora_ejec){
                            return (new \DateTime($row->fch_hora_ejec))->format("d-m-Y G:i") . PHP_EOL;  
                        }                       
                    })
                    ->addColumn('codigo_orden', function($row){ 
                        $des_gen=DB::table('vw_orden')->where('id',$row->id_orden)->get();
                        return $row->codigo_orden." - (".$des_gen[0]->cliente.") - ".$this->limitar_cadena($des_gen[0]->des_gen,60,'...');
                    })
                    ->addColumn('fch_sal', function($row){                    
                        if($row->fch_sal){                  
                            return (new \DateTime($row->fch_sal))->format("d-m-Y") . PHP_EOL." ".$row->hora_sal;   
                        }                     
                    })
                    ->addColumn('ruta_url', function($row){                    
                        if($row->ruta_url!=""){
                            return "<button onclick='VerPLanoPdf(\"".$row->ruta_url."\")' class='btn btn-danger btn-sm' data-toggle='modal' 
                                        data-target='.default-example-modal-right' title='Ver Plano'><i class='fal fa-file-image'></i>
                                    </button>";
                        }            
                    })
                    ->addColumn('id_user', function($row){                    
                        if($row->id_user){
                            $name_surname=DB::table('users')->where('id',$row->id_user)->get();                  
                            return $name_surname[0]->name." ".$name_surname[0]->surname;   
                        }                     
                    })              
                    ->rawColumns(['fch_entrega','fch_ent','estado_trab','ruta_url','fch_hora_ejec','fch_sal','id_user'])              
                    ->make(true);
    }

    public function enviarSiguienteServicio(Request $request,$id_list_trab,$id_orden_det,$id_orden,$numeracion){                
        // actualizar a este operario segun sus servicios de trabajo
        $tot_serv = DB::table('vw_orden_detalle')->where('id_operario',Auth::user()->id)->where('id_estado','<>',2)->count('id');
        if($tot_serv>1){
            //echo 'sigues ocupado';
        }else{
            DB::table('users')->where('id',Auth::user()->id)->update([
                'ope_est' => 0,                  
            ]);
        }

        $hora=DB::table('orden_detalle')->where('id', $id_orden_det)->get();

        $horaInicio = new \DateTime($hora[0]->fch_hor_ejecucion);
        $horaTermino = new \DateTime(date('Y-m-d H:i'));
        
        $interval = $horaInicio->diff($horaTermino);
        DB::table('orden_detalle')->where('id',$id_orden_det)->update([ //orden detalle actual
            'estado'        => 2,
            'fch_sal'       => date('Y-m-d'),
            'hora_sal'      => date('H:i'),
            'tiempo'        => $interval->format('%Hh:%imin')
        ]);
           
        DB::table('lista_trabajo')->where('id_orden',$id_orden)->where('id_orden_det',$id_orden_det)->update([ //actualizar lista de trabajo
            'estado'        => 2,
            'fch_sal'       => date('Y-m-d'),
            'hora_sal'      => date('H:i'),
            'tiempo'        => $interval->format('%Hh:%imin')
        ]);

        $cant_detalle = DB::table('vw_orden_detalle')->where('id_orden', $id_orden)->count('id');
        $sum_id_estado_detalle = DB::table('vw_orden_detalle')->where('id_orden', $id_orden)->sum('id_estado');

        $ttotal=$cant_detalle*2;

        if($ttotal==$sum_id_estado_detalle){
            $id = DB::table('orden')->where('id',$id_orden)->update([
                'estado'    => 2,                   
            ]);
        }

        $roww=DB::table('lista_trabajo')->where('id', $id_list_trab)->get(); /// validar si esta en pausa
        if($roww[0]->ini_pausa==0){
            $requestObj = new Request(array('id_lista_trabajo' => $id_list_trab));
            $this->pausar($requestObj);
        }            
    }

    public function UltimoServicio(Request $request, $id_orden_det, $id_orden){
        // actualizar a este operario segun sus servicios de trabajo
        $tot_serv = DB::table('vw_orden_detalle')->where('id_operario',Auth::user()->id)->where('id_estado','<>',2)->count('id');
      
        if($tot_serv>1){
            echo 'sigues ocupado';
        }else{
            DB::table('users')->where('id',Auth::user()->id)->update([
                'ope_est' => 0,                  
            ]);
        }

        $hora=DB::table('orden_detalle')->where('id', $id_orden_det)->get();

        $horaInicio = new \DateTime($hora[0]->fch_hor_ejecucion);
        $horaTermino = new \DateTime(date('Y-m-d H:i'));        
        $interval = $horaInicio->diff($horaTermino);

        DB::table('orden_detalle')->where('id',$id_orden_det)->update([
            'estado'        => 2,
            'fch_sal'       => date('Y-m-d'), 
            'hora_sal'      => date('H:i'),
            'tiempo'        => $interval->format('%Hh:%imin')
        ]);

        DB::table('lista_trabajo')->where('id_orden',$id_orden)->where('id_orden_det',$id_orden_det)->update([ //actualizar lista de trabajo
            'estado'        => 2,
            'fch_sal'       => date('Y-m-d'),
            'hora_sal'      => date('H:i'),
            'tiempo'        => $interval->format('%Hh:%imin')
        ]);

        $id = DB::table('orden')->where('id',$id_orden)->update([
            'estado'    => 2,                   
        ]);
    }
    
    public function create()
    {
        //
    }

    public function add_coment_trabajo(Request $request){
        $ad_coment = DB::table('lista_trabajo')
            ->where('id', $request['id'])
            ->update([
                'comentario' => strtoupper($request['comentario']),               
            ]);

        if($ad_coment){
            return response()->json(['msg'=>'Comentario agregado'], 200);
        }else{
            return response()->json(['msg'=>'Error de base de datos...'], 500);
        }
    }


    public function pausar(Request $request){
        $roww=DB::table('lista_trabajo')->where('id', $request['id_lista_trabajo'])->get();
        $t_ini = new \DateTime($roww[0]->t_ini);
        $t_actual= new \DateTime(date('Y-m-d H:i'));
        $t_efectivo = $t_ini->diff($t_actual);
        $time = $t_efectivo->format('%H:%I:00');
        // $time = $t_efectivo->format('%Hh:%imin');
        // $this->sumar($time,'01:00:00')

        if($roww[0]->t_efectivo==null || $roww[0]->t_efectivo==""){
            $t_2='00:00:00';
        }else{
            $t_2=$roww[0]->t_efectivo;
        }
        
        DB::table('lista_trabajo')->where('id',$request['id_lista_trabajo'])->update([
            't_efectivo'  =>  $this->sumar($time,$t_2),
            'ini_pausa'   => 1,  
        ]);
    }

    public function reanudar(Request $request){
        DB::table('lista_trabajo')->where('id',$request['id_lista_trabajo'])->update([
            't_ini'  =>  date('Y-m-d H:i'),
            'ini_pausa'   => 0,  
        ]);
    }

    
    public function store(Request $request){
        //
    }

   
    public function show($id){
        //
    }

   
    public function edit(Request $request, $id){// Aceptar/Inicio de trabajo - Ejecutar 
        
        $data_detalle = DB::table('vw_orden_detalle')->where('id',$id)->get();

        $data_user = DB::table('users')->where('id',$data_detalle[0]->id_operario)->get();
        
        if($data_user[0]->ope_est==0){// actualiza el estado del operario  0->libre   1->ocupado
            DB::table('users')->where('id', $data_detalle[0]->id_operario)->update([
                'ope_est' => 1,                   
            ]);
        }

        DB::table('orden_detalle')->where('id',$id)->update([
            'estado'            => 1,
            'fch_hor_ejecucion' => date('Y-m-d H:i')
        ]);
        
        DB::table('orden')->where('id',$data_detalle[0]->id_orden)->update([
            'estado'    => 1,                   
        ]);

        /* para lista de trabajo */
        DB::table('lista_trabajo')->where('id_orden_det',$id)->update([
            'estado'        => 1,
            'fch_hora_ejec' => date('Y-m-d H:i'),
            't_ini'         => date('Y-m-d H:i'),
            'ini_pausa'     => 0,
            'llave'         => 1,  // es el encargado // se podria quitar este campo.
        ]);
    }

    
    public function update(Request $request, $id){
        //
    }

    
    public function destroy($id){
        //
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

    function sumar($hora1, $hora2){
        list($h, $m, $s) = explode(':', $hora2); //Separo los elementos de la segunda hora
        $a = new \DateTime($hora1); //Creo un DateTime
        $b = new \DateInterval(sprintf('PT%sH%sM%sS', $h, $m, $s)); //Creo un DateInterval
        $a->add($b); //SUMO las horas
        return $a->format('H:i:s'); //Retorno la Suma
    }
    
}



// if($total==$row->numeracion){
    // if($row->id_estado_trab==0){
    //     return "<button onclick='InicioTrabajo(".$row->id_orden_det.")' class='btn btn-success btn-sm' 
    //             title='Aceptar trabajo'><i class='fas fa-check'></i>
    //         </button>";                                 
    // }
    // else{
    //     return "<button onclick='UltimoServicio(".$row->id_orden_det.",".$row->id_orden.",".$row->llave.")' class='btn btn-primary btn-sm'
    //                 title='Finalizar Ultimo Servicio'><i class='fas fa-arrow-alt-circle-right'></i>
    //             </button>|
    //             <button onclick='open_modal(".$row->id_orden_det.")' class='btn btn-warning btn-sm' 
    //                     title='Agregar colaborador'><i class='fas fa-user-plus'></i>
    //             </button>|
    //             <button onclick='add_coment(".$row->id.")' class='btn btn-info btn-sm' 
    //                     title='Comentario'><i class='fa fa-comment' aria-hidden='true'></i>
    //             </button>";
    // }
    
// }else{
    // if($row->id_estado_trab==0){ //aceptar trabajo
    //     return "<button onclick='InicioTrabajo(".$row->id_orden_det.")' class='btn btn-success btn-sm' 
    //                 title='Inicio de trabajo'><i class='fas fa-check'></i>
    //             </button>";                           
    // }else{ // trabajo aceptado - botones sgte y colaborador habilidatos
    //     // if($row->grupal==1 && $row->llave==1){
    //         return "<button onclick='enviarSgteServicio(".$row->id.",".$row->id_orden_det.",".$row->id_orden.",".$row->numeracion.",\"".$servicio[0]->descripcion."\",".$row->llave.",".$row->grupal.")' class='btn btn-primary btn-sm' 
    //                     title='Terminar este trabajo'><i class='fas fa-thumbs-up'></i>
    //                 </button>|
    //                 <button onclick='open_modal(".$row->id_orden_det.")' class='btn btn-warning btn-sm' 
    //                         title='Agregar colaborador'><i class='fas fa-user-plus'></i>
    //                 </button>|
    //                 <button onclick='add_coment(".$row->id.")' class='btn btn-info btn-sm' 
    //                         title='Comentario'><i class='fa fa-comment' aria-hidden='true'></i>
    //                 </button>";
        // }else{                                    
        //     return "<button onclick='enviarSgteServicio(".$row->id.",".$row->id_orden_det.",".$row->id_orden.",".$row->numeracion.",\"".$servicio[0]->descripcion."\",".$row->llave.",".$row->grupal.")' class='btn btn-primary btn-sm' 
        //                 title='Terminar este trabajo'><i class='fas fa-thumbs-up'></i>
        //             </button>|
        //             <button onclick='open_modal(".$row->id_orden_det.")' class='btn btn-warning btn-sm' 
        //                     title='Agregar colaborador'><i class='fas fa-user-plus'></i>
        //             </button>|
        //             <button onclick='add_coment(".$row->id.")' class='btn btn-info btn-sm' 
        //                     title='Comentario'><i class='fa fa-comment' aria-hidden='true'></i>
        //             </button>";
        // }
        
    // }
// }