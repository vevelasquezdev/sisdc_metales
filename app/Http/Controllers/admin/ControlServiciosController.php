<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class ControlServiciosController extends Controller
{
    
    public function index(){
        $operarios = DB::table('users')->where('rol','=','OPERARIO')->where('active',1)->where('id','!=',Auth::user()->id)->get();
        $horas = DB::table('horas')->orderBy('t_min')->get();

        return view('admin.centro-control',compact('operarios','horas'));
        
    }

    public function getCtrlServicios(Request $request){        
        $data = DB::table('vw_orden_detalle')->orderByRaw("id_orden desc, numeracion ASC")->limit($request['limite'])->get();

        if($request['from_date'] &&$request['to_date']){
            $data = $data->whereBetween('fch', [$request->from_date, $request->to_date]);
        }

        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    
                    $buttons =  "<button onclick='enviarAlServicio(".$row->id.",".$row->id_orden.",\"".$row->descripcion."\")' class='btn btn-primary btn-sm' data-toggle='modal' 
                                    data-target='.default-example-modal-right' title='Enviar a Trabajar'><i class='fas fa-arrow-alt-circle-right'></i>
                                </button>"; 
                   
                    if($row->id_estado==0 && ($row->id_operario=="" || $row->id_operario==null)){
                        return $buttons;
                    }               
                })
                ->addColumn('id_orden', function($row){ 
                    $orden = DB::table('vw_orden')->where('id', $row->id_orden)->first();
                    return $row->codigo_orden." - (".$orden->cliente.") - ".$this->limitar_cadena($orden->des_gen,60,'...');
                    
                })                
                ->addColumn('estado', function($row){
                    return $row->id_estado;                                   
                })
                ->addColumn('fch_ent', function($row){ 
                    if($row->fch_ent){ 
                        return (new \DateTime($row->fch_ent))->format("d-m-Y") . PHP_EOL." ".$row->hora_ent;
                    }
                })
                ->addColumn('fch_sal', function($row){ 
                    if($row->fch_sal){
                        return (new \DateTime($row->fch_sal))->format("d-m-Y") . PHP_EOL." ".$row->hora_sal;
                    }
                })
                ->addColumn('fch_hor_ejecucion', function($row){ 
                    if($row->fch_hor_ejecucion){
                        return (new \DateTime($row->fch_hor_ejecucion))->format("d-m-Y G:i") . PHP_EOL;
                    }                    
                })
                ->addColumn('operario', function($row){ 
                    if($row->id_operario=="" ||$row->id_operario==null){

                    }else{
                        $colaboradores=DB::select("SELECT STRING_AGG(operario, ', ' order by a.id) as colaboradores FROM vw_lista_trabajo a where id_orden_det=".$row->id);
                        return $colaboradores[0]->colaboradores;
                    }
                })
                ->addColumn('descripcion', function($row){
                    $material=DB::table('vw_consumo_mat')->where('id_orden_det',$row->id)->get();
                    if(isset($material[0]->id)){
                        return $material[0]->new_desc;
                    }else{
                        return $row->descripcion;
                    }
                })
                ->addColumn('fch', function($row){ 
                    if($row->fch){
                        return (new \DateTime($row->fch))->format("d-m-Y") . PHP_EOL;
                    }                    
                })        
                ->rawColumns(['action','fch_ent','fch_sal','estado','fch_hor_ejecucion','fch','id_orden'])              
                ->make(true);
    }

    public function enviarAlServicio(Request $request,$id_orden_det,$id_orden){
       
        
        DB::table('orden_detalle')->where('id',$id_orden_det)->update([
            'fch_ent'       => date('Y-m-d'), 
            'hora_ent'      => date('H:i'),
            'id_operario'   => $request['id_operario'],
        ]);

        $detalle = DB::table('orden_detalle')->where('id',$id_orden_det)->get();

        $id_lista_trab = DB::table('lista_trabajo')->insert([                      
            'id_orden_det'  => $id_orden_det,
            'id_operador'   => $request['id_operario'],
            'fch_ent'       => date('Y-m-d'), 
            'hora_ent'      => date('H:i'), 
            'id_servicio'   => $detalle[0]->id_servicio,
            'id_orden'      => $detalle[0]->id_orden,
            'id_tiempo_ref' => $request['id_tiempo_ref'],
            'tiempo_ref'    => $request['tiempo_ref'],
            'llave'         => 1
        ]);
        
        // if($id_lista_trab){
        //     date_default_timezone_set('America/Lima');

        //     $ultima_fecha = DB::select('select fecha from tot_tiempo order by fecha desc limit 1');
        //     $count_id_dia = DB::table('tot_tiempo')->where('fecha',date('Y-m-d'))->count('id');
            
        //     $date1 = new DateTime($ultima_fecha[0]->fecha ?? date('Y-m-d'));
        //     $date2 = new DateTime(date('Y-m-d'));
        //     $diff = $date2->diff($date1);
        //     if($diff->days>0){
        //         echo 'cuenta';
        //     }else{
        //         $sum_t_ref = DB::table('tot_tiempo')->where('fecha',date('Y-m-d'))->sum('tiempo');
        //         // echo $sum_t_ref;
        //         if(isset($sum_t_ref)){
        //             //echo '11';
        //             if($sum_t_ref==0 || $sum_t_ref=='0' || $sum_t_ref==null){
        //                 echo 0; 
        //                 DB::table('linea_tiempo')->insert([
        //                         'fecha'  => date('Y-m-d'),
        //                         't_1'    => '1',
        //                     ]);
        //             }else{
        //                 //echo 1; //update
        //                 $id_1 = DB::table('horas')->where('id',$request['id_tiempo_ref'])->get();
        //                 $sum1=$sum_t_ref+$id_1[0]->t_min;
        //                 $id_2 = DB::table('horas')->where('t_min',$sum1)->get();
        //                 echo $id_2[0]->id;
        //                 for($x=1; $x<=$id_2[0]->id; $x++){
        //                     DB::table('linea_tiempo')->where('fecha',date('Y-m-d'))->update([
        //                         't_'.$x    => '1',
        //                     ]);
        //                 }
                        
        //             }
        //         }else{
        //             echo '00';
        //             DB::table('linea_tiempo')->insert([
        //                 'fecha'  => date('Y-m-d'),
        //                 't_1'    => '1',
        //             ]);
        //         }
                
        //     }
        // }

        
        // will output 2 days
        //echo $diff->days . ' days ';
        // for($x=1; $x<=2; $x++){
        //     if($x==1){
        //         DB::table('linea_tiempo')->insert([
        //             'fecha'  => date('Y-m-d'),
        //             't_1'    => 1,
        //             't_2'    => 1,
        //             't_3'    => 1,
        //             't_4'    => 1,
        //             't_5'    => 1,
        //             't_6'    => 1,
        //             't_7'    => 1,
        //             't_8'    => 1,
        //             't_9'    => 1,
        //             't_10'    => 1,
        //         ]);
        //     }
            
        // }


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

    public function edit($id)
    {
       
    }

  
    public function update(Request $request, $id)
    {
        //
    }

   
    public function destroy($id)
    {
        //
    }

    public function getOperarios(){
        // $operarios = DB::table('users')->where('rol','=','OPERARIO')->where('active',1)->where('id','!=',Auth::user()->id)->get();
        // $horas = DB::table('horas')->get();
        // return response()->json(['operarios'=>$operarios,'horas'=>$horas],200);
    }

    public function getOperariosColaborador(){
        $operarios = DB::table('users')->where('rol','=','OPERARIO')->where('active',1)->where('ope_est',0)->where('id','!=',Auth::user()->id)->get();
        return response()->json($operarios);
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
