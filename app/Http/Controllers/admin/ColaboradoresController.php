<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class ColaboradoresController extends Controller
{
    
    public function addColaborador(Request $request, $id_orden_det){
        // echo $id_orden_det.'-'.$request['id_operario'];
        $detalle = DB::table('orden_detalle')->where('id',$id_orden_det)->get();
       
        if($detalle[0]->id_operario==Auth::user()->id){
            DB::table('lista_trabajo')->insert([                      
                'id_orden_det'  => $id_orden_det,
                'id_operador'   => $request['id_operario'],
                'fch_ent'       => $detalle[0]->fch_ent, 
                'hora_ent'      => $detalle[0]->hora_ent, 
                'id_servicio'   => $detalle[0]->id_servicio,
                'id_orden'      => $detalle[0]->id_orden,
                'fch_hora_ejec' => $detalle[0]->fch_hor_ejecucion,
                'estado'        => $detalle[0]->estado,
                'llave'         => 0           
            ]);
    
            DB::table('lista_trabajo')->where('id_orden_det',$id_orden_det)->update([
                'grupal' => 1
            ]);
    
            $data_user = DB::table('users')->where('id',$request['id_operario'])->get();
            
            if($data_user[0]->ope_est==0){// actualiza el estado del operario  0->libre   1->ocupado
                DB::table('users')->where('id', $request['id_operario'])->update([
                    'ope_est' => 1,                   
                ]);
            }
            return response()->json(['msg'=>'1'], 200); //autorizado
        }else{
            return response()->json(['msg'=>'0'], 200);  // no autorizado
        }

        
    }

    public function tabla_colaboradores($id_servicio){
        $data = DB::table('vw_lista_trabajo')->where('id_orden_det',$id_servicio)->orderBy('id')->get();

        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){  
                    $servicio = DB::table('vw_orden_detalle')->where('id_orden',$row->id_orden)->where('numeracion',1)->get('descripcion');


                    return "<a href='javascript:void(0);' onclick='deleteColaborador(".$row->id.",".$row->id_orden_det.")' class='btn btn-danger btn-sm btn-icon waves-effect waves-themed'>
                                <i class='fal fa-trash'></i>
                            </a>";
                    
                })
                ->addColumn('tipo', function($row){  
                    if($row->llave==1){
                        return  "ENCARGADO";
                    }else{
                        return  "COLABORADOR";
                    }               
                })
                ->addColumn('nombres_apellidos', function($row){  
                    return $row->operario;
                })               
                ->rawColumns(['action','tipo','nombres_apellidos'])              
                ->make(true);
    }

    public function deleteColaborador(Request $request, $id_lista_trabajo){
        // actualizar a este operario segun sus servicios de trabajo
        $tot_serv = DB::table('vw_orden_detalle')->where('id_operario',Auth::user()->id)->where('id_estado','<>',2)->count('id');
        if($tot_serv>1){
            //echo 'sigues ocupado';
        }else{
            DB::table('users')->where('id',Auth::user()->id)->update([
                'ope_est' => 0,                  
            ]);
        }

        $list_trab = DB::table('lista_trabajo')->where('id_orden_det',$request['id_orden_det'])->orderBy('id')->first();
        $user_autorizado=$list_trab->id_operador;
        //echo "autorizado: ".$user_autorizado;

        if(Auth::user()->id==$user_autorizado){
            $data = DB::table('lista_trabajo')->where('id',$id_lista_trabajo)->delete();
            return response()->json(['msg'=>'1'], 200); //autorizado
        }else{
            return response()->json(['msg'=>'0'], 200);  // no autorizado
        }

        // if($list_trab[0]->llave==1){
           
        //     echo "si puedes eliminar";
        // }
    }

    public function check_encargado_trabajo($id_lista_trabajo){
        $list_trab = DB::table('lista_trabajo')->where('id',$id_lista_trabajo)->first();
        return response()->json(['llave'=>$list_trab->llave], 200);
    }

    public function index()
    {
        //
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
        //
    }

   
    public function update(Request $request, $id)
    {
        //
    }

   
    public function destroy($id)
    {
        
    }
}
