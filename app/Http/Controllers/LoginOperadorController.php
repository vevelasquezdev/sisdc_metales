<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginOperadorController extends Controller
{
    public function home_ini(){
        $operadores = DB::select("select id,\"name\",surname,(select count(id) from vw_lista_trabajo where id_operador =a.id and id_estado_trab<>2) as tot from users a where rol='OPERARIO' and active=1 order by id");
        return view('dashboard',compact('operadores'));
    }


    public function login_operador($id_user){
        Auth::loginUsingId($id_user);
        return redirect('/lista-trabajo');
    }

    public function tot_por_local_procedencia(Request $request){
        if($request['mes']=="00"){            
            $Consulta = DB::select("select procedencia,anio, sum(total) as total from vw_tot_ordenes_por_local  
                                    where anio=".$request['anio']." group by procedencia, anio");
        }else{
            $Consulta = DB::table('vw_tot_ordenes_por_local')->where('anio',$request['anio'])->where('mes',$request['mes'])->get();
                                              
        }

        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();          
            $Lista->name = trim($Datos->procedencia);
            $Lista->total = trim($Datos->total);           
            array_push($todo, $Lista);
        }
                                
        return response()->json($todo);
        
    }
    public function tot_por_local_procedencia_monto(Request $request){
        if($request['mes']=="00"){            
            $Consulta = DB::select("select procedencia,anio, sum(total) as total from vw_tot_ordenes_por_local_monto  
                                    where anio=".$request['anio']." group by procedencia, anio");
        }else{
            $Consulta = DB::table('vw_tot_ordenes_por_local_monto')->where('anio',$request['anio'])->where('mes',$request['mes'])->get();
                                              
        }

        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();          
            $Lista->name = trim($Datos->procedencia);
            $Lista->total = trim($Datos->total);           
            array_push($todo, $Lista);
        }
                                
        return response()->json($todo);
       
    }

    public function piechart_tot_orden_usuario_monto(Request $request){
        if($request['mes']=="00"){
            $Consulta = DB::select("select id,name,anio, sum(total_monto) as total_monto from vw_tot_monto_orden_user  
                                    where anio=".$request['anio']." group by id, name, anio"); //and id<>29 and id<>39 and id<>40 and id<>42 and id<>34
        }else{
            $Consulta = DB::table('vw_tot_monto_orden_user')->where('anio',$request['anio'])->where('mes',$request['mes'])->get();
                                                //   ->where('id','<>',29)->where('id','<>',39)
                                                //   ->where('id','<>',40)->where('id','<>',42)->where('id','<>',34)->get();
        }
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();          
            $Lista->name = trim($Datos->name);
            $Lista->total = trim($Datos->total_monto);           
            array_push($todo, $Lista);
        }
                                
        return response()->json($todo);
    }

    public function piechart_tot_orden_usuario(Request $request){
        if($request['mes']=="00"){
            $Consulta = DB::select("select id,name,anio,sum (total_ordenes_por_usuario) as total_ordenes_por_usuario from vw_tot_orden_user
                                    where anio=".$request['anio']." group by id, name, anio"); //and id<>29 and id<>39 and id<>40 and id<>42 and id<>34
        }else{
            $Consulta = DB::table('vw_tot_orden_user')->where('anio',$request['anio'])->where('mes',$request['mes'])->get();
                                                //   ->where('id','<>',29)->where('id','<>',39)
                                                //   ->where('id','<>',40)->where('id','<>',42)->where('id','<>',34)->get();
        }
        
        
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();          
            $Lista->name = trim($Datos->name);
            $Lista->total = trim($Datos->total_ordenes_por_usuario);           
            array_push($todo, $Lista);
        }
                                
        return response()->json($todo);
    }

    public function count_trab_operadores(Request $request){
     
        if($request['mes']=="00"){
            $Consulta=DB::select("select 
                                        u.id,
                                        (left((u.\"name\"|| ' ' ||u.surname),18)||'.') as operario,
                                        coalesce (b.total_trabajos,0) as total_trabajos
                                    from users u 
                                    left join (
                                        select a.id_operador ,count(a.id) as total_trabajos from lista_trabajo a
                                        where a.fch_hora_ejec like ('".$request['anio']."%')
                                        group by a.id_operador 
                                    ) as b on u.id = b.id_operador
                                    where u.rol='OPERARIO'
                                    order by total_trabajos desc");      
        }else{
            $Consulta=DB::select("select
                                        u.id,
                                        (left((u.\"name\"|| ' ' ||u.surname),18)||'.') as operario,
                                        coalesce (b.total_trabajos,0) as total_trabajos
                                    from users u 
                                    left join (
                                        select a.id_operador ,count(a.id) as total_trabajos from lista_trabajo a
                                        where a.fch_hora_ejec like ('".$request['anio']."-".$request['mes']."%')
                                        group by a.id_operador 
                                    ) as b on u.id = b.id_operador
                                    where u.rol='OPERARIO' 
                                    order by total_trabajos desc");   
        }
                                     
    
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();          
            $Lista->operario = trim($Datos->operario);
            $Lista->total_operador = trim($Datos->total_trabajos);           
            array_push($todo, $Lista);
        }
        
        return response()->json($todo);
    }


    public function get_totales_por_mes(Request $request){
        $meses=['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre'];

        $Consulta = DB::table('vw_grafica_tot_mes')->where('anio',$request['anio'])->get();
        
        $i=0;
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();          
            $Lista->mes = $meses[$i];
            $Lista->total_mes = $Datos->total;           
            array_push($todo, $Lista);
            $i++;
        }

        return response()->json($todo);
    }

    public function report_serv_monto(Request $request){
        $anio = $request['anio'];
        $mes = str_pad($request['mes'], 2, '0', STR_PAD_LEFT);
        if($request['mes']=="00"){
            $Consulta = DB::select("
                                    SELECT
                                        s.id,
                                        s.descripcion,
                                        COALESCE(tb.tot, 0) AS total
                                    FROM servicios s
                                    LEFT JOIN (
                                        SELECT 
                                            a.id_servicio, 
                                            a.descripcion, 
                                            SUM(a.pre_tot) AS tot
                                        FROM vw_orden_detalle a
                                        WHERE a.fch LIKE ?
                                        GROUP BY a.id_servicio, a.descripcion
                                    ) AS tb ON s.id = tb.id_servicio
                                    WHERE s.active = 1
                                    ORDER BY s.id
                                ", [$request['anio'] . '%']);
        }else{
            $Consulta = DB::select("
                                    SELECT
                                        s.id,
                                        s.descripcion,
                                        COALESCE(tb.tot, 0) AS total
                                    FROM servicios s
                                    LEFT JOIN (
                                        SELECT
                                            a.id_servicio,
                                            a.descripcion,
                                            SUM(a.pre_tot) AS tot
                                        FROM vw_orden_detalle a
                                        WHERE a.fch LIKE :fecha
                                        GROUP BY a.id_servicio, a.descripcion
                                    ) AS tb ON s.id = tb.id_servicio
                                    WHERE s.active = 1
                                    ORDER BY 1
                                ", ['fecha' => "$anio-$mes%"]);          
        }
          
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();          
            $Lista->descripcion = trim($Datos->descripcion);
            $Lista->total = trim($Datos->total);           
            array_push($todo, $Lista);
        }
        
        return response()->json($todo); 
    }

    public function report_serv_cantidad(Request $request){
        if($request['mes']=="00"){
            $Consulta=DB::select("select
                                        s.id,
                                        s.descripcion,
                                        coalesce (tb.tot,0) as tot
                                    from servicios s 
                                    left join (
                                        select a.id_servicio, count(a.id_servicio) as tot,a.descripcion  from vw_orden_detalle a
                                        where a.fch like ('".$request['anio']."%')
                                        group by a.id_servicio, a.descripcion
                                    ) as tb on s.id = tb.id_servicio
                                    where s.active = 1
                                    order by 1");
        }else{
            $Consulta=DB::select("select
                                        s.id,
                                        s.descripcion,
                                        coalesce (tb.tot,0) as tot
                                    from servicios s 
                                    left join (
                                        select a.id_servicio, count(a.id_servicio) as tot,a.descripcion  from vw_orden_detalle a
                                        where a.fch like ('".$request['anio']."-".$request['mes']."%')
                                        group by a.id_servicio, a.descripcion
                                    ) as tb on s.id = tb.id_servicio
                                    where s.active = 1
                                    order by 1");
        }
          
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();          
            $Lista->descripcion = trim($Datos->descripcion);
            $Lista->total = trim($Datos->tot);           
            array_push($todo, $Lista);
        }
        
        return response()->json($todo); 
    }
}

  // $Consulta=DB::select("select
            //                         s.id,
            //                         s.descripcion,
            //                         coalesce (tb.tot,0) as total
            //                     from servicios s 
            //                     left join (
            //                         select a.id_servicio,a.descripcion ,sum(a.pre_tot) as tot  from vw_orden_detalle a
            //                         where a.fch_hor_ejecucion like ('".$request['anio']."%')
            //                         group by a.id_servicio, a.descripcion
            //                     ) as tb on s.id = tb.id_servicio
            //                     where s.active = 1
            //                     order by 1");

